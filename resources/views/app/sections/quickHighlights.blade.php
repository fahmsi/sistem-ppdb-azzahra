{{-- Section: Quick Highlights --}}
<section id="keunggulan" class="relative overflow-hidden py-20 lg:py-28">
    {{-- Soft accents that blend into the shared landing background --}}
    <div class="pointer-events-none absolute -left-32 top-20 h-72 w-72 rounded-full bg-primary-100/35 blur-3xl" aria-hidden="true"></div>
    <div class="pointer-events-none absolute -right-24 bottom-10 h-72 w-72 rounded-full bg-secondary-100/35 blur-3xl" aria-hidden="true"></div>

    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid items-start gap-12 lg:grid-cols-12 lg:gap-10 xl:gap-16">
            {{-- Editorial introduction --}}
            <div class="fade-left lg:col-span-4 lg:pt-3">
                <span class="inline-flex items-center gap-2 rounded-full border border-primary-200 bg-white/80 px-4 py-2 text-sm font-semibold text-primary-700 shadow-sm backdrop-blur-sm">
                    <i data-lucide="sparkles" class="h-4 w-4" aria-hidden="true"></i>
                    Pilihan untuk masa emas anak
                </span><br>

                <h2 class="section-heading section-heading-left mt-6 font-heading text-gray-900">
                    <span>Mengapa Memilih <span class="gradient-text">Azzahra?</span></span>
                </h2>

                <p class="mt-6 max-w-xl text-base text-justify leading-7 text-gray-600 lg:text-lg">
                    Kami mendampingi setiap anak untuk bertumbuh dengan gembira melalui pengalaman belajar yang seimbang, aman, dan berlandaskan nilai Qur'ani.
                </p>

                <div class="mt-8 flex flex-wrap gap-2.5" aria-label="Nilai utama Azzahra">
                    @foreach (['Ramah anak', 'Belajar aktif', "Nilai Qur'ani"] as $value)
                        <span class="inline-flex items-center gap-2 rounded-xl border border-white/90 bg-white/70 px-3 py-2 text-xs font-bold text-gray-700 shadow-sm backdrop-blur-sm">
                            <i data-lucide="circle-check" class="h-4 w-4 text-secondary-500" aria-hidden="true"></i>
                            {{ $value }}
                        </span>
                    @endforeach
                </div>
            </div>

            @php
                $highlights = [
                    [
                        'title' => 'Fasilitas Modern',
                        'eyebrow' => 'Ruang siap belajar',
                        'desc' => 'Ruang kelas ber-AC, kolam mandi bola, dan area bermain yang mendukung eksplorasi anak dengan nyaman.',
                        'note' => 'Nyaman untuk belajar dan bermain',
                        'icon' => 'school',
                        'gradient' => 'from-blue-500 to-indigo-600',
                        'soft' => 'bg-blue-50 text-blue-600 ring-blue-100',
                        'glow' => 'bg-blue-200/60',
                        'border' => 'hover:border-blue-200',
                        'href' => '#fasilitas',
                        'link_label' => 'Lihat fasilitas',
                    ],
                    [
                        'title' => 'Prestasi Gemilang',
                        'eyebrow' => 'Potensi diapresiasi',
                        'desc' => 'Setiap bakat dan keberanian anak dihargai melalui pengalaman serta berbagai kegiatan yang membangun percaya diri.',
                        'note' => 'Tumbuh berani dan percaya diri',
                        'icon' => 'trophy',
                        'gradient' => 'from-amber-400 to-orange-500',
                        'soft' => 'bg-amber-50 text-amber-600 ring-amber-100',
                        'glow' => 'bg-amber-200/60',
                        'border' => 'hover:border-amber-200',
                        'href' => '#program',
                        'link_label' => 'Lihat prestasi',
                    ],
                    [
                        'title' => 'Program Unggulan',
                        'eyebrow' => 'Belajar menyeluruh',
                        'desc' => 'Kurikulum Merdeka dipadukan dengan pembelajaran berbasis Al-Qur’an dan metode yang menyenangkan bagi anak.',
                        'note' => 'Akademik dan karakter seimbang',
                        'icon' => 'book-open',
                        'gradient' => 'from-emerald-500 to-green-600',
                        'soft' => 'bg-emerald-50 text-emerald-600 ring-emerald-100',
                        'glow' => 'bg-emerald-200/60',
                        'border' => 'hover:border-emerald-200',
                        'href' => '#program',
                        'link_label' => 'Lihat program',
                    ],
                    [
                        'title' => 'Lingkungan Kondusif',
                        'eyebrow' => 'Aman dan suportif',
                        'desc' => 'Lingkungan sekolah yang nyaman, aman, Islami, dan ramah anak membantu proses belajar terasa lebih menyenangkan.',
                        'note' => 'Didampingi dengan penuh perhatian',
                        'icon' => 'trees',
                        'gradient' => 'from-teal-500 to-cyan-600',
                        'soft' => 'bg-teal-50 text-teal-600 ring-teal-100',
                        'glow' => 'bg-teal-200/60',
                        'border' => 'hover:border-teal-200',
                        'href' => '#tentang',
                        'link_label' => 'Tentang sekolah',
                    ],
                ];
            @endphp

            {{-- Bento highlight cards --}}
            <div class="fade-up grid gap-5 sm:grid-cols-2 lg:col-span-8">
                @foreach ($highlights as $item)
                    <a href="{{ $item['href'] }}"
                       class="group relative flex min-h-[285px] transform-gpu cursor-pointer flex-col overflow-hidden rounded-[1.75rem] border border-white/90 bg-white/90 p-6 shadow-lg shadow-primary-900/[0.05] transition-[transform,box-shadow,border-color] duration-200 ease-out will-change-transform hover:-translate-y-0.5 hover:shadow-xl hover:shadow-primary-900/10 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 focus-visible:ring-offset-2 motion-reduce:transform-none motion-reduce:transition-none sm:p-7 {{ $item['border'] }}"
                       aria-label="{{ $item['link_label'] }}: {{ $item['title'] }}">
                        <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r {{ $item['gradient'] }}"></div>
                        <div class="absolute -right-14 -top-14 h-36 w-36 rounded-full {{ $item['glow'] }} opacity-40 blur-2xl" aria-hidden="true"></div>
                        <span class="absolute right-6 top-5 font-heading text-4xl font-black text-gray-300 transition-colors duration-300 group-hover:text-gray-200/80" aria-hidden="true">
                            {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}
                        </span>

                        <div class="relative mb-5 flex items-center gap-4 pr-12">
                            <span class="flex h-12 w-12 shrink-0 transform-gpu items-center justify-center rounded-2xl shadow-sm ring-1 ring-inset transition-[transform,box-shadow] duration-200 ease-out will-change-transform group-hover:-translate-y-0.5 group-hover:shadow-md motion-reduce:transform-none motion-reduce:transition-none {{ $item['soft'] }}">
                                <i data-lucide="{{ $item['icon'] }}" class="h-6 w-6" aria-hidden="true"></i>
                            </span>
                            <p class="text-[11px] font-extrabold uppercase tracking-[0.16em] text-gray-400">
                                {{ $item['eyebrow'] }}
                            </p>
                        </div>

                        <h3 class="relative font-heading text-xl font-extrabold text-gray-900 sm:text-2xl">
                            {{ $item['title'] }}
                        </h3>
                        <p class="relative mt-3 text-sm leading-6 text-gray-600">
                            {{ $item['desc'] }}
                        </p>

                        <div class="relative mt-auto flex items-end justify-between gap-4 border-t border-gray-100 pt-5">
                            <span class="inline-flex items-center gap-2 text-xs font-semibold text-gray-500">
                                <span class="h-1.5 w-1.5 rounded-full bg-secondary-500"></span>
                                {{ $item['note'] }}
                            </span>
                            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-gray-50 text-gray-500 transition-colors duration-200 group-hover:bg-primary-600 group-hover:text-white" aria-hidden="true">
                                <i data-lucide="arrow-up-right" class="h-4 w-4" aria-hidden="true"></i>
                            </span>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- Section: Legalitas Izin Resmi --}}
