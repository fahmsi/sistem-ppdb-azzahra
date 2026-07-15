<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSiswaRequest;
use App\Http\Requests\UpdateSiswaRequest;
use App\Models\ActivityLog;
use App\Models\PendaftaranDetail;
use App\Models\Siswa;
use App\Support\AuthorizesParentSiswa;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SiswaController extends Controller
{
    use AuthorizesParentSiswa;

    public function index(): View
    {
        $siswas = Auth::user()
            ->siswas()
            ->with([
                'pendaftaranDetails' => fn ($query) => $query
                    ->with(['pendaftaran', 'pembayaran'])
                    ->latest(),
            ])
            ->latest()
            ->get();

        return view('parent.siswa.index', compact('siswas'));
    }

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

        // Handle conditional KTP uploads
        if ($validated['tinggal_bersama'] === 'orang_tua') {
            $validated['foto_ktp_ayah'] = $request->file('foto_ktp_ayah')->store('siswa/ktp-ayah', 'local');
            $validated['foto_ktp_ibu'] = $request->file('foto_ktp_ibu')->store('siswa/ktp-ibu', 'local');
            $validated['foto_ktp_wali'] = null;
        } else {
            $validated['foto_ktp_ayah'] = null;
            $validated['foto_ktp_ibu'] = null;
            $validated['foto_ktp_wali'] = $request->file('foto_ktp_wali')->store('siswa/ktp-wali', 'local');
        }

        // Link to authenticated user
        $validated['user_id'] = Auth::id();
        $validated['input_source'] = Siswa::INPUT_SOURCE_ONLINE;

        $siswa = Siswa::create($validated);

        return redirect()->route('parent.siswa.show', $siswa)
            ->with('success', 'Data anak berhasil disimpan.');
    }

    /**
     * Show the siswa profile / detail page.
     */
    public function show(Siswa $siswa): View
    {
        $this->authorizeParentSiswa($siswa);

        $siswa->load('pendaftaranDetails.pendaftaran');

        return view('parent.siswa.show', compact('siswa'));
    }

    /**
     * Show the edit form for a siswa.
     */
    public function edit(Siswa $siswa): View
    {
        $this->authorizeParentSiswa($siswa);

        $userPhone = Auth::user()->no_telpon;

        return view('parent.siswa.edit', compact('siswa', 'userPhone'));
    }

    /**
     * Update a siswa's data.
     */
    public function update(UpdateSiswaRequest $request, Siswa $siswa): RedirectResponse
    {
        $this->authorizeParentSiswa($siswa);

        $validated = $request->validated();
        $oldTinggalBersama = $siswa->tinggal_bersama;

        $filesToDelete = [];
        $newFilesToCleanUp = [];

        try {
            DB::transaction(function () use ($request, $siswa, &$validated, &$filesToDelete, &$newFilesToCleanUp, $oldTinggalBersama) {
                // Handle optional file uploads — replace old file when new one is provided
                foreach (['foto', 'foto_kk', 'foto_akta', 'foto_ktp_ayah', 'foto_ktp_ibu', 'foto_ktp_wali'] as $field) {
                    if ($request->hasFile($field)) {
                        $storePath = match ($field) {
                            'foto' => 'siswa/foto',
                            'foto_kk' => 'siswa/kk',
                            'foto_akta' => 'siswa/akta',
                            'foto_ktp_ayah' => 'siswa/ktp-ayah',
                            'foto_ktp_ibu' => 'siswa/ktp-ibu',
                            'foto_ktp_wali' => 'siswa/ktp-wali',
                        };
                        $disk = $field === 'foto' ? 'public' : 'local';

                        // Store new file first
                        $newPath = $request->file($field)->store($storePath, $disk);
                        $newFilesToCleanUp[] = ['disk' => $disk, 'path' => $newPath];

                        if ($siswa->{$field}) {
                            $filesToDelete[] = ['disk' => $disk, 'path' => $siswa->{$field}];
                        }

                        $validated[$field] = $newPath;
                    } else {
                        unset($validated[$field]);
                    }
                }

                $targetTinggalBersama = $validated['tinggal_bersama'] ?? $siswa->tinggal_bersama;
                if ($oldTinggalBersama !== $targetTinggalBersama) {
                    if ($targetTinggalBersama === 'wali') {
                        // Queue deletion of KTP Ayah & Ibu
                        if ($siswa->foto_ktp_ayah) {
                            $filesToDelete[] = ['disk' => 'local', 'path' => $siswa->foto_ktp_ayah];
                        }
                        if ($siswa->foto_ktp_ibu) {
                            $filesToDelete[] = ['disk' => 'local', 'path' => $siswa->foto_ktp_ibu];
                        }
                        $validated['foto_ktp_ayah'] = null;
                        $validated['foto_ktp_ibu'] = null;
                    } else {
                        // Queue deletion of KTP Wali
                        if ($siswa->foto_ktp_wali) {
                            $filesToDelete[] = ['disk' => 'local', 'path' => $siswa->foto_ktp_wali];
                        }
                        $validated['foto_ktp_wali'] = null;
                        $validated['nama_wali'] = null;
                        $validated['nik_wali'] = null;
                        $validated['hubungan_wali'] = null;
                        $validated['no_telpon_wali'] = null;
                    }
                }

                $siswa->update($validated);

                // If there's a registration with status 'perlu_revisi', set it back to 'menunggu_verifikasi'
                $siswa->pendaftaranDetails()
                    ->where('status', PendaftaranDetail::STATUS_PERLU_REVISI)
                    ->update(['status' => PendaftaranDetail::STATUS_MENUNGGU_VERIFIKASI]);

                // Only delete old files after successful transaction commit
                DB::afterCommit(function () use ($filesToDelete) {
                    foreach ($filesToDelete as $file) {
                        Storage::disk($file['disk'])->delete($file['path']);
                    }
                });
            });
        } catch (\Throwable $e) {
            // Delete newly uploaded files since update failed
            foreach ($newFilesToCleanUp as $file) {
                Storage::disk($file['disk'])->delete($file['path']);
            }
            throw $e;
        }

        return redirect()->route('parent.siswa.pendaftaran.status', $siswa)
            ->with('success', 'Data anak berhasil diperbarui. Status telah dikembalikan ke Menunggu Verifikasi.');
    }

    /**
     * Delete siswa data (only if no active registration).
     */
    public function destroy(Siswa $siswa): RedirectResponse
    {
        $this->authorizeParentSiswa($siswa);

        // Check if there is any registration history or decision history
        $hasHistory = $siswa->pendaftaranDetails()->exists();

        if ($hasHistory) {
            return back()->with('error', 'Tidak dapat menghapus data anak karena masih ada riwayat pendaftaran atau keputusan sekolah.');
        }

        $nama = $siswa->nama;

        // Delete all rejected registrations inside transaction
        DB::transaction(function () use ($siswa) {
            $siswa->pendaftaranDetails()->delete();
            $siswa->forceDelete(); // triggers static::forceDeleted file cleanup event
        });

        ActivityLog::log('deleted', null, "Orang tua menghapus data anak: {$nama}");

        return redirect()->route('parent.siswa.index')
            ->with('success', 'Data anak berhasil dihapus.');
    }

    /**
     * Show printable ID card for accepted registration.
     */
    public function kartu(Siswa $siswa, PendaftaranDetail $detail): View
    {
        $this->authorizeParentSiswa($siswa);
        abort_unless($detail->siswa_id === $siswa->id, 404);

        $registration = $detail->load(['pendaftaran', 'pembayaran']);

        // Untuk sementara, sampai PR final endpoint:
        // kartu hanya boleh diakses jika:
        // - keputusan diterima;
        // - pembayaran ada;
        // - pembayaran status lunas.
        // Tambahkan komentar bahwa PR berikutnya akan mengganti gate ini dengan final_status = siswa_resmi_terdaftar.
        $isLunas = $registration->pembayaran?->isLunas();
        if (! $registration->isKeputusanDiterima()
            || ! $registration->pembayaran
            || ! $isLunas
        ) {
            abort(403, 'Akses ditolak. Kartu pendaftaran hanya tersedia setelah calon siswa diterima dan pembayaran lunas.');
        }

        return view('parent.siswa.kartu', compact('siswa', 'registration'));
    }
}
