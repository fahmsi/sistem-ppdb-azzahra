<?php

use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\VerifikasiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Models\Setting;
use App\Models\Siswa;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\SettingSeeder;
use Illuminate\Support\Facades\Route;

test('gallery and testimonial resources expose only implemented actions', function () {
    expect(Route::has('admin.gallery.show'))->toBeFalse()
        ->and(Route::has('admin.testimonials.show'))->toBeFalse();

    foreach ([
        'admin.gallery.index',
        'admin.gallery.create',
        'admin.gallery.store',
        'admin.gallery.edit',
        'admin.gallery.update',
        'admin.gallery.destroy',
        'admin.testimonials.index',
        'admin.testimonials.create',
        'admin.testimonials.store',
        'admin.testimonials.edit',
        'admin.testimonials.update',
        'admin.testimonials.destroy',
    ] as $routeName) {
        expect(Route::has($routeName))->toBeTrue();
    }

    expect(method_exists(GalleryController::class, 'show'))->toBeFalse()
        ->and(method_exists(TestimonialController::class, 'show'))->toBeFalse();
});

test('every application controller route points to an available action', function () {
    foreach (Route::getRoutes() as $route) {
        $action = $route->getActionName();

        if (! str_starts_with($action, 'App\\Http\\Controllers\\') || ! str_contains($action, '@')) {
            continue;
        }

        [$controller, $method] = explode('@', $action, 2);

        expect(
            method_exists($controller, $method),
            "Route {$route->getName()} points to missing action {$action}.",
        )->toBeTrue();
    }
});

test('unused bulk verification mutation has no route method or rendered form', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    expect(Route::has('admin.verifikasi.bulkUpdate'))->toBeFalse()
        ->and(method_exists(VerifikasiController::class, 'bulkUpdate'))->toBeFalse();

    $this->actingAs($admin)
        ->get(route('admin.verifikasi.index'))
        ->assertOk()
        ->assertDontSee('bulk-update', false)
        ->assertDontSee('bulkUpdate', false);
});

test('password update uses only the profile controller flow', function () {
    $route = Route::getRoutes()->getByName('password.update');

    expect($route)
        ->not->toBeNull()
        ->and($route->getActionName())->toBe(ProfileController::class.'@updatePassword')
        ->and(method_exists(AuthController::class, 'changePassword'))->toBeFalse();

    $user = User::factory()->create(['role' => 'parent']);

    $this->actingAs($user)
        ->get(route('profile.edit'))
        ->assertOk()
        ->assertSee(route('password.update'), false);
});

test('parent without child data sees the create destination in search modal', function () {
    $parent = User::factory()->create(['role' => 'parent']);

    $this->actingAs($parent)
        ->get(route('parent.dashboard'))
        ->assertOk()
        ->assertSee('Lengkapi Data Anak')
        ->assertSee(route('parent.siswa.create'), false)
        ->assertDontSee('Kelola Data Anak');
});

test('parent with child data sees the management destination in search modal', function () {
    $parent = User::factory()->create(['role' => 'parent']);
    Siswa::factory()->for($parent)->create();

    $this->actingAs($parent)
        ->get(route('parent.dashboard'))
        ->assertOk()
        ->assertSee('Kelola Data Anak')
        ->assertSee(route('parent.siswa.index'), false)
        ->assertDontSee('Lengkapi Data Anak');
});

test('database seeder installs baseline settings without creating a super admin', function () {
    $this->seed(DatabaseSeeder::class);

    $this->assertDatabaseHas('settings', [
        'key' => 'hero_title',
        'group' => 'hero',
    ]);
    $this->assertDatabaseHas('settings', [
        'key' => 'social_instagram',
        'group' => 'footer',
    ]);
    $this->assertDatabaseMissing('users', [
        'role' => 'super_admin',
    ]);
});

test('setting seeder is idempotent', function () {
    $this->seed(SettingSeeder::class);
    $initialCount = Setting::query()->count();

    $this->seed(SettingSeeder::class);

    expect($initialCount)->toBe(10)
        ->and(Setting::query()->count())->toBe($initialCount)
        ->and(Setting::query()->where('key', 'hero_title')->count())->toBe(1);
});
