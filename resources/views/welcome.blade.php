@extends('app.layouts.app')

@section('content')

{{-- 1. THE HOOK (Kesan Pertama & Nilai Jual Cepat) --}}
@include('app.sections.hero')
@include('app.sections.quickHighlights') 

{{-- 2. VALUE & TRUST (Apa yang ditawarkan & Validasi) --}}
@include('app.sections.program')
@include('app.sections.testimonial') {{-- Dipindah ke atas agar kepercayaan cepat terbangun --}}

{{-- 3. THE DETAILS (Penjelasan Mendalam bagi yang butuh detail) --}}
@include('app.sections.tentangSekolah')
@include('app.sections.kurikulum')
@include('app.sections.fasilitas')
@include('app.sections.gallery')

{{-- 4. CONVERSION (Logika Praktis & Tindakan) --}}
@include('app.sections.biaya') {{-- Biaya ditaruh pertama di sesi ini karena paling sering dicari --}}
@include('app.sections.agenda')
@include('app.sections.persyaratan')
@include('app.sections.kontak')

@endsection