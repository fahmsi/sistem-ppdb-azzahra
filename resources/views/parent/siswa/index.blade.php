@extends('layouts.app')

@section('title', 'Anak Saya')
@section('header_title', 'Anak Saya')

@section('content')
<div class="mx-auto max-w-6xl space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="font-heading text-2xl font-bold text-[#566a7f] dark:text-[#d5d5e2]">Anak Saya</h2>
            <p class="mt-1 text-sm text-[#a1b0cb]">Kelola profil anak dan lanjutkan proses pendaftaran masing-masing anak.</p>
        </div>
        <a href="{{ route('parent.siswa.create') }}" class="sneat-btn-primary justify-center">
            <i data-lucide="plus" class="h-4 w-4"></i>
            Tambah Anak
        </a>
    </div>

    @if($siswas->isEmpty())
        <div class="rounded-lg border border-[#d9dee3] bg-white p-8 text-center shadow-sneat dark:border-[#434463] dark:bg-[#2b2c40] dark:shadow-sneat-dark">
            <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-[#e7e7ff] text-[#696cff] dark:bg-[#696cff]/20">
                <i data-lucide="user-plus" class="h-7 w-7"></i>
            </div>
            <h3 class="font-heading text-lg font-semibold text-[#566a7f] dark:text-[#d5d5e2]">Belum Ada Data Anak</h3>
            <p class="mx-auto mt-2 max-w-md text-sm text-[#a1b0cb]">Anda belum memiliki data anak. Tambahkan data anak terlebih dahulu untuk memulai pendaftaran SPMB.</p>
            <a href="{{ route('parent.siswa.create') }}" class="sneat-btn-primary mt-5 inline-flex justify-center">
                <i data-lucide="plus" class="h-4 w-4"></i>
                Tambah Data Anak
            </a>
        </div>
    @else
        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
            @foreach($siswas as $siswa)
                @php
                    $latestRegistration = $siswa->pendaftaranDetails->first();
                    $statusLabels = [
                        'pending' => 'Pending',
                        'menunggu_verifikasi' => 'Menunggu Verifikasi',
                        'perlu_revisi' => 'Perlu Revisi',
                        'ditolak' => 'Ditolak',
                        'diterima' => 'Berkas Terverifikasi',
                    ];
                    $statusClasses = [
                        'pending' => 'bg-gray-100 text-gray-700 dark:bg-[#434463] dark:text-[#d5d5e2]',
                        'menunggu_verifikasi' => 'bg-amber-50 text-amber-700 dark:bg-amber-500/10 dark:text-amber-300',
                        'perlu_revisi' => 'bg-orange-50 text-orange-700 dark:bg-orange-500/10 dark:text-orange-300',
                        'ditolak' => 'bg-red-50 text-red-700 dark:bg-red-500/10 dark:text-red-300',
                        'diterima' => 'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-300',
                    ];
                @endphp
                <article class="flex flex-col rounded-lg border border-[#d9dee3] bg-white p-5 shadow-sneat dark:border-[#434463] dark:bg-[#2b2c40] dark:shadow-sneat-dark">
                    <div class="flex items-start gap-4">
                        <img src="{{ $siswa->foto ? Storage::url($siswa->foto) : asset('images/default-avatar.png') }}" alt="Foto {{ $siswa->nama }}" class="h-16 w-16 rounded-lg border border-[#d9dee3] object-cover dark:border-[#434463]">
                        <div class="min-w-0 flex-1">
                            <h3 class="break-words font-heading text-lg font-bold text-[#566a7f] dark:text-[#d5d5e2]">{{ $siswa->nama }}</h3>
                            <p class="text-sm text-[#a1b0cb]">Panggilan: {{ $siswa->nama_panggilan ?: '-' }}</p>
                            <p class="mt-1 text-xs text-[#a1b0cb]">{{ $siswa->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }} &middot; {{ $siswa->tanggal_lahir ? \Carbon\Carbon::parse($siswa->tanggal_lahir)->translatedFormat('d M Y') : '-' }}</p>
                        </div>
                    </div>

                    <div class="mt-4 rounded-lg bg-[#f5f5f9] p-3 dark:bg-[#232333]">
                        <p class="text-xs font-semibold uppercase tracking-wide text-[#a1b0cb]">Status Pendaftaran Terbaru</p>
                        @if($latestRegistration)
                            <div class="mt-2 flex flex-wrap items-center gap-2">
                                <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $statusClasses[$latestRegistration->status] ?? 'bg-gray-100 text-gray-700' }}">
                                    {{ $statusLabels[$latestRegistration->status] ?? str($latestRegistration->status)->replace('_', ' ')->title() }}
                                </span>
                                <span class="text-xs text-[#a1b0cb]">{{ $latestRegistration->pendaftaran?->gelombang ?? 'Gelombang tidak tersedia' }}</span>
                            </div>
                        @else
                            <p class="mt-2 text-sm text-[#697a8d] dark:text-[#a1b0cb]">Belum mendaftar gelombang.</p>
                        @endif
                    </div>

                    <div class="mt-5 grid grid-cols-2 gap-2">
                        <a href="{{ route('parent.siswa.show', $siswa) }}" class="inline-flex items-center justify-center gap-2 rounded-md border border-[#d9dee3] px-3 py-2 text-sm font-medium text-[#566a7f] transition-colors hover:bg-[#f5f5f9] dark:border-[#434463] dark:text-[#d5d5e2] dark:hover:bg-[#232333]">
                            <i data-lucide="eye" class="h-4 w-4"></i>
                            Lihat
                        </a>
                        <a href="{{ route('parent.siswa.edit', $siswa) }}" class="inline-flex items-center justify-center gap-2 rounded-md border border-[#d9dee3] px-3 py-2 text-sm font-medium text-[#566a7f] transition-colors hover:bg-[#f5f5f9] dark:border-[#434463] dark:text-[#d5d5e2] dark:hover:bg-[#232333]">
                            <i data-lucide="edit-3" class="h-4 w-4"></i>
                            Edit
                        </a>
                        <a href="{{ route('parent.siswa.pendaftaran.index', $siswa) }}" class="sneat-btn-primary col-span-2 justify-center">
                            <i data-lucide="clipboard-list" class="h-4 w-4"></i>
                            Lihat Proses Anak
                        </a>
                    </div>
                </article>
            @endforeach
        </div>
    @endif
</div>
@endsection
