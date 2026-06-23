@extends('layouts.app')

@section('title', 'Data Induk Siswa')
@section('header_title', 'Data Induk Siswa')

@section('content')
<div class="admin-table-card">
    <div class="admin-table-header">
        <!-- <h2 class="admin-table-title">
            Data Induk Siswa SPMB
        </h2> -->

        <div class="admin-table-toolbar">
            <div class="admin-table-search relative">
                <input type="text" id="student-search-input" placeholder="Cari Nama / Orang Tua / No HP..." autocomplete="off"
                    class="sneat-input h-10 w-full !pl-10 !pr-10">
                <i data-lucide="search" class="absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-[#a1b0cb]"></i>

                <div id="searchSpinner" class="hidden absolute right-3 top-1/2 -translate-y-1/2">
                    <svg class="h-4 w-4 animate-spin text-[#696cff]" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
            </div>

            <div class="admin-table-actions">
                <a href="{{ route('admin.siswa.create') }}"
                    class="inline-flex h-10 items-center justify-center gap-2 rounded-md bg-[#696cff] px-4 text-sm font-medium text-white transition-colors hover:bg-[#5f61e6] admin-table-action-btn">
                    <i data-lucide="user-plus" class="h-4 w-4"></i>
                    Tambah Data Siswa
                </a>

                <a href="{{ route('admin.siswa.trash') }}"
                    class="inline-flex h-10 items-center justify-center gap-2 rounded-md bg-orange-50 px-4 text-sm font-medium text-orange-700 transition-colors hover:bg-orange-100 dark:bg-orange-500/10 dark:text-orange-300 dark:hover:bg-orange-500/20 admin-table-action-btn">
                    <i data-lucide="archive" class="h-4 w-4"></i>
                    Data Terhapus
                </a>

                <div class="relative inline-block w-full text-left sm:w-auto">
                    <button id="exportDropdownBtn" type="button" aria-expanded="false"
                        class="inline-flex h-10 w-full items-center justify-center gap-2 rounded-md bg-green-600 px-4 text-sm font-medium text-white transition-colors hover:bg-green-700 sm:w-auto admin-table-action-btn">
                        <i data-lucide="download" class="h-4 w-4"></i>
                        Export
                        <i data-lucide="chevron-down" class="h-4 w-4"></i>
                    </button>

                    <div id="exportDropdownMenu"
                        class="absolute left-0 z-50 mt-2 hidden w-40 origin-top-left rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none dark:bg-[#2b2c40] sm:left-auto sm:right-0">
                        <div class="py-1">
                            <a href="{{ route('admin.siswa.export', ['type' => 'xlsx']) }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-[#d5d5e2] dark:hover:bg-[#232333]">
                                Excel (.xlsx)
                            </a>
                            <a href="{{ route('admin.siswa.export', ['type' => 'csv']) }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-[#d5d5e2] dark:hover:bg-[#232333]">
                                CSV (.csv)
                            </a>
                            <a href="{{ route('admin.siswa.export', ['type' => 'pdf']) }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-[#d5d5e2] dark:hover:bg-[#232333]">
                                PDF (.pdf)
                            </a>
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
                    <th class="w-14">No</th>
                    <th>Nama Lengkap</th>
                    <th>Nama Panggilan</th>
                    <th>Jenis Kelamin</th>
                    <th>Orang Tua</th>
                    <th>Sumber Data</th>
                    <th class="w-[210px] text-center">Aksi</th>
                </tr>
            </thead>
            <tbody id="siswaTableBody">
                @forelse($siswas as $siswa)
                    <tr class="text-center">
                        <td>{{ $loop->iteration + $siswas->firstItem() - 1 }}</td>
                        <td class="font-medium text-[#566a7f] dark:text-[#d5d5e2]">{{ $siswa->nama }}</td>
                        <td>{{ $siswa->nama_panggilan ?? '-' }}</td>
                        <td>{{ $siswa->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                        <td>{{ $siswa->user?->name ?? '-' }}</td>
                        <td>
                            @if($siswa->input_source === \App\Models\Siswa::INPUT_SOURCE_MANUAL_ADMIN)
                                <span class="inline-flex items-center gap-1 rounded-md bg-amber-100 px-2 py-1 text-xs font-medium text-amber-700">
                                    <i data-lucide="clipboard-edit" class="w-3 h-3"></i> Manual Admin
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 rounded-md bg-blue-100 px-2 py-1 text-xs font-medium text-blue-700">
                                    <i data-lucide="globe" class="w-3 h-3"></i> Online
                                </span>
                            @endif
                        </td>
                        <td class="text-center admin-table-actions-cell">
                            <div class="flex items-center justify-center gap-1.5">
                                <a href="{{ route('admin.siswa.show', $siswa->id) }}"
                                    class="inline-flex items-center gap-1 rounded-md bg-[#e7e7ff] px-2.5 py-2.5 text-xs font-medium text-[#696cff] transition-colors hover:bg-[#696cff] hover:text-white dark:bg-[#696cff]/20" title="Lihat Detail Siswa">
                                    <i data-lucide="eye" class="h-4 w-4"></i>
                                </a>

                                @if($siswa->no_telpon)
                                    <a href="https://wa.me/62{{ ltrim(preg_replace('/^0/', '', $siswa->no_telpon), '+') }}"
                                        target="_blank" title="Chat WhatsApp"
                                        class="inline-flex items-center gap-1 rounded-md bg-emerald-50 px-2.5 py-2.5 text-xs font-medium text-emerald-600 transition-colors hover:bg-emerald-100 dark:bg-emerald-500/10 dark:text-emerald-400 dark:hover:bg-emerald-500/20" title="Chat WhatsApp">
                                        <i data-lucide="message-circle" class="h-4 w-4"></i>
                                    </a>
                                @endif

                                <form action="{{ route('admin.siswa.destroy', $siswa->id) }}" method="POST"
                                    class="inline admin-soft-delete-form"
                                    data-student-name="{{ $siswa->nama }}"
                                    data-has-history="{{ ($siswa->pendaftaran_details_count ?? 0) > 0 ? '1' : '0' }}">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="deleted_reason" value="">
                                    <button type="submit"
                                        class="inline-flex items-center gap-1 rounded-md bg-red-50 px-2.5 py-2.5 text-xs font-medium text-red-600 transition-colors hover:bg-red-100 dark:bg-red-500/10 dark:text-red-400 dark:hover:bg-red-500/20" title="Hapus Data Siswa">
                                        <i data-lucide="trash-2" class="h-4 w-4"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-[#a1b0cb]">
                            Tidak ada data siswa ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="admin-table-footer">
        @if($siswas->hasPages())
        <div id="paginationContainer">
            {{ $siswas->links() }}
        </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var searchInput = document.getElementById('student-search-input');
    var tableBody = document.getElementById('siswaTableBody');
    var spinner = document.getElementById('searchSpinner');
    var pagination = document.getElementById('paginationContainer');
    var debounceTimer = null;
    var csrfToken = '{{ csrf_token() }}';
    var initialTableHtml = tableBody ? tableBody.innerHTML : '';
    var initialPaginationDisplay = pagination ? pagination.style.display : '';
    var studentSearchFocused = false;

    function escapeHtml(value) {
        return String(value || '-')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function sourceBadge(source) {
        if (source === 'manual_admin') {
            return '<span class="inline-flex items-center gap-1 rounded-md bg-amber-100 px-2 py-1 text-xs font-medium text-amber-700"><i data-lucide="clipboard-edit" class="w-3 h-3"></i> Manual Admin</span>';
        }

        return '<span class="inline-flex items-center gap-1 rounded-md bg-blue-100 px-2 py-1 text-xs font-medium text-blue-700"><i data-lucide="globe" class="w-3 h-3"></i> Online</span>';
    }

    function bindSoftDeleteForms() {
        document.querySelectorAll('.admin-soft-delete-form').forEach(function (form) {
            if (form.dataset.bound === '1') return;
            form.dataset.bound = '1';

            form.addEventListener('submit', function (e) {
                e.preventDefault();

                var name = form.dataset.studentName || 'data siswa ini';
                var hasHistory = form.dataset.hasHistory === '1';
                var historyWarning = hasHistory
                    ? '<div class="mt-3 rounded-md bg-orange-50 p-3 text-left text-sm text-orange-700">Data siswa ini memiliki riwayat pendaftaran. Data akan disembunyikan, bukan dihapus permanen.</div>'
                    : '';

                Swal.fire({
                    title: 'Hapus sementara data siswa?',
                    html: '<p class="text-sm text-gray-600">Masukkan alasan penghapusan untuk <strong>' + escapeHtml(name) + '</strong>.</p>' + historyWarning,
                    input: 'textarea',
                    inputPlaceholder: 'Tulis alasan penghapusan...',
                    inputAttributes: {
                        'aria-label': 'Alasan penghapusan'
                    },
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#697a8d',
                    confirmButtonText: 'Hapus Sementara',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                    preConfirm: function(value) {
                        var reason = (value || '').trim();
                        if (!reason) {
                            Swal.showValidationMessage('Alasan penghapusan wajib diisi.');
                            return false;
                        }

                        return reason;
                    }
            }).then(function (result) {
                if (result.isConfirmed) {
                    form.querySelector('input[name="deleted_reason"]').value = result.value;
                    Swal.fire({
                        title: 'Memproses...',
                        text: 'Mohon tunggu sebentar.',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        didOpen: function () {
                            Swal.showLoading();
                        }
                    });
                    HTMLFormElement.prototype.submit.call(form);
                }
                });
            });
        });
    }

    function preserveSearchFocus(cursorPosition) {
        if (!studentSearchFocused || !searchInput) return;

        searchInput.focus({ preventScroll: true });

        if (typeof cursorPosition === 'number' && searchInput.setSelectionRange) {
            searchInput.setSelectionRange(cursorPosition, cursorPosition);
        }
    }

    if (searchInput) {
        searchInput.addEventListener('focus', function () {
            studentSearchFocused = true;
        });

        searchInput.addEventListener('blur', function () {
            studentSearchFocused = false;
        });

        searchInput.addEventListener('input', function () {
            var query = this.value.trim();
            var cursorPosition = this.selectionStart;

            clearTimeout(debounceTimer);

            if (query.length === 0) {
                spinner.classList.add('hidden');
                tableBody.innerHTML = initialTableHtml;
                pagination.style.display = initialPaginationDisplay;
                bindSoftDeleteForms();

                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }

                preserveSearchFocus(cursorPosition);
                return;
            }

            if (query.length < 2) {
                spinner.classList.add('hidden');
                preserveSearchFocus(cursorPosition);
                return;
            }

            spinner.classList.remove('hidden');

            debounceTimer = setTimeout(function () {
                var activeCursorPosition = searchInput.selectionStart;

                fetch('{{ route("admin.siswa.index") }}?search=' + encodeURIComponent(query), {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                    .then(function (r) {
                        return r.json();
                    })
                    .then(function (data) {
                        spinner.classList.add('hidden');
                        pagination.style.display = 'none';

                        if (data.length === 0) {
                            tableBody.innerHTML = '<tr><td colspan="7" class="px-4 py-8 text-center text-[#a1b0cb]">Tidak ada data ditemukan untuk "' + escapeHtml(query) + '"</td></tr>';
                            preserveSearchFocus(activeCursorPosition);
                            return;
                        }

                        var html = '';
                        data.forEach(function (s, i) {
                            html += '<tr>';
                            html += '<td>' + (i + 1) + '</td>';
                            html += '<td class="font-medium text-[#566a7f] dark:text-[#d5d5e2]">' + escapeHtml(s.nama) + '</td>';
                            html += '<td>' + escapeHtml(s.nama_panggilan) + '</td>';
                            html += '<td>' + escapeHtml(s.jenis_kelamin) + '</td>';
                            html += '<td>' + escapeHtml(s.orang_tua) + '</td>';
                            html += '<td>' + sourceBadge(s.input_source) + '</td>';
                            html += '<td class="text-center admin-table-actions-cell"><div class="flex items-center justify-center gap-1.5">';

                            if (s.show_url) {
                                html += '<a href="' + escapeHtml(s.show_url) + '" class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-[#e7e7ff] dark:bg-[#696cff]/20 text-[#696cff] hover:bg-[#696cff] hover:text-white rounded-md text-xs font-medium transition-colors"><i data-lucide="eye" class="w-4 h-4"></i> Detail</a>';
                            }

                            if (s.wa_url && s.wa_url !== '#') {
                                html += '<a href="' + escapeHtml(s.wa_url) + '" target="_blank" class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 hover:bg-emerald-100 rounded-md text-xs font-medium transition-colors"><i data-lucide="message-circle" class="w-4 h-4"></i> WA</a>';
                            }

                            if (s.delete_url) {
                                html += '<form action="' + escapeHtml(s.delete_url) + '" method="POST" class="inline admin-soft-delete-form" data-student-name="' + escapeHtml(s.nama) + '" data-has-history="' + (s.has_history ? '1' : '0') + '">';
                                html += '<input type="hidden" name="_token" value="' + csrfToken + '">';
                                html += '<input type="hidden" name="_method" value="DELETE">';
                                html += '<input type="hidden" name="deleted_reason" value="">';
                                html += '<button type="submit" class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-500/20 rounded-md text-xs font-medium transition-colors"><i data-lucide="trash-2" class="w-4 h-4"></i> Hapus</button>';
                                html += '</form>';
                            }

                            html += '</div></td></tr>';
                        });
                        tableBody.innerHTML = html;
                        bindSoftDeleteForms();

                        if (typeof lucide !== 'undefined') {
                            lucide.createIcons();
                        }

                        preserveSearchFocus(activeCursorPosition);
                    })
                    .catch(function () {
                        spinner.classList.add('hidden');
                        preserveSearchFocus(activeCursorPosition);
                    });
            }, 350);
        });
    }

    bindSoftDeleteForms();

    // Export dropdown toggle
    var exportBtn = document.getElementById('exportDropdownBtn');
    var exportMenu = document.getElementById('exportDropdownMenu');
    if (exportBtn && exportMenu) {
        exportBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            exportMenu.classList.toggle('hidden');
        });

        document.addEventListener('click', function () {
            if (!exportMenu.classList.contains('hidden')) {
                exportMenu.classList.add('hidden');
            }
        });
    }
});
</script>
@endsection
