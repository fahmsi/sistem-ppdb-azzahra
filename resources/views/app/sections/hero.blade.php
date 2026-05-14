{{-- ============================================
    Section 2: Hero — 2-Column + Video Profile
    ============================================ --}}
<section id="home" class="relative bg-gradient-to-br from-blue-50 via-white to-green-50 overflow-hidden islamic-pattern">
    {{-- Decorative Elements --}}
    <div class="absolute top-20 right-10 w-72 h-72 bg-primary-200/20 rounded-full blur-3xl"></div>
    <div class="absolute bottom-20 left-10 w-96 h-96 bg-secondary-200/20 rounded-full blur-3xl"></div>
    <div class="absolute top-40 left-1/3 w-20 h-20 bg-primary-400/10 rounded-2xl rotate-45 animate-float"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-32">

        <div class="grid lg:grid-cols-2 gap-12 lg:gap-16 items-center">

            {{-- Left: Content --}}
            <div class="fade-up">
                {{-- Badge --}}
                <div class="inline-flex items-center gap-2 bg-primary-50 border border-primary-200 text-primary-700 px-4 py-1.5 rounded-full text-sm font-medium mb-6">
                    <i data-lucide="sparkles" class="w-4 h-4"></i>
                    {{ $settings['hero_badge_text'] ?? 'Pendaftaran Dibuka 2026/2027' }}
                </div>

                <h1 class="font-heading text-4xl lg:text-5xl xl:text-6xl font-bold mb-6 leading-tight">
                    <span class="gradient-text">Wujudkan Masa Depan</span>
                    <br>
                    <span class="gradient-text">Cemerlang</span>
                    <span class="text-gray-900"> Sejak</span>
                    <br>
                    <span class="text-gray-900">Usia Dini</span>
                </h1>

                <div class="text-lg text-gray-600 mb-8 space-y-3 leading-relaxed">
                    <p>
                        <strong class="text-gray-800">PAUD AL QUR'AN AZ-ZAHRA</strong>
                        menghadirkan sistem pendidikan yang memadukan
                        <span class="text-primary-600 font-semibold">ilmu umum</span> dan
                        <span class="text-secondary-600 font-semibold">nilai-nilai Islam</span>
                        untuk mencetak generasi Qur'ani yang cerdas dan berakhlak mulia.
                    </p>
                </div>

                {{-- Buttons --}}
                <div class="flex flex-wrap gap-4 mb-8">
                    {{-- Daftar (Solid Blue) --}}
                    <a href="/register"
                        class="group relative inline-flex items-center justify-center bg-gradient-to-r from-primary-600 to-primary-500 text-white px-8 py-3.5 rounded-xl text-lg font-semibold overflow-hidden transform-gpu transition-all duration-500 hover:-translate-y-1 hover:shadow-xl hover:shadow-primary-600/25">
                        <div class="absolute inset-0 bg-gradient-to-r from-primary-700 to-primary-600 scale-x-0 origin-left transition-transform duration-500 group-hover:scale-x-100"></div>
                        <span class="relative block h-[26px] w-full overflow-hidden z-10 text-center">
                            <span class="block w-full transition-transform duration-500 group-hover:-translate-y-full">
                                Belum punya akun?
                            </span>
                            <span class="absolute left-0 w-full top-full flex items-center justify-center gap-2 transition-transform duration-500 group-hover:-translate-y-full">
                                <i data-lucide="user-plus" class="w-5 h-5"></i>
                                Buat akun
                            </span>
                        </span>
                    </a>

                    {{-- Login (Outline Blue) --}}
                    <a href="/login"
                        class="group relative inline-flex items-center justify-center border-2 border-primary-600 text-primary-600 px-8 py-3.5 rounded-xl text-lg font-semibold overflow-hidden transform-gpu transition-all duration-500 hover:-translate-y-1 hover:shadow-xl hover:text-white">
                        <div class="absolute inset-0 bg-gradient-to-r from-primary-600 to-primary-500 scale-x-0 origin-left transition-transform duration-500 group-hover:scale-x-100"></div>
                        <span class="relative block h-[26px] w-full overflow-hidden z-10 text-center">
                            <span class="block w-full transition-transform duration-500 group-hover:-translate-y-full">
                                Sudah punya akun?
                            </span>
                            <span class="absolute left-0 w-full top-full flex items-center justify-center gap-2 transition-transform duration-500 group-hover:-translate-y-full">
                                <i data-lucide="log-in" class="w-5 h-5"></i>
                                Aplikasi PSB
                            </span>
                        </span>
                    </a>
                </div>

                {{-- Stats --}}
                <div class="flex gap-8">
                    <div>
                        <p class="text-3xl font-heading font-bold gradient-text">200+</p>
                        <p class="text-sm text-gray-500">Alumni</p>
                    </div>
                    <div class="w-px bg-gray-200"></div>
                    <div>
                        <p class="text-3xl font-heading font-bold gradient-text">Akreditasi</p>
                        <p class="text-sm text-gray-500">Terakreditasi oleh BAN-PAUD</p>
                    </div>
                    <div class="w-px bg-gray-200"></div>
                    <div>
                        <p class="text-3xl font-heading font-bold gradient-text">15</p>
                        <p class="text-sm text-gray-500">Tahun Berdiri</p>
                    </div>
                </div>
            </div>

            {{-- Right: Image + Decorations --}}
            <div class="relative fade-right">
                <div class="relative h-[400px] lg:h-[500px] overflow-hidden rounded-3xl shadow-2xl">
                    <img src="https://images.unsplash.com/photo-1503676260728-1c00da094a0b?w=800&auto=format&fit=crop"
                        alt="Siswa PAUD Az-Zahra Belajar"
                        class="w-full h-full object-cover parallax"
                        loading="eager">
                    {{-- Overlay Gradient --}}
                    <div class="absolute inset-0 bg-gradient-to-t from-primary-900/30 via-transparent to-transparent"></div>
                </div>

                {{-- Floating Card --}}
                <div class="absolute -bottom-4 -left-4 glass-card rounded-2xl p-4 shadow-xl animate-float" style="animation-delay: 1s">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-secondary-100 rounded-xl flex items-center justify-center">
                            <i data-lucide="graduation-cap" class="w-5 h-5 text-secondary-600"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-sm text-gray-900">Kurikulum Islami</p>
                            <p class="text-xs text-gray-500">Berbasis Al-Qur'an</p>
                        </div>
                    </div>
                </div>

                {{-- Floating Card 2 --}}
                <div class="absolute -top-4 -right-4 glass-card rounded-2xl p-4 shadow-xl animate-float" style="animation-delay: 2s">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-primary-100 rounded-xl flex items-center justify-center">
                            <i data-lucide="shield-check" class="w-5 h-5 text-primary-600"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-sm text-gray-900">Lingkungan Aman</p>
                            <p class="text-xs text-gray-500">& Nyaman</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- Video Profile --}}
        <div class="mt-24 lg:mt-32 fade-up">
            <div class="text-center mb-10">
                <h2 class="font-heading text-3xl lg:text-4xl font-bold text-gray-900 mb-3 section-heading">
                    Profil Sekolah Kami
                </h2>
                <p class="text-gray-600 mt-6">Kenali lebih dekat PAUD Al Qur'an Az-Zahra</p>
            </div>

            <div class="max-w-4xl mx-auto">
                <div class="relative pb-[56.25%] h-0 overflow-hidden rounded-2xl shadow-2xl ring-1 ring-gray-200 bg-gray-100">
                    
                    <video 
                        class="absolute top-0 left-0 w-full h-full object-cover" 
                        controls 
                        autoplay 
                        loop 
                        muted 
                        playsinline
                    >
                        <source src="{{ asset('videos/profil-paud.mp4') }}" type="video/mp4">
                        
                        Maaf, browser Anda tidak mendukung pemutaran video.
                    </video>

                </div>
            </div>
        </div>

    </div>

    {{-- Bottom Wave --}}
    <div class="absolute bottom-0 left-0 right-0">
        <svg viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M0 120L60 110C120 100 240 80 360 70C480 60 600 60 720 65C840 70 960 80 1080 85C1200 90 1320 90 1380 90L1440 90V120H1380C1320 120 1200 120 1080 120C960 120 840 120 720 120C600 120 480 120 360 120C240 120 120 120 60 120H0Z" fill="white"/>
        </svg>
    </div>
</section>