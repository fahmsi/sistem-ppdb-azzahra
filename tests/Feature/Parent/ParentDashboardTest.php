<?php

use App\Http\Controllers\ParentDashboardController;
use App\Models\PaymentSetting;
use App\Models\Pembayaran;
use App\Models\Pendaftaran;
use App\Models\PendaftaranDetail;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Support\Facades\Route;

test('ParentDashboard route uses index once inside parent authentication middleware', function () {
    $route = Route::getRoutes()->getByName('parent.dashboard');

    expect($route)
        ->not->toBeNull()
        ->and($route->uri())->toBe('parent/dashboard')
        ->and($route->methods())->toContain('GET')
        ->and($route->getActionName())->toBe(ParentDashboardController::class.'@index')
        ->and($route->gatherMiddleware())->toContain('auth')
        ->and($route->gatherMiddleware())->toContain('role:parent')
        ->and(
            collect(Route::getRoutes())
                ->filter(fn ($registeredRoute) => $registeredRoute->getName() === 'parent.dashboard')
                ->count()
        )->toBe(1);
});

test('ParentDashboard index renders only the authenticated parents children', function () {
    $parent = User::factory()->create(['role' => 'parent']);
    $otherParent = User::factory()->create(['role' => 'parent']);
    $first = Siswa::factory()->for($parent)->create(['nama' => 'Anak Dashboard Pertama']);
    $second = Siswa::factory()->for($parent)->create(['nama' => 'Anak Dashboard Kedua']);
    $otherChild = Siswa::factory()->for($otherParent)->create(['nama' => 'Anak Dashboard Orang Lain']);

    $this->actingAs($parent)
        ->get(route('parent.dashboard'))
        ->assertOk()
        ->assertSee($first->nama)
        ->assertSee($second->nama)
        ->assertDontSee($otherChild->nama);
});

test('ParentDashboard renders the six real-time steps and a focused first action', function () {
    $parent = User::factory()->create(['role' => 'parent']);

    $this->actingAs($parent)
        ->get(route('parent.dashboard'))
        ->assertOk()
        ->assertSee("Assalamu'alaikum", false)
        ->assertSee('Panduan Langkah Pendaftaran (SPMB)')
        ->assertSee('Langkah 1')
        ->assertSee('Langkah 6')
        ->assertSee('Isi Data Anak')
        ->assertSee('Resmi Terdaftar')
        ->assertSee('Aksi Cepat')
        ->assertSee('Butuh Bantuan?')
        ->assertSee(route('parent.siswa.create'), false)
        ->assertSee('animate-ping', false)
        ->assertDontSee('Observasi')
        ->assertDontSee('Berkas yang Perlu Disiapkan');
});

test('ParentDashboard advances an unregistered child to wave selection', function () {
    $parent = User::factory()->create(['role' => 'parent']);
    $siswa = Siswa::factory()->for($parent)->create(['nama' => 'Anak Pilih Gelombang']);

    $this->actingAs($parent)
        ->get(route('parent.dashboard'))
        ->assertOk()
        ->assertSee($siswa->nama)
        ->assertSee('Data tersimpan')
        ->assertSee('Pilih sekarang')
        ->assertSee('Daftar Gelombang')
        ->assertSee(route('parent.siswa.pendaftaran.index', $siswa), false);
});

