@extends('app.layouts.app')

@section('content')

{{-- 1. ATTENTION (Menarik Perhatian) --}}
@include('app.sections.hero')
@include('app.sections.quickHighlights') 

{{-- 2. INTEREST (Membangun Ketertarikan & Narasi) --}}
@include('app.sections.tentangSekolah')

{{-- 3. DESIRE (Menawarkan Nilai & Bukti Nyata) --}}
@include('app.sections.program')
@include('app.sections.kurikulum')
@include('app.sections.fasilitas')
@include('app.sections.testimonial')

{{-- 4. ACTION (Mendorong Tindakan & Urgensi) --}}
@include('app.sections.agenda')
@include('app.sections.persyaratan')
@include('app.sections.biaya')
@include('app.sections.kontak')

@endsection