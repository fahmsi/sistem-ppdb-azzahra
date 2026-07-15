@extends('layouts.app')

@section('title', 'Dashboard Wali Murid')
@section('header_title', 'Dashboard')

@section('content')
@php
    $adminWhatsapp = preg_replace('/[^0-9]/', '', (string) config('spmb.admin_whatsapp', ''));
    $helpWaMessage = "Assalamu'alaikum Admin PAUD Az-Zahra, saya ingin menanyakan proses pendaftaran anak saya.";
    $helpWaUrl = $adminWhatsapp ? 'https://wa.me/'.$adminWhatsapp.'?text='.urlencode($helpWaMessage) : '#';
    $statusLabels = [
        'pending' => 'Pending',
        'menunggu_verifikasi' => 'Menunggu Verifikasi Berkas',
        'perlu_revisi' => 'Perlu Revisi Data',
        'ditolak' => 'Pendaftaran Ditolak',
        'diterima' => 'Berkas Terverifikasi - Lanjut Observasi',
        'administrasi_lengkap' => 'Administrasi Lengkap',
        'menunggu_keputusan' => 'Menunggu Keputusan',
    ];
    $statusClasses = [
        'pending' => 'bg-gray-100 text-gray-700 dark:bg-[#434463] dark:text-[#d5d5e2]',
        'menunggu_verifikasi' => 'bg-amber-50 text-amber-700 dark:bg-amber-500/10 dark:text-amber-300',
        'perlu_revisi' => 'bg-orange-50 text-orange-700 dark:bg-orange-500/10 dark:text-orange-300',
        'ditolak' => 'bg-red-50 text-red-700 dark:bg-red-500/10 dark:text-red-300',
        'diterima' => 'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-300',
        'administrasi_lengkap' => 'bg-teal-50 text-teal-700 dark:bg-teal-500/10 dark:text-teal-300',
        'menunggu_keputusan' => 'bg-indigo-50 text-indigo-700 dark:bg-indigo-500/10 dark:text-indigo-300',
    ];
@endphp

