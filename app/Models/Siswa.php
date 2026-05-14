<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Siswa extends Model
{
    use HasFactory;

    protected $table = 'psb_siswa';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'nama',
        'nisn',
        'nis',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'agama',
        'anak_ke',
        'jumlah_saudara',
        'hobi',
        'cita_cita',
        'no_telpon',
        'jenis_tempat_tinggal',
        'alamat',
        'kelurahan',
        'kecamatan',
        'kota',
        'provinsi',
        'kode_pos',
        'transportasi',
        'no_kk',
        'kepala_keluarga',
        'nama_ayah',
        'nik_ayah',
        'tanggal_lahir_ayah',
        'pendidikan_ayah',
        'pekerjaan_ayah',
        'penghasilan_ayah',
        'nama_ibu',
        'nik_ibu',
        'tanggal_lahir_ibu',
        'pendidikan_ibu',
        'pekerjaan_ibu',
        'penghasilan_ibu',
        'foto',
        'foto_kk',
        'foto_akta',
    ];

    /**
     * The parent (User) who registered this child.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Pendaftaran detail records for this siswa (pivot table).
     */
    public function pendaftaranDetails(): HasMany
    {
        return $this->hasMany(PendaftaranDetail::class, 'siswa_id');
    }

    /**
     * Pendaftaran periods this siswa is enrolled in (many-to-many through pivot).
     */
    public function pendaftarans(): BelongsToMany
    {
        return $this->belongsToMany(
            Pendaftaran::class,
            'psb_pendaftaran_detail',
            'siswa_id',
            'pendaftaran_id'
        )->withPivot('status', 'notifikasi')->withTimestamps();
    }
}