<section id="legalitas" class="relative overflow-hidden py-20 lg:py-28">
    <div class="absolute left-0 top-0 h-full w-full islamic-pattern opacity-70"></div>

    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        @php
            $permitScanFilename = 'surat izin resmi.jpg';
            $permitScanPath = public_path('images/' . $permitScanFilename);
            $hasPermitScan = file_exists($permitScanPath);
        @endphp

        <div class="mx-auto mb-12 max-w-3xl text-center fade-up">
            <span class="mb-5 inline-flex items-center gap-2 rounded-full border border-secondary-200 bg-secondary-50 px-4 py-2 text-sm font-semibold text-secondary-700">
                <i data-lucide="shield-check" class="h-4 w-4" aria-hidden="true"></i>
                Legalitas Sekolah
            </span><br>
            <h2 class="section-heading font-heading text-gray-900">
                <span>PAUD Al Qur'an Azzahra <span class="gradient-text">Berizin Resmi</span></span>
            </h2>
            <p class="mx-auto mt-5 max-w-2xl text-base leading-relaxed text-gray-600 sm:text-lg">
                Kegiatan pendidikan PAUD Al Qur'an Azzahra didukung surat izin pendirian dan penyelenggaraan
                Pendidikan Anak Usia Dini dari Pemerintah Kota Depok.
            </p>
        </div>

        <div class="grid items-stretch gap-6 lg:grid-cols-[0.95fr_1.05fr] lg:gap-8">
            <div class="fade-left">
                <div class="h-full rounded-lg border border-slate-200 bg-white p-6 shadow-xl shadow-slate-900/5 sm:p-8">
                    <div class="flex flex-col gap-5 sm:flex-row sm:items-start">
                        <span class="flex h-16 w-16 shrink-0 items-center justify-center rounded-lg bg-gradient-to-br from-primary-600 to-secondary-600 text-white shadow-lg shadow-primary-600/20">
                            <i data-lucide="file-check-2" class="h-8 w-8" aria-hidden="true"></i>
                        </span>
                        <div>
                            <p class="text-xs font-bold uppercase tracking-[0.22em] text-primary-600">Pemerintah Kota Depok</p>
                            <h3 class="mt-2 font-heading text-2xl font-extrabold leading-tight text-gray-900 sm:text-3xl">
                                Surat Izin Pendirian dan Penyelenggaraan PAUD
                            </h3>
                            <p class="mt-3 text-sm leading-6 text-gray-600">
                                Diterbitkan oleh Dinas Penanaman Modal dan Pelayanan Terpadu Satu Pintu Kota Depok.
                            </p>
                        </div>
                    </div>

                    <div class="mt-8 grid gap-4 sm:grid-cols-2">
                        <div class="rounded-lg border border-primary-100 bg-primary-50/70 p-4">
                            <p class="text-xs font-bold uppercase tracking-[0.18em] text-primary-600">Nomor Izin</p>
                            <p class="mt-2 break-words font-heading text-lg font-extrabold text-gray-900">421.1/0185/DPMPTSP/VII/2024</p>
                        </div>
                        <div class="rounded-lg border border-secondary-100 bg-secondary-50/80 p-4">
                            <p class="text-xs font-bold uppercase tracking-[0.18em] text-secondary-700">Tanggal Terbit</p>
                            <p class="mt-2 font-heading text-lg font-extrabold text-gray-900">12 Juli 2024</p>
                        </div>
                    </div>

                    <div class="mt-6 rounded-lg border border-amber-100 bg-amber-50/80 p-4">
                        <div class="flex items-start gap-3">
                            <span class="mt-0.5 flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-white text-amber-700 shadow-sm ring-1 ring-amber-100">
                                <i data-lucide="badge-check" class="h-5 w-5" aria-hidden="true"></i>
                            </span>
                            <div>
                                <p class="font-heading text-base font-bold text-gray-900">Jenis izin</p>
                                <p class="mt-1 text-sm leading-6 text-gray-600">
                                    Mendirikan dan menyelenggarakan Pendidikan Non Formal - PAUD.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-7">
                        <button type="button" data-open-permit-modal
                            class="inline-flex items-center justify-center gap-2 rounded-lg bg-primary-600 px-5 py-3 text-sm font-bold text-white shadow-md shadow-primary-600/20 transition-all duration-200 hover:-translate-y-0.5 hover:bg-primary-700 hover:shadow-lg focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 focus-visible:ring-offset-2">
                            <i data-lucide="scan-search" class="h-4 w-4" aria-hidden="true"></i>
                            Lihat Surat Izin
                        </button>
                    </div>
                </div>
            </div>

            <div class="fade-right">
                <div class="h-full rounded-lg border border-slate-200 bg-white p-6 shadow-xl shadow-slate-900/5 sm:p-8">
                    @php
                        $permitDetails = [
                            ['label' => 'Nama Badan Usaha', 'value' => 'Yayasan Cahaya Annisa', 'icon' => 'building-2'],
                            ['label' => 'Nama Sekolah', 'value' => "PAUD AL QUR'AN AZZAHRA", 'icon' => 'school'],
                            ['label' => 'Kepala Sekolah', 'value' => 'RAHMANI, S.Pd.I', 'icon' => 'user-round-check'],
                            ['label' => 'Alamat Sekolah', 'value' => 'Jl. Serimpi V No. 338 RT. 004 RW. 010 Kel. Mekarjaya, Kec. Sukmajaya, Kota Depok, Jawa Barat', 'icon' => 'map-pin'],
                        ];
                    @endphp

                    <div class="mb-6 flex items-center justify-between gap-4">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-[0.22em] text-secondary-700">Data Perizinan</p>
                            <h3 class="mt-2 font-heading text-2xl font-extrabold text-gray-900">Identitas lembaga pada surat izin</h3>
                        </div>
                        <span class="hidden h-12 w-12 shrink-0 items-center justify-center rounded-lg bg-secondary-50 text-secondary-700 ring-1 ring-secondary-100 sm:flex">
                            <i data-lucide="landmark" class="h-6 w-6" aria-hidden="true"></i>
                        </span>
                    </div>

                    <div class="grid gap-3">
                        @foreach ($permitDetails as $detail)
                            <div class="rounded-lg border border-slate-100 bg-slate-50/70 p-4">
                                <div class="flex items-start gap-3">
                                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-white text-primary-600 shadow-sm ring-1 ring-slate-200">
                                        <i data-lucide="{{ $detail['icon'] }}" class="h-5 w-5" aria-hidden="true"></i>
                                    </span>
                                    <div class="min-w-0">
                                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-gray-500">{{ $detail['label'] }}</p>
                                        <p class="mt-1 text-sm font-semibold leading-6 text-gray-900">{{ $detail['value'] }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-6 flex flex-col gap-3 rounded-lg border border-secondary-100 bg-secondary-50/70 p-4 sm:flex-row sm:items-center">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-white text-secondary-700 shadow-sm ring-1 ring-secondary-100">
                            <i data-lucide="qr-code" class="h-5 w-5" aria-hidden="true"></i>
                        </span>
                        <p class="text-sm leading-6 text-gray-600">
                            Surat izin dilengkapi tanda tangan elektronik dan kode verifikasi pada dokumen resmi.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div
    id="permit-modal"
    class="fixed inset-0 z-[200] hidden overflow-y-auto bg-slate-950/70 p-4 opacity-0 backdrop-blur-sm transition-opacity duration-200 sm:p-6"
    role="dialog"
    aria-modal="true"
    aria-hidden="true"
    aria-labelledby="permit-modal-title"
    data-permit-modal-backdrop>
    <div class="flex min-h-full items-center justify-center">
        <div
            id="permit-modal-panel"
            class="relative w-full max-w-5xl translate-y-3 scale-95 overflow-hidden rounded-lg bg-white shadow-2xl shadow-slate-950/25 transition-all duration-200"
            tabindex="-1">
            <div class="flex items-center justify-between gap-4 border-b border-slate-200 px-4 py-4 sm:px-6">
                <div class="min-w-0">
                    <p class="text-xs font-bold uppercase tracking-[0.2em] text-primary-600">Dokumen Legalitas</p>
                    <h3 id="permit-modal-title" class="mt-1 truncate font-heading text-lg font-extrabold text-gray-900 sm:text-2xl">
                        Surat Izin PAUD Al Qur'an Azzahra
                    </h3>
                </div>
                <button
                    type="button"
                    data-close-permit-modal
                    class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg text-slate-500 transition-colors hover:bg-slate-100 hover:text-slate-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500"
                    aria-label="Tutup surat izin">
                    <i data-lucide="x" class="h-5 w-5" aria-hidden="true"></i>
                </button>
            </div>

            <div class="max-h-[78vh] overflow-y-auto bg-slate-100 p-4 sm:p-6">
                @if ($hasPermitScan)
                    <div class="mx-auto max-w-3xl overflow-hidden rounded-lg border border-slate-200 bg-white shadow-lg">
                        <img
                            src="{{ asset('images/' . $permitScanFilename) }}"
                            alt="Surat izin resmi PAUD Al Qur'an Azzahra"
                            class="h-auto w-full object-contain"
                            loading="lazy">
                    </div>
                @else
                    <div class="mx-auto max-w-3xl rounded-lg border border-slate-200 bg-white p-5 text-slate-900 shadow-lg sm:p-8">
                        <div class="flex items-start gap-4 border-b border-slate-300 pb-5">
                            <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-lg border border-slate-200 bg-slate-50 text-primary-700">
                                <i data-lucide="landmark" class="h-8 w-8" aria-hidden="true"></i>
                            </div>
                            <div class="text-center sm:flex-1">
                                <p class="font-heading text-lg font-extrabold uppercase tracking-wide text-slate-900">Pemerintah Kota Depok</p>
                                <p class="mt-1 text-sm font-bold uppercase leading-6 text-slate-800">
                                    Dinas Penanaman Modal dan Pelayanan Terpadu Satu Pintu
                                </p>
                                <p class="mt-1 text-xs leading-5 text-slate-500">
                                    Jl. Margonda Raya No. 54 Depok 16431
                                </p>
                            </div>
                        </div>

                        <div class="py-8 text-center">
                            <p class="font-heading text-2xl font-extrabold uppercase tracking-wide text-slate-900">Surat Izin</p>
                            <p class="mt-3 text-base font-bold text-slate-800">
                                Nomor: 421.1/0185/DPMPTSP/VII/2024
                            </p>
                            <p class="mt-5 text-sm font-semibold text-slate-600">Tentang</p>
                            <p class="mx-auto mt-2 max-w-xl text-base font-extrabold uppercase leading-7 text-slate-900">
                                Izin Pendirian dan Penyelenggaraan Pendidikan Anak Usia Dini
                            </p>
                        </div>

                        <div class="rounded-lg border border-slate-200 bg-slate-50 p-4 sm:p-5">
                            <p class="mb-4 text-center font-heading text-lg font-extrabold uppercase text-slate-900">Mengizinkan</p>

                            <dl class="grid gap-4 text-sm sm:grid-cols-[180px_1fr]">
                                <dt class="font-bold text-slate-500">Nama Badan Usaha</dt>
                                <dd class="font-semibold text-slate-900">Yayasan Cahaya Annisa</dd>

                                <dt class="font-bold text-slate-500">Nama Sekolah</dt>
                                <dd class="font-semibold text-slate-900">PAUD AL QUR'AN AZZAHRA</dd>

                                <dt class="font-bold text-slate-500">Alamat Sekolah</dt>
                                <dd class="font-semibold leading-6 text-slate-900">
                                    Jl. Serimpi V No. 338 RT. 004 RW. 010 Kel. Mekarjaya, Kec. Sukmajaya, Kota Depok, Jawa Barat
                                </dd>

                                <dt class="font-bold text-slate-500">Kepala Sekolah</dt>
                                <dd class="font-semibold text-slate-900">RAHMANI, S.Pd.I</dd>

                                <dt class="font-bold text-slate-500">Peruntukan</dt>
                                <dd class="font-semibold leading-6 text-slate-900">
                                    Mendirikan dan menyelenggarakan Pendidikan Non Formal - PAUD.
                                </dd>
                            </dl>
                        </div>

                        <div class="mt-6 grid gap-4 border-t border-slate-200 pt-5 sm:grid-cols-[1fr_auto] sm:items-end">
                            <div>
                                <p class="text-sm font-semibold text-slate-600">Dikeluarkan di Depok</p>
                                <p class="mt-1 text-sm font-bold text-slate-900">Pada tanggal: 12 Juli 2024</p>
                                <p class="mt-3 text-xs leading-5 text-slate-500">
                                    Dokumen telah ditandatangani secara elektronik oleh Kepala Dinas Penanaman Modal dan Pelayanan Terpadu Satu Pintu.
                                </p>
                            </div>
                            <div class="flex items-center gap-3 rounded-lg border border-slate-200 bg-white p-3">
                                <span class="flex h-12 w-12 items-center justify-center rounded-md bg-slate-100 text-slate-700">
                                    <i data-lucide="qr-code" class="h-7 w-7" aria-hidden="true"></i>
                                </span>
                                <div>
                                    <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-500">Verifikasi</p>
                                    <p class="mt-1 text-xs font-semibold text-slate-900">Kode QR dokumen resmi</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="flex flex-col-reverse gap-3 border-t border-slate-200 bg-white px-4 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-6">
                <p class="text-xs leading-5 text-slate-500">
                    Ringkasan dokumen ditampilkan untuk membantu orang tua memeriksa legalitas sekolah.
                </p>
                <button
                    type="button"
                    data-close-permit-modal
                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-bold text-white transition-colors hover:bg-slate-800 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 focus-visible:ring-offset-2">
                    <i data-lucide="check" class="h-4 w-4" aria-hidden="true"></i>
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const modal = document.getElementById('permit-modal');
        const panel = document.getElementById('permit-modal-panel');
        const openButtons = document.querySelectorAll('[data-open-permit-modal]');
        const closeButtons = document.querySelectorAll('[data-close-permit-modal]');

        if (!modal || !panel || openButtons.length === 0) {
            return;
        }

        // Pindahkan modal ke document.body agar tidak terhalang stacking context/z-index navbar
        document.body.appendChild(modal);

        let lastFocusedElement = null;
        let closeTimer = null;

        const openModal = () => {
            window.clearTimeout(closeTimer);
            lastFocusedElement = document.activeElement;
            modal.classList.remove('hidden');
            modal.setAttribute('aria-hidden', 'false');
            document.body.classList.add('overflow-hidden');

            window.requestAnimationFrame(() => {
                modal.classList.remove('opacity-0');
                panel.classList.remove('translate-y-3', 'scale-95');
                panel.focus({ preventScroll: true });
            });

            if (window.lucide && typeof window.lucide.createIcons === 'function') {
                window.lucide.createIcons();
            }
        };

        const closeModal = () => {
            modal.classList.add('opacity-0');
            panel.classList.add('translate-y-3', 'scale-95');
            modal.setAttribute('aria-hidden', 'true');
            document.body.classList.remove('overflow-hidden');

            closeTimer = window.setTimeout(() => {
                modal.classList.add('hidden');
                if (lastFocusedElement && typeof lastFocusedElement.focus === 'function') {
                    lastFocusedElement.focus({ preventScroll: true });
                }
            }, 200);
        };

        openButtons.forEach((button) => button.addEventListener('click', openModal));
        closeButtons.forEach((button) => button.addEventListener('click', closeModal));

        // Menutup modal ketika area luar panel diklik
        modal.addEventListener('mousedown', function (event) {
            if (!panel.contains(event.target)) {
                closeModal();
            }
        });

        document.addEventListener('keydown', function (event) {
            if (modal.classList.contains('hidden')) {
                return;
            }

            if (event.key === 'Escape') {
                closeModal();
            }
        });
    });
</script>
