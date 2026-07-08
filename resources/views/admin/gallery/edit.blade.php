@extends('layouts.app')
@section('title', 'Edit Foto Gallery')
@section('header_title', 'Edit Foto Gallery')
@section('content')
<div class="mx-auto w-full max-w-4xl py-2 sm:py-4">
    <div class="bg-white dark:bg-[#2b2c40] rounded-lg shadow-sneat dark:shadow-sneat-dark border border-[#d9dee3] dark:border-[#434463] overflow-hidden">
        <div class="flex flex-col gap-4 border-b border-[#d9dee3] bg-amber-50 px-5 py-5 dark:border-[#434463] dark:bg-amber-500/10 sm:flex-row sm:items-center sm:justify-between sm:px-8 sm:py-6">
            <div>
                <h2 class="text-xl font-heading font-bold text-[#566a7f] dark:text-[#d5d5e2] flex items-center gap-2">
                    <i data-lucide="edit" class="w-5 h-5 text-amber-500"></i> Edit Foto Gallery
                </h2>
                <p class="text-amber-600 dark:text-[#a1b0cb] text-sm mt-1">Perbarui foto "{{ $gallery->title }}".</p>
            </div>
            <a href="{{ route('admin.gallery.index') }}" class="flex w-fit items-center gap-1 text-sm font-medium text-[#697a8d] transition-colors hover:text-[#696cff] dark:text-[#a1b0cb]"><i data-lucide="x" class="w-4 h-4"></i> Batal</a>
        </div>
        <form action="{{ route('admin.gallery.update', $gallery->id) }}" method="POST" enctype="multipart/form-data" class="p-6 sm:p-8 space-y-6">
            @csrf
            @method('PUT')

            {{-- Judul --}}
            <div>
                <label class="block text-sm font-medium text-[#566a7f] dark:text-[#d5d5e2] mb-1">Judul Foto <span class="text-red-500">*</span></label>
                <input type="text" name="title" value="{{ old('title', $gallery->title) }}" placeholder="Contoh: Kegiatan Manasik Haji" class="sneat-input @error('title', 'updateGallery'.$gallery->id) !border-red-500 @enderror">
                @error('title', 'updateGallery'.$gallery->id) <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            {{-- Deskripsi --}}
            <div>
                <label class="block text-sm font-medium text-[#566a7f] dark:text-[#d5d5e2] mb-1">Deskripsi</label>
                <textarea name="description" rows="3" placeholder="Deskripsi singkat tentang foto ini (opsional)" class="sneat-input @error('description', 'updateGallery'.$gallery->id) !border-red-500 @enderror">{{ old('description', $gallery->description) }}</textarea>
                <p class="mt-1 text-xs text-[#a1b0cb]">Maksimal 500 karakter.</p>
                @error('description', 'updateGallery'.$gallery->id) <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            {{-- Current Image & Upload --}}
            <div>
                <label class="block text-sm font-medium text-[#566a7f] dark:text-[#d5d5e2] mb-1">Foto</label>

                {{-- Current Image --}}
                <div class="mb-3">
                    <p class="text-xs text-[#a1b0cb] mb-2">Foto saat ini:</p>
                    <img src="{{ $gallery->image_url }}" alt="{{ $gallery->title }}" class="w-full max-w-sm h-48 object-cover rounded-lg border border-[#d9dee3] dark:border-[#434463]">
                </div>

                <div class="relative">
                    <input type="file" name="image" accept="image/*" id="imageInput"
                           class="sneat-input !py-2 file:mr-4 file:py-1.5 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-[#e7e7ff] file:text-[#696cff] hover:file:bg-[#696cff] hover:file:text-white file:transition-colors file:cursor-pointer @error('image', 'updateGallery'.$gallery->id) !border-red-500 @enderror">
                </div>
                <p class="mt-1 text-xs text-[#a1b0cb]">Kosongkan jika tidak ingin mengganti foto. Format: JPG, PNG, WebP. Maksimal 5MB.</p>
                @error('image', 'updateGallery'.$gallery->id) <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror

                {{-- New Image Preview --}}
                <div id="imagePreview" class="mt-3 hidden">
                    <p class="text-xs text-[#a1b0cb] mb-2">Preview foto baru:</p>
                    <img id="previewImg" src="" alt="Preview" class="w-full max-w-sm h-48 object-cover rounded-lg border border-[#d9dee3] dark:border-[#434463]">
                </div>
            </div>

            {{-- Sort Order & Status --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-[#566a7f] dark:text-[#d5d5e2] mb-1">Urutan Tampil</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', $gallery->sort_order) }}" min="0" max="999" class="sneat-input @error('sort_order', 'updateGallery'.$gallery->id) !border-red-500 @enderror">
                    <p class="mt-1 text-xs text-[#a1b0cb]">Semakin kecil semakin atas.</p>
                    @error('sort_order', 'updateGallery'.$gallery->id) <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-[#566a7f] dark:text-[#d5d5e2] mb-1">Status</label>
                    <label class="inline-flex items-center gap-3 mt-2 cursor-pointer">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $gallery->is_active) ? 'checked' : '' }}
                               class="w-5 h-5 rounded border-[#d9dee3] text-[#696cff] focus:ring-[#696cff]">
                        <span class="text-sm text-[#566a7f] dark:text-[#d5d5e2]">Tampilkan di landing page</span>
                    </label>
                </div>
            </div>

            <div class="flex flex-col-reverse items-stretch justify-end gap-3 border-t border-[#d9dee3] pt-6 dark:border-[#434463] sm:flex-row sm:items-center">
                <a href="{{ route('admin.gallery.index') }}" class="sneat-btn-secondary justify-center">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali
                </a>
                <button type="submit" class="sneat-btn-primary justify-center"><i data-lucide="save" class="w-4 h-4"></i> Perbarui Foto</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const input = document.getElementById('imageInput');
        const preview = document.getElementById('imagePreview');
        const previewImg = document.getElementById('previewImg');

        if (input) {
            input.addEventListener('change', function () {
                if (this.files && this.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        previewImg.src = e.target.result;
                        preview.classList.remove('hidden');
                    };
                    reader.readAsDataURL(this.files[0]);
                } else {
                    preview.classList.add('hidden');
                }
            });
        }
    });
</script>
@endsection
