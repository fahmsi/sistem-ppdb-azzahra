@extends('layouts.app')
@section('title', 'Tambah Admin Baru')
@section('header_title', 'Tambah Admin Baru')
@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center gap-3 mb-2">
        <a href="{{ route('admin.kelola-admin.index') }}" class="text-[#697a8d] dark:text-[#a1b0cb] hover:text-[#696cff] transition-colors"><i data-lucide="arrow-left" class="w-5 h-5"></i></a>
        <div>
            <h2 class="text-2xl font-heading font-bold text-[#566a7f] dark:text-[#d5d5e2]">Tambah Admin</h2>
            <p class="text-[#a1b0cb] text-sm mt-0.5">Buat akun admin baru untuk mengelola sistem PPDB.</p>
        </div>
    </div>
    <div class="bg-white dark:bg-[#2b2c40] rounded-lg shadow-sneat dark:shadow-sneat-dark border border-[#d9dee3] dark:border-[#434463] p-6 sm:p-8">
        <form method="POST" action="{{ route('admin.kelola-admin.store') }}" class="space-y-5">
            @csrf
            <div>
                <label for="name" class="block text-sm font-semibold text-[#566a7f] dark:text-[#d5d5e2] mb-1.5">Nama Lengkap</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required class="sneat-input @error('name') !border-red-500 @enderror" placeholder="Nama lengkap admin">
                @error('name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="email" class="block text-sm font-semibold text-[#566a7f] dark:text-[#d5d5e2] mb-1.5">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required class="sneat-input @error('email') !border-red-500 @enderror" placeholder="admin@contoh.com">
                @error('email') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="role" class="block text-sm font-semibold text-[#566a7f] dark:text-[#d5d5e2] mb-1.5">Role</label>
                <select id="role" name="role" required class="sneat-input @error('role') !border-red-500 @enderror">
                    <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="super_admin" {{ old('role') === 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                </select>
                @error('role') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="password" class="block text-sm font-semibold text-[#566a7f] dark:text-[#d5d5e2] mb-1.5">Password</label>
                <input type="password" id="password" name="password" required class="sneat-input @error('password') !border-red-500 @enderror" placeholder="Minimal 8 karakter">
                @error('password') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="password_confirmation" class="block text-sm font-semibold text-[#566a7f] dark:text-[#d5d5e2] mb-1.5">Konfirmasi Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required class="sneat-input" placeholder="Ulangi password">
            </div>
            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="sneat-btn-primary"><i data-lucide="save" class="w-4 h-4"></i> Simpan Admin</button>
                <a href="{{ route('admin.kelola-admin.index') }}" class="px-4 py-2.5 text-[#697a8d] dark:text-[#a1b0cb] hover:text-[#566a7f] text-sm font-medium transition-colors">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
