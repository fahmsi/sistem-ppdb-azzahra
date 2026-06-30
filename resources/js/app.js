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
// Curriculum Tabs
// ===========================
window.openTab = function (tab, el) {
    const section = el?.closest('#kurikulum');
    if (!section) return;

    const isLearningJourney = el.hasAttribute('data-curriculum-tab');
    const buttons = [...section.querySelectorAll(isLearningJourney ? '[data-curriculum-tab]' : '.tab-btn')];
    const panels = [...section.querySelectorAll(isLearningJourney ? '.curriculum-tab-panel' : '.tab-content')];
    const targetTab = section.querySelector('#tab-' + tab);

    panels.forEach((panel) => {
        panel.classList.add('hidden');
        if (isLearningJourney) panel.classList.remove('is-active');
        panel.setAttribute('aria-hidden', 'true');
    });

    if (targetTab) {
        targetTab.classList.remove('hidden');
        targetTab.setAttribute('aria-hidden', 'false');
        if (isLearningJourney) {
            window.requestAnimationFrame(() => targetTab.classList.add('is-active'));
        }
    }

    buttons.forEach((button) => {
        const isActive = button === el;
        button.classList.toggle('active', isActive);
        button.setAttribute('aria-selected', String(isActive));
        button.tabIndex = isActive ? 0 : -1;
    });

    createIcons({ icons });
};

