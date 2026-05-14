<?php

namespace App\Observers;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;

class ActivityLogObserver
{
    /**
     * Handle the "created" event.
     */
    public function created(Model $model): void
    {
        // Skip logging for ActivityLog itself to prevent recursion
        if ($model instanceof ActivityLog) {
            return;
        }

        ActivityLog::log(
            'created',
            $model,
            class_basename($model) . ' baru telah dibuat.'
        );
    }

    /**
     * Handle the "updated" event.
     */
    public function updated(Model $model): void
    {
        if ($model instanceof ActivityLog) {
            return;
        }

        $changes = $model->getChanges();
        $original = collect($model->getOriginal())
            ->only(array_keys($changes))
            ->toArray();

        // Remove password and timestamps from log
        unset($changes['password'], $changes['updated_at'], $changes['remember_token']);
        unset($original['password'], $original['updated_at'], $original['remember_token']);

        if (empty($changes)) {
            return;
        }

        ActivityLog::log(
            'updated',
            $model,
            class_basename($model) . ' telah diperbarui.',
            ['old' => $original, 'new' => $changes]
        );
    }

    /**
     * Handle the "deleted" event.
     */
    public function deleted(Model $model): void
    {
        if ($model instanceof ActivityLog) {
            return;
        }

        ActivityLog::log(
            'deleted',
            $model,
            class_basename($model) . ' telah dihapus.'
        );
    }
}
