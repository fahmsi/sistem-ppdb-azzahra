@extends('app.layouts.legal')

@section('title', 'Syarat dan Ketentuan Penggunaan - PAUD Al-Qur’an Azzahra')
@section('description', 'Syarat dan ketentuan penggunaan website dan sistem SPMB PAUD Al-Qur’an Azzahra.')

@section('content')
<section class="relative min-h-screen overflow-hidden bg-gradient-to-b from-primary-50 via-white to-gray-50 pb-20 pt-28 sm:pb-24 sm:pt-32">
    {{-- Decorative background elements --}}
    <div class="pointer-events-none absolute inset-0">
        <div class="absolute -right-32 -top-32 h-96 w-96 rounded-full bg-primary-100/40 blur-3xl"></div>
        <div class="absolute -bottom-32 -left-32 h-96 w-96 rounded-full bg-secondary-100/30 blur-3xl"></div>
    </div>

    <div class="relative mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">

        {{-- Page Header --}}
        <header class="mb-10 text-center sm:mb-14">
            <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-primary-100 text-primary-600 shadow-sm">
                <i data-lucide="scale" class="h-7 w-7"></i>
            </div>
            <p class="mb-2 text-sm font-bold uppercase tracking-[0.14em] text-primary-600">Informasi Legal</p>
            <h1 class="text-3xl font-extrabold tracking-tight text-gray-900 sm:text-4xl lg:text-5xl">
                Syarat dan Ketentuan Penggunaan
            </h1>
            <p class="mx-auto mt-3 max-w-xl text-base text-gray-500">
                Ketentuan penggunaan website dan sistem SPMB PAUD Al-Qur’an Azzahra.
            </p>
            <div class="mx-auto mt-5 flex flex-wrap items-center justify-center gap-x-2 gap-y-1 text-sm text-gray-400">
                <i data-lucide="calendar" class="h-4 w-4"></i>
                <span>Berlaku sejak: 27 Juni 2026</span>
                <span aria-hidden="true">&bull;</span>
                <span>Versi: 1.0</span>
            </div>
        </header>

        {{-- Content Card --}}
        <article class="rounded-2xl border border-gray-200/80 bg-white/80 shadow-xl shadow-gray-900/[0.04] backdrop-blur-sm">

            @php
                $sections = [
                    [
                        'icon' => 'info',
                        'title' => 'Pengantar',
                        'type' => 'text',
                        'content' => 'Ketentuan ini mengatur penggunaan website dan Sistem Penerimaan Murid Baru (SPMB) PAUD Al-Qur’an Azzahra oleh orang tua atau wali, admin, dan super admin. Pengguna wajib membaca dan memahami ketentuan ini sebelum menggunakan layanan.',
                    ],
                    [
                        'icon' => 'school',
                        'title' => 'Identitas Pengelola',
                        'type' => 'text',
                        'content' => 'Website SPMB ini dikelola oleh PAUD Al-Qur’an Azzahra sebagai media pendukung proses penerimaan murid baru. Seluruh informasi, instruksi, dan proses administrasi yang ditampilkan dalam sistem digunakan untuk kepentingan pendaftaran dan administrasi sekolah atau yayasan.',
                    ],
                    [
                        'icon' => 'monitor',
                        'title' => 'Penggunaan Website',
                        'type' => 'text',
                        'content' => 'Orang tua atau wali menggunakan website untuk membuat akun, mengisi data calon siswa, memilih gelombang pendaftaran, mengunggah dokumen, melihat status pendaftaran, dan melakukan daftar ulang. Admin menggunakan sistem untuk memverifikasi data, dokumen, pembayaran, serta menyusun rekap administrasi SPMB.',
                    ],
                    [
                        'icon' => 'list-checks',
                        'title' => 'Kewajiban Pengguna',
                        'type' => 'list',
                        'items' => [
                            'Orang tua atau wali wajib mengisi data dengan benar, lengkap, dan dapat dipertanggungjawabkan.',
                            'Dokumen yang diunggah harus asli, sah, valid, dan sesuai dengan data calon siswa.',
                            'Pengguna wajib menjaga keamanan akun dan kata sandi serta tidak memberikannya kepada pihak yang tidak berwenang.',
                            'Pengguna wajib mengikuti instruksi, jadwal, dan tahapan administrasi SPMB yang disampaikan melalui kanal resmi.',
                        ],
                    ],
                    [
                        'icon' => 'file-warning',
                        'title' => 'Kebenaran Data dan Konsekuensi',
                        'type' => 'list',
                        'intro' => 'Orang tua atau wali bertanggung jawab atas kebenaran data dan dokumen yang dikirimkan melalui sistem. Apabila ditemukan data yang tidak benar, tidak lengkap, atau tidak dapat diverifikasi, pihak sekolah atau yayasan berhak mengambil tindakan sesuai kebijakan yang berlaku, antara lain:',
                        'items' => [
                            'Menunda proses pendaftaran sampai data atau dokumen dapat diverifikasi.',
                            'Meminta orang tua atau wali melakukan perbaikan atau melengkapi data.',
                            'Menolak pendaftaran apabila persyaratan tidak terpenuhi atau data tidak dapat dipertanggungjawabkan.',
                            'Mengambil tindakan administratif lain sesuai kebijakan sekolah atau yayasan dan ketentuan yang berlaku.',
                        ],
                    ],
                    [
                        'icon' => 'check-circle',
                        'title' => 'Verifikasi dan Keputusan Admin',
                        'type' => 'text',
                        'content' => 'Admin berhak memverifikasi, menerima, menolak, atau meminta perbaikan data dan dokumen. Pendaftaran belum dianggap selesai sampai seluruh proses verifikasi dan daftar ulang dinyatakan selesai oleh pihak sekolah atau yayasan.',
                    ],
                    [
                        'icon' => 'credit-card',
                        'title' => 'Daftar Ulang dan Pembayaran',
                        'type' => 'text',
                        'content' => 'Jika pendaftaran diterima, orang tua atau wali wajib melakukan daftar ulang sesuai jadwal dan instruksi dalam sistem. Bukti pembayaran harus diunggah melalui sistem dan akan diverifikasi oleh admin. Status pembayaran atau daftar ulang baru dinyatakan selesai setelah memperoleh konfirmasi dari pihak sekolah atau yayasan.',
                    ],
                    [
                        'icon' => 'shield-alert',
                        'title' => 'Larangan',
                        'type' => 'list',
                        'items' => [
                            'Mengunggah data atau dokumen palsu.',
                            'Mencoba mengakses data milik pengguna lain.',
                            'Menyalahgunakan website atau mengganggu keamanan dan ketersediaan layanan.',
                            'Menggunakan akun, informasi, atau dokumen dalam sistem untuk tujuan yang melanggar hukum atau merugikan pihak lain.',
                        ],
                    ],
                    [
                        'icon' => 'server-cog',
                        'title' => 'Ketersediaan Layanan',
                        'type' => 'text',
                        'content' => 'Pihak sekolah atau yayasan berupaya menjaga website agar dapat digunakan dengan baik. Namun, akses ke website dapat terganggu sewaktu-waktu karena pemeliharaan sistem, gangguan jaringan, pembaruan aplikasi, keadaan di luar kendali pengelola, atau kondisi teknis lainnya. Informasi penting mengenai proses SPMB akan disampaikan melalui kanal resmi apabila diperlukan.',
                    ],
                    [
                        'icon' => 'shield-check',
                        'title' => 'Hubungan dengan Kebijakan Privasi',
                        'type' => 'link',
                        'content' => 'Penggunaan website ini juga tunduk pada Kebijakan Privasi dan Penggunaan Data. Dengan menggunakan website dan mengirimkan data pendaftaran, pengguna dianggap telah membaca dan memahami ketentuan penggunaan serta kebijakan privasi yang berlaku.',
                        'route' => 'privacy',
                        'link_label' => 'Baca Kebijakan Privasi dan Penggunaan Data',
                    ],
                    [
                        'icon' => 'refresh-cw',
                        'title' => 'Perubahan Ketentuan',
                        'type' => 'text',
                        'content' => 'Pihak sekolah atau yayasan dapat memperbarui ketentuan penggunaan ini apabila terdapat perubahan proses SPMB, kebutuhan sistem, kebijakan administrasi, atau ketentuan yang berlaku. Versi terbaru dan tanggal berlakunya akan ditampilkan pada halaman ini.',
                    ],
                    [
                        'icon' => 'mail',
                        'title' => 'Kontak',
                        'type' => 'text',
                        'content' => 'Jika terdapat pertanyaan terkait penggunaan website, proses SPMB, ketentuan ini, atau penggunaan data pribadi, orang tua atau wali dapat menghubungi admin sekolah atau yayasan melalui kontak resmi yang tersedia.',
                    ],
                ];
            @endphp

            <div class="divide-y divide-gray-100">
                @foreach($sections as $index => $section)
                    <div class="group flex gap-4 px-6 py-6 transition-colors duration-200 hover:bg-primary-50/30 sm:gap-5 sm:px-10 sm:py-7 {{ $index === 0 ? 'rounded-t-2xl' : '' }} {{ $index === count($sections) - 1 ? 'rounded-b-2xl' : '' }}">
                        {{-- Icon badge --}}
                        <div class="flex flex-shrink-0 flex-col items-center gap-1">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-primary-50 text-primary-600 ring-1 ring-primary-100 transition-colors group-hover:bg-primary-100">
                                <i data-lucide="{{ $section['icon'] }}" class="h-5 w-5"></i>
                            </div>
                            <span class="text-[11px] font-bold text-gray-300">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                        </div>

                        {{-- Content --}}
                        <div class="min-w-0">
                            <h2 class="mb-1.5 text-lg font-bold text-gray-900">{{ $section['title'] }}</h2>

                            @if($section['type'] === 'text')
                                <p class="text-sm text-justify leading-relaxed text-gray-600 sm:text-base">{{ $section['content'] }}</p>
                            @elseif($section['type'] === 'list')
                                @isset($section['intro'])
                                    <p class="mb-3 text-sm text-justify leading-relaxed text-gray-600 sm:text-base">{{ $section['intro'] }}</p>
                                @endisset
                                <ul class="space-y-2 text-sm leading-relaxed text-gray-600 sm:text-base">
                                    @foreach($section['items'] as $item)
                                        <li class="flex items-start gap-2.5">
                                            <span class="mt-2 h-1.5 w-1.5 flex-shrink-0 rounded-full bg-primary-400"></span>
                                            <span>{{ $item }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            @elseif($section['type'] === 'link')
                                <p class="text-sm text-justify leading-relaxed text-gray-600 sm:text-base">{{ $section['content'] }}</p>
                                <a href="{{ route($section['route']) }}"
                                   class="mt-3 inline-flex items-center gap-2 text-sm font-bold text-primary-600 transition-colors hover:text-primary-700 hover:underline focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 focus-visible:ring-offset-2">
                                    {{ $section['link_label'] }}
                                    <i data-lucide="arrow-up-right" class="h-4 w-4"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </article>

    </div>
</section>
@endsection
