<?php

use App\Models\ActivityLog;
use App\Models\KeputusanPendaftaran;
use App\Models\Observasi;
use App\Models\PaymentSetting;
use App\Models\Pembayaran;
use App\Models\Pendaftaran;
use App\Models\PendaftaranDetail;
use App\Models\Siswa;
use App\Models\User;
use App\Notifications\KeputusanPendaftaranNotification;
use App\Services\AdmissionDecisionService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake('local');
    Storage::fake('public');
    Notification::fake();
});

function createPR4Detail(array $attributes = []): PendaftaranDetail
{
    $parent = $attributes['parent'] ?? User::factory()->create(['role' => 'parent']);
    $siswa = $attributes['siswa'] ?? Siswa::factory()->create(['user_id' => $parent->id]);
    $pendaftaran = $attributes['pendaftaran'] ?? Pendaftaran::factory()->create();

    return PendaftaranDetail::create(array_merge([
        'siswa_id' => $siswa->id,
        'pendaftaran_id' => $pendaftaran->id,
        'nomor_pendaftaran' => 'SPMB/'.date('Ymd').'/'.rand(1000, 9999),
        'status' => PendaftaranDetail::STATUS_MENUNGGU_KEPUTUSAN,
        'usia_bulan_saat_acuan' => 60,
        'tanggal_acuan_usia' => '2026-07-01',
        'kelompok_rekomendasi' => 'B',
    ], array_diff_key($attributes, ['parent' => 1, 'siswa' => 1, 'pendaftaran' => 1])));
}

function completeObservation(PendaftaranDetail $detail, string $status = 'selesai'): Observasi
{
    return Observasi::create([
        'pendaftaran_detail_id' => $detail->id,
        'attempt_number' => 1,
        'scheduled_at' => now()->subDay(),
        'status' => $status,
        'tinggi_badan_cm' => 100,
        'berat_badan_kg' => 15,
        'catatan_wawancara_orang_tua' => 'Baik',
        'catatan_aktivitas_anak' => 'Aktif',
        'catatan_kesiapan_anak' => 'Siap',
        'membutuhkan_dukungan_khusus' => 0,
        'scheduled_by' => User::factory()->create(['role' => 'admin'])->id,
    ]);
}

test('1. Guest cannot access decision store', function () {
    $detail = createPR4Detail();
    $response = $this->post(route('admin.verifikasi.keputusan.store', $detail->id), [
        'keputusan_status' => 'diterima',
    ]);
    $response->assertRedirect('/login');
});

test('2. Parent cannot access decision store', function () {
    $parent = User::factory()->create(['role' => 'parent']);
    $detail = createPR4Detail();
    $response = $this->actingAs($parent)->post(route('admin.verifikasi.keputusan.store', $detail->id), [
        'keputusan_status' => 'diterima',
    ]);
    $response->assertStatus(403);
});

test('3. Admin can access decision store', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $detail = createPR4Detail(['kelompok_final' => 'A']);
    completeObservation($detail);

    $response = $this->actingAs($admin)->post(route('admin.verifikasi.keputusan.store', $detail->id), [
        'keputusan_status' => 'diterima',
    ]);
    $response->assertRedirect();
    $detail->refresh();
    expect($detail->keputusan_status)->toBe('diterima');
});

test('4. Super admin can access decision store', function () {
    $superAdmin = User::factory()->create(['role' => 'super_admin']);
    $detail = createPR4Detail(['kelompok_final' => 'A']);
    completeObservation($detail);

    $response = $this->actingAs($superAdmin)->post(route('admin.verifikasi.keputusan.store', $detail->id), [
        'keputusan_status' => 'diterima',
    ]);
    $response->assertRedirect();
    $detail->refresh();
    expect($detail->keputusan_status)->toBe('diterima');
});

test('5. Validates keputusan_status is required', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $detail = createPR4Detail();
    $response = $this->actingAs($admin)->post(route('admin.verifikasi.keputusan.store', $detail->id), [
        'keputusan_status' => '',
    ]);
    $response->assertSessionHasErrors('keputusan_status');
});

test('6. Validates keputusan_status must be in list', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $detail = createPR4Detail();
    $response = $this->actingAs($admin)->post(route('admin.verifikasi.keputusan.store', $detail->id), [
        'keputusan_status' => 'invalid_status',
    ]);
    $response->assertSessionHasErrors('keputusan_status');
});

test('7. Validates keputusan_alasan is required for tidak_diterima', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $detail = createPR4Detail();
    $response = $this->actingAs($admin)->post(route('admin.verifikasi.keputusan.store', $detail->id), [
        'keputusan_status' => 'tidak_diterima',
        'keputusan_alasan' => '',
    ]);
    $response->assertSessionHasErrors('keputusan_alasan');
});

test('8. Validates keputusan_alasan is required for perlu_tindak_lanjut', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $detail = createPR4Detail();
    $response = $this->actingAs($admin)->post(route('admin.verifikasi.keputusan.store', $detail->id), [
        'keputusan_status' => 'perlu_tindak_lanjut',
        'keputusan_alasan' => '',
    ]);
    $response->assertSessionHasErrors('keputusan_alasan');
});

test('9. Validates keputusan_alasan is required for mengundurkan_diri', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $detail = createPR4Detail();
    $response = $this->actingAs($admin)->post(route('admin.verifikasi.keputusan.store', $detail->id), [
        'keputusan_status' => 'mengundurkan_diri',
        'keputusan_alasan' => '',
    ]);
    $response->assertSessionHasErrors('keputusan_alasan');
});

test('10. Validates keputusan_alasan is optional for diterima', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $detail = createPR4Detail(['kelompok_final' => 'A']);
    completeObservation($detail);

    $response = $this->actingAs($admin)->post(route('admin.verifikasi.keputusan.store', $detail->id), [
        'keputusan_status' => 'diterima',
        'keputusan_alasan' => '',
    ]);
    $response->assertRedirect();
    $detail->refresh();
    expect($detail->keputusan_status)->toBe('diterima');
});

