<?php

it('contains the theme toggle markup in the admin layout', function () {
    $layout = file_get_contents(__DIR__ . '/../../resources/views/layouts/app.blade.php');

    expect($layout)->toContain('id="themeToggleBtn"')
        ->and($layout)->toContain('id="themeDropdown"')
        ->and($layout)->toContain('data-theme-value="light"')
        ->and($layout)->toContain('data-theme-value="dark"')
        ->and($layout)->toContain('data-theme-value="system"');
});
