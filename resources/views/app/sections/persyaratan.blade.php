{{-- ============================================
    Section 10: Persyaratan Administrasi & Usia
    ============================================ --}}
<section id="persyaratan" class="relative overflow-hidden py-20 lg:py-28">
    {{-- Decorative Background Elements --}}
    <div class="absolute -top-24 -right-24 w-96 h-96 bg-primary-100/30 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-secondary-100/30 rounded-full blur-3xl pointer-events-none"></div>

    <div class="relative max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Section Header --}}
        <div class="flex flex-col items-center text-center mb-16 fade-up">
            <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-primary-50 border border-primary-200 text-primary-700 text-sm font-semibold mb-5 shadow-sm">
                <i data-lucide="clipboard-check" class="w-4 h-4 text-primary-600"></i>
                <span>Panduan &amp; Syarat Pendaftaran</span>
            </span>
            <h2 class="section-heading font-heading mb-4 text-gray-900">
                <span>Persyaratan <span class="gradient-text">Pendaftaran SPMB</span></span>
            </h2>
            <p class="text-gray-600 max-w-2xl mx-auto mt-4 text-sm sm:text-base leading-relaxed">
                Persiapkan dokumen pendaftaran dan pahami kriteria usia calon siswa secara transparan untuk kelancaran proses admisi.
            </p>
        </div>

        {{-- Bento Grid Container --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-12">

            {{-- 1. Dokumen Utama (6 Cols) --}}
            <div class="lg:col-span-6 bg-white rounded-3xl p-6 sm:p-8 border border-gray-100 shadow-xl shadow-gray-100/50 hover-card group flex flex-col justify-between fade-up">
                <div>
                    <div class="flex items-center justify-between gap-4 mb-6">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-primary-500 to-primary-600 flex items-center justify-center text-white shadow-lg shadow-primary-500/25 transition-transform duration-300 group-hover:scale-110">
                                <i data-lucide="file-check" class="w-6 h-6"></i>
                            </div>
                            <div>
                                <h3 class="font-heading font-bold text-lg text-gray-900">Dokumen Utama Anak</h3>
                                <p class="text-xs text-gray-500">Wajib diunggah saat pendaftaran</p>
                            </div>
                        </div>
                        <span class="px-2.5 py-1 rounded-full bg-primary-50 text-primary-700 text-[11px] font-bold">3 Berkas</span>
                    </div>

                    <div class="space-y-4">
                        {{-- Item 1 --}}
                        <div class="flex items-start gap-3.5 p-3.5 rounded-2xl bg-gray-50/80 border border-gray-100 transition-colors duration-200 hover:bg-primary-50/40">
                            <div class="w-8 h-8 rounded-xl bg-primary-100 text-primary-600 flex items-center justify-center flex-shrink-0 mt-0.5">
                                <i data-lucide="camera" class="w-4 h-4"></i>
                            </div>
                            <div class="flex-1">
                                <h4 class="text-sm font-bold text-gray-800">Pas Foto Anak</h4>
                                <p class="text-xs text-gray-500 mt-0.5">Pas foto terbaru calon siswa (tampak wajah jelas, latar polos).</p>
                            </div>
                        </div>

                        {{-- Item 2 --}}
                        <div class="flex items-start gap-3.5 p-3.5 rounded-2xl bg-gray-50/80 border border-gray-100 transition-colors duration-200 hover:bg-primary-50/40">
                            <div class="w-8 h-8 rounded-xl bg-primary-100 text-primary-600 flex items-center justify-center flex-shrink-0 mt-0.5">
                                <i data-lucide="file-text" class="w-4 h-4"></i>
                            </div>
                            <div class="flex-1">
                                <h4 class="text-sm font-bold text-gray-800">Kartu Keluarga (KK)</h4>
                                <p class="text-xs text-gray-500 mt-0.5">Scan / foto dokumen Kartu Keluarga asli terbaru.</p>
                            </div>
                        </div>

                        {{-- Item 3 --}}
                        <div class="flex items-start gap-3.5 p-3.5 rounded-2xl bg-gray-50/80 border border-gray-100 transition-colors duration-200 hover:bg-primary-50/40">
                            <div class="w-8 h-8 rounded-xl bg-primary-100 text-primary-600 flex items-center justify-center flex-shrink-0 mt-0.5">
                                <i data-lucide="award" class="w-4 h-4"></i>
                            </div>
                            <div class="flex-1">
                                <h4 class="text-sm font-bold text-gray-800">Akta Kelahiran</h4>
                                <p class="text-xs text-gray-500 mt-0.5">Scan / foto Akta Kelahiran resmi calon siswa dari Disdukcapil.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-6 pt-4 border-t border-gray-100 flex items-center justify-between text-xs text-gray-500">
                    <span class="flex items-center gap-1.5 font-medium">
                        <i data-lucide="info" class="w-4 h-4 text-primary-500"></i>
                        Format: JPG, PNG, atau PDF
                    </span>
                    <span class="font-semibold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">Maks. 2MB / File</span>
                </div>
            </div>

            {{-- 2. Dokumen Identitas Orang Tua / Wali (6 Cols) --}}
            <div class="lg:col-span-6 bg-white rounded-3xl p-6 sm:p-8 border border-gray-100 shadow-xl shadow-gray-100/50 hover-card group flex flex-col justify-between fade-up">
                <div>
                    <div class="flex items-center justify-between gap-4 mb-6">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-secondary-500 to-secondary-600 flex items-center justify-center text-white shadow-lg shadow-secondary-500/25 transition-transform duration-300 group-hover:scale-110">
                                <i data-lucide="users" class="w-6 h-6"></i>
                            </div>
                            <div>
                                <h3 class="font-heading font-bold text-lg text-gray-900">Identitas Orang Tua / Wali</h3>
                                <p class="text-xs text-gray-500">Sesuai kondisi domisili tinggal siswa</p>
                            </div>
                        </div>
                        <span class="px-2.5 py-1 rounded-full bg-secondary-50 text-secondary-700 text-[11px] font-bold">KTP &amp; Kontak</span>
                    </div>

                    <div class="space-y-4">
                        {{-- Sub Condition 1 --}}
                        <div class="p-4 rounded-2xl bg-gradient-to-r from-secondary-50/60 to-emerald-50/30 border border-secondary-100">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="w-2 h-2 rounded-full bg-secondary-500"></span>
                                <h4 class="text-xs font-bold uppercase tracking-wider text-secondary-800">Tinggal Bersama Orang Tua</h4>
                            </div>
                            <div class="flex items-start gap-3 pl-4 border-l-2 border-secondary-200">
                                <i data-lucide="id-card" class="w-4 h-4 text-secondary-600 mt-0.5 flex-shrink-0"></i>
                                <p class="text-xs text-gray-700 font-medium">
                                    Foto KTP Ayah Kandung &amp; KTP Ibu Kandung yang masih berlaku.
                                </p>
                            </div>
                        </div>

                        {{-- Sub Condition 2 --}}
                        <div class="p-4 rounded-2xl bg-gradient-to-r from-blue-50/60 to-indigo-50/30 border border-blue-100">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                                <h4 class="text-xs font-bold uppercase tracking-wider text-blue-800">Tinggal Bersama Wali</h4>
                            </div>
                            <div class="flex items-start gap-3 pl-4 border-l-2 border-blue-200">
                                <i data-lucide="user-check" class="w-4 h-4 text-blue-600 mt-0.5 flex-shrink-0"></i>
                                <p class="text-xs text-gray-700 font-medium">
                                    Foto KTP Wali beserta Surat Keterangan / Data Identitas Wali Legal.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-6 pt-4 border-t border-gray-100 flex items-center justify-between text-xs text-gray-500">
                    <span class="flex items-center gap-1.5 font-medium text-secondary-700">
                        <i data-lucide="shield-check" class="w-4 h-4 text-secondary-500"></i>
                        Data dijamin aman &amp; terlindungi
                    </span>
                    <span class="text-gray-400">Verifikasi Kontak</span>
                </div>
            </div>

            {{-- 3. Kriteria Usia (6 Cols) --}}
            <div class="lg:col-span-6 bg-white rounded-3xl p-6 sm:p-8 border border-gray-100 shadow-xl shadow-gray-100/50 hover-card group flex flex-col justify-between fade-up">
                <div>
                    <div class="flex items-center justify-between gap-4 mb-6">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-amber-500 to-orange-500 flex items-center justify-center text-white shadow-lg shadow-amber-500/25 transition-transform duration-300 group-hover:scale-110">
                                <i data-lucide="baby" class="w-6 h-6"></i>
                            </div>
                            <div>
                                <h3 class="font-heading font-bold text-lg text-gray-900">Kriteria Usia Anak</h3>
                                <p class="text-xs text-gray-500">Perhitungan per 1 Juli tahun ajaran</p>
                            </div>
                        </div>
                        <span class="px-2.5 py-1 rounded-full bg-amber-50 text-amber-700 text-[11px] font-bold">Non-Administratif</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                        {{-- Kelompok A --}}
                        <div class="p-4 rounded-2xl bg-amber-50/50 border border-amber-100 flex flex-col justify-between">
                            <div>
                                <span class="inline-block px-2 py-0.5 rounded-md bg-amber-100 text-amber-800 text-[10px] font-extrabold uppercase mb-2">Kelompok A</span>
                                <h4 class="font-heading text-xl font-extrabold text-gray-900">4 s.d. &lt; 5 Thn</h4>
                            </div>
                            <p class="text-xs text-amber-700 mt-2 font-medium">Usia 4 tahun hingga kurang dari 5 tahun.</p>
                        </div>

                        {{-- Kelompok B --}}
                        <div class="p-4 rounded-2xl bg-emerald-50/50 border border-emerald-100 flex flex-col justify-between">
                            <div>
                                <span class="inline-block px-2 py-0.5 rounded-md bg-emerald-100 text-emerald-800 text-[10px] font-extrabold uppercase mb-2">Kelompok B</span>
                                <h4 class="font-heading text-xl font-extrabold text-gray-900">5 s.d. &lt; 7 Thn</h4>
                            </div>
                            <p class="text-xs text-emerald-700 mt-2 font-medium">Usia 5 tahun hingga kurang dari 7 tahun.</p>
                        </div>
                    </div>

                    {{-- Policy Note --}}
                    <div class="p-3.5 rounded-xl bg-gray-50 border border-gray-200/60 flex items-start gap-2.5">
                        <i data-lucide="help-circle" class="w-4 h-4 text-amber-600 mt-0.5 flex-shrink-0"></i>
                        <p class="text-xs text-gray-600 leading-relaxed">
                            <strong class="text-gray-800">Usia di luar rentang?</strong> Usia anak di luar rentang di atas tetap dapat mendaftar dan akan dikonfirmasi oleh panitia sekolah (tidak otomatis ditolak).
                        </p>
                    </div>
                </div>
            </div>

            {{-- 4. Interactive Bunda Checklist Tracker (6 Cols) --}}
            <div class="lg:col-span-6 bg-gradient-to-br from-primary-900 via-primary-800 to-indigo-950 rounded-3xl p-6 sm:p-8 text-white shadow-xl shadow-primary-900/20 hover-card group flex flex-col justify-between fade-up relative overflow-hidden">
                <div class="absolute top-0 right-0 w-48 h-48 bg-primary-500/10 rounded-full blur-2xl pointer-events-none"></div>

                <div>
                    <div class="flex items-center justify-between gap-4 mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-white/10 backdrop-blur-md flex items-center justify-center text-white border border-white/15">
                                <i data-lucide="list-checks" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <h3 class="font-heading font-bold text-lg text-white">Checklist Kelengkapan</h3>
                                <p class="text-xs text-primary-200">Bantu Ayah &amp; Bunda mengecek kesiapan berkas</p>
                            </div>
                        </div>
                        <span id="checklist-counter" class="px-3 py-1 rounded-full bg-white/15 backdrop-blur-md border border-white/20 text-xs font-bold text-white">
                            0 / 4 Berkas
                        </span>
                    </div>

                    {{-- Progress bar --}}
                    <div class="w-full bg-white/10 rounded-full h-2 mb-6 overflow-hidden">
                        <div id="checklist-progress" class="bg-gradient-to-r from-secondary-400 to-emerald-300 h-2 rounded-full transition-all duration-500" style="width: 0%"></div>
                    </div>

                    {{-- Interactive checkboxes --}}
                    <div class="space-y-2.5">
                        <label class="flex items-center gap-3 p-2.5 rounded-xl bg-white/5 border border-white/10 cursor-pointer hover:bg-white/10 transition-all select-none">
                            <input type="checkbox" class="req-checkbox w-4 h-4 rounded text-secondary-500 focus:ring-secondary-400 border-white/30 bg-white/10" onchange="updateChecklistProgress()">
                            <span class="text-xs font-medium text-white/90">Pas Foto Terbaru Anak</span>
                        </label>

                        <label class="flex items-center gap-3 p-2.5 rounded-xl bg-white/5 border border-white/10 cursor-pointer hover:bg-white/10 transition-all select-none">
                            <input type="checkbox" class="req-checkbox w-4 h-4 rounded text-secondary-500 focus:ring-secondary-400 border-white/30 bg-white/10" onchange="updateChecklistProgress()">
                            <span class="text-xs font-medium text-white/90">Dokumen Kartu Keluarga (KK)</span>
                        </label>

                        <label class="flex items-center gap-3 p-2.5 rounded-xl bg-white/5 border border-white/10 cursor-pointer hover:bg-white/10 transition-all select-none">
                            <input type="checkbox" class="req-checkbox w-4 h-4 rounded text-secondary-500 focus:ring-secondary-400 border-white/30 bg-white/10" onchange="updateChecklistProgress()">
                            <span class="text-xs font-medium text-white/90">Dokumen Akta Kelahiran</span>
                        </label>

                        <label class="flex items-center gap-3 p-2.5 rounded-xl bg-white/5 border border-white/10 cursor-pointer hover:bg-white/10 transition-all select-none">
                            <input type="checkbox" class="req-checkbox w-4 h-4 rounded text-secondary-500 focus:ring-secondary-400 border-white/30 bg-white/10" onchange="updateChecklistProgress()">
                            <span class="text-xs font-medium text-white/90">KTP Orang Tua / Wali</span>
                        </label>
                    </div>
                </div>

                <div id="checklist-status" class="mt-5 pt-3 border-t border-white/10 text-xs text-primary-200 flex items-center gap-2">
                    <i data-lucide="sparkles" class="w-4 h-4 text-amber-300 flex-shrink-0"></i>
                    <span>Tandai berkas yang sudah siap di HP/Laptop Bunda.</span>
                </div>
            </div>

        </div>

        {{-- Step-by-Step Procedure Card --}}
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-gray-100 shadow-xl shadow-gray-100/50 mb-12 fade-up">
            <div class="text-center max-w-xl mx-auto mb-8">
                <span class="text-xs font-bold uppercase tracking-wider text-primary-600 bg-primary-50 px-3 py-1 rounded-full">Alur Singkat Pendaftaran</span>
                <h3 class="font-heading font-extrabold text-xl sm:text-2xl text-gray-900 mt-2">4 Langkah Mudah Berkas Diterima</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 relative">
                {{-- Connecting Line for desktop --}}
                <div class="hidden md:block absolute top-10 left-[12%] right-[12%] h-0.5 bg-gradient-to-r from-primary-200 via-secondary-200 to-primary-200 z-0"></div>

                {{-- Step 1 --}}
                <div class="relative z-10 text-center group">
                    <div class="w-14 h-14 mx-auto rounded-2xl bg-gradient-to-br from-primary-500 to-primary-600 text-white font-heading font-extrabold text-lg flex items-center justify-center shadow-lg shadow-primary-500/25 mb-4 group-hover:scale-110 transition-transform">
                        1
                    </div>
                    <h4 class="font-heading font-bold text-sm text-gray-900 mb-1">Formulir Online</h4>
                    <p class="text-xs text-gray-500 leading-relaxed">Mengisi biodata orang tua &amp; anak di portal Aplikasi SPMB.</p>
                </div>

                {{-- Step 2 --}}
                <div class="relative z-10 text-center group">
                    <div class="w-14 h-14 mx-auto rounded-2xl bg-gradient-to-br from-secondary-500 to-secondary-600 text-white font-heading font-extrabold text-lg flex items-center justify-center shadow-lg shadow-secondary-500/25 mb-4 group-hover:scale-110 transition-transform">
                        2
                    </div>
                    <h4 class="font-heading font-bold text-sm text-gray-900 mb-1">Unggah Berkas</h4>
                    <p class="text-xs text-gray-500 leading-relaxed">Mengunggah pas foto, KK, Akta, dan KTP di akun pendaftar.</p>
                </div>

                {{-- Step 3 --}}
                <div class="relative z-10 text-center group">
                    <div class="w-14 h-14 mx-auto rounded-2xl bg-gradient-to-br from-amber-500 to-orange-500 text-white font-heading font-extrabold text-lg flex items-center justify-center shadow-lg shadow-amber-500/25 mb-4 group-hover:scale-110 transition-transform">
                        3
                    </div>
                    <h4 class="font-heading font-bold text-sm text-gray-900 mb-1">Observasi Siswa</h4>
                    <p class="text-xs text-gray-500 leading-relaxed">Datang ke sekolah mengikuti sesi wawancara &amp; observasi anak.</p>
                </div>

                {{-- Step 4 --}}
                <div class="relative z-10 text-center group">
                    <div class="w-14 h-14 mx-auto rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-600 text-white font-heading font-extrabold text-lg flex items-center justify-center shadow-lg shadow-emerald-500/25 mb-4 group-hover:scale-110 transition-transform">
                        4
                    </div>
                    <h4 class="font-heading font-bold text-sm text-gray-900 mb-1">Daftar Ulang</h4>
                    <p class="text-xs text-gray-500 leading-relaxed">Melakukan konfirmasi daftar ulang setelah dinyatakan diterima.</p>
                </div>
            </div>
        </div>

        {{-- Catatan Penting Banner --}}
        <div class="p-6 sm:p-8 bg-gradient-to-r from-primary-50 via-white to-secondary-50 rounded-3xl border border-primary-100 shadow-md fade-up">
            <div class="flex flex-col sm:flex-row items-start gap-4 sm:gap-6">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-primary-500 to-secondary-600 text-white flex items-center justify-center flex-shrink-0 shadow-md">
                    <i data-lucide="shield-alert" class="w-6 h-6"></i>
                </div>
                <div class="flex-1">
                    <h4 class="font-heading font-bold text-gray-900 text-lg mb-3 flex items-center gap-2">
                        <span>Catatan Penting Pendaftaran</span>
                        <span class="text-xs font-semibold text-secondary-700 bg-secondary-100 px-2.5 py-0.5 rounded-full">Transparan &amp; Resmi</span>
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs text-gray-600">
                        <div class="flex items-start gap-2.5 bg-white/80 p-3 rounded-xl border border-gray-100">
                            <i data-lucide="check-circle-2" class="w-4 h-4 text-secondary-600 flex-shrink-0 mt-0.5"></i>
                            <span>Semua dokumen harus <strong>asli, terbaca jelas, dan masih berlaku</strong>.</span>
                        </div>
                        <div class="flex items-start gap-2.5 bg-white/80 p-3 rounded-xl border border-gray-100">
                            <i data-lucide="alert-circle" class="w-4 h-4 text-amber-600 flex-shrink-0 mt-0.5"></i>
                            <span>Berkas yang tidak lengkap akan ditunda verifikasinya sampai dilengkapi.</span>
                        </div>
                        <div class="flex items-start gap-2.5 bg-white/80 p-3 rounded-xl border border-gray-100">
                            <i data-lucide="sparkles" class="w-4 h-4 text-primary-600 flex-shrink-0 mt-0.5"></i>
                            <span><strong>Pendaftaran 100% GRATIS</strong>. Pembayaran hanya pada tahap daftar ulang setelah observasi.</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>

<script>
    function updateChecklistProgress() {
        const checkboxes = document.querySelectorAll('.req-checkbox');
        const checkedCount = Array.from(checkboxes).filter(cb => cb.checked).length;
        const total = checkboxes.length;
        const percentage = (checkedCount / total) * 100;

        const counterEl = document.getElementById('checklist-counter');
        const progressEl = document.getElementById('checklist-progress');
        const statusEl = document.getElementById('checklist-status');

        if (counterEl) counterEl.textContent = `${checkedCount} / ${total} Berkas`;
        if (progressEl) progressEl.style.width = `${percentage}%`;

        if (statusEl) {
            if (checkedCount === total) {
                statusEl.innerHTML = `<i data-lucide="party-popper" class="w-4 h-4 text-emerald-300 flex-shrink-0"></i><span class="text-emerald-200 font-bold">Luar biasa! Berkas Anda lengkap & siap untuk mendaftar.</span>`;
            } else if (checkedCount > 0) {
                statusEl.innerHTML = `<i data-lucide="sparkles" class="w-4 h-4 text-amber-300 flex-shrink-0"></i><span>Bagus! Tersisa ${total - checkedCount} dokumen lagi yang perlu disiapkan.</span>`;
            } else {
                statusEl.innerHTML = `<i data-lucide="sparkles" class="w-4 h-4 text-amber-300 flex-shrink-0"></i><span>Tandai berkas yang sudah siap di HP/Laptop Bunda.</span>`;
            }
            if (window.lucide) lucide.createIcons();
        }
    }
</script>

