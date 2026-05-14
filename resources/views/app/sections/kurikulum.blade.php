{{-- ============================================
    Section: Kurikulum Tabs (Umum, Keislaman, Tambahan)
    ============================================ --}}
<section id="kurikulum" class="py-20 lg:py-28 bg-gradient-to-b from-gray-50 to-white relative overflow-hidden islamic-pattern">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Heading --}}
        <div class="text-center mb-14 fade-up">
            <span class="inline-flex items-center gap-2 bg-primary-50 text-primary-700 px-4 py-1.5 rounded-full text-sm font-medium mb-4">
                <i data-lucide="book-marked" class="w-4 h-4"></i>
                Kurikulum
            </span>
            <h2 class="font-heading text-3xl lg:text-4xl font-bold text-gray-900 mb-4 section-heading">
                Kurikulum & Program Pembelajaran
            </h2>
            <p class="text-gray-600 max-w-2xl mx-auto mt-6">
                Program pembelajaran yang dirancang untuk perkembangan anak secara holistik,
                memadukan nilai islami dan metode modern.
            </p>
        </div>

        @php
            $tabs = [
                'umum' => [
                    'title' => 'Program Umum',
                    'icon' => 'book-open',
                    'color' => 'primary',
                    'desc' => 'Kurikulum nasional yang mengembangkan aspek kognitif, motorik, bahasa, sosial-emosional, dan seni.',
                    'image' => 'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?w=600&auto=format&fit=crop',
                    'items' => [
                        ['icon' => 'heart', 'title' => 'Pengembangan Akhlak', 'desc' => 'Membentuk karakter mulia sejak dini'],
                        ['icon' => 'brain', 'title' => 'Pengembangan Kognitif', 'desc' => 'Melatih daya pikir dan kreativitas'],
                        ['icon' => 'message-circle', 'title' => 'Pengembangan Bahasa', 'desc' => 'Kemampuan berkomunikasi yang baik'],
                        ['icon' => 'activity', 'title' => 'Pengembangan Motorik', 'desc' => 'Motorik halus dan kasar seimbang'],
                        ['icon' => 'palette', 'title' => 'Pengembangan Seni', 'desc' => 'Ekspresi kreativitas melalui seni'],
                    ]
                ],
                'islam' => [
                    'title' => 'Program Keislaman',
                    'icon' => 'moon',
                    'color' => 'secondary',
                    'desc' => 'Program keislaman yang menanamkan nilai-nilai Qur\'ani dan akhlak mulia pada anak usia dini.',
                    'image' => 'https://images.unsplash.com/photo-1609599006353-e629aaabfeae?w=600&auto=format&fit=crop',
                    'items' => [
                        ['icon' => 'book-open', 'title' => "Membaca Al-Qur'an (KIBAR)", 'desc' => 'Metode KIBAR yang mudah & menyenangkan'],
                        ['icon' => 'moon', 'title' => 'Praktek Sholat', 'desc' => 'Belajar sholat dengan benar'],
                        ['icon' => 'book-marked', 'title' => 'Hafalan Surat Pendek', 'desc' => 'Juz 30 dan doa harian'],
                        ['icon' => 'sparkles', 'title' => 'Ayat Kursi & Asmaul Husna', 'desc' => 'Hafalan ayat pilihan'],
                        ['icon' => 'heart', 'title' => 'Kisah Nabi & Hadist', 'desc' => 'Meneladani akhlak mulia'],
                    ]
                ],
                'tambahan' => [
                    'title' => 'Program Tambahan',
                    'icon' => 'star',
                    'color' => 'purple',
                    'desc' => 'Kegiatan tambahan yang memperkaya pengalaman belajar dan mengembangkan bakat siswa.',
                    'image' => 'https://images.unsplash.com/photo-1606733894347-7cb201dc810b?w=600&auto=format&fit=crop',
                    'items' => [
                        ['icon' => 'map-pin', 'title' => 'Manasik Haji', 'desc' => 'Simulasi ibadah haji sejak dini'],
                        ['icon' => 'waves', 'title' => 'Berenang', 'desc' => 'Olahraga air yang menyehatkan'],
                        ['icon' => 'award', 'title' => 'Wisuda', 'desc' => 'Perayaan kelulusan yang meriah'],
                        ['icon' => 'map', 'title' => 'Fieldtrip', 'desc' => 'Belajar dari alam dan lingkungan'],
                        ['icon' => 'trophy', 'title' => 'Lomba', 'desc' => 'Kompetisi mengasah bakat'],
                    ]
                ],
            ];
        @endphp

        {{-- Tabs Buttons --}}
        <div class="flex flex-wrap justify-center gap-3 mb-12 fade-up">
            @foreach ($tabs as $key => $tab)
                <button onclick="openTab('{{ $key }}', this)"
                    class="tab-btn flex items-center gap-2"
                    id="btn-{{ $key }}">
                    <i data-lucide="{{ $tab['icon'] }}" class="w-4 h-4"></i>
                    {{ $tab['title'] }}
                </button>
            @endforeach
        </div>

        {{-- Tabs Content --}}
        @foreach ($tabs as $key => $tab)
            <div id="tab-{{ $key }}" class="tab-content hidden">

                {{-- Description + Image Card --}}
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden mb-8">
                    <div class="grid lg:grid-cols-5 gap-0">
                        {{-- Image --}}
                        <div class="lg:col-span-2">
                            <img src="{{ $tab['image'] }}"
                                alt="{{ $tab['title'] }}"
                                class="w-full h-48 lg:h-full object-cover"
                                loading="lazy">
                        </div>
                        {{-- Description --}}
                        <div class="lg:col-span-3 p-8 flex flex-col justify-center">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-10 h-10 rounded-xl bg-{{ $tab['color'] }}-50 flex items-center justify-center">
                                    <i data-lucide="{{ $tab['icon'] }}" class="w-5 h-5 text-{{ $tab['color'] }}-600"></i>
                                </div>
                                <h3 class="font-heading text-xl font-bold text-gray-900">{{ $tab['title'] }}</h3>
                            </div>
                            <p class="text-gray-600 leading-relaxed">{{ $tab['desc'] }}</p>
                        </div>
                    </div>
                </div>

                {{-- Items Grid --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                    @foreach ($tab['items'] as $item)
                        <div class="hover-card bg-white p-6 rounded-xl border border-gray-100 shadow-sm group">
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 rounded-xl bg-{{ $tab['color'] }}-50 flex items-center justify-center flex-shrink-0 group-hover:bg-{{ $tab['color'] }}-100 transition-colors">
                                    <i data-lucide="{{ $item['icon'] }}" class="w-6 h-6 text-{{ $tab['color'] }}-600"></i>
                                </div>
                                <div>
                                    <h4 class="font-heading text-base font-semibold text-gray-900 mb-1">
                                        {{ $item['title'] }}
                                    </h4>
                                    <p class="text-sm text-gray-500">{{ $item['desc'] }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach

    </div>
</section>