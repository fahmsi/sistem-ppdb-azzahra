<?php

namespace App\Models;

use App\Services\StudentGroupRecommendationService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Siswa extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'spmb_siswa';

    public const INPUT_SOURCE_ONLINE = 'online';

    public const INPUT_SOURCE_MANUAL_ADMIN = 'manual_admin';

    public const TINGGAL_BERSAMA_ORANG_TUA = 'orang_tua';

    public const TINGGAL_BERSAMA_WALI = 'wali';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'nama',
        'nama_panggilan',
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
        'tinggal_bersama',
        'nama_wali',
        'nik_wali',
        'hubungan_wali',
        'no_telpon_wali',
        'foto_ktp_ayah',
        'foto_ktp_ibu',
        'foto_ktp_wali',
        'created_by_admin_id',
        'input_source',
        'deleted_by',
        'deleted_reason',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tanggal_lahir' => 'date',
        'tanggal_lahir_ayah' => 'date',
        'tanggal_lahir_ibu' => 'date',
    ];

    /**
     * The parent (User) who registered this child.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Admin who manually created this student data, if any.
     */
    public function createdByAdmin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_admin_id');
    }

    /**
     * Admin who soft-deleted this student data, if any.
     */
    public function deletedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'deleted_by');
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
            'spmb_pendaftaran_detail',
            'siswa_id',
            'pendaftaran_id'
        )->withPivot('no_pendaftaran', 'status', 'notifikasi')->withTimestamps();
    }

    /**
     * Boot the model.
     */
    protected static function booted()
    {
        static::forceDeleted(function (Siswa $siswa) {
            foreach (['foto', 'foto_kk', 'foto_akta', 'foto_ktp_ayah', 'foto_ktp_ibu', 'foto_ktp_wali'] as $field) {
                if ($siswa->{$field}) {
                    $disk = ($field === 'foto') ? 'public' : 'local';
                    Storage::disk($disk)->delete($siswa->{$field});
                }
            }
        });

        static::updated(function (Siswa $siswa) {
            if ($siswa->wasChanged('tanggal_lahir')) {
                // Find registrations with status pending, menunggu_verifikasi, perlu_revisi
                $details = $siswa->pendaftaranDetails()
                    ->whereIn('status', [
                        PendaftaranDetail::STATUS_PENDING,
                        PendaftaranDetail::STATUS_MENUNGGU_VERIFIKASI,
                        PendaftaranDetail::STATUS_PERLU_REVISI,
                    ])
                    ->get();

                if ($details->isNotEmpty()) {
                    $service = app(StudentGroupRecommendationService::class);
                    foreach ($details as $detail) {
                        $detail->load('pendaftaran');
                        if ($detail->pendaftaran) {
                            $calc = $service->calculate($siswa->tanggal_lahir, $detail->pendaftaran->tahun_ajaran);
                            $detail->update([
                                'tanggal_acuan_usia' => $calc['tanggal_acuan'],
                                'usia_bulan_saat_acuan' => $calc['usia_bulan'],
                                'kelompok_rekomendasi' => $calc['kelompok_rekomendasi'],
                                'kelompok_final' => null,
                                'kelompok_ditetapkan_oleh' => null,
                                'kelompok_ditetapkan_at' => null,
                            ]);
                        }
                    }
                }
            }
        });
    }
}
