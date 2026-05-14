<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePendaftaranRequest;
use App\Models\Pendaftaran;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PendaftaranManageController extends Controller
{
    /**
     * List all registration periods for admin management.
     */
    public function index(): View
    {
        $pendaftarans = Pendaftaran::withCount('pendaftaranDetails')
            ->orderBy('tahun_ajaran', 'desc')
            ->orderBy('gelombang', 'desc')
            ->get();

        return view('admin.pendaftaran.index', compact('pendaftarans'));
    }

    /**
     * Show form to create a new registration period.
     */
    public function create(): View
    {
        return view('admin.pendaftaran.create');
    }

    /**
     * Store a new registration period.
     */
    public function store(StorePendaftaranRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        if ($request->hasFile('gambar')) {
            $validated['gambar'] = $request->file('gambar')->store('pendaftaran', 'public');
        }

        Pendaftaran::create($validated);

        return redirect()->route('admin.pendaftaran.index')
            ->with('success', 'Gelombang pendaftaran berhasil dibuat.');
    }

    /**
     * Show form to edit a registration period.
     */
    public function edit(Pendaftaran $pendaftaran): View
    {
        return view('admin.pendaftaran.edit', compact('pendaftaran'));
    }

    /**
     * Update a registration period.
     */
    public function update(StorePendaftaranRequest $request, Pendaftaran $pendaftaran): RedirectResponse
    {
        $validated = $request->validated();

        if ($request->hasFile('gambar')) {
            if ($pendaftaran->gambar) {
                Storage::disk('public')->delete($pendaftaran->gambar);
            }
            $validated['gambar'] = $request->file('gambar')->store('pendaftaran', 'public');
        }

        $pendaftaran->update($validated);

        return redirect()->route('admin.pendaftaran.index')
            ->with('success', 'Gelombang pendaftaran berhasil diperbarui.');
    }

    /**
     * Toggle registration period status (open/close).
     */
    public function toggleStatus(Pendaftaran $pendaftaran): RedirectResponse
    {
        $pendaftaran->update([
            'status' => $pendaftaran->isOpen() ? 'tutup' : 'buka',
        ]);

        $statusLabel = $pendaftaran->isOpen() ? 'dibuka' : 'ditutup';

        return back()->with('success', "Pendaftaran berhasil {$statusLabel}.");
    }
}
