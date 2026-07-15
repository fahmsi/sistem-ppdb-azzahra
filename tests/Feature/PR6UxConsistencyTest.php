<?php

use App\Models\Observasi;
use App\Models\Pembayaran;
use App\Models\Pendaftaran;
use App\Models\PendaftaranDetail;
use App\Models\Siswa;
use App\Models\User;
use App\Support\SpmbStatusPresenter;

function pr6Context(array $detailAttributes = []): array
{
    $parent = User::factory()->create(['role' => 'parent']);
    $siswa = Siswa::factory()->create(['user_id' => $parent->id]);
    $period = Pendaftaran::factory()->create([
        'tanggal_mpls' => '2026-08-03',
        'jam_mpls_mulai' => '08:00',
        'jam_mpls_selesai' => '11:30',
        'lokasi_mpls' => 'Sekolah',
        'tanggal_mulai_kbm' => '2026-08-10',
        'jam_masuk_kbm' => '08:00',
    ]);
    $detail = PendaftaranDetail::create(array_merge([
        'siswa_id' => $siswa->id,
        'pendaftaran_id' => $period->id,
        'no_pendaftaran' => 'PR6-'.fake()->unique()->numerify('#####'),
        'status' => PendaftaranDetail::STATUS_MENUNGGU_VERIFIKASI,
        'kelompok_rekomendasi' => 'A',
    ], $detailAttributes));

    return [$parent, $siswa, $period, $detail];
}

function pr6Accepted(array $attributes = []): array
{
    return pr6Context(array_merge([
        'status' => PendaftaranDetail::STATUS_KEPUTUSAN_SELESAI,
        'keputusan_status' => PendaftaranDetail::KEPUTUSAN_DITERIMA,
        'keputusan_diputuskan_at' => now(),
        'kelompok_final' => 'A',
        'final_status' => PendaftaranDetail::FINAL_DALAM_PROSES,
    ], $attributes));
}

test('1. parent tanpa anak melihat empty state yang dapat ditindaklanjuti', function () {
    $parent = User::factory()->create(['role' => 'parent']);

    $this->actingAs($parent)->get(route('parent.dashboard'))
        ->assertOk()
        ->assertSee('Belum Ada Data Anak')
        ->assertSee('Tambah Data Anak');
});

test('2. dua anak menampilkan identitas dan status secara terpisah', function () {
    [$parent, $first] = pr6Context(['status' => PendaftaranDetail::STATUS_PERLU_REVISI]);
    $second = Siswa::factory()->create(['user_id' => $parent->id, 'nama' => 'Anak Kedua PR6']);

    $this->actingAs($parent)->get(route('parent.dashboard'))
        ->assertOk()
        ->assertSee($first->nama)
        ->assertSee('Anak Kedua PR6')
        ->assertSee('Perlu Perbaikan Data')
        ->assertSee('Belum Ada Pendaftaran');
});

test('3. perlu revisi menampilkan satu aksi perbaikan yang tepat', function () {
    [$parent] = pr6Context(['status' => PendaftaranDetail::STATUS_PERLU_REVISI]);

    $this->actingAs($parent)->get(route('parent.dashboard'))
        ->assertOk()
        ->assertSee('Perbaiki Data Anak')
        ->assertDontSee('Lanjutkan Daftar Ulang');
});

test('4. observasi dijadwalkan menampilkan jadwal parent-safe', function () {
    [$parent, $siswa, , $detail] = pr6Context(['status' => PendaftaranDetail::STATUS_ADMINISTRASI_LENGKAP]);
    Observasi::create([
        'pendaftaran_detail_id' => $detail->id,
        'attempt_number' => 1,
        'scheduled_at' => now()->addDay()->setTime(9, 0),
        'status' => Observasi::STATUS_DIJADWALKAN,
        'catatan_wawancara_orang_tua' => 'RAHASIA_WAWANCARA_PR6',
        'catatan_aktivitas_anak' => 'RAHASIA_AKTIVITAS_PR6',
        'catatan_kesiapan_anak' => 'RAHASIA_KESIAPAN_PR6',
        'membutuhkan_dukungan_khusus' => true,
        'scheduled_by' => User::factory()->create(['role' => 'admin'])->id,
    ]);

    $this->actingAs($parent)->get(route('parent.siswa.pendaftaran.status', $siswa))
        ->assertOk()
        ->assertSee('Jadwal Observasi')
        ->assertDontSee('RAHASIA_WAWANCARA_PR6')
        ->assertDontSee('RAHASIA_AKTIVITAS_PR6');
});

