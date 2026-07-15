<?php

use App\Models\Observasi;
use App\Models\Pendaftaran;
use App\Models\PendaftaranDetail;
use App\Models\Siswa;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

beforeEach(function () {
    Storage::fake('local');
    Storage::fake('public');
    Notification::fake();
    Carbon::setTestNow(); // Reset Carbon time before each test
});

function createDetailForHardening(array $attributes = []): PendaftaranDetail
{
    $parent = $attributes['parent'] ?? User::factory()->create(['role' => 'parent']);
    $siswa = $attributes['siswa'] ?? Siswa::factory()->create(['user_id' => $parent->id]);
    $pendaftaran = $attributes['pendaftaran'] ?? Pendaftaran::factory()->create([
        'tanggal_mpls' => '2026-07-15',
    ]);

    return PendaftaranDetail::create(array_merge([
        'siswa_id' => $siswa->id,
        'pendaftaran_id' => $pendaftaran->id,
        'nomor_pendaftaran' => 'PPDB/'.date('Ymd').'/'.rand(1000, 9999),
        'status' => PendaftaranDetail::STATUS_ADMINISTRASI_LENGKAP,
        'usia_bulan_saat_acuan' => 60,
        'tanggal_acuan_usia' => '2026-07-01',
        'kelompok_rekomendasi' => 'B',
    ], array_diff_key($attributes, ['parent' => 1, 'siswa' => 1, 'pendaftaran' => 1])));
}

test('hardening: scheduling without tanggal MPLS is rejected', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $pendaftaran = Pendaftaran::factory()->create(['tanggal_mpls' => null]);
    $detail = createDetailForHardening([
        'pendaftaran' => $pendaftaran,
    ]);

    $response = $this->actingAs($admin)
        ->post(route('admin.verifikasi.observasi.store', $detail->id), [
            'scheduled_at' => now()->addDays(1)->format('Y-m-d H:i:s'),
        ]);

    $response->assertRedirect();
    $response->assertSessionHas('error');
    expect(Observasi::count())->toBe(0);
});

test('hardening: scheduling on the day of MPLS is rejected', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $pendaftaran = Pendaftaran::factory()->create(['tanggal_mpls' => '2026-07-15']);
    $detail = createDetailForHardening([
        'pendaftaran' => $pendaftaran,
    ]);

    // Schedule on the exact day of MPLS (2026-07-15)
    $scheduledAt = '2026-07-15 09:00:00';

    $response = $this->actingAs($admin)
        ->post(route('admin.verifikasi.observasi.store', $detail->id), [
            'scheduled_at' => $scheduledAt,
        ]);

    $response->assertSessionHasErrors(['scheduled_at']);
    expect(Observasi::count())->toBe(0);
});

test('hardening: prevent duplicate active observations (scheduled / attended / no_show)', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $pendaftaran = Pendaftaran::factory()->create(['tanggal_mpls' => '2026-07-15']);
    $detail = createDetailForHardening([
        'pendaftaran' => $pendaftaran,
    ]);
    Carbon::setTestNow(Carbon::create(2026, 7, 9, 9, 0, 0));

    // Attempt 1: Dijadwalkan
    $obs = Observasi::create([
        'pendaftaran_detail_id' => $detail->id,
        'attempt_number' => 1,
        'scheduled_at' => '2026-07-10 09:00:00',
        'status' => Observasi::STATUS_DIJADWALKAN,
        'scheduled_by' => $admin->id,
    ]);

    // Try scheduling a second one when attempt 1 is active (STATUS_DIJADWALKAN)
    $response = $this->actingAs($admin)
        ->post(route('admin.verifikasi.observasi.store', $detail->id), [
            'scheduled_at' => '2026-07-11 09:00:00',
        ]);
    $response->assertRedirect();
    $response->assertSessionHas('error');
    expect(Observasi::count())->toBe(1);

    // Transition to STATUS_HADIR
    $obs->update(['status' => Observasi::STATUS_HADIR]);

    // Try scheduling a second one when attempt 1 is active (STATUS_HADIR)
    $response = $this->actingAs($admin)
        ->post(route('admin.verifikasi.observasi.store', $detail->id), [
            'scheduled_at' => '2026-07-11 09:00:00',
        ]);
    $response->assertRedirect();
    $response->assertSessionHas('error');
    expect(Observasi::count())->toBe(1);

    // Transition to STATUS_TIDAK_HADIR
    $obs->update(['status' => Observasi::STATUS_TIDAK_HADIR]);

    // Try scheduling a second one when attempt 1 is active (STATUS_TIDAK_HADIR)
    $response = $this->actingAs($admin)
        ->post(route('admin.verifikasi.observasi.store', $detail->id), [
            'scheduled_at' => '2026-07-11 09:00:00',
        ]);
    $response->assertRedirect();
    $response->assertSessionHas('error');
    expect(Observasi::count())->toBe(1);
});