test('ParentDashboard status card follows the selected childs latest administration and payment data', function () {
    $parent = User::factory()->create(['role' => 'parent']);
    $siswa = Siswa::factory()->for($parent)->create(['nama' => 'Anak Status Terkini']);
    $detail = createParentDashboardRegistration($siswa, PendaftaranDetail::STATUS_MENUNGGU_VERIFIKASI);
    $detail->pendaftaran->update([
        'gelombang' => 'Gelombang Status Test',
        'tahun_ajaran' => '2026/2027',
    ]);
    $detail->update([
        'no_pendaftaran' => 'SPMB-STATUS-001',
        'notifikasi' => 'Dokumen sedang diperiksa oleh panitia.',
    ]);

    $this->actingAs($parent)
        ->get(route('parent.dashboard'))
        ->assertOk()
        ->assertSee('Status Pendaftaran Anak Saat Ini')
        ->assertSee($siswa->nama)
        ->assertSee('SPMB-STATUS-001')
        ->assertSee('Gelombang Status Test')
        ->assertSee('TA 2026/2027')
        ->assertSee('Sedang diverifikasi')
        ->assertSee('Belum dimulai')
        ->assertSee('Dokumen sedang diperiksa oleh panitia.')
        ->assertSee('Diperbarui')
        ->assertSee(route('parent.siswa.pendaftaran.status', $siswa), false);

    $detail->update([
        'status' => PendaftaranDetail::STATUS_DITERIMA,
        'notifikasi' => 'Berkas administrasi diterima.',
    ]);
    Pembayaran::query()->create([
        'pendaftaran_detail_id' => $detail->id,
        'jumlah' => 500000,
        'bukti_bayar' => 'pembayaran/status-test.png',
        'status' => Pembayaran::STATUS_DITOLAK,
        'catatan_admin' => 'Nominal pada bukti belum terlihat jelas.',
    ]);

    $this->actingAs($parent)
        ->get(route('parent.dashboard'))
        ->assertOk()
        ->assertSee('Berkas diterima')
        ->assertSee('Perlu unggah ulang')
        ->assertSee('Nominal pada bukti belum terlihat jelas.');
});

test('ParentDashboard maps administration states to the right current step and action', function (
    string $status,
    string $stepLabel,
    string $actionLabel,
) {
    $parent = User::factory()->create(['role' => 'parent']);
    $siswa = Siswa::factory()->for($parent)->create();
    createParentDashboardRegistration($siswa, $status);

    $this->actingAs($parent)
        ->get(route('parent.dashboard'))
        ->assertOk()
        ->assertSee('Verifikasi Administrasi')
        ->assertSee($stepLabel)
        ->assertSee($actionLabel)
        ->assertSee('animate-ping', false);
})->with([
    'waiting' => [PendaftaranDetail::STATUS_MENUNGGU_VERIFIKASI, 'Menunggu verifikasi', 'Lihat Status Pendaftaran'],
    'revision' => [PendaftaranDetail::STATUS_PERLU_REVISI, 'Perlu revisi', 'Perbaiki Data Anak'],
    'rejected' => [PendaftaranDetail::STATUS_DITOLAK, 'Tidak dilanjutkan', 'Daftar Gelombang'],
]);

test('ParentDashboard maps payment progress from upload through final receipt', function () {
    $parent = User::factory()->create(['role' => 'parent']);
    $siswa = Siswa::factory()->for($parent)->create();
    $detail = createParentDashboardRegistration($siswa, PendaftaranDetail::STATUS_DITERIMA);
    PaymentSetting::query()->create([
        'bank_name' => 'Bank Test',
        'account_number' => '1234567890',
        'account_holder_name' => 'PAUD Az-Zahra',
        'amount' => 500000,
    ]);

    $this->actingAs($parent)
        ->get(route('parent.dashboard'))
        ->assertOk()
        ->assertSee('Unggah bukti')
        ->assertSee('Upload Bukti Pembayaran')
        ->assertSee(route('parent.siswa.pendaftaran.status', $siswa), false);

    $payment = Pembayaran::query()->create([
        'pendaftaran_detail_id' => $detail->id,
        'jumlah' => 500000,
        'bukti_bayar' => 'pembayaran/test.png',
        'status' => Pembayaran::STATUS_DITOLAK,
        'catatan_admin' => 'Bukti tidak terbaca.',
    ]);

    $this->actingAs($parent)
        ->get(route('parent.dashboard'))
        ->assertOk()
        ->assertSee('Bukti ditolak')
        ->assertSee('Unggah Ulang Bukti Pembayaran');

    $payment->update(['status' => Pembayaran::STATUS_MENUNGGU_VERIFIKASI]);

    $this->actingAs($parent)
        ->get(route('parent.dashboard'))
        ->assertOk()
        ->assertSee('Bukti pembayaran sudah dikirim')
        ->assertSee('Lihat Status Pembayaran');

    $payment->update(['status' => Pembayaran::STATUS_LUNAS]);

    $this->actingAs($parent)
        ->get(route('parent.dashboard'))
        ->assertOk()
        ->assertSee('Pembayaran lunas')
        ->assertSee('Seluruh proses selesai')
        ->assertSee('Lihat Bukti Pembayaran')
        ->assertSee('Lihat Kartu Pendaftaran')
        ->assertSee(route('parent.pembayaran.receipt', $detail), false)
        ->assertSee(route('parent.siswa.pendaftaran.kartu', [
            'siswa' => $siswa,
            'detail' => $detail,
        ]), false);
});

