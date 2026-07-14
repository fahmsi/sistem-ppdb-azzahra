{{-- ============================================
    Section 10: Persyaratan Administrasi
    ============================================ --}}
<section id="persyaratan" class="relative overflow-hidden py-20 lg:py-28">
    {{-- Decorative --}}
    <div class="absolute bottom-0 right-0 w-72 h-72 bg-secondary-50 rounded-full translate-x-1/3 translate-y-1/3"></div>

    <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="text-center mb-14 fade-up">
            <h2 class="section-heading font-heading mb-4 text-gray-900">
                <span>Persyaratan <span class="gradient-text">Administrasi</span></span>
            </h2>
            <p class="text-gray-600 max-w-2xl mx-auto mt-6">
                Pastikan semua dokumen sudah lengkap sebelum mendaftar
            </p>
        </div>

        {{-- Card --}}
        <div class="bg-white shadow-xl rounded-3xl p-8 lg:p-10 border border-gray-100 fade-up">

            @php
                $requirements = [
                    "Pas Foto Anak (pas foto terbaru)",
                    "Dokumen Kartu Keluarga (KK)",
                    "Dokumen Akta Kelahiran",
                    "Persyaratan Dokumen Identitas sesuai kondisi tinggal:",
                    "• Tinggal bersama Orang Tua: Foto KTP Ayah dan KTP Ibu",
                    "• Tinggal bersama Wali: Foto KTP Wali dan Data Identitas Wali",
                    "Kriteria Usia Nonadministratif (per 1 Juli tahun ajaran):",
                    "• Kelompok A: usia 4 sampai kurang dari 5 tahun",
                    "• Kelompok B: usia 5 sampai kurang dari 7 tahun",
                    "• Usia di luar rentang di atas akan dikonfirmasi pihak sekolah (tidak otomatis ditolak)",
                    "Mengisi formulir pendaftaran online di Aplikasi SPMB",
                    "Mengikuti observasi/wawancara calon siswa di sekolah sesuai jadwal",
                ];
            @endphp

            <div class="space-y-2">
                @foreach ($requirements as $i => $item)
                    <div class="req-item flex items-start gap-4 p-4 group">
                        {{-- Checkmark --}}
                        <div class="req-check flex-shrink-0 w-8 h-8 rounded-lg bg-secondary-50 flex items-center justify-center transition-all duration-300 group-hover:bg-secondary-100">
                            <i data-lucide="check" class="w-4 h-4 text-secondary-600"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-gray-700 font-medium">{{ $item }}</p>
                        </div>
                        <span class="flex-shrink-0 text-xs text-gray-400 font-mono">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                    </div>
                @endforeach
            </div>

            {{-- Catatan Penting --}}
            <div class="mt-10 p-6 bg-gradient-to-r from-primary-50 to-secondary-50 rounded-2xl border border-primary-100">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 rounded-xl bg-primary-100 flex items-center justify-center flex-shrink-0">
                        <i data-lucide="alert-circle" class="w-5 h-5 text-primary-600"></i>
                    </div>
                    <div>
                        <h4 class="font-heading font-semibold text-gray-900 mb-3">Catatan Penting</h4>
                        <ul class="text-sm text-gray-600 space-y-2">
                            <li class="flex items-start gap-2">
                                <i data-lucide="chevron-right" class="w-3 h-3 text-primary-500 flex-shrink-0 mt-1"></i>
                                <span>Semua dokumen harus asli dan masih berlaku</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <i data-lucide="chevron-right" class="w-3 h-3 text-primary-500 flex-shrink-0 mt-1"></i>
                                <span>Berkas tidak lengkap tidak akan diproses</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <i data-lucide="chevron-right" class="w-3 h-3 text-primary-500 flex-shrink-0 mt-1"></i>
                                <span>Pendaftaran tidak dikenakan biaya. Pembayaran hanya dilakukan pada tahap daftar ulang setelah observasi/wawancara dan sesuai arahan pihak sekolah</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>

    </div>
</section>
