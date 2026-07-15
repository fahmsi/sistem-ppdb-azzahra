<?php

use App\Models\ActivityLog;
use App\Models\Pendaftaran;
use App\Models\PendaftaranDetail;
use App\Models\Siswa;
use App\Models\User;
use App\Services\StudentGroupRecommendationService;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake('local');
    Storage::fake('public');
});

/*
|--------------------------------------------------------------------------
| Age & StudentGroupRecommendationService Scenarios (1 - 6)
|--------------------------------------------------------------------------
*/

test('1. recommends Group A for exactly 48 months', function () {
    $service = new StudentGroupRecommendationService;
    $result = $service->calculate('2022-07-01', '2026/2027');

    expect((int) $result['usia_bulan'])->toBe(48)
        ->and($result['kelompok_rekomendasi'])->toBe('A');
});

test('2. recommends Group A for 59 months', function () {
    $service = new StudentGroupRecommendationService;
    $result = $service->calculate('2021-08-01', '2026/2027');

    expect((int) $result['usia_bulan'])->toBe(59)
        ->and($result['kelompok_rekomendasi'])->toBe('A');
});

test('3. recommends Group B for exactly 60 months', function () {
    $service = new StudentGroupRecommendationService;
    $result = $service->calculate('2021-07-01', '2026/2027');

    expect((int) $result['usia_bulan'])->toBe(60)
        ->and($result['kelompok_rekomendasi'])->toBe('B');
});

test('4. recommends Group B for 83 months', function () {
    $service = new StudentGroupRecommendationService;
    $result = $service->calculate('2019-08-01', '2026/2027');

    expect((int) $result['usia_bulan'])->toBe(83)
        ->and($result['kelompok_rekomendasi'])->toBe('B');
});

test('5. recommends perlu_konfirmasi for under 48 months', function () {
    $service = new StudentGroupRecommendationService;
    $result = $service->calculate('2022-08-01', '2026/2027');

    expect((int) $result['usia_bulan'])->toBe(47)
        ->and($result['kelompok_rekomendasi'])->toBe('perlu_konfirmasi');
});

test('6. recommends perlu_konfirmasi for 84 months and older', function () {
    $service = new StudentGroupRecommendationService;
    $result = $service->calculate('2019-07-01', '2026/2027');

    expect((int) $result['usia_bulan'])->toBe(84)
        ->and($result['kelompok_rekomendasi'])->toBe('perlu_konfirmasi');
});

/*
|--------------------------------------------------------------------------
| StorePendaftaranRequest (Academic Year validation) Scenarios (7 - 11)
|--------------------------------------------------------------------------
*/

test('7. accepts valid sequential YYYY/YYYY format', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $response = $this->actingAs($admin)->post(route('admin.pendaftaran.store'), [
        'tahun_ajaran' => '2026/2027',
        'gelombang' => 'Gelombang 1',
        'kuota' => 30,
        'status' => 'buka',
        'tanggal_mulai' => '2026-01-01',
        'tanggal_selesai' => '2026-02-01',
        'tanggal_mpls' => '2026-02-02',
    ]);

    $response->assertSessionHasNoErrors();
    $this->assertDatabaseHas('spmb_pendaftaran', ['tahun_ajaran' => '2026/2027']);
});

test('8. rejects YYYY-YYYY format', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $response = $this->actingAs($admin)->post(route('admin.pendaftaran.store'), [
        'tahun_ajaran' => '2026-2027',
        'gelombang' => 'Gelombang 1',
        'kuota' => 30,
        'status' => 'buka',
        'tanggal_mulai' => '2026-01-01',
        'tanggal_selesai' => '2026-02-01',
    ]);

    $response->assertSessionHasErrors(['tahun_ajaran']);
});

test('9. rejects non-sequential gap larger than 1 year', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $response = $this->actingAs($admin)->post(route('admin.pendaftaran.store'), [
        'tahun_ajaran' => '2026/2028',
        'gelombang' => 'Gelombang 1',
        'kuota' => 30,
        'status' => 'buka',
        'tanggal_mulai' => '2026-01-01',
        'tanggal_selesai' => '2026-02-01',
    ]);

    $response->assertSessionHasErrors(['tahun_ajaran']);
});

test('10. rejects non-sequential descending years', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $response = $this->actingAs($admin)->post(route('admin.pendaftaran.store'), [
        'tahun_ajaran' => '2027/2026',
        'gelombang' => 'Gelombang 1',
        'kuota' => 30,
        'status' => 'buka',
        'tanggal_mulai' => '2026-01-01',
        'tanggal_selesai' => '2026-02-01',
    ]);

    $response->assertSessionHasErrors(['tahun_ajaran']);
});

test('11. rejects non-numeric academic year format', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $response = $this->actingAs($admin)->post(route('admin.pendaftaran.store'), [
        'tahun_ajaran' => 'abcd/efgh',
        'gelombang' => 'Gelombang 1',
        'kuota' => 30,
        'status' => 'buka',
        'tanggal_mulai' => '2026-01-01',
        'tanggal_selesai' => '2026-02-01',
    ]);

    $response->assertSessionHasErrors(['tahun_ajaran']);
});

/*
|--------------------------------------------------------------------------
| Registration & Snapshotting Scenarios (12 - 13)
|--------------------------------------------------------------------------
*/

