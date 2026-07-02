<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PaymentSetting extends Model
{
    public const SINGLETON_ID = 1;

    protected $fillable = [
        'bank_name',
        'account_number',
        'account_holder_name',
        'amount',
        'qris_path',
        'payment_note',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
        ];
    }

    /**
     * Get the single payment configuration record.
     */
    public static function current(): ?self
    {
        return static::query()->find(self::SINGLETON_ID);
    }

    /**
     * Return a public QRIS URL only when the underlying file still exists.
     */
    public function getQrisUrlAttribute(): ?string
    {
        if (! $this->qris_path || ! Storage::disk('public')->exists($this->qris_path)) {
            return null;
        }

        return '/storage/'.ltrim(str_replace('\\', '/', $this->qris_path), '/');
    }

    public function getFormattedAmountAttribute(): string
    {
        return 'Rp '.number_format((float) $this->amount, 0, ',', '.');
    }
}
