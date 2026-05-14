<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSiswaRequest;
use App\Http\Requests\UpdateSiswaRequest;
use App\Models\ActivityLog;
use App\Models\Siswa;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SiswaController extends Controller
{
    /**
     * Show the form to register a new child (siswa).
     */
    public function create(): View
    {
        // Autofill phone from user profile
        $userPhone = Auth::user()->no_telpon;

        return view('parent.siswa.create', compact('userPhone'));
    }

    /**
     * Store a newly registered child.
     */
    public function store(StoreSiswaRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        // Upload files
        $validated['foto'] = $request->file('foto')->store('siswa/foto', 'public');
        $validated['foto_kk'] = $request->file('foto_kk')->store('siswa/kk', 'local');
        $validated['foto_akta'] = $request->file('foto_akta')->store('siswa/akta', 'local');

        // Link to authenticated user
        $validated['user_id'] = Auth::id();

        Siswa::create($validated);

        return redirect()->route('parent.dashboard')
            ->with('success', 'Data anak berhasil disimpan.');
    }

    /**
     * Show the siswa profile / detail page.
     */
    public function show(Siswa $siswa): View
    {
        $this->authorizeParent($siswa);

        $siswa->load('pendaftaranDetails.pendaftaran');

        return view('parent.siswa.show', compact('siswa'));
    }

    /**
     * Show the edit form for a siswa.
     */
    public function edit(Siswa $siswa): View
    {
        $this->authorizeParent($siswa);

        $userPhone = Auth::user()->no_telpon;

        return view('parent.siswa.edit', compact('siswa', 'userPhone'));
    }

    /**
     * Update a siswa's data.
     */
    public function update(UpdateSiswaRequest $request, Siswa $siswa): RedirectResponse
    {
        $this->authorizeParent($siswa);

        $validated = $request->validated();

        // Handle optional file uploads — replace old file when new one is provided
        foreach (['foto', 'foto_kk', 'foto_akta'] as $field) {
            if ($request->hasFile($field)) {
                // Delete old file
                if ($siswa->{$field}) {
                    $disk = $field === 'foto' ? 'public' : 'local';
                    Storage::disk($disk)->delete($siswa->{$field});
                }
                $storePath = match ($field) {
                    'foto' => 'siswa/foto',
                    'foto_kk' => 'siswa/kk',
                    'foto_akta' => 'siswa/akta',
                };
                $disk = $field === 'foto' ? 'public' : 'local';
                $validated[$field] = $request->file($field)->store($storePath, $disk);
            } else {
                unset($validated[$field]);
            }
        }

        $siswa->update($validated);

        // If there's a registration with status 'perlu_revisi', set it back to 'menunggu_verifikasi'
        $siswa->pendaftaranDetails()
            ->where('status', \App\Models\PendaftaranDetail::STATUS_PERLU_REVISI)
            ->update(['status' => \App\Models\PendaftaranDetail::STATUS_MENUNGGU_VERIFIKASI]);

        return redirect()->route('parent.pendaftaran.status')
            ->with('success', 'Data anak berhasil diperbarui. Status telah dikembalikan ke Menunggu Verifikasi.');
    }

    /**
     * Delete siswa data (only if no active registration).
     */
    public function destroy(Siswa $siswa): RedirectResponse
    {
        $this->authorizeParent($siswa);

        // Check if there are any non-rejected registrations
        $activeRegistrations = $siswa->pendaftaranDetails()
            ->whereNotIn('status', ['ditolak'])
            ->count();

        if ($activeRegistrations > 0) {
            return back()->with('error', 'Tidak dapat menghapus data anak karena masih ada pendaftaran aktif.');
        }

        // Delete associated files
        if ($siswa->foto) Storage::disk('public')->delete($siswa->foto);
        if ($siswa->foto_kk) Storage::disk('local')->delete($siswa->foto_kk);
        if ($siswa->foto_akta) Storage::disk('local')->delete($siswa->foto_akta);

        $nama = $siswa->nama;

        // Delete all rejected registrations
        $siswa->pendaftaranDetails()->delete();
        $siswa->delete();

        ActivityLog::log('deleted', null, "Orang tua menghapus data anak: {$nama}");

        return redirect()->route('parent.dashboard')
            ->with('success', 'Data anak berhasil dihapus.');
    }

    /**
     * Show printable ID card for accepted registration.
     */
    public function kartu(): View
    {
        $siswa = Auth::user()->siswa;
        
        if (!$siswa) {
            abort(404, 'Data anak belum ada.');
        }

        // Get the latest 'diterima' registration for this child
        $registration = $siswa->pendaftaranDetails()
            ->where('status', 'diterima')
            ->with('pendaftaran')
            ->latest()
            ->first();

        if (!$registration) {
            abort(403, 'Belum ada pendaftaran yang diterima untuk dicetak kartunya.');
        }

        return view('parent.siswa.kartu', compact('siswa', 'registration'));
    }

    /**
     * Ensure the authenticated parent can only access their own child data.
     */
    private function authorizeParent(Siswa $siswa): void
    {
        if (Auth::user()->isAdmin()) {
            return; // Admin can see all
        }

        if ($siswa->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }
    }
}
