@extends('layouts.app')

@section('title', 'Pengaturan Situs')
@section('header_title', 'Pengaturan Situs')

@section('content')
<div class="space-y-6">

    <!-- Header Card -->
    <div class="bg-white dark:bg-[#2b2c40] rounded-lg shadow-sneat dark:shadow-sneat-dark border border-[#d9dee3] dark:border-[#434463] p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <h2 class="text-xl font-bold text-[#566a7f] dark:text-[#d5d5e2] flex items-center gap-2 m-0">
            Pengaturan Situs
        </h2>
    </div>

    <!-- Form Container (max-w-4xl) -->
    <div class="space-y-6">
        <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-6">
            @csrf
            @method('PUT')

            @foreach($settings as $group => $items)
                <div class="bg-white dark:bg-[#2b2c40] rounded-lg shadow-sneat dark:shadow-sneat-dark border border-[#d9dee3] dark:border-[#434463] overflow-hidden animate-fade-up">
                    <div class="px-6 py-4 border-b border-[#d9dee3] dark:border-[#434463] bg-[#f5f5f9] dark:bg-[#232333]">
                        <h3 class="text-base font-heading font-semibold text-[#566a7f] dark:text-[#d5d5e2] capitalize flex items-center gap-2">
                            @if($group === 'hero')
                                <i data-lucide="star" class="w-4 h-4 text-amber-500"></i>
                            @elseif($group === 'agenda')
                                <i data-lucide="calendar-days" class="w-4 h-4 text-blue-500"></i>
                            @elseif($group === 'footer')
                                <i data-lucide="share-2" class="w-4 h-4 text-purple-500"></i>
                            @else
                                <i data-lucide="settings" class="w-4 h-4 text-[#a1b0cb]"></i>
                            @endif
                            {{ ucfirst($group) }}
                        </h3>
                    </div>
                    <div class="p-6 space-y-5">
                        @foreach($items as $setting)
                            <div>
                                <label for="setting_{{ $setting->key }}" class="block text-sm font-semibold text-[#566a7f] dark:text-[#d5d5e2] mb-1.5">
                                    {{ $setting->label }}
                                </label>
                                @if($setting->type === 'textarea')
                                    <textarea id="setting_{{ $setting->key }}" name="settings[{{ $setting->key }}]" rows="3"
                                        class="sneat-input">{{ old("settings.{$setting->key}", $setting->value) }}</textarea>
                                @else
                                    <input type="text" id="setting_{{ $setting->key }}" name="settings[{{ $setting->key }}]" value="{{ old("settings.{$setting->key}", $setting->value) }}"
                                        class="sneat-input">
                                @endif
                                <p class="text-xs text-[#a1b0cb] mt-1">Key: <code class="bg-[#f5f5f9] dark:bg-[#232333] px-1 py-0.5 rounded text-[#697a8d] dark:text-[#a1b0cb]">{{ $setting->key }}</code></p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach

            <div class="flex items-center gap-3">
                <button type="submit" class="sneat-btn-primary">
                    <i data-lucide="save" class="w-4 h-4"></i>
                    Simpan Pengaturan
                </button>
            </div>
        </form>
    </div>

    <section id="prestasi" class="space-y-6 scroll-mt-24">
        <div class="flex flex-col gap-4 rounded-lg border border-[#d9dee3] bg-white p-5 shadow-sneat dark:border-[#434463] dark:bg-[#2b2c40] dark:shadow-sneat-dark sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="flex items-center gap-2 text-xl font-bold text-[#566a7f] dark:text-[#d5d5e2]">
                    <i data-lucide="trophy" class="h-5 w-5 text-amber-500"></i>
                    Prestasi Siswa
                </h2>
                <p class="mt-1 text-sm text-[#697a8d] dark:text-[#a1b0cb]">Tambah, edit, urutkan, atau sembunyikan prestasi yang tampil di landing page.</p>
            </div>
            <span class="inline-flex w-fit items-center gap-2 rounded-full bg-[#e7e7ff] px-3 py-1.5 text-xs font-bold text-[#696cff] dark:bg-[#696cff]/20 dark:text-[#b0b1ff]">
                <i data-lucide="award" class="h-3.5 w-3.5"></i>
                {{ $achievements->count() }} Prestasi
            </span>
        </div>

        <details class="overflow-hidden rounded-lg border border-[#d9dee3] bg-white shadow-sneat dark:border-[#434463] dark:bg-[#2b2c40] dark:shadow-sneat-dark" {{ $errors->getBag('createAchievement')->any() ? 'open' : '' }}>
            <summary class="flex cursor-pointer list-none items-center justify-between gap-3 bg-[#f5f5f9] px-6 py-4 dark:bg-[#232333]">
                <span class="flex items-center gap-2 font-semibold text-[#566a7f] dark:text-[#d5d5e2]">
                    <i data-lucide="circle-plus" class="h-5 w-5 text-[#696cff]"></i>
                    Tambah Prestasi Baru
                </span>
                <i data-lucide="chevron-down" class="h-4 w-4 text-[#a1b0cb]"></i>
            </summary>
            <form method="POST" action="{{ route('admin.settings.achievements.store') }}" enctype="multipart/form-data" class="space-y-6 p-6">
                @csrf
                @include('admin.settings._achievement-fields', ['errorBag' => 'createAchievement'])
                <div class="flex justify-end border-t border-[#d9dee3] pt-5 dark:border-[#434463]">
                    <button type="submit" class="sneat-btn-primary">
                        <i data-lucide="plus" class="h-4 w-4"></i>
                        Tambah Prestasi
                    </button>
                </div>
            </form>
        </details>

        <div class="grid gap-5 xl:grid-cols-2">
            @forelse($achievements as $achievement)
                @php($updateBag = 'updateAchievement'.$achievement->id)
                <article class="overflow-hidden rounded-lg border border-[#d9dee3] bg-white shadow-sneat dark:border-[#434463] dark:bg-[#2b2c40] dark:shadow-sneat-dark">
                    <div class="grid sm:grid-cols-[160px_1fr]">
                        <div class="relative h-44 bg-[#f5f5f9] sm:h-full">
                            <img src="{{ $achievement->image_url }}" alt="{{ $achievement->title }}" class="h-full w-full object-cover">
                            <span class="absolute left-3 top-3 rounded-full px-2.5 py-1 text-[10px] font-bold {{ $achievement->is_active ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/15 dark:text-emerald-300' : 'bg-gray-200 text-gray-600 dark:bg-gray-500/20 dark:text-gray-300' }}">
                                {{ $achievement->is_active ? 'Tampil' : 'Disembunyikan' }}
                            </span>
                        </div>
                        <div class="flex flex-col justify-between p-5">
                            <div>
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <p class="text-xs font-bold uppercase tracking-wider text-[#696cff]">{{ $achievement->level }}</p>
                                        <h3 class="mt-1 font-heading text-lg font-bold text-[#566a7f] dark:text-[#d5d5e2]">{{ $achievement->title }}</h3>
                                    </div>
                                    <span class="rounded-md bg-[#f5f5f9] px-2 py-1 text-xs font-semibold text-[#697a8d] dark:bg-[#232333] dark:text-[#a1b0cb]">#{{ $achievement->sort_order }}</span>
                                </div>
                                @if($achievement->description)
                                    <p class="mt-2 line-clamp-2 text-sm text-[#697a8d] dark:text-[#a1b0cb]">{{ $achievement->description }}</p>
                                @endif
                            </div>
                            <div class="mt-4 flex items-center justify-between gap-3">
                                <span class="text-xs font-medium text-[#a1b0cb]">Tahun {{ $achievement->achievement_year ?? '-' }}</span>
                                <div class="flex items-center gap-2">
                                    <button type="button" data-achievement-toggle="achievement-edit-{{ $achievement->id }}" class="sneat-btn-secondary !px-3 !py-1.5">
                                        <i data-lucide="pencil" class="h-3.5 w-3.5"></i>
                                        Edit
                                    </button>
                                    <form method="POST" action="{{ route('admin.settings.achievements.destroy', $achievement) }}"
                                        class="form-delete"
                                        data-confirm-title="Hapus Prestasi?"
                                        data-confirm-text="Prestasi {{ $achievement->title }} akan dihapus permanen dari landing page."
                                        data-confirm-button="Ya, Hapus Prestasi">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center gap-1.5 rounded-md bg-red-50 px-3 py-1.5 text-xs font-semibold text-red-600 transition-colors hover:bg-red-100 dark:bg-red-500/10 dark:text-red-400">
                                            <i data-lucide="trash-2" class="h-3.5 w-3.5"></i>
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="achievement-edit-{{ $achievement->id }}" class="{{ $errors->getBag($updateBag)->any() ? '' : 'hidden' }} border-t border-[#d9dee3] dark:border-[#434463]">
                        <form method="POST" action="{{ route('admin.settings.achievements.update', $achievement) }}" enctype="multipart/form-data" class="space-y-6 p-6">
                            @csrf
                            @method('PUT')
                            @include('admin.settings._achievement-fields', ['achievement' => $achievement, 'errorBag' => $updateBag])
                            <div class="flex justify-end border-t border-[#d9dee3] pt-5 dark:border-[#434463]">
                                <button type="submit" class="sneat-btn-primary">
                                    <i data-lucide="save" class="h-4 w-4"></i>
                                    Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </article>
            @empty
                <div class="rounded-lg border border-dashed border-[#d9dee3] bg-white p-10 text-center dark:border-[#434463] dark:bg-[#2b2c40] xl:col-span-2">
                    <i data-lucide="trophy" class="mx-auto h-10 w-10 text-[#a1b0cb]"></i>
                    <h3 class="mt-3 font-semibold text-[#566a7f] dark:text-[#d5d5e2]">Belum ada prestasi</h3>
                    <p class="mt-1 text-sm text-[#a1b0cb]">Buka formulir di atas untuk menambahkan prestasi pertama.</p>
                </div>
            @endforelse
        </div>
    </section>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('[data-achievement-toggle]').forEach(function (button) {
            button.addEventListener('click', function () {
                document.getElementById(button.dataset.achievementToggle)?.classList.toggle('hidden');
            });
        });

        @if($errors->getBag('createAchievement')->any())
            Swal.fire({
                icon: 'error',
                title: 'Prestasi Belum Tersimpan',
                html: 'Periksa kembali kolom yang ditandai pada formulir tambah prestasi.',
                confirmButtonColor: '#696cff',
                confirmButtonText: 'Periksa Form'
            });
        @elseif(collect($achievements)->contains(fn ($achievement) => $errors->getBag('updateAchievement'.$achievement->id)->any()))
            Swal.fire({
                icon: 'error',
                title: 'Perubahan Belum Tersimpan',
                html: 'Periksa kembali kolom yang ditandai pada formulir edit prestasi.',
                confirmButtonColor: '#696cff',
                confirmButtonText: 'Periksa Form'
            });
        @endif
    });
</script>
@endsection
