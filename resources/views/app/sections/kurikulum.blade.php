{{-- Section: Curriculum Learning Journey --}}
<section id="kurikulum" class="relative overflow-hidden py-20 lg:py-28">
    <div class="absolute -left-32 top-20 h-80 w-80 rounded-full bg-primary-200/30 blur-3xl"></div>
    <div class="absolute -right-32 bottom-0 h-96 w-96 rounded-full bg-secondary-200/30 blur-3xl"></div>
    <div class="absolute left-1/2 top-1/3 h-24 w-24 rotate-45 rounded-3xl bg-primary-400/5"></div>

    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="mx-auto mb-10 max-w-3xl text-center fade-up lg:mb-12">
            <span class="mb-5 inline-flex items-center gap-2 rounded-full border border-primary-200 bg-primary-50 px-4 py-2 text-sm font-semibold text-primary-700">
                <i data-lucide="book-open-check" class="h-4 w-4" aria-hidden="true"></i>
                Kurikulum Merdeka
            </span><br>
            <h2 class="section-heading font-heading text-gray-900">
                <span>Setiap Hari Adalah <span class="gradient-text">Perjalanan Belajar</span></span>
            </h2>
            <p class="mx-auto mt-5 max-w-2xl text-base leading-relaxed text-gray-600 sm:text-lg">
                Pembelajaran yang menyeimbangkan kemampuan akademik, nilai keislaman,
                karakter, dan pengalaman nyata anak.
            </p>
        </div>

        @php
            $tabs = [
                'umum' => [
                    'title' => 'Program Umum',
                    'shortTitle' => 'Umum',
                    'eyebrow' => 'Fondasi Tumbuh Kembang',
                    'icon' => 'book-open',
                    'desc' => "Kurikulum Merdeka yang dipadukan dengan pembelajaran berbasis Al-Qur'an untuk mengembangkan aspek kognitif, motorik, bahasa, sosial-emosional, seni, dan karakter anak.",
                    'image' => 'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?w=1000&auto=format&fit=crop',
                    'gradient' => 'from-primary-500 to-indigo-600',
                    'softBg' => 'bg-primary-50/80',
                    'softBorder' => 'border-primary-100',
                    'iconColor' => 'text-primary-600',
                    'badge' => '5 Area Perkembangan',
                    'items' => [
                        ['icon' => 'heart', 'title' => 'Pengembangan Akhlak', 'desc' => 'Membentuk karakter mulia dan kebiasaan baik sejak dini.'],
                        ['icon' => 'brain', 'title' => 'Pengembangan Kognitif', 'desc' => 'Melatih daya pikir, rasa ingin tahu, dan kreativitas.'],
                        ['icon' => 'message-circle', 'title' => 'Pengembangan Bahasa', 'desc' => 'Membangun keberanian dan kemampuan berkomunikasi.'],
                        ['icon' => 'activity', 'title' => 'Pengembangan Motorik', 'desc' => 'Menyeimbangkan keterampilan motorik halus dan kasar.'],
                        ['icon' => 'palette', 'title' => 'Pengembangan Seni', 'desc' => 'Memberi ruang berekspresi melalui warna, musik, dan karya.'],
                    ],
                ],
                'islam' => [
                    'title' => 'Program Keislaman',
                    'shortTitle' => 'Keislaman',
                    'eyebrow' => 'Iman, Adab, dan Ibadah',
                    'icon' => 'moon-star',
                    'desc' => "Program keislaman yang mengenalkan adab Rasulullah, membangun kedekatan dengan Al-Qur'an, dan menanamkan kebiasaan ibadah melalui kegiatan yang sesuai usia anak.",
                    'image' => 'https://images.unsplash.com/photo-1609599006353-e629aaabfeae?w=1000&auto=format&fit=crop',
                    'gradient' => 'from-secondary-500 to-emerald-600',
                    'softBg' => 'bg-secondary-50/80',
                    'softBorder' => 'border-secondary-100',
                    'iconColor' => 'text-secondary-600',
                    'badge' => '5 Pembiasaan Islami',
                    'items' => [
                        ['icon' => 'book-open', 'title' => "Membaca Al-Qur'an", 'desc' => 'Belajar dengan metode KIBAR yang mudah dan menyenangkan.'],
                        ['icon' => 'person-standing', 'title' => 'Praktik Sholat', 'desc' => 'Mengenal gerakan, bacaan, dan tertib sholat sejak dini.'],
                        ['icon' => 'book-marked', 'title' => 'Hafalan Surat Pendek', 'desc' => 'Menghafal surat Juz 30 serta doa-doa harian.'],
                        ['icon' => 'sparkles', 'title' => 'Ayat Kursi & Asmaul Husna', 'desc' => 'Mengenal ayat pilihan dan nama-nama baik Allah.'],
                        ['icon' => 'heart-handshake', 'title' => 'Kisah Nabi & Hadits', 'desc' => 'Meneladani akhlak mulia melalui cerita yang dekat dengan anak.'],
                    ],
                ],
                'tambahan' => [
                    'title' => 'Program Tambahan',
                    'shortTitle' => 'Tambahan',
                    'eyebrow' => 'Eksplorasi di Luar Kelas',
                    'icon' => 'sparkles',
                    'desc' => 'Pengalaman belajar kontekstual yang membantu anak menjadi lebih percaya diri, aktif, mandiri, dan mampu menemukan minatnya melalui kegiatan bersama.',
                    'image' => 'https://images.unsplash.com/photo-1606733894347-7cb201dc810b?w=1000&auto=format&fit=crop',
                    'gradient' => 'from-primary-500 to-secondary-500',
                    'softBg' => 'bg-white/90',
                    'softBorder' => 'border-primary-100',
                    'iconColor' => 'text-primary-600',
                    'badge' => '4 Pengalaman Nyata',
                    'items' => [
                        ['icon' => 'map-pin', 'title' => 'Manasik Haji', 'desc' => 'Simulasi ibadah haji bersama Ayah dan Bunda.'],
                        ['icon' => 'waves', 'title' => 'Berenang', 'desc' => 'Melatih keberanian, koordinasi tubuh, dan kebugaran.'],
                        ['icon' => 'map', 'title' => 'Field Trip', 'desc' => 'Belajar langsung dari alam dan lingkungan sekitar.'],
                        ['icon' => 'trophy', 'title' => 'Kegiatan Lomba', 'desc' => 'Mengasah bakat, sportivitas, dan rasa percaya diri.'],
                    ],
                ],
            ];
        @endphp

        <div class="mb-8 flex justify-center fade-up">
            <div class="curriculum-tablist grid w-full max-w-2xl grid-cols-3 gap-1.5 rounded-2xl border border-gray-200 bg-white/80 p-1.5 shadow-sm backdrop-blur-md" role="tablist" aria-label="Kategori kurikulum">
                @foreach ($tabs as $key => $tab)
                    <button
                        id="curriculum-tab-{{ $key }}"
                        type="button"
                        class="curriculum-tab-btn group flex min-w-0 items-center justify-center gap-2 rounded-xl px-3 py-3 text-sm font-semibold text-gray-600"
                        role="tab"
                        aria-controls="tab-{{ $key }}"
                        aria-selected="false"
                        tabindex="-1"
                        data-curriculum-tab="{{ $key }}">
                        <i data-lucide="{{ $tab['icon'] }}" class="h-4 w-4 shrink-0" aria-hidden="true"></i>
                        <span class="hidden sm:inline">{{ $tab['title'] }}</span>
                        <span class="sm:hidden">{{ $tab['shortTitle'] }}</span>
                    </button>
                @endforeach
            </div>
        </div>

        @foreach ($tabs as $key => $tab)
            <div
                id="tab-{{ $key }}"
                class="curriculum-tab-panel hidden"
                role="tabpanel"
                aria-labelledby="curriculum-tab-{{ $key }}"
                aria-hidden="true">
                <div class="overflow-hidden rounded-[2rem] border border-gray-100 bg-white shadow-xl shadow-primary-900/5">
                    <div class="grid lg:grid-cols-12">
                        <div class="relative min-h-[260px] overflow-hidden sm:min-h-[340px] lg:col-span-5 lg:min-h-[430px]">
                            <img src="{{ $tab['image'] }}" alt="Kegiatan {{ strtolower($tab['title']) }}" class="absolute inset-0 h-full w-full object-cover transition-transform duration-700 transform-gpu will-change-transform hover:scale-105" loading="lazy">
                            <div class="absolute inset-0 bg-gradient-to-t from-primary-900/80 via-primary-900/10 to-transparent"></div>

                            <div class="absolute inset-x-0 bottom-0 p-6 sm:p-8">
                                <span class="inline-flex items-center gap-2 rounded-full border border-white/30 bg-white/90 px-3 py-1.5 text-xs font-bold text-primary-700 shadow-sm backdrop-blur-md">
                                    <span class="h-2 w-2 rounded-full bg-secondary-400"></span>
                                    {{ $tab['badge'] }}
                                </span>
                                <p class="mt-4 max-w-sm text-sm leading-relaxed text-white">
                                    Belajar melalui bermain, pembiasaan, eksplorasi, dan interaksi yang bermakna.
                                </p>
                            </div>
                        </div>

                        <div class="flex flex-col justify-center p-6 sm:p-8 lg:col-span-7 lg:p-12">
                            <div class="mb-6 flex items-center gap-4">
                                <span class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br {{ $tab['gradient'] }} shadow-lg">
                                    <i data-lucide="{{ $tab['icon'] }}" class="h-7 w-7 text-white" aria-hidden="true"></i>
                                </span>
                                <div>
                                    <p class="text-xs font-bold uppercase tracking-[0.2em] {{ $tab['iconColor'] }}">{{ $tab['eyebrow'] }}</p>
                                    <h3 class="mt-1 font-heading text-2xl font-bold text-gray-900 sm:text-3xl">{{ $tab['title'] }}</h3>
                                </div>
                            </div>

                            <p class="text-base leading-8 text-gray-600 sm:text-lg">{{ $tab['desc'] }}</p>

                            <div class="mt-8 grid grid-cols-3 gap-3 border-t border-gray-100 pt-6">
                                <div>
                                    <p class="text-xl font-bold text-primary-700">Aktif</p>
                                    <p class="mt-1 text-xs text-gray-500">Berbasis bermain</p>
                                </div>
                                <div>
                                    <p class="text-xl font-bold text-secondary-700">Holistik</p>
                                    <p class="mt-1 text-xs text-gray-500">Tumbuh seimbang</p>
                                </div>
                                <div>
                                    <p class="text-xl font-bold text-primary-700">Qur'ani</p>
                                    <p class="mt-1 text-xs text-gray-500">Penuh nilai baik</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-5 grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-6">
                    @foreach ($tab['items'] as $item)
                        @php
                            $itemCount = count($tab['items']);
                            $spanClass = $itemCount === 4
                                ? 'lg:col-span-3'
                                : ($loop->index < 3 ? 'lg:col-span-2' : 'lg:col-span-3');
                        @endphp
                        <article class="curriculum-card group relative overflow-hidden rounded-2xl border {{ $tab['softBorder'] }} {{ $tab['softBg'] }} p-5 shadow-sm backdrop-blur-sm transition-[transform,border-color,background-color,box-shadow] duration-300 transform-gpu will-change-[transform,box-shadow] hover:-translate-y-1 hover:border-primary-200 hover:bg-white hover:shadow-xl hover:shadow-primary-900/5 {{ $spanClass }}">
                            <div class="absolute right-4 top-3 font-heading text-4xl font-black text-primary-900/[0.05] transition-colors group-hover:text-primary-900/[0.09]">
                                {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}
                            </div>
                            <div class="relative flex items-start gap-4">
                                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border {{ $tab['softBorder'] }} bg-white {{ $tab['iconColor'] }} shadow-sm transition-transform duration-300 transform-gpu will-change-transform group-hover:scale-110">
                                    <i data-lucide="{{ $item['icon'] }}" class="h-5 w-5" aria-hidden="true"></i>
                                </span>
                                <div class="pr-3">
                                    <h4 class="font-heading text-base font-bold text-gray-900">{{ $item['title'] }}</h4>
                                    <p class="mt-1.5 text-sm leading-6 text-gray-500">{{ $item['desc'] }}</p>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</section>
