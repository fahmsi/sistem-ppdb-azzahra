@extends('layouts.app')

@section('title', 'Data Pendaftar & Verifikasi')
@section('header_title', 'Verifikasi Pendaftar')

@section('content')
<div class="admin-table-card">
    <div class="admin-table-header">
        <!-- <h2 class="admin-table-title">Verifikasi Pendaftaran</h2> -->

        <form action="{{ route('admin.verifikasi.index') }}" method="GET" class="admin-table-toolbar">
            <div class="admin-table-search relative">
                <label class="sr-only" for="search">Cari</label>
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <i data-lucide="search" class="w-5 h-5 text-[#a1b0cb]"></i>
                </div>
                <input type="text" name="search" id="search" value="{{ request('search') }}" class="sneat-input h-10 !pl-10" placeholder="Cari nama anak atau No. Pendaftaran...">
            </div>

            <select name="pendaftaran_id" class="sneat-input h-10 sm:w-48">
                <option value="">Semua Gelombang</option>
                @foreach($pendaftarans as $p)
                    <option value="{{ $p->id }}" {{ request('pendaftaran_id') == $p->id ? 'selected' : '' }}>{{ $p->gelombang }} ({{ $p->tahun_ajaran }})</option>
                @endforeach
            </select>

            <select name="status" class="sneat-input h-10 sm:w-40">
                <option value="">Semua Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="menunggu_verifikasi" {{ request('status') == 'menunggu_verifikasi' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                <option value="diterima" {{ request('status') == 'diterima' ? 'selected' : '' }}>Diterima</option>
                <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                <option value="perlu_revisi" {{ request('status') == 'perlu_revisi' ? 'selected' : '' }}>Perlu Revisi</option>
            </select>

            <button type="submit" class="sneat-btn-primary h-10 admin-table-action-btn">
                <i data-lucide="filter" class="w-4 h-4"></i> Filter
            </button>

            <a href="{{ route('admin.verifikasi.index') }}" class="sneat-btn-secondary h-10 admin-table-action-btn">
                Reset
            </a>

            <div class="relative inline-block text-left">
                <button id="exportDropdownBtnVerifikasi" type="button" class="inline-flex h-10 items-center justify-center gap-2 rounded-md bg-green-600 px-3 text-sm font-medium text-white transition-colors hover:bg-green-700 admin-table-action-btn" aria-expanded="false">
                    <i data-lucide="download" class="w-4 h-4"></i>
                    Export
                    <i data-lucide="chevron-down" class="w-4 h-4"></i>
                </button>

                <div id="exportDropdownMenuVerifikasi" class="absolute right-0 z-50 mt-2 hidden w-40 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none dark:bg-[#2b2c40]">
                    <div class="py-1">
                        <a href="{{ route('admin.verifikasi.export', ['type' => 'xlsx']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-[#d5d5e2] dark:hover:bg-[#232333]">Excel (.xlsx)</a>
                        <a href="{{ route('admin.verifikasi.export', ['type' => 'csv']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-[#d5d5e2] dark:hover:bg-[#232333]">CSV (.csv)</a>
                        <a href="{{ route('admin.verifikasi.export', ['type' => 'pdf']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-[#d5d5e2] dark:hover:bg-[#232333]">PDF (.pdf)</a>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="admin-table-responsive text-nowrap">
        <table class="table table-hover align-middle admin-table">
            <thead>
                <tr>
                    <th>Tgl Daftar</th>
                    <th>No. Pendaftaran</th>
                    <th>Nama Anak</th>
                    <th>Wali Murid</th>
                    <th>Gelombang</th>
                    <th>Status</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($registrations as $reg)
                    <tr>
                        <td>{{ $reg->created_at->format('d/m/Y H:i') }} WIB</td>
                        <td class="font-semibold text-[#566a7f] dark:text-[#d5d5e2]">{{ $reg->nomor_pendaftaran }}</td>
                        <td>
                            <div class="font-medium text-[#566a7f] dark:text-[#d5d5e2]">{{ $reg->siswa->nama ?? '-' }}</div>
                            <div class="text-xs text-[#a1b0cb] mt-0.5">{{ $reg->siswa->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</div>
                        </td>
                        <td>
                            <div class="text-[#566a7f] dark:text-[#d5d5e2]">{{ $reg->siswa?->user?->name ?? '-' }}</div>
                            <div class="text-xs text-[#a1b0cb] mt-0.5">{{ $reg->siswa?->no_telpon ?? $reg->siswa?->user?->no_telpon ?? '-' }}</div>
                        </td>
                        <td>{{ $reg->pendaftaran->gelombang ?? '-' }}</td>
                        <td>
                            @if($reg->status === 'pending')
                                <span class="sneat-badge bg-[#f5f5f9] dark:bg-[#232333] text-[#697a8d] dark:text-[#a1b0cb] border border-[#d9dee3] dark:border-[#434463]">Pending</span>
                            @elseif($reg->status === 'menunggu_verifikasi')
                                <span class="sneat-badge bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-200 dark:border-amber-500/20">Verifikasi</span>
                            @elseif($reg->status === 'diterima')
                                <span class="sneat-badge bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/20">Diterima</span>
                            @elseif($reg->status === 'ditolak')
                                <span class="sneat-badge bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 border border-red-200 dark:border-red-500/20">Ditolak</span>
                            @elseif($reg->status === 'perlu_revisi')
                                <span class="sneat-badge bg-orange-50 dark:bg-orange-500/10 text-orange-600 dark:text-orange-400 border border-orange-200 dark:border-orange-500/20">Perlu Revisi</span>
                            @endif
                        </td>
                        <td class="text-center admin-table-actions-cell">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.verifikasi.show', $reg->id) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-md bg-[#e7e7ff] dark:bg-[#696cff]/20 text-[#696cff] hover:bg-[#696cff] hover:text-white transition-colors" title="Lihat Detail & Verifikasi">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                </a>

                                <form action="{{ route('admin.verifikasi.destroy', $reg->id) }}" method="POST" class="inline-block form-delete">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center justify-center w-8 h-8 rounded-md bg-red-50 dark:bg-red-500/10 text-red-500 hover:bg-red-500 hover:text-white transition-colors" title="Hapus Data">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-10 text-center text-[#a1b0cb]">Belum ada data.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="admin-table-footer">
        @if($registrations->hasPages())
        <div>
            {{ $registrations->links() }}
        </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var btn = document.getElementById('exportDropdownBtnVerifikasi');
    var menu = document.getElementById('exportDropdownMenuVerifikasi');
    if (btn && menu) {
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
