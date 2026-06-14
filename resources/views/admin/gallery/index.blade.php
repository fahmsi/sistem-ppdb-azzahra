@extends('layouts.app')

@section('title', 'Kelola Gallery')
@section('header_title', 'Kelola Gallery')

@section('content')
<div class="space-y-6">

    <!-- Header Card -->
    <div class="bg-white dark:bg-[#2b2c40] rounded-lg shadow-sneat dark:shadow-sneat-dark border border-[#d9dee3] dark:border-[#434463] p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <h2 class="text-xl font-bold text-[#566a7f] dark:text-[#d5d5e2] flex items-center gap-2 m-0">
            <i data-lucide="images" class="w-5 h-5 text-[#696cff]"></i>
            Kelola Gallery
        </h2>
        <a href="{{ route('admin.gallery.create') }}" class="sneat-btn-primary">
            <i data-lucide="plus-circle" class="w-4 h-4"></i> Tambah Foto
        </a>
    </div>

    <!-- Gallery Table -->
    <div class="bg-white dark:bg-[#2b2c40] rounded-lg shadow-sneat dark:shadow-sneat-dark border border-[#d9dee3] dark:border-[#434463] overflow-hidden">
        <div class="w-full overflow-x-auto">
            <table class="sneat-table w-full table-auto whitespace-nowrap">
                <thead>
                    <tr>
                        <th class="w-12">#</th>
                        <th>Foto</th>
                        <th>Judul</th>
                        <th>Deskripsi</th>
                        <th>Urutan</th>
                        <th>Status</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($galleries as $index => $gallery)
                        <tr>
                            <td class="font-medium text-[#566a7f] dark:text-[#d5d5e2]">{{ $index + 1 }}</td>
                            <td>
                                <img src="{{ $gallery->image_url }}"
                                     alt="{{ $gallery->title }}"
                                     class="w-16 h-12 rounded-lg object-cover border border-[#d9dee3] dark:border-[#434463]">
                            </td>
                            <td>
                                <span class="font-medium text-[#566a7f] dark:text-[#d5d5e2]">{{ $gallery->title }}</span>
                            </td>
                            <td class="max-w-xs">
                                <p class="text-sm text-[#697a8d] dark:text-[#a1b0cb] truncate">{{ Str::limit($gallery->description, 50) ?: '-' }}</p>
                            </td>
                            <td>
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-[#f5f5f9] dark:bg-[#232333] text-sm font-semibold text-[#566a7f] dark:text-[#d5d5e2]">
                                    {{ $gallery->sort_order }}
                                </span>
                            </td>
                            <td>
                                @if($gallery->is_active)
                                    <span class="sneat-badge bg-emerald-100 dark:bg-emerald-500/20 text-emerald-700 dark:text-emerald-400">
                                        <i data-lucide="check-circle" class="w-3 h-3"></i> Aktif
                                    </span>
                                @else
                                    <span class="sneat-badge bg-gray-100 dark:bg-gray-500/20 text-gray-500 dark:text-gray-400">
                                        <i data-lucide="eye-off" class="w-3 h-3"></i> Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.gallery.edit', $gallery->id) }}"
                                       class="inline-flex items-center justify-center w-8 h-8 rounded-md bg-[#e7e7ff] dark:bg-[#696cff]/20 text-[#696cff] hover:bg-[#696cff] hover:text-white transition-colors"
                                       title="Edit">
                                        <i data-lucide="edit-2" class="w-4 h-4"></i>
                                    </a>
                                    <form action="{{ route('admin.gallery.destroy', $gallery->id) }}" method="POST" class="inline form-delete">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="inline-flex items-center justify-center w-8 h-8 rounded-md bg-red-50 dark:bg-red-500/10 text-red-500 hover:bg-red-500 hover:text-white transition-colors btn-delete"
                                                title="Hapus"
                                                data-name="{{ $gallery->title }}">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center gap-3">
                                    <div class="w-16 h-16 bg-[#f5f5f9] dark:bg-[#232333] rounded-full flex items-center justify-center">
                                        <i data-lucide="image" class="w-8 h-8 text-[#a1b0cb]"></i>
                                    </div>
                                    <h3 class="text-lg font-heading font-semibold text-[#566a7f] dark:text-[#d5d5e2]">Belum Ada Foto</h3>
                                    <p class="text-[#a1b0cb] text-sm">Silakan tambahkan foto baru untuk ditampilkan di gallery landing page.</p>
                                    <a href="{{ route('admin.gallery.create') }}" class="sneat-btn-primary mt-2">
                                        <i data-lucide="plus" class="w-5 h-5"></i> Tambah Sekarang
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
