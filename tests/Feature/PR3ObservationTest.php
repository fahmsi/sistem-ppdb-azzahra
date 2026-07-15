<?php

use App\Models\ActivityLog;
use App\Models\Observasi;
use App\Models\Pendaftaran;
use App\Models\PendaftaranDetail;
use App\Models\Siswa;
use App\Models\User;
use App\Notifications\AdministrasiLengkapNotification;
use App\Notifications\ObservasiDijadwalkanNotification;
use App\Notifications\ObservasiSelesaiNotification;
use App\Notifications\ObservasiTidakHadirNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake('local');
    Storage::fake('public');
    Notification::fake();
});

function createDetail(array $attributes = []): PendaftaranDetail
{
    $parent = $attributes['parent'] ?? User::factory()->create(['role' => 'parent']);
    $siswa = $attributes['siswa'] ?? Siswa::factory()->create(['user_id' => $parent->id]);
    $pendaftaran = $attributes['pendaftaran'] ?? Pendaftaran::factory()->create();

    return PendaftaranDetail::create(array_merge([
        'siswa_id' => $siswa->id,
        'pendaftaran_id' => $pendaftaran->id,
        'nomor_pendaftaran' => 'PPDB/'.date('Ymd').'/'.rand(1000, 9999),
        'status' => PendaftaranDetail::STATUS_MENUNGGU_VERIFIKASI,
        'usia_bulan_saat_acuan' => 60,
        'tanggal_acuan_usia' => '2026-07-01',
        'kelompok_rekomendasi' => 'B',
    ], array_diff_key($attributes, ['parent' => 1, 'siswa' => 1, 'pendaftaran' => 1])));
}

test('1. admin can mark registration as administrasi_lengkap from menunggu_verifikasi', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $parent = User::factory()->create(['role' => 'parent']);
    $siswa = Siswa::factory()->create(['user_id' => $parent->id]);

    $detail = createDetail([
        'parent' => $parent,
        'siswa' => $siswa,
        'status' => PendaftaranDetail::STATUS_MENUNGGU_VERIFIKASI,
    ]);

    $response = $this->actingAs($admin)
        ->post(route('admin.verifikasi.administrasi-lengkap', $detail->id));

    $response->assertRedirect();
    $response->assertSessionHas('success');

    $detail->refresh();
    expect($detail->status)->toBe(PendaftaranDetail::STATUS_ADMINISTRASI_LENGKAP);

    // Assert activity log is recorded
    $log = ActivityLog::latest()->first();
    expect($log->action)->toBe('administration_completed')
        ->and($log->target_id)->toBe($detail->id)
        ->and($log->target_type)->toBe(PendaftaranDetail::class);

    // Assert notification sent
    Notification::assertSentTo($parent, AdministrasiLengkapNotification::class);
});

test('2. admin cannot mark administrasi_lengkap from invalid status', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $detail = createDetail([
        'status' => PendaftaranDetail::STATUS_DITERIMA,
    ]);

    $response = $this->actingAs($admin)
        ->post(route('admin.verifikasi.administrasi-lengkap', $detail->id));

    $response->assertRedirect();
    $response->assertSessionHas('error');

    $detail->refresh();
    expect($detail->status)->toBe(PendaftaranDetail::STATUS_DITERIMA);
});

test('3. admin can schedule first observation with valid datetime', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $parent = User::factory()->create(['role' => 'parent']);
    $siswa = Siswa::factory()->create(['user_id' => $parent->id]);

    // MPLS date is 10 days in future
    $mplsDate = now()->addDays(10)->format('Y-m-d');
    $pendaftaran = Pendaftaran::factory()->create(['tanggal_mpls' => $mplsDate]);

    $detail = createDetail([
        'parent' => $parent,
        'siswa' => $siswa,
        'pendaftaran' => $pendaftaran,
        'status' => PendaftaranDetail::STATUS_ADMINISTRASI_LENGKAP,
    ]);

    // Schedule 2 days in future (valid, before MPLS and in future)
    $scheduledAt = now()->addDays(2)->format('Y-m-d H:i:s');

    $response = $this->actingAs($admin)
        ->post(route('admin.verifikasi.observasi.store', $detail->id), [
            'scheduled_at' => $scheduledAt,
            'catatan_jadwal' => 'Datang tepat waktu',
        ]);

    $response->assertRedirect();
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('spmb_observasi', [
        'pendaftaran_detail_id' => $detail->id,
        'attempt_number' => 1,
        'status' => Observasi::STATUS_DIJADWALKAN,
    ]);

    // Assert notification sent
    Notification::assertSentTo($parent, ObservasiDijadwalkanNotification::class);
});

