@extends('layouts.misc')

@section('title', '403 - Akses Ditolak | SPMB PAUD Az-Zahra')
@section('meta_description', 'Anda tidak memiliki hak akses untuk membuka halaman ini.')

@section('content')
<div class="mx-auto max-w-3xl text-center">
    <!-- Card Wrapper -->
    <div class="rounded-3xl border border-slate-200/80 bg-white p-8 shadow-xl shadow-slate-200/50 dark:border-slate-800 dark:bg-[#2b2c40] dark:shadow-none sm:p-12">
        <!-- Status Badge -->
        <div class="mb-6 inline-flex items-center gap-2 rounded-full border border-orange-200 bg-orange-50 px-4 py-1.5 text-xs font-bold text-orange-700 dark:border-orange-900/50 dark:bg-orange-950/40 dark:text-orange-400">
            <i data-lucide="shield-alert" class="h-3.5 w-3.5 text-orange-600"></i>
            <span>Akses Dibatasi (403 Forbidden)</span>
        </div>

        <!-- Illustration -->
        <div class="relative mx-auto mb-8 max-w-sm overflow-hidden rounded-2xl p-4 transition-transform duration-300 hover:scale-[1.02]">
            <img src="{{ asset('images/hero-paud-azzahra.png') }}"
                 alt="Ilustrasi Akses Tidak Diizinkan"
                 class="mx-auto h-56 w-auto max-w-full object-contain sm:h-64"
                 loading="eager">
        </div>

        <!-- Heading & Description -->
        <h1 class="text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white sm:text-4xl">
            Akses Ditolak (Not Authorized)
        </h1>
        <p class="mt-4 text-sm leading-relaxed text-slate-600 dark:text-slate-300 sm:text-base">
            Maaf, akun Anda tidak memiliki hak akses yang diperlukan untuk melihat halaman ini. Silakan pastikan Anda sudah masuk dengan akun yang benar.
        </p>

        <!-- Navigation Buttons -->
        <div class="mt-8 flex flex-col items-center justify-center gap-3 sm:flex-row sm:gap-4">
            <!-- Navigasi Ke Beranda Landing Page -->
            <a href="{{ route('home') }}"
               class="w-full inline-flex items-center justify-center gap-2.5 rounded-xl bg-[#696cff] px-6 py-3.5 text-sm font-bold text-white shadow-md shadow-[#696cff]/25 transition-all duration-200 ease-in-out hover:bg-[#5b5ebd] hover:shadow-lg hover:shadow-[#696cff]/35 focus:outline-none focus-visible:ring-2 focus-visible:ring-[#696cff] active:scale-[0.98] sm:w-auto">
                <i data-lucide="home" class="h-4 w-4"></i>
                <span>Kembali ke Beranda</span>
            </a>

            <!-- Navigasi Ke Login (Masuk dengan Akun Lain) -->
            <a href="{{ route('login') }}"
               class="w-full inline-flex items-center justify-center gap-2.5 rounded-xl border border-slate-300 bg-white px-6 py-3.5 text-sm font-bold text-slate-700 shadow-sm transition-all duration-200 ease-in-out hover:bg-slate-50 hover:border-slate-400 hover:text-[#696cff] dark:border-slate-700 dark:bg-[#232333] dark:text-slate-200 dark:hover:bg-[#32344d] dark:hover:text-indigo-400 focus:outline-none focus-visible:ring-2 focus-visible:ring-[#696cff] active:scale-[0.98] sm:w-auto">
                <i data-lucide="log-in" class="h-4 w-4 text-[#696cff] dark:text-indigo-400"></i>
                <span>Masuk Akun Lain</span>
            </a>

            <!-- Action Kembali Ke Halaman Sebelumnya -->
            <button onclick="window.history.back()"
                    type="button"
                    class="w-full inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-slate-100 px-5 py-3.5 text-sm font-semibold text-slate-600 transition-all duration-200 ease-in-out hover:bg-slate-200 hover:text-slate-900 dark:border-slate-800 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700 dark:hover:text-white focus:outline-none focus-visible:ring-2 focus-visible:ring-[#696cff] active:scale-[0.98] sm:w-auto">
                <i data-lucide="arrow-left" class="h-4 w-4"></i>
                <span>Halaman Sebelumnya</span>
            </button>
        </div>
    </div>
</div>
@endsection