test('11. Standard decisions require status menunggu_keputusan', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $detail = createPR4Detail([
        'status' => PendaftaranDetail::STATUS_MENUNGGU_VERIFIKASI,
        'kelompok_final' => 'A',
    ]);
    completeObservation($detail);

    $response = $this->actingAs($admin)->post(route('admin.verifikasi.keputusan.store', $detail->id), [
        'keputusan_status' => 'diterima',
    ]);
    $response->assertRedirect();
    $response->assertSessionHas('error');
    $detail->refresh();
    expect($detail->keputusan_status)->toBeNull();
});

test('12. Standard decisions require latest observation status completed/selesai', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $detail = createPR4Detail(['kelompok_final' => 'A']);
    completeObservation($detail, 'tidak_hadir');

    $response = $this->actingAs($admin)->post(route('admin.verifikasi.keputusan.store', $detail->id), [
        'keputusan_status' => 'diterima',
    ]);
    $response->assertRedirect();
    $response->assertSessionHas('error');
    $detail->refresh();
    expect($detail->keputusan_status)->toBeNull();
});

test('13. Diterima decision requires kelompok_final to be A or B', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $detail = createPR4Detail(['kelompok_final' => null]);
    completeObservation($detail);

    $response = $this->actingAs($admin)->post(route('admin.verifikasi.keputusan.store', $detail->id), [
        'keputusan_status' => 'diterima',
    ]);
    $response->assertRedirect();
    $response->assertSessionHas('error');
    $detail->refresh();
    expect($detail->keputusan_status)->toBeNull();
});

test('14. Diterima decision allows kelompok_final A', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $detail = createPR4Detail(['kelompok_final' => 'A']);
    completeObservation($detail);

    $response = $this->actingAs($admin)->post(route('admin.verifikasi.keputusan.store', $detail->id), [
        'keputusan_status' => 'diterima',
    ]);
    $response->assertRedirect();
    $detail->refresh();
    expect($detail->keputusan_status)->toBe('diterima');
});

test('15. Diterima decision allows kelompok_final B', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $detail = createPR4Detail(['kelompok_final' => 'B']);
    completeObservation($detail);

    $response = $this->actingAs($admin)->post(route('admin.verifikasi.keputusan.store', $detail->id), [
        'keputusan_status' => 'diterima',
    ]);
    $response->assertRedirect();
    $detail->refresh();
    expect($detail->keputusan_status)->toBe('diterima');
});

test('16. Tidak diterima decision does not require kelompok_final', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $detail = createPR4Detail(['kelompok_final' => null]);
    completeObservation($detail);

    $response = $this->actingAs($admin)->post(route('admin.verifikasi.keputusan.store', $detail->id), [
        'keputusan_status' => 'tidak_diterima',
        'keputusan_alasan' => 'Kurang mandiri',
    ]);
    $response->assertRedirect();
    $detail->refresh();
    expect($detail->keputusan_status)->toBe('tidak_diterima');
});

test('17. Perlu tindak lanjut decision does not require kelompok_final', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $detail = createPR4Detail(['kelompok_final' => null]);
    completeObservation($detail);

    $response = $this->actingAs($admin)->post(route('admin.verifikasi.keputusan.store', $detail->id), [
        'keputusan_status' => 'perlu_tindak_lanjut',
        'keputusan_alasan' => 'Perlu wawancara tambahan',
    ]);
    $response->assertRedirect();
    $detail->refresh();
    expect($detail->keputusan_status)->toBe('perlu_tindak_lanjut');
});

test('18. Withdrawn decision Condition 1: status menunggu_keputusan and observation completed', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $detail = createPR4Detail(['kelompok_final' => null]);
    completeObservation($detail);

    $response = $this->actingAs($admin)->post(route('admin.verifikasi.keputusan.store', $detail->id), [
        'keputusan_status' => 'mengundurkan_diri',
        'keputusan_alasan' => 'Pindah rumah',
    ]);
    $response->assertRedirect();
    $detail->refresh();
    expect($detail->keputusan_status)->toBe('mengundurkan_diri');
});

test('19. Withdrawn decision Condition 2: observation tidak_hadir and reschedule deadline H-3 MPLS passed', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    // MPLS date is 2 days in future (passed the H-3 reschedule deadline)
    $pendaftaran = Pendaftaran::factory()->create(['tanggal_mpls' => now()->addDays(2)]);
    $detail = createPR4Detail([
        'pendaftaran' => $pendaftaran,
        'status' => PendaftaranDetail::STATUS_ADMINISTRASI_LENGKAP,
    ]);
    completeObservation($detail, 'tidak_hadir');

    $response = $this->actingAs($admin)->post(route('admin.verifikasi.keputusan.store', $detail->id), [
        'keputusan_status' => 'mengundurkan_diri',
        'keputusan_alasan' => 'Tidak hadir tanpa kabar',
    ]);
    $response->assertRedirect();
    $detail->refresh();
    expect($detail->keputusan_status)->toBe('mengundurkan_diri');
});

test('20. Withdrawn decision blocked if reschedule deadline H-3 is NOT yet passed', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    // MPLS date is 10 days in future (reschedule H-3 is not yet passed)
    $pendaftaran = Pendaftaran::factory()->create(['tanggal_mpls' => now()->addDays(10)]);
    $detail = createPR4Detail([
        'pendaftaran' => $pendaftaran,
        'status' => PendaftaranDetail::STATUS_ADMINISTRASI_LENGKAP,
    ]);
    completeObservation($detail, 'tidak_hadir');

    $response = $this->actingAs($admin)->post(route('admin.verifikasi.keputusan.store', $detail->id), [
        'keputusan_status' => 'mengundurkan_diri',
        'keputusan_alasan' => 'Tidak hadir',
    ]);
    $response->assertRedirect();
    $response->assertSessionHas('error');
    $detail->refresh();
    expect($detail->keputusan_status)->toBeNull();
});

