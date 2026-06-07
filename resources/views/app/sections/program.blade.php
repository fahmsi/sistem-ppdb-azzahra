{{-- ============================================
    Section 6: Program Unggulan + Prestasi Siswa
    ============================================ --}}
<section id="program" class="py-20 lg:py-28 bg-gray-50 relative overflow-hidden">
    {{-- Decorative --}}
    <div class="absolute top-0 left-0 w-80 h-80 bg-primary-100/30 rounded-full -translate-y-1/2 -translate-x-1/2"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="text-center mb-14 fade-up">
            <span class="inline-flex items-center gap-2 bg-primary-50 text-primary-700 px-4 py-1.5 rounded-full text-sm font-medium mb-4">
                <i data-lucide="rocket" class="w-4 h-4"></i>
                
            </span>
            <h2 class="font-heading text-3xl lg:text-4xl font-bold text-gray-900 mb-4 section-heading">
                Program Unggulan Kami
            </h2>
            <p class="text-gray-600 max-w-2xl mx-auto mt-6">
                Berbagai program berkualitas untuk mengembangkan potensi siswa secara menyeluruh
            </p>
        </div>

        {{-- Program Cards --}}
        @php
        $programs = [
            [
                'title' => 'Pendidikan Karakter',
                'desc' => 'Membentuk akhlak mulia dan karakter islami sejak dini',
                'icon' => 'heart',
                'gradient' => 'from-rose-500 to-pink-600'
            ],
            [
                'title' => 'Kegiatan Islami',
                'desc' => 'Membaca Al-Quran, sholat berjamaah, doa harian, hafalan asmaul husna, hafalan surat pendek dan hadist',
                'icon' => 'moon',
                'gradient' => 'from-blue-500 to-indigo-600'
            ],
            [
                'title' => 'Program Literasi Dini',
                'desc' => 'Mengembangkan kemampuan membaca, menulis, dan berhitung sejak dini',
                'icon' => 'book-a',
                'gradient' => 'from-purple-500 to-violet-600'
            ],
        ];
        @endphp

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-24 stagger-children">
            @foreach ($programs as $p)
                <div class="hover-card bg-white p-7 border border-gray-100 rounded-2xl shadow-sm group relative overflow-hidden">
                    {{-- Gradient bar --}}
                    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r {{ $p['gradient'] }} opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

                    <div class="mb-5">
                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br {{ $p['gradient'] }} flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                            <i data-lucide="{{ $p['icon'] }}" class="w-7 h-7 text-white"></i>
                        </div>
                    </div>
                    <h3 class="font-heading text-lg font-semibold text-gray-900 mb-2">{{ $p['title'] }}</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">{{ $p['desc'] }}</p>
                </div>
            @endforeach
        </div>

        {{-- Prestasi Siswa --}}
        <div class="fade-up">
            <div class="text-center mb-12">
                <span class="inline-flex items-center gap-2 bg-amber-50 text-amber-700 px-4 py-1.5 rounded-full text-sm font-medium mb-4">
                    <i data-lucide="award" class="w-4 h-4"></i>
                    
                </span>
                <h3 class="font-heading text-2xl lg:text-3xl font-bold text-gray-900 section-heading">
                    Prestasi Siswa
                </h3>
            </div>

            @php
                $prestasi = [
                    [
                        'title' => 'Juara Hafalan Quran',
                        'badge' => 'Kota Depok',
                        'image' => 'https://images.unsplash.com/photo-1667967699372-1c26d40dec46?w=600&auto=format&fit=crop'
                    ],
                    [
                        'title' => 'Juara Mewarnai',
                        'badge' => 'Tingkat Kecamatan',
                        'image' => 'https://images.unsplash.com/photo-1513542789411-b6a5d4f31634?w=600&auto=format&fit=crop'
                    ],
                    [
                        'title' => 'Juara Lomba Adzan',
                        'badge' => 'Kota Depok',
                        'image' => 'https://images.unsplash.com/photo-1577896851231-70ef18881754?w=600&auto=format&fit=crop'
                    ],
                ];
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 stagger-children">
                @foreach ($prestasi as $item)
                    <div class="group bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-400 hover:-translate-y-2 border border-gray-100">
                        <div class="relative h-52 overflow-hidden">
                            <img src="{{ $item['image'] }}"
                                alt="{{ $item['title'] }}"
                                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                                loading="lazy">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>

                            {{-- Badge --}}
                            <span class="absolute top-4 left-4 text-xs bg-white/90 backdrop-blur-sm text-primary-700 px-3 py-1.5 rounded-full font-semibold shadow-sm">
                                {{ $item['badge'] }}
                            </span>
                        </div>
                        <div class="p-6">
                            <h4 class="font-heading font-semibold text-gray-900 text-lg">
                                {{ $item['title'] }}
                            </h4>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>
</section>