test('12. captures age calculation snapshots on registration creation', function () {
    $parent = User::factory()->create(['role' => 'parent']);
    $pendaftaran = Pendaftaran::factory()->create(['tahun_ajaran' => '2026/2027', 'status' => 'buka']);

    $siswa = Siswa::factory()->create([
        'user_id' => $parent->id,
        'tanggal_lahir' => '2021-07-01', // exactly 5 years old on 2026-07-01 (60 months) -> Kelompok B
    ]);

    $response = $this->actingAs($parent)->post(route('parent.siswa.pendaftaran.daftar', ['siswa' => $siswa->id, 'pendaftaran' => $pendaftaran->id]), [
        'data_declaration' => '1',
    ]);

    $response->assertRedirect();

    $detail = PendaftaranDetail::where('siswa_id', $siswa->id)->first();
    expect($detail)->not->toBeNull()
        ->and($detail->usia_bulan_saat_acuan)->toBe(60)
        ->and($detail->kelompok_rekomendasi)->toBe('B')
        ->and($detail->tanggal_acuan_usia->format('Y-m-d'))->toBe('2026-07-01');
});

test('13. allows out-of-range student (perlu_konfirmasi) to register', function () {
    $parent = User::factory()->create(['role' => 'parent']);
    $pendaftaran = Pendaftaran::factory()->create(['tahun_ajaran' => '2026/2027', 'status' => 'buka']);

    $siswa = Siswa::factory()->create([
        'user_id' => $parent->id,
        'tanggal_lahir' => '2024-07-01', // 2 years old (24 months) -> perlu_konfirmasi
    ]);

    $response = $this->actingAs($parent)->post(route('parent.siswa.pendaftaran.daftar', ['siswa' => $siswa->id, 'pendaftaran' => $pendaftaran->id]), [
        'data_declaration' => '1',
    ]);

    $response->assertRedirect();

    $detail = PendaftaranDetail::where('siswa_id', $siswa->id)->first();
    expect($detail)->not->toBeNull()
        ->and($detail->kelompok_rekomendasi)->toBe('perlu_konfirmasi');
});

/*
|--------------------------------------------------------------------------
| Final Group Confirmation Scenarios (14 - 18)
|--------------------------------------------------------------------------
*/

test('14. allows admin to assign final group', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $siswa = Siswa::factory()->create();
    $pendaftaran = Pendaftaran::factory()->create();
    $detail = PendaftaranDetail::create([
        'siswa_id' => $siswa->id,
        'pendaftaran_id' => $pendaftaran->id,
        'nomor_pendaftaran' => 'REG-1234',
        'status' => 'menunggu_verifikasi',
    ]);

    $response = $this->actingAs($admin)->patch(route('admin.verifikasi.kelompok', $detail->id), [
        'kelompok_final' => 'A',
    ]);

    $response->assertRedirect();
    $detail->refresh();
    expect($detail->kelompok_final)->toBe('A')
        ->and($detail->kelompok_ditetapkan_oleh)->toBe($admin->id)
        ->and($detail->kelompok_ditetapkan_at)->not->toBeNull();
});

test('15. allows super admin to assign final group', function () {
    $superAdmin = User::factory()->create(['role' => 'super_admin']);
    $siswa = Siswa::factory()->create();
    $pendaftaran = Pendaftaran::factory()->create();
    $detail = PendaftaranDetail::create([
        'siswa_id' => $siswa->id,
        'pendaftaran_id' => $pendaftaran->id,
        'nomor_pendaftaran' => 'REG-1234',
        'status' => 'menunggu_verifikasi',
    ]);

    $response = $this->actingAs($superAdmin)->patch(route('admin.verifikasi.kelompok', $detail->id), [
        'kelompok_final' => 'B',
    ]);

    $response->assertRedirect();
    $detail->refresh();
    expect($detail->kelompok_final)->toBe('B')
        ->and($detail->kelompok_ditetapkan_oleh)->toBe($superAdmin->id);
});

test('16. forbids parent from assigning final group', function () {
    $parent = User::factory()->create(['role' => 'parent']);
    $siswa = Siswa::factory()->create();
    $pendaftaran = Pendaftaran::factory()->create();
    $detail = PendaftaranDetail::create([
        'siswa_id' => $siswa->id,
        'pendaftaran_id' => $pendaftaran->id,
        'nomor_pendaftaran' => 'REG-1234',
        'status' => 'menunggu_verifikasi',
    ]);

    $response = $this->actingAs($parent)->patch(route('admin.verifikasi.kelompok', $detail->id), [
        'kelompok_final' => 'A',
    ]);

    $response->assertStatus(403);
});

test('17. records log in ActivityLog when final group is assigned', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $siswa = Siswa::factory()->create();
    $pendaftaran = Pendaftaran::factory()->create();
    $detail = PendaftaranDetail::create([
        'siswa_id' => $siswa->id,
        'pendaftaran_id' => $pendaftaran->id,
        'nomor_pendaftaran' => 'REG-1234',
        'status' => 'menunggu_verifikasi',
    ]);

    $this->actingAs($admin)->patch(route('admin.verifikasi.kelompok', $detail->id), [
        'kelompok_final' => 'B',
    ]);

    $log = ActivityLog::where('action', 'group_assigned')
        ->where('target_type', PendaftaranDetail::class)
        ->where('target_id', $detail->id)
        ->first();

    expect($log)->not->toBeNull()
        ->and($log->description)->toContain('menetapkan Kelompok final B');
});

