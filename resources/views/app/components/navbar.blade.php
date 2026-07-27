@php
    $navItems = [
        ['label' => 'Beranda', 'href' => '#home', 'icon' => 'home'],
        ['label' => 'Program', 'href' => '#program', 'icon' => 'layers-3'],
        ['label' => 'Kurikulum', 'href' => '#kurikulum', 'icon' => 'book-open-check'],
        ['label' => 'Biaya', 'href' => '#biaya', 'icon' => 'wallet-cards'],
        ['label' => 'Syarat', 'href' => '#persyaratan', 'icon' => 'clipboard-check'],
        ['label' => 'Agenda', 'href' => '#agenda', 'icon' => 'calendar-days'],
        ['label' => 'Kontak', 'href' => '#kontak', 'icon' => 'phone'],
    ];

    $dashboardUrl = auth()->check()
        ? (auth()->user()->isAdmin() ? route('admin.dashboard') : route('parent.dashboard'))
        : null;
@endphp

<header id="landing-header" class="pointer-events-none fixed inset-x-0 top-0 z-[100] px-3 pt-3 transition-all duration-300 sm:px-5 sm:pt-4 lg:px-8">
    <div id="navbar-backdrop" class="pointer-events-auto fixed inset-0 hidden bg-slate-950/20 backdrop-blur-[2px] xl:hidden" aria-hidden="true"></div>

    <div id="landing-navbar" class="pointer-events-auto relative z-10 mx-auto flex w-full max-w-7xl items-center justify-between gap-3 rounded-2xl border border-white/70 bg-white/80 px-3 py-2 shadow-lg shadow-slate-900/5 backdrop-blur-xl transition-all duration-300 sm:px-4">
        <a href="/" class="group flex min-w-0 shrink-0 items-center gap-2.5 rounded-xl focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 focus-visible:ring-offset-2" aria-label="Kembali ke beranda PAUD Al Qur'an Az-Zahra">
            <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-white shadow-sm ring-1 ring-slate-200/70">
                <img src="{{ asset('images/azzahra_logo.png') }}" alt="" class="h-9 w-9 object-contain transition-transform duration-300 transform-gpu will-change-transform group-hover:scale-105">
            </span>
            <span class="min-w-0 leading-tight">
                <span class="hidden text-[10px] font-bold uppercase tracking-[0.16em] text-slate-500 sm:block">PAUD Al Qur'an</span>
                <span class="block truncate text-lg font-extrabold tracking-tight text-primary-700">Azzahra Depok</span>
            </span>
        </a>

        <nav class="hidden items-center gap-0.5 xl:flex" aria-label="Navigasi utama">
            @foreach ($navItems as $item)
                <a href="{{ $item['href'] }}" data-nav-link class="rounded-xl px-3 py-2 text-[13px] font-medium text-slate-600 transition-colors duration-200 hover:bg-primary-50 hover:text-primary-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 focus-visible:ring-offset-1">
                    {{ $item['label'] }}
                </a>
            @endforeach
        </nav>

        <div class="flex shrink-0 items-center gap-1.5 sm:gap-2">
            @guest
                <div class="hidden items-center gap-1.5 md:flex">
                    <a href="{{ route('login') }}" class="rounded-full px-3 py-2 text-sm font-semibold text-slate-600 transition-colors hover:bg-slate-100 hover:text-primary-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}" class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-primary-600 to-primary-500 px-4 py-2.5 text-sm font-bold text-white shadow-md shadow-primary-600/20 transition-all duration-200 hover:-translate-y-0.5 hover:from-primary-700 hover:to-primary-600 hover:shadow-lg focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 focus-visible:ring-offset-2">
                        <i data-lucide="user-plus" class="h-4 w-4" aria-hidden="true"></i>
                        Daftar
                    </a>
                </div>
            @else
                <a href="{{ $dashboardUrl }}" class="hidden items-center gap-2 rounded-full bg-primary-600 px-4 py-2.5 text-sm font-bold text-white shadow-md shadow-primary-600/20 transition-all duration-200 hover:-translate-y-0.5 hover:bg-primary-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 focus-visible:ring-offset-2 md:inline-flex">
                    <i data-lucide="layout-dashboard" class="h-4 w-4" aria-hidden="true"></i>
                    Dashboard
                </a>
            @endguest

            <button id="navbar-menu-button" class="inline-flex h-11 w-11 items-center justify-center rounded-full text-slate-700 transition-colors hover:bg-primary-50 hover:text-primary-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 xl:hidden" type="button" aria-label="Buka menu navigasi" aria-expanded="false" aria-controls="navbar-mobile-menu">
                <i data-lucide="menu" data-menu-open-icon class="h-6 w-6" aria-hidden="true"></i>
                <i data-lucide="x" data-menu-close-icon class="hidden h-6 w-6" aria-hidden="true"></i>
            </button>
        </div>

        <nav id="navbar-mobile-menu" class="absolute inset-x-0 top-full mt-2 hidden max-h-[calc(100vh-6.5rem)] overflow-y-auto rounded-2xl border border-slate-200/80 bg-white/95 p-3 shadow-2xl shadow-slate-900/15 backdrop-blur-xl xl:hidden" aria-label="Navigasi seluler" aria-hidden="true">
            <div class="grid gap-1 sm:grid-cols-2">
                @foreach ($navItems as $item)
                    <a href="{{ $item['href'] }}" data-nav-link class="flex items-center gap-3 rounded-xl px-3.5 py-3 text-sm font-semibold text-slate-700 transition-colors hover:bg-primary-50 hover:text-primary-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-slate-100 text-slate-500">
                            <i data-lucide="{{ $item['icon'] }}" class="h-[18px] w-[18px]" aria-hidden="true"></i>
                        </span>
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </div>

            <div class="mt-3 grid gap-2 border-t border-slate-200 pt-3 md:hidden">
                @guest
                    <a href="{{ route('login') }}" class="flex items-center justify-center gap-2 rounded-xl border border-slate-300 px-4 py-3 text-sm font-bold text-slate-700 transition-colors hover:bg-slate-50 hover:text-primary-700">
                        <i data-lucide="log-in" class="h-4 w-4" aria-hidden="true"></i>
                        Masuk
                    </a>
                    <a href="{{ route('register') }}" class="flex items-center justify-center gap-2 rounded-xl bg-primary-600 px-4 py-3 text-sm font-bold text-white shadow-md shadow-primary-600/20 transition-colors hover:bg-primary-700">
                        <i data-lucide="user-plus" class="h-4 w-4" aria-hidden="true"></i>
                        Daftar Sekarang
                    </a>
                @else
                    <a href="{{ $dashboardUrl }}" class="flex items-center justify-center gap-2 rounded-xl bg-primary-600 px-4 py-3 text-sm font-bold text-white shadow-md shadow-primary-600/20 transition-colors hover:bg-primary-700">
                        <i data-lucide="layout-dashboard" class="h-4 w-4" aria-hidden="true"></i>
                        Buka Dashboard
                    </a>
                @endguest
            </div>
        </nav>
    </div>
