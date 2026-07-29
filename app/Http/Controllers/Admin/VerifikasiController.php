<?php

namespace App\Http\Controllers\Admin;

use App\Exports\VerifikasiExport;
use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Pembayaran;
use App\Models\Pendaftaran;
use App\Models\PendaftaranDetail;
use App\Notifications\StatusPendaftaranNotification;
use App\Services\WhatsAppNotificationService;
use Barryvdh\DomPDF\Facade;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class VerifikasiController extends Controller
{
    public function __construct(private WhatsAppNotificationService $whatsApp) {}

    /**
     * Show all registrations for a specific pendaftaran period, filterable by status.
     */
    public function index(Request $request): View
    {
        $query = PendaftaranDetail::with(['siswa.user', 'pendaftaran']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->whereHas('siswa', function ($sq) use ($search) {
                    $sq->where('nama', 'like', "%{$search}%");

                    if (Schema::hasColumn('spmb_siswa', 'nisn')) {
                        $sq->orWhere('nisn', 'like', "%{$search}%");
                    }
                })
                    ->orWhere('no_pendaftaran', 'like', "%{$search}%");
            });
        }

        if ($request->filled('pendaftaran_id')) {
            $query->where('pendaftaran_id', $request->pendaftaran_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $registrations = $query->latest()->paginate(20);
        $pendaftarans = Pendaftaran::orderBy('tahun_ajaran', 'desc')->get();

        return view('admin.verifikasi.index', compact('registrations', 'pendaftarans'));
    }

    /**
     * Show detail of a single registration for verification review.
     */
    public function show(PendaftaranDetail $detail): View
    {
        $detail->load(['siswa.user', 'pendaftaran', 'pembayaran']);

        return view('admin.verifikasi.show', compact('detail'));
    }

    /**
     * Move a registration to 'menunggu_verifikasi' (document review stage).
     */
    public function startVerifikasi(PendaftaranDetail $detail): RedirectResponse
    {
        if (! $detail->isPending()) {
            return back()->with('error', 'Pendaftaran ini sudah melewati tahap pending.');
        }

        $detail->update([
            'status' => PendaftaranDetail::STATUS_MENUNGGU_VERIFIKASI,
            'notifikasi' => 'Dokumen sedang diverifikasi oleh admin.',
        ]);

        return back()->with('success', 'Status diperbarui ke: Menunggu Verifikasi.');
    }

    /**
     * Accept a registration (set status to 'diterima').
     */
    public function terima(Request $request, PendaftaranDetail $detail): RedirectResponse
    {
        $request->validate([
            'notifikasi' => ['nullable', 'string', 'max:1000'],
        ]);

        $detail->update([
            'status' => PendaftaranDetail::STATUS_DITERIMA,
            'notifikasi' => $request->notifikasi ?? 'Selamat! Pendaftaran anak Anda diterima.',
        ]);

        ActivityLog::log('accepted', $detail, "Pendaftaran {$detail->nomor_pendaftaran} diterima.");

        $detail->loadMissing('siswa.user');
        $detail->siswa?->user?->notify(new StatusPendaftaranNotification($detail->notifikasi, 'diterima'));

        $phone = $detail->siswa?->no_telpon ?? $detail->siswa?->user?->no_telpon ?? null;
        $waMessage = "Assalamu'alaikum. Ada update status pendaftaran di PAUD Az-Zahra.\n\nStatus: ".strtoupper($detail->status)."\nCatatan: ".$detail->notifikasi;
        $this->whatsApp->send($phone, $waMessage);

        return back()->with('success', 'Pendaftaran diterima. Notifikasi dikirim ke wali murid.');
    }

    /**
     * Reject a registration (set status to 'ditolak').
     */
    public function tolak(Request $request, PendaftaranDetail $detail): RedirectResponse
    {
        $request->validate([
            'notifikasi' => ['required', 'string', 'max:1000'],
        ]);

        $detail->update([
            'status' => PendaftaranDetail::STATUS_DITOLAK,
            'notifikasi' => $request->notifikasi,
        ]);

        ActivityLog::log('rejected', $detail, "Pendaftaran {$detail->nomor_pendaftaran} ditolak.");

        $detail->loadMissing('siswa.user');
        $detail->siswa?->user?->notify(new StatusPendaftaranNotification($detail->notifikasi, 'ditolak'));

        $phone = $detail->siswa?->no_telpon ?? $detail->siswa?->user?->no_telpon ?? null;
        $waMessage = "Assalamu'alaikum. Ada update status pendaftaran di PAUD Az-Zahra.\n\nStatus: ".strtoupper($detail->status)."\nCatatan: ".$detail->notifikasi;
        $this->whatsApp->send($phone, $waMessage);

        return back()->with('success', 'Pendaftaran ditolak. Notifikasi dikirim ke wali murid.');
    }

    /**
     * Set a registration to 'perlu_revisi' (needs revision).
     */
    public function revisi(Request $request, PendaftaranDetail $detail): RedirectResponse
    {
        $request->validate([
            'notifikasi' => ['required', 'string', 'max:1000'],
        ]);

        $detail->update([
            'status' => PendaftaranDetail::STATUS_PERLU_REVISI,
            'notifikasi' => $request->notifikasi,
        ]);

        ActivityLog::log('revision', $detail, "Pendaftaran {$detail->nomor_pendaftaran} diminta revisi.");

        $detail->loadMissing('siswa.user');
        $detail->siswa?->user?->notify(new StatusPendaftaranNotification($detail->notifikasi, 'perlu_revisi'));

        $phone = $detail->siswa?->no_telpon ?? $detail->siswa?->user?->no_telpon ?? null;
        $waMessage = "Assalamu'alaikum. Ada update status pendaftaran di PAUD Az-Zahra.\n\nStatus: ".strtoupper($detail->status)."\nCatatan: ".$detail->notifikasi;
        $this->whatsApp->send($phone, $waMessage);

        return back()->with('success', 'Status diubah menjadi Perlu Revisi. Notifikasi dikirim ke wali murid.');
    }

    /**
     * Verify payment proof uploaded by parent.
     */
    public function verifyPembayaran(Request $request, Pembayaran $pembayaran): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:'.Pembayaran::STATUS_LUNAS.','.Pembayaran::STATUS_DITOLAK],
            'catatan_admin' => ['nullable', 'required_if:status,'.Pembayaran::STATUS_DITOLAK, 'string', 'max:500'],
        ]);

        $pembayaran->update([
            'status' => $validated['status'],
            'catatan_admin' => $validated['status'] === Pembayaran::STATUS_DITOLAK ? $validated['catatan_admin'] : null,
        ]);

        $pembayaran->loadMissing('pendaftaranDetail');

        ActivityLog::log(
            $validated['status'] === Pembayaran::STATUS_LUNAS ? 'verified' : 'rejected',
            $pembayaran,
            $validated['status'] === Pembayaran::STATUS_LUNAS
                ? "Pembayaran {$pembayaran->pendaftaranDetail?->nomor_pendaftaran} diverifikasi."
                : "Pembayaran {$pembayaran->pendaftaranDetail?->nomor_pendaftaran} ditolak."
        );

        $statusText = $validated['status'] === Pembayaran::STATUS_LUNAS ? 'Lunas / Diterima' : 'Ditolak / Perlu Revisi';

        return back()->with('success', 'Status pembayaran berhasil diperbarui menjadi: '.$statusText);
    }

    /**
     * Delete a registration detail.
     */
    public function destroy(PendaftaranDetail $detail): RedirectResponse
    {
        if ($detail->pembayaran && $detail->pembayaran->bukti_bayar) {
            Storage::disk('local')->delete($detail->pembayaran->bukti_bayar);
        }

        $detail->delete();

        return back()->with('success', 'Data pendaftaran berhasil dihapus secara permanen.');
    }

    public function export(Request $request)
    {
        $type = $request->query('type', 'xlsx');
        $filenameBase = 'data_verifikasi_azzahra';

        if ($type === 'csv') {
            return Excel::download(new VerifikasiExport, $filenameBase.'.csv', \Maatwebsite\Excel\Excel::CSV);
        }

        if ($type === 'pdf') {
            if (class_exists(Facade::class) || app()->bound('dompdf')) {
                $items = PendaftaranDetail::with(['siswa.user', 'pendaftaran'])->get();
                $pdf = app('dompdf.wrapper');
                $pdf->loadView('admin.verifikasi.export_pdf', compact('items'));

                return $pdf->download($filenameBase.'.pdf');
            }

            return back()->with('error', 'PDF export requires barryvdh/laravel-dompdf. Run: composer require barryvdh/laravel-dompdf');
        }

        return Excel::download(new VerifikasiExport, $filenameBase.'.xlsx');
    }
}
