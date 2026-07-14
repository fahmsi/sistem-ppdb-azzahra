<?php

use App\Models\Pembayaran;
use App\Models\Pendaftaran;
use App\Models\PendaftaranDetail;
use App\Models\PaymentSetting;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

test('parent can add two children and both belong to the same user', function () {
    Storage::fake('public');
    Storage::fake('local');

    $parent = User::factory()->create(['role' => 'parent']);

    $this->actingAs($parent)
        ->post(route('parent.siswa.store'), validSiswaPayload('Alya'))
        ->assertRedirect();

    $this->actingAs($parent)
        ->post(route('parent.siswa.store'), validSiswaPayload('Bima'))
        ->assertRedirect();

    expect($parent->siswas()->count())->toBe(2);

    $this->assertDatabaseHas('spmb_siswa', [
        'user_id' => $parent->id,
        'nama' => 'Alya Azzahra',
        'input_source' => Siswa::INPUT_SOURCE_ONLINE,
    ]);
    $this->assertDatabaseHas('spmb_siswa', [
        'user_id' => $parent->id,
        'nama' => 'Bima Azzahra',
        'input_source' => Siswa::INPUT_SOURCE_ONLINE,
    ]);
});

test('parent sees only their own children and can open the second child', function () {
    $parent = User::factory()->create(['role' => 'parent']);
    $otherParent = User::factory()->create(['role' => 'parent']);
    $first = Siswa::factory()->for($parent)->create(['nama' => 'Anak Pertama']);
    $second = Siswa::factory()->for($parent)->create(['nama' => 'Anak Kedua']);
    $otherChild = Siswa::factory()->for($otherParent)->create(['nama' => 'Anak Orang Lain']);

    $this->actingAs($parent)
        ->get(route('parent.siswa.index'))
        ->assertOk()
        ->assertSee($first->nama)
        ->assertSee($second->nama)
        ->assertDontSee($otherChild->nama);

    $this->actingAs($parent)
        ->get(route('parent.siswa.show', $second))
        ->assertOk()
        ->assertSee($second->nama);
});

test('parent cannot access edit delete register status or card for another parent child', function () {
    $parent = User::factory()->create(['role' => 'parent']);
    $otherParent = User::factory()->create(['role' => 'parent']);
    $otherChild = Siswa::factory()->for($otherParent)->create();
    $period = openPeriod();
    $detail = PendaftaranDetail::create([
        'siswa_id' => $otherChild->id,
        'pendaftaran_id' => $period->id,
        'status' => PendaftaranDetail::STATUS_DITERIMA,
    ]);

    $this->actingAs($parent)->get(route('parent.siswa.show', $otherChild))->assertForbidden();
    $this->actingAs($parent)->get(route('parent.siswa.edit', $otherChild))->assertForbidden();
    $this->actingAs($parent)->delete(route('parent.siswa.destroy', $otherChild))->assertForbidden();
    $this->actingAs($parent)->get(route('parent.siswa.pendaftaran.index', $otherChild))->assertForbidden();
    $this->actingAs($parent)->get(route('parent.siswa.pendaftaran.status', $otherChild))->assertForbidden();
    $this->actingAs($parent)
        ->post(route('parent.siswa.pendaftaran.daftar', ['siswa' => $otherChild, 'pendaftaran' => $period]), [
            'data_declaration' => '1',
        ])
        ->assertForbidden();
    $this->actingAs($parent)
        ->get(route('parent.siswa.pendaftaran.kartu', ['siswa' => $otherChild, 'detail' => $detail]))
        ->assertForbidden();
});

