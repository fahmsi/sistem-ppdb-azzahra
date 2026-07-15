<?php

use App\Models\Pembayaran;
use App\Models\PaymentSetting;
use App\Models\Pendaftaran;
use App\Models\PendaftaranDetail;
use App\Models\Siswa;
use App\Models\User;
use App\Models\Observasi;
use App\Notifications\FinalEnrollmentNotification;
use App\Services\AdmissionDecisionService;
use App\Services\FinalEnrollmentService;
use App\Services\PaymentVerificationService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;

function pr5AcceptedDetail(array $attributes = []): PendaftaranDetail
{
    $parent = $attributes['parent'] ?? User::factory()->create(['role' => 'parent']);
    $siswa = $attributes['siswa'] ?? Siswa::factory()->create(['user_id' => $parent->id]);
    $pendaftaran = $attributes['pendaftaran'] ?? Pendaftaran::factory()->create();

    return PendaftaranDetail::create(array_merge([
        'siswa_id' => $siswa->id, 'pendaftaran_id' => $pendaftaran->id,
        'no_pendaftaran' => 'PR5-'.fake()->unique()->numerify('#####'),
        'status' => PendaftaranDetail::STATUS_KEPUTUSAN_SELESAI,
        'keputusan_status' => PendaftaranDetail::KEPUTUSAN_DITERIMA,
        'keputusan_diputuskan_at' => now(), 'kelompok_final' => 'A',
    ], array_diff_key($attributes, ['parent' => 1, 'siswa' => 1, 'pendaftaran' => 1])));
}

beforeEach(function () { Storage::fake('local'); });

function pr5DecisionReadyDetail(): PendaftaranDetail
{
    $detail = pr5AcceptedDetail([
        'status' => PendaftaranDetail::STATUS_MENUNGGU_KEPUTUSAN,
        'keputusan_status' => null,
        'keputusan_diputuskan_at' => null,
    ]);

    Observasi::create([
        'pendaftaran_detail_id' => $detail->id,
        'attempt_number' => 1,
        'scheduled_at' => now()->subDay(),
        'status' => Observasi::STATUS_SELESAI,
        'tinggi_badan_cm' => 100,
        'berat_badan_kg' => 15,
        'catatan_wawancara_orang_tua' => 'Aman untuk test',
        'catatan_aktivitas_anak' => 'Aman untuk test',
        'catatan_kesiapan_anak' => 'Aman untuk test',
        'membutuhkan_dukungan_khusus' => false,
        'scheduled_by' => User::factory()->create(['role' => 'admin'])->id,
    ]);

    return $detail;
}

function pr5OfficialDetail(?User $parent = null): array
{
    $detail = pr5AcceptedDetail($parent ? ['parent' => $parent] : []);
    Pembayaran::create([
        'pendaftaran_detail_id' => $detail->id,
        'jumlah' => 250000,
        'bukti_bayar' => 'pembayaran/official.png',
        'status' => Pembayaran::STATUS_LUNAS,
        'verified_at' => now(),
    ]);
    $detail->update([
        'final_status' => PendaftaranDetail::FINAL_SISWA_RESMI_TERDAFTAR,
        'final_ditetapkan_at' => now(),
    ]);

    return [$detail->fresh(), $detail->siswa->fresh(), $detail->siswa->user];
}

test('accepted decision remains in final enrollment process until verified payment', function () {
    $detail = pr5AcceptedDetail();

    expect($detail->final_status)->toBe(PendaftaranDetail::FINAL_DALAM_PROSES)
        ->and($detail->canSubmitPayment())->toBeTrue()
        ->and($detail->canPrintOfficialCard())->toBeFalse();
});

test('payment verification finalizes accepted child atomically', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $detail = pr5AcceptedDetail();
    $payment = Pembayaran::create(['pendaftaran_detail_id' => $detail->id, 'jumlah' => 250000, 'bukti_bayar' => 'pembayaran/test.png', 'status' => Pembayaran::STATUS_MENUNGGU_VERIFIKASI]);

    app(PaymentVerificationService::class)->verify($payment, Pembayaran::STATUS_LUNAS, null, $admin->id);

    $detail->refresh();
    $payment->refresh();
    expect($payment->isLunas())->toBeTrue()
        ->and($payment->verified_by)->toBe($admin->id)
        ->and($detail->isSiswaResmiTerdaftar())->toBeTrue()
        ->and($detail->finalisasiHistories()->count())->toBe(1);
});