test('18. logs assigner identity in detail record metadata fields', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $siswa = Siswa::factory()->create();
    $pendaftaran = Pendaftaran::factory()->create();
    $detail = PendaftaranDetail::create([
        'siswa_id' => $siswa->id,
        'pendaftaran_id' => $pendaftaran->id,
        'nomor_pendaftaran' => 'REG-1234',
        'status' => 'menunggu_verifikasi',
    ]);

    $this->actingAs($admin)->patch(route('admin.verifikasi.kelompok', $detail->id), [
        'kelompok_final' => 'A',
    ]);

    $detail->refresh();
    expect($detail->kelompokDitetapkanOleh->id)->toBe($admin->id)
        ->and($detail->kelompok_ditetapkan_at)->toBeInstanceOf(Carbon::class);
});

/*
|--------------------------------------------------------------------------
| Recalculation on Birthdate Changes Scenarios (19 - 21)
|--------------------------------------------------------------------------
*/

test('19. birthdate update recalculates snapshot for active registrations', function () {
    $parent = User::factory()->create(['role' => 'parent']);
    $pendaftaran = Pendaftaran::factory()->create(['tahun_ajaran' => '2026/2027']);

    $siswa = Siswa::factory()->create([
        'user_id' => $parent->id,
        'tanggal_lahir' => '2021-07-01', // 60 months (Group B)
    ]);

    $detail = PendaftaranDetail::create([
        'siswa_id' => $siswa->id,
        'pendaftaran_id' => $pendaftaran->id,
        'nomor_pendaftaran' => 'REG-1234',
        'usia_bulan_saat_acuan' => 60,
        'kelompok_rekomendasi' => 'B',
        'status' => 'pending', // active status
        'tanggal_acuan_usia' => '2026-07-01',
    ]);

    // Update birthdate to 2022-07-01 (48 months -> Group A)
    $siswa->update([
        'tanggal_lahir' => '2022-07-01',
    ]);

    $detail->refresh();
    expect($detail->usia_bulan_saat_acuan)->toBe(48)
        ->and($detail->kelompok_rekomendasi)->toBe('A');
});

test('20. birthdate update resets final group selection for active registrations', function () {
    $parent = User::factory()->create(['role' => 'parent']);
    $pendaftaran = Pendaftaran::factory()->create(['tahun_ajaran' => '2026/2027']);

    $siswa = Siswa::factory()->create([
        'user_id' => $parent->id,
        'tanggal_lahir' => '2021-07-01',
    ]);

    $detail = PendaftaranDetail::create([
        'siswa_id' => $siswa->id,
        'pendaftaran_id' => $pendaftaran->id,
        'nomor_pendaftaran' => 'REG-1234',
        'status' => 'menunggu_verifikasi', // active
        'kelompok_final' => 'B',
        'kelompok_ditetapkan_oleh' => User::factory()->create()->id,
        'kelompok_ditetapkan_at' => now(),
        'tanggal_acuan_usia' => '2026-07-01',
    ]);

    $siswa->update(['tanggal_lahir' => '2022-07-01']);

    $detail->refresh();
    expect($detail->kelompok_final)->toBeNull()
        ->and($detail->kelompok_ditetapkan_oleh)->toBeNull()
        ->and($detail->kelompok_ditetapkan_at)->toBeNull();
});

test('21. birthdate update does not affect finalized registrations', function () {
    $parent = User::factory()->create(['role' => 'parent']);
    $pendaftaran = Pendaftaran::factory()->create(['tahun_ajaran' => '2026/2027']);

    $siswa = Siswa::factory()->create([
        'user_id' => $parent->id,
        'tanggal_lahir' => '2021-07-01',
    ]);

    $detail = PendaftaranDetail::create([
        'siswa_id' => $siswa->id,
        'pendaftaran_id' => $pendaftaran->id,
        'nomor_pendaftaran' => 'REG-1234',
        'status' => 'diterima', // finalized
        'usia_bulan_saat_acuan' => 60,
        'kelompok_rekomendasi' => 'B',
        'kelompok_final' => 'B',
        'tanggal_acuan_usia' => '2026-07-01',
    ]);

    $siswa->update(['tanggal_lahir' => '2022-07-01']);

    $detail->refresh();
    expect($detail->usia_bulan_saat_acuan)->toBe(60)
        ->and($detail->kelompok_rekomendasi)->toBe('B')
        ->and($detail->kelompok_final)->toBe('B');
});

/*
|--------------------------------------------------------------------------
| Living Arrangement Validation Scenarios (22 - 26)
|--------------------------------------------------------------------------
*/

test('22. requires KTP Ayah and KTP Ibu when tinggal bersama orang tua', function () {
    $parent = User::factory()->create(['role' => 'parent']);

    $data = [
        'nama' => 'Budi',
        'nama_panggilan' => 'Budi',
        'jenis_kelamin' => 'L',
        'tempat_lahir' => 'Jakarta',
        'tanggal_lahir' => '2021-07-01',
        'agama' => 'Islam',
        'anak_ke' => 1,
        'jumlah_saudara' => 0,
        'no_telpon' => '081234567890',
        'jenis_tempat_tinggal' => 'Milik Sendiri',
        'alamat' => 'Jalan Merdeka No 1',
        'kelurahan' => 'Gambir',
        'kecamatan' => 'Gambir',
        'kota' => 'Jakarta Pusat',
        'provinsi' => 'DKI Jakarta',
        'kode_pos' => '10110',
        'no_kk' => '1234567890123456',
        'kepala_keluarga' => 'Hasan',
        'tinggal_bersama' => 'orang_tua',
        // Ayah & Ibu details
        'nama_ayah' => 'Hasan',
        'nik_ayah' => '1234567890123456',
        'tanggal_lahir_ayah' => '1985-05-05',
        'pendidikan_ayah' => 'S1',
        'pekerjaan_ayah' => 'PNS',
        'penghasilan_ayah' => '3-5 Juta',
        'nama_ibu' => 'Siti',
        'nik_ibu' => '1234567890123457',
        'tanggal_lahir_ibu' => '1988-08-08',
        'pendidikan_ibu' => 'S1',
        'pekerjaan_ibu' => 'Karyawan Swasta',
        'penghasilan_ibu' => '3-5 Juta',
        // Files KK, Akta, Foto
        'foto' => UploadedFile::fake()->image('foto.jpg'),
        'foto_kk' => UploadedFile::fake()->image('kk.jpg'),
        'foto_akta' => UploadedFile::fake()->image('akta.jpg'),
        // KTP files missing
    ];

    $response = $this->actingAs($parent)->post(route('parent.siswa.store'), $data);
    $response->assertSessionHasErrors(['foto_ktp_ayah', 'foto_ktp_ibu']);
});

