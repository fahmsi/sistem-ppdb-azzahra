{{-- ============================================
    Section 7-8: Testimonial Carousels (Siswa + Orang Tua)
    ============================================ --}}
<section class="py-20 lg:py-28 bg-white relative overflow-hidden">
    {{-- Decorative --}}
    <div class="absolute top-1/2 right-0 w-96 h-96 bg-primary-50 rounded-full translate-x-1/2 -translate-y-1/2"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="text-center mb-16 fade-up">
            <span class="inline-flex items-center gap-2 bg-secondary-50 text-secondary-700 px-4 py-1.5 rounded-full text-sm font-medium mb-4">
                <i data-lucide="message-circle" class="w-4 h-4"></i>
                Testimoni
            </span>
            <h2 class="font-heading text-3xl lg:text-4xl font-bold text-gray-900 section-heading">
                Apa Kata Mereka?
            </h2>
            <p class="text-gray-600 max-w-2xl mx-auto mt-6">
                Testimoni dari siswa dan orang tua yang telah merasakan pendidikan di Az-Zahra
            </p>
        </div>

        <div class="grid lg:grid-cols-2 gap-12">

            {{-- ==========================================
                Carousel 1: Testimoni Siswa
                ========================================== --}}
            <div class="fade-left">
                <h3 class="font-heading text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <i data-lucide="smile" class="w-5 h-5 text-primary-600"></i>
                    Testimoni Siswa
                </h3>

                <div id="carousel-siswa" class="relative">
                    <div class="overflow-hidden rounded-2xl">
                        <div class="carousel-track">
                            @php
                                $siswaTestimonials = [
                                    [
                                        'name' => 'Ahmad Fauzan',
                                        'class' => 'Kelas B',
                                        'text' => '"Saya senang belajar di Az-Zahra karena banyak teman dan gurunya baik-baik. Saya suka belajar mengaji!"',
                                        'avatar' => '👦'
                                    ],
                                    [
                                        'name' => 'Aisyah Putri',
                                        'class' => 'Kelas A',
                                        'text' => '"Belajar di sini menyenangkan! Saya sudah hafal banyak surat pendek dan doa harian."',
                                        'avatar' => '👧'
                                    ],
                                    [
                                        'name' => 'Muhammad Rizki',
                                        'class' => 'Kelas B',
                                        'text' => '"Saya suka bermain di taman bermain dan belajar berenang bersama teman-teman!"',
                                        'avatar' => '👦'
                                    ],
                                ];
                            @endphp

                            @foreach ($siswaTestimonials as $t)
                                <div class="carousel-slide">
                                    <div class="bg-gradient-to-br from-primary-50 to-blue-50 rounded-2xl p-8 border border-primary-100">
                                        {{-- Quote icon --}}
                                        <div class="mb-4">
                                            <i data-lucide="quote" class="w-8 h-8 text-primary-300"></i>
                                        </div>
                                        <p class="text-gray-700 italic mb-6 leading-relaxed text-lg">
                                            {{ $t['text'] }}
                                        </p>
                                        <div class="flex items-center gap-3">
                                            <div class="w-12 h-12 rounded-full bg-white flex items-center justify-center text-2xl shadow-sm">
                                                {{ $t['avatar'] }}
                                            </div>
                                            <div>
                                                <p class="font-heading font-semibold text-gray-900">{{ $t['name'] }}</p>
                                                <p class="text-sm text-primary-600">{{ $t['class'] }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Controls --}}
                    <div class="flex items-center justify-between mt-6">
                        <div class="carousel-dots flex gap-2"></div>
                        <div class="flex gap-2">
                            <button class="carousel-prev w-10 h-10 rounded-xl bg-white border border-gray-200 flex items-center justify-center hover:bg-primary-50 hover:border-primary-200 transition-colors shadow-sm">
                                <i data-lucide="chevron-left" class="w-5 h-5 text-gray-600"></i>
                            </button>
                            <button class="carousel-next w-10 h-10 rounded-xl bg-white border border-gray-200 flex items-center justify-center hover:bg-primary-50 hover:border-primary-200 transition-colors shadow-sm">
                                <i data-lucide="chevron-right" class="w-5 h-5 text-gray-600"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ==========================================
                Carousel 2: Testimoni Orang Tua
                ========================================== --}}
            <div class="fade-right">
                <h3 class="font-heading text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <i data-lucide="users" class="w-5 h-5 text-secondary-600"></i>
                    Testimoni Orang Tua
                </h3>

                <div id="carousel-ortu" class="relative">
                    <div class="overflow-hidden rounded-2xl">
                        <div class="carousel-track">
                            @php
                                $ortuTestimonials = [
                                    [
                                        'name' => 'Ibu Siti Rahmah',
                                        'relation' => 'Orang Tua Ahmad',
                                        'text' => '"Alhamdulillah, anak saya berkembang pesat di Az-Zahra. Hafalannya bertambah dan akhlaknya semakin baik."',
                                        'avatar' => '👩'
                                    ],
                                    [
                                        'name' => 'Bapak Hendra',
                                        'relation' => 'Orang Tua Aisyah',
                                        'text' => '"Sekolah ini sangat recommended. Guru-gurunya profesional dan lingkungannya Islami. Anak saya sangat betah."',
                                        'avatar' => '👨'
                                    ],
                                    [
                                        'name' => 'Ibu Fatimah',
                                        'relation' => 'Orang Tua Rizki',
                                        'text' => '"Biaya terjangkau tapi kualitas pendidikan luar biasa. Anak saya sudah bisa baca Al-Quran di usia 5 tahun."',
                                        'avatar' => '👩'
                                    ],
                                ];
                            @endphp

                            @foreach ($ortuTestimonials as $t)
                                <div class="carousel-slide">
                                    <div class="bg-gradient-to-br from-secondary-50 to-emerald-50 rounded-2xl p-8 border border-secondary-100">
                                        <div class="mb-4">
                                            <i data-lucide="quote" class="w-8 h-8 text-secondary-300"></i>
                                        </div>
                                        <p class="text-gray-700 italic mb-6 leading-relaxed text-lg">
                                            {{ $t['text'] }}
                                        </p>
                                        <div class="flex items-center gap-3">
                                            <div class="w-12 h-12 rounded-full bg-white flex items-center justify-center text-2xl shadow-sm">
                                                {{ $t['avatar'] }}
                                            </div>
                                            <div>
                                                <p class="font-heading font-semibold text-gray-900">{{ $t['name'] }}</p>
                                                <p class="text-sm text-secondary-600">{{ $t['relation'] }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Controls --}}
                    <div class="flex items-center justify-between mt-6">
                        <div class="carousel-dots flex gap-2"></div>
                        <div class="flex gap-2">
                            <button class="carousel-prev w-10 h-10 rounded-xl bg-white border border-gray-200 flex items-center justify-center hover:bg-secondary-50 hover:border-secondary-200 transition-colors shadow-sm">
                                <i data-lucide="chevron-left" class="w-5 h-5 text-gray-600"></i>
                            </button>
                            <button class="carousel-next w-10 h-10 rounded-xl bg-white border border-gray-200 flex items-center justify-center hover:bg-secondary-50 hover:border-secondary-200 transition-colors shadow-sm">
                                <i data-lucide="chevron-right" class="w-5 h-5 text-gray-600"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
</section>
