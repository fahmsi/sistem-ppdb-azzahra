@extends('layouts.app')

@section('title', 'Dashboard Wali Murid')
@section('header_title', 'Dashboard')

@section('content')
@php
    $adminWhatsapp = preg_replace('/[^0-9]/', '', (string) config('spmb.admin_whatsapp', ''));
    $helpWaMessage = "Assalamu'alaikum Admin PAUD Az-Zahra, saya ingin menanyakan proses pendaftaran anak saya.";
    $helpWaUrl = $adminWhatsapp ? 'https://wa.me/'.$adminWhatsapp.'?text='.urlencode($helpWaMessage) : null;
    $selectedSiswa = $progress['siswa'];
    $statusCard = $progress['status_card'];
@endphp

<div class="parent-dashboard-page w-full space-y-5 sm:space-y-6">
    <header class="relative overflow-hidden rounded-xl bg-gradient-to-r from-[#696cff] to-[#7b7dff] p-5 text-white shadow-sneat-lg sm:p-7">
        <div class="absolute -right-12 -top-24 h-64 w-64 rounded-full bg-white/10 blur-2xl"></div>
        <div class="absolute -bottom-16 right-40 h-40 w-40 rounded-full bg-white/10 blur-xl"></div>
        <div class="relative z-10">
            <h2 class="break-words font-heading text-xl font-bold leading-tight sm:text-3xl">
                Assalamu'alaikum, {{ str(auth()->user()->name)->before(' ') }}!
            </h2>
            <p class="mt-2 max-w-3xl text-sm leading-6 text-white/85 sm:text-base">
                Selamat datang di panel orang tua. Lengkapi data anak, daftar gelombang SPMB, dan pantau status pendaftaran dari satu tempat.
            </p>
        </div>
    </header>

    <div class="grid items-start gap-5 lg:grid-cols-[minmax(0,2fr)_minmax(280px,0.85fr)] xl:gap-6">
        <div class="min-w-0 space-y-5">
        <section id="panduan-pendaftaran" aria-labelledby="panduan-title" class="min-w-0 rounded-xl border border-[#d9dee3] bg-white p-4 shadow-sneat dark:border-[#434463] dark:bg-[#2b2c40] dark:shadow-sneat-dark sm:p-6">
            <div class="flex flex-col gap-4 border-b border-[#eceef1] pb-5 dark:border-[#434463] sm:flex-row sm:items-start sm:justify-between">
                <div class="min-w-0">
                    <div class="flex items-center gap-2">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-[#e7e7ff] text-[#696cff] dark:bg-[#696cff]/20">
                            <i data-lucide="map" class="h-5 w-5"></i>
                        </span>
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide text-[#696cff]">Progres real-time</p>
                            <h3 id="panduan-title" class="font-heading text-lg font-bold text-[#566a7f] dark:text-[#d5d5e2] sm:text-xl">
                                Panduan Langkah Pendaftaran (SPMB)
                            </h3>
                        </div>
                    </div>
                    <p class="mt-3 max-w-2xl text-sm leading-6 text-[#697a8d] dark:text-[#a1b0cb]">
                        Status berikut dirangkum dari data anak, pendaftaran, verifikasi administrasi, dan pembayaran terbaru.
                    </p>
                </div>

                @if($siswas->count() > 1)
                    <form method="GET" action="{{ route('parent.dashboard') }}" class="w-full shrink-0 sm:w-56">
                        <label for="dashboard-siswa" class="mb-1.5 block text-xs font-semibold text-[#566a7f] dark:text-[#d5d5e2]">Tampilkan progres anak</label>
                        <div class="relative">
                            <select id="dashboard-siswa" name="siswa" onchange="this.form.submit()" class="w-full rounded-lg border-[#d9dee3] bg-white py-2.5 pl-3 pr-9 text-sm font-medium text-[#566a7f] shadow-none focus:border-[#696cff] focus:ring-[#696cff] dark:border-[#434463] dark:bg-[#232333] dark:text-[#d5d5e2]">
                                @foreach($siswas as $siswa)
                                    <option value="{{ $siswa->id }}" @selected($selectedSiswa?->is($siswa))>{{ $siswa->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <noscript>
                            <button type="submit" class="mt-2 text-xs font-semibold text-[#696cff]">Tampilkan</button>
                        </noscript>
                    </form>
                @endif
            </div>

            <div class="mt-5 flex flex-col gap-3 rounded-xl border border-[#e7e7ff] bg-[#f8f8ff] p-4 dark:border-[#696cff]/25 dark:bg-[#696cff]/10 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex min-w-0 items-center gap-3">
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-[#696cff] text-sm font-bold text-white">
                        {{ $selectedSiswa ? str($selectedSiswa->nama)->substr(0, 1)->upper() : '1' }}
                    </span>
                    <div class="min-w-0">
                        <p class="truncate text-sm font-bold text-[#566a7f] dark:text-[#d5d5e2]">
                            {{ $selectedSiswa?->nama ?? 'Mulai pendaftaran anak' }}
                        </p>
                        <p class="mt-0.5 text-xs leading-5 text-[#697a8d] dark:text-[#a1b0cb]">{{ $progress['summary'] }}</p>
                    </div>
                </div>
                <span class="inline-flex w-fit shrink-0 items-center gap-2 rounded-full bg-white px-3 py-1.5 text-xs font-semibold text-[#696cff] shadow-sm dark:bg-[#2b2c40]">
                    @if($progress['current_step']['current'])
                        <span class="relative flex h-2 w-2" aria-hidden="true">
                            <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex h-2 w-2 rounded-full bg-emerald-500"></span>
                        </span>
                    @else
                        <i data-lucide="circle-check-big" class="h-3.5 w-3.5 text-emerald-500"></i>
                    @endif
                    {{ $progress['current_step']['title'] }}
                </span>
            </div>

            <ol class="mt-5 grid grid-cols-1 gap-3 sm:grid-cols-2 xl:grid-cols-3">
                @foreach($progress['steps'] as $step)
                    <li
                        @class([
                            'relative flex min-h-[148px] min-w-0 flex-col rounded-xl border p-4 transition-colors',
                            'border-emerald-200 bg-emerald-50/40 dark:border-emerald-500/20 dark:bg-emerald-500/5' => $step['state'] === 'done',
                            'border-[#696cff] bg-[#696cff]/5 ring-1 ring-[#696cff]/20 dark:bg-[#696cff]/10' => $step['state'] === 'active',
                            'border-amber-200 bg-amber-50/50 dark:border-amber-500/25 dark:bg-amber-500/10' => $step['state'] === 'waiting',
                            'border-red-200 bg-red-50/50 dark:border-red-500/25 dark:bg-red-500/10' => $step['state'] === 'failed',
                            'border-[#d9dee3] bg-[#f9fafb] dark:border-[#434463] dark:bg-[#28293d]/60' => $step['state'] === 'locked',
                        ])
                    >
                        <div class="flex items-start justify-between gap-3">
                            <span
                                @class([
                                    'inline-flex rounded-full px-2.5 py-1 text-[10px] font-bold uppercase tracking-wide',
                                    'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/15 dark:text-emerald-300' => $step['state'] === 'done',
                                    'bg-[#e7e7ff] text-[#696cff] dark:bg-[#696cff]/20 dark:text-[#aeb0ff]' => $step['state'] === 'active',
                                    'bg-amber-100 text-amber-700 dark:bg-amber-500/15 dark:text-amber-300' => $step['state'] === 'waiting',
                                    'bg-red-100 text-red-700 dark:bg-red-500/15 dark:text-red-300' => $step['state'] === 'failed',
                                    'bg-[#eceef1] text-[#8592a3] dark:bg-[#434463] dark:text-[#a1b0cb]' => $step['state'] === 'locked',
                                ])
                            >
                                Langkah {{ $step['number'] }}
                            </span>

                            @if($step['current'])
                                <span class="relative flex h-2.5 w-2.5" title="Tahap saat ini">
                                    <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-400 opacity-75"></span>
                                    <span class="relative inline-flex h-2.5 w-2.5 rounded-full bg-emerald-500"></span>
                                    <span class="sr-only">Tahap aktif saat ini</span>
                                </span>
                            @elseif($step['state'] === 'done')
                                <i data-lucide="check-circle-2" class="h-4 w-4 text-emerald-500" aria-hidden="true"></i>
                            @elseif($step['state'] === 'locked')
                                <i data-lucide="lock-keyhole" class="h-4 w-4 text-[#a1b0cb]" aria-hidden="true"></i>
                            @endif
                        </div>

                        <div class="my-3 min-w-0 flex-1">
                            <div class="flex items-center gap-2">
                                <i
                                    data-lucide="{{ $step['icon'] }}"
                                    @class([
                                        'h-4 w-4 shrink-0',
                                        'text-emerald-600 dark:text-emerald-400' => $step['state'] === 'done',
                                        'text-[#696cff]' => $step['state'] === 'active',
                                        'text-amber-600 dark:text-amber-400' => $step['state'] === 'waiting',
                                        'text-red-600 dark:text-red-400' => $step['state'] === 'failed',
                                        'text-[#a1b0cb]' => $step['state'] === 'locked',
                                    ])
                                ></i>
                                <h4 class="truncate text-sm font-bold text-[#566a7f] dark:text-[#d5d5e2]">{{ $step['title'] }}</h4>
                            </div>
                            <p class="mt-2 text-xs leading-5 text-[#697a8d] dark:text-[#a1b0cb]">{{ $step['description'] }}</p>
                        </div>

                        <div class="border-t border-black/5 pt-2.5 text-xs font-semibold dark:border-white/5">
                            <span
                                @class([
                                    'inline-flex items-center gap-1.5',
                                    'text-emerald-600 dark:text-emerald-400' => $step['state'] === 'done',
                                    'text-[#696cff] dark:text-[#aeb0ff]' => $step['state'] === 'active',
                                    'text-amber-700 dark:text-amber-300' => $step['state'] === 'waiting',
                                    'text-red-700 dark:text-red-300' => $step['state'] === 'failed',
                                    'text-[#a1b0cb]' => $step['state'] === 'locked',
                                ])
                            >
                                @if($step['state'] === 'done')
                                    <i data-lucide="check" class="h-3.5 w-3.5"></i>
                                @elseif($step['state'] === 'waiting')
                                    <i data-lucide="clock-3" class="h-3.5 w-3.5"></i>
                                @elseif($step['state'] === 'failed')
                                    <i data-lucide="triangle-alert" class="h-3.5 w-3.5"></i>
                                @elseif($step['state'] === 'locked')
                                    <i data-lucide="lock" class="h-3.5 w-3.5"></i>
                                @else
                                    <i data-lucide="arrow-right" class="h-3.5 w-3.5"></i>
                                @endif
                                {{ $step['status_label'] }}
                            </span>
                        </div>
                    </li>
                @endforeach
            </ol>
        </section>

        <section id="status-pendaftaran-terkini" aria-labelledby="status-terkini-title" class="min-w-0 rounded-xl border border-[#d9dee3] bg-white p-4 shadow-sneat dark:border-[#434463] dark:bg-[#2b2c40] dark:shadow-sneat-dark sm:p-6">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                <div class="flex min-w-0 items-start gap-3">
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-[#e7e7ff] text-[#696cff] dark:bg-[#696cff]/20">
                        <i data-lucide="activity" class="h-5 w-5"></i>
                    </span>
                    <div class="min-w-0">
                        <p class="text-xs font-semibold uppercase tracking-wide text-[#696cff]">Informasi terbaru</p>
                        <h3 id="status-terkini-title" class="font-heading text-lg font-bold text-[#566a7f] dark:text-[#d5d5e2] sm:text-xl">
                            Status Pendaftaran Anak Saat Ini
                        </h3>
                        <p class="mt-1 text-sm text-[#697a8d] dark:text-[#a1b0cb]">
                            {{ $selectedSiswa?->nama ?? 'Belum ada anak yang didaftarkan' }}
                        </p>
                    </div>
                </div>

                <span
                    @class([
                        'inline-flex w-fit shrink-0 items-center gap-2 rounded-full px-3 py-1.5 text-xs font-semibold',
                        'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-300' => $statusCard['tone'] === 'done',
                        'bg-[#e7e7ff] text-[#696cff] dark:bg-[#696cff]/20 dark:text-[#aeb0ff]' => $statusCard['tone'] === 'active',
                        'bg-amber-50 text-amber-700 dark:bg-amber-500/10 dark:text-amber-300' => $statusCard['tone'] === 'waiting',
                        'bg-red-50 text-red-700 dark:bg-red-500/10 dark:text-red-300' => $statusCard['tone'] === 'failed',
                        'bg-[#f5f5f9] text-[#8592a3] dark:bg-[#434463] dark:text-[#a1b0cb]' => $statusCard['tone'] === 'locked',
                    ])
                >
                    @if($statusCard['tone'] === 'done')
                        <i data-lucide="check-circle-2" class="h-3.5 w-3.5"></i>
                    @elseif($statusCard['tone'] === 'failed')
                        <i data-lucide="triangle-alert" class="h-3.5 w-3.5"></i>
                    @elseif($statusCard['tone'] === 'locked')
                        <i data-lucide="lock" class="h-3.5 w-3.5"></i>
                    @else
                        <span class="relative flex h-2 w-2" aria-hidden="true">
                            <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex h-2 w-2 rounded-full bg-emerald-500"></span>
                        </span>
                    @endif
                    {{ $statusCard['label'] }}
                </span>
            </div>

            <div class="mt-5 rounded-xl border border-[#eceef1] bg-[#f8f8fa] p-4 dark:border-[#434463] dark:bg-[#232333]">
                <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-[#a1b0cb]">Tahap sekarang</p>
                        <h4 class="mt-1 font-heading text-base font-bold text-[#566a7f] dark:text-[#d5d5e2]">{{ $statusCard['headline'] }}</h4>
                    </div>
                    @if($statusCard['updated_at'])
                        <span class="inline-flex w-fit items-center gap-1.5 text-xs text-[#a1b0cb]">
                            <i data-lucide="refresh-cw" class="h-3.5 w-3.5"></i>
                            Diperbarui {{ $statusCard['updated_at'] }}
                        </span>
                    @endif
                </div>
                <p class="mt-2 text-sm leading-6 text-[#697a8d] dark:text-[#a1b0cb]">{{ $statusCard['description'] }}</p>
            </div>

            <dl class="mt-4 grid grid-cols-1 gap-3 sm:grid-cols-2 xl:grid-cols-4">
                <div class="min-w-0 rounded-lg border border-[#eceef1] p-3.5 dark:border-[#434463]">
                    <dt class="flex items-center gap-1.5 text-xs font-semibold text-[#a1b0cb]">
                        <i data-lucide="hash" class="h-3.5 w-3.5"></i>
                        Nomor Pendaftaran
                    </dt>
                    <dd class="mt-1.5 truncate text-sm font-bold text-[#566a7f] dark:text-[#d5d5e2]" title="{{ $statusCard['registration_number'] ?? 'Belum tersedia' }}">
                        {{ $statusCard['registration_number'] ?? 'Belum tersedia' }}
                    </dd>
                </div>

                <div class="min-w-0 rounded-lg border border-[#eceef1] p-3.5 dark:border-[#434463]">
                    <dt class="flex items-center gap-1.5 text-xs font-semibold text-[#a1b0cb]">
                        <i data-lucide="calendar-days" class="h-3.5 w-3.5"></i>
                        Gelombang
                    </dt>
                    <dd class="mt-1.5 truncate text-sm font-bold text-[#566a7f] dark:text-[#d5d5e2]" title="{{ $statusCard['wave'] ?? 'Belum dipilih' }}">
                        {{ $statusCard['wave'] ?? 'Belum dipilih' }}
                    </dd>
                    @if($statusCard['academic_year'])
                        <dd class="mt-0.5 text-xs text-[#a1b0cb]">TA {{ $statusCard['academic_year'] }}</dd>
                    @endif
                </div>

                <div class="min-w-0 rounded-lg border border-[#eceef1] p-3.5 dark:border-[#434463]">
                    <dt class="flex items-center gap-1.5 text-xs font-semibold text-[#a1b0cb]">
                        <i data-lucide="clipboard-check" class="h-3.5 w-3.5"></i>
                        Administrasi
                    </dt>
                    <dd
                        @class([
                            'mt-1.5 text-sm font-bold',
                            'text-emerald-600 dark:text-emerald-400' => $statusCard['administration_tone'] === 'done',
                            'text-[#696cff] dark:text-[#aeb0ff]' => $statusCard['administration_tone'] === 'active',
                            'text-amber-700 dark:text-amber-300' => $statusCard['administration_tone'] === 'waiting',
                            'text-red-700 dark:text-red-300' => $statusCard['administration_tone'] === 'failed',
                            'text-[#8592a3] dark:text-[#a1b0cb]' => $statusCard['administration_tone'] === 'locked',
                        ])
                    >
                        {{ $statusCard['administration_label'] }}
                    </dd>
                </div>

                <div class="min-w-0 rounded-lg border border-[#eceef1] p-3.5 dark:border-[#434463]">
                    <dt class="flex items-center gap-1.5 text-xs font-semibold text-[#a1b0cb]">
                        <i data-lucide="credit-card" class="h-3.5 w-3.5"></i>
                        Daftar Ulang
                    </dt>
                    <dd
                        @class([
                            'mt-1.5 text-sm font-bold',
                            'text-emerald-600 dark:text-emerald-400' => $statusCard['payment_tone'] === 'done',
                            'text-[#696cff] dark:text-[#aeb0ff]' => $statusCard['payment_tone'] === 'active',
                            'text-amber-700 dark:text-amber-300' => $statusCard['payment_tone'] === 'waiting',
                            'text-red-700 dark:text-red-300' => $statusCard['payment_tone'] === 'failed',
                            'text-[#8592a3] dark:text-[#a1b0cb]' => $statusCard['payment_tone'] === 'locked',
                        ])
                    >
                        {{ $statusCard['payment_label'] }}
                    </dd>
                </div>
            </dl>

            @if($statusCard['admin_note'])
                <div class="mt-4 flex items-start gap-3 rounded-xl border border-amber-200 bg-amber-50 p-4 dark:border-amber-500/20 dark:bg-amber-500/10">
                    <i data-lucide="message-square-text" class="mt-0.5 h-4 w-4 shrink-0 text-amber-600 dark:text-amber-400"></i>
                    <div class="min-w-0">
                        <p class="text-xs font-bold uppercase tracking-wide text-amber-700 dark:text-amber-300">Catatan admin</p>
                        <p class="mt-1 break-words text-sm leading-6 text-amber-800 dark:text-amber-200">{{ $statusCard['admin_note'] }}</p>
                    </div>
                </div>
            @endif

            @if($statusCard['status_url'])
                <div class="mt-4 flex justify-end border-t border-[#eceef1] pt-4 dark:border-[#434463]">
                    <a href="{{ $statusCard['status_url'] }}" class="inline-flex items-center gap-2 text-sm font-semibold text-[#696cff] hover:text-[#5a5de6]">
                        Lihat Detail Status
                        <i data-lucide="arrow-right" class="h-4 w-4"></i>
                    </a>
                </div>
            @endif
        </section>
        </div>

        <aside class="min-w-0 space-y-5">
            <section aria-labelledby="aksi-cepat-title" class="rounded-xl border border-[#d9dee3] bg-white p-4 shadow-sneat dark:border-[#434463] dark:bg-[#2b2c40] dark:shadow-sneat-dark sm:p-5">
                <div class="flex items-center gap-2">
                    <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-[#e7e7ff] text-[#696cff] dark:bg-[#696cff]/20">
                        <i data-lucide="zap" class="h-5 w-5"></i>
                    </span>
                    <div>
                        <h3 id="aksi-cepat-title" class="font-heading text-lg font-bold text-[#566a7f] dark:text-[#d5d5e2]">Aksi Cepat</h3>
                        <p class="text-xs text-[#a1b0cb]">{{ $selectedSiswa ? 'Untuk '.$selectedSiswa->nama : 'Langkah utama berikutnya' }}</p>
                    </div>
                </div>

                <div class="mt-4 space-y-3">
                    @foreach($progress['quick_actions'] as $action)
                        <a
                            href="{{ $action['url'] }}"
                            @class([
                                'group flex min-w-0 items-center gap-3 rounded-xl border p-3.5 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-[#696cff] focus-visible:ring-offset-2',
                                'border-[#696cff] bg-[#696cff] text-white hover:bg-[#5a5de6]' => $action['primary'],
                                'border-[#d9dee3] bg-white text-[#566a7f] hover:border-[#696cff] hover:bg-[#f8f8ff] dark:border-[#434463] dark:bg-[#2b2c40] dark:text-[#d5d5e2] dark:hover:bg-[#696cff]/10' => ! $action['primary'],
                            ])
                        >
                            <span
                                @class([
                                    'flex h-10 w-10 shrink-0 items-center justify-center rounded-lg',
                                    'bg-white/15 text-white' => $action['primary'],
                                    'bg-[#e7e7ff] text-[#696cff] dark:bg-[#696cff]/20' => ! $action['primary'],
                                ])
                            >
                                <i data-lucide="{{ $action['icon'] }}" class="h-5 w-5"></i>
                            </span>
                            <span class="min-w-0 flex-1">
                                <span class="block text-sm font-bold leading-5">{{ $action['title'] }}</span>
                                <span @class(['mt-0.5 block text-xs leading-5', 'text-white/75' => $action['primary'], 'text-[#a1b0cb]' => ! $action['primary']])>
                                    {{ $action['description'] }}
                                </span>
                            </span>
                            <i data-lucide="chevron-right" @class(['h-4 w-4 shrink-0 transition-transform group-hover:translate-x-0.5', 'text-white/70' => $action['primary'], 'text-[#a1b0cb]' => ! $action['primary']])></i>
                        </a>
                    @endforeach
                </div>

                @if($siswas->isNotEmpty())
                    <a href="{{ route('parent.siswa.index') }}" class="mt-4 inline-flex items-center gap-1.5 text-xs font-semibold text-[#696cff] hover:text-[#5a5de6]">
                        Kelola semua data anak
                        <i data-lucide="arrow-right" class="h-3.5 w-3.5"></i>
                    </a>
                @endif
            </section>

            <section aria-labelledby="bantuan-title" class="rounded-xl border border-[#d9dee3] bg-white p-5 text-center shadow-sneat dark:border-[#434463] dark:bg-[#2b2c40] dark:shadow-sneat-dark">
                <span class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400">
                    <i data-lucide="messages-square" class="h-6 w-6"></i>
                </span>
                <h3 id="bantuan-title" class="mt-3 font-heading text-base font-bold text-[#566a7f] dark:text-[#d5d5e2]">Butuh Bantuan?</h3>
                <p class="mx-auto mt-2 max-w-sm text-sm leading-6 text-[#697a8d] dark:text-[#a1b0cb]">
                    Jika mengalami kendala selama pendaftaran, silakan hubungi admin SPMB.
                </p>

                @if($helpWaUrl)
                    <a href="{{ $helpWaUrl }}" target="_blank" rel="noopener noreferrer" class="mt-4 inline-flex w-full items-center justify-center gap-2 rounded-lg bg-emerald-500 px-4 py-2.5 text-sm font-semibold text-white transition-colors hover:bg-emerald-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500 focus-visible:ring-offset-2">
                        <i data-lucide="message-circle" class="h-4 w-4"></i>
                        Hubungi Admin SPMB
                    </a>
                @else
                    <p class="mt-4 rounded-lg bg-[#f5f5f9] px-3 py-2.5 text-xs font-medium text-[#697a8d] dark:bg-[#232333] dark:text-[#a1b0cb]">
                        Kontak admin belum tersedia.
                    </p>
                @endif
            </section>
        </aside>
    </div>
</div>
@endsection
