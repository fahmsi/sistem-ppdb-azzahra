<?php

namespace App\Support;

use App\Models\Observasi;
use App\Models\Pembayaran;
use App\Models\PendaftaranDetail;

final class SpmbStatusPresenter
{
    /** @return array{label:string,description:string,icon:string,attention:string,badge_class:string} */
    public static function process(?string $status): array
    {
        return self::present(match ($status) {
            PendaftaranDetail::STATUS_PENDING => ['Pendaftaran Tercatat', 'Data pendaftaran telah tersimpan dan akan masuk ke tahap verifikasi administrasi.', 'clipboard-check', 'neutral'],
            PendaftaranDetail::STATUS_MENUNGGU_VERIFIKASI => ['Menunggu Verifikasi Administrasi', 'Admin sedang memeriksa data dan dokumen pendaftaran.', 'search', 'info'],
            PendaftaranDetail::STATUS_PERLU_REVISI => ['Perlu Perbaikan Data', 'Orang Tua/Wali perlu memperbaiki data atau dokumen yang diberi catatan.', 'circle-alert', 'warning'],
            PendaftaranDetail::STATUS_ADMINISTRASI_LENGKAP => ['Administrasi Lengkap', 'Data dan dokumen administrasi telah dinyatakan lengkap.', 'file-check-2', 'success'],
            PendaftaranDetail::STATUS_MENUNGGU_KEPUTUSAN => ['Menunggu Keputusan Sekolah', 'Tahap observasi telah selesai dan keputusan sekolah sedang diproses.', 'hourglass', 'info'],
            PendaftaranDetail::STATUS_KEPUTUSAN_SELESAI => ['Keputusan Sekolah Selesai', 'Keputusan sekolah untuk pendaftaran ini telah ditetapkan.', 'badge-check', 'neutral'],
            PendaftaranDetail::STATUS_DITERIMA => ['Administrasi Lengkap', 'Data legacy telah melewati verifikasi administrasi.', 'file-check-2', 'success'],
            PendaftaranDetail::STATUS_DITOLAK => ['Pendaftaran Tidak Dilanjutkan', 'Data legacy tidak dilanjutkan ke tahap berikutnya.', 'circle-stop', 'danger'],
            default => ['Status Belum Tersedia', 'Status proses belum tersedia.', 'circle-help', 'neutral'],
        });
    }

    /** @return array{label:string,description:string,icon:string,attention:string,badge_class:string} */
    public static function decision(?string $status): array
    {
        return self::present(match ($status) {
            PendaftaranDetail::KEPUTUSAN_DITERIMA => ['Diterima', 'Calon Siswa dapat melanjutkan ke tahap daftar ulang.', 'circle-check', 'success'],
            PendaftaranDetail::KEPUTUSAN_TIDAK_DITERIMA => ['Tidak Diterima', 'Proses pendaftaran berhenti berdasarkan keputusan sekolah.', 'circle-x', 'danger'],
            PendaftaranDetail::KEPUTUSAN_PERLU_TINDAK_LANJUT => ['Perlu Tindak Lanjut', 'Silakan ikuti arahan sekolah sebelum proses dapat dilanjutkan.', 'circle-alert', 'warning'],
            PendaftaranDetail::KEPUTUSAN_MENGUNDURKAN_DIRI => ['Mengundurkan Diri', 'Proses pendaftaran telah dihentikan atas pengunduran diri.', 'log-out', 'neutral'],
            default => ['Belum Ada Keputusan', 'Keputusan sekolah belum ditetapkan.', 'circle-dashed', 'neutral'],
        });
    }

    /** @return array{label:string,description:string,icon:string,attention:string,badge_class:string} */
    public static function payment(?string $status): array
    {
        return self::present(match ($status) {
            Pembayaran::STATUS_PENDING, Pembayaran::STATUS_MENUNGGU_VERIFIKASI => ['Pembayaran Menunggu Verifikasi', 'Bukti pembayaran telah diterima dan sedang diperiksa admin.', 'clock-3', 'info'],
            Pembayaran::STATUS_DITOLAK => ['Bukti Pembayaran Perlu Diperbaiki', 'Unggah ulang bukti pembayaran yang jelas dan sesuai arahan admin.', 'file-warning', 'warning'],
            Pembayaran::STATUS_LUNAS => ['Pembayaran Lunas', 'Pembayaran daftar ulang telah diverifikasi.', 'circle-check', 'success'],
            default => ['Belum Diunggah', 'Bukti pembayaran daftar ulang belum diunggah.', 'upload', 'neutral'],
        });
    }

    /** @return array{label:string,description:string,icon:string,attention:string,badge_class:string} */
    public static function final(?string $status): array
    {
        return self::present(match ($status) {
            PendaftaranDetail::FINAL_DALAM_PROSES => ['Dalam Proses', 'Proses SPMB masih berlangsung.', 'loader-circle', 'info'],
            PendaftaranDetail::FINAL_SISWA_RESMI_TERDAFTAR => ['Siswa Resmi Terdaftar', 'Seluruh tahap pendaftaran dan daftar ulang telah selesai.', 'badge-check', 'success'],
            PendaftaranDetail::FINAL_PENDAFTARAN_TIDAK_DILANJUTKAN => ['Pendaftaran Tidak Dilanjutkan', 'Proses daftar ulang telah dihentikan.', 'circle-stop', 'danger'],
            PendaftaranDetail::FINAL_MENGUNDURKAN_DIRI => ['Mengundurkan Diri', 'Proses pendaftaran telah dihentikan atas pengunduran diri.', 'log-out', 'neutral'],
            default => ['Dalam Proses', 'Proses SPMB masih berlangsung.', 'loader-circle', 'info'],
        });
    }