test('two children from one account can register in the same period but one child cannot have two active registrations', function () {
    $parent = User::factory()->create(['role' => 'parent']);
    $first = Siswa::factory()->for($parent)->create();
    $second = Siswa::factory()->for($parent)->create();
    $period = openPeriod();

    $this->actingAs($parent)
        ->post(route('parent.siswa.pendaftaran.daftar', ['siswa' => $first, 'pendaftaran' => $period]), [
            'data_declaration' => '1',
        ])
        ->assertRedirect(route('parent.siswa.pendaftaran.status', $first));

    $this->actingAs($parent)
        ->post(route('parent.siswa.pendaftaran.daftar', ['siswa' => $second, 'pendaftaran' => $period]), [
            'data_declaration' => '1',
        ])
        ->assertRedirect(route('parent.siswa.pendaftaran.status', $second));

    expect(PendaftaranDetail::where('pendaftaran_id', $period->id)->count())->toBe(2);

    $otherPeriod = openPeriod(['gelombang' => 'Gelombang Lain']);
    $this->actingAs($parent)
        ->post(route('parent.siswa.pendaftaran.daftar', ['siswa' => $first, 'pendaftaran' => $otherPeriod]), [
            'data_declaration' => '1',
        ])
        ->assertSessionHas('error');

    expect($first->pendaftaranDetails()->whereNotIn('status', [PendaftaranDetail::STATUS_DITOLAK])->count())->toBe(1);
});

test('status and payment remain tied to the selected child registration detail', function () {
    Storage::fake('local');

    $parent = User::factory()->create(['role' => 'parent']);
    $first = Siswa::factory()->for($parent)->create(['nama' => 'Status Anak Pertama']);
    $second = Siswa::factory()->for($parent)->create(['nama' => 'Status Anak Kedua']);
    $period = openPeriod();
    $firstDetail = PendaftaranDetail::create([
        'siswa_id' => $first->id,
        'pendaftaran_id' => $period->id,
        'status' => PendaftaranDetail::STATUS_MENUNGGU_VERIFIKASI,
        'notifikasi' => 'ONLY_FIRST_CHILD',
    ]);
    $secondDetail = PendaftaranDetail::create([
        'siswa_id' => $second->id,
        'pendaftaran_id' => $period->id,
        'status' => PendaftaranDetail::STATUS_DITERIMA,
        'notifikasi' => 'ONLY_SECOND_CHILD',
    ]);

    $this->actingAs($parent)
        ->get(route('parent.siswa.pendaftaran.status', $second))
        ->assertOk()
        ->assertSee('ONLY_SECOND_CHILD')
        ->assertDontSee('ONLY_FIRST_CHILD');

    PaymentSetting::create([
        'bank_name' => 'Bank Demo',
        'account_number' => '123456789',
        'account_holder_name' => 'PAUD Az-Zahra',
        'amount' => 900000,
    ]);

    $this->actingAs($parent)
        ->post(route('parent.pembayaran.store', $secondDetail), [
            'bukti_bayar' => UploadedFile::fake()->image('bukti.png'),
        ])
        ->assertRedirect()
        ->assertSessionHas('success');

    $this->assertDatabaseHas('spmb_pembayaran', [
        'pendaftaran_detail_id' => $secondDetail->id,
        'jumlah' => 900000,
    ]);
    $this->assertDatabaseMissing('spmb_pembayaran', [
        'pendaftaran_detail_id' => $firstDetail->id,
    ]);
});

test('registration card requires the selected child and matching accepted detail', function () {
    $parent = User::factory()->create(['role' => 'parent']);
    $first = Siswa::factory()->for($parent)->create();
    $second = Siswa::factory()->for($parent)->create();
    $period = openPeriod();
    $detail = PendaftaranDetail::create([
        'siswa_id' => $second->id,
        'pendaftaran_id' => $period->id,
        'status' => PendaftaranDetail::STATUS_DITERIMA,
    ]);

    $this->actingAs($parent)
        ->get(route('parent.siswa.pendaftaran.kartu', ['siswa' => $first, 'detail' => $detail]))
        ->assertNotFound();

    $this->actingAs($parent)
        ->get(route('parent.siswa.pendaftaran.kartu', ['siswa' => $second, 'detail' => $detail]))
        ->assertOk()
        ->assertSee($second->nama);
});