test('23. requires guardian details and KTP Wali when tinggal bersama wali', function () {
    $parent = User::factory()->create(['role' => 'parent']);

    $data = [
        'nama' => 'Budi',
        'nama_panggilan' => 'Budi',
        'jenis_kelamin' => 'L',
        'tempat_lahir' => 'Jakarta',
        'tanggal_lahir' => '2021-07-01',
        'agama' => 'Islam',
        'anak_ke' => 1,
        'jumlah_saudara' => 0,
        'no_telpon' => '081234567890',
        'jenis_tempat_tinggal' => 'Milik Sendiri',
        'alamat' => 'Jalan Merdeka No 1',
        'kelurahan' => 'Gambir',
        'kecamatan' => 'Gambir',
        'kota' => 'Jakarta Pusat',
        'provinsi' => 'DKI Jakarta',
        'kode_pos' => '10110',
        'no_kk' => '1234567890123456',
        'kepala_keluarga' => 'Hasan',
        'tinggal_bersama' => 'wali',
        // Ayah & Ibu details
        'nama_ayah' => 'Hasan',
        'nik_ayah' => '1234567890123456',
        'tanggal_lahir_ayah' => '1985-05-05',
        'pendidikan_ayah' => 'S1',
        'pekerjaan_ayah' => 'PNS',
        'penghasilan_ayah' => '3-5 Juta',
        'nama_ibu' => 'Siti',
        'nik_ibu' => '1234567890123457',
        'tanggal_lahir_ibu' => '1988-08-08',
        'pendidikan_ibu' => 'S1',
        'pekerjaan_ibu' => 'Karyawan Swasta',
        'penghasilan_ibu' => '3-5 Juta',
        // Wali details missing
        // Files KK, Akta, Foto
        'foto' => UploadedFile::fake()->image('foto.jpg'),
        'foto_kk' => UploadedFile::fake()->image('kk.jpg'),
        'foto_akta' => UploadedFile::fake()->image('akta.jpg'),
    ];

    $response = $this->actingAs($parent)->post(route('parent.siswa.store'), $data);
    $response->assertSessionHasErrors([
        'nama_wali',
        'nik_wali',
        'hubungan_wali',
        'no_telpon_wali',
        'foto_ktp_wali',
    ]);
});

test('24. KTP Wali is optional/ignored when tinggal bersama orang tua', function () {
    $parent = User::factory()->create(['role' => 'parent']);

    $data = [
        'nama' => 'Budi',
        'nama_panggilan' => 'Budi',
        'jenis_kelamin' => 'L',
        'tempat_lahir' => 'Jakarta',
        'tanggal_lahir' => '2021-07-01',
        'agama' => 'Islam',
        'anak_ke' => 1,
        'jumlah_saudara' => 0,
        'no_telpon' => '081234567890',
        'jenis_tempat_tinggal' => 'Milik Sendiri',
        'alamat' => 'Jalan Merdeka No 1',
        'kelurahan' => 'Gambir',
        'kecamatan' => 'Gambir',
        'kota' => 'Jakarta Pusat',
        'provinsi' => 'DKI Jakarta',
        'kode_pos' => '10110',
        'no_kk' => '1234567890123456',
        'kepala_keluarga' => 'Hasan',
        'tinggal_bersama' => 'orang_tua',
        'nama_ayah' => 'Hasan',
        'nik_ayah' => '1234567890123456',
        'tanggal_lahir_ayah' => '1985-05-05',
        'pendidikan_ayah' => 'S1',
        'pekerjaan_ayah' => 'PNS',
        'penghasilan_ayah' => '3-5 Juta',
        'nama_ibu' => 'Siti',
        'nik_ibu' => '1234567890123457',
        'tanggal_lahir_ibu' => '1988-08-08',
        'pendidikan_ibu' => 'S1',
        'pekerjaan_ibu' => 'Karyawan Swasta',
        'penghasilan_ibu' => '3-5 Juta',
        'foto' => UploadedFile::fake()->image('foto.jpg'),
        'foto_kk' => UploadedFile::fake()->image('kk.jpg'),
        'foto_akta' => UploadedFile::fake()->image('akta.jpg'),
        'foto_ktp_ayah' => UploadedFile::fake()->image('ktp_a.jpg'),
        'foto_ktp_ibu' => UploadedFile::fake()->image('ktp_i.jpg'),
        // no KTP Wali
    ];

    $response = $this->actingAs($parent)->post(route('parent.siswa.store'), $data);
    $response->assertSessionHasNoErrors();
});

