<?php

it('keeps the shared dashboard footer compact responsive and separate from the landing footer', function () {
    $dashboardLayout = file_get_contents(__DIR__.'/../../resources/views/layouts/app.blade.php');
    $landingLayout = file_get_contents(__DIR__.'/../../resources/views/app/layouts/app.blade.php');

    expect(substr_count($dashboardLayout, '<footer'))->toBe(1)
        ->and($dashboardLayout)->toContain('<div class="mt-auto pt-5">')
        ->and($dashboardLayout)->toContain('border-t border-[#d9dee3] py-3')
        ->and($dashboardLayout)->toContain('dark:border-[#434463]')
        ->and($dashboardLayout)->toContain('flex min-w-0 flex-col gap-2 sm:flex-row sm:items-center sm:justify-between')
        ->and($dashboardLayout)->toContain('{{ now()->year }}')
        ->and($dashboardLayout)->toContain('PAUD Al-Qur’an Azzahra Depok. All rights reserved.')
        ->and($dashboardLayout)->toContain("route('terms')")
        ->and($dashboardLayout)->toContain('Ketentuan Layanan')
        ->and($dashboardLayout)->toContain("route('privacy')")
        ->and($dashboardLayout)->toContain('Kebijakan Privasi')
        ->and($dashboardLayout)->not->toContain("date('Y')")
        ->and($landingLayout)->toContain("@include('app.components.footer')");
});
