'use strict';

import './bootstrap';
import { createIcons, icons } from 'lucide';

// ===========================
// Initialize Lucide Icons
// ===========================
createIcons({ icons });

// ===========================
// Scroll Reveal Animations
// ===========================
document.addEventListener('DOMContentLoaded', function () {
    const animatedElements = document.querySelectorAll('.fade-up, .fade-left, .fade-right, .stagger-children');

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('show');
                }
            });
        },
        { threshold: 0.15, rootMargin: '0px 0px -50px 0px' }
    );

    animatedElements.forEach((el) => observer.observe(el));
});

// ===========================
// Navbar Mobile Toggle
// ===========================
window.toggleMenu = function () {
    const menu = document.getElementById('mobileMenu');
    const icon = document.getElementById('menuIcon');
    if (menu) {
        menu.classList.toggle('hidden');
        if (icon) {
            const isOpen = !menu.classList.contains('hidden');
            icon.setAttribute('data-lucide', isOpen ? 'x' : 'menu');
            createIcons({ icons });
        }
    }
};

// Close mobile menu on link click
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('#mobileMenu a').forEach((link) => {
        link.addEventListener('click', () => {
            const menu = document.getElementById('mobileMenu');
            if (menu) menu.classList.add('hidden');
        });
    });
});

// ===========================
// Navbar scroll effect
// ===========================
window.addEventListener('scroll', function () {
    const nav = document.getElementById('mainNav');
    if (nav) {
        if (window.scrollY > 50) {
            nav.classList.add('shadow-lg');
            nav.classList.remove('shadow-none');
        } else {
            nav.classList.remove('shadow-lg');
            nav.classList.add('shadow-none');
        }
    }

    // For dashboardNav, it's inside the h-full layout where mainScrollArea scrolls
    const mainScrollArea = document.getElementById('mainScrollArea');
    const dNav = document.getElementById('dashboardNav');
    if (mainScrollArea && dNav) {
        mainScrollArea.addEventListener('scroll', function() {
            if (mainScrollArea.scrollTop > 10) {
                dNav.classList.add('shadow-sm');
                dNav.classList.remove('shadow-none');
            } else {
                dNav.classList.remove('shadow-sm');
                dNav.classList.add('shadow-none');
            }
        });
    }
});

// ===========================
// Active Nav Link Highlight
// ===========================
document.addEventListener('DOMContentLoaded', function () {
    const sections = document.querySelectorAll('section[id]');
    const navLinks = document.querySelectorAll('.nav-link');

    window.addEventListener('scroll', function () {
        let current = '';
        sections.forEach((section) => {
            const sectionTop = section.offsetTop - 100;
            if (window.scrollY >= sectionTop) {
                current = section.getAttribute('id');
            }
        });

        navLinks.forEach((link) => {
            link.classList.remove('text-primary-600', 'font-semibold');
            link.classList.add('text-slate-600');
            if (link.getAttribute('href') === '#' + current) {
                link.classList.add('text-primary-600', 'font-semibold');
                link.classList.remove('text-slate-600');
            }
        });
    });
});

// ===========================
// Curriculum Tabs
// ===========================
window.openTab = function (tab, el) {
    // Hide all tab content
    document.querySelectorAll('.tab-content').forEach((e) => {
        e.classList.add('hidden');
    });

    // Show target tab
    const targetTab = document.getElementById('tab-' + tab);
    if (targetTab) {
        targetTab.classList.remove('hidden');
    }

    // Update button states
    document.querySelectorAll('.tab-btn').forEach((btn) => {
        btn.classList.remove('active');
    });
    el.classList.add('active');

    // Re-initialize icons in newly visible tab
    createIcons({ icons });
};

// Initialize first tab
document.addEventListener('DOMContentLoaded', function () {
    const firstBtn = document.getElementById('btn-umum');
    if (firstBtn) openTab('umum', firstBtn);
});

// ===========================
// FAQ Accordion
// ===========================
window.toggleFAQ = function (el) {
    const parent = el.closest('.faq-item');
    const allItems = document.querySelectorAll('.faq-item');

    allItems.forEach((item) => {
        if (item !== parent) {
            item.classList.remove('open');
        }
    });

    parent.classList.toggle('open');
};

