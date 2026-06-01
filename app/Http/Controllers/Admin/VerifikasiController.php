<?php

namespace App\Http\Controllers\Admin;

use App\Exports\VerifikasiExport;
use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\Pendaftaran;
use App\Models\PendaftaranDetail;
use App\Notifications\StatusPendaftaranNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class VerifikasiController extends Controller
{
    /**
     * Show all registrations for a specific pendaftaran period, filterable by status.
     */
    public function index(Request $request): View
    {
        $query = PendaftaranDetail::with(['siswa.user', 'pendaftaran']);

        // Search by siswa name or related user name / no pendaftaran
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->whereHas('siswa', function ($sq) use ($search) {
                    $sq->where('nama', 'like', "%{$search}%")
                        ->orWhere('nisn', 'like', "%{$search}%");
                })
                ->orWhere('no_pendaftaran', 'like', "%{$search}%");
            });
        }

        // Filter by pendaftaran period
        if ($request->filled('pendaftaran_id')) {
            $query->where('pendaftaran_id', $request->pendaftaran_id);
        }

        // Filter by status
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
        $detail->load(['siswa.user', 'pendaftaran']);

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

        // Send Notification
        $detail->siswa->user->notify(new StatusPendaftaranNotification($detail->notifikasi, 'diterima'));

        // Ambil nomor WA (prioritaskan nomor di tabel siswa, jika kosong ambil dari tabel user)
        $phone = $detail->siswa->no_telpon ?? $detail->siswa->user->no_telpon ?? null;
        $waMessage = "Assalamu'alaikum. Ada update status pendaftaran di PAUD Az-Zahra.\n\nStatus: " . strtoupper($detail->status) . "\nCatatan: " . $detail->notifikasi;
        $this->sendWhatsAppNotification($phone, $waMessage);

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

        // Send Notification
        $detail->siswa->user->notify(new StatusPendaftaranNotification($detail->notifikasi, 'ditolak'));

        // Ambil nomor WA (prioritaskan nomor di tabel siswa, jika kosong ambil dari tabel user)
        $phone = $detail->siswa->no_telpon ?? $detail->siswa->user->no_telpon ?? null;
        $waMessage = "Assalamu'alaikum. Ada update status pendaftaran di PAUD Az-Zahra.\n\nStatus: " . strtoupper($detail->status) . "\nCatatan: " . $detail->notifikasi;
        $this->sendWhatsAppNotification($phone, $waMessage);

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

        // Send Notification
        $detail->siswa->user->notify(new StatusPendaftaranNotification($detail->notifikasi, 'perlu_revisi'));

        // Ambil nomor WA (prioritaskan nomor di tabel siswa, jika kosong ambil dari tabel user)
        $phone = $detail->siswa->no_telpon ?? $detail->siswa->user->no_telpon ?? null;
        $waMessage = "Assalamu'alaikum. Ada update status pendaftaran di PAUD Az-Zahra.\n\nStatus: " . strtoupper($detail->status) . "\nCatatan: " . $detail->notifikasi;
        $this->sendWhatsAppNotification($phone, $waMessage);

        return back()->with('success', 'Status diubah menjadi Perlu Revisi. Notifikasi dikirim ke wali murid.');
    }

    /**
     * Bulk update status for multiple registrations at once.
     */
    public function bulkUpdate(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'detail_ids' => ['required', 'array', 'min:1'],
            'detail_ids.*' => ['required', 'integer', 'exists:psb_pendaftaran_detail,id'],
            'status' => ['required', 'in:menunggu_verifikasi,diterima,ditolak,perlu_revisi'],
            'notifikasi' => ['nullable', 'string', 'max:1000'],
        ]);

        PendaftaranDetail::whereIn('id', $validated['detail_ids'])
            ->update([
                'status' => $validated['status'],
                'notifikasi' => $validated['notifikasi'] ?? null,
            ]);

        return back()->with('success', count($validated['detail_ids']).' pendaftaran berhasil diperbarui.');
    }

    /**
     * Verify payment proof uploaded by parent.
     */
    public function verifyPembayaran(Request $request, Pembayaran $pembayaran): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:lunas,ditolak'],
            'catatan_admin' => ['nullable', 'string', 'max:500'],
        ]);

        $pembayaran->update([
            'status' => $validated['status'],
            'catatan_admin' => $validated['catatan_admin'],
        ]);

        $statusText = $validated['status'] === 'lunas' ? 'Lunas / Diterima' : 'Ditolak';

        return back()->with('success', 'Status pembayaran berhasil diperbarui menjadi: '.$statusText);
    }

    /**
     * Delete a registration detail.
     */
    public function destroy(PendaftaranDetail $detail): RedirectResponse
    {
        // Optionally delete associated payment proof files here if needed
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
            return Excel::download(new VerifikasiExport, $filenameBase . '.csv', \Maatwebsite\Excel\Excel::CSV);
        }

        if ($type === 'pdf') {
            if (class_exists(\Barryvdh\DomPDF\Facade::class) || app()->bound('dompdf')) {
                $items = PendaftaranDetail::with(['siswa.user', 'pendaftaran'])->get();
                $pdf = app('dompdf.wrapper');
                $pdf->loadView('admin.verifikasi.export_pdf', compact('items'));
                return $pdf->download($filenameBase . '.pdf');
            }

            return back()->with('error', 'PDF export requires barryvdh/laravel-dompdf. Run: composer require barryvdh/laravel-dompdf');
        }

        return Excel::download(new VerifikasiExport, $filenameBase . '.xlsx');
    }

    private function sendWhatsAppNotification($phone, $message)
    {
        $token = env('FONNTE_TOKEN');
        if (!$token || empty($phone)) return;

        // Bersihkan dan format nomor ke standar internasional (62)
        $phone = preg_replace('/[^0-9]/', '', $phone);
        if (substr($phone, 0, 1) == '0') {
            $phone = '62' . substr($phone, 1);
        }

        try {
            Http::withHeaders([
                'Authorization' => $token,
            ])->post('https://api.fonnte.com/send', [
                'target' => $phone,
                'message' => $message,
                'countryCode' => '62',
            ]);
        } catch (\Exception $e) {
            // Fail silently agar error API WA tidak merusak/menggagalkan proses verifikasi admin
        }
    }
}