test('25. KTP Ayah & Ibu are optional/ignored when tinggal bersama wali', function () {
    $parent = User::factory()->create(['role' => 'parent']);

    $data = [
        'nama' => 'Budi',
        'nama_panggilan' => 'Budi',
        'jenis_kelamin' => 'L',
        'tempat_lahir' => 'Jakarta',
        'tanggal_lahir' => '2021-07-01',
        'agama' => 'Islam',
        'anak_ke' => 1,
        'jumlah_saudara' => 0,
        'no_telpon' => '081234567890',
        'jenis_tempat_tinggal' => 'Milik Sendiri',
        'alamat' => 'Jalan Merdeka No 1',
        'kelurahan' => 'Gambir',
        'kecamatan' => 'Gambir',
        'kota' => 'Jakarta Pusat',
        'provinsi' => 'DKI Jakarta',
        'kode_pos' => '10110',
        'no_kk' => '1234567890123456',
        'kepala_keluarga' => 'Hasan',
        'tinggal_bersama' => 'wali',
        'nama_ayah' => 'Hasan',
        'nik_ayah' => '1234567890123456',
        'tanggal_lahir_ayah' => '1985-05-05',
        'pendidikan_ayah' => 'S1',
        'pekerjaan_ayah' => 'PNS',
        'penghasilan_ayah' => '3-5 Juta',
        'nama_ibu' => 'Siti',
        'nik_ibu' => '1234567890123457',
        'tanggal_lahir_ibu' => '1988-08-08',
        'pendidikan_ibu' => 'S1',
        'pekerjaan_ibu' => 'Karyawan Swasta',
        'penghasilan_ibu' => '3-5 Juta',
        'nama_wali' => 'Wawan',
        'nik_wali' => '1234567890123459',
        'hubungan_wali' => 'Paman',
        'no_telpon_wali' => '081234567890',
        'foto' => UploadedFile::fake()->image('foto.jpg'),
        'foto_kk' => UploadedFile::fake()->image('kk.jpg'),
        'foto_akta' => UploadedFile::fake()->image('akta.jpg'),
        'foto_ktp_wali' => UploadedFile::fake()->image('ktp_w.jpg'),
        // no KTP Ayah or KTP Ibu
    ];

    $response = $this->actingAs($parent)->post(route('parent.siswa.store'), $data);
    $response->assertSessionHasNoErrors();
});

test('26. validation allows update without re-uploading documents', function () {
    $parent = User::factory()->create(['role' => 'parent']);

    $siswa = Siswa::factory()->create([
        'user_id' => $parent->id,
        'tinggal_bersama' => 'orang_tua',
        'foto_ktp_ayah' => 'siswa/ktp_ayah/existing_ayah.jpg',
        'foto_ktp_ibu' => 'siswa/ktp_ibu/existing_ibu.jpg',
    ]);

    $data = [
        'nama' => 'Budi Baru',
        'nama_panggilan' => 'Budi',
        'jenis_kelamin' => 'L',
        'tempat_lahir' => 'Jakarta',
        'tanggal_lahir' => '2021-07-01',
        'agama' => 'Islam',
        'anak_ke' => 1,
        'jumlah_saudara' => 0,
        'no_telpon' => '081234567890',
        'jenis_tempat_tinggal' => 'Milik Sendiri',
        'alamat' => 'Jalan Merdeka No 1',
        'kelurahan' => 'Gambir',
        'kecamatan' => 'Gambir',
        'kota' => 'Jakarta Pusat',
        'provinsi' => 'DKI Jakarta',
        'kode_pos' => '10110',
        'no_kk' => '1234567890123456',
        'kepala_keluarga' => 'Hasan',
        'tinggal_bersama' => 'orang_tua',
        'nama_ayah' => 'Hasan',
        'nik_ayah' => '1234567890123456',
        'tanggal_lahir_ayah' => '1985-05-05',
        'pendidikan_ayah' => 'S1',
        'pekerjaan_ayah' => 'PNS',
        'penghasilan_ayah' => '3-5 Juta',
        'nama_ibu' => 'Siti',
        'nik_ibu' => '1234567890123457',
        'tanggal_lahir_ibu' => '1988-08-08',
        'pendidikan_ibu' => 'S1',
        'pekerjaan_ibu' => 'Karyawan Swasta',
        'penghasilan_ibu' => '3-5 Juta',
        // KTP files not provided in update request, but already present in db
    ];

    $response = $this->actingAs($parent)->put(route('parent.siswa.update', $siswa->id), $data);
    $response->assertSessionHasNoErrors();
    expect($siswa->fresh()->nama)->toBe('Budi Baru');
});

/*
|--------------------------------------------------------------------------
| Transitions & Cleanups Scenarios (27 - 30)
|--------------------------------------------------------------------------
*/