<div class="parent-dashboard-page mx-auto max-w-7xl space-y-6">
    <div class="relative overflow-hidden rounded-xl bg-gradient-to-r from-[#696cff] to-[#7b7dff] p-5 text-white shadow-sneat-lg animate-fade-up sm:p-8">
        <div class="absolute -right-10 -top-24 h-64 w-64 rounded-full bg-white/10 blur-2xl"></div>
        <div class="relative z-10 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="mb-2 break-words font-heading text-xl font-bold leading-tight sm:text-3xl">Assalamu'alaikum, {{ explode(' ', auth()->user()->name)[0] }}!</h2>
                <p class="max-w-2xl text-sm leading-6 text-white/85 sm:text-base sm:leading-7">Kelola data setiap anak secara terpisah agar profil, pendaftaran, pembayaran, dan kartu pendaftaran tidak saling tercampur.</p>
            </div>
            <a href="{{ route('parent.siswa.create') }}" class="inline-flex items-center justify-center gap-2 rounded-md bg-white px-4 py-2.5 text-sm font-semibold text-[#696cff] transition-colors hover:bg-[#f5f5f9]">
                <i data-lucide="plus" class="h-4 w-4"></i>
                Tambah Anak
            </a>
        </div>
    </div>

    @if($siswas->isEmpty())
        <div class="rounded-lg border border-[#d9dee3] bg-white p-8 text-center shadow-sneat dark:border-[#434463] dark:bg-[#2b2c40] dark:shadow-sneat-dark">
            <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-[#e7e7ff] text-[#696cff] dark:bg-[#696cff]/20">
                <i data-lucide="user-plus" class="h-7 w-7"></i>
            </div>
            <h3 class="font-heading text-lg font-semibold text-[#566a7f] dark:text-[#d5d5e2]">Belum Ada Data Anak</h3>
            <p class="mx-auto mt-2 max-w-md text-sm text-[#a1b0cb]">Tambahkan data anak terlebih dahulu. Setelah itu Anda dapat memilih gelombang pendaftaran untuk masing-masing anak.</p>
            <a href="{{ route('parent.siswa.create') }}" class="sneat-btn-primary mt-5 inline-flex justify-center">
                <i data-lucide="plus" class="h-4 w-4"></i>
                Tambah Anak
            </a>
        </div>
    @else
        <section class="space-y-4">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <h3 class="font-heading text-xl font-bold text-[#566a7f] dark:text-[#d5d5e2]">Ringkasan Anak</h3>
                    <p class="text-sm text-[#a1b0cb]">Setiap kartu menampilkan status terbaru anak tersebut saja.</p>
                </div>
                <a href="{{ route('parent.siswa.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-[#696cff] hover:text-[#5a5de6]">
                    Kelola Anak
                    <i data-lucide="arrow-right" class="h-4 w-4"></i>
                </a>
            </div>

            <div class="grid gap-4 lg:grid-cols-2">
                @foreach($siswas as $siswa)
                    @php
                        $latestRegistration = $siswa->pendaftaranDetails->first();
                        $payment = $latestRegistration?->pembayaran;
                        $paymentStatus = $payment?->status;
                        $isPaymentWaiting = $payment && in_array($paymentStatus, ['pending', 'menunggu_verifikasi'], true);
                        $isPaymentLunas = $paymentStatus === 'lunas';
                        $isPaymentRejected = $paymentStatus === 'ditolak';
                    @endphp

                    <article class="rounded-lg border border-[#d9dee3] bg-white p-5 shadow-sneat dark:border-[#434463] dark:bg-[#2b2c40] dark:shadow-sneat-dark">
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-start">
                            <img src="{{ $siswa->foto ? Storage::url($siswa->foto) : asset('images/default-avatar.png') }}" alt="Foto {{ $siswa->nama }}" class="h-20 w-20 rounded-lg border border-[#d9dee3] object-cover dark:border-[#434463]">
                            <div class="min-w-0 flex-1">
                                <h4 class="break-words font-heading text-lg font-bold text-[#566a7f] dark:text-[#d5d5e2]">{{ $siswa->nama }}</h4>
                                <p class="text-sm text-[#a1b0cb]">Panggilan: {{ $siswa->nama_panggilan ?: '-' }}</p>
                                <p class="mt-1 text-xs text-[#a1b0cb]">{{ $siswa->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }} | {{ $siswa->tanggal_lahir ? \Carbon\Carbon::parse($siswa->tanggal_lahir)->translatedFormat('d M Y') : '-' }}</p>
                            </div>
                            <a href="{{ route('parent.siswa.show', $siswa) }}" class="inline-flex items-center justify-center gap-2 rounded-md border border-[#d9dee3] px-3 py-2 text-sm font-medium text-[#566a7f] transition-colors hover:bg-[#f5f5f9] dark:border-[#434463] dark:text-[#d5d5e2] dark:hover:bg-[#232333]">
                                <i data-lucide="eye" class="h-4 w-4"></i>
                                Detail
                            </a>
                        </div>

                        <div class="mt-5 rounded-lg bg-[#f5f5f9] p-4 dark:bg-[#232333]">
                            @if($latestRegistration)
                                <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                                    <div>
                                        <p class="text-xs font-semibold uppercase tracking-wide text-[#a1b0cb]">Status Terbaru</p>
                                        <div class="mt-2 flex flex-wrap items-center gap-2">
                                            <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $statusClasses[$latestRegistration->status] ?? 'bg-gray-100 text-gray-700' }}">
                                                {{ $statusLabels[$latestRegistration->status] ?? str($latestRegistration->status)->replace('_', ' ')->title() }}
                                            </span>
                                            <span class="text-xs text-[#a1b0cb]">{{ $latestRegistration->pendaftaran?->gelombang ?? '-' }}</span>
                                        </div>
                                        <p class="mt-2 text-xs text-[#a1b0cb]">No. Pendaftaran: <span class="font-semibold text-[#566a7f] dark:text-[#d5d5e2]">{{ $latestRegistration->nomor_pendaftaran }}</span></p>
                                        @if($latestRegistration->observasiTerbaru)
                                            <p class="mt-2 text-xs text-[#a1b0cb] flex items-center gap-1.5">
                                                <i data-lucide="calendar" class="w-3.5 h-3.5 text-indigo-500"></i>
                                                <span>Jadwal Observasi: <b>{{ $latestRegistration->observasiTerbaru->scheduled_at->locale('id')->isoFormat('D MMM YYYY, HH:mm') }}</b></span>
                                            </p>
                                        @endif
                                    </div>
                                    <a href="{{ route('parent.siswa.pendaftaran.status', $siswa) }}" class="sneat-btn-secondary justify-center whitespace-nowrap">
                                        <i data-lucide="activity" class="h-4 w-4"></i>
                                        Lihat Status
                                    </a>
                                </div>

                                @if($latestRegistration->isKeputusanDiterima())
                                    <div class="mt-4 rounded-lg border border-emerald-100 bg-emerald-50 p-4 dark:border-emerald-500/20 dark:bg-emerald-500/10">
                                        @if(!$payment)
                                            <p class="text-sm text-[#697a8d] dark:text-[#d5d5e2]">Berkas sudah terverifikasi dan Ananda dinyatakan <strong>Diterima</strong>. Silakan melakukan pembayaran daftar ulang.</p>
                                            <div class="mt-3">
                                                @include('parent.components.payment-information', ['paymentSetting' => $paymentSetting])
                                            </div>
                                        @elseif($isPaymentWaiting)
                                            <p class="text-sm font-semibold text-amber-700 dark:text-amber-300">Bukti pembayaran sedang menunggu verifikasi admin.</p>
                                        @elseif($isPaymentLunas)
                                            <p class="text-sm font-semibold text-emerald-700 dark:text-emerald-300">Daftar ulang selesai dan pembayaran terverifikasi.</p>
                                        @elseif($isPaymentRejected)
                                            <p class="text-sm font-semibold text-red-700 dark:text-red-300">Bukti pembayaran perlu diperbaiki.</p>
                                        @endif

                                        <div class="mt-4 flex flex-wrap gap-2">
                                            @if($isPaymentLunas)
                                                <a href="{{ route('parent.siswa.pendaftaran.kartu', ['siswa' => $siswa, 'detail' => $latestRegistration]) }}" target="_blank" class="inline-flex items-center justify-center gap-2 rounded-md bg-emerald-600 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-emerald-700">
                                                    <i data-lucide="printer" class="h-4 w-4"></i>
                                                    Cetak Kartu
                                                </a>
                                                <a href="{{ route('parent.pembayaran.receipt', $latestRegistration) }}" target="_blank" class="inline-flex items-center justify-center gap-2 rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-indigo-700">
                                                    <i data-lucide="file-text" class="h-4 w-4"></i>
                                                    Cetak Kuitansi
                                                </a>
                                            @else
                                                <a href="{{ route('parent.siswa.pendaftaran.status', $siswa) }}" class="inline-flex items-center justify-center gap-2 rounded-md bg-emerald-600 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-emerald-700">
                                                    <i data-lucide="upload" class="h-4 w-4"></i>
                                                    Unggah Pembayaran
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                @elseif($latestRegistration->keputusan_status === 'tidak_diterima')
                                    <div class="mt-4 rounded-lg border border-red-100 bg-red-50 p-4 dark:border-red-500/20 dark:bg-red-500/10">
                                        <p class="text-sm text-red-800 dark:text-red-300">Keputusan: <strong>Tidak Diterima</strong></p>
                                        @if($latestRegistration->keputusan_alasan)
                                            <p class="mt-2 text-xs text-red-700 dark:text-red-400">Keterangan: {{ $latestRegistration->keputusan_alasan }}</p>
                                        @endif
                                    </div>
                                @elseif($latestRegistration->keputusan_status === 'perlu_tindak_lanjut')
                                    <div class="mt-4 rounded-lg border border-orange-100 bg-orange-50 p-4 dark:border-orange-500/20 dark:bg-orange-500/10">
                                        <p class="text-sm text-orange-800 dark:text-orange-300">Keputusan: <strong>Perlu Tindak Lanjut</strong></p>
                                        @if($latestRegistration->keputusan_alasan)
                                            <p class="mt-2 text-xs text-orange-700 dark:text-orange-400">Keterangan: {{ $latestRegistration->keputusan_alasan }}</p>
                                        @endif
                                        <p class="mt-2 text-xs text-gray-500">Silakan hubungi pihak sekolah untuk informasi lebih lanjut.</p>
                                    </div>
                                @elseif($latestRegistration->keputusan_status === 'mengundurkan_diri')
                                    <div class="mt-4 rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-500/20 dark:bg-gray-500/10">
                                        <p class="text-sm text-gray-800 dark:text-gray-400">Status: <strong>Mengundurkan Diri</strong></p>
                                        @if($latestRegistration->keputusan_alasan)
                                            <p class="mt-2 text-xs text-gray-600 dark:text-gray-400">Keterangan: {{ $latestRegistration->keputusan_alasan }}</p>
                                        @endif
                                    </div>
                                @endif
                            @else
                                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                    <div>
                                        <p class="text-sm font-semibold text-[#566a7f] dark:text-[#d5d5e2]">Belum ada pendaftaran</p>
                                        <p class="mt-1 text-xs text-[#a1b0cb]">Pilih gelombang untuk mendaftarkan anak ini.</p>
                                    </div>
                                    <a href="{{ route('parent.siswa.pendaftaran.index', $siswa) }}" class="sneat-btn-primary justify-center">
                                        <i data-lucide="clipboard-list" class="h-4 w-4"></i>
                                        Daftar Gelombang
                                    </a>
                                </div>
                            @endif
                        </div>

                        <div class="mt-4 flex flex-wrap gap-2">
                            <a href="{{ route('parent.siswa.edit', $siswa) }}" class="inline-flex items-center justify-center gap-2 rounded-md border border-[#d9dee3] px-3 py-2 text-sm font-medium text-[#566a7f] transition-colors hover:bg-[#f5f5f9] dark:border-[#434463] dark:text-[#d5d5e2] dark:hover:bg-[#232333]">
                                <i data-lucide="edit-3" class="h-4 w-4"></i>
                                Edit Data
                            </a>
                            @if(!$latestRegistration || $latestRegistration->status === 'ditolak')
                                <form action="{{ route('parent.siswa.destroy', $siswa) }}" method="POST" class="child-delete-form" data-child-name="{{ $siswa->nama }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-md border border-red-200 bg-red-50 px-3 py-2 text-sm font-medium text-red-600 transition-colors hover:bg-red-100 dark:border-red-500/20 dark:bg-red-500/10 dark:text-red-300 dark:hover:bg-red-500/20">
                                        <i data-lucide="trash-2" class="h-4 w-4"></i>
                                        Hapus
                                    </button>
                                </form>
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>
        </section>
    @endif

    <div class="rounded-lg bg-white p-5 text-center text-slate-700 shadow-sneat dark:bg-[#2b2c40] dark:text-white dark:shadow-sneat-dark sm:p-6">
        <div class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-[#696cff]/10 text-[#696cff] dark:bg-[#696cff]/20">
            <i data-lucide="help-circle" class="h-6 w-6"></i>
        </div>
        <h3 class="mb-2 font-semibold text-slate-900 dark:text-[#d5d5e2]">Butuh Bantuan?</h3>
        <p class="mx-auto mb-4 max-w-lg text-sm text-slate-500 dark:text-[#a1b0cb]">Jika Anda mengalami kendala saat mendaftarkan salah satu anak, silakan hubungi admin SPMB.</p>
        @if($adminWhatsapp)
            <a href="{{ $helpWaUrl }}" target="_blank" class="inline-flex items-center justify-center gap-2 rounded-md bg-emerald-500 px-4 py-2.5 text-sm font-medium text-white transition-colors hover:bg-emerald-600">
                <i data-lucide="message-circle" class="h-4 w-4"></i>
                Hubungi Admin SPMB
            </a>
        @endif
    </div>
</div>
@endsection
