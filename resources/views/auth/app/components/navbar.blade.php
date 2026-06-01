{{-- ============================================
    Section 1: Navbar — Sticky, Blur, Premium
    ============================================ --}}
<nav id="mainNav" class="sticky top-0 z-50 backdrop-blur-xl bg-white/80 border-b border-gray-100 shadow-none transition-all duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex justify-between items-center h-16 lg:h-18">

            {{-- Logo --}}
            <a href="#home" class="flex items-center gap-3 group">
                <img src="{{ asset('images/azzahra_logo.png') }}" alt="Logo PAUD Az-Zahra" class="w-12 h-12 object-contain group-hover:scale-105 transition-transform duration-300">
                
                <div>
                    <span class="hidden sm:block text-[10px] text-gray-500 -mt-1 font-medium tracking-wider uppercase">PAUD Al Qur'an</span>
                    <span class="text-lg font-heading font-bold text-gray-900 tracking-tight">Az-Zahra</span>
                </div>
            </a>

            @php
            $menu = [
                ['label'=>'Home', 'id'=>'home'],
                ['label'=>'Program', 'id'=>'kurikulum'], 
                ['label'=>'Agenda', 'id'=>'agenda'],
                ['label'=>'Syarat', 'id'=>'persyaratan'], 
                ['label'=>'Biaya', 'id'=>'biaya'],
                ['label'=>'Kontak', 'id'=>'kontak'],
            ];
            @endphp

            {{-- Desktop Menu --}}
            <div class="hidden lg:flex items-center gap-1">

                @foreach($menu as $item)
                <a href="#{{ $item['id'] }}"
                    class="nav-link relative px-4 py-2 text-sm font-medium text-slate-600 hover:text-primary-600 transition-colors duration-200 rounded-lg hover:bg-primary-50/50 group">
                    {{ $item['label'] }}
                    {{-- Active indicator --}}
                    <span class="absolute bottom-0 left-1/2 -translate-x-1/2 w-0 h-0.5 bg-gradient-to-r from-primary-600 to-secondary-600 rounded-full transition-all duration-300 group-hover:w-3/4"></span>
                </a>
                @endforeach

                <div class="flex items-center gap-2 ml-4 pl-4 border-l border-gray-200">
                    {{-- Tombol Masuk (Secondary - Low Visual Weight) --}}
                    <a href="/login"
                        class="px-4 py-2 text-sm font-semibold text-slate-600 hover:text-primary-600 transition-colors duration-200">
                        Masuk
                    </a>

                    {{-- Tombol Daftar (Primary - High Visual Weight) --}}
                    <a href="/register"
                        class="inline-flex items-center gap-2 bg-gradient-to-r from-primary-600 to-primary-500 hover:from-primary-700 hover:to-primary-600 text-white px-5 py-2.5 rounded-xl text-sm font-semibold shadow-md hover:shadow-lg transition-all duration-300 hover:-translate-y-0.5">
                        <i data-lucide="user-plus" class="w-4 h-4"></i>
                        Daftar
                    </a>
                </div>

            </div>

            {{-- Mobile Button --}}
            <button onclick="toggleMenu()" class="lg:hidden p-2 rounded-lg hover:bg-gray-100 transition" aria-label="Toggle menu">
                <i id="menuIcon" data-lucide="menu" class="w-6 h-6 text-gray-700"></i>
            </button>

        </div>

        {{-- Mobile Menu --}}
        <div id="mobileMenu" class="hidden lg:hidden pb-6 pt-2 border-t border-gray-100 mt-2">
            <div class="flex flex-col gap-1">
                @foreach($menu as $item)
                <a href="#{{ $item['id'] }}"
                    class="flex items-center gap-3 py-3 px-4 text-gray-700 hover:text-primary-600 hover:bg-primary-50 rounded-xl transition-all duration-200 font-medium">
                    {{ $item['label'] }}
                </a>
                @endforeach

                <div class="mt-3 pt-3 border-t border-gray-100 flex flex-col gap-2">
                    {{-- Tombol Masuk --}}
                    <a href="/login"
                        class="flex items-center justify-center gap-2 bg-gray-50 text-slate-700 px-5 py-3 rounded-xl font-semibold border border-gray-200">
                        Masuk
                    </a>
                    
                    {{-- Tombol Daftar --}}
                    <a href="/register"
                        class="flex items-center justify-center gap-2 bg-gradient-to-r from-primary-600 to-primary-500 text-white px-5 py-3 rounded-xl font-semibold shadow-md">
                        <i data-lucide="user-plus" class="w-4 h-4"></i>
                        Daftar
                    </a>
                </div>
            </div>
        </div>

    </div>
</nav>