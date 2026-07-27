{{-- ============================================
    Section: Footer — Premium Redesign
    ============================================ --}}
<footer class="relative overflow-hidden bg-gray-900 text-gray-400">
    {{-- Decorative gradient line --}}
    <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-primary-600 via-secondary-500 to-primary-600"></div>

    {{-- Subtle background pattern --}}
    <div class="pointer-events-none absolute inset-0 opacity-[0.03]"
         style="background-image: radial-gradient(circle at 1px 1px, white 1px, transparent 0); background-size: 40px 40px;">
    </div>

    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

        {{-- Main Footer Content --}}
        <div class="py-12 sm:py-14">
            
            {{-- Column 3: Social Media --}}
            <div class="mx-auto flex max-w-xl flex-col items-center text-center">
                <h3 class="mb-4 text-xs font-bold uppercase tracking-[0.16em] text-gray-500">Ikuti Kami</h3>
                @php
                    $socials = [
                        ['name' => 'Facebook',  'icon' => 'facebook',  'color' => 'hover:bg-blue-600  hover:shadow-blue-600/30',  'url' => $settings['social_facebook'] ?? '#'],
                        ['name' => 'Instagram', 'icon' => 'instagram', 'color' => 'hover:bg-[linear-gradient(45deg,#f9ce34,#ee2a7b,#6228d7)]  hover:shadow-pink-500/30',  'url' => $settings['social_instagram'] ?? '#'],
                        ['name' => 'Youtube',   'icon' => 'youtube',   'color' => 'hover:bg-red-600   hover:shadow-red-600/30',   'url' => $settings['social_youtube'] ?? '#'],
                        ['name' => 'TikTok',    'icon' => 'tiktok',    'color' => 'hover:bg-gray-700  hover:shadow-gray-700/30',  'url' => $settings['social_tiktok'] ?? '#'],
                    ];
                @endphp

                <div class="flex items-center gap-3">
                    @foreach($socials as $social)
                        <a href="{{ $social['url'] }}"
                           target="_blank"
                           rel="noopener noreferrer"
                           aria-label="{{ $social['name'] }}"
                           class="group flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-xl bg-white/[0.06] ring-1 ring-white/10 transition-[transform,background-color,box-shadow] duration-300 transform-gpu will-change-[transform,box-shadow] hover:-translate-y-1 hover:shadow-lg {{ $social['color'] }}">
                            <i class="{{ 'ri-' . $social['icon'] . '-fill' }} text-lg text-gray-400 transition-colors group-hover:text-white"></i>
                        </a>
                    @endforeach
                </div>

                <p class="mt-5 text-sm leading-relaxed text-gray-500">
                    Ikuti media sosial kami untuk info terbaru seputar kegiatan dan pendaftaran.
                </p>
            </div>
        </div>

        {{-- Bottom Bar --}}
        <div class="flex flex-col items-center gap-4 border-t border-gray-800 py-6 sm:flex-row sm:justify-between">
            {{-- Copyright --}}
            <p class="text-center text-sm sm:text-left">
                &copy; {{ date('Y') }} <span class="font-semibold text-gray-300">PAUD AL QUR'AN AZZAHRA</span>.
                <!-- Hak Cipta Dilindungi. -->
            </p>

            {{-- Legal Links --}}
            <nav class="flex flex-wrap items-center justify-center gap-1 text-sm" aria-label="Informasi legal">
                <a href="{{ route('terms') }}"
                   class="rounded-lg px-3 py-1.5 transition-colors duration-200 hover:bg-white/[0.06] hover:text-white">
                    Syarat & Ketentuan
                </a>
                <span class="text-gray-700">•</span>
                <a href="{{ route('privacy') }}"
                   class="rounded-lg px-3 py-1.5 transition-colors duration-200 hover:bg-white/[0.06] hover:text-white">
                    Kebijakan Privasi
                </a>
            </nav>
        </div>

        {{-- Creator Credit --}}
        <div class="flex flex-wrap items-center justify-center gap-1.5 border-t border-gray-800/50 px-4 py-4 text-center text-xs text-gray-600">
            Made with
            <i data-lucide="heart" class="inline-block h-3.5 w-3.5 animate-pulse fill-red-500 text-red-500"></i>
            and
            <i data-lucide="coffee" class="inline-block h-3.5 w-3.5 text-[#8b5a2b]"></i>
            by
            <a href="https://www.linkedin.com/in/fahmimuhammadalhafizh/" target="_blank" class="font-semibold text-gray-200 hover:text-primary-400 transition-colors">
                Fami
            </a>

            <span class="hidden sm:inline text-gray-600">|</span>
                
            <a href="https://nurulfikri.ac.id/" target="_blank" rel="noopener noreferrer" class="font-medium text-primary-500 hover:text-primary-400 hover:underline tracking-wide transition-colors text-center">
                Student of Nurul Fikri College of Integrated Technology
            </a>
        </div>
    </div>
</footer>

{{-- Back to Top Button --}}
<button id="backToTop"
    onclick="window.scrollTo({top: 0, behavior: 'smooth'})"
    class="group fixed bottom-6 right-6 z-50 flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-primary-600 to-primary-500 text-white opacity-0 invisible shadow-lg transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-primary-500/30"
    aria-label="Kembali ke atas">
    <i data-lucide="arrow-up" class="h-5 w-5 group-hover:animate-bounce"></i>
</button>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const backToTopBtn = document.getElementById('backToTop');

        if (backToTopBtn) {
            window.addEventListener('scroll', function() {
                if (window.scrollY > 400) {
                    backToTopBtn.classList.remove('opacity-0', 'invisible');
                    backToTopBtn.classList.add('opacity-100', 'visible');
                } else {
                    backToTopBtn.classList.add('opacity-0', 'invisible');
                    backToTopBtn.classList.remove('opacity-100', 'visible');
                }
            });
        }
    });
</script>
