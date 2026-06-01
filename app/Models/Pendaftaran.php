<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pendaftaran extends Model
{
    use HasFactory;

    protected $table = 'psb_pendaftaran';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'tahun_ajaran',
        'gelombang',
        'kuota',
        'status',
        'tanggal_mulai',
        'tanggal_selesai',
        'gambar',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tanggal_mulai' => 'date',
            'tanggal_selesai' => 'date',
            'kuota' => 'integer',
        ];
    }

    /**
     * Pendaftaran detail records for this period.
     */
    public function pendaftaranDetails(): HasMany
    {
        return $this->hasMany(PendaftaranDetail::class, 'pendaftaran_id');
    }

    /**
     * Siswa enrolled in this registration period (many-to-many through pivot).
     */
    public function siswas(): BelongsToMany
    {
        return $this->belongsToMany(
            Siswa::class,
            'psb_pendaftaran_detail',
            'pendaftaran_id',
            'siswa_id'
        )->withPivot('status', 'notifikasi')->withTimestamps();
    }

    /**
     * Scope: only open registration periods.
     */
    public function scopeOpen(Builder $query): Builder
    {
        return $query->where('status', 'buka');
    }

    /**
     * Check if the registration period is currently open.
     */
    public function isOpen(): bool
    {
        return $this->status === 'buka';
    }

    /**
     * Check if the quota has been reached.
     */
    public function isQuotaFull(): bool
    {
        return $this->pendaftaranDetails()->count() >= $this->kuota;
    }

    /**
     * Accessor: Sisa Kuota
     * Mengembalikan sisa kuota. Jika kuota null/0, anggap tidak terbatas.
     */
    public function getSisaKuotaAttribute(): int
    {
        if (!$this->kuota || $this->kuota <= 0) {
            return 999999; // unlimited
        }
        $sisa = $this->kuota - $this->pendaftaranDetails()->count();
        return $sisa > 0 ? $sisa : 0;
    }

    /**
     * Accessor: Is Penuh
     * Mengembalikan true jika sisa kuota <= 0 dan kuota tidak 0/null
     */
    public function getIsPenuhAttribute(): bool
    {
        if (!$this->kuota || $this->kuota <= 0) {
            return false; // unlimited, never full
        }
        return $this->sisa_kuota <= 0;
    }

    /**
     * Accessor: Is Expired
     * Mereturn boolean true jika tanggal saat ini sudah melewati tanggal_selesai
     */
    public function getIsExpiredAttribute(): bool
    {
        if (!$this->tanggal_selesai) {
            return false;
        }
        return now()->startOfDay()->greaterThan($this->tanggal_selesai->endOfDay());
    }

    /**
     * Accessor: Is Bisa Dipilih
     * Mereturn boolean true jika status gelombang aktif, is_penuh adalah false, dan is_expired adalah false.
     */
    public function getIsBisaDipilihAttribute(): bool
    {
        return $this->isOpen() && !$this->is_penuh && !$this->is_expired;
    }
}
