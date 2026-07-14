@extends('layouts.app')

@section('title', 'Detail & Verifikasi Pendaftaran')
@section('header_title', 'Verifikasi Dokumen Pendaftar')

@section('content')
<!-- Fancybox CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css" />

@php
    $payment = $detail->pembayaran;
    $paymentStatus = $payment?->status;
    $isPaymentWaiting = $payment && in_array($paymentStatus, ['pending', 'menunggu_verifikasi'], true);
    $isPaymentLunas = $paymentStatus === 'lunas';
    $isPaymentRejected = $paymentStatus === 'ditolak';
@endphp

<div class="max-w-7xl mx-auto">

    <!-- Back Button & Header -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <a href="{{ route('admin.verifikasi.index') }}" class="inline-flex items-center gap-2 text-sm font-medium text-[#697a8d] dark:text-[#a1b0cb] hover:text-[#696cff] transition-colors">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali ke Daftar
        </a>

        <div class="flex flex-wrap items-center gap-2 sm:gap-3">
            <span class="text-sm font-medium text-[#697a8d] dark:text-[#a1b0cb]">Status Saat Ini:</span>
            @if($detail->status === 'pending')
                <span class="sneat-badge bg-[#f5f5f9] dark:bg-[#232333] text-[#697a8d] dark:text-[#a1b0cb] border border-[#d9dee3] dark:border-[#434463]">Pending</span>
            @elseif($detail->status === 'menunggu_verifikasi')
                <span class="sneat-badge bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-200 dark:border-amber-500/20">Menunggu Verifikasi Berkas</span>
            @elseif($detail->status === 'diterima')
                <span class="sneat-badge bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/20">Berkas Terverifikasi – Lanjut Observasi</span>
            @elseif($detail->status === 'ditolak')
                <span class="sneat-badge bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 border border-red-200 dark:border-red-500/20">Pendaftaran Ditolak</span>
            @elseif($detail->status === 'perlu_revisi')
                <span class="sneat-badge bg-orange-50 dark:bg-orange-500/10 text-orange-600 dark:text-orange-400 border border-orange-200 dark:border-orange-500/20">Perlu Revisi Data</span>
            @endif
        </div>
    </div>

    <!-- Split View Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Left Column: Data & Dokumen (Span 2) -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Profil Anak -->
            <div class="bg-white dark:bg-[#2b2c40] rounded-lg shadow-sneat dark:shadow-sneat-dark border border-[#d9dee3] dark:border-[#434463] overflow-hidden">
                <div class="bg-[#e7e7ff] dark:bg-[#696cff]/20 px-6 py-4 border-b border-[#d9dee3] dark:border-[#434463] flex flex-wrap items-center justify-between gap-4">
                    <div class="flex items-center gap-2">
                        <i data-lucide="user" class="w-5 h-5 text-[#696cff]"></i>
                        <h3 class="font-heading font-semibold text-[#566a7f] dark:text-[#d5d5e2]">Profil Calon Siswa</h3>
                    </div>
                    <a href="{{ route('admin.siswa.show', $detail->siswa->id) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1 bg-white hover:bg-gray-50 border border-gray-200 dark:bg-[#2b2c40] dark:hover:bg-[#434463] dark:border-[#434463] text-xs font-semibold text-[#696cff] rounded transition-colors">
                        <i data-lucide="external-link" class="w-3.5 h-3.5"></i> Detail Lengkap Siswa
                    </a>
                </div>
                <div class="p-4 sm:p-6">
                    <div class="flex flex-col sm:flex-row gap-6 mb-6">
                        <img src="{{ $detail->siswa->foto ? Storage::url($detail->siswa->foto) : asset('images/default-avatar.png') }}" class="w-24 h-32 object-cover rounded-lg border border-[#d9dee3] dark:border-[#434463] shadow-sm cursor-pointer hover:opacity-80 hover:shadow-lg transition-all duration-300" alt="Foto Siswa" onclick="openLightbox(this.src)">
                        <div class="flex-1 space-y-2.5">
                            <div>
                                <p class="text-xs text-[#a1b0cb] uppercase tracking-wider">Nama Lengkap</p>
                                <p class="font-bold text-[#566a7f] dark:text-[#d5d5e2] text-lg">{{ $detail->siswa->nama ?? '-' }}</p>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-xs text-[#a1b0cb] uppercase">No. Pendaftaran</p>
                                    <p class="font-semibold text-[#566a7f] dark:text-[#d5d5e2]">{{ $detail->nomor_pendaftaran }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-[#a1b0cb] uppercase">Jenis Kelamin</p>
                                    <p class="font-semibold text-[#566a7f] dark:text-[#d5d5e2]">{{ $detail->siswa->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Navigation Tabs -->
                    <div class="flex border-b border-[#d9dee3] dark:border-[#434463] mb-5 overflow-x-auto scrollbar-none gap-2">
                        <button type="button" onclick="switchVerificationTab('tab-data-anak')" id="btn-tab-data-anak" class="verification-tab-btn px-4 py-2 text-xs font-bold uppercase tracking-wider border-b-2 border-[#696cff] text-[#696cff] focus:outline-none transition-colors whitespace-nowrap">
                            Data Anak
                        </button>
                        <button type="button" onclick="switchVerificationTab('tab-alamat')" id="btn-tab-alamat" class="verification-tab-btn px-4 py-2 text-xs font-bold uppercase tracking-wider border-b-2 border-transparent text-[#697a8d] dark:text-[#a1b0cb] hover:text-[#696cff] focus:outline-none transition-colors whitespace-nowrap">
                            Alamat & Kontak
                        </button>
                        <button type="button" onclick="switchVerificationTab('tab-keluarga')" id="btn-tab-keluarga" class="verification-tab-btn px-4 py-2 text-xs font-bold uppercase tracking-wider border-b-2 border-transparent text-[#697a8d] dark:text-[#a1b0cb] hover:text-[#696cff] focus:outline-none transition-colors whitespace-nowrap">
                            Orang Tua / Wali
                        </button>
                        <button type="button" onclick="switchVerificationTab('tab-pendaftaran')" id="btn-tab-pendaftaran" class="verification-tab-btn px-4 py-2 text-xs font-bold uppercase tracking-wider border-b-2 border-transparent text-[#697a8d] dark:text-[#a1b0cb] hover:text-[#696cff] focus:outline-none transition-colors whitespace-nowrap">
                            Pendaftaran
                        </button>
                    </div>

                    <!-- Tab Contents -->
                    <div class="space-y-4 text-sm text-[#566a7f] dark:text-[#d5d5e2]">
                        <!-- Tab 1: Data Anak -->
                        <div id="tab-data-anak" class="verification-tab-content block">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="bg-slate-50/50 dark:bg-[#232333]/30 p-3.5 rounded-lg border border-slate-100 dark:border-[#434463]/50">
                                    <span class="text-xs text-[#a1b0cb] block mb-0.5">Nama Panggilan</span>
                                    <span class="font-medium text-gray-900 dark:text-[#e7e7ff]">{{ $detail->siswa->nama_panggilan ?: '-' }}</span>
                                </div>
                                <div class="bg-slate-50/50 dark:bg-[#232333]/30 p-3.5 rounded-lg border border-slate-100 dark:border-[#434463]/50">
                                    <span class="text-xs text-[#a1b0cb] block mb-0.5">Tempat, Tanggal Lahir</span>
                                    <span class="font-medium text-gray-900 dark:text-[#e7e7ff]">{{ $detail->siswa->tempat_lahir ?: '-' }}, {{ $detail->siswa->tanggal_lahir ? \Carbon\Carbon::parse($detail->siswa->tanggal_lahir)->translatedFormat('d F Y') : '-' }}</span>
                                </div>
                                <div class="bg-slate-50/50 dark:bg-[#232333]/30 p-3.5 rounded-lg border border-slate-100 dark:border-[#434463]/50">
                                    <span class="text-xs text-[#a1b0cb] block mb-0.5">Agama</span>
                                    <span class="font-medium text-gray-900 dark:text-[#e7e7ff]">{{ $detail->siswa->agama ?: '-' }}</span>
                                </div>
                                <div class="bg-slate-50/50 dark:bg-[#232333]/30 p-3.5 rounded-lg border border-slate-100 dark:border-[#434463]/50">
                                    <span class="text-xs text-[#a1b0cb] block mb-0.5">Anak Ke</span>
                                    <span class="font-medium text-gray-900 dark:text-[#e7e7ff]">{{ $detail->siswa->anak_ke ?: '-' }}</span>
                                </div>
                                <div class="bg-slate-50/50 dark:bg-[#232333]/30 p-3.5 rounded-lg border border-slate-100 dark:border-[#434463]/50">
                                    <span class="text-xs text-[#a1b0cb] block mb-0.5">Jumlah Saudara</span>
                                    <span class="font-medium text-gray-900 dark:text-[#e7e7ff]">{{ $detail->siswa->jumlah_saudara !== null ? $detail->siswa->jumlah_saudara : '-' }}</span>
                                </div>
                                <div class="bg-slate-50/50 dark:bg-[#232333]/30 p-3.5 rounded-lg border border-slate-100 dark:border-[#434463]/50">
                                    <span class="text-xs text-[#a1b0cb] block mb-0.5">Hobi</span>
                                    <span class="font-medium text-gray-900 dark:text-[#e7e7ff]">{{ $detail->siswa->hobi ?: '-' }}</span>
                                </div>
                                <div class="bg-slate-50/50 dark:bg-[#232333]/30 p-3.5 rounded-lg border border-slate-100 dark:border-[#434463]/50">
                                    <span class="text-xs text-[#a1b0cb] block mb-0.5">Cita-Cita</span>
                                    <span class="font-medium text-gray-900 dark:text-[#e7e7ff]">{{ $detail->siswa->cita_cita ?: '-' }}</span>
                                </div>
                                <div class="bg-slate-50/50 dark:bg-[#232333]/30 p-3.5 rounded-lg border border-slate-100 dark:border-[#434463]/50">
                                    <span class="text-xs text-[#a1b0cb] block mb-0.5">Moda Transportasi</span>
                                    <span class="font-medium text-gray-900 dark:text-[#e7e7ff]">{{ $detail->siswa->transportasi ?: '-' }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Tab 2: Alamat & Kontak -->
                        <div id="tab-alamat" class="verification-tab-content hidden">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                                <div class="bg-slate-50/50 dark:bg-[#232333]/30 p-3.5 rounded-lg border border-slate-100 dark:border-[#434463]/50">
                                    <span class="text-xs text-[#a1b0cb] block mb-0.5">No. Telepon / WA</span>
                                    <span class="font-semibold text-[#696cff]">{{ $detail->siswa->no_telpon ?: '-' }}</span>
                                </div>
                                <div class="bg-slate-50/50 dark:bg-[#232333]/30 p-3.5 rounded-lg border border-slate-100 dark:border-[#434463]/50">
                                    <span class="text-xs text-[#a1b0cb] block mb-0.5">Jenis Tempat Tinggal</span>
                                    <span class="font-medium text-gray-900 dark:text-[#e7e7ff]">{{ $detail->siswa->jenis_tempat_tinggal ?: '-' }}</span>
                                </div>
                                <div class="bg-slate-50/50 dark:bg-[#232333]/30 p-3.5 rounded-lg border border-slate-100 dark:border-[#434463]/50">
                                    <span class="text-xs text-[#a1b0cb] block mb-0.5">Provinsi</span>
                                    <span class="font-medium text-gray-900 dark:text-[#e7e7ff]">{{ $detail->siswa->provinsi ?: '-' }}</span>
                                </div>
                                <div class="bg-slate-50/50 dark:bg-[#232333]/30 p-3.5 rounded-lg border border-slate-100 dark:border-[#434463]/50">
                                    <span class="text-xs text-[#a1b0cb] block mb-0.5">Kota / Kabupaten</span>
                                    <span class="font-medium text-gray-900 dark:text-[#e7e7ff]">{{ $detail->siswa->kota ?: '-' }}</span>
                                </div>
                                <div class="bg-slate-50/50 dark:bg-[#232333]/30 p-3.5 rounded-lg border border-slate-100 dark:border-[#434463]/50">
                                    <span class="text-xs text-[#a1b0cb] block mb-0.5">Kecamatan</span>
                                    <span class="font-medium text-gray-900 dark:text-[#e7e7ff]">{{ $detail->siswa->kecamatan ?: '-' }}</span>
                                </div>
                                <div class="bg-slate-50/50 dark:bg-[#232333]/30 p-3.5 rounded-lg border border-slate-100 dark:border-[#434463]/50">
                                    <span class="text-xs text-[#a1b0cb] block mb-0.5">Kelurahan / Desa</span>
                                    <span class="font-medium text-gray-900 dark:text-[#e7e7ff]">{{ $detail->siswa->kelurahan ?: '-' }}</span>
                                </div>
                                <div class="bg-slate-50/50 dark:bg-[#232333]/30 p-3.5 rounded-lg border border-slate-100 dark:border-[#434463]/50 sm:col-span-2">
                                    <span class="text-xs text-[#a1b0cb] block mb-0.5">Kode Pos</span>
                                    <span class="font-medium text-gray-900 dark:text-[#e7e7ff]">{{ $detail->siswa->kode_pos ?: '-' }}</span>
                                </div>
                            </div>
                            <div class="bg-slate-50/50 dark:bg-[#232333]/30 p-4 rounded-lg border border-slate-100 dark:border-[#434463]/50">
                                <span class="text-xs text-[#a1b0cb] block mb-1">Alamat Jalan / RT / RW</span>
                                <span class="font-medium text-gray-900 dark:text-[#e7e7ff] leading-relaxed">{{ $detail->siswa->alamat ?: '-' }}</span>
                            </div>
                        </div>

                        <!-- Tab 3: Orang Tua / Wali -->
                        <div id="tab-keluarga" class="verification-tab-content hidden space-y-4">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 bg-slate-50 dark:bg-[#232333]/20 p-4 rounded-lg border border-slate-100 dark:border-[#434463]/50">
                                <div>
                                    <span class="text-xs text-[#a1b0cb] block mb-0.5">Nomor Kartu Keluarga (KK)</span>
                                    <span class="font-semibold font-mono text-gray-900 dark:text-[#e7e7ff]">{{ $detail->siswa->no_kk ?: '-' }}</span>
                                </div>
                                <div>
                                    <span class="text-xs text-[#a1b0cb] block mb-0.5">Nama Kepala Keluarga</span>
                                    <span class="font-semibold text-gray-900 dark:text-[#e7e7ff]">{{ $detail->siswa->kepala_keluarga ?: '-' }}</span>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                @if($detail->siswa->tinggal_bersama === 'wali')
                                    <div class="bg-primary-50/50 dark:bg-primary-500/5 p-4 rounded-lg border border-primary-100 dark:border-primary-500/20 space-y-2 sm:col-span-2 text-sm">
                                        <h4 class="font-bold text-primary-800 dark:text-primary-400 flex items-center gap-1.5 border-b border-primary-100 dark:border-primary-500/10 pb-1.5 mb-2">
                                            <i data-lucide="user-check" class="w-3.5 h-3.5"></i> Data Wali Anak (Tinggal Bersama Wali)
                                        </h4>
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-2 text-xs">
                                            <div class="flex justify-between border-b border-gray-50 dark:border-gray-800 pb-1">
                                                <span class="text-gray-500">Nama Wali</span>
                                                <span class="font-semibold text-gray-900 dark:text-[#e7e7ff]">{{ $detail->siswa->nama_wali ?: '-' }}</span>
                                            </div>
                                            <div class="flex justify-between border-b border-gray-50 dark:border-gray-800 pb-1">
                                                <span class="text-gray-500">NIK Wali</span>
                                                <span class="font-medium font-mono text-gray-900 dark:text-[#e7e7ff]">{{ $detail->siswa->nik_wali ?: '-' }}</span>
                                            </div>
                                            <div class="flex justify-between border-b border-gray-50 dark:border-gray-800 pb-1">
                                                <span class="text-gray-500">Hubungan Wali</span>
                                                <span class="font-medium text-gray-900 dark:text-[#e7e7ff]">{{ $detail->siswa->hubungan_wali ?: '-' }}</span>
                                            </div>
                                            <div class="flex justify-between border-b border-gray-50 dark:border-gray-800 pb-1">
                                                <span class="text-gray-500">Telepon Wali</span>
                                                <span class="font-medium text-gray-900 dark:text-[#e7e7ff]">{{ $detail->siswa->no_telpon_wali ?: '-' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <!-- Ayah -->
                                <div class="bg-white dark:bg-[#2b2c40] p-4 rounded-lg border border-gray-150 dark:border-[#434463] space-y-2">
                                    <h4 class="font-bold text-[#696cff] flex items-center gap-1.5 border-b border-gray-100 dark:border-[#434463]/80 pb-1.5 mb-2">
                                        <i data-lucide="user" class="w-3.5 h-3.5"></i> Data Ayah
                                    </h4>
                                    <div class="flex justify-between text-xs">
                                        <span class="text-gray-500 dark:text-gray-400">Nama</span>
                                        <span class="font-semibold text-gray-900 dark:text-[#e7e7ff]">{{ $detail->siswa->nama_ayah ?: '-' }}</span>
                                    </div>
                                    <div class="flex justify-between text-xs">
                                        <span class="text-gray-500 dark:text-gray-400">NIK</span>
                                        <span class="font-medium font-mono text-gray-900 dark:text-[#e7e7ff]">{{ $detail->siswa->nik_ayah ?: '-' }}</span>
                                    </div>
                                    <div class="flex justify-between text-xs">
                                        <span class="text-gray-500 dark:text-gray-400">Tgl Lahir</span>
                                        <span class="font-medium text-gray-900 dark:text-[#e7e7ff]">{{ $detail->siswa->tanggal_lahir_ayah ? \Carbon\Carbon::parse($detail->siswa->tanggal_lahir_ayah)->translatedFormat('d F Y') : '-' }}</span>
                                    </div>
                                    <div class="flex justify-between text-xs">
                                        <span class="text-gray-500 dark:text-gray-400">Pendidikan</span>
                                        <span class="font-medium text-gray-900 dark:text-[#e7e7ff]">{{ $detail->siswa->pendidikan_ayah ?: '-' }}</span>
                                    </div>
                                    <div class="flex justify-between text-xs">
                                        <span class="text-gray-500 dark:text-gray-400">Pekerjaan</span>
                                        <span class="font-medium text-gray-900 dark:text-[#e7e7ff]">{{ $detail->siswa->pekerjaan_ayah ?: '-' }}</span>
                                    </div>
                                    <div class="flex justify-between text-xs">
                                        <span class="text-gray-500 dark:text-gray-400">Penghasilan</span>
                                        <span class="font-medium text-gray-900 dark:text-[#e7e7ff]">{{ $detail->siswa->penghasilan_ayah ?: '-' }}</span>
                                    </div>
                                </div>
                                <!-- Ibu -->
                                <div class="bg-white dark:bg-[#2b2c40] p-4 rounded-lg border border-gray-150 dark:border-[#434463] space-y-2">
                                    <h4 class="font-bold text-[#696cff] flex items-center gap-1.5 border-b border-gray-100 dark:border-[#434463]/80 pb-1.5 mb-2">
                                        <i data-lucide="user" class="w-3.5 h-3.5"></i> Data Ibu
                                    </h4>
                                    <div class="flex justify-between text-xs">
                                        <span class="text-gray-500 dark:text-gray-400">Nama</span>
                                        <span class="font-semibold text-gray-900 dark:text-[#e7e7ff]">{{ $detail->siswa->nama_ibu ?: '-' }}</span>
                                    </div>
                                    <div class="flex justify-between text-xs">
                                        <span class="text-gray-500 dark:text-gray-400">NIK</span>
                                        <span class="font-medium font-mono text-gray-900 dark:text-[#e7e7ff]">{{ $detail->siswa->nik_ibu ?: '-' }}</span>
                                    </div>
                                    <div class="flex justify-between text-xs">
                                        <span class="text-gray-500 dark:text-gray-400">Tgl Lahir</span>
                                        <span class="font-medium text-gray-900 dark:text-[#e7e7ff]">{{ $detail->siswa->tanggal_lahir_ibu ? \Carbon\Carbon::parse($detail->siswa->tanggal_lahir_ibu)->translatedFormat('d F Y') : '-' }}</span>
                                    </div>
                                    <div class="flex justify-between text-xs">
                                        <span class="text-gray-500 dark:text-gray-400">Pendidikan</span>
                                        <span class="font-medium text-gray-900 dark:text-[#e7e7ff]">{{ $detail->siswa->pendidikan_ibu ?: '-' }}</span>
                                    </div>
                                    <div class="flex justify-between text-xs">
                                        <span class="text-gray-500 dark:text-gray-400">Pekerjaan</span>
                                        <span class="font-medium text-gray-900 dark:text-[#e7e7ff]">{{ $detail->siswa->pekerjaan_ibu ?: '-' }}</span>
                                    </div>
                                    <div class="flex justify-between text-xs">
                                        <span class="text-gray-500 dark:text-gray-400">Penghasilan</span>
                                        <span class="font-medium text-gray-900 dark:text-[#e7e7ff]">{{ $detail->siswa->penghasilan_ibu ?: '-' }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-slate-50 dark:bg-[#232333]/30 p-3.5 rounded-lg border border-slate-100 dark:border-[#434463]/50">
                                <span class="text-xs text-[#a1b0cb] block mb-0.5">Akun Wali Penginput (Sistem)</span>
                                <span class="font-semibold text-gray-900 dark:text-[#e7e7ff]">
                                    @if($detail->siswa->user)
                                        {{ $detail->siswa->user->name }} ({{ $detail->siswa->user->email }})
                                    @else
                                        <span class="text-gray-400 font-normal italic">Dibuat Manual oleh Admin</span>
                                    @endif
                                </span>
                            </div>
                        </div>

                        <!-- Tab 4: Pendaftaran -->
                        <div id="tab-pendaftaran" class="verification-tab-content hidden space-y-4">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="bg-slate-50/50 dark:bg-[#232333]/30 p-3.5 rounded-lg border border-slate-100 dark:border-[#434463]/50">
                                    <span class="text-xs text-[#a1b0cb] block mb-0.5">Gelombang Pendaftaran</span>
                                    <span class="font-semibold text-[#696cff]">{{ $detail->pendaftaran->gelombang ?? '-' }}</span>
                                </div>
                                <div class="bg-slate-50/50 dark:bg-[#232333]/30 p-3.5 rounded-lg border border-slate-100 dark:border-[#434463]/50">
                                    <span class="text-xs text-[#a1b0cb] block mb-0.5">Tahun Ajaran</span>
                                    <span class="font-medium text-gray-900 dark:text-[#e7e7ff]">{{ $detail->pendaftaran->tahun_ajaran ?? '-' }}</span>
                                </div>
                                <div class="bg-slate-50/50 dark:bg-[#232333]/30 p-3.5 rounded-lg border border-slate-100 dark:border-[#434463]/50">
                                    <span class="text-xs text-[#a1b0cb] block mb-0.5">Kuota Gelombang</span>
                                    <span class="font-medium text-gray-900 dark:text-[#e7e7ff]">{{ $detail->pendaftaran->kuota ?? '-' }} Pendaftar</span>
                                </div>
                                <div class="bg-slate-50/50 dark:bg-[#232333]/30 p-3.5 rounded-lg border border-slate-100 dark:border-[#434463]/50">
                                    <span class="text-xs text-[#a1b0cb] block mb-0.5">Tanggal Mendaftar</span>
                                    <span class="font-medium text-gray-900 dark:text-[#e7e7ff]">{{ $detail->created_at?->translatedFormat('d F Y H:i') ?: '-' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                function switchVerificationTab(tabId) {
                    document.querySelectorAll('.verification-tab-content').forEach(content => {
                        content.classList.add('hidden');
                        content.classList.remove('block');
                    });
                    document.getElementById(tabId).classList.remove('hidden');
                    document.getElementById(tabId).classList.add('block');

                    document.querySelectorAll('.verification-tab-btn').forEach(btn => {
                        btn.classList.remove('border-[#696cff]', 'text-[#696cff]');
                        btn.classList.add('border-transparent', 'text-[#697a8d]', 'dark:text-[#a1b0cb]');
                    });
                    const activeBtn = document.getElementById('btn-' + tabId);
                    activeBtn.classList.remove('border-transparent', 'text-[#697a8d]', 'dark:text-[#a1b0cb]');
                    activeBtn.classList.add('border-[#696cff]', 'text-[#696cff]');
                }
            </script>

            <!-- Pratinjau Dokumen -->
            <div class="bg-white dark:bg-[#2b2c40] rounded-lg shadow-sneat dark:shadow-sneat-dark border border-[#d9dee3] dark:border-[#434463] overflow-hidden">
                <div class="bg-[#e7e7ff] dark:bg-[#696cff]/20 px-6 py-4 border-b border-[#d9dee3] dark:border-[#434463] flex items-center gap-2">
                    <i data-lucide="folder-open" class="w-5 h-5 text-[#696cff]"></i>
                    <h3 class="font-heading font-semibold text-[#566a7f] dark:text-[#d5d5e2]">Berkas Lampiran</h3>
                </div>
                <div class="grid grid-cols-1 gap-4 p-4 sm:grid-cols-2 sm:p-6">

                    <!-- KK -->
                    <div class="flex flex-col items-center rounded-lg border border-[#d9dee3] p-4 text-center transition-colors hover:border-[#696cff] hover:bg-[#f5f5f9] dark:border-[#434463] dark:hover:bg-[#232333] {{ $detail->siswa->foto_kk ? 'border-emerald-200 dark:border-emerald-500/20' : '' }}">
                        <i data-lucide="file-text" class="w-12 h-12 text-[#a1b0cb] mb-3"></i>
                        <h4 class="font-medium text-[#566a7f] dark:text-[#d5d5e2] mb-1">Kartu Keluarga (KK)</h4>
                        <p class="text-xs text-[#a1b0cb] mb-4">No: {{ $detail->siswa->no_kk ?? '-' }}</p>
                        @if($detail->siswa->foto_kk)
                            <a href="{{ route('dokumen.show', ['siswa' => $detail->siswa, 'field' => 'foto_kk']) }}" onclick="openLightbox(this.href); event.preventDefault();" class="inline-flex w-full cursor-pointer items-center justify-center gap-2 rounded-md bg-[#e7e7ff] px-3 py-2 text-sm font-medium text-[#696cff] transition-colors duration-200 hover:bg-[#d4d5ff] dark:bg-[#696cff]/20 dark:hover:bg-[#696cff]/30">
                                <i data-lucide="zoom-in" class="w-4 h-4"></i> Perbesar Dokumen
                            </a>
                        @else
                            <span class="inline-flex w-full items-center justify-center gap-2 rounded-md bg-red-50 text-red-700 px-3 py-2 text-sm font-medium border border-red-100 dark:bg-red-500/10 dark:text-red-400 dark:border-red-500/20">
                                Tidak Tersedia
                            </span>
                        @endif
                    </div>

                    <!-- Akta -->
                    <div class="flex flex-col items-center rounded-lg border border-[#d9dee3] p-4 text-center transition-colors hover:border-[#696cff] hover:bg-[#f5f5f9] dark:border-[#434463] dark:hover:bg-[#232333] {{ $detail->siswa->foto_akta ? 'border-emerald-200 dark:border-emerald-500/20' : '' }}">
                        <i data-lucide="file-badge-2" class="w-12 h-12 text-[#a1b0cb] mb-3"></i>
                        <h4 class="font-medium text-[#566a7f] dark:text-[#d5d5e2] mb-1">Akta Kelahiran</h4>
                        <p class="text-xs text-[#a1b0cb] mb-4">Pastikan terbaca jelas</p>
                        @if($detail->siswa->foto_akta)
                            <a href="{{ route('dokumen.show', ['siswa' => $detail->siswa, 'field' => 'foto_akta']) }}" onclick="openLightbox(this.href); event.preventDefault();" class="inline-flex w-full cursor-pointer items-center justify-center gap-2 rounded-md bg-[#e7e7ff] px-3 py-2 text-sm font-medium text-[#696cff] transition-colors duration-200 hover:bg-[#d4d5ff] dark:bg-[#696cff]/20 dark:hover:bg-[#696cff]/30">
                                <i data-lucide="zoom-in" class="w-4 h-4"></i> Perbesar Dokumen
                            </a>
                        @else
                            <span class="inline-flex w-full items-center justify-center gap-2 rounded-md bg-red-50 text-red-700 px-3 py-2 text-sm font-medium border border-red-100 dark:bg-red-500/10 dark:text-red-400 dark:border-red-500/20">
                                Tidak Tersedia
                            </span>
                        @endif
                    </div>

                    <!-- KTP Ayah / Ibu or Wali conditionally -->
                    @if($detail->siswa->tinggal_bersama === 'orang_tua')
                        <!-- KTP Ayah -->
                        <div class="flex flex-col items-center rounded-lg border border-[#d9dee3] p-4 text-center transition-colors hover:border-[#696cff] hover:bg-[#f5f5f9] dark:border-[#434463] dark:hover:bg-[#232333] {{ $detail->siswa->foto_ktp_ayah ? 'border-emerald-200 dark:border-emerald-500/20' : '' }}">
                            <i data-lucide="file-digit" class="w-12 h-12 text-[#a1b0cb] mb-3"></i>
                            <h4 class="font-medium text-[#566a7f] dark:text-[#d5d5e2] mb-1">KTP Ayah</h4>
                            <p class="text-xs text-[#a1b0cb] mb-4 font-mono">Pastikan NIK Ayah cocok</p>
                            @if($detail->siswa->foto_ktp_ayah)
                                <a href="{{ route('dokumen.show', ['siswa' => $detail->siswa, 'field' => 'foto_ktp_ayah']) }}" onclick="openLightbox(this.href); event.preventDefault();" class="inline-flex w-full cursor-pointer items-center justify-center gap-2 rounded-md bg-[#e7e7ff] px-3 py-2 text-sm font-medium text-[#696cff] transition-colors duration-200 hover:bg-[#d4d5ff] dark:bg-[#696cff]/20 dark:hover:bg-[#696cff]/30">
                                    <i data-lucide="zoom-in" class="w-4 h-4"></i> Perbesar Dokumen
                                </a>
                            @else
                                <span class="inline-flex w-full items-center justify-center gap-2 rounded-md bg-red-50 text-red-700 px-3 py-2 text-sm font-medium border border-red-100 dark:bg-red-500/10 dark:text-red-400 dark:border-red-500/20">
                                    Tidak Tersedia
                                </span>
                            @endif
                        </div>

                        <!-- KTP Ibu -->
                        <div class="flex flex-col items-center rounded-lg border border-[#d9dee3] p-4 text-center transition-colors hover:border-[#696cff] hover:bg-[#f5f5f9] dark:border-[#434463] dark:hover:bg-[#232333] {{ $detail->siswa->foto_ktp_ibu ? 'border-emerald-200 dark:border-emerald-500/20' : '' }}">
                            <i data-lucide="file-digit" class="w-12 h-12 text-[#a1b0cb] mb-3"></i>
                            <h4 class="font-medium text-[#566a7f] dark:text-[#d5d5e2] mb-1">KTP Ibu</h4>
                            <p class="text-xs text-[#a1b0cb] mb-4 font-mono">Pastikan NIK Ibu cocok</p>
                            @if($detail->siswa->foto_ktp_ibu)
                                <a href="{{ route('dokumen.show', ['siswa' => $detail->siswa, 'field' => 'foto_ktp_ibu']) }}" onclick="openLightbox(this.href); event.preventDefault();" class="inline-flex w-full cursor-pointer items-center justify-center gap-2 rounded-md bg-[#e7e7ff] px-3 py-2 text-sm font-medium text-[#696cff] transition-colors duration-200 hover:bg-[#d4d5ff] dark:bg-[#696cff]/20 dark:hover:bg-[#696cff]/30">
                                    <i data-lucide="zoom-in" class="w-4 h-4"></i> Perbesar Dokumen
                                </a>
                            @else
                                <span class="inline-flex w-full items-center justify-center gap-2 rounded-md bg-red-50 text-red-700 px-3 py-2 text-sm font-medium border border-red-100 dark:bg-red-500/10 dark:text-red-400 dark:border-red-500/20">
                                    Tidak Tersedia
                                </span>
                            @endif
                        </div>
                    @elseif($detail->siswa->tinggal_bersama === 'wali')
                        <!-- KTP Wali -->
                        <div class="flex flex-col items-center rounded-lg border border-[#d9dee3] p-4 text-center transition-colors hover:border-[#696cff] hover:bg-[#f5f5f9] dark:border-[#434463] dark:hover:bg-[#232333] {{ $detail->siswa->foto_ktp_wali ? 'border-emerald-200 dark:border-emerald-500/20' : '' }}">
                            <i data-lucide="file-digit" class="w-12 h-12 text-[#a1b0cb] mb-3"></i>
                            <h4 class="font-medium text-[#566a7f] dark:text-[#d5d5e2] mb-1">KTP Wali</h4>
                            <p class="text-xs text-[#a1b0cb] mb-4 font-mono">Pastikan NIK Wali cocok</p>
                            @if($detail->siswa->foto_ktp_wali)
                                <a href="{{ route('dokumen.show', ['siswa' => $detail->siswa, 'field' => 'foto_ktp_wali']) }}" onclick="openLightbox(this.href); event.preventDefault();" class="inline-flex w-full cursor-pointer items-center justify-center gap-2 rounded-md bg-[#e7e7ff] px-3 py-2 text-sm font-medium text-[#696cff] transition-colors duration-200 hover:bg-[#d4d5ff] dark:bg-[#696cff]/20 dark:hover:bg-[#696cff]/30">
                                    <i data-lucide="zoom-in" class="w-4 h-4"></i> Perbesar Dokumen
                                </a>
                            @else
                                <span class="inline-flex w-full items-center justify-center gap-2 rounded-md bg-red-50 text-red-700 px-3 py-2 text-sm font-medium border border-red-100 dark:bg-red-500/10 dark:text-red-400 dark:border-red-500/20">
                                    Tidak Tersedia
                                </span>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <!-- Pembayaran / Daftar Ulang -->
            @if($payment)
            <div class="bg-white dark:bg-[#2b2c40] rounded-lg shadow-sneat dark:shadow-sneat-dark border border-[#d9dee3] dark:border-[#434463] overflow-hidden mt-6">
                <div class="flex flex-col items-start justify-between gap-3 border-b border-blue-100 bg-blue-50 px-4 py-4 dark:border-blue-500/20 dark:bg-blue-500/10 sm:flex-row sm:items-center sm:px-6">
                    <div class="flex items-center gap-2">
                        <i data-lucide="credit-card" class="w-5 h-5 text-blue-600 dark:text-blue-400"></i>
                        <h3 class="font-heading font-semibold text-blue-900 dark:text-blue-300">Bukti Daftar Ulang (Pembayaran)</h3>
                    </div>
                    @if($isPaymentLunas)
                        <span class="px-3 py-1 bg-secondary-100 text-secondary-800 text-xs font-bold rounded-full">LUNAS</span>
                    @elseif($isPaymentRejected)
                        <span class="px-3 py-1 bg-red-100 text-red-800 text-xs font-bold rounded-full">DITOLAK</span>
                    @else
                        <span class="px-3 py-1 bg-yellow-100 text-yellow-800 text-xs font-bold rounded-full">PERLU VERIFIKASI</span>
                    @endif
                </div>

                <div class="grid grid-cols-1 gap-6 p-4 sm:p-6 md:grid-cols-2">
                    <div>
                        <p class="text-sm text-[#a1b0cb] mb-1">Nominal Ditransfer</p>
                        <p class="text-2xl font-bold text-[#566a7f] dark:text-[#d5d5e2] mb-4">Rp {{ number_format($payment->jumlah, 0, ',', '.') }}</p>

                        <a href="{{ route('dokumen.show', ['siswa' => $detail->siswa, 'field' => 'bukti_bayar', 'pembayaran' => $payment]) }}" target="_blank" class="inline-flex items-center justify-center w-full gap-2 px-4 py-2 bg-[#f5f5f9] dark:bg-[#232333] hover:bg-[#e7e7ff] dark:hover:bg-[#696cff]/10 text-[#566a7f] dark:text-[#d5d5e2] text-sm font-medium rounded-lg transition-all duration-300 border border-[#d9dee3] dark:border-[#434463] cursor-pointer hover:shadow-lg">
                            <i data-lucide="file-search" class="w-4 h-4"></i> Lihat Bukti Pembayaran
                        </a>

                        @if($payment->catatan_admin)
                        <div class="mt-4 p-3 bg-red-50 border border-red-100 rounded-lg">
                            <p class="text-xs text-red-800 font-semibold mb-1">Catatan Admin Sebelumnya:</p>
                            <p class="text-sm text-red-700">{{ $payment->catatan_admin }}</p>
                        </div>
                        @endif
                    </div>

                    <div class="border-t border-[#d9dee3] pt-6 dark:border-[#434463] md:border-l md:border-t-0 md:pl-6 md:pt-0">
                        <form action="{{ route('admin.pembayaran.verify', $payment->id) }}" method="POST" class="space-y-4">
                            @csrf
                            @method('PATCH')

                            <div>
                                <label class="block text-sm font-medium text-[#566a7f] dark:text-[#d5d5e2] mb-1">Tindakan</label>
                                <select name="status" id="paymentStatusSelect" class="sneat-input" onchange="togglePaymentNote()">
                                    <option value="">-- Pilih Keputusan --</option>
                                    <option value="lunas" {{ $isPaymentLunas ? 'selected' : '' }}>Terima (Lunas)</option>
                                    <option value="ditolak" {{ $isPaymentRejected ? 'selected' : '' }}>Tolak (Perlu Revisi)</option>
                                </select>
                            </div>

                            <div id="paymentNoteContainer" class="{{ $isPaymentRejected ? 'block' : 'hidden' }}">
                                <label class="block text-sm font-medium text-[#566a7f] dark:text-[#d5d5e2] mb-1">Catatan Penolakan</label>
                                <textarea name="catatan_admin" rows="3" class="sneat-input" placeholder="Contoh: Bukti buram, nominal tidak sesuai...">{{ $payment->catatan_admin }}</textarea>
                            </div>

                            @if($isPaymentLunas)
                                <button type="button" disabled class="w-full inline-flex items-center gap-2 justify-center py-2.5 px-4 rounded-md text-sm font-medium transition-all bg-gray-100 dark:bg-[#434463] text-gray-400 dark:text-gray-500 cursor-not-allowed">
                                    <i data-lucide="check-circle" class="w-4 h-4"></i> Pembayaran Sudah Disetujui
                                </button>
                            @else
                                <button type="submit" class="w-full sneat-btn-primary justify-center py-2.5">
                                    <i data-lucide="save" class="w-4 h-4"></i> Simpan Verifikasi Bayar
                                </button>
                            @endif
                        </form>
                    </div>
                </div>
            </div>

            <script>
                function togglePaymentNote() {
                    const status = document.getElementById('paymentStatusSelect').value;
                    const container = document.getElementById('paymentNoteContainer');
                    if (status === 'ditolak') {
                        container.classList.remove('hidden');
                    } else {
                        container.classList.add('hidden');
                    }
                }
            </script>
            @endif
        </div>

        <!-- Right Column: Aksi Verifikasi -->
        <div class="space-y-6 relative lg:sticky lg:top-6">
            <!-- Kelompok Siswa Confirmation Card -->
            <div class="overflow-hidden rounded-lg border border-[#d9dee3] bg-white shadow-sneat dark:border-[#434463] dark:bg-[#2b2c40] dark:shadow-sneat-dark">
                <div class="bg-[#2b2c40] dark:bg-[#232333] px-6 py-4 border-b border-[#434463] flex items-center gap-2">
                    <i data-lucide="tag" class="w-5 h-5 text-[#a1b0cb]"></i>
                    <h3 class="font-heading font-semibold text-white">Penetapan Kelompok</h3>
                </div>
                <div class="p-4 sm:p-6 text-sm space-y-4">
                    <div class="bg-gray-50 dark:bg-[#232333]/50 p-3.5 rounded-lg space-y-2 text-xs">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Usia Calon Siswa:</span>
                            <span class="font-semibold text-gray-900 dark:text-[#e7e7ff]">
                                @if($detail->usia_bulan_saat_acuan !== null)
                                    @php
                                        $years = (int) floor($detail->usia_bulan_saat_acuan / 12);
                                        $remainingMonths = (int) ($detail->usia_bulan_saat_acuan % 12);
                                    @endphp
                                    {{ $years }} tahun {{ $remainingMonths }} bulan ({{ $detail->usia_bulan_saat_acuan }} bln)
                                @else
                                    -
                                @endif
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Tanggal Acuan (1 Juli):</span>
                            <span class="font-semibold text-gray-900 dark:text-[#e7e7ff]">{{ $detail->tanggal_acuan_usia ? \Carbon\Carbon::parse($detail->tanggal_acuan_usia)->translatedFormat('d F Y') : '-' }}</span>
                        </div>
                        <div class="flex justify-between items-center pt-1 border-t border-dashed border-gray-200 dark:border-gray-700">
                            <span class="text-gray-500">Rekomendasi Kelompok:</span>
                            <span class="px-2 py-0.5 rounded font-semibold text-xs {{ $detail->kelompok_rekomendasi === 'A' ? 'bg-blue-50 text-blue-700 dark:bg-blue-500/10 dark:text-blue-400' : ($detail->kelompok_rekomendasi === 'B' ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-500/10 dark:text-indigo-400' : 'bg-amber-50 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400') }}">
                                {{ $detail->kelompok_rekomendasi ? 'Kelompok ' . $detail->kelompok_rekomendasi : 'Perlu Konfirmasi' }}
                            </span>
                        </div>
                    </div>

                    <form action="{{ route('admin.verifikasi.kelompok', $detail->id) }}" method="POST" class="space-y-3">
                        @csrf
                        @method('PATCH')
                        <div>
                            <label class="block text-xs font-semibold text-[#697a8d] dark:text-[#a1b0cb] uppercase tracking-wider mb-1">Pilih Kelompok Final</label>
                            <select name="kelompok_final" required class="sneat-input">
                                <option value="">-- Pilih Kelompok --</option>
                                <option value="A" {{ $detail->kelompok_final === 'A' ? 'selected' : '' }}>Kelompok A (Usia 4 - 5 Tahun)</option>
                                <option value="B" {{ $detail->kelompok_final === 'B' ? 'selected' : '' }}>Kelompok B (Usia 5 - 7 Tahun)</option>
                            </select>
                        </div>
                        <button type="submit" class="w-full flex justify-center items-center gap-2 py-2 px-4 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-lg transition-colors text-xs">
                            <i data-lucide="check-circle" class="w-4 h-4"></i> Tetapkan Kelompok Final
                        </button>
                    </form>

                    @if($detail->kelompok_final)
                        <div class="mt-4 pt-4 border-t border-gray-100 dark:border-[#434463] text-xs space-y-1 text-gray-500">
                            <p>Ditetapkan oleh: <span class="font-medium text-gray-800 dark:text-gray-200">{{ $detail->kelompokDitetapkanOleh->name ?? 'Admin' }}</span></p>
                            <p>Pada: <span class="font-medium text-gray-800 dark:text-gray-200">{{ $detail->kelompok_ditetapkan_at?->translatedFormat('d F Y, H:i') }} WIB</span></p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="overflow-hidden rounded-lg border border-[#d9dee3] bg-white shadow-sneat dark:border-[#434463] dark:bg-[#2b2c40] dark:shadow-sneat-dark">
                <div class="bg-[#2b2c40] dark:bg-[#232333] px-6 py-4 border-b border-[#434463] flex items-center gap-2">
                    <i data-lucide="check-square" class="w-5 h-5 text-[#a1b0cb]"></i>
                    <h3 class="font-heading font-semibold text-white">Aksi Verifikasi</h3>
                </div>

                <div class="p-4 sm:p-6">
                    <!-- Checklist Dokumen (Only if not final) -->
                    @if($detail->status !== 'diterima' && $detail->status !== 'ditolak')
                        <div class="mb-5 p-4 rounded-lg bg-slate-50 dark:bg-[#232333] border border-[#d9dee3] dark:border-[#434463]">
                            <h4 class="text-sm font-semibold text-[#566a7f] dark:text-[#d5d5e2] mb-3 flex items-center gap-1.5">
                                <i data-lucide="list-checks" class="w-4 h-4 text-[#696cff]"></i>
                                Checklist Masalah Dokumen (Minta Revisi)
                            </h4>
                            <div class="space-y-2.5">
                                <label class="flex items-start gap-2.5 text-xs text-[#697a8d] dark:text-[#a1b0cb] cursor-pointer">
                                    <input type="checkbox" id="chk_foto" onchange="updateNotifikasiFromChecklist()" class="mt-0.5 h-4 w-4 rounded border-gray-300 text-[#696cff] focus:ring-[#696cff]">
                                    <span>Foto anak kurang jelas/tidak sesuai.</span>
                                </label>
                                <label class="flex items-start gap-2.5 text-xs text-[#697a8d] dark:text-[#a1b0cb] cursor-pointer">
                                    <input type="checkbox" id="chk_kk" onchange="updateNotifikasiFromChecklist()" class="mt-0.5 h-4 w-4 rounded border-gray-300 text-[#696cff] focus:ring-[#696cff]">
                                    <span>Foto KK kurang jelas.</span>
                                </label>
                                <label class="flex items-start gap-2.5 text-xs text-[#697a8d] dark:text-[#a1b0cb] cursor-pointer">
                                    <input type="checkbox" id="chk_akta" onchange="updateNotifikasiFromChecklist()" class="mt-0.5 h-4 w-4 rounded border-gray-300 text-[#696cff] focus:ring-[#696cff]">
                                    <span>Akta kelahiran kurang jelas.</span>
                                </label>
                            </div>
                        </div>

                        <script>
                            document.addEventListener('DOMContentLoaded', () => {
                                const registrationId = "{{ $detail->id }}";
                                let hasLoadedAny = false;
                                ['foto', 'kk', 'akta'].forEach(item => {
                                    const val = localStorage.getItem(`spmb_chk_${registrationId}_${item}`);
                                    const element = document.getElementById(`chk_${item}`);
                                    if (element && val === 'true') {
                                        element.checked = true;
                                        hasLoadedAny = true;
                                    }
                                });

                                const textarea = document.getElementById('notifikasi');
                                if (textarea && textarea.value.trim() === "" && hasLoadedAny) {
                                    updateNotifikasiFromChecklist();
                                }
                            });

                            function updateNotifikasiFromChecklist() {
                                const chkFoto = document.getElementById('chk_foto').checked;
                                const chkKk = document.getElementById('chk_kk').checked;
                                const chkAkta = document.getElementById('chk_akta').checked;

                                const registrationId = "{{ $detail->id }}";
                                localStorage.setItem(`spmb_chk_${registrationId}_foto`, chkFoto);
                                localStorage.setItem(`spmb_chk_${registrationId}_kk`, chkKk);
                                localStorage.setItem(`spmb_chk_${registrationId}_akta`, chkAkta);

                                const textarea = document.getElementById('notifikasi');
                                if (!textarea) return;

                                let items = [];
                                if (chkFoto) items.push('Foto anak kurang jelas/tidak sesuai.');
                                if (chkKk) items.push('Foto KK kurang jelas.');
                                if (chkAkta) items.push('Akta kelahiran kurang jelas.');

                                if (items.length > 0) {
                                    textarea.value = "Mohon perbaiki data/dokumen berikut:\n" + items.map(item => `* ${item}`).join('\n');
                                } else {
                                    textarea.value = "";
                                }
                            }
                        </script>
                    @endif



                    @if($detail->status === 'diterima' || $detail->status === 'ditolak')
                        {{-- Final status reached — show badge only --}}
                        <div class="text-center py-4">
                            @if($detail->status === 'diterima')
                                <div class="w-14 h-14 mx-auto rounded-full bg-secondary-100 flex items-center justify-center mb-3">
                                    <i data-lucide="check-circle" class="w-8 h-8 text-secondary-600"></i>
                                </div>
                                <span class="inline-flex items-center px-4 py-2 rounded-full text-xs font-bold bg-secondary-100 text-secondary-700 border border-secondary-200">BERKAS TERVERIFIKASI – LANJUT OBSERVASI</span>
                                <p class="text-xs text-[#a1b0cb] mt-3">Berkas pendaftaran telah diverifikasi. Calon siswa diarahkan untuk observasi/wawancara di sekolah.</p>
                            @else
                                <div class="w-14 h-14 mx-auto rounded-full bg-red-100 flex items-center justify-center mb-3">
                                    <i data-lucide="x-circle" class="w-8 h-8 text-red-600"></i>
                                </div>
                                <span class="inline-flex items-center px-4 py-2 rounded-full text-xs font-bold bg-red-100 text-red-700 border border-red-200">PENDAFTARAN DITOLAK</span>
                                <p class="text-xs text-[#a1b0cb] mt-3">Pendaftaran ini sudah ditolak.</p>
                            @endif
                        </div>

                        {{-- Also hide payment verification if already lunas --}}
                        @if($payment && $isPaymentLunas)
                            <div class="mt-4 pt-4 border-t border-[#d9dee3] dark:border-[#434463] text-center">
                                <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-bold bg-secondary-100 text-secondary-700">
                                    <i data-lucide="check" class="w-4 h-4"></i> Pembayaran Lunas
                                </span>
                            </div>
                        @endif

                    @elseif($detail->status === 'pending')
                        <div class="text-center pb-4 border-b border-[#d9dee3] dark:border-[#434463] mb-4">
                            <i data-lucide="info" class="w-8 h-8 text-blue-500 mx-auto mb-2"></i>
                            <p class="text-sm text-[#697a8d] dark:text-[#a1b0cb]">Pendaftar baru masuk. Silakan ubah status ke <b class="text-[#566a7f] dark:text-[#d5d5e2]">Menunggu Verifikasi</b> untuk mulai mengecek dokumen.</p>
                        </div>
                        <form action="{{ route('admin.verifikasi.start', $detail->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="w-full flex justify-center items-center gap-2 py-2.5 px-4 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                                <i data-lucide="play-circle" class="w-4 h-4"></i> Mulai Verifikasi
                            </button>
                        </form>
                    @else
                        <!-- Form for Terima / Tolak -->
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-[#566a7f] dark:text-[#d5d5e2] mb-1">Catatan Tambahan / Alasan</label>
                                <textarea id="notifikasi" name="notifikasi" rows="4" class="sneat-input" placeholder="Opsional jika diterima. Wajib diisi jika ditolak / minta revisi..."></textarea>
                                <p class="text-xs text-[#a1b0cb] mt-1">Catatan ini akan dilihat oleh Wali Murid.</p>
                            </div>

                            <p class="text-xs text-blue-600 dark:text-blue-400 bg-blue-500/10 p-2.5 rounded-lg border border-blue-500/20 mb-1">
                                <i data-lucide="info" class="w-3.5 h-3.5 inline mr-1"></i> Setelah berkas disetujui, orang tua/wali diarahkan untuk datang ke sekolah bersama calon siswa pada tahap observasi/wawancara sebelum proses daftar ulang.
                            </p>

                            <div class="flex flex-col gap-3 pt-2">
                                <button type="button" onclick="handleTerima()" class="w-full flex justify-center items-center gap-2 py-3 px-4 bg-secondary-600 hover:bg-secondary-700 text-white font-semibold rounded-lg shadow-sm transition-colors">
                                    <i data-lucide="check" class="w-5 h-5"></i> Setujui Berkas & Lanjut Observasi
                                </button>

                                <button type="button" onclick="handleRevisi()" class="flex w-full items-center justify-center gap-2 rounded-lg border border-orange-200 bg-white px-4 py-3 font-semibold text-orange-600 transition-colors hover:bg-orange-50 dark:border-orange-500/30 dark:bg-[#2b2c40] dark:text-orange-400 dark:hover:bg-orange-500/10">
                                    <i data-lucide="edit-3" class="w-5 h-5"></i> Minta Revisi Dokumen
                                </button>

                                <button type="button" onclick="handleTolak()" class="flex w-full items-center justify-center gap-2 rounded-lg border border-red-200 bg-white px-4 py-3 font-semibold text-red-600 transition-colors hover:bg-red-50 dark:border-red-500/30 dark:bg-[#2b2c40] dark:text-red-400 dark:hover:bg-red-500/10">
                                    <i data-lucide="x" class="w-5 h-5"></i> Tolak Pendaftaran
                                </button>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Hidden Forms -->
<form id="formTerima" action="{{ route('admin.verifikasi.terima', $detail->id) }}" method="POST" style="display: none;">
    @csrf
    @method('PATCH')
    <input type="hidden" id="hiddenNotifikasiTerima" name="notifikasi" value="">
</form>

<form id="formTolak" action="{{ route('admin.verifikasi.tolak', $detail->id) }}" method="POST" style="display: none;">
    @csrf
    @method('PATCH')
    <input type="hidden" id="hiddenNotifikasiTolak" name="notifikasi" value="">
</form>

<form id="formRevisi" action="{{ route('admin.verifikasi.revisi', $detail->id) }}" method="POST" style="display: none;">
    @csrf
    @method('PATCH')
    <input type="hidden" id="hiddenNotifikasiRevisi" name="notifikasi" value="">
</form>

<script>
    async function handleTerima() {
        const notifikasi = document.getElementById('notifikasi').value;

        const result = await Swal.fire({
            title: 'Konfirmasi Persetujuan',
            html: `Apakah Anda yakin dokumen pendaftar atas nama <b>{{ $detail->siswa->nama }}</b> sudah valid dan lengkap? Tindakan ini akan mengarahkan orang tua ke tahap <span class="font-bold text-[#696cff]">Observasi & Wawancara di Sekolah</span>.`,
            icon: 'success',
            showCancelButton: true,
            confirmButtonText: 'Ya, Setujui Berkas',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#2dce89',
        });

        if (result.isConfirmed) {
            document.getElementById('hiddenNotifikasiTerima').value = notifikasi;
            document.getElementById('formTerima').submit();
        }
    }

    async function handleTolak() {
        const notifikasi = document.getElementById('notifikasi').value;

        if (!notifikasi.trim()) {
            Swal.fire({
                title: 'Catatan Diperlukan',
                text: 'Harap isi "Catatan Tambahan / Alasan" sebelum menolak pendaftaran.',
                icon: 'warning',
                confirmButtonColor: '#696cff',
            });
            document.getElementById('notifikasi').focus();
            return;
        }

        const result = await Swal.fire({
            title: 'Konfirmasi Penolakan',
            text: 'Anda akan menolak pendaftar ini secara permanen. Pastikan alasan sudah jelas di catatan.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Tolak Pendaftaran',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#dc2626',
        });

        if (result.isConfirmed) {
            document.getElementById('hiddenNotifikasiTolak').value = notifikasi;
            document.getElementById('formTolak').submit();
        }
    }

    async function handleRevisi() {
        const notifikasi = document.getElementById('notifikasi').value;

        if (!notifikasi.trim()) {
            Swal.fire({
                title: 'Catatan Diperlukan',
                text: 'Harap isi "Catatan Tambahan / Alasan" dengan detail (contoh: berkas KK kurang jelas) sebelum meminta revisi.',
                icon: 'warning',
                confirmButtonColor: '#696cff',
            });
            document.getElementById('notifikasi').focus();
            return;
        }

        const result = await Swal.fire({
            title: 'Konfirmasi Permintaan Revisi',
            text: 'Anda akan meminta revisi untuk pendaftar ini. Wali murid akan menerima catatan dan dapat memperbaikinya.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Minta Revisi',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#f97316',
        });

        if (result.isConfirmed) {
            document.getElementById('hiddenNotifikasiRevisi').value = notifikasi;
            document.getElementById('formRevisi').submit();
        }
    }
</script>

<!-- Fancybox JS -->
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
<script>
    Fancybox.bind("[data-fancybox]", {
        // Custom options
    });
</script>
<!-- Lightbox Modal -->
<div id="lightboxModal" class="fixed inset-0 z-[9999] bg-black/90 hidden flex items-center justify-center transition-opacity duration-300 opacity-0 backdrop-blur-sm">
    <button type="button" onclick="closeLightbox()" class="absolute right-3 top-3 z-50 flex h-11 w-11 cursor-pointer items-center justify-center rounded-full bg-black/40 text-3xl leading-none text-white transition-colors hover:bg-black/70 hover:text-gray-200 sm:right-5 sm:top-5" aria-label="Tutup pratinjau dokumen">&times;</button>
    <img id="lightboxImage" src="" alt="Pratinjau dokumen" class="max-h-[90vh] max-w-full scale-95 transform rounded-lg object-contain p-4 shadow-2xl transition-transform duration-300">
</div>

<script>
    function openLightbox(imageSrc) {
        if (imageSrc === '#' || !imageSrc) return;
        const modal = document.getElementById('lightboxModal');
        const lightboxImage = document.getElementById('lightboxImage');

        lightboxImage.src = imageSrc;
        modal.classList.remove('hidden');

        // Trigger animasi smooth open
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            lightboxImage.classList.remove('scale-95');
            lightboxImage.classList.add('scale-100');
        }, 10);
    }

    function closeLightbox() {
        const modal = document.getElementById('lightboxModal');
        const lightboxImage = document.getElementById('lightboxImage');

        // Trigger animasi smooth close
        modal.classList.add('opacity-0');
        lightboxImage.classList.remove('scale-100');
        lightboxImage.classList.add('scale-95');

        setTimeout(() => {
            modal.classList.add('hidden');
            lightboxImage.src = '';
        }, 300);
    }

    // Tutup lightbox jika user mengklik area gelap di luar gambar
    document.getElementById('lightboxModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeLightbox();
        }
    });

    // Tutup lightbox jika user menekan tombol ESC di keyboard
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !document.getElementById('lightboxModal').classList.contains('hidden')) {
            closeLightbox();
        }
    });
</script>

@endsection
