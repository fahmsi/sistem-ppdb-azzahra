@extends('layouts.app')

@section('title', 'Detail Gelombang SPMB')
@section('header_title', 'Detail Gelombang Pendaftaran')

@section('content')
<div class="mx-auto max-w-3xl space-y-5">
    <a href="{{ route('parent.siswa.pendaftaran.index', $siswa) }}" class="inline-flex items-center gap-2 text-sm font-semibold text-[#696cff] hover:text-[#5a5de6]">
        <i data-lucide="arrow-left" class="h-4 w-4"></i>
        Kembali ke Daftar Gelombang
    </a>

    <article class="overflow-hidden rounded-lg border border-[#d9dee3] bg-white shadow-sneat dark:border-[#434463] dark:bg-[#2b2c40] dark:shadow-sneat-dark">
        @if($pendaftaran->gambar)
            <img src="{{ Storage::url($pendaftaran->gambar) }}" alt="Banner {{ $pendaftaran->gelombang }}" class="h-48 w-full object-cover sm:h-64">
        @endif

        <div class="space-y-6 p-5 sm:p-7">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                <div class="min-w-0">
                    <p class="text-sm font-semibold text-[#696cff]">Tahun Ajaran {{ $pendaftaran->tahun_ajaran }}</p>
                    <h2 class="mt-1 break-words text-2xl font-bold text-[#566a7f] dark:text-[#d5d5e2]">{{ $pendaftaran->gelombang }}</h2>
                </div>
                <span class="sneat-badge w-fit {{ $pendaftaran->is_bisa_dipilih ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400' : 'bg-red-50 text-red-700 dark:bg-red-500/10 dark:text-red-400' }}">
                    {{ $pendaftaran->is_bisa_dipilih ? 'Pendaftaran Dibuka' : 'Tidak Tersedia' }}
                </span>
            </div>

            <dl class="grid gap-4 rounded-lg border border-[#d9dee3] bg-[#f5f5f9] p-4 dark:border-[#434463] dark:bg-[#232333] sm:grid-cols-2">
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wide text-[#a1b0cb]">Periode</dt>
                    <dd class="mt-1 text-sm font-semibold text-[#566a7f] dark:text-[#d5d5e2]">{{ $pendaftaran->tanggal_mulai->translatedFormat('d M Y') }} – {{ $pendaftaran->tanggal_selesai->translatedFormat('d M Y') }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wide text-[#a1b0cb]">Kuota</dt>
                    <dd class="mt-1 text-sm font-semibold text-[#566a7f] dark:text-[#d5d5e2]">{{ $pendaftaran->kuota }} siswa · tersisa {{ $pendaftaran->sisa_kuota }}</dd>
                </div>
            </dl>

            @if($existingDetail)
                <div class="rounded-lg border border-blue-200 bg-blue-50 p-4 text-sm text-blue-800 dark:border-blue-500/20 dark:bg-blue-500/10 dark:text-blue-300">
                    @php
                        $statusNames = [
                            'pending' => 'Pending',
                            'menunggu_verifikasi' => 'Menunggu Verifikasi Berkas',
                            'perlu_revisi' => 'Perlu Revisi Data',
                            'ditolak' => 'Pendaftaran Ditolak',
                            'diterima' => 'Berkas Terverifikasi – Lanjut Observasi',
                        ];
                        $displayStatus = $statusNames[$existingDetail->status] ?? str($existingDetail->status)->replace('_', ' ')->title();
                    @endphp
                    {{ $siswa->nama }} sudah terdaftar pada gelombang ini dengan status <strong>{{ $displayStatus }}</strong>.
                    <a href="{{ route('parent.siswa.pendaftaran.status', $siswa) }}" class="mt-2 block font-semibold underline">Lihat status pendaftaran</a>
                </div>
            @elseif($hasActiveRegistration)
                <div class="rounded-lg border border-amber-200 bg-amber-50 p-4 text-sm text-amber-800 dark:border-amber-500/20 dark:bg-amber-500/10 dark:text-amber-300">
                    {{ $siswa->nama }} sudah memiliki pendaftaran aktif. Pantau prosesnya pada halaman status pendaftaran.
                </div>
            @elseif($pendaftaran->is_bisa_dipilih)
                <form action="{{ route('parent.siswa.pendaftaran.daftar', ['siswa' => $siswa, 'pendaftaran' => $pendaftaran]) }}" method="POST" class="space-y-4">
                    @csrf
                    <label for="data_declaration" class="flex cursor-pointer items-start gap-3 text-sm leading-6 text-[#697a8d] dark:text-[#a1b0cb]">
                        <input id="data_declaration" type="checkbox" name="data_declaration" value="1" required @checked(old('data_declaration')) class="mt-1 h-4 w-4 flex-shrink-0 rounded border-[#d9dee3] text-[#696cff] focus:ring-[#696cff]">
                        <span>Saya menyatakan data dan dokumen yang diunggah adalah benar.</span>
                    </label>
                    @error('data_declaration')<p class="text-sm text-red-500">{{ $message }}</p>@enderror
                    <button type="submit" class="sneat-btn-primary w-full justify-center sm:w-auto">
                        Daftar ke Gelombang Ini
                        <i data-lucide="arrow-right" class="h-4 w-4"></i>
                    </button>
                </form>
            @else
                <p class="rounded-lg border border-[#d9dee3] bg-[#f5f5f9] p-4 text-sm text-[#697a8d] dark:border-[#434463] dark:bg-[#232333] dark:text-[#a1b0cb]">Gelombang ini sudah ditutup, melewati batas waktu, atau kuotanya penuh.</p>
            @endif
        </div>
    </article>
</div>
@endsection
