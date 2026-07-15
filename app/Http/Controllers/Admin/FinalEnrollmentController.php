<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DiscontinueEnrollmentRequest;
use App\Models\ActivityLog;
use App\Models\Pembayaran;
use App\Models\PendaftaranDetail;
use App\Notifications\FinalEnrollmentNotification;
use App\Services\FinalEnrollmentService;
use App\Services\WhatsAppNotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class FinalEnrollmentController extends Controller
{
    public function __construct(private readonly FinalEnrollmentService $finalEnrollment, private readonly WhatsAppNotificationService $whatsApp) {}

    public function discontinue(DiscontinueEnrollmentRequest $request, PendaftaranDetail $detail): RedirectResponse
    {
        try {
            $updated = DB::transaction(function () use ($request, $detail) {
                $locked = PendaftaranDetail::whereKey($detail->id)->lockForUpdate()->firstOrFail();
                $payment = Pembayaran::where('pendaftaran_detail_id', $locked->id)->lockForUpdate()->first();
                if (! $locked->isKeputusanDiterima() || ! $locked->isFinalDalamProses() || $payment?->isLunas()) {
                    throw new RuntimeException('Pendaftaran ini tidak dapat ditutup pada status saat ini.');
                }
                return $this->finalEnrollment->transition(
                    $locked,
                    PendaftaranDetail::FINAL_PENDAFTARAN_TIDAK_DILANJUTKAN,
                    FinalEnrollmentService::SOURCE_MANUAL_DISCONTINUE,
                    $request->user()->id,
                    $request->validated('final_alasan'),
                    $request->validated('final_catatan'),
                );
            });
        } catch (RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        $updated->load(['siswa.user', 'pendaftaran']);
        ActivityLog::log('enrollment_discontinued', $updated, "Pendaftaran {$updated->nomor_pendaftaran} ({$updated->siswa?->nama}) ditutup oleh admin.");
        $updated->siswa?->user?->notify(new FinalEnrollmentNotification($updated, 'enrollment_discontinued'));
        $this->whatsApp->send($updated->siswa?->no_telpon ?? $updated->siswa?->user?->no_telpon, "Assalamu'alaikum. Proses daftar ulang {$updated->siswa?->nama} (No. {$updated->nomor_pendaftaran}) telah ditutup dengan status Pendaftaran Tidak Dilanjutkan.");

        return back()->with('success', 'Pendaftaran berhasil ditutup dengan status Pendaftaran Tidak Dilanjutkan.');
    }
}
