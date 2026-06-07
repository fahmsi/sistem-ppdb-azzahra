<header id="header-wrapper" class="fixed top-0 left-0 w-full z-[100] transition-all duration-500 ease-in-out py-4 px-4 sm:px-6 lg:px-8 pointer-events-none">
    <div id="navbar-container" class="mx-auto w-full max-w-7xl flex items-center justify-between px-0 py-2 transition-all duration-500 ease-in-out bg-transparent pointer-events-auto relative gap-2 lg:gap-4">
        
        <div class="flex items-center gap-3 lg:gap-6 min-w-0">
            <a href="/" class="flex items-center gap-2 pl-2 shrink-0 group">
                <img src="{{ asset('images/azzahra_logo.png') }}" alt="Logo PAUD Az-Zahra" class="h-10 w-auto object-contain transition-transform group-hover:scale-105">
                <span class="font-extrabold text-xl tracking-wide text-primary-700">Azzahra</span>
            </a>

            <nav class="hidden lg:flex items-center gap-0.5 xl:gap-1 overflow-hidden">
                <a href="#home" class="nav-link flex items-center gap-1.5 px-3 py-2 rounded-full text-sm transition-all duration-300 hover:text-primary-700 hover:bg-primary-50 whitespace-nowrap text-primary-700 bg-primary-50">
                    <i data-lucide="home" class="w-4 h-4 nav-icon"></i> Home
                </a>
                <a href="#kurikulum" class="nav-link flex items-center gap-1.5 px-3 py-2 rounded-full text-sm transition-all duration-300 hover:text-primary-700 hover:bg-primary-50 whitespace-nowrap text-gray-700">
                    <i data-lucide="layers" class="w-4 h-4 nav-icon"></i> Program
                </a>
                <a href="#agenda" class="nav-link flex items-center gap-1.5 px-3 py-2 rounded-full text-sm transition-all duration-300 hover:text-primary-700 hover:bg-primary-50 whitespace-nowrap text-gray-700">
                    <i data-lucide="calendar" class="w-4 h-4 nav-icon"></i> Agenda
                </a>
                <a href="#persyaratan" class="nav-link flex items-center gap-1.5 px-3 py-2 rounded-full text-sm transition-all duration-300 hover:text-primary-700 hover:bg-primary-50 whitespace-nowrap text-gray-700">
                    <i data-lucide="file-check" class="w-4 h-4 nav-icon"></i> Syarat
                </a>
                <a href="#biaya" class="nav-link flex items-center gap-1.5 px-3 py-2 rounded-full text-sm transition-all duration-300 hover:text-primary-700 hover:bg-primary-50 whitespace-nowrap text-gray-700">
                    <i data-lucide="credit-card" class="w-4 h-4 nav-icon"></i> Biaya
                </a>
                <a href="#kontak" class="nav-link flex items-center gap-1.5 px-3 py-2 rounded-full text-sm transition-all duration-300 hover:text-primary-700 hover:bg-primary-50 whitespace-nowrap text-gray-700">
                    <i data-lucide="phone" class="w-4 h-4 nav-icon"></i> Kontak
                </a>
            </nav>
        </div>

        <div class="flex items-center gap-2 pr-2 shrink-0">
            <div class="flex items-center gap-2 ml-4 pl-4 border-l border-gray-200">
                    {{-- Tombol Masuk (Secondary - Low Visual Weight) --}}
                    <a href="/login"
                        class="px-4 py-2 text-sm font-semibold text-slate-600 hover:text-primary-600 transition-colors duration-200">
                        Masuk
                    </a>
                    {{-- Tombol Daftar (Primary - High Visual Weight) --}}
                    <a href="/register"
                        class="inline-flex items-center gap-2 bg-gradient-to-r from-primary-600 to-primary-500 hover:from-primary-700 hover:to-primary-600 text-white px-5 py-2.5 rounded-xl text-sm font-semibold shadow-md hover:shadow-lg transition-all duration-300 hover:-translate-y-0.5">
                        <i data-lucide="user-plus" class="w-4 h-4"></i>
                        Daftar
                    </a>
            </div>
            <div class="">

            <button id="mobile-menu-button" class="lg:hidden p-2 rounded-full text-gray-800 hover:bg-primary-50 transition-colors focus:outline-none" aria-label="Toggle Menu">
                <i data-lucide="menu" class="w-6 h-6"></i>
            </button>
        </div>

        <div id="mobile-menu-panel" class="hidden absolute top-full left-0 w-full mt-2 bg-white/95 backdrop-blur-lg rounded-2xl border border-gray-200 shadow-xl p-4 flex flex-col gap-1 z-[110]">
            <a href="#home" class="mobile-nav-link flex items-center gap-3 px-4 py-3 rounded-xl text-base font-semibold text-gray-800 hover:bg-primary-50 hover:text-primary-700 transition-colors"> <i data-lucide="home" class="w-5 h-5 mobile-nav-icon"></i> Home </a>
            <a href="#kurikulum" class="mobile-nav-link flex items-center gap-3 px-4 py-3 rounded-xl text-base font-semibold text-gray-800 hover:bg-primary-50 hover:text-primary-700 transition-colors"> <i data-lucide="layers" class="w-5 h-5 mobile-nav-icon"></i> Program </a>
            <a href="#agenda" class="mobile-nav-link flex items-center gap-3 px-4 py-3 rounded-xl text-base font-semibold text-gray-800 hover:bg-primary-50 hover:text-primary-700 transition-colors"> <i data-lucide="calendar" class="w-5 h-5 mobile-nav-icon"></i> Agenda </a>
            <a href="#persyaratan" class="mobile-nav-link flex items-center gap-3 px-4 py-3 rounded-xl text-base font-semibold text-gray-800 hover:bg-primary-50 hover:text-primary-700 transition-colors"> <i data-lucide="file-check" class="w-5 h-5 mobile-nav-icon"></i> Syarat </a>
            <a href="#biaya" class="mobile-nav-link flex items-center gap-3 px-4 py-3 rounded-xl text-base font-semibold text-gray-800 hover:bg-primary-50 hover:text-primary-700 transition-colors"> <i data-lucide="credit-card" class="w-5 h-5 mobile-nav-icon"></i> Biaya </a>
            <a href="#kontak" class="mobile-nav-link flex items-center gap-3 px-4 py-3 rounded-xl text-base font-semibold text-gray-800 hover:bg-primary-50 hover:text-primary-700 transition-colors"> <i data-lucide="phone" class="w-5 h-5 mobile-nav-icon"></i> Kontak </a>
            
            <div class="sm:hidden border-t border-gray-200 mt-2 pt-3 flex flex-col gap-2">
                    <a href="{{ route('login') }}" class="flex items-center justify-center gap-2 w-full py-3 rounded-xl border border-gray-300 text-gray-800 font-bold text-sm">
                        <i data-lucide="log-in" class="w-4 h-4"></i> Masuk
                    </a>
                    <a href="{{ route('register') }}" class="flex items-center justify-center gap-2 w-full py-3 rounded-xl bg-primary-600 text-white font-bold text-sm shadow-sm">
                        <i data-lucide="user-plus" class="w-4 h-4"></i> Daftar
                    </a>
            </div>
        </div>

    </div>