test('nested child URLs reject mismatched registration details and foreign payment details', function () {
    Storage::fake('local');

    $parent = User::factory()->create(['role' => 'parent']);
    $otherParent = User::factory()->create(['role' => 'parent']);
    $first = Siswa::factory()->for($parent)->create();
    $second = Siswa::factory()->for($parent)->create();
    $otherChild = Siswa::factory()->for($otherParent)->create();
    $period = openPeriod();
    $secondDetail = PendaftaranDetail::create([
        'siswa_id' => $second->id,
        'pendaftaran_id' => $period->id,
        'status' => PendaftaranDetail::STATUS_DITERIMA,
    ]);
    $otherDetail = PendaftaranDetail::create([
        'siswa_id' => $otherChild->id,
        'pendaftaran_id' => $period->id,
        'status' => PendaftaranDetail::STATUS_DITERIMA,
    ]);
    $secondPayment = Pembayaran::create([
        'pendaftaran_detail_id' => $secondDetail->id,
        'jumlah' => 900000,
        'bukti_bayar' => 'pembayaran/second-proof.png',
        'status' => Pembayaran::STATUS_MENUNGGU_VERIFIKASI,
    ]);
    Storage::disk('local')->put($secondPayment->bukti_bayar, 'proof');

    $this->actingAs($parent)
        ->get(route('parent.siswa.pendaftaran.kartu', ['siswa' => $first, 'detail' => $secondDetail]))
        ->assertNotFound();

    $this->actingAs($parent)
        ->get(route('dokumen.show', ['siswa' => $first, 'field' => 'bukti_bayar', 'pembayaran' => $secondPayment]))
        ->assertNotFound();

    $this->actingAs($parent)
        ->post(route('parent.pembayaran.store', $otherDetail), [
            'bukti_bayar' => UploadedFile::fake()->image('foreign-proof.png'),
        ])
        ->assertForbidden();
});

test('legacy parent registration routes redirect to child selection', function (string $url) {
    $parent = User::factory()->create(['role' => 'parent']);

    $this->actingAs($parent)
        ->get($url)
        ->assertRedirect(route('parent.siswa.index'))
        ->assertSessionHas('warning', 'Silakan pilih anak untuk melanjutkan proses pendaftaran.');
})->with([
    '/parent/pendaftaran',
    '/parent/status',
    '/parent/siswa/kartu',
]);

test('deleting one child does not delete another child', function () {
    $parent = User::factory()->create(['role' => 'parent']);
    $first = Siswa::factory()->for($parent)->create(['nama' => 'Anak Dihapus']);
    $second = Siswa::factory()->for($parent)->create(['nama' => 'Anak Tetap Ada']);

    $this->actingAs($parent)
        ->delete(route('parent.siswa.destroy', $first))
        ->assertRedirect(route('parent.siswa.index'));

    $this->assertDatabaseMissing('spmb_siswa', ['id' => $first->id]);
    $this->assertDatabaseHas('spmb_siswa', ['id' => $second->id, 'deleted_at' => null]);
});

test('dashboard renders with no children and with multiple children', function () {
    $parent = User::factory()->create(['role' => 'parent']);

    $this->actingAs($parent)
        ->get(route('parent.dashboard'))
        ->assertOk()
        ->assertSee('Belum Ada Data Anak');

    $first = Siswa::factory()->for($parent)->create(['nama' => 'Dashboard Anak Satu']);
    $second = Siswa::factory()->for($parent)->create(['nama' => 'Dashboard Anak Dua']);

    $this->actingAs($parent)
        ->get(route('parent.dashboard'))
        ->assertOk()
        ->assertSee($first->nama)
        ->assertSee($second->nama);
});

test('account deletion is rejected when any child has registration history', function () {
    $parent = User::factory()->create(['role' => 'parent']);
    $childWithoutHistory = Siswa::factory()->for($parent)->create();
    $childWithHistory = Siswa::factory()->for($parent)->create();
    PendaftaranDetail::create([
        'siswa_id' => $childWithHistory->id,
        'pendaftaran_id' => openPeriod()->id,
        'status' => PendaftaranDetail::STATUS_DITOLAK,
    ]);

    $this->actingAs($parent)
        ->from(route('profile.edit'))
        ->delete(route('profile.destroy'), ['password' => 'password'])
        ->assertRedirect(route('profile.edit'))
        ->assertSessionHas('error');

    $this->assertDatabaseHas('users', ['id' => $parent->id]);
    $this->assertDatabaseHas('spmb_siswa', ['id' => $childWithoutHistory->id]);
    $this->assertDatabaseHas('spmb_siswa', ['id' => $childWithHistory->id]);
});

