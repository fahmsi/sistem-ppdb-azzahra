<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PendaftaranDetail extends Model
{
    protected $table = 'spmb_pendaftaran_detail';

    /**
     * Registration status constants.
     */
    public const STATUS_PENDING = 'pending';

    public const STATUS_DITERIMA = 'diterima';

    public const STATUS_MENUNGGU_VERIFIKASI = 'menunggu_verifikasi';

    public const STATUS_DITOLAK = 'ditolak';

    public const STATUS_PERLU_REVISI = 'perlu_revisi';

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
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tanggal_acuan_usia' => 'date',
        'kelompok_ditetapkan_at' => 'datetime',
    ];

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
     * Check if registration was accepted.
     */
    public function isDiterima(): bool
    {
        return $this->status === self::STATUS_DITERIMA;
    }

    /**
     * Check if registration was rejected.
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
}
