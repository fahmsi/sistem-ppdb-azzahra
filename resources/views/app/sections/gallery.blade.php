{{-- Section: Galeri kegiatan sekolah --}}
<section id="gallery" class="relative isolate overflow-hidden py-20 sm:py-24 lg:py-28">
    <div class="pointer-events-none absolute -left-28 top-12 h-72 w-72 rounded-full bg-primary-200/30 blur-3xl" aria-hidden="true"></div>
    <div class="pointer-events-none absolute -right-24 bottom-0 h-80 w-80 rounded-full bg-secondary-200/30 blur-3xl" aria-hidden="true"></div>

    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="fade-up mb-10 flex flex-col gap-6 lg:mb-12 lg:flex-row lg:items-end lg:justify-between">
            <div class="max-w-2xl">
                <span class="inline-flex items-center gap-2 rounded-full border border-primary-100 bg-white/80 px-3.5 py-2 text-xs font-bold uppercase tracking-[0.16em] text-primary-700 shadow-sm backdrop-blur-sm">
                    <i data-lucide="camera" class="h-4 w-4" aria-hidden="true"></i>
                    Dokumentasi kegiatan
                </span>
                <h2 class="section-heading mt-5 font-heading text-gray-900">
                    Momen belajar yang <span class="gradient-text">penuh cerita</span>
                </h2>
                <p class="mt-4 max-w-xl text-sm leading-7 text-gray-600 sm:text-base">
                    Lihat keseruan anak-anak saat belajar, bermain, berkarya, dan bertumbuh bersama di PAUD Al Qur'an Azzahra.
                </p>
            </div>

            @if($galleries->isNotEmpty())
                <div class="flex items-center justify-between gap-4 sm:justify-start">
                    <span class="inline-flex items-center gap-2 text-sm font-semibold text-gray-500">
                        <span class="h-2 w-2 rounded-full bg-secondary-500"></span>
                        {{ $galleries->count() }} momen
                    </span>

                    @if($galleries->count() > 1)
                        <div class="flex items-center gap-2" aria-label="Kontrol galeri">
                            <button type="button" id="galleryPrevButton" onclick="galleryCarousel.prev()" class="gallery-nav-btn" aria-label="Foto sebelumnya">
                                <i data-lucide="chevron-left" class="h-5 w-5" aria-hidden="true"></i>
                            </button>
                            <button type="button" id="galleryNextButton" onclick="galleryCarousel.next()" class="gallery-nav-btn" aria-label="Foto berikutnya">
                                <i data-lucide="chevron-right" class="h-5 w-5" aria-hidden="true"></i>
                            </button>
                        </div>
                    @endif
                </div>
            @endif
        </div>

        @if($galleries->isNotEmpty())
            <div class="gallery-carousel-wrapper fade-up" role="region" aria-label="Galeri kegiatan sekolah">
                <div class="gallery-carousel" id="galleryCarousel">
                    <div class="gallery-carousel-track" id="galleryTrack">
                        @foreach($galleries as $item)
                            <div class="gallery-carousel-slide">
                                <button
                                    type="button"
                                    class="gallery-card group"
                                    onclick="openGalleryLightbox({{ $loop->index }})"
                                    aria-label="Buka foto: {{ $item->title }}">
                                    <img
                                        src="{{ $item->image_url }}"
                                        alt="{{ $item->title }}"
                                        class="gallery-card-img"
                                        loading="lazy"
                                        decoding="async">

                                    <span class="gallery-card-overlay" aria-hidden="true">
                                        <span class="gallery-card-caption">
                                            <span class="mb-2 block text-[10px] font-bold uppercase tracking-[0.18em] text-white/65">Momen {{ str_pad((string) ($loop->index + 1), 2, '0', STR_PAD_LEFT) }}</span>
                                            <span class="block text-left font-heading text-base font-bold leading-snug text-white sm:text-lg">{{ $item->title }}</span>
                                            @if($item->description)
                                                <span class="mt-1.5 line-clamp-1 block text-left text-xs leading-5 text-white/70 sm:text-sm">{{ $item->description }}</span>
                                            @endif
                                        </span>
                                        <span class="gallery-card-zoom">
                                            <i data-lucide="maximize-2" class="h-4 w-4" aria-hidden="true"></i>
                                        </span>
                                    </span>
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>

                @if($galleries->count() > 1)
                    <div class="gallery-progress" id="galleryProgress" aria-hidden="true">
                        <div class="gallery-progress-bar" id="galleryProgressBar"></div>
                    </div>
                @endif
            </div>

            @php
                $galleryArray = $galleries->map(fn($g) => [
                    'url' => $g->image_url,
                    'title' => $g->title,
                    'desc' => $g->description ?? '',
                ])->values()->all();
            @endphp
            <script>
                var galleryData = @json($galleryArray);
            </script>
        @else
            <div class="fade-up rounded-[2rem] border border-dashed border-gray-200 bg-white/70 px-6 py-16 text-center shadow-sm backdrop-blur-sm">
                <span class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-primary-50 text-primary-500">
                    <i data-lucide="images" class="h-8 w-8" aria-hidden="true"></i>
                </span>
                <h3 class="mt-5 font-heading text-lg font-bold text-gray-800">Galeri sedang disiapkan</h3>
                <p class="mt-2 text-sm text-gray-500">Dokumentasi kegiatan terbaru akan segera hadir di sini.</p>
            </div>
        @endif
    </div>
</section>

@if($galleries->isNotEmpty())
    <div
        id="galleryLightbox"
        class="gallery-lightbox"
        role="dialog"
        aria-modal="true"
        aria-hidden="true"
        aria-labelledby="lightboxTitle"
        onclick="closeGalleryLightbox(event)">
        <button type="button" class="gallery-lightbox-close" onclick="closeGalleryLightbox(event)" aria-label="Tutup pratinjau galeri">
            <i data-lucide="x" class="h-6 w-6" aria-hidden="true"></i>
        </button>

        @if($galleries->count() > 1)
            <button type="button" class="gallery-lightbox-nav gallery-lightbox-prev" onclick="navigateGallery(-1, event)" aria-label="Foto sebelumnya">
                <i data-lucide="chevron-left" class="h-6 w-6" aria-hidden="true"></i>
            </button>
            <button type="button" class="gallery-lightbox-nav gallery-lightbox-next" onclick="navigateGallery(1, event)" aria-label="Foto berikutnya">
                <i data-lucide="chevron-right" class="h-6 w-6" aria-hidden="true"></i>
            </button>
        @endif

        <div class="gallery-lightbox-content" onclick="event.stopPropagation()">
            <div class="gallery-lightbox-stage">
                <img id="lightboxImage" src="" alt="" class="gallery-lightbox-img">
            </div>
            <div id="lightboxCaption" class="gallery-lightbox-caption">
                <div>
                    <h3 id="lightboxTitle" class="font-heading text-lg font-bold text-white sm:text-xl"></h3>
                    <p id="lightboxDesc" class="mt-1 text-sm leading-6 text-white/60"></p>
                </div>
                <p id="lightboxCounter" class="shrink-0 text-xs font-bold tracking-[0.16em] text-white/45"></p>
            </div>
        </div>
    </div>
@endif
