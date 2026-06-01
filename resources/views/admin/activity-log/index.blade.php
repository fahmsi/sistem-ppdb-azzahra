@extends('layouts.app')
@section('title', 'Activity Log')
@section('header_title', 'Activity Log')
@section('content')
<div class="space-y-6">


    <!-- Header Card -->
    <div class="bg-white dark:bg-[#2b2c40] rounded-lg shadow-sneat dark:shadow-sneat-dark border border-[#d9dee3] dark:border-[#434463] p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <h2 class="text-xl font-bold text-[#566a7f] dark:text-[#d5d5e2] flex items-center gap-2 m-0">
            Riwayat Aktivitas
        </h2>
    </div>
    <div class="bg-white dark:bg-[#2b2c40] rounded-lg shadow-sneat dark:shadow-sneat-dark border border-[#d9dee3] dark:border-[#434463] overflow-hidden">
        <div class="w-full overflow-x-auto">
            <table class="sneat-table w-full table-auto whitespace-nowrap">
                <thead><tr><th>Waktu</th><th>Aktor</th><th>Aksi</th><th>Target</th><th>Deskripsi</th><th>IP Address</th></tr></thead>
                <tbody>
                    @forelse($logs as $log)
                    <tr>
                        <td class="whitespace-nowrap">
                            <div class="text-xs">
                                <span class="font-medium text-[#566a7f] dark:text-[#d5d5e2]">{{ $log->created_at->format('d M Y') }}</span><br>
                                <span>{{ $log->created_at->format('H:i:s') }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-full bg-[#e7e7ff] dark:bg-[#696cff]/20 flex items-center justify-center text-[#696cff] font-bold text-[10px] uppercase flex-shrink-0">{{ substr($log->user_name ?? '?', 0, 1) }}</div>
                                <span class="font-medium text-[#566a7f] dark:text-[#d5d5e2] text-xs">{{ $log->user_name ?? 'System' }}</span>
                            </div>
                        </td>
                        <td><span class="sneat-badge {{ $log->action_color }}">{{ $log->action_label }}</span></td>
                        <td>
                            <div class="text-xs">
                                <span class="text-[#a1b0cb]">{{ $log->target_type_label }}</span>
                                @if($log->target_label)<br><span class="font-medium text-[#566a7f] dark:text-[#d5d5e2]">{{ Str::limit($log->target_label, 30) }}</span>@endif
                            </div>
                        </td>
                        <td class="max-w-xs"><p class="text-xs truncate" title="{{ $log->description }}">{{ $log->description ?? '-' }}</p></td>
                        <td class="whitespace-nowrap"><code class="text-[10px] bg-[#f5f5f9] dark:bg-[#232333] text-[#a1b0cb] px-2 py-0.5 rounded font-mono">{{ $log->ip_address ?? '-' }}</code></td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-6 py-8 text-center text-[#a1b0cb]"><i data-lucide="scroll-text" class="w-8 h-8 text-[#a1b0cb] mx-auto mb-2"></i><p>Belum ada riwayat aktivitas.</p></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($logs->hasPages())<div class="px-6 py-4 border-t border-[#d9dee3] dark:border-[#434463]">{{ $logs->links() }}</div>@endif
    </div>
</div>
@endsection
