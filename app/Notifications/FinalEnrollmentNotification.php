<?php

namespace App\Notifications;

use App\Models\PendaftaranDetail;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class FinalEnrollmentNotification extends Notification
{
    use Queueable;

    public function __construct(private readonly PendaftaranDetail $detail, private readonly string $event) {}

    public function via(object $notifiable): array { return ['database']; }

    public function toArray(object $notifiable): array
    {
        $nama = $this->detail->siswa?->nama ?? 'Anak Anda';
        $tahun = $this->detail->pendaftaran?->tahun_ajaran ?? '-';
        $message = match ($this->event) {
            'payment_rejected' => "Bukti pembayaran daftar ulang Ananda {$nama} perlu diperbaiki. Silakan melihat catatan verifikasi dan mengunggah ulang bukti pembayaran.",
            'officially_enrolled' => "Alhamdulillah, pembayaran daftar ulang Ananda {$nama} telah diverifikasi. Ananda resmi terdaftar sebagai siswa PAUD Al-Qur'an Az-Zahra untuk tahun ajaran {$tahun}.",
            default => "Proses daftar ulang Ananda {$nama} telah ditutup dengan status Pendaftaran Tidak Dilanjutkan.",
        };

        return [
            'type' => 'final_enrollment', 'event' => $this->event,
            'pendaftaran_detail_id' => $this->detail->id,
            'nama_anak' => $nama, 'no_pendaftaran' => $this->detail->nomor_pendaftaran,
            'message' => $message,
            'url' => route('parent.siswa.pendaftaran.status', $this->detail->siswa_id),
        ];
    }
}
