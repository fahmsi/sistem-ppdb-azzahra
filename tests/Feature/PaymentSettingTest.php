<?php

use App\Models\ActivityLog;
use App\Models\Pendaftaran;
use App\Models\PendaftaranDetail;
use App\Models\PaymentSetting;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

test('admin can view the payment configuration page', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $this->actingAs($admin)
        ->get(route('admin.payment-settings.edit'))
        ->assertOk()
        ->assertSee('Konfigurasi Pembayaran')
        ->assertSee('Belum dikonfigurasi');
});

test('parent cannot access the payment configuration page', function () {
    $parent = User::factory()->create(['role' => 'parent']);

    $this->actingAs($parent)
        ->get(route('admin.payment-settings.edit'))
        ->assertForbidden();
});

test('admin can update payment configuration without qris', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $this->actingAs($admin)
        ->put(route('admin.payment-settings.update'), [
            'bank_name' => 'Bank Syariah Indonesia',
            'account_number' => '0012345678',
            'account_holder_name' => 'Yayasan Azzahra',
            'amount' => 750000,
            'payment_note' => 'Cantumkan nama anak pada berita transfer.',
        ])
        ->assertRedirect()
        ->assertSessionHas('swal');

    $this->assertDatabaseHas('payment_settings', [
        'id' => PaymentSetting::SINGLETON_ID,
        'bank_name' => 'Bank Syariah Indonesia',
        'account_number' => '0012345678',
        'account_holder_name' => 'Yayasan Azzahra',
        'amount' => 750000,
        'qris_path' => null,
    ]);

    expect(PaymentSetting::query()->count())->toBe(1);

    $this->assertDatabaseHas('activity_logs', [
        'user_id' => $admin->id,
        'action' => 'updated',
        'description' => 'Memperbarui konfigurasi pembayaran',
    ]);
});

test('admin can replace and remove qris', function () {
    Storage::fake('public');

    $admin = User::factory()->create(['role' => 'admin']);
    $paymentSetting = PaymentSetting::create([
        'bank_name' => 'Bank Lama',
        'account_number' => '111111',
        'account_holder_name' => 'Pemilik Lama',
        'amount' => 500000,
        'qris_path' => 'payment/qris/old.png',
    ]);
    Storage::disk('public')->put($paymentSetting->qris_path, 'old qris');

    $this->actingAs($admin)->put(route('admin.payment-settings.update'), [
        'bank_name' => 'Bank Baru',
        'account_number' => '222222',
        'account_holder_name' => 'Pemilik Baru',
        'amount' => 600000,
        'qris' => UploadedFile::fake()->image('qris-baru.png', 500, 500),
    ])->assertRedirect();

    $paymentSetting->refresh();
    $newQrisPath = $paymentSetting->qris_path;

    expect($newQrisPath)->not->toBeNull();
    Storage::disk('public')->assertMissing('payment/qris/old.png');
    Storage::disk('public')->assertExists($newQrisPath);

    $this->actingAs($admin)->put(route('admin.payment-settings.update'), [
        'bank_name' => 'Bank Baru',
        'account_number' => '222222',
        'account_holder_name' => 'Pemilik Baru',
        'amount' => 600000,
        'remove_qris' => '1',
    ])->assertRedirect();

    expect($paymentSetting->refresh()->qris_path)->toBeNull();
    Storage::disk('public')->assertMissing($newQrisPath);
});

test('parent registration status shows configured payment information without an empty qris', function () {
    $parent = User::factory()->create(['role' => 'parent']);
    $detail = createAcceptedRegistrationForPaymentSettingTest($parent);

    PaymentSetting::create([
        'bank_name' => 'Bank Muamalat',
        'account_number' => '0099887766',
        'account_holder_name' => 'PAUD Al-Quran Azzahra',
        'amount' => 825000,
        'payment_note' => 'Simpan bukti transfer untuk diunggah.',
    ]);

    $this->actingAs($parent)
        ->get(route('parent.siswa.pendaftaran.status', $detail->siswa))
        ->assertOk()
        ->assertSee('Bank Muamalat')
        ->assertSee('0099887766')
        ->assertSee('PAUD Al-Quran Azzahra')
        ->assertSee('Rp 825.000')
        ->assertSee('Simpan bukti transfer untuk diunggah.')
        ->assertDontSee('alt="QRIS pembayaran"', false);
});

test('parent sees a friendly message before payment configuration exists', function () {
    $parent = User::factory()->create(['role' => 'parent']);
    $detail = createAcceptedRegistrationForPaymentSettingTest($parent);

    $this->actingAs($parent)
        ->get(route('parent.dashboard'))
        ->assertOk()
        ->assertSee('Informasi pembayaran belum tersedia. Silakan hubungi admin sekolah.')
        ->assertDontSee('alt="QRIS pembayaran"', false);
});

test('parent page hides qris when its stored file is missing', function () {
    Storage::fake('public');

    $parent = User::factory()->create(['role' => 'parent']);
    $detail = createAcceptedRegistrationForPaymentSettingTest($parent);

    PaymentSetting::create([
        'bank_name' => 'Bank BRI',
        'account_number' => '1234567890',
        'account_holder_name' => 'Yayasan Azzahra',
        'amount' => 500000,
        'qris_path' => 'payment/qris/missing.png',
    ]);

    $this->actingAs($parent)
        ->get(route('parent.siswa.pendaftaran.status', $detail->siswa))
        ->assertOk()
        ->assertSee('Bank BRI')
        ->assertDontSee('alt="QRIS pembayaran"', false);
});

test('parent page displays qris when its stored file exists', function () {
    Storage::fake('public');

    $parent = User::factory()->create(['role' => 'parent']);
    $detail = createAcceptedRegistrationForPaymentSettingTest($parent);

    $qrisPath = 'payment/qris/qris-sekolah.png';
    Storage::disk('public')->put($qrisPath, 'qris image');

    PaymentSetting::create([
        'bank_name' => 'Bank BRI',
        'account_number' => '1234567890',
        'account_holder_name' => 'Yayasan Azzahra',
        'amount' => 500000,
        'qris_path' => $qrisPath,
    ]);

    $this->actingAs($parent)
        ->get(route('parent.siswa.pendaftaran.status', $detail->siswa))
        ->assertOk()
        ->assertSee('alt="QRIS pembayaran"', false)
        ->assertSee('/storage/payment/qris/qris-sekolah.png', false);
});

function createAcceptedRegistrationForPaymentSettingTest(User $parent): PendaftaranDetail
{
    $siswa = Siswa::factory()->for($parent)->create();
    $pendaftaran = Pendaftaran::factory()->create();

    return PendaftaranDetail::create([
        'siswa_id' => $siswa->id,
        'pendaftaran_id' => $pendaftaran->id,
        'status' => PendaftaranDetail::STATUS_DITERIMA,
    ]);
}
