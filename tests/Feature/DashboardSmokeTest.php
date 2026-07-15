<?php

use App\Models\PaymentSetting;
use App\Models\Pembayaran;
use App\Models\Pendaftaran;
use App\Models\PendaftaranDetail;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

test('admin dashboard and management pages render for an admin', function (string $routeName) {
    $admin = User::factory()->create(['role' => 'admin']);

    $this->actingAs($admin)
        ->get(route($routeName))
        ->assertOk();
})->with([
    'dashboard' => 'admin.dashboard',
    'registration periods' => 'admin.pendaftaran.index',
    'students' => 'admin.siswa.index',
    'registrations to verify' => 'admin.verifikasi.index',
    'payments' => 'admin.pembayaran.index',
    'payment configuration' => 'admin.payment-settings.edit',
]);

test('super admin-only dashboard pages render and remain protected', function () {
    $superAdmin = User::factory()->create(['role' => 'super_admin']);
    $admin = User::factory()->create(['role' => 'admin']);

    foreach (['admin.kelola-admin.index', 'admin.activity-log.index'] as $routeName) {
        $this->actingAs($superAdmin)->get(route($routeName))->assertOk();
        $this->actingAs($admin)->get(route($routeName))->assertForbidden();
    }
});

test('admin dashboard exports generate downloadable files', function (string $routeName, string $type) {
    $admin = User::factory()->create(['role' => 'admin']);

    $this->actingAs($admin)
        ->get(route($routeName, ['type' => $type]))
        ->assertOk()
        ->assertDownload();
})->with([
    'students xlsx' => ['admin.siswa.export', 'xlsx'],
    'students csv' => ['admin.siswa.export', 'csv'],
    'students pdf' => ['admin.siswa.export', 'pdf'],
    'registrations xlsx' => ['admin.verifikasi.export', 'xlsx'],
    'registrations csv' => ['admin.verifikasi.export', 'csv'],
    'registrations pdf' => ['admin.verifikasi.export', 'pdf'],
    'payments xlsx' => ['admin.pembayaran.export', 'xlsx'],
    'payments csv' => ['admin.pembayaran.export', 'csv'],
    'payments pdf' => ['admin.pembayaran.export', 'pdf'],
]);

test('parent dashboard registration pages and every registration status render', function () {
    $parent = User::factory()->create(['role' => 'parent']);
    $siswa = Siswa::factory()->for($parent)->create();

    foreach ([
        PendaftaranDetail::STATUS_PENDING,
        PendaftaranDetail::STATUS_MENUNGGU_VERIFIKASI,
        PendaftaranDetail::STATUS_PERLU_REVISI,
        PendaftaranDetail::STATUS_DITERIMA,
        PendaftaranDetail::STATUS_DITOLAK,
    ] as $status) {
        $period = Pendaftaran::factory()->create([
            'status' => 'buka',
            'tanggal_mulai' => now()->subDay(),
            'tanggal_selesai' => now()->addMonth(),
        ]);

        PendaftaranDetail::create([
            'siswa_id' => $siswa->id,
            'pendaftaran_id' => $period->id,
            'status' => $status,
            'notifikasi' => "Status {$status}",
        ]);
    }

    $availablePeriod = Pendaftaran::factory()->create([
        'status' => 'buka',
        'tanggal_mulai' => now()->subDay(),
        'tanggal_selesai' => now()->addMonth(),
    ]);

    $this->actingAs($parent)->get(route('parent.dashboard'))->assertOk();
    $this->actingAs($parent)->get(route('parent.siswa.pendaftaran.index', $siswa))->assertOk();
    $this->actingAs($parent)
        ->get(route('parent.siswa.pendaftaran.show', ['siswa' => $siswa, 'pendaftaran' => $availablePeriod]))
        ->assertOk()
        ->assertSee($availablePeriod->gelombang);
    $this->actingAs($parent)
        ->get(route('parent.siswa.pendaftaran.status', $siswa))
        ->assertOk()
        ->assertSee('Menunggu Verifikasi Admin')
        ->assertSee('Perlu Revisi Dokumen')
        ->assertSee('Diterima')
        ->assertSee('Ditolak');
});

