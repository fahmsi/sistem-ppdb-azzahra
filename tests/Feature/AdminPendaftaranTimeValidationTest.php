<?php

use App\Models\Pendaftaran;
use App\Models\User;

function adminPendaftaranPayload(array $overrides = []): array
{
    return array_merge([
        'tahun_ajaran' => '2026/2027',
        'gelombang' => 'Gelombang Waktu',
        'kuota' => 30,
        'status' => 'buka',
        'tanggal_mulai' => '2026-01-01',
        'tanggal_selesai' => '2026-01-31',
        'tanggal_mpls' => '2026-02-01',
        'jam_mpls_mulai' => '08:00',
        'jam_mpls_selesai' => '13:00',
    ], $overrides);
}

test('admin can create registration period with valid MPLS time range', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $this->actingAs($admin)->post(route('admin.pendaftaran.store'), adminPendaftaranPayload())
        ->assertSessionHasNoErrors();

    $this->assertDatabaseHas('spmb_pendaftaran', [
        'gelombang' => 'Gelombang Waktu',
        'jam_mpls_mulai' => '08:00',
        'jam_mpls_selesai' => '13:00',
    ]);
});

test('admin can submit locale-formatted MPLS times with dot separator', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $this->actingAs($admin)->post(route('admin.pendaftaran.store'), adminPendaftaranPayload([
        'gelombang' => 'Gelombang Format Lokal',
        'jam_mpls_mulai' => '08.00',
        'jam_mpls_selesai' => '11.30',
        'jam_masuk_kbm' => '08.00',
    ]))->assertSessionHasNoErrors();

    $this->assertDatabaseHas('spmb_pendaftaran', [
        'gelombang' => 'Gelombang Format Lokal',
        'jam_mpls_mulai' => '08:00',
        'jam_mpls_selesai' => '11:30',
        'jam_masuk_kbm' => '08:00',
    ]);
});

test('admin can update registration period with valid MPLS time range', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $pendaftaran = Pendaftaran::factory()->create();

    $this->actingAs($admin)->put(route('admin.pendaftaran.update', $pendaftaran), adminPendaftaranPayload([
        'gelombang' => 'Gelombang Diperbarui',
    ]))->assertSessionHasNoErrors();

    $this->assertDatabaseHas('spmb_pendaftaran', [
        'id' => $pendaftaran->id,
        'jam_mpls_mulai' => '08:00',
        'jam_mpls_selesai' => '13:00',
    ]);
});

test('admin update normalizes SQL TIME values that include seconds', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $pendaftaran = Pendaftaran::factory()->create();

    $this->actingAs($admin)->put(route('admin.pendaftaran.update', $pendaftaran), adminPendaftaranPayload([
        'jam_mpls_mulai' => '08:00:00',
        'jam_mpls_selesai' => '11:30:00',
        'jam_masuk_kbm' => '08:00:00',
    ]))->assertSessionHasNoErrors();

    $this->assertDatabaseHas('spmb_pendaftaran', [
        'id' => $pendaftaran->id,
        'jam_mpls_mulai' => '08:00',
        'jam_mpls_selesai' => '11:30',
        'jam_masuk_kbm' => '08:00',
    ]);
});

test('MPLS end time must be later than start time with Indonesian message', function (string $mulai, string $selesai) {
    $admin = User::factory()->create(['role' => 'admin']);

    $this->actingAs($admin)->post(route('admin.pendaftaran.store'), adminPendaftaranPayload([
        'jam_mpls_mulai' => $mulai,
        'jam_mpls_selesai' => $selesai,
    ]))->assertSessionHasErrors([
        'jam_mpls_selesai' => 'Jam MPLS selesai harus lebih besar dari jam MPLS mulai.',
    ]);
})->with([
    'same time' => ['08:00', '08:00'],
    'earlier time' => ['13:00', '08:00'],
]);

test('MPLS time format and KBM date validation use Indonesian messages', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $this->actingAs($admin)->post(route('admin.pendaftaran.store'), adminPendaftaranPayload([
        'jam_mpls_mulai' => 'pagi',
        'jam_mpls_selesai' => 'siang',
        'tanggal_mulai_kbm' => '2026-01-31',
    ]))->assertSessionHasErrors([
        'jam_mpls_mulai' => 'Kolom jam MPLS mulai harus menggunakan format HH:MM.',
        'jam_mpls_selesai' => 'Kolom jam MPLS selesai harus menggunakan format HH:MM.',
        'tanggal_mulai_kbm' => 'Kolom tanggal mulai KBM harus setelah atau sama dengan tanggal MPLS.',
    ]);
});
