@extends('layouts.app')

@section('title', 'Data Pendaftar & Verifikasi')
@section('header_title', 'Verifikasi Pendaftar')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    <!-- Breadcrumb -->
    <nav class="text-sm text-[#a1b0cb] dark:text-[#7e8ba1] font-medium tracking-wide mb-6">
        Dashboard <span class="mx-1">&gt;</span> Admin <span class="mx-1">&gt;</span> <span class="text-[#697a8d] dark:text-[#b4bdc6] font-semibold">Verifikasi Pendaftar</span>
    </nav>

    <!-- Header Card -->
    <div class="bg-white dark:bg-[#2b2c40] rounded-lg shadow-sneat dark:shadow-sneat-dark border border-[#d9dee3] dark:border-[#434463] p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <h2 class="text-xl font-bold text-[#566a7f] dark:text-[#d5d5e2] flex items-center gap-2 m-0">
            Verifikasi Pendaftar
        </h2>
    </div>

    <!-- Filters & Search -->
    <div class="bg-white dark:bg-[#2b2c40] rounded-lg shadow-sneat dark:shadow-sneat-dark border border-[#d9dee3] dark:border-[#434463] p-5">
        <form action="{{ route('admin.verifikasi.index') }}" method="GET" class="flex flex-col sm:flex-row gap-4">
            
            <!-- Search -->
            <div class="flex-1">
                <label class="sr-only" for="search">Cari</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i data-lucide="search" class="w-5 h-5 text-[#a1b0cb]"></i>
                    </div>
                    <input type="text" name="search" id="search" value="{{ request('search') }}" class="sneat-input !pl-10" placeholder="Cari nama anak atau No. Pendaftaran...">
                </div>
            </div>

            <!-- Filter Gelombang -->
            <div class="sm:w-48">
                <select name="pendaftaran_id" class="sneat-input">
                    <option value="">Semua Gelombang</option>
                    @foreach($pendaftarans as $p)
                        <option value="{{ $p->id }}" {{ request('pendaftaran_id') == $p->id ? 'selected' : '' }}>{{ $p->gelombang }} ({{ $p->tahun_ajaran }})</option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Status -->
            <div class="sm:w-48">
                <select name="status" class="sneat-input">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="menunggu_verifikasi" {{ request('status') == 'menunggu_verifikasi' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                    <option value="diterima" {{ request('status') == 'diterima' ? 'selected' : '' }}>Diterima</option>
                    <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                    <option value="perlu_revisi" {{ request('status') == 'perlu_revisi' ? 'selected' : '' }}>Perlu Revisi</option>
                </select>
            </div>

            <!-- Submit -->
            <button type="submit" class="sneat-btn-primary">
                <i data-lucide="filter" class="w-4 h-4"></i> Filter
            </button>
            <a href="{{ route('admin.verifikasi.index') }}" class="sneat-btn-secondary">
                Reset
            </a>
            <div class="relative inline-block text-left">
                <button id="exportDropdownBtnVerifikasi" type="button" class="inline-flex items-center gap-2 px-3 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md transition-colors" aria-expanded="false">
                    <i data-lucide="download" class="w-4 h-4"></i>
                    Export
                    <i data-lucide="chevron-down" class="w-4 h-4"></i>
                </button>

                <div id="exportDropdownMenuVerifikasi" class="hidden origin-top-right absolute right-0 mt-2 w-40 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50">
                    <div class="py-1">
                        <a href="{{ route('admin.verifikasi.export', ['type' => 'xlsx']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Excel (.xlsx)</a>
                        <a href="{{ route('admin.verifikasi.export', ['type' => 'csv']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">CSV (.csv)</a>
                        <a href="{{ route('admin.verifikasi.export', ['type' => 'pdf']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">PDF (.pdf)</a>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Data Table -->
    <div class="bg-white dark:bg-[#2b2c40] rounded-lg shadow-sneat dark:shadow-sneat-dark border border-[#d9dee3] dark:border-[#434463] overflow-hidden">
        <div class="w-full overflow-x-auto">
            <table class="sneat-table w-full table-auto whitespace-nowrap">
                <thead>
                    <tr>
                        <th>Tgl Daftar</th>
                        <th>Nama Anak</th>
                        <th>Wali Murid</th>
                        <th>Gelombang</th>
                        <th>Status</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($registrations as $reg)
                        <tr>
                            <td>
                                {{ $reg->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td>
                                <div class="font-medium text-[#566a7f] dark:text-[#d5d5e2]">{{ $reg->siswa->nama ?? '-' }}</div>
                                <div class="text-xs text-[#a1b0cb] mt-0.5">{{ $reg->siswa->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</div>
                            </td>
                            <td>
                                <div class="text-[#566a7f] dark:text-[#d5d5e2]">{{ $reg->siswa->user->name ?? '-' }}</div>
                                <div class="text-xs text-[#a1b0cb] mt-0.5">{{ $reg->siswa->no_telpon ?? '-' }}</div>
                            </td>
                            <td>
                                {{ $reg->pendaftaran->gelombang ?? '-' }}
                            </td>
                            <td>
                                @if($reg->status === 'pending')
                                    <span class="sneat-badge bg-[#f5f5f9] dark:bg-[#232333] text-[#697a8d] dark:text-[#a1b0cb] border border-[#d9dee3] dark:border-[#434463]">
                                        Pending
                                    </span>
                                @elseif($reg->status === 'menunggu_verifikasi')
                                    <span class="sneat-badge bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-200 dark:border-amber-500/20">
                                        Verifikasi
                                    </span>
                                @elseif($reg->status === 'diterima')
                                    <span class="sneat-badge bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/20">
                                        Diterima
                                    </span>
                                @elseif($reg->status === 'ditolak')
                                    <span class="sneat-badge bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 border border-red-200 dark:border-red-500/20">
                                        Ditolak
                                    </span>
                                @elseif($reg->status === 'perlu_revisi')
                                    <span class="sneat-badge bg-orange-50 dark:bg-orange-500/10 text-orange-600 dark:text-orange-400 border border-orange-200 dark:border-orange-500/20">
                                        Perlu Revisi
                                    </span>
                                @endif
                            </td>
                            <td class="text-right space-x-2">
                                <a href="{{ route('admin.verifikasi.show', $reg->id) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-md bg-[#e7e7ff] dark:bg-[#696cff]/20 text-[#696cff] hover:bg-[#696cff] hover:text-white transition-colors" title="Lihat Detail & Verifikasi">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                </a>
                                
                                <!-- Hapus / Delete Button -->
                                <form action="{{ route('admin.verifikasi.destroy', $reg->id) }}" method="POST" class="inline-block form-delete">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center justify-center w-8 h-8 rounded-md bg-red-50 dark:bg-red-500/10 text-red-500 hover:bg-red-500 hover:text-white transition-colors" title="Hapus Data">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="w-16 h-16 bg-[#f5f5f9] dark:bg-[#232333] rounded-full flex items-center justify-center mx-auto mb-3">
                                    <i data-lucide="search-X" class="w-8 h-8 text-[#a1b0cb]"></i>
                                </div>
                                <p class="text-base font-medium text-[#566a7f] dark:text-[#d5d5e2]">Tidak ada pendaftar ditemukan.</p>
                                <p class="text-sm mt-1 text-[#a1b0cb]">Coba ubah filter pencarian Anda.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($registrations->hasPages())
        <div class="px-6 py-4 border-t border-[#d9dee3] dark:border-[#434463]">
            {{ $registrations->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var btn = document.getElementById('exportDropdownBtnVerifikasi');
        var menu = document.getElementById('exportDropdownMenuVerifikasi');
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
