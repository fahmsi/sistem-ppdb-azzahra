<?php

use App\Models\ActivityLog;

test('activity log action colors include dark mode friendly classes', function () {
    $log = new ActivityLog;
    $log->action = 'created';

    expect($log->getActionColorAttribute())->toContain('dark:');
});