test('4. scheduling with past datetime is rejected', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $detail = createDetail([
        'status' => PendaftaranDetail::STATUS_ADMINISTRASI_LENGKAP,
    ]);

    $scheduledAt = now()->subDays(1)->format('Y-m-d H:i:s');

    $response = $this->actingAs($admin)
        ->post(route('admin.verifikasi.observasi.store', $detail->id), [
            'scheduled_at' => $scheduledAt,
        ]);

    $response->assertSessionHasErrors(['scheduled_at']);
    expect(Observasi::count())->toBe(0);
});

test('5. scheduling after MPLS datetime is rejected', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $mplsDate = now()->addDays(5)->format('Y-m-d');
    $pendaftaran = Pendaftaran::factory()->create(['tanggal_mpls' => $mplsDate]);

    $detail = createDetail([
        'pendaftaran' => $pendaftaran,
        'status' => PendaftaranDetail::STATUS_ADMINISTRASI_LENGKAP,
    ]);

    // Scheduled at 6 days in future (after MPLS)
    $scheduledAt = now()->addDays(6)->format('Y-m-d H:i:s');

    $response = $this->actingAs($admin)
        ->post(route('admin.verifikasi.observasi.store', $detail->id), [
            'scheduled_at' => $scheduledAt,
        ]);

    $response->assertSessionHas('error');
    expect(Observasi::count())->toBe(0);
});

test('6. admin can mark attendance as Hadir', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $detail = createDetail([
        'status' => PendaftaranDetail::STATUS_ADMINISTRASI_LENGKAP,
    ]);

    $observasi = Observasi::create([
        'pendaftaran_detail_id' => $detail->id,
        'attempt_number' => 1,
        'scheduled_at' => now()->addDays(1),
        'status' => Observasi::STATUS_DIJADWALKAN,
        'scheduled_by' => $admin->id,
    ]);

    $response = $this->actingAs($admin)
        ->patch(route('admin.observasi.hadir', $observasi->id));

    $response->assertRedirect();
    $observasi->refresh();
    expect($observasi->status)->toBe(Observasi::STATUS_HADIR)
        ->and($observasi->attended_at)->not->toBeNull();

    $log = ActivityLog::latest()->first();
    expect($log->action)->toBe('observation_attended');
});

test('7. admin can mark attendance as Tidak Hadir', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $parent = User::factory()->create(['role' => 'parent']);
    $siswa = Siswa::factory()->create(['user_id' => $parent->id]);
    $pendaftaran = Pendaftaran::factory()->create(['tanggal_mpls' => now()->addDays(5)]);

    $detail = createDetail([
        'parent' => $parent,
        'siswa' => $siswa,
        'pendaftaran' => $pendaftaran,
        'status' => PendaftaranDetail::STATUS_ADMINISTRASI_LENGKAP,
    ]);

    $observasi = Observasi::create([
        'pendaftaran_detail_id' => $detail->id,
        'attempt_number' => 1,
        'scheduled_at' => now()->addDays(1),
        'status' => Observasi::STATUS_DIJADWALKAN,
        'scheduled_by' => $admin->id,
    ]);

    $response = $this->actingAs($admin)
        ->patch(route('admin.observasi.tidak-hadir', $observasi->id));

    $response->assertRedirect();
    $observasi->refresh();
    expect($observasi->status)->toBe(Observasi::STATUS_TIDAK_HADIR);

    Notification::assertSentTo($parent, ObservasiTidakHadirNotification::class);

    $log = ActivityLog::latest()->first();
    expect($log->action)->toBe('observation_no_show');
});

test('8. admin can reschedule observation within deadline', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $parent = User::factory()->create(['role' => 'parent']);
    $siswa = Siswa::factory()->create(['user_id' => $parent->id]);

    // MPLS is 10 days away. H-3 is end of day 7 days away.
    $pendaftaran = Pendaftaran::factory()->create(['tanggal_mpls' => now()->addDays(10)]);

    $detail = createDetail([
        'parent' => $parent,
        'siswa' => $siswa,
        'pendaftaran' => $pendaftaran,
        'status' => PendaftaranDetail::STATUS_ADMINISTRASI_LENGKAP,
    ]);

    $observasi = Observasi::create([
        'pendaftaran_detail_id' => $detail->id,
        'attempt_number' => 1,
        'scheduled_at' => now()->addDays(1),
        'status' => Observasi::STATUS_TIDAK_HADIR,
        'scheduled_by' => $admin->id,
    ]);

    // Reschedule to 4 days from now (before MPLS date)
    $newScheduledAt = now()->addDays(4)->format('Y-m-d H:i:s');

    $response = $this->actingAs($admin)
        ->post(route('admin.observasi.jadwal-ulang', $observasi->id), [
            'scheduled_at' => $newScheduledAt,
            'reschedule_reason' => 'Anak sakit',
        ]);

    $response->assertRedirect();
    $response->assertSessionHas('success');

    $observasi->refresh();
    expect($observasi->status)->toBe(Observasi::STATUS_DIJADWALKAN_ULANG)
        ->and($observasi->reschedule_reason)->toBe('Anak sakit');

    $this->assertDatabaseHas('spmb_observasi', [
        'pendaftaran_detail_id' => $detail->id,
        'attempt_number' => 2,
        'status' => Observasi::STATUS_DIJADWALKAN,
        'rescheduled_from_id' => $observasi->id,
    ]);

    Notification::assertSentTo($parent, ObservasiDijadwalkanNotification::class);

    $log = ActivityLog::latest()->first();
    expect($log->action)->toBe('observation_rescheduled');
});

