<?php

namespace App\Services;

use App\Models\FinalisasiPendaftaran;
use App\Models\PendaftaranDetail;
use RuntimeException;

class FinalEnrollmentService
{
    public const SOURCE_ADMISSION_DECISION = 'admission_decision';
    public const SOURCE_PAYMENT_VERIFIED = 'payment_verified';
    public const SOURCE_MANUAL_DISCONTINUE = 'manual_discontinue';

    /**
     * Applies a single terminal final-enrollment transition. The caller owns
     * the transaction and must pass a detail locked with lockForUpdate().
     */
    public function transition(
        PendaftaranDetail $detail,
        string $toStatus,
        string $source,
        ?int $actorId,
        ?string $alasan = null,
        ?string $catatan = null,
    ): PendaftaranDetail {
        $allowed = [
            PendaftaranDetail::FINAL_SISWA_RESMI_TERDAFTAR,
            PendaftaranDetail::FINAL_PENDAFTARAN_TIDAK_DILANJUTKAN,
            PendaftaranDetail::FINAL_MENGUNDURKAN_DIRI,
        ];

        if (! in_array($toStatus, $allowed, true)) {
            throw new RuntimeException('Status akhir pendaftaran tidak valid.');
        }
        if (! $detail->isFinalDalamProses()) {
            throw new RuntimeException('Status akhir pendaftaran sudah terminal dan tidak dapat diubah.');
        }

        $fromStatus = $detail->final_status;
        $now = now();
        $detail->update([
            'final_status' => $toStatus,
            'final_alasan' => $alasan,
            'final_catatan' => $catatan,
            'final_ditetapkan_oleh' => $actorId,
            'final_ditetapkan_at' => $now,
        ]);

        FinalisasiPendaftaran::create([
            'pendaftaran_detail_id' => $detail->id,
            'from_status' => $fromStatus,
            'to_status' => $toStatus,
            'source' => $source,
            'alasan' => $alasan,
            'catatan' => $catatan,
            'finalized_by' => $actorId,
            'finalized_at' => $now,
        ]);

        return $detail->fresh();
    }
}
