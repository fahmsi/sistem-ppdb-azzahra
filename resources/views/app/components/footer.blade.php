{{-- ============================================
    Section: Footer — Simplified (Social + Copyright)
    ============================================ --}}
<footer class="bg-gray-900 text-gray-400 relative overflow-hidden">
    {{-- Top Gradient Line --}}
    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-primary-600 via-secondary-500 to-primary-600"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="flex flex-col items-center justify-center gap-6">

            {{-- Social Media Icons --}}
            <div class="flex items-center gap-4">
                @php
                    $socials = [
                        ['name' => 'Facebook',  'icon' => 'facebook',  'hover' => 'hover:bg-blue-600',  'url' => $settings['social_facebook'] ?? '#'],
                        ['name' => 'Instagram', 'icon' => 'instagram', 'hover' => 'hover:bg-pink-500',  'url' => $settings['social_instagram'] ?? '#'],
                        ['name' => 'Youtube',   'icon' => 'youtube',   'hover' => 'hover:bg-red-600',   'url' => $settings['social_youtube'] ?? '#'],
                        ['name' => 'TikTok',    'icon' => 'tiktok',    'hover' => 'hover:bg-gray-700',  'url' => $settings['social_tiktok'] ?? '#'],
                    ];
                @endphp

                @foreach($socials as $social)
                    <a href="{{ $social['url'] }}"
                        target="_blank"
                        aria-label="{{ $social['name'] }}"
                        class="group w-11 h-11 rounded-xl bg-gray-800 flex items-center justify-center {{ $social['hover'] }} transition-all duration-300 hover:-translate-y-1 hover:shadow-lg flex-shrink-0">
                        <i class="{{ 'ri-' . $social['icon'] . '-fill' }} text-xl text-gray-300 group-hover:text-white transition-colors"></i>
                    </a>
                @endforeach
            </div>

            {{-- Copyright --}}
            <p class="text-sm text-center">
                &copy; {{ date('Y') }} <span class="text-gray-200 font-semibold tracking-wide">PAUD AL QUR'AN AZ-ZAHRA</span>. All rights reserved.
            </p>

            {{-- Creator Credit & Campus Identity --}}
            <div class="flex flex-col sm:flex-row items-center justify-center gap-3 text-sm mt-2 border-t border-gray-800 pt-6 w-full max-w-3xl">
                <span class="flex items-center gap-1.5 flex-wrap justify-center">
                    Made with 
                    <i data-lucide="heart" class="w-4 h-4 text-red-500 fill-red-500 inline-block animate-pulse"></i> 
                    and 
                    <i data-lucide="coffee" class="w-4 h-4 text-[#8b5a2b] inline-block"></i> 
                    by 
                    <a href="https://github.com/fahmsi" target="_blank" class="font-semibold text-gray-200 hover:text-primary-400 transition-colors">
                        Fahmi M. Al Hafizh
                    </a>
                </span>
                
                <span class="hidden sm:inline text-gray-600">|</span>
                
                <a href="https://nurulfikri.ac.id/" target="_blank" class="font-medium text-primary-500 hover:text-primary-400 hover:underline tracking-wide transition-colors text-center">
                    Student of Nurul Fikri College of Integrated Technology
                </a>
            </div>

        </div>
    </div>
</footer>

{{-- Back to Top Button --}}
<button id="backToTop"
    onclick="window.scrollTo({top: 0, behavior: 'smooth'})"
    class="fixed bottom-6 right-6 w-12 h-12 rounded-xl bg-gradient-to-br from-primary-600 to-primary-500 text-white flex items-center justify-center shadow-lg hover:shadow-xl hover:shadow-primary-500/30 transition-all duration-300 hover:-translate-y-1 opacity-0 invisible z-50 group"
    aria-label="Back to top">
    <i data-lucide="arrow-up" class="w-5 h-5 group-hover:animate-bounce"></i>
</button>

<script>
    // Memastikan DOM termuat sempurna sebelum menambahkan event listener
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