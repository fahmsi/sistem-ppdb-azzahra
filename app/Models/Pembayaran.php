<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $table = 'psb_pembayaran';

    public const STATUS_PENDING = 'pending';

    public const STATUS_MENUNGGU_VERIFIKASI = 'menunggu_verifikasi';

    public const STATUS_LUNAS = 'lunas';

    public const STATUS_DITOLAK = 'ditolak';

    protected $fillable = [
        'pendaftaran_detail_id',
        'jumlah',
        'bukti_bayar',
        'status',
        'catatan_admin',
    ];

    public function pendaftaranDetail()
    {
        return $this->belongsTo(PendaftaranDetail::class, 'pendaftaran_detail_id');
    }

    public function isMenungguVerifikasi(): bool
    {
        return in_array($this->status, [self::STATUS_PENDING, self::STATUS_MENUNGGU_VERIFIKASI], true);
    }

    public function isLunas(): bool
    {
        return $this->status === self::STATUS_LUNAS;
    }

    public function isDitolak(): bool
    {
        return $this->status === self::STATUS_DITOLAK;
    }
}
