<?php

use App\Models\Siswa;
use App\Models\User;
use Illuminate\Support\Facades\Route;

test('landing page remains publicly renderable', function () {
    $this->get(route('home'))
        ->assertOk()
        ->assertSee('PAUD', false);
});

test('each role can render its baseline dashboard', function (string $role, string $routeName, string $semanticContent) {
    $user = User::factory()->create(['role' => $role]);

    $this->actingAs($user)
        ->get(route($routeName))
        ->assertOk()
        ->assertSee($semanticContent, false);
})->with([
    'parent dashboard' => ['parent', 'parent.dashboard', "Assalamu'alaikum"],
    'admin dashboard' => ['admin', 'admin.dashboard', 'Ringkasan SPMB'],
    'super admin dashboard' => ['super_admin', 'admin.dashboard', 'Ringkasan SPMB'],
]);

test('admin sidebar exposes every baseline admin menu and hides super admin menus', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $response = $this->actingAs($admin)
        ->get(route('admin.dashboard'))
        ->assertOk();

    foreach (baselineAdminMenuRoutes() as $label => $routeName) {
        $response
            ->assertSee($label)
            ->assertSee(route($routeName), false);
    }

    foreach (baselineSuperAdminMenuRoutes() as $routeName) {
        $response->assertDontSee(route($routeName), false);
    }
});

test('super admin sidebar includes admin and super admin menus', function () {
    $superAdmin = User::factory()->create(['role' => 'super_admin']);

    $response = $this->actingAs($superAdmin)
        ->get(route('admin.dashboard'))
        ->assertOk();

    foreach (baselineAdminMenuRoutes() as $label => $routeName) {
        $response
            ->assertSee($label)
            ->assertSee(route($routeName), false);
    }

    foreach (baselineSuperAdminMenuRoutes() as $label => $routeName) {
        $response
            ->assertSee($label)
            ->assertSee(route($routeName), false);
    }
});

test('parent sidebar contains only parent destinations before child data exists', function () {
    $parent = User::factory()->create(['role' => 'parent']);

    $response = $this->actingAs($parent)
        ->get(route('parent.dashboard'))
        ->assertOk()
        ->assertSee('Lengkapi Data Anak')
        ->assertSee(route('parent.siswa.create'), false)
        ->assertSee('Daftar Gelombang')
        ->assertSee(route('parent.pendaftaran.index'), false)
        ->assertSee('Status Pendaftaran')
        ->assertSee(route('parent.pendaftaran.status'), false);

    foreach (baselineAdminMenuRoutes() + baselineSuperAdminMenuRoutes() as $routeName) {
        $response->assertDontSee(route($routeName), false);
    }
});

test('parent sidebar links to existing child data after it is created', function () {
    $parent = User::factory()->create(['role' => 'parent']);
    $siswa = Siswa::factory()->for($parent)->create();

    $this->actingAs($parent)
        ->get(route('parent.dashboard'))
        ->assertOk()
        ->assertSee('Data Anak')
        ->assertSee(route('parent.siswa.show', $siswa), false);
});

test('site settings remains visible and renderable for admin roles', function (string $role) {
    $user = User::factory()->create(['role' => $role]);

    $this->actingAs($user)
        ->get(route('admin.settings.index'))
        ->assertOk()
        ->assertSee('Pengaturan Situs')
        ->assertSee(route('admin.settings.update'), false);
})->with([
    'admin' => ['admin'],
    'super admin' => ['super_admin'],
]);

test('baseline content and payment configuration pages remain renderable for admin roles', function (string $routeName, string $semanticContent) {
    $admin = User::factory()->create(['role' => 'admin']);

    $this->actingAs($admin)
        ->get(route($routeName))
        ->assertOk()
        ->assertSee($semanticContent);
})->with([
    'gallery' => ['admin.gallery.index', 'Kelola Gallery'],
    'testimonials' => ['admin.testimonials.index', 'Kelola Testimoni'],
    'payment configuration' => ['admin.payment-settings.edit', 'Konfigurasi Pembayaran'],
]);

