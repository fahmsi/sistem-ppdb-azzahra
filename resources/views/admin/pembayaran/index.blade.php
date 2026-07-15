@extends('layouts.app')

@section('title', 'Rekap Pembayaran')
@section('header_title', 'Rekap Pembayaran')

@section('content')
<div class="admin-table-card">
    <div class="admin-table-header">
        <!-- <h2 class="admin-table-title">Rekap Pembayaran</h2> -->

        <div class="admin-table-toolbar">
            <div class="admin-table-search relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <i data-lucide="search" class="w-5 h-5 text-[#a1b0cb]"></i>
                </div>
                <input type="search" id="paymentLiveSearch" class="sneat-input h-10 !pl-10" placeholder="Cari no pendaftaran, siswa, gelombang...">
            </div>

            <div class="admin-table-actions">
                <a href="{{ route('admin.pembayaran.index') }}" class="inline-flex h-10 items-center justify-center rounded-md px-3 text-sm font-medium transition-colors admin-table-action-btn {{ !request('status') ? 'bg-[#e7e7ff] dark:bg-[#696cff]/20 text-[#696cff]' : 'text-[#697a8d] dark:text-[#a1b0cb] hover:bg-[#f5f5f9] dark:hover:bg-[#232333]' }}">Semua</a>
                <a href="{{ route('admin.pembayaran.index', ['status' => 'menunggu_verifikasi']) }}" class="inline-flex h-10 items-center justify-center rounded-md px-3 text-sm font-medium transition-colors admin-table-action-btn {{ request('status') === 'menunggu_verifikasi' ? 'bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400' : 'text-[#697a8d] dark:text-[#a1b0cb] hover:bg-[#f5f5f9] dark:hover:bg-[#232333]' }}">Menunggu Verifikasi</a>
                <a href="{{ route('admin.pembayaran.index', ['status' => 'lunas']) }}" class="inline-flex h-10 items-center justify-center rounded-md px-3 text-sm font-medium transition-colors admin-table-action-btn {{ request('status') === 'lunas' ? 'bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400' : 'text-[#697a8d] dark:text-[#a1b0cb] hover:bg-[#f5f5f9] dark:hover:bg-[#232333]' }}">Lunas</a>
                <a href="{{ route('admin.pembayaran.index', ['status' => 'ditolak']) }}" class="inline-flex h-10 items-center justify-center rounded-md px-3 text-sm font-medium transition-colors admin-table-action-btn {{ request('status') === 'ditolak' ? 'bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400' : 'text-[#697a8d] dark:text-[#a1b0cb] hover:bg-[#f5f5f9] dark:hover:bg-[#232333]' }}">Ditolak</a>

                <div class="relative inline-block text-left">
                    <button id="exportDropdownBtnPembayaran" type="button" class="inline-flex h-10 items-center justify-center gap-2 rounded-md bg-green-600 px-3 text-sm font-medium text-white transition-colors hover:bg-green-700 admin-table-action-btn" aria-expanded="false">
                        <i data-lucide="download" class="w-4 h-4"></i>
                        Export
                        <i data-lucide="chevron-down" class="w-4 h-4"></i>
                    </button>

                    <div id="exportDropdownMenuPembayaran" class="absolute right-0 z-50 mt-2 hidden w-40 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none dark:bg-[#2b2c40]">
                        <div class="py-1">
                            <a href="{{ route('admin.pembayaran.export', ['type' => 'xlsx']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-[#d5d5e2] dark:hover:bg-[#232333]">Excel (.xlsx)</a>
                            <a href="{{ route('admin.pembayaran.export', ['type' => 'csv']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-[#d5d5e2] dark:hover:bg-[#232333]">CSV (.csv)</a>
                            <a href="{{ route('admin.pembayaran.export', ['type' => 'pdf']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-[#d5d5e2] dark:hover:bg-[#232333]">PDF (.pdf)</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="admin-table-responsive text-nowrap">
        <table class="table table-hover align-middle admin-table">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>No. Pendaftaran</th>
                    <th>Nama Siswa</th>
                    <th>Gelombang</th>
                    <th>Keputusan / Status Akhir</th>
                    <th>Jumlah (Rp)</th>
                    <th>Status</th>
                    <th>Verifier</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody id="paymentTableBody">
                @forelse($pembayarans as $pembayaran)
                    <tr data-payment-row>
                        <td>{{ $pembayaran->created_at->translatedFormat('d M Y') }}</td>
                        <td class="font-semibold text-[#566a7f] dark:text-[#d5d5e2]">{{ $pembayaran->pendaftaranDetail->nomor_pendaftaran ?? '-' }}</td>
                        <td class="font-medium text-[#566a7f] dark:text-[#d5d5e2]">{{ $pembayaran->pendaftaranDetail->siswa->nama ?? '-' }}</td>
                        <td>{{ $pembayaran->pendaftaranDetail->pendaftaran->gelombang ?? '-' }}</td>
                        <td><div class="text-xs">{{ ucwords(str_replace('_', ' ', $pembayaran->pendaftaranDetail?->keputusan_status ?? '-')) }}</div><div class="mt-1 text-xs text-[#a1b0cb]">{{ ucwords(str_replace('_', ' ', $pembayaran->pendaftaranDetail?->final_status ?? '-')) }}</div></td>
                        <td class="font-semibold text-[#566a7f] dark:text-[#d5d5e2]">{{ number_format($pembayaran->jumlah, 0, ',', '.') }}</td>
                        <td>
                            @if(in_array($pembayaran->status, ['pending', 'menunggu_verifikasi'], true))
                                <span class="sneat-badge bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400">Menunggu Verifikasi</span>
                            @elseif($pembayaran->status === 'lunas')
                                <span class="sneat-badge bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400">Lunas</span>
                            @else
                                <span class="sneat-badge bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400">Ditolak</span>
                            @endif
                        </td>
                        <td class="text-xs">{{ $pembayaran->verifiedBy?->name ?? '-' }}<br><span class="text-[#a1b0cb]">{{ $pembayaran->verified_at?->format('d/m/Y H:i') ?? '-' }}</span></td>
                        <td class="text-center admin-table-actions-cell">
                            <a href="{{ route('admin.verifikasi.show', $pembayaran->pendaftaranDetail->id) }}" class="inline-flex items-center gap-1 rounded-md bg-[#e7e7ff] px-3 py-1.5 text-xs font-medium text-[#696cff] transition-colors hover:bg-[#696cff] hover:text-white dark:bg-[#696cff]/20">
                                <i data-lucide="eye" class="w-4 h-4"></i> Cek & Verifikasi
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr data-payment-empty>
                        <td colspan="9" class="px-4 py-8 text-center text-[#a1b0cb]">Belum ada data.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="admin-table-footer">
        @if($pembayarans->hasPages())
        <div id="paymentPagination">
            {{ $pembayarans->links() }}
        </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var btn = document.getElementById('exportDropdownBtnPembayaran');
    var menu = document.getElementById('exportDropdownMenuPembayaran');
    if (btn && menu) {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            menu.classList.toggle('hidden');
        });
        document.addEventListener('click', function() {
            if (!menu.classList.contains('hidden')) menu.classList.add('hidden');
        });
    }

    var paymentSearch = document.getElementById('paymentLiveSearch');
    var paymentBody = document.getElementById('paymentTableBody');
    var paymentPagination = document.getElementById('paymentPagination');

    if (paymentSearch && paymentBody) {
        var paymentRows = Array.prototype.slice.call(paymentBody.querySelectorAll('[data-payment-row]'));
        var emptyRow = paymentBody.querySelector('[data-payment-empty]');
        var noResultRow = document.createElement('tr');
        noResultRow.className = 'hidden';
        noResultRow.innerHTML = '<td colspan="9" class="px-4 py-8 text-center text-[#a1b0cb]">Tidak ada data pembayaran yang cocok.</td>';
        paymentBody.appendChild(noResultRow);

        paymentSearch.addEventListener('input', function() {
            var query = this.value.trim().toLowerCase();
            var visibleCount = 0;

            paymentRows.forEach(function(row) {
                var isMatch = row.textContent.toLowerCase().indexOf(query) !== -1;
                row.classList.toggle('hidden', query.length > 0 && !isMatch);

                if (query.length === 0 || isMatch) {
                    visibleCount++;
                }
            });

            if (emptyRow) {
                emptyRow.classList.toggle('hidden', query.length > 0);
            }

            noResultRow.classList.toggle('hidden', query.length === 0 || visibleCount > 0);

            if (paymentPagination) {
                paymentPagination.classList.toggle('hidden', query.length > 0);
            }
        });
    }
});
</script>
@endsection
