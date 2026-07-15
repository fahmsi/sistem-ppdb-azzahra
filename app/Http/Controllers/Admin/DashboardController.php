<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Pendaftaran;
use App\Models\PendaftaranDetail;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Admin dashboard with summary statistics.
     */
    public function index(): View
    {
        $stats = [
            'total_siswa' => Siswa::count(),
            'total_pendaftar' => PendaftaranDetail::count(),
            'pending' => PendaftaranDetail::where('status', PendaftaranDetail::STATUS_PENDING)->count(),
            'menunggu_verifikasi' => PendaftaranDetail::where('status', PendaftaranDetail::STATUS_MENUNGGU_VERIFIKASI)->count(),
            'diterima' => PendaftaranDetail::where('keputusan_status', PendaftaranDetail::KEPUTUSAN_DITERIMA)->count(),
            'ditolak' => PendaftaranDetail::where('keputusan_status', PendaftaranDetail::KEPUTUSAN_TIDAK_DITERIMA)->count(),
            'administrasi_lengkap' => PendaftaranDetail::where('status', PendaftaranDetail::STATUS_ADMINISTRASI_LENGKAP)->count(),
            'menunggu_keputusan' => PendaftaranDetail::where('status', PendaftaranDetail::STATUS_MENUNGGU_KEPUTUSAN)->count(),
            'diterima_dalam_proses' => PendaftaranDetail::where('keputusan_status', PendaftaranDetail::KEPUTUSAN_DITERIMA)->where('final_status', PendaftaranDetail::FINAL_DALAM_PROSES)->count(),
            'pembayaran_menunggu_verifikasi' => \App\Models\Pembayaran::whereIn('status', [\App\Models\Pembayaran::STATUS_PENDING, \App\Models\Pembayaran::STATUS_MENUNGGU_VERIFIKASI])->count(),
            'siswa_resmi_terdaftar' => PendaftaranDetail::where('final_status', PendaftaranDetail::FINAL_SISWA_RESMI_TERDAFTAR)->count(),
            'pendaftaran_tidak_dilanjutkan' => PendaftaranDetail::where('final_status', PendaftaranDetail::FINAL_PENDAFTARAN_TIDAK_DILANJUTKAN)->count(),
            'mengundurkan_diri' => PendaftaranDetail::where('final_status', PendaftaranDetail::FINAL_MENGUNDURKAN_DIRI)->count(),
            'perlu_revisi' => PendaftaranDetail::where('status', PendaftaranDetail::STATUS_PERLU_REVISI)->count(),
            'total_users' => User::where('role', 'parent')->count(),
            'gelombang_aktif' => Pendaftaran::open()->count(),
        ];

        $recentRegistrations = PendaftaranDetail::with(['siswa.user', 'pendaftaran'])
            ->latest()
            ->limit(10)
            ->get();

        // Recent Activity Logs (for Super Admin dashboard section)
        $recentLogs = [];
        if (auth()->user()->isSuperAdmin()) {
            $recentLogs = ActivityLog::with('user')
                ->latest()
                ->limit(8)
                ->get();
        }

        // Data for Charts
        $genderData = [
            'Laki-laki' => Siswa::where('jenis_kelamin', 'L')->count(),
            'Perempuan' => Siswa::where('jenis_kelamin', 'P')->count(),
        ];
        $chartGender = [
            'labels' => array_keys($genderData),
            'values' => array_values($genderData),
        ];

        $rawStatuses = PendaftaranDetail::selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $statusData = [
            'Pending' => $rawStatuses['pending'] ?? 0,
            'Menunggu' => $rawStatuses['menunggu_verifikasi'] ?? 0,
            'Revisi' => $rawStatuses['perlu_revisi'] ?? 0,
            'Diterima' => PendaftaranDetail::where('keputusan_status', PendaftaranDetail::KEPUTUSAN_DITERIMA)->count(),
            'Tidak Diterima' => PendaftaranDetail::where('keputusan_status', PendaftaranDetail::KEPUTUSAN_TIDAK_DITERIMA)->count(),
            'Siswa Resmi' => PendaftaranDetail::where('final_status', PendaftaranDetail::FINAL_SISWA_RESMI_TERDAFTAR)->count(),
        ];
        $chartStatus = [
            'labels' => array_keys($statusData),
            'values' => array_values($statusData),
        ];

        $gelombangData = Pendaftaran::withCount('pendaftaranDetails')
            ->get()
            ->pluck('pendaftaran_details_count', 'gelombang')
            ->toArray();
        $chartGelombang = [
            'labels' => array_keys($gelombangData),
            'values' => array_values($gelombangData),
        ];

        return view('admin.dashboard', compact(
            'stats',
            'recentRegistrations',
            'recentLogs',
            'chartGender',
            'chartStatus',
            'chartGelombang'
        ));
    }
}
