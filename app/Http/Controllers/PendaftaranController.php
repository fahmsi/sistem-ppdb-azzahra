<?php

namespace App\Http\Controllers;

use App\Models\Pendaftaran;
use App\Models\PendaftaranDetail;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PendaftaranController extends Controller
{
    /**
     * Show available registration periods for the parent.
     */
    public function index(): View
    {
        $pendaftarans = Pendaftaran::open()
            ->orderBy('tanggal_mulai', 'desc')
            ->get();

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
        /** @var User $user */
        $user = Auth::user();

        // Ensure user has a siswa profile
        $siswa = $user->siswa;
        if (! $siswa) {
            return redirect()->route('parent.siswa.create')
                ->with('warning', 'Silakan lengkapi data anak terlebih dahulu sebelum mendaftar.');
        }

        // Check if the registration period is open
        if (! $pendaftaran->isOpen()) {
            return back()->with('error', 'Pendaftaran sudah ditutup.');
        }

        // Check quota
        if ($pendaftaran->isQuotaFull()) {
            return back()->with('error', 'Kuota pendaftaran sudah penuh.');
        }

        // LIMIT: Only 1 active gelombang at a time
        $hasActiveRegistration = $siswa->pendaftaranDetails()
            ->whereNotIn('status', [PendaftaranDetail::STATUS_DITOLAK])
            ->exists();

        if ($hasActiveRegistration) {
            return back()->with('error', 'Anak Anda sudah terdaftar di gelombang lain. Perpindahan gelombang hanya dapat dilakukan oleh Admin.');
        }

        // Check if child is already accepted
        $isAccepted = $siswa->pendaftaranDetails()
            ->where('status', PendaftaranDetail::STATUS_DITERIMA)
            ->exists();

        if ($isAccepted) {
            return back()->with('error', 'Anak Anda sudah diterima. Tidak perlu mendaftar lagi.');
        }

        // Check if already registered for this period
        $alreadyRegistered = PendaftaranDetail::where('siswa_id', $siswa->id)
            ->where('pendaftaran_id', $pendaftaran->id)
            ->exists();

        if ($alreadyRegistered) {
            return back()->with('error', 'Anak Anda sudah terdaftar di gelombang ini.');
        }

        // Create the registration detail
        PendaftaranDetail::create([
            'siswa_id' => $siswa->id,
            'pendaftaran_id' => $pendaftaran->id,
            'status' => PendaftaranDetail::STATUS_PENDING,
            'notifikasi' => null,
        ]);

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
            $registrations = PendaftaranDetail::with(['pendaftaran', 'pembayaran'])
                ->where('siswa_id', $siswa->id)
                ->latest()
                ->get();
        }

        return view('parent.pendaftaran.status', compact('registrations'));
    }
}
