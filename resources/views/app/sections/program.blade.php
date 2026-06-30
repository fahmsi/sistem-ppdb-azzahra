{{-- Section: Program Unggulan dan Prestasi Murid --}}
<section id="program" class="relative overflow-hidden py-20 lg:py-28">
    <div class="pointer-events-none absolute -right-40 top-24 h-96 w-96 rounded-full bg-primary-100/35 blur-3xl" aria-hidden="true"></div>
    <div class="pointer-events-none absolute -left-40 bottom-1/3 h-96 w-96 rounded-full bg-secondary-100/30 blur-3xl" aria-hidden="true"></div>

    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        {{-- Program introduction --}}
        <div class="grid items-center gap-8 lg:grid-cols-12 lg:gap-12">
            <div class="fade-left lg:col-span-7">
                <span class="inline-flex items-center gap-2 rounded-full border border-primary-200 bg-white/80 px-4 py-2 text-sm font-semibold text-primary-700 shadow-sm backdrop-blur-sm">
                    <i data-lucide="sparkles" class="h-4 w-4" aria-hidden="true"></i>
                    Program yang bertumbuh bersama anak
                </span>
                <h2 class="section-heading section-heading-left mt-6 font-heading text-gray-900">
                    <span>Fondasi Belajar untuk Anak yang <span class="gradient-text">Utuh & Bahagia</span></span>
                </h2>
                <p class="mt-6 max-w-3xl text-base text-justify leading-8 text-gray-600 sm:text-lg">
                    Program Azzahra tidak hanya mengenalkan kemampuan akademik. Anak dibimbing untuk memiliki adab yang baik, dekat dengan nilai Islam, mampu berkomunikasi, serta percaya diri mengeksplorasi dunia di sekitarnya.
                </p>
            </div>

            <div class="fade-right lg:col-span-5">
                <div class="relative overflow-hidden rounded-[1.75rem] bg-gradient-to-br from-primary-900 via-primary-800 to-secondary-900 p-6 text-white shadow-xl shadow-primary-900/15 sm:p-7">
                    <div class="pointer-events-none absolute -right-16 -top-20 h-48 w-48 rounded-full border-[38px] border-white/[0.05]" aria-hidden="true"></div>
                    <p class="relative text-xs font-bold uppercase tracking-[0.18em] text-secondary-200">Yang orang tua akan lihat</p>
                    <div class="relative mt-5 grid gap-3 sm:grid-cols-3 lg:grid-cols-1 xl:grid-cols-3">
                        @foreach ([
                            ['icon' => 'gamepad-2', 'title' => 'Aktif', 'text' => 'Belajar melalui bermain'],
                            ['icon' => 'repeat-2', 'title' => 'Konsisten', 'text' => 'Pembiasaan setiap hari'],
                            ['icon' => 'sprout', 'title' => 'Bertahap', 'text' => 'Sesuai tumbuh kembang'],
                        ] as $approach)
                            <div class="rounded-2xl border border-white/10 bg-white/[0.07] p-4 backdrop-blur-sm">
                                <i data-lucide="{{ $approach['icon'] }}" class="h-5 w-5 text-secondary-300" aria-hidden="true"></i>
                                <p class="mt-3 font-heading text-sm font-bold">{{ $approach['title'] }}</p>
                                <p class="mt-1 text-xs leading-5 text-primary-100/75">{{ $approach['text'] }}</p>
                            </div>
                        @endforeach
                    </div>
                    <a href="#kurikulum" class="relative mt-5 inline-flex items-center gap-2 text-xs font-bold text-white transition-colors hover:text-secondary-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-offset-2 focus-visible:ring-offset-primary-800">
                        Pelajari alur kurikulum
                        <i data-lucide="arrow-down-right" class="h-4 w-4" aria-hidden="true"></i>
                    </a>
                </div>
            </div>
        </div>

        @php
            $programs = [
                [
                    'number' => '01',
                    'title' => 'Pendidikan Karakter',
                    'eyebrow' => 'Adab dan kemandirian',
                    'summary' => 'Anak belajar mengenali emosi, menghargai orang lain, bertanggung jawab, serta melakukan kebiasaan sederhana secara mandiri.',
                    'why' => 'Karakter yang dibiasakan sejak dini membantu anak lebih siap bersosialisasi, mengikuti aturan, dan menghadapi pengalaman baru.',
                    'image' => 'images/foto_lomba17an.jpg',
                    'image_alt' => 'Murid Azzahra belajar percaya diri dan bekerja sama dalam kegiatan sekolah',
                    'icon' => 'heart-handshake',
                    'gradient' => 'from-rose-500 to-pink-600',
                    'soft' => 'bg-rose-50 text-rose-600 ring-rose-100',
                    'border' => 'hover:border-rose-200',
                    'outcomes' => ['Mandiri', 'Empati', 'Disiplin'],
                    'activities' => [
                        ['icon' => 'users', 'text' => 'Belajar berbagi, antre, dan bekerja sama.'],
                        ['icon' => 'smile', 'text' => 'Mengenali serta mengekspresikan emosi dengan baik.'],
                        ['icon' => 'hand-heart', 'text' => 'Membiasakan salam, sopan santun, dan kepedulian.'],
                        ['icon' => 'backpack', 'text' => 'Berlatih merapikan barang dan menyelesaikan tugas sederhana.'],
                    ],
                ],
                [
                    'number' => '02',
                    'title' => 'Program Keislaman',
                    'eyebrow' => 'Iman, adab, dan ibadah',
                    'summary' => 'Nilai Islam dikenalkan melalui pembiasaan yang hangat dan sesuai usia agar anak mencintai ibadah, Al-Qur’an, serta akhlak Rasulullah.',
                    'why' => 'Pengalaman keislaman yang menyenangkan membantu nilai baik tumbuh sebagai kebiasaan, bukan sekadar hafalan.',
                    'image' => 'images/foto_ramadhan.jpg',
                    'image_alt' => 'Kegiatan Ramadan murid PAUD Al Qur’an Azzahra',
                    'icon' => 'moon-star',
                    'gradient' => 'from-emerald-500 to-green-600',
                    'soft' => 'bg-emerald-50 text-emerald-600 ring-emerald-100',
                    'border' => 'hover:border-emerald-200',
                    'outcomes' => ['Cinta Al-Qur’an', 'Adab', 'Ibadah'],
                    'activities' => [
                        ['icon' => 'book-open', 'text' => 'Belajar membaca Al-Qur’an dengan metode KIBAR.'],
                        ['icon' => 'person-standing', 'text' => 'Mengenal gerakan, bacaan, dan tertib sholat.'],
                        ['icon' => 'book-marked', 'text' => 'Hafalan surat pendek, doa harian, dan Asmaul Husna.'],
                        ['icon' => 'messages-square', 'text' => 'Mengenal kisah nabi dan hadits melalui cerita.'],
                    ],
                ],
                [
                    'number' => '03',
                    'title' => 'Literasi & Numerasi Dini',
                    'eyebrow' => 'Bahasa, logika, dan kreativitas',
                    'summary' => 'Anak membangun kesiapan membaca, menulis, dan berhitung melalui cerita, permainan konkret, percakapan, seni, serta eksplorasi.',
                    'why' => 'Fondasi literasi dan numerasi yang menyenangkan menumbuhkan rasa ingin tahu tanpa membuat anak merasa terbebani.',
                    'image' => 'images/foto_kegiatanKBM2.jpg',
                    'image_alt' => 'Kegiatan literasi dan numerasi murid PAUD Al Qur’an Azzahra di kelas',
                    'icon' => 'book-a',
                    'gradient' => 'from-primary-500 to-indigo-600',
                    'soft' => 'bg-primary-50 text-primary-600 ring-primary-100',
                    'border' => 'hover:border-primary-200',
                    'outcomes' => ['Bahasa', 'Logika', 'Kreativitas'],
                    'activities' => [
                        ['icon' => 'message-circle', 'text' => 'Bercerita, menyimak, berbicara, dan memperkaya kosakata.'],
                        ['icon' => 'pencil-line', 'text' => 'Pra-menulis melalui aktivitas motorik halus.'],
                        ['icon' => 'calculator', 'text' => 'Mengenal angka, pola, bentuk, dan konsep jumlah.'],
                        ['icon' => 'palette', 'text' => 'Proyek seni dan eksperimen sederhana untuk memantik ide.'],
                    ],
                ],
            ];
        @endphp

        {{-- Program exploration cards --}}
        <div class="fade-up mt-12 grid items-start gap-6 lg:grid-cols-3 lg:gap-7">
            @foreach ($programs as $program)
                <article class="group transform-gpu overflow-hidden rounded-[2rem] border border-white/90 bg-white/90 shadow-xl shadow-primary-900/[0.06] transition-[transform,box-shadow,border-color] duration-200 ease-out will-change-transform hover:-translate-y-0.5 hover:shadow-xl hover:shadow-primary-900/10 motion-reduce:transform-none motion-reduce:transition-none {{ $program['border'] }}">
                    <div class="relative h-60 overflow-hidden sm:h-72 lg:h-64">
                        <img src="{{ asset($program['image']) }}"
                             alt="{{ $program['image_alt'] }}"
                             class="h-full w-full transform-gpu object-cover transition-transform duration-500 ease-out will-change-transform group-hover:scale-[1.03] motion-reduce:transform-none motion-reduce:transition-none"
                             loading="lazy">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/75 via-slate-900/10 to-transparent"></div>
                        <span class="absolute left-5 top-5 inline-flex items-center gap-2 rounded-full border border-white/25 bg-slate-950/35 px-3 py-2 text-[10px] font-extrabold uppercase tracking-[0.14em] text-white backdrop-blur-md">
                            <span class="h-2 w-2 rounded-full bg-secondary-300"></span>
                            Dokumentasi program
                        </span>
                        <span class="absolute bottom-5 right-5 font-heading text-6xl font-black text-white/25" aria-hidden="true">{{ $program['number'] }}</span>
                    </div>

                    <div class="relative p-6 sm:p-7">
                        <span class="absolute -top-7 left-6 flex h-14 w-14 transform-gpu items-center justify-center rounded-2xl bg-gradient-to-br {{ $program['gradient'] }} text-white shadow-lg ring-4 ring-white transition-[transform,box-shadow] duration-200 ease-out will-change-transform group-hover:-translate-y-0.5 group-hover:shadow-xl motion-reduce:transform-none motion-reduce:transition-none">
                            <i data-lucide="{{ $program['icon'] }}" class="h-8 w-8" aria-hidden="true"></i>
                        </span>

                        <p class="mt-3 text-[11px] font-extrabold uppercase tracking-[0.17em] text-gray-400">{{ $program['eyebrow'] }}</p>
                        <h3 class="mt-2 font-heading text-2xl font-extrabold text-gray-900">{{ $program['title'] }}</h3>
                        <p class="mt-4 text-sm text-justify leading-7 text-gray-600">{{ $program['summary'] }}</p>

                        <div class="mt-5">
                            <p class="text-[10px] font-extrabold uppercase tracking-[0.16em] text-gray-400">Yang ikut berkembang</p>
                            <div class="mt-2.5 flex flex-wrap gap-2">
                                @foreach ($program['outcomes'] as $outcome)
                                    <span class="rounded-lg px-2.5 py-1.5 text-xs font-bold ring-1 ring-inset {{ $program['soft'] }}">{{ $outcome }}</span>
                                @endforeach
                            </div>
                        </div>

                        <details class="mt-6 border-t border-gray-100 pt-5">
                            <summary class="flex cursor-pointer list-none items-center justify-between gap-4 rounded-xl text-sm font-bold text-gray-800 transition-colors hover:text-primary-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 focus-visible:ring-offset-4">
                                <span class="inline-flex items-center gap-2">
                                    <i data-lucide="search" class="h-4 w-4 text-primary-500" aria-hidden="true"></i>
                                    Eksplorasi proses belajar
                                </span>
                                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-gray-50 text-gray-500" aria-hidden="true">
                                    <i data-lucide="chevrons-up-down" class="h-4 w-4"></i>
                                </span>
                            </summary>

                            <div class="mt-5">
                                <p class="text-[10px] font-extrabold uppercase tracking-[0.16em] text-gray-400">Contoh kegiatan anak</p>
                                <ul class="mt-3 space-y-3">
                                    @foreach ($program['activities'] as $activity)
                                        <li class="flex items-start gap-3 text-sm leading-6 text-gray-600">
                                            <span class="mt-0.5 flex h-7 w-7 shrink-0 items-center justify-center rounded-lg {{ $program['soft'] }}">
                                                <i data-lucide="{{ $activity['icon'] }}" class="h-3.5 w-3.5" aria-hidden="true"></i>
                                            </span>
                                            <span>{{ $activity['text'] }}</span>
                                        </li>
                                    @endforeach
                                </ul>

                                <div class="mt-5 rounded-2xl bg-gray-50 p-4 ring-1 ring-inset ring-gray-100">
                                    <p class="flex items-center gap-2 text-xs font-bold text-gray-800">
                                        <i data-lucide="lightbulb" class="h-4 w-4 text-amber-500" aria-hidden="true"></i>
                                        Mengapa ini penting?
                                    </p>
                                    <p class="mt-2 text-xs text-justify leading-5 text-gray-500">{{ $program['why'] }}</p>
                                </div>
                            </div>
                        </details>
                    </div>
                </article>
            @endforeach
        </div>

        {{-- Parent-facing outcome bridge --}}
        <div class="fade-up mt-10 overflow-hidden rounded-[1.75rem] border border-primary-100 bg-gradient-to-r from-primary-50 via-white to-secondary-50 shadow-lg shadow-primary-900/[0.04]">
            <div class="grid lg:grid-cols-[1.15fr_1.85fr] lg:items-center">
                <div class="border-b border-primary-100 p-6 sm:p-8 lg:border-b-0 lg:border-r">
                    <span class="inline-flex h-11 w-11 items-center justify-center rounded-xl bg-white text-primary-600 shadow-sm ring-1 ring-primary-100">
                        <i data-lucide="route" class="h-5 w-5" aria-hidden="true"></i>
                    </span>
                    <h3 class="mt-4 font-heading text-xl font-extrabold text-gray-900 sm:text-2xl">Program yang terlihat dalam keseharian anak.</h3>
                    <p class="mt-2 text-sm leading-6 text-gray-600">Kemajuan tumbuh melalui proses kecil yang dilakukan berulang, didampingi, dan diapresiasi.</p>
                </div>

                <div class="grid sm:grid-cols-3">
                    @foreach ([
                        ['icon' => 'message-circle-heart', 'title' => 'Berani Berkomunikasi', 'text' => 'Menyampaikan ide dan perasaan.'],
                        ['icon' => 'users-round', 'title' => 'Mampu Bersosialisasi', 'text' => 'Berbagi dan bekerja sama.'],
                        ['icon' => 'star', 'title' => 'Percaya Diri Mencoba', 'text' => 'Tekun dan tidak takut belajar.'],
                    ] as $outcome)
                        <div class="border-b border-gray-100 p-6 last:border-b-0 sm:border-b-0 sm:border-r sm:last:border-r-0">
                            <i data-lucide="{{ $outcome['icon'] }}" class="h-5 w-5 text-secondary-600" aria-hidden="true"></i>
                            <p class="mt-3 font-heading text-sm font-bold text-gray-900">{{ $outcome['title'] }}</p>
                            <p class="mt-1 text-xs leading-5 text-gray-500">{{ $outcome['text'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Dynamic achievements as evidence of the learning process --}}
        @if($achievements->isNotEmpty())
            <div class="mt-24 lg:mt-28">
                <div class="fade-up grid items-end gap-8 lg:grid-cols-12">
                    <div class="lg:col-span-7">
                        <span class="inline-flex items-center gap-2 rounded-full border border-amber-200 bg-amber-50 px-4 py-2 text-sm font-semibold text-amber-700">
                            <i data-lucide="trophy" class="h-4 w-4" aria-hidden="true"></i>
                            Jejak keberanian anak
                        </span>
                        <h3 class="section-heading section-heading-left mt-6 font-heading text-gray-900">
                            <span>Prestasi sebagai <span class="gradient-text">Bukti Proses</span></span>
                        </h3>
                        <p class="mt-5 max-w-3xl text-base leading-7 text-gray-600 sm:text-lg">
                            Bagi kami, prestasi bukan hanya tentang piala. Setiap pencapaian menunjukkan keberanian anak untuk mencoba, berlatih, tampil, dan menyelesaikan tantangan.
                        </p>
                    </div>

                    <div class="lg:col-span-5 lg:flex lg:justify-end">
                        <div class="max-w-md rounded-2xl border border-white/90 bg-white/75 p-5 shadow-sm backdrop-blur-md">
                            <div class="flex items-start gap-4">
                                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-secondary-50 text-secondary-600 ring-1 ring-secondary-100">
                                    <i data-lucide="award" class="h-5 w-5" aria-hidden="true"></i>
                                </span>
                                <div>
                                    <p class="font-heading text-2xl font-extrabold text-gray-900">{{ $achievements->count() }}</p>
                                    <p class="mt-1 text-sm leading-6 text-gray-500">pencapaian yang menjadi bagian dari perjalanan tumbuh murid Azzahra.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="stagger-children mt-12 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                    @foreach($achievements as $achievement)
                        <article class="group overflow-hidden rounded-[1.75rem] border border-white/90 bg-white/85 shadow-lg shadow-primary-900/[0.05] backdrop-blur-sm transition-[transform,border-color,box-shadow] duration-500 transform-gpu will-change-[transform,box-shadow] hover:-translate-y-1.5 hover:border-primary-100 hover:shadow-2xl hover:shadow-primary-900/10">
                            <div class="relative h-60 overflow-hidden">
                                <img src="{{ $achievement->image_url }}"
                                     alt="{{ $achievement->title }}"
                                     class="h-full w-full object-cover transition-transform duration-700 transform-gpu will-change-transform group-hover:scale-105"
                                     loading="lazy">
                                <div class="absolute inset-0 bg-gradient-to-t from-primary-950/75 via-primary-900/5 to-transparent"></div>

                                <div class="absolute left-4 top-4 flex flex-wrap items-center gap-2">
                                    <span class="rounded-xl bg-white/95 px-3 py-1.5 text-xs font-bold text-primary-700 shadow-sm backdrop-blur-sm">{{ $achievement->level }}</span>
                                    @if($achievement->achievement_year)
                                        <span class="rounded-xl border border-white/25 bg-primary-950/45 px-2.5 py-1.5 text-xs font-bold text-white backdrop-blur-sm">{{ $achievement->achievement_year }}</span>
                                    @endif
                                </div>

                                <span class="absolute bottom-4 right-4 font-heading text-5xl font-black text-white/25">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                            </div>

                            <div class="relative p-6">
                                <span class="absolute -top-5 left-6 flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-amber-400 to-orange-500 text-white shadow-lg ring-4 ring-white">
                                    <i data-lucide="medal" class="h-5 w-5" aria-hidden="true"></i>
                                </span>
                                <h4 class="mt-2 font-heading text-xl font-extrabold text-gray-900">{{ $achievement->title }}</h4>
                                @if($achievement->description)
                                    <p class="mt-2 text-sm leading-6 text-gray-500">{{ $achievement->description }}</p>
                                @endif
                                <div class="mt-5 flex items-center gap-2 border-t border-gray-100 pt-4 text-xs font-semibold text-secondary-700">
                                    <i data-lucide="sparkles" class="h-4 w-4" aria-hidden="true"></i>
                                    Berani mencoba, berlatih, dan berkembang
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</section>
