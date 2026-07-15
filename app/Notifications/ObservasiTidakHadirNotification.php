<?php

namespace App\Notifications;

use App\Models\Observasi;
use App\Services\ObservationSchedulingService;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ObservasiTidakHadirNotification extends Notification
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
        $pendaftaran = $detail?->pendaftaran;

        $rescheduleDeadlineText = '';
        if ($pendaftaran?->tanggal_mpls) {
            $service = app(ObservationSchedulingService::class);
            $deadline = $service->getDeadline($pendaftaran);
            $rescheduleDeadlineText = ' Penjadwalan ulang dapat dilakukan sampai dengan '.
                $deadline->locale('id')->isoFormat('dddd, D MMMM YYYY').'.';
        }

        return [
            'type' => 'observasi_tidak_hadir',
            'observasi_id' => $this->observasi->id,
            'attempt_number' => $this->observasi->attempt_number,
            'nama_anak' => $namaAnak,
            'no_pendaftaran' => $noPendaftaran,
            'title' => "Tidak Hadir Observasi — {$namaAnak}",
            'message' => "{$namaAnak} ({$noPendaftaran}) tercatat tidak hadir pada jadwal observasi percobaan #{$this->observasi->attempt_number}. "
                              ."Jika Anda perlu menjadwalkan ulang, silakan hubungi pihak sekolah.{$rescheduleDeadlineText}",
            'url' => $detail ? route('parent.siswa.pendaftaran.status', $detail->siswa_id) : '/',
        ];
    }
}