test('5. menunggu keputusan menggunakan copy Keputusan Sekolah', function () {
    [$parent, $siswa] = pr6Context(['status' => PendaftaranDetail::STATUS_MENUNGGU_KEPUTUSAN]);

    $this->actingAs($parent)->get(route('parent.siswa.pendaftaran.status', $siswa))
        ->assertOk()
        ->assertSee('Menunggu Keputusan Sekolah');
});

test('6. diterima dalam proses menampilkan aksi unggah pembayaran', function () {
    [$parent, $siswa] = pr6Accepted();

    $this->actingAs($parent)->get(route('parent.siswa.pendaftaran.status', $siswa))
        ->assertOk()
        ->assertSee('Unggah Bukti Pembayaran')
        ->assertDontSee('Cetak Kartu Bukti Siswa Resmi');
});

test('7. pembayaran ditolak menampilkan unggah ulang', function () {
    [$parent, $siswa, , $detail] = pr6Accepted();
    Pembayaran::create([
        'pendaftaran_detail_id' => $detail->id,
        'jumlah' => 250000,
        'bukti_bayar' => 'pembayaran/pr6-ditolak.png',
        'status' => Pembayaran::STATUS_DITOLAK,
    ]);

    $this->actingAs($parent)->get(route('parent.siswa.pendaftaran.status', $siswa))
        ->assertOk()
        ->assertSee('Bukti Pembayaran Perlu Diperbaiki')
        ->assertSee('Unggah Ulang Bukti Pembayaran');
});

test('8. siswa resmi melihat kartu receipt MPLS dan KBM', function () {
    [$parent, $siswa, , $detail] = pr6Accepted([
        'final_status' => PendaftaranDetail::FINAL_SISWA_RESMI_TERDAFTAR,
        'final_ditetapkan_at' => now(),
    ]);
    Pembayaran::create([
        'pendaftaran_detail_id' => $detail->id,
        'jumlah' => 250000,
        'bukti_bayar' => 'pembayaran/pr6-lunas.png',
        'status' => Pembayaran::STATUS_LUNAS,
        'verified_at' => now(),
    ]);

    $this->actingAs($parent)->get(route('parent.siswa.pendaftaran.status', $siswa))
        ->assertOk()
        ->assertSee('Siswa Resmi Terdaftar')
        ->assertSee('Cetak Kartu')
        ->assertSee('Cetak Kuitansi')
        ->assertSee('MPLS')
        ->assertSee('Mulai KBM');
});

test('9. Tidak Diterima tidak melihat aksi pembayaran', function () {
    [$parent, $siswa] = pr6Context([
        'status' => PendaftaranDetail::STATUS_KEPUTUSAN_SELESAI,
        'keputusan_status' => PendaftaranDetail::KEPUTUSAN_TIDAK_DITERIMA,
        'keputusan_diputuskan_at' => now(),
        'final_status' => PendaftaranDetail::FINAL_PENDAFTARAN_TIDAK_DILANJUTKAN,
    ]);

    $this->actingAs($parent)->get(route('parent.siswa.pendaftaran.status', $siswa))
        ->assertSee('Tidak Diterima')
        ->assertDontSee('Unggah Bukti Pembayaran');
});

test('10. Mengundurkan Diri tidak melihat aksi pembayaran', function () {
    [$parent, $siswa] = pr6Context([
        'status' => PendaftaranDetail::STATUS_KEPUTUSAN_SELESAI,
        'keputusan_status' => PendaftaranDetail::KEPUTUSAN_MENGUNDURKAN_DIRI,
        'keputusan_diputuskan_at' => now(),
        'final_status' => PendaftaranDetail::FINAL_MENGUNDURKAN_DIRI,
    ]);

    $this->actingAs($parent)->get(route('parent.siswa.pendaftaran.status', $siswa))
        ->assertSee('Mengundurkan Diri')
        ->assertDontSee('Unggah Bukti Pembayaran');
});

test('11. Pendaftaran Tidak Dilanjutkan tidak melihat aksi pembayaran', function () {
    [$parent, $siswa] = pr6Accepted(['final_status' => PendaftaranDetail::FINAL_PENDAFTARAN_TIDAK_DILANJUTKAN]);

    $this->actingAs($parent)->get(route('parent.siswa.pendaftaran.status', $siswa))
        ->assertSee('Pendaftaran Tidak Dilanjutkan')
        ->assertDontSee('Unggah Bukti Pembayaran');
});

