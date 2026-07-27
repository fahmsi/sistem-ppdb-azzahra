{{-- Hero Section: Inspired by Modern Educational Reference --}}
<section id="home" class="relative overflow-hidden pt-24 pb-20 lg:pt-28 lg:pb-28">
    {{-- Decorative Background Glows --}}
    <div
        class="pointer-events-none absolute -right-32 top-16 h-[28rem] w-[28rem] rounded-full bg-primary-200/30 blur-3xl">
    </div>
    <div
        class="pointer-events-none absolute -left-40 top-[32rem] h-[32rem] w-[32rem] rounded-full bg-secondary-200/25 blur-3xl">
    </div>

    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

        {{-- Main Hero Canvas (Inspired by Reference Banner Layout) --}}
        <div
            class="relative overflow-hidden rounded-[2.5rem] bg-gradient-to-br from-primary-900 via-primary-800 to-secondary-900 p-8 sm:p-12 lg:p-14 text-white shadow-2xl shadow-primary-900/20 border border-white/10 mb-10 fade-up">

            {{-- Decorative Light Orbs & Pattern --}}
            <div class="pointer-events-none absolute -right-20 -top-20 h-96 w-96 rounded-full bg-secondary-400/20 blur-3xl"
                aria-hidden="true"></div>
            <div class="pointer-events-none absolute -left-20 -bottom-20 h-96 w-96 rounded-full bg-primary-400/25 blur-3xl"
                aria-hidden="true"></div>
            <div class="pointer-events-none absolute inset-0 opacity-[0.05] islamic-pattern" aria-hidden="true"></div>

            <div class="relative z-10 grid items-center gap-8 lg:grid-cols-12 xl:gap-12">

                {{-- Left Text & Action Column (7 cols) --}}
                <div class="lg:col-span-7 text-center lg:text-left">

                    {{-- Badges --}}
                    <div class="mb-5 flex flex-wrap items-center justify-center lg:justify-start gap-3">
                        <span
                            class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-4 py-1.5 text-xs font-semibold text-white shadow-sm backdrop-blur-md sm:text-sm">
                            <span class="relative flex h-2.5 w-2.5">
                                <span
                                    class="absolute inline-flex h-full w-full animate-ping rounded-full bg-secondary-400 opacity-60"></span>
                                <span class="relative inline-flex h-2.5 w-2.5 rounded-full bg-secondary-400"></span>
                            </span>
                            {{ $settings['hero_badge_text'] ?? 'Pendaftaran Dibuka 2026/2027' }}
                        </span>
                        <span
                            class="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-[0.18em] text-primary-200">
                            <i data-lucide="map-pin" class="h-3.5 w-3.5 text-secondary-300" aria-hidden="true"></i>
                            Depok, Jawa Barat
                        </span>
                    </div>

                    {{-- Headline (EXACT COPYWRITING & FONT) --}}
                    <h1
                        class="hero-display-font max-w-3xl text-4xl leading-[1.04] text-white sm:text-5xl lg:text-5xl xl:text-[3.75rem]">
                        Wujudkan Masa Depan
                        <span
                            class="text-transparent bg-clip-text bg-gradient-to-r from-secondary-200 via-emerald-300 to-teal-200 block pb-1">Cemerlang
                            Sejak Usia Dini</span>
                    </h1>

                    {{-- Sub-paragraph (EXACT COPYWRITING) --}}
                    <div
                        class="mt-5 max-w-xl border-l-2 border-secondary-400/60 pl-4 text-sm leading-6 text-primary-100 text-justify sm:text-base sm:leading-7 lg:mt-6">
                        <p>
                            <strong class="font-bold text-white">PAUD AL QUR'AN AZZAHRA DEPOK</strong>
                            menghadirkan pendidikan yang memadukan
                            <span class="font-semibold text-secondary-200">ilmu umum</span> dan
                            <span class="font-semibold text-emerald-300">nilai-nilai Islam</span>
                            untuk membentuk generasi Qur'ani yang cerdas dan berakhlak mulia.
                        </p>
                    </div>

                    {{-- CTA Buttons (EXACT CTAs) --}}
                    <div class="mt-8 flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4">
                        <a href="{{ route('register') }}"
                            class="group inline-flex min-w-0 transform-gpu items-center justify-center gap-3 rounded-2xl bg-secondary-500 hover:bg-secondary-600 px-6 py-3.5 text-sm font-bold text-white shadow-xl shadow-secondary-500/30 transition-all duration-200 hover:scale-[1.02] w-full sm:w-auto">
                            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-white/20">
                                <i data-lucide="user-plus" class="h-4 w-4 text-white" aria-hidden="true"></i>
                            </span>
                            <span class="min-w-0 text-left leading-tight">
                                <span class="block text-[10px] font-medium text-secondary-100">Belum punya akun?</span>
                                <span class="block truncate text-sm">Buat Akun</span>
                            </span>
                            <i data-lucide="arrow-up-right"
                                class="h-4 w-4 shrink-0 transition-transform group-hover:translate-x-0.5 group-hover:-translate-y-0.5"
                                aria-hidden="true"></i>
                        </a>

                        <a href="{{ route('login') }}"
                            class="group inline-flex min-w-0 transform-gpu items-center justify-center gap-3 rounded-2xl border border-white/20 bg-white/10 hover:bg-white/20 px-6 py-3.5 text-sm font-bold text-white backdrop-blur-md shadow-sm transition-all duration-200 w-full sm:w-auto">
                            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-white/10">
                                <i data-lucide="log-in" class="h-4 w-4 text-white" aria-hidden="true"></i>
                            </span>
                            <span class="min-w-0 text-left leading-tight">
                                <span class="block text-[10px] font-medium text-primary-200">Sudah punya akun?</span>
                                <span class="block truncate text-sm">Dashboard SPMB</span>
                            </span>
                        </a>
                    </div>

                </div>

                {{-- Right Column: Clean Main Image Frame (5 cols, WITHOUT FLOATING CARDS) --}}
                <div class="lg:col-span-5 relative flex justify-center">
                    <div
                        class="relative w-full max-w-md rounded-3xl border-4 border-white/20 bg-white/10 p-2.5 backdrop-blur-md shadow-2xl overflow-hidden group">
                        <img src="{{ asset('images/banner.png') }}"
                            alt="Ilustrasi siswa PAUD Al Qur'an Az-Zahra menyambut SPMB 2026/2027"
                            class="w-full h-72 sm:h-96 lg:h-[420px] object-cover object-center rounded-2xl transition-transform duration-700 transform-gpu will-change-transform group-hover:scale-105"
                            loading="eager">
                        <div
                            class="absolute inset-0 bg-gradient-to-t from-primary-950/40 via-transparent to-transparent pointer-events-none">
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- 3-Column Feature Cards Row (Inspired by reference 3-card block) --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-16 fade-up">

            {{-- Card 1 --}}
            <div
                class="hover-card bg-white rounded-3xl p-6 border border-gray-100 shadow-xl shadow-gray-100/50 flex items-center gap-4 group">
                <div
                    class="w-14 h-14 rounded-2xl bg-primary-50 text-primary-600 flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                    <i data-lucide="users" class="w-7 h-7"></i>
                </div>
                <div>
                    <h3 class="font-heading text-2xl font-extrabold text-primary-700">200+</h3>
                    <p class="text-xs font-bold text-gray-800 uppercase tracking-wider">Alumni</p>
                    <p class="text-[11px] text-gray-500 mt-0.5">Mendampingi ratusan anak tumbuh cemerlang</p>
                </div>
            </div>

            {{-- Card 2 --}}
            <div
                class="hover-card bg-white rounded-3xl p-6 border border-gray-100 shadow-xl shadow-gray-100/50 flex items-center gap-4 group">
                <div
                    class="w-14 h-14 rounded-2xl bg-secondary-50 text-secondary-600 flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                    <i data-lucide="award" class="w-7 h-7"></i>
                </div>
                <div>
                    <h3 class="font-heading text-xl font-extrabold text-secondary-700">BAN-PAUD</h3>
                    <p class="text-xs font-bold text-gray-800 uppercase tracking-wider">Terakreditasi</p>
                    <p class="text-[11px] text-gray-500 mt-0.5">Kualitas pendidikan terjamin &amp; resmi</p>
                </div>
            </div>

            {{-- Card 3 --}}
            <div
                class="hover-card bg-white rounded-3xl p-6 border border-gray-100 shadow-xl shadow-gray-100/50 flex items-center gap-4 group">
                <div
                    class="w-14 h-14 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                    <i data-lucide="calendar" class="w-7 h-7"></i>
                </div>
                <div>
                    <h3 class="font-heading text-2xl font-extrabold text-amber-700">19</h3>
                    <p class="text-xs font-bold text-gray-800 uppercase tracking-wider">Tahun Berdiri</p>
                    <p class="text-[11px] text-gray-500 mt-0.5">Terpercaya membimbing sejak 2007</p>
                </div>
            </div>

        </div>

        {{-- Video Profil Section (EXACT CONTENT PRESERVED) --}}
        <div class="fade-up">
            <div
                class="overflow-hidden rounded-[2.5rem] border border-gray-100 bg-white shadow-2xl shadow-primary-900/10">
                <div class="grid lg:grid-cols-12">
                    <div class="flex flex-col justify-center p-6 sm:p-10 lg:col-span-4 lg:p-12">
                        <span
                            class="mb-5 inline-flex w-fit self-start items-center gap-2 whitespace-nowrap rounded-full border border-primary-200 bg-primary-50 px-4 py-2 text-sm font-semibold text-primary-700">
                            <i data-lucide="circle-play" class="h-4 w-4" aria-hidden="true"></i>
                            Video Profil
                        </span>
                        <h2 class="section-heading section-heading-left mt-3 font-heading text-gray-900">
                            <span>Profil <span class="gradient-text">Sekolah Kami</span></span>
                        </h2>
                        <p class="mt-4 leading-7 text-gray-600">Kenali lebih dekat lingkungan, kegiatan, dan pengalaman
                            belajar di PAUD Al Qur'an Azzahra.</p>

                        <div class="mt-7 flex items-center gap-3 border-t border-gray-100 pt-6">
                            <span
                                class="flex h-9 w-9 items-center justify-center rounded-lg bg-secondary-50 text-secondary-600">
                                <i data-lucide="heart" class="h-4 w-4" aria-hidden="true"></i>
                            </span>
                            <p class="text-sm font-semibold text-gray-700">Tumbuh bersama nilai Qur'ani</p>
                        </div>
                    </div>

                    <div class="relative min-h-[260px] bg-gray-100 sm:min-h-[420px] lg:col-span-8 lg:min-h-[480px]">
                        <video class="absolute inset-0 h-full w-full object-cover" controls autoplay loop muted
                            playsinline preload="metadata">
                            <source src="{{ asset('videos/profil-paud.mp4') }}" type="video/mp4">
                            Maaf, browser Anda tidak mendukung pemutaran video.
                        </video>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- Bottom Wave --}}
    <div class="landing-hero-wave pointer-events-none absolute inset-x-0 bottom-0">
        <svg viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <path
                d="M0 120L60 110C120 100 240 80 360 70C480 60 600 60 720 65C840 70 960 80 1080 85C1200 90 1320 90 1380 90L1440 90V120H1380C1320 120 1200 120 1080 120C600 120 480 120 360 120C240 120 120 120 60 120H0Z"
                fill="white" fill-opacity="0.5" />
        </svg>
    </div>
</section>