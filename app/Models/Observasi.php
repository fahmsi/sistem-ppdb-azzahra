<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Observasi extends Model
{
    protected $table = 'spmb_observasi';

    /**
     * Observation status constants.
     */
    public const STATUS_DIJADWALKAN = 'dijadwalkan';

    public const STATUS_HADIR = 'hadir';

    public const STATUS_TIDAK_HADIR = 'tidak_hadir';

    public const STATUS_DIJADWALKAN_ULANG = 'dijadwalkan_ulang';

    public const STATUS_SELESAI = 'selesai';

    public const STATUS_DIBATALKAN = 'dibatalkan';

    protected $fillable = [
        'pendaftaran_detail_id',
        'attempt_number',
        'scheduled_at',
        'status',
        'rescheduled_from_id',
        'reschedule_reason',
        'attended_at',
        'completed_at',
        'tinggi_badan_cm',
        'berat_badan_kg',
        'catatan_wawancara_orang_tua',
        'catatan_aktivitas_anak',
        'catatan_kesiapan_anak',
        'membutuhkan_dukungan_khusus',
        'catatan_kebutuhan_dukungan_khusus',
        'catatan_sekolah',
        'scheduled_by',
        'observed_by',
        'rescheduled_by',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'attended_at' => 'datetime',
        'completed_at' => 'datetime',
        'membutuhkan_dukungan_khusus' => 'boolean',
        'tinggi_badan_cm' => 'decimal:2',
        'berat_badan_kg' => 'decimal:2',
    ];

    // =========================================================================
    // Relations
    // =========================================================================

    public function pendaftaranDetail(): BelongsTo
    {
        return $this->belongsTo(PendaftaranDetail::class, 'pendaftaran_detail_id');
    }

    public function scheduledBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'scheduled_by');
    }

    public function observedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'observed_by');
    }

    public function rescheduledBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'rescheduled_by');
    }

    public function rescheduledFrom(): BelongsTo
    {
        return $this->belongsTo(self::class, 'rescheduled_from_id');
    }

    // =========================================================================
    // State Checks
    // =========================================================================

    public function isDijadwalkan(): bool
    {
        return $this->status === self::STATUS_DIJADWALKAN;
    }

    public function isHadir(): bool
    {
        return $this->status === self::STATUS_HADIR;
    }

    public function isTidakHadir(): bool
    {
        return $this->status === self::STATUS_TIDAK_HADIR;
    }

    public function isDijadwalkanUlang(): bool
    {
        return $this->status === self::STATUS_DIJADWALKAN_ULANG;
    }

    public function isSelesai(): bool
    {
        return $this->status === self::STATUS_SELESAI;
    }

    public function isDibatalkan(): bool
    {
        return $this->status === self::STATUS_DIBATALKAN;
    }

    /**
     * Whether this observation can be rescheduled (from a status perspective).
     */
    public function canBeRescheduled(): bool
    {
        return in_array($this->status, [
            self::STATUS_DIJADWALKAN,
            self::STATUS_TIDAK_HADIR,
        ], true);
    }

    /**
     * Whether results can be entered for this observation.
     */
    public function canBeCompleted(): bool
    {
        return in_array($this->status, [
            self::STATUS_HADIR,
            self::STATUS_DIJADWALKAN, // allow direct completion if already present
        ], true);
    }

    public function canHadir(): bool
    {
        return $this->isDijadwalkan();
    }

    public function canTidakHadir(): bool
    {
        return $this->isDijadwalkan();
    }
}
