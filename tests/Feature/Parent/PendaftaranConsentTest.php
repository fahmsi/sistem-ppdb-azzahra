<?php

use App\Models\Pendaftaran;
use App\Models\User;

test('parent must confirm data accuracy before submitting a registration', function () {
    $parent = User::factory()->create(['role' => 'parent']);
    $pendaftaran = Pendaftaran::create([
        'tahun_ajaran' => '2026/2027',
        'gelombang' => 'Gelombang 1',
        'kuota' => 20,
        'status' => 'buka',
        'tanggal_mulai' => now()->subDay(),
        'tanggal_selesai' => now()->addMonth(),
    ]);

    $response = $this->actingAs($parent)
        ->post(route('parent.pendaftaran.daftar', $pendaftaran));

    $response->assertSessionHasErrors('data_declaration');
});

test('accepted data confirmation reaches the existing registration flow', function () {
    $parent = User::factory()->create(['role' => 'parent']);
    $pendaftaran = Pendaftaran::create([
        'tahun_ajaran' => '2026/2027',
        'gelombang' => 'Gelombang 1',
        'kuota' => 20,
        'status' => 'buka',
        'tanggal_mulai' => now()->subDay(),
        'tanggal_selesai' => now()->addMonth(),
    ]);

    $response = $this->actingAs($parent)
        ->post(route('parent.pendaftaran.daftar', $pendaftaran), [
            'data_declaration' => '1',
        ]);

    $response->assertSessionDoesntHaveErrors('data_declaration')
        ->assertRedirect(route('parent.siswa.create'));
});