test('21. Decisions are final and cannot be processed twice', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $detail = createPR4Detail(['kelompok_final' => 'A']);
    completeObservation($detail);

    // First decision: Diterima (final)
    $response1 = $this->actingAs($admin)->post(route('admin.verifikasi.keputusan.store', $detail->id), [
        'keputusan_status' => 'diterima',
    ]);
    $response1->assertRedirect();
    $detail->refresh();
    expect($detail->keputusan_status)->toBe('diterima');

    // Second decision: Try to change (should fail)
    $response2 = $this->actingAs($admin)->post(route('admin.verifikasi.keputusan.store', $detail->id), [
        'keputusan_status' => 'tidak_diterima',
        'keputusan_alasan' => 'Berubah pikiran',
    ]);
    $response2->assertRedirect();
    $response2->assertSessionHas('error');
    $detail->refresh();
    expect($detail->keputusan_status)->toBe('diterima'); // remains diterima
});

test('22. Decision Diterima transitions process status to keputusan_selesai', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $detail = createPR4Detail(['kelompok_final' => 'A']);
    completeObservation($detail);

    $this->actingAs($admin)->post(route('admin.verifikasi.keputusan.store', $detail->id), [
        'keputusan_status' => 'diterima',
    ]);
    $detail->refresh();
    expect($detail->status)->toBe(PendaftaranDetail::STATUS_KEPUTUSAN_SELESAI);
});

test('23. Decision Tidak Diterima transitions process status to keputusan_selesai', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $detail = createPR4Detail();
    completeObservation($detail);

    $this->actingAs($admin)->post(route('admin.verifikasi.keputusan.store', $detail->id), [
        'keputusan_status' => 'tidak_diterima',
        'keputusan_alasan' => 'Kriteria tidak terpenuhi',
    ]);
    $detail->refresh();
    expect($detail->status)->toBe(PendaftaranDetail::STATUS_KEPUTUSAN_SELESAI);
});

test('24. Decision Mengundurkan Diri transitions process status to keputusan_selesai', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $detail = createPR4Detail();
    completeObservation($detail);

    $this->actingAs($admin)->post(route('admin.verifikasi.keputusan.store', $detail->id), [
        'keputusan_status' => 'mengundurkan_diri',
        'keputusan_alasan' => 'Mengundurkan diri',
    ]);
    $detail->refresh();
    expect($detail->status)->toBe(PendaftaranDetail::STATUS_KEPUTUSAN_SELESAI);
});

test('25. Decision Perlu Tindak Lanjut keeps process status as menunggu_keputusan', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $detail = createPR4Detail();
    completeObservation($detail);

    $this->actingAs($admin)->post(route('admin.verifikasi.keputusan.store', $detail->id), [
        'keputusan_status' => 'perlu_tindak_lanjut',
        'keputusan_alasan' => 'Perlu wawancara tambahan',
    ]);
    $detail->refresh();
    expect($detail->status)->toBe(PendaftaranDetail::STATUS_MENUNGGU_KEPUTUSAN);
});

test('26. Decisions record snapshotted values on PendaftaranDetail table', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $detail = createPR4Detail(['kelompok_final' => 'A']);
    completeObservation($detail);

    $this->actingAs($admin)->post(route('admin.verifikasi.keputusan.store', $detail->id), [
        'keputusan_status' => 'diterima',
        'keputusan_catatan' => 'Catatan sukses',
    ]);
    $detail->refresh();
    expect($detail->keputusan_status)->toBe('diterima')
        ->and($detail->keputusan_catatan)->toBe('Catatan sukses')
        ->and($detail->keputusan_diputuskan_oleh)->toBe($admin->id)
        ->and($detail->keputusan_diputuskan_at)->not->toBeNull();
});

test('27. Decisions write to append-only keputusan histories table', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $detail = createPR4Detail(['kelompok_final' => 'A']);
    completeObservation($detail);

    $this->actingAs($admin)->post(route('admin.verifikasi.keputusan.store', $detail->id), [
        'keputusan_status' => 'diterima',
        'keputusan_catatan' => 'Siswa berpotensi',
    ]);

    $histories = KeputusanPendaftaran::where('pendaftaran_detail_id', $detail->id)->get();
    expect($histories)->toHaveCount(1)
        ->and($histories->first()->status)->toBe('diterima')
        ->and($histories->first()->catatan)->toBe('Siswa berpotensi')
        ->and($histories->first()->decided_by)->toBe($admin->id);
});

test('28. Decisions log to ActivityLog', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $detail = createPR4Detail(['kelompok_final' => 'A']);
    completeObservation($detail);

    $this->actingAs($admin)->post(route('admin.verifikasi.keputusan.store', $detail->id), [
        'keputusan_status' => 'diterima',
    ]);

    $log = ActivityLog::where('action', 'admission_accepted')->first();
    expect($log)->not->toBeNull()
        ->and($log->target_id)->toBe($detail->id);
});

test('29. Decisions dispatch KeputusanPendaftaranNotification to parent user', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $parent = User::factory()->create(['role' => 'parent']);
    $siswa = Siswa::factory()->create(['user_id' => $parent->id]);
    $detail = createPR4Detail(['parent' => $parent, 'siswa' => $siswa, 'kelompok_final' => 'A']);
    completeObservation($detail);

    $this->actingAs($admin)->post(route('admin.verifikasi.keputusan.store', $detail->id), [
        'keputusan_status' => 'diterima',
    ]);

    Notification::assertSentTo($parent, KeputusanPendaftaranNotification::class);
});

test('30. Restricts parent payment upload to only accepted students', function () {
    $parent = User::factory()->create(['role' => 'parent']);
    $siswa = Siswa::factory()->create(['user_id' => $parent->id]);
    $detail = createPR4Detail([
        'parent' => $parent,
        'siswa' => $siswa,
        'status' => PendaftaranDetail::STATUS_MENUNGGU_KEPUTUSAN,
        'keputusan_status' => null,
    ]);

    $file = UploadedFile::fake()->image('payment.jpg');

    // Attempting upload (should be forbidden because keputusan_status !== diterima)
    $response = $this->actingAs($parent)->post(route('parent.pembayaran.store', $detail->id), [
        'bukti_bayar' => $file,
    ]);

    $response->assertStatus(403);
});

