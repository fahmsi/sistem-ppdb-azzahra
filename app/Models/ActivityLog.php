<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'user_name',
        'action',
        'target_type',
        'target_id',
        'target_label',
        'description',
        'properties',
        'ip_address',
        'user_agent',
    ];

    protected function casts(): array
    {
        return [
            'properties' => 'array',
        ];
    }

    /**
     * The user who performed the action.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the target model (polymorphic manual lookup).
     */
    public function target(): ?Model
    {
        if ($this->target_type && $this->target_id) {
            return $this->target_type::find($this->target_id);
        }

        return null;
    }

    /**
     * Log an activity.
     */
    public static function log(
        string $action,
        ?Model $target = null,
        ?string $description = null,
        ?array $properties = null
    ): self {
        $user = auth()->user();
        $request = request();

        return static::create([
            'user_id'      => $user?->id,
            'user_name'    => $user?->name ?? 'System',
            'action'       => $action,
            'target_type'  => $target ? get_class($target) : null,
            'target_id'    => $target?->getKey(),
            'target_label' => $target ? ($target->nama ?? $target->name ?? $target->title ?? "#{$target->getKey()}") : null,
            'description'  => $description,
            'properties'   => $properties,
            'ip_address'   => $request?->ip(),
            'user_agent'   => $request?->userAgent(),
        ]);
    }

    /**
     * Human-readable action label.
     */
    public function getActionLabelAttribute(): string
    {
        return match ($this->action) {
            'created'  => 'Membuat',
            'updated'  => 'Mengubah',
            'deleted'  => 'Menghapus',
            'soft_deleted' => 'Menghapus Sementara',
            'restored' => 'Memulihkan',
            'force_deleted' => 'Menghapus Permanen',
            'payment_uploaded' => 'Upload Pembayaran',
            'payment_reuploaded' => 'Upload Ulang Pembayaran',
            'login'    => 'Login',
            'logout'   => 'Logout',
            'verified' => 'Memverifikasi',
            'accepted' => 'Menerima',
            'rejected' => 'Menolak',
            'revision' => 'Minta Revisi',
            default    => ucfirst($this->action),
        };
    }

    /**
     * Human-readable target type label.
     */
    public function getTargetTypeLabelAttribute(): string
    {
        if (!$this->target_type) {
            return '-';
        }

        return match ($this->target_type) {
            'App\Models\User'              => 'User',
            'App\Models\Siswa'             => 'Data Siswa',
            'App\Models\Pendaftaran'       => 'Gelombang',
            'App\Models\PendaftaranDetail' => 'Pendaftaran',
            'App\Models\Pembayaran'        => 'Pembayaran',
            'App\Models\Setting'           => 'Pengaturan',
            default => class_basename($this->target_type),
        };
    }

    /**
     * Get a color class for the action badge.
     */
    public function getActionColorAttribute(): string
    {
        return match ($this->action) {
            'created' => 'bg-blue-100 text-blue-700 border border-blue-200 dark:bg-blue-500/15 dark:text-blue-300 dark:border-blue-400/20',
            'updated' => 'bg-yellow-100 text-yellow-700 border border-yellow-200 dark:bg-amber-500/15 dark:text-amber-300 dark:border-amber-400/20',
            'deleted' => 'bg-red-100 text-red-700 border border-red-200 dark:bg-red-500/15 dark:text-red-300 dark:border-red-400/20',
            'soft_deleted' => 'bg-orange-100 text-orange-700 border border-orange-200 dark:bg-orange-500/15 dark:text-orange-300 dark:border-orange-400/20',
            'restored' => 'bg-emerald-100 text-emerald-700 border border-emerald-200 dark:bg-emerald-500/15 dark:text-emerald-300 dark:border-emerald-400/20',
            'force_deleted' => 'bg-red-100 text-red-700 border border-red-200 dark:bg-red-500/15 dark:text-red-300 dark:border-red-400/20',
            'payment_uploaded', 'payment_reuploaded' => 'bg-blue-100 text-blue-700 border border-blue-200 dark:bg-blue-500/15 dark:text-blue-300 dark:border-blue-400/20',
            'login' => 'bg-green-100 text-green-700 border border-green-200 dark:bg-emerald-500/15 dark:text-emerald-300 dark:border-emerald-400/20',
            'logout' => 'bg-gray-100 text-gray-700 border border-gray-200 dark:bg-slate-500/15 dark:text-slate-300 dark:border-slate-400/20',
            'verified', 'accepted' => 'bg-emerald-100 text-emerald-700 border border-emerald-200 dark:bg-emerald-500/15 dark:text-emerald-300 dark:border-emerald-400/20',
            'rejected' => 'bg-red-100 text-red-700 border border-red-200 dark:bg-red-500/15 dark:text-red-300 dark:border-red-400/20',
            'revision' => 'bg-orange-100 text-orange-700 border border-orange-200 dark:bg-orange-500/15 dark:text-orange-300 dark:border-orange-400/20',
            default => 'bg-gray-100 text-gray-700 border border-gray-200 dark:bg-slate-500/15 dark:text-slate-300 dark:border-slate-400/20',
        };
    }
}