// ===========================
// Testimonial Carousels
// ===========================
window.initCarousel = function (carouselId) {
    const track = document.querySelector(`#${carouselId} .carousel-track`);
    const slides = document.querySelectorAll(`#${carouselId} .carousel-slide`);
    const dotsContainer = document.querySelector(`#${carouselId} .carousel-dots`);
    let currentIndex = 0;
    let autoPlayTimer;

    if (!track || slides.length === 0) return;

    function goToSlide(index) {
        if (index < 0) index = slides.length - 1;
        if (index >= slides.length) index = 0;
        currentIndex = index;
        track.style.transform = `translateX(-${currentIndex * 100}%)`;

        // Update dots
        if (dotsContainer) {
            dotsContainer.querySelectorAll('.carousel-dot').forEach((dot, i) => {
                dot.classList.toggle('bg-primary-600', i === currentIndex);
                dot.classList.toggle('bg-gray-300', i !== currentIndex);
                dot.classList.toggle('w-8', i === currentIndex);
                dot.classList.toggle('w-3', i !== currentIndex);
            });
        }
    }

    // Create dots
    if (dotsContainer) {
        slides.forEach((_, i) => {
            const dot = document.createElement('button');
            dot.className = `carousel-dot h-3 rounded-full transition-all duration-300 ${i === 0 ? 'bg-primary-600 w-8' : 'bg-gray-300 w-3'}`;
            dot.addEventListener('click', () => {
                goToSlide(i);
                resetAutoPlay();
            });
            dotsContainer.appendChild(dot);
        });
    }

    // Prev / Next
    const prevBtn = document.querySelector(`#${carouselId} .carousel-prev`);
    const nextBtn = document.querySelector(`#${carouselId} .carousel-next`);

    if (prevBtn) {
        prevBtn.addEventListener('click', () => {
            goToSlide(currentIndex - 1);
            resetAutoPlay();
        });
    }
    if (nextBtn) {
        nextBtn.addEventListener('click', () => {
            goToSlide(currentIndex + 1);
            resetAutoPlay();
        });
    }

    // Auto-play
    function startAutoPlay() {
        autoPlayTimer = setInterval(() => goToSlide(currentIndex + 1), 5000);
    }
    function resetAutoPlay() {
        clearInterval(autoPlayTimer);
        startAutoPlay();
    }
    startAutoPlay();
};

document.addEventListener('DOMContentLoaded', function () {
    initCarousel('carousel-siswa');
    initCarousel('carousel-ortu');
});

// ===========================
// Parallax Effect
// ===========================
window.addEventListener('scroll', function () {
    const scrolled = window.scrollY;
    document.querySelectorAll('.parallax').forEach((el) => {
        el.style.transform = `translateY(${scrolled * 0.15}px)`;
    });
});

// ===========================
// Re-init Lucide after DOM updates
// ===========================
document.addEventListener('DOMContentLoaded', function () {
    // Small delay to ensure all dynamic content is rendered
    setTimeout(() => createIcons({ icons }), 100);
});

console.log('✅ PSB Az-Zahra Landing Page loaded successfully.');

// ===========================
// PSB Dashboard Layout Scripts
// (Moved from resources/views/layouts/app.blade.php except SweetAlert2)
// ===========================

