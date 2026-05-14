@extends('layouts.app')

@section('title', 'Pengaturan Situs')
@section('header_title', 'Pengaturan Situs')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Header -->
    <div class="animate-fade-in">
        <h2 class="text-2xl font-heading font-bold text-[#566a7f] dark:text-[#d5d5e2]">Pengaturan Situs</h2>
        <p class="text-[#a1b0cb] text-sm mt-1">Kelola konten dinamis di Landing Page: Badge Hero, Agenda Pembukaan, dan link media sosial.</p>
    </div>

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
@endsection
