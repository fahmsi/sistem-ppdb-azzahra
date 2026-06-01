@extends('layouts.app')
@section('title', 'Buat Gelombang Pendaftaran')
@section('header_title', 'Tambah Gelombang Baru')
@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white dark:bg-[#2b2c40] rounded-lg shadow-sneat dark:shadow-sneat-dark border border-[#d9dee3] dark:border-[#434463] overflow-hidden">
        <div class="bg-[#e7e7ff] dark:bg-[#696cff]/20 border-b border-[#d9dee3] dark:border-[#434463] px-6 py-6 flex items-center justify-between">
            <div>
                <h2 class="text-xl font-heading font-bold text-[#566a7f] dark:text-[#d5d5e2] flex items-center gap-2">
                    <i data-lucide="plus-circle" class="w-5 h-5 text-[#696cff]"></i> Form Tambah Gelombang
                </h2>
                <p class="text-[#696cff] dark:text-[#a1b0cb] text-sm mt-1">Isi detail periode pendaftaran baru.</p>
            </div>
            <a href="{{ route('admin.pendaftaran.index') }}" class="text-sm font-medium text-[#697a8d] dark:text-[#a1b0cb] hover:text-[#696cff] transition-colors flex items-center gap-1"><i data-lucide="x" class="w-4 h-4"></i> Batal</a>
        </div>
        <form action="{{ route('admin.pendaftaran.store') }}" method="POST" enctype="multipart/form-data" class="p-6 sm:p-8 space-y-6">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-[#566a7f] dark:text-[#d5d5e2] mb-1">Tahun Ajaran <span class="text-red-500">*</span></label>
                    <input type="text" name="tahun_ajaran" value="{{ old('tahun_ajaran', date('Y').'/'.(date('Y')+1)) }}" placeholder="Contoh: 2026/2027" class="sneat-input @error('tahun_ajaran') !border-red-500 @enderror">
                    @error('tahun_ajaran') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-[#566a7f] dark:text-[#d5d5e2] mb-1">Nama Gelombang <span class="text-red-500">*</span></label>
                    <input type="text" name="gelombang" value="{{ old('gelombang') }}" placeholder="Contoh: Gelombang 1" class="sneat-input @error('gelombang') !border-red-500 @enderror">
                    @error('gelombang') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-[#566a7f] dark:text-[#d5d5e2] mb-1">Tanggal Mulai <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}" class="sneat-input @error('tanggal_mulai') !border-red-500 @enderror">
                    @error('tanggal_mulai') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-[#566a7f] dark:text-[#d5d5e2] mb-1">Tanggal Selesai <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai') }}" class="sneat-input @error('tanggal_selesai') !border-red-500 @enderror">
                    @error('tanggal_selesai') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-[#566a7f] dark:text-[#d5d5e2] mb-1">Kuota Penerimaan <span class="text-red-500">*</span></label>
                    <input type="number" name="kuota" value="{{ old('kuota', 30) }}" min="1" class="sneat-input @error('kuota') !border-red-500 @enderror">
                    @error('kuota') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-[#566a7f] dark:text-[#d5d5e2] mb-1">Status Awal <span class="text-red-500">*</span></label>
                    <select name="status" class="sneat-input @error('status') !border-red-500 @enderror">
                        <option value="buka" {{ old('status') == 'buka' ? 'selected' : '' }}>BUKA</option>
                        <option value="tutup" {{ old('status') == 'tutup' ? 'selected' : '' }}>TUTUP</option>
                    </select>
                    @error('status') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-[#566a7f] dark:text-[#d5d5e2] mb-2">Banner Gambar (Opsional)</label>
                <div class="relative border-2 border-dashed border-[#d9dee3] dark:border-[#434463] rounded-lg bg-[#f5f5f9] dark:bg-[#232333] hover:bg-[#e7e7ff]/30 dark:hover:bg-[#696cff]/10 hover:border-[#696cff] transition-colors cursor-pointer group p-6 text-center">
                    <input type="file" name="gambar" accept="image/jpeg,image/png,image/jpg" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" onchange="previewFile(this, 'preview-gambar')">
                    <i data-lucide="image" class="w-10 h-10 text-[#a1b0cb] mx-auto mb-3 group-hover:text-[#696cff] transition-colors"></i>
                    <p class="text-sm font-medium text-[#696cff]">Pilih File Gambar</p>
                    <p class="text-xs text-[#a1b0cb] mt-1">Format: JPG, PNG (Max 2MB). Rasio ideal 16:9.</p>
                    <p id="preview-gambar" class="text-xs font-semibold text-emerald-600 mt-3 truncate"></p>
                </div>
                @error('gambar') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>
            <div class="pt-6 border-t border-[#d9dee3] dark:border-[#434463] flex items-center justify-end">
                <button type="submit" class="sneat-btn-primary"><i data-lucide="save" class="w-4 h-4"></i> Simpan Gelombang Baru</button>
            </div>
        </form>
    </div>
</div>
<script>
    function previewFile(input, targetId) {
        const previewElement = document.getElementById(targetId);
        if (input.files && input.files[0]) {
            previewElement.textContent = "File terpilih: " + input.files[0].name;
            input.parentElement.classList.add('border-[#696cff]', 'bg-[#e7e7ff]/30');
            input.parentElement.classList.remove('border-[#d9dee3]', 'bg-[#f5f5f9]');
        } else {
            previewElement.textContent = "";
            input.parentElement.classList.remove('border-[#696cff]', 'bg-[#e7e7ff]/30');
            input.parentElement.classList.add('border-[#d9dee3]', 'bg-[#f5f5f9]');
        }
    }
</script>
@endsection
