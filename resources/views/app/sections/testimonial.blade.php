{{-- ============================================
Section: Testimoni Dinamis (Google Reviews Style - Carousel)
============================================ --}}

<section id="testimonial" class="py-20 lg:py-28 islamic-pattern bg-gradient-to-br from-blue-50 via-white to-green-50 relative overflow-hidden">
    {{-- Decorative --}}
    <div class="absolute top-1/2 right-0 w-96 h-96 bg-primary-50 rounded-full translate-x-1/2 -translate-y-1/2"></div>
    <div class="absolute bottom-0 left-0 w-72 h-72 bg-secondary-50 rounded-full -translate-x-1/3 translate-y-1/3"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Section Header --}}
        <div class="text-center mb-16 fade-up">
            <h2 class="section-heading font-heading text-3xl text-gray-900 lg:text-4xl">
                <span>Apa <span class="gradient-text">Kata Mereka?</span></span>
            </h2>
            <p class="text-gray-600 max-w-2xl mx-auto mt-6">
                Testimoni dari orang tua dan siswa yang telah merasakan pendidikan di Azzahra
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

        {{-- Testimonial Carousel --}}
        @if(isset($testimonials) && $testimonials->count() > 0)
            @php
                $testimonials = $testimonials->values();
                $perSlide = 2;
                $slides = $testimonials->chunk($perSlide);
                $totalSlides = $slides->count();
            @endphp

            <div class="testimonial-carousel fade-up" id="testiCarousel" data-total="{{ $totalSlides }}" data-interval="4000">

                <div class="testimonial-track" id="testiTrack">
                    @foreach($slides as $slideIndex => $slideItems)
                        <div class="testimonial-slide">
                            @foreach($slideItems as $testimonial)
                                <article class="testimonial-card">
                                    {{-- Left accent border is via CSS ::before --}}

                                    {{-- Photo --}}
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($testimonial->name) }}&background=random&color=fff&size=96&font-size=0.4&bold=true&rounded=false"
                                        alt="{{ $testimonial->name }}"
                                        class="testi-photo"
                                        loading="lazy">

                                    {{-- Content --}}
                                    <div class="testi-content">
                                        {{-- Decorative quote icon --}}
                                        <svg class="testi-quote-icon" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M14.017 18L14.017 10.609C14.017 4.905 17.748 1.039 23 0L23.995 2.151C21.563 3.068 20 5.789 20 8H24V18H14.017ZM0 18V10.609C0 4.905 3.748 1.039 9 0L9.996 2.151C7.563 3.068 6 5.789 6 8H9.983L9.983 18L0 18Z" />
                                        </svg>

                                        {{-- Review text --}}
                                        <p class="testi-text">{{ $testimonial->content }}</p>

                                        {{-- Meta: Name, Role, Google icon --}}
                                        <div class="testi-meta">
                                            <div class="testi-meta-left">
                                                <span class="testi-name">{{ $testimonial->name }}</span>
                                                <span class="testi-role">
                                                    {{ $testimonial->created_at->diffForHumans() }}
                                                </span>
                                            </div>
                                            {{-- Google logo --}}
                                            <div class="testi-google-icon">
                                                <svg class="w-5 h-5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 0 1-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z" fill="#4285F4" />
                                                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853" />
                                                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05" />
                                                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335" />
                                                </svg>
                                            </div>
                                        </div>

                                        {{-- Stars --}}
                                        <div class="testi-stars">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $testimonial->rating)
                                                    <i data-lucide="star" class="w-4 h-4 text-amber-400 fill-amber-400"></i>
                                                @else
                                                    <i data-lucide="star" class="w-4 h-4 text-gray-300"></i>
                                                @endif
                                            @endfor
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    @endforeach
                </div>

                {{-- Pagination Dots --}}
                @if($totalSlides > 1)
                    <div class="testi-dots" id="testiDots">
                        @for($d = 0; $d < $totalSlides; $d++)
                            <button class="testi-dot {{ $d === 0 ? 'active' : '' }}"
                                    data-slide="{{ $d }}"
                                    aria-label="Slide {{ $d + 1 }}"></button>
                        @endfor
                    </div>
                @endif
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
