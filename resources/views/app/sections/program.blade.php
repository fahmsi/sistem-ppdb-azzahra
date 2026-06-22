{{-- ============================================
    Section 6: Program Unggulan + Prestasi Siswa
    ============================================ --}}
<section id="program" class="py-20 lg:py-28 bg-gray-50 relative overflow-hidden">
    {{-- Decorative --}}
    <div class="absolute top-0 right-0 w-80 h-80 bg-primary-100/30 rounded-full -translate-y-1/2 translate-x-1/2"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="text-center mb-14 fade-up">
            <h2 class="section-heading font-heading mb-4 text-3xl text-gray-900 lg:text-4xl">
                <span>Program <span class="gradient-text">Unggulan Kami</span></span>
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
        @if($achievements->isNotEmpty())
            <div class="fade-up">
                <div class="mx-auto mb-10 max-w-3xl text-center lg:mb-12">
                    <div class="mb-5">
                        <span class="inline-flex items-center gap-2 rounded-full border border-primary-200 bg-primary-50 px-4 py-2 text-sm font-semibold text-primary-700">
                            <i data-lucide="sparkles" class="h-4 w-4" aria-hidden="true"></i>
                            Jejak Kebanggaan
                        </span>
                    </div>
                    <h3 class="section-heading font-heading text-3xl text-gray-900 sm:text-4xl lg:text-4xl">
                        <span>Prestasi <span class="gradient-text">Murid Azzahra</span></span>
                    </h3>
                    <p class="mx-auto mt-5 max-w-2xl text-base leading-relaxed text-gray-600 sm:text-lg">
                        Setiap pencapaian adalah bagian dari perjalanan anak untuk tumbuh lebih percaya diri, tekun, dan berani mencoba.
                    </p>
                    <span class="mt-5 inline-flex items-center gap-2 rounded-xl border border-secondary-100 bg-secondary-50 px-3.5 py-2 text-xs font-bold text-secondary-700">
                        <i data-lucide="award" class="h-4 w-4" aria-hidden="true"></i>
                        {{ $achievements->count() }} prestasi ditampilkan
                    </span>
                </div>

                <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                    @foreach($achievements as $achievement)
                        <article class="group overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm transition-all duration-300 hover:-translate-y-2 hover:border-primary-100 hover:shadow-xl hover:shadow-primary-900/10">
                            <div class="relative h-56 overflow-hidden sm:h-60">
                                <img src="{{ $achievement->image_url }}"
                                    alt="{{ $achievement->title }}"
                                    class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-110"
                                    loading="lazy">
                                <div class="absolute inset-0 bg-gradient-to-t from-primary-900/60 via-primary-900/5 to-transparent"></div>

                                <div class="absolute left-4 top-4 flex items-center gap-2">
                                    <span class="rounded-xl bg-white/95 px-3 py-1.5 text-xs font-bold text-primary-700 shadow-sm backdrop-blur-sm">
                                        {{ $achievement->level }}
                                    </span>
                                    @if($achievement->achievement_year)
                                        <span class="rounded-xl border border-white/25 bg-primary-900/45 px-2.5 py-1.5 text-xs font-bold text-white backdrop-blur-sm">
                                            {{ $achievement->achievement_year }}
                                        </span>
                                    @endif
                                </div>

                                <span class="absolute bottom-4 right-4 font-heading text-5xl font-black text-white/25">
                                    {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}
                                </span>
                            </div>

                            <div class="relative p-6">
                                <span class="absolute -top-5 left-6 flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-primary-500 to-secondary-500 text-white shadow-lg shadow-primary-600/20">
                                    <i data-lucide="medal" class="h-5 w-5" aria-hidden="true"></i>
                                </span>
                                <h4 class="mt-2 font-heading text-xl font-bold text-gray-900">{{ $achievement->title }}</h4>
                                @if($achievement->description)
                                    <p class="mt-2 text-sm leading-6 text-gray-500">{{ $achievement->description }}</p>
                                @endif
                                <div class="mt-5 flex items-center gap-2 border-t border-gray-100 pt-4 text-xs font-semibold text-secondary-700">
                                    <i data-lucide="badge-check" class="h-4 w-4" aria-hidden="true"></i>
                                    Pencapaian Siswa Azzahra
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        @endif

    </div>
</section>
