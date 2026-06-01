@extends('layouts.app')

@section('title', 'Detail Profil Anak')
@section('header_title', 'Profil Biodata Anak')

@section('content')
<style>
    @media print {
        body * {
            visibility: hidden !important;
        }

        #printableArea, #printableArea * {
            visibility: visible !important;
        }

        #printableArea {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }

        .no-print {
            display: none !important;
        }
    }
</style>
<div class="max-w-5xl mx-auto space-y-6">

    <!-- Action Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 animate-fade-in no-print">
        <a href="{{ route('admin.siswa.index') }}" class="inline-flex items-center gap-2 text-sm font-medium text-gray-500 hover:text-primary-600 transition-colors">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali ke Data Siswa
        </a>
        <div class="flex items-center gap-2">
            @if($siswa->no_telpon)
            <a href="https://wa.me/62{{ ltrim(preg_replace('/^0/', '', $siswa->no_telpon), '+') }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-green-50 text-green-700 hover:bg-green-100 border border-green-200 rounded-lg text-sm font-medium transition-colors">
                <i data-lucide="message-circle" class="w-4 h-4"></i> Chat WA
            </a>
            @endif
            <button onclick="window.print()" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 hover:bg-gray-200 border border-gray-200 rounded-lg text-sm font-medium transition-colors">
                <i data-lucide="printer" class="w-4 h-4"></i> Cetak PDF
            </button>
        </div>
    </div>

    <!-- Main Profile Card -->
    <div id="printableArea" class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden animate-fade-up">
        
        <!-- Header / Cover -->
        <div class="h-32 bg-gradient-to-r from-primary-600 to-primary-800 relative">
            <div class="absolute inset-0 bg-white/10 pattern-dots opacity-30"></div>
        </div>

        <div class="px-6 sm:px-10 pb-10">
            <!-- Avatar & Quick Info -->
            <div class="flex flex-col sm:flex-row gap-6 items-start -mt-16 mb-8 relative z-10">
                <img src="{{ $siswa->foto ? Storage::url($siswa->foto) : asset('images/default-avatar.png') }}" alt="Foto Siswa" class="w-32 h-32 rounded-2xl object-cover border-4 border-white shadow-md bg-gray-100">
                <div class="pt-16 sm:pt-18 flex-1 w-full">
                    <h2 class="text-3xl font-heading font-bold text-gray-900">{{ $siswa->nama }}</h2>
                    <div class="flex flex-wrap gap-4 mt-2 text-sm text-gray-600">
                        @if($siswa->nama_panggilan)
                            <span class="flex items-center gap-1.5"><i data-lucide="user" class="w-4 h-4 text-primary-500"></i> Panggilan: {{ $siswa->nama_panggilan }}</span>
                        @endif
                        <span class="flex items-center gap-1.5"><i data-lucide="calendar" class="w-4 h-4 text-primary-500"></i> {{ $siswa->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                        <span class="flex items-center gap-1.5"><i data-lucide="map-pin" class="w-4 h-4 text-primary-500"></i> {{ $siswa->kota }}</span>
                    </div>
                </div>
            </div>

            <!-- Detail Tabs/Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                
                <!-- Left Column -->
                <div class="space-y-8">
                    <!-- Info Pribadi -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2 border-b border-gray-100 pb-2">
                            <i data-lucide="user" class="w-5 h-5 text-primary-600"></i> Informasi Pribadi
                        </h3>
                        <dl class="space-y-3 text-sm">
                            <div class="flex justify-between">
                                <dt class="text-gray-500">Jenis Kelamin</dt>
                                <dd class="font-medium text-gray-900">{{ $siswa->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-gray-500">Tempat, Tanggal Lahir</dt>
                                <dd class="font-medium text-gray-900">{{ $siswa->tempat_lahir }}, {{ \Carbon\Carbon::parse($siswa->tanggal_lahir)->format('d F Y') }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-gray-500">Agama</dt>
                                <dd class="font-medium text-gray-900">{{ $siswa->agama }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-gray-500">Anak Ke</dt>
                                <dd class="font-medium text-gray-900">{{ $siswa->anak_ke }} dari {{ $siswa->jumlah_saudara + 1 }} Bersaudara</dd>
                            </div>
                            @if($siswa->hobi)
                            <div class="flex justify-between">
                                <dt class="text-gray-500">Hobi</dt>
                                <dd class="font-medium text-gray-900">{{ $siswa->hobi }}</dd>
                            </div>
                            @endif
                            @if($siswa->cita_cita)
                            <div class="flex justify-between">
                                <dt class="text-gray-500">Cita-cita</dt>
                                <dd class="font-medium text-gray-900">{{ $siswa->cita_cita }}</dd>
                            </div>
                            @endif
                        </dl>
                    </div>

                    <!-- Kontak & Alamat -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2 border-b border-gray-100 pb-2">
                            <i data-lucide="map-pin" class="w-5 h-5 text-primary-600"></i> Kontak & Alamat
                        </h3>
                        <dl class="space-y-3 text-sm">
                            <div class="flex justify-between">
                                <dt class="text-gray-500">No. Telepon/WA</dt>
                                <dd class="font-medium text-gray-900">{{ $siswa->no_telpon }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-gray-500">Jenis Tinggal</dt>
                                <dd class="font-medium text-gray-900">{{ $siswa->jenis_tempat_tinggal }}</dd>
                            </div>
                            <div class="flex flex-col mt-2 pt-2 border-t border-gray-50">
                                <dt class="text-gray-500 mb-1">Alamat Lengkap</dt>
                                <dd class="font-medium text-gray-900 leading-relaxed">
                                    {{ $siswa->alamat }}<br>
                                    Kel. {{ $siswa->kelurahan }}, Kec. {{ $siswa->kecamatan }}<br>
                                    {{ $siswa->kota }}, {{ $siswa->provinsi }} {{ $siswa->kode_pos }}
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-8">
                    <!-- Data Orang Tua -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2 border-b border-gray-100 pb-2">
                            <i data-lucide="users" class="w-5 h-5 text-primary-600"></i> Data Orang Tua
                        </h3>
                        
                        <div class="bg-gray-50 rounded-xl p-4 mb-4">
                            <div class="text-xs text-gray-500 uppercase tracking-wider mb-1">No. Kartu Keluarga (KK)</div>
                            <div class="font-medium text-gray-900 font-mono">{{ $siswa->no_kk }}</div>
                        </div>

                        <div class="grid grid-cols-2 gap-6 text-sm">
                            <!-- Ayah -->
                            <div class="space-y-2">
                                <h4 class="font-semibold text-primary-800">Ayah</h4>
                                <div>
                                    <p class="text-gray-500 text-xs">Nama Lengkap</p>
                                    <p class="font-medium text-gray-900">{{ $siswa->nama_ayah }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-500 text-xs">Pendidikan</p>
                                    <p class="font-medium text-gray-900">{{ $siswa->pendidikan_ayah }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-500 text-xs">Pekerjaan</p>
                                    <p class="font-medium text-gray-900">{{ $siswa->pekerjaan_ayah }}</p>
                                </div>
                            </div>
                            <!-- Ibu -->
                            <div class="space-y-2">
                                <h4 class="font-semibold text-primary-800">Ibu</h4>
                                <div>
                                    <p class="text-gray-500 text-xs">Nama Lengkap</p>
                                    <p class="font-medium text-gray-900">{{ $siswa->nama_ibu }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-500 text-xs">Pendidikan</p>
                                    <p class="font-medium text-gray-900">{{ $siswa->pendidikan_ibu }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-500 text-xs">Pekerjaan</p>
                                    <p class="font-medium text-gray-900">{{ $siswa->pekerjaan_ibu }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Dokumen Terlampir -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2 border-b border-gray-100 pb-2">
                            <i data-lucide="folder" class="w-5 h-5 text-primary-600"></i> Dokumen Terlampir
                        </h3>
                        <div class="flex gap-4">
                            @if($siswa->foto_kk)
                            <a href="{{ route('dokumen.show', ['path' => $siswa->foto_kk]) }}" target="_blank" class="flex-1 flex flex-col items-center justify-center p-4 rounded-xl border border-gray-200 bg-white hover:bg-gray-50 hover:border-primary-300 transition-colors group">
                                <i data-lucide="file-text" class="w-8 h-8 text-gray-400 group-hover:text-primary-600 mb-2 transition-colors"></i>
                                <span class="text-sm font-medium text-gray-700 group-hover:text-primary-700">Kartu Keluarga</span>
                            </a>
                            @endif

                            @if($siswa->foto_akta)
                            <a href="{{ route('dokumen.show', ['path' => $siswa->foto_akta]) }}" target="_blank" class="flex-1 flex flex-col items-center justify-center p-4 rounded-xl border border-gray-200 bg-white hover:bg-gray-50 hover:border-primary-300 transition-colors group">
                                <i data-lucide="file-badge-2" class="w-8 h-8 text-gray-400 group-hover:text-primary-600 mb-2 transition-colors"></i>
                                <span class="text-sm font-medium text-gray-700 group-hover:text-primary-700">Akta Kelahiran</span>
                            </a>
                            @endif
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>
@endsection
