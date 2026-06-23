<?php

it('encodes admin chart data safely for the javascript context', function () {
    $dashboard = file_get_contents(__DIR__.'/../../resources/views/admin/dashboard.blade.php');

    expect($dashboard)->not->toContain('{!!')
        ->and($dashboard)->toContain('Illuminate\Support\Js::from');
});