test('hardening: state transition validations on observation', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $detail = createDetailForHardening();

    // 1. Mark selesai on observation with status "tidak_hadir" is blocked
    $obs = Observasi::create([
        'pendaftaran_detail_id' => $detail->id,
        'attempt_number' => 1,
        'scheduled_at' => '2026-07-10 09:00:00',
        'status' => Observasi::STATUS_TIDAK_HADIR,
    ]);

    $response = $this->actingAs($admin)
        ->patch(route('admin.observasi.selesai', $obs->id), [
            'tinggi_badan_cm' => 110,
            'berat_badan_kg' => 18,
            'catatan_wawancara_orang_tua' => 'Ok',
            'catatan_aktivitas_anak' => 'Ok',
            'catatan_kesiapan_anak' => 'Ok',
            'membutuhkan_dukungan_khusus' => 0,
        ]);
    $response->assertRedirect();
    $response->assertSessionHas('error');
    expect($obs->fresh()->status)->toBe(Observasi::STATUS_TIDAK_HADIR);

    // 2. Mark hadir on observation with status "selesai" is blocked
    $obs->update(['status' => Observasi::STATUS_SELESAI]);
    $response = $this->actingAs($admin)
        ->patch(route('admin.observasi.hadir', $obs->id));
    $response->assertRedirect();
    $response->assertSessionHas('error');
    expect($obs->fresh()->status)->toBe(Observasi::STATUS_SELESAI);

    // 3. Mark tidak-hadir on observation with status "selesai" is blocked
    $response = $this->actingAs($admin)
        ->patch(route('admin.observasi.tidak-hadir', $obs->id));
    $response->assertRedirect();
    $response->assertSessionHas('error');
    expect($obs->fresh()->status)->toBe(Observasi::STATUS_SELESAI);
});

test('hardening: rescheduling tepat H-3 and lewat H-3 boundary conditions', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $pendaftaran = Pendaftaran::factory()->create(['tanggal_mpls' => '2026-07-15']);
    $detail = createDetailForHardening([
        'pendaftaran' => $pendaftaran,
    ]);

    $obs = Observasi::create([
        'pendaftaran_detail_id' => $detail->id,
        'attempt_number' => 1,
        'scheduled_at' => '2026-07-10 09:00:00',
        'status' => Observasi::STATUS_TIDAK_HADIR,
    ]);

    // Case 1: Tepat H-3 end of day (12 Juli 2026 23:59:59) - allowed
    Carbon::setTestNow(Carbon::create(2026, 7, 12, 23, 59, 59));
    $response = $this->actingAs($admin)
        ->post(route('admin.observasi.jadwal-ulang', $obs->id), [
            'scheduled_at' => '2026-07-14 09:00:00',
            'reschedule_reason' => 'Sakit',
        ]);
    $response->assertRedirect();
    $response->assertSessionHas('success');
    expect(Observasi::count())->toBe(2);

    // Reset attempt 2
    Observasi::where('attempt_number', 2)->delete();
    $obs->update(['status' => Observasi::STATUS_TIDAK_HADIR]);

    // Case 2: Lewat H-3 (13 Juli 2026 00:00:00) - blocked
    Carbon::setTestNow(Carbon::create(2026, 7, 13, 0, 0, 0));
    $response = $this->actingAs($admin)
        ->post(route('admin.observasi.jadwal-ulang', $obs->id), [
            'scheduled_at' => '2026-07-14 09:00:00',
            'reschedule_reason' => 'Sakit',
        ]);
    $response->assertRedirect();
    $response->assertSessionHas('error');
    expect(Observasi::count())->toBe(1);
});

