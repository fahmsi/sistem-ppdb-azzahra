<?php

namespace App\Http\Controllers\Admin;

use App\Exports\PembayaranExport;
use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class PembayaranController extends Controller
{
    /**
     * Display a listing of all payments.
     */
    public function index(Request $request): View
    {
        $status = $request->input('status');

        $query = Pembayaran::with(['pendaftaranDetail.siswa.user', 'pendaftaranDetail.pendaftaran'])->latest();

        if ($status) {
            $query->where('status', $status);
        }

        $pembayarans = $query->paginate(20);

        return view('admin.pembayaran.index', compact('pembayarans'));
    }

    public function export(Request $request)
    {
        $type = $request->query('type', 'xlsx');
        $filenameBase = 'rekap_pembayaran_azzahra';

        if ($type === 'csv') {
            return Excel::download(new PembayaranExport, $filenameBase . '.csv', \Maatwebsite\Excel\Excel::CSV);
        }

        if ($type === 'pdf') {
            if (class_exists(\Barryvdh\DomPDF\Facade::class) || app()->bound('dompdf')) {
                $items = Pembayaran::with(['pendaftaranDetail.siswa.user', 'pendaftaranDetail.pendaftaran'])->get();
                $pdf = app('dompdf.wrapper');
                $pdf->loadView('admin.pembayaran.export_pdf', compact('items'));
                return $pdf->download($filenameBase . '.pdf');
            }

            return back()->with('error', 'PDF export requires barryvdh/laravel-dompdf. Run: composer require barryvdh/laravel-dompdf');
        }

        return Excel::download(new PembayaranExport, $filenameBase . '.xlsx');
    }
}
