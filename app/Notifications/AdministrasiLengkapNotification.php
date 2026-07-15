<?php

namespace App\Notifications;

use App\Models\PendaftaranDetail;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AdministrasiLengkapNotification extends Notification
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

        return [
            'type' => 'administrasi_lengkap',
            'pendaftaran_id' => $this->detail->id,
            'no_pendaftaran' => $noPendaftaran,
            'nama_anak' => $namaAnak,
            'title' => 'Administrasi Lengkap — '.$namaAnak,
            'message' => "Berkas administrasi {$namaAnak} ({$noPendaftaran}) telah dinyatakan lengkap oleh admin. ".
                                   'Tahap selanjutnya adalah penjadwalan observasi di sekolah. '.
                                   'Kami akan memberitahu Anda segera setelah jadwal observasi ditetapkan.',
            'url' => route('parent.siswa.pendaftaran.status', $this->detail->siswa_id),
        ];
    }
}
