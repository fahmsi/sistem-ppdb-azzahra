@extends('layouts.app')

@section('title', 'Data Induk Siswa')
@section('header_title', 'Data Induk Siswa')

@section('content')
<div class="bg-white dark:bg-[#2b2c40] rounded-lg shadow-sneat dark:shadow-sneat-dark border border-[#d9dee3] dark:border-[#434463] p-6">
    
    <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mb-6">
        <h2 class="text-xl font-heading font-bold text-[#566a7f] dark:text-[#d5d5e2]">Data Induk Siswa PPDB</h2>
        
        <div class="flex items-center gap-3 w-full sm:w-auto">
            <div class="relative w-full sm:w-auto">
                <input type="text" id="liveSearch" placeholder="Cari Nama / Orang Tua / No HP..." autocomplete="off" class="sneat-input w-full sm:w-96 !pl-10 !pr-10">
                <i data-lucide="search" class="w-5 h-5 text-[#a1b0cb] absolute left-3 top-1/2 -translate-y-1/2"></i>
                <div id="searchSpinner" class="hidden absolute right-3 top-1/2 -translate-y-1/2">
                    <svg class="animate-spin h-4 w-4 text-[#696cff]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
            </div>

            <div class="relative inline-block text-left">
                <button id="exportDropdownBtn" type="button" class="inline-flex items-center gap-2 px-3 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md transition-colors" aria-expanded="false">
                    <i data-lucide="download" class="w-4 h-4"></i>
                    Export
                    <i data-lucide="chevron-down" class="w-4 h-4"></i>
                </button>
                <div id="exportDropdownMenu" class="hidden origin-top-right absolute right-0 mt-2 w-40 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50">
                    <div class="py-1">
                        <a href="{{ route('admin.siswa.export', ['type' => 'xlsx']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Excel (.xlsx)</a>
                        <a href="{{ route('admin.siswa.export', ['type' => 'csv']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">CSV (.csv)</a>
                        <a href="{{ route('admin.siswa.export', ['type' => 'pdf']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">PDF (.pdf)</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="w-full overflow-x-auto">
        <table class="sneat-table w-full table-auto whitespace-nowrap">
            <thead>
            <tr>
                <th>No</th>
                <th>Nama Lengkap</th>
                <th>Nama Panggilan</th>
                <th>Jenis Kelamin</th>
                <th>Orang Tua</th>
                <th class="text-center">Aksi</th>
            </tr>
            </thead>
            <tbody id="siswaTableBody">
                @forelse($siswas as $siswa)
                    <tr>
                        <td>{{ $loop->iteration + $siswas->firstItem() - 1 }}</td>
                        <td class="font-medium text-[#566a7f] dark:text-[#d5d5e2]">{{ $siswa->nama }}</td>
                        <td>{{ $siswa->nama_panggilan ?? '-' }}</td>
                        <td>{{ $siswa->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                        <td>{{ $siswa->user->name }}</td>
                        <td class="text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.siswa.show', $siswa->id) }}" class="inline-flex items-center gap-1 px-3 py-1.5 bg-[#e7e7ff] dark:bg-[#696cff]/20 text-[#696cff] hover:bg-[#696cff] hover:text-white rounded-md text-xs font-medium transition-colors">
                                    <i data-lucide="eye" class="w-4 h-4"></i> Detail
                                </a>
                                @if($siswa->no_telpon)
                                <a href="https://wa.me/62{{ ltrim(preg_replace('/^0/', '', $siswa->no_telpon), '+') }}" target="_blank" class="inline-flex items-center gap-1 px-3 py-1.5 bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 hover:bg-emerald-100 dark:hover:bg-emerald-500/20 rounded-md text-xs font-medium transition-colors" title="Chat WhatsApp">
                                    <i data-lucide="message-circle" class="w-4 h-4"></i> WA
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-[#a1b0cb]">
                            Tidak ada data siswa ditemukan.
                        </td>
                    </tr>
                    @endforelse
            </tbody>
        </table>
    </div>
    
     @if($siswas->hasPages())
        <div class="px-6 py-4 border-t border-[#d9dee3] dark:border-[#434463]" id="paginationContainer">
            {{ $siswas->links() }}
        </div>
        @else
        <div class="px-6 py-4" id="paginationContainer">
            {{ $siswas->links() }}
        </div>
        @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var searchInput = document.getElementById('liveSearch');
    var tableBody = document.getElementById('siswaTableBody');
    var spinner = document.getElementById('searchSpinner');
    var pagination = document.getElementById('paginationContainer');
    var debounceTimer = null;

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            var query = this.value.trim();
            
            clearTimeout(debounceTimer);

            if (query.length === 0) {
                window.location.href = '{{ route("admin.siswa.index") }}';
                return;
            }

            if (query.length < 2) return;

            spinner.classList.remove('hidden');

            debounceTimer = setTimeout(function() {
                fetch('{{ route("admin.siswa.index") }}?search=' + encodeURIComponent(query), {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(function(r) { return r.json(); })
                .then(function(data) {
                    spinner.classList.add('hidden');
                    pagination.style.display = 'none';
                    
                    if (data.length === 0) {
                        tableBody.innerHTML = '<tr><td colspan="6" class="px-4 py-8 text-center text-[#a1b0cb]">Tidak ada data ditemukan untuk "' + query + '"</td></tr>';
                        return;
                    }

                    var html = '';
                    data.forEach(function(s, i) {
                        html += '<tr class="border-b border-[#d9dee3] dark:border-[#434463] hover:bg-[#f5f5f9]/50 dark:hover:bg-[#232333]/50 transition-colors">';
                        html += '<td class="px-5 py-3.5 text-[#697a8d] dark:text-[#a1b0cb]">' + (i + 1) + '</td>';
                        html += '<td class="px-5 py-3.5 font-medium text-[#566a7f] dark:text-[#d5d5e2]">' + (s.nama || '-') + '</td>';
                        html += '<td class="px-5 py-3.5 text-[#697a8d] dark:text-[#a1b0cb]">' + s.nama_panggilan + '</td>';
                        html += '<td class="px-5 py-3.5 text-[#697a8d] dark:text-[#a1b0cb]">' + (s.jenis_kelamin === 'L' ? 'Laki-laki' : (s.jenis_kelamin === 'P' ? 'Perempuan' : s.jenis_kelamin)) + '</td>';
                        html += '<td class="px-5 py-3.5 text-[#697a8d] dark:text-[#a1b0cb]">' + (s.orang_tua || 'Tidak Ada') + '</td>';
                        html += '<td class="px-5 py-3.5 text-center"><div class="flex items-center justify-center gap-2">';
                        
                        if (s.show_url) {
                            html += '<a href="' + s.show_url + '" class="inline-flex items-center gap-1 px-3 py-1.5 bg-[#e7e7ff] dark:bg-[#696cff]/20 text-[#696cff] hover:bg-[#696cff] hover:text-white rounded-md text-xs font-medium transition-colors"><i data-lucide="eye" class="w-4 h-4"></i> Detail</a>';
                        }
                        
                        if (s.wa_url && s.wa_url !== '#') {
                            html += '<a href="' + s.wa_url + '" target="_blank" class="inline-flex items-center gap-1 px-3 py-1.5 bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 hover:bg-emerald-100 rounded-md text-xs font-medium transition-colors"><i data-lucide="message-circle" class="w-4 h-4"></i> WA</a>';
                        }
                        
                        html += '</div></td></tr>';
                    });
                    tableBody.innerHTML = html;
                    
                    if (typeof lucide !== 'undefined') {
                        lucide.createIcons();
                    }
                })
                .catch(function() {
                    spinner.classList.add('hidden');
                });
            }, 350);
        });
    }

    // Export dropdown toggle
    var exportBtn = document.getElementById('exportDropdownBtn');
    var exportMenu = document.getElementById('exportDropdownMenu');
    if (exportBtn && exportMenu) {
        exportBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            exportMenu.classList.toggle('hidden');
        });

        document.addEventListener('click', function() {
            if (!exportMenu.classList.contains('hidden')) {
                exportMenu.classList.add('hidden');
            }
        });
    }
});
</script>
@endsection