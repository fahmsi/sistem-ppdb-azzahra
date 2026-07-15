<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KeputusanPendaftaran extends Model
{
    protected $table = 'spmb_keputusan_pendaftaran';

    protected $fillable = [
        'pendaftaran_detail_id',
        'status',
        'catatan',
        'alasan',
        'decided_by',
        'decided_at',
    ];

    protected $casts = [
        'decided_at' => 'datetime',
    ];

    /**
     * The registration detail associated with this decision history.
     */
    public function pendaftaranDetail(): BelongsTo
    {
        return $this->belongsTo(PendaftaranDetail::class, 'pendaftaran_detail_id');
    }

    /**
     * The user (admin) who made this decision.
     */
    public function decidedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'decided_by');
    }
}