document.addEventListener('DOMContentLoaded', function () {
    const tabs = [...document.querySelectorAll('[data-curriculum-tab]')];
    if (tabs.length === 0) {
        const legacyFirstTab = document.getElementById('btn-umum');
        if (legacyFirstTab) openTab('umum', legacyFirstTab);
        return;
    }

    tabs.forEach((tab, index) => {
        tab.addEventListener('click', () => openTab(tab.dataset.curriculumTab, tab));
        tab.addEventListener('keydown', (event) => {
            let nextIndex = null;

            if (event.key === 'ArrowRight') nextIndex = (index + 1) % tabs.length;
            if (event.key === 'ArrowLeft') nextIndex = (index - 1 + tabs.length) % tabs.length;
            if (event.key === 'Home') nextIndex = 0;
            if (event.key === 'End') nextIndex = tabs.length - 1;

            if (nextIndex !== null) {
                event.preventDefault();
                tabs[nextIndex].focus();
                openTab(tabs[nextIndex].dataset.curriculumTab, tabs[nextIndex]);
            }
        });
    });

    openTab(tabs[0].dataset.curriculumTab, tabs[0]);
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

console.log('SPMB Az-Zahra Landing Page loaded successfully.');

// ===========================
// SPMB Dashboard Layout Scripts
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

        localStorage.setItem('spmb_theme', mode);

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
    const savedTheme = localStorage.getItem('spmb_theme') || 'light';
    applyTheme(savedTheme);

    // Listen for system theme changes
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
        if (localStorage.getItem('spmb_theme') === 'system') {
            applyTheme('system');
        }
    });

    // =============================================
    // 2. Mobile Sidebar Toggle
    // =============================================
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const mobileSidebarCloseBtn = document.getElementById('mobileSidebarCloseBtn');
    const sidebar = document.getElementById('sidebar');
    const sidebarOverlay = document.getElementById('sidebarOverlay');

    if (mobileMenuBtn && sidebar && sidebarOverlay) {
        let mobileSidebarOpen = false;

        const setMobileSidebar = (open) => {
            mobileSidebarOpen = open && window.innerWidth < 768;
            sidebar.classList.toggle('mobile-open', mobileSidebarOpen);
            sidebarOverlay.classList.toggle('hidden', !mobileSidebarOpen);
            document.body.classList.toggle('sidebar-mobile-open', mobileSidebarOpen);
            mobileMenuBtn.setAttribute('aria-expanded', String(mobileSidebarOpen));
            mobileMenuBtn.setAttribute('aria-label', mobileSidebarOpen ? 'Tutup menu navigasi' : 'Buka menu navigasi');

            window.requestAnimationFrame(() => {
                sidebarOverlay.classList.toggle('opacity-0', !mobileSidebarOpen);
            });
        };

        mobileMenuBtn.addEventListener('click', () => setMobileSidebar(!mobileSidebarOpen));
        mobileSidebarCloseBtn?.addEventListener('click', () => {
            setMobileSidebar(false);
            mobileMenuBtn.focus({ preventScroll: true });
        });
        sidebarOverlay.addEventListener('click', () => setMobileSidebar(false));

        sidebar.querySelectorAll('.sidebar-menu-link').forEach((link) => {
            link.addEventListener('click', () => setMobileSidebar(false));
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && mobileSidebarOpen) {
                setMobileSidebar(false);
                mobileMenuBtn.focus({ preventScroll: true });
            }
        });

        window.addEventListener('resize', () => {
            if (window.innerWidth >= 768 && mobileSidebarOpen) {
                setMobileSidebar(false);
            }
        });
    }

    // =============================================
    // 3. User Dropdown & Notification Dropdown Toggle
    // =============================================
    const userMenuBtn = document.getElementById('userMenuBtn');
    const userMenu = document.getElementById('userMenu');
    const notifToggleBtn = document.getElementById('notifToggleBtn');
    const notifDropdown = document.getElementById('notifDropdown');

    if (userMenuBtn && userMenu) {
        userMenuBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            userMenu.classList.toggle('show');
            if (themeDropdown) themeDropdown.classList.remove('show');
            if (notifDropdown) notifDropdown.classList.add('hidden');
        });
    }

    if (notifToggleBtn && notifDropdown) {
        notifToggleBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            notifDropdown.classList.toggle('hidden');
            if (themeDropdown) themeDropdown.classList.remove('show');
            if (userMenu) userMenu.classList.remove('show');
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
        
        if (notifDropdown && notifToggleBtn) {
            if (!notifDropdown.contains(e.target) && !notifToggleBtn.contains(e.target)) {
                notifDropdown.classList.add('hidden');
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
    
    
    // =============================================
    // 7. Confirm delete siswa dengan SweetAlert2
    // =============================================
    document.querySelectorAll('.child-delete-form').forEach((form) => {
        form.addEventListener('submit', function (event) {
            event.preventDefault();

            const childName = this.dataset.childName || '';

            Swal.fire({
                title: 'Hapus Data Anak?',
                html: `Untuk melanjutkan, ketik nama lengkap anak: <strong>${childName}</strong>`,
                icon: 'warning',
                input: 'text',
                inputPlaceholder: 'Nama lengkap anak',
                inputAutoTrim: true,
                showCancelButton: true,
                confirmButtonText: 'Hapus Sekarang',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#697a8d',
                allowOutsideClick: false,
                inputValidator: (value) => {
                    if (!value) {
                        return 'Nama lengkap anak harus diisi.';
                    }
                    if (value.trim() !== childName) {
                        return 'Nama tidak cocok. Ketik persis nama lengkap anak.';
                    }
                    return null;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        });
    });

    document.querySelectorAll('.registration-confirm-form').forEach((form) => {
        form.addEventListener('submit', function (event) {
            event.preventDefault();

            const gelombang = this.dataset.gelombang || 'gelombang ini';

            Swal.fire({
                title: 'Konfirmasi Pendaftaran',
                text: `Apakah Anda yakin ingin mendaftar di ${gelombang}?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Daftar',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#2563eb',
                cancelButtonColor: '#6b7280',
                allowOutsideClick: false,
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        });
    });

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

// =============================================
// 8. Testimonial Infinite Carousel Logic
// =============================================
document.addEventListener('DOMContentLoaded', function () {
    const carousel = document.getElementById('testiCarousel');
    if (!carousel) return;

    const track = document.getElementById('testiTrack');
    const dots = document.querySelectorAll('#testiDots .testi-dot');
    const total = parseInt(carousel.dataset.total, 10);
    const interval = parseInt(carousel.dataset.interval, 10) || 4000;

    if (total <= 1) return;

    // Clone first slide and append to the end for infinite loop
    const firstSlideClone = track.children[0].cloneNode(true);
    firstSlideClone.setAttribute('aria-hidden', 'true');
    track.appendChild(firstSlideClone);

    let current = 0;
    let timer = null;
    let isTransitioning = false;
    const transitionCSS = 'transform 0.7s cubic-bezier(0.4, 0, 0.2, 1)';

    // Set initial track state
    track.style.transition = transitionCSS;

    function updateDots() {
        dots.forEach(function (dot, i) {
            dot.classList.toggle('active', i === (current % total));
        });
    }

    function goToSlide(index, animate = true) {
        if (isTransitioning && animate) return;
        
        current = index;
        if (animate) {
            track.style.transition = transitionCSS;
            isTransitioning = true;
        } else {
            track.style.transition = 'none';
        }
        
        track.style.transform = 'translateX(-' + (current * 100) + '%)';
        updateDots();
    }

    // Handle end of transition for infinite loop
    track.addEventListener('transitionend', function () {
        isTransitioning = false;
        if (current === total) {
            // Reached the clone at the end, instantly snap back to real first slide
            goToSlide(0, false);
        }
    });

    function nextSlide() {
        if (isTransitioning) return;
        goToSlide(current + 1);
    }

    function prevSlide() {
        if (isTransitioning) return;
        if (current === 0) {
            // Snap to clone first, then transition to actual previous
            goToSlide(total, false);
            // Force reflow to apply the snap instantly
            void track.offsetWidth;
            goToSlide(total - 1, true);
        } else {
            goToSlide(current - 1);
        }
    }

    function startAutoPlay() {
        stopAutoPlay();
        timer = setInterval(nextSlide, interval);
    }

    function stopAutoPlay() {
        if (timer) {
            clearInterval(timer);
            timer = null;
        }
    }

    // Dot click
    dots.forEach(function (dot) {
        dot.addEventListener('click', function () {
            if (isTransitioning) return;
            const target = parseInt(this.dataset.slide, 10);
            goToSlide(target);
            startAutoPlay(); // reset timer
        });
    });

    // Prev / Next Button click
    const prevBtn = document.getElementById('testiPrev');
    const nextBtn = document.getElementById('testiNext');
    const prevBtnMobile = document.getElementById('testiPrevMobile');
    const nextBtnMobile = document.getElementById('testiNextMobile');

    if (prevBtn) {
        prevBtn.addEventListener('click', function () {
            prevSlide();
            startAutoPlay();
        });
    }
    if (nextBtn) {
        nextBtn.addEventListener('click', function () {
            nextSlide();
            startAutoPlay();
        });
    }
    if (prevBtnMobile) {
        prevBtnMobile.addEventListener('click', function () {
            prevSlide();
            startAutoPlay();
        });
    }
    if (nextBtnMobile) {
        nextBtnMobile.addEventListener('click', function () {
            nextSlide();
            startAutoPlay();
        });
    }

    // Pause on hover
    carousel.addEventListener('mouseenter', stopAutoPlay);
    carousel.addEventListener('mouseleave', startAutoPlay);

    // Touch/swipe support
    let touchStartX = 0;
    let touchEndX = 0;

    carousel.addEventListener('touchstart', function (e) {
        touchStartX = e.changedTouches[0].screenX;
        stopAutoPlay();
    }, { passive: true });

    carousel.addEventListener('touchend', function (e) {
        touchEndX = e.changedTouches[0].screenX;
        const diff = touchStartX - touchEndX;
        if (Math.abs(diff) > 50) {
            if (diff > 0) {
                // Swipe left → next
                nextSlide();
            } else {
                // Swipe right → prev
                prevSlide();
            }
        }
        startAutoPlay();
    }, { passive: true });

    // Start
    startAutoPlay();
});

// =============================================
// 9. Gallery Carousel + Lightbox
// =============================================
(function () {
    var track = document.getElementById('galleryTrack');
    if (!track) return;

    var slides = track.querySelectorAll('.gallery-carousel-slide');
    var progressBar = document.getElementById('galleryProgressBar');
    var totalSlides = slides.length;
    var currentPos = 0;
    var autoTimer = null;
    var isDragging = false;
    var startX = 0;
    var scrollLeft = 0;

    function getVisibleCount() {
        var w = window.innerWidth;
        if (w >= 1024) return 4;
        if (w >= 640) return 3;
        return 2;
    }

    function getMaxPos() {
        return Math.max(0, totalSlides - getVisibleCount());
    }

    function updateCarousel() {
        var visible = getVisibleCount();
        var gap = 16;
        var slideWidth = (track.parentElement.offsetWidth - gap * (visible - 1)) / visible;
        var offset = currentPos * (slideWidth + gap);
        track.style.transform = 'translateX(-' + offset + 'px)';

        // Update progress
        var maxPos = getMaxPos();
        if (progressBar) {
            var progress = maxPos > 0 ? ((currentPos / maxPos) * 100) : 100;
            progressBar.style.width = progress + '%';
        }
    }

    window.galleryCarousel = {
        next: function () {
            currentPos = currentPos >= getMaxPos() ? 0 : currentPos + 1;
            updateCarousel();
            resetAuto();
        },
        prev: function () {
            currentPos = currentPos <= 0 ? getMaxPos() : currentPos - 1;
            updateCarousel();
            resetAuto();
        }
    };

    // Autoplay
    function startAuto() {
        autoTimer = setInterval(function () {
            currentPos = currentPos >= getMaxPos() ? 0 : currentPos + 1;
            updateCarousel();
        }, 4000);
    }

    function resetAuto() {
        clearInterval(autoTimer);
        startAuto();
    }

    // Drag / Swipe support
    track.addEventListener('mousedown', function (e) {
        isDragging = true;
        startX = e.pageX;
        track.classList.add('dragging');
    });

    track.addEventListener('mousemove', function (e) {
        if (!isDragging) return;
        e.preventDefault();
    });

    track.addEventListener('mouseup', function (e) {
        if (!isDragging) return;
        isDragging = false;
        track.classList.remove('dragging');
        var diff = e.pageX - startX;
        if (diff < -40) galleryCarousel.next();
        else if (diff > 40) galleryCarousel.prev();
    });

    track.addEventListener('mouseleave', function () {
        if (isDragging) {
            isDragging = false;
            track.classList.remove('dragging');
        }
    });

    // Touch support
    track.addEventListener('touchstart', function (e) {
        startX = e.touches[0].clientX;
    }, { passive: true });

    track.addEventListener('touchend', function (e) {
        var diff = e.changedTouches[0].clientX - startX;
        if (diff < -40) galleryCarousel.next();
        else if (diff > 40) galleryCarousel.prev();
    });

    // Pause on hover
    var wrapper = track.closest('.gallery-carousel-wrapper');
    if (wrapper) {
        wrapper.addEventListener('mouseenter', function () { clearInterval(autoTimer); });
        wrapper.addEventListener('mouseleave', function () { startAuto(); });
    }

    // Resize handler
    window.addEventListener('resize', function () {
        if (currentPos > getMaxPos()) currentPos = getMaxPos();
        updateCarousel();
    });

    // Init
    updateCarousel();
    startAuto();

    // ---- Lightbox ----
    var currentGalleryIndex = 0;

    window.openGalleryLightbox = function (index) {
        if (typeof galleryData === 'undefined' || !galleryData.length) return;

        currentGalleryIndex = index;
        updateLightboxContent();

        var lightbox = document.getElementById('galleryLightbox');
        if (lightbox) {
            lightbox.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        if (typeof lucide !== 'undefined') {
            setTimeout(function () { lucide.createIcons(); }, 50);
        }
    };

    window.closeGalleryLightbox = function (event) {
        if (event) event.stopPropagation();
        var lightbox = document.getElementById('galleryLightbox');
        if (lightbox) {
            lightbox.classList.remove('active');
            document.body.style.overflow = '';
        }
    };

    window.navigateGallery = function (direction, event) {
        if (event) event.stopPropagation();
        if (typeof galleryData === 'undefined' || !galleryData.length) return;
        currentGalleryIndex = (currentGalleryIndex + direction + galleryData.length) % galleryData.length;
        updateLightboxContent();
    };

    function updateLightboxContent() {
        if (typeof galleryData === 'undefined' || !galleryData.length) return;
        var item = galleryData[currentGalleryIndex];
        var img = document.getElementById('lightboxImage');
        var title = document.getElementById('lightboxTitle');
        var desc = document.getElementById('lightboxDesc');
        var counter = document.getElementById('lightboxCounter');

        if (img) { img.src = item.url; img.alt = item.title; }
        if (title) title.textContent = item.title;
        if (desc) {
            desc.textContent = item.desc;
            desc.style.display = item.desc ? '' : 'none';
        }
        if (counter) counter.textContent = (currentGalleryIndex + 1) + ' / ' + galleryData.length;
    }

    document.addEventListener('keydown', function (e) {
        var lightbox = document.getElementById('galleryLightbox');
        if (!lightbox || !lightbox.classList.contains('active')) return;
        if (e.key === 'Escape') closeGalleryLightbox();
        if (e.key === 'ArrowRight') navigateGallery(1);
        if (e.key === 'ArrowLeft') navigateGallery(-1);
    });
})();
