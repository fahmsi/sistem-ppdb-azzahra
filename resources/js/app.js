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
