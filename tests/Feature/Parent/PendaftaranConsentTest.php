<?php

use App\Models\Pendaftaran;
use App\Models\PendaftaranDetail;
use App\Models\Siswa;
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
    $siswa = Siswa::factory()->for($parent)->create();

    $response = $this->actingAs($parent)
        ->post(route('parent.siswa.pendaftaran.daftar', ['siswa' => $siswa, 'pendaftaran' => $pendaftaran]));

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
    $siswa = Siswa::factory()->for($parent)->create();

    $response = $this->actingAs($parent)
        ->post(route('parent.siswa.pendaftaran.daftar', ['siswa' => $siswa, 'pendaftaran' => $pendaftaran]), [
            'data_declaration' => '1',
        ]);

    $response->assertSessionDoesntHaveErrors('data_declaration')
        ->assertRedirect(route('parent.siswa.pendaftaran.status', $siswa));

    $this->assertDatabaseHas('spmb_pendaftaran_detail', [
        'siswa_id' => $siswa->id,
        'pendaftaran_id' => $pendaftaran->id,
        'status' => PendaftaranDetail::STATUS_PENDING,
    ]);
});
