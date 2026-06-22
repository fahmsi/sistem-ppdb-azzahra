@extends('layouts.app')

@section('title', 'Dashboard Wali Murid')
@section('header_title', 'Dashboard')

@section('content')
<div class="mx-auto max-w-7xl space-y-4 sm:space-y-6">

    <!-- Welcome Banner -->
    <div class="relative overflow-hidden rounded-xl bg-gradient-to-r from-[#696cff] to-[#7b7dff] p-5 text-white shadow-sneat-lg animate-fade-up sm:p-8">
        <!-- Decoration -->
        <div class="absolute -right-10 -top-24 w-64 h-64 bg-white/10 rounded-full blur-2xl"></div>
        <div class="absolute right-32 -bottom-10 w-40 h-40 bg-white/10 rounded-full blur-xl"></div>
        
        <div class="relative z-10">
            <h2 class="mb-2 break-words font-heading text-xl font-bold leading-tight sm:text-3xl">Assalamu'alaikum, {{ explode(' ', auth()->user()->name)[0] }}!</h2>
            <p class="max-w-2xl text-sm leading-6 text-white/85 sm:text-base sm:leading-7">
                Selamat datang di panel wali murid. Di sini Anda dapat melengkapi data anak, mendaftar gelombang SPMB, dan memantau status seleksi penerimaan.
            </p>
        </div>
    </div>

    @php
        $siswa = auth()->user()->siswa;
        $latestRegistration = $siswa ? $siswa->pendaftaranDetails()->with(['pendaftaran', 'pembayaran'])->latest()->first() : null;
        $isAccepted = $latestRegistration && $latestRegistration->status === 'diterima';
        $payment = $latestRegistration?->pembayaran;
        $paymentStatus = $payment?->status;
        $isPaymentWaiting = $payment && in_array($paymentStatus, ['pending', 'menunggu_verifikasi'], true);
        $isPaymentLunas = $paymentStatus === 'lunas';
        $isPaymentRejected = $paymentStatus === 'ditolak';
        $bankName = config('spmb.bank_name', '-');
        $bankAccountNumber = config('spmb.bank_account_number', '-');
        $bankAccountHolder = config('spmb.bank_account_holder', '-');
        $daftarUlangAmount = (int) config('spmb.daftar_ulang_amount', 0);
        $adminWhatsapp = preg_replace('/[^0-9]/', '', (string) config('spmb.admin_whatsapp', ''));
        $helpWaMessage = "Assalamu'alaikum Admin Az Zahra, saya butuh bantuan terkait pendaftaran anak saya.";
        $helpWaUrl = $adminWhatsapp ? 'https://wa.me/'.$adminWhatsapp.'?text='.urlencode($helpWaMessage) : '#';
    @endphp

    <div class="grid grid-cols-1 gap-4 sm:gap-6 lg:grid-cols-3">
        
        <!-- Main Status Card -->
        <div class="space-y-4 sm:space-y-6 lg:col-span-2">
            
            <!-- Registration Status -->
            <div class="rounded-lg border border-[#d9dee3] bg-white p-4 shadow-sneat animate-fade-up dark:border-[#434463] dark:bg-[#2b2c40] dark:shadow-sneat-dark sm:p-6" style="animation-delay: 0.1s;">
                <h3 class="mb-4 flex items-start gap-2 font-heading text-base font-semibold leading-6 text-[#566a7f] dark:text-[#d5d5e2] sm:items-center sm:text-lg">
                    <i data-lucide="activity" class="w-5 h-5 text-[#696cff]"></i>
                    Status Pendaftaran Saat Ini
                </h3>

                @if(!$siswa)
                    <div class="flex flex-col items-center gap-4 rounded-lg border border-amber-200 bg-amber-50 p-4 text-center dark:border-amber-500/20 dark:bg-amber-500/10 sm:flex-row sm:p-5 sm:text-left">
                        <div class="w-12 h-12 rounded-lg bg-amber-100 dark:bg-amber-500/20 flex items-center justify-center flex-shrink-0 text-amber-500">
                            <i data-lucide="user-plus" class="w-6 h-6"></i>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-semibold text-amber-800 dark:text-amber-400">Data Anak Belum Lengkap</h4>
                            <p class="text-sm text-amber-700 dark:text-amber-300/80 mt-1">Anda belum melengkapi profil dan dokumen anak. Silakan lengkapi terlebih dahulu untuk dapat memilih gelombang pendaftaran.</p>
                        </div>
                        <a href="{{ route('parent.siswa.create') }}" class="mt-2 inline-flex w-full items-center justify-center whitespace-nowrap rounded-md bg-amber-500 px-4 py-2.5 text-sm font-medium text-white shadow-sm transition-colors hover:bg-amber-600 sm:mt-0 sm:w-auto">
                            Lengkapi Data
                        </a>
                    </div>
                @elseif(!$latestRegistration)
                    <div class="flex flex-col items-center gap-4 rounded-lg border border-blue-200 bg-blue-50 p-4 text-center dark:border-blue-500/20 dark:bg-blue-500/10 sm:flex-row sm:p-5 sm:text-left">
                        <div class="w-12 h-12 rounded-lg bg-blue-100 dark:bg-blue-500/20 flex items-center justify-center flex-shrink-0 text-blue-500">
                            <i data-lucide="info" class="w-6 h-6"></i>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-semibold text-blue-800 dark:text-blue-400">Belum Memilih Gelombang</h4>
                            <p class="text-sm text-blue-700 dark:text-blue-300/80 mt-1">Data anak sudah lengkap, namun Anda belum mendaftar di gelombang manapun.</p>
                        </div>
                        <a href="{{ route('parent.pendaftaran.index') }}" class="sneat-btn-primary mt-2 w-full justify-center whitespace-nowrap sm:mt-0 sm:w-auto">
                            Daftar Sekarang
                        </a>
                    </div>
                @else
                    <!-- Status Display -->
                    <div class="rounded-lg border border-[#d9dee3] bg-[#f5f5f9] p-4 dark:border-[#434463] dark:bg-[#232333] sm:p-5">
                        <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4 mb-4">
                            <div>
                                <p class="text-xs text-[#a1b0cb] uppercase tracking-wide font-semibold">Gelombang Pilihan</p>
                                <p class="font-medium text-[#566a7f] dark:text-[#d5d5e2]">{{ $latestRegistration->pendaftaran->gelombang }} - {{ $latestRegistration->pendaftaran->tahun_ajaran }}</p>
                            </div>
                            
                            <!-- Badges -->
                            <div>
                                @if($latestRegistration->status === 'pending')
                                    <span class="sneat-badge bg-[#f5f5f9] dark:bg-[#434463] text-[#697a8d] dark:text-[#a1b0cb] border border-[#d9dee3] dark:border-[#434463]">
                                        <i data-lucide="clock" class="w-3.5 h-3.5"></i> Pending
                                    </span>
                                @elseif($latestRegistration->status === 'menunggu_verifikasi')
                                    <span class="sneat-badge bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-200 dark:border-amber-500/20">
                                        <i data-lucide="search" class="w-3.5 h-3.5"></i> Menunggu Verifikasi
                                    </span>
                                @elseif($latestRegistration->status === 'diterima')
                                    <span class="sneat-badge bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/20">
                                        <i data-lucide="check-circle-2" class="w-3.5 h-3.5"></i> Diterima
                                    </span>
                                @elseif($latestRegistration->status === 'ditolak')
                                    <span class="sneat-badge bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 border border-red-200 dark:border-red-500/20">
                                        <i data-lucide="x-circle" class="w-3.5 h-3.5"></i> Ditolak
                                    </span>
                                @elseif($latestRegistration->status === 'perlu_revisi')
                                    <span class="sneat-badge bg-orange-50 dark:bg-orange-500/10 text-orange-600 dark:text-orange-400 border border-orange-200 dark:border-orange-500/20">
                                        <i data-lucide="edit-3" class="w-3.5 h-3.5"></i> Perlu Revisi
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Notification Alert -->
                        @if($latestRegistration->notifikasi)
                            <div class="mt-4 p-4 rounded-lg {{ $latestRegistration->status === 'diterima' ? 'bg-emerald-50 dark:bg-emerald-500/10 border-emerald-200 dark:border-emerald-500/20' : ($latestRegistration->status === 'ditolak' ? 'bg-red-50 dark:bg-red-500/10 border-red-200 dark:border-red-500/20' : 'bg-white dark:bg-[#2b2c40] border-[#d9dee3] dark:border-[#434463]') }} border text-sm">
                                <span class="block font-semibold mb-1 {{ $latestRegistration->status === 'diterima' ? 'text-emerald-800 dark:text-emerald-400' : ($latestRegistration->status === 'ditolak' ? 'text-red-800 dark:text-red-400' : 'text-[#566a7f] dark:text-[#d5d5e2]') }}">Pesan dari Admin:</span>
                                <p class="text-[#697a8d] dark:text-[#a1b0cb]">{{ $latestRegistration->notifikasi }}</p>
                            </div>
                        @endif

                        @if($isAccepted)
                            <div class="mt-4 p-4 rounded-lg bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20">
                                @if(!$payment)
                                    <h4 class="font-semibold text-emerald-800 dark:text-emerald-400">Selamat, pendaftaran anak Anda telah diterima.</h4>
                                    <p class="text-sm text-[#697a8d] dark:text-[#a1b0cb] mt-2">Silakan melakukan daftar ulang dengan pembayaran ke rekening berikut:</p>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 mt-3 text-sm">
                                        <div>
                                            <span class="text-[#a1b0cb]">Bank:</span>
                                            <span class="font-semibold text-[#566a7f] dark:text-[#d5d5e2]">{{ $bankName }}</span>
                                        </div>
                                        <div>
                                            <span class="text-[#a1b0cb]">No. Rekening:</span>
                                            <span class="font-semibold text-[#566a7f] dark:text-[#d5d5e2]">{{ $bankAccountNumber }}</span>
                                        </div>
                                        <div>
                                            <span class="text-[#a1b0cb]">Atas Nama:</span>
                                            <span class="font-semibold text-[#566a7f] dark:text-[#d5d5e2]">{{ $bankAccountHolder }}</span>
                                        </div>
                                        <div>
                                            <span class="text-[#a1b0cb]">Nominal:</span>
                                            <span class="font-semibold text-[#566a7f] dark:text-[#d5d5e2]">{{ $daftarUlangAmount > 0 ? 'Rp '.number_format($daftarUlangAmount, 0, ',', '.') : '-' }}</span>
                                        </div>
                                    </div>
                                    <p class="text-sm text-[#697a8d] dark:text-[#a1b0cb] mt-3">Setelah melakukan pembayaran, silakan upload bukti pembayaran melalui tombol di bawah ini.</p>
                                @elseif($isPaymentWaiting)
                                    <h4 class="font-semibold text-amber-700 dark:text-amber-400">Bukti pembayaran Anda sedang menunggu verifikasi admin.</h4>
                                    <p class="text-sm text-[#697a8d] dark:text-[#a1b0cb] mt-1">Silakan hubungi admin untuk konfirmasi.</p>
                                @elseif($isPaymentLunas)
                                    <h4 class="font-semibold text-emerald-800 dark:text-emerald-400">Pembayaran daftar ulang telah diverifikasi. Proses daftar ulang selesai.</h4>
                                @elseif($isPaymentRejected)
                                    <h4 class="font-semibold text-red-700 dark:text-red-400">Bukti pembayaran perlu diperbaiki. Silakan upload ulang bukti pembayaran.</h4>
                                @endif
                            </div>
                        @endif
                        
                        <!-- Actions if Accepted -->
                        @if($isAccepted)
                            <div class="mt-5 grid grid-cols-1 gap-3 sm:flex sm:flex-wrap sm:items-center">
                                <a href="{{ route('parent.siswa.kartu') }}" target="_blank" class="inline-flex w-full items-center justify-center gap-2 rounded-md bg-emerald-500 px-4 py-2.5 text-sm font-medium text-white transition-colors hover:bg-emerald-600 sm:w-auto sm:py-2">
                                    <i data-lucide="printer" class="w-4 h-4"></i> Cetak Bukti Lulus
                                </a>
                                @if($isPaymentLunas)
                                    <a href="{{ route('parent.pembayaran.receipt', $latestRegistration->id) }}" target="_blank" class="inline-flex w-full items-center justify-center gap-2 rounded-md bg-blue-500 px-4 py-2.5 text-sm font-medium text-white transition-colors hover:bg-blue-600 sm:w-auto sm:py-2">
                                        <i data-lucide="file-down" class="w-4 h-4"></i> Cetak Bukti Bayar
                                    </a>
                                @elseif($isPaymentWaiting)
                                    <a href="{{ route('parent.pendaftaran.status') }}" class="sneat-btn-secondary w-full justify-center sm:w-auto">
                                        <i data-lucide="clock" class="w-4 h-4"></i> Lihat Status Pembayaran
                                    </a>
                                @else
                                    <a href="{{ route('parent.pendaftaran.status') }}" class="sneat-btn-secondary w-full justify-center sm:w-auto">
                                        <i data-lucide="upload" class="w-4 h-4"></i> Upload Bukti Pembayaran
                                    </a>
                                @endif
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Child Info Summary (If exists) -->
            @if($siswa)
            <div class="rounded-lg border border-[#d9dee3] bg-white p-4 shadow-sneat animate-fade-up dark:border-[#434463] dark:bg-[#2b2c40] dark:shadow-sneat-dark sm:p-6" style="animation-delay: 0.2s;">
                <div class="mb-4 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                    <h3 class="text-lg font-heading font-semibold text-[#566a7f] dark:text-[#d5d5e2] flex items-center gap-2">
                        <i data-lucide="user" class="w-5 h-5 text-[#696cff]"></i>
                        Ringkasan Profil Anak
                    </h3>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('parent.siswa.show', $siswa->id) }}" class="text-sm text-[#696cff] hover:text-[#5a5de6] font-medium">Lihat Detail &rarr;</a>
                    </div>
                </div>

                <div class="flex flex-col items-stretch gap-4 rounded-lg border border-[#d9dee3] bg-[#f5f5f9] p-4 dark:border-[#434463] dark:bg-[#232333] sm:flex-row sm:items-center">
                    <img src="{{ $siswa->foto ? Storage::url($siswa->foto) : asset('images/default-avatar.png') }}" alt="Foto Anak" class="h-16 w-16 self-center rounded-full border-2 border-white bg-[#f5f5f9] object-cover shadow-sm dark:border-[#434463] dark:bg-[#232333] sm:self-auto">
                    <div class="min-w-0 flex-1 text-center sm:text-left">
                        <h4 class="font-semibold text-[#566a7f] dark:text-[#d5d5e2]">{{ $siswa->nama }}</h4>
                        <p class="text-sm text-[#a1b0cb] mt-0.5">Panggilan: {{ $siswa->nama_panggilan ?? '-' }}</p>
                        <p class="text-xs text-[#a1b0cb] mt-1"><i data-lucide="map-pin" class="w-3 h-3 inline"></i> {{ $siswa->kota }}</p>
                    </div>
                    <!-- Edit & Delete Buttons -->
                    <div class="grid grid-cols-2 gap-2 sm:flex sm:flex-col">
                        <a href="{{ route('parent.siswa.edit', $siswa->id) }}" class="inline-flex items-center justify-center gap-1.5 rounded-md bg-[#e7e7ff] px-3 py-2 text-xs font-medium text-[#696cff] transition-colors hover:bg-[#d4d5ff] dark:bg-[#696cff]/20 dark:hover:bg-[#696cff]/30">
                            <i data-lucide="edit-3" class="w-3 h-3"></i> Edit
                        </a>
                        @if(!$latestRegistration || $latestRegistration->status === 'ditolak')
                        <form action="{{ route('parent.siswa.destroy', $siswa->id) }}" method="POST" class="child-delete-form" data-child-name="{{ $siswa->nama }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex w-full items-center justify-center gap-1.5 rounded-md bg-red-50 px-3 py-2 text-xs font-medium text-red-500 transition-colors hover:bg-red-100 dark:bg-red-500/10 dark:hover:bg-red-500/20">
                                <i data-lucide="trash-2" class="w-3 h-3"></i> Hapus
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
            @endif

        </div>

        <!-- Right Sidebar (Quick Actions) -->
        <div class="space-y-4 animate-fade-up sm:space-y-6" style="animation-delay: 0.3s;">
            <div class="rounded-lg border border-[#d9dee3] bg-white p-4 shadow-sneat dark:border-[#434463] dark:bg-[#2b2c40] dark:shadow-sneat-dark sm:p-6">
                <h3 class="text-lg font-heading font-semibold text-[#566a7f] dark:text-[#d5d5e2] mb-4">Aksi Cepat</h3>
                
                <div class="space-y-3">
                    @if(!$siswa)
                        <a href="{{ route('parent.siswa.create') }}" class="flex items-center justify-between p-3 rounded-lg border border-[#d9dee3] dark:border-[#434463] hover:border-[#696cff] dark:hover:border-[#696cff] hover:bg-[#e7e7ff]/30 dark:hover:bg-[#696cff]/10 transition-colors group">
                            <div class="flex min-w-0 items-center gap-3">
                                <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-lg bg-[#e7e7ff] text-[#696cff] dark:bg-[#696cff]/20">
                                    <i data-lucide="file-edit" class="w-5 h-5"></i>
                                </div>
                                <div class="min-w-0">
                                    <p class="font-medium text-sm text-[#566a7f] dark:text-[#d5d5e2] group-hover:text-[#696cff]">Isi Data Anak</p>
                                    <p class="text-xs text-[#a1b0cb]">Lengkapi formulir biodata</p>
                                </div>
                            </div>
                            <i data-lucide="chevron-right" class="w-4 h-4 text-[#a1b0cb] group-hover:text-[#696cff] transition-transform group-hover:translate-x-1"></i>
                        </a>
                    @else
                        <a href="{{ route('parent.siswa.edit', $siswa->id) }}" class="flex items-center justify-between p-3 rounded-lg border border-[#d9dee3] dark:border-[#434463] hover:border-[#696cff] dark:hover:border-[#696cff] hover:bg-[#e7e7ff]/30 dark:hover:bg-[#696cff]/10 transition-colors group">
                            <div class="flex min-w-0 items-center gap-3">
                                <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-lg bg-[#e7e7ff] text-[#696cff] dark:bg-[#696cff]/20">
                                    <i data-lucide="edit-3" class="w-5 h-5"></i>
                                </div>
                                <div class="min-w-0">
                                    <p class="font-medium text-sm text-[#566a7f] dark:text-[#d5d5e2] group-hover:text-[#696cff]">Edit Data Anak</p>
                                    <p class="text-xs text-[#a1b0cb]">Perbarui biodata & dokumen</p>
                                </div>
                            </div>
                            <i data-lucide="chevron-right" class="w-4 h-4 text-[#a1b0cb] group-hover:text-[#696cff] transition-transform group-hover:translate-x-1"></i>
                        </a>
                    @endif

                    @if(!$isAccepted)
                    <a href="{{ route('parent.pendaftaran.index') }}" class="flex items-center justify-between p-3 rounded-lg border border-[#d9dee3] dark:border-[#434463] hover:border-blue-400 dark:hover:border-blue-400 hover:bg-blue-50/50 dark:hover:bg-blue-500/10 transition-colors group">
                        <div class="flex min-w-0 items-center gap-3">
                            <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-lg bg-blue-50 text-blue-500 dark:bg-blue-500/10">
                                <i data-lucide="calendar-check" class="w-5 h-5"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="font-medium text-sm text-[#566a7f] dark:text-[#d5d5e2] group-hover:text-blue-600 dark:group-hover:text-blue-400">Daftar Gelombang</p>
                                <p class="text-xs text-[#a1b0cb]">Pilih periode pendaftaran</p>
                            </div>
                        </div>
                        <i data-lucide="chevron-right" class="w-4 h-4 text-[#a1b0cb] group-hover:text-blue-500 transition-transform group-hover:translate-x-1"></i>
                    </a>
                    @else
                    <div class="flex items-center justify-between p-3 rounded-lg border border-[#d9dee3] dark:border-[#434463] bg-[#f5f5f9] dark:bg-[#232333] opacity-60 cursor-not-allowed">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-[#f5f5f9] dark:bg-[#434463] flex items-center justify-center text-[#a1b0cb]">
                                <i data-lucide="calendar-check" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <p class="font-medium text-sm text-[#a1b0cb]">Daftar Gelombang</p>
                                <p class="text-xs text-[#a1b0cb]">Sudah diterima ✓</p>
                            </div>
                        </div>
                        <i data-lucide="lock" class="w-4 h-4 text-[#a1b0cb]"></i>
                    </div>
                    @endif

                    @if($isAccepted)
                    <a href="{{ route('parent.pendaftaran.status') }}" class="flex items-center justify-between p-3 rounded-lg border border-[#d9dee3] dark:border-[#434463] hover:border-emerald-400 dark:hover:border-emerald-400 hover:bg-emerald-50/50 dark:hover:bg-emerald-500/10 transition-colors group">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-emerald-50 dark:bg-emerald-500/10 flex items-center justify-center text-emerald-500">
                                <i data-lucide="credit-card" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <p class="font-medium text-sm text-[#566a7f] dark:text-[#d5d5e2] group-hover:text-emerald-600 dark:group-hover:text-emerald-400">Pembayaran</p>
                                <p class="text-xs text-[#a1b0cb]">Upload bukti daftar ulang</p>
                            </div>
                        </div>
                        <i data-lucide="chevron-right" class="w-4 h-4 text-[#a1b0cb] group-hover:text-emerald-500 transition-transform group-hover:translate-x-1"></i>
                    </a>
                    @endif
                </div>
            </div>

            <!-- Help / Contact Card -->
            <div class="rounded-lg bg-white p-5 text-center text-slate-700 shadow-sneat dark:bg-[#2b2c40] dark:text-white dark:shadow-sneat-dark sm:p-6">
                <div class="w-12 h-12 rounded-full bg-[#696cff]/10 dark:bg-[#696cff]/20 mx-auto flex items-center justify-center mb-4">
                    <i data-lucide="help-circle" class="w-6 h-6 text-[#696cff]"></i>
                </div>
                <h3 class="font-semibold mb-2 text-slate-900 dark:text-[#d5d5e2]">Butuh Bantuan?</h3>
                <p class="text-sm text-slate-500 dark:text-[#a1b0cb] mb-4">Jika Anda mengalami kendala saat mendaftar, silakan hubungi admin kami.</p>
                <a href="{{ $helpWaUrl }}" target="_blank" class="inline-flex items-center justify-center gap-2 w-full py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white rounded-md transition-colors text-sm font-medium">
                    <i data-lucide="message-circle" class="w-4 h-4"></i> Hubungi Admin SPMB
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
