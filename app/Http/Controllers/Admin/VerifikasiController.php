<?php

namespace App\Http\Controllers\Admin;

use App\Exports\VerifikasiExport;
use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Pembayaran;
use App\Models\Pendaftaran;
use App\Models\PendaftaranDetail;
use App\Notifications\AdministrasiLengkapNotification;
use App\Notifications\StatusPendaftaranNotification;
use App\Services\ObservationSchedulingService;
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
    public function __construct(
        private WhatsAppNotificationService $whatsApp,
        private ObservationSchedulingService $scheduler,
    ) {}

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
                    $sq->where('nama', 'like', "%{$search}%");

                    if (Schema::hasColumn('spmb_siswa', 'nisn')) {
                        $sq->orWhere('nisn', 'like', "%{$search}%");
                    }
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
        $detail->load([
            'siswa.user',
            'pendaftaran',
            'pembayaran',
            'observasis.scheduledBy',
            'observasis.observedBy',
            'observasis.rescheduledBy',
            'observasiTerbaru',
        ]);

        $rescheduleDeadline = null;
        $isPastDeadline = false;
        if ($detail->pendaftaran?->tanggal_mpls) {
            $rescheduleDeadline = $this->scheduler->getDeadline($detail->pendaftaran);
            $isPastDeadline = $this->scheduler->isPastRescheduleDeadline($detail->pendaftaran);
        }

        return view('admin.verifikasi.show', compact('detail', 'rescheduleDeadline', 'isPastDeadline'));
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

        // Send Notification if this siswa is linked to a parent account.
        $detail->loadMissing('siswa.user');
        $detail->siswa?->user?->notify(new StatusPendaftaranNotification($detail->notifikasi, 'perlu_revisi'));

        // Ambil nomor WA (prioritaskan nomor di tabel siswa, jika kosong ambil dari tabel user)
        $phone = $detail->siswa?->no_telpon ?? $detail->siswa?->user?->no_telpon ?? null;
        $waMessage = "Assalamu'alaikum. Ada update status pendaftaran di PAUD Az-Zahra.\n\nStatus: ".strtoupper($detail->status)."\nCatatan: ".$detail->notifikasi;
        $this->whatsApp->send($phone, $waMessage);

        return back()->with('success', 'Status diubah menjadi Perlu Revisi. Notifikasi dikirim ke wali murid.');
    }

    /**
     * Bulk update status for multiple registrations at once.
     */
    public function bulkUpdate(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'detail_ids' => ['required', 'array', 'min:1'],
            'detail_ids.*' => ['required', 'integer', 'exists:spmb_pendaftaran_detail,id'],
            // Restricted to verification phase states (no administrasi_lengkap, diterima, ditolak, or menunggu_keputusan)
            'status' => ['required', 'in:menunggu_verifikasi,perlu_revisi'],
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

    public function setKelompok(Request $request, PendaftaranDetail $detail): RedirectResponse
    {
        $validated = $request->validate([
            'kelompok_final' => ['required', 'in:A,B'],
        ]);

        $detail->update([
            'kelompok_final' => $validated['kelompok_final'],
            'kelompok_ditetapkan_oleh' => auth()->id(),
            'kelompok_ditetapkan_at' => now(),
        ]);

        ActivityLog::log(
            'group_assigned',
            $detail,
            'Admin menetapkan Kelompok final '.$validated['kelompok_final'].' untuk pendaftaran '.$detail->nomor_pendaftaran
        );

        return back()->with('success', 'Kelompok final berhasil ditetapkan menjadi Kelompok '.$validated['kelompok_final'].'.');
    }

    /**
     * Mark administration documents as complete and move to observation stage.
     */
    public function administrasiLengkap(PendaftaranDetail $detail): RedirectResponse
    {
        if (! in_array($detail->status, [
            PendaftaranDetail::STATUS_MENUNGGU_VERIFIKASI,
            PendaftaranDetail::STATUS_PERLU_REVISI,
        ], true)) {
            return back()->with('error', 'Administrasi hanya dapat dinyatakan lengkap dari status menunggu verifikasi atau perlu revisi.');
        }

        $detail->update([
            'status' => PendaftaranDetail::STATUS_ADMINISTRASI_LENGKAP,
            'notifikasi' => 'Berkas administrasi telah dinyatakan lengkap. Tahap selanjutnya adalah penjadwalan observasi.',
        ]);

        ActivityLog::log(
            'administration_completed',
            $detail,
            "Administrasi pendaftaran {$detail->nomor_pendaftaran} dinyatakan lengkap."
        );

        // Notify parent via database notification
        $detail->loadMissing('siswa.user');
        $user = $detail->siswa?->user;
        if ($user) {
            $user->notify(new AdministrasiLengkapNotification($detail));
        }

        // WhatsApp notification (best-effort)
        $phone = $detail->siswa?->no_telpon ?? $detail->siswa?->user?->no_telpon ?? null;
        $namaAnak = $detail->siswa?->nama ?? 'Anak Anda';
        $waMessage = "Assalamu'alaikum. Berkas administrasi {$namaAnak} (No. {$detail->nomor_pendaftaran}) telah dinyatakan lengkap oleh admin PAUD Az-Zahra. Tahap selanjutnya adalah penjadwalan observasi di sekolah. Kami akan memberitahu Anda segera setelah jadwal ditetapkan.";
        $this->whatsApp->send($phone, $waMessage);

        return back()->with('success', 'Administrasi dinyatakan lengkap. Notifikasi dikirim ke orang tua.');
    }
}