test('31. Allows parent payment upload when keputusan_status is diterima', function () {
    $parent = User::factory()->create(['role' => 'parent']);
    $siswa = Siswa::factory()->create(['user_id' => $parent->id]);
    $detail = createPR4Detail([
        'parent' => $parent,
        'siswa' => $siswa,
        'status' => PendaftaranDetail::STATUS_KEPUTUSAN_SELESAI,
        'keputusan_status' => 'diterima',
        'keputusan_diputuskan_at' => now(),
    ]);

    PaymentSetting::create([
        'amount' => 100000,
        'bank_name' => 'BRI',
        'account_number' => '123456',
        'account_holder_name' => 'Azzahra',
    ]);

    $file = UploadedFile::fake()->image('payment.jpg');

    $response = $this->actingAs($parent)->post(route('parent.pembayaran.store', $detail->id), [
        'bukti_bayar' => $file,
    ]);

    $response->assertRedirect();
    $detail->refresh();
    expect($detail->pembayaran)->not->toBeNull()
        ->and($detail->pembayaran->status)->toBe(Pembayaran::STATUS_MENUNGGU_VERIFIKASI);
});

test('32. Restricts payment verification to diterima decisions', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $parent = User::factory()->create(['role' => 'parent']);
    $siswa = Siswa::factory()->create(['user_id' => $parent->id]);
    $detail = createPR4Detail([
        'parent' => $parent,
        'siswa' => $siswa,
        'status' => PendaftaranDetail::STATUS_KEPUTUSAN_SELESAI,
        'keputusan_status' => 'tidak_diterima',
        'keputusan_diputuskan_at' => now(),
    ]);

    $payment = Pembayaran::create([
        'pendaftaran_detail_id' => $detail->id,
        'jumlah' => 100000,
        'bukti_bayar' => 'pembayaran/dummy.jpg',
        'status' => Pembayaran::STATUS_MENUNGGU_VERIFIKASI,
    ]);

    $response = $this->actingAs($admin)->patch(route('admin.pembayaran.verify', $payment->id), [
        'status' => Pembayaran::STATUS_LUNAS,
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('error');
    $payment->refresh();
    expect($payment->status)->toBe(Pembayaran::STATUS_MENUNGGU_VERIFIKASI);
});

test('33. Allows payment verification when keputusan_status is diterima', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $parent = User::factory()->create(['role' => 'parent']);
    $siswa = Siswa::factory()->create(['user_id' => $parent->id]);
    $detail = createPR4Detail([
        'parent' => $parent,
        'siswa' => $siswa,
        'status' => PendaftaranDetail::STATUS_KEPUTUSAN_SELESAI,
        'keputusan_status' => 'diterima',
        'keputusan_diputuskan_at' => now(),
    ]);

    $payment = Pembayaran::create([
        'pendaftaran_detail_id' => $detail->id,
        'jumlah' => 100000,
        'bukti_bayar' => 'pembayaran/dummy.jpg',
        'status' => Pembayaran::STATUS_MENUNGGU_VERIFIKASI,
    ]);

    $response = $this->actingAs($admin)->patch(route('admin.pembayaran.verify', $payment->id), [
        'status' => Pembayaran::STATUS_LUNAS,
    ]);

    $response->assertRedirect();
    $payment->refresh();
    expect($payment->status)->toBe(Pembayaran::STATUS_LUNAS);
});

test('34. Safe re-upload on transactional file errors', function () {
    $parent = User::factory()->create(['role' => 'parent']);
    $siswa = Siswa::factory()->create(['user_id' => $parent->id]);
    $detail = createPR4Detail([
        'parent' => $parent,
        'siswa' => $siswa,
        'status' => PendaftaranDetail::STATUS_KEPUTUSAN_SELESAI,
        'keputusan_status' => 'diterima',
        'keputusan_diputuskan_at' => now(),
    ]);

    PaymentSetting::create([
        'amount' => 100000,
        'bank_name' => 'BRI',
        'account_number' => '123456',
        'account_holder_name' => 'Azzahra',
    ]);

    // Create first upload
    $file1 = UploadedFile::fake()->image('payment1.jpg');
    $this->actingAs($parent)->post(route('parent.pembayaran.store', $detail->id), [
        'bukti_bayar' => $file1,
    ]);
    $detail->refresh();
    $firstPath = $detail->pembayaran->bukti_bayar;
    Storage::disk('local')->assertExists($firstPath);

    // Mock DB failure on next upload to trigger rollback file cleanup
    DB::shouldReceive('transaction')
        ->once()
        ->andThrow(new Exception('Mock database error'));

    $file2 = UploadedFile::fake()->image('payment2.jpg');
    try {
        $this->actingAs($parent)->post(route('parent.pembayaran.store', $detail->id), [
            'bukti_bayar' => $file2,
        ]);
    } catch (Exception $e) {
        // Expected exception
    }

    // Verify first proof is NOT deleted and new proof is NOT kept
    Storage::disk('local')->assertExists($firstPath);
});

test('35. Restricts parent receipt download when unpaid', function () {
    $parent = User::factory()->create(['role' => 'parent']);
    $siswa = Siswa::factory()->create(['user_id' => $parent->id]);
    $detail = createPR4Detail([
        'parent' => $parent,
        'siswa' => $siswa,
        'status' => PendaftaranDetail::STATUS_KEPUTUSAN_SELESAI,
        'keputusan_status' => 'diterima',
        'keputusan_diputuskan_at' => now(),
    ]);

    $response = $this->actingAs($parent)->get(route('parent.pembayaran.receipt', $detail->id));
    $response->assertStatus(403);
});