test('rejected payment remains in process and reupload resets verification metadata', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $detail = pr5AcceptedDetail();
    $payment = Pembayaran::create(['pendaftaran_detail_id' => $detail->id, 'jumlah' => 250000, 'bukti_bayar' => 'pembayaran/old.png', 'status' => Pembayaran::STATUS_MENUNGGU_VERIFIKASI]);
    app(PaymentVerificationService::class)->verify($payment, Pembayaran::STATUS_DITOLAK, 'Bukti buram', $admin->id);
    $detail->refresh();
    expect($detail->isFinalDalamProses())->toBeTrue()->and($detail->canSubmitPayment())->toBeTrue();
    PaymentSetting::create(['bank_name' => 'Bank Test', 'account_number' => '123456', 'account_holder_name' => 'PAUD Test', 'amount' => 250000]);

    $this->actingAs($detail->siswa->user)->post(route('parent.pembayaran.store', $detail), ['bukti_bayar' => UploadedFile::fake()->image('baru.png')])->assertRedirect();
    $payment->refresh();
    expect($payment->status)->toBe(Pembayaran::STATUS_MENUNGGU_VERIFIKASI)
        ->and($payment->verified_by)->toBeNull()
        ->and($payment->verified_at)->toBeNull();
});

test('terminal payment cannot be verified twice and creates no duplicate history', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $detail = pr5AcceptedDetail();
    $payment = Pembayaran::create(['pendaftaran_detail_id' => $detail->id, 'jumlah' => 250000, 'bukti_bayar' => 'pembayaran/test.png', 'status' => Pembayaran::STATUS_MENUNGGU_VERIFIKASI]);
    $service = app(PaymentVerificationService::class);
    $service->verify($payment, Pembayaran::STATUS_LUNAS, null, $admin->id);
    expect(fn () => $service->verify($payment->fresh(), Pembayaran::STATUS_LUNAS, null, $admin->id))->toThrow(RuntimeException::class)
        ->and($detail->finalisasiHistories()->count())->toBe(1);
});

test('manual discontinuation records terminal snapshot and append only history', function () {
    $detail = pr5AcceptedDetail();
    $admin = User::factory()->create(['role' => 'admin']);
    app(FinalEnrollmentService::class)->transition($detail, PendaftaranDetail::FINAL_PENDAFTARAN_TIDAK_DILANJUTKAN, FinalEnrollmentService::SOURCE_MANUAL_DISCONTINUE, $admin->id, 'Tidak melanjutkan daftar ulang');
    $detail->refresh();
    expect($detail->isPendaftaranTidakDilanjutkan())->toBeTrue()
        ->and($detail->isActive())->toBeFalse()
        ->and($detail->finalisasiHistories()->count())->toBe(1);
});

test('admission decisions synchronize every required final state', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $service = app(AdmissionDecisionService::class);

    $accepted = $service->makeDecision(pr5DecisionReadyDetail(), ['keputusan_status' => PendaftaranDetail::KEPUTUSAN_DITERIMA], $admin->id);
    $notAccepted = $service->makeDecision(pr5DecisionReadyDetail(), ['keputusan_status' => PendaftaranDetail::KEPUTUSAN_TIDAK_DITERIMA, 'keputusan_alasan' => 'Kuota selesai'], $admin->id);
    $withdrawn = $service->makeDecision(pr5DecisionReadyDetail(), ['keputusan_status' => PendaftaranDetail::KEPUTUSAN_MENGUNDURKAN_DIRI, 'keputusan_alasan' => 'Wali mengundurkan diri'], $admin->id);
    $followUp = $service->makeDecision(pr5DecisionReadyDetail(), ['keputusan_status' => PendaftaranDetail::KEPUTUSAN_PERLU_TINDAK_LANJUT, 'keputusan_alasan' => 'Perlu komunikasi'], $admin->id);

    expect($accepted->final_status)->toBe(PendaftaranDetail::FINAL_DALAM_PROSES)
        ->and($notAccepted->final_status)->toBe(PendaftaranDetail::FINAL_PENDAFTARAN_TIDAK_DILANJUTKAN)
        ->and($notAccepted->finalisasiHistories()->count())->toBe(1)
        ->and($withdrawn->final_status)->toBe(PendaftaranDetail::FINAL_MENGUNDURKAN_DIRI)
        ->and($withdrawn->finalisasiHistories()->count())->toBe(1)
        ->and($followUp->final_status)->toBe(PendaftaranDetail::FINAL_DALAM_PROSES)
        ->and($followUp->isActive())->toBeTrue();
});

