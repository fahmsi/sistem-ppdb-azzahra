<?php

use Carbon\Carbon;

test('aplikasi menggunakan tanggal dan waktu Indonesia', function () {
    $date = Carbon::create(2026, 7, 2, 14, 30, 0, config('app.timezone'));

    expect(config('app.timezone'))->toBe('Asia/Jakarta')
        ->and(config('app.locale'))->toBe('id')
        ->and($date->timezoneName)->toBe('Asia/Jakarta')
        ->and($date->translatedFormat('d F Y, H:i'))->toBe('02 Juli 2026, 14:30');
});
