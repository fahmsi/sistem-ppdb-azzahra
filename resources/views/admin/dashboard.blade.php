@extends('layouts.app')

@section('title', 'Admin Dashboard')
@section('header_title', 'Dashboard Administrator')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    <!-- Header Section -->
    <div class="flex items-center justify-between animate-fade-in">
        <div>
            <h2 class="text-2xl font-heading font-bold text-[#566a7f] dark:text-[#d5d5e2]">Ringkasan PPDB</h2>
            <p class="text-[#697a8d] dark:text-[#a1b0cb] text-sm mt-1">Pantau statistik pendaftaran dan aktivitas terbaru hari ini.</p>
        </div>
        <div class="hidden sm:block">
            <span class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-white dark:bg-[#2b2c40] border border-[#d9dee3] dark:border-[#434463] text-sm font-medium text-[#697a8d] dark:text-[#a1b0cb] shadow-sneat dark:shadow-sneat-dark">
                <i data-lucide="calendar" class="w-4 h-4"></i> {{ date('d F Y') }}
            </span>
        </div>
    </div>

    <!-- 4 Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 animate-fade-up">
        
        <!-- Total Pendaftar -->
        <div class="bg-white dark:bg-[#2b2c40] rounded-lg shadow-sneat dark:shadow-sneat-dark border border-[#d9dee3] dark:border-[#434463] p-6 flex items-center gap-4 hover:shadow-sneat-lg dark:hover:shadow-sneat-dark-lg transition-shadow">
            <div class="w-12 h-12 rounded-lg bg-[#e7e7ff] dark:bg-[#696cff]/20 flex items-center justify-center flex-shrink-0 text-[#696cff]">
                <i data-lucide="users" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-sm font-medium text-[#697a8d] dark:text-[#a1b0cb]">Total Pendaftar</p>
                <h3 class="text-2xl font-bold text-[#566a7f] dark:text-[#d5d5e2]">{{ number_format($stats['total_pendaftar'] ?? 0) }}</h3>
            </div>
        </div>

        <!-- Menunggu Verifikasi -->
        <div class="bg-white dark:bg-[#2b2c40] rounded-lg shadow-sneat dark:shadow-sneat-dark border border-[#d9dee3] dark:border-[#434463] p-6 flex items-center gap-4 hover:shadow-sneat-lg dark:hover:shadow-sneat-dark-lg transition-shadow">
            <div class="w-12 h-12 rounded-lg bg-amber-50 dark:bg-amber-500/10 flex items-center justify-center flex-shrink-0 text-amber-500">
                <i data-lucide="clock" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-sm font-medium text-[#697a8d] dark:text-[#a1b0cb]">Menunggu Verifikasi</p>
                <h3 class="text-2xl font-bold text-[#566a7f] dark:text-[#d5d5e2]">{{ number_format($stats['menunggu_verifikasi'] ?? 0) }}</h3>
            </div>
        </div>

        <!-- Diterima -->
        <div class="bg-white dark:bg-[#2b2c40] rounded-lg shadow-sneat dark:shadow-sneat-dark border border-[#d9dee3] dark:border-[#434463] p-6 flex items-center gap-4 hover:shadow-sneat-lg dark:hover:shadow-sneat-dark-lg transition-shadow">
            <div class="w-12 h-12 rounded-lg bg-emerald-50 dark:bg-emerald-500/10 flex items-center justify-center flex-shrink-0 text-emerald-500">
                <i data-lucide="check-circle" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-sm font-medium text-[#697a8d] dark:text-[#a1b0cb]">Diterima</p>
                <h3 class="text-2xl font-bold text-[#566a7f] dark:text-[#d5d5e2]">{{ number_format($stats['diterima'] ?? 0) }}</h3>
            </div>
        </div>

        <!-- Ditolak -->
        <div class="bg-white dark:bg-[#2b2c40] rounded-lg shadow-sneat dark:shadow-sneat-dark border border-[#d9dee3] dark:border-[#434463] p-6 flex items-center gap-4 hover:shadow-sneat-lg dark:hover:shadow-sneat-dark-lg transition-shadow">
            <div class="w-12 h-12 rounded-lg bg-red-50 dark:bg-red-500/10 flex items-center justify-center flex-shrink-0 text-red-500">
                <i data-lucide="x-circle" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-sm font-medium text-[#697a8d] dark:text-[#a1b0cb]">Ditolak</p>
                <h3 class="text-2xl font-bold text-[#566a7f] dark:text-[#d5d5e2]">{{ number_format($stats['ditolak'] ?? 0) }}</h3>
            </div>
        </div>
    </div>

    <!-- Recent Activity Table -->
    <div class="bg-white dark:bg-[#2b2c40] rounded-lg shadow-sneat dark:shadow-sneat-dark border border-[#d9dee3] dark:border-[#434463] overflow-hidden animate-fade-up" style="animation-delay: 0.2s;">
        
        <!-- Table Header -->
        <div class="px-6 py-5 border-b border-[#d9dee3] dark:border-[#434463] flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h3 class="text-lg font-heading font-semibold text-[#566a7f] dark:text-[#d5d5e2]">Pendaftar Terbaru</h3>
                <p class="text-sm text-[#a1b0cb] mt-1">5 aktivitas pendaftaran terakhir yang masuk ke sistem.</p>
            </div>
            <a href="{{ route('admin.verifikasi.index') }}" class="inline-flex items-center gap-2 text-sm font-medium text-[#696cff] hover:text-[#5a5de6] transition-colors">
                Lihat Semua <i data-lucide="arrow-right" class="w-4 h-4"></i>
            </a>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="sneat-table">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Nama Siswa</th>
                        <th>Gelombang</th>
                        <th>Status</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentRegistrations ?? [] as $reg)
                        <tr>
                            <td class="whitespace-nowrap">
                                {{ $reg->created_at->format('d M Y, H:i') }}
                            </td>
                            <td>
                                <div class="font-medium text-[#566a7f] dark:text-[#d5d5e2]">{{ $reg->siswa->nama ?? '-' }}</div>
                                <div class="text-xs text-[#a1b0cb] mt-0.5">{{ $reg->siswa->user->name ?? '-' }} (Wali)</div>
                            </td>
                            <td>
                                {{ $reg->pendaftaran->gelombang ?? '-' }}
                            </td>
                            <td class="whitespace-nowrap">
                                @if($reg->status === 'pending')
                                    <span class="sneat-badge bg-[#f5f5f9] dark:bg-[#232333] text-[#697a8d] dark:text-[#a1b0cb]">Pending</span>
                                @elseif($reg->status === 'menunggu_verifikasi')
                                    <span class="sneat-badge bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400">Verifikasi</span>
                                @elseif($reg->status === 'diterima')
                                    <span class="sneat-badge bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400">Diterima</span>
                                @elseif($reg->status === 'ditolak')
                                    <span class="sneat-badge bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400">Ditolak</span>
                                @elseif($reg->status === 'perlu_revisi')
                                    <span class="sneat-badge bg-orange-50 dark:bg-orange-500/10 text-orange-600 dark:text-orange-400">Perlu Revisi</span>
                                @endif
                            </td>
                            <td class="text-right whitespace-nowrap">
                                <a href="{{ route('admin.verifikasi.show', $reg->id) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-md bg-[#e7e7ff] dark:bg-[#696cff]/20 text-[#696cff] hover:bg-[#696cff] hover:text-white transition-colors" title="Lihat Detail">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <i data-lucide="inbox" class="w-8 h-8 text-[#a1b0cb]"></i>
                                    <p>Belum ada data pendaftar terbaru.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Activity Log Section (Super Admin Only) -->
    @if(auth()->user()->isSuperAdmin() && count($recentLogs) > 0)
    <div class="bg-white dark:bg-[#2b2c40] rounded-lg shadow-sneat dark:shadow-sneat-dark border border-[#d9dee3] dark:border-[#434463] overflow-hidden animate-fade-up" style="animation-delay: 0.4s;">
        
        <div class="px-6 py-5 border-b border-[#d9dee3] dark:border-[#434463] flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h3 class="text-lg font-heading font-semibold text-[#566a7f] dark:text-[#d5d5e2] flex items-center gap-2">
                    <i data-lucide="scroll-text" class="w-5 h-5 text-amber-500"></i>
                    Riwayat Aktivitas Terbaru
                </h3>
                <p class="text-sm text-[#a1b0cb] mt-1">8 aktivitas terakhir di sistem.</p>
            </div>
            <a href="{{ route('admin.activity-log.index') }}" class="inline-flex items-center gap-2 text-sm font-medium text-[#696cff] hover:text-[#5a5de6] transition-colors">
                Lihat Semua <i data-lucide="arrow-right" class="w-4 h-4"></i>
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="sneat-table">
                <thead>
                    <tr>
                        <th>Waktu</th>
                        <th>Aktor</th>
                        <th>Aksi</th>
                        <th>Deskripsi</th>
                        <th>IP</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentLogs as $log)
                        <tr>
                            <td class="whitespace-nowrap text-xs">
                                {{ $log->created_at->format('d/m H:i') }}
                            </td>
                            <td class="text-xs font-medium text-[#566a7f] dark:text-[#d5d5e2]">
                                {{ $log->user_name ?? 'System' }}
                            </td>
                            <td>
                                <span class="sneat-badge {{ $log->action_color }}">
                                    {{ $log->action_label }}
                                </span>
                            </td>
                            <td class="text-xs max-w-xs truncate">
                                {{ Str::limit($log->description, 50) }}
                            </td>
                            <td class="whitespace-nowrap">
                                <code class="text-[10px] bg-[#f5f5f9] dark:bg-[#232333] text-[#a1b0cb] px-1.5 py-0.5 rounded font-mono">{{ $log->ip_address ?? '-' }}</code>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

</div>
@endsection
