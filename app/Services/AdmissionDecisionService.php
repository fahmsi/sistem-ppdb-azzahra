<?php

namespace App\Services;

use App\Models\KeputusanPendaftaran;
use App\Models\Observasi;
use App\Models\PendaftaranDetail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class AdmissionDecisionService
{
    public function __construct(private readonly FinalEnrollmentService $finalEnrollment) {}

    /**
     * Make an admission decision.
     */
    public function makeDecision(PendaftaranDetail $detail, array $data, int $decidedByUserId): PendaftaranDetail
    {
        $user = User::findOrFail($decidedByUserId);
        if (! in_array($user->role, ['admin', 'super_admin'], true)) {
            throw new RuntimeException('Aksi hanya dapat dilakukan oleh Admin atau Super Admin.');
        }

        return DB::transaction(function () use ($detail, $data, $decidedByUserId) {
            // 1. Lock PendaftaranDetail for update
            $lockedDetail = PendaftaranDetail::whereKey($detail->id)->lockForUpdate()->firstOrFail();

            // 2. Prevent decision processing if already has a final decision
            if ($lockedDetail->isKeputusanFinal()) {
                throw new RuntimeException('Keputusan final sudah ditetapkan dan tidak dapat diubah.');
            }

            $status = $data['keputusan_status'];
            $catatan = $data['keputusan_catatan'] ?? null;
            $alasan = $data['keputusan_alasan'] ?? null;

            // Get the latest observation attempt
            $latestObs = Observasi::where('pendaftaran_detail_id', $lockedDetail->id)
                ->orderBy('attempt_number', 'desc')
                ->lockForUpdate()
                ->first();

            // Conditions validation
            if ($status === PendaftaranDetail::KEPUTUSAN_DITERIMA ||
                $status === PendaftaranDetail::KEPUTUSAN_TIDAK_DITERIMA ||
                $status === PendaftaranDetail::KEPUTUSAN_PERLU_TINDAK_LANJUT) {

                if ($lockedDetail->status !== PendaftaranDetail::STATUS_MENUNGGU_KEPUTUSAN) {
                    throw new RuntimeException('Keputusan hanya dapat diproses saat pendaftaran berstatus Menunggu Keputusan.');
                }

                if (! $latestObs || $latestObs->status !== Observasi::STATUS_SELESAI) {
                    throw new RuntimeException('Keputusan hanya dapat dilakukan jika observasi terbaru sudah selesai.');
                }

                // If Diterima, kelompok_final must be set to A or B
                if ($status === PendaftaranDetail::KEPUTUSAN_DITERIMA) {
                    if (! in_array($lockedDetail->kelompok_final, ['A', 'B'], true)) {
                        throw new RuntimeException('Kelompok final harus ditetapkan sebelum calon siswa dinyatakan diterima.');
                    }
                }
            } elseif ($status === PendaftaranDetail::KEPUTUSAN_MENGUNDURKAN_DIRI) {
                // Condition 1: observasi selesai and status menunggu_keputusan
                $cond1 = ($lockedDetail->status === PendaftaranDetail::STATUS_MENUNGGU_KEPUTUSAN && $latestObs && $latestObs->status === Observasi::STATUS_SELESAI);

                // Condition 2: latest obs is tidak_hadir and H-3 deadline passed
                $cond2 = false;
                if ($latestObs && $latestObs->status === Observasi::STATUS_TIDAK_HADIR) {
                    $pendaftaran = $lockedDetail->pendaftaran;
                    if ($pendaftaran && $pendaftaran->tanggal_mpls) {
                        $deadline = Carbon::parse($pendaftaran->tanggal_mpls)->subDays(3)->endOfDay();
                        if (now()->greaterThan($deadline)) {
                            $cond2 = true;
                        }
                    }
                }

                if (! $cond1 && ! $cond2) {
                    throw new RuntimeException('Pengunduran diri hanya dapat dilakukan setelah observasi selesai, atau jika siswa tidak hadir observasi dan batas H-3 penjadwalan ulang sudah terlampaui.');
                }
            } else {
                throw new RuntimeException('Status keputusan tidak valid.');
            }

            // Update PendaftaranDetail process status
            // Jika diterima, tidak_diterima, atau mengundurkan_diri -> keputusan_selesai
            // Jika perlu_tindak_lanjut -> tetap menunggu_keputusan
            $processStatus = in_array($status, [
                PendaftaranDetail::KEPUTUSAN_DITERIMA,
                PendaftaranDetail::KEPUTUSAN_TIDAK_DITERIMA,
                PendaftaranDetail::KEPUTUSAN_MENGUNDURKAN_DIRI,
            ], true) ? PendaftaranDetail::STATUS_KEPUTUSAN_SELESAI : PendaftaranDetail::STATUS_MENUNGGU_KEPUTUSAN;

            $lockedDetail->update([
                'status' => $processStatus,
                'keputusan_status' => $status,
                'keputusan_catatan' => $catatan,
                'keputusan_alasan' => $alasan,
                'keputusan_diputuskan_oleh' => $decidedByUserId,
                'keputusan_diputuskan_at' => now(),
            ]);

            // Create Decision History
            KeputusanPendaftaran::create([
                'pendaftaran_detail_id' => $lockedDetail->id,
                'status' => $status,
                'catatan' => $catatan,
                'alasan' => $alasan,
                'decided_by' => $decidedByUserId,
                'decided_at' => now(),
            ]);

            if ($status === PendaftaranDetail::KEPUTUSAN_TIDAK_DITERIMA) {
                $lockedDetail = $this->finalEnrollment->transition($lockedDetail, PendaftaranDetail::FINAL_PENDAFTARAN_TIDAK_DILANJUTKAN, FinalEnrollmentService::SOURCE_ADMISSION_DECISION, $decidedByUserId, $alasan);
            } elseif ($status === PendaftaranDetail::KEPUTUSAN_MENGUNDURKAN_DIRI) {
                $lockedDetail = $this->finalEnrollment->transition($lockedDetail, PendaftaranDetail::FINAL_MENGUNDURKAN_DIRI, FinalEnrollmentService::SOURCE_ADMISSION_DECISION, $decidedByUserId, $alasan);
            }

            return $lockedDetail->fresh();
        });
    }
}
