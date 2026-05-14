<!DOCTYPE html>
<html lang="id" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem PPDB PAUD Az-Zahra')</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=poppins:400,500,600,700|public-sans:400,500,600,700" rel="stylesheet" />

    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Prevent FOUC: Apply theme before render -->
    <script>
        (function() {
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
<body class="bg-[#f5f5f9] dark:bg-[#232333] font-body text-[#697a8d] dark:text-[#a1b0cb] antialiased overflow-hidden h-screen flex transition-colors duration-300">

    <!-- ============================================
         SIDEBAR — Sneat Style
         ============================================ -->
    <aside id="sidebar" class="bg-white dark:bg-[#2b2c40] w-[260px] flex-shrink-0 hidden md:flex flex-col h-full transition-all duration-300 relative z-20 border-r border-[#d9dee3] dark:border-[#434463]">
        
        <!-- Sidebar Brand -->
        <div class="px-6 py-5 flex items-center gap-3">
            <div class="w-9 h-9 rounded-md bg-[#696cff] flex items-center justify-center shadow-lg flex-shrink-0">
                <i data-lucide="book-open" class="w-5 h-5 text-white"></i>
            </div>
            <span class="text-xl font-heading font-bold text-[#566a7f] dark:text-[#d5d5e2] tracking-tight">Az-Zahra</span>
        </div>

        <!-- Sidebar Navigation -->
        <nav class="flex-1 py-4 overflow-y-auto" id="sidebarNav">
            @if(auth()->check() && auth()->user()->isAdmin())
                <!-- Admin Menu -->
                <p class="sneat-section-label">Admin Menu</p>
                
                <a href="{{ route('admin.dashboard') }}" class="sidebar-menu-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                    <span>Dashboard</span>
                </a>
                
                <a href="{{ route('admin.pendaftaran.index') }}" class="sidebar-menu-link {{ request()->routeIs('admin.pendaftaran.*') ? 'active' : '' }}">
                    <i data-lucide="calendar" class="w-5 h-5"></i>
                    <span>Gelombang PPDB</span>
                </a>

                <a href="{{ route('admin.siswa.index') }}" class="sidebar-menu-link {{ request()->routeIs('admin.siswa.*') ? 'active' : '' }}">
                    <i data-lucide="users" class="w-5 h-5"></i>
                    <span>Data Siswa</span>
                </a>

                <a href="{{ route('admin.verifikasi.index') }}" class="sidebar-menu-link {{ request()->routeIs('admin.verifikasi.*') ? 'active' : '' }}">
                    <i data-lucide="check-square" class="w-5 h-5"></i>
                    <span>Verifikasi Data</span>
                </a>

                <a href="{{ route('admin.pembayaran.index') }}" class="sidebar-menu-link {{ request()->routeIs('admin.pembayaran.*') ? 'active' : '' }}">
                    <i data-lucide="credit-card" class="w-5 h-5"></i>
                    <span>Rekap Pembayaran</span>
                </a>

                <a href="{{ route('admin.settings.index') }}" class="sidebar-menu-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                    <i data-lucide="settings" class="w-5 h-5"></i>
                    <span>Pengaturan Situs</span>
                </a>

                @if(auth()->user()->isSuperAdmin())
                    <!-- Super Admin Only -->
                    <p class="sneat-section-label !text-amber-500 dark:!text-amber-400">Super Admin</p>

                    <a href="{{ route('admin.kelola-admin.index') }}" class="sidebar-menu-link {{ request()->routeIs('admin.kelola-admin.*') ? 'active' : '' }}">
                        <i data-lucide="shield" class="w-5 h-5"></i>
                        <span>Kelola Admin</span>
                    </a>

                    <a href="{{ route('admin.activity-log.index') }}" class="sidebar-menu-link {{ request()->routeIs('admin.activity-log.*') ? 'active' : '' }}">
                        <i data-lucide="scroll-text" class="w-5 h-5"></i>
                        <span>Activity Log</span>
                    </a>
                @endif
            @endif

            @if(auth()->check() && auth()->user()->isParent())
                <!-- Parent Menu -->
                <p class="sneat-section-label">Menu Utama</p>

                <a href="{{ route('parent.dashboard') }}" class="sidebar-menu-link {{ request()->routeIs('parent.dashboard') ? 'active' : '' }}">
                    <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                    <span>Dashboard</span>
                </a>
                
                @if(auth()->user()->siswa)
                    <a href="{{ route('parent.siswa.show', auth()->user()->siswa->id) }}" class="sidebar-menu-link {{ request()->routeIs('parent.siswa.*') ? 'active' : '' }}">
                        <i data-lucide="user" class="w-5 h-5"></i>
                        <span>Data Anak</span>
                    </a>
                @else
                    <a href="{{ route('parent.siswa.create') }}" class="sidebar-menu-link {{ request()->routeIs('parent.siswa.*') ? 'active' : '' }}">
                        <i data-lucide="user-plus" class="w-5 h-5"></i>
                        <span>Lengkapi Data Anak</span>
                    </a>
                @endif

                <a href="{{ route('parent.pendaftaran.index') }}" class="sidebar-menu-link {{ request()->routeIs('parent.pendaftaran.index') || request()->routeIs('parent.pendaftaran.show') ? 'active' : '' }}">
                    <i data-lucide="clipboard-list" class="w-5 h-5"></i>
                    <span>Daftar Gelombang</span>
                </a>

                <a href="{{ route('parent.pendaftaran.status') }}" class="sidebar-menu-link {{ request()->routeIs('parent.pendaftaran.status') ? 'active' : '' }}">
                    <i data-lucide="activity" class="w-5 h-5"></i>
                    <span>Status Pendaftaran</span>
                </a>
            @endif
        </nav>

        <!-- Sidebar Footer — Logout -->
        <div class="p-4 border-t border-[#d9dee3] dark:border-[#434463]">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center gap-2 bg-[#f5f5f9] dark:bg-[#232333] hover:bg-red-50 dark:hover:bg-red-900/20 text-[#697a8d] dark:text-[#a1b0cb] hover:text-red-600 dark:hover:text-red-400 py-2.5 rounded-md transition-colors text-sm font-medium">
                    <i data-lucide="log-out" class="w-4 h-4"></i>
                    Logout
                </button>
            </form>
        </div>
    </aside>

    <!-- Mobile Sidebar Overlay -->
    <div id="sidebarOverlay" class="fixed inset-0 bg-black/50 z-10 hidden md:hidden transition-opacity opacity-0"></div>

    <!-- ============================================
         MAIN CONTENT WRAPPER
         ============================================ -->
    <div class="flex-1 flex flex-col h-full overflow-hidden relative">
        
        <!-- ============================================
             FLOATING NAVBAR — Sneat Style
             ============================================ -->
        <header class="sneat-navbar flex items-center justify-between z-10 border border-[#d9dee3] dark:border-[#434463]">
            
            <div class="flex items-center gap-4">
                <!-- Mobile Menu Toggle -->
                <button id="mobileMenuBtn" class="md:hidden text-[#697a8d] dark:text-[#a1b0cb] hover:text-[#696cff] focus:outline-none">
                    <i data-lucide="menu" class="w-6 h-6"></i>
                </button>

                <!-- Search Bar -->
                <div class="hidden sm:block relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i data-lucide="search" class="w-4 h-4 text-[#a1b0cb]"></i>
                    </div>
                    <input type="text" class="sneat-input pl-10 w-64 !bg-transparent !border-0 !ring-0 focus:!border-0 focus:!ring-0 text-sm" placeholder="Search (Ctrl+K)" readonly>
                </div>
            </div>

            <!-- Navbar Right Actions -->
            <div class="flex items-center gap-2">

                <!-- Theme Mode Dropdown -->
                <div class="relative" id="themeContainer">
                    <button id="themeToggleBtn" class="w-9 h-9 rounded-full flex items-center justify-center text-[#697a8d] dark:text-[#a1b0cb] hover:bg-[#f5f5f9] dark:hover:bg-[#232333] transition-colors" title="Toggle Theme">
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
                    <button id="userMenuBtn" class="flex items-center gap-2 focus:outline-none hover:opacity-90 transition-opacity py-1">
                        @if(auth()->user()->avatar)
                            <img src="{{ Storage::url(auth()->user()->avatar) }}" alt="Avatar" class="w-9 h-9 rounded-full object-cover border-2 border-[#d9dee3] dark:border-[#434463]">
                        @else
                            <div class="w-9 h-9 rounded-full bg-[#e7e7ff] dark:bg-[#696cff]/20 flex items-center justify-center text-[#696cff] font-bold text-sm uppercase">
                                {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                            </div>
                        @endif
                    </button>

                    <!-- User Dropdown Menu -->
                    <div id="userMenu" class="user-dropdown">
                        <div class="px-4 py-3 border-b border-[#d9dee3] dark:border-[#434463]">
                            <div class="flex items-center gap-3">
                                @if(auth()->user()->avatar)
                                    <img src="{{ Storage::url(auth()->user()->avatar) }}" alt="Avatar" class="w-10 h-10 rounded-full object-cover">
                                @else
                                    <div class="w-10 h-10 rounded-full bg-[#e7e7ff] dark:bg-[#696cff]/20 flex items-center justify-center text-[#696cff] font-bold text-base uppercase">
                                        {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                                    </div>
                                @endif
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-[#566a7f] dark:text-[#d5d5e2] truncate">{{ auth()->user()->name ?? 'User' }}</p>
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
                        <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-[#697a8d] dark:text-[#a1b0cb] hover:bg-[#f5f5f9] dark:hover:bg-[#232333] transition-colors">
                            <i data-lucide="user" class="w-4 h-4"></i> Profil Saya
                        </a>
                        <div class="border-t border-[#d9dee3] dark:border-[#434463] mt-1">
                            <form action="{{ route('logout') }}" method="POST" class="w-full">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                    <i data-lucide="log-out" class="w-4 h-4"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- ============================================
             MAIN CONTENT SCROLLABLE AREA
             ============================================ -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto p-4 sm:p-6">
            
            @yield('content')
            
        </main>
        
        <!-- ============================================
             FOOTER
             ============================================ -->
        <footer class="py-4 px-6 text-center text-sm text-[#a1b0cb]">
            &copy; {{ date('Y') }} PAUD Al Qur'an Az-Zahra. Hak Cipta Dilindungi.
        </footer>
    </div>

    <!-- ============================================
         SCRIPTS — Initialize Lucide & Interactions
         ============================================ -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            lucide.createIcons();

            // =============================================
            // SweetAlert2 — Global Flash Messages
            // =============================================
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: @json(session('success')),
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 4000,
                    timerProgressBar: true,
                    showCloseButton: true,
                    customClass: {
                        popup: 'swal-toast-popup'
                    }
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: @json(session('error')),
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 5000,
                    timerProgressBar: true,
                    showCloseButton: true,
                    customClass: {
                        popup: 'swal-toast-popup'
                    }
                });
            @endif

            @if(session('warning'))
                Swal.fire({
                    icon: 'warning',
                    title: 'Perhatian!',
                    text: @json(session('warning')),
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 5000,
                    timerProgressBar: true,
                    showCloseButton: true,
                    customClass: {
                        popup: 'swal-toast-popup'
                    }
                });
            @endif

            // =============================================
            // Theme Mode Toggle (Light / Dark / System)
            // =============================================
            const html = document.documentElement;
            const themeToggleBtn = document.getElementById('themeToggleBtn');
            const themeDropdown = document.getElementById('themeDropdown');
            const themeIconLight = document.getElementById('themeIconLight');
            const themeIconDark = document.getElementById('themeIconDark');
            const themeIconSystem = document.getElementById('themeIconSystem');
            const themeOptLight = document.getElementById('themeOptLight');
            const themeOptDark = document.getElementById('themeOptDark');
            const themeOptSystem = document.getElementById('themeOptSystem');

            function getSystemTheme() {
                return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            }

            function applyTheme(mode) {
                // mode = 'light', 'dark', or 'system'
                const effectiveTheme = mode === 'system' ? getSystemTheme() : mode;

                if (effectiveTheme === 'dark') {
                    html.classList.add('dark');
                    html.classList.remove('light');
                } else {
                    html.classList.add('light');
                    html.classList.remove('dark');
                }

                // Update icons in navbar button
                themeIconLight.classList.toggle('hidden', mode !== 'light');
                themeIconDark.classList.toggle('hidden', mode !== 'dark');
                themeIconSystem.classList.toggle('hidden', mode !== 'system');

                // Update dropdown active states
                [themeOptLight, themeOptDark, themeOptSystem].forEach(opt => {
                    opt.classList.remove('active');
                });
                if (mode === 'light') themeOptLight.classList.add('active');
                else if (mode === 'dark') themeOptDark.classList.add('active');
                else themeOptSystem.classList.add('active');

                localStorage.setItem('ppdb_theme', mode);
                
                // Re-create Lucide icons to apply dark mode colors
                setTimeout(() => lucide.createIcons(), 50);
            }

            // Toggle dropdown
            if (themeToggleBtn) {
                themeToggleBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    themeDropdown.classList.toggle('show');
                    // Close user menu if open
                    const um = document.getElementById('userMenu');
                    if (um) um.classList.remove('show');
                });
            }

            // Handle option clicks
            [themeOptLight, themeOptDark, themeOptSystem].forEach(opt => {
                if (opt) {
                    opt.addEventListener('click', () => {
                        const mode = opt.getAttribute('data-theme-value');
                        applyTheme(mode);
                        themeDropdown.classList.remove('show');
                    });
                }
            });

            // Initialize theme
            const savedTheme = localStorage.getItem('ppdb_theme') || 'light';
            applyTheme(savedTheme);

            // Listen for system theme changes
            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
                if (localStorage.getItem('ppdb_theme') === 'system') {
                    applyTheme('system');
                }
            });

            // =============================================
            // Mobile Sidebar Toggle
            // =============================================
            const mobileMenuBtn = document.getElementById('mobileMenuBtn');
            const sidebar = document.getElementById('sidebar');
            const sidebarOverlay = document.getElementById('sidebarOverlay');

            if (mobileMenuBtn && sidebar && sidebarOverlay) {
                mobileMenuBtn.addEventListener('click', () => {
                    sidebar.classList.toggle('hidden');
                    sidebar.classList.toggle('absolute');
                    sidebar.classList.toggle('z-30');
                    
                    sidebarOverlay.classList.toggle('hidden');
                    setTimeout(() => {
                        sidebarOverlay.classList.toggle('opacity-0');
                    }, 10);
                });

                sidebarOverlay.addEventListener('click', () => {
                    sidebar.classList.add('hidden');
                    sidebar.classList.remove('absolute');
                    sidebar.classList.remove('z-30');
                    
                    sidebarOverlay.classList.add('opacity-0');
                    setTimeout(() => {
                        sidebarOverlay.classList.add('hidden');
                    }, 300);
                });
            }

            // =============================================
            // User Dropdown Toggle
            // =============================================
            const userMenuBtn = document.getElementById('userMenuBtn');
            const userMenu = document.getElementById('userMenu');

            if (userMenuBtn && userMenu) {
                userMenuBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    userMenu.classList.toggle('show');
                    // Close theme dropdown if open
                    themeDropdown.classList.remove('show');
                });
            }

            // =============================================
            // Close All Dropdowns on Outside Click
            // =============================================
            document.addEventListener('click', (e) => {
                // Close user menu
                if (userMenu && !userMenu.contains(e.target) && !userMenuBtn.contains(e.target)) {
                    userMenu.classList.remove('show');
                }
                // Close theme dropdown
                const themeContainer = document.getElementById('themeContainer');
                if (themeDropdown && themeContainer && !themeContainer.contains(e.target)) {
                    themeDropdown.classList.remove('show');
                }
            });
        });
    </script>
</body>
</html>
