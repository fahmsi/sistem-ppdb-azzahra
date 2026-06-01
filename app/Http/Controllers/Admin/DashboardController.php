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
            'diterima' => PendaftaranDetail::where('status', PendaftaranDetail::STATUS_DITERIMA)->count(),
            'ditolak' => PendaftaranDetail::where('status', PendaftaranDetail::STATUS_DITOLAK)->count(),
            'perlu_revisi' => PendaftaranDetail::where('status', PendaftaranDetail::STATUS_PERLU_REVISI)->count(),
            'total_users' => User::where('role', 'parent')->count(),
            'gelombang_aktif' => Pendaftaran::open()->count(),
        ];

        $recentRegistrations = PendaftaranDetail::with(['siswa', 'pendaftaran'])
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
            'Diterima' => $rawStatuses['diterima'] ?? 0,
            'Ditolak' => $rawStatuses['ditolak'] ?? 0,
            'Revisi' => $rawStatuses['perlu_revisi'] ?? 0,
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