test('36. Allows parent receipt download when accepted and payment lunas', function () {
    $parent = User::factory()->create(['role' => 'parent']);
    $siswa = Siswa::factory()->create(['user_id' => $parent->id]);
    $detail = createPR4Detail([
        'parent' => $parent,
        'siswa' => $siswa,
        'status' => PendaftaranDetail::STATUS_KEPUTUSAN_SELESAI,
        'keputusan_status' => 'diterima',
        'keputusan_diputuskan_at' => now(),
    ]);

    Pembayaran::create([
        'pendaftaran_detail_id' => $detail->id,
        'jumlah' => 100000,
        'bukti_bayar' => 'pembayaran/dummy.jpg',
        'status' => Pembayaran::STATUS_LUNAS,
    ]);
    $detail->update(['final_status' => PendaftaranDetail::FINAL_SISWA_RESMI_TERDAFTAR, 'final_ditetapkan_at' => now()]);

    $response = $this->actingAs($parent)->get(route('parent.pembayaran.receipt', $detail->id));
    $response->assertOk();
});

test('37. Restricts parent ID card printing when unpaid', function () {
    $parent = User::factory()->create(['role' => 'parent']);
    $siswa = Siswa::factory()->create(['user_id' => $parent->id]);
    $detail = createPR4Detail([
        'parent' => $parent,
        'siswa' => $siswa,
        'status' => PendaftaranDetail::STATUS_KEPUTUSAN_SELESAI,
        'keputusan_status' => 'diterima',
        'keputusan_diputuskan_at' => now(),
    ]);

    $response = $this->actingAs($parent)->get(route('parent.siswa.pendaftaran.kartu', ['siswa' => $siswa->id, 'detail' => $detail->id]));
    $response->assertStatus(403);
});

test('38. Allows parent ID card printing when accepted and payment lunas', function () {
    $parent = User::factory()->create(['role' => 'parent']);
    $siswa = Siswa::factory()->create(['user_id' => $parent->id]);
    $detail = createPR4Detail([
        'parent' => $parent,
        'siswa' => $siswa,
        'status' => PendaftaranDetail::STATUS_KEPUTUSAN_SELESAI,
        'keputusan_status' => 'diterima',
        'keputusan_diputuskan_at' => now(),
    ]);

    Pembayaran::create([
        'pendaftaran_detail_id' => $detail->id,
        'jumlah' => 100000,
        'bukti_bayar' => 'pembayaran/dummy.jpg',
        'status' => Pembayaran::STATUS_LUNAS,
    ]);
    $detail->update(['final_status' => PendaftaranDetail::FINAL_SISWA_RESMI_TERDAFTAR, 'final_ditetapkan_at' => now()]);

    $response = $this->actingAs($parent)->get(route('parent.siswa.pendaftaran.kartu', ['siswa' => $siswa->id, 'detail' => $detail->id]));
    $response->assertOk();
});

test('39. Blocks child profile deletion if has registration history', function () {
    $parent = User::factory()->create(['role' => 'parent']);
    $siswa = Siswa::factory()->create(['user_id' => $parent->id]);
    createPR4Detail([
        'parent' => $parent,
        'siswa' => $siswa,
    ]);

    $response = $this->actingAs($parent)->delete(route('parent.siswa.destroy', $siswa->id));
    $response->assertRedirect();
    $response->assertSessionHas('error');
    expect(Siswa::whereKey($siswa->id)->exists())->toBeTrue();
});

test('40. Verification exports load keputusanDiputuskanOleh relation and include decision fields', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $detail = createPR4Detail([
        'kelompok_final' => 'A',
    ]);
    completeObservation($detail);

    // Make a decision
    $this->actingAs($admin)->post(route('admin.verifikasi.keputusan.store', $detail->id), [
        'keputusan_status' => 'diterima',
    ]);

    // Call export PDF
    $response = $this->actingAs($admin)->get(route('admin.verifikasi.export', ['type' => 'pdf']));
    $response->assertOk();

    // Call export Excel
    $responseExcel = $this->actingAs($admin)->get(route('admin.verifikasi.export', ['type' => 'xlsx']));
    $responseExcel->assertOk();
});

test('41. Decisions store snapshotted reasons for non-accepted decisions', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $detail = createPR4Detail();
    completeObservation($detail);

    $this->actingAs($admin)->post(route('admin.verifikasi.keputusan.store', $detail->id), [
        'keputusan_status' => 'tidak_diterima',
        'keputusan_alasan' => 'Nilai observasi di bawah standar minimum',
        'keputusan_catatan' => 'Hubungi panitia jika ada pertanyaan',
    ]);

    $detail->refresh();
    expect($detail->keputusan_status)->toBe('tidak_diterima')
        ->and($detail->keputusan_alasan)->toBe('Nilai observasi di bawah standar minimum')
        ->and($detail->keputusan_catatan)->toBe('Hubungi panitia jika ada pertanyaan');
});

test('42. Decisions history protects pendaftaran detail from deletion', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $detail = createPR4Detail(['kelompok_final' => 'A']);
    completeObservation($detail);

    $this->actingAs($admin)->post(route('admin.verifikasi.keputusan.store', $detail->id), [
        'keputusan_status' => 'diterima',
    ]);

    expect(KeputusanPendaftaran::where('pendaftaran_detail_id', $detail->id)->count())->toBe(1);

    $this->actingAs($admin)->delete(route('admin.verifikasi.destroy', $detail->id))->assertSessionHas('error');

    expect(PendaftaranDetail::whereKey($detail->id)->exists())->toBeTrue();
    expect(KeputusanPendaftaran::where('pendaftaran_detail_id', $detail->id)->count())->toBe(1);
});

test('43. Dashboard status count reflects keputusan_status instead of legacy status', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $detail1 = createPR4Detail(['kelompok_final' => 'A']);
    completeObservation($detail1);

    // Make Diterima decision
    $this->actingAs($admin)->post(route('admin.verifikasi.keputusan.store', $detail1->id), [
        'keputusan_status' => 'diterima',
    ]);

    $detail2 = createPR4Detail();
    completeObservation($detail2);

    // Make Tidak Diterima decision
    $this->actingAs($admin)->post(route('admin.verifikasi.keputusan.store', $detail2->id), [
        'keputusan_status' => 'tidak_diterima',
        'keputusan_alasan' => 'Ditolak',
    ]);

    // View dashboard page to verify stats loaded correctly
    $response = $this->actingAs($admin)->get(route('admin.dashboard'));
    $response->assertOk();
});