test('12. catatan internal tidak muncul pada HTML parent', function () {
    [$parent, $siswa, , $detail] = pr6Accepted([
        'keputusan_catatan' => 'RAHASIA_KEPUTUSAN_PR6',
        'final_catatan' => 'RAHASIA_FINAL_PR6',
        'keputusan_alasan' => 'Informasi aman untuk keluarga',
    ]);

    $this->actingAs($parent)->get(route('parent.siswa.pendaftaran.status', $siswa))
        ->assertSee('Informasi aman untuk keluarga')
        ->assertDontSee('RAHASIA_KEPUTUSAN_PR6')
        ->assertDontSee('RAHASIA_FINAL_PR6')
        ->assertDontSee($detail->getRawOriginal('bukti_bayar') ?? 'PATH_TIDAK_ADA');
});

test('13. final terminal tidak menampilkan aksi mutasi admin', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    [, , , $detail] = pr6Context([
        'status' => PendaftaranDetail::STATUS_KEPUTUSAN_SELESAI,
        'keputusan_status' => PendaftaranDetail::KEPUTUSAN_TIDAK_DITERIMA,
        'keputusan_diputuskan_at' => now(),
        'final_status' => PendaftaranDetail::FINAL_PENDAFTARAN_TIDAK_DILANJUTKAN,
    ]);

    $this->actingAs($admin)->get(route('admin.verifikasi.show', $detail))
        ->assertOk()
        ->assertSee('Pendaftaran sudah berada pada status akhir.')
        ->assertDontSee('action="'.route('admin.verifikasi.administrasi-lengkap', $detail).'"', false)
        ->assertDontSee('action="'.route('admin.verifikasi.observasi.store', $detail).'"', false)
        ->assertDontSee('action="'.route('admin.verifikasi.keputusan.store', $detail).'"', false);
});

test('14. navigasi admin dan super admin mengikuti kewenangan role', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $superAdmin = User::factory()->create(['role' => 'super_admin']);

    $this->actingAs($admin)->get(route('admin.dashboard'))
        ->assertSee('Operasional SPMB')
        ->assertSee('Konten Website')
        ->assertSee('Sistem')
        ->assertDontSee('Kelola Admin');

    $this->actingAs($superAdmin)->get(route('admin.dashboard'))
        ->assertSee('Kelola Admin')
        ->assertSee('Activity Log');
});

test('15. presenter memetakan seluruh status dengan struktur konsisten', function () {
    $groups = [
        'process' => ['pending', 'menunggu_verifikasi', 'perlu_revisi', 'administrasi_lengkap', 'menunggu_keputusan', 'keputusan_selesai'],
        'decision' => [null, 'perlu_tindak_lanjut', 'diterima', 'tidak_diterima', 'mengundurkan_diri'],
        'payment' => [null, 'pending', 'menunggu_verifikasi', 'ditolak', 'lunas'],
        'final' => ['dalam_proses', 'siswa_resmi_terdaftar', 'pendaftaran_tidak_dilanjutkan', 'mengundurkan_diri'],
    ];

    foreach ($groups as $method => $statuses) {
        foreach ($statuses as $status) {
            $presentation = SpmbStatusPresenter::$method($status);
            expect($presentation)->toHaveKeys(['label', 'description', 'icon', 'attention', 'badge_class'])
                ->and($presentation['label'])->not->toBeEmpty();
        }
    }
});

test('16. timeline memiliki tujuh tahap dan indikator non-warna', function () {
    [, , , $detail] = pr6Context(['status' => PendaftaranDetail::STATUS_PERLU_REVISI]);
    $timeline = SpmbStatusPresenter::timeline($detail);

    expect($timeline)->toHaveCount(7)
        ->and($timeline[0]['label'])->toBe('Data Anak')
        ->and($timeline[2]['state'])->toBe('action')
        ->and($timeline[2]['state_label'])->toBe('Perlu tindakan')
        ->and($timeline[6]['label'])->toBe('Siswa Resmi Terdaftar');
});

test('17. komponen penting mempertahankan dark mode dan baseline responsif', function () {
    [$parent] = pr6Context();

    $this->actingAs($parent)->get(route('parent.dashboard'))
        ->assertOk()
        ->assertSee('dark:bg-[#2b2c40]', false)
        ->assertSee('sm:grid-cols-2', false)
        ->assertSee('data-attention=', false)
        ->assertSee('Tahapan proses SPMB', false);
});