test('ParentDashboard prioritizes children needing action and accepts an owned child selection', function () {
    $parent = User::factory()->create(['role' => 'parent']);
    $completedChild = Siswa::factory()->for($parent)->create(['nama' => 'Anak Selesai']);
    $revisionChild = Siswa::factory()->for($parent)->create(['nama' => 'Anak Revisi']);

    $completedDetail = createParentDashboardRegistration($completedChild, PendaftaranDetail::STATUS_DITERIMA);
    Pembayaran::query()->create([
        'pendaftaran_detail_id' => $completedDetail->id,
        'jumlah' => 500000,
        'bukti_bayar' => 'pembayaran/lunas.png',
        'status' => Pembayaran::STATUS_LUNAS,
    ]);
    createParentDashboardRegistration($revisionChild, PendaftaranDetail::STATUS_PERLU_REVISI);

    $this->actingAs($parent)
        ->get(route('parent.dashboard'))
        ->assertOk()
        ->assertSee('Tampilkan progres anak')
        ->assertSee('Perbaiki Data Anak')
        ->assertSee('Anak Revisi');

    $this->actingAs($parent)
        ->get(route('parent.dashboard', ['siswa' => $completedChild]))
        ->assertOk()
        ->assertSee('Anak Selesai')
        ->assertSee('Seluruh proses selesai')
        ->assertSee('Lihat Bukti Pembayaran');
});

test('ParentDashboard never selects another parents child from the query string', function () {
    $parent = User::factory()->create(['role' => 'parent']);
    $ownedChild = Siswa::factory()->for($parent)->create(['nama' => 'Anak Milik Sendiri']);
    $otherParent = User::factory()->create(['role' => 'parent']);
    $otherChild = Siswa::factory()->for($otherParent)->create(['nama' => 'Anak Bukan Miliknya']);

    $this->actingAs($parent)
        ->get(route('parent.dashboard', ['siswa' => $otherChild]))
        ->assertOk()
        ->assertSee($ownedChild->nama)
        ->assertDontSee($otherChild->nama);
});

test('parent navigation no longer includes a separate registration guide menu', function () {
    $layout = file_get_contents(resource_path('views/layouts/app.blade.php'));

    expect($layout)
        ->not->toContain('>Panduan Pendaftaran</span>')
        ->not->toContain('> Panduan Pendaftaran</a>');
});

test('parent child list is full width and has one clear add action', function () {
    $parent = User::factory()->create(['role' => 'parent']);

    $response = $this->actingAs($parent)
        ->get(route('parent.siswa.index'))
        ->assertOk()
        ->assertSee('Data Anak')
        ->assertSee('Kelola data anak dan lanjutkan pendaftaran.')
        ->assertDontSee('Anak Saya')
        ->assertDontSee('masing-masing anak')
        ->assertDontSee('Tambah Data Anak')
        ->assertSee('class="w-full space-y-6"', false);

    expect(substr_count($response->getContent(), 'Tambah Anak'))->toBe(1);
});

test('ParentDashboard requires authentication', function () {
    $this->get(route('parent.dashboard'))
        ->assertRedirect(route('login'));
});

test('admin roles use the admin dashboard and cannot open the parent dashboard', function (string $role) {
    $user = User::factory()->create(['role' => $role]);

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertRedirect(route('admin.dashboard'));

    $this->actingAs($user)
        ->get(route('parent.dashboard'))
        ->assertForbidden();
})->with([
    'admin' => 'admin',
    'super admin' => 'super_admin',
]);

function createParentDashboardRegistration(Siswa $siswa, string $status): PendaftaranDetail
{
    $pendaftaran = Pendaftaran::factory()->open()->create();

    return PendaftaranDetail::query()->create([
        'siswa_id' => $siswa->id,
        'pendaftaran_id' => $pendaftaran->id,
        'no_pendaftaran' => 'SPMB-TEST-'.$siswa->id,
        'status' => $status,
    ]);
}
