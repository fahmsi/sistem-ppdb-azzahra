<!DOCTYPE html>
<html lang="id" class="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem PPDB PAUD Az-Zahra')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=poppins:400,500,600,700|public-sans:400,500,600,700"
        rel="stylesheet" />

    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Prevent FOUC: Apply theme before render -->
    <script>
        (function () {
            const saved = localStorage.getItem('ppdb_theme');
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

<body
    class="bg-[#f5f5f9] dark:bg-[#232333] font-body text-[#697a8d] dark:text-[#a1b0cb] antialiased overflow-hidden h-screen transition-colors duration-300">

    <!-- ============================================
         SIDEBAR — Sneat Style
         ============================================ -->
    <aside id="sidebar"
        class="bg-white dark:bg-[#2b2c40] w-[260px] hidden md:flex flex-col h-full transition-[width] duration-300 fixed inset-y-0 left-0 z-50 border-r border-[#d9dee3] dark:border-[#434463] group">

        <button id="sidebarToggleBtn" class="sidebar-toggle-btn focus:outline-none transition-all duration-300">
            <span id="iconWrapper" class="transition-transform duration-300 transform relative z-10">
                <i data-lucide="chevron-left" class="w-4 h-4 -translate-x-[1px]"></i>
            </span>
        </button>

        <div class="px-6 py-5 flex items-center gap-3 overflow-hidden whitespace-nowrap h-[76px]">
            <div class="w-9 h-9 rounded-md bg-[#696cff] flex items-center justify-center shadow-lg flex-shrink-0">
                <i data-lucide="book-open" class="w-5 h-5 text-white"></i>
            </div>
            <span
                class="menu-text text-xl font-heading font-bold text-[#566a7f] dark:text-[#d5d5e2] tracking-tight transition-opacity duration-300">Az-Zahra</span>
        </div>

        <nav class="flex-1 py-4 overflow-y-auto overflow-x-hidden" id="sidebarNav">
            @if(auth()->check() && auth()->user()->isAdmin())
                <p class="sneat-section-label menu-text transition-opacity duration-300 whitespace-nowrap px-6 mb-2">Admin
                    Menu</p>

                <a href="{{ route('admin.dashboard') }}"
                    class="sidebar-menu-link flex items-center px-6 py-2.5 mx-3 rounded-lg overflow-hidden whitespace-nowrap {{ request()->routeIs('admin.dashboard') ? 'active bg-[#696cff] text-white' : 'text-[#697a8d] hover:bg-gray-100 dark:hover:bg-[#232333]' }}">
                    <i data-lucide="layout-dashboard" class="w-5 h-5 flex-shrink-0"></i>
                    <span class="menu-text ml-3 transition-opacity duration-300">Dashboard</span>
                </a>

                <a href="{{ route('admin.pendaftaran.index') }}"
                    class="sidebar-menu-link flex items-center px-6 py-2.5 mx-3 rounded-lg overflow-hidden whitespace-nowrap {{ request()->routeIs('admin.pendaftaran.*') ? 'active bg-[#696cff] text-white' : 'text-[#697a8d] hover:bg-gray-100 dark:hover:bg-[#232333]' }}">
                    <i data-lucide="calendar" class="w-5 h-5 flex-shrink-0"></i>
                    <span class="menu-text ml-3 transition-opacity duration-300">Gelombang PPDB</span>
                </a>

                <a href="{{ route('admin.siswa.index') }}"
                    class="sidebar-menu-link flex items-center px-6 py-2.5 mx-3 rounded-lg overflow-hidden whitespace-nowrap {{ request()->routeIs('admin.siswa.*') ? 'active bg-[#696cff] text-white' : 'text-[#697a8d] hover:bg-gray-100 dark:hover:bg-[#232333]' }}">
                    <i data-lucide="users" class="w-5 h-5 flex-shrink-0"></i>
                    <span class="menu-text ml-3 transition-opacity duration-300">Data Siswa</span>
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

                <a href="{{ route('admin.settings.index') }}"
                    class="sidebar-menu-link flex items-center px-6 py-2.5 mx-3 rounded-lg overflow-hidden whitespace-nowrap {{ request()->routeIs('admin.settings.*') ? 'active bg-[#696cff] text-white' : 'text-[#697a8d] hover:bg-gray-100 dark:hover:bg-[#232333]' }}">
                    <i data-lucide="settings" class="w-5 h-5 flex-shrink-0"></i>
                    <span class="menu-text ml-3 transition-opacity duration-300">Pengaturan Situs</span>
                </a>

                @if(auth()->user()->isSuperAdmin())
                    <p
                        class="sneat-section-label !text-amber-500 dark:!text-amber-400 menu-text transition-opacity duration-300 whitespace-nowrap px-6 mt-4 mb-2">
                        Super Admin</p>

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

                @if(auth()->user()->siswa)
                    <a href="{{ route('parent.siswa.show', auth()->user()->siswa->id) }}"
                        class="sidebar-menu-link flex items-center px-6 py-2.5 mx-3 rounded-lg overflow-hidden whitespace-nowrap {{ request()->routeIs('parent.siswa.*') ? 'active bg-[#696cff] text-white' : 'text-[#697a8d] hover:bg-gray-100 dark:hover:bg-[#232333]' }}">
                        <i data-lucide="user" class="w-5 h-5 flex-shrink-0"></i>
                        <span class="menu-text ml-3 transition-opacity duration-300">Data Anak</span>
                    </a>
                @else
                    <a href="{{ route('parent.siswa.create') }}"
                        class="sidebar-menu-link flex items-center px-6 py-2.5 mx-3 rounded-lg overflow-hidden whitespace-nowrap {{ request()->routeIs('parent.siswa.*') ? 'active bg-[#696cff] text-white' : 'text-[#697a8d] hover:bg-gray-100 dark:hover:bg-[#232333]' }}">
                        <i data-lucide="user-plus" class="w-5 h-5 flex-shrink-0"></i>
                        <span class="menu-text ml-3 transition-opacity duration-300">Lengkapi Data Anak</span>
                    </a>
                @endif

                <a href="{{ route('parent.pendaftaran.index') }}"
                    class="sidebar-menu-link flex items-center px-6 py-2.5 mx-3 rounded-lg overflow-hidden whitespace-nowrap {{ request()->routeIs('parent.pendaftaran.index') || request()->routeIs('parent.pendaftaran.show') ? 'active bg-[#696cff] text-white' : 'text-[#697a8d] hover:bg-gray-100 dark:hover:bg-[#232333]' }}">
                    <i data-lucide="clipboard-list" class="w-5 h-5 flex-shrink-0"></i>
                    <span class="menu-text ml-3 transition-opacity duration-300">Daftar Gelombang</span>
                </a>

                <a href="{{ route('parent.pendaftaran.status') }}"
                    class="sidebar-menu-link flex items-center px-6 py-2.5 mx-3 rounded-lg overflow-hidden whitespace-nowrap {{ request()->routeIs('parent.pendaftaran.status') ? 'active bg-[#696cff] text-white' : 'text-[#697a8d] hover:bg-gray-100 dark:hover:bg-[#232333]' }}">
                    <i data-lucide="activity" class="w-5 h-5 flex-shrink-0"></i>
                    <span class="menu-text ml-3 transition-opacity duration-300">Status Pendaftaran</span>
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
    <div id="sidebarOverlay" class="fixed inset-0 bg-black/50 z-10 hidden md:hidden transition-opacity opacity-0"></div>

    <!-- ============================================
         MAIN CONTENT WRAPPER
         ============================================ -->
    <div id="main-content" class="md:ml-[260px] h-full overflow-hidden relative transition-[margin] duration-300">

        <!-- ============================================
             FLOATING NAVBAR — Sneat Style
             ============================================ -->
        <div class="relative">
            <div id="dashboardNavBackdrop"
                class="pointer-events-none absolute inset-x-0 top-0 z-30 h-20 backdrop-blur-xl bg-white/10 dark:bg-[#232333]/10"></div>

            <header id="dashboardNav"
                class="sneat-navbar absolute top-0 inset-x-0 z-40 flex items-center justify-between border border-[#d9dee3] dark:border-[#434463] !bg-white/80 dark:!bg-[#2b2c40]/80 !backdrop-blur-none transition-all duration-300">

                <div class="flex items-center gap-4">
                    <!-- Mobile Menu Toggle -->
                    <button id="mobileMenuBtn"
                        class="md:hidden text-[#697a8d] dark:text-[#a1b0cb] hover:text-[#696cff] focus:outline-none">
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
                    <button id="themeToggleBtn"
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
                

                <!-- Divider -->
                <div class="w-px h-6 bg-[#d9dee3] dark:bg-[#434463] mx-1"></div>

                <!-- User Dropdown -->
                <div class="relative">
                    <button id="userMenuBtn"
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
                                            Wali Murid
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
        <main id="mainScrollArea" class="h-full overflow-x-hidden overflow-y-auto p-4 sm:p-6 pt-24 sm:pt-24 pb-16">

            @yield('content')

        </main>

        <!-- ============================================
             FOOTER
             ============================================ -->
        <footer class="py-4 px-6 text-center text-sm text-[#a1b0cb]">
            &copy; {{ date('Y') }} PAUD Al Qur'an Az-Zahra. Hak Cipta Dilindungi.
        </footer>

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
                            <button id="closeSearchBtn"
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
                                                    data-lucide="check-square" class="w-4 h-4"></i> Verifikasi Data</a></li>
                                    </ul>
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-xs font-semibold text-[#a1b0cb] uppercase tracking-wider mb-3">Keuangan
                                        & Sistem</h3>
                                    <ul class="space-y-3">
                                        <li><a href="{{ route('admin.pembayaran.index') }}"
                                                class="flex items-center gap-2 text-sm text-[#566a7f] dark:text-[#d5d5e2] hover:text-[#696cff] transition-colors"><i
                                                    data-lucide="credit-card" class="w-4 h-4"></i> Rekap Pembayaran</a></li>
                                        <li><a href="{{ route('admin.settings.index') }}"
                                                class="flex items-center gap-2 text-sm text-[#566a7f] dark:text-[#d5d5e2] hover:text-[#696cff] transition-colors"><i
                                                    data-lucide="settings" class="w-4 h-4"></i> Pengaturan Situs</a></li>
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
                                        <li><a href="{{ route('parent.pendaftaran.index') }}"
                                                class="flex items-center gap-2 text-sm text-[#566a7f] dark:text-[#d5d5e2] hover:text-[#696cff] transition-colors"><i
                                                    data-lucide="clipboard-list" class="w-4 h-4"></i> Daftar Gelombang</a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-xs font-semibold text-[#a1b0cb] uppercase tracking-wider mb-3">Data
                                        Pendaftaran</h3>
                                    <ul class="space-y-3">
                                        <li><a href="{{ route('parent.siswa.create') }}"
                                                class="flex items-center gap-2 text-sm text-[#566a7f] dark:text-[#d5d5e2] hover:text-[#696cff] transition-colors"><i
                                                    data-lucide="user" class="w-4 h-4"></i> Lengkapi Data Anak</a></li>
                                        <li><a href="{{ route('parent.pendaftaran.status') }}"
                                                class="flex items-center gap-2 text-sm text-[#566a7f] dark:text-[#d5d5e2] hover:text-[#696cff] transition-colors"><i
                                                    data-lucide="activity" class="w-4 h-4"></i> Status Pendaftaran</a></li>
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
         SCRIPTS — SweetAlert2 (interactions moved to resources/js/app.js)
         ============================================ -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // =============================================
            // SweetAlert2 — Global Flash Messages
            // =============================================

            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: @json(session('success')),
                    showConfirmButton: true,
                    timer: 4000,
                    timerProgressBar: true,
                    showCloseButton: true
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: @json(session('error')),
                    showConfirmButton: true,
                    timer: 5000,
                    timerProgressBar: true,
                    showCloseButton: true
                });
            @endif

            @if(session('warning'))
                Swal.fire({
                    icon: 'warning',
                    title: 'Perhatian!',
                    text: @json(session('warning')),
                    showConfirmButton: true,
                    timer: 5000,
                    timerProgressBar: true,
                    showCloseButton: true
                });
            @endif
        });
    </script>
</body>

</html>