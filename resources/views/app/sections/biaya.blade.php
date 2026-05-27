{{-- ============================================
    Section 11: Biaya & FAQ
    ============================================ --}}
<section id="biaya" class="py-20 lg:py-28 bg-gray-50 relative overflow-hidden islamic-pattern">
    {{-- Decorative --}}
    <div class="absolute top-0 left-0 w-64 h-64 bg-primary-100/20 rounded-full -translate-x-1/2 -translate-y-1/2"></div>

    <div class="relative max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Title --}}
        <div class="text-center mb-14 fade-up">
            <span class="inline-flex items-center gap-2 bg-primary-50 text-primary-700 px-4 py-1.5 rounded-full text-sm font-medium mb-4">
                <i data-lucide="wallet" class="w-4 h-4"></i>
                Biaya Pendidikan
            </span>
            <h2 class="font-heading text-3xl lg:text-4xl font-bold text-gray-900 mb-4 section-heading">
                Informasi Biaya Pendidikan
            </h2>
            <p class="text-gray-600 max-w-2xl mx-auto mt-6">
                Investasi terbaik untuk masa depan anak Anda dengan biaya yang transparan
            </p>
        </div>

        {{-- Pricing Cards --}}
        @php
        $fees = [
            [
                'title' => 'Biaya Pendaftaran',
                'amount' => 'GRATIS',
                'desc' => 'Gratis registrasi awal pendaftaran',
                'badge' => '',
                'icon' => 'file-text',
                'highlight' => true
            ],
            [
                'title' => 'Biaya Masuk',
                'amount' => 'Rp 850.000',
                'desc' => 'Termasuk seragam, buku pendukung, dan perlengkapan alat tulis sekolah',
                'badge' => 'Sekali Bayar',
                'icon' => 'package',
                'highlight' => false
            ],
            [
                'title' => 'SPP Bulanan',
                'amount' => 'Rp 110.000',
                'desc' => 'Termasuk kegiatan pembelajaran',
                'badge' => 'Per Bulan',
                'icon' => 'calendar',
                'highlight' => false
            ],
            [
                'title' => 'Biaya Tahunan',
                'amount' => '-',
                'desc' => 'Field trip, manasik haji, dan kegiatan tahunan',
                'badge' => 'Per Tahun',
                'icon' => 'gift',
                'highlight' => false
            ],
        ];
        @endphp

        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6 mb-16 stagger-children">
            @foreach($fees as $item)
            <div class="pricing-highlight hover-card bg-white rounded-2xl shadow-sm p-7 border {{ $item['highlight'] ? 'border-primary-200 ring-2 ring-primary-100' : 'border-gray-100' }} relative overflow-hidden group">
                {{-- Popular badge --}}
                @if($item['highlight'])
                    <div class="absolute top-4 right-4">
                        <span class="bg-gradient-to-r from-primary-600 to-primary-500 text-white text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider">
                            Populer
                        </span>
                    </div>
                @endif

                {{-- Badge --}}
                <span class="inline-flex items-center gap-1.5 text-xs bg-primary-50 text-primary-700 px-3 py-1.5 rounded-full font-semibold mb-4">
                    <i data-lucide="{{ $item['icon'] }}" class="w-3 h-3"></i>
                    {{ $item['badge'] }}
                </span>

                <h3 class="font-heading font-semibold text-gray-900 text-lg mb-3">
                    {{ $item['title'] }}
                </h3>

                <p class="text-2xl lg:text-3xl font-heading font-bold gradient-text mb-3">
                    {{ $item['amount'] }}
                </p>

                <p class="text-sm text-gray-500 leading-relaxed">
                    {{ $item['desc'] }}
                </p>
            </div>
            @endforeach
        </div>

        {{-- Included --}}
        @php
        $includes = [
            ['item' => 'Seragam lengkap (3 set)', 'icon' => 'shirt'],
            ['item' => 'Buku pendukung', 'icon' => 'book'],
            ['item' => 'Alat tulis sekolah', 'icon' => 'pencil'],
            ['item' => 'Kegiatan pembelajaran', 'icon' => 'school'],
            ['item' => 'Kegiatan hari besar Islam', 'icon' => 'gift'],
        ];
        @endphp

        <div class="bg-gradient-to-r from-primary-50 to-secondary-50 border border-primary-100 rounded-2xl p-8 mb-16 fade-up">
            <h3 class="font-heading text-xl font-semibold text-gray-900 mb-6 flex items-center gap-2">
                <i data-lucide="check-circle-2" class="w-5 h-5 text-secondary-600"></i>
                Yang Sudah Termasuk
            </h3>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($includes as $inc)
                <div class="flex items-center gap-3 bg-white/70 backdrop-blur-sm p-3 rounded-xl">
                    <div class="w-8 h-8 rounded-lg bg-secondary-100 flex items-center justify-center flex-shrink-0">
                        <i data-lucide="{{ $inc['icon'] }}" class="w-4 h-4 text-secondary-600"></i>
                    </div>
                    <span class="text-gray-700 text-sm font-medium">{{ $inc['item'] }}</span>
                </div>
                @endforeach
            </div>
        </div>

        {{-- FAQ --}}
        @php
        $faqs = [
            ['q' => 'Apakah tersedia cicilan pembayaran?', 'a' => 'Ya, tersedia cicilan hingga 3x pembayaran tanpa bunga. Anda dapat menghubungi bagian administrasi untuk informasi lebih lanjut.'],
            ['q' => 'Apakah ada diskon untuk saudara kandung?', 'a' => 'Ya, kami memberikan diskon 10% untuk SPP bulanan bagi saudara kandung yang terdaftar di sekolah kami.'],
            ['q' => 'Apa saja yang termasuk dalam biaya tahunan?', 'a' => 'Biaya tahunan mencakup study tour/fieldtrip, wisuda, kegiatan hari besar Islam, dan perlengkapan ujian semester.'],
            ['q' => 'Apakah ada program beasiswa?', 'a' => 'Ya, kami menyediakan beasiswa prestasi hingga 50% untuk siswa berprestasi dan beasiswa bagi keluarga kurang mampu. Hubungi kami untuk persyaratannya.'],
            ['q' => 'Bagaimana metode pembayaran yang tersedia?', 'a' => 'Pembayaran dapat dilakukan melalui transfer bank, QRIS, atau tunai di kantor administrasi sekolah.'],
        ];
        @endphp

        <div class="max-w-3xl mx-auto fade-up">
            <div class="text-center mb-10">
                <h3 class="font-heading text-2xl lg:text-3xl font-bold text-gray-900 section-heading">
                    Pertanyaan Umum (FAQ)
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