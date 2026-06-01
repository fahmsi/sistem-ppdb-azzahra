@extends('layouts.app')

@section('title', 'Kelola Testimoni')
@section('header_title', 'Kelola Testimoni')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    <!-- Breadcrumb -->
    <nav class="text-sm text-[#a1b0cb] dark:text-[#7e8ba1] font-medium tracking-wide mb-6">
        Dashboard <span class="mx-1">&gt;</span> Admin <span class="mx-1">&gt;</span> <span class="text-[#697a8d] dark:text-[#b4bdc6] font-semibold">Kelola Testimoni</span>
    </nav>

    <!-- Header Card -->
    <div class="bg-white dark:bg-[#2b2c40] rounded-lg shadow-sneat dark:shadow-sneat-dark border border-[#d9dee3] dark:border-[#434463] p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <h2 class="text-xl font-bold text-[#566a7f] dark:text-[#d5d5e2] flex items-center gap-2 m-0">
            Kelola Testimoni
        </h2>
        <a href="{{ route('admin.testimonials.create') }}" class="sneat-btn-primary">
            <i data-lucide="plus-circle" class="w-4 h-4"></i> Tambah Testimoni
        </a>
    </div>

    <!-- Testimonials Table -->
    <div class="bg-white dark:bg-[#2b2c40] rounded-lg shadow-sneat dark:shadow-sneat-dark border border-[#d9dee3] dark:border-[#434463] overflow-hidden">
        <div class="w-full overflow-x-auto">
            <table class="sneat-table w-full table-auto whitespace-nowrap">
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
                                {{ $testimonial->created_at->format('d M Y') }}
                            </td>
                            <td class="text-right whitespace-nowrap">
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
                                <div class="flex flex-col items-center justify-center gap-3">
                                    <div class="w-16 h-16 bg-[#f5f5f9] dark:bg-[#232333] rounded-full flex items-center justify-center">
                                        <i data-lucide="message-circle" class="w-8 h-8 text-[#a1b0cb]"></i>
                                    </div>
                                    <h3 class="text-lg font-heading font-semibold text-[#566a7f] dark:text-[#d5d5e2]">Belum Ada Testimoni</h3>
                                    <p class="text-[#a1b0cb] text-sm">Silakan tambahkan testimoni baru untuk ditampilkan di halaman landing page.</p>
                                    <a href="{{ route('admin.testimonials.create') }}" class="sneat-btn-primary mt-2">
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