test('27. transition from orang_tua to wali deletes KTP Ayah & Ibu from disk', function () {
    $parent = User::factory()->create(['role' => 'parent']);

    // Put fake files on local disk
    Storage::disk('local')->put('siswa/ktp_ayah/ayah.jpg', 'fake-ayah');
    Storage::disk('local')->put('siswa/ktp_ibu/ibu.jpg', 'fake-ibu');

    $siswa = Siswa::factory()->create([
        'user_id' => $parent->id,
        'tinggal_bersama' => 'orang_tua',
        'foto_ktp_ayah' => 'siswa/ktp_ayah/ayah.jpg',
        'foto_ktp_ibu' => 'siswa/ktp_ibu/ibu.jpg',
    ]);

    $data = [
        'nama' => 'Budi',
        'nama_panggilan' => 'Budi',
        'jenis_kelamin' => 'L',
        'tempat_lahir' => 'Jakarta',
        'tanggal_lahir' => '2021-07-01',
        'agama' => 'Islam',
        'anak_ke' => 1,
        'jumlah_saudara' => 0,
        'no_telpon' => '081234567890',
        'jenis_tempat_tinggal' => 'Milik Sendiri',
        'alamat' => 'Jalan Merdeka No 1',
        'kelurahan' => 'Gambir',
        'kecamatan' => 'Gambir',
        'kota' => 'Jakarta Pusat',
        'provinsi' => 'DKI Jakarta',
        'kode_pos' => '10110',
        'no_kk' => '1234567890123456',
        'kepala_keluarga' => 'Hasan',
        'tinggal_bersama' => 'wali', // transitioning to wali
        'nama_ayah' => 'Hasan',
        'nik_ayah' => '1234567890123456',
        'tanggal_lahir_ayah' => '1985-05-05',
        'pendidikan_ayah' => 'S1',
        'pekerjaan_ayah' => 'PNS',
        'penghasilan_ayah' => '3-5 Juta',
        'nama_ibu' => 'Siti',
        'nik_ibu' => '1234567890123457',
        'tanggal_lahir_ibu' => '1988-08-08',
        'pendidikan_ibu' => 'S1',
        'pekerjaan_ibu' => 'Karyawan Swasta',
        'penghasilan_ibu' => '3-5 Juta',
        'nama_wali' => 'Wawan',
        'nik_wali' => '1234567890123459',
        'hubungan_wali' => 'Paman',
        'no_telpon_wali' => '081234567890',
        'foto_ktp_wali' => UploadedFile::fake()->image('ktp_w.jpg'),
    ];

    $response = $this->actingAs($parent)->put(route('parent.siswa.update', $siswa->id), $data);
    $response->assertSessionHasNoErrors();

    // Check files are deleted from local disk
    Storage::disk('local')->assertMissing('siswa/ktp_ayah/ayah.jpg');
    Storage::disk('local')->assertMissing('siswa/ktp_ibu/ibu.jpg');

    $siswa->refresh();
    expect($siswa->foto_ktp_ayah)->toBeNull()
        ->and($siswa->foto_ktp_ibu)->toBeNull();
});

test('28. transition from wali to orang_tua deletes KTP Wali from disk', function () {
    $parent = User::factory()->create(['role' => 'parent']);

    Storage::disk('local')->put('siswa/ktp_wali/wali.jpg', 'fake-wali');

    $siswa = Siswa::factory()->create([
        'user_id' => $parent->id,
        'tinggal_bersama' => 'wali',
        'foto_ktp_wali' => 'siswa/ktp_wali/wali.jpg',
        'nama_wali' => 'Wawan',
        'nik_wali' => '1234567890123459',
        'hubungan_wali' => 'Paman',
        'no_telpon_wali' => '081234567890',
    ]);

    $data = [
        'nama' => 'Budi',
        'nama_panggilan' => 'Budi',
        'jenis_kelamin' => 'L',
        'tempat_lahir' => 'Jakarta',
        'tanggal_lahir' => '2021-07-01',
        'agama' => 'Islam',
        'anak_ke' => 1,
        'jumlah_saudara' => 0,
        'no_telpon' => '081234567890',
        'jenis_tempat_tinggal' => 'Milik Sendiri',
        'alamat' => 'Jalan Merdeka No 1',
        'kelurahan' => 'Gambir',
        'kecamatan' => 'Gambir',
        'kota' => 'Jakarta Pusat',
        'provinsi' => 'DKI Jakarta',
        'kode_pos' => '10110',
        'no_kk' => '1234567890123456',
        'kepala_keluarga' => 'Hasan',
        'tinggal_bersama' => 'orang_tua', // transitioning to orang_tua
        'nama_ayah' => 'Hasan',
        'nik_ayah' => '1234567890123456',
        'tanggal_lahir_ayah' => '1985-05-05',
        'pendidikan_ayah' => 'S1',
        'pekerjaan_ayah' => 'PNS',
        'penghasilan_ayah' => '3-5 Juta',
        'nama_ibu' => 'Siti',
        'nik_ibu' => '1234567890123457',
        'tanggal_lahir_ibu' => '1988-08-08',
        'pendidikan_ibu' => 'S1',
        'pekerjaan_ibu' => 'Karyawan Swasta',
        'penghasilan_ibu' => '3-5 Juta',
        'foto_ktp_ayah' => UploadedFile::fake()->image('ktp_a.jpg'),
        'foto_ktp_ibu' => UploadedFile::fake()->image('ktp_i.jpg'),
    ];

    $response = $this->actingAs($parent)->put(route('parent.siswa.update', $siswa->id), $data);
    $response->assertSessionHasNoErrors();

    Storage::disk('local')->assertMissing('siswa/ktp_wali/wali.jpg');

    $siswa->refresh();
    expect($siswa->foto_ktp_wali)->toBeNull()
        ->and($siswa->nama_wali)->toBeNull()
        ->and($siswa->nik_wali)->toBeNull();
});