test('parent cannot see or open admin features', function () {
    $parent = User::factory()->create(['role' => 'parent']);

    $this->actingAs($parent)
        ->get(route('admin.dashboard'))
        ->assertForbidden();

    $this->actingAs($parent)
        ->get(route('admin.settings.index'))
        ->assertForbidden();

    $this->actingAs($parent)
        ->get(route('admin.payment-settings.edit'))
        ->assertForbidden();
});

test('ordinary admin cannot open super admin features', function (string $routeName) {
    $admin = User::factory()->create(['role' => 'admin']);

    $this->actingAs($admin)
        ->get(route($routeName))
        ->assertForbidden();
})->with([
    'manage admins' => ['admin.kelola-admin.index'],
    'activity log' => ['admin.activity-log.index'],
]);

test('super admin can render exclusive management pages', function (string $routeName, string $semanticContent) {
    $superAdmin = User::factory()->create(['role' => 'super_admin']);

    $this->actingAs($superAdmin)
        ->get(route($routeName))
        ->assertOk()
        ->assertSee($semanticContent);
})->with([
    'manage admins' => ['admin.kelola-admin.index', 'Kelola Admin'],
    'activity log' => ['admin.activity-log.index', 'Activity Log'],
]);

test('every sidebar destination is a registered get route', function (string $routeName, string $expectedUri) {
    expect(Route::has($routeName))->toBeTrue();

    $route = Route::getRoutes()->getByName($routeName);

    expect($route)
        ->not->toBeNull()
        ->and($route->methods())->toContain('GET')
        ->and($route->uri())->toBe($expectedUri);
})->with([
    'admin dashboard' => ['admin.dashboard', 'admin/dashboard'],
    'admin registration periods' => ['admin.pendaftaran.index', 'admin/pendaftaran'],
    'admin students' => ['admin.siswa.index', 'admin/siswa'],
    'admin verification' => ['admin.verifikasi.index', 'admin/verifikasi'],
    'admin payments' => ['admin.pembayaran.index', 'admin/pembayaran'],
    'admin payment configuration' => ['admin.payment-settings.edit', 'admin/payment-settings'],
    'admin testimonials' => ['admin.testimonials.index', 'admin/testimonials'],
    'admin gallery' => ['admin.gallery.index', 'admin/gallery'],
    'admin site settings' => ['admin.settings.index', 'admin/settings'],
    'super admin management' => ['admin.kelola-admin.index', 'admin/kelola-admin'],
    'super admin activity log' => ['admin.activity-log.index', 'admin/activity-log'],
    'parent dashboard' => ['parent.dashboard', 'parent/dashboard'],
    'parent child form' => ['parent.siswa.create', 'parent/siswa/create'],
    'parent registration periods' => ['parent.pendaftaran.index', 'parent/pendaftaran'],
    'parent registration status' => ['parent.pendaftaran.status', 'parent/status'],
]);

/**
 * @return array<string, string>
 */
function baselineAdminMenuRoutes(): array
{
    return [
        'Dashboard' => 'admin.dashboard',
        'Gelombang SPMB' => 'admin.pendaftaran.index',
        'Data Siswa' => 'admin.siswa.index',
        'Verifikasi Data' => 'admin.verifikasi.index',
        'Rekap Pembayaran' => 'admin.pembayaran.index',
        'Konfigurasi Pembayaran' => 'admin.payment-settings.edit',
        'Kelola Testimoni' => 'admin.testimonials.index',
        'Kelola Gallery' => 'admin.gallery.index',
        'Pengaturan Situs' => 'admin.settings.index',
    ];
}

/**
 * @return array<string, string>
 */
function baselineSuperAdminMenuRoutes(): array
{
    return [
        'Kelola Admin' => 'admin.kelola-admin.index',
        'Activity Log' => 'admin.activity-log.index',
    ];
}
