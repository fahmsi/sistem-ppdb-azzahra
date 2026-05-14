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
}