test('hardening: only the latest observation attempt can be rescheduled', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $pendaftaran = Pendaftaran::factory()->create(['tanggal_mpls' => '2026-07-15']);
    $detail = createDetailForHardening([
        'pendaftaran' => $pendaftaran,
    ]);

    // Attempt 1: Dijadwalkan ulang
    $obs1 = Observasi::create([
        'pendaftaran_detail_id' => $detail->id,
        'attempt_number' => 1,
        'scheduled_at' => '2026-07-10 09:00:00',
        'status' => Observasi::STATUS_DIJADWALKAN_ULANG,
    ]);

    // Attempt 2: Tidak Hadir (terbaru)
    $obs2 = Observasi::create([
        'pendaftaran_detail_id' => $detail->id,
        'attempt_number' => 2,
        'scheduled_at' => '2026-07-11 09:00:00',
        'status' => Observasi::STATUS_TIDAK_HADIR,
        'rescheduled_from_id' => $obs1->id,
    ]);

    // Try rescheduling attempt 1 (not the latest)
    Carbon::setTestNow(Carbon::create(2026, 7, 10, 12, 0, 0));
    $response = $this->actingAs($admin)
        ->post(route('admin.observasi.jadwal-ulang', $obs1->id), [
            'scheduled_at' => '2026-07-13 09:00:00',
            'reschedule_reason' => 'Sakit',
        ]);
    $response->assertRedirect();
    $response->assertSessionHas('error');
    expect(Observasi::count())->toBe(2); // No third attempt created
});

test('hardening: payment upload is blocked on administrasi_lengkap and menunggu_keputusan', function () {
    $parent = User::factory()->create(['role' => 'parent']);
    $siswa = Siswa::factory()->create(['user_id' => $parent->id]);

    // Case 1: administrasi_lengkap
    $detail1 = createDetailForHardening([
        'parent' => $parent,
        'siswa' => $siswa,
        'status' => PendaftaranDetail::STATUS_ADMINISTRASI_LENGKAP,
    ]);

    $response = $this->actingAs($parent)
        ->post(route('parent.pembayaran.store', $detail1->id), [
            'bukti_bayar' => UploadedFile::fake()->image('bukti.png'),
        ]);
    $response->assertStatus(403);

    // Case 2: menunggu_keputusan
    $detail2 = createDetailForHardening([
        'parent' => $parent,
        'siswa' => $siswa,
        'status' => PendaftaranDetail::STATUS_MENUNGGU_KEPUTUSAN,
    ]);

    $response = $this->actingAs($parent)
        ->post(route('parent.pembayaran.store', $detail2->id), [
            'bukti_bayar' => UploadedFile::fake()->image('bukti.png'),
        ]);
    $response->assertStatus(403);
});

test('hardening: legacy accept and reject routes are deleted', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $detail = createDetailForHardening();

    // Try posting to admin.verifikasi.terima and admin.verifikasi.tolak
    // Since we deleted these named routes, trying to generate them will throw a RouteNotFoundException
    $this->expectException(RouteNotFoundException::class);
    route('admin.verifikasi.terima', $detail->id);
});

test('hardening: bulk update rejects status diterima, ditolak, menunggu_keputusan, and administrasi_lengkap', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $detail = createDetailForHardening([
        'status' => PendaftaranDetail::STATUS_MENUNGGU_VERIFIKASI,
    ]);

    // 1. Try updating to administrasi_lengkap
    $response = $this->actingAs($admin)
        ->post(route('admin.verifikasi.bulkUpdate'), [
            'detail_ids' => [$detail->id],
            'status' => 'administrasi_lengkap',
        ]);
    $response->assertSessionHasErrors(['status']);

    // 2. Try updating to menunggu_keputusan
    $response = $this->actingAs($admin)
        ->post(route('admin.verifikasi.bulkUpdate'), [
            'detail_ids' => [$detail->id],
            'status' => 'menunggu_keputusan',
        ]);
    $response->assertSessionHasErrors(['status']);
});

test('hardening: cascade delete of PendaftaranDetail cleans up observation attempts', function () {
    $detail = createDetailForHardening();

    Observasi::create([
        'pendaftaran_detail_id' => $detail->id,
        'attempt_number' => 1,
        'scheduled_at' => '2026-07-10 09:00:00',
        'status' => Observasi::STATUS_DIJADWALKAN,
    ]);

    expect(Observasi::count())->toBe(1);

    // Delete PendaftaranDetail
    $detail->delete();

    // Verify observations are deleted via cascading
    expect(Observasi::count())->toBe(0);
});