test('payment upload uses the amount configured by admin and can be verified', function () {
    Storage::fake('local');

    $parent = User::factory()->create(['role' => 'parent']);
    $admin = User::factory()->create(['role' => 'admin']);
    $siswa = Siswa::factory()->for($parent)->create();
    $period = Pendaftaran::factory()->create();
    $detail = PendaftaranDetail::create([
        'siswa_id' => $siswa->id,
        'pendaftaran_id' => $period->id,
        'status' => PendaftaranDetail::STATUS_KEPUTUSAN_SELESAI,
        'keputusan_status' => PendaftaranDetail::KEPUTUSAN_DITERIMA,
        'keputusan_diputuskan_at' => now(),
    ]);

    PaymentSetting::create([
        'bank_name' => 'Bank Demo',
        'account_number' => '123456789',
        'account_holder_name' => 'PAUD Az-Zahra',
        'amount' => 875000,
    ]);

    $this->actingAs($parent)
        ->post(route('parent.pembayaran.store', $detail), [
            'bukti_bayar' => UploadedFile::fake()->image('bukti.png'),
        ])
        ->assertRedirect()
        ->assertSessionHas('success');

    $payment = Pembayaran::query()->where('pendaftaran_detail_id', $detail->id)->firstOrFail();

    expect((int) $payment->jumlah)->toBe(875000)
        ->and($payment->status)->toBe(Pembayaran::STATUS_MENUNGGU_VERIFIKASI);
    Storage::disk('local')->assertExists($payment->bukti_bayar);

    $this->actingAs($admin)
        ->get(route('admin.pembayaran.index'))
        ->assertOk()
        ->assertSee($detail->nomor_pendaftaran);
    $this->actingAs($admin)
        ->get(route('admin.verifikasi.show', $detail))
        ->assertOk()
        ->assertSee('Bukti Daftar Ulang (Pembayaran)')
        ->assertSee('Simpan Verifikasi Bayar');
    $this->actingAs($admin)
        ->patch(route('admin.pembayaran.verify', $payment), ['status' => Pembayaran::STATUS_LUNAS])
        ->assertRedirect()
        ->assertSessionHas('success');

    expect($payment->refresh()->isLunas())->toBeTrue();

    $this->actingAs($parent)
        ->get(route('parent.pembayaran.receipt', $detail))
        ->assertOk();
});

test('payment upload is rejected until admin configuration exists', function () {
    Storage::fake('local');

    $parent = User::factory()->create(['role' => 'parent']);
    $siswa = Siswa::factory()->for($parent)->create();
    $detail = PendaftaranDetail::create([
        'siswa_id' => $siswa->id,
        'pendaftaran_id' => Pendaftaran::factory()->create()->id,
        'status' => PendaftaranDetail::STATUS_KEPUTUSAN_SELESAI,
        'keputusan_status' => PendaftaranDetail::KEPUTUSAN_DITERIMA,
        'keputusan_diputuskan_at' => now(),
    ]);

    $this->actingAs($parent)
        ->post(route('parent.pembayaran.store', $detail), [
            'bukti_bayar' => UploadedFile::fake()->image('bukti.png'),
        ])
        ->assertRedirect()
        ->assertSessionHas('error');

    $this->assertDatabaseMissing('spmb_pembayaran', [
        'pendaftaran_detail_id' => $detail->id,
    ]);
});

test('registration card remains unavailable until registration is accepted', function () {
    $parent = User::factory()->create(['role' => 'parent']);
    $siswa = Siswa::factory()->for($parent)->create();
    $detail = PendaftaranDetail::create([
        'siswa_id' => $siswa->id,
        'pendaftaran_id' => Pendaftaran::factory()->create()->id,
        'status' => PendaftaranDetail::STATUS_MENUNGGU_VERIFIKASI,
    ]);

    $this->actingAs($parent)
        ->get(route('parent.siswa.pendaftaran.kartu', ['siswa' => $siswa, 'detail' => $detail]))
        ->assertForbidden();

    $detail->update([
        'status' => PendaftaranDetail::STATUS_KEPUTUSAN_SELESAI,
        'keputusan_status' => PendaftaranDetail::KEPUTUSAN_DITERIMA,
        'keputusan_diputuskan_at' => now(),
    ]);
    Pembayaran::create([
        'pendaftaran_detail_id' => $detail->id,
        'jumlah' => 100000,
        'bukti_bayar' => 'bukti.png',
        'status' => Pembayaran::STATUS_LUNAS,
    ]);

    $this->actingAs($parent)
        ->get(route('parent.siswa.pendaftaran.kartu', ['siswa' => $siswa, 'detail' => $detail]))
        ->assertOk();
});