test('rejected payment requires new upload before another verification', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $detail = pr5AcceptedDetail();
    $payment = Pembayaran::create(['pendaftaran_detail_id' => $detail->id, 'jumlah' => 250000, 'bukti_bayar' => 'pembayaran/test.png', 'status' => Pembayaran::STATUS_MENUNGGU_VERIFIKASI]);
    $service = app(PaymentVerificationService::class);
    $service->verify($payment, Pembayaran::STATUS_DITOLAK, 'Nominal tidak terbaca', $admin->id);

    expect(fn () => $service->verify($payment->fresh(), Pembayaran::STATUS_DITOLAK, 'Ditolak ulang', $admin->id))->toThrow(RuntimeException::class)
        ->and($payment->fresh()->status)->toBe(Pembayaran::STATUS_DITOLAK)
        ->and($detail->fresh()->isFinalDalamProses())->toBeTrue();
});

test('verify retry creates neither duplicate final history nor notification', function () {
    Notification::fake();
    $admin = User::factory()->create(['role' => 'admin']);
    $detail = pr5AcceptedDetail();
    $payment = Pembayaran::create(['pendaftaran_detail_id' => $detail->id, 'jumlah' => 250000, 'bukti_bayar' => 'pembayaran/test.png', 'status' => Pembayaran::STATUS_MENUNGGU_VERIFIKASI]);

    $this->actingAs($admin)->patch(route('admin.pembayaran.verify', $payment), ['status' => Pembayaran::STATUS_LUNAS])->assertSessionHas('success');
    $this->actingAs($admin)->patch(route('admin.pembayaran.verify', $payment), ['status' => Pembayaran::STATUS_LUNAS])->assertSessionHas('error');

    expect($detail->finalisasiHistories()->count())->toBe(1)
        ->and(Notification::sent($detail->siswa->user, FinalEnrollmentNotification::class)->count())->toBe(1);
});

test('manual discontinuation endpoint accepts valid state and rejects paid enrollment', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $detail = pr5AcceptedDetail();

    $this->actingAs($admin)->post(route('admin.verifikasi.final.tidak-dilanjutkan', $detail), [
        'final_alasan' => 'Tidak ada konfirmasi dari wali', 'final_catatan' => 'Internal saja',
    ])->assertSessionHas('success');
    expect($detail->fresh()->isPendaftaranTidakDilanjutkan())->toBeTrue();

    $paid = pr5AcceptedDetail();
    Pembayaran::create(['pendaftaran_detail_id' => $paid->id, 'jumlah' => 250000, 'bukti_bayar' => 'pembayaran/paid.png', 'status' => Pembayaran::STATUS_LUNAS]);
    $this->actingAs($admin)->post(route('admin.verifikasi.final.tidak-dilanjutkan', $paid), ['final_alasan' => 'Tidak dapat ditutup'])->assertSessionHas('error');
    expect($paid->fresh()->isFinalDalamProses())->toBeTrue();
});

