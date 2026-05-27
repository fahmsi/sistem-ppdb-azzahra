@extends('layouts.app')

@section('title', 'Rekap Pembayaran')
@section('header_title', 'Rekap Pembayaran')

@section('content')
<div class="bg-white dark:bg-[#2b2c40] rounded-lg shadow-sneat dark:shadow-sneat-dark border border-[#d9dee3] dark:border-[#434463] p-6">
    <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mb-6">
        <h2 class="text-xl font-heading font-bold text-[#566a7f] dark:text-[#d5d5e2]">Semua Data Pembayaran</h2>
        <div class="flex gap-2 w-full sm:w-auto">
            <a href="{{ route('admin.pembayaran.index') }}" class="px-3 py-2 text-sm font-medium rounded-md {{ !request('status') ? 'bg-[#e7e7ff] dark:bg-[#696cff]/20 text-[#696cff]' : 'text-[#697a8d] dark:text-[#a1b0cb] hover:bg-[#f5f5f9] dark:hover:bg-[#232333]' }} transition-colors">Semua</a>
            <a href="{{ route('admin.pembayaran.index', ['status' => 'pending']) }}" class="px-3 py-2 text-sm font-medium rounded-md {{ request('status') === 'pending' ? 'bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400' : 'text-[#697a8d] dark:text-[#a1b0cb] hover:bg-[#f5f5f9] dark:hover:bg-[#232333]' }} transition-colors">Pending</a>
            <a href="{{ route('admin.pembayaran.index', ['status' => 'lunas']) }}" class="px-3 py-2 text-sm font-medium rounded-md {{ request('status') === 'lunas' ? 'bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400' : 'text-[#697a8d] dark:text-[#a1b0cb] hover:bg-[#f5f5f9] dark:hover:bg-[#232333]' }} transition-colors">Lunas</a>
            <a href="{{ route('admin.pembayaran.index', ['status' => 'ditolak']) }}" class="px-3 py-2 text-sm font-medium rounded-md {{ request('status') === 'ditolak' ? 'bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400' : 'text-[#697a8d] dark:text-[#a1b0cb] hover:bg-[#f5f5f9] dark:hover:bg-[#232333]' }} transition-colors">Ditolak</a>
            <div class="relative inline-block text-left">
                <button id="exportDropdownBtnPembayaran" type="button" class="inline-flex items-center gap-2 px-3 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md transition-colors" aria-expanded="false">
                    <i data-lucide="download" class="w-4 h-4"></i>
                    Export
                    <i data-lucide="chevron-down" class="w-4 h-4"></i>
                </button>

                <div id="exportDropdownMenuPembayaran" class="hidden origin-top-right absolute right-0 mt-2 w-40 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50">
                    <div class="py-1">
                        <a href="{{ route('admin.pembayaran.export', ['type' => 'xlsx']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Excel (.xlsx)</a>
                        <a href="{{ route('admin.pembayaran.export', ['type' => 'csv']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">CSV (.csv)</a>
                        <a href="{{ route('admin.pembayaran.export', ['type' => 'pdf']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">PDF (.pdf)</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="sneat-table">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Nama Siswa</th>
                    <th>Gelombang</th>
                    <th>Jumlah (Rp)</th>
                    <th>Status</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pembayarans as $pembayaran)
                <tr>
                    <td>{{ $pembayaran->created_at->format('d M Y') }}</td>
                    <td class="font-medium text-[#566a7f] dark:text-[#d5d5e2]">{{ $pembayaran->pendaftaranDetail->siswa->nama ?? '-' }}</td>
                    <td>{{ $pembayaran->pendaftaranDetail->pendaftaran->gelombang ?? '-' }}</td>
                    <td class="font-semibold text-[#566a7f] dark:text-[#d5d5e2]">{{ number_format($pembayaran->jumlah, 0, ',', '.') }}</td>
                    <td>
                        @if($pembayaran->status === 'pending')
                            <span class="sneat-badge bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400">Pending</span>
                        @elseif($pembayaran->status === 'lunas')
                            <span class="sneat-badge bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400">Lunas</span>
                        @else
                            <span class="sneat-badge bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400">Ditolak</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <a href="{{ route('admin.verifikasi.show', $pembayaran->pendaftaranDetail->id) }}" class="inline-flex items-center gap-1 px-3 py-1.5 bg-[#e7e7ff] dark:bg-[#696cff]/20 text-[#696cff] hover:bg-[#696cff] hover:text-white rounded-md text-xs font-medium transition-colors">
                            <i data-lucide="eye" class="w-4 h-4"></i> Cek & Verifikasi
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-8 text-center text-[#a1b0cb]">
                        Tidak ada data pembayaran.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-4">
        {{ $pembayarans->links() }}
    </div>
</div>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var btn = document.getElementById('exportDropdownBtnPembayaran');
        var menu = document.getElementById('exportDropdownMenuPembayaran');
        if (btn) {
            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                menu.classList.toggle('hidden');
            });
            document.addEventListener('click', function() {
                if (!menu.classList.contains('hidden')) menu.classList.add('hidden');
            });
        }
    });
    </script>
@endsection