</header>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const headerWrapper = document.getElementById('header-wrapper');
        const navbarContainer = document.getElementById('navbar-container');
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileMenuPanel = document.getElementById('mobile-menu-panel');
        
        const navLinks = document.querySelectorAll('.nav-link');
        const mobileNavLinks = document.querySelectorAll('.mobile-nav-link');
        const sections = document.querySelectorAll('section[id], div[id="home"]'); 

        // Hamburger Menu Logic
        mobileMenuButton.addEventListener('click', () => {
            const isHidden = mobileMenuPanel.classList.contains('hidden');
            if (isHidden) {
                mobileMenuPanel.classList.remove('hidden');
                mobileMenuButton.innerHTML = '<i data-lucide="x" class="w-6 h-6"></i>';
            } else {
                mobileMenuPanel.classList.add('hidden');
                mobileMenuButton.innerHTML = '<i data-lucide="menu" class="w-6 h-6"></i>';
            }
            if (typeof lucide !== 'undefined') lucide.createIcons();
        });

        mobileNavLinks.forEach(link => {
            link.addEventListener('click', () => {
                mobileMenuPanel.classList.add('hidden');
                mobileMenuButton.innerHTML = '<i data-lucide="menu" class="w-6 h-6"></i>';
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        });

        // Scroll Transition & Active Link
        const handleScroll = () => {
            if (window.scrollY > 60) {
                navbarContainer.classList.remove('max-w-7xl', 'px-0', 'bg-transparent');
                navbarContainer.classList.add('max-w-5xl', 'bg-white/85', 'backdrop-blur-md', 'rounded-full', 'px-3', 'border', 'border-gray-200');
                headerWrapper.classList.remove('py-4');
                headerWrapper.classList.add('py-2', 'translate-y-2');
            } else {
                navbarContainer.classList.add('max-w-7xl', 'px-0', 'bg-transparent');
                navbarContainer.classList.remove('max-w-5xl', 'bg-white/85', 'backdrop-blur-md', 'rounded-full', 'px-3', 'border', 'border-gray-200');
                headerWrapper.classList.add('py-4');
                headerWrapper.classList.remove('py-2', 'translate-y-2');
            }

            let current = '';
            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                if (window.scrollY >= (sectionTop - 340)) {
                    current = section.getAttribute('id');
                }
            });

            // Sync Desktop
            navLinks.forEach(link => {
                link.classList.remove('text-primary-700', 'bg-primary-50');
                link.classList.add('text-gray-700');
                if (link.getAttribute('href') === `#${current}` || (current === '' && link.getAttribute('href') === '#home')) {
                    link.classList.add('text-primary-700', 'bg-primary-50');
                    link.classList.remove('text-gray-700');
                }
            });

            // Sync Mobile
            mobileNavLinks.forEach(link => {
                link.classList.remove('text-primary-700', 'bg-primary-50');
                link.classList.add('text-gray-800');
                if (link.getAttribute('href') === `#${current}` || (current === '' && link.getAttribute('href') === '#home')) {
                    link.classList.add('text-primary-700', 'bg-primary-50');
                    link.classList.remove('text-gray-800');
                }
            });
        };

        window.addEventListener('scroll', handleScroll);
        // Execute immediately to set initial state correctly
        handleScroll();
        
        if (typeof lucide !== 'undefined') lucide.createIcons();
    });
</script>