@extends('layouts.misc')

@section('title', 'Segera Hadir | SPMB PAUD Az-Zahra')
@section('meta_description', 'Halaman atau fitur ini sedang dalam tahap pengembangan dan akan segera hadir.')

@section('content')
<div class="mx-auto max-w-3xl text-center">
    <!-- Card Wrapper -->
    <div class="rounded-3xl border border-slate-200/80 bg-white p-8 shadow-xl shadow-slate-200/50 dark:border-slate-800 dark:bg-[#2b2c40] dark:shadow-none sm:p-12">
        <!-- Status Badge -->
        <div class="mb-6 inline-flex items-center gap-2 rounded-full border border-indigo-200 bg-indigo-50 px-4 py-1.5 text-xs font-bold text-indigo-700 dark:border-indigo-900/50 dark:bg-indigo-950/40 dark:text-indigo-400">
            <i data-lucide="sparkles" class="h-3.5 w-3.5 text-[#696cff]"></i>
            <span>Dalam Pengembangan (Coming Soon)</span>
        </div>

        <!-- Illustration -->
        <div class="relative mx-auto mb-8 max-w-xs overflow-hidden rounded-2xl p-4 transition-transform duration-300 hover:scale-[1.02]">
            <img src="{{ asset('images/girl-doing-yoga-light.png') }}"
                 alt="Ilustrasi Segera Hadir"
                 class="mx-auto h-56 w-auto max-w-full object-contain sm:h-64"
                 loading="eager">
        </div>

        <!-- Heading & Description -->
        <h1 class="text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white sm:text-4xl">
            Sesuatu yang Menarik Segera Hadir!
        </h1>
        <p class="mt-4 text-sm leading-relaxed text-slate-600 dark:text-slate-300 sm:text-base">
            Kami sedang merancang dan menyempurnakan fitur ini untuk memberikan pengalaman terbaik dalam pendaftaran siswa baru PAUD Az-Zahra. Tetap pantau pembaruan kami!
        </p>

        <!-- Navigation Buttons -->
        <div class="mt-8 flex flex-col items-center justify-center gap-3 sm:flex-row sm:gap-4">
            <!-- Navigasi Ke Beranda Landing Page -->
            <a href="{{ route('home') }}"
               class="w-full inline-flex items-center justify-center gap-2.5 rounded-xl bg-[#696cff] px-6 py-3.5 text-sm font-bold text-white shadow-md shadow-[#696cff]/25 transition-all duration-200 ease-in-out hover:bg-[#5b5ebd] hover:shadow-lg hover:shadow-[#696cff]/35 focus:outline-none focus-visible:ring-2 focus-visible:ring-[#696cff] active:scale-[0.98] sm:w-auto">
                <i data-lucide="home" class="h-4 w-4"></i>
                <span>Kembali ke Beranda</span>
            </a>

            <!-- Navigasi Ke Login -->
            <a href="{{ route('login') }}"
               class="w-full inline-flex items-center justify-center gap-2.5 rounded-xl border border-slate-300 bg-white px-6 py-3.5 text-sm font-bold text-slate-700 shadow-sm transition-all duration-200 ease-in-out hover:bg-slate-50 hover:border-slate-400 hover:text-[#696cff] dark:border-slate-700 dark:bg-[#232333] dark:text-slate-200 dark:hover:bg-[#32344d] dark:hover:text-indigo-400 focus:outline-none focus-visible:ring-2 focus-visible:ring-[#696cff] active:scale-[0.98] sm:w-auto">
                <i data-lucide="log-in" class="h-4 w-4 text-[#696cff] dark:text-indigo-400"></i>
                <span>Halaman Login</span>
            </a>
        </div>
    </div>
</div>
@endsection