test('kartu and receipt require the strict official final state and preserve multi child isolation', function () {
    $parent = User::factory()->create(['role' => 'parent']);
    [$official, $officialChild] = pr5OfficialDetail($parent);
    $otherChild = Siswa::factory()->create(['user_id' => $parent->id]);
    $notOfficial = pr5AcceptedDetail(['parent' => $parent, 'siswa' => $otherChild]);
    Pembayaran::create(['pendaftaran_detail_id' => $notOfficial->id, 'jumlah' => 250000, 'bukti_bayar' => 'pembayaran/lunas.png', 'status' => Pembayaran::STATUS_LUNAS]);

    $this->actingAs($parent)->get(route('parent.siswa.pendaftaran.kartu', ['siswa' => $officialChild, 'detail' => $official]))->assertOk();
    $this->actingAs($parent)->get(route('parent.pembayaran.receipt', $official))->assertOk();
    $this->actingAs($parent)->get(route('parent.siswa.pendaftaran.kartu', ['siswa' => $otherChild, 'detail' => $notOfficial]))->assertForbidden();
    $this->actingAs($parent)->get(route('parent.siswa.pendaftaran.kartu', ['siswa' => $officialChild, 'detail' => $notOfficial]))->assertNotFound();
});

test('mpls kbm and parent safe final reason are shown only after official enrollment without internal note', function () {
    $parent = User::factory()->create(['role' => 'parent']);
    [$official, $child] = pr5OfficialDetail($parent);
    $official->pendaftaran->update(['tanggal_mulai_kbm' => now()->addWeek()->toDateString(), 'informasi_kbm' => 'Masuk pukul tujuh']);
    $official->update(['final_catatan' => 'RAHASIA_FINAL', 'final_alasan' => 'Aman ditampilkan']);

    $this->actingAs($parent)->get(route('parent.siswa.pendaftaran.status', $child))
        ->assertSee('Mulai KBM')
        ->assertDontSee('RAHASIA_FINAL')
        ->assertDontSee('catatan_wawancara_orang_tua');

    $inProcess = pr5AcceptedDetail(['parent' => $parent]);
    $inProcess->pendaftaran->update(['tanggal_mulai_kbm' => now()->addWeek()->toDateString(), 'informasi_kbm' => 'INTERNAL KBM']);
    $this->actingAs($parent)->get(route('parent.siswa.pendaftaran.status', $inProcess->siswa))
        ->assertDontSee('INTERNAL KBM');
});

test('registration with finalization history cannot be deleted', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $detail = pr5AcceptedDetail();
    app(FinalEnrollmentService::class)->transition($detail, PendaftaranDetail::FINAL_PENDAFTARAN_TIDAK_DILANJUTKAN, FinalEnrollmentService::SOURCE_MANUAL_DISCONTINUE, $admin->id, 'Tutup');

    $this->actingAs($admin)->delete(route('admin.verifikasi.destroy', $detail))->assertSessionHas('error');
    expect(PendaftaranDetail::whereKey($detail->id)->exists())->toBeTrue();
});

test('locked verification rejects a sequential competing request without a second finalization', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $detail = pr5AcceptedDetail();
    $payment = Pembayaran::create(['pendaftaran_detail_id' => $detail->id, 'jumlah' => 250000, 'bukti_bayar' => 'pembayaran/test.png', 'status' => Pembayaran::STATUS_PENDING]);
    $service = app(PaymentVerificationService::class);
    $service->verify($payment, Pembayaran::STATUS_LUNAS, null, $admin->id);

    expect(fn () => $service->verify($payment->fresh(), Pembayaran::STATUS_LUNAS, null, $admin->id))->toThrow(RuntimeException::class)
        ->and($detail->finalisasiHistories()->count())->toBe(1);
});

test('whatsapp transport failure never rolls back payment finalization', function () {
    Http::fake(fn () => throw new RuntimeException('WhatsApp unavailable'));
    $admin = User::factory()->create(['role' => 'admin']);
    $detail = pr5AcceptedDetail();
    $payment = Pembayaran::create(['pendaftaran_detail_id' => $detail->id, 'jumlah' => 250000, 'bukti_bayar' => 'pembayaran/test.png', 'status' => Pembayaran::STATUS_MENUNGGU_VERIFIKASI]);

    $this->actingAs($admin)->patch(route('admin.pembayaran.verify', $payment), ['status' => Pembayaran::STATUS_LUNAS])->assertSessionHas('success');
    expect($payment->fresh()->isLunas())->toBeTrue()
        ->and($detail->fresh()->isSiswaResmiTerdaftar())->toBeTrue();
});
