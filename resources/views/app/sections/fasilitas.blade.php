{{-- Section: Fasilitas Sekolah --}}
<section id="fasilitas" class="relative overflow-hidden py-20 lg:py-28">
    {{-- Decorative accents that remain part of the continuous landing canvas --}}
    <div class="pointer-events-none absolute -left-32 top-1/3 h-80 w-80 rounded-full bg-primary-100/35 blur-3xl" aria-hidden="true"></div>
    <div class="pointer-events-none absolute -right-32 bottom-16 h-96 w-96 rounded-full bg-secondary-100/30 blur-3xl" aria-hidden="true"></div>

    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        {{-- Section heading --}}
        <div class="fade-up mx-auto mb-12 max-w-4xl text-center lg:mb-16">
            <span class="inline-flex items-center gap-2 rounded-full border border-secondary-200 bg-white/80 px-4 py-2 text-sm font-semibold text-secondary-700 shadow-sm backdrop-blur-sm">
                <i data-lucide="shield-check" class="h-4 w-4" aria-hidden="true"></i>
                Ruang tumbuh yang terjaga
            </span>
            <h2 class="section-heading mt-6 font-heading text-gray-900">
                <span>Fasilitas Pendukung KBM yang <span class="gradient-text">Aman & Nyaman</span></span>
            </h2>
            <p class="mx-auto mt-6 max-w-3xl text-base leading-7 text-gray-600 sm:text-lg">
                Lingkungan belajar kami dirancang agar anak dapat bergerak, bereksplorasi, dan berinteraksi dengan nyaman—sehingga proses belajar berlangsung lebih kondusif dan menyenangkan.
            </p>
        </div>

        @php
            $facilityPhotos = [
                [
                    'src' => 'images/foto_kegiatanKBM2.jpg',
                    'alt' => 'Kegiatan belajar siswa PAUD Al Qur’an Azzahra di ruang kelas',
                    'label' => 'Ruang Belajar Aktif',
                    'caption' => 'Kelas tertata untuk aktivitas bersama',
                    'icon' => 'presentation',
                ],
                [
                    'src' => 'images/foto_kegiatanKBM.jpg',
                    'alt' => 'Siswa PAUD Al Qur’an Azzahra menggunakan area bermain indoor',
                    'label' => 'Area Bermain Indoor',
                    'caption' => 'Belajar motorik melalui permainan',
                    'icon' => 'blocks',
                ],
                [
                    'src' => 'images/foto_cekkesehatan.jpg',
                    'alt' => 'Kegiatan pemeriksaan kesehatan siswa PAUD Al Qur’an Azzahra',
                    'label' => 'Perhatian Kesehatan',
                    'caption' => 'Tumbuh sehat menjadi perhatian bersama',
                    'icon' => 'heart-pulse',
                ],
            ];

            $facilitySupports = [
                [
                    'title' => 'Ruang Kelas Sejuk',
                    'desc' => 'Ruang belajar ber-AC dengan meja dan kursi yang disesuaikan untuk aktivitas anak.',
                    'icon' => 'wind',
                    'style' => 'bg-blue-500/15 text-blue-200 ring-blue-300/20',
                ],
                [
                    'title' => 'Area Bermain Variatif',
                    'desc' => 'Kolam mandi bola, area bermain indoor, dan kegiatan luar ruang mendukung perkembangan motorik.',
                    'icon' => 'shapes',
                    'style' => 'bg-amber-400/15 text-amber-200 ring-amber-300/20',
                ],
                [
                    'title' => 'Media Belajar Anak',
                    'desc' => 'Alat peraga dan media visual membantu pembelajaran terasa lebih konkret serta menyenangkan.',
                    'icon' => 'puzzle',
                    'style' => 'bg-emerald-400/15 text-emerald-200 ring-emerald-300/20',
                ],
                [
                    'title' => 'Pendampingan & Kesehatan',
                    'desc' => 'Aktivitas berlangsung dengan pendampingan guru serta perhatian pada kebiasaan hidup sehat anak.',
                    'icon' => 'heart-handshake',
                    'style' => 'bg-rose-400/15 text-rose-200 ring-rose-300/20',
                ],
            ];
        @endphp

        <div class="grid items-stretch gap-6 lg:grid-cols-12 lg:gap-8">
            {{-- Authentic school documentation --}}
            <div class="fade-left lg:col-span-7">
                <div class="grid h-full grid-cols-2 gap-3 sm:grid-rows-2 sm:gap-4">
                    @foreach ($facilityPhotos as $photo)
                        <figure class="group relative overflow-hidden rounded-[1.75rem] border-4 border-white bg-gray-100 shadow-xl shadow-primary-900/10 {{ $loop->first ? 'col-span-2 h-[330px] sm:col-span-1 sm:row-span-2 sm:h-auto sm:min-h-[580px]' : 'h-48 sm:h-auto sm:min-h-[280px]' }}">
                            <img src="{{ asset($photo['src']) }}"
                                 alt="{{ $photo['alt'] }}"
                                 class="h-full w-full object-cover transition-transform duration-700 transform-gpu will-change-transform group-hover:scale-105 {{ $loop->first ? 'object-center' : '' }}"
                                 loading="lazy">
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-slate-900/10 to-transparent"></div>

                            @if($loop->first)
                                <span class="absolute left-4 top-4 inline-flex items-center gap-2 rounded-full border border-white/30 bg-slate-950/35 px-3 py-2 text-[11px] font-bold uppercase tracking-[0.14em] text-white backdrop-blur-md sm:left-5 sm:top-5">
                                    <span class="relative flex h-2 w-2">
                                        <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-secondary-300 opacity-70"></span>
                                        <span class="relative h-2 w-2 rounded-full bg-secondary-300"></span>
                                    </span>
                                    Dokumentasi Azzahra
                                </span>
                            @endif

                            <figcaption class="absolute inset-x-0 bottom-0 flex items-end gap-3 p-4 text-white sm:p-5">
                                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-white/20 bg-white/15 backdrop-blur-md">
                                    <i data-lucide="{{ $photo['icon'] }}" class="h-5 w-5" aria-hidden="true"></i>
                                </span>
                                <span class="min-w-0">
                                    <span class="block font-heading text-base font-bold sm:text-lg">{{ $photo['label'] }}</span>
                                    <span class="mt-0.5 hidden text-xs text-white/75 sm:block">{{ $photo['caption'] }}</span>
                                </span>
                            </figcaption>
                        </figure>
                    @endforeach
                </div>
            </div>

            {{-- Parent trust panel --}}
            <div class="fade-right lg:col-span-5">
                <div class="relative flex h-full min-h-[620px] flex-col overflow-hidden rounded-[2rem] bg-gradient-to-br from-primary-900 via-primary-800 to-secondary-900 p-6 text-white shadow-2xl shadow-primary-900/20 sm:p-8 lg:p-9">
                    <div class="pointer-events-none absolute -right-24 -top-24 h-72 w-72 rounded-full border-[55px] border-white/[0.04]" aria-hidden="true"></div>
                    <div class="pointer-events-none absolute -bottom-24 -left-20 h-64 w-64 rounded-full bg-secondary-400/15 blur-3xl" aria-hidden="true"></div>
                    <div class="pointer-events-none absolute inset-0 opacity-[0.05]" style="background-image: radial-gradient(circle at 1px 1px, white 1px, transparent 0); background-size: 28px 28px;" aria-hidden="true"></div>

                    <div class="relative">
                        <span class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-3.5 py-2 text-xs font-bold text-primary-50 backdrop-blur-sm">
                            <i data-lucide="badge-check" class="h-4 w-4 text-secondary-300" aria-hidden="true"></i>
                            Dirancang untuk kebutuhan anak
                        </span>
                        <h3 class="mt-6 max-w-md font-heading text-3xl font-extrabold leading-tight sm:text-4xl">
                            Bukan sekadar ruang, tetapi bagian dari proses belajar.
                        </h3>
                        <p class="mt-4 max-w-lg text-sm leading-7 text-primary-100 sm:text-base">
                            Setiap fasilitas dipilih untuk membantu anak merasa tenang, aktif, dan siap mengikuti kegiatan belajar bersama guru serta teman-temannya.
                        </p>
                    </div>

                    <div class="relative mt-8 grid gap-4">
                        @foreach ($facilitySupports as $support)
                            <div class="group flex items-start gap-4 rounded-2xl border border-white/10 bg-white/[0.07] p-4 backdrop-blur-sm transition-colors duration-300 hover:bg-white/[0.12]">
                                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl ring-1 ring-inset transition-transform duration-300 transform-gpu will-change-transform group-hover:scale-105 {{ $support['style'] }}">
                                    <i data-lucide="{{ $support['icon'] }}" class="h-5 w-5" aria-hidden="true"></i>
                                </span>
                                <div>
                                    <h4 class="font-heading text-sm font-bold text-white sm:text-base">{{ $support['title'] }}</h4>
                                    <p class="mt-1 text-xs leading-5 text-primary-100/80 sm:text-sm sm:leading-6">{{ $support['desc'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="relative mt-auto border-t border-white/10 pt-7">
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                            <div class="flex items-center gap-3">
                                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-secondary-400/15 text-secondary-200 ring-1 ring-secondary-300/20">
                                    <i data-lucide="camera" class="h-5 w-5" aria-hidden="true"></i>
                                </span>
                                <p class="text-xs leading-5 text-primary-100">Lihat suasana belajar dan kegiatan anak lainnya.</p>
                            </div>
                            <a href="#gallery"
                               class="inline-flex shrink-0 items-center justify-center gap-2 rounded-xl bg-white px-4 py-2.5 text-xs font-extrabold text-primary-700 shadow-lg transition-all duration-300 hover:-translate-y-0.5 hover:bg-primary-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-offset-2 focus-visible:ring-offset-primary-800">
                                Lihat Galeri
                                <i data-lucide="arrow-up-right" class="h-4 w-4" aria-hidden="true"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Trust summary --}}
        <div class="fade-up mt-8 grid overflow-hidden rounded-[1.5rem] border border-white/90 bg-white/75 shadow-lg shadow-primary-900/[0.04] backdrop-blur-md sm:grid-cols-3">
            @foreach ([
                ['icon' => 'shield-check', 'label' => 'Aman', 'text' => 'Ruang dan aktivitas disesuaikan dengan kebutuhan anak.'],
                ['icon' => 'focus', 'label' => 'Kondusif', 'text' => 'Lingkungan mendukung interaksi serta pembelajaran aktif.'],
                ['icon' => 'smile', 'label' => 'Nyaman', 'text' => 'Anak belajar dengan suasana hangat dan menyenangkan.'],
            ] as $point)
                <div class="flex items-start gap-4 border-b border-gray-100 p-5 last:border-b-0 sm:border-b-0 sm:border-r sm:p-6 sm:last:border-r-0">
                    <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-primary-50 to-secondary-50 text-primary-600 ring-1 ring-primary-100">
                        <i data-lucide="{{ $point['icon'] }}" class="h-5 w-5" aria-hidden="true"></i>
                    </span>
                    <div>
                        <p class="font-heading text-base font-extrabold text-gray-900">{{ $point['label'] }}</p>
                        <p class="mt-1 text-xs leading-5 text-gray-500 sm:text-sm">{{ $point['text'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
