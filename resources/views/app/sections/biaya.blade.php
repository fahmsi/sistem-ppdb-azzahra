{{-- ============================================
    Section 11: Biaya & FAQ
    ============================================ --}}
<section id="biaya" class="py-20 lg:py-28 bg-gray-50 relative overflow-hidden islamic-pattern">
    {{-- Decorative --}}
    <div class="absolute top-0 left-0 w-64 h-64 bg-primary-100/20 rounded-full -translate-x-1/2 -translate-y-1/2"></div>

    <div class="relative max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Title --}}
        <div class="text-center mb-14 fade-up">
            <h2 class="section-heading font-heading mb-4 text-3xl text-gray-900 lg:text-4xl">
                <span>Informasi <span class="gradient-text">Biaya Pendidikan</span></span>
            </h2>
            <p class="text-gray-600 max-w-2xl mx-auto mt-6">
                Investasi terbaik untuk masa depan anak Anda dengan biaya yang transparan
            </p>
        </div>

        {{-- Pricing Cards --}}
        @php
        $fees = [
            [
                'title' => 'Biaya Masuk',
                'amount' => 'Rp 850.000',
                'desc' => 'Paket perlengkapan awal untuk menemani hari-hari pertama anak di sekolah.',
                'badge' => 'Paket Awal Sekolah',
                'icon' => 'package',
                'period' => 'Dibayar satu kali saat daftar ulang',
                'featured' => true,
                'layout' => 'lg:col-span-5 lg:row-span-2',
            ],
            [
                'title' => 'Biaya Pendaftaran',
                'amount' => 'GRATIS',
                'desc' => 'Registrasi awal tanpa biaya apa pun.',
                'badge' => 'Tanpa Biaya',
                'icon' => 'file-text',
                'period' => 'Mulai daftar dengan lebih mudah',
                'featured' => false,
                'layout' => 'lg:col-span-3',
                'accent' => 'from-emerald-400 to-secondary-600',
                'icon_style' => 'bg-secondary-50 text-secondary-700 ring-secondary-100',
                'badge_style' => 'bg-secondary-50 text-secondary-700 border-secondary-100',
            ],
            [
                'title' => 'SPP Bulanan',
                'amount' => 'Rp 110.000',
                'desc' => 'Mendukung kegiatan belajar dan pendampingan anak setiap bulan.',
                'badge' => 'Per Bulan',
                'icon' => 'calendar',
                'period' => 'Dibayarkan setiap bulan',
                'featured' => false,
                'layout' => 'lg:col-span-4',
                'accent' => 'from-primary-400 to-primary-600',
                'icon_style' => 'bg-primary-50 text-primary-700 ring-primary-100',
                'badge_style' => 'bg-primary-50 text-primary-700 border-primary-100',
            ],
            [
                'title' => 'Biaya Tahunan',
                'amount' => 'Sesuai kegiatan',
                'desc' => 'Field trip, manasik haji, dan kegiatan tahunan',
                'badge' => 'Per Tahun',
                'icon' => 'sparkles',
                'period' => 'Transparan sesuai agenda kegiatan',
                'featured' => false,
                'layout' => 'lg:col-span-7',
                'accent' => 'from-amber-400 to-orange-500',
                'icon_style' => 'bg-amber-50 text-amber-700 ring-amber-100',
                'badge_style' => 'bg-amber-50 text-amber-700 border-amber-100',
            ],
        ];
        @endphp

        <div class="mb-16 grid gap-5 md:grid-cols-2 lg:auto-rows-fr lg:grid-cols-12 stagger-children">
            @foreach($fees as $item)
                @if($item['featured'])
                    <article class="group relative flex min-h-[420px] overflow-hidden rounded-[2rem] bg-gradient-to-br from-primary-800 via-primary-600 to-secondary-700 p-7 text-white shadow-xl shadow-primary-900/15 transition-all duration-500 hover:-translate-y-1 hover:shadow-2xl hover:shadow-primary-900/20 sm:p-8 {{ $item['layout'] }}">
                        <div class="absolute -right-16 -top-16 h-56 w-56 rounded-full border border-white/10 bg-white/10 transition-transform duration-700 group-hover:scale-110"></div>
                        <div class="absolute -bottom-20 -left-12 h-52 w-52 rounded-full bg-secondary-300/20 blur-2xl"></div>
                        <div class="absolute inset-0 opacity-[0.08] islamic-pattern"></div>

                        <div class="relative flex w-full flex-col">
                            <div class="mb-10 flex items-start justify-between gap-4">
                                <span class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-3.5 py-2 text-xs font-bold text-white backdrop-blur-sm">
                                    <i data-lucide="sparkles" class="h-3.5 w-3.5" aria-hidden="true"></i>
                                    {{ $item['badge'] }}
                                </span>
                                <span class="flex h-12 w-12 items-center justify-center rounded-2xl border border-white/20 bg-white/15 shadow-lg backdrop-blur-sm transition-transform duration-300 group-hover:-rotate-6 group-hover:scale-110">
                                    <i data-lucide="{{ $item['icon'] }}" class="h-6 w-6" aria-hidden="true"></i>
                                </span>
                            </div>

                            <p class="mb-2 text-sm font-semibold text-primary-100">{{ $item['title'] }}</p>
                            <h3 class="font-heading text-4xl font-extrabold tracking-tight sm:text-5xl">
                                {{ $item['amount'] }}
                            </h3>
                            <p class="mt-4 max-w-sm text-sm leading-6 text-white/75">
                                {{ $item['desc'] }}
                            </p>

                            <div class="mt-8 border-t border-white/15 pt-6">
                                <p class="mb-3 text-[11px] font-bold uppercase tracking-[0.18em] text-primary-100">Sudah termasuk</p>
                                <div class="flex flex-wrap gap-2">
                                    @foreach(['3 set seragam', 'Buku pendukung', 'Alat tulis'] as $included)
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-white/10 px-3 py-1.5 text-xs font-semibold text-white backdrop-blur-sm">
                                            <i data-lucide="check" class="h-3.5 w-3.5 text-secondary-200" aria-hidden="true"></i>
                                            {{ $included }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>

                            <div class="mt-auto flex items-center gap-2 pt-8 text-xs font-semibold text-white/80">
                                <i data-lucide="badge-check" class="h-4 w-4 text-secondary-200" aria-hidden="true"></i>
                                {{ $item['period'] }}
                            </div>
                        </div>
                    </article>
                @else
                    <article class="group relative flex min-h-[200px] overflow-hidden rounded-[1.75rem] border border-gray-200/80 bg-white p-6 shadow-sm transition-all duration-300 hover:-translate-y-1.5 hover:border-primary-100 hover:shadow-xl hover:shadow-primary-900/10 sm:p-7 {{ $item['layout'] }}">
                        <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r {{ $item['accent'] }}"></div>
                        <div class="absolute -right-12 -top-12 h-32 w-32 rounded-full bg-gray-50 transition-transform duration-500 group-hover:scale-125"></div>

                        <div class="relative flex w-full flex-col">
                            <div class="mb-6 flex items-start justify-between gap-3">
                                <span class="flex h-11 w-11 items-center justify-center rounded-2xl ring-1 ring-inset transition-all duration-300 group-hover:-rotate-6 group-hover:scale-110 {{ $item['icon_style'] }}">
                                    <i data-lucide="{{ $item['icon'] }}" class="h-5 w-5" aria-hidden="true"></i>
                                </span>
                                <span class="rounded-full border px-3 py-1.5 text-[10px] font-bold uppercase tracking-wider {{ $item['badge_style'] }}">
                                    {{ $item['badge'] }}
                                </span>
                            </div>

                            <p class="text-sm font-semibold text-gray-500">{{ $item['title'] }}</p>
                            <h3 class="mt-1.5 font-heading text-2xl font-extrabold tracking-tight text-gray-900 sm:text-3xl">
                                {{ $item['amount'] }}
                            </h3>
                            <p class="mt-3 text-sm leading-6 text-gray-500">
                                {{ $item['desc'] }}
                            </p>

                            <div class="mt-auto flex items-center gap-2 border-t border-gray-100 pt-5 text-xs font-semibold text-gray-500">
                                <span class="h-1.5 w-1.5 rounded-full bg-secondary-500"></span>
                                {{ $item['period'] }}
                            </div>
                        </div>
                    </article>
                @endif
            @endforeach
        </div>

        {{-- Transparency note --}}
        @php
        $includes = [
            ['item' => 'Seragam lengkap', 'meta' => '3 set', 'icon' => 'shirt'],
            ['item' => 'Buku pendukung', 'meta' => 'Siap belajar', 'icon' => 'book-open'],
            ['item' => 'Alat tulis sekolah', 'meta' => 'Perlengkapan awal', 'icon' => 'pencil'],
            ['item' => 'Kegiatan pembelajaran', 'meta' => 'Termasuk SPP', 'icon' => 'school'],
        ];
        @endphp

        <div class="mb-16 overflow-hidden rounded-[1.75rem] border border-primary-100 bg-white shadow-sm fade-up">
            <div class="relative flex flex-col gap-5 overflow-hidden bg-gradient-to-r from-primary-50 via-white to-secondary-50 p-6 sm:flex-row sm:items-center sm:justify-between sm:p-7">
                <div class="absolute -right-8 -top-16 h-40 w-40 rounded-full bg-secondary-100/50 blur-2xl"></div>
                <div class="relative flex items-start gap-4 sm:items-center">
                    <span class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-2xl bg-white text-primary-600 shadow-sm ring-1 ring-primary-100">
                        <i data-lucide="shield-check" class="h-6 w-6" aria-hidden="true"></i>
                    </span>
                    <div>
                        <h3 class="font-heading text-xl font-bold text-gray-900">Biaya jelas sejak awal</h3>
                        <p class="mt-1 max-w-2xl text-sm leading-6 text-gray-600">Kebutuhan utama dirinci agar Ayah dan Bunda dapat mempersiapkan pendidikan anak dengan tenang.</p>
                    </div>
                </div>
                <span class="relative inline-flex w-fit flex-shrink-0 items-center gap-2 rounded-full border border-secondary-200 bg-white/80 px-4 py-2 text-xs font-bold text-secondary-700 shadow-sm backdrop-blur-sm">
                    <span class="h-2 w-2 rounded-full bg-secondary-500"></span>
                    Transparan &amp; terencana
                </span>
            </div>

            <div class="grid gap-px border-t border-gray-100 bg-gray-100 sm:grid-cols-2 lg:grid-cols-4">
                @foreach($includes as $inc)
                <div class="group flex items-center gap-3 bg-white p-5 transition-colors hover:bg-gray-50">
                    <div class="flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-xl bg-secondary-50 text-secondary-600 ring-1 ring-inset ring-secondary-100 transition-transform duration-300 group-hover:scale-105">
                        <i data-lucide="{{ $inc['icon'] }}" class="h-4 w-4" aria-hidden="true"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-bold leading-5 text-gray-800">{{ $inc['item'] }}</p>
                        <p class="mt-0.5 text-xs text-gray-500">{{ $inc['meta'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- FAQ --}}
        @php
        $faqs = [
            ['q' => 'Bagaimana PAUD Azzahra menilai perkembangan anak?', 'a' => 'Di PAUD Azzahra, perkembangan anak dipantau melalui observasi harian, portofolio karya anak, dan checklist perkembangan sesuai tahap usianya.'],
            ['q' => 'Apakah tersedia cicilan pembayaran?', 'a' => 'Ya, tersedia cicilan sebelum tahun ajaran dimulai. Anda dapat menghubungi Admin SPMB untuk informasi lebih lanjut.'],
            ['q' => 'Apa saja yang termasuk dalam biaya tahunan?', 'a' => 'Biaya tahunan mencakup study tour/fieldtrip, kegiatan hari besar Islam dan manasik haji bersama Ayah dan Bunda.'],
            ['q' => 'Bagaimana metode pembayaran yang tersedia?', 'a' => 'Pembayaran dapat dilakukan hanya melalui transfer bank.'],
            ['q' => 'Berapa total biaya yang harus dibayar?', 'a' => 'Total biaya yang harus dibayar adalah Rp 850.000'],
        ];
        @endphp

        <div class="max-w-3xl mx-auto fade-up">
            <div class="text-center mb-10">
                <h3 class="font-heading text-2xl lg:text-3xl font-bold text-gray-900 section-heading">
                    Pertanyaan Umum <span class="gradient-text">(FAQ)</span>
                </h3>
            </div>

            <div class="space-y-3 mt-10">
                @foreach($faqs as $i => $faq)
                <div class="faq-item bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                    <button onclick="toggleFAQ(this)"
                        class="w-full text-left p-6 flex items-center justify-between gap-4 hover:bg-gray-50 transition-colors">
                        <span class="font-heading font-semibold text-gray-900">{{ $faq['q'] }}</span>
                        <i data-lucide="chevron-down" class="faq-chevron w-5 h-5 text-gray-400 flex-shrink-0"></i>
                    </button>
                    <div class="faq-answer text-gray-600 leading-relaxed">
                        {{ $faq['a'] }}
                    </div>
                </div>
                @endforeach
            </div>
        </div>

    </div>
</section>
