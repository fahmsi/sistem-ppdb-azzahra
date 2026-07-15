<?php

namespace App\Services;

use App\Models\Observasi;
use App\Models\Pendaftaran;
use App\Models\PendaftaranDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class ObservationSchedulingService
{
    /**
     * Create the first (or next) observation schedule for a registration detail.
     *
     * @param  array  $data  { scheduled_at: string|Carbon, catatan_jadwal?: string }
     * @param  int  $scheduledById  Auth user ID
     */
    public function schedule(PendaftaranDetail $detail, array $data, int $scheduledById): Observasi
    {
        return DB::transaction(function () use ($detail, $data, $scheduledById): Observasi {
            // Lock the detail row to prevent race conditions
            $detail = PendaftaranDetail::query()
                ->whereKey($detail->id)
                ->lockForUpdate()
                ->firstOrFail();

            $pendaftaran = $detail->pendaftaran;

            if (! $detail->isAdministrasiLengkap()) {
                throw new RuntimeException('Jadwal observasi hanya dapat dibuat setelah administrasi dinyatakan lengkap.');
            }

            if (! $pendaftaran->tanggal_mpls) {
                throw new RuntimeException('Tanggal MPLS belum dikonfigurasi. Hubungi admin untuk mengaturnya terlebih dahulu.');
            }

            // Check no active observation exists
            $hasActive = Observasi::where('pendaftaran_detail_id', $detail->id)
                ->whereIn('status', [
                    Observasi::STATUS_DIJADWALKAN,
                    Observasi::STATUS_HADIR,
                    Observasi::STATUS_TIDAK_HADIR,
                ])
                ->lockForUpdate()
                ->exists();

            if ($hasActive) {
                throw new RuntimeException('Masih ada observasi yang aktif atau sedang berlangsung untuk pendaftaran ini.');
            }

            // Check no completed observation exists
            $hasCompleted = Observasi::where('pendaftaran_detail_id', $detail->id)
                ->where('status', Observasi::STATUS_SELESAI)
                ->exists();

            if ($hasCompleted) {
                throw new RuntimeException('Observasi untuk pendaftaran ini sudah selesai dan tidak dapat dijadwalkan ulang.');
            }

            $scheduledAt = Carbon::parse($data['scheduled_at']);

            $this->validateScheduleWindow($scheduledAt, $pendaftaran);

            // Determine attempt number
            $latestAttempt = Observasi::where('pendaftaran_detail_id', $detail->id)
                ->max('attempt_number') ?? 0;

            return Observasi::create([
                'pendaftaran_detail_id' => $detail->id,
                'attempt_number' => $latestAttempt + 1,
                'scheduled_at' => $scheduledAt,
                'status' => Observasi::STATUS_DIJADWALKAN,
                'scheduled_by' => $scheduledById,
            ]);
        });
    }

    /**
     * Reschedule an existing observation: mark old as rescheduled, create new attempt.
     *
     * @param  Observasi  $observasi  The current (latest) observation to reschedule
     * @param  array  $data  { scheduled_at: string|Carbon, reschedule_reason: string }
     * @param  int  $rescheduledById  Auth user ID
     * @return Observasi The newly created observation attempt
     */
    public function reschedule(Observasi $observasi, array $data, int $rescheduledById): Observasi
    {
        return DB::transaction(function () use ($observasi, $data, $rescheduledById): Observasi {
            // Lock both the observation and the related detail
            $observasi = Observasi::query()
                ->whereKey($observasi->id)
                ->lockForUpdate()
                ->firstOrFail();

            $detail = PendaftaranDetail::query()
                ->whereKey($observasi->pendaftaran_detail_id)
                ->lockForUpdate()
                ->firstOrFail();

            $pendaftaran = $detail->pendaftaran;

            if (! $pendaftaran->tanggal_mpls) {
                throw new RuntimeException('Tanggal MPLS belum dikonfigurasi. Penjadwalan ulang tidak dapat dilakukan.');
            }

            // Guard: reschedule deadline
            if ($this->isPastRescheduleDeadline($pendaftaran)) {
                $deadline = $this->getDeadline($pendaftaran)->format('d/m/Y');
                throw new RuntimeException("Batas waktu penjadwalan ulang ({$deadline}) telah terlewat. Tidak dapat menjadwalkan ulang setelah H-3 MPLS.");
            }

            if (! $observasi->canBeRescheduled()) {
                throw new RuntimeException('Observasi ini tidak dapat dijadwalkan ulang (status: '.$observasi->status.').');
            }

            // Confirm this is the latest attempt for the registration
            $latestAttempt = Observasi::where('pendaftaran_detail_id', $detail->id)
                ->max('attempt_number');

            if ($observasi->attempt_number !== $latestAttempt) {
                throw new RuntimeException('Hanya observasi terbaru yang dapat dijadwalkan ulang.');
            }

            $scheduledAt = Carbon::parse($data['scheduled_at']);
            $this->validateScheduleWindow($scheduledAt, $pendaftaran);

            // Mark old observation as rescheduled
            $observasi->update([
                'status' => Observasi::STATUS_DIJADWALKAN_ULANG,
                'reschedule_reason' => $data['reschedule_reason'],
                'rescheduled_by' => $rescheduledById,
            ]);

            // Create new attempt
            return Observasi::create([
                'pendaftaran_detail_id' => $detail->id,
                'attempt_number' => $latestAttempt + 1,
                'scheduled_at' => $scheduledAt,
                'status' => Observasi::STATUS_DIJADWALKAN,
                'rescheduled_from_id' => $observasi->id,
                'scheduled_by' => $rescheduledById,
                'rescheduled_by' => $rescheduledById,
            ]);
        });
    }

    /**
     * Get the last allowed reschedule date/time for a pendaftaran.
     * That is the end of the day 3 days before MPLS.
     */
    public function getDeadline(Pendaftaran $pendaftaran): Carbon
    {
        return $pendaftaran->tanggal_mpls
            ->copy()
            ->subDays(3)
            ->endOfDay();
    }

    /**
     * Return true if today is past the reschedule deadline.
     */
    public function isPastRescheduleDeadline(Pendaftaran $pendaftaran): bool
    {
        if (! $pendaftaran->tanggal_mpls) {
            return false; // no MPLS date configured, treat as no deadline
        }

        return now()->isAfter($this->getDeadline($pendaftaran));
    }

    /**
     * Validate that a scheduled_at datetime fits within the allowed window:
     * - After now
     * - Before tanggal_mpls
     *
     * @throws RuntimeException
     */
    public function validateScheduleWindow(Carbon $scheduledAt, Pendaftaran $pendaftaran): void
    {
        if ($scheduledAt->isPast()) {
            throw new RuntimeException('Jadwal observasi harus setelah waktu saat ini.');
        }

        if ($pendaftaran->tanggal_mpls && $scheduledAt->greaterThanOrEqualTo($pendaftaran->tanggal_mpls->startOfDay())) {
            throw new RuntimeException('Jadwal observasi harus sebelum tanggal mulai MPLS ('.$pendaftaran->tanggal_mpls->format('d/m/Y').').');
        }
    }
}