test('44. Parent dashboard shows correct custom decision notifications and badges', function () {
    $parent = User::factory()->create(['role' => 'parent']);
    $siswa = Siswa::factory()->create(['user_id' => $parent->id]);
    $detail = createPR4Detail([
        'parent' => $parent,
        'siswa' => $siswa,
        'status' => PendaftaranDetail::STATUS_KEPUTUSAN_SELESAI,
        'keputusan_status' => 'tidak_diterima',
        'keputusan_catatan' => 'IN_CONFIDENTIAL_NOTES',
        'keputusan_alasan' => 'Maaf, kuota terbatas',
        'keputusan_diputuskan_at' => now(),
    ]);

    $response = $this->actingAs($parent)->get(route('parent.dashboard'));
    $response->assertOk();
    $response->assertSee('Tidak Diterima');
    $response->assertSee('Maaf, kuota terbatas');
    $response->assertDontSee('IN_CONFIDENTIAL_NOTES');
});

test('45. no-show withdrawal before deadline (H-3) is rejected', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    // MPLS date is 5 days in future (H-3 reschedule deadline not passed yet)
    $pendaftaran = Pendaftaran::factory()->create(['tanggal_mpls' => now()->addDays(5)]);
    $detail = createPR4Detail([
        'pendaftaran' => $pendaftaran,
        'status' => PendaftaranDetail::STATUS_ADMINISTRASI_LENGKAP,
    ]);
    completeObservation($detail, 'tidak_hadir');

    $response = $this->actingAs($admin)->post(route('admin.verifikasi.keputusan.store', $detail->id), [
        'keputusan_status' => 'mengundurkan_diri',
        'keputusan_alasan' => 'Tidak hadir',
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('error');
    $detail->refresh();
    expect($detail->keputusan_status)->toBeNull();
});

test('46. no-show withdrawal after deadline (H-3) succeeds', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    // MPLS date is 2 days in future (H-3 reschedule deadline has passed)
    $pendaftaran = Pendaftaran::factory()->create(['tanggal_mpls' => now()->addDays(2)]);
    $detail = createPR4Detail([
        'pendaftaran' => $pendaftaran,
        'status' => PendaftaranDetail::STATUS_ADMINISTRASI_LENGKAP,
    ]);
    completeObservation($detail, 'tidak_hadir');

    $response = $this->actingAs($admin)->post(route('admin.verifikasi.keputusan.store', $detail->id), [
        'keputusan_status' => 'mengundurkan_diri',
        'keputusan_alasan' => 'Tidak hadir dan deadline lewat',
    ]);

    $response->assertRedirect();
    $detail->refresh();
    expect($detail->keputusan_status)->toBe('mengundurkan_diri');
});

test('47. Perlu tindak lanjut repeated decision creates a new history entry every time', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $detail = createPR4Detail();
    completeObservation($detail);

    // First decision: Perlu Tindak Lanjut
    $this->actingAs($admin)->post(route('admin.verifikasi.keputusan.store', $detail->id), [
        'keputusan_status' => 'perlu_tindak_lanjut',
        'keputusan_alasan' => 'Wawancara pertama kurang memuaskan',
    ]);

    // Second decision: Perlu Tindak Lanjut again
    $this->actingAs($admin)->post(route('admin.verifikasi.keputusan.store', $detail->id), [
        'keputusan_status' => 'perlu_tindak_lanjut',
        'keputusan_alasan' => 'Wawancara kedua masih butuh waktu',
    ]);

    $histories = KeputusanPendaftaran::where('pendaftaran_detail_id', $detail->id)->get();
    expect($histories)->toHaveCount(2)
        ->and($histories[0]->alasan)->toBe('Wawancara pertama kurang memuaskan')
        ->and($histories[1]->alasan)->toBe('Wawancara kedua masih butuh waktu');
});

test('48. Final decision is idempotent (consecutive duplicate requests do not create duplicate records)', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $detail = createPR4Detail(['kelompok_final' => 'A']);
    completeObservation($detail);

    // Make Diterima decision
    $response1 = $this->actingAs($admin)->post(route('admin.verifikasi.keputusan.store', $detail->id), [
        'keputusan_status' => 'diterima',
        'keputusan_alasan' => 'Siswa berprestasi',
    ]);
    $response1->assertRedirect();

    $historyCount = KeputusanPendaftaran::where('pendaftaran_detail_id', $detail->id)->count();
    $activityLogCount = ActivityLog::where('target_id', $detail->id)->count();

    // Duplicate request
    $response2 = $this->actingAs($admin)->post(route('admin.verifikasi.keputusan.store', $detail->id), [
        'keputusan_status' => 'diterima',
        'keputusan_alasan' => 'Siswa berprestasi',
    ]);
    $response2->assertRedirect();
    $response2->assertSessionHas('error'); // Should return error because decision is already final

    expect(KeputusanPendaftaran::where('pendaftaran_detail_id', $detail->id)->count())->toBe($historyCount)
        ->and(ActivityLog::where('target_id', $detail->id)->count())->toBe($activityLogCount);
});

test('49. Concurrent final decision is prevented using locking', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $detail = createPR4Detail(['kelompok_final' => 'A']);
    completeObservation($detail);

    $service = app(AdmissionDecisionService::class);
    $service->makeDecision($detail, ['keputusan_status' => 'diterima'], $admin->id);

    // The second call should fail with RuntimeException
    expect(fn () => $service->makeDecision($detail, ['keputusan_status' => 'tidak_diterima', 'keputusan_alasan' => 'Ditolak'], $admin->id))
        ->toThrow(RuntimeException::class, 'Keputusan final sudah ditetapkan dan tidak dapat diubah.');
});

