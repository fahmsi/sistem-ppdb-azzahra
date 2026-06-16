<?php

namespace App\Http\Controllers\Admin;

use App\Exports\SiswaExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminStoreSiswaRequest;
use App\Models\ActivityLog;
use App\Models\Siswa;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class SiswaController extends Controller
{
    /**
     * Display a listing of all registered students.
     */
    public function index(Request $request): View|JsonResponse
    {
        $search = $request->input('search');

        $query = Siswa::with(['user', 'createdByAdmin'])
            ->withCount('pendaftaranDetails')
            ->latest();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('nama_ayah', 'like', "%{$search}%")
                    ->orWhere('nama_ibu', 'like', "%{$search}%")
                    ->orWhere('no_telpon', 'like', "%{$search}%");

                if ($this->hasSiswaColumn('nisn')) {
                    $q->orWhere('nisn', 'like', "%{$search}%");
                }

                $q->orWhereHas('user', function ($q2) use ($search) {
                    $q2->where('name', 'like', "%{$search}%");
                });
            });
        }

        // AJAX live search
        if ($request->ajax()) {
            $siswas = $query->limit(20)->get();

            return response()->json($siswas->map(function ($s) {
                return [
                    'id' => $s->id,
                    'nama' => $s->nama,
                    'nama_panggilan' => $s->nama_panggilan ?? '-',
                    'nisn' => $s->nisn ?? '-',
                    'jenis_kelamin' => $s->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan',
                    'orang_tua' => $s->user?->name ?? '-',
                    'no_telpon' => $s->no_telpon ?? $s->user?->no_telpon ?? '-',
                    'input_source' => $s->input_source ?? Siswa::INPUT_SOURCE_ONLINE,
                    'source_label' => $this->inputSourceLabel($s),
                    'has_history' => $s->pendaftaran_details_count > 0,
                    'show_url' => route('admin.siswa.show', $s->id),
                    'delete_url' => route('admin.siswa.destroy', $s->id),
                    'wa_url' => $s->no_telpon ? 'https://wa.me/62'.ltrim(preg_replace('/^0/', '', $s->no_telpon), '+') : '#',
                ];
            }));

        }

        $siswas = $query->paginate(20);

        return view('admin.siswa.index', compact('siswas'));
    }

    /**
     * Show the manual student creation form for admin users.
     */
    public function create(): View
    {
        return view('parent.siswa.create', [
            'pageTitle' => 'Tambah Data Siswa Manual',
            'headerTitle' => 'Tambah Data Siswa',
            'formHeading' => 'Tambah Biodata Siswa',
            'formDescription' => 'Isi data siswa secara manual. Data ini boleh belum terhubung dengan akun wali murid.',
            'formAction' => route('admin.siswa.store'),
            'cancelUrl' => route('admin.siswa.index'),
            'cancelText' => 'Batal',
            'submitText' => 'Simpan Data Siswa',
            'userPhone' => '',
        ]);
    }

    /**
     * Store manually created student data.
     */
    public function store(AdminStoreSiswaRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $validated['foto'] = $request->file('foto')->store('siswa/foto', 'public');
        $validated['foto_kk'] = $request->file('foto_kk')->store('siswa/kk', 'local');
        $validated['foto_akta'] = $request->file('foto_akta')->store('siswa/akta', 'local');
        $validated['user_id'] = null;
        $validated['created_by_admin_id'] = Auth::id();
        $validated['input_source'] = Siswa::INPUT_SOURCE_MANUAL_ADMIN;

        $siswa = Siswa::create($validated);

        ActivityLog::log(
            'created',
            $siswa,
            "Admin menambahkan data siswa manual: {$siswa->nama}",
            ['input_source' => Siswa::INPUT_SOURCE_MANUAL_ADMIN]
        );

        return redirect()->route('admin.siswa.show', $siswa)
            ->with('success', 'Data siswa berhasil ditambahkan secara manual.');
    }

    /**
     * Display the specified student detail (for Admin view / PDF print).
     */
    public function show(Siswa $siswa): View
    {
        $siswa->load('user', 'createdByAdmin', 'pendaftaranDetails.pendaftaran', 'pendaftaranDetails.pembayaran');

        return view('admin.siswa.show', compact('siswa'));
    }

    /**
     * Soft delete a student record from the admin area.
     */
    public function destroy(Request $request, Siswa $siswa): RedirectResponse
    {
        $validated = $request->validate([
            'deleted_reason' => ['required', 'string', 'max:1000'],
        ], [
            'deleted_reason.required' => 'Alasan penghapusan wajib diisi.',
        ]);

        $siswa->forceFill([
            'deleted_by' => Auth::id(),
            'deleted_reason' => $validated['deleted_reason'],
        ])->save();

        $siswa->delete();

        ActivityLog::log(
            'soft_deleted',
            $siswa,
            "Admin menghapus sementara data siswa: {$siswa->nama}",
            ['reason' => $validated['deleted_reason']]
        );

        return redirect()->route('admin.siswa.index')
            ->with('success', 'Data siswa berhasil dihapus sementara.');
    }

    /**
     * Display soft-deleted student records.
     */
    public function trash(): View
    {
        $siswas = Siswa::onlyTrashed()
            ->with(['user', 'createdByAdmin', 'deletedBy'])
            ->withCount('pendaftaranDetails')
            ->orderByDesc('deleted_at')
            ->paginate(20);

        return view('admin.siswa.trash', compact('siswas'));
    }

    /**
     * Restore soft-deleted student data. Super admin only.
     */
    public function restore(int $id): RedirectResponse
    {
        abort_unless(Auth::user()?->isSuperAdmin(), 403, 'Akses ditolak.');

        $siswa = Siswa::withTrashed()->findOrFail($id);

        if (! $siswa->trashed()) {
            return redirect()->route('admin.siswa.trash')
                ->with('error', 'Data siswa tidak berada di Data Terhapus.');
        }

        $siswa->restore();

        ActivityLog::log(
            'restored',
            $siswa,
            "Super admin memulihkan data siswa: {$siswa->nama}"
        );

        return redirect()->route('admin.siswa.trash')
            ->with('success', 'Data siswa berhasil dipulihkan.');
    }

    /**
     * Permanently delete soft-deleted student data. Super admin only.
     */
    public function forceDelete(int $id): RedirectResponse
    {
        abort_unless(Auth::user()?->isSuperAdmin(), 403, 'Akses ditolak.');

        $siswa = Siswa::onlyTrashed()->findOrFail($id);

        $hasRegistrationHistory = $siswa->pendaftaranDetails()->exists();
        $hasPaymentHistory = $siswa->pendaftaranDetails()->whereHas('pembayaran')->exists();

        if ($hasRegistrationHistory || $hasPaymentHistory) {
            return redirect()->route('admin.siswa.trash')
                ->with('error', 'Data siswa tidak dapat dihapus permanen karena masih memiliki riwayat pendaftaran atau pembayaran.');
        }

        ActivityLog::log(
            'force_deleted',
            $siswa,
            "Super admin menghapus permanen data siswa: {$siswa->nama}"
        );

        $this->deleteStoredDocuments($siswa);

        $siswa->forceDelete();

        return redirect()->route('admin.siswa.trash')
            ->with('success', 'Data siswa berhasil dihapus permanen.');
    }

    // Export data (supports xlsx (default), csv, and pdf (requires dompdf))
    public function export(Request $request)
    {
        $type = $request->query('type', 'xlsx');
        $filenameBase = 'data_siswa_azzahra';

        if ($type === 'csv') {
            return Excel::download(new SiswaExport, $filenameBase . '.csv', \Maatwebsite\Excel\Excel::CSV);
        }

        if ($type === 'pdf') {
            if (class_exists(\Barryvdh\DomPDF\Facade::class) || app()->bound('dompdf')) {
                $siswas = Siswa::with('user')->get();
                $pdf = app('dompdf.wrapper');
                $pdf->loadView('admin.siswa.export_pdf', compact('siswas'));
                return $pdf->download($filenameBase . '.pdf');
            }

            return back()->with('error', 'PDF export requires barryvdh/laravel-dompdf. Run: composer require barryvdh/laravel-dompdf');
        }

        return Excel::download(new SiswaExport, $filenameBase . '.xlsx');
    }

    private function inputSourceLabel(Siswa $siswa): string
    {
        return $siswa->input_source === Siswa::INPUT_SOURCE_MANUAL_ADMIN
            ? 'Manual Admin'
            : 'Online';
    }

    private function deleteStoredDocuments(Siswa $siswa): void
    {
        if ($siswa->foto) {
            Storage::disk('public')->delete($siswa->foto);
        }

        if ($siswa->foto_kk) {
            Storage::disk('local')->delete($siswa->foto_kk);
        }

        if ($siswa->foto_akta) {
            Storage::disk('local')->delete($siswa->foto_akta);
        }
    }

    private function hasSiswaColumn(string $column): bool
    {
        static $columns = [];

        return $columns[$column] ??= Schema::hasColumn('psb_siswa', $column);
    }
}
