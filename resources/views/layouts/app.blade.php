<!DOCTYPE html>
<html lang="id" class="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Penerimaan Murid Baru (SPMB) PAUD Az-Zahra')</title>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('images/azzahra_logo.png') }}" type="image/png">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Prevent FOUC: Apply theme before render -->
    <script>
        (function () {
            const saved = localStorage.getItem('spmb_theme');
            if (saved === 'dark') {
                document.documentElement.classList.add('dark');
                document.documentElement.classList.remove('light');
            } else if (saved === 'light') {
                document.documentElement.classList.add('light');
                document.documentElement.classList.remove('dark');
            } else {
                // System preference
                if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
                    document.documentElement.classList.add('dark');
                    document.documentElement.classList.remove('light');
                } else {
                    document.documentElement.classList.add('light');
                    document.documentElement.classList.remove('dark');
                }
            }
        })();
    </script>

</head>

@php
    $currentUser = auth()->user();
    $parentHasSiswa = $currentUser?->isParent()
        ? $currentUser->siswas()->exists()
        : false;
@endphp

<body
    class="dashboard-readable {{ request()->routeIs('parent.dashboard') ? 'parent-dashboard-performance' : '' }} bg-[#f5f5f9] dark:bg-[#232333] font-body text-[#697a8d] dark:text-[#a1b0cb] antialiased overflow-hidden h-screen transition-colors duration-300">

    <!-- ============================================
         SIDEBAR — Sneat Style
         ============================================ -->
    <aside id="sidebar"
        class="bg-white dark:bg-[#2b2c40] w-[260px] hidden md:flex flex-col h-full transition-[width] duration-300 fixed inset-y-0 left-0 z-[70] border-r border-[#d9dee3] dark:border-[#434463] group">

        <button id="sidebarToggleBtn" type="button" aria-label="Ciutkan atau perluas menu navigasi" aria-controls="sidebarNav" class="sidebar-toggle-btn focus:outline-none focus-visible:ring-2 focus-visible:ring-[#696cff] transition-all duration-300 shadow-none" style="box-shadow: none !important;">
            <span id="iconWrapper" class="transition-transform duration-300 transform relative z-10">
                <i data-lucide="chevron-left" class="w-4 h-4 -translate-x-[1px]"></i>
            </span>
        </button>

        <div class="px-6 py-5 flex items-center gap-3 overflow-hidden whitespace-nowrap h-[76px]">
            <img src="{{ asset('images/azzahra_logo.png') }}" alt="Logo PAUD Az-Zahra" class="h-8 w-auto object-contain flex-shrink-0">
            <span
                class="menu-text text-xl font-heading font-bold text-[#566a7f] dark:text-[#d5d5e2] tracking-tight transition-opacity duration-300">SPMB Azzahra</span>
            <button id="mobileSidebarCloseBtn" type="button"
                class="ml-auto inline-flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-lg text-[#697a8d] transition-colors hover:bg-[#f5f5f9] hover:text-red-500 focus:outline-none focus-visible:ring-2 focus-visible:ring-[#696cff] dark:text-[#a1b0cb] dark:hover:bg-[#232333] md:hidden"
                aria-label="Tutup menu navigasi">
                <i data-lucide="x" class="h-5 w-5"></i>
            </button>
        </div>

        <nav class="flex-1 py-4 overflow-y-auto overflow-x-hidden" id="sidebarNav">
            @if(auth()->check() && auth()->user()->isAdmin())
                <p class="sneat-section-label menu-text transition-opacity duration-300 whitespace-nowrap px-6 mb-2">Operasional SPMB</p>

                <a href="{{ route('admin.dashboard') }}"
                    class="sidebar-menu-link flex items-center px-6 py-2.5 mx-3 rounded-lg overflow-hidden whitespace-nowrap {{ request()->routeIs('admin.dashboard') ? 'active bg-[#696cff] text-white' : 'text-[#697a8d] hover:bg-gray-100 dark:hover:bg-[#232333]' }}">
                    <i data-lucide="layout-dashboard" class="w-5 h-5 flex-shrink-0"></i>
                    <span class="menu-text ml-3 transition-opacity duration-300">Dashboard</span>
                </a>

                <a href="{{ route('admin.pendaftaran.index') }}"
                    class="sidebar-menu-link flex items-center px-6 py-2.5 mx-3 rounded-lg overflow-hidden whitespace-nowrap {{ request()->routeIs('admin.pendaftaran.*') ? 'active bg-[#696cff] text-white' : 'text-[#697a8d] hover:bg-gray-100 dark:hover:bg-[#232333]' }}">
                    <i data-lucide="calendar" class="w-5 h-5 flex-shrink-0"></i>
                    <span class="menu-text ml-3 transition-opacity duration-300">Gelombang SPMB</span>
                </a>

                <a href="{{ route('admin.verifikasi.index') }}"
                    class="sidebar-menu-link flex items-center px-6 py-2.5 mx-3 rounded-lg overflow-hidden whitespace-nowrap {{ request()->routeIs('admin.verifikasi.*') ? 'active bg-[#696cff] text-white' : 'text-[#697a8d] hover:bg-gray-100 dark:hover:bg-[#232333]' }}">
                    <i data-lucide="check-square" class="w-5 h-5 flex-shrink-0"></i>
                    <span class="menu-text ml-3 transition-opacity duration-300">Verifikasi Data</span>
                </a>

                <a href="{{ route('admin.pembayaran.index') }}"
                    class="sidebar-menu-link flex items-center px-6 py-2.5 mx-3 rounded-lg overflow-hidden whitespace-nowrap {{ request()->routeIs('admin.pembayaran.*') ? 'active bg-[#696cff] text-white' : 'text-[#697a8d] hover:bg-gray-100 dark:hover:bg-[#232333]' }}">
                    <i data-lucide="credit-card" class="w-5 h-5 flex-shrink-0"></i>
                    <span class="menu-text ml-3 transition-opacity duration-300">Rekap Pembayaran</span>
                </a>

                <a href="{{ route('admin.siswa.index') }}"
                    class="sidebar-menu-link flex items-center px-6 py-2.5 mx-3 rounded-lg overflow-hidden whitespace-nowrap {{ request()->routeIs('admin.siswa.*') ? 'active bg-[#696cff] text-white' : 'text-[#697a8d] hover:bg-gray-100 dark:hover:bg-[#232333]' }}">
                    <i data-lucide="users" class="w-5 h-5 flex-shrink-0"></i>
                    <span class="menu-text ml-3 transition-opacity duration-300">Data Siswa</span>
                </a>

                <p class="sneat-section-label menu-text mt-4 mb-2 whitespace-nowrap px-6 transition-opacity duration-300">Konten Website</p>

                <a href="{{ route('admin.testimonials.index') }}"
                    class="sidebar-menu-link flex items-center px-6 py-2.5 mx-3 rounded-lg overflow-hidden whitespace-nowrap {{ request()->routeIs('admin.testimonials.*') ? 'active bg-[#696cff] text-white' : 'text-[#697a8d] hover:bg-gray-100 dark:hover:bg-[#232333]' }}">
                    <i data-lucide="message-circle" class="w-5 h-5 flex-shrink-0"></i>
                    <span class="menu-text ml-3 transition-opacity duration-300">Testimoni</span>
                </a>

                <a href="{{ route('admin.gallery.index') }}"
                    class="sidebar-menu-link flex items-center px-6 py-2.5 mx-3 rounded-lg overflow-hidden whitespace-nowrap {{ request()->routeIs('admin.gallery.*') ? 'active bg-[#696cff] text-white' : 'text-[#697a8d] hover:bg-gray-100 dark:hover:bg-[#232333]' }}">
                    <i data-lucide="images" class="w-5 h-5 flex-shrink-0"></i>
                    <span class="menu-text ml-3 transition-opacity duration-300">Gallery</span>
                </a>

                <p class="sneat-section-label menu-text mt-4 mb-2 whitespace-nowrap px-6 transition-opacity duration-300">Sistem</p>

                <a href="{{ route('admin.payment-settings.edit') }}"
                    class="sidebar-menu-link flex items-center px-6 py-2.5 mx-3 rounded-lg overflow-hidden whitespace-nowrap {{ request()->routeIs('admin.payment-settings.*') ? 'active bg-[#696cff] text-white' : 'text-[#697a8d] hover:bg-gray-100 dark:hover:bg-[#232333]' }}">
                    <i data-lucide="landmark" class="w-5 h-5 flex-shrink-0"></i>
                    <span class="menu-text ml-3 transition-opacity duration-300">Konfigurasi Pembayaran</span>
                </a>

                <a href="{{ route('admin.settings.index') }}"
                    class="sidebar-menu-link flex items-center px-6 py-2.5 mx-3 rounded-lg overflow-hidden whitespace-nowrap {{ request()->routeIs('admin.settings.*') ? 'active bg-[#696cff] text-white' : 'text-[#697a8d] hover:bg-gray-100 dark:hover:bg-[#232333]' }}">
                    <i data-lucide="settings" class="w-5 h-5 flex-shrink-0"></i>
                    <span class="menu-text ml-3 transition-opacity duration-300">Pengaturan Situs</span>
                </a>

                @if(auth()->user()->isSuperAdmin())
                    <p
                        class="sneat-section-label !text-amber-500 dark:!text-amber-400 menu-text transition-opacity duration-300 whitespace-nowrap px-6 mt-4 mb-2">
                        Akses Super Admin</p>

                    <a href="{{ route('admin.kelola-admin.index') }}"
                        class="sidebar-menu-link flex items-center px-6 py-2.5 mx-3 rounded-lg overflow-hidden whitespace-nowrap {{ request()->routeIs('admin.kelola-admin.*') ? 'active bg-[#696cff] text-white' : 'text-[#697a8d] hover:bg-gray-100 dark:hover:bg-[#232333]' }}">
                        <i data-lucide="shield" class="w-5 h-5 flex-shrink-0"></i>
                        <span class="menu-text ml-3 transition-opacity duration-300">Kelola Admin</span>
                    </a>

                    <a href="{{ route('admin.activity-log.index') }}"
                        class="sidebar-menu-link flex items-center px-6 py-2.5 mx-3 rounded-lg overflow-hidden whitespace-nowrap {{ request()->routeIs('admin.activity-log.*') ? 'active bg-[#696cff] text-white' : 'text-[#697a8d] hover:bg-gray-100 dark:hover:bg-[#232333]' }}">
                        <i data-lucide="scroll-text" class="w-5 h-5 flex-shrink-0"></i>
                        <span class="menu-text ml-3 transition-opacity duration-300">Activity Log</span>
                    </a>
                @endif
            @endif

            @if(auth()->check() && auth()->user()->isParent())
                <p class="sneat-section-label menu-text transition-opacity duration-300 whitespace-nowrap px-6 mb-2">Menu
                    Utama</p>

                <a href="{{ route('parent.dashboard') }}"
                    class="sidebar-menu-link flex items-center px-6 py-2.5 mx-3 rounded-lg overflow-hidden whitespace-nowrap {{ request()->routeIs('parent.dashboard') ? 'active bg-[#696cff] text-white' : 'text-[#697a8d] hover:bg-gray-100 dark:hover:bg-[#232333]' }}">
                    <i data-lucide="layout-dashboard" class="w-5 h-5 flex-shrink-0"></i>
                    <span class="menu-text ml-3 transition-opacity duration-300">Dashboard</span>
                </a>

                <a href="{{ route('parent.siswa.index') }}"
                    class="sidebar-menu-link flex items-center px-6 py-2.5 mx-3 rounded-lg overflow-hidden whitespace-nowrap {{ request()->routeIs('parent.siswa.*') ? 'active bg-[#696cff] text-white' : 'text-[#697a8d] hover:bg-gray-100 dark:hover:bg-[#232333]' }}">
                    <i data-lucide="users" class="w-5 h-5 flex-shrink-0"></i>
                    <span class="menu-text ml-3 transition-opacity duration-300">Anak Saya</span>
                </a>
            @endif
        </nav>

        <div class="p-4 border-t border-[#d9dee3] dark:border-[#434463] overflow-hidden">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit"
                    class="w-full flex items-center px-3 py-2.5 bg-[#f5f5f9] dark:bg-[#232333] hover:bg-red-50 dark:hover:bg-red-900/20 text-[#697a8d] dark:text-[#a1b0cb] hover:text-red-600 dark:hover:text-red-400 rounded-md transition-all text-sm font-medium overflow-hidden whitespace-nowrap">
                    <i data-lucide="log-out" class="w-5 h-5 flex-shrink-0"></i>
                    <span class="menu-text ml-3 transition-opacity duration-300">Logout</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Mobile Sidebar Overlay -->
    <div id="sidebarOverlay" class="fixed inset-0 z-[60] hidden bg-slate-950/50 opacity-0 backdrop-blur-[2px] transition-opacity duration-200 md:hidden"></div>

    <!-- ============================================
         MAIN CONTENT WRAPPER
         ============================================ -->
    <div id="main-content" class="md:ml-[260px] h-full overflow-hidden relative transition-[margin] duration-300">

        <!-- ============================================
             FLOATING NAVBAR — Sneat Style
             ============================================ -->
        <div class="relative">
            <div id="dashboardNavBackdrop"
                class="pointer-events-none absolute inset-x-0 top-0 z-30 h-20 bg-white/80 dark:bg-[#232333]/80"></div>

            <header id="dashboardNav"
                class="sneat-navbar absolute top-0 inset-x-0 z-40 flex items-center justify-between border border-[#d9dee3] dark:border-[#434463] !mx-3 !px-3 sm:!mx-4 sm:!px-6 !bg-white/90 dark:!bg-[#2b2c40]/90 !backdrop-blur-none transition-all duration-300">

                <div class="flex items-center gap-4">
                    <!-- Mobile Menu Toggle -->
                    <button id="mobileMenuBtn"
                        type="button"
                        class="inline-flex h-10 w-10 items-center justify-center rounded-lg text-[#697a8d] transition-colors hover:bg-[#f5f5f9] hover:text-[#696cff] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#696cff] dark:text-[#a1b0cb] dark:hover:bg-[#232333] md:hidden"
                        aria-label="Buka menu navigasi"
                        aria-controls="sidebar"
                        aria-expanded="false">
                        <i data-lucide="menu" class="w-6 h-6"></i>
                    </button>

                    <!-- Search Bar -->
                <div class="hidden sm:block relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i data-lucide="search" class="w-4 h-4 text-[#a1b0cb]"></i>
                    </div>
                    <input type="text" id="searchTriggerBtn"
                        class="sneat-input !pl-12 w-64 !bg-transparent !border-0 !ring-0 focus:!border-0 focus:!ring-0 text-sm cursor-pointer"
                        placeholder="Search (Ctrl+K)" readonly>
                </div>
            </div>

            <!-- Navbar Right Actions -->
            <div class="flex items-center gap-2">

                <!-- Theme Mode Dropdown -->
                <div class="relative" id="themeContainer">
                    <button id="themeToggleBtn" type="button" aria-label="Ubah tema tampilan" aria-expanded="false" aria-controls="themeDropdown"
                        class="w-9 h-9 rounded-full flex items-center justify-center text-[#697a8d] dark:text-[#a1b0cb] hover:bg-[#f5f5f9] dark:hover:bg-[#232333] transition-colors"
                        title="Toggle Theme">
                        <i data-lucide="sun" class="w-5 h-5 hidden" id="themeIconLight"></i>
                        <i data-lucide="moon" class="w-5 h-5 hidden" id="themeIconDark"></i>
                        <i data-lucide="monitor" class="w-5 h-5 hidden" id="themeIconSystem"></i>
                    </button>

                    <!-- Theme Dropdown -->
                    <div class="theme-dropdown" id="themeDropdown">
                        <div class="theme-dropdown-item" data-theme-value="light" id="themeOptLight">
                            <i data-lucide="sun" class="w-4 h-4"></i>
                            <span>Light</span>
                        </div>
                        <div class="theme-dropdown-item" data-theme-value="dark" id="themeOptDark">
                            <i data-lucide="moon" class="w-4 h-4"></i>
                            <span>Dark</span>
                        </div>
                        <div class="theme-dropdown-item" data-theme-value="system" id="themeOptSystem">
                            <i data-lucide="monitor" class="w-4 h-4"></i>
                            <span>System</span>
                        </div>
                    </div>
                </div>

                @if(auth()->check() && auth()->user()->isParent())
                <!-- Notification Bell -->
                <div class="relative" id="notifContainer">
                    <button id="notifToggleBtn" type="button" aria-label="Buka notifikasi" aria-expanded="false" aria-controls="notifDropdown"
                        class="w-9 h-9 rounded-full flex items-center justify-center text-[#697a8d] dark:text-[#a1b0cb] hover:bg-[#f5f5f9] dark:hover:bg-[#232333] transition-colors relative"
                        title="Notifikasi">
                        <i data-lucide="bell" class="w-5 h-5"></i>
                        @if(auth()->user()->unreadNotifications->count() > 0)
                            <span class="absolute -top-0.5 -right-0.5 w-5 h-5 bg-red-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center shadow-sm animate-pulse">
                                {{ auth()->user()->unreadNotifications->count() > 9 ? '9+' : auth()->user()->unreadNotifications->count() }}
                            </span>
                        @endif
                    </button>

                    <!-- Notification Dropdown -->
                    <div id="notifDropdown" class="fixed inset-x-3 top-20 z-50 hidden overflow-hidden rounded-lg border border-[#d9dee3] bg-white shadow-xl dark:border-[#434463] dark:bg-[#2b2c40] sm:absolute sm:inset-x-auto sm:right-0 sm:top-auto sm:mt-2 sm:w-96">
                        <div class="px-4 py-3 border-b border-[#d9dee3] dark:border-[#434463] flex items-center justify-between">
                            <h4 class="text-sm font-semibold text-[#566a7f] dark:text-[#d5d5e2] flex items-center gap-2">
                                <i data-lucide="bell" class="w-4 h-4 text-[#696cff]"></i> Notifikasi
                            </h4>
                            @if(auth()->user()->unreadNotifications->count() > 0)
                                <form action="{{ route('notifications.markAllRead') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="text-xs text-[#696cff] hover:underline font-medium">Tandai Semua Dibaca</button>
                                </form>
                            @endif
                        </div>
                        <div class="max-h-80 overflow-y-auto divide-y divide-[#d9dee3] dark:divide-[#434463]">
                            @forelse(auth()->user()->notifications->take(10) as $notif)
                                <div class="px-4 py-3 flex items-start gap-3 {{ $notif->read_at ? 'opacity-60' : 'bg-[#f5f5f9] dark:bg-[#232333]' }} hover:bg-[#e7e7ff]/30 dark:hover:bg-[#696cff]/5 transition-colors">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0
                                        {{ ($notif->data['status'] ?? '') === 'diterima' ? 'bg-emerald-100 dark:bg-emerald-500/20 text-emerald-600' : '' }}
                                        {{ ($notif->data['status'] ?? '') === 'ditolak' ? 'bg-red-100 dark:bg-red-500/20 text-red-600' : '' }}
                                        {{ ($notif->data['status'] ?? '') === 'perlu_revisi' ? 'bg-orange-100 dark:bg-orange-500/20 text-orange-600' : '' }}
                                        {{ !in_array($notif->data['status'] ?? '', ['diterima', 'ditolak', 'perlu_revisi']) ? 'bg-blue-100 dark:bg-blue-500/20 text-blue-600' : '' }}">
                                        <i data-lucide="{{ $notif->data['icon'] ?? 'info' }}" class="w-4 h-4"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm text-[#566a7f] dark:text-[#d5d5e2] leading-snug">{{ $notif->data['message'] ?? 'Notifikasi baru.' }}</p>
                                        <p class="text-xs text-[#a1b0cb] mt-1">{{ $notif->created_at->locale('id')->diffForHumans() }}</p>
                                    </div>
                                    @if(!$notif->read_at)
                                        <span class="w-2 h-2 bg-[#696cff] rounded-full flex-shrink-0 mt-2"></span>
                                    @endif
                                </div>
                            @empty
                                <div class="px-4 py-8 text-center">
                                    <i data-lucide="bell-off" class="w-8 h-8 text-[#a1b0cb] mx-auto mb-2"></i>
                                    <p class="text-sm text-[#a1b0cb]">Belum ada notifikasi.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
                @endif
                

                <!-- Divider -->
                <div class="hidden h-6 w-px bg-[#d9dee3] dark:bg-[#434463] sm:block sm:mx-1"></div>

                <!-- User Dropdown -->
                <div class="relative">
                    <button id="userMenuBtn" type="button" aria-label="Buka menu akun" aria-expanded="false" aria-controls="userMenu"
                        class="flex items-center gap-2 focus:outline-none hover:opacity-90 transition-opacity py-1">
                        @if(auth()->user()->avatar)
                            <img src="{{ Storage::url(auth()->user()->avatar) }}" alt="Avatar"
                                class="w-9 h-9 rounded-full object-cover border-2 border-[#d9dee3] dark:border-[#434463]">
                        @else
                            <div
                                class="w-9 h-9 rounded-full bg-[#e7e7ff] dark:bg-[#696cff]/20 flex items-center justify-center text-[#696cff] font-bold text-sm uppercase">
                                {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                            </div>
                        @endif
                    </button>

                    <!-- User Dropdown Menu -->
                    <div id="userMenu" class="user-dropdown">
                        <div class="px-4 py-3 border-b border-[#d9dee3] dark:border-[#434463]">
                            <div class="flex items-center gap-3">
                                @if(auth()->user()->avatar)
                                    <img src="{{ Storage::url(auth()->user()->avatar) }}" alt="Avatar"
                                        class="w-10 h-10 rounded-full object-cover">
                                @else
                                    <div
                                        class="w-10 h-10 rounded-full bg-[#e7e7ff] dark:bg-[#696cff]/20 flex items-center justify-center text-[#696cff] font-bold text-base uppercase">
                                        {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                                    </div>
                                @endif
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-[#566a7f] dark:text-[#d5d5e2] truncate">
                                        {{ auth()->user()->name ?? 'User' }}</p>
                                    <p class="text-xs text-[#a1b0cb] truncate">
                                        @if(auth()->user()->isSuperAdmin())
                                            Super Admin
                                        @elseif(auth()->user()->isAdmin())
                                            Administrator
                                        @else
                                            Orang Tua/Wali
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                        <a href="{{ route('profile.edit') }}"
                            class="flex items-center gap-3 px-4 py-2.5 text-sm text-[#697a8d] dark:text-[#a1b0cb] hover:bg-[#f5f5f9] dark:hover:bg-[#232333] transition-colors">
                            <i data-lucide="user" class="w-4 h-4"></i> Profil Saya
                        </a>
                        <div class="border-t border-[#d9dee3] dark:border-[#434463] mt-1">
                            <form action="{{ route('logout') }}" method="POST" class="w-full">
                                @csrf
                                <button type="submit"
                                    class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                    <i data-lucide="log-out" class="w-4 h-4"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        </div>

        <!-- ============================================
             MAIN CONTENT SCROLLABLE AREA
             ============================================ -->
        <main id="mainScrollArea" class="flex h-full flex-col overflow-x-hidden overflow-y-auto p-3 pb-12 pt-20 sm:p-6 sm:pb-16 sm:pt-24">

            @yield('content')

            <!-- ============================================
                 FOOTER
                 ============================================ -->
            <div class="mt-auto pt-8">
                <footer class="border-t border-[#d9dee3] px-3 py-4 text-center text-xs text-[#a1b0cb] dark:border-[#434463] sm:px-6 sm:text-sm">
                    <p>&copy; {{ date('Y') }} PAUD Al Qur'an Az-Zahra. Hak Cipta Dilindungi.</p>
                    <nav class="mt-2 flex flex-wrap justify-center gap-x-4 gap-y-1" aria-label="Informasi legal">
                        <a href="{{ route('terms') }}" class="hover:text-[#696cff] hover:underline">Syarat dan Ketentuan</a>
                        <a href="{{ route('privacy') }}" class="hover:text-[#696cff] hover:underline">Kebijakan Privasi</a>
                    </nav>
                </footer>
            </div>

        </main>

        <!-- Search Modal -->
        <div id="searchModal" class="fixed inset-0 z-[9999] hidden">
            <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity" id="searchBackdrop"></div>

            <div class="fixed inset-0 z-10 overflow-y-auto">
                <div class="flex min-h-full items-start justify-center p-4 pt-20 sm:p-6 sm:pt-24">
                    <div id="searchModalContent"
                        class="w-full max-w-2xl transform divide-y divide-[#d9dee3] dark:divide-[#434463] overflow-hidden rounded-xl bg-white dark:bg-[#2b2c40] shadow-2xl ring-1 ring-black ring-opacity-5 transition-all">

                        <div class="relative">
                            <i data-lucide="search"
                                class="pointer-events-none absolute left-4 top-4 h-5 w-5 text-[#a1b0cb]"></i>
                            <input type="text" id="searchInputModal"
                                class="h-14 w-full border-0 bg-transparent pl-11 pr-12 text-[#566a7f] dark:text-[#d5d5e2] placeholder-[#a1b0cb] focus:ring-0 sm:text-sm outline-none"
                                placeholder="Ketik pencarian... [esc]" autocomplete="off">
                            <button id="closeSearchBtn" type="button" aria-label="Tutup pencarian"
                                class="absolute right-4 top-4 text-[#a1b0cb] hover:text-red-500 transition-colors">
                                <i data-lucide="x" class="h-5 w-5"></i>
                            </button>
                        </div>

                        <div class="flex flex-col sm:flex-row p-4 gap-6">
                            @if(auth()->check() && auth()->user()->isAdmin())
                                <div class="flex-1">
                                    <h3 class="text-xs font-semibold text-[#a1b0cb] uppercase tracking-wider mb-3">Menu
                                        Admin</h3>
                                    <ul class="space-y-3">
                                        <li><a href="{{ route('admin.dashboard') }}"
                                                class="flex items-center gap-2 text-sm text-[#566a7f] dark:text-[#d5d5e2] hover:text-[#696cff] transition-colors"><i
                                                    data-lucide="layout-dashboard" class="w-4 h-4"></i> Dashboard</a></li>
                                        <li><a href="{{ route('admin.siswa.index') }}"
                                                class="flex items-center gap-2 text-sm text-[#566a7f] dark:text-[#d5d5e2] hover:text-[#696cff] transition-colors"><i
                                                    data-lucide="users" class="w-4 h-4"></i> Data Siswa</a></li>
                                        <li><a href="{{ route('admin.verifikasi.index') }}"
                                                class="flex items-center gap-2 text-sm text-[#566a7f] dark:text-[#d5d5e2] hover:text-[#696cff] transition-colors"><i
                                                    data-lucide="check-square" class="w-4 h-4"></i> Verifikasi dan Observasi</a></li>
                                    </ul>
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-xs font-semibold text-[#a1b0cb] uppercase tracking-wider mb-3">Konten & Sistem</h3>
                                    <ul class="space-y-3">
                                        <li><a href="{{ route('admin.pembayaran.index') }}"
                                                class="flex items-center gap-2 text-sm text-[#566a7f] dark:text-[#d5d5e2] hover:text-[#696cff] transition-colors"><i
                                                    data-lucide="credit-card" class="w-4 h-4"></i> Pembayaran</a></li>
                                        <li><a href="{{ route('admin.testimonials.index') }}"
                                                class="flex items-center gap-2 text-sm text-[#566a7f] dark:text-[#d5d5e2] hover:text-[#696cff] transition-colors"><i
                                                        data-lucide="message-circle" class="w-4 h-4"></i> Testimoni</a></li>
                                        <li><a href="{{ route('admin.gallery.index') }}"
                                                class="flex items-center gap-2 text-sm text-[#566a7f] dark:text-[#d5d5e2] hover:text-[#696cff] transition-colors"><i
                                                        data-lucide="images" class="w-4 h-4"></i> Galeri</a></li>
                                        <li><a href="{{ route('admin.settings.index') }}"
                                                class="flex items-center gap-2 text-sm text-[#566a7f] dark:text-[#d5d5e2] hover:text-[#696cff] transition-colors"><i
                                                        data-lucide="settings" class="w-4 h-4"></i> Pengaturan Konten</a></li>
                                    </ul>
                                </div>
                                @if(auth()->user()->isSuperAdmin())
                                    <div class="flex-1">
                                        <h3 class="text-xs font-semibold text-amber-500 uppercase tracking-wider mb-3">Super
                                            Admin</h3>
                                        <ul class="space-y-3">
                                            <li><a href="{{ route('admin.kelola-admin.index') }}"
                                                    class="flex items-center gap-2 text-sm text-[#566a7f] dark:text-[#d5d5e2] hover:text-[#696cff] transition-colors"><i
                                                        data-lucide="shield" class="w-4 h-4"></i> Kelola Admin</a></li>
                                            <li><a href="{{ route('admin.activity-log.index') }}"
                                                    class="flex items-center gap-2 text-sm text-[#566a7f] dark:text-[#d5d5e2] hover:text-[#696cff] transition-colors"><i
                                                        data-lucide="scroll-text" class="w-4 h-4"></i> Activity Log</a></li>
                                        </ul>
                                    </div>
                                @endif
                            @elseif(auth()->check() && auth()->user()->isParent())
                                <div class="flex-1">
                                    <h3 class="text-xs font-semibold text-[#a1b0cb] uppercase tracking-wider mb-3">Menu
                                        Utama</h3>
                                    <ul class="space-y-3">
                                        <li><a href="{{ route('parent.dashboard') }}"
                                                class="flex items-center gap-2 text-sm text-[#566a7f] dark:text-[#d5d5e2] hover:text-[#696cff] transition-colors"><i
                                                    data-lucide="layout-dashboard" class="w-4 h-4"></i> Dashboard Saya</a>
                                        </li>
                                        @if($parentHasSiswa)
                                            <li><a href="{{ route('parent.siswa.index') }}"
                                                    class="flex items-center gap-2 text-sm text-[#566a7f] dark:text-[#d5d5e2] hover:text-[#696cff] transition-colors"><i
                                                        data-lucide="users" class="w-4 h-4"></i> Kelola Data Anak</a>
                                            </li>
                                        @else
                                            <li><a href="{{ route('parent.siswa.create') }}"
                                                    class="flex items-center gap-2 text-sm text-[#566a7f] dark:text-[#d5d5e2] hover:text-[#696cff] transition-colors"><i
                                                        data-lucide="user-plus" class="w-4 h-4"></i> Lengkapi Data Anak</a>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-xs font-semibold text-[#a1b0cb] uppercase tracking-wider mb-3">Data
                                        Pendaftaran</h3>
                                    <ul class="space-y-3">
                                        <li><a href="{{ route('parent.siswa.create') }}"
                                                class="flex items-center gap-2 text-sm text-[#566a7f] dark:text-[#d5d5e2] hover:text-[#696cff] transition-colors"><i
                                                    data-lucide="user-plus" class="w-4 h-4"></i> Tambah Anak</a></li>
                                        <li><a href="{{ route('parent.siswa.index') }}"
                                                class="flex items-center gap-2 text-sm text-[#566a7f] dark:text-[#d5d5e2] hover:text-[#696cff] transition-colors"><i
                                                    data-lucide="activity" class="w-4 h-4"></i> Status per Anak</a></li>
                                    </ul>
                                </div>
                            @endif
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================
         SCRIPTS — SweetAlert2
         ============================================ -->
    <!-- ============================================
         FLOATING WHATSAPP BUTTON (PARENT ONLY)
         ============================================ -->
    @if(auth()->check() && auth()->user()->isParent())
        @php
            $adminWhatsapp = preg_replace('/[^0-9]/', '', (string) config('spmb.admin_whatsapp', ''));
            $helpWaMessage = "Assalamu'alaikum Admin PAUD Az-Zahra, saya ingin menanyakan proses pendaftaran anak saya.";
            $helpWaUrl = $adminWhatsapp ? 'https://wa.me/'.$adminWhatsapp.'?text='.urlencode($helpWaMessage) : '#';
        @endphp
        @if($adminWhatsapp)
            <a href="{{ $helpWaUrl }}" target="_blank" rel="noopener noreferrer" 
               class="fixed bottom-6 right-6 z-[999] flex h-14 w-14 items-center justify-center rounded-full bg-emerald-500 text-white shadow-lg hover:bg-emerald-600 hover:scale-110 active:scale-95 transition-all duration-300 group"
               title="Hubungi Kami via WhatsApp">
                <svg class="h-8 w-8 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L0 24l6.335-1.662c1.746.953 3.71 1.458 5.704 1.459h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                </svg>
                <span class="absolute right-16 bg-slate-900 text-white text-xs px-2.5 py-1.5 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300 whitespace-nowrap shadow-md pointer-events-none">Hubungi Kami (WhatsApp)</span>
            </a>
        @endif
    @endif

    @include('components.sweetalert')
</body>

</html>