test('9. rescheduling past H-3 MPLS is blocked', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $pendaftaran = Pendaftaran::factory()->create(['tanggal_mpls' => now()->addDays(2)]); // MPLS in 2 days (past H-3 deadline)

    $detail = createDetail([
        'pendaftaran' => $pendaftaran,
        'status' => PendaftaranDetail::STATUS_ADMINISTRASI_LENGKAP,
    ]);

    $observasi = Observasi::create([
        'pendaftaran_detail_id' => $detail->id,
        'attempt_number' => 1,
        'scheduled_at' => now()->subDays(1),
        'status' => Observasi::STATUS_TIDAK_HADIR,
        'scheduled_by' => $admin->id,
    ]);

    $newScheduledAt = now()->addHours(12)->format('Y-m-d H:i:s');

    // Make rescheduling attempt
    $response = $this->actingAs($admin)
        ->post(route('admin.observasi.jadwal-ulang', $observasi->id), [
            'scheduled_at' => $newScheduledAt,
            'reschedule_reason' => 'Ingin jadwal ulang',
        ]);

    $response->assertRedirect();
    $response->assertSessionHas('error'); // Throws/returns error message because deadline has passed

    expect(Observasi::count())->toBe(1); // No new attempt created
});

test('10. admin can complete observation and record qualitative results', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $parent = User::factory()->create(['role' => 'parent']);
    $siswa = Siswa::factory()->create(['user_id' => $parent->id]);

    $detail = createDetail([
        'parent' => $parent,
        'siswa' => $siswa,
        'status' => PendaftaranDetail::STATUS_ADMINISTRASI_LENGKAP,
    ]);

    $observasi = Observasi::create([
        'pendaftaran_detail_id' => $detail->id,
        'attempt_number' => 1,
        'scheduled_at' => now()->addDays(1),
        'status' => Observasi::STATUS_HADIR,
        'scheduled_by' => $admin->id,
    ]);

    $response = $this->actingAs($admin)
        ->patch(route('admin.observasi.selesai', $observasi->id), [
            'tinggi_badan_cm' => 110.5,
            'berat_badan_kg' => 18.3,
            'catatan_wawancara_orang_tua' => 'Orang tua mendukung program sekolah.',
            'catatan_aktivitas_anak' => 'Anak aktif and bersosialisasi dengan baik.',
            'catatan_kesiapan_anak' => 'Siap masuk Kelompok A.',
            'membutuhkan_dukungan_khusus' => 0,
            'catatan_sekolah' => 'Internal: Kesiapan sangat baik.',
        ]);

    $response->assertRedirect();
    $response->assertSessionHas('success');

    $observasi->refresh();
    expect($observasi->status)->toBe(Observasi::STATUS_SELESAI)
        ->and((float) $observasi->tinggi_badan_cm)->toBe(110.5)
        ->and($observasi->catatan_sekolah)->toBe('Internal: Kesiapan sangat baik.');

    $detail->refresh();
    expect($detail->status)->toBe(PendaftaranDetail::STATUS_MENUNGGU_KEPUTUSAN);

    Notification::assertSentTo($parent, ObservasiSelesaiNotification::class);

    $log = ActivityLog::latest()->first();
    expect($log->action)->toBe('observation_completed');
});

test('11. parent dashboard / status views do not leak internal notes', function () {
    $parent = User::factory()->create(['role' => 'parent']);
    $siswa = Siswa::factory()->create(['user_id' => $parent->id]);

    $detail = createDetail([
        'parent' => $parent,
        'siswa' => $siswa,
        'status' => PendaftaranDetail::STATUS_MENUNGGU_KEPUTUSAN,
    ]);

    $observasi = Observasi::create([
        'pendaftaran_detail_id' => $detail->id,
        'attempt_number' => 1,
        'scheduled_at' => now()->subDays(1),
        'status' => Observasi::STATUS_SELESAI,
        'catatan_sekolah' => 'Internal secret note',
    ]);

    // Parent accesses status page
    $responseStatus = $this->actingAs($parent)
        ->get(route('parent.siswa.pendaftaran.status', $siswa->id));

    $responseStatus->assertOk();
    $responseStatus->assertDontSee('Internal secret note');

    // Parent accesses dashboard
    $responseDashboard = $this->actingAs($parent)
        ->get(route('parent.dashboard'));

    $responseDashboard->assertOk();
    $responseDashboard->assertDontSee('Internal secret note');
});
