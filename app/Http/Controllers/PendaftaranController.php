<?php

namespace App\Http\Controllers;

use App\Models\Pendaftaran;
use App\Models\PendaftaranDetail;
use App\Models\PaymentSetting;
use App\Models\Siswa;
use App\Support\AuthorizesParentSiswa;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use RuntimeException;

class PendaftaranController extends Controller
{
    use AuthorizesParentSiswa;

    /**
     * Show available registration periods for the parent.
     */
    public function index(Siswa $siswa): View
    {
        $this->authorizeParentSiswa($siswa);

        $pendaftarans = Pendaftaran::orderBy('tanggal_mulai', 'desc')
            ->get()
            ->filter(function ($pendaftaran) {
                return $pendaftaran->is_bisa_dipilih;
            });

        $isAccepted = $siswa->pendaftaranDetails()
            ->where('status', PendaftaranDetail::STATUS_DITERIMA)
            ->exists();

        $hasActiveRegistration = $siswa->pendaftaranDetails()
            ->whereNotIn('status', [PendaftaranDetail::STATUS_DITOLAK])
            ->exists();

        return view('parent.pendaftaran.index', compact('pendaftarans', 'siswa', 'isAccepted', 'hasActiveRegistration'));
    }

    /**
     * Show detail of a specific registration period.
     */
    public function show(Siswa $siswa, Pendaftaran $pendaftaran): View
    {
        $this->authorizeParentSiswa($siswa);

        $existingDetail = PendaftaranDetail::where('siswa_id', $siswa->id)
            ->where('pendaftaran_id', $pendaftaran->id)
            ->first();

        $hasActiveRegistration = $siswa->pendaftaranDetails()
            ->whereNotIn('status', [PendaftaranDetail::STATUS_DITOLAK])
            ->exists();

        return view('parent.pendaftaran.show', compact(
            'pendaftaran',
            'siswa',
            'existingDetail',
            'hasActiveRegistration'
        ));
    }

    /**
     * Register the parent's child for a specific registration period.
     */
    public function daftar(Request $request, Siswa $siswa, Pendaftaran $pendaftaran): RedirectResponse
    {
        $this->authorizeParentSiswa($siswa);

        $request->validate([
            'data_declaration' => ['accepted'],
        ], [
            'data_declaration.accepted' => 'Anda harus menyatakan bahwa data dan dokumen yang diunggah adalah benar.',
        ]);

        try {
            DB::transaction(function () use ($pendaftaran, $siswa): void {
                /** @var Pendaftaran $lockedPendaftaran */
                $lockedPendaftaran = Pendaftaran::query()
                    ->whereKey($pendaftaran->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                if (! $lockedPendaftaran->isOpen()) {
                    throw new RuntimeException('Pendaftaran sudah ditutup.');
                }

                if ($lockedPendaftaran->is_expired) {
                    throw new RuntimeException('Mohon maaf, masa pendaftaran untuk gelombang ini telah ditutup karena sudah melewati batas tanggal.');
                }

                if ($lockedPendaftaran->kuota > 0 && $lockedPendaftaran->pendaftaranDetails()->count() >= $lockedPendaftaran->kuota) {
                    throw new RuntimeException('Mohon maaf, kuota untuk gelombang ini sudah penuh.');
                }

                $isAccepted = PendaftaranDetail::query()
                    ->where('siswa_id', $siswa->id)
                    ->where('status', PendaftaranDetail::STATUS_DITERIMA)
                    ->lockForUpdate()
                    ->first();

                if ($isAccepted) {
                    throw new RuntimeException('Anak Anda sudah diterima. Tidak perlu mendaftar lagi.');
                }

                $hasActiveRegistration = PendaftaranDetail::query()
                    ->where('siswa_id', $siswa->id)
                    ->whereNotIn('status', [PendaftaranDetail::STATUS_DITOLAK])
                    ->lockForUpdate()
                    ->first();

                if ($hasActiveRegistration) {
                    throw new RuntimeException('Anak Anda sudah terdaftar di gelombang lain. Perpindahan gelombang hanya dapat dilakukan oleh Admin.');
                }

                $alreadyRegistered = PendaftaranDetail::query()
                    ->where('siswa_id', $siswa->id)
                    ->where('pendaftaran_id', $lockedPendaftaran->id)
                    ->lockForUpdate()
                    ->first();

                if ($alreadyRegistered) {
                    throw new RuntimeException('Anak Anda sudah terdaftar di gelombang ini.');
                }

                $recommendationService = app(\App\Services\StudentGroupRecommendationService::class);
                $calc = $recommendationService->calculate($siswa->tanggal_lahir, $lockedPendaftaran->tahun_ajaran);

                $detail = PendaftaranDetail::create([
                    'siswa_id' => $siswa->id,
                    'pendaftaran_id' => $lockedPendaftaran->id,
                    'status' => PendaftaranDetail::STATUS_PENDING,
                    'notifikasi' => null,
                    'tanggal_acuan_usia' => $calc['tanggal_acuan'],
                    'usia_bulan_saat_acuan' => $calc['usia_bulan'],
                    'kelompok_rekomendasi' => $calc['kelompok_rekomendasi'],
                    'kelompok_final' => null,
                ]);

                $detail->update([
                    'no_pendaftaran' => $this->generateNoPendaftaran($detail),
                ]);
            });
        } catch (RuntimeException $exception) {
            return back()->with('error', $exception->getMessage());
        }

        return redirect()->route('parent.siswa.pendaftaran.status', $siswa)
            ->with('success', 'Pendaftaran berhasil! Status: menunggu verifikasi.');
    }

    /**
     * Show the registration status for the parent's child.
     */
    public function status(Siswa $siswa): View
    {
        $this->authorizeParentSiswa($siswa);

        $registrations = PendaftaranDetail::with(['siswa', 'pendaftaran', 'pembayaran'])
            ->where('siswa_id', $siswa->id)
            ->latest()
            ->get();

        return view('parent.pendaftaran.status', [
            'siswa' => $siswa,
            'registrations' => $registrations,
            'paymentSetting' => PaymentSetting::current(),
        ]);
    }

    private function generateNoPendaftaran(PendaftaranDetail $detail): string
    {
        return sprintf('SPMB-%s-%04d', now()->year, $detail->id);
    }
}
