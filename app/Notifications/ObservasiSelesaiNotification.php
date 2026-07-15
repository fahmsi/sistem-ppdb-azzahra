<?php

namespace App\Notifications;

use App\Models\Observasi;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ObservasiSelesaiNotification extends Notification
{
    use Queueable;

    public function __construct(
        private readonly Observasi $observasi
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $detail = $this->observasi->pendaftaranDetail;
        $namaAnak = $detail?->siswa?->nama ?? 'Anak Anda';
        $noPendaftaran = $detail?->nomor_pendaftaran ?? '-';

        return [
            'type' => 'observasi_selesai',
            'observasi_id' => $this->observasi->id,
            'nama_anak' => $namaAnak,
            'no_pendaftaran' => $noPendaftaran,
            'title' => "Observasi Selesai — {$namaAnak}",
            'message' => "Observasi {$namaAnak} ({$noPendaftaran}) telah selesai dilaksanakan. "
                              .'Hasil observasi sedang dalam proses penilaian oleh pihak sekolah. '
                              .'Kami akan menginformasikan keputusan penerimaan dalam waktu yang akan ditentukan.',
            'url' => $detail ? route('parent.siswa.pendaftaran.status', $detail->siswa_id) : '/',
        ];
    }
}
