<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $table = 'psb_pembayaran';

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
}
