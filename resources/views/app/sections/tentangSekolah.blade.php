{{-- ============================================
    Section 3: Tentang Sekolah + Tujuan
    ============================================ --}}
<section id="tentang" class="py-20 lg:py-28 bg-white relative overflow-hidden">
    {{-- Decorative --}}
    <div class="absolute top-0 right-0 w-64 h-64 bg-primary-50 rounded-full -translate-y-1/2 translate-x-1/2"></div>
    <div class="absolute bottom-0 left-0 w-48 h-48 bg-secondary-50 rounded-full translate-y-1/2 -translate-x-1/2"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Tentang Sekolah --}}
        <div class="mb-20">
            <div class="text-center mb-14 fade-up">
                <h2 class="section-heading font-heading mb-4 text-3xl text-gray-900 lg:text-4xl">
                    <span>Tentang <span class="gradient-text">Sekolah</span></span>
                </h2>
                <p class="text-gray-600 mt-8 max-w-2xl mx-auto">
                    Lembaga pendidikan anak usia dini yang mengedepankan nilai-nilai islami dan perkembangan holistik
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">

                {{-- Text --}}
                <div class="text-gray-700 space-y-5 leading-relaxed fade-left">
                    <p class="text-lg text-justify">
                        <strong class="text-gray-900">PAUD Al Qur'an Az-Zahra</strong> berkomitmen memberikan pendidikan berkualitas dengan memadukan
                        <span class="text-primary-600 font-semibold">pembelajaran modern</span> dan
                        <span class="text-secondary-600 font-semibold">nilai-nilai keislaman</span>.
                    </p>

                    <p class="text-justify">
                        Masa usia dini merupakan <span class="font-semibold text-gray-900">masa emas perkembangan anak</span>.
                        Kami menghadirkan lingkungan belajar yang
                        <span class="text-primary-600 font-semibold">aman, nyaman, dan menyenangkan</span> yang didukung oleh tenaga pendidik profesional, kami membantu setiap anak berkembang secara
                        <span class="text-secondary-600 font-semibold">optimal</span> sesuai potensinya.
                    </p>

                    <p class="text-justify">
                        <strong>PAUD Al Qur'an Az-Zahra</strong> juga mendukung penuh dalam mensukseskan
                        <span class="text-secondary-600 font-semibold">PROGRAM PAUD SATU TAHUN</span> di Kota Depok.
                    </p>

                    {{-- Feature pills --}}
                    <div class="flex flex-wrap gap-3 pt-2">
                        @php
                            $tags = ['Berbasis Al-Quran', 'Tenaga Profesional', 'Lingkungan Islami', 'Metode Modern'];
                        @endphp
                        @foreach ($tags as $tag)
                            <span class="inline-flex items-center gap-1.5 bg-gray-50 border border-gray-200 text-gray-700 px-3 py-1.5 rounded-lg text-sm">
                                <i data-lucide="check-circle" class="w-3.5 h-3.5 text-secondary-500"></i>
                                {{ $tag }}
                            </span>
                        @endforeach
                    </div>
                </div>

                {{-- Image --}}
                <div class="fade-right">
                    <div class="relative">
                        <img
                            src="{{ asset('images/foto_wisuda.jpg') }}"
                            class="w-full h-[420px] object-cover rounded-3xl shadow-2xl"
                            alt="Lingkungan PAUD Az-Zahra"
                            loading="lazy">

                        {{-- Accent border --}}
                        <div class="absolute -bottom-4 -right-4 w-full h-full rounded-3xl border-2 border-dashed border-primary-200 -z-10"></div>
                    </div>
                </div>

            </div>
        </div>

        {{-- Tujuan Sekolah --}}
        @php
            $goals = [
                [
                    'icon' => 'heart',
                    'title' => "Generasi Qur'ani",
                    'desc' => "Menyiapkan generasi Qur'ani untuk menyongsong masa depan gemilang.",
                    'iconWrap' => 'bg-primary-50 group-hover:bg-primary-100',
                    'iconColor' => 'text-primary-600',
                ],
                [
                    'icon' => 'sparkles',
                    'title' => "Aqidah & Cinta Allah",
                    'desc' => "Menanamkan aqidah agar anak mencintai Allah SWT dan Rasul-Nya.",
                    'iconWrap' => 'bg-secondary-50 group-hover:bg-secondary-100',
                    'iconColor' => 'text-secondary-600',
                ],
                [
                    'icon' => 'brain',
                    'title' => "Akhlak & Potensi",
                    'desc' => "Mengembangkan anak berakhlakul karimah, sehat, cerdas, dan kreatif.",
                    'iconWrap' => 'bg-primary-50 group-hover:bg-primary-100',
                    'iconColor' => 'text-primary-600',
                ],
            ];
        @endphp

        <div class="fade-up">
            <div class="text-center mb-12">
                <h3 class="font-heading text-2xl lg:text-3xl font-bold text-gray-900 section-heading">
                    <span>Tujuan <span class="gradient-text"> Sekolah</span></span>
                </h3>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 max-w-5xl mx-auto stagger-children">
                @foreach ($goals as $g)
                    <div class="hover-card bg-white border border-gray-100 rounded-2xl p-8 text-center shadow-sm group">
                        {{-- Icon --}}
                        <div class="mb-5 flex justify-center">
                            <div class="w-16 h-16 rounded-2xl {{ $g['iconWrap'] }} flex items-center justify-center duration-300 group-hover:scale-110 transform transition-all">
                                <i data-lucide="{{ $g['icon'] }}" class="w-8 h-8 {{ $g['iconColor'] }}"></i>
                            </div>
                        </div>

                        {{-- Title --}}
                        <h4 class="font-heading text-lg font-semibold text-gray-900 mb-3">
                            {{ $g['title'] }}
                        </h4>

                        {{-- Desc --}}
                        <p class="text-sm text-gray-600 leading-relaxed">
                            {{ $g['desc'] }}
                        </p>
                    </div>
                @endforeach
            </div>
        </div>

    </div>
</section>