test('29. deleting a child deletes all their KTP files from disk', function () {
    $parent = User::factory()->create(['role' => 'parent']);

    Storage::disk('local')->put('siswa/ktp_ayah/ayah.jpg', 'fake-ayah');
    Storage::disk('local')->put('siswa/ktp_ibu/ibu.jpg', 'fake-ibu');
    Storage::disk('public')->put('siswa/foto/anak.jpg', 'fake-foto');

    $siswa = Siswa::factory()->create([
        'user_id' => $parent->id,
        'tinggal_bersama' => 'orang_tua',
        'foto' => 'siswa/foto/anak.jpg',
        'foto_ktp_ayah' => 'siswa/ktp_ayah/ayah.jpg',
        'foto_ktp_ibu' => 'siswa/ktp_ibu/ibu.jpg',
    ]);

    $response = $this->actingAs($parent)->delete(route('parent.siswa.destroy', $siswa->id));
    $response->assertRedirect();

    Storage::disk('local')->assertMissing('siswa/ktp_ayah/ayah.jpg');
    Storage::disk('local')->assertMissing('siswa/ktp_ibu/ibu.jpg');
    Storage::disk('public')->assertMissing('siswa/foto/anak.jpg');

    $this->assertDatabaseMissing('spmb_siswa', ['id' => $siswa->id]);
});

test('30. deleting parent account cleans up all children KTP files', function () {
    $parent = User::factory()->create(['role' => 'parent', 'password' => bcrypt('password')]);

    Storage::disk('local')->put('siswa/ktp_ayah/ayah.jpg', 'fake-ayah');
    Storage::disk('local')->put('siswa/ktp_ibu/ibu.jpg', 'fake-ibu');

    $siswa = Siswa::factory()->create([
        'user_id' => $parent->id,
        'tinggal_bersama' => 'orang_tua',
        'foto_ktp_ayah' => 'siswa/ktp_ayah/ayah.jpg',
        'foto_ktp_ibu' => 'siswa/ktp_ibu/ibu.jpg',
    ]);

    $response = $this->actingAs($parent)->delete('/profile', [
        'password' => 'password',
    ]);

    $response->assertRedirect('/');

    Storage::disk('local')->assertMissing('siswa/ktp_ayah/ayah.jpg');
    Storage::disk('local')->assertMissing('siswa/ktp_ibu/ibu.jpg');

    $this->assertDatabaseMissing('users', ['id' => $parent->id]);
    $this->assertDatabaseMissing('spmb_siswa', ['id' => $siswa->id]);
});

test('31. soft delete admin keeps files, restore works, and force delete destroys files', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $superAdmin = User::factory()->create(['role' => 'super_admin']);

    Storage::disk('public')->put('siswa/foto/anak.jpg', 'fake-foto');
    Storage::disk('local')->put('siswa/ktp_ayah/ayah.jpg', 'fake-ayah');

    $siswa = Siswa::factory()->create([
        'tinggal_bersama' => 'orang_tua',
        'foto' => 'siswa/foto/anak.jpg',
        'foto_ktp_ayah' => 'siswa/ktp_ayah/ayah.jpg',
    ]);

    // 1. Soft Delete
    $response = $this->actingAs($admin)->delete(route('admin.siswa.destroy', $siswa->id), [
        'deleted_reason' => 'Test soft delete',
    ]);
    $response->assertRedirect();

    // Assert soft deleted in database
    $this->assertSoftDeleted('spmb_siswa', ['id' => $siswa->id]);

    // Assert files STILL exist on storage
    Storage::disk('public')->assertExists('siswa/foto/anak.jpg');
    Storage::disk('local')->assertExists('siswa/ktp_ayah/ayah.jpg');

    // 2. Restore
    $response = $this->actingAs($superAdmin)->patch(route('admin.siswa.restore', $siswa->id));
    $response->assertRedirect();

    // Assert restored
    expect($siswa->fresh()->deleted_at)->toBeNull();
    Storage::disk('public')->assertExists('siswa/foto/anak.jpg');
    Storage::disk('local')->assertExists('siswa/ktp_ayah/ayah.jpg');

    // Soft delete again to prepare for force delete
    $siswa->delete();

    // 3. Force Delete
    $response = $this->actingAs($superAdmin)->delete(route('admin.siswa.force-delete', $siswa->id));
    $response->assertRedirect();

    // Assert database missing and files deleted
    $this->assertDatabaseMissing('spmb_siswa', ['id' => $siswa->id]);
    Storage::disk('public')->assertMissing('siswa/foto/anak.jpg');
    Storage::disk('local')->assertMissing('siswa/ktp_ayah/ayah.jpg');
});

test('32. update orang_tua tanpa file lama dan tanpa upload baru ditolak', function () {
    $parent = User::factory()->create(['role' => 'parent']);
    $siswa = Siswa::factory()->create([
        'user_id' => $parent->id,
        'tinggal_bersama' => 'orang_tua',
        'foto_ktp_ayah' => null,
        'foto_ktp_ibu' => null,
    ]);

    $data = [
        'nama' => 'Budi Baru',
        'nama_panggilan' => 'Budi',
        'jenis_kelamin' => 'L',
        'tempat_lahir' => 'Jakarta',
        'tanggal_lahir' => '2021-07-01',
        'agama' => 'Islam',
        'anak_ke' => 1,
        'jumlah_saudara' => 0,
        'no_telpon' => '081234567890',
        'jenis_tempat_tinggal' => 'Milik Sendiri',
        'alamat' => 'Jalan Merdeka No 1',
        'kelurahan' => 'Gambir',
        'kecamatan' => 'Gambir',
        'kota' => 'Jakarta Pusat',
        'provinsi' => 'DKI Jakarta',
        'kode_pos' => '10110',
        'no_kk' => '1234567890123456',
        'kepala_keluarga' => 'Hasan',
        'tinggal_bersama' => 'orang_tua',
        'nama_ayah' => 'Hasan',
        'nik_ayah' => '1234567890123456',
        'tanggal_lahir_ayah' => '1985-05-05',
        'pendidikan_ayah' => 'S1',
        'pekerjaan_ayah' => 'PNS',
        'penghasilan_ayah' => '3-5 Juta',
        'nama_ibu' => 'Siti',
        'nik_ibu' => '1234567890123457',
        'tanggal_lahir_ibu' => '1988-08-08',
        'pendidikan_ibu' => 'S1',
        'pekerjaan_ibu' => 'Karyawan Swasta',
        'penghasilan_ibu' => '3-5 Juta',
    ];

    $response = $this->actingAs($parent)->put(route('parent.siswa.update', $siswa->id), $data);
    $response->assertSessionHasErrors(['foto_ktp_ayah', 'foto_ktp_ibu']);
});

