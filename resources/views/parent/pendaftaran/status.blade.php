@extends('layouts.app')

@section('title', 'Status Pendaftaran')
@section('header_title', 'Status Pendaftaran Anak')

@section('content')
@php
    $adminWhatsapp = preg_replace('/[^0-9]/', '', (string) config('spmb.admin_whatsapp', ''));
@endphp

<div class="space-y-6">
    <div class="animate-fade-up overflow-hidden rounded-lg border border-[#d9dee3] bg-white shadow-sneat dark:border-[#434463] dark:bg-[#2b2c40] dark:shadow-sneat-dark">
        <div class="border-b border-primary-100 bg-primary-50 p-4 dark:border-[#434463] dark:bg-[#696cff]/15 sm:px-8 sm:py-6">
            <h2 class="flex items-start gap-2 font-heading text-xl font-bold text-primary-900 dark:text-[#d5d5e2] sm:items-center sm:text-2xl">
                <i data-lucide="activity" class="w-6 h-6 text-primary-600"></i> Riwayat & Status Pendaftaran
            </h2>
            <p class="mt-1 text-sm text-primary-600 dark:text-[#a1b0cb]">Pantau perkembangan proses verifikasi, penerimaan, dan daftar ulang anak Anda di sini.</p>
        </div>

        <div class="p-4 sm:p-8">
            @if(!isset($registrations) || $registrations->isEmpty())
                <div class="py-12 flex flex-col items-center justify-center text-center">
                    <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-gray-100 dark:bg-[#232333]">
                        <i data-lucide="clipboard-list" class="w-8 h-8 text-gray-400"></i>
                    </div>
                    <h3 class="font-heading text-lg font-semibold text-gray-800 dark:text-[#d5d5e2]">Belum Ada Pendaftaran</h3>
                    <p class="mb-6 mt-1 max-w-md text-gray-500 dark:text-[#a1b0cb]">Anda belum mendaftar ke gelombang manapun. Silakan pilih gelombang yang tersedia.</p>
                    <a href="{{ route('parent.pendaftaran.index') }}" class="px-5 py-2.5 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition-colors">
                        Lihat Gelombang Pendaftaran
                    </a>
                </div>
            @else
                <div class="space-y-6">
                    @foreach($registrations as $reg)
                        @php
                            $payment = $reg->pembayaran;
                            $paymentStatus = $payment?->status;
                            $isPaymentWaiting = $payment && in_array($paymentStatus, ['pending', 'menunggu_verifikasi'], true);
                            $isPaymentLunas = $paymentStatus === 'lunas';
                            $isPaymentRejected = $paymentStatus === 'ditolak';
                            $waMessage = "Assalamu'alaikum Admin, saya sudah mengunggah bukti pembayaran daftar ulang SPMB untuk nomor pendaftaran {$reg->nomor_pendaftaran}. Mohon konfirmasinya. Terima kasih.";
                            $waUrl = $adminWhatsapp ? 'https://wa.me/'.$adminWhatsapp.'?text='.urlencode($waMessage) : null;
                        @endphp

                        <div class="overflow-hidden rounded-lg border border-[#d9dee3] bg-white transition-shadow hover:shadow-sneat-lg dark:border-[#434463] dark:bg-[#2b2c40]">
                            <div class="flex flex-col justify-between gap-4 border-b border-[#d9dee3] bg-[#f5f5f9] px-4 py-4 dark:border-[#434463] dark:bg-[#232333] sm:flex-row sm:items-center sm:px-6">
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-[#a1b0cb]">Gelombang</p>
                                    <h4 class="text-lg font-bold text-gray-900 dark:text-[#d5d5e2]">{{ $reg->pendaftaran->gelombang }}</h4>
                                    <p class="text-sm text-gray-500 dark:text-[#a1b0cb]">Tahun Ajaran {{ $reg->pendaftaran->tahun_ajaran }}</p>
                                    <p class="mt-1 break-all text-xs text-gray-500 dark:text-[#a1b0cb]">No. Pendaftaran: <span class="font-semibold text-gray-700 dark:text-[#d5d5e2]">{{ $reg->nomor_pendaftaran }}</span></p>
                                </div>
                                <div class="text-left sm:text-right">
                                    <p class="mb-1 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-[#a1b0cb]">Status Saat Ini</p>
                                    @if($reg->status === 'pending')
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-gray-200 px-3 py-1.5 text-sm font-semibold text-gray-700 dark:bg-[#434463] dark:text-[#d5d5e2]">
                                            <i data-lucide="clock" class="w-4 h-4"></i> Pending
                                        </span>
                                    @elseif($reg->status === 'menunggu_verifikasi')
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-yellow-100 px-3 py-1.5 text-sm font-semibold text-yellow-700 dark:bg-yellow-500/15 dark:text-yellow-300">
                                            <i data-lucide="search" class="w-4 h-4"></i> Menunggu Verifikasi Admin
                                        </span>
                                    @elseif($reg->status === 'diterima')
                                        <span class="inline-flex items-center gap-1.5 rounded-full border border-secondary-200 bg-secondary-100 px-3 py-1.5 text-sm font-semibold text-secondary-700 dark:border-secondary-500/25 dark:bg-secondary-500/15 dark:text-secondary-300">
                                            <i data-lucide="check-circle" class="w-4 h-4"></i> Diterima
                                        </span>
                                    @elseif($reg->status === 'ditolak')
                                        <span class="inline-flex items-center gap-1.5 rounded-full border border-red-200 bg-red-100 px-3 py-1.5 text-sm font-semibold text-red-700 dark:border-red-500/25 dark:bg-red-500/15 dark:text-red-300">
                                            <i data-lucide="x-circle" class="w-4 h-4"></i> Ditolak
                                        </span>
                                    @elseif($reg->status === 'perlu_revisi')
                                        <span class="inline-flex items-center gap-1.5 rounded-full border border-orange-200 bg-orange-100 px-3 py-1.5 text-sm font-semibold text-orange-700 dark:border-orange-500/25 dark:bg-orange-500/15 dark:text-orange-300">
                                            <i data-lucide="edit-3" class="w-4 h-4"></i> Perlu Revisi Dokumen
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="p-4 sm:p-6">
                                <!-- Visual Timeline -->
                                <div class="mb-8 rounded-xl border border-gray-100 bg-[#f5f5f9] p-4 dark:border-[#434463] dark:bg-[#232333] sm:p-5">
                                    <h5 class="text-sm font-semibold text-gray-700 dark:text-[#d5d5e2] mb-5 flex items-center gap-2">
                                        <i data-lucide="map" class="w-4 h-4 text-[#696cff]"></i> Alur Proses Pendaftaran Anak
                                    </h5>
                                    
                                    @php
                                        // Determine status states
                                        $step1 = 'completed'; // Pendaftaran
                                        
                                        // Step 2: Verifikasi
                                        if (in_array($reg->status, ['pending', 'menunggu_verifikasi'])) {
                                            $step2 = 'active';
                                        } elseif ($reg->status === 'perlu_revisi') {
                                            $step2 = 'warning';
                                        } else {
                                            $step2 = 'completed';
                                        }
                                        
                                        // Step 3: Hasil Pendaftaran
                                        if (in_array($reg->status, ['pending', 'menunggu_verifikasi', 'perlu_revisi'])) {
                                            $step3 = 'upcoming';
                                        } elseif ($reg->status === 'ditolak') {
                                            $step3 = 'failed';
                                        } else {
                                            $step3 = 'completed';
                                        }
                                        
                                        // Step 4: Daftar Ulang
                                        if ($reg->status !== 'diterima' && !$isPaymentLunas) {
                                            $step4 = 'upcoming';
                                        } else {
                                            if (!$payment) {
                                                $step4 = 'active';
                                            } elseif ($isPaymentWaiting) {
                                                $step4 = 'waiting';
                                            } elseif ($isPaymentRejected) {
                                                $step4 = 'warning';
                                            } else {
                                                $step4 = 'completed';
                                            }
                                        }
                                        
                                        // Step 5: Selesai
                                        if ($isPaymentLunas) {
                                            $step5 = 'completed';
                                        } else {
                                            $step5 = 'upcoming';
                                        }
                                    @endphp

                                    <!-- Horizontal Timeline (hidden on mobile) -->
                                    <div class="hidden md:flex items-stretch justify-between relative min-h-[90px]">
                                        <!-- Timeline progress -->
                                        @php
                                            $width = '0%';
                                            if ($step5 === 'completed') $width = '100%';
                                            elseif ($step4 === 'completed') $width = '75%';
                                            elseif ($step3 === 'completed') $width = '50%';
                                            elseif ($step2 === 'completed') $width = '25%';
                                        @endphp
                                        <div class="absolute left-10 right-10 top-5 z-0 h-1 overflow-hidden rounded-full bg-gray-200 dark:bg-[#434463]">
                                            <div class="h-full bg-emerald-500 transition-[width] duration-500" style="width: {{ $width }};"></div>
                                        </div>

                                        <!-- Step 1 -->
                                        <div class="flex-1 flex flex-col items-center text-center px-1 relative z-10">
                                            <div class="w-10 h-10 rounded-full flex items-center justify-center bg-emerald-500 text-white font-bold shadow-md">
                                                <i data-lucide="check" class="w-5 h-5"></i>
                                            </div>
                                            <span class="text-xs font-bold text-gray-800 dark:text-[#d5d5e2] mt-3">Mendaftar</span>
                                            <span class="text-[10px] text-gray-500 mt-1">Selesai</span>
                                        </div>

                                        <!-- Step 2 -->
                                        <div class="flex-1 flex flex-col items-center text-center px-1 relative z-10">
                                            @if($step2 === 'completed')
                                                <div class="w-10 h-10 rounded-full flex items-center justify-center bg-emerald-500 text-white font-bold shadow-md">
                                                    <i data-lucide="check" class="w-5 h-5"></i>
                                                </div>
                                                <span class="text-xs font-bold text-gray-800 dark:text-[#d5d5e2] mt-3">Verifikasi Berkas</span>
                                                <span class="text-[10px] text-emerald-600 font-semibold mt-1">Selesai</span>
                                            @elseif($step2 === 'active')
                                                <div class="w-10 h-10 rounded-full flex items-center justify-center bg-blue-500 text-white font-bold shadow-md animate-pulse">
                                                    <i data-lucide="search" class="w-5 h-5"></i>
                                                </div>
                                                <span class="text-xs font-bold text-blue-600 mt-3">Verifikasi Berkas</span>
                                                <span class="text-[10px] text-blue-500 mt-1">Proses</span>
                                            @elseif($step2 === 'warning')
                                                <div class="w-10 h-10 rounded-full flex items-center justify-center bg-orange-500 text-white font-bold shadow-md">
                                                    <i data-lucide="alert-circle" class="w-5 h-5"></i>
                                                </div>
                                                <span class="text-xs font-bold text-orange-600 mt-3">Verifikasi Berkas</span>
                                                <span class="text-[10px] text-orange-500 mt-1 font-semibold">Perlu Revisi</span>
                                            @endif
                                        </div>

                                        <!-- Step 3 -->
                                        <div class="flex-1 flex flex-col items-center text-center px-1 relative z-10">
                                            @if($step3 === 'completed')
                                                <div class="w-10 h-10 rounded-full flex items-center justify-center bg-emerald-500 text-white font-bold shadow-md">
                                                    <i data-lucide="check" class="w-5 h-5"></i>
                                                </div>
                                                <span class="text-xs font-bold text-gray-800 dark:text-[#d5d5e2] mt-3">Hasil Pendaftaran</span>
                                                <span class="text-[10px] text-emerald-600 font-semibold mt-1">Diterima</span>
                                            @elseif($step3 === 'failed')
                                                <div class="w-10 h-10 rounded-full flex items-center justify-center bg-red-500 text-white font-bold shadow-md">
                                                    <i data-lucide="x" class="w-5 h-5"></i>
                                                </div>
                                                <span class="text-xs font-bold text-red-600 mt-3">Hasil Pendaftaran</span>
                                                <span class="text-[10px] text-red-500 font-semibold mt-1">Ditolak</span>
                                            @elseif($step3 === 'upcoming')
                                                <div class="w-10 h-10 rounded-full flex items-center justify-center bg-gray-200 dark:bg-[#434463] text-gray-400 dark:text-gray-500 font-bold">
                                                    <i data-lucide="award" class="w-5 h-5"></i>
                                                </div>
                                                <span class="text-xs font-medium text-gray-500 mt-3">Hasil Pendaftaran</span>
                                                <span class="text-[10px] text-gray-400 mt-1">Menunggu</span>
                                            @endif
                                        </div>

                                        <!-- Step 4 -->
                                        <div class="flex-1 flex flex-col items-center text-center px-1 relative z-10">
                                            @if($step4 === 'completed')
                                                <div class="w-10 h-10 rounded-full flex items-center justify-center bg-emerald-500 text-white font-bold shadow-md">
                                                    <i data-lucide="check" class="w-5 h-5"></i>
                                                </div>
                                                <span class="text-xs font-bold text-gray-800 dark:text-[#d5d5e2] mt-3">Daftar Ulang</span>
                                                <span class="text-[10px] text-emerald-600 font-semibold mt-1">Lunas</span>
                                            @elseif($step4 === 'active')
                                                <div class="w-10 h-10 rounded-full flex items-center justify-center bg-blue-500 text-white font-bold shadow-md animate-pulse">
                                                    <i data-lucide="upload" class="w-5 h-5"></i>
                                                </div>
                                                <span class="text-xs font-bold text-blue-600 mt-3">Daftar Ulang</span>
                                                <span class="text-[10px] text-blue-500 mt-1">Upload Bukti</span>
                                            @elseif($step4 === 'waiting')
                                                <div class="w-10 h-10 rounded-full flex items-center justify-center bg-yellow-500 text-white font-bold shadow-md">
                                                    <i data-lucide="clock" class="w-5 h-5"></i>
                                                </div>
                                                <span class="text-xs font-bold text-yellow-600 mt-3">Daftar Ulang</span>
                                                <span class="text-[10px] text-yellow-500 mt-1">Dicek Admin</span>
                                            @elseif($step4 === 'warning')
                                                <div class="w-10 h-10 rounded-full flex items-center justify-center bg-red-500 text-white font-bold shadow-md">
                                                    <i data-lucide="alert-triangle" class="w-5 h-5"></i>
                                                </div>
                                                <span class="text-xs font-bold text-red-600 mt-3">Daftar Ulang</span>
                                                <span class="text-[10px] text-red-500 mt-1 font-semibold">Bukti Ditolak</span>
                                            @elseif($step4 === 'upcoming')
                                                <div class="w-10 h-10 rounded-full flex items-center justify-center bg-gray-200 dark:bg-[#434463] text-gray-400 dark:text-gray-500 font-bold">
                                                    <i data-lucide="credit-card" class="w-5 h-5"></i>
                                                </div>
                                                <span class="text-xs font-medium text-gray-500 mt-3">Daftar Ulang</span>
                                                <span class="text-[10px] text-gray-400 mt-1">Belum Mulai</span>
                                            @endif
                                        </div>

                                        <!-- Step 5 -->
                                        <div class="flex-1 flex flex-col items-center text-center px-1 relative z-10">
                                            @if($step5 === 'completed')
                                                <div class="w-10 h-10 rounded-full flex items-center justify-center bg-emerald-500 text-white font-bold shadow-md">
                                                    <i data-lucide="party-popper" class="w-5 h-5"></i>
                                                </div>
                                                <span class="text-xs font-bold text-emerald-600 mt-3">Selesai</span>
                                                <span class="text-[10px] text-emerald-600 font-semibold mt-1">Terdaftar</span>
                                            @elseif($step5 === 'upcoming')
                                                <div class="w-10 h-10 rounded-full flex items-center justify-center bg-gray-200 dark:bg-[#434463] text-gray-400 dark:text-gray-500 font-bold">
                                                    <i data-lucide="check-square" class="w-5 h-5"></i>
                                                </div>
                                                <span class="text-xs font-medium text-gray-500 mt-3">Selesai</span>
                                                <span class="text-[10px] text-gray-400 mt-1">Menunggu</span>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Vertical Timeline (shown on mobile) -->
                                    <div class="flex md:hidden flex-col gap-5 relative pl-2">
                                        <!-- Line Background -->
                                        <div class="absolute left-6 top-2 bottom-2 w-0.5 bg-gray-200 dark:bg-[#434463] z-0"></div>
                                        
                                        <!-- Step 1 -->
                                        <div class="flex items-center gap-3 relative z-10">
                                            <div class="w-8 h-8 rounded-full flex items-center justify-center bg-emerald-500 text-white font-bold flex-shrink-0">
                                                <i data-lucide="check" class="w-4 h-4"></i>
                                            </div>
                                            <div>
                                                <p class="text-xs font-bold text-gray-800 dark:text-[#d5d5e2]">Mendaftar (Selesai)</p>
                                                <p class="text-[10px] text-gray-500">Pendaftaran online berhasil dikirim</p>
                                            </div>
                                        </div>

                                        <!-- Step 2 -->
                                        <div class="flex items-center gap-3 relative z-10">
                                            @if($step2 === 'completed')
                                                <div class="w-8 h-8 rounded-full flex items-center justify-center bg-emerald-500 text-white font-bold flex-shrink-0">
                                                    <i data-lucide="check" class="w-4 h-4"></i>
                                                </div>
                                                <div>
                                                    <p class="text-xs font-bold text-gray-800 dark:text-[#d5d5e2]">Verifikasi Berkas (Selesai)</p>
                                                    <p class="text-[10px] text-emerald-600 font-semibold">Dokumen lengkap & terverifikasi</p>
                                                </div>
                                            @elseif($step2 === 'active')
                                                <div class="w-8 h-8 rounded-full flex items-center justify-center bg-blue-500 text-white font-bold flex-shrink-0 animate-pulse">
                                                    <i data-lucide="search" class="w-4 h-4"></i>
                                                </div>
                                                <div>
                                                    <p class="text-xs font-bold text-blue-600">Verifikasi Berkas (Sedang Proses)</p>
                                                    <p class="text-[10px] text-blue-500">Dokumen sedang dicek oleh admin</p>
                                                </div>
                                            @elseif($step2 === 'warning')
                                                <div class="w-8 h-8 rounded-full flex items-center justify-center bg-orange-500 text-white font-bold flex-shrink-0">
                                                    <i data-lucide="alert-circle" class="w-4 h-4"></i>
                                                </div>
                                                <div>
                                                    <p class="text-xs font-bold text-orange-600">Verifikasi Berkas (Perlu Perbaikan)</p>
                                                    <p class="text-[10px] text-orange-500 font-semibold">Harap perbaiki dokumen yang ditandai</p>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Step 3 -->
                                        <div class="flex items-center gap-3 relative z-10">
                                            @if($step3 === 'completed')
                                                <div class="w-8 h-8 rounded-full flex items-center justify-center bg-emerald-500 text-white font-bold flex-shrink-0">
                                                    <i data-lucide="check" class="w-4 h-4"></i>
                                                </div>
                                                <div>
                                                    <p class="text-xs font-bold text-gray-800 dark:text-[#d5d5e2]">Hasil Pendaftaran (Diterima)</p>
                                                    <p class="text-[10px] text-emerald-600 font-semibold">Selamat! Anak Anda dinyatakan diterima</p>
                                                </div>
                                            @elseif($step3 === 'failed')
                                                <div class="w-8 h-8 rounded-full flex items-center justify-center bg-red-500 text-white font-bold flex-shrink-0">
                                                    <i data-lucide="x" class="w-4 h-4"></i>
                                                </div>
                                                <div>
                                                    <p class="text-xs font-bold text-red-600">Hasil Pendaftaran (Ditolak)</p>
                                                    <p class="text-[10px] text-red-500 font-semibold">Mohon maaf, pendaftaran ditolak</p>
                                                </div>
                                            @elseif($step3 === 'upcoming')
                                                <div class="w-8 h-8 rounded-full flex items-center justify-center bg-gray-200 dark:bg-[#434463] text-gray-400 dark:text-gray-500 font-bold flex-shrink-0">
                                                    <i data-lucide="award" class="w-4 h-4"></i>
                                                </div>
                                                <div>
                                                    <p class="text-xs font-medium text-gray-500">Hasil Pendaftaran</p>
                                                    <p class="text-[10px] text-gray-400">Menunggu hasil keputusan sekolah</p>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Step 4 -->
                                        <div class="flex items-center gap-3 relative z-10">
                                            @if($step4 === 'completed')
                                                <div class="w-8 h-8 rounded-full flex items-center justify-center bg-emerald-500 text-white font-bold flex-shrink-0">
                                                    <i data-lucide="check" class="w-4 h-4"></i>
                                                </div>
                                                <div>
                                                    <p class="text-xs font-bold text-gray-800 dark:text-[#d5d5e2]">Daftar Ulang (Selesai)</p>
                                                    <p class="text-[10px] text-emerald-600 font-semibold">Bukti transfer disetujui & lunas</p>
                                                </div>
                                            @elseif($step4 === 'active')
                                                <div class="w-8 h-8 rounded-full flex items-center justify-center bg-blue-500 text-white font-bold flex-shrink-0 animate-pulse">
                                                    <i data-lucide="upload" class="w-4 h-4"></i>
                                                </div>
                                                <div>
                                                    <p class="text-xs font-bold text-blue-600">Daftar Ulang (Harap Bayar)</p>
                                                    <p class="text-[10px] text-blue-500">Silakan lakukan transfer biaya daftar ulang</p>
                                                </div>
                                            @elseif($step4 === 'waiting')
                                                <div class="w-8 h-8 rounded-full flex items-center justify-center bg-yellow-500 text-white font-bold flex-shrink-0">
                                                    <i data-lucide="clock" class="w-4 h-4"></i>
                                                </div>
                                                <div>
                                                    <p class="text-xs font-bold text-yellow-600">Daftar Ulang (Pengecekan)</p>
                                                    <p class="text-[10px] text-yellow-500">Bukti bayar sedang diperiksa sekolah</p>
                                                </div>
                                            @elseif($step4 === 'warning')
                                                <div class="w-8 h-8 rounded-full flex items-center justify-center bg-red-500 text-white font-bold flex-shrink-0">
                                                    <i data-lucide="alert-triangle" class="w-4 h-4"></i>
                                                </div>
                                                <div>
                                                    <p class="text-xs font-bold text-red-600">Daftar Ulang (Bukti Ditolak)</p>
                                                    <p class="text-[10px] text-red-500 font-semibold">Bukti bayar salah / tidak terbaca</p>
                                                </div>
                                            @elseif($step4 === 'upcoming')
                                                <div class="w-8 h-8 rounded-full flex items-center justify-center bg-gray-200 dark:bg-[#434463] text-gray-400 dark:text-gray-500 font-bold flex-shrink-0">
                                                    <i data-lucide="credit-card" class="w-4 h-4"></i>
                                                </div>
                                                <div>
                                                    <p class="text-xs font-medium text-gray-500">Daftar Ulang</p>
                                                    <p class="text-[10px] text-gray-400">Menunggu pengumuman hasil pendaftaran</p>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Step 5 -->
                                        <div class="flex items-center gap-3 relative z-10">
                                            @if($step5 === 'completed')
                                                <div class="w-8 h-8 rounded-full flex items-center justify-center bg-emerald-500 text-white font-bold flex-shrink-0">
                                                    <i data-lucide="party-popper" class="w-4 h-4"></i>
                                                </div>
                                                <div>
                                                    <p class="text-xs font-bold text-emerald-600">Selesai</p>
                                                    <p class="text-[10px] text-emerald-600 font-semibold">Anak Anda resmi terdaftar sebagai murid baru!</p>
                                                </div>
                                            @elseif($step5 === 'upcoming')
                                                <div class="w-8 h-8 rounded-full flex items-center justify-center bg-gray-200 dark:bg-[#434463] text-gray-400 dark:text-gray-500 font-bold flex-shrink-0">
                                                    <i data-lucide="check-square" class="w-4 h-4"></i>
                                                </div>
                                                <div>
                                                    <p class="text-xs font-medium text-gray-500">Selesai</p>
                                                    <p class="text-[10px] text-gray-400">Proses pendaftaran rampung</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                    <div>
                                        <h5 class="mb-1 text-sm font-medium text-gray-500 dark:text-[#a1b0cb]">Tanggal Mendaftar</h5>
                                        <p class="font-medium text-gray-900 dark:text-[#d5d5e2]">{{ $reg->created_at->translatedFormat('d F Y, H:i') }} WIB</p>
                                    </div>
                                    <div>
                                        <h5 class="mb-1 text-sm font-medium text-gray-500 dark:text-[#a1b0cb]">Nama Anak</h5>
                                        <p class="font-medium text-gray-900 dark:text-[#d5d5e2]">{{ $reg->siswa->nama ?? '-' }}</p>
                                    </div>
                                </div>

                                @if($reg->notifikasi)
                                    <div class="mt-6 rounded-lg p-4 {{ $reg->status === 'diterima' ? 'border border-secondary-100 bg-secondary-50 text-secondary-800 dark:border-secondary-500/20 dark:bg-secondary-500/10 dark:text-secondary-300' : ($reg->status === 'ditolak' ? 'border border-red-100 bg-red-50 text-red-800 dark:border-red-500/20 dark:bg-red-500/10 dark:text-red-300' : 'border border-blue-100 bg-blue-50 text-blue-800 dark:border-blue-500/20 dark:bg-blue-500/10 dark:text-blue-300') }}">
                                        <h5 class="text-sm font-bold flex items-center gap-2 mb-1">
                                            <i data-lucide="message-square" class="w-4 h-4"></i> Pesan dari Admin:
                                        </h5>
                                        <p class="text-sm">{{ $reg->notifikasi }}</p>
                                    </div>
                                @endif

                                @if($reg->status === 'diterima')
                                    <div class="mt-6 rounded-xl border border-secondary-200 bg-secondary-50 p-4 dark:border-secondary-500/20 dark:bg-secondary-500/10 sm:p-5">
                                        @if(!$payment)
                                            <h5 class="mb-3 flex items-center gap-2 text-base font-bold text-secondary-800 dark:text-secondary-300">
                                                <i data-lucide="check-circle" class="w-5 h-5 text-secondary-600"></i>
                                                Selamat, pendaftaran anak Anda telah diterima.
                                            </h5>
                                            <p class="mb-4 text-sm text-gray-700 dark:text-[#d5d5e2]">Silakan melakukan daftar ulang dengan melakukan pembayaran ke rekening berikut:</p>
                                            @include('parent.components.payment-information', ['paymentSetting' => $paymentSetting])
                                            @if($paymentSetting)
                                                <p class="mt-4 text-sm text-gray-700 dark:text-[#d5d5e2]">Setelah melakukan pembayaran, silakan upload bukti pembayaran melalui tombol di bawah ini.</p>
                                            @endif
                                        @elseif($isPaymentWaiting)
                                            <h5 class="text-base font-bold text-yellow-800 flex items-center gap-2 mb-2">
                                                <i data-lucide="clock" class="w-5 h-5 text-yellow-600"></i>
                                                Bukti pembayaran Anda sedang menunggu verifikasi admin.
                                            </h5>
                                            <p class="text-sm text-gray-700 dark:text-[#d5d5e2]">Silakan hubungi admin untuk konfirmasi.</p>
                                        @elseif($isPaymentLunas)
                                            <div class="text-center py-6 px-4 bg-emerald-100/50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/25 rounded-xl">
                                                <div class="w-12 h-12 bg-emerald-500 text-white rounded-full flex items-center justify-center mx-auto mb-3 shadow-md">
                                                    <i data-lucide="party-popper" class="w-6 h-6"></i>
                                                </div>
                                                <h4 class="text-lg font-bold text-emerald-800 dark:text-emerald-400 mb-2">Daftar Ulang Selesai</h4>
                                                <p class="text-xs text-emerald-700 dark:text-[#a1b0cb] max-w-md mx-auto">
                                                    Selamat! Seluruh proses pendaftaran dan daftar ulang untuk <strong>{{ $reg->siswa->nama }}</strong> telah selesai dan terverifikasi sepenuhnya. Selamat bergabung di keluarga besar PAUD Al-Qur'an Azzahra.
                                                </p>
                                                <p class="text-[11px] text-emerald-600/75 dark:text-[#a1b0cb]/75 mt-3">
                                                    Jadwal orientasi murid baru dan pembagian kelas akan diinfokan kemudian. Cek berkala dashboard Anda.
                                                </p>
                                            </div>
                                        @elseif($isPaymentRejected)
                                            <h5 class="text-base font-bold text-red-800 flex items-center gap-2 mb-2">
                                                <i data-lucide="alert-circle" class="w-5 h-5 text-red-600"></i>
                                                Bukti pembayaran perlu diperbaiki. Silakan upload ulang bukti pembayaran.
                                            </h5>
                                            @if($payment->catatan_admin)
                                                <p class="text-sm text-red-700"><strong>Catatan Admin:</strong> {{ $payment->catatan_admin }}</p>
                                            @endif
                                        @endif

                                        @if($waUrl && ($isPaymentWaiting || session('success')))
                                            <a href="{{ $waUrl }}" target="_blank" class="mt-4 inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-lg transition-colors">
                                                <i data-lucide="message-circle" class="w-4 h-4"></i> Hubungi Admin via WhatsApp
                                            </a>
                                        @endif
                                    </div>
                                @endif

                                @if($payment)
                                    <div class="mt-4 rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-[#434463] dark:bg-[#232333]">
                                        <h5 class="mb-2 text-sm font-bold text-gray-800 dark:text-[#d5d5e2]">Status Daftar Ulang / Pembayaran</h5>
                                        <div class="flex flex-wrap items-center gap-3 mb-2">
                                            @if($isPaymentLunas)
                                                <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-secondary-100 text-secondary-800">Lunas / Diverifikasi</span>
                                            @elseif($isPaymentRejected)
                                                <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Ditolak / Perlu Revisi</span>
                                            @else
                                                <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Menunggu Verifikasi Admin</span>
                                            @endif
                                            <span class="text-sm font-medium text-gray-700 dark:text-[#d5d5e2]">Rp {{ number_format($payment->jumlah, 0, ',', '.') }}</span>
                                        </div>

                                        @if($payment->catatan_admin && $isPaymentRejected)
                                            <p class="text-xs text-red-600 mt-2"><strong>Catatan Admin:</strong> {{ $payment->catatan_admin }}</p>
                                        @endif
                                    </div>
                                @endif

                                @if($reg->status === 'diterima')
                                    <div class="relative z-10 mt-6 flex flex-wrap gap-3 border-t border-gray-100 pt-6 dark:border-[#434463]">
                                        <a href="{{ route('parent.siswa.kartu') }}" target="_blank" class="inline-flex w-full cursor-pointer items-center justify-center gap-2 rounded-lg bg-secondary-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition-colors hover:bg-secondary-700 sm:w-auto">
                                            <i data-lucide="printer" class="w-4 h-4"></i> Cetak Kartu Pendaftaran
                                        </a>

                                        @if($isPaymentLunas)
                                            <a href="{{ route('parent.pembayaran.receipt', $reg->id) }}" target="_blank" class="inline-flex w-full cursor-pointer items-center justify-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition-colors hover:bg-blue-700 sm:w-auto">
                                                <i data-lucide="file-down" class="w-4 h-4"></i> Cetak Bukti Bayar (PDF)
                                            </a>
                                        @elseif(!$payment || $isPaymentRejected)
                                            <button type="button" onclick="document.getElementById('modalPayment-{{ $reg->id }}').classList.remove('hidden')" class="inline-flex w-full cursor-pointer items-center justify-center gap-2 rounded-lg border border-primary-600 bg-white px-4 py-2 text-sm font-medium text-primary-600 shadow-sm transition-colors hover:bg-primary-50 dark:bg-[#2b2c40] dark:hover:bg-[#696cff]/10 sm:w-auto">
                                                <i data-lucide="upload" class="w-4 h-4"></i> {{ $payment ? 'Upload Ulang Bukti Pembayaran' : 'Upload Bukti Pembayaran' }}
                                            </button>
                                        @else
                                            <span class="inline-flex w-full items-center justify-center gap-2 rounded-lg border border-yellow-200 bg-yellow-50 px-4 py-2 text-sm font-medium text-yellow-700 dark:border-yellow-500/20 dark:bg-yellow-500/10 dark:text-yellow-300 sm:w-auto">
                                                <i data-lucide="clock" class="w-4 h-4"></i> Menunggu Verifikasi Pembayaran
                                            </span>
                                        @endif
                                    </div>
                                @elseif($reg->status === 'perlu_revisi')
                                    <div class="relative z-10 mt-6 flex gap-4 border-t border-gray-100 pt-6 dark:border-[#434463]">
                                        <a href="{{ route('parent.siswa.edit', $reg->siswa_id) }}" class="inline-flex w-full cursor-pointer items-center justify-center gap-2 rounded-lg bg-orange-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition-colors hover:bg-orange-700 sm:w-auto">
                                            <i data-lucide="edit-3" class="w-4 h-4"></i> Perbaiki Dokumen Anak
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>

                        @if($reg->status === 'diterima' && (!$payment || $isPaymentRejected))
                            <div id="modalPayment-{{ $reg->id }}" class="fixed inset-0 z-[100] hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                                <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" onclick="document.getElementById('modalPayment-{{ $reg->id }}').classList.add('hidden')"></div>
                                <div class="fixed inset-0 z-10 overflow-y-auto">
                                    <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0 pointer-events-none">
                                        <div class="pointer-events-auto relative transform overflow-hidden rounded-xl bg-white text-left shadow-xl transition-all dark:bg-[#2b2c40] sm:my-8 sm:w-full sm:max-w-md">
                                            <div class="bg-primary-800 px-6 py-4 flex items-center justify-between">
                                                <h3 class="text-lg font-heading font-bold text-white flex items-center gap-2">
                                                    <i data-lucide="credit-card" class="w-5 h-5"></i> Daftar Ulang
                                                </h3>
                                                <button type="button" onclick="document.getElementById('modalPayment-{{ $reg->id }}').classList.add('hidden')" class="text-primary-200 hover:text-white transition-colors">
                                                    <i data-lucide="x" class="w-5 h-5"></i>
                                                </button>
                                            </div>

                                            <form action="{{ route('parent.pembayaran.store', $reg->id) }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <div class="space-y-5 px-4 py-5 sm:px-6 sm:py-6">
                                                    <div>
                                                        <p class="mb-3 text-sm font-semibold text-gray-800 dark:text-[#d5d5e2]">Informasi pembayaran:</p>
                                                        @include('parent.components.payment-information', ['paymentSetting' => $paymentSetting])
                                                    </div>

                                                    <div>
                                                        <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-[#d5d5e2]">Bukti Transfer <span class="text-red-500">*</span></label>
                                                        <input type="file" name="bukti_bayar" accept="image/jpeg,image/png,image/jpg,application/pdf" required class="block w-full rounded-lg border border-gray-300 text-sm text-gray-500 file:mr-3 file:border-0 file:bg-primary-50 file:px-3 file:py-2 file:text-sm file:font-semibold file:text-primary-700 hover:file:bg-primary-100 dark:border-[#434463] dark:bg-[#232333] dark:text-[#a1b0cb] sm:file:mr-4 sm:file:px-4">
                                                        <p class="mt-1 text-xs text-gray-500 dark:text-[#a1b0cb]">Format JPG, PNG, atau PDF max 2MB.</p>
                                                    </div>
                                                </div>

                                                <div class="flex flex-col-reverse justify-end gap-3 rounded-b-xl border-t border-gray-200 bg-gray-50 px-4 py-4 dark:border-[#434463] dark:bg-[#232333] sm:flex-row sm:px-6">
                                                    <button type="button" onclick="document.getElementById('modalPayment-{{ $reg->id }}').classList.add('hidden')" class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-[#434463] dark:bg-[#2b2c40] dark:text-[#d5d5e2] dark:hover:bg-[#434463]">Batal</button>
                                                    <button type="submit" class="flex items-center justify-center gap-2 rounded-lg bg-primary-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-primary-700">
                                                        <i data-lucide="upload" class="w-4 h-4"></i> Unggah Bukti
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
