@php
    $editing = isset($achievement);
    $formErrors = $errors->getBag($errorBag);
    $useOldInput = $formErrors->any();
@endphp

<div class="grid gap-5 md:grid-cols-2">
    <div class="md:col-span-2">
        <label class="mb-1.5 block text-sm font-semibold text-[#566a7f] dark:text-[#d5d5e2]">
            Judul Prestasi <span class="text-red-500">*</span>
        </label>
        <input type="text" name="title" value="{{ $useOldInput ? old('title', $achievement->title ?? '') : ($achievement->title ?? '') }}"
            placeholder="Contoh: Juara Hafalan Al-Qur'an"
            class="sneat-input {{ $formErrors->has('title') ? '!border-red-500' : '' }}">
        @if($formErrors->has('title'))
            <p class="mt-1 text-xs text-red-500">{{ $formErrors->first('title') }}</p>
        @endif
    </div>

    <div>
        <label class="mb-1.5 block text-sm font-semibold text-[#566a7f] dark:text-[#d5d5e2]">
            Tingkat / Cakupan <span class="text-red-500">*</span>
        </label>
        <input type="text" name="level" value="{{ $useOldInput ? old('level', $achievement->level ?? '') : ($achievement->level ?? '') }}"
            placeholder="Contoh: Kota Depok"
            class="sneat-input {{ $formErrors->has('level') ? '!border-red-500' : '' }}">
        @if($formErrors->has('level'))
            <p class="mt-1 text-xs text-red-500">{{ $formErrors->first('level') }}</p>
        @endif
    </div>

    <div class="grid grid-cols-2 gap-3">
        <div>
            <label class="mb-1.5 block text-sm font-semibold text-[#566a7f] dark:text-[#d5d5e2]">Tahun</label>
            <input type="number" name="achievement_year" min="2000" max="2100"
                value="{{ $useOldInput ? old('achievement_year', $achievement->achievement_year ?? now()->year) : ($achievement->achievement_year ?? now()->year) }}"
                class="sneat-input {{ $formErrors->has('achievement_year') ? '!border-red-500' : '' }}">
        </div>
        <div>
            <label class="mb-1.5 block text-sm font-semibold text-[#566a7f] dark:text-[#d5d5e2]">Urutan</label>
            <input type="number" name="sort_order" min="0" max="999"
                value="{{ $useOldInput ? old('sort_order', $achievement->sort_order ?? 0) : ($achievement->sort_order ?? 0) }}"
                class="sneat-input {{ $formErrors->has('sort_order') ? '!border-red-500' : '' }}">
        </div>
    </div>

    <div class="md:col-span-2">
        <label class="mb-1.5 block text-sm font-semibold text-[#566a7f] dark:text-[#d5d5e2]">Deskripsi Singkat</label>
        <textarea name="description" rows="3" maxlength="500"
            placeholder="Ceritakan singkat pencapaian siswa..."
            class="sneat-input {{ $formErrors->has('description') ? '!border-red-500' : '' }}">{{ $useOldInput ? old('description', $achievement->description ?? '') : ($achievement->description ?? '') }}</textarea>
        <p class="mt-1 text-xs text-[#a1b0cb]">Maksimal 500 karakter.</p>
        @if($formErrors->has('description'))
            <p class="mt-1 text-xs text-red-500">{{ $formErrors->first('description') }}</p>
        @endif
    </div>

    <div class="md:col-span-2">
        <label class="mb-1.5 block text-sm font-semibold text-[#566a7f] dark:text-[#d5d5e2]">
            Foto Prestasi @unless($editing)<span class="text-red-500">*</span>@endunless
        </label>
        <input type="file" name="image" accept="image/jpeg,image/png,image/webp"
            class="block w-full rounded-md border border-[#d9dee3] bg-white text-sm text-[#697a8d] file:mr-4 file:border-0 file:bg-[#e7e7ff] file:px-4 file:py-2.5 file:font-semibold file:text-[#696cff] hover:file:bg-[#d4d5ff] dark:border-[#434463] dark:bg-[#232333] dark:text-[#a1b0cb] {{ $formErrors->has('image') ? '!border-red-500' : '' }}">
        <p class="mt-1 text-xs text-[#a1b0cb]">JPG, PNG, atau WebP. Maksimal 3 MB. Rasio landscape disarankan.</p>
        @if($formErrors->has('image'))
            <p class="mt-1 text-xs text-red-500">{{ $formErrors->first('image') }}</p>
        @endif
    </div>

    <label class="md:col-span-2 flex cursor-pointer items-center gap-3 rounded-lg border border-[#d9dee3] bg-[#f5f5f9] px-4 py-3 dark:border-[#434463] dark:bg-[#232333]">
        <input type="hidden" name="is_active" value="0">
        <input type="checkbox" name="is_active" value="1"
            class="rounded border-[#d9dee3] text-[#696cff] focus:ring-[#696cff]"
            @checked($useOldInput ? old('is_active', $achievement->is_active ?? true) : ($achievement->is_active ?? true))>
        <span>
            <span class="block text-sm font-semibold text-[#566a7f] dark:text-[#d5d5e2]">Tampilkan di landing page</span>
            <span class="block text-xs text-[#a1b0cb]">Nonaktifkan jika prestasi ingin disimpan tanpa ditampilkan.</span>
        </span>
    </label>
</div>
