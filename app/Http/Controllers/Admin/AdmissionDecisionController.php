<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreAdmissionDecisionRequest;
use App\Models\ActivityLog;
use App\Models\PendaftaranDetail;
use App\Notifications\KeputusanPendaftaranNotification;
use App\Services\AdmissionDecisionService;
use App\Services\WhatsAppNotificationService;
use Illuminate\Http\RedirectResponse;
use RuntimeException;

class AdmissionDecisionController extends Controller
{
    public function __construct(
        private readonly AdmissionDecisionService $decisionService,
        private readonly WhatsAppNotificationService $whatsApp,
    ) {}

    /**
     * Store admission decision.
     */
    public function store(StoreAdmissionDecisionRequest $request, PendaftaranDetail $detail): RedirectResponse
    {
        try {
            $updatedDetail = $this->decisionService->makeDecision($detail, $request->validated(), auth()->id());

            // 1. Log Activity
            $action = match ($updatedDetail->keputusan_status) {
                PendaftaranDetail::KEPUTUSAN_DITERIMA => 'admission_accepted',
                PendaftaranDetail::KEPUTUSAN_TIDAK_DITERIMA => 'admission_not_accepted',
                PendaftaranDetail::KEPUTUSAN_PERLU_TINDAK_LANJUT => 'admission_follow_up',
                PendaftaranDetail::KEPUTUSAN_MENGUNDURKAN_DIRI => 'admission_withdrawn',
            };

            $statusText = match ($updatedDetail->keputusan_status) {
                PendaftaranDetail::KEPUTUSAN_DITERIMA => 'Diterima',
                PendaftaranDetail::KEPUTUSAN_TIDAK_DITERIMA => 'Tidak Diterima',
                PendaftaranDetail::KEPUTUSAN_PERLU_TINDAK_LANJUT => 'Perlu Tindak Lanjut',
                PendaftaranDetail::KEPUTUSAN_MENGUNDURKAN_DIRI => 'Mengundurkan Diri',
            };

            $namaAnak = $updatedDetail->siswa?->nama ?? 'Anak';
            $adminName = auth()->user()->name;

            ActivityLog::log(
                $action,
                $updatedDetail,
                "Keputusan pendaftaran {$updatedDetail->nomor_pendaftaran} untuk {$namaAnak} ditetapkan sebagai {$statusText} oleh {$adminName}."
            );

            // 2. Database Notification
            $updatedDetail->loadMissing('siswa.user');
            $parent = $updatedDetail->siswa?->user;
            if ($parent) {
                $parent->notify(new KeputusanPendaftaranNotification($updatedDetail));
            }

            // 3. Best-effort WhatsApp Notification (wrapped in try-catch)
            try {
                $phone = $updatedDetail->siswa?->no_telpon ?? $parent?->no_telpon ?? null;
                if ($phone) {
                    $waMessage = match ($updatedDetail->keputusan_status) {
                        PendaftaranDetail::KEPUTUSAN_DITERIMA => "Assalamu'alaikum. Alhamdulillah, Ananda {$namaAnak} dinyatakan diterima di PAUD Az-Zahra. Silakan melanjutkan proses daftar ulang dan pembayaran melalui sistem.",
                        PendaftaranDetail::KEPUTUSAN_TIDAK_DITERIMA => "Assalamu'alaikum. Terima kasih telah mengikuti proses SPMB. Berdasarkan hasil keseluruhan proses, Ananda {$namaAnak} belum dapat diterima pada tahun ajaran ini.",
                        PendaftaranDetail::KEPUTUSAN_PERLU_TINDAK_LANJUT => "Assalamu'alaikum. Pendaftaran Ananda {$namaAnak} memerlukan tindak lanjut dari pihak sekolah. Silakan melihat catatan keputusan atau menghubungi sekolah.",
                        PendaftaranDetail::KEPUTUSAN_MENGUNDURKAN_DIRI => "Assalamu'alaikum. Pendaftaran Ananda {$namaAnak} telah ditutup dengan status Mengundurkan Diri.",
                    };
                    $this->whatsApp->send($phone, $waMessage);
                }
            } catch (\Exception $e) {
                // Ignore WhatsApp notification failure
            }

            return back()->with('success', "Keputusan {$statusText} berhasil disimpan.");

        } catch (RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
