@extends('app.layouts.legal')

@section('title', 'Kebijakan Privasi dan Penggunaan Data - PAUD Al-Qur’an Azzahra')
@section('description', 'Kebijakan penggunaan dan perlindungan data pada sistem SPMB PAUD Al-Qur’an Azzahra.')

@section('content')
<section class="relative min-h-screen overflow-hidden bg-gradient-to-b from-primary-50 via-white to-gray-50 pb-20 pt-28 sm:pb-24 sm:pt-32">
    {{-- Decorative background elements --}}
    <div class="pointer-events-none absolute inset-0">
        <div class="absolute -left-32 -top-32 h-96 w-96 rounded-full bg-primary-100/40 blur-3xl"></div>
        <div class="absolute -bottom-32 -right-32 h-96 w-96 rounded-full bg-secondary-100/30 blur-3xl"></div>
    </div>

    <div class="relative mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">

        {{-- Page Header --}}
        <header class="mb-10 text-center sm:mb-14">
            <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-primary-100 text-primary-600 shadow-sm">
                <i data-lucide="shield-check" class="h-7 w-7"></i>
            </div>
            <p class="mb-2 text-sm font-bold uppercase tracking-[0.14em] text-primary-600">Informasi Legal</p>
            <h1 class="text-3xl font-extrabold tracking-tight text-gray-900 sm:text-4xl lg:text-5xl">
                Kebijakan Privasi dan Penggunaan Data
            </h1>
            <p class="mx-auto mt-3 max-w-xl text-base text-gray-500">
                Kebijakan penggunaan dan perlindungan data pada sistem SPMB PAUD Al-Qur’an Azzahra.
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
                        'content' => 'Kebijakan ini menjelaskan cara data pribadi orang tua atau wali dan calon siswa dikumpulkan, digunakan, disimpan, dan dilindungi selama proses Sistem Penerimaan Murid Baru (SPMB) PAUD Al-Qur’an Azzahra.',
                    ],
                    [
                        'icon' => 'school',
                        'title' => 'Pengelola Data',
                        'type' => 'text',
                        'content' => 'Website dan data SPMB dikelola oleh PAUD Al-Qur’an Azzahra untuk mendukung penerimaan murid baru dan administrasi sekolah atau yayasan. Pengelolaan data dilakukan sesuai kebutuhan layanan, kewenangan pengguna, kebijakan internal, dan ketentuan yang berlaku.',
                    ],
                    [
                        'icon' => 'users',
                        'title' => 'Persetujuan Orang Tua atau Wali',
                        'type' => 'text',
                        'content' => 'Karena proses SPMB melibatkan data calon siswa yang masih anak-anak, pengisian dan pengiriman data dilakukan oleh orang tua atau wali yang sah. Dengan menggunakan sistem ini, orang tua atau wali menyatakan bahwa data yang diberikan benar serta menyetujui penggunaan data pribadi orang tua atau wali dan calon siswa untuk keperluan administrasi SPMB.',
                    ],
                    [
                        'icon' => 'database',
                        'title' => 'Data yang Dikumpulkan',
                        'type' => 'list',
                        'intro' => 'Data yang dapat dikumpulkan dan dikelola melalui sistem meliputi:',
                        'items' => [
                            'Data akun dan orang tua atau wali, termasuk nama, alamat email, nomor telepon atau WhatsApp, dan alamat tempat tinggal.',
                            'Data calon siswa, termasuk identitas, tanggal lahir, jenis kelamin, alamat, serta informasi lain yang diperlukan dalam formulir pendaftaran.',
                            'Data keluarga yang diperlukan untuk administrasi dan verifikasi pendaftaran.',
                            'Foto calon siswa, Kartu Keluarga (KK), akta kelahiran, dan dokumen pendukung lainnya.',
                            'Bukti pembayaran serta informasi yang berkaitan dengan daftar ulang.',
                            'Status pendaftaran dan riwayat aktivitas administrasi dalam sistem.',
                        ],
                    ],
                    [
                        'icon' => 'target',
                        'title' => 'Tujuan Penggunaan Data',
                        'type' => 'list',
                        'intro' => 'Data digunakan secara terbatas untuk mendukung kebutuhan berikut:',
                        'items' => [
                            'Administrasi SPMB dan pengelolaan tahapan pendaftaran.',
                            'Verifikasi identitas calon siswa, data keluarga, dan dokumen persyaratan.',
                            'Komunikasi dengan orang tua atau wali mengenai proses dan status pendaftaran.',
                            'Pengelolaan daftar ulang serta verifikasi pembayaran.',
                            'Penyusunan rekap internal dan arsip administrasi sekolah atau yayasan.',
                            'Pelaporan administrasi pendidikan kepada pihak yang berwenang apabila diperlukan.',
                        ],
                    ],
                    [
                        'icon' => 'key',
                        'title' => 'Akses Data',
                        'type' => 'text',
                        'content' => 'Orang tua atau wali hanya dapat mengakses data miliknya. Admin dan super admin dapat mengakses data sesuai kewenangan untuk keperluan administrasi. Data pendaftaran tidak dibuka untuk publik.',
                    ],
                    [
                        'icon' => 'hard-drive',
                        'title' => 'Penyimpanan Dokumen',
                        'type' => 'text',
                        'content' => 'Dokumen sensitif seperti KK, akta kelahiran, foto siswa, dan bukti pembayaran disimpan secara privat dan hanya dapat diakses oleh pengguna yang berwenang sesuai kebutuhan verifikasi dan administrasi SPMB.',
                    ],
                    [
                        'icon' => 'cookie',
                        'title' => 'Cookie, Session, dan Log Aktivitas',
                        'type' => 'text',
                        'content' => 'Website dapat menggunakan cookie, session, dan log aktivitas untuk mendukung proses login, keamanan akun, pencatatan aktivitas sistem, penelusuran gangguan, serta peningkatan layanan. Data teknis tersebut digunakan secara terbatas untuk kebutuhan operasional dan keamanan sistem.',
                    ],
                    [
                        'icon' => 'share-2',
                        'title' => 'Pembagian Data',
                        'type' => 'text',
                        'content' => 'Data tidak dijual atau dibagikan untuk kepentingan komersial. Data hanya dapat diberikan kepada pihak yang berwenang apabila diperlukan untuk administrasi sekolah atau yayasan, pemenuhan kewajiban pelaporan resmi, penyediaan layanan teknis, atau berdasarkan ketentuan yang berlaku.',
                    ],
                    [
                        'icon' => 'cloud-cog',
                        'title' => 'Penyedia Layanan Teknis',
                        'type' => 'text',
                        'content' => 'Dalam menjalankan sistem, sekolah atau yayasan dapat menggunakan layanan teknis seperti hosting, email, komunikasi, atau layanan pendukung lainnya. Penggunaan layanan tersebut dilakukan hanya sejauh diperlukan untuk mendukung proses SPMB dan administrasi sekolah, dengan memperhatikan keamanan serta kerahasiaan data.',
                    ],
                    [
                        'icon' => 'lock',
                        'title' => 'Keamanan Data',
                        'type' => 'text',
                        'content' => 'Sistem menerapkan langkah perlindungan yang wajar, termasuk pembatasan akses berdasarkan akun dan peran pengguna serta penyimpanan privat untuk dokumen sensitif. Pengguna wajib menjaga kerahasiaan akun dan kata sandi serta segera menghubungi admin apabila mencurigai penyalahgunaan akun.',
                    ],
                    [
                        'icon' => 'shield-alert',
                        'title' => 'Penanganan Insiden Keamanan',
                        'type' => 'text',
                        'content' => 'Apabila terjadi gangguan atau insiden keamanan yang berpotensi berdampak pada data pengguna, pihak sekolah atau yayasan akan melakukan pemeriksaan, pembatasan dampak, pemulihan layanan, dan penanganan lain sesuai kebijakan internal serta ketentuan yang berlaku.',
                    ],
                    [
                        'icon' => 'user-check',
                        'title' => 'Hak Orang Tua atau Wali atas Data',
                        'type' => 'text',
                        'content' => 'Orang tua atau wali dapat mengajukan permintaan akses, perubahan, koreksi, pembatasan, atau penghapusan data melalui admin sekolah atau yayasan. Permintaan akan diproses setelah identitas dan kewenangan pemohon diverifikasi, sesuai kebutuhan administrasi, kebijakan sekolah atau yayasan, serta ketentuan yang berlaku. Sebagian data dapat tetap disimpan apabila diperlukan sebagai arsip administrasi atau untuk memenuhi kewajiban yang berlaku.',
                    ],
                    [
                        'icon' => 'clock',
                        'title' => 'Retensi Data',
                        'type' => 'text',
                        'content' => 'Data disimpan selama diperlukan untuk proses SPMB, administrasi pendidikan, penyelesaian kewajiban terkait, dan arsip sekolah atau yayasan. Jangka waktu penyimpanan serta penghapusan atau perubahan data mengikuti kebutuhan administrasi, kebijakan sekolah atau yayasan, dan ketentuan yang berlaku.',
                    ],
                    [
                        'icon' => 'file-check-2',
                        'title' => 'Hubungan dengan Syarat dan Ketentuan',
                        'type' => 'link',
                        'content' => 'Kebijakan Privasi dan Penggunaan Data ini merupakan bagian yang berkaitan dengan Syarat dan Ketentuan Penggunaan website. Pengguna dianjurkan membaca keduanya agar memahami ketentuan layanan serta cara data pribadi digunakan dan dilindungi.',
                        'route' => 'terms',
                        'link_label' => 'Baca Syarat dan Ketentuan Penggunaan',
                    ],
                    [
                        'icon' => 'refresh-cw',
                        'title' => 'Perubahan Kebijakan',
                        'type' => 'text',
                        'content' => 'Kebijakan ini dapat diperbarui apabila terdapat perubahan proses SPMB, kebutuhan sistem, kebijakan administrasi, layanan teknis, atau ketentuan yang berlaku. Versi terbaru dan tanggal berlakunya akan ditampilkan pada halaman ini.',
                    ],
                    [
                        'icon' => 'mail',
                        'title' => 'Kontak',
                        'type' => 'text',
                        'content' => 'Jika terdapat pertanyaan terkait penggunaan website, proses SPMB, atau penggunaan data pribadi, orang tua atau wali dapat menghubungi admin sekolah atau yayasan melalui kontak resmi yang tersedia.',
                    ],
                ];
            @endphp

            <div class="divide-y divide-gray-100">
                @foreach($sections as $index => $section)
                    <div class="group flex gap-4 px-6 py-6 transition-colors duration-200 hover:bg-primary-50/30 sm:gap-5 sm:px-10 sm:py-7 {{ $index === 0 ? 'rounded-t-2xl' : '' }} {{ $index === count($sections) - 1 ? 'rounded-b-2xl' : '' }}">
                        {{-- Number badge --}}
                        <div class="flex flex-shrink-0 flex-col items-center gap-1">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-primary-50 text-primary-600 ring-1 ring-primary-100 transition-colors group-hover:bg-primary-100">
                                <i data-lucide="{{ $section['icon'] }}" class="h-5 w-5"></i>
                            </div>
                            <span class="text-[12px] font-bold text-gray-300">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                        </div>

                        {{-- Content --}}
                        <div class="min-w-0">
                            <h2 class="mb-1.5 text-lg font-bold text-gray-900">{{ $section['title'] }}</h2>

                            @if($section['type'] === 'text')
                                <p class="text-sm text-justify leading-relaxed text-gray-600 sm:text-base">{{ $section['content'] }}</p>
                            @elseif($section['type'] === 'list')
                                @isset($section['intro'])
                                    <p class="mb-3 text-sm leading-relaxed text-gray-600 sm:text-base">{{ $section['intro'] }}</p>
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
                                <p class="text-sm leading-relaxed text-gray-600 sm:text-base">{{ $section['content'] }}</p>
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