</header>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const header = document.getElementById('landing-header');
        const navbar = document.getElementById('landing-navbar');
        const menuButton = document.getElementById('navbar-menu-button');
        const mobileMenu = document.getElementById('navbar-mobile-menu');
        const backdrop = document.getElementById('navbar-backdrop');

        if (!header || !navbar || !menuButton || !mobileMenu || !backdrop) {
            return;
        }

        const navLinks = [...document.querySelectorAll('[data-nav-link]')];
        const openIcon = menuButton.querySelector('[data-menu-open-icon]');
        const closeIcon = menuButton.querySelector('[data-menu-close-icon]');
        const sectionIds = [...new Set(navLinks.map((link) => link.hash.slice(1)))];
        const sections = sectionIds.map((id) => document.getElementById(id)).filter(Boolean);
        let menuOpen = false;
        let ticking = false;

        const setMenu = (open, restoreFocus = false) => {
            menuOpen = open;
            mobileMenu.classList.toggle('hidden', !open);
            backdrop.classList.toggle('hidden', !open);
            openIcon?.classList.toggle('hidden', open);
            closeIcon?.classList.toggle('hidden', !open);
            menuButton.setAttribute('aria-expanded', String(open));
            menuButton.setAttribute('aria-label', open ? 'Tutup menu navigasi' : 'Buka menu navigasi');
            mobileMenu.setAttribute('aria-hidden', String(!open));
            document.body.classList.toggle('overflow-hidden', open);

            if (open) {
                mobileMenu.querySelector('a')?.focus({ preventScroll: true });
            } else if (restoreFocus) {
                menuButton.focus({ preventScroll: true });
            }
        };

        const setActiveLink = (sectionId) => {
            navLinks.forEach((link) => {
                const isActive = link.hash === `#${sectionId}`;
                link.classList.toggle('bg-primary-50', isActive);
                link.classList.toggle('text-primary-700', isActive);
                link.classList.toggle('font-bold', isActive);
                link.classList.toggle('text-slate-600', !isActive && !link.closest('#navbar-mobile-menu'));
                link.classList.toggle('text-slate-700', !isActive && Boolean(link.closest('#navbar-mobile-menu')));

                if (isActive) {
                    link.setAttribute('aria-current', 'location');
                } else {
                    link.removeAttribute('aria-current');
                }
            });
        };

        const updateNavbar = () => {
            const isScrolled = window.scrollY > 24;
            header.classList.toggle('pt-3', !isScrolled);
            header.classList.toggle('sm:pt-4', !isScrolled);
            header.classList.toggle('pt-2', isScrolled);
            navbar.classList.toggle('max-w-7xl', !isScrolled);
            navbar.classList.toggle('max-w-6xl', isScrolled);
            navbar.classList.toggle('bg-white/80', !isScrolled);
            navbar.classList.toggle('border-white/70', !isScrolled);
            navbar.classList.toggle('bg-white/95', isScrolled);
            navbar.classList.toggle('border-slate-200/80', isScrolled);
            navbar.classList.toggle('shadow-xl', isScrolled);

            const marker = window.scrollY + Math.min(180, window.innerHeight * 0.3);
            let currentSection = sections[0]?.id || 'home';

            sections.forEach((section) => {
                if (marker >= section.offsetTop) {
                    currentSection = section.id;
                }
            });

            if (window.innerHeight + window.scrollY >= document.documentElement.scrollHeight - 8) {
                currentSection = sections.at(-1)?.id || currentSection;
            }

            setActiveLink(currentSection);
            ticking = false;
        };

        const requestNavbarUpdate = () => {
            if (!ticking) {
                window.requestAnimationFrame(updateNavbar);
                ticking = true;
            }
        };

        menuButton.addEventListener('click', () => setMenu(!menuOpen));
        backdrop.addEventListener('click', () => setMenu(false, true));

        navLinks.forEach((link) => {
            link.addEventListener('click', () => {
                if (menuOpen) {
                    setMenu(false);
                }
            });
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && menuOpen) {
                setMenu(false, true);
            }
        });

        window.addEventListener('scroll', requestNavbarUpdate, { passive: true });
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1280 && menuOpen) {
                setMenu(false);
            }
            requestNavbarUpdate();
        });

        updateNavbar();
    });
</script>