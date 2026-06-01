{{-- ============================================
Section: Testimoni Dinamis (Google Reviews Style)
============================================ --}}
<style>
    @keyframes marquee {
        0% {
            transform: translateX(0);
        }

        100% {
            transform: translateX(-50%);
        }
    }

    .animate-marquee-horizontal {
        display: flex;
        width: max-content;
        gap: 1.5rem;
        /* gap-6 */
        animation: marquee 30s linear infinite;
    }

    /* Opsional: Berhenti bergulir saat mouse diarahkan (hover) agar user bisa membaca */
    .animate-marquee-horizontal:hover {
        animation-play-state: paused;
    }
</style>
<section id="testimonial" class="py-20 lg:py-28 bg-white relative overflow-hidden">
    {{-- Decorative --}}
    <div class="absolute top-1/2 right-0 w-96 h-96 bg-primary-50 rounded-full translate-x-1/2 -translate-y-1/2"></div>
    <div class="absolute bottom-0 left-0 w-72 h-72 bg-secondary-50 rounded-full -translate-x-1/3 translate-y-1/3"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Section Header --}}
        <div class="text-center mb-16 fade-up">
            <span
                class="inline-flex items-center gap-2 bg-secondary-50 text-secondary-700 px-4 py-1.5 rounded-full text-sm font-medium mb-4">
                <i data-lucide="message-circle" class="w-4 h-4"></i>
                Testimoni
            </span>
            <h2 class="font-heading text-3xl lg:text-4xl font-bold text-gray-900 section-heading">
                Apa Kata Mereka?
            </h2>
            <p class="text-gray-600 max-w-2xl mx-auto mt-6">
                Testimoni dari orang tua dan siswa yang telah merasakan pendidikan di Az-Zahra
            </p>
        </div>

        {{-- Google Reviews Summary Bar --}}
        @if(isset($testimonials) && $testimonials->count() > 0)
            @php
                $avgRating = round($testimonials->avg('rating'), 1);
                $totalReviews = $testimonials->count();
            @endphp
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mb-12 fade-up">
                <div class="flex items-center gap-3 bg-white border border-gray-300 rounded-2xl px-6 py-3 shadow-sm">
                    <span class="text-3xl font-bold text-gray-900">{{ number_format($avgRating, 1) }}</span>
                    <div>
                        <div class="flex gap-0.5">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= floor($avgRating))
                                    <i data-lucide="star" class="w-5 h-5 text-amber-400 fill-amber-400"></i>
                                @elseif($i - $avgRating < 1 && $i - $avgRating > 0)
                                    <i data-lucide="star" class="w-5 h-5 text-amber-400 fill-amber-400 opacity-50"></i>
                                @else
                                    <i data-lucide="star" class="w-5 h-5 text-gray-300"></i>
                                @endif
                            @endfor
                        </div>
                        <p class="text-sm text-gray-500 mt-0.5">{{ $totalReviews }} ulasan</p>
                    </div>
                </div>
            </div>
        @endif

        {{-- Reviews Marquee (Flat & Smooth Mask) --}}
        @if(isset($testimonials) && $testimonials->count() > 0)
            @php
                $testimonials = $testimonials->values();
            @endphp

            <div class="relative w-full mt-14">
                <div class="relative mx-auto max-w-7xl">
                    <div class="relative w-full overflow-hidden py-4"
                        style="-webkit-mask-image: linear-gradient(to right, transparent, black 10%, black 90%, transparent); mask-image: linear-gradient(to right, transparent, black 10%, black 90%, transparent);">

                        <div class="animate-marquee-horizontal">

                            {{-- Set 1 --}}
                            <div class="flex gap-6">
                                @foreach($testimonials as $testimonial)
                                    <article class="w-[350px] flex-shrink-0 group">
                                        <div class="bg-white dark:bg-[#2b2c40] rounded-2xl border border-gray-200 dark:border-gray-700 hover:border-primary-200 transition-all duration-300 p-6 h-full flex flex-col">
                                            <div class="flex items-start gap-4 mb-4">
                                                <img src="https://ui-avatars.com/api/?name={{ urlencode($testimonial->name) }}&background=random&color=fff&size=48&font-size=0.4&bold=true&rounded=true"
                                                    alt="{{ $testimonial->name }}"
                                                    class="w-12 h-12 rounded-full flex-shrink-0 ring-2 ring-gray-100 group-hover:ring-primary-100 transition-all duration-300">

                                                <div class="flex-1 min-w-0">
                                                    <p class="font-heading font-bold text-gray-900 dark:text-gray-100 truncate">
                                                        {{ $testimonial->name }}</p>
                                                    <p class="text-xs text-gray-400 mt-0.5">
                                                        {{ $testimonial->created_at->diffForHumans() }}</p>
                                                </div>

                                                <div
                                                    class="flex-shrink-0 opacity-60 group-hover:opacity-100 transition-opacity">
                                                    <svg class="w-5 h-5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 0 1-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z"
                                                            fill="#4285F4" />
                                                        <path
                                                            d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"
                                                            fill="#34A853" />
                                                        <path
                                                            d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"
                                                            fill="#FBBC05" />
                                                        <path
                                                            d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"
                                                            fill="#EA4335" />
                                                    </svg>
                                                </div>
                                            </div>

                                            <div class="flex gap-0.5 mb-3">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= $testimonial->rating)
                                                        <i data-lucide="star" class="w-4 h-4 text-amber-400 fill-amber-400"></i>
                                                    @else
                                                        <i data-lucide="star" class="w-4 h-4 text-gray-300"></i>
                                                    @endif
                                                @endfor
                                            </div>

                                            <p class="text-gray-700 dark:text-gray-300 leading-relaxed text-sm flex-1">
                                                {{ $testimonial->content }}
                                            </p>
                                        </div>
                                    </article>
                                @endforeach
                            </div>

                            {{-- Set 2 (Duplicated for seamless loop) --}}
                            <div class="flex gap-6" aria-hidden="true">
                                @foreach($testimonials as $testimonial)
                                    <article class="w-[350px] flex-shrink-0 group">
                                        <div
                                            class="bg-white dark:bg-[#2b2c40] rounded-2xl border border-gray-100 dark:border-gray-700 hover:border-primary-200 transition-all duration-300 p-6 h-full flex flex-col">
                                            <div class="flex items-start gap-4 mb-4">
                                                <img src="https://ui-avatars.com/api/?name={{ urlencode($testimonial->name) }}&background=random&color=fff&size=48&font-size=0.4&bold=true&rounded=true"
                                                    alt="{{ $testimonial->name }}"
                                                    class="w-12 h-12 rounded-full flex-shrink-0 ring-2 ring-gray-100 group-hover:ring-primary-100 transition-all duration-300">

                                                <div class="flex-1 min-w-0">
                                                    <p class="font-heading font-bold text-gray-900 dark:text-gray-100 truncate">
                                                        {{ $testimonial->name }}</p>
                                                    <p class="text-xs text-gray-400 mt-0.5">
                                                        {{ $testimonial->created_at->diffForHumans() }}</p>
                                                </div>

                                                <div
                                                    class="flex-shrink-0 opacity-60 group-hover:opacity-100 transition-opacity">
                                                    <svg class="w-5 h-5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 0 1-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z"
                                                            fill="#4285F4" />
                                                        <path
                                                            d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"
                                                            fill="#34A853" />
                                                        <path
                                                            d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"
                                                            fill="#FBBC05" />
                                                        <path
                                                            d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"
                                                            fill="#EA4335" />
                                                    </svg>
                                                </div>
                                            </div>

                                            <div class="flex gap-0.5 mb-3">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= $testimonial->rating)
                                                        <i data-lucide="star" class="w-4 h-4 text-amber-400 fill-amber-400"></i>
                                                    @else
                                                        <i data-lucide="star" class="w-4 h-4 text-gray-300"></i>
                                                    @endif
                                                @endfor
                                            </div>

                                            <p class="text-gray-700 dark:text-gray-300 leading-relaxed text-sm flex-1">
                                                {{ $testimonial->content }}
                                            </p>
                                        </div>
                                    </article>
                                @endforeach
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        @else
            {{-- Empty State --}}
            <div class="text-center py-12 fade-up">
                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="message-circle" class="w-10 h-10 text-gray-400"></i>
                </div>
                <p class="text-gray-500">Belum ada testimoni tersedia.</p>
            </div>
        @endif

    </div>
</section>