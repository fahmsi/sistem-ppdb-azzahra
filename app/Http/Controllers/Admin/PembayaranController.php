<?php

namespace App\Http\Controllers\Admin;

use App\Exports\PembayaranExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\VerifyPaymentRequest;
use App\Models\ActivityLog;
use App\Models\Pembayaran;
use App\Notifications\FinalEnrollmentNotification;
use App\Services\PaymentVerificationService;
use App\Services\WhatsAppNotificationService;
use Barryvdh\DomPDF\Facade;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use RuntimeException;

class PembayaranController extends Controller
{
    public function __construct(private readonly PaymentVerificationService $paymentVerification, private readonly WhatsAppNotificationService $whatsApp) {}

    /**
     * Display a listing of all payments.
     */
    public function index(Request $request): View
    {
        $status = $request->input('status');

        $query = Pembayaran::with(['verifiedBy', 'pendaftaranDetail.siswa.user', 'pendaftaranDetail.pendaftaran'])->latest();

        if ($status === Pembayaran::STATUS_MENUNGGU_VERIFIKASI) {
            $query->whereIn('status', [Pembayaran::STATUS_PENDING, Pembayaran::STATUS_MENUNGGU_VERIFIKASI]);
        } elseif ($status) {
            $query->where('status', $status);
        }

        $pembayarans = $query->paginate(20);

        return view('admin.pembayaran.index', compact('pembayarans'));
    }

    public function verify(VerifyPaymentRequest $request, Pembayaran $pembayaran): RedirectResponse
    {
        try {
            $result = $this->paymentVerification->verify($pembayaran, $request->validated('status'), $request->validated('catatan_admin'), $request->user()->id);
        } catch (RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        $detail = $result['detail'];
        $isLunas = $result['status'] === Pembayaran::STATUS_LUNAS;
        ActivityLog::log($isLunas ? 'payment_verified' : 'payment_rejected', $result['payment'], "Pembayaran {$detail->nomor_pendaftaran} ({$detail->siswa?->nama}) ".($isLunas ? 'diverifikasi lunas' : 'ditolak')." oleh admin.");
        if ($isLunas) {
            ActivityLog::log('student_officially_enrolled', $detail, "{$detail->nomor_pendaftaran} ({$detail->siswa?->nama}) resmi terdaftar oleh {$request->user()->name}.");
        }
        $detail->siswa?->user?->notify(new FinalEnrollmentNotification($detail, $isLunas ? 'officially_enrolled' : 'payment_rejected'));
        $statusText = $isLunas ? 'Siswa Resmi Terdaftar' : 'Bukti pembayaran perlu diperbaiki';
        $mpls = $isLunas && $detail->pendaftaran?->tanggal_mpls ? ' MPLS: '.$detail->pendaftaran->tanggal_mpls->format('d/m/Y').'.' : '';
        $this->whatsApp->send($detail->siswa?->no_telpon ?? $detail->siswa?->user?->no_telpon, "Assalamu'alaikum. {$detail->siswa?->nama} (No. {$detail->nomor_pendaftaran}) - {$statusText}.{$mpls}");

        return back()->with('success', 'Status pembayaran berhasil diperbarui.');
    }

    public function export(Request $request)
    {
        $type = $request->query('type', 'xlsx');
        $filenameBase = 'rekap_pembayaran_azzahra';

        if ($type === 'csv') {
            return Excel::download(new PembayaranExport, $filenameBase.'.csv', \Maatwebsite\Excel\Excel::CSV);
        }

        if ($type === 'pdf') {
            if (class_exists(Facade::class) || app()->bound('dompdf')) {
                $items = Pembayaran::with(['verifiedBy', 'pendaftaranDetail.siswa.user', 'pendaftaranDetail.pendaftaran'])->get();
                $pdf = app('dompdf.wrapper');
                $pdf->loadView('admin.pembayaran.export_pdf', compact('items'));

                return $pdf->download($filenameBase.'.pdf');
            }

            return back()->with('error', 'PDF export requires barryvdh/laravel-dompdf. Run: composer require barryvdh/laravel-dompdf');
        }

        return Excel::download(new PembayaranExport, $filenameBase.'.xlsx');
    }
}
