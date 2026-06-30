{{-- ============================================
    Section: Gallery Kegiatan Sekolah (Carousel)
    ============================================ --}}
<section id="gallery" class="relative overflow-hidden py-20 lg:py-24">
    {{-- Decorative --}}
    <div class="absolute top-0 left-0 w-72 h-72 bg-primary-50 rounded-full -translate-y-1/2 -translate-x-1/2"></div>
    <div class="absolute bottom-0 right-0 w-64 h-64 bg-secondary-50 rounded-full translate-y-1/3 translate-x-1/3"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between mb-10 fade-up">
            <div>
                <h2 class="section-heading font-heading mb-2 text-gray-900">
                    <span>Gallery <span class="gradient-text">Kegiatan</span></span>
                </h2>
                <p class="text-gray-600 max-w-xl mt-3">
                    Momen-momen berharga dari kegiatan belajar dan bermain di PAUD Al Qur'an Azzahra
                </p>
            </div>

            @if($galleries->count() > 3)
            <div class="flex items-center gap-2 mt-4 sm:mt-0">
                <button onclick="galleryCarousel.prev()" class="gallery-nav-btn" aria-label="Sebelumnya">
                    <i data-lucide="chevron-left" class="w-5 h-5"></i>
                </button>
                <button onclick="galleryCarousel.next()" class="gallery-nav-btn" aria-label="Berikutnya">
                    <i data-lucide="chevron-right" class="w-5 h-5"></i>
                </button>
            </div>
            @endif
        </div>

        @if($galleries->isNotEmpty())
            {{-- Carousel --}}
            <div class="gallery-carousel-wrapper fade-up">
                <div class="gallery-carousel" id="galleryCarousel">
                    <div class="gallery-carousel-track" id="galleryTrack">
                        @foreach($galleries as $item)
                            <div class="gallery-carousel-slide">
                                <div class="gallery-card group" onclick="openGalleryLightbox({{ $loop->index }})">
                                    <img src="{{ $item->image_url }}"
                                         alt="{{ $item->title }}"
                                         class="gallery-card-img"
                                         loading="lazy">

                                    {{-- Overlay --}}
                                    <div class="gallery-card-overlay">
                                        <div class="gallery-card-caption">
                                            <h4 class="text-white font-semibold text-sm leading-snug">{{ $item->title }}</h4>
                                            @if($item->description)
                                                <p class="text-white/70 text-xs mt-1 line-clamp-1">{{ $item->description }}</p>
                                            @endif
                                        </div>
                                        <div class="gallery-card-zoom">
                                            <i data-lucide="zoom-in" class="w-4 h-4"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Progress Bar --}}
                <div class="gallery-progress" id="galleryProgress">
                    <div class="gallery-progress-bar" id="galleryProgressBar"></div>
                </div>
            </div>

            {{-- Lightbox Modal --}}
            <div id="galleryLightbox" class="gallery-lightbox" onclick="closeGalleryLightbox(event)">
                <button class="gallery-lightbox-close" onclick="closeGalleryLightbox(event)" aria-label="Tutup">
                    <i data-lucide="x" class="w-6 h-6"></i>
                </button>

                <button class="gallery-lightbox-nav gallery-lightbox-prev" onclick="navigateGallery(-1, event)" aria-label="Sebelumnya">
                    <i data-lucide="chevron-left" class="w-6 h-6"></i>
                </button>

                <button class="gallery-lightbox-nav gallery-lightbox-next" onclick="navigateGallery(1, event)" aria-label="Berikutnya">
                    <i data-lucide="chevron-right" class="w-6 h-6"></i>
                </button>

                <div class="gallery-lightbox-content" onclick="event.stopPropagation()">
                    <img id="lightboxImage" src="" alt="" class="gallery-lightbox-img">
                    <div id="lightboxCaption" class="gallery-lightbox-caption">
                        <h4 id="lightboxTitle" class="text-white font-bold text-lg"></h4>
                        <p id="lightboxDesc" class="text-white/70 text-sm mt-1"></p>
                        <p id="lightboxCounter" class="text-white/50 text-xs mt-2"></p>
                    </div>
                </div>
            </div>

            {{-- Gallery Data for JS --}}
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
            {{-- Empty State --}}
            <div class="text-center py-12 fade-up">
                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="image" class="w-10 h-10 text-gray-400"></i>
                </div>
                <p class="text-gray-500">Belum ada foto gallery tersedia.</p>
            </div>
        @endif

    </div>
</section>
