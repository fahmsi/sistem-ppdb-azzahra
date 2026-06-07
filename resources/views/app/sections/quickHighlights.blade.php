{{-- ============================================
    Section 5: Quick Highlights — 4 Cards
    ============================================ --}}
<section class="py-20 bg-gradient-to-b from-white to-gray-50 relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="text-center mb-14 fade-up">
            <span class="inline-flex items-center gap-2 bg-secondary-50 text-secondary-700 px-4 py-1.5 rounded-full text-sm font-medium mb-4">
                <i data-lucide="star" class="w-4 h-4"></i>
            </span>               

            <h2 class="font-heading text-3xl lg:text-4xl font-bold text-gray-900 section-heading">
                Mengapa Memilih Az-Zahra?
            </h2>
        </div>

        @php
            $highlights = [
                [
                    'title' => 'Fasilitas Modern',
                    'desc' => 'Ruang kelas ber-AC, kolam mandi bola, dan tempat bermain yang aman.',
                    'icon' => 'school',
                    'color' => 'primary',
                    'gradient' => 'from-blue-500 to-blue-600'
                ],
                [
                    'title' => 'Prestasi Gemilang',
                    'desc' => 'Siswa berprestasi di berbagai lomba tingkat kota.',
                    'icon' => 'trophy',
                    'color' => 'yellow',
                    'gradient' => 'from-amber-500 to-orange-500'
                ],
                [
                    'title' => 'Program Unggulan',
                    'desc' => 'Kurikulum Merdeka yang dikolaborasikan dengan kurikulum berbasis Al-Quran dengan metode pembelajaran modern.',
                    'icon' => 'book-open',
                    'color' => 'secondary',
                    'gradient' => 'from-emerald-500 to-green-600'
                ],
                [
                    'title' => 'Lingkungan Kondusif',
                    'desc' => 'Lingkungan sekolah yang nyaman, aman, dan Islami.',
                    'icon' => 'trees',
                    'color' => 'teal',
                    'gradient' => 'from-teal-500 to-cyan-600'
                ],
            ];
        @endphp

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 stagger-children">
            @foreach ($highlights as $item)
                <div class="hover-card group bg-white p-7 border border-gray-100 rounded-2xl shadow-sm text-center relative overflow-hidden">
                    {{-- Hover gradient top bar --}}
                    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r {{ $item['gradient'] }} opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

                    {{-- Icon --}}
                    <div class="mb-5 flex justify-center">
                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br {{ $item['gradient'] }} flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                            <i data-lucide="{{ $item['icon'] }}" class="w-7 h-7 text-white"></i>
                        </div>
                    </div>

                    <h3 class="font-heading text-lg font-semibold text-gray-900 mb-3">
                        {{ $item['title'] }}
                    </h3>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        {{ $item['desc'] }}
                    </p>
                </div>
            @endforeach
        </div>

    </div>
</section>