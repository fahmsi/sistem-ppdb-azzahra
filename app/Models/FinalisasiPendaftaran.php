<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FinalisasiPendaftaran extends Model
{
    protected $table = 'spmb_finalisasi_pendaftaran';

    protected $fillable = [
        'pendaftaran_detail_id', 'from_status', 'to_status', 'source',
        'alasan', 'catatan', 'finalized_by', 'finalized_at',
    ];

    protected $casts = ['finalized_at' => 'datetime'];

    public function pendaftaranDetail(): BelongsTo
    {
        return $this->belongsTo(PendaftaranDetail::class, 'pendaftaran_detail_id');
    }

    public function finalizedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'finalized_by');
    }
}
