@extends('layouts.app')

@section('title', 'Kelola Testimoni')
@section('header_title', 'Kelola Testimoni')

@section('content')
<div class="admin-table-card">
    <div class="admin-table-header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="admin-table-title flex items-center gap-2">
                <i data-lucide="message-square-quote" class="w-5 h-5 text-[#696cff]"></i>
                Kelola Testimoni
            </h2>
            <div class="admin-table-actions">
                <a href="{{ route('admin.testimonials.create') }}" class="sneat-btn-primary h-10 admin-table-action-btn">
                    <i data-lucide="plus-circle" class="w-4 h-4"></i> Tambah Testimoni
                </a>
            </div>
        </div>
    </div>

    <div class="admin-table-responsive text-nowrap">
            <table class="table table-hover align-middle admin-table">
                <thead>
                    <tr>
                        <th class="w-12">#</th>
                        <th>Nama</th>
                        <th>Rating</th>
                        <th>Isi Testimoni</th>
                        <th>Tanggal</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($testimonials as $index => $testimonial)
                        <tr>
                            <td class="font-medium text-[#566a7f] dark:text-[#d5d5e2]">{{ $index + 1 }}</td>
                            <td>
                                <div class="flex items-center gap-3">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($testimonial->name) }}&background=random&size=36&font-size=0.4&bold=true"
                                         alt="{{ $testimonial->name }}"
                                         class="w-9 h-9 rounded-full flex-shrink-0">
                                    <span class="font-medium text-[#566a7f] dark:text-[#d5d5e2]">{{ $testimonial->name }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="flex items-center gap-0.5">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $testimonial->rating)
                                            <i data-lucide="star" class="w-4 h-4 text-amber-400 fill-amber-400"></i>
                                        @else
                                            <i data-lucide="star" class="w-4 h-4 text-gray-300 dark:text-[#434463]"></i>
                                        @endif
                                    @endfor
                                </div>
                            </td>
                            <td class="max-w-xs">
                                <p class="text-sm text-[#697a8d] dark:text-[#a1b0cb] truncate">{{ Str::limit($testimonial->content, 60) }}</p>
                            </td>
                            <td class="whitespace-nowrap text-sm">
                                {{ $testimonial->created_at->translatedFormat('d M Y') }}
                            </td>
                            <td class="text-right admin-table-actions-cell">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.testimonials.edit', $testimonial->id) }}"
                                       class="inline-flex items-center justify-center w-8 h-8 rounded-md bg-[#e7e7ff] dark:bg-[#696cff]/20 text-[#696cff] hover:bg-[#696cff] hover:text-white transition-colors"
                                       title="Edit">
                                        <i data-lucide="edit-2" class="w-4 h-4"></i>
                                    </a>
                                    <form action="{{ route('admin.testimonials.destroy', $testimonial->id) }}" method="POST" class="inline form-delete">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="inline-flex items-center justify-center w-8 h-8 rounded-md bg-red-50 dark:bg-red-500/10 text-red-500 hover:bg-red-500 hover:text-white transition-colors btn-delete"
                                                title="Hapus"
                                                data-name="{{ $testimonial->name }}">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                Belum ada data.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
    </div>
</div>
@endsection