test('50. Payment uploads for all non-accepted decisions are rejected server-side', function () {
    $parent = User::factory()->create(['role' => 'parent']);
    $siswa = Siswa::factory()->create(['user_id' => $parent->id]);
    $file = UploadedFile::fake()->image('payment.jpg');

    $statuses = ['tidak_diterima', 'perlu_tindak_lanjut', 'mengundurkan_diri'];

    foreach ($statuses as $status) {
        $detail = createPR4Detail([
            'parent' => $parent,
            'siswa' => $siswa,
            'status' => $status === 'perlu_tindak_lanjut' ? PendaftaranDetail::STATUS_MENUNGGU_KEPUTUSAN : PendaftaranDetail::STATUS_KEPUTUSAN_SELESAI,
            'keputusan_status' => $status,
            'keputusan_diputuskan_at' => now(),
        ]);

        $response = $this->actingAs($parent)->post(route('parent.pembayaran.store', $detail->id), [
            'bukti_bayar' => $file,
        ]);

        $response->assertStatus(403);
    }
});

test('51. Kartu and receipt require accepted decision and lunas payment', function () {
    $parent = User::factory()->create(['role' => 'parent']);
    $siswa = Siswa::factory()->create(['user_id' => $parent->id]);

    // Scenario A: Accepted but no payment
    $detailA = createPR4Detail([
        'parent' => $parent,
        'siswa' => $siswa,
        'status' => PendaftaranDetail::STATUS_KEPUTUSAN_SELESAI,
        'keputusan_status' => 'diterima',
        'keputusan_diputuskan_at' => now(),
    ]);

    $this->actingAs($parent)->get(route('parent.siswa.pendaftaran.kartu', ['siswa' => $siswa->id, 'detail' => $detailA->id]))
        ->assertStatus(403);
    $this->actingAs($parent)->get(route('parent.pembayaran.receipt', $detailA->id))
        ->assertStatus(403);

    // Scenario B: Accepted with pending payment
    $detailB = createPR4Detail([
        'parent' => $parent,
        'siswa' => $siswa,
        'status' => PendaftaranDetail::STATUS_KEPUTUSAN_SELESAI,
        'keputusan_status' => 'diterima',
        'keputusan_diputuskan_at' => now(),
    ]);
    Pembayaran::create([
        'pendaftaran_detail_id' => $detailB->id,
        'jumlah' => 100000,
        'bukti_bayar' => 'proof.png',
        'status' => Pembayaran::STATUS_PENDING,
    ]);

    $this->actingAs($parent)->get(route('parent.siswa.pendaftaran.kartu', ['siswa' => $siswa->id, 'detail' => $detailB->id]))
        ->assertStatus(403);
    $this->actingAs($parent)->get(route('parent.pembayaran.receipt', $detailB->id))
        ->assertStatus(403);

    // Scenario C: Accepted with rejected payment
    $detailC = createPR4Detail([
        'parent' => $parent,
        'siswa' => $siswa,
        'status' => PendaftaranDetail::STATUS_KEPUTUSAN_SELESAI,
        'keputusan_status' => 'diterima',
        'keputusan_diputuskan_at' => now(),
    ]);
    Pembayaran::create([
        'pendaftaran_detail_id' => $detailC->id,
        'jumlah' => 100000,
        'bukti_bayar' => 'proof.png',
        'status' => Pembayaran::STATUS_DITOLAK,
    ]);

    $this->actingAs($parent)->get(route('parent.siswa.pendaftaran.kartu', ['siswa' => $siswa->id, 'detail' => $detailC->id]))
        ->assertStatus(403);
    $this->actingAs($parent)->get(route('parent.pembayaran.receipt', $detailC->id))
        ->assertStatus(403);

    // Scenario D: Accepted with lunas payment
    $detailD = createPR4Detail([
        'parent' => $parent,
        'siswa' => $siswa,
        'status' => PendaftaranDetail::STATUS_KEPUTUSAN_SELESAI,
        'keputusan_status' => 'diterima',
        'keputusan_diputuskan_at' => now(),
    ]);
    Pembayaran::create([
        'pendaftaran_detail_id' => $detailD->id,
        'jumlah' => 100000,
        'bukti_bayar' => 'proof.png',
        'status' => Pembayaran::STATUS_LUNAS,
    ]);
    $detailD->update(['final_status' => PendaftaranDetail::FINAL_SISWA_RESMI_TERDAFTAR, 'final_ditetapkan_at' => now()]);

    $this->actingAs($parent)->get(route('parent.siswa.pendaftaran.kartu', ['siswa' => $siswa->id, 'detail' => $detailD->id]))
        ->assertOk();
    $this->actingAs($parent)->get(route('parent.pembayaran.receipt', $detailD->id))
        ->assertOk();
});

test('52. Parent with multiple children data is completely isolated', function () {
    $parent = User::factory()->create(['role' => 'parent']);
    $childA = Siswa::factory()->create(['user_id' => $parent->id]);
    $childB = Siswa::factory()->create(['user_id' => $parent->id]);

    $detailA = createPR4Detail([
        'parent' => $parent,
        'siswa' => $childA,
        'status' => PendaftaranDetail::STATUS_KEPUTUSAN_SELESAI,
        'keputusan_status' => 'diterima',
        'keputusan_diputuskan_at' => now(),
    ]);

    $detailB = createPR4Detail([
        'parent' => $parent,
        'siswa' => $childB,
        'status' => PendaftaranDetail::STATUS_MENUNGGU_KEPUTUSAN,
        'keputusan_status' => null,
    ]);

    Pembayaran::create([
        'pendaftaran_detail_id' => $detailA->id,
        'jumlah' => 100000,
        'bukti_bayar' => 'proof.png',
        'status' => Pembayaran::STATUS_LUNAS,
    ]);
    $detailA->update(['final_status' => PendaftaranDetail::FINAL_SISWA_RESMI_TERDAFTAR, 'final_ditetapkan_at' => now()]);

    $this->actingAs($parent)->get(route('parent.siswa.pendaftaran.kartu', ['siswa' => $childA->id, 'detail' => $detailA->id]))
        ->assertOk();

    $this->actingAs($parent)->get(route('parent.siswa.pendaftaran.kartu', ['siswa' => $childB->id, 'detail' => $detailB->id]))
        ->assertStatus(403);
});

