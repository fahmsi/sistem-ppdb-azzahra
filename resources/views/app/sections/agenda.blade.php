{{-- ============================================
    Section 12: Agenda & Timeline
    ============================================ --}}
<section id="agenda" class="relative overflow-hidden py-20 lg:py-28">
    {{-- Decorative --}}
    <div class="absolute top-1/4 right-0 w-64 h-64 bg-primary-50 rounded-full translate-x-1/2"></div>

    <div class="relative max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Title --}}
        <div class="text-center mb-16 fade-up">
            <h2 class="section-heading font-heading mb-4 text-gray-900">
                <span>Agenda <span class="gradient-text">SPMB 2026/2027</span></span>
            </h2>
            <p class="text-gray-600 max-w-3xl mx-auto mt-6 text-sm sm:text-base leading-relaxed text-justify sm:text-center">
                Administrasi awal dilakukan secara online melalui website. Setelah data dan berkas diverifikasi, orang tua/wali bersama calon siswa datang ke sekolah untuk mengikuti observasi atau wawancara. Tahap daftar ulang dilakukan setelah proses observasi sesuai arahan pihak sekolah.
            </p>
        </div>

        {{-- Vertical Timeline --}}
        <div class="max-w-3xl mx-auto mb-20 relative fade-up">

            @php
            $timeline = [
                [
                    'title' => 'Pendaftaran Online',
                    'date' => $settings['agenda_pembukaan_date'] ?? '1 Mei — 30 Juni 2026',
                    'desc' => 'Orang tua/wali membuat akun, mengisi biodata calon siswa, mengunggah berkas, dan memilih gelombang secara online.',
                    'icon' => 'user-plus',
                    'status' => $settings['agenda_pembukaan_status'] ?? 'active'
                ],
                [
                    'title' => 'Verifikasi Berkas',
                    'date' => '1 Mei — 30 Juni 2026',
                    'desc' => 'Panitia sekolah melakukan pemeriksaan dan memverifikasi kelengkapan berkas dokumen yang diunggah secara online.',
                    'icon' => 'search',
                    'status' => 'upcoming'
                ],
                [
                    'title' => 'Observasi & Wawancara',
                    'date' => '5 Juli 2026',
                    'desc' => 'Orang tua/wali datang langsung ke sekolah bersama calon siswa untuk mengikuti tahap observasi perkembangan anak dan wawancara.',
                    'icon' => 'clipboard-check',
                    'status' => 'upcoming'
                ],
                [
                    'title' => 'Pengumuman Hasil',
                    'date' => '5-7 Juli 2026',
                    'desc' => 'Panitia mengumumkan hasil kelulusan seleksi berkas & observasi melalui website dan notifikasi WhatsApp.',
                    'icon' => 'megaphone',
                    'status' => 'upcoming'
                ],
                [
                    'title' => 'Daftar Ulang & Pembayaran',
                    'date' => '5-8 Juli 2026',
                    'desc' => 'Melakukan konfirmasi daftar ulang dan mengunggah bukti pembayaran biaya sekolah setelah mendapat arahan dari admin sekolah.',
                    'icon' => 'credit-card',
                    'status' => 'upcoming'
                ],
                [
                    'title' => 'Masa Pengenalan Lingkungan Sekolah (MPLS)',
                    'date' => '13 Juli 2026',
                    'desc' => 'Hari pertama masuk sekolah serta orientasi pengenalan lingkungan sekolah bagi siswa baru.',
                    'icon' => 'flag',
                    'status' => 'upcoming'
                ],
            ];
            @endphp

            {{-- Timeline Line --}}
            <div class="timeline-line hidden md:block"></div>

            <div class="space-y-8">
                @foreach($timeline as $i => $item)
                <div class="relative pl-0 md:pl-14">
                     {{-- Dot --}}
                     <div class="timeline-dot hidden md:block {{ $item['status'] === 'active' ? 'animate-pulse-glow' : '' }}"></div>

                     {{-- Card --}}
                     <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 lg:p-8 hover-card group {{ $item['status'] === 'active' ? 'ring-2 ring-primary-100 border-primary-200' : '' }}">
                         <div class="flex flex-col sm:flex-row sm:items-start gap-4">
                             {{-- Icon --}}
                             <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-primary-500 to-primary-600 flex items-center justify-center flex-shrink-0 shadow-lg shadow-primary-200/50 transition-transform duration-300 transform-gpu will-change-transform group-hover:scale-110">
                                 <i data-lucide="{{ $item['icon'] }}" class="w-6 h-6 text-white"></i>
                             </div>

                             <div class="flex-1">
                                 <div class="flex flex-wrap items-center gap-3 mb-2">
                                     <h3 class="font-heading text-lg font-bold text-gray-900">
                                         {{ $item['title'] }}
                                     </h3>
                                     @if($item['status'] === 'active')
                                         <span class="inline-flex items-center gap-1 text-[10px] bg-secondary-100 text-secondary-700 px-2.5 py-1 rounded-full font-bold uppercase tracking-wider">
                                             <span class="w-1.5 h-1.5 bg-secondary-500 rounded-full animate-pulse"></span>
                                             Sedang Berlangsung
                                         </span>
                                     @endif
                                 </div>
                                 <p class="text-primary-600 font-semibold text-sm mb-2 flex items-center gap-1.5">
                                     <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                                     {{ $item['date'] }}
                                 </p>
                                 <p class="text-gray-600 text-sm leading-relaxed">
                                     {{ $item['desc'] }}
                                 </p>
                             </div>
                         </div>
                     </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Horizontal Important Dates --}}
        @php
        $important = [
            ['event' => 'Batas Akhir Pendaftaran', 'date' => '30 Juni 2026', 'gradient' => 'from-red-500 to-rose-600', 'icon' => 'clock'],
            ['event' => 'Observasi & Wawancara', 'date' => '5 Juli 2026', 'gradient' => 'from-blue-500 to-indigo-600', 'icon' => 'message-circle'],
            ['event' => 'Daftar Ulang & Pembayaran', 'date' => '5-8 Juli 2026', 'gradient' => 'from-emerald-500 to-green-600', 'icon' => 'credit-card'],
            ['event' => 'MPLS & Masuk Sekolah', 'date' => '13 Juli 2026', 'gradient' => 'from-violet-500 to-purple-600', 'icon' => 'flag'],
            ['event' => 'Tes Kesehatan', 'date' => '13 Juli 2026', 'gradient' => 'from-emerald-500 to-green-600', 'icon' => 'stethoscope'],
        ];
        @endphp

        <div class="fade-up">
            <h3 class="section-heading font-heading font-bold text-center text-gray-900 mb-8">
                Tanggal Penting
            </h3>

            <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mt-12">
                @foreach($important as $item)
                <div class="hover-card bg-white rounded-2xl p-5 border border-gray-100 shadow-sm text-center group">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br {{ $item['gradient'] }} flex items-center justify-center mx-auto mb-3 shadow-lg transition-transform duration-300 transform-gpu will-change-transform group-hover:scale-110">
                        <i data-lucide="{{ $item['icon'] }}" class="w-5 h-5 text-white"></i>
                    </div>
                    <p class="font-heading font-semibold text-gray-900 text-xs mb-1.5 leading-tight">
                        {{ $item['event'] }}
                    </p>
                    <span class="text-[10px] font-bold text-primary-600 bg-primary-50 px-2 py-1 rounded-full">
                        {{ $item['date'] }}
                    </span>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Notice --}}
        <div class="mt-14 bg-gradient-to-r from-primary-50 to-secondary-50 border border-primary-100 rounded-2xl p-8 fade-up">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-xl bg-primary-100 flex items-center justify-center flex-shrink-0">
                    <i data-lucide="info" class="w-6 h-6 text-primary-600"></i>
                </div>
                <div>
                    <h4 class="font-heading font-semibold text-gray-900 mb-3">Catatan Penting</h4>
                    <ul class="text-sm text-gray-600 space-y-2">
                        <li class="flex items-center gap-2">
                            <i data-lucide="chevron-right" class="w-3 h-3 text-primary-500 flex-shrink-0"></i>
                            Peserta yang tidak hadir sesuai jadwal dianggap mengundurkan diri
                        </li>
                        <li class="flex items-center gap-2">
                            <i data-lucide="chevron-right" class="w-3 h-3 text-primary-500 flex-shrink-0"></i>
                            Pengumuman dikirim via website & WhatsApp
                        </li>
                        <li class="flex items-center gap-2">
                            <i data-lucide="chevron-right" class="w-3 h-3 text-primary-500 flex-shrink-0"></i>
                            Wajib daftar ulang sesuai jadwal yang telah ditentukan
                        </li>
                        <li class="flex items-center gap-2">
                            <i data-lucide="chevron-right" class="w-3 h-3 text-primary-500 flex-shrink-0"></i>
                            Keterlambatan daftar ulang dapat membatalkan kelulusan
                        </li>
                    </ul>
                </div>
            </div>
        </div>

    </div>
</section>
