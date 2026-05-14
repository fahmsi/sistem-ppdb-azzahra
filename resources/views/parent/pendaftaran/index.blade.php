@extends('layouts.app')
@section('title', 'Daftar Gelombang PPDB')
@section('header_title', 'Pilih Gelombang Pendaftaran')
@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <div class="bg-white dark:bg-[#2b2c40] rounded-lg shadow-sneat dark:shadow-sneat-dark border border-[#d9dee3] dark:border-[#434463] p-6 sm:p-8">
        <h2 class="text-2xl font-heading font-bold text-[#566a7f] dark:text-[#d5d5e2] mb-2">Gelombang Pendaftaran Tersedia</h2>
        <p class="text-[#a1b0cb] text-sm max-w-3xl">Pilih salah satu gelombang di bawah ini untuk mendaftarkan anak Anda. Pastikan data profil anak sudah lengkap sebelum memilih gelombang.</p>
        @if(!auth()->user()->siswa)
        <div class="mt-4 p-4 rounded-lg bg-amber-50 dark:bg-amber-500/10 border border-amber-200 dark:border-amber-500/20 flex items-start gap-3">
            <i data-lucide="alert-triangle" class="w-5 h-5 text-amber-500 mt-0.5 flex-shrink-0"></i>
            <div>
                <h4 class="font-medium text-amber-800 dark:text-amber-400 text-sm">Perhatian</h4>
                <p class="text-sm text-amber-700 dark:text-amber-300/80 mt-1">Anda belum melengkapi data profil anak. Anda tidak dapat melakukan pendaftaran sebelum profil dilengkapi.</p>
                <a href="{{ route('parent.siswa.create') }}" class="inline-block mt-2 text-sm font-semibold text-amber-800 dark:text-amber-400 hover:underline">Lengkapi Data Anak Sekarang &rarr;</a>
            </div>
        </div>
        @endif
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($pendaftarans as $p)
        <div class="bg-white dark:bg-[#2b2c40] rounded-lg shadow-sneat dark:shadow-sneat-dark border border-[#d9dee3] dark:border-[#434463] overflow-hidden flex flex-col hover:shadow-sneat-lg dark:hover:shadow-sneat-dark-lg transition-shadow">
            @if($p->gambar)
                <img src="{{ Storage::url($p->gambar) }}" class="w-full h-40 object-cover" alt="Banner Gelombang">
            @else
                <div class="w-full h-40 bg-gradient-to-br from-[#696cff] to-[#7b7dff] flex items-center justify-center p-6 text-center">
                    <i data-lucide="calendar" class="w-12 h-12 text-white/50"></i>
                </div>
            @endif
            <div class="p-6 flex-1 flex flex-col">
                <div class="flex items-center justify-between mb-3">
                    <span class="sneat-badge {{ $p->status === 'buka' ? 'bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400' : 'bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400' }}">
                        {{ $p->status === 'buka' ? 'Pendaftaran Dibuka' : 'Ditutup' }}
                    </span>
                    <span class="text-sm font-medium text-[#a1b0cb]">T.A. {{ $p->tahun_ajaran }}</span>
                </div>
                <h3 class="text-xl font-heading font-bold text-[#566a7f] dark:text-[#d5d5e2] mb-2">{{ $p->gelombang }}</h3>
                <div class="space-y-2 mb-6">
                    <div class="flex items-center gap-2 text-sm text-[#697a8d] dark:text-[#a1b0cb]">
                        <i data-lucide="calendar-range" class="w-4 h-4 text-[#696cff]"></i>
                        {{ \Carbon\Carbon::parse($p->tanggal_mulai)->format('d M') }} - {{ \Carbon\Carbon::parse($p->tanggal_selesai)->format('d M Y') }}
                    </div>
                    <div class="flex items-center gap-2 text-sm text-[#697a8d] dark:text-[#a1b0cb]">
                        <i data-lucide="users" class="w-4 h-4 text-[#696cff]"></i>
                        Kuota: {{ $p->kuota }} Siswa
                    </div>
                </div>
                <div class="mt-auto">
                    @if($p->status === 'buka')
                        @if(!$siswa)
                            <button disabled class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-[#f5f5f9] dark:bg-[#232333] text-[#a1b0cb] font-semibold rounded-md cursor-not-allowed">Lengkapi Data Dahulu</button>
                        @elseif($isAccepted)
                            <button disabled class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-[#f5f5f9] dark:bg-[#232333] text-[#a1b0cb] font-semibold rounded-md cursor-not-allowed"><i data-lucide="check" class="w-4 h-4"></i> Anak Sudah Diterima</button>
                        @elseif($hasActiveRegistration)
                            <button disabled class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400 font-semibold rounded-md cursor-not-allowed"><i data-lucide="lock" class="w-4 h-4"></i> Sudah Terdaftar di Gelombang Lain</button>
                        @else
                            <form action="{{ route('parent.pendaftaran.daftar', $p->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin mendaftar di gelombang ini?')">@csrf
                                <button type="submit" class="w-full sneat-btn-primary justify-center py-2.5 font-semibold">Daftar Sekarang <i data-lucide="arrow-right" class="w-4 h-4"></i></button>
                            </form>
                        @endif
                    @else
                        <button disabled class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-[#f5f5f9] dark:bg-[#232333] text-[#a1b0cb] font-semibold rounded-md cursor-not-allowed border border-[#d9dee3] dark:border-[#434463]">Pendaftaran Ditutup</button>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full py-12 flex flex-col items-center justify-center text-center bg-white dark:bg-[#2b2c40] rounded-lg shadow-sneat dark:shadow-sneat-dark border border-[#d9dee3] dark:border-[#434463]">
            <div class="w-16 h-16 bg-[#f5f5f9] dark:bg-[#232333] rounded-full flex items-center justify-center mb-4"><i data-lucide="calendar-x" class="w-8 h-8 text-[#a1b0cb]"></i></div>
            <h3 class="text-lg font-heading font-semibold text-[#566a7f] dark:text-[#d5d5e2]">Tidak Ada Pendaftaran</h3>
            <p class="text-[#a1b0cb] mt-1 max-w-md">Saat ini belum ada gelombang pendaftaran yang dibuka oleh pihak sekolah.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