    /** @return array{label:string,description:string,icon:string,attention:string,badge_class:string} */
    public static function primary(PendaftaranDetail $detail): array
    {
        if ($detail->isSiswaResmiTerdaftar() || $detail->isPendaftaranTidakDilanjutkan() || $detail->isFinalMengundurkanDiri()) {
            return self::final($detail->final_status);
        }

        if ($detail->keputusan_status !== null) {
            if ($detail->isKeputusanDiterima()) {
                return self::payment($detail->pembayaran?->status);
            }

            return self::decision($detail->keputusan_status);
        }

        return self::process($detail->status);
    }

    /** @return list<array{label:string,state:string,state_label:string,icon:string}> */
    public static function timeline(PendaftaranDetail $detail): array
    {
        $states = array_fill(0, 7, 'upcoming');
        $states[0] = 'completed';
        $states[1] = 'completed';

        if ($detail->isFinalMengundurkanDiri() || $detail->isMengundurkanDiri()) {
            for ($i = 0; $i < 4; $i++) {
                $states[$i] = 'completed';
            }
            self::stopTimeline($states, 4);
        } elseif ($detail->isPendaftaranTidakDilanjutkan()) {
            for ($i = 0; $i < 5; $i++) {
                $states[$i] = 'completed';
            }
            self::stopTimeline($states, 5);
        } elseif ($detail->isSiswaResmiTerdaftar()) {
            $states = array_fill(0, 7, 'completed');
        } else {
            self::applyProcessStates($detail, $states);
        }

        $labels = ['Data Anak', 'Pendaftaran Gelombang', 'Verifikasi Administrasi', 'Observasi', 'Keputusan Sekolah', 'Daftar Ulang', 'Siswa Resmi Terdaftar'];
        $icons = ['user-round-check', 'clipboard-check', 'files', 'calendar-search', 'scale', 'credit-card', 'badge-check'];

        return array_map(fn (string $label, int $index): array => [
            'label' => $label,
            'state' => $states[$index],
            'state_label' => self::timelineStateLabel($states[$index]),
            'icon' => $icons[$index],
        ], $labels, array_keys($labels));
    }

    /** @param array<int, string> $states */
    private static function applyProcessStates(PendaftaranDetail $detail, array &$states): void
    {
        $states[2] = match ($detail->status) {
            PendaftaranDetail::STATUS_PENDING => 'upcoming',
            PendaftaranDetail::STATUS_MENUNGGU_VERIFIKASI => 'active',
            PendaftaranDetail::STATUS_PERLU_REVISI => 'action',
            default => 'completed',
        };

        if ($states[2] !== 'completed') {
            return;
        }

        $observation = $detail->observasiTerbaru;
        $states[3] = match ($observation?->status) {
            null, Observasi::STATUS_DIJADWALKAN, Observasi::STATUS_DIJADWALKAN_ULANG, Observasi::STATUS_HADIR => 'active',
            Observasi::STATUS_TIDAK_HADIR => 'action',
            Observasi::STATUS_SELESAI => 'completed',
            Observasi::STATUS_DIBATALKAN => 'action',
            default => 'active',
        };

        if ($detail->isPerluTindakLanjut()) {
            $states[3] = 'completed';
            $states[4] = 'action';
            return;
        }

        if ($detail->keputusan_status === PendaftaranDetail::KEPUTUSAN_TIDAK_DITERIMA) {
            $states[3] = 'completed';
            self::stopTimeline($states, 4);
            return;
        }

        if ($detail->isKeputusanDiterima()) {
            $states[3] = 'completed';
            $states[4] = 'completed';
            $payment = $detail->pembayaran;
            $states[5] = $payment?->isDitolak() ? 'action' : ($payment?->isLunas() ? 'completed' : 'active');
            return;
        }

        if ($states[3] === 'completed' || $detail->isMenungguKeputusan()) {
            $states[4] = 'active';
        }
    }

    /** @param array<int, string> $states */
    private static function stopTimeline(array &$states, int $index): void
    {
        $states[$index] = 'stopped';
        for ($i = $index + 1; $i < count($states); $i++) {
            $states[$i] = 'stopped';
        }
    }

    private static function timelineStateLabel(string $state): string
    {
        return match ($state) {
            'completed' => 'Selesai',
            'active' => 'Sedang berlangsung',
            'action' => 'Perlu tindakan',
            'stopped' => 'Proses berhenti',
            default => 'Belum dimulai',
        };
    }

    /** @param array{0:string,1:string,2:string,3:string} $data
     *  @return array{label:string,description:string,icon:string,attention:string,badge_class:string}
     */
    private static function present(array $data): array
    {
        [$label, $description, $icon, $attention] = $data;

        $classes = [
            'neutral' => 'border-slate-200 bg-slate-100 text-slate-700 dark:border-slate-500/30 dark:bg-slate-500/15 dark:text-slate-200',
            'info' => 'border-indigo-200 bg-indigo-50 text-indigo-700 dark:border-indigo-500/30 dark:bg-indigo-500/15 dark:text-indigo-200',
            'warning' => 'border-amber-200 bg-amber-50 text-amber-800 dark:border-amber-500/30 dark:bg-amber-500/15 dark:text-amber-200',
            'success' => 'border-emerald-200 bg-emerald-50 text-emerald-700 dark:border-emerald-500/30 dark:bg-emerald-500/15 dark:text-emerald-200',
            'danger' => 'border-rose-200 bg-rose-50 text-rose-700 dark:border-rose-500/30 dark:bg-rose-500/15 dark:text-rose-200',
        ];

        return compact('label', 'description', 'icon', 'attention') + ['badge_class' => $classes[$attention]];
    }
}
