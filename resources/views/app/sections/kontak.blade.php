{{-- ============================================
    Section 13: Kontak & Google Maps
    ============================================ --}}
<section id="faq-dan-kontak" class="relative overflow-hidden py-20 lg:py-28">
    {{-- Decorative --}}
    <div class="absolute bottom-0 left-0 w-80 h-80 bg-secondary-50 rounded-full translate-y-1/2 -translate-x-1/3"></div>

    <div class="relative max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- FAQ (Dipindah ke sebelum Informasi Kontak) --}}
        @php
        $faqs = [
            ['q' => 'Bagaimana PAUD Azzahra menilai perkembangan anak?', 'a' => 'Di PAUD Azzahra, perkembangan anak dipantau melalui observasi harian, portofolio karya anak, dan checklist perkembangan sesuai tahap usianya.'],
            ['q' => 'Apakah tersedia cicilan pembayaran?', 'a' => 'Ya, tersedia cicilan sebelum tahun ajaran dimulai. Anda dapat menghubungi Admin SPMB untuk informasi lebih lanjut.'],
            ['q' => 'Apa saja yang termasuk dalam biaya tahunan?', 'a' => 'Biaya tahunan mencakup study tour/fieldtrip, kegiatan hari besar Islam dan manasik haji bersama Ayah dan Bunda.'],
            ['q' => 'Bagaimana metode pembayaran yang tersedia?', 'a' => 'Pembayaran dapat dilakukan hanya melalui transfer bank.'],
            ['q' => 'Berapa total biaya yang harus dibayar?', 'a' => 'Total biaya yang harus dibayar adalah Rp 850.000'],
        ];
        @endphp

        <div class="max-w-3xl mx-auto mb-16 fade-up">
            <div class="flex flex-col items-center text-center mb-10">
                <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-primary-50 border border-primary-200 text-primary-700 text-sm font-semibold mb-4 shadow-sm">
                    <i data-lucide="help-circle" class="w-4 h-4 text-primary-600"></i>
                    <span>Informasi &amp; Tanya Jawab</span>
                </span>
                <h2 class="section-heading font-heading mb-3 text-gray-900">
                    <span>Pertanyaan Sering <span class="gradient-text">Diajukan (FAQ)</span></span>
                </h2>
                <p class="text-gray-600 max-w-2xl mx-auto mt-2 text-sm sm:text-base leading-relaxed">
                    Jawaban lengkap atas berbagai pertanyaan umum Ayah &amp; Bunda seputar pendaftaran, program, dan kegiatan sekolah.
                </p>
            </div>

            <div class="space-y-3 mt-8">
                @foreach($faqs as $i => $faq)
                <div class="faq-item bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden transition-all duration-200 hover:border-primary-100">
                    <button onclick="toggleFAQ(this)"
                        class="w-full text-left px-6 py-5 flex items-center justify-between gap-4 hover:bg-gray-50/80 active:bg-gray-100/70 transition-colors select-none">
                        <span class="font-heading font-semibold text-gray-900 text-sm sm:text-base">{{ $faq['q'] }}</span>
                        <i data-lucide="chevron-down" class="faq-chevron w-5 h-5 text-gray-400 flex-shrink-0"></i>
                    </button>
                    <div class="faq-answer">
                        <div class="faq-answer-inner text-sm leading-relaxed text-gray-600">
                            {{ $faq['a'] }}
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Title: Hubungi Kami (Target Anchor untuk Navbar Kontak) --}}
        <div id="kontak" class="text-center mb-12 fade-up scroll-mt-28">
            <h2 class="section-heading font-heading mb-4 text-gray-900">
                <span>Hubungi <span class="gradient-text">Panitia SPMB</span></span>
            </h2>
            <p class="text-gray-600 max-w-2xl mx-auto mt-2 text-sm sm:text-base leading-relaxed">
                Punya pertanyaan seputar pendaftaran, program, atau berkas? Tim kami siap membantu Ayah &amp; Bunda.
            </p>
        </div>

        {{-- Contact Hub Grid (Non-repetitive, Clean & Modern) --}}
        <div class="grid lg:grid-cols-12 gap-6 mb-16 fade-up">

            {{-- 1. WhatsApp Card (Main Call-to-Action) -- 7 cols --}}
            <div class="lg:col-span-7 hover-card bg-gradient-to-br from-emerald-600 via-teal-600 to-emerald-800 rounded-3xl p-6 sm:p-8 text-white shadow-xl shadow-emerald-900/15 relative overflow-hidden flex flex-col justify-between">
                <div class="absolute -right-10 -top-10 w-48 h-48 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
                <div class="absolute -left-10 -bottom-10 w-48 h-48 bg-emerald-400/20 rounded-full blur-2xl pointer-events-none"></div>

                <div>
                    <div class="flex items-center justify-between gap-4 mb-6">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-2xl bg-white/15 backdrop-blur-md border border-white/20 text-white flex items-center justify-center shadow-sm">
                                <i data-lucide="message-circle" class="w-6 h-6"></i>
                            </div>
                            <div>
                                <h3 class="font-heading font-bold text-xl text-white">WhatsApp Admin SPMB</h3>
                                <p class="text-xs text-emerald-100">Respon cepat &amp; pelayanan ramah</p>
                            </div>
                        </div>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/20 backdrop-blur-md text-[11px] font-bold text-white border border-white/25">
                            <span class="w-2 h-2 rounded-full bg-emerald-300 animate-pulse"></span>
                            Online Jam Kerja
                        </span>
                    </div>

                    <p class="text-sm text-emerald-50 leading-relaxed mb-6">
                        Dapatkan jawaban langsung mengenai alur pendaftaran, rincian biaya, maupun konfirmasi jadwal observasi secara fleksibel via WhatsApp.
                    </p>
                </div>

                <div class="pt-4 border-t border-white/15 flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4">
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-emerald-200">Nomor WhatsApp Resmi</p>
                        <p class="text-lg font-extrabold text-white font-mono">0813-1040-8525</p>
                    </div>

                    <a href="https://wa.me/6281310408525?text=Halo%20Admin%20PAUD%20Az%20Zahra,%20saya%20ingin%20bertanya%20informasi%20mengenai%20SPMB..."
                       target="_blank"
                       rel="noopener noreferrer"
                       class="group inline-flex items-center justify-center gap-2 px-6 py-3 rounded-2xl bg-white hover:bg-emerald-50 text-emerald-800 text-xs sm:text-sm font-bold shadow-lg transition-all duration-200 cursor-pointer">
                        <i data-lucide="message-square" class="w-4 h-4 text-emerald-600 transition-transform duration-200 group-hover:scale-110"></i>
                        <span>Chat via WhatsApp</span>
                    </a>
                </div>
            </div>

            {{-- Right Stack (5 cols): Email & Konsultasi Tatap Muka --}}
            <div class="lg:col-span-5 flex flex-col gap-6 justify-between">

                {{-- 2. Email Card --}}
                <div class="hover-card bg-white rounded-3xl p-6 border border-gray-100 shadow-lg shadow-gray-100/50 flex-1 flex flex-col justify-between">
                    <div class="flex items-start gap-4">
                        <div class="w-11 h-11 rounded-2xl bg-primary-50 text-primary-600 flex items-center justify-center flex-shrink-0 border border-primary-100">
                            <i data-lucide="mail" class="w-5 h-5"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="font-heading font-bold text-gray-900 text-base">Email Pendaftaran</h4>
                            <p class="text-xs text-gray-500 mt-0.5">Untuk pertanyaan tertulis &amp; berkas dokumen</p>
                            <a href="mailto:nanirahmani72@gmail.com" class="inline-block mt-2 text-sm font-bold text-primary-600 hover:text-primary-700 truncate underline decoration-primary-200 underline-offset-4">
                                nanirahmani72@gmail.com
                            </a>
                        </div>
                    </div>
                </div>

                {{-- 3. Layanan Kunjungan & Konsultasi --}}
                <div class="hover-card bg-white rounded-3xl p-6 border border-gray-100 shadow-lg shadow-gray-100/50 flex-1 flex flex-col justify-between">
                    <div class="flex items-start gap-4">
                        <div class="w-11 h-11 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center flex-shrink-0 border border-amber-100">
                            <i data-lucide="calendar-check" class="w-5 h-5"></i>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-heading font-bold text-gray-900 text-base">Konsultasi Langsung</h4>
                            <p class="text-xs text-gray-600 mt-1 leading-relaxed">
                                Ayah &amp; Bunda juga dapat berkunjung langsung ke sekolah untuk konsultasi tatap muka sesuai jam operasional.
                            </p>
                        </div>
                    </div>
                </div>

            </div>

        </div>

        {{-- Google Maps Section (Redesigned & High Aesthetic) --}}
        <div class="w-full mt-16 fade-up">
            <div class="text-center max-w-xl mx-auto mb-8">
                <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-secondary-50 border border-secondary-200 text-secondary-700 text-xs font-bold uppercase tracking-wider mb-3 shadow-sm">
                    <i data-lucide="map-pin" class="w-4 h-4 text-secondary-600"></i>
                    <span>Lokasi Sekolah</span>
                </span>
                <h3 class="font-heading font-extrabold text-2xl sm:text-3xl text-gray-900">
                    Kunjungi <span class="gradient-text">PAUD Azzahra Depok</span>
                </h3>
                <p class="text-gray-600 text-sm mt-2">
                    Berada di lingkungan yang asri, aman, dan mudah diakses dari berbagai wilayah Kota Depok.
                </p>
            </div>

            {{-- Main Map Wrapper Card --}}
            <div class="relative bg-white rounded-2xl p-3 sm:p-4 border border-gray-100 shadow-xl shadow-gray-200/60 overflow-hidden group">
                
                {{-- Top Control Bar inside Card --}}
                <div class="flex flex-wrap items-center justify-between gap-3 p-3 sm:p-4 mb-2 bg-gradient-to-r from-gray-50 via-white to-primary-50/40 rounded-2xl border border-gray-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-primary-600 text-white flex items-center justify-center shadow-md shadow-primary-600/30">
                            <i data-lucide="navigation" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <h4 class="font-heading font-bold text-gray-900 text-sm sm:text-base flex items-center gap-2">
                                <span>PAUD Al Qur'an Azzahra Depok</span>
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 text-[10px] font-bold border border-emerald-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                    Lokasi
                                </span>
                            </h4>
                            <p class="text-xs text-gray-500 hidden sm:block">Jl. Serimpi V No.338, Sukmajaya, Kota Depok</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2.5 w-full sm:w-auto flex-shrink-0">
                        <a href="https://www.google.com/maps/dir/?api=1&destination=-6.394642,106.8353951"
                           target="_blank"
                           rel="noopener noreferrer"
                           class="group inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-primary-600 hover:bg-primary-700 active:bg-primary-800 text-white text-xs font-bold shadow-md shadow-primary-600/20 hover:shadow-lg hover:shadow-primary-600/35 transition-all duration-200 whitespace-nowrap w-full sm:w-auto cursor-pointer">
                            <i data-lucide="corner-up-right" class="w-4 h-4 text-white transition-transform duration-200 group-hover:translate-x-0.5"></i>
                            <span>Petunjuk Arah GPS</span>
                        </a>
                        <a href="https://www.google.com/maps/search/?api=1&query=-6.394642%2C106.8353951"
                           target="_blank"
                           rel="noopener noreferrer"
                           class="inline-flex items-center justify-center gap-1.5 px-3.5 py-2.5 rounded-xl bg-white hover:bg-gray-50 active:bg-gray-100 text-gray-700 text-xs font-bold border border-gray-200 shadow-sm hover:shadow-md transition-all duration-200 whitespace-nowrap hidden md:inline-flex cursor-pointer">
                            <i data-lucide="external-link" class="w-3.5 h-3.5 text-gray-500"></i>
                            <span>Google Maps</span>
                        </a>
                    </div>
                </div>

                {{-- Map Container & Floating Card --}}
                <div class="relative w-full rounded-2xl overflow-hidden shadow-inner border border-gray-100">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3510.6726761888185!2d106.8353951!3d-6.394641999999999!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69e974c0b42d7f%3A0x23c154e4eaa6d1c1!2sPAUD%20AL%20QURAN%20AZZAHRA!5e1!3m2!1sen!2sid!4v1776849031234!5m2!1sen!2sid"
                        class="w-full h-[380px] sm:h-[450px] border-0 filter contrast-[1.02]"
                        title="Lokasi PAUD Az-Zahra Depok"
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>

                    {{-- Floating Info Badge on top of map (Desktop/Tablet) --}}
                    <div class="hidden sm:block absolute top-4 left-4 max-w-xs bg-white/95 backdrop-blur-md p-4 rounded-2xl border border-white/60 shadow-xl text-left pointer-events-auto">
                        <div class="flex items-center gap-2 mb-1.5">
                            <div class="w-7 h-7 rounded-lg bg-secondary-100 text-secondary-700 flex items-center justify-center">
                                <i data-lucide="building-2" class="w-4 h-4"></i>
                            </div>
                            <h5 class="font-heading font-bold text-gray-900 text-xs">PAUD Al Qur'an Az-Zahra</h5>
                        </div>
                        <p class="text-[11px] text-gray-600 leading-relaxed mb-3">
                            Sukmajaya, Kota Depok, Jawa Barat 16411
                        </p>
                        <div class="flex items-center gap-2 text-[10px] font-semibold text-secondary-700 bg-secondary-50 px-2.5 py-1 rounded-lg">
                            <i data-lucide="clock" class="w-3 h-3 text-secondary-600"></i>
                            <span>Buka: Senin - Jumat (08.00 - 11.00)</span>
                        </div>
                    </div>
                </div>

                {{-- Feature Badges below Map --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mt-3">
                    <div class="flex items-center gap-3 p-3 rounded-2xl bg-gray-50/80 border border-gray-100">
                        <div class="w-8 h-8 rounded-xl bg-primary-100 text-primary-600 flex items-center justify-center flex-shrink-0">
                            <i data-lucide="car" class="w-4 h-4"></i>
                        </div>
                        <div class="min-w-0">
                            <h5 class="text-xs font-bold text-gray-800">Akses Kendaraan</h5>
                            <p class="text-[11px] text-gray-500 truncate">Mudah dijangkau mobil &amp; motor</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 p-3 rounded-2xl bg-gray-50/80 border border-gray-100">
                        <div class="w-8 h-8 rounded-xl bg-secondary-100 text-secondary-600 flex items-center justify-center flex-shrink-0">
                            <i data-lucide="parking-circle" class="w-4 h-4"></i>
                        </div>
                        <div class="min-w-0">
                            <h5 class="text-xs font-bold text-gray-800">Area Parkir Aman</h5>
                            <p class="text-[11px] text-gray-500 truncate">Kenyamanan antar-jemput anak</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 p-3 rounded-2xl bg-gray-50/80 border border-gray-100">
                        <div class="w-8 h-8 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center flex-shrink-0">
                            <i data-lucide="shield-check" class="w-4 h-4"></i>
                        </div>
                        <div class="min-w-0">
                            <h5 class="text-xs font-bold text-gray-800">Lingkungan Asri</h5>
                            <p class="text-[11px] text-gray-500 truncate">Kondusif untuk belajar anak</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- Final Enrollment CTA Banner (Ajakan Sekolah di PAUD Az-Zahra) --}}
        <div class="mt-20 fade-up">
            <div class="relative overflow-hidden rounded-[2.5rem] bg-gradient-to-br from-primary-900 via-primary-800 to-secondary-900 p-8 sm:p-12 text-white shadow-2xl shadow-primary-900/20 border border-white/10">
                
                {{-- Decorative Light Orbs & Islamic Pattern --}}
                <div class="absolute -right-20 -top-20 h-80 w-80 rounded-full bg-secondary-400/20 blur-3xl pointer-events-none"></div>
                <div class="absolute -left-20 -bottom-20 h-80 w-80 rounded-full bg-primary-400/20 blur-3xl pointer-events-none"></div>
                <div class="absolute inset-0 opacity-[0.05] islamic-pattern pointer-events-none"></div>

                <div class="relative z-10 grid items-center gap-8 lg:grid-cols-12">
                    
                    {{-- Left Text & Action Area (7 cols) --}}
                    <div class="lg:col-span-7 text-center lg:text-left">
                        <div class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-4 py-2 text-xs font-bold text-white backdrop-blur-md mb-6 shadow-sm">
                            <i data-lucide="sparkles" class="h-4 w-4 text-amber-300"></i>
                            <span>Tempat Terbaik untuk Tumbuh Kembang Anak</span>
                        </div>

                        <h3 class="font-heading text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight text-white leading-tight mb-4">
                            Mari Bergabung Bersama <span class="text-transparent bg-clip-text bg-gradient-to-r from-secondary-200 via-emerald-300 to-teal-200">PAUD Al-Qur'an Azzahra</span>
                        </h3>

                        <p class="text-sm sm:text-base leading-relaxed text-primary-100/90 mb-8 max-w-xl mx-auto lg:mx-0">
                            Siap mendampingi putra-putri Ayah Bunda menjadi pribadi yang cerdas, berakhlak mulia, ceria, dan memiliki kecintaan pada Al-Qur'an sejak usia dini. Pendaftaran SPMB telah dibuka!
                        </p>

                        {{-- Action Buttons --}}
                        <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4">
                            @auth
                                <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('parent.dashboard') }}"
                                   class="group inline-flex items-center justify-center gap-3 rounded-2xl bg-secondary-500 hover:bg-secondary-600 px-7 py-4 text-sm font-bold text-white shadow-xl shadow-secondary-500/30 transition-all duration-300 hover:scale-[1.02] w-full sm:w-auto">
                                    <i data-lucide="layout-dashboard" class="w-5 h-5 transition-transform group-hover:scale-110"></i>
                                    <span>Masuk ke Portal SPMB</span>
                                </a>
                            @else
                                <a href="{{ route('register') }}"
                                   class="group inline-flex items-center justify-center gap-3 rounded-2xl bg-secondary-500 hover:bg-secondary-600 px-7 py-4 text-sm font-bold text-white shadow-xl shadow-secondary-500/30 transition-all duration-300 hover:scale-[1.02] w-full sm:w-auto">
                                    <i data-lucide="user-plus" class="w-5 h-5 transition-transform group-hover:scale-110"></i>
                                    <span>Daftar SPMB Sekarang</span>
                                </a>
                            @endauth

                            <a href="https://wa.me/6281310408525?text=Halo%20Admin%20PAUD%20Az%20Zahra,%20saya%20ingin%20bertanya%20informasi%20pendaftaran%20anak..."
                               target="_blank"
                               rel="noopener noreferrer"
                               class="inline-flex items-center justify-center gap-2.5 rounded-2xl border border-white/20 bg-white/10 hover:bg-white/20 px-6 py-4 text-sm font-bold text-white backdrop-blur-md shadow-sm transition-all duration-300 w-full sm:w-auto">
                                <i data-lucide="message-circle" class="w-5 h-5 text-emerald-300"></i>
                                <span>Konsultasi WhatsApp</span>
                            </a>
                        </div>

                        {{-- Quota / Guarantee info --}}
                        <div class="mt-6 flex flex-wrap items-center justify-center lg:justify-start gap-4 text-xs text-primary-200">
                            <span class="flex items-center gap-1.5 font-medium">
                                <i data-lucide="check-circle-2" class="w-4 h-4 text-secondary-300"></i>
                                Bebas Biaya Pendaftaran
                            </span>
                            <span class="flex items-center gap-1.5 font-medium">
                                <i data-lucide="shield-check" class="w-4 h-4 text-secondary-300"></i>
                                Kuota Terbatas Per Kelas
                            </span>
                        </div>
                    </div>

                    {{-- Right Graphic / Photo Card Area (5 cols) --}}
                    <div class="lg:col-span-5 relative flex justify-center">
                        
                        {{-- Decorative Glow Circle Behind Image --}}
                        <div class="absolute inset-0 bg-gradient-to-tr from-secondary-400/30 to-primary-400/30 rounded-3xl blur-2xl transform scale-95"></div>

                        {{-- Main Graphic Frame --}}
                        <div class="relative rounded-3xl border-2 border-white/20 bg-white/10 p-3 backdrop-blur-md shadow-2xl overflow-hidden max-w-sm sm:max-w-md">
                            <img src="{{ asset('images/hero-paud-azzahra.png') }}"
                                 alt="Anak-anak PAUD Al Qur'an Az-Zahra"
                                 class="w-full h-64 sm:h-72 object-cover rounded-2xl shadow-inner transform hover:scale-105 transition-transform duration-500">

                            {{-- Floating Badges over Graphic --}}
                            <div class="absolute top-6 left-6 bg-white/95 backdrop-blur-md px-3.5 py-2 rounded-xl shadow-lg border border-white/40 flex items-center gap-2">
                                <span class="w-7 h-7 rounded-lg bg-amber-100 text-amber-600 flex items-center justify-center text-xs font-bold">
                                    <i data-lucide="heart" class="w-4 h-4"></i>
                                </span>
                                <div>
                                    <p class="text-[11px] font-bold text-gray-900">Bermain &amp; Belajar</p>
                                    <p class="text-[9px] text-gray-500">Pembiasaan Positif</p>
                                </div>
                            </div>

                            <div class="absolute bottom-6 right-6 bg-white/95 backdrop-blur-md px-3.5 py-2 rounded-xl shadow-lg border border-white/40 flex items-center gap-2">
                                <span class="w-7 h-7 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center text-xs font-bold">
                                    <i data-lucide="book-open" class="w-4 h-4"></i>
                                </span>
                                <div>
                                    <p class="text-[11px] font-bold text-gray-900">Cinta Al-Qur'an</p>
                                    <p class="text-[9px] text-gray-500">Generasi Qur'ani</p>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

            </div>
        </div>

    </div>
</section>

