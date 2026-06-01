@extends('layouts.app')
@section('title', 'Edit Testimoni')
@section('header_title', 'Edit Testimoni')
@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white dark:bg-[#2b2c40] rounded-lg shadow-sneat dark:shadow-sneat-dark border border-[#d9dee3] dark:border-[#434463] overflow-hidden">
        <div class="bg-amber-50 dark:bg-amber-500/10 border-b border-[#d9dee3] dark:border-[#434463] px-6 py-6 flex items-center justify-between">
            <div>
                <h2 class="text-xl font-heading font-bold text-[#566a7f] dark:text-[#d5d5e2] flex items-center gap-2">
                    <i data-lucide="edit" class="w-5 h-5 text-amber-500"></i> Edit Testimoni
                </h2>
                <p class="text-amber-600 dark:text-[#a1b0cb] text-sm mt-1">Perbarui data testimoni dari "{{ $testimonial->name }}".</p>
            </div>
            <a href="{{ route('admin.testimonials.index') }}" class="text-sm font-medium text-[#697a8d] dark:text-[#a1b0cb] hover:text-[#696cff] transition-colors flex items-center gap-1"><i data-lucide="x" class="w-4 h-4"></i> Batal</a>
        </div>
        <form action="{{ route('admin.testimonials.update', $testimonial->id) }}" method="POST" class="p-6 sm:p-8 space-y-6">
            @csrf
            @method('PUT')

            {{-- Nama --}}
            <div>
                <label class="block text-sm font-medium text-[#566a7f] dark:text-[#d5d5e2] mb-1">Nama Reviewer <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $testimonial->name) }}" placeholder="Contoh: Ibu Siti Rahmah" class="sneat-input @error('name') !border-red-500 @enderror">
                @error('name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            {{-- Rating --}}
            <div>
                <label class="block text-sm font-medium text-[#566a7f] dark:text-[#d5d5e2] mb-2">Rating <span class="text-red-500">*</span></label>
                <div class="flex items-center gap-1" id="star-rating">
                    @for($i = 1; $i <= 5; $i++)
                        <label class="cursor-pointer group">
                            <input type="radio" name="rating" value="{{ $i }}" class="sr-only peer" {{ old('rating', $testimonial->rating) == $i ? 'checked' : '' }}>
                            <i data-lucide="star"
                               class="w-8 h-8 transition-all duration-200 star-icon
                                      {{ old('rating', $testimonial->rating) >= $i ? 'text-amber-400 fill-amber-400' : 'text-gray-300 dark:text-[#434463]' }}
                                      hover:text-amber-400 hover:fill-amber-400 hover:scale-110">
                            </i>
                        </label>
                    @endfor
                    <span class="ml-3 text-sm font-medium text-[#566a7f] dark:text-[#d5d5e2]" id="rating-label">{{ old('rating', $testimonial->rating) }} dari 5 bintang</span>
                </div>
                @error('rating') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            {{-- Content --}}
            <div>
                <label class="block text-sm font-medium text-[#566a7f] dark:text-[#d5d5e2] mb-1">Isi Testimoni <span class="text-red-500">*</span></label>
                <textarea name="content" rows="5" placeholder="Tuliskan isi testimoni..." class="sneat-input @error('content') !border-red-500 @enderror">{{ old('content', $testimonial->content) }}</textarea>
                <p class="mt-1 text-xs text-[#a1b0cb]">Maksimal 1000 karakter.</p>
                @error('content') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <div class="pt-6 border-t border-[#d9dee3] dark:border-[#434463] flex items-center justify-end gap-3">
                <a href="{{ route('admin.testimonials.index') }}" class="sneat-btn-secondary">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali
                </a>
                <button type="submit" class="sneat-btn-primary"><i data-lucide="save" class="w-4 h-4"></i> Perbarui Testimoni</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const container = document.getElementById('star-rating');
        const radios = container.querySelectorAll('input[type="radio"]');
        const icons = container.querySelectorAll('.star-icon');
        const label = document.getElementById('rating-label');

        function updateStars(value) {
            icons.forEach(function (icon, index) {
                if (index < value) {
                    icon.classList.add('text-amber-400', 'fill-amber-400');
                    icon.classList.remove('text-gray-300', 'dark:text-[#434463]');
                } else {
                    icon.classList.remove('text-amber-400', 'fill-amber-400');
                    icon.classList.add('text-gray-300', 'dark:text-[#434463]');
                }
            });
            label.textContent = value + ' dari 5 bintang';
        }

        radios.forEach(function (radio) {
            radio.addEventListener('change', function () {
                updateStars(parseInt(this.value));
            });
        });

        icons.forEach(function (icon, index) {
            icon.addEventListener('click', function () {
                radios[index].checked = true;
                radios[index].dispatchEvent(new Event('change'));
            });
        });
    });
</script>
@endsection
