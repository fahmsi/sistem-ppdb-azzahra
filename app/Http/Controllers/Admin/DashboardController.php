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

        return view('admin.dashboard', compact('stats', 'recentRegistrations', 'recentLogs'));
    }
}
