@extends('layouts.app')

@section('title', 'Kelola Gelombang Pendaftaran')
@section('header_title', 'Gelombang Pendaftaran')

@section('content')
<div class="space-y-6">

    <!-- Header Card -->
    <div class="bg-white dark:bg-[#2b2c40] rounded-lg shadow-sneat dark:shadow-sneat-dark border border-[#d9dee3] dark:border-[#434463] p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <h2 class="text-xl font-bold text-[#566a7f] dark:text-[#d5d5e2] flex items-center gap-2 m-0">
            Gelombang Pendaftaran
        </h2>
        <a href="{{ route('admin.pendaftaran.create') }}" class="sneat-btn-primary">
            <i data-lucide="plus-circle" class="w-4 h-4"></i> Buat Gelombang Baru
        </a>
    </div>

    <!-- Data Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        @forelse($pendaftarans as $p)
            <div class="bg-white dark:bg-[#2b2c40] rounded-lg shadow-sneat dark:shadow-sneat-dark border {{ $p->status === 'buka' ? 'border-[#696cff]/30 ring-1 ring-[#696cff]/10' : 'border-[#d9dee3] dark:border-[#434463]' }} overflow-hidden flex flex-col hover:shadow-sneat-lg dark:hover:shadow-sneat-dark-lg transition-all animate-fade-up relative">
                
                <!-- Status Badge Absolute -->
                <div class="absolute top-4 right-4 z-10">
                    <span class="px-3 py-1 text-xs font-bold rounded-full shadow-sm backdrop-blur-sm {{ $p->status === 'buka' ? 'bg-emerald-500/90 text-white' : 'bg-red-500/90 text-white' }}">
                        {{ $p->status === 'buka' ? 'BUKA' : 'TUTUP' }}
                    </span>
                </div>

                <!-- Banner Image -->
                <div class="h-32 bg-[#f5f5f9] dark:bg-[#232333] relative">
                    @if($p->gambar)
                        <img src="{{ Storage::url($p->gambar) }}" class="w-full h-full object-cover" alt="Banner Gelombang">
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-[#2b2c40] to-[#232333] flex items-center justify-center">
                            <i data-lucide="image" class="w-8 h-8 text-white/30"></i>
                        </div>
                    @endif
                </div>
                
                <div class="p-6 flex-1 flex flex-col">
                    <h3 class="text-xl font-heading font-bold text-[#566a7f] dark:text-[#d5d5e2] mb-1">{{ $p->gelombang }}</h3>
                    <p class="text-sm font-medium text-[#696cff] mb-4">Tahun Ajaran {{ $p->tahun_ajaran }}</p>
                    
                    <div class="space-y-3 mb-6 bg-[#f5f5f9] dark:bg-[#232333] rounded-lg p-4 border border-[#d9dee3] dark:border-[#434463]">
                        <div class="flex items-start justify-between text-sm">
                            <span class="text-[#a1b0cb]">Masa Pendaftaran:</span>
                            <span class="font-medium text-[#566a7f] dark:text-[#d5d5e2] text-right">{{ \Carbon\Carbon::parse($p->tanggal_mulai)->format('d M Y') }} <br> s/d {{ \Carbon\Carbon::parse($p->tanggal_selesai)->format('d M Y') }}</span>
                        </div>
                        <div class="flex items-center justify-between text-sm pt-3 border-t border-[#d9dee3] dark:border-[#434463]">
                            <span class="text-[#a1b0cb]">Kuota / Pendaftar:</span>
                            <span class="font-medium text-[#566a7f] dark:text-[#d5d5e2]">
                                <span class="text-[#696cff] font-bold">{{ $p->pendaftaran_details_count ?? 0 }}</span> / {{ $p->kuota }}
                            </span>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="mt-auto grid grid-cols-2 gap-3">
                        <a href="{{ route('admin.pendaftaran.edit', $p->id) }}" class="sneat-btn-secondary justify-center">
                            <i data-lucide="edit" class="w-4 h-4"></i> Edit
                        </a>
                        
                        <form action="{{ route('admin.pendaftaran.toggle', $p->id) }}" method="POST" class="w-full">
                            @csrf
                            @method('PATCH')
                            @if($p->status === 'buka')
                                <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-3 py-2 bg-red-50 dark:bg-red-500/10 hover:bg-red-100 dark:hover:bg-red-500/20 text-red-600 dark:text-red-400 border border-red-200 dark:border-red-500/20 text-sm font-medium rounded-md transition-colors">
                                    <i data-lucide="lock" class="w-4 h-4"></i> Tutup
                                </button>
                            @else
                                <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-3 py-2 bg-emerald-50 dark:bg-emerald-500/10 hover:bg-emerald-100 dark:hover:bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/20 text-sm font-medium rounded-md transition-colors">
                                    <i data-lucide="unlock" class="w-4 h-4"></i> Buka
                                </button>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-16 flex flex-col items-center justify-center text-center bg-white dark:bg-[#2b2c40] rounded-lg shadow-sneat dark:shadow-sneat-dark border border-[#d9dee3] dark:border-[#434463]">
                <div class="w-16 h-16 bg-[#f5f5f9] dark:bg-[#232333] rounded-full flex items-center justify-center mb-4">
                    <i data-lucide="calendar-plus" class="w-8 h-8 text-[#a1b0cb]"></i>
                </div>
                <h3 class="text-lg font-heading font-semibold text-[#566a7f] dark:text-[#d5d5e2]">Belum Ada Gelombang Pendaftaran</h3>
                <p class="text-[#a1b0cb] mt-1 mb-6">Silakan buat gelombang pendaftaran baru untuk mulai menerima siswa.</p>
                <a href="{{ route('admin.pendaftaran.create') }}" class="sneat-btn-primary">
                    <i data-lucide="plus" class="w-5 h-5"></i> Buat Sekarang
                </a>
            </div>
        @endforelse
    </div>
</div>
@endsection
