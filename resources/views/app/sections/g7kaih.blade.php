{{-- Section: Gerakan 7 Kebiasaan Hebat Anak Indonesia --}}
<section id="g7kaih" class="relative overflow-hidden py-20 lg:py-28">
    <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-primary-200 to-transparent"></div>
    <div class="absolute inset-x-0 bottom-0 h-px bg-gradient-to-r from-transparent via-secondary-200 to-transparent"></div>

    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid items-center gap-12 lg:grid-cols-[0.9fr_1.1fr] lg:gap-16">
            <div class="fade-left">
                <span class="mb-5 inline-flex items-center gap-2 rounded-full border border-primary-200 bg-primary-50 px-4 py-2 text-sm font-semibold text-primary-700">
                    <i data-lucide="sparkles" class="h-4 w-4" aria-hidden="true"></i>
                    G7KAIH
                </span>

                <h2 class="section-heading section-heading-left font-heading text-gray-900">
                    <span>Gerakan 7 Kebiasaan <span class="gradient-text">Hebat Anak Indonesia</span></span>
                </h2>

                <p class="mt-6 max-w-xl text-base text-justify leading-8 text-gray-600 sm:text-lg">
                    PAUD Al Qur'an Azzahra mendukung pembiasaan harian yang membantu anak tumbuh sehat, mandiri,
                    disiplin, senang belajar, dan peduli kepada lingkungan sekitarnya.
                </p>

                <div class="mt-8 grid max-w-lg grid-cols-3 gap-3">
                    <div class="rounded-lg border border-primary-100 bg-primary-50/70 p-4">
                        <p class="font-heading text-2xl font-extrabold text-primary-700">7</p>
                        <p class="mt-1 text-xs font-semibold text-gray-600">Kebiasaan utama</p>
                    </div>
                    <div class="rounded-lg border border-secondary-100 bg-secondary-50/80 p-4">
                        <p class="font-heading text-2xl font-extrabold text-secondary-700">2</p>
                        <p class="mt-1 text-xs font-semibold text-gray-600">Ruang pembiasaan</p>
                    </div>
                    <div class="rounded-lg border border-amber-100 bg-amber-50/80 p-4">
                        <p class="font-heading text-2xl font-extrabold text-amber-700">1</p>
                        <p class="mt-1 text-xs font-semibold text-gray-600">Tujuan tumbuh baik</p>
                    </div>
                </div>
            </div>

            <div class="fade-right">
                <div class="relative mx-auto max-w-xl rounded-lg border border-primary-100 bg-gradient-to-br from-primary-50 via-white to-secondary-50 p-6 shadow-xl shadow-primary-900/5 sm:p-8">
                    <div class="grid gap-5 sm:grid-cols-[auto_1fr] sm:items-center">
                        <div class="flex h-28 w-28 items-center justify-center rounded-lg bg-gradient-to-br from-primary-600 to-secondary-600 font-heading text-7xl font-black text-white shadow-lg shadow-primary-600/20 sm:h-36 sm:w-36 sm:text-8xl">
                            7
                        </div>
                        <div>
                            <p class="text-xs font-bold uppercase tracking-[0.22em] text-primary-600">PAUD Al Qur'an Azzahra</p>
                            <h3 class="mt-2 font-heading text-2xl font-extrabold leading-tight text-gray-900 sm:text-3xl">
                                Kebiasaan kecil yang dibangun setiap hari.
                            </h3>
                            <p class="mt-3 text-sm leading-6 text-gray-600">
                                Diselaraskan antara kegiatan sekolah dan dukungan keluarga di rumah.
                            </p>
                        </div>
                    </div>

                    <div class="mt-7 grid grid-cols-2 gap-3 sm:grid-cols-4">
                        <span class="rounded-lg bg-white px-3 py-2 text-center text-xs font-bold text-primary-700 shadow-sm ring-1 ring-primary-100">Sehat</span>
                        <span class="rounded-lg bg-white px-3 py-2 text-center text-xs font-bold text-secondary-700 shadow-sm ring-1 ring-secondary-100">Mandiri</span>
                        <span class="rounded-lg bg-white px-3 py-2 text-center text-xs font-bold text-amber-700 shadow-sm ring-1 ring-amber-100">Disiplin</span>
                        <span class="rounded-lg bg-white px-3 py-2 text-center text-xs font-bold text-rose-700 shadow-sm ring-1 ring-rose-100">Peduli</span>
                    </div>
                </div>
            </div>
        </div>

        @php
            $habits = [
                [
                    'title' => 'Bangun Pagi',
                    'desc' => 'Melatih disiplin, kesiapan belajar, dan semangat menyambut hari.',
                    'icon' => 'sun',
                    'gradient' => 'from-sky-500 to-blue-600',
                    'soft' => 'bg-sky-50',
                    'border' => 'border-sky-100',
                    'text' => 'text-sky-700',
                ],
                [
                    'title' => 'Beribadah',
                    'desc' => 'Menanamkan cinta ibadah, doa, dan rasa syukur sejak dini.',
                    'icon' => 'heart',
                    'gradient' => 'from-emerald-500 to-green-600',
                    'soft' => 'bg-emerald-50',
                    'border' => 'border-emerald-100',
                    'text' => 'text-emerald-700',
                ],
                [
                    'title' => 'Olahraga',
                    'desc' => 'Membiasakan tubuh aktif, sehat, dan kuat melalui gerak yang menyenangkan.',
                    'icon' => 'activity',
                    'gradient' => 'from-orange-500 to-amber-500',
                    'soft' => 'bg-orange-50',
                    'border' => 'border-orange-100',
                    'text' => 'text-orange-700',
                ],
                [
                    'title' => 'Makan Bergizi',
                    'desc' => 'Mengenalkan pilihan makanan sehat untuk mendukung tumbuh kembang anak.',
                    'icon' => 'utensils',
                    'gradient' => 'from-lime-500 to-green-600',
                    'soft' => 'bg-lime-50',
                    'border' => 'border-lime-100',
                    'text' => 'text-lime-700',
                ],
                [
                    'title' => 'Tidur Cepat',
                    'desc' => 'Menjaga istirahat cukup agar anak lebih fokus, ceria, dan siap beraktivitas.',
                    'icon' => 'moon',
                    'gradient' => 'from-indigo-500 to-violet-600',
                    'soft' => 'bg-indigo-50',
                    'border' => 'border-indigo-100',
                    'text' => 'text-indigo-700',
                ],
                [
                    'title' => 'Bermasyarakat',
                    'desc' => 'Belajar menyapa, berbagi, bekerja sama, dan peduli pada sesama.',
                    'icon' => 'users',
                    'gradient' => 'from-rose-500 to-pink-600',
                    'soft' => 'bg-rose-50',
                    'border' => 'border-rose-100',
                    'text' => 'text-rose-700',
                ],
                [
                    'title' => 'Gemar Belajar',
                    'desc' => 'Menumbuhkan rasa ingin tahu, literasi, dan keberanian bertanya.',
                    'icon' => 'book-open',
                    'gradient' => 'from-cyan-500 to-teal-600',
                    'soft' => 'bg-cyan-50',
                    'border' => 'border-cyan-100',
                    'text' => 'text-cyan-700',
                ],
            ];
        @endphp

        <div class="mt-12 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-12 stagger-children">
            @foreach ($habits as $habit)
                @php
                    $spanClass = $loop->last ? 'lg:col-span-4 lg:col-start-5' : 'lg:col-span-4';
                @endphp
                <article class="group relative overflow-hidden rounded-lg border {{ $habit['border'] }} bg-white p-5 shadow-sm transition-[transform,box-shadow] duration-300 transform-gpu will-change-[transform,box-shadow] hover:-translate-y-1 hover:shadow-xl hover:shadow-primary-900/5 {{ $spanClass }}">
                    <div class="absolute left-0 top-0 h-1 w-full bg-gradient-to-r {{ $habit['gradient'] }} opacity-80"></div>
                    <div class="flex items-start gap-4">
                        <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-lg bg-gradient-to-br {{ $habit['gradient'] }} text-white shadow-md transition-transform duration-300 transform-gpu will-change-transform group-hover:scale-105">
                            <i data-lucide="{{ $habit['icon'] }}" class="h-6 w-6" aria-hidden="true"></i>
                        </span>
                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="rounded-md {{ $habit['soft'] }} px-2 py-1 text-xs font-extrabold {{ $habit['text'] }}">
                                    {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}
                                </span>
                                <h3 class="font-heading text-lg font-bold text-gray-900">{{ $habit['title'] }}</h3>
                            </div>
                            <p class="mt-2 text-sm leading-6 text-gray-600">{{ $habit['desc'] }}</p>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>
