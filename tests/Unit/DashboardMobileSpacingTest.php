<?php

it('keeps enough space between the floating dashboard navbar and mobile content', function () {
    $layout = file_get_contents(__DIR__.'/../../resources/views/layouts/app.blade.php');
    $styles = file_get_contents(__DIR__.'/../../resources/css/app.css');

    expect($layout)
        ->toContain('id="mainScrollArea"')
        ->toContain('p-3 pb-12 pt-24 sm:p-6 sm:pb-16 sm:pt-24')
        ->and($styles)
        ->toContain('@apply bg-white/95 backdrop-blur-md rounded-xl mx-4 mt-3 py-3 px-6;');
});
