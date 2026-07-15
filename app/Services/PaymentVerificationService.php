<?php

namespace App\Services;

use App\Models\Pembayaran;
use App\Models\PendaftaranDetail;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class PaymentVerificationService
{
    public function __construct(private readonly FinalEnrollmentService $finalEnrollment) {}

    /** @return array{payment: Pembayaran, detail: PendaftaranDetail, status: string} */
    public function verify(Pembayaran $pembayaran, string $status, ?string $catatanAdmin, int $actorId): array
    {
        return DB::transaction(function () use ($pembayaran, $status, $catatanAdmin, $actorId) {
            $lockedPayment = Pembayaran::whereKey($pembayaran->id)->lockForUpdate()->firstOrFail();
            $detail = PendaftaranDetail::whereKey($lockedPayment->pendaftaran_detail_id)->lockForUpdate()->firstOrFail();

            if (! $lockedPayment->bukti_bayar) {
                throw new RuntimeException('Bukti pembayaran belum tersedia.');
            }
            if (! $detail->isKeputusanDiterima() || ! $detail->isFinalDalamProses()) {
                throw new RuntimeException('Pembayaran hanya dapat diverifikasi untuk calon siswa yang diterima dan masih dalam proses daftar ulang.');
            }
            if ($lockedPayment->isLunas()) {
                throw new RuntimeException('Pembayaran lunas bersifat terminal dan tidak dapat diverifikasi ulang.');
            }

            if ($status === Pembayaran::STATUS_LUNAS) {
                if (! $lockedPayment->isMenungguVerifikasi()) {
                    throw new RuntimeException('Hanya pembayaran menunggu verifikasi yang dapat dinyatakan lunas.');
                }
                $lockedPayment->update([
                    'status' => Pembayaran::STATUS_LUNAS,
                    'catatan_admin' => null,
                    'verified_by' => $actorId,
                    'verified_at' => now(),
                ]);
                $detail->setRelation('pembayaran', $lockedPayment->fresh());
                $detail = $this->finalEnrollment->transition(
                    $detail,
                    PendaftaranDetail::FINAL_SISWA_RESMI_TERDAFTAR,
                    FinalEnrollmentService::SOURCE_PAYMENT_VERIFIED,
                    $actorId,
                );
            } elseif ($status === Pembayaran::STATUS_DITOLAK) {
                if (! $lockedPayment->isMenungguVerifikasi()) {
                    throw new RuntimeException('Pembayaran ditolak harus diunggah ulang sebelum dapat diverifikasi kembali.');
                }
                if (! filled($catatanAdmin)) {
                    throw new RuntimeException('Catatan penolakan pembayaran wajib diisi.');
                }
                $lockedPayment->update([
                    'status' => Pembayaran::STATUS_DITOLAK,
                    'catatan_admin' => $catatanAdmin,
                    'verified_by' => $actorId,
                    'verified_at' => now(),
                ]);
            } else {
                throw new RuntimeException('Status verifikasi pembayaran tidak valid.');
            }

            return [
                'payment' => $lockedPayment->fresh(['verifiedBy']),
                'detail' => $detail->fresh(['siswa.user', 'pendaftaran', 'pembayaran']),
                'status' => $status,
            ];
        });
    }
}
