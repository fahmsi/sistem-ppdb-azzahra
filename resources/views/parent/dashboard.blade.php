@extends('layouts.app')

@section('title', 'Dashboard Orang Tua/Wali')
@section('header_title', 'Dashboard')

@section('content')
@php
    $adminWhatsapp = preg_replace('/[^0-9]/', '', (string) config('spmb.admin_whatsapp', ''));
    $helpWaMessage = "Assalamu'alaikum Admin PAUD Az-Zahra, saya ingin menanyakan proses pendaftaran anak saya.";
    $helpWaUrl = $adminWhatsapp ? 'https://wa.me/'.$adminWhatsapp.'?text='.urlencode($helpWaMessage) : '#';
@endphp

<div class="parent-dashboard-page mx-auto max-w-7xl space-y-6">
    <section class="relative overflow-hidden rounded-xl bg-gradient-to-r from-[#696cff] to-[#7b7dff] p-5 text-white shadow-sneat-lg sm:p-8" aria-labelledby="account-summary-title">
        <div class="absolute -right-10 -top-24 h-64 w-64 rounded-full bg-white/10 blur-2xl" aria-hidden="true"></div>
        <div class="relative z-10 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 id="account-summary-title" class="mb-2 break-words font-heading text-xl font-bold leading-tight sm:text-3xl">Assalamu'alaikum, {{ explode(' ', auth()->user()->name)[0] }}!</h2>
                <p class="max-w-2xl text-sm leading-6 text-white/85 sm:text-base">Pantau proses SPMB setiap anak secara terpisah dari satu akun Orang Tua/Wali.</p>
            </div>
            <a href="{{ route('parent.siswa.create') }}" class="inline-flex items-center justify-center gap-2 rounded-md bg-white px-4 py-2.5 text-sm font-semibold text-[#696cff] transition-colors hover:bg-[#f5f5f9] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white">
                <i data-lucide="plus" class="h-4 w-4" aria-hidden="true"></i>
                Tambah Anak
            </a>
        </div>
    </section>

    @if($siswas->isEmpty())
        <section class="rounded-lg border border-[#d9dee3] bg-white p-8 text-center shadow-sneat dark:border-[#434463] dark:bg-[#2b2c40] dark:shadow-sneat-dark" aria-labelledby="empty-child-title">
            <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-[#e7e7ff] text-[#696cff] dark:bg-[#696cff]/20">
                <i data-lucide="user-plus" class="h-7 w-7" aria-hidden="true"></i>
            </div>
            <h2 id="empty-child-title" class="font-heading text-lg font-semibold text-[#566a7f] dark:text-[#d5d5e2]">Belum Ada Data Anak</h2>
            <p class="mx-auto mt-2 max-w-md text-sm text-[#a1b0cb]">Tambahkan data anak terlebih dahulu, lalu pilih gelombang pendaftaran yang tersedia.</p>
            <a href="{{ route('parent.siswa.create') }}" class="sneat-btn-primary mt-5 inline-flex justify-center">
                <i data-lucide="plus" class="h-4 w-4" aria-hidden="true"></i>
                Tambah Data Anak
            </a>
        </section>
    @else
        <section class="space-y-4" aria-labelledby="children-summary-title">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <h2 id="children-summary-title" class="font-heading text-xl font-bold text-[#566a7f] dark:text-[#d5d5e2]">Daftar Anak</h2>
                    <p class="text-sm text-[#a1b0cb]">Status, tahapan, dan aksi berikutnya ditampilkan khusus untuk masing-masing anak.</p>
                </div>
                <a href="{{ route('parent.siswa.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-[#696cff] hover:text-[#5a5de6] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#696cff]">
                    Kelola Data Anak
                    <i data-lucide="arrow-right" class="h-4 w-4" aria-hidden="true"></i>
                </a>
            </div>

            <div class="grid gap-4 xl:grid-cols-2">
                @foreach($siswas as $siswa)
                    @php
                        $registration = $siswa->pendaftaranDetails->first();
                        $primary = $registration ? \App\Support\SpmbStatusPresenter::primary($registration) : null;
                        $decision = $registration ? \App\Support\SpmbStatusPresenter::decision($registration->keputusan_status) : null;
                        $final = $registration ? \App\Support\SpmbStatusPresenter::final($registration->final_status) : null;

                        if (!$registration) {
                            $actionUrl = route('parent.siswa.pendaftaran.index', $siswa);
                            $actionLabel = 'Pilih Gelombang';
                            $actionIcon = 'clipboard-list';
                        } elseif ($registration->isPerluRevisi()) {
                            $actionUrl = route('parent.siswa.edit', $siswa);
                            $actionLabel = 'Perbaiki Data Anak';
                            $actionIcon = 'edit-3';
                        } else {
                            $actionUrl = route('parent.siswa.pendaftaran.status', $siswa);
                            $actionLabel = $registration->canSubmitPayment() && (!$registration->pembayaran || $registration->pembayaran->isDitolak())
                                ? ($registration->pembayaran ? 'Unggah Ulang Pembayaran' : 'Lanjutkan Daftar Ulang')
                                : 'Lihat Status Lengkap';
                            $actionIcon = $registration->canSubmitPayment() ? 'arrow-right-circle' : 'activity';
                        }
                    @endphp

                    <article class="overflow-hidden rounded-lg border border-[#d9dee3] bg-white shadow-sneat dark:border-[#434463] dark:bg-[#2b2c40] dark:shadow-sneat-dark" aria-labelledby="child-{{ $siswa->id }}-name">
                        <div class="p-5">
                            <div class="flex items-start gap-4">
                                <img src="{{ $siswa->foto ? Storage::url($siswa->foto) : asset('images/default-avatar.png') }}" alt="Foto {{ $siswa->nama }}" class="h-20 w-20 shrink-0 rounded-lg border border-[#d9dee3] object-cover dark:border-[#434463]">
                                <div class="min-w-0 flex-1">
                                    <h3 id="child-{{ $siswa->id }}-name" class="break-words font-heading text-lg font-bold text-[#566a7f] dark:text-[#d5d5e2]">{{ $siswa->nama }}</h3>
                                    <p class="mt-1 text-sm text-[#697a8d] dark:text-[#a1b0cb]">
                                        {{ $registration?->kelompok_final ? 'Kelompok '.$registration->kelompok_final : ($registration?->kelompok_rekomendasi ? 'Rekomendasi Kelompok '.$registration->kelompok_rekomendasi : 'Kelompok belum ditentukan') }}
                                    </p>
                                    @if($registration)
                                        <p class="mt-1 text-xs text-[#a1b0cb]">{{ $registration->nomor_pendaftaran }} · {{ $registration->pendaftaran?->gelombang ?? 'Gelombang tidak tersedia' }}</p>
                                    @else
                                        <p class="mt-1 text-xs text-[#a1b0cb]">Belum ada pendaftaran aktif.</p>
                                    @endif
                                </div>
                            </div>

                            @if($registration)
                                <div class="mt-5 rounded-lg bg-[#f5f5f9] p-4 dark:bg-[#232333]">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-[#a1b0cb]">Status Utama</p>
                                    <x-spmb.status-badge :presentation="$primary" show-description class="mt-2" />

                                    <dl class="mt-4 grid gap-3 border-t border-[#d9dee3] pt-4 text-sm dark:border-[#434463] sm:grid-cols-2">
                                        <div>
                                            <dt class="text-xs text-[#a1b0cb]">Keputusan Sekolah</dt>
                                            <dd class="mt-1 font-semibold text-[#566a7f] dark:text-[#d5d5e2]">{{ $decision['label'] }}</dd>
                                        </div>
                                        <div>
                                            <dt class="text-xs text-[#a1b0cb]">Status Akhir</dt>
                                            <dd class="mt-1 font-semibold text-[#566a7f] dark:text-[#d5d5e2]">{{ $final['label'] }}</dd>
                                        </div>
                                    </dl>

                                    @if($registration->keputusan_alasan)
                                        <div class="mt-3 rounded-md border border-indigo-100 bg-indigo-50 p-3 text-sm text-indigo-800 dark:border-indigo-500/20 dark:bg-indigo-500/10 dark:text-indigo-200">
                                            <span class="font-semibold">Keterangan sekolah:</span> {{ $registration->keputusan_alasan }}
                                        </div>
                                    @endif

                                    <div class="mt-4">
                                        <p class="mb-2 text-xs font-semibold uppercase tracking-wide text-[#a1b0cb]">Tahapan Ringkas</p>
                                        <x-spmb.progress-timeline :registration="$registration" compact />
                                    </div>

                                    @if($registration->isKeputusanDiterima() && !$registration->pembayaran)
                                        <div class="mt-4">
                                            @include('parent.components.payment-information', ['paymentSetting' => $paymentSetting])
                                        </div>
                                    @endif
                                </div>
                            @else
                                <div class="mt-5 rounded-lg border border-dashed border-[#d9dee3] bg-[#f5f5f9] p-4 dark:border-[#434463] dark:bg-[#232333]">
                                    <p class="font-semibold text-[#566a7f] dark:text-[#d5d5e2]">Belum Ada Pendaftaran</p>
                                    <p class="mt-1 text-sm text-[#a1b0cb]">Pilih gelombang untuk memulai proses SPMB anak ini.</p>
                                </div>
                            @endif
                        </div>

                        <div class="border-t border-[#d9dee3] bg-[#f5f5f9]/70 p-4 dark:border-[#434463] dark:bg-[#232333]/70">
                            <a href="{{ $actionUrl }}" class="sneat-btn-primary inline-flex w-full items-center justify-center gap-2 sm:w-auto">
                                <i data-lucide="{{ $actionIcon }}" class="h-4 w-4" aria-hidden="true"></i>
                                {{ $actionLabel }}
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>
        </section>
    @endif

    <section class="rounded-lg bg-white p-5 text-center text-slate-700 shadow-sneat dark:bg-[#2b2c40] dark:text-white dark:shadow-sneat-dark sm:p-6" aria-labelledby="help-title">
        <div class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-[#696cff]/10 text-[#696cff] dark:bg-[#696cff]/20">
            <i data-lucide="help-circle" class="h-6 w-6" aria-hidden="true"></i>
        </div>
        <h2 id="help-title" class="mb-2 font-semibold text-slate-900 dark:text-[#d5d5e2]">Butuh Bantuan?</h2>
        <p class="mx-auto mb-4 max-w-lg text-sm text-slate-500 dark:text-[#a1b0cb]">Hubungi admin SPMB jika Anda memerlukan bantuan terkait proses salah satu anak.</p>
        @if($adminWhatsapp)
            <a href="{{ $helpWaUrl }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center gap-2 rounded-md bg-emerald-500 px-4 py-2.5 text-sm font-medium text-white transition-colors hover:bg-emerald-600">
                <i data-lucide="message-circle" class="h-4 w-4" aria-hidden="true"></i>
                Hubungi Admin SPMB
            </a>
        @endif
    </section>
</div>
@endsection
