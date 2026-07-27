@extends('layouts.app')

@section('title', 'Admin Dashboard')
@section('header_title', 'Dashboard Administrator')

@section('content')
<div class="mx-auto max-w-7xl space-y-4 sm:space-y-6">

    <!-- Header Section -->
    <div class="relative overflow-hidden rounded-xl border border-[#d9dee3] bg-white p-5 shadow-sneat animate-fade-in dark:border-[#434463] dark:bg-[#2b2c40] dark:shadow-sneat-dark sm:min-h-[168px] sm:p-7 lg:px-8">
        <div class="pointer-events-none absolute inset-y-0 right-0 hidden w-64 bg-gradient-to-l from-[#e7e7ff]/80 via-[#f5f5f9]/60 to-transparent dark:from-[#696cff]/15 dark:via-[#232333]/30 sm:block"></div>
        <div class="pointer-events-none absolute -right-10 -top-16 hidden h-52 w-52 rounded-full border border-[#696cff]/10 sm:block"></div>

        <div class="relative z-10 flex h-full items-center justify-between gap-6">
            <div class="max-w-2xl">
                <span class="mb-3 inline-flex items-center gap-2 rounded-full bg-[#e7e7ff] px-3 py-1 text-xs font-bold text-[#696cff] dark:bg-[#696cff]/20 dark:text-[#b0b1ff]">
                    <i data-lucide="layout-dashboard" class="h-3.5 w-3.5"></i>
                    Dashboard Admin
                </span>
                <h2 class="font-heading text-2xl font-bold leading-tight text-[#566a7f] dark:text-[#d5d5e2] sm:text-3xl">Ringkasan SPMB</h2>
                <p class="mt-1.5 text-sm leading-6 text-[#697a8d] dark:text-[#a1b0cb]">Pantau statistik pendaftaran dan aktivitas terbaru hari ini.</p>

                <span class="mt-4 inline-flex w-full items-center justify-center gap-2 rounded-lg border border-[#d9dee3] bg-[#f5f5f9] px-3.5 py-2 text-sm font-semibold text-[#697a8d] shadow-sm transition-colors dark:border-[#434463] dark:bg-[#232333] dark:text-[#a1b0cb] sm:w-auto sm:justify-start">
                    <i data-lucide="calendar" class="h-4 w-4 text-[#696cff]"></i>
                    {{ now()->translatedFormat('d F Y') }}
                </span>
            </div>

            <div class="relative hidden h-36 w-48 flex-shrink-0 self-end sm:block lg:mr-3 lg:w-56">
                <div class="absolute bottom-0 right-1 h-28 w-40 rounded-[50%] bg-white/60 blur-sm dark:bg-[#696cff]/10 lg:w-48"></div>
                <img src="{{ asset('images/man-with-laptop.png') }}"
                    alt="Ilustrasi administrator menggunakan laptop"
                    width="216"
                    height="216"
                    class="absolute -bottom-7 right-0 h-44 w-44 object-contain drop-shadow-sm lg:h-48 lg:w-48"
                    loading="eager">
            </div>
        </div>
    </div>

    <!-- 4 Summary Cards -->
    <div class="grid grid-cols-2 gap-3 animate-fade-up sm:gap-6 lg:grid-cols-4">
        
        <!-- Total Pendaftar -->
        <div class="flex min-w-0 flex-col items-start gap-3 rounded-lg border border-[#d9dee3] bg-white p-4 shadow-sneat transition-shadow hover:shadow-sneat-lg dark:border-[#434463] dark:bg-[#2b2c40] dark:shadow-sneat-dark dark:hover:shadow-sneat-dark-lg sm:flex-row sm:items-center sm:gap-4 sm:p-6">
            <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-lg bg-[#e7e7ff] text-[#696cff] dark:bg-[#696cff]/20 sm:h-12 sm:w-12">
                <i data-lucide="users" class="w-6 h-6"></i>
            </div>
            <div class="min-w-0">
                <p class="text-xs font-medium leading-4 text-[#697a8d] dark:text-[#a1b0cb] sm:text-sm">Total Pendaftar</p>
                <h3 class="mt-1 text-xl font-bold text-[#566a7f] dark:text-[#d5d5e2] sm:text-2xl">{{ number_format($stats['total_pendaftar'] ?? 0) }}</h3>
            </div>
        </div>

        <!-- Menunggu Verifikasi -->
        <div class="flex min-w-0 flex-col items-start gap-3 rounded-lg border border-[#d9dee3] bg-white p-4 shadow-sneat transition-shadow hover:shadow-sneat-lg dark:border-[#434463] dark:bg-[#2b2c40] dark:shadow-sneat-dark dark:hover:shadow-sneat-dark-lg sm:flex-row sm:items-center sm:gap-4 sm:p-6">
            <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-lg bg-amber-50 text-amber-500 dark:bg-amber-500/10 sm:h-12 sm:w-12">
                <i data-lucide="clock" class="w-6 h-6"></i>
            </div>
            <div class="min-w-0">
                <p class="text-xs font-medium leading-4 text-[#697a8d] dark:text-[#a1b0cb] sm:text-sm">Menunggu Verifikasi</p>
                <h3 class="mt-1 text-xl font-bold text-[#566a7f] dark:text-[#d5d5e2] sm:text-2xl">{{ number_format($stats['menunggu_verifikasi'] ?? 0) }}</h3>
            </div>
        </div>

        <!-- Diterima -->
        <div class="flex min-w-0 flex-col items-start gap-3 rounded-lg border border-[#d9dee3] bg-white p-4 shadow-sneat transition-shadow hover:shadow-sneat-lg dark:border-[#434463] dark:bg-[#2b2c40] dark:shadow-sneat-dark dark:hover:shadow-sneat-dark-lg sm:flex-row sm:items-center sm:gap-4 sm:p-6">
            <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-lg bg-emerald-50 text-emerald-500 dark:bg-emerald-500/10 sm:h-12 sm:w-12">
                <i data-lucide="check-circle" class="w-6 h-6"></i>
            </div>
            <div class="min-w-0">
                <p class="text-xs font-medium leading-4 text-[#697a8d] dark:text-[#a1b0cb] sm:text-sm">Diterima</p>
                <h3 class="mt-1 text-xl font-bold text-[#566a7f] dark:text-[#d5d5e2] sm:text-2xl">{{ number_format($stats['diterima'] ?? 0) }}</h3>
            </div>
        </div>

        <!-- Ditolak -->
        <div class="flex min-w-0 flex-col items-start gap-3 rounded-lg border border-[#d9dee3] bg-white p-4 shadow-sneat transition-shadow hover:shadow-sneat-lg dark:border-[#434463] dark:bg-[#2b2c40] dark:shadow-sneat-dark dark:hover:shadow-sneat-dark-lg sm:flex-row sm:items-center sm:gap-4 sm:p-6">
            <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-lg bg-red-50 text-red-500 dark:bg-red-500/10 sm:h-12 sm:w-12">
                <i data-lucide="x-circle" class="w-6 h-6"></i>
            </div>
            <div class="min-w-0">
                <p class="text-xs font-medium leading-4 text-[#697a8d] dark:text-[#a1b0cb] sm:text-sm">Ditolak</p>
                <h3 class="mt-1 text-xl font-bold text-[#566a7f] dark:text-[#d5d5e2] sm:text-2xl">{{ number_format($stats['ditolak'] ?? 0) }}</h3>
            </div>
        </div>
    </div>

    <!-- Analytics Charts -->
    <div class="grid grid-cols-1 gap-4 animate-fade-up sm:gap-6 lg:grid-cols-3" style="animation-delay: 0.1s;">
        <!-- Gender Chart -->
        <div class="rounded-lg border border-[#d9dee3] bg-white p-4 shadow-sneat dark:border-[#434463] dark:bg-[#2b2c40] dark:shadow-sneat-dark sm:p-5">
            <h4 class="text-sm font-semibold text-[#566a7f] dark:text-[#d5d5e2] mb-4">Statistik Gender</h4>
            <div class="relative h-56 w-full sm:h-64">
                <canvas id="genderChart"></canvas>
            </div>
        </div>

        <!-- Status Chart -->
        <div class="rounded-lg border border-[#d9dee3] bg-white p-4 shadow-sneat dark:border-[#434463] dark:bg-[#2b2c40] dark:shadow-sneat-dark sm:p-5">
            <h4 class="text-sm font-semibold text-[#566a7f] dark:text-[#d5d5e2] mb-4">Status Pendaftaran</h4>
            <div class="relative h-56 w-full sm:h-64">
                <canvas id="statusChart"></canvas>
            </div>
        </div>

        <!-- Gelombang Chart -->
        <div class="rounded-lg border border-[#d9dee3] bg-white p-4 shadow-sneat dark:border-[#434463] dark:bg-[#2b2c40] dark:shadow-sneat-dark sm:p-5">
            <h4 class="text-sm font-semibold text-[#566a7f] dark:text-[#d5d5e2] mb-4">Pendaftar per Gelombang</h4>
            <div class="relative h-56 w-full sm:h-64">
                <canvas id="gelombangChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent Activity Table -->
    <div class="bg-white dark:bg-[#2b2c40] rounded-lg shadow-sneat dark:shadow-sneat-dark border border-[#d9dee3] dark:border-[#434463] overflow-hidden animate-fade-up" style="animation-delay: 0.2s;">
        
        <!-- Table Header -->
        <div class="flex flex-col justify-between gap-3 border-b border-[#d9dee3] px-4 py-4 dark:border-[#434463] sm:flex-row sm:items-center sm:px-6 sm:py-5">
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
            <table class="sneat-table min-w-[680px]">
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
                                {{ $reg->created_at->translatedFormat('d M Y, H:i') }} WIB
                            </td>
                            <td>
                                <div class="font-medium text-[#566a7f] dark:text-[#d5d5e2]">{{ $reg->siswa->nama ?? '-' }}</div>
                                <div class="text-xs text-[#a1b0cb] mt-0.5">{{ $reg->siswa?->user?->name ?? '-' }} (Wali)</div>
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
        
        <div class="flex flex-col justify-between gap-3 border-b border-[#d9dee3] px-4 py-4 dark:border-[#434463] sm:flex-row sm:items-center sm:px-6 sm:py-5">
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
            <table class="sneat-table min-w-[720px]">
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
                                {{ $log->created_at->translatedFormat('d/m H:i') }} WIB
                            </td>
                            <td class="text-xs font-medium text-[#566a7f] dark:text-[#d5d5e2]">
                                {{ $log->user_name ?? 'System' }}
                            </td>
                            <td>
                                <span class="activity-action-badge activity-action-{{ Str::slug($log->action) }} sneat-badge {{ $log->action_color }}">
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

<!-- Chart.js and Initialization -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const isDark = document.documentElement.classList.contains('dark');
        const textColor = isDark ? '#d5d5e2' : '#566a7f';
        const gridColor = isDark ? '#434463' : '#d9dee3';

        Chart.defaults.color = textColor;
        Chart.defaults.font.family = "'Jakarta Sans', sans-serif";

        // Data arrays will be injected directly into the charts below

        // 1. Gender Chart (Doughnut)
        new Chart(document.getElementById('genderChart'), {
            type: 'doughnut',
            data: {
                labels: {{ Illuminate\Support\Js::from($chartGender['labels'] ?? []) }},
                datasets: [{
                    data: {{ Illuminate\Support\Js::from($chartGender['values'] ?? []) }},
                    backgroundColor: ['#696cff', '#ff3e1d'],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });

        // 2. Status Chart (Doughnut)
        new Chart(document.getElementById('statusChart'), {
            type: 'doughnut',
            data: {
                labels: {{ Illuminate\Support\Js::from($chartStatus['labels'] ?? []) }},
                datasets: [{
                    data: {{ Illuminate\Support\Js::from($chartStatus['values'] ?? []) }},
                    backgroundColor: [
                        '#697a8d', // Pending (Gray)
                        '#ffab00', // Menunggu (Warning)
                        '#71dd37', // Diterima (Success)
                        '#ff3e1d', // Ditolak (Danger)
                        '#fd7e14'  // Revisi (Orange)
                    ],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });

        // 3. Gelombang Chart (Bar)
        new Chart(document.getElementById('gelombangChart'), {
            type: 'bar',
            data: {
                labels: {{ Illuminate\Support\Js::from($chartGelombang['labels'] ?? []) }},
                datasets: [{
                    label: 'Jumlah Pendaftar',
                    data: {{ Illuminate\Support\Js::from($chartGelombang['values'] ?? []) }},
                    backgroundColor: '#696cff',
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1 },
                        grid: { color: gridColor, drawBorder: false }
                    },
                    x: {
                        grid: { display: false, drawBorder: false }
                    }
                }
            }
        });
    });
</script>

@endsection
