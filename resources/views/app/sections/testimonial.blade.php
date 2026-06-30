{{-- Section: Testimoni orang tua dan siswa --}}
<section id="testimonial" class="relative overflow-hidden py-16 sm:py-20 lg:py-28">
    <div class="pointer-events-none absolute -right-28 top-16 h-80 w-80 rounded-full bg-primary-200/25 blur-3xl" aria-hidden="true"></div>
    <div class="pointer-events-none absolute -left-32 bottom-10 h-80 w-80 rounded-full bg-secondary-200/25 blur-3xl" aria-hidden="true"></div>

    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        @if(isset($testimonials) && $testimonials->count() > 0)
            @php
                $avgRating = round($testimonials->avg('rating'), 1);
                $totalReviews = $testimonials->count();
                $testimonials = $testimonials->sortBy('created_at')->values();
                $totalSlides = $testimonials->count();
            @endphp

            <div class="grid items-center gap-10 sm:gap-12 lg:grid-cols-12 lg:gap-16">
                <!-- Left Column: Title & Ratings Summary -->
                <div class="min-w-0 lg:col-span-5 fade-up">
                    <span class="inline-flex items-center gap-2 rounded-full border border-primary-200 bg-white/90 px-4 py-2 text-sm font-semibold text-primary-700 shadow-sm">
                        <i data-lucide="heart-handshake" class="h-4 w-4 text-secondary-600" aria-hidden="true"></i>
                        Cerita Keluarga Azzahra
                    </span>
                    
                    <h2 class="mt-5 font-heading text-2xl font-extrabold tracking-tight text-gray-900 sm:mt-6 sm:text-3xl lg:text-4xl leading-tight">
                        <span>Pengalaman yang <span class="gradient-text">Mereka Rasakan</span></span>
                    </h2>
                    
                    <p class="mt-3 text-sm leading-relaxed text-gray-600 sm:mt-4 sm:text-base lg:text-lg">
                        Cerita nyata dari orang tua dan siswa tentang proses belajar, pendampingan, dan lingkungan tumbuh di PAUD Al Qur'an Azzahra.
                    </p>

                    <!-- Beautifully Refined Rating Summary Card -->
                    <div class="mt-6 rounded-2xl border border-primary-700/20 bg-gradient-to-br from-primary-900 via-primary-800 to-secondary-900 p-5 text-white shadow-xl shadow-primary-900/15 relative overflow-hidden sm:mt-8 sm:rounded-3xl sm:p-6">
                        <!-- Glowing spots inside dark card -->
                        <div class="absolute -right-10 -top-10 h-32 w-32 rounded-full bg-primary-500/10 blur-2xl" aria-hidden="true"></div>
                        <div class="absolute -left-10 -bottom-10 h-32 w-32 rounded-full bg-secondary-500/10 blur-2xl" aria-hidden="true"></div>

                        <div class="relative flex items-center justify-between gap-4">
                            <div class="min-w-0">
                                <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-primary-300">Rata-rata penilaian</p>
                                <div class="mt-2 flex items-end gap-2">
                                    <span class="font-heading text-4xl font-extrabold leading-none sm:text-5xl">{{ number_format($avgRating, 1) }}</span>
                                    <span class="pb-1 text-sm font-semibold text-primary-300">/ 5.0</span>
                                </div>
                            </div>
                            <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-white/10 ring-1 ring-white/15 sm:h-14 sm:w-14">
                                <i data-lucide="message-circle-heart" class="h-6 w-6 text-secondary-300 sm:h-7 sm:w-7" aria-hidden="true"></i>
                            </span>
                        </div>

                        <div class="relative mt-5 flex flex-wrap items-center justify-between gap-3 border-t border-white/10 pt-4 sm:mt-6">
                            <div class="flex gap-1" aria-label="Rating {{ number_format($avgRating, 1) }} dari 5">
                                @for($i = 1; $i <= 5; $i++)
                                    <i data-lucide="star"
                                       class="h-4 w-4 {{ $i <= round($avgRating) ? 'fill-amber-400 text-amber-400' : 'text-white/25' }}"
                                       aria-hidden="true"></i>
                                @endfor
                            </div>
                            <span class="text-xs font-semibold text-primary-200">{{ $totalReviews }} ulasan di Google Reviews</span>
                        </div>
                    </div>

                    <!-- Navigation controls (Desktop) -->
                    @if($totalSlides > 1)
                        <div class="mt-8 hidden lg:flex items-center gap-3">
                            <button type="button" id="testiPrev" class="flex h-12 w-12 items-center justify-center rounded-full border border-gray-200 bg-white text-gray-700 shadow-sm hover:border-primary-300 hover:text-primary-600 transition-all focus:outline-none focus:ring-2 focus:ring-primary-500" aria-label="Testimoni sebelumnya">
                                <i data-lucide="arrow-left" class="h-5 w-5"></i>
                            </button>
                            <button type="button" id="testiNext" class="flex h-12 w-12 items-center justify-center rounded-full border border-gray-200 bg-white text-gray-700 shadow-sm hover:border-primary-300 hover:text-primary-600 transition-all focus:outline-none focus:ring-2 focus:ring-primary-500" aria-label="Testimoni selanjutnya">
                                <i data-lucide="arrow-right" class="h-5 w-5"></i>
                            </button>
                        </div>
                    @endif
                </div>

                <!-- Right Column: Spotlight Testimonials Carousel -->
                <div class="min-w-0 overflow-hidden lg:col-span-7 relative">
                    <!-- Soft gradient glow under the card -->
                    <div class="absolute -inset-2 rounded-[2rem] bg-gradient-to-tr from-primary-100 to-secondary-100 opacity-50 blur-lg sm:rounded-[2.5rem]" aria-hidden="true"></div>

                    <div class="testimonial-carousel-shell fade-up rounded-2xl bg-white/40 p-1.5 shadow-2xl shadow-primary-900/[0.04] backdrop-blur-md relative z-10 sm:rounded-[2rem] sm:p-2 md:rounded-[2.5rem] md:p-4">
                        <div class="testimonial-carousel" id="testiCarousel" data-total="{{ $totalSlides }}" data-interval="6000" role="region" aria-label="Testimoni keluarga Azzahra">
                            <div class="testimonial-track" id="testiTrack">
                                @foreach($testimonials as $slideIndex => $testimonial)
                                    <div class="testimonial-slide" role="group" aria-label="Testimoni {{ $slideIndex + 1 }} dari {{ $totalSlides }}">
                                        <article class="testimonial-card group relative flex flex-col justify-between h-full bg-white/90 border border-white rounded-xl p-5 shadow-lg hover:shadow-xl transition-shadow duration-300 sm:rounded-2xl sm:p-6 md:rounded-[2rem] md:p-8">
                                            
                                            <!-- Quote icon in background -->
                                            <svg class="absolute right-4 top-4 h-14 w-14 text-primary-200/25 pointer-events-none transition-transform duration-300 transform-gpu will-change-transform group-hover:scale-110 sm:right-6 sm:top-6 sm:h-20 sm:w-20" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                                <path d="M14.017 18L14.017 10.609C14.017 4.905 17.748 1.039 23 0L23.995 2.151C21.563 3.068 20 5.789 20 8H24V18H14.017ZM0 18V10.609C0 4.905 3.748 1.039 9 0L9.996 2.151C7.563 3.068 6 5.789 6 8H9.983L9.983 18L0 18Z" />
                                            </svg>

                                            <div class="min-w-0">
                                                <!-- Stars rating -->
                                                <div class="flex gap-1" aria-label="{{ $testimonial->rating }} dari 5 bintang">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <i data-lucide="star"
                                                           class="h-4 w-4 sm:h-5 sm:w-5 {{ $i <= $testimonial->rating ? 'fill-amber-400 text-amber-400' : 'text-gray-300' }}"
                                                           aria-hidden="true"></i>
                                                    @endfor
                                                </div>

                                                <!-- Testimonial Content text -->
                                                <p class="mt-4 text-sm leading-relaxed text-gray-700 italic font-medium sm:mt-6 sm:text-base lg:text-lg break-words">
                                                    "{{ $testimonial->content }}"
                                                </p>
                                            </div>

                                            <!-- Author metadata footer -->
                                            <div class="mt-5 flex flex-col gap-3 border-t border-gray-100 pt-4 sm:mt-8 sm:flex-row sm:items-center sm:justify-between sm:gap-4 sm:pt-6">
                                                <div class="flex items-center gap-3 min-w-0">
                                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($testimonial->name) }}&background=696cff&color=fff&size=96&font-size=0.4&bold=true&rounded=false"
                                                         alt="Foto profil {{ $testimonial->name }}"
                                                         class="h-10 w-10 shrink-0 rounded-xl object-cover border-2 border-white shadow-md ring-1 ring-primary-100 sm:h-12 sm:w-12"
                                                         loading="lazy">
                                                    <div class="min-w-0">
                                                        <h4 class="font-heading text-sm font-bold text-gray-900 truncate">{{ $testimonial->name }}</h4>
                                                        <p class="text-xs text-gray-500">{{ $testimonial->created_at->diffForHumans() }}</p>
                                                    </div>
                                                </div>

                                                <div class="flex items-center gap-2 shrink-0">
                                                    <!-- Google Logo -->
                                                    <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg border border-gray-200 bg-white p-1.5 shadow-sm" title="Ulasan terpublikasi di Google">
                                                        <svg class="h-full w-full" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                                            <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 0 1-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z" fill="#4285F4" />
                                                            <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853" />
                                                            <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05" />
                                                            <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335" />
                                                        </svg>
                                                    </span>

                                                    <div class="flex items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">
                                                        <i data-lucide="badge-check" class="h-3.5 w-3.5 text-emerald-600" aria-hidden="true"></i>
                                                        Orang Tua
                                                    </div>
                                                </div>
                                            </div>

                                        </article>
                                    </div>
                                @endforeach
                            </div>
                            
                            <!-- Bottom control dots and mobile arrows -->
                            <div class="mt-4 flex items-center justify-between gap-2 px-1 sm:mt-6 sm:px-2">
                                @if($totalSlides > 1)
                                    <div class="testi-dots flex min-w-0 flex-wrap gap-1" id="testiDots" aria-label="Pilih halaman testimoni">
                                        @for($d = 0; $d < $totalSlides; $d++)
                                            <button type="button"
                                                    class="testi-dot {{ $d === 0 ? 'active' : '' }}"
                                                    data-slide="{{ $d }}"
                                                    aria-label="Tampilkan testimoni halaman {{ $d + 1 }}"></button>
                                        @endfor
                                    </div>
                                    
                                    <!-- Mobile only navigation arrows -->
                                    <div class="flex shrink-0 items-center gap-2 lg:hidden">
                                        <button type="button" id="testiPrevMobile" class="flex h-9 w-9 items-center justify-center rounded-full border border-gray-200 bg-white text-gray-700 shadow-sm active:bg-gray-50 sm:h-10 sm:w-10" aria-label="Testimoni sebelumnya">
                                            <i data-lucide="chevron-left" class="h-4 w-4 sm:h-5 sm:w-5"></i>
                                        </button>
                                        <button type="button" id="testiNextMobile" class="flex h-9 w-9 items-center justify-center rounded-full border border-gray-200 bg-white text-gray-700 shadow-sm active:bg-gray-50 sm:h-10 sm:w-10" aria-label="Testimoni selanjutnya">
                                            <i data-lucide="chevron-right" class="h-4 w-4 sm:h-5 sm:w-5"></i>
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="fade-up rounded-2xl border border-gray-200 bg-white/80 px-6 py-14 text-center shadow-sm sm:rounded-[2rem]">
                <span class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-primary-50 text-primary-600">
                    <i data-lucide="message-circle" class="h-8 w-8" aria-hidden="true"></i>
                </span>
                <h2 class="mt-5 font-heading text-xl font-bold text-gray-900">Cerita keluarga Azzahra segera hadir</h2>
                <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-gray-500">Belum ada testimoni yang dipublikasikan saat ini.</p>
            </div>
        @endif
    </div>
</section>
