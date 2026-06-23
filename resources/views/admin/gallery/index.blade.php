@extends('layouts.app')

@section('title', 'Kelola Gallery')
@section('header_title', 'Kelola Gallery')

@section('content')
<div class="admin-table-card">
    <div class="admin-table-header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="admin-table-title flex items-center gap-2">
                <i data-lucide="images" class="w-5 h-5 text-[#696cff]"></i>
                Kelola Gallery
            </h2>
            <div class="admin-table-actions">
                <a href="{{ route('admin.gallery.create') }}" class="sneat-btn-primary h-10 admin-table-action-btn">
                    <i data-lucide="plus-circle" class="w-4 h-4"></i> Tambah Foto
                </a>
            </div>
        </div>
    </div>

    <div class="admin-table-responsive text-nowrap">
            <table class="table table-hover align-middle admin-table">
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
                            <td class="text-right admin-table-actions-cell">
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
                                Belum ada data.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
    </div>
</div>
@endsection
