{{-- Hero: Editorial Enrollment --}}
<section id="home" class="islamic-pattern relative overflow-hidden bg-gradient-to-br from-blue-50 via-white to-green-50 pb-28 lg:pb-36">
    {{-- Decorative background --}}
    <div class="pointer-events-none absolute -right-32 top-16 h-[28rem] w-[28rem] rounded-full bg-primary-200/30 blur-3xl"></div>
    <div class="pointer-events-none absolute -left-40 top-[32rem] h-[32rem] w-[32rem] rounded-full bg-secondary-200/25 blur-3xl"></div>

    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid min-h-[100svh] items-center gap-6 pb-8 pt-24 sm:gap-8 sm:pb-10 sm:pt-28 lg:grid-cols-12 lg:gap-10 lg:pb-8 lg:pt-24 xl:gap-14">
            {{-- Editorial content --}}
            <div class="fade-up lg:col-span-6 xl:col-span-6">
                <div class="mb-4 flex flex-wrap items-center gap-3 lg:mb-5">
                    <span class="inline-flex items-center gap-2 rounded-full border border-primary-200 bg-white/80 px-3.5 py-1.5 text-xs font-semibold text-primary-700 shadow-sm backdrop-blur-sm sm:text-sm">
                        <span class="relative flex h-2.5 w-2.5">
                            <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-secondary-400 opacity-60"></span>
                            <span class="relative inline-flex h-2.5 w-2.5 rounded-full bg-secondary-500"></span>
                        </span>
                        {{ $settings['hero_badge_text'] ?? 'Pendaftaran Dibuka 2026/2027' }}
                    </span>
                    <span class="hidden items-center gap-1.5 text-xs font-bold uppercase tracking-[0.18em] text-gray-500 sm:inline-flex">
                        <i data-lucide="map-pin" class="h-3.5 w-3.5 text-secondary-600" aria-hidden="true"></i>
                        Depok, Jawa Barat
                    </span>
                </div>

                <h1 class="hero-display-font max-w-3xl text-4xl leading-[1.04] text-gray-900 sm:text-5xl lg:text-5xl xl:text-[3.75rem]">
                    Wujudkan Masa Depan
                    <span class="gradient-text block pb-1">Cemerlang Sejak Usia Dini</span>
                </h1>

                <div class="mt-4 max-w-xl border-l-2 border-primary-200 pl-4 text-sm leading-6 text-gray-600 sm:text-base sm:leading-7 lg:mt-5">
                    <p>
                        <strong class="font-bold text-gray-900">PAUD AL QUR'AN AZZAHRA DEPOK</strong>
                        menghadirkan pendidikan yang memadukan
                        <span class="font-semibold text-primary-600">ilmu umum</span> dan
                        <span class="font-semibold text-secondary-600">nilai-nilai Islam</span>
                        untuk membentuk generasi Qur'ani yang cerdas dan berakhlak mulia.
                    </p>
                </div>

                {{-- Existing CTAs, refreshed --}}
                <div class="mt-5 grid grid-cols-2 gap-2.5 sm:flex sm:flex-wrap lg:mt-6">
                    <a href="{{ route('register') }}" class="group inline-flex min-w-0 items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-primary-600 to-primary-500 px-3 py-3 text-sm font-bold text-white shadow-lg shadow-primary-600/20 transition-all duration-300 hover:-translate-y-1 hover:from-primary-700 hover:to-primary-600 hover:shadow-xl focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 focus-visible:ring-offset-2 sm:gap-3 sm:px-5 sm:text-base">
                        <span class="hidden h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-white/15 sm:flex">
                            <i data-lucide="user-plus" class="h-5 w-5" aria-hidden="true"></i>
                        </span>
                        <span class="min-w-0 text-center leading-tight sm:text-left">
                            <span class="hidden text-[11px] font-medium text-primary-100 sm:block">Belum punya akun?</span>
                            <span class="block truncate">Buat Akun</span>
                        </span>
                        <i data-lucide="arrow-up-right" class="hidden h-4 w-4 shrink-0 transition-transform group-hover:translate-x-0.5 group-hover:-translate-y-0.5 sm:block" aria-hidden="true"></i>
                    </a>

                    <a href="{{ route('login') }}" class="group inline-flex min-w-0 items-center justify-center gap-2 rounded-xl border border-primary-200 bg-white/80 px-3 py-3 text-sm font-bold text-primary-700 shadow-sm backdrop-blur-sm transition-all duration-300 hover:-translate-y-1 hover:border-primary-300 hover:bg-primary-50 hover:shadow-lg focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 focus-visible:ring-offset-2 sm:gap-3 sm:px-5 sm:text-base">
                        <span class="hidden h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-primary-50 sm:flex">
                            <i data-lucide="log-in" class="h-5 w-5" aria-hidden="true"></i>
                        </span>
                        <span class="min-w-0 text-center leading-tight sm:text-left">
                            <span class="hidden text-[11px] font-medium text-gray-500 sm:block">Sudah punya akun?</span>
                            <span class="block truncate">Dashboard SPMB</span>
                        </span>
                    </a>
                </div>

                {{-- Existing stats in one visual panel --}}
                <div class="mt-5 grid grid-cols-3 divide-x divide-gray-200 rounded-2xl border border-white/80 bg-white/70 px-2 py-3 shadow-lg shadow-primary-900/5 backdrop-blur-md sm:max-w-xl sm:px-4 lg:mt-6">
                    <div class="min-w-0 px-2 sm:px-4">
                        <p class="font-heading text-lg font-extrabold text-primary-700 sm:text-xl">200+</p>
                        <p class="mt-1 text-[11px] font-medium text-gray-500 sm:text-xs">Alumni</p>
                    </div>
                    <div class="min-w-0 px-2 sm:px-4">
                        <p class="truncate font-heading text-sm font-extrabold text-secondary-700 sm:text-lg">BAN-PAUD</p>
                        <p class="mt-1 text-[11px] font-medium text-gray-500 sm:text-xs">Terakreditasi</p>
                    </div>
                    <div class="min-w-0 px-2 sm:px-4">
                        <p class="font-heading text-lg font-extrabold text-primary-700 sm:text-xl">19</p>
                        <p class="mt-1 text-[11px] font-medium text-gray-500 sm:text-xs">Tahun Berdiri</p>
                    </div>
                </div>
            </div>

            {{-- Existing main image, editorial framing --}}
            <div class="fade-right relative mx-auto w-full max-w-xl lg:col-span-6 lg:max-w-none xl:col-span-6">
                <div class="relative px-2 pt-2 sm:pl-6 sm:pr-0 sm:pt-5">
                    <div class="absolute inset-x-8 bottom-3 top-12 -rotate-3 rounded-[2.25rem] bg-gradient-to-br from-primary-200 to-secondary-200"></div>

                    <div class="group relative h-[210px] overflow-hidden rounded-[1.5rem] border-4 border-white bg-gray-100 shadow-2xl shadow-primary-900/15 sm:h-[340px] sm:rounded-[2rem] lg:h-[min(62vh,520px)] lg:min-h-[400px]">
                        <img
                            src="{{ asset('images/banner.png') }}"
                            alt="Ilustrasi siswa PAUD Al Qur'an Az-Zahra menyambut SPMB 2026/2027"
                            class="h-full w-full object-cover object-center transition-transform duration-700 group-hover:scale-105"
                            loading="eager">
                        <div class="absolute inset-0 bg-gradient-to-t from-primary-900/20 via-transparent to-white/5"></div>
                    </div>

                    {{-- Existing floating cards --}}
                    <div class="glass-card animate-float absolute -left-1 bottom-3 hidden rounded-2xl border border-white/80 p-3 shadow-xl sm:flex sm:-left-4 sm:bottom-2 sm:p-4" style="animation-delay: 1s">
                        <div class="flex items-center gap-3">
                            <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-secondary-100">
                                <i data-lucide="graduation-cap" class="h-5 w-5 text-secondary-600" aria-hidden="true"></i>
                            </span>
                            <div>
                                <p class="text-sm font-bold text-gray-900">Kurikulum Islami</p>
                                <p class="text-xs text-gray-500">Berbasis Al-Qur'an</p>
                            </div>
                        </div>
                    </div>

                    <div class="glass-card animate-float absolute -right-1 top-0 hidden rounded-2xl border border-white/80 p-3 shadow-xl sm:flex sm:-right-4 sm:p-4" style="animation-delay: 2s">
                        <div class="flex items-center gap-3">
                            <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-primary-100">
                                <i data-lucide="shield-check" class="h-5 w-5 text-primary-600" aria-hidden="true"></i>
                            </span>
                            <div>
                                <p class="text-sm font-bold text-gray-900">Lingkungan Aman</p>
                                <p class="text-xs text-gray-500">Nyaman & ramah anak</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Existing school profile video, integrated editorial block --}}
        <div class="fade-up mt-12 lg:mt-20">
            <div class="overflow-hidden rounded-[2rem] border border-gray-100 bg-white shadow-2xl shadow-primary-900/10">
                <div class="grid lg:grid-cols-12">
                    <div class="flex flex-col justify-center p-6 sm:p-10 lg:col-span-4 lg:p-12">
                        <span class="mb-5 inline-flex w-fit self-start items-center gap-2 whitespace-nowrap rounded-full border border-primary-200 bg-primary-50 px-4 py-2 text-sm font-semibold text-primary-700">
                            <i data-lucide="circle-play" class="h-4 w-4" aria-hidden="true"></i>
                            Video Profil
                        </span>
                        <h2 class="section-heading section-heading-left mt-3 font-heading text-3xl text-gray-900 lg:text-4xl">
                            <span>Profil <span class="gradient-text">Sekolah Kami</span></span>
                        </h2>
                        <p class="mt-4 leading-7 text-gray-600">Kenali lebih dekat lingkungan, kegiatan, dan pengalaman belajar di PAUD Al Qur'an Azzahra.</p>

                        <div class="mt-7 flex items-center gap-3 border-t border-gray-100 pt-6">
                            <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-secondary-50 text-secondary-600">
                                <i data-lucide="heart" class="h-4 w-4" aria-hidden="true"></i>
                            </span>
                            <p class="text-sm font-semibold text-gray-700">Tumbuh bersama nilai Qur'ani</p>
                        </div>
                    </div>

                    <div class="relative min-h-[260px] bg-gray-100 sm:min-h-[420px] lg:col-span-8 lg:min-h-[480px]">
                        <video class="absolute inset-0 h-full w-full object-cover" controls autoplay loop muted playsinline preload="metadata">
                            <source src="{{ asset('videos/profil-paud.mp4') }}" type="video/mp4">
                            Maaf, browser Anda tidak mendukung pemutaran video.
                        </video>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Existing bottom wave --}}
    <div class="pointer-events-none absolute inset-x-0 bottom-0">
        <svg viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <path d="M0 120L60 110C120 100 240 80 360 70C480 60 600 60 720 65C840 70 960 80 1080 85C1200 90 1320 90 1380 90L1440 90V120H1380C1320 120 1200 120 1080 120C960 120 840 120 720 120C600 120 480 120 360 120C240 120 120 120 60 120H0Z" fill="white"/>
        </svg>
    </div>
</section>