document.addEventListener('DOMContentLoaded', function () {

    // =============================================
    // 0. Initialize Lucide Icons
    // =============================================
    if (typeof lucide !== 'undefined' && typeof lucide.createIcons === 'function') {
        lucide.createIcons();
    }

    // =============================================
    // 1. Theme Mode Toggle (Light / Dark / System)
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
        const effectiveTheme = mode === 'system' ? getSystemTheme() : mode;

        if (effectiveTheme === 'dark') {
            html.classList.add('dark');
            html.classList.remove('light');
        } else {
            html.classList.add('light');
            html.classList.remove('dark');
        }

        // Update icons in navbar button using fresh DOM references
        const currentThemeIconLight = document.getElementById('themeIconLight');
        const currentThemeIconDark = document.getElementById('themeIconDark');
        const currentThemeIconSystem = document.getElementById('themeIconSystem');

        if (currentThemeIconLight) currentThemeIconLight.classList.toggle('hidden', mode !== 'light');
        if (currentThemeIconDark) currentThemeIconDark.classList.toggle('hidden', mode !== 'dark');
        if (currentThemeIconSystem) currentThemeIconSystem.classList.toggle('hidden', mode !== 'system');

        // Update dropdown active states
        [themeOptLight, themeOptDark, themeOptSystem].forEach((opt) => {
            if (opt) opt.classList.remove('active');
        });
        if (mode === 'light' && themeOptLight) themeOptLight.classList.add('active');
        else if (mode === 'dark' && themeOptDark) themeOptDark.classList.add('active');
        else if (themeOptSystem) themeOptSystem.classList.add('active');

        localStorage.setItem('ppdb_theme', mode);

        // Re-create Lucide icons to apply dark mode colors
        setTimeout(() => {
            if (typeof lucide !== 'undefined' && typeof lucide.createIcons === 'function') {
                lucide.createIcons();
            }
        }, 50);
    }

    if (themeToggleBtn && themeDropdown) {
        themeToggleBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            themeDropdown.classList.toggle('show');
            const um = document.getElementById('userMenu');
            if (um) um.classList.remove('show');
        });
    }

    [themeOptLight, themeOptDark, themeOptSystem].forEach((opt) => {
        if (!opt) return;
        opt.addEventListener('click', () => {
            const mode = opt.getAttribute('data-theme-value');
            applyTheme(mode);
            if (themeDropdown) themeDropdown.classList.remove('show');
        });
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
    // 2. Mobile Sidebar Toggle
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
    // 3. User Dropdown Toggle
    // =============================================
    const userMenuBtn = document.getElementById('userMenuBtn');
    const userMenu = document.getElementById('userMenu');

    if (userMenuBtn && userMenu) {
        userMenuBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            userMenu.classList.toggle('show');
            if (themeDropdown) themeDropdown.classList.remove('show');
        });
    }

    // =============================================
    // 4. Close All Dropdowns on Outside Click
    // =============================================
    document.addEventListener('click', (e) => {
        if (userMenu && userMenuBtn) {
            if (!userMenu.contains(e.target) && !userMenuBtn.contains(e.target)) {
                userMenu.classList.remove('show');
            }
        }

        const themeContainer = document.getElementById('themeContainer');
        if (themeDropdown && themeContainer) {
            if (!themeContainer.contains(e.target)) {
                themeDropdown.classList.remove('show');
            }
        }
    });

    // =============================================
    // 5. Search Modal Logic
    // =============================================
    const searchTrigger = document.getElementById('searchTriggerBtn');
    const searchModal = document.getElementById('searchModal');
    const searchModalContent = document.getElementById('searchModalContent');
    const searchInputModal = document.getElementById('searchInputModal');
    const closeSearchBtn = document.getElementById('closeSearchBtn');

    function openSearch() {
        if(searchModal) searchModal.classList.remove('hidden');
        if(searchInputModal) setTimeout(() => searchInputModal.focus(), 100);
        if (typeof lucide !== 'undefined') { lucide.createIcons(); }
    }

    function closeSearch() {
        if(searchModal) searchModal.classList.add('hidden');
        if(searchInputModal) searchInputModal.value = '';
    }

    if (searchTrigger) searchTrigger.addEventListener('click', openSearch);
    if (closeSearchBtn) closeSearchBtn.addEventListener('click', closeSearch);
    
    if (searchModal && searchModalContent) {
        searchModal.addEventListener('mousedown', (e) => {
            if (!searchModalContent.contains(e.target)) {
                closeSearch();
            }
        });
    }

    document.addEventListener('keydown', function (e) {
        if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
            e.preventDefault();
            openSearch();
        }
        if (e.key === 'Escape' && searchModal && !searchModal.classList.contains('hidden')) {
            closeSearch();
        }
    });

    // =============================================
    // 6. Sidebar Collapsible Logic (Sneat Style)
    // =============================================
    // Gunakan pengecekan elemen agar tidak error di halaman login/register
    if (sidebar) {
        const mainContent = document.getElementById('main-content');
        const toggleBtn = document.getElementById('sidebarToggleBtn');
        const iconWrapper = document.getElementById('iconWrapper');
        const menuTexts = document.querySelectorAll('.menu-text');
        const sidebarStateKey = 'sidebarPinned';

        let isPinned = true;

        function saveSidebarState() {
            try {
                localStorage.setItem(sidebarStateKey, JSON.stringify(isPinned));
            } catch (error) {
                console.warn('Could not save sidebar state:', error);
            }
        }

        function loadSidebarState() {
            try {
                const storedValue = localStorage.getItem(sidebarStateKey);
                if (storedValue === null) {
                    return true;
                }
                return JSON.parse(storedValue);
            } catch (error) {
                console.warn('Could not load sidebar state:', error);
                return true;
            }
        }

        function expandSidebar(isHoveringUnpinned = false) {
            sidebar.classList.remove('w-[80px]');
            sidebar.classList.add('w-[260px]');
            menuTexts.forEach(el => el.classList.remove('opacity-0', 'invisible'));

            if (toggleBtn) {
                toggleBtn.classList.remove('opacity-0', 'invisible', 'scale-50');
                toggleBtn.classList.add('opacity-100', 'visible', 'scale-100');
            }

            if (iconWrapper) {
                if (isHoveringUnpinned) {
                    iconWrapper.classList.add('rotate-180');
                } else {
                    iconWrapper.classList.remove('rotate-180');
                }
            }
        }

        function collapseSidebar() {
            sidebar.classList.remove('w-[260px]');
            sidebar.classList.add('w-[80px]');
            menuTexts.forEach(el => el.classList.add('opacity-0', 'invisible'));

            if (toggleBtn) {
                toggleBtn.classList.remove('opacity-100', 'visible', 'scale-100');
                toggleBtn.classList.add('opacity-0', 'invisible', 'scale-50');
            }

            if (iconWrapper) {
                iconWrapper.classList.add('rotate-180');
            }
        }

        isPinned = loadSidebarState();
        if (!isPinned) {
            collapseSidebar();
            if (mainContent) {
                mainContent.classList.remove('md:ml-[260px]');
                mainContent.classList.add('md:ml-[80px]');
            }
        }

        if (toggleBtn) {
            toggleBtn.addEventListener('click', () => {
                isPinned = !isPinned;
                saveSidebarState();

                if (isPinned) {
                    expandSidebar(false);
                    if (mainContent) {
                        mainContent.classList.remove('md:ml-[80px]');
                        mainContent.classList.add('md:ml-[260px]');
                    }
                } else {
                    collapseSidebar();
                    if (mainContent) {
                        mainContent.classList.remove('md:ml-[260px]');
                        mainContent.classList.add('md:ml-[80px]');
                    }
                }
            });
        }

        sidebar.addEventListener('mouseenter', () => {
            if (!isPinned) expandSidebar(true);
        });

        sidebar.addEventListener('mouseleave', () => {
            if (!isPinned) collapseSidebar();
        });
    }
    

});