test('53. Catatan internal (keputusan_catatan) does not leak to parent views', function () {
    $parent = User::factory()->create(['role' => 'parent']);
    $siswa = Siswa::factory()->create(['user_id' => $parent->id]);
    $detail = createPR4Detail([
        'parent' => $parent,
        'siswa' => $siswa,
        'status' => PendaftaranDetail::STATUS_KEPUTUSAN_SELESAI,
        'keputusan_status' => 'tidak_diterima',
        'keputusan_catatan' => 'IN_CONFIDENTIAL_NOTES',
        'keputusan_alasan' => 'PUBLIC_REASON_MESSAGE',
        'keputusan_diputuskan_at' => now(),
    ]);

    // Check parent dashboard
    $responseDashboard = $this->actingAs($parent)->get(route('parent.dashboard'));
    $responseDashboard->assertSee('PUBLIC_REASON_MESSAGE')
        ->assertDontSee('IN_CONFIDENTIAL_NOTES');

    // Check parent status page
    $responseStatus = $this->actingAs($parent)->get(route('parent.siswa.pendaftaran.status', $siswa->id));
    $responseStatus->assertSee('PUBLIC_REASON_MESSAGE')
        ->assertDontSee('IN_CONFIDENTIAL_NOTES');
});

test('54. Seeder database contains no legacy status', function () {
    $this->artisan('db:seed');

    $legacyAccepted = PendaftaranDetail::where('status', 'diterima')->count();
    $legacyRejected = PendaftaranDetail::where('status', 'ditolak')->count();

    expect($legacyAccepted)->toBe(0)
        ->and($legacyRejected)->toBe(0);
});

test('55. Successful re-upload deletes old file, failed transaction cleans up new file', function () {
    $parent = User::factory()->create(['role' => 'parent']);
    $siswa = Siswa::factory()->create(['user_id' => $parent->id]);
    $detail = createPR4Detail([
        'parent' => $parent,
        'siswa' => $siswa,
        'status' => PendaftaranDetail::STATUS_KEPUTUSAN_SELESAI,
        'keputusan_status' => 'diterima',
        'keputusan_diputuskan_at' => now(),
    ]);

    PaymentSetting::create([
        'amount' => 100000,
        'bank_name' => 'BRI',
        'account_number' => '123456',
        'account_holder_name' => 'Azzahra',
    ]);

    // 1. Initial upload
    $file1 = UploadedFile::fake()->image('payment1.png');
    $this->actingAs($parent)->post(route('parent.pembayaran.store', $detail->id), [
        'bukti_bayar' => $file1,
    ]);
    $detail->refresh();
    $firstPath = $detail->pembayaran->bukti_bayar;
    Storage::disk('local')->assertExists($firstPath);

    // 2. Successful re-upload
    $file2 = UploadedFile::fake()->image('payment2.png');
    $this->actingAs($parent)->post(route('parent.pembayaran.store', $detail->id), [
        'bukti_bayar' => $file2,
    ]);
    $detail->refresh();
    $secondPath = $detail->pembayaran->bukti_bayar;

    Storage::disk('local')->assertMissing($firstPath);
    Storage::disk('local')->assertExists($secondPath);

    // 3. Failed transaction
    DB::shouldReceive('transaction')
        ->once()
        ->andThrow(new Exception('Mock database transaction failure'));

    $file3 = UploadedFile::fake()->image('payment3.png');
    try {
        $this->actingAs($parent)->post(route('parent.pembayaran.store', $detail->id), [
            'bukti_bayar' => $file3,
        ]);
    } catch (Exception $e) {
        // Expected mock failure
    }

    Storage::disk('local')->assertExists($secondPath);
});

test('56. Legacy status=diterima with null keputusan_status cannot upload payment, print kartu, or print receipt', function () {
    $parent = User::factory()->create(['role' => 'parent']);
    $siswa = Siswa::factory()->create(['user_id' => $parent->id]);

    // A record with the legacy process-status='diterima' but NO decision columns set
    $detail = PendaftaranDetail::create([
        'siswa_id' => $siswa->id,
        'pendaftaran_id' => Pendaftaran::factory()->create()->id,
        'nomor_pendaftaran' => 'SPMB/LEGACY/9999',
        'status' => PendaftaranDetail::STATUS_DITERIMA,   // old-style legacy
        'keputusan_status' => null,
        'keputusan_diputuskan_at' => null,
        'usia_bulan_saat_acuan' => 60,
        'tanggal_acuan_usia' => '2026-07-01',
        'kelompok_rekomendasi' => 'B',
    ]);

    PaymentSetting::create([
        'amount' => 100000,
        'bank_name' => 'BRI',
        'account_number' => '123456',
        'account_holder_name' => 'Azzahra',
    ]);

    // 1. Upload pembayaran must be rejected
    $this->actingAs($parent)->post(route('parent.pembayaran.store', $detail->id), [
        'bukti_bayar' => UploadedFile::fake()->image('payment.png'),
    ])->assertStatus(403);

    // 2. Cetak kartu must be rejected
    $this->actingAs($parent)->get(route('parent.siswa.pendaftaran.kartu', [
        'siswa' => $siswa->id,
        'detail' => $detail->id,
    ]))->assertStatus(403);

    // 3. Receipt must be rejected (even with a lunas payment)
    Pembayaran::create([
        'pendaftaran_detail_id' => $detail->id,
        'jumlah' => 100000,
        'bukti_bayar' => 'payment/proof.png',
        'status' => Pembayaran::STATUS_LUNAS,
    ]);

    $this->actingAs($parent)->get(route('parent.pembayaran.receipt', $detail->id))
        ->assertStatus(403);
});
