<?php

use App\Models\Pendaftaran;
use App\Models\PendaftaranDetail;
use App\Models\PaymentSetting;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\Exceptions\PostTooLargeException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

test('parent can upload child documents at the two megabyte boundary', function () {
    Storage::fake('public');
    Storage::fake('local');

    $parent = User::factory()->create(['role' => 'parent']);
    $payload = validParentUploadPayload('Alya');
    $payload['foto'] = UploadedFile::fake()->image('alya-foto.jpg')->size(2048);
    $payload['foto_kk'] = UploadedFile::fake()->image('alya-kk.jpg')->size(2048);
    $payload['foto_akta'] = UploadedFile::fake()->image('alya-akta.jpg')->size(2048);

    $this->actingAs($parent)
        ->post(route('parent.siswa.store'), $payload)
        ->assertSessionHasNoErrors()
        ->assertSessionHas('clear_parent_draft', "spmb:parent:siswa:create:{$parent->id}");

    $siswa = Siswa::query()->whereBelongsTo($parent)->sole();

    Storage::disk('public')->assertExists($siswa->foto);
    Storage::disk('local')->assertExists($siswa->foto_kk);
    Storage::disk('local')->assertExists($siswa->foto_akta);
});

test('oversized child document is rejected with Indonesian message and old input', function () {
    Storage::fake('public');
    Storage::fake('local');

    $parent = User::factory()->create(['role' => 'parent']);
    $payload = validParentUploadPayload('Bima');
    $payload['nama'] = 'Bima Draft Tetap Ada';
    $payload['foto'] = UploadedFile::fake()->image('bima-foto.jpg')->size(2049);

    $this->actingAs($parent)
        ->from(route('parent.siswa.create'))
        ->post(route('parent.siswa.store'), $payload)
        ->assertRedirect(route('parent.siswa.create'))
        ->assertSessionHasErrors([
            'foto' => 'Ukuran file maksimal 2 MB.',
        ])
        ->assertSessionHasInput('nama', 'Bima Draft Tetap Ada');

    $this->assertDatabaseMissing('spmb_siswa', ['user_id' => $parent->id]);
});

test('unsupported child document format is rejected with Indonesian message', function () {
    Storage::fake('public');
    Storage::fake('local');

    $parent = User::factory()->create(['role' => 'parent']);
    $payload = validParentUploadPayload('Citra');
    $payload['foto_kk'] = UploadedFile::fake()->create('kartu-keluarga.txt', 10, 'text/plain');

    $this->actingAs($parent)
        ->from(route('parent.siswa.create'))
        ->post(route('parent.siswa.store'), $payload)
        ->assertRedirect(route('parent.siswa.create'))
        ->assertSessionHasErrors([
            'foto_kk' => 'Format file tidak didukung.',
        ]);
});

test('oversized payment proof is rejected before it is stored', function () {
    Storage::fake('local');

    $parent = User::factory()->create(['role' => 'parent']);
    $siswa = Siswa::factory()->for($parent)->create();
    $period = Pendaftaran::factory()->create();
    $detail = PendaftaranDetail::create([
        'siswa_id' => $siswa->id,
        'pendaftaran_id' => $period->id,
        'status' => PendaftaranDetail::STATUS_DITERIMA,
    ]);
    PaymentSetting::create([
        'bank_name' => 'Bank Demo',
        'account_number' => '123456789',
        'account_holder_name' => 'PAUD Az-Zahra',
        'amount' => 900000,
    ]);

    $this->actingAs($parent)
        ->from(route('parent.siswa.pendaftaran.status', $siswa))
        ->post(route('parent.pembayaran.store', $detail), [
            'bukti_bayar' => UploadedFile::fake()->image('bukti.png')->size(2049),
        ])
        ->assertSessionHasErrors([
            'bukti_bayar' => 'Ukuran file maksimal 2 MB.',
        ]);

    $this->assertDatabaseMissing('spmb_pembayaran', [
        'pendaftaran_detail_id' => $detail->id,
    ]);
});

test('oversized avatar is rejected with Indonesian message', function () {
    Storage::fake('public');

    $parent = User::factory()->create(['role' => 'parent']);

    $this->actingAs($parent)
        ->from(route('profile.edit'))
        ->patch(route('profile.update'), [
            'name' => $parent->name,
            'email' => $parent->email,
            'avatar' => UploadedFile::fake()->image('avatar.png')->size(2049),
        ])
        ->assertRedirect(route('profile.edit'))
        ->assertSessionHasErrors([
            'avatar' => 'Ukuran file maksimal 2 MB.',
        ]);
});

test('post too large exception returns a friendly response without technical details', function () {
    Route::middleware('web')->post('/testing/post-too-large', function () {
        throw new PostTooLargeException('The POST data is too large.');
    });

    $parent = User::factory()->create(['role' => 'parent']);

    $this->actingAs($parent)
        ->from(route('parent.siswa.create'))
        ->post('/testing/post-too-large')
        ->assertRedirect(route('parent.siswa.create').'?upload_error=too_large')
        ->assertSessionHas(
            'error',
            'Total ukuran file terlalu besar. Pastikan setiap file maksimal 2 MB.'
        )
        ->assertDontSee('PostTooLargeException')
        ->assertDontSee('The POST data is too large.');
});

function validParentUploadPayload(string $name): array
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
        'nama_ayah' => "Ayah {$name}",
        'nik_ayah' => '3201010101010002',
        'tanggal_lahir_ayah' => now()->subYears(35)->format('Y-m-d'),
        'pendidikan_ayah' => 'S1',
        'pekerjaan_ayah' => 'Karyawan Swasta',
        'penghasilan_ayah' => '3-5 Juta',
        'nama_ibu' => "Ibu {$name}",
        'nik_ibu' => '3201010101010003',
        'tanggal_lahir_ibu' => now()->subYears(33)->format('Y-m-d'),
        'pendidikan_ibu' => 'S1',
        'pekerjaan_ibu' => 'Ibu Rumah Tangga',
        'penghasilan_ibu' => '1-3 Juta',
        'foto' => UploadedFile::fake()->image("{$name}-foto.jpg"),
        'foto_kk' => UploadedFile::fake()->image("{$name}-kk.jpg"),
        'foto_akta' => UploadedFile::fake()->image("{$name}-akta.jpg"),
    ];
}