// =============================================
// 7. Auto-Logout (30 Minutes Idle)
// =============================================
document.addEventListener('DOMContentLoaded', function () {
    const logoutForm = document.querySelector('form[action*="logout"]');
    
    if (logoutForm) {
        const IDLE_TIMEOUT = 30 * 60 * 1000; // 30 Menit (waktu maksimal)
        const WARNING_TIME = 1 * 60 * 1000;  // 1 Menit (waktu peringatan)
        
        let idleTimer;
        let warningTimer;

        function resetTimer() {
            clearTimeout(idleTimer);
            clearTimeout(warningTimer);

            warningTimer = setTimeout(() => {
                Swal.fire({
                    title: 'Sesi Hampir Habis!',
                    text: 'Anda sudah tidak aktif cukup lama. Sistem akan otomatis logout dalam 1 menit untuk keamanan.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Tetap Login',
                    cancelButtonText: 'Logout Sekarang',
                    timer: WARNING_TIME,
                    timerProgressBar: true,
                    allowOutsideClick: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        resetTimer(); 
                        // Ping server to keep session alive
                        fetch(window.location.href, { method: 'HEAD' });
                    } else if (result.dismiss === Swal.DismissReason.timer || result.dismiss === Swal.DismissReason.cancel) {
                        executeLogout(); 
                    }
                });
            }, IDLE_TIMEOUT - WARNING_TIME);

            idleTimer = setTimeout(executeLogout, IDLE_TIMEOUT);
        }

        function executeLogout() {
            logoutForm.submit();
        }

        const events = ['mousemove', 'keydown', 'scroll', 'click'];
        
        events.forEach(event => {
            window.addEventListener(event, () => {
                if (!Swal.isVisible()) {
                    resetTimer();
                }
            }, { passive: true }); // passive: true for better performance
        });

        resetTimer();
    }
});