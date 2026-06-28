@extends('app.layouts.app')

@section('content')
<div class="landing-flow">
    {{-- A single visual thread keeps the page feeling like one continuous journey. --}}
    <div class="landing-flow__backdrop" aria-hidden="true">
        <svg viewBox="0 0 1440 9000" preserveAspectRatio="none" role="presentation">
            <defs>
                <linearGradient id="landing-flow-gradient" x1="0" y1="0" x2="1" y2="1">
                    <stop offset="0%" stop-color="#696cff" />
                    <stop offset="48%" stop-color="#10b981" />
                    <stop offset="100%" stop-color="#696cff" />
                </linearGradient>
            </defs>
            <path
                class="landing-flow__ribbon"
                d="M-120 80 C 420 420, 80 920, 760 1320 S 1450 2050, 820 2520 S 120 3350, 690 3920 S 1510 4690, 790 5300 S 20 6170, 660 6820 S 1500 7670, 900 8240 S 260 8750, 60 9050" />
            <path
                class="landing-flow__thread"
                d="M-120 80 C 420 420, 80 920, 760 1320 S 1450 2050, 820 2520 S 120 3350, 690 3920 S 1510 4690, 790 5300 S 20 6170, 660 6820 S 1500 7670, 900 8240 S 260 8750, 60 9050" />
        </svg>
    </div>

    {{-- 1. THE HOOK (Kesan Pertama & Nilai Jual Cepat) --}}
    @include('app.sections.hero')
    @include('app.sections.quickHighlights')

    {{-- 2. VALUE & TRUST (Apa yang ditawarkan & Validasi) --}}
    @include('app.sections.program')
    @include('app.sections.testimonial') {{-- Dipindah ke atas agar kepercayaan cepat terbangun --}}

    {{-- 3. THE DETAILS (Penjelasan Mendalam bagi yang butuh detail) --}}
    @include('app.sections.tentangSekolah')
    @include('app.sections.kurikulum')
    @include('app.sections.g7kaih')
    @include('app.sections.fasilitas')
    @include('app.sections.gallery')

    {{-- 4. CONVERSION (Logika Praktis & Tindakan) --}}
    @include('app.sections.biaya') {{-- Biaya ditaruh pertama di sesi ini karena paling sering dicari --}}
    @include('app.sections.agenda')
    @include('app.sections.persyaratan')
    @include('app.sections.kontak')
</div>

@endsection
