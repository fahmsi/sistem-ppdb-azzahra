<?php

namespace App\Support;

use App\Models\PendaftaranDetail;
use App\Models\Siswa;

class ParentRegistrationProgress
{
    public function __construct(
        private readonly ?Siswa $siswa,
        private readonly bool $paymentConfigured,
    ) {}

    /**
     * Build the parent-facing registration progress without changing workflow state.
     *
     * @return array{
     *     siswa: ?Siswa,
     *     registration: ?PendaftaranDetail,
     *     steps: array<int, array<string, mixed>>,
     *     current_step: array<string, mixed>,
     *     status_card: array<string, mixed>,
     *     quick_actions: array<int, array<string, mixed>>,
     *     summary: string,
     *     attention_priority: int
     * }
     */
    public function toArray(): array
    {
        $steps = $this->baseSteps();

        $this->complete($steps, 1, 'Akun aktif');

        if (! $this->siswa) {
            $this->activate($steps, 2, 'Isi sekarang');

            return $this->result(
                steps: $steps,
                registration: null,
                summary: 'Mulai dengan melengkapi biodata dan dokumen anak.',
                priority: 100,
                actions: [
                    $this->action(
                        'Isi Data Anak',
                        'Lengkapi biodata dan dokumen anak',
                        route('parent.siswa.create'),
                        'user-plus'
                    ),
                ],
            );
        }

        $this->complete($steps, 2, 'Data tersimpan');

        /** @var PendaftaranDetail|null $registration */
        $registration = $this->siswa->pendaftaranDetails->first();

        if (! $registration) {
            $this->activate($steps, 3, 'Pilih sekarang');

            return $this->result(
                steps: $steps,
                registration: null,
                summary: 'Data anak sudah tersimpan. Pilih gelombang yang masih tersedia.',
                priority: 80,
                actions: [
                    $this->action(
                        'Daftar Gelombang',
                        'Pilih periode pendaftaran untuk '.$this->siswa->nama,
                        route('parent.siswa.pendaftaran.index', $this->siswa),
                        'calendar-check'
                    ),
                ],
            );
        }

        $this->complete($steps, 3, 'Sudah terdaftar');

        return match ($registration->status) {
            PendaftaranDetail::STATUS_PENDING,
            PendaftaranDetail::STATUS_MENUNGGU_VERIFIKASI => $this->administrationWaiting($steps, $registration),
            PendaftaranDetail::STATUS_PERLU_REVISI => $this->administrationRevision($steps, $registration),
            PendaftaranDetail::STATUS_DITOLAK => $this->administrationRejected($steps, $registration),
            PendaftaranDetail::STATUS_DITERIMA => $this->accepted($steps, $registration),
            default => $this->unknownStatus($steps, $registration),
        };
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function baseSteps(): array
    {
        return [
            $this->step(1, 'Buat Akun', 'Akun wali murid siap digunakan.', 'user-check'),
            $this->step(2, 'Isi Data Anak', 'Lengkapi biodata dan dokumen anak.', 'contact'),
            $this->step(3, 'Pilih Gelombang', 'Daftar ke gelombang yang tersedia.', 'calendar-days'),
            $this->step(4, 'Verifikasi Administrasi', 'Berkas diperiksa oleh panitia sekolah.', 'clipboard-check'),
            $this->step(5, 'Daftar Ulang', 'Unggah dan verifikasi bukti pembayaran.', 'credit-card'),
            $this->step(6, 'Resmi Terdaftar', 'Seluruh proses pendaftaran telah selesai.', 'badge-check'),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function step(int $number, string $title, string $description, string $icon): array
    {
        return [
            'number' => $number,
            'title' => $title,
            'description' => $description,
            'icon' => $icon,
            'state' => 'locked',
            'status_label' => 'Terkunci',
            'current' => false,
        ];
    }

    /**
     * @param  array<int, array<string, mixed>>  $steps
     */
    private function complete(array &$steps, int $number, string $label): void
    {
        $steps[$number - 1]['state'] = 'done';
        $steps[$number - 1]['status_label'] = $label;
    }

    /**
     * @param  array<int, array<string, mixed>>  $steps
     */
    private function activate(array &$steps, int $number, string $label, string $state = 'active'): void
    {
        $steps[$number - 1]['state'] = $state;
        $steps[$number - 1]['status_label'] = $label;
        $steps[$number - 1]['current'] = true;
    }

    /**
     * @param  array<int, array<string, mixed>>  $steps
     * @return array<string, mixed>
     */
    private function administrationWaiting(array $steps, PendaftaranDetail $registration): array
    {
        $this->activate($steps, 4, 'Menunggu verifikasi', 'waiting');

        return $this->result(
            steps: $steps,
            registration: $registration,
            summary: 'Pendaftaran sudah dikirim dan sedang diperiksa oleh panitia sekolah.',
            priority: 50,
            actions: [
                $this->statusAction('Lihat Status Pendaftaran', 'Pantau hasil pemeriksaan berkas'),
            ],
        );
    }

    /**
     * @param  array<int, array<string, mixed>>  $steps
     * @return array<string, mixed>
     */
    private function administrationRevision(array $steps, PendaftaranDetail $registration): array
    {
        $this->activate($steps, 4, 'Perlu revisi', 'failed');

        return $this->result(
            steps: $steps,
            registration: $registration,
            summary: 'Admin meminta perbaikan data atau dokumen sebelum verifikasi dilanjutkan.',
            priority: 95,
            actions: [
                $this->action(
                    'Perbaiki Data Anak',
                    'Perbarui data sesuai catatan admin',
                    route('parent.siswa.edit', $this->siswa),
                    'file-pen-line'
                ),
                $this->statusAction('Lihat Catatan Admin', 'Baca detail data yang perlu diperbaiki', false),
            ],
        );
    }

    /**
     * @param  array<int, array<string, mixed>>  $steps
     * @return array<string, mixed>
     */
    private function administrationRejected(array $steps, PendaftaranDetail $registration): array
    {
        $this->activate($steps, 4, 'Tidak dilanjutkan', 'failed');

        return $this->result(
            steps: $steps,
            registration: $registration,
            summary: 'Pendaftaran pada gelombang ini tidak dilanjutkan. Periksa catatan admin sebelum mendaftar kembali.',
            priority: 70,
            actions: [
                $this->action(
                    'Daftar Gelombang',
                    'Lihat gelombang lain yang masih tersedia',
                    route('parent.siswa.pendaftaran.index', $this->siswa),
                    'calendar-plus'
                ),
                $this->statusAction('Lihat Catatan Admin', 'Baca hasil keputusan pendaftaran', false),
            ],
        );
    }

    /**
     * @param  array<int, array<string, mixed>>  $steps
     * @return array<string, mixed>
     */
    private function accepted(array $steps, PendaftaranDetail $registration): array
    {
        $this->complete($steps, 4, 'Berkas diterima');

        $payment = $registration->pembayaran;

        if (! $payment) {
            if (! $this->paymentConfigured) {
                $this->activate($steps, 5, 'Menunggu arahan', 'waiting');

                return $this->result(
                    steps: $steps,
                    registration: $registration,
                    summary: 'Berkas sudah diterima. Informasi pembayaran belum tersedia. Silakan hubungi admin sekolah.',
                    priority: 65,
                    actions: [
                        $this->statusAction('Lihat Status Pendaftaran', 'Pantau informasi daftar ulang'),
                    ],
                );
            }

            $this->activate($steps, 5, 'Unggah bukti');

            return $this->result(
                steps: $steps,
                registration: $registration,
                summary: 'Berkas sudah diterima. Lanjutkan daftar ulang dengan mengunggah bukti pembayaran.',
                priority: 85,
                actions: [
                    $this->statusAction('Upload Bukti Pembayaran', 'Selesaikan proses daftar ulang', true, 'upload'),
                ],
            );
        }

        if ($payment->isDitolak()) {
            $this->activate($steps, 5, 'Bukti ditolak', 'failed');

            return $this->result(
                steps: $steps,
                registration: $registration,
                summary: 'Bukti pembayaran perlu diperbaiki sesuai catatan admin.',
                priority: 100,
                actions: [
                    $this->statusAction('Unggah Ulang Bukti Pembayaran', 'Kirim bukti pembayaran yang sesuai', true, 'refresh-cw'),
                ],
            );
        }

        if ($payment->isLunas()) {
            $this->complete($steps, 5, 'Pembayaran lunas');
            $this->complete($steps, 6, 'Selesai');

            return $this->result(
                steps: $steps,
                registration: $registration,
                summary: 'Seluruh proses selesai. Anak Anda resmi terdaftar.',
                priority: 0,
                actions: [
                    $this->action(
                        'Lihat Bukti Pembayaran',
                        'Buka receipt pembayaran terverifikasi',
                        route('parent.pembayaran.receipt', $registration),
                        'receipt-text'
                    ),
                    $this->action(
                        'Lihat Kartu Pendaftaran',
                        'Buka kartu pendaftaran anak',
                        route('parent.siswa.pendaftaran.kartu', [
                            'siswa' => $this->siswa,
                            'detail' => $registration,
                        ]),
                        'printer',
                        false
                    ),
                ],
            );
        }

        $this->activate($steps, 5, 'Menunggu verifikasi', 'waiting');

        return $this->result(
            steps: $steps,
            registration: $registration,
            summary: 'Bukti pembayaran sudah dikirim dan sedang diverifikasi admin.',
            priority: 40,
            actions: [
                $this->statusAction('Lihat Status Pembayaran', 'Pantau hasil verifikasi daftar ulang'),
            ],
        );
    }

    /**
     * @param  array<int, array<string, mixed>>  $steps
     * @return array<string, mixed>
     */
    private function unknownStatus(array $steps, PendaftaranDetail $registration): array
    {
        $this->activate($steps, 4, 'Periksa status', 'waiting');

        return $this->result(
            steps: $steps,
            registration: $registration,
            summary: 'Ada pembaruan status pendaftaran. Buka detail untuk melihat informasi terbaru.',
            priority: 60,
            actions: [
                $this->statusAction('Lihat Status Pendaftaran', 'Buka informasi pendaftaran terbaru'),
            ],
        );
    }

    /**
     * @param  array<int, array<string, mixed>>  $steps
     * @param  array<int, array<string, mixed>>  $actions
     * @return array<string, mixed>
     */
    private function result(
        array $steps,
        ?PendaftaranDetail $registration,
        string $summary,
        int $priority,
        array $actions,
    ): array {
        $currentStep = collect($steps)->firstWhere('current', true) ?? $steps[array_key_last($steps)];

        return [
            'siswa' => $this->siswa,
            'registration' => $registration,
            'steps' => $steps,
            'current_step' => $currentStep,
            'status_card' => $this->statusCard($registration, $currentStep, $summary),
            'quick_actions' => $actions,
            'summary' => $summary,
            'attention_priority' => $priority,
        ];
    }

    /**
     * @param  array<string, mixed>  $currentStep
     * @return array<string, mixed>
     */
    private function statusCard(
        ?PendaftaranDetail $registration,
        array $currentStep,
        string $summary,
    ): array {
        $payment = $registration?->pembayaran;
        $updatedAt = $registration?->updated_at;

        if ($payment?->updated_at && (! $updatedAt || $payment->updated_at->greaterThan($updatedAt))) {
            $updatedAt = $payment->updated_at;
        }

        $administration = match ($registration?->status) {
            PendaftaranDetail::STATUS_PENDING => ['Pendaftaran terkirim', 'waiting'],
            PendaftaranDetail::STATUS_MENUNGGU_VERIFIKASI => ['Sedang diverifikasi', 'waiting'],
            PendaftaranDetail::STATUS_PERLU_REVISI => ['Perlu revisi', 'failed'],
            PendaftaranDetail::STATUS_DITOLAK => ['Tidak dilanjutkan', 'failed'],
            PendaftaranDetail::STATUS_DITERIMA => ['Berkas diterima', 'done'],
            default => [$this->siswa ? 'Belum mendaftar' : 'Belum dimulai', $this->siswa ? 'active' : 'locked'],
        };

        $paymentStatus = match (true) {
            ! $registration || $registration->status !== PendaftaranDetail::STATUS_DITERIMA => ['Belum dimulai', 'locked'],
            ! $payment && ! $this->paymentConfigured => ['Menunggu informasi', 'waiting'],
            ! $payment => ['Belum diunggah', 'active'],
            $payment->isDitolak() => ['Perlu unggah ulang', 'failed'],
            $payment->isLunas() => ['Lunas / terverifikasi', 'done'],
            default => ['Menunggu verifikasi', 'waiting'],
        };

        $adminNote = $payment?->isDitolak()
            ? $payment->catatan_admin
            : $registration?->notifikasi;

        return [
            'headline' => $this->siswa
                ? $currentStep['title']
                : 'Belum ada data anak',
            'label' => $currentStep['status_label'],
            'tone' => $currentStep['state'],
            'description' => $summary,
            'registration_number' => $registration?->nomor_pendaftaran,
            'wave' => $registration?->pendaftaran?->gelombang,
            'academic_year' => $registration?->pendaftaran?->tahun_ajaran,
            'administration_label' => $administration[0],
            'administration_tone' => $administration[1],
            'payment_label' => $paymentStatus[0],
            'payment_tone' => $paymentStatus[1],
            'admin_note' => $adminNote,
            'updated_at' => $updatedAt?->translatedFormat('d M Y, H:i').' WIB',
            'status_url' => $registration
                ? route('parent.siswa.pendaftaran.status', $this->siswa)
                : null,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function statusAction(
        string $title,
        string $description,
        bool $primary = true,
        string $icon = 'activity',
    ): array {
        return $this->action(
            $title,
            $description,
            route('parent.siswa.pendaftaran.status', $this->siswa),
            $icon,
            $primary,
        );
    }

    /**
     * @return array<string, mixed>
     */
    private function action(
        string $title,
        string $description,
        string $url,
        string $icon,
        bool $primary = true,
    ): array {
        return compact('title', 'description', 'url', 'icon', 'primary');
    }
}