test('33. update wali tanpa file lama dan tanpa upload baru ditolak', function () {
    $parent = User::factory()->create(['role' => 'parent']);
    $siswa = Siswa::factory()->create([
        'user_id' => $parent->id,
        'tinggal_bersama' => 'wali',
        'foto_ktp_wali' => null,
    ]);

    $data = [
        'nama' => 'Budi Baru',
        'nama_panggilan' => 'Budi',
        'jenis_kelamin' => 'L',
        'tempat_lahir' => 'Jakarta',
        'tanggal_lahir' => '2021-07-01',
        'agama' => 'Islam',
        'anak_ke' => 1,
        'jumlah_saudara' => 0,
        'no_telpon' => '081234567890',
        'jenis_tempat_tinggal' => 'Milik Sendiri',
        'alamat' => 'Jalan Merdeka No 1',
        'kelurahan' => 'Gambir',
        'kecamatan' => 'Gambir',
        'kota' => 'Jakarta Pusat',
        'provinsi' => 'DKI Jakarta',
        'kode_pos' => '10110',
        'no_kk' => '1234567890123456',
        'kepala_keluarga' => 'Hasan',
        'tinggal_bersama' => 'wali',
        'nama_ayah' => 'Hasan',
        'nik_ayah' => '1234567890123456',
        'tanggal_lahir_ayah' => '1985-05-05',
        'pendidikan_ayah' => 'S1',
        'pekerjaan_ayah' => 'PNS',
        'penghasilan_ayah' => '3-5 Juta',
        'nama_ibu' => 'Siti',
        'nik_ibu' => '1234567890123457',
        'tanggal_lahir_ibu' => '1988-08-08',
        'pendidikan_ibu' => 'S1',
        'pekerjaan_ibu' => 'Karyawan Swasta',
        'penghasilan_ibu' => '3-5 Juta',
        'nama_wali' => 'Wawan',
        'nik_wali' => '1234567890123459',
        'hubungan_wali' => 'Paman',
        'no_telpon_wali' => '081234567890',
    ];

    $response = $this->actingAs($parent)->put(route('parent.siswa.update', $siswa->id), $data);
    $response->assertSessionHasErrors(['foto_ktp_wali']);
});

test('34. pergantian jenis tinggal tanpa dokumen tujuan ditolak', function () {
    $parent = User::factory()->create(['role' => 'parent']);

    $siswa = Siswa::factory()->create([
        'user_id' => $parent->id,
        'tinggal_bersama' => 'orang_tua',
        'foto_ktp_ayah' => 'siswa/ktp_ayah/existing_ayah.jpg',
        'foto_ktp_ibu' => 'siswa/ktp_ibu/existing_ibu.jpg',
    ]);

    $data = [
        'nama' => 'Budi Baru',
        'nama_panggilan' => 'Budi',
        'jenis_kelamin' => 'L',
        'tempat_lahir' => 'Jakarta',
        'tanggal_lahir' => '2021-07-01',
        'agama' => 'Islam',
        'anak_ke' => 1,
        'jumlah_saudara' => 0,
        'no_telpon' => '081234567890',
        'jenis_tempat_tinggal' => 'Milik Sendiri',
        'alamat' => 'Jalan Merdeka No 1',
        'kelurahan' => 'Gambir',
        'kecamatan' => 'Gambir',
        'kota' => 'Jakarta Pusat',
        'provinsi' => 'DKI Jakarta',
        'kode_pos' => '10110',
        'no_kk' => '1234567890123456',
        'kepala_keluarga' => 'Hasan',
        'tinggal_bersama' => 'wali', // transition to wali
        'nama_ayah' => 'Hasan',
        'nik_ayah' => '1234567890123456',
        'tanggal_lahir_ayah' => '1985-05-05',
        'pendidikan_ayah' => 'S1',
        'pekerjaan_ayah' => 'PNS',
        'penghasilan_ayah' => '3-5 Juta',
        'nama_ibu' => 'Siti',
        'nik_ibu' => '1234567890123457',
        'tanggal_lahir_ibu' => '1988-08-08',
        'pendidikan_ibu' => 'S1',
        'pekerjaan_ibu' => 'Karyawan Swasta',
        'penghasilan_ibu' => '3-5 Juta',
        'nama_wali' => 'Wawan',
        'nik_wali' => '1234567890123459',
        'hubungan_wali' => 'Paman',
        'no_telpon_wali' => '081234567890',
        // no KTP Wali provided
    ];

    $response = $this->actingAs($parent)->put(route('parent.siswa.update', $siswa->id), $data);
    $response->assertSessionHasErrors(['foto_ktp_wali']);
});
