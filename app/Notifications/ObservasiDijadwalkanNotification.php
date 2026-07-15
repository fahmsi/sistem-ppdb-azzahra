<?php

namespace App\Notifications;

use App\Models\Observasi;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ObservasiDijadwalkanNotification extends Notification
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
        $jadwal = $this->observasi->scheduled_at->locale('id')->isoFormat('dddd, D MMMM YYYY [pukul] HH:mm');
        $attempt = $this->observasi->attempt_number;
        $noPendaftaran = $detail?->nomor_pendaftaran ?? '-';
        $isReschedule = $attempt > 1;

        $title = $isReschedule
            ? "Perubahan Jadwal Observasi — {$namaAnak}"
            : "Jadwal Observasi Ditetapkan — {$namaAnak}";

        $message = $isReschedule
            ? "Terdapat perubahan jadwal observasi untuk {$namaAnak} ({$noPendaftaran}) menjadi Percobaan #{$attempt}. "
            : "Jadwal observasi untuk {$namaAnak} ({$noPendaftaran}) telah ditetapkan. ";

        $message .= "Harap hadir bersama anak pada {$jadwal} di PAUD Az-Zahra. "
                  .'Pastikan membawa dokumen yang diperlukan.';

        return [
            'type' => 'observasi_dijadwalkan',
            'observasi_id' => $this->observasi->id,
            'attempt_number' => $attempt,
            'scheduled_at' => $this->observasi->scheduled_at->toIso8601String(),
            'nama_anak' => $namaAnak,
            'no_pendaftaran' => $noPendaftaran,
            'title' => $title,
            'message' => $message,
            'url' => $detail ? route('parent.siswa.pendaftaran.status', $detail->siswa_id) : '/',
        ];
    }
}
