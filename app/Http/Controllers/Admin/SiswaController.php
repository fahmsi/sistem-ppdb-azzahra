<?php

namespace App\Http\Controllers\Admin;

use App\Exports\SiswaExport;
use App\Http\Controllers\Controller;
use App\Models\Siswa;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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

        $query = Siswa::with('user')->latest();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('nisn', 'like', "%{$search}%")
                    ->orWhere('nama_ayah', 'like', "%{$search}%")
                    ->orWhere('nama_ibu', 'like', "%{$search}%")
                    ->orWhere('no_telpon', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($q2) use ($search) {
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
                    'nisn' => $s->nisn ?? '-',
                    'jenis_kelamin' => $s->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan',
                    'orang_tua' => $s->user->name ?? '-',
                    'no_telpon' => $s->no_telpon ?? $s->user->no_telpon ?? '-',
                    'show_url' => route('admin.siswa.show', $s->id),
                    'wa_url' => $s->no_telpon ? 'https://wa.me/62'.ltrim(preg_replace('/^0/', '', $s->no_telpon), '+') : '#',
                ];
            }));

        }

        $siswas = $query->paginate(20);

        return view('admin.siswa.index', compact('siswas'));
    }

    /**
     * Display the specified student detail (for Admin view / PDF print).
     */
    public function show(Siswa $siswa): View
    {
        $siswa->load('user', 'pendaftaranDetails.pendaftaran', 'pendaftaranDetails.pembayaran');

        return view('admin.siswa.show', compact('siswa'));
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
}
