{{-- ============================================
    Section 14: Footer — Simplified (Social + Copyright)
    ============================================ --}}
<footer class="bg-gray-900 text-gray-300 relative overflow-hidden">
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
                    class="w-11 h-11 rounded-xl bg-gray-800 flex items-center justify-center {{ $social['hover'] }} transition-all duration-300 hover:-translate-y-1 hover:shadow-lg flex-shrink-0 leading-none">
                    <i class="{{ 'ri-' . $social['icon'] . '-fill' }} text-xl leading-none align-middle text-white"></i>
                </a>
                @endforeach
            </div>

            {{-- Copyright --}}
            <p class="text-sm text-gray-500 text-center">
                © {{ date('Y') }} <span class="text-gray-400 font-medium">PAUD AL QUR'AN AZ-ZAHRA</span>. All rights reserved.
            </p>

        </div>

    </div>
</footer>

{{-- Back to Top Button --}}
<button id="backToTop"
    onclick="window.scrollTo({top: 0, behavior: 'smooth'})"
    class="fixed bottom-6 right-6 w-12 h-12 rounded-xl bg-gradient-to-br from-primary-600 to-primary-500 text-white flex items-center justify-center shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-1 opacity-0 invisible z-50"
    aria-label="Back to top">
    <i data-lucide="arrow-up" class="w-5 h-5"></i>
</button>

<script>
    // Back to top visibility
    window.addEventListener('scroll', function() {
        var btn = document.getElementById('backToTop');
        if (btn) {
            if (window.scrollY > 400) {
                btn.classList.remove('opacity-0', 'invisible');
                btn.classList.add('opacity-100', 'visible');
            } else {
                btn.classList.add('opacity-0', 'invisible');
                btn.classList.remove('opacity-100', 'visible');
            }
        }
    });
</script>