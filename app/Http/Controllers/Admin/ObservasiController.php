<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RescheduleObservasiRequest;
use App\Http\Requests\Admin\SelesaiObservasiRequest;
use App\Http\Requests\Admin\StoreObservasiRequest;
use App\Models\ActivityLog;
use App\Models\Observasi;
use App\Models\PendaftaranDetail;
use App\Notifications\ObservasiDijadwalkanNotification;
use App\Notifications\ObservasiSelesaiNotification;
use App\Notifications\ObservasiTidakHadirNotification;
use App\Services\ObservationSchedulingService;
use App\Services\WhatsAppNotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use RuntimeException;

class ObservasiController extends Controller
{
    public function __construct(
        private ObservationSchedulingService $scheduler,
        private WhatsAppNotificationService $whatsApp,
    ) {}

    /**
     * Store a new observation schedule for a registration detail.
     */
    public function store(StoreObservasiRequest $request, PendaftaranDetail $detail): RedirectResponse
    {
        try {
            $observasi = $this->scheduler->schedule($detail, $request->validated(), auth()->id());
        } catch (RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        ActivityLog::log(
            'observation_scheduled',
            $detail,
            "Observasi percobaan #{$observasi->attempt_number} dijadwalkan untuk pendaftaran {$detail->nomor_pendaftaran} pada {$observasi->scheduled_at->format('d/m/Y H:i')}."
        );

        // Notify parent
        $detail->loadMissing('siswa.user');
        $user = $detail->siswa?->user;
        if ($user) {
            $user->notify(new ObservasiDijadwalkanNotification($observasi));
        }

        // WhatsApp (best-effort)
        $phone = $detail->siswa?->no_telpon ?? $detail->siswa?->user?->no_telpon ?? null;
        $namaAnak = $detail->siswa?->nama ?? 'Anak Anda';
        $jadwal = $observasi->scheduled_at->format('d/m/Y H:i');
        $attempt = $observasi->attempt_number;
        $isReschedule = $attempt > 1;
        $waMessage = $isReschedule
            ? "Assalamu'alaikum. Terdapat perubahan jadwal observasi untuk {$namaAnak} (No. {$detail->nomor_pendaftaran}) menjadi Percobaan #{$attempt}: {$jadwal}. Hadir bersama anak di PAUD Az-Zahra."
            : "Assalamu'alaikum. Jadwal observasi untuk {$namaAnak} (No. {$detail->nomor_pendaftaran}) telah ditetapkan: {$jadwal}. Harap hadir bersama anak di PAUD Az-Zahra.";
        $this->whatsApp->send($phone, $waMessage);

        return back()->with('success', "Jadwal observasi percobaan #{$observasi->attempt_number} berhasil dibuat.");
    }

    public function hadir(Observasi $observasi): RedirectResponse
    {
        $latestAttempt = Observasi::where('pendaftaran_detail_id', $observasi->pendaftaran_detail_id)
            ->max('attempt_number');
        if ($observasi->attempt_number !== $latestAttempt) {
            return back()->with('error', 'Hanya observasi terbaru yang dapat diubah.');
        }

        if (! $observasi->canHadir()) {
            return back()->with('error', 'Hanya observasi berstatus "dijadwalkan" yang dapat ditandai hadir.');
        }

        $observasi->update([
            'status' => Observasi::STATUS_HADIR,
            'attended_at' => now(),
        ]);

        $detail = $observasi->pendaftaranDetail;

        ActivityLog::log(
            'observation_attended',
            $detail,
            "Pendaftar {$detail->nomor_pendaftaran} hadir pada observasi percobaan #{$observasi->attempt_number}."
        );

        return back()->with('success', 'Kehadiran berhasil dicatat.');
    }

    public function tidakHadir(Request $request, Observasi $observasi): RedirectResponse
    {
        $latestAttempt = Observasi::where('pendaftaran_detail_id', $observasi->pendaftaran_detail_id)
            ->max('attempt_number');
        if ($observasi->attempt_number !== $latestAttempt) {
            return back()->with('error', 'Hanya observasi terbaru yang dapat diubah.');
        }

        if (! $observasi->canTidakHadir()) {
            return back()->with('error', 'Hanya observasi berstatus "dijadwalkan" yang dapat ditandai tidak hadir.');
        }

        $observasi->update([
            'status' => Observasi::STATUS_TIDAK_HADIR,
        ]);

        $detail = $observasi->pendaftaranDetail;

        ActivityLog::log(
            'observation_no_show',
            $detail,
            "Pendaftar {$detail->nomor_pendaftaran} tidak hadir pada observasi percobaan #{$observasi->attempt_number}."
        );

        // Notify parent
        $detail->loadMissing('siswa.user', 'pendaftaran');
        $user = $detail->siswa?->user;
        if ($user) {
            $user->notify(new ObservasiTidakHadirNotification($observasi));
        }

        // WhatsApp (best-effort)
        $phone = $detail->siswa?->no_telpon ?? $detail->siswa?->user?->no_telpon ?? null;
        $namaAnak = $detail->siswa?->nama ?? 'Anak Anda';
        $deadlineText = '';
        if ($detail->pendaftaran?->tanggal_mpls) {
            $deadline = $this->scheduler->getDeadline($detail->pendaftaran);
            $deadlineText = ' Penjadwalan ulang dapat dilakukan hingga '.$deadline->format('d/m/Y').'.';
        }
        $waMessage = "Assalamu'alaikum. {$namaAnak} (No. {$detail->nomor_pendaftaran}) tercatat tidak hadir pada jadwal observasi percobaan #{$observasi->attempt_number}.{$deadlineText} Hubungi sekolah jika ingin menjadwalkan ulang.";
        $this->whatsApp->send($phone, $waMessage);

        return back()->with('success', 'Ketidakhadiran berhasil dicatat. Notifikasi dikirim ke orang tua.');
    }

    public function jadwalUlang(RescheduleObservasiRequest $request, Observasi $observasi): RedirectResponse
    {
        $latestAttempt = Observasi::where('pendaftaran_detail_id', $observasi->pendaftaran_detail_id)
            ->max('attempt_number');
        if ($observasi->attempt_number !== $latestAttempt) {
            return back()->with('error', 'Hanya observasi terbaru yang dapat dijadwalkan ulang.');
        }

        try {
            $newObservasi = $this->scheduler->reschedule($observasi, $request->validated(), auth()->id());
        } catch (RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        $detail = $newObservasi->pendaftaranDetail;

        ActivityLog::log(
            'observation_rescheduled',
            $detail,
            "Observasi pendaftaran {$detail->nomor_pendaftaran} dijadwalkan ulang ke percobaan #{$newObservasi->attempt_number} pada {$newObservasi->scheduled_at->format('d/m/Y H:i')}."
        );

        // Notify parent
        $detail->loadMissing('siswa.user');
        $user = $detail->siswa?->user;
        if ($user) {
            $user->notify(new ObservasiDijadwalkanNotification($newObservasi));
        }

        // WhatsApp (best-effort)
        $phone = $detail->siswa?->no_telpon ?? $detail->siswa?->user?->no_telpon ?? null;
        $namaAnak = $detail->siswa?->nama ?? 'Anak Anda';
        $jadwal = $newObservasi->scheduled_at->format('d/m/Y H:i');
        $waMessage = "Assalamu'alaikum. Terdapat perubahan jadwal observasi untuk {$namaAnak} (No. {$detail->nomor_pendaftaran}) menjadi percobaan #{$newObservasi->attempt_number}: {$jadwal}. Hadir bersama anak di PAUD Az-Zahra.";
        $this->whatsApp->send($phone, $waMessage);

        return back()->with('success', "Penjadwalan ulang berhasil. Percobaan #{$newObservasi->attempt_number} dibuat.");
    }

    public function selesai(SelesaiObservasiRequest $request, Observasi $observasi): RedirectResponse
    {
        $latestAttempt = Observasi::where('pendaftaran_detail_id', $observasi->pendaftaran_detail_id)
            ->max('attempt_number');
        if ($observasi->attempt_number !== $latestAttempt) {
            return back()->with('error', 'Hanya observasi terbaru yang dapat diubah.');
        }

        if (! $observasi->canBeCompleted()) {
            return back()->with('error', 'Hanya observasi berstatus "hadir" atau "dijadwalkan" yang dapat diselesaikan.');
        }

        $validated = $request->validated();

        $observasi->update([
            'status' => Observasi::STATUS_SELESAI,
            'attended_at' => $observasi->attended_at ?? now(),
            'completed_at' => now(),
            'observed_by' => auth()->id(),
            'tinggi_badan_cm' => $validated['tinggi_badan_cm'],
            'berat_badan_kg' => $validated['berat_badan_kg'],
            'catatan_wawancara_orang_tua' => $validated['catatan_wawancara_orang_tua'],
            'catatan_aktivitas_anak' => $validated['catatan_aktivitas_anak'],
            'catatan_kesiapan_anak' => $validated['catatan_kesiapan_anak'],
            'membutuhkan_dukungan_khusus' => $validated['membutuhkan_dukungan_khusus'],
            'catatan_kebutuhan_dukungan_khusus' => $validated['catatan_kebutuhan_dukungan_khusus'] ?? null,
            'catatan_sekolah' => $validated['catatan_sekolah'] ?? null,
        ]);

        // Update PendaftaranDetail status to awaiting school decision
        $detail = $observasi->pendaftaranDetail;
        $detail->update([
            'status' => PendaftaranDetail::STATUS_MENUNGGU_KEPUTUSAN,
            'notifikasi' => 'Observasi telah selesai. Sedang menunggu keputusan pihak sekolah.',
        ]);

        ActivityLog::log(
            'observation_completed',
            $detail,
            "Observasi percobaan #{$observasi->attempt_number} untuk pendaftaran {$detail->nomor_pendaftaran} selesai dicatat."
        );

        // Notify parent (do NOT include internal notes)
        $detail->loadMissing('siswa.user');
        $user = $detail->siswa?->user;
        if ($user) {
            $user->notify(new ObservasiSelesaiNotification($observasi));
        }

        // WhatsApp (best-effort)
        $phone = $detail->siswa?->no_telpon ?? $detail->siswa?->user?->no_telpon ?? null;
        $namaAnak = $detail->siswa?->nama ?? 'Anak Anda';
        $waMessage = "Assalamu'alaikum. Observasi {$namaAnak} (No. {$detail->nomor_pendaftaran}) telah selesai dilaksanakan. Hasil sedang dalam proses penilaian oleh pihak sekolah. Kami akan menginformasikan keputusan penerimaan setelah tersedia.";
        $this->whatsApp->send($phone, $waMessage);

        return back()->with('success', 'Hasil observasi berhasil dicatat. Status diperbarui ke "Menunggu Keputusan".');
    }
}
