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
        <a href="{{ route('admin.siswa.index') }}" class="inline-flex items-center gap-2 text-sm font-medium text-gray-500 transition-colors hover:text-primary-600 dark:text-gray-400 dark:hover:text-primary-400">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali ke Data Siswa
        </a>
        <div class="flex items-center gap-2">
            @if($siswa->no_telpon)
            <a href="https://wa.me/62{{ ltrim(preg_replace('/^0/', '', $siswa->no_telpon), '+') }}" target="_blank" class="inline-flex items-center gap-2 rounded-lg border border-green-200 bg-green-50 px-4 py-2 text-sm font-medium text-green-700 transition-colors hover:bg-green-100 dark:border-green-500/25 dark:bg-green-500/10 dark:text-green-300 dark:hover:bg-green-500/20">
                <i data-lucide="message-circle" class="w-4 h-4"></i> Chat WA
            </a>
            @endif
            <button onclick="window.print()" class="inline-flex items-center gap-2 rounded-lg border border-gray-200 bg-gray-100 px-4 py-2 text-sm font-medium text-gray-700 transition-colors hover:bg-gray-200 dark:border-[#434463] dark:bg-[#2b2c40] dark:text-gray-200 dark:hover:bg-[#434463]">
                <i data-lucide="printer" class="w-4 h-4"></i> Cetak PDF
            </button>
            <form action="{{ route('admin.siswa.destroy', $siswa->id) }}" method="POST" class="admin-soft-delete-form" data-student-name="{{ $siswa->nama }}" data-has-history="{{ $siswa->pendaftaranDetails->isNotEmpty() ? '1' : '0' }}">
                @csrf
                @method('DELETE')
                <input type="hidden" name="deleted_reason" value="">
                <button type="submit" class="inline-flex items-center gap-2 rounded-lg border border-red-200 bg-red-50 px-4 py-2 text-sm font-medium text-red-700 transition-colors hover:bg-red-100 dark:border-red-500/25 dark:bg-red-500/10 dark:text-red-300 dark:hover:bg-red-500/20">
                    <i data-lucide="trash-2" class="w-4 h-4"></i> Hapus Sementara
                </button>
            </form>
        </div>
    </div>

    <!-- Main Profile Card -->
    <div id="printableArea" class="admin-student-detail-card animate-fade-up overflow-hidden rounded-2xl border border-[#d9dee3] dark:border-[#434463] bg-white dark:bg-[#2b2c40] shadow-sneat dark:shadow-sneat-dark">

        <!-- Header / Cover -->
        <div class="h-32 bg-gradient-to-r from-primary-600 to-primary-800 relative">
            <div class="absolute inset-0 bg-white/10 pattern-dots opacity-30"></div>
        </div>

        <div class="px-6 sm:px-10 pb-10">
            <!-- Avatar & Quick Info -->
            <div class="flex flex-col sm:flex-row gap-6 items-start -mt-16 mb-8 relative z-10">
                <img src="{{ $siswa->foto ? Storage::url($siswa->foto) : asset('images/default-avatar.png') }}" alt="Foto Siswa" class="w-32 h-32 rounded-2xl object-cover border-4 border-white dark:border-[#2b2c40] shadow-md bg-gray-100 dark:bg-[#232333]">
                <div class="pt-16 sm:pt-18 flex-1 w-full">
                    <h2 class="text-3xl font-heading font-bold text-gray-900 dark:text-[#e7e7ff]">{{ $siswa->nama }}</h2>
                    <div class="flex flex-wrap gap-4 mt-2 text-sm text-gray-600 dark:text-gray-400">
                        @if($siswa->nama_panggilan)
                            <span class="flex items-center gap-1.5"><i data-lucide="user" class="w-4 h-4 text-[#696cff]"></i> Panggilan: {{ $siswa->nama_panggilan }}</span>
                        @endif
                        <span class="flex items-center gap-1.5"><i data-lucide="info" class="w-4 h-4 text-[#696cff]"></i> {{ $siswa->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                        <span class="flex items-center gap-1.5"><i data-lucide="map-pin" class="w-4 h-4 text-[#696cff]"></i> {{ $siswa->kota ?: 'Kota Belum Diisi' }}</span>
                        @if($siswa->input_source === \App\Models\Siswa::INPUT_SOURCE_MANUAL_ADMIN)
                            <span class="inline-flex items-center gap-1.5 rounded-md bg-amber-100 dark:bg-amber-500/20 px-2 py-1 text-xs font-medium text-amber-700 dark:text-amber-400">
                                <i data-lucide="clipboard-edit" class="w-3.5 h-3.5"></i> Manual Admin
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 rounded-md bg-blue-100 dark:bg-blue-500/20 px-2 py-1 text-xs font-medium text-blue-700 dark:text-blue-400">
                                <i data-lucide="globe" class="w-3.5 h-3.5"></i> Online
                            </span>
                        @endif
                    </div>
                    @if($siswa->input_source === \App\Models\Siswa::INPUT_SOURCE_MANUAL_ADMIN && $siswa->createdByAdmin)
                        <p class="mt-3 text-xs text-gray-500 dark:text-gray-400">
                            Dibuat manual oleh <span class="font-medium text-gray-700 dark:text-gray-300">{{ $siswa->createdByAdmin->name }}</span>.
                        </p>
                    @endif
                </div>
            </div>

            <!-- Detail Sections Layout -->
            <div class="space-y-8">

                <!-- Row 1: Data Anak & Alamat -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Section 1: Data Pribadi Anak -->
                    <div class="bg-gray-50/50 dark:bg-[#232333]/50 rounded-xl p-6 border border-[#d9dee3] dark:border-[#434463]">
                        <h3 class="text-base font-semibold text-gray-900 dark:text-[#e7e7ff] mb-4 flex items-center gap-2 border-b border-[#d9dee3] dark:border-[#434463] pb-2">
                            <i data-lucide="user" class="w-4 h-4 text-[#696cff]"></i> Data Pribadi Anak
                        </h3>
                        <dl class="space-y-3 text-sm">
                            <div class="flex justify-between">
                                <dt class="text-gray-500 dark:text-gray-400">Nama Lengkap</dt>
                                <dd class="font-semibold text-gray-900 dark:text-[#e7e7ff] text-right">{{ $siswa->nama ?: '-' }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-gray-500 dark:text-gray-400">Nama Panggilan</dt>
                                <dd class="font-medium text-gray-900 dark:text-[#e7e7ff] text-right">{{ $siswa->nama_panggilan ?: '-' }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-gray-500 dark:text-gray-400">Tempat, Tanggal Lahir</dt>
                                <dd class="font-medium text-gray-900 dark:text-[#e7e7ff] text-right">
                                    {{ $siswa->tempat_lahir ?: '-' }},
                                    {{ $siswa->tanggal_lahir ? \Carbon\Carbon::parse($siswa->tanggal_lahir)->translatedFormat('d F Y') : '-' }}
                                </dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-gray-500 dark:text-gray-400">Jenis Kelamin</dt>
                                <dd class="font-medium text-gray-900 dark:text-[#e7e7ff] text-right">{{ $siswa->jenis_kelamin == 'L' ? 'Laki-laki' : ($siswa->jenis_kelamin == 'P' ? 'Perempuan' : '-') }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-gray-500 dark:text-gray-400">Agama</dt>
                                <dd class="font-medium text-gray-900 dark:text-[#e7e7ff] text-right">{{ $siswa->agama ?: '-' }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-gray-500 dark:text-gray-400">Anak Ke</dt>
                                <dd class="font-medium text-gray-900 dark:text-[#e7e7ff] text-right">{{ $siswa->anak_ke ?: '-' }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-gray-500 dark:text-gray-400">Jumlah Saudara</dt>
                                <dd class="font-medium text-gray-900 dark:text-[#e7e7ff] text-right">{{ $siswa->jumlah_saudara !== null ? $siswa->jumlah_saudara : '-' }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-gray-500 dark:text-gray-400">Hobi</dt>
                                <dd class="font-medium text-gray-900 dark:text-[#e7e7ff] text-right">{{ $siswa->hobi ?: '-' }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-gray-500 dark:text-gray-400">Cita-cita</dt>
                                <dd class="font-medium text-gray-900 dark:text-[#e7e7ff] text-right">{{ $siswa->cita_cita ?: '-' }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-gray-500 dark:text-gray-400">Moda Transportasi</dt>
                                <dd class="font-medium text-gray-900 dark:text-[#e7e7ff] text-right">{{ $siswa->transportasi ?: '-' }}</dd>
                            </div>
                        </dl>
                    </div>

                    <!-- Section 2: Alamat & Kontak -->
                    <div class="bg-gray-50/50 dark:bg-[#232333]/50 rounded-xl p-6 border border-[#d9dee3] dark:border-[#434463]">
                        <h3 class="text-base font-semibold text-gray-900 dark:text-[#e7e7ff] mb-4 flex items-center gap-2 border-b border-[#d9dee3] dark:border-[#434463] pb-2">
                            <i data-lucide="map-pin" class="w-4 h-4 text-[#696cff]"></i> Alamat & Tempat Tinggal
                        </h3>
                        <dl class="space-y-3 text-sm">
                            <div class="flex justify-between">
                                <dt class="text-gray-500 dark:text-gray-400">No. Telepon / WA</dt>
                                <dd class="font-semibold text-gray-900 dark:text-[#e7e7ff] text-right">{{ $siswa->no_telpon ?: '-' }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-gray-500 dark:text-gray-400">Jenis Tempat Tinggal</dt>
                                <dd class="font-medium text-gray-900 dark:text-[#e7e7ff] text-right">{{ $siswa->jenis_tempat_tinggal ?: '-' }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-gray-500 dark:text-gray-400">Provinsi</dt>
                                <dd class="font-medium text-gray-900 dark:text-[#e7e7ff] text-right">{{ $siswa->provinsi ?: '-' }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-gray-500 dark:text-gray-400">Kota / Kabupaten</dt>
                                <dd class="font-medium text-gray-900 dark:text-[#e7e7ff] text-right">{{ $siswa->kota ?: '-' }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-gray-500 dark:text-gray-400">Kecamatan</dt>
                                <dd class="font-medium text-gray-900 dark:text-[#e7e7ff] text-right">{{ $siswa->kecamatan ?: '-' }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-gray-500 dark:text-gray-400">Kelurahan / Desa</dt>
                                <dd class="font-medium text-gray-900 dark:text-[#e7e7ff] text-right">{{ $siswa->kelurahan ?: '-' }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-gray-500 dark:text-gray-400">Kode Pos</dt>
                                <dd class="font-medium text-gray-900 dark:text-[#e7e7ff] text-right">{{ $siswa->kode_pos ?: '-' }}</dd>
                            </div>
                            <div class="flex flex-col pt-2 border-t border-[#d9dee3] dark:border-[#434463]">
                                <dt class="text-gray-500 dark:text-gray-400 mb-1">Alamat Jalan / RT / RW</dt>
                                <dd class="font-medium text-gray-900 dark:text-[#e7e7ff] leading-relaxed">
                                    {{ $siswa->alamat ?: '-' }}
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Section 3: Data Orang Tua / Wali -->
                <div class="bg-gray-50/50 dark:bg-[#232333]/50 rounded-xl p-6 border border-[#d9dee3] dark:border-[#434463]">
                    <h3 class="text-base font-semibold text-gray-900 dark:text-[#e7e7ff] mb-4 flex items-center gap-2 border-b border-[#d9dee3] dark:border-[#434463] pb-2">
                        <i data-lucide="users" class="w-4 h-4 text-[#696cff]"></i> Data Keluarga & Orang Tua / Wali
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                        <div class="bg-white dark:bg-[#2b2c40] rounded-xl p-4 border border-[#d9dee3] dark:border-[#434463]">
                            <div class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Nomor Kartu Keluarga (KK)</div>
                            <div class="font-semibold text-gray-950 dark:text-[#e7e7ff] font-mono text-sm">{{ $siswa->no_kk ?: '-' }}</div>
                        </div>
                        <div class="bg-white dark:bg-[#2b2c40] rounded-xl p-4 border border-[#d9dee3] dark:border-[#434463]">
                            <div class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Nama Kepala Keluarga</div>
                            <div class="font-semibold text-gray-950 dark:text-[#e7e7ff] text-sm">{{ $siswa->kepala_keluarga ?: '-' }}</div>
                        </div>
                        <div class="bg-white dark:bg-[#2b2c40] rounded-xl p-4 border border-[#d9dee3] dark:border-[#434463]">
                            <div class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Tinggal Bersama</div>
                            <span class="inline-block mt-1 px-2.5 py-0.5 text-xs font-semibold rounded bg-primary-50 text-primary-700 dark:bg-primary-500/10 dark:text-primary-400 border border-primary-200">
                                {{ $siswa->tinggal_bersama === 'wali' ? 'Wali' : 'Orang Tua' }}
                            </span>
                        </div>
                        <div class="bg-white dark:bg-[#2b2c40] rounded-xl p-4 border border-[#d9dee3] dark:border-[#434463]">
                            <div class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Akun Wali (Pendaftar)</div>
                            <div class="font-semibold text-gray-950 dark:text-[#e7e7ff] text-sm">
                                @if($siswa->user)
                                    {{ $siswa->user->name }}
                                @else
                                    <span class="text-gray-400 italic">Manual Admin</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if($siswa->tinggal_bersama === 'wali')
                        <div class="bg-white dark:bg-[#2b2c40] rounded-xl p-5 border border-[#d9dee3] dark:border-[#434463] space-y-3 max-w-xl text-sm">
                            <h4 class="font-bold text-[#696cff] flex items-center gap-1.5 border-b border-gray-100 dark:border-[#434463] pb-2">
                                <i data-lucide="user-check" class="w-4 h-4"></i> Data Wali Anak
                            </h4>
                            <div class="flex justify-between">
                                <span class="text-gray-500 dark:text-gray-400">Nama Wali</span>
                                <span class="font-semibold text-gray-900 dark:text-[#e7e7ff]">{{ $siswa->nama_wali ?: '-' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500 dark:text-gray-400">NIK Wali</span>
                                <span class="font-medium text-gray-900 dark:text-[#e7e7ff] font-mono">{{ $siswa->nik_wali ?: '-' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500 dark:text-gray-400">Hubungan Wali</span>
                                <span class="font-medium text-gray-900 dark:text-[#e7e7ff]">{{ $siswa->hubungan_wali ?: '-' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500 dark:text-gray-400">No. Telepon Wali</span>
                                <span class="font-medium text-gray-900 dark:text-[#e7e7ff]">{{ $siswa->no_telpon_wali ?: '-' }}</span>
                            </div>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 text-sm">
                            <!-- Ayah -->
                            <div class="bg-white dark:bg-[#2b2c40] rounded-xl p-5 border border-[#d9dee3] dark:border-[#434463] space-y-3">
                                <h4 class="font-bold text-[#696cff] flex items-center gap-1.5 border-b border-gray-100 dark:border-[#434463] pb-2">
                                    <i data-lucide="user" class="w-4 h-4"></i> Data Ayah Kandung
                                </h4>
                                <div class="flex justify-between">
                                    <span class="text-gray-500 dark:text-gray-400">Nama Lengkap</span>
                                    <span class="font-semibold text-gray-900 dark:text-[#e7e7ff]">{{ $siswa->nama_ayah ?: '-' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500 dark:text-gray-400">NIK Ayah</span>
                                    <span class="font-medium text-gray-900 dark:text-[#e7e7ff] font-mono">{{ $siswa->nik_ayah ?: '-' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500 dark:text-gray-400">Tanggal Lahir Ayah</span>
                                    <span class="font-medium text-gray-900 dark:text-[#e7e7ff]">{{ $siswa->tanggal_lahir_ayah ? \Carbon\Carbon::parse($siswa->tanggal_lahir_ayah)->translatedFormat('d F Y') : '-' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500 dark:text-gray-400">Pendidikan Terakhir</span>
                                    <span class="font-medium text-gray-900 dark:text-[#e7e7ff]">{{ $siswa->pendidikan_ayah ?: '-' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500 dark:text-gray-400">Pekerjaan</span>
                                    <span class="font-medium text-gray-900 dark:text-[#e7e7ff]">{{ $siswa->pekerjaan_ayah ?: '-' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500 dark:text-gray-400">Estimasi Penghasilan</span>
                                    <span class="font-medium text-gray-900 dark:text-[#e7e7ff] text-right">{{ $siswa->penghasilan_ayah ?: '-' }}</span>
                                </div>
                            </div>

                            <!-- Ibu -->
                            <div class="bg-white dark:bg-[#2b2c40] rounded-xl p-5 border border-[#d9dee3] dark:border-[#434463] space-y-3">
                                <h4 class="font-bold text-[#696cff] flex items-center gap-1.5 border-b border-gray-100 dark:border-[#434463] pb-2">
                                    <i data-lucide="user" class="w-4 h-4"></i> Data Ibu Kandung
                                </h4>
                                <div class="flex justify-between">
                                    <span class="text-gray-500 dark:text-gray-400">Nama Lengkap</span>
                                    <span class="font-semibold text-gray-900 dark:text-[#e7e7ff]">{{ $siswa->nama_ibu ?: '-' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500 dark:text-gray-400">NIK Ibu</span>
                                    <span class="font-medium text-gray-900 dark:text-[#e7e7ff] font-mono">{{ $siswa->nik_ibu ?: '-' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500 dark:text-gray-400">Tanggal Lahir Ibu</span>
                                    <span class="font-medium text-gray-900 dark:text-[#e7e7ff]">{{ $siswa->tanggal_lahir_ibu ? \Carbon\Carbon::parse($siswa->tanggal_lahir_ibu)->translatedFormat('d F Y') : '-' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500 dark:text-gray-400">Pendidikan Terakhir</span>
                                    <span class="font-medium text-gray-900 dark:text-[#e7e7ff]">{{ $siswa->pendidikan_ibu ?: '-' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500 dark:text-gray-400">Pekerjaan</span>
                                    <span class="font-medium text-gray-900 dark:text-[#e7e7ff]">{{ $siswa->pekerjaan_ibu ?: '-' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500 dark:text-gray-400">Estimasi Penghasilan</span>
                                    <span class="font-medium text-gray-900 dark:text-[#e7e7ff] text-right">{{ $siswa->penghasilan_ibu ?: '-' }}</span>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Section 4: Dokumen Pendukung -->
                <div class="bg-gray-50/50 dark:bg-[#232333]/50 rounded-xl p-6 border border-[#d9dee3] dark:border-[#434463]">
                    <h3 class="text-base font-semibold text-gray-900 dark:text-[#e7e7ff] mb-4 flex items-center gap-2 border-b border-[#d9dee3] dark:border-[#434463] pb-2">
                        <i data-lucide="folder-open" class="w-4 h-4 text-[#696cff]"></i> Berkas & Dokumen Pendukung
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                        <!-- Pas Foto -->
                        <div class="flex flex-col items-center justify-center p-5 rounded-xl border bg-white dark:bg-[#2b2c40] {{ $siswa->foto ? 'border-emerald-200 dark:border-emerald-500/20' : 'border-[#d9dee3] dark:border-[#434463]' }}">
                            <div class="p-3 rounded-full bg-emerald-50 dark:bg-emerald-500/10 mb-3">
                                <i data-lucide="image" class="w-6 h-6 text-emerald-600 dark:text-emerald-400"></i>
                            </div>
                            <span class="text-sm font-semibold text-gray-700 dark:text-[#e7e7ff] mb-1">Pas Foto Anak</span>
                            @if($siswa->foto)
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400 uppercase">Tersedia</span>
                                <a href="{{ Storage::url($siswa->foto) }}" target="_blank" class="mt-3 text-xs font-bold text-[#696cff] hover:underline flex items-center gap-1">
                                    <i data-lucide="eye" class="w-3.5 h-3.5"></i> Lihat / Unduh
                                </a>
                            @else
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-red-100 text-red-700 dark:bg-red-500/20 dark:text-red-400 uppercase">Tidak Ada</span>
                                <span class="mt-3 text-xs text-gray-400">Belum diupload</span>
                            @endif
                        </div>

                        <!-- Kartu Keluarga -->
                        <div class="flex flex-col items-center justify-center p-5 rounded-xl border bg-white dark:bg-[#2b2c40] {{ $siswa->foto_kk ? 'border-emerald-200 dark:border-emerald-500/20' : 'border-[#d9dee3] dark:border-[#434463]' }}">
                            <div class="p-3 rounded-full bg-emerald-50 dark:bg-emerald-500/10 mb-3">
                                <i data-lucide="file-text" class="w-6 h-6 text-emerald-600 dark:text-emerald-400"></i>
                            </div>
                            <span class="text-sm font-semibold text-gray-700 dark:text-[#e7e7ff] mb-1">Kartu Keluarga (KK)</span>
                            @if($siswa->foto_kk)
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400 uppercase">Tersedia</span>
                                <a href="{{ route('dokumen.show', ['siswa' => $siswa, 'field' => 'foto_kk']) }}" target="_blank" class="mt-3 text-xs font-bold text-[#696cff] hover:underline flex items-center gap-1">
                                    <i data-lucide="eye" class="w-3.5 h-3.5"></i> Lihat / Unduh
                                </a>
                            @else
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-red-100 text-red-700 dark:bg-red-500/20 dark:text-red-400 uppercase">Tidak Ada</span>
                                <span class="mt-3 text-xs text-gray-400">Belum diupload</span>
                            @endif
                        </div>

                        <!-- Akta Kelahiran -->
                        <div class="flex flex-col items-center justify-center p-5 rounded-xl border bg-white dark:bg-[#2b2c40] {{ $siswa->foto_akta ? 'border-emerald-200 dark:border-emerald-500/20' : 'border-[#d9dee3] dark:border-[#434463]' }}">
                            <div class="p-3 rounded-full bg-emerald-50 dark:bg-emerald-500/10 mb-3">
                                <i data-lucide="file-badge" class="w-6 h-6 text-emerald-600 dark:text-emerald-400"></i>
                            </div>
                            <span class="text-sm font-semibold text-gray-700 dark:text-[#e7e7ff] mb-1">Akta Kelahiran</span>
                            @if($siswa->foto_akta)
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400 uppercase">Tersedia</span>
                                <a href="{{ route('dokumen.show', ['siswa' => $siswa, 'field' => 'foto_akta']) }}" target="_blank" class="mt-3 text-xs font-bold text-[#696cff] hover:underline flex items-center gap-1">
                                    <i data-lucide="eye" class="w-3.5 h-3.5"></i> Lihat / Unduh
                                </a>
                            @else
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-red-100 text-red-700 dark:bg-red-500/20 dark:text-red-400 uppercase">Tidak Ada</span>
                                <span class="mt-3 text-xs text-gray-400">Belum diupload</span>
                            @endif
                        </div>

                        <!-- Conditional KTP files -->
                        @if($siswa->tinggal_bersama === 'wali')
                            <div class="flex flex-col items-center justify-center p-5 rounded-xl border bg-white dark:bg-[#2b2c40] {{ $siswa->foto_ktp_wali ? 'border-emerald-200 dark:border-emerald-500/20' : 'border-[#d9dee3] dark:border-[#434463]' }}">
                                <div class="p-3 rounded-full bg-emerald-50 dark:bg-emerald-500/10 mb-3">
                                    <i data-lucide="file-digit" class="w-6 h-6 text-emerald-600 dark:text-emerald-400"></i>
                                </div>
                                <span class="text-sm font-semibold text-gray-700 dark:text-[#e7e7ff] mb-1">KTP Wali</span>
                                @if($siswa->foto_ktp_wali)
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400 uppercase">Tersedia</span>
                                    <a href="{{ route('dokumen.show', ['siswa' => $siswa, 'field' => 'foto_ktp_wali']) }}" target="_blank" class="mt-3 text-xs font-bold text-[#696cff] hover:underline flex items-center gap-1">
                                        <i data-lucide="eye" class="w-3.5 h-3.5"></i> Lihat / Unduh
                                    </a>
                                @else
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-red-100 text-red-700 dark:bg-red-500/20 dark:text-red-400 uppercase">Tidak Ada</span>
                                    <span class="mt-3 text-xs text-gray-400">Belum diupload</span>
                                @endif
                            </div>
                        @else
                            <div class="flex flex-col items-center justify-center p-5 rounded-xl border bg-white dark:bg-[#2b2c40] {{ $siswa->foto_ktp_ayah && $siswa->foto_ktp_ibu ? 'border-emerald-200 dark:border-emerald-500/20' : 'border-[#d9dee3] dark:border-[#434463]' }} space-y-1">
                                <div class="p-2 rounded-full bg-emerald-50 dark:bg-emerald-500/10 mb-1">
                                    <i data-lucide="file-digit" class="w-5 h-5 text-emerald-600 dark:text-emerald-400"></i>
                                </div>
                                <span class="text-xs font-semibold text-gray-700 dark:text-[#e7e7ff] text-center">KTP Ayah & Ibu</span>
                                <div class="flex flex-col gap-1 items-center">
                                    @if($siswa->foto_ktp_ayah)
                                        <a href="{{ route('dokumen.show', ['siswa' => $siswa, 'field' => 'foto_ktp_ayah']) }}" target="_blank" class="text-[11px] font-bold text-[#696cff] hover:underline flex items-center gap-0.5">
                                            <i data-lucide="eye" class="w-3 h-3"></i> KTP Ayah
                                        </a>
                                    @else
                                        <span class="text-[10px] text-red-500 font-semibold">KTP Ayah Kosong</span>
                                    @endif

                                    @if($siswa->foto_ktp_ibu)
                                        <a href="{{ route('dokumen.show', ['siswa' => $siswa, 'field' => 'foto_ktp_ibu']) }}" target="_blank" class="text-[11px] font-bold text-[#696cff] hover:underline flex items-center gap-0.5">
                                            <i data-lucide="eye" class="w-3 h-3"></i> KTP Ibu
                                        </a>
                                    @else
                                        <span class="text-[10px] text-red-500 font-semibold">KTP Ibu Kosong</span>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Section 5: Status Pendaftaran & Pembayaran -->
                <div class="bg-gray-50/50 dark:bg-[#232333]/50 rounded-xl p-6 border border-[#d9dee3] dark:border-[#434463]">
                    <h3 class="text-base font-semibold text-gray-900 dark:text-[#e7e7ff] mb-4 flex items-center gap-2 border-b border-[#d9dee3] dark:border-[#434463] pb-2">
                        <i data-lucide="clipboard-list" class="w-4 h-4 text-[#696cff]"></i> Status Pendaftaran & Pembayaran
                    </h3>

                    @if($siswa->pendaftaranDetails->isEmpty())
                        <div class="text-center py-6 text-gray-500 dark:text-gray-400">
                            <i data-lucide="help-circle" class="w-8 h-8 text-gray-300 mx-auto mb-2"></i>
                            <p class="text-sm">Belum terdaftar di gelombang penerimaan manapun.</p>
                        </div>
                    @else
                        <div class="space-y-6">
                            @foreach($siswa->pendaftaranDetails as $detail)
                                <div class="bg-white dark:bg-[#2b2c40] rounded-xl border border-[#d9dee3] dark:border-[#434463] overflow-hidden">
                                    <!-- Header Wave -->
                                    <div class="px-5 py-3.5 bg-slate-50 dark:bg-[#232333]/80 border-b border-[#d9dee3] dark:border-[#434463] flex flex-wrap items-center justify-between gap-3">
                                        <div class="flex items-center gap-2">
                                            <i data-lucide="bookmark" class="w-4 h-4 text-[#696cff]"></i>
                                            <span class="text-sm font-semibold text-gray-800 dark:text-[#e7e7ff]">
                                                {{ $detail->pendaftaran->gelombang ?? 'Gelombang Tidak Diketahui' }} - TA {{ $detail->pendaftaran->tahun_ajaran ?? '-' }}
                                            </span>
                                        </div>
                                        <span class="text-xs font-mono font-bold bg-[#f5f5f9] dark:bg-[#232333] border border-[#d9dee3] dark:border-[#434463] px-2.5 py-1 rounded text-gray-700 dark:text-[#a1b0cb]">
                                            No: {{ $detail->nomor_pendaftaran }}
                                        </span>
                                    </div>

                                    <div class="p-5 grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
                                        <!-- Left Side: Registration Details -->
                                        <div class="space-y-3.5">
                                            <h5 class="font-semibold text-gray-800 dark:text-[#d5d5e2] uppercase tracking-wide text-xs">Detail Pendaftaran</h5>
                                            <div class="flex justify-between">
                                                <span class="text-gray-500 dark:text-gray-400">Tanggal Daftar</span>
                                                <span class="font-medium text-gray-900 dark:text-[#e7e7ff]">{{ $detail->created_at?->translatedFormat('d F Y H:i') ?: '-' }}</span>
                                            </div>
                                            <div class="flex justify-between items-center">
                                                <span class="text-gray-500 dark:text-gray-400">Status Verifikasi</span>
                                                @if($detail->status === 'pending')
                                                    <span class="px-2.5 py-0.5 rounded bg-gray-100 text-gray-700 border border-gray-200 text-xs font-bold uppercase">Pendaftaran Tercatat</span>
                                                @elseif($detail->status === 'menunggu_verifikasi')
                                                    <span class="px-2.5 py-0.5 rounded bg-amber-50 text-amber-700 border border-amber-200 text-xs font-bold uppercase dark:bg-amber-500/10 dark:text-amber-400 dark:border-amber-500/20">Menunggu Verifikasi Administrasi</span>
                                                @elseif($detail->status === 'diterima')
                                                    <span class="px-2.5 py-0.5 rounded bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-bold uppercase dark:bg-emerald-500/10 dark:text-emerald-400 dark:border-emerald-500/20">Administrasi Lengkap (Data Legacy)</span>
                                                @elseif($detail->status === 'ditolak')
                                                    <span class="px-2.5 py-0.5 rounded bg-red-50 text-red-700 border border-red-200 text-xs font-bold uppercase dark:bg-red-500/10 dark:text-red-400 dark:border-red-500/20">Pendaftaran Tidak Dilanjutkan (Data Legacy)</span>
                                                @elseif($detail->status === 'perlu_revisi')
                                                    <span class="px-2.5 py-0.5 rounded bg-orange-50 text-orange-700 border border-orange-200 text-xs font-bold uppercase dark:bg-orange-500/10 dark:text-orange-400 dark:border-orange-500/20">Perlu Revisi Data</span>
                                                @endif
                                            </div>
                                            @if($detail->notifikasi)
                                                <div class="bg-gray-50 dark:bg-[#232333]/40 p-3 rounded-lg border border-gray-100 dark:border-[#434463] text-xs">
                                                    <p class="font-bold text-gray-700 dark:text-[#a1b0cb] mb-1">Catatan Verifikasi:</p>
                                                    <p class="text-gray-600 dark:text-gray-300 leading-normal">{{ $detail->notifikasi }}</p>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Right Side: Payment Details -->
                                        <div class="border-t md:border-t-0 md:border-l border-[#d9dee3] dark:border-[#434463] pt-5 md:pt-0 md:pl-6 space-y-3.5">
                                            <h5 class="font-semibold text-gray-800 dark:text-[#d5d5e2] uppercase tracking-wide text-xs">Detail Pembayaran</h5>
                                            @if($detail->pembayaran)
                                                @php $payment = $detail->pembayaran; @endphp
                                                <div class="flex justify-between">
                                                    <span class="text-gray-500 dark:text-gray-400">Nominal Transfer</span>
                                                    <span class="font-bold text-gray-900 dark:text-[#e7e7ff]">Rp {{ number_format($payment->jumlah, 0, ',', '.') }}</span>
                                                </div>
                                                <div class="flex justify-between items-center">
                                                    <span class="text-gray-500 dark:text-gray-400">Status Bayar</span>
                                                    @if($payment->status === 'lunas')
                                                        <span class="px-2.5 py-0.5 rounded bg-emerald-100 text-emerald-800 text-xs font-bold uppercase dark:bg-emerald-500/20 dark:text-emerald-400">DAFTAR ULANG SELESAI / SISWA DITERIMA</span>
                                                    @elseif($payment->status === 'menunggu_verifikasi')
                                                        <span class="px-2.5 py-0.5 rounded bg-yellow-100 text-yellow-800 text-xs font-bold uppercase dark:bg-yellow-500/20 dark:text-yellow-400">MENUNGGU VERIFIKASI DAFTAR ULANG</span>
                                                    @elseif($payment->status === 'ditolak')
                                                        <span class="px-2.5 py-0.5 rounded bg-red-100 text-red-800 text-xs font-bold uppercase dark:bg-red-500/20 dark:text-red-400">DITOLAK</span>
                                                    @else
                                                        <span class="px-2.5 py-0.5 rounded bg-gray-100 text-gray-700 text-xs font-bold uppercase">{{ strtoupper($payment->status) }}</span>
                                                    @endif
                                                </div>
                                                <div class="flex justify-between">
                                                    <span class="text-gray-500 dark:text-gray-400">Tanggal Bayar</span>
                                                    <span class="font-medium text-gray-900 dark:text-[#e7e7ff]">{{ $payment->created_at?->translatedFormat('d F Y H:i') ?: '-' }}</span>
                                                </div>
                                                @if($payment->bukti_bayar)
                                                    <div class="pt-1.5">
                                                        <a href="{{ route('dokumen.show', ['siswa' => $siswa, 'field' => 'bukti_bayar', 'pembayaran' => $payment]) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-[#f5f5f9] dark:bg-[#232333] hover:bg-[#e7e7ff] dark:hover:bg-[#696cff]/10 text-xs font-semibold text-[#696cff] dark:text-[#e7e7ff] rounded border border-[#d9dee3] dark:border-[#434463] transition-colors duration-200">
                                                            <i data-lucide="file-search" class="w-3.5 h-3.5"></i> Lihat Bukti Bayar
                                                        </a>
                                                    </div>
                                                @endif
                                                @if($payment->catatan_admin)
                                                    <div class="bg-red-50/50 dark:bg-red-500/5 p-3 rounded-lg border border-red-100 dark:border-red-500/10 text-xs">
                                                        <p class="font-bold text-red-800 dark:text-red-400 mb-1">Catatan Admin:</p>
                                                        <p class="text-red-700 dark:text-red-300 leading-normal">{{ $payment->catatan_admin }}</p>
                                                    </div>
                                                @endif
                                            @else
                                                <div class="text-center py-4 text-gray-500 dark:text-gray-400 italic">
                                                    Belum mengupload bukti pembayaran daftar ulang.
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

            </div>

        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    function escapeHtml(value) {
        return String(value || '-')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    document.querySelectorAll('.admin-soft-delete-form').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            var name = form.dataset.studentName || 'data siswa ini';
            var hasHistory = form.dataset.hasHistory === '1';
            var historyWarning = hasHistory
                ? '<div class="mt-3 rounded-md bg-orange-50 p-3 text-left text-sm text-orange-700">Data siswa ini memiliki riwayat pendaftaran. Data akan disembunyikan, bukan dihapus permanen.</div>'
                : '';

            Swal.fire({
                title: 'Hapus sementara data siswa?',
                html: '<p class="text-sm text-gray-600">Masukkan alasan penghapusan untuk <strong>' + escapeHtml(name) + '</strong>.</p>' + historyWarning,
                input: 'textarea',
                inputPlaceholder: 'Tulis alasan penghapusan...',
                inputAttributes: {
                    'aria-label': 'Alasan penghapusan'
                },
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#697a8d',
                confirmButtonText: 'Hapus Sementara',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                preConfirm: function(value) {
                    var reason = (value || '').trim();
                    if (!reason) {
                        Swal.showValidationMessage('Alasan penghapusan wajib diisi.');
                        return false;
                    }

                    return reason;
                }
            }).then(function(result) {
                if (result.isConfirmed) {
                    form.querySelector('input[name="deleted_reason"]').value = result.value;
                    HTMLFormElement.prototype.submit.call(form);
                }
            });
        });
    });
});
</script>
@endsection
