<?php

namespace App\Http\Controllers;

use App\Models\Pendaftaran;
use App\Models\PendaftaranDetail;
use App\Models\PaymentSetting;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use RuntimeException;

class PendaftaranController extends Controller
{
    /**
     * Show available registration periods for the parent.
     */
    public function index(): View
    {
        $pendaftarans = Pendaftaran::orderBy('tanggal_mulai', 'desc')
            ->get()
            ->filter(function ($pendaftaran) {
                return $pendaftaran->is_bisa_dipilih;
            });

        /** @var User $user */
        $user = Auth::user();
        $siswa = $user->siswa;

        // Check if child is already accepted anywhere (block further registration)
        $isAccepted = false;
        $hasActiveRegistration = false;

        if ($siswa) {
            $isAccepted = $siswa->pendaftaranDetails()
                ->where('status', PendaftaranDetail::STATUS_DITERIMA)
                ->exists();

            // Check if already registered to any open gelombang (limit 1)
            $hasActiveRegistration = $siswa->pendaftaranDetails()
                ->whereNotIn('status', [PendaftaranDetail::STATUS_DITOLAK])
                ->exists();
        }

        return view('parent.pendaftaran.index', compact('pendaftarans', 'siswa', 'isAccepted', 'hasActiveRegistration'));
    }

    /**
     * Show detail of a specific registration period.
     */
    public function show(Pendaftaran $pendaftaran): View
    {
        $siswa = Auth::user()->siswa;

        // Check if already registered
        $existingDetail = null;
        if ($siswa) {
            $existingDetail = PendaftaranDetail::where('siswa_id', $siswa->id)
                ->where('pendaftaran_id', $pendaftaran->id)
                ->first();
        }

        return view('parent.pendaftaran.show', compact('pendaftaran', 'siswa', 'existingDetail'));
    }

    /**
     * Register the parent's child for a specific registration period.
     */
    public function daftar(Request $request, Pendaftaran $pendaftaran): RedirectResponse
    {
        $request->validate([
            'data_declaration' => ['accepted'],
        ], [
            'data_declaration.accepted' => 'Anda harus menyatakan bahwa data dan dokumen yang diunggah adalah benar.',
        ]);

        /** @var User $user */
        $user = Auth::user();

        // Ensure user has a siswa profile
        $siswa = $user->siswa;
        if (! $siswa) {
            return redirect()->route('parent.siswa.create')
                ->with('warning', 'Silakan lengkapi data anak terlebih dahulu sebelum mendaftar.');
        }

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

                $detail = PendaftaranDetail::create([
                    'siswa_id' => $siswa->id,
                    'pendaftaran_id' => $lockedPendaftaran->id,
                    'status' => PendaftaranDetail::STATUS_PENDING,
                    'notifikasi' => null,
                ]);

                $detail->update([
                    'no_pendaftaran' => $this->generateNoPendaftaran($detail),
                ]);
            });
        } catch (RuntimeException $exception) {
            return back()->with('error', $exception->getMessage());
        }

        return back()->with('success', 'Pendaftaran berhasil! Status: menunggu verifikasi.');
    }

    /**
     * Show the registration status for the parent's child.
     */
    public function status(): View
    {
        /** @var User $user */
        $user = Auth::user();
        $siswa = $user->siswa;

        $registrations = collect();
        if ($siswa) {
            $registrations = PendaftaranDetail::with(['siswa', 'pendaftaran', 'pembayaran'])
                ->where('siswa_id', $siswa->id)
                ->latest()
                ->get();
        }

        return view('parent.pendaftaran.status', [
            'registrations' => $registrations,
            'paymentSetting' => PaymentSetting::current(),
        ]);
    }

    private function generateNoPendaftaran(PendaftaranDetail $detail): string
    {
        return sprintf('SPMB-%s-%04d', now()->year, $detail->id);
    }
}
