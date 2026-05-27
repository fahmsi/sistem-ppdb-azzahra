@extends('layouts.app')

@section('title', 'Lengkapi Data Anak')
@section('header_title', 'Formulir Data Anak')

@section('content')
<div class="max-w-5xl mx-auto mb-10">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        
        <!-- Header Form yang Dirapikan -->
        <div class="bg-primary-800 px-6 py-8 sm:px-10 relative overflow-hidden">
            <!-- Aksen Dekoratif (Opsional, agar tidak terlalu polos) -->
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-32 h-32 bg-primary-700 rounded-full opacity-50 blur-2xl"></div>
            
            <div class="relative z-10">
                <h2 class="text-2xl font-heading font-bold mb-2 flex items-center gap-3 text-white">
                    <div class="p-2 bg-primary-700/50 rounded-lg backdrop-blur-sm">
                        <i data-lucide="file-text" class="w-6 h-6 text-primary-100"></i>
                    </div>
                    Lengkapi Biodata Anak
                </h2>
                <p class="text-primary-100 text-sm mt-2 ml-1">Harap isi data dengan sebenar-benarnya sesuai dengan dokumen resmi (Kartu Keluarga & Akta Kelahiran).</p>
            </div>
        </div>

        <!-- Form dengan jarak (padding top) yang lebih lega -->
        <form action="{{ route('parent.siswa.store') }}" method="POST" enctype="multipart/form-data" class="p-6 sm:p-10 pt-8">
            @csrf

            <!-- Section 1: Data Pribadi Anak -->
            <div class="mb-12">
                <h3 class="text-lg font-semibold text-gray-800 border-b border-gray-200 pb-3 mb-6 flex items-center gap-2">
                    <i data-lucide="user" class="w-5 h-5 text-primary-600"></i> 1. Data Pribadi Anak
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nama Lengkap -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" name="nama" value="{{ old('nama') }}" class="w-full rounded-lg focus:ring-primary-500 focus:border-primary-500 @error('nama') border-red-500 @else border-gray-300 @enderror">
                        @error('nama') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <!-- NISN & NIS -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">NISN (Jika Ada)</label>
                        <input type="text" name="nisn" value="{{ old('nisn') }}" class="w-full rounded-lg border-gray-300 focus:ring-primary-500 focus:border-primary-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">NIS (Jika Ada)</label>
                        <input type="text" name="nis" value="{{ old('nis') }}" class="w-full rounded-lg border-gray-300 focus:ring-primary-500 focus:border-primary-500">
                    </div>

                    <!-- Tempat & Tanggal Lahir -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tempat Lahir <span class="text-red-500">*</span></label>
                        <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir') }}" class="w-full rounded-lg focus:ring-primary-500 focus:border-primary-500 @error('tempat_lahir') border-red-500 @else border-gray-300 @enderror">
                        @error('tempat_lahir') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lahir <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" class="w-full rounded-lg focus:ring-primary-500 focus:border-primary-500 @error('tanggal_lahir') border-red-500 @else border-gray-300 @enderror">
                        @error('tanggal_lahir') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <!-- Jenis Kelamin & Agama -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin <span class="text-red-500">*</span></label>
                        <select name="jenis_kelamin" class="w-full rounded-lg focus:ring-primary-500 focus:border-primary-500 @error('jenis_kelamin') border-red-500 @else border-gray-300 @enderror">
                            <option value="">-- Pilih --</option>
                            <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        @error('jenis_kelamin') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Agama <span class="text-red-500">*</span></label>
                        <select name="agama" class="w-full rounded-lg focus:ring-primary-500 focus:border-primary-500 @error('agama') border-red-500 @else border-gray-300 @enderror">
                            <option value="Islam" {{ old('agama', 'Islam') == 'Islam' ? 'selected' : '' }}>Islam</option>
                        </select>
                        @error('agama') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <!-- Anak Ke & Saudara -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Anak Ke <span class="text-red-500">*</span></label>
                        <input type="number" name="anak_ke" value="{{ old('anak_ke') }}" min="1" class="w-full rounded-lg focus:ring-primary-500 focus:border-primary-500 @error('anak_ke') border-red-500 @else border-gray-300 @enderror">
                        @error('anak_ke') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Saudara <span class="text-red-500">*</span></label>
                        <input type="number" name="jumlah_saudara" value="{{ old('jumlah_saudara') }}" min="0" class="w-full rounded-lg focus:ring-primary-500 focus:border-primary-500 @error('jumlah_saudara') border-red-500 @else border-gray-300 @enderror">
                        @error('jumlah_saudara') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <!-- Hobi & Cita -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Hobi</label>
                        <input type="text" name="hobi" value="{{ old('hobi') }}" class="w-full rounded-lg border-gray-300 focus:ring-primary-500 focus:border-primary-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Cita-cita</label>
                        <input type="text" name="cita_cita" value="{{ old('cita_cita') }}" class="w-full rounded-lg border-gray-300 focus:ring-primary-500 focus:border-primary-500">
                    </div>
                </div>
            </div>

            <!-- Section 2: Alamat & Kontak -->
            <div class="mb-12">
                <h3 class="text-lg font-semibold text-gray-800 border-b border-gray-200 pb-3 mb-6 flex items-center gap-2">
                    <i data-lucide="map-pin" class="w-5 h-5 text-primary-600"></i> 2. Alamat & Kontak Tempat Tinggal
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Provinsi (Dependent Dropdown Start) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Provinsi <span class="text-red-500">*</span></label>
                        <select id="provinsi" class="w-full rounded-lg focus:ring-primary-500 focus:border-primary-500 @error('provinsi') border-red-500 @else border-gray-300 @enderror">
                            <option value="">-- Pilih Provinsi --</option>
                        </select>
                        <input type="hidden" name="provinsi" id="provinsi_name" value="{{ old('provinsi') }}">
                        @error('provinsi') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kota / Kabupaten <span class="text-red-500">*</span></label>
                        <select id="kota" class="w-full rounded-lg focus:ring-primary-500 focus:border-primary-500 @error('kota') border-red-500 @else border-gray-300 @enderror" disabled>
                            <option value="">-- Pilih Kota --</option>
                        </select>
                        <input type="hidden" name="kota" id="kota_name" value="{{ old('kota') }}">
                        @error('kota') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kecamatan <span class="text-red-500">*</span></label>
                        <select id="kecamatan" class="w-full rounded-lg focus:ring-primary-500 focus:border-primary-500 @error('kecamatan') border-red-500 @else border-gray-300 @enderror" disabled>
                            <option value="">-- Pilih Kecamatan --</option>
                        </select>
                        <input type="hidden" name="kecamatan" id="kecamatan_name" value="{{ old('kecamatan') }}">
                        @error('kecamatan') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kelurahan / Desa <span class="text-red-500">*</span></label>
                        <select id="kelurahan" class="w-full rounded-lg focus:ring-primary-500 focus:border-primary-500 @error('kelurahan') border-red-500 @else border-gray-300 @enderror" disabled>
                            <option value="">-- Pilih Kelurahan --</option>
                        </select>
                        <input type="hidden" name="kelurahan" id="kelurahan_name" value="{{ old('kelurahan') }}">
                        @error('kelurahan') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Jalan / RT / RW <span class="text-red-500">*</span></label>
                        <input type="text" name="alamat" value="{{ old('alamat') }}" placeholder="Jl. Contoh No.1, RT 01/RW 02" class="w-full rounded-lg focus:ring-primary-500 focus:border-primary-500 @error('alamat') border-red-500 @else border-gray-300 @enderror">
                        @error('alamat') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kode Pos</label>
                        <input type="text" name="kode_pos" value="{{ old('kode_pos') }}" class="w-full rounded-lg border-gray-300 focus:ring-primary-500 focus:border-primary-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Tempat Tinggal <span class="text-red-500">*</span></label>
                        <select name="jenis_tempat_tinggal" class="w-full rounded-lg focus:ring-primary-500 focus:border-primary-500 @error('jenis_tempat_tinggal') border-red-500 @else border-gray-300 @enderror">
                            <option value="">-- Pilih --</option>
                            <option value="Bersama Orang Tua" {{ old('jenis_tempat_tinggal') == 'Bersama Orang Tua' ? 'selected' : '' }}>Bersama Orang Tua</option>
                            <option value="Asrama" {{ old('jenis_tempat_tinggal') == 'Asrama' ? 'selected' : '' }}>Asrama</option>
                            <option value="Lainnya" {{ old('jenis_tempat_tinggal') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                        @error('jenis_tempat_tinggal') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">No. Telepon / WhatsApp <span class="text-red-500">*</span></label>
                        <input type="text" name="no_telpon" id="no_telpon" value="{{ old('no_telpon', $userPhone ?? '') }}" class="w-full rounded-lg focus:ring-primary-500 focus:border-primary-500 @error('no_telpon') border-red-500 @else border-gray-300 @enderror">
                        @error('no_telpon') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Moda Transportasi</label>
                        <input type="text" name="transportasi" value="{{ old('transportasi') }}" placeholder="Contoh: Jalan Kaki, Diantar" class="w-full rounded-lg border-gray-300 focus:ring-primary-500 focus:border-primary-500">
                    </div>
                </div>
            </div>

            <!-- Section 3: Data Orang Tua -->
            <div class="mb-12">
                <h3 class="text-lg font-semibold text-gray-800 border-b border-gray-200 pb-3 mb-6 flex items-center gap-2">
                    <i data-lucide="users" class="w-5 h-5 text-primary-600"></i> 3. Data Orang Tua
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                    
                    <!-- KK Info -->
                    <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6 bg-gray-50 p-5 rounded-xl border border-gray-200 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Kartu Keluarga (KK) <span class="text-red-500">*</span></label>
                            <input type="text" name="no_kk" id="no_kk" value="{{ old('no_kk') }}" maxlength="16" class="w-full rounded-lg focus:ring-primary-500 focus:border-primary-500 @error('no_kk') border-red-500 @else border-gray-300 @enderror">
                            <p id="no_kk_feedback" class="mt-1 text-xs font-medium hidden"></p>
                            @error('no_kk') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Kepala Keluarga <span class="text-red-500">*</span></label>
                            <input type="text" name="kepala_keluarga" value="{{ old('kepala_keluarga') }}" class="w-full rounded-lg focus:ring-primary-500 focus:border-primary-500 @error('kepala_keluarga') border-red-500 @else border-gray-300 @enderror">
                            @error('kepala_keluarga') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- Ayah -->
                    <div class="space-y-5 bg-white p-5 rounded-xl border border-gray-100 shadow-sm">
                        <h4 class="font-medium text-primary-800 flex items-center gap-2 pb-2 border-b border-gray-100"><i data-lucide="user" class="w-4 h-4 text-primary-500"></i> Data Ayah</h4>
                        
                        <div>
                            <label class="block text-sm text-gray-700 mb-1">Nama Ayah <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_ayah" value="{{ old('nama_ayah') }}" class="w-full rounded-lg focus:ring-primary-500 focus:border-primary-500 @error('nama_ayah') border-red-500 @else border-gray-300 @enderror">
                            @error('nama_ayah') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm text-gray-700 mb-1">NIK Ayah <span class="text-red-500">*</span></label>
                            <input type="text" name="nik_ayah" id="nik_ayah" value="{{ old('nik_ayah') }}" maxlength="16" class="w-full rounded-lg focus:ring-primary-500 focus:border-primary-500 @error('nik_ayah') border-red-500 @else border-gray-300 @enderror">
                            <p id="nik_ayah_feedback" class="mt-1 text-xs font-medium hidden"></p>
                            @error('nik_ayah') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm text-gray-700 mb-1">Tanggal Lahir Ayah <span class="text-red-500">*</span></label>
                            <input type="date" name="tanggal_lahir_ayah" value="{{ old('tanggal_lahir_ayah') }}" class="w-full rounded-lg focus:ring-primary-500 focus:border-primary-500 @error('tanggal_lahir_ayah') border-red-500 @else border-gray-300 @enderror">
                            @error('tanggal_lahir_ayah') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm text-gray-700 mb-1">Pendidikan Ayah <span class="text-red-500">*</span></label>
                            <select name="pendidikan_ayah" class="w-full rounded-lg focus:ring-primary-500 focus:border-primary-500 @error('pendidikan_ayah') border-red-500 @else border-gray-300 @enderror">
                                <option value="">-- Pilih --</option>
                                <option value="SD" {{ old('pendidikan_ayah') == 'SD' ? 'selected' : '' }}>SD</option>
                                <option value="SMP" {{ old('pendidikan_ayah') == 'SMP' ? 'selected' : '' }}>SMP</option>
                                <option value="SMA/SMK" {{ old('pendidikan_ayah') == 'SMA/SMK' ? 'selected' : '' }}>SMA/SMK</option>
                                <option value="D3/S1/S2" {{ old('pendidikan_ayah') == 'D3/S1/S2' ? 'selected' : '' }}>D3/S1/S2</option>
                            </select>
                            @error('pendidikan_ayah') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm text-gray-700 mb-1">Pekerjaan Ayah <span class="text-red-500">*</span></label>
                            <input type="text" name="pekerjaan_ayah" value="{{ old('pekerjaan_ayah') }}" class="w-full rounded-lg focus:ring-primary-500 focus:border-primary-500 @error('pekerjaan_ayah') border-red-500 @else border-gray-300 @enderror">
                            @error('pekerjaan_ayah') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm text-gray-700 mb-1">Penghasilan Ayah <span class="text-red-500">*</span></label>
                            <select name="penghasilan_ayah" class="w-full rounded-lg focus:ring-primary-500 focus:border-primary-500 @error('penghasilan_ayah') border-red-500 @else border-gray-300 @enderror">
                                <option value="">-- Pilih --</option>
                                <option value="< 1 Juta" {{ old('penghasilan_ayah') == '< 1 Juta' ? 'selected' : '' }}>Kurang dari 1 Juta</option>
                                <option value="1-3 Juta" {{ old('penghasilan_ayah') == '1-3 Juta' ? 'selected' : '' }}>1 Juta - 3 Juta</option>
                                <option value="> 3 Juta" {{ old('penghasilan_ayah') == '> 3 Juta' ? 'selected' : '' }}>Lebih dari 3 Juta</option>
                            </select>
                            @error('penghasilan_ayah') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- Ibu -->
                    <div class="space-y-5 bg-white p-5 rounded-xl border border-gray-100 shadow-sm">
                        <h4 class="font-medium text-primary-800 flex items-center gap-2 pb-2 border-b border-gray-100"><i data-lucide="user" class="w-4 h-4 text-primary-500"></i> Data Ibu</h4>
                        
                        <div>
                            <label class="block text-sm text-gray-700 mb-1">Nama Ibu <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_ibu" value="{{ old('nama_ibu') }}" class="w-full rounded-lg focus:ring-primary-500 focus:border-primary-500 @error('nama_ibu') border-red-500 @else border-gray-300 @enderror">
                            @error('nama_ibu') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm text-gray-700 mb-1">NIK Ibu <span class="text-red-500">*</span></label>
                            <input type="text" name="nik_ibu" id="nik_ibu" value="{{ old('nik_ibu') }}" maxlength="16" class="w-full rounded-lg focus:ring-primary-500 focus:border-primary-500 @error('nik_ibu') border-red-500 @else border-gray-300 @enderror">
                            <p id="nik_ibu_feedback" class="mt-1 text-xs font-medium hidden"></p>
                            @error('nik_ibu') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm text-gray-700 mb-1">Tanggal Lahir Ibu <span class="text-red-500">*</span></label>
                            <input type="date" name="tanggal_lahir_ibu" value="{{ old('tanggal_lahir_ibu') }}" class="w-full rounded-lg focus:ring-primary-500 focus:border-primary-500 @error('tanggal_lahir_ibu') border-red-500 @else border-gray-300 @enderror">
                            @error('tanggal_lahir_ibu') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm text-gray-700 mb-1">Pendidikan Ibu <span class="text-red-500">*</span></label>
                            <select name="pendidikan_ibu" class="w-full rounded-lg focus:ring-primary-500 focus:border-primary-500 @error('pendidikan_ibu') border-red-500 @else border-gray-300 @enderror">
                                <option value="">-- Pilih --</option>
                                <option value="SD" {{ old('pendidikan_ibu') == 'SD' ? 'selected' : '' }}>SD</option>
                                <option value="SMP" {{ old('pendidikan_ibu') == 'SMP' ? 'selected' : '' }}>SMP</option>
                                <option value="SMA/SMK" {{ old('pendidikan_ibu') == 'SMA/SMK' ? 'selected' : '' }}>SMA/SMK</option>
                                <option value="D3/S1/S2" {{ old('pendidikan_ibu') == 'D3/S1/S2' ? 'selected' : '' }}>D3/S1/S2</option>
                            </select>
                            @error('pendidikan_ibu') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm text-gray-700 mb-1">Pekerjaan Ibu <span class="text-red-500">*</span></label>
                            <input type="text" name="pekerjaan_ibu" value="{{ old('pekerjaan_ibu') }}" class="w-full rounded-lg focus:ring-primary-500 focus:border-primary-500 @error('pekerjaan_ibu') border-red-500 @else border-gray-300 @enderror">
                            @error('pekerjaan_ibu') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm text-gray-700 mb-1">Penghasilan Ibu <span class="text-red-500">*</span></label>
                            <select name="penghasilan_ibu" class="w-full rounded-lg focus:ring-primary-500 focus:border-primary-500 @error('penghasilan_ibu') border-red-500 @else border-gray-300 @enderror">
                                <option value="">-- Pilih --</option>
                                <option value="< 1 Juta" {{ old('penghasilan_ibu') == '< 1 Juta' ? 'selected' : '' }}>Kurang dari 1 Juta</option>
                                <option value="1-3 Juta" {{ old('penghasilan_ibu') == '1-3 Juta' ? 'selected' : '' }}>1 Juta - 3 Juta</option>
                                <option value="> 3 Juta" {{ old('penghasilan_ibu') == '> 3 Juta' ? 'selected' : '' }}>Lebih dari 3 Juta</option>
                            </select>
                            @error('penghasilan_ibu') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 4: Dokumen Pendukung -->
            <div class="mb-10">
                <h3 class="text-lg font-semibold text-gray-800 border-b border-gray-200 pb-3 mb-6 flex items-center gap-2">
                    <i data-lucide="folder-open" class="w-5 h-5 text-primary-600"></i> 4. Upload Dokumen Pendukung
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Foto Anak -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pas Foto Anak (Warna) <span class="text-red-500">*</span></label>
                        <div class="relative border-2 border-dashed border-gray-300 rounded-xl bg-gray-50 hover:bg-gray-100 hover:border-primary-400 transition-colors cursor-pointer group p-6 text-center">
                            <input type="file" name="foto" accept="image/jpeg,image/png,image/jpg" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" onchange="previewFile(this, 'preview-foto')">
                            <i data-lucide="image" class="w-10 h-10 text-gray-400 mx-auto mb-3 group-hover:text-primary-500 transition-colors"></i>
                            <p class="text-sm font-medium text-primary-600">Klik atau Drop file</p>
                            <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG (Max 2MB)</p>
                            <p id="preview-foto" class="text-xs font-semibold text-secondary-600 mt-3 truncate"></p>
                        </div>
                        @error('foto') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <!-- KK -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Scan Kartu Keluarga <span class="text-red-500">*</span></label>
                        <div class="relative border-2 border-dashed border-gray-300 rounded-xl bg-gray-50 hover:bg-gray-100 hover:border-primary-400 transition-colors cursor-pointer group p-6 text-center">
                            <input type="file" name="foto_kk" accept="image/jpeg,image/png,image/jpg" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" onchange="previewFile(this, 'preview-kk')">
                            <i data-lucide="file-text" class="w-10 h-10 text-gray-400 mx-auto mb-3 group-hover:text-primary-500 transition-colors"></i>
                            <p class="text-sm font-medium text-primary-600">Klik atau Drop file</p>
                            <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG (Max 2MB)</p>
                            <p id="preview-kk" class="text-xs font-semibold text-secondary-600 mt-3 truncate"></p>
                        </div>
                        @error('foto_kk') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <!-- Akta -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Scan Akta Kelahiran <span class="text-red-500">*</span></label>
                        <div class="relative border-2 border-dashed border-gray-300 rounded-xl bg-gray-50 hover:bg-gray-100 hover:border-primary-400 transition-colors cursor-pointer group p-6 text-center">
                            <input type="file" name="foto_akta" accept="image/jpeg,image/png,image/jpg" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" onchange="previewFile(this, 'preview-akta')">
                            <i data-lucide="file-badge-2" class="w-10 h-10 text-gray-400 mx-auto mb-3 group-hover:text-primary-500 transition-colors"></i>
                            <p class="text-sm font-medium text-primary-600">Klik atau Drop file</p>
                            <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG (Max 2MB)</p>
                            <p id="preview-akta" class="text-xs font-semibold text-secondary-600 mt-3 truncate"></p>
                        </div>
                        @error('foto_akta') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Submit -->
            <div class="pt-6 mt-4 border-t border-gray-200 flex items-center justify-end gap-4 bg-gray-50 -mx-6 -mb-6 sm:-mx-10 sm:-mb-10 p-6 sm:p-8 rounded-b-2xl">
                <a href="{{ route('parent.dashboard') }}" class="px-5 py-2.5 text-sm font-medium text-gray-700 hover:text-gray-900 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 transition-colors">Batal</a>
                <button type="submit" class="px-6 py-2.5 flex items-center gap-2 text-sm font-medium text-white bg-primary-600 border border-transparent rounded-lg shadow-sm hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                    <i data-lucide="save" class="w-4 h-4"></i> Simpan Data Anak
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function previewFile(input, targetId) {
        const previewElement = document.getElementById(targetId);
        if (input.files && input.files[0]) {
            previewElement.textContent = "File: " + input.files[0].name;
            input.parentElement.classList.add('border-primary-500', 'bg-primary-50');
            input.parentElement.classList.remove('border-gray-300', 'bg-gray-50');
        } else {
            previewElement.textContent = "";
            input.parentElement.classList.remove('border-primary-500', 'bg-primary-50');
            input.parentElement.classList.add('border-gray-300', 'bg-gray-50');
        }
    }

    // =============================================
    // Real-time NIK/KK 16-digit Validation
    // =============================================
    function validateDigitField(inputId, feedbackId) {
        var input = document.getElementById(inputId);
        var feedback = document.getElementById(feedbackId);
        if (!input || !feedback) return;

        input.addEventListener('input', function() {
            var val = this.value.replace(/\D/g, '');
            this.value = val; // only digits
            var len = val.length;

            if (len === 0) {
                feedback.classList.add('hidden');
                input.classList.remove('border-red-500', 'border-green-500');
            } else if (len === 16) {
                feedback.textContent = '✓ 16 digit — valid';
                feedback.className = 'mt-1 text-xs font-medium text-green-600';
                input.classList.remove('border-red-500');
                input.classList.add('border-green-500');
            } else {
                feedback.textContent = '✗ ' + len + '/16 digit — harus tepat 16 digit';
                feedback.className = 'mt-1 text-xs font-medium text-red-500';
                input.classList.remove('border-green-500');
                input.classList.add('border-red-500');
            }
        });

        // Trigger on load if old value exists
        if (input.value.length > 0) {
            input.dispatchEvent(new Event('input'));
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        validateDigitField('no_kk', 'no_kk_feedback');
        validateDigitField('nik_ayah', 'nik_ayah_feedback');
        validateDigitField('nik_ibu', 'nik_ibu_feedback');

        // =============================================
        // Dependent Dropdown — Fetch from emsifa API
        // API: https://www.emsifa.com/api-wilayah-indonesia/
        // =============================================
        var API_BASE = 'https://www.emsifa.com/api-wilayah-indonesia/api';

        var provSelect = document.getElementById('provinsi');
        var kotaSelect = document.getElementById('kota');
        var kecSelect = document.getElementById('kecamatan');
        var kelSelect = document.getElementById('kelurahan');

        var provHidden = document.getElementById('provinsi_name');
        var kotaHidden = document.getElementById('kota_name');
        var kecHidden = document.getElementById('kecamatan_name');
        var kelHidden = document.getElementById('kelurahan_name');

        function fetchOptions(url, selectEl, hiddenEl, nextSelects, callback) {
            selectEl.innerHTML = '<option value="">Memuat...</option>';
            selectEl.disabled = true;

            fetch(url)
                .then(function(r) { return r.json(); })
                .then(function(data) {
                    selectEl.innerHTML = '<option value="">-- Pilih --</option>';
                    data.forEach(function(item) {
                        var opt = document.createElement('option');
                        opt.value = item.id;
                        opt.textContent = item.name;
                        selectEl.appendChild(opt);
                    });
                    selectEl.disabled = false;

                    // Restore old value if exists
                    if (hiddenEl && hiddenEl.value) {
                        for (var i = 0; i < selectEl.options.length; i++) {
                            if (selectEl.options[i].textContent === hiddenEl.value) {
                                selectEl.selectedIndex = i;
                                selectEl.dispatchEvent(new Event('change'));
                                break;
                            }
                        }
                    }

                    if (callback) callback(data);
                })
                .catch(function() {
                    selectEl.innerHTML = '<option value="">Gagal memuat data</option>';
                    selectEl.disabled = false;
                });

            // Reset next selects
            nextSelects.forEach(function(s) {
                s.innerHTML = '<option value="">-- Pilih --</option>';
                s.disabled = true;
            });
        }

        // Load Provinsi
        fetchOptions(API_BASE + '/provinces.json', provSelect, provHidden, [kotaSelect, kecSelect, kelSelect]);

        provSelect.addEventListener('change', function() {
            var selected = provSelect.options[provSelect.selectedIndex];
            provHidden.value = selected.textContent !== '-- Pilih --' ? selected.textContent : '';
            if (this.value) {
                fetchOptions(API_BASE + '/regencies/' + this.value + '.json', kotaSelect, kotaHidden, [kecSelect, kelSelect]);
            }
        });

        kotaSelect.addEventListener('change', function() {
            var selected = kotaSelect.options[kotaSelect.selectedIndex];
            kotaHidden.value = selected.textContent !== '-- Pilih --' ? selected.textContent : '';
            if (this.value) {
                fetchOptions(API_BASE + '/districts/' + this.value + '.json', kecSelect, kecHidden, [kelSelect]);
            }
        });

        kecSelect.addEventListener('change', function() {
            var selected = kecSelect.options[kecSelect.selectedIndex];
            kecHidden.value = selected.textContent !== '-- Pilih --' ? selected.textContent : '';
            if (this.value) {
                fetchOptions(API_BASE + '/villages/' + this.value + '.json', kelSelect, kelHidden, []);
            }
        });

        kelSelect.addEventListener('change', function() {
            var selected = kelSelect.options[kelSelect.selectedIndex];
            kelHidden.value = selected.textContent !== '-- Pilih --' ? selected.textContent : '';
        });
    });
</script>
@endsection