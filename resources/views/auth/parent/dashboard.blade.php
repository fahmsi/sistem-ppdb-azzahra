@extends('layouts.app')

@section('title', 'Dashboard Wali Murid')
@section('header_title', 'Dashboard')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    <!-- Welcome Banner -->
    <div class="bg-gradient-to-r from-[#696cff] to-[#7b7dff] rounded-lg shadow-sneat-lg p-6 sm:p-8 text-white relative overflow-hidden animate-fade-up">
        <!-- Decoration -->
        <div class="absolute -right-10 -top-24 w-64 h-64 bg-white/10 rounded-full blur-2xl"></div>
        <div class="absolute right-32 -bottom-10 w-40 h-40 bg-white/10 rounded-full blur-xl"></div>
        
        <div class="relative z-10">
            <h2 class="text-2xl sm:text-3xl font-heading font-bold mb-2">Assalamu'alaikum, {{ explode(' ', auth()->user()->name)[0] }}!</h2>
            <p class="text-white/80 max-w-2xl text-sm sm:text-base">
                Selamat datang di panel wali murid. Di sini Anda dapat melengkapi data anak, mendaftar gelombang PPDB, dan memantau status seleksi penerimaan.
            </p>
        </div>
    </div>

    @php
        $siswa = auth()->user()->siswa;
        $latestRegistration = $siswa ? $siswa->pendaftaranDetails()->with('pembayaran')->latest()->first() : null;
        $isAccepted = $latestRegistration && $latestRegistration->status === 'diterima';
    @endphp

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Main Status Card -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Registration Status -->
            <div class="bg-white dark:bg-[#2b2c40] rounded-lg shadow-sneat dark:shadow-sneat-dark border border-[#d9dee3] dark:border-[#434463] p-6 animate-fade-up" style="animation-delay: 0.1s;">
                <h3 class="text-lg font-heading font-semibold text-[#566a7f] dark:text-[#d5d5e2] mb-4 flex items-center gap-2">
                    <i data-lucide="activity" class="w-5 h-5 text-[#696cff]"></i>
                    Status Pendaftaran Saat Ini
                </h3>

                @if(!$siswa)
                    <div class="bg-amber-50 dark:bg-amber-500/10 border border-amber-200 dark:border-amber-500/20 rounded-lg p-5 flex flex-col sm:flex-row items-center gap-4 text-center sm:text-left">
                        <div class="w-12 h-12 rounded-lg bg-amber-100 dark:bg-amber-500/20 flex items-center justify-center flex-shrink-0 text-amber-500">
                            <i data-lucide="user-plus" class="w-6 h-6"></i>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-semibold text-amber-800 dark:text-amber-400">Data Anak Belum Lengkap</h4>
                            <p class="text-sm text-amber-700 dark:text-amber-300/80 mt-1">Anda belum melengkapi profil dan dokumen anak. Silakan lengkapi terlebih dahulu untuk dapat memilih gelombang pendaftaran.</p>
                        </div>
                        <a href="{{ route('parent.siswa.create') }}" class="mt-3 sm:mt-0 whitespace-nowrap bg-amber-500 hover:bg-amber-600 text-white px-4 py-2 rounded-md font-medium text-sm transition-colors shadow-sm">
                            Lengkapi Data
                        </a>
                    </div>
                @elseif(!$latestRegistration)
                    <div class="bg-blue-50 dark:bg-blue-500/10 border border-blue-200 dark:border-blue-500/20 rounded-lg p-5 flex flex-col sm:flex-row items-center gap-4 text-center sm:text-left">
                        <div class="w-12 h-12 rounded-lg bg-blue-100 dark:bg-blue-500/20 flex items-center justify-center flex-shrink-0 text-blue-500">
                            <i data-lucide="info" class="w-6 h-6"></i>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-semibold text-blue-800 dark:text-blue-400">Belum Memilih Gelombang</h4>
                            <p class="text-sm text-blue-700 dark:text-blue-300/80 mt-1">Data anak sudah lengkap, namun Anda belum mendaftar di gelombang manapun.</p>
                        </div>
                        <a href="{{ route('parent.pendaftaran.index') }}" class="sneat-btn-primary mt-3 sm:mt-0 whitespace-nowrap">
                            Daftar Sekarang
                        </a>
                    </div>
                @else
                    <!-- Status Display -->
                    <div class="border border-[#d9dee3] dark:border-[#434463] rounded-lg p-5 bg-[#f5f5f9] dark:bg-[#232333]">
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
                        
                        <!-- Actions if Accepted -->
                        @if($isAccepted)
                            <div class="mt-5 flex flex-wrap items-center gap-3">
                                <a href="{{ route('parent.siswa.kartu') }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-medium rounded-md transition-colors">
                                    <i data-lucide="printer" class="w-4 h-4"></i> Cetak Bukti Lulus
                                </a>
                                @if($latestRegistration->pembayaran && $latestRegistration->pembayaran->status === 'lunas')
                                    <a href="{{ route('parent.pembayaran.receipt', $latestRegistration->id) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium rounded-md transition-colors">
                                        <i data-lucide="file-down" class="w-4 h-4"></i> Cetak Bukti Bayar
                                    </a>
                                @else
                                    <a href="{{ route('parent.pendaftaran.status') }}" class="sneat-btn-secondary">
                                        <i data-lucide="upload" class="w-4 h-4"></i> Upload Bukti Bayar
                                    </a>
                                @endif
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Child Info Summary (If exists) -->
            @if($siswa)
            <div class="bg-white dark:bg-[#2b2c40] rounded-lg shadow-sneat dark:shadow-sneat-dark border border-[#d9dee3] dark:border-[#434463] p-6 animate-fade-up" style="animation-delay: 0.2s;">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-heading font-semibold text-[#566a7f] dark:text-[#d5d5e2] flex items-center gap-2">
                        <i data-lucide="user" class="w-5 h-5 text-[#696cff]"></i>
                        Ringkasan Profil Anak
                    </h3>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('parent.siswa.show', $siswa->id) }}" class="text-sm text-[#696cff] hover:text-[#5a5de6] font-medium">Lihat Detail &rarr;</a>
                    </div>
                </div>

                <div class="flex items-center gap-4 p-4 border border-[#d9dee3] dark:border-[#434463] rounded-lg bg-[#f5f5f9] dark:bg-[#232333]">
                    <img src="{{ $siswa->foto ? Storage::url($siswa->foto) : asset('images/default-avatar.png') }}" alt="Foto Anak" class="w-16 h-16 rounded-full object-cover border-2 border-white dark:border-[#434463] shadow-sm bg-[#f5f5f9] dark:bg-[#232333]">
                    <div class="flex-1">
                        <h4 class="font-semibold text-[#566a7f] dark:text-[#d5d5e2]">{{ $siswa->nama }}</h4>
                        <p class="text-sm text-[#a1b0cb] mt-0.5">Panggilan: {{ $siswa->nama_panggilan ?? '-' }}</p>
                        <p class="text-xs text-[#a1b0cb] mt-1"><i data-lucide="map-pin" class="w-3 h-3 inline"></i> {{ $siswa->kota }}</p>
                    </div>
                    <!-- Edit & Delete Buttons -->
                    <div class="flex flex-col gap-2">
                        <a href="{{ route('parent.siswa.edit', $siswa->id) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-[#696cff] bg-[#e7e7ff] dark:bg-[#696cff]/20 hover:bg-[#d4d5ff] dark:hover:bg-[#696cff]/30 rounded-md transition-colors">
                            <i data-lucide="edit-3" class="w-3 h-3"></i> Edit
                        </a>
                        @if(!$latestRegistration || $latestRegistration->status === 'ditolak')
                        <form action="{{ route('parent.siswa.destroy', $siswa->id) }}" method="POST" class="child-delete-form" data-child-name="{{ $siswa->nama }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-red-500 bg-red-50 dark:bg-red-500/10 hover:bg-red-100 dark:hover:bg-red-500/20 rounded-md transition-colors w-full">
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
        <div class="space-y-6 animate-fade-up" style="animation-delay: 0.3s;">
            <div class="bg-white dark:bg-[#2b2c40] rounded-lg shadow-sneat dark:shadow-sneat-dark border border-[#d9dee3] dark:border-[#434463] p-6">
                <h3 class="text-lg font-heading font-semibold text-[#566a7f] dark:text-[#d5d5e2] mb-4">Aksi Cepat</h3>
                
                <div class="space-y-3">
                    @if(!$siswa)
                        <a href="{{ route('parent.siswa.create') }}" class="flex items-center justify-between p-3 rounded-lg border border-[#d9dee3] dark:border-[#434463] hover:border-[#696cff] dark:hover:border-[#696cff] hover:bg-[#e7e7ff]/30 dark:hover:bg-[#696cff]/10 transition-colors group">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-[#e7e7ff] dark:bg-[#696cff]/20 flex items-center justify-center text-[#696cff]">
                                    <i data-lucide="file-edit" class="w-5 h-5"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-sm text-[#566a7f] dark:text-[#d5d5e2] group-hover:text-[#696cff]">Isi Data Anak</p>
                                    <p class="text-xs text-[#a1b0cb]">Lengkapi formulir biodata</p>
                                </div>
                            </div>
                            <i data-lucide="chevron-right" class="w-4 h-4 text-[#a1b0cb] group-hover:text-[#696cff] transition-transform group-hover:translate-x-1"></i>
                        </a>
                    @else
                        <a href="{{ route('parent.siswa.edit', $siswa->id) }}" class="flex items-center justify-between p-3 rounded-lg border border-[#d9dee3] dark:border-[#434463] hover:border-[#696cff] dark:hover:border-[#696cff] hover:bg-[#e7e7ff]/30 dark:hover:bg-[#696cff]/10 transition-colors group">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-[#e7e7ff] dark:bg-[#696cff]/20 flex items-center justify-center text-[#696cff]">
                                    <i data-lucide="edit-3" class="w-5 h-5"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-sm text-[#566a7f] dark:text-[#d5d5e2] group-hover:text-[#696cff]">Edit Data Anak</p>
                                    <p class="text-xs text-[#a1b0cb]">Perbarui biodata & dokumen</p>
                                </div>
                            </div>
                            <i data-lucide="chevron-right" class="w-4 h-4 text-[#a1b0cb] group-hover:text-[#696cff] transition-transform group-hover:translate-x-1"></i>
                        </a>
                    @endif

                    @if(!$isAccepted)
                    <a href="{{ route('parent.pendaftaran.index') }}" class="flex items-center justify-between p-3 rounded-lg border border-[#d9dee3] dark:border-[#434463] hover:border-blue-400 dark:hover:border-blue-400 hover:bg-blue-50/50 dark:hover:bg-blue-500/10 transition-colors group">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-blue-50 dark:bg-blue-500/10 flex items-center justify-center text-blue-500">
                                <i data-lucide="calendar-check" class="w-5 h-5"></i>
                            </div>
                            <div>
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
            <div class="bg-white dark:bg-[#2b2c40] rounded-lg shadow-sneat dark:shadow-sneat-dark p-6 text-slate-700 dark:text-white text-center">
                <div class="w-12 h-12 rounded-full bg-[#696cff]/10 dark:bg-[#696cff]/20 mx-auto flex items-center justify-center mb-4">
                    <i data-lucide="help-circle" class="w-6 h-6 text-[#696cff]"></i>
                </div>
                <h3 class="font-semibold mb-2 text-slate-900 dark:text-[#d5d5e2]">Butuh Bantuan?</h3>
                <p class="text-sm text-slate-500 dark:text-[#a1b0cb] mb-4">Jika Anda mengalami kendala saat mendaftar, silakan hubungi admin kami.</p>
                <a href="https://wa.me/{{ env('WHATSAPP_ADMIN_NUMBER', '6281310408525') }}?text={{ urlencode('Assalamu\'alaikum Admin Az Zahra, saya butuh bantuan terkait pendaftaran anak saya.') }}" target="_blank" class="inline-flex items-center justify-center gap-2 w-full py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white rounded-md transition-colors text-sm font-medium">
                    <i data-lucide="message-circle" class="w-4 h-4"></i> Hubungi WhatsApp
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
