<?php

namespace App\Notifications;

use App\Models\PendaftaranDetail;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class KeputusanPendaftaranNotification extends Notification
{
    use Queueable;

    public function __construct(
        private readonly PendaftaranDetail $detail
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $namaAnak = $this->detail->siswa?->nama ?? 'Anak Anda';
        $noPendaftaran = $this->detail->nomor_pendaftaran;
        $status = $this->detail->keputusan_status;

        $title = '';
        $message = '';

        if ($status === PendaftaranDetail::KEPUTUSAN_DITERIMA) {
            $title = "Pendaftaran Diterima — {$namaAnak}";
            $message = "Alhamdulillah, Ananda {$namaAnak} dinyatakan diterima. Silakan melanjutkan proses daftar ulang dan pembayaran melalui sistem.";
        } elseif ($status === PendaftaranDetail::KEPUTUSAN_TIDAK_DITERIMA) {
            $title = "Pendaftaran Belum Diterima — {$namaAnak}";
            $message = "Terima kasih telah mengikuti proses SPMB. Berdasarkan hasil keseluruhan proses, Ananda {$namaAnak} belum dapat diterima pada tahun ajaran ini.";
        } elseif ($status === PendaftaranDetail::KEPUTUSAN_PERLU_TINDAK_LANJUT) {
            $title = "Pendaftaran Perlu Tindak Lanjut — {$namaAnak}";
            $message = "Pendaftaran Ananda {$namaAnak} memerlukan tindak lanjut dari pihak sekolah. Silakan melihat catatan keputusan atau menghubungi sekolah.";
        } elseif ($status === PendaftaranDetail::KEPUTUSAN_MENGUNDURKAN_DIRI) {
            $title = "Status Mengundurkan Diri — {$namaAnak}";
            $message = "Pendaftaran Ananda {$namaAnak} telah ditutup dengan status Mengundurkan Diri.";
        }

        return [
            'type' => 'keputusan_pendaftaran',
            'pendaftaran_detail_id' => $this->detail->id,
            'nama_anak' => $namaAnak,
            'no_pendaftaran' => $noPendaftaran,
            'title' => $title,
            'message' => $message,
            'url' => route('parent.siswa.pendaftaran.status', $this->detail->siswa_id),
        ];
    }
}
