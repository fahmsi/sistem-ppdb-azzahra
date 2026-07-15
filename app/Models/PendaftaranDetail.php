<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PendaftaranDetail extends Model
{
    protected $table = 'spmb_pendaftaran_detail';

    /**
     * Registration status constants.
     */
    public const STATUS_PENDING = 'pending';

    /** @deprecated Use keputusan_status instead for new logic */
    public const STATUS_DITERIMA = 'diterima';

    public const STATUS_MENUNGGU_VERIFIKASI = 'menunggu_verifikasi';

    /** @deprecated Use keputusan_status instead for new logic */
    public const STATUS_DITOLAK = 'ditolak';

    public const STATUS_PERLU_REVISI = 'perlu_revisi';

    public const STATUS_ADMINISTRASI_LENGKAP = 'administrasi_lengkap';

    public const STATUS_MENUNGGU_KEPUTUSAN = 'menunggu_keputusan';

    public const STATUS_KEPUTUSAN_SELESAI = 'keputusan_selesai';

    // Decision status snapshot constants
    public const KEPUTUSAN_DITERIMA = 'diterima';

    public const KEPUTUSAN_TIDAK_DITERIMA = 'tidak_diterima';

    public const KEPUTUSAN_PERLU_TINDAK_LANJUT = 'perlu_tindak_lanjut';

    public const KEPUTUSAN_MENGUNDURKAN_DIRI = 'mengundurkan_diri';

    public const FINAL_DALAM_PROSES = 'dalam_proses';
    public const FINAL_SISWA_RESMI_TERDAFTAR = 'siswa_resmi_terdaftar';
    public const FINAL_PENDAFTARAN_TIDAK_DILANJUTKAN = 'pendaftaran_tidak_dilanjutkan';
    public const FINAL_MENGUNDURKAN_DIRI = 'mengundurkan_diri';

    protected $fillable = [
        'siswa_id',
        'pendaftaran_id',
        'no_pendaftaran',
        'status',
        'notifikasi',
        'tanggal_acuan_usia',
        'usia_bulan_saat_acuan',
        'kelompok_rekomendasi',
        'kelompok_final',
        'kelompok_ditetapkan_oleh',
        'kelompok_ditetapkan_at',
        'keputusan_status',
        'keputusan_catatan',
        'keputusan_alasan',
        'keputusan_diputuskan_oleh',
        'keputusan_diputuskan_at',
        'final_status',
        'final_alasan',
        'final_catatan',
        'final_ditetapkan_oleh',
        'final_ditetapkan_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tanggal_acuan_usia' => 'date',
        'kelompok_ditetapkan_at' => 'datetime',
        'keputusan_diputuskan_at' => 'datetime',
        'final_ditetapkan_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $detail): void {
            $detail->final_status ??= self::FINAL_DALAM_PROSES;
        });
    }

    /**
     * All observation attempts for this registration.
     */
    public function observasis(): HasMany
    {
        return $this->hasMany(Observasi::class, 'pendaftaran_detail_id');
    }

    /**
     * The latest/most recent observation attempt.
     */
    public function observasiTerbaru(): HasOne
    {
        return $this->hasOne(Observasi::class, 'pendaftaran_detail_id')->latestOfMany();
    }

    /**
     * User who set the final group.
     */
    public function kelompokDitetapkanOleh(): BelongsTo
    {
        return $this->belongsTo(User::class, 'kelompok_ditetapkan_oleh');
    }

    /**
     * The Siswa this detail belongs to.
     */
    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'siswa_id')->withTrashed();
    }

    /**
     * The Pendaftaran period this detail belongs to.
     */
    public function pendaftaran(): BelongsTo
    {
        return $this->belongsTo(Pendaftaran::class, 'pendaftaran_id');
    }

    /**
     * Check if registration is still pending.
     */
    public function pembayaran(): HasOne
    {
        return $this->hasOne(Pembayaran::class, 'pendaftaran_detail_id');
    }

    public function getNomorPendaftaranAttribute(): string
    {
        return $this->no_pendaftaran ?: sprintf('SPMB-%s-%04d', $this->created_at?->format('Y') ?? now()->year, $this->id);
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * @deprecated Legacy compatibility only – do not use in new runtime flows.
     * Checks the old process-status column value 'diterima'.
     */
    public function isDiterima(): bool
    {
        return $this->status === self::STATUS_DITERIMA;
    }

    /**
     * @deprecated Legacy compatibility only – do not use in new runtime flows.
     * Checks the old process-status column value 'ditolak'.
     */
    public function isDitolak(): bool
    {
        return $this->status === self::STATUS_DITOLAK;
    }

    /**
     * Check if registration needs revision.
     */
    public function isPerluRevisi(): bool
    {
        return $this->status === self::STATUS_PERLU_REVISI;
    }

    /**
     * Check if administration documents are complete.
     */
    public function isAdministrasiLengkap(): bool
    {
        return $this->status === self::STATUS_ADMINISTRASI_LENGKAP;
    }

    /**
     * Check if registration is awaiting school decision.
     */
    public function isMenungguKeputusan(): bool
    {
        return $this->status === self::STATUS_MENUNGGU_KEPUTUSAN;
    }

    /**
     * User who decided the admission.
     */
    public function keputusanDiputuskanOleh(): BelongsTo
    {
        return $this->belongsTo(User::class, 'keputusan_diputuskan_oleh');
    }

    /**
     * History of admission decisions.
     */
    public function keputusanHistories(): HasMany
    {
        return $this->hasMany(KeputusanPendaftaran::class, 'pendaftaran_detail_id');
    }

    /**
     * The latest decision history.
     */
    public function keputusanTerbaru(): HasOne
    {
        return $this->hasOne(KeputusanPendaftaran::class, 'pendaftaran_detail_id')->latestOfMany();
    }

    public function finalDitetapkanOleh(): BelongsTo
    {
        return $this->belongsTo(User::class, 'final_ditetapkan_oleh');
    }

    public function finalisasiHistories(): HasMany
    {
        return $this->hasMany(FinalisasiPendaftaran::class, 'pendaftaran_detail_id');
    }

    public function finalisasiTerbaru(): HasOne
    {
        return $this->hasOne(FinalisasiPendaftaran::class, 'pendaftaran_detail_id')->latestOfMany('finalized_at');
    }

    public function hasDecision(): bool
    {
        return ! empty($this->keputusan_status);
    }

    /**
     * True only when ALL three decision columns are set and the decision is 'diterima'.
     * Does NOT fall back to the legacy status column.
     */
    public function isKeputusanDiterima(): bool
    {
        return $this->status === self::STATUS_KEPUTUSAN_SELESAI
            && $this->keputusan_status === self::KEPUTUSAN_DITERIMA
            && $this->keputusan_diputuskan_at !== null;
    }

    /**
     * True only when ALL three decision columns are set and the decision is 'tidak_diterima'.
     * Does NOT fall back to the legacy status column.
     */
    public function isKeputusanTidakDiterima(): bool
    {
        return $this->status === self::STATUS_KEPUTUSAN_SELESAI
            && $this->keputusan_status === self::KEPUTUSAN_TIDAK_DITERIMA
            && $this->keputusan_diputuskan_at !== null;
    }

    public function isPerluTindakLanjut(): bool
    {
        return $this->keputusan_status === self::KEPUTUSAN_PERLU_TINDAK_LANJUT;
    }

    public function isMengundurkanDiri(): bool
    {
        return $this->keputusan_status === self::KEPUTUSAN_MENGUNDURKAN_DIRI;
    }

    public function isKeputusanFinal(): bool
    {
        return in_array($this->keputusan_status, [
            self::KEPUTUSAN_DITERIMA,
            self::KEPUTUSAN_TIDAK_DITERIMA,
            self::KEPUTUSAN_MENGUNDURKAN_DIRI,
        ], true);
    }

    public function isFinalDalamProses(): bool { return $this->final_status === self::FINAL_DALAM_PROSES; }

    public function isSiswaResmiTerdaftar(): bool
    {
        return $this->final_status === self::FINAL_SISWA_RESMI_TERDAFTAR
            && $this->final_ditetapkan_at !== null
            && $this->isKeputusanDiterima()
            && $this->pembayaran?->isLunas();
    }

    public function isPendaftaranTidakDilanjutkan(): bool { return $this->final_status === self::FINAL_PENDAFTARAN_TIDAK_DILANJUTKAN; }
    public function isFinalMengundurkanDiri(): bool { return $this->final_status === self::FINAL_MENGUNDURKAN_DIRI; }
    public function isFinalTerminal(): bool { return ! $this->isFinalDalamProses(); }

    public function canSubmitPayment(): bool
    {
        return $this->isKeputusanDiterima()
            && $this->isFinalDalamProses()
            && ! $this->isSiswaResmiTerdaftar()
            && ! $this->pembayaran?->isLunas();
    }

    public function canPrintOfficialCard(): bool { return $this->isSiswaResmiTerdaftar(); }

    /**
     * Check if the registration is in an active non-final state
     * (admin verification, observation, or follow-up stages).
     */
    public function isActive(): bool
    {
        return $this->isFinalDalamProses();
    }
}
