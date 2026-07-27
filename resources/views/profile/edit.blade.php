@extends('layouts.app')

@section('title', 'Pengaturan Akun')
@section('header_title', 'Pengaturan Profil & Akun')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <!-- Grid Container -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

        <!-- Left Column: Profile Card Overview (lg:col-span-4) -->
        <div class="lg:col-span-4 space-y-6 animate-fade-up">
            <div class="bg-white dark:bg-[#2b2c40] rounded-xl shadow-sneat dark:shadow-sneat-dark border border-[#d9dee3] dark:border-[#434463] overflow-hidden">
                <!-- Cover Banner Gradient -->
                <div class="h-32 bg-gradient-to-r from-[#696cff] to-[#7b7dff] relative overflow-hidden">
                    <div class="absolute -right-6 -top-10 w-32 h-32 bg-white/10 rounded-full blur-xl"></div>
                    <div class="absolute left-10 -bottom-8 w-24 h-24 bg-white/10 rounded-full blur-lg"></div>
                </div>

                <!-- Avatar and Profile Meta Section -->
                <div class="px-6 pb-6 pt-0 relative flex flex-col items-center">
                    <!-- Overlapping Avatar Upload Container -->
                    <div class="relative -mt-16 mb-4 group">
                        <div class="w-32 h-32 rounded-full p-1 bg-white dark:bg-[#2b2c40] shadow-md">
                            <img id="avatarPreview" src="{{ $user->avatar ? Storage::url($user->avatar) : asset('images/default-avatar.png') }}"
                                 alt="Avatar" class="w-full h-full rounded-full object-cover border border-gray-100 dark:border-[#434463] bg-[#f5f5f9] dark:bg-[#232333]">
                        </div>

                        <!-- Hover trigger for photo upload -->
                        <label for="avatarInput" class="absolute inset-1 w-30 h-30 flex flex-col items-center justify-center bg-black/50 text-white rounded-full opacity-0 group-hover:opacity-100 transition-all duration-300 cursor-pointer text-xs font-semibold">
                            <i data-lucide="camera" class="w-6 h-6 mb-1 text-white"></i>
                            Ganti Foto
                        </label>
                    </div>

                    <!-- User Name & Role -->
                    <h3 class="text-lg font-heading font-bold text-[#566a7f] dark:text-[#d5d5e2] text-center line-clamp-1">
                        {{ $user->name }}
                    </h3>

                    <span class="mt-2 inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold
                        @if($user->isSuperAdmin())
                            bg-indigo-50 text-indigo-600 dark:bg-indigo-500/10 dark:text-indigo-400
                        @elseif($user->isAdmin())
                            bg-purple-50 text-purple-600 dark:bg-purple-500/10 dark:text-purple-400
                        @else
                            bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400
                        @endif">
                        <i data-lucide="{{ $user->isAdmin() ? 'shield-check' : 'user' }}" class="w-3.5 h-3.5"></i>
                        @if($user->isSuperAdmin())
                            Super Admin
                        @elseif($user->isAdmin())
                            Administrator
                        @else
                            Wali Murid
                        @endif
                    </span>

                    <div class="w-full border-t border-[#d9dee3] dark:border-[#434463] my-5"></div>

                    <!-- Metadata Details List -->
                    <div class="w-full space-y-4">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-[#a1b0cb] flex items-center gap-2">
                                <i data-lucide="mail" class="w-4 h-4 text-[#696cff]"></i> Email
                            </span>
                            <span class="text-[#566a7f] dark:text-[#d5d5e2] font-medium truncate max-w-[180px]">{{ $user->email }}</span>
                        </div>

                        <div class="flex items-center justify-between text-sm">
                            <span class="text-[#a1b0cb] flex items-center gap-2">
                                <i data-lucide="phone" class="w-4 h-4 text-[#696cff]"></i> Telepon
                            </span>
                            <span class="text-[#566a7f] dark:text-[#d5d5e2] font-medium">{{ $user->no_telpon ?? '-' }}</span>
                        </div>

                        <div class="flex items-center justify-between text-sm">
                            <span class="text-[#a1b0cb] flex items-center gap-2">
                                <i data-lucide="calendar" class="w-4 h-4 text-[#696cff]"></i> Terdaftar
                            </span>
                            <span class="text-[#566a7f] dark:text-[#d5d5e2] font-medium">{{ $user->created_at ? $user->created_at->translatedFormat('d M Y') : '-' }}</span>
                        </div>

                        @if($user->isParent())
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-[#a1b0cb] flex items-center gap-2">
                                    <i data-lucide="smile" class="w-4 h-4 text-[#696cff]"></i> Anak Terdaftar
                                </span>
                                @if($user->siswa)
                                    <span class="text-emerald-600 dark:text-emerald-400 font-semibold text-xs flex items-center gap-1 bg-emerald-50 dark:bg-emerald-500/10 px-2 py-0.5 rounded">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        {{ Str::limit($user->siswa->nama, 15) }}
                                    </span>
                                @else
                                    <span class="text-amber-600 dark:text-amber-400 font-semibold text-xs flex items-center gap-1 bg-amber-50 dark:bg-amber-500/10 px-2 py-0.5 rounded">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                        Belum diisi
                                    </span>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Settings Sections (lg:col-span-8) -->
        <div class="lg:col-span-8 space-y-6">

            <!-- Card 1: Profile Information -->
            <div class="bg-white dark:bg-[#2b2c40] rounded-xl shadow-sneat dark:shadow-sneat-dark border border-[#d9dee3] dark:border-[#434463] p-6 sm:p-8 animate-fade-up" style="animation-delay: 0.05s;">
                <div class="mb-6 border-b border-[#d9dee3] dark:border-[#434463] pb-4 flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-heading font-bold text-[#566a7f] dark:text-[#d5d5e2] flex items-center gap-2">
                            <i data-lucide="user-cog" class="w-5 h-5 text-[#696cff]"></i> Detail Profil
                        </h2>
                        <p class="text-xs text-[#a1b0cb] mt-1">Perbarui informasi utama akun Anda.</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('profile.update') }}" class="space-y-5" enctype="multipart/form-data">
                    @csrf
                    @method('patch')

                    <!-- Hidden file input triggered by outer label -->
                    <input type="file" id="avatarInput" name="avatar" accept="image/jpeg,image/png,image/jpg" class="hidden" onchange="previewAvatar(this)">

                    @error('avatar')
                        <div class="p-3 bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/20 text-red-500 text-xs rounded-md flex items-center gap-2">
                            <i data-lucide="alert-circle" class="w-4 h-4"></i> {{ $message }}
                        </div>
                    @enderror

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <!-- Nama Lengkap -->
                        <div>
                            <label for="name" class="block text-xs font-semibold text-[#566a7f] dark:text-[#d5d5e2] mb-1.5 uppercase tracking-wider">Nama Lengkap</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i data-lucide="user" class="w-4 h-4 text-[#a1b0cb]"></i>
                                </div>
                                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required autofocus
                                       class="sneat-input !pl-10 w-full">
                            </div>
                            @error('name') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-xs font-semibold text-[#566a7f] dark:text-[#d5d5e2] mb-1.5 uppercase tracking-wider">Email</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i data-lucide="mail" class="w-4 h-4 text-[#a1b0cb]"></i>
                                </div>
                                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                                       class="sneat-input !pl-10 w-full">
                            </div>
                            @error('email') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- No. Telepon -->
                        <div class="md:col-span-2">
                            <label for="no_telpon" class="block text-xs font-semibold text-[#566a7f] dark:text-[#d5d5e2] mb-1.5 uppercase tracking-wider">No. Telepon / WhatsApp</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i data-lucide="phone" class="w-4 h-4 text-[#a1b0cb]"></i>
                                </div>
                                <input type="text" id="no_telpon" name="no_telpon" value="{{ old('no_telpon', $user->no_telpon) }}" placeholder="08xxxxxxxxxx"
                                       class="sneat-input !pl-10 w-full">
                            </div>
                            @error('no_telpon') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="pt-2 flex justify-end">
                        <button type="submit" class="sneat-btn-primary">
                            <i data-lucide="save" class="w-4 h-4"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>

            <!-- Card 2: Update Password -->
            <div class="bg-white dark:bg-[#2b2c40] rounded-xl shadow-sneat dark:shadow-sneat-dark border border-[#d9dee3] dark:border-[#434463] p-6 sm:p-8 animate-fade-up" style="animation-delay: 0.1s;">
                <div class="mb-6 border-b border-[#d9dee3] dark:border-[#434463] pb-4">
                    <h2 class="text-lg font-heading font-bold text-[#566a7f] dark:text-[#d5d5e2] flex items-center gap-2">
                        <i data-lucide="lock" class="w-5 h-5 text-[#696cff]"></i> Keamanan Akun
                    </h2>
                    <p class="text-xs text-[#a1b0cb] mt-1">Ubah kata sandi secara berkala untuk menjaga akun tetap aman.</p>
                </div>

                <form method="POST" action="{{ route('password.update') }}" class="space-y-5">
                    @csrf
                    @method('put')

                    <!-- Current Password -->
                    <div>
                        <div class="flex justify-between items-center mb-1.5">
                            <label for="current_password" class="block text-xs font-semibold text-[#566a7f] dark:text-[#d5d5e2] uppercase tracking-wider">Password Saat Ini</label>
                            @if (Route::has('password.request'))
                                <a href="#" id="forgot-password-alert" class="text-[11px] text-[#696cff] hover:underline font-bold transition-all">
                                    Lupa password?
                                </a>
                            @endif
                        </div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i data-lucide="key-round" class="w-4 h-4 text-[#a1b0cb]"></i>
                            </div>
                            <input type="password" id="current_password" name="current_password" required
                                class="sneat-input w-full !pl-10 !pr-10" placeholder="············">
                            <button type="button" class="toggle-password absolute inset-y-0 right-0 flex items-center pr-3 text-[#a1b0cb] hover:text-[#696cff] focus:outline-none transition-colors" data-target="current_password">
                                <i data-lucide="eye" class="icon-eye w-4 h-4"></i>
                                <i data-lucide="eye-off" class="icon-eye-off w-4 h-4 hidden"></i>
                            </button>
                        </div>
                        @error('current_password') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- New Password -->
                    <div>
                        <label for="password" class="block text-xs font-semibold text-[#566a7f] dark:text-[#d5d5e2] mb-1.5 uppercase tracking-wider">Password Baru</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i data-lucide="key" class="w-4 h-4 text-[#a1b0cb]"></i>
                            </div>
                            <input type="password" id="password" name="password" required class="sneat-input w-full !pl-10 !pr-10" placeholder="············">
                            <button type="button" class="toggle-password absolute inset-y-0 right-0 flex items-center pr-3 text-[#a1b0cb] hover:text-[#696cff] focus:outline-none transition-colors" data-target="password">
                                <i data-lucide="eye" class="icon-eye w-4 h-4"></i>
                                <i data-lucide="eye-off" class="icon-eye-off w-4 h-4 hidden"></i>
                            </button>
                        </div>

                        <!-- Password Strength Grid Badges -->
                        <div id="password-rules" class="hidden mt-3 p-3 bg-[#f5f5f9] dark:bg-[#232333] rounded-lg border border-[#d9dee3] dark:border-[#434463] transition-all duration-300">
                            <p class="text-[10px] uppercase font-bold tracking-wider text-[#a1b0cb] mb-2">Persyaratan Password Baru:</p>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs">
                                <div id="rule-length" class="text-red-500 flex items-center gap-1.5 font-medium transition-colors">
                                    <span class="icon-x-wrapper inline-flex items-center justify-center p-0.5 rounded-full bg-red-100 text-red-600 dark:bg-red-500/10 dark:text-red-400">
                                        <i data-lucide="x" class="icon-x w-3 h-3"></i>
                                    </span>
                                    <span class="icon-check-wrapper hidden inline-flex items-center justify-center p-0.5 rounded-full bg-emerald-100 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400">
                                        <i data-lucide="check" class="icon-check w-3 h-3"></i>
                                    </span>
                                    <span>Minimal 8 karakter</span>
                                </div>
                                <div id="rule-upper" class="text-red-500 flex items-center gap-1.5 font-medium transition-colors">
                                    <span class="icon-x-wrapper inline-flex items-center justify-center p-0.5 rounded-full bg-red-100 text-red-600 dark:bg-red-500/10 dark:text-red-400">
                                        <i data-lucide="x" class="icon-x w-3 h-3"></i>
                                    </span>
                                    <span class="icon-check-wrapper hidden inline-flex items-center justify-center p-0.5 rounded-full bg-emerald-100 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400">
                                        <i data-lucide="check" class="icon-check w-3 h-3"></i>
                                    </span>
                                    <span>Minimal 1 huruf besar</span>
                                </div>
                                <div id="rule-lower" class="text-red-500 flex items-center gap-1.5 font-medium transition-colors">
                                    <span class="icon-x-wrapper inline-flex items-center justify-center p-0.5 rounded-full bg-red-100 text-red-600 dark:bg-red-500/10 dark:text-red-400">
                                        <i data-lucide="x" class="icon-x w-3 h-3"></i>
                                    </span>
                                    <span class="icon-check-wrapper hidden inline-flex items-center justify-center p-0.5 rounded-full bg-emerald-100 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400">
                                        <i data-lucide="check" class="icon-check w-3 h-3"></i>
                                    </span>
                                    <span>Minimal 1 huruf kecil</span>
                                </div>
                                <div id="rule-number" class="text-red-500 flex items-center gap-1.5 font-medium transition-colors">
                                    <span class="icon-x-wrapper inline-flex items-center justify-center p-0.5 rounded-full bg-red-100 text-red-600 dark:bg-red-500/10 dark:text-red-400">
                                        <i data-lucide="x" class="icon-x w-3 h-3"></i>
                                    </span>
                                    <span class="icon-check-wrapper hidden inline-flex items-center justify-center p-0.5 rounded-full bg-emerald-100 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400">
                                        <i data-lucide="check" class="icon-check w-3 h-3"></i>
                                    </span>
                                    <span>Minimal 1 angka</span>
                                </div>
                            </div>
                        </div>
                        @error('password') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-xs font-semibold text-[#566a7f] dark:text-[#d5d5e2] mb-1.5 uppercase tracking-wider">Konfirmasi Password Baru</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i data-lucide="check-check" class="w-4 h-4 text-[#a1b0cb]"></i>
                            </div>
                            <input type="password" id="password_confirmation" name="password_confirmation" required class="sneat-input w-full !pl-10 !pr-10" placeholder="············">
                            <button type="button" class="toggle-password absolute inset-y-0 right-0 flex items-center pr-3 text-[#a1b0cb] hover:text-[#696cff] focus:outline-none transition-colors" data-target="password_confirmation">
                                <i data-lucide="eye" class="icon-eye w-4 h-4"></i>
                                <i data-lucide="eye-off" class="icon-eye-off w-4 h-4 hidden"></i>
                            </button>
                        </div>

                        <div id="match-rule" class="text-xs mt-3 hidden p-2.5 rounded flex items-center gap-2 border">
                            <span class="match-icon-x-wrapper inline-flex items-center justify-center p-0.5 rounded-full bg-red-100 text-red-600 dark:bg-red-500/10 dark:text-red-400">
                                <i data-lucide="x" class="w-3.5 h-3.5"></i>
                            </span>
                            <span class="match-icon-check-wrapper hidden inline-flex items-center justify-center p-0.5 rounded-full bg-emerald-100 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400">
                                <i data-lucide="check" class="w-3.5 h-3.5"></i>
                            </span>
                            <span class="match-text font-medium text-red-500">Password tidak cocok</span>
                        </div>
                        @error('password_confirmation') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="pt-2 flex justify-end">
                        <button type="submit" id="btn-submit" class="sneat-btn-primary">
                            <i data-lucide="refresh-cw" class="w-4 h-4"></i> Perbarui Password
                        </button>
                    </div>
                </form>
            </div>

            <!-- Card 3: Delete Account (if parent) -->
            @if(auth()->user()->role === 'parent')
            <div class="bg-red-50/20 dark:bg-red-500/5 rounded-xl shadow-sneat dark:shadow-sneat-dark border border-red-200 dark:border-red-500/20 p-6 sm:p-8 animate-fade-up" style="animation-delay: 0.15s;">
                <div class="mb-6 border-b border-red-200 dark:border-red-500/20 pb-4">
                    <h2 class="text-lg font-heading font-bold text-red-700 dark:text-red-400 flex items-center gap-2">
                        <i data-lucide="alert-triangle" class="w-5 h-5 text-red-600"></i> Area Sensitif (Hapus Akun)
                    </h2>
                    <p class="text-xs text-red-600 dark:text-red-400/80 mt-1">Tindakan ini permanen. Setelah akun Anda dihapus, semua data dan riwayat pendaftaran anak Anda akan dihapus secara permanen.</p>
                </div>

                <div class="flex items-center justify-between flex-wrap gap-4">
                    <div class="max-w-md">
                        <p class="text-xs text-red-600/80 dark:text-red-400/60 leading-normal">
                            Pastikan Anda telah mempertimbangkan matang-matang sebelum menghapus akun. Segala data pendaftaran tidak akan bisa dipulihkan.
                        </p>
                    </div>
                    <button type="button" id="btnDeleteAccount" class="inline-flex items-center gap-2 px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-md transition-all duration-200 shadow-sm hover:shadow-[0_2px_10px_rgba(220,38,38,0.4)]">
                        <i data-lucide="trash-2" class="w-4 h-4"></i> Hapus Akun Secara Permanen
                    </button>
                </div>

                <form id="deleteAccountForm" method="POST" action="{{ route('profile.destroy') }}" class="hidden">
                    @csrf
                    @method('delete')
                    <input type="password" name="password" id="hidden_delete_password">
                </form>
            </div>
            @endif

            <!-- Card 3: Administrative Info (if admin/super_admin) -->
            @if(auth()->user()->isAdmin())
            <div class="bg-blue-50/20 dark:bg-blue-500/5 rounded-xl shadow-sneat dark:shadow-sneat-dark border border-blue-200 dark:border-blue-500/20 p-6 sm:p-8 animate-fade-up" style="animation-delay: 0.15s;">
                <div class="mb-6 border-b border-blue-200 dark:border-blue-500/20 pb-4">
                    <h2 class="text-lg font-heading font-bold text-blue-700 dark:text-blue-400 flex items-center gap-2">
                        <i data-lucide="shield-check" class="w-5 h-5 text-blue-600 dark:text-blue-400"></i> Hak Akses & Kepatuhan
                    </h2>
                    <p class="text-xs text-blue-600 dark:text-blue-400/80 mt-1">Status dan wewenang administratif akun Anda di platform SPMB Azzahra.</p>
                </div>

                <div class="space-y-4">
                    <div class="flex items-center justify-between p-3.5 bg-blue-50/40 dark:bg-blue-500/10 rounded-lg border border-blue-100/50 dark:border-blue-500/20">
                        <div class="flex items-center gap-3">
                            <span class="relative flex h-3 w-3">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                            </span>
                            <span class="text-xs font-semibold text-blue-800 dark:text-blue-300">Status Hak Akses</span>
                        </div>
                        <span class="text-xs font-bold uppercase px-2.5 py-0.5 rounded bg-emerald-100 dark:bg-emerald-500/20 text-emerald-700 dark:text-emerald-400">
                            Aktif & Terverifikasi
                        </span>
                    </div>

                    <div class="text-xs text-blue-600/80 dark:text-blue-400/80 space-y-2.5">
                        <p class="font-semibold text-blue-700 dark:text-blue-400 uppercase tracking-wider text-[10px]">Cakupan Wewenang Admin:</p>
                        <ul class="space-y-2">
                            <li class="flex items-center gap-2 text-blue-700 dark:text-blue-300">
                                <i data-lucide="check" class="w-3.5 h-3.5 text-emerald-500 flex-shrink-0"></i>
                                <span>Mengelola data pendaftaran siswa dan melakukan verifikasi berkas.</span>
                            </li>
                            <li class="flex items-center gap-2 text-blue-700 dark:text-blue-300">
                                <i data-lucide="check" class="w-3.5 h-3.5 text-emerald-500 flex-shrink-0"></i>
                                <span>Mengatur konfigurasi sistem, data gelombang, dan kelola testimoni/galeri.</span>
                            </li>
                            <li class="flex items-center gap-2 text-blue-700 dark:text-blue-300">
                                <i data-lucide="check" class="w-3.5 h-3.5 text-emerald-500 flex-shrink-0"></i>
                                <span>Memantau rekap pembayaran masuk dari wali murid.</span>
                            </li>
                        </ul>
                    </div>

                    <div class="pt-2 border-t border-blue-100/50 dark:border-blue-500/20">
                        <div class="flex items-start gap-2.5 p-3 bg-amber-500/5 rounded-lg border border-amber-500/10 text-amber-700 dark:text-amber-400">
                            <i data-lucide="info" class="w-4 h-4 mt-0.5 flex-shrink-0 text-amber-600 dark:text-amber-400"></i>
                            <p class="text-[11px] leading-relaxed">
                                <strong>Log Kepatuhan Keamanan:</strong> Setiap tindakan administratif seperti menambah, mengubah, atau menghapus data akan dicatat secara otomatis dalam sistem audit log aktivitas demi menjaga kepatuhan dan integritas data sekolah.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            @endif

        </div>
    </div>
</div>

<script>
    // --- 4. LOGIKA SWEETALERT2 UNTUK HAPUS AKUN ---
    const isDarkSwal = document.documentElement.classList.contains('dark');
    const swalBgColor = isDarkSwal ? '#2b2c40' : '#fff';
    const swalTextColor = isDarkSwal ? '#d5d5e2' : '#566a7f';

    const btnDeleteAccount = document.getElementById('btnDeleteAccount');
    const deleteAccountForm = document.getElementById('deleteAccountForm');
    const hiddenPasswordInput = document.getElementById('hidden_delete_password');

    if (btnDeleteAccount) {
        btnDeleteAccount.addEventListener('click', function () {
            Swal.fire({
                title: 'Hapus Akun Permanen?',
                background: swalBgColor,
                color: swalTextColor,
                html: `
                    <p class="text-sm text-[#566a7f] dark:text-[#d5d5e2] mb-5 leading-relaxed">
                        Tindakan ini tidak bisa dibatalkan! Semua riwayat pendaftaran anak akan hilang. Masukkan password Anda untuk mengonfirmasi.
                    </p>
                    <div class="relative w-full max-w-xs mx-auto">
                        <input type="password" id="swal-input-password" class="sneat-input w-full pr-10 focus:!border-red-500 focus:!ring-red-500" placeholder="Masukkan password Anda..." autocapitalize="off" autocorrect="off">
                        <button type="button" id="swal-toggle-password" class="absolute inset-y-0 right-0 flex items-center pr-3 text-[#a1b0cb] hover:text-[#696cff] focus:outline-none transition-colors">
                            <i data-lucide="eye" id="swal-icon-eye" class="w-4 h-4"></i>
                            <i data-lucide="eye-off" id="swal-icon-eye-off" class="w-4 h-4 hidden"></i>
                        </button>
                    </div>
                `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#697a8d',
                confirmButtonText: 'Ya, Hapus Akun!',
                cancelButtonText: 'Batal',
                scrollbarPadding: false,
                heightAuto: false,
                customClass: {
                    popup: 'rounded-xl',
                    confirmButton: 'px-4 py-2 text-white font-medium rounded-md shadow-sm',
                    cancelButton: 'px-4 py-2 text-white font-medium rounded-md shadow-sm'
                },
                didOpen: () => {
                    if (typeof lucide !== 'undefined') {
                        lucide.createIcons();
                    }

                    const toggleBtn = document.getElementById('swal-toggle-password');
                    const passwordInput = document.getElementById('swal-input-password');
                    const iconEye = document.getElementById('swal-icon-eye');
                    const iconEyeOff = document.getElementById('swal-icon-eye-off');

                    toggleBtn.addEventListener('click', () => {
                        if (passwordInput.type === 'password') {
                            passwordInput.type = 'text';
                            iconEye.classList.add('hidden');
                            iconEyeOff.classList.remove('hidden');
                        } else {
                            passwordInput.type = 'password';
                            iconEye.classList.remove('hidden');
                            iconEyeOff.classList.add('hidden');
                        }
                    });
                },
                preConfirm: () => {
                    const password = document.getElementById('swal-input-password').value;
                    if (!password) {
                        Swal.showValidationMessage('Password tidak boleh kosong!');
                    }
                    return password;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    hiddenPasswordInput.value = result.value;
                    deleteAccountForm.submit();
                }
            });
        });
    }

    @if($errors->has('password') && old('_method') === 'delete')
        Swal.fire({
            title: 'Gagal!',
            text: '{{ $errors->first('password') }}',
            icon: 'error',
            background: swalBgColor,
            color: swalTextColor,
            confirmButtonColor: '#696cff',
            scrollbarPadding: false,
            heightAuto: false,
            customClass: {
                popup: 'rounded-xl',
                confirmButton: 'px-5 py-2.5 text-white font-medium rounded-md shadow-sm'
            }
        });
    @endif
</script>

<script>
function previewAvatar(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('avatarPreview').src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {

        // --- 1. LOGIKA TOGGLE PASSWORD (IKON MATA) ---
        const toggleButtons = document.querySelectorAll('.toggle-password');

        toggleButtons.forEach(button => {
            button.addEventListener('click', function () {
                const targetId = this.getAttribute('data-target');
                const inputField = document.getElementById(targetId);
                const iconEye = this.querySelector('.icon-eye');
                const iconEyeOff = this.querySelector('.icon-eye-off');

                if (inputField.type === 'password') {
                    inputField.type = 'text';
                    iconEye.classList.add('hidden');
                    iconEyeOff.classList.remove('hidden');
                } else {
                    inputField.type = 'password';
                    iconEye.classList.remove('hidden');
                    iconEyeOff.classList.add('hidden');
                }
            });
        });

        // --- 2. LOGIKA VALIDASI REAL-TIME ---
        const passwordInput = document.getElementById('password');
        const confirmInput = document.getElementById('password_confirmation');
        const rulesBox = document.getElementById('password-rules');
        const matchBox = document.getElementById('match-rule');

        function toggleRule(id, isValid) {
            const el = document.getElementById(id);
            if (!el) return;

            const iconXWrapper = el.querySelector('.icon-x-wrapper');
            const iconCheckWrapper = el.querySelector('.icon-check-wrapper');

            if (isValid) {
                el.classList.remove('text-red-500');
                el.classList.add('text-green-500');
                if(iconXWrapper) iconXWrapper.classList.add('hidden');
                if(iconCheckWrapper) iconCheckWrapper.classList.remove('hidden');
            } else {
                el.classList.remove('text-green-500');
                el.classList.add('text-red-500');
                if(iconCheckWrapper) iconCheckWrapper.classList.add('hidden');
                if(iconXWrapper) iconXWrapper.classList.remove('hidden');
            }
        }

        if (passwordInput) {
            passwordInput.addEventListener('input', function () {
                const val = this.value;

                if (val.length > 0) {
                    rulesBox.classList.remove('hidden');
                } else {
                    rulesBox.classList.add('hidden');
                }

                toggleRule('rule-length', val.length >= 8);
                toggleRule('rule-upper', /[A-Z]/.test(val));
                toggleRule('rule-lower', /[a-z]/.test(val));
                toggleRule('rule-number', /[0-9]/.test(val));

                checkMatch();
            });
        }

        if (confirmInput) {
            confirmInput.addEventListener('input', checkMatch);
        }

        function checkMatch() {
            if (!passwordInput || !confirmInput) return;

            const passVal = passwordInput.value;
            const confVal = confirmInput.value;

            if (confVal.length > 0) {
                matchBox.classList.remove('hidden');
                const iconXWrapper = matchBox.querySelector('.match-icon-x-wrapper');
                const iconCheckWrapper = matchBox.querySelector('.match-icon-check-wrapper');
                const textSpan = matchBox.querySelector('.match-text');

                if (passVal === confVal && passVal !== "") {
                    textSpan.textContent = 'Password cocok';
                    textSpan.classList.remove('text-red-500');
                    textSpan.classList.add('text-green-500');
                    matchBox.classList.remove('border-red-200', 'bg-red-50/50', 'dark:border-red-500/20', 'dark:bg-red-500/5', 'text-red-500');
                    matchBox.classList.add('border-green-200', 'bg-green-50/50', 'dark:border-green-500/20', 'dark:bg-green-500/5', 'text-green-500');
                    if(iconXWrapper) iconXWrapper.classList.add('hidden');
                    if(iconCheckWrapper) iconCheckWrapper.classList.remove('hidden');
                } else {
                    textSpan.textContent = 'Password tidak cocok';
                    textSpan.classList.remove('text-green-500');
                    textSpan.classList.add('text-red-500');
                    matchBox.classList.remove('border-green-200', 'bg-green-50/50', 'dark:border-green-500/20', 'dark:bg-green-500/5', 'text-green-500');
                    matchBox.classList.add('border-red-200', 'bg-red-50/50', 'dark:border-red-500/20', 'dark:bg-red-500/5', 'text-red-500');
                    if(iconCheckWrapper) iconCheckWrapper.classList.add('hidden');
                    if(iconXWrapper) iconXWrapper.classList.remove('hidden');
                }
            } else {
                matchBox.classList.add('hidden');
            }
        }

        // --- 3. LOGIKA SWEETALERT2 UNTUK LUPA PASSWORD ---
        const forgotPasswordBtn = document.getElementById('forgot-password-alert');
        if (forgotPasswordBtn) {
            forgotPasswordBtn.addEventListener('click', function(e) {
                e.preventDefault();

                const isDarkLupa = document.documentElement.classList.contains('dark');
                const swalBgLupa = isDarkLupa ? '#2b2c40' : '#fff';
                const swalColorLupa = isDarkLupa ? '#d5d5e2' : '#566a7f';

                Swal.fire({
                    title: 'Informasi Keamanan',
                    text: 'Untuk alasan keamanan, silakan Logout (Keluar) dari akun Anda terlebih dahulu, kemudian gunakan fitur Lupa Password di halaman Login.',
                    icon: 'info',
                    background: swalBgLupa,
                    color: swalColorLupa,
                    confirmButtonText: 'Mengerti',
                    confirmButtonColor: '#696cff',
                    scrollbarPadding: false,
                    heightAuto: false,
                    customClass: {
                        popup: 'rounded-xl',
                        confirmButton: 'px-5 py-2.5 text-white font-medium rounded-md shadow-sm'
                    }
                });
            });
        }

    });
</script>

@endsection
