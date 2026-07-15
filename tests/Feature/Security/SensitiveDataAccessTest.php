<?php

use App\Models\Achievement;
use App\Models\Gallery;
use App\Models\Pendaftaran;
use App\Models\PendaftaranDetail;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

test('private documents require authentication', function () {
    $siswa = Siswa::factory()->create(['foto_kk' => 'siswa/kk/private.png']);

    $this->get(route('dokumen.show', [$siswa, 'foto_kk']))
        ->assertRedirect(route('login'));
});

test('a parent cannot access another parents private document', function () {
    $parent = User::factory()->create(['role' => 'parent']);
    $otherParent = User::factory()->create(['role' => 'parent']);
    $siswa = Siswa::factory()->for($otherParent)->create([
        'foto_kk' => 'siswa/kk/private.png',
    ]);

    $this->actingAs($parent)
        ->get(route('dokumen.show', [$siswa, 'foto_kk']))
        ->assertForbidden();
});

test('a parent can access their own whitelisted private document', function () {
    Storage::fake('local');
    Storage::disk('local')->put('siswa/kk/private.png', 'private document');

    $parent = User::factory()->create(['role' => 'parent']);
    $siswa = Siswa::factory()->for($parent)->create([
        'foto_kk' => 'siswa/kk/private.png',
    ]);

    $this->actingAs($parent)
        ->get(route('dokumen.show', [$siswa, 'foto_kk']))
        ->assertOk()
        ->assertHeader('Cache-Control', 'max-age=0, no-store, private')
        ->assertHeader('X-Content-Type-Options', 'nosniff');

    $this->actingAs($parent)
        ->get(route('dokumen.show', [$siswa, 'not_a_document']))
        ->assertNotFound();
});

test('a parent cannot access another parents payment routes', function () {
    $parent = User::factory()->create(['role' => 'parent']);
    $otherParent = User::factory()->create(['role' => 'parent']);
    $siswa = Siswa::factory()->for($otherParent)->create();
    $pendaftaran = Pendaftaran::factory()->create();
    $detail = PendaftaranDetail::create([
        'siswa_id' => $siswa->id,
        'pendaftaran_id' => $pendaftaran->id,
        'status' => PendaftaranDetail::STATUS_DITERIMA,
    ]);

    $this->actingAs($parent)
        ->post(route('parent.pembayaran.store', $detail))
        ->assertForbidden();

    $this->actingAs($parent)
        ->get(route('parent.pembayaran.receipt', $detail))
        ->assertForbidden();
});

test('admin exports reject parent accounts', function (string $routeName) {
    $parent = User::factory()->create(['role' => 'parent']);

    $this->actingAs($parent)
        ->get(route($routeName))
        ->assertForbidden();
})->with([
    'student export' => 'admin.siswa.export',
    'verification export' => 'admin.verifikasi.export',
    'payment export' => 'admin.pembayaran.export',
]);

test('suspended admins cannot use the private document route', function () {
    $admin = User::factory()->create([
        'role' => 'admin',
        'suspended_at' => now(),
    ]);
    $siswa = Siswa::factory()->create(['foto_kk' => 'siswa/kk/private.png']);

    $this->actingAs($admin)
        ->get(route('dokumen.show', [$siswa, 'foto_kk']))
        ->assertRedirect(route('login'));

    $this->assertGuest();
});

test('the generic private storage route is disabled', function () {
    expect(Route::has('storage.local'))->toBeFalse();
});

test('inactive public media cannot be enumerated by id', function () {
    $achievement = Achievement::create([
        'title' => 'Draft achievement',
        'level' => 'Draft',
        'image' => 'achievements/draft.png',
        'is_active' => false,
    ]);
    $gallery = Gallery::create([
        'title' => 'Draft gallery',
        'image' => 'galleries/draft.png',
        'is_active' => false,
    ]);

    $this->get(route('achievements.image', $achievement))->assertNotFound();
    $this->get(route('galleries.image', $gallery))->assertNotFound();
});
