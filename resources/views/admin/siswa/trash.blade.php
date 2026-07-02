@extends('layouts.app')

@section('title', 'Data Siswa Terhapus')
@section('header_title', 'Data Siswa Terhapus')

@section('content')
<div class="bg-white dark:bg-[#2b2c40] rounded-lg shadow-sneat dark:shadow-sneat-dark border border-[#d9dee3] dark:border-[#434463] p-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h2 class="text-xl font-heading font-bold text-[#566a7f] dark:text-[#d5d5e2]">Data Terhapus</h2>
            <p class="text-sm text-[#697a8d] dark:text-[#a1b0cb] mt-1">Data siswa yang dihapus sementara masih bisa dipulihkan oleh super admin.</p>
        </div>
        <a href="{{ route('admin.siswa.index') }}" class="inline-flex items-center gap-2 px-3 py-2 bg-[#e7e7ff] dark:bg-[#696cff]/20 text-[#696cff] hover:bg-[#696cff] hover:text-white text-sm font-medium rounded-md transition-colors">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            Kembali ke Data Siswa
        </a>
    </div>

    <div class="w-full overflow-x-auto">
        <table class="sneat-table w-full table-auto whitespace-nowrap">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Siswa</th>
                    <th>NISN</th>
                    <th>Orang Tua</th>
                    <th>Sumber Data</th>
                    <th>Dihapus Oleh</th>
                    <th>Alasan Hapus</th>
                    <th>Tanggal Dihapus</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($siswas as $siswa)
                    <tr>
                        <td>{{ $loop->iteration + $siswas->firstItem() - 1 }}</td>
                        <td class="font-medium text-[#566a7f] dark:text-[#d5d5e2]">{{ $siswa->nama }}</td>
                        <td>{{ $siswa->nisn ?? '-' }}</td>
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
                        <td>{{ $siswa->deletedBy?->name ?? '-' }}</td>
                        <td class="max-w-xs whitespace-normal">{{ $siswa->deleted_reason ?? '-' }}</td>
                        <td>{{ $siswa->deleted_at ? $siswa->deleted_at->translatedFormat('d M Y, H:i').' WIB' : '-' }}</td>
                        <td class="text-center">
                            @if(auth()->user()->isSuperAdmin())
                                <div class="flex items-center justify-center gap-2">
                                    <form action="{{ route('admin.siswa.restore', $siswa->id) }}" method="POST" class="form-restore" data-student-name="{{ $siswa->nama }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 hover:bg-emerald-100 dark:hover:bg-emerald-500/20 rounded-md text-xs font-medium transition-colors">
                                            <i data-lucide="rotate-ccw" class="w-4 h-4"></i> Pulihkan
                                        </button>
                                    </form>

                                    <form action="{{ route('admin.siswa.force-delete', $siswa->id) }}" method="POST" class="form-delete" data-confirm-title="Hapus permanen data siswa?" data-confirm-text="Data akan dihapus permanen dan tidak dapat dikembalikan." data-confirm-button="Ya, Hapus Permanen">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-500/20 rounded-md text-xs font-medium transition-colors">
                                            <i data-lucide="trash" class="w-4 h-4"></i> Hapus Permanen
                                        </button>
                                    </form>
                                </div>
                                @if(($siswa->pendaftaran_details_count ?? 0) > 0)
                                    <p class="mt-2 text-xs text-orange-600">Memiliki riwayat pendaftaran, hard delete akan ditolak.</p>
                                @endif
                            @else
                                <span class="text-xs text-[#a1b0cb]">Tidak tersedia</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="px-4 py-8 text-center text-[#a1b0cb]">
                            Belum ada data siswa terhapus.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="px-6 py-4 border-t border-[#d9dee3] dark:border-[#434463]">
        {{ $siswas->links() }}
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.form-restore').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            Swal.fire({
                title: 'Pulihkan data siswa?',
                text: 'Data akan muncul kembali di daftar siswa aktif.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#697a8d',
                confirmButtonText: 'Pulihkan',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then(function(result) {
                if (result.isConfirmed) {
                    HTMLFormElement.prototype.submit.call(form);
                }
            });
        });
    });
});
</script>
@endsection
