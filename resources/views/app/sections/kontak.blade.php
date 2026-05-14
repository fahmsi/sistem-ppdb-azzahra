{{-- ============================================
    Section 13: Kontak & Google Maps
    ============================================ --}}
<section id="kontak" class="py-20 lg:py-28 bg-gradient-to-b from-gray-50 to-white relative overflow-hidden islamic-pattern">
    {{-- Decorative --}}
    <div class="absolute bottom-0 left-0 w-80 h-80 bg-secondary-50 rounded-full translate-y-1/2 -translate-x-1/3"></div>

    <div class="relative max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Title --}}
        <div class="text-center mb-14 fade-up">
            <span class="inline-flex items-center gap-2 bg-primary-50 text-primary-700 px-4 py-1.5 rounded-full text-sm font-medium mb-4">
                <i data-lucide="phone" class="w-4 h-4"></i>
                Hubungi Kami
            </span>
            <h2 class="font-heading text-3xl lg:text-4xl font-bold text-gray-900 mb-4 section-heading">
                Hubungi Kami
            </h2>
            <p class="text-gray-600 max-w-2xl mx-auto mt-6">
                Ada pertanyaan? Silakan hubungi kami, kami siap membantu Anda
            </p>
        </div>

        <div class="grid lg:grid-cols-5 gap-8 lg:gap-12 mb-12">

            {{-- Contact Form (3 cols) --}}
            <div class="lg:col-span-3 fade-left h-full">
                <div class="hover-card bg-white py-8 px-6 sm:px-10 rounded-2xl shadow-sm border border-gray-100 flex flex-col items-center justify-center text-center h-full relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-40 h-40 bg-[#25D366] rounded-full opacity-10 blur-3xl -mr-10 -mt-10 pointer-events-none"></div>
                    <div class="absolute bottom-0 left-0 w-40 h-40 bg-primary-600 rounded-full opacity-10 blur-3xl -ml-10 -mb-10 pointer-events-none"></div>

                    <div class="relative z-10 flex flex-col items-center">
                        <div class="w-20 h-20 bg-green-50 text-[#25D366] rounded-full flex items-center justify-center mb-5 shadow-sm border border-green-100">
                            <i data-lucide="message-circle-question" class="w-10 h-10"></i>
                        </div>
                        
                        <h3 class="text-3xl font-bold text-gray-900 mb-3 font-heading">Punya Pertanyaan?</h3>
                        <p class="text-gray-600 text-sm mb-8 px-4 sm:px-8 leading-relaxed">
                            Jangan ragu untuk menghubungi kami! Admin kami yang ramah siap membantu menjawab semua pertanyaan Anda seputar PPDB, program sekolah, maupun rincian biaya secara langsung via WhatsApp.
                        </p>
                        
                        <a href="https://wa.me/6281310408525?text=Halo%20Admin%20PAUD%20Az%20Zahra,%20saya%20ingin%20bertanya%20informasi%20mengenai%20PPDB..." target="_blank" class="animate-floating inline-flex items-center justify-center gap-3 bg-[#25D366] hover:bg-[#20bd5a] text-white font-bold py-3.5 px-8 rounded-full shadow-lg hover:shadow-xl transition-all duration-300 w-full sm:w-auto">
                            <i data-lucide="message-square" class="w-5 h-5"></i> 
                            <span>Chat Admin Sekarang</span>
                        </a>
                        
                        <p class="text-xs font-medium text-gray-400 mt-5 flex items-center justify-center gap-1.5">
                            <i data-lucide="clock" class="w-3.5 h-3.5"></i> Dibalas cepat pada jam kerja (08.00 - 11.00)
                        </p>
                    </div>
                </div>
            </div>
            
            {{-- Contact Info (2 cols) --}}
            <div class="lg:col-span-2 space-y-4 fade-right">

                @php
                $contacts = [
                    [
                        'title' => 'Alamat',
                        'content' => 'Jl. Serimpi V No.338, RT.04/RW.10, Mekar Jaya, Kec. Sukmajaya, Kota Depok, Jawa Barat 16411',
                        'icon' => 'map-pin',
                        'color' => 'primary'
                    ],
                    [
                        'title' => 'Telepon / WhatsApp',
                        'content' => '0813-1040-8525',
                        'icon' => 'phone',
                        'color' => 'secondary'
                    ],
                    [
                        'title' => 'Email',
                        'content' => 'info@azzahra.sch.id',
                        'icon' => 'mail',
                        'color' => 'primary'
                    ],
                    [
                        'title' => 'Jam Operasional',
                        'content' => "Senin - Jumat: 08.00 - 11.00\nSabtu - Minggu: Tutup",
                        'icon' => 'clock',
                        'color' => 'secondary'
                    ],
                ];
                @endphp

                @foreach($contacts as $item)
                <div class="hover-card bg-white rounded-2xl p-5 border border-gray-100 shadow-sm group">
                    <div class="flex items-start gap-4">
                        <div class="w-11 h-11 rounded-xl bg-{{ $item['color'] }}-50 flex items-center justify-center flex-shrink-0 group-hover:bg-{{ $item['color'] }}-100 transition-colors">
                            <i data-lucide="{{ $item['icon'] }}" class="w-5 h-5 text-{{ $item['color'] }}-600"></i>
                        </div>
                        <div>
                            <h4 class="font-heading font-semibold text-gray-900 text-sm">
                                {{ $item['title'] }}
                            </h4>
                            <p class="text-gray-600 text-sm whitespace-pre-line leading-snug mt-0.5">
                                {{ $item['content'] }}
                            </p>
                        </div>
                    </div>
                </div>
                @endforeach

            </div>
        </div>

        {{-- Google Maps Bawah (Full Width) --}}
        <div class="w-full fade-up">
            <div class="bg-white p-2 rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3510.6726761888185!2d106.8353951!3d-6.394641999999999!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69e974c0b42d7f%3A0x23c154e4eaa6d1c1!2sPAUD%20AL%20QURAN%20AZZAHRA!5e1!3m2!1sen!2sid!4v1776849031234!5m2!1sen!2sid"
                    class="w-full h-[400px] md:h-[450px] rounded-xl border-0"
                    title="Lokasi PAUD Az-Zahra"
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>

    </div>
</section>