test('account deletion without registration history deletes all children and their files', function () {
    Storage::fake('public');
    Storage::fake('local');

    $parent = User::factory()->create([
        'role' => 'parent',
        'avatar' => 'avatars/parent.png',
    ]);
    $first = Siswa::factory()->for($parent)->create([
        'foto' => 'siswa/foto/first.png',
        'foto_kk' => 'siswa/kk/first.png',
        'foto_akta' => 'siswa/akta/first.png',
    ]);
    $second = Siswa::factory()->for($parent)->create([
        'foto' => 'siswa/foto/second.png',
        'foto_kk' => 'siswa/kk/second.png',
        'foto_akta' => 'siswa/akta/second.png',
    ]);
    foreach ([$parent->avatar, $first->foto, $second->foto] as $path) {
        Storage::disk('public')->put($path, 'image');
    }
    foreach ([$first->foto_kk, $first->foto_akta, $second->foto_kk, $second->foto_akta] as $path) {
        Storage::disk('local')->put($path, 'image');
    }

    $this->actingAs($parent)
        ->delete(route('profile.destroy'), ['password' => 'password'])
        ->assertRedirect('/');

    $this->assertDatabaseMissing('users', ['id' => $parent->id]);
    $this->assertDatabaseMissing('spmb_siswa', ['id' => $first->id]);
    $this->assertDatabaseMissing('spmb_siswa', ['id' => $second->id]);
    foreach (['avatars/parent.png', 'siswa/foto/first.png', 'siswa/foto/second.png'] as $path) {
        Storage::disk('public')->assertMissing($path);
    }
    foreach (['siswa/kk/first.png', 'siswa/akta/first.png', 'siswa/kk/second.png', 'siswa/akta/second.png'] as $path) {
        Storage::disk('local')->assertMissing($path);
    }
});

function openPeriod(array $overrides = []): Pendaftaran
{
    return Pendaftaran::factory()->create(array_merge([
        'status' => 'buka',
        'tanggal_mulai' => now()->subDay(),
        'tanggal_selesai' => now()->addMonth(),
        'kuota' => 20,
    ], $overrides));
}

function validSiswaPayload(string $name): array
{
    return [
        'nama' => "{$name} Azzahra",
        'nama_panggilan' => $name,
        'jenis_kelamin' => 'L',
        'tempat_lahir' => 'Depok',
        'tanggal_lahir' => now()->subYears(5)->format('Y-m-d'),
        'agama' => 'Islam',
        'anak_ke' => 1,
        'jumlah_saudara' => 1,
        'hobi' => 'Menggambar',
        'cita_cita' => 'Guru',
        'no_telpon' => '081234567890',
        'jenis_tempat_tinggal' => 'Rumah Sendiri',
        'alamat' => 'Jl. Pendidikan No. 1',
        'kelurahan' => 'Sukamaju',
        'kecamatan' => 'Cilodong',
        'kota' => 'Depok',
        'provinsi' => 'Jawa Barat',
        'kode_pos' => '16415',
        'transportasi' => 'Motor',
        'no_kk' => '3201010101010001',
        'kepala_keluarga' => 'Bapak Azzahra',
        'nama_ayah' => 'Ayah '.$name,
        'nik_ayah' => '3201010101010002',
        'tanggal_lahir_ayah' => now()->subYears(35)->format('Y-m-d'),
        'pendidikan_ayah' => 'S1',
        'pekerjaan_ayah' => 'Karyawan Swasta',
        'penghasilan_ayah' => '3-5 Juta',
        'nama_ibu' => 'Ibu '.$name,
        'nik_ibu' => '3201010101010003',
        'tanggal_lahir_ibu' => now()->subYears(33)->format('Y-m-d'),
        'pendidikan_ibu' => 'S1',
        'pekerjaan_ibu' => 'Ibu Rumah Tangga',
        'penghasilan_ibu' => '1-3 Juta',
        'foto' => UploadedFile::fake()->image($name.'-foto.jpg'),
        'foto_kk' => UploadedFile::fake()->image($name.'-kk.jpg'),
        'foto_akta' => UploadedFile::fake()->image($name.'-akta.jpg'),
    ];
}
