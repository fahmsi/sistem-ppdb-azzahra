{{-- ============================================
    Section 9: Fasilitas Sekolah
    ============================================ --}}
<section class="py-20 lg:py-28 bg-gradient-to-b from-gray-50 to-white relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="text-center mb-14 fade-up">
            <span class="inline-flex items-center gap-2 bg-primary-50 text-primary-700 px-4 py-1.5 rounded-full text-sm font-medium mb-4">
                <i data-lucide="building" class="w-4 h-4"></i>
                Fasilitas
            </span>
            <h2 class="font-heading text-3xl lg:text-4xl font-bold text-gray-900 section-heading">
                Fasilitas Sekolah
            </h2>
            <p class="text-gray-600 max-w-2xl mx-auto mt-6">
                Fasilitas lengkap dan modern untuk mendukung proses belajar yang optimal
            </p>
        </div>

        @php
            $fasilitas = [
                [
                    'nama' => 'Ruangan Ber-AC',
                    'desc' => 'Ruang kelas nyaman dengan pendingin udara',
                    'gambar' => 'https://images.unsplash.com/photo-1567746455504-cb3213f8f5b8?w=600&auto=format&fit=crop',
                    'icon' => 'wind'
                ],
                [
                    'nama' => 'Kolam Mandi Bola',
                    'desc' => 'Area bermain indoor yang aman dan menyenangkan',
                    'gambar' => 'https://images.unsplash.com/photo-1498940757830-82f7813bf178?w=600&auto=format&fit=crop',
                    'icon' => 'circle-dot'
                ],
                [
                    'nama' => 'Taman Bermain',
                    'desc' => 'Playground outdoor dengan perlengkapan lengkap',
                    'gambar' => 'https://images.unsplash.com/photo-1606733894347-7cb201dc810b?w=600&auto=format&fit=crop',
                    'icon' => 'trees'
                ],
                [
                    'nama' => 'Ruang Sholat',
                    'desc' => 'Musholla bersih untuk praktek ibadah',
                    'gambar' => 'https://images.unsplash.com/photo-1609599006353-e629aaabfeae?w=600&auto=format&fit=crop',
                    'icon' => 'moon'
                ],
                [
                    'nama' => 'Perpustakaan',
                    'desc' => 'Koleksi buku cerita dan edukasi anak',
                    'gambar' => 'https://images.unsplash.com/photo-1481627834876-b7833e8f5570?w=600&auto=format&fit=crop',
                    'icon' => 'book-open'
                ],
                [
                    'nama' => 'Kolam Renang',
                    'desc' => 'Fasilitas renang dengan pengawasan',
                    'gambar' => 'https://images.unsplash.com/photo-1760259903993-2c4fb13e8e5f?q=80&w=870&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                    'icon' => 'waves'
                ],
            ];
        @endphp

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 stagger-children">
            @foreach ($fasilitas as $f)
                <div class="hover-card group bg-white border border-gray-100 rounded-2xl overflow-hidden shadow-sm">
                    {{-- Image --}}
                    <div class="relative h-52 overflow-hidden">
                        <img src="{{ $f['gambar'] }}"
                            alt="{{ $f['nama'] }}"
                            class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                            loading="lazy">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    </div>

                    {{-- Content --}}
                    <div class="p-5 flex items-center gap-4">
                        <div class="w-11 h-11 rounded-xl bg-primary-50 flex items-center justify-center flex-shrink-0 group-hover:bg-primary-100 transition-colors">
                            <i data-lucide="{{ $f['icon'] }}" class="w-5 h-5 text-primary-600"></i>
                        </div>
                        <div>
                            <h4 class="font-heading font-semibold text-gray-900">{{ $f['nama'] }}</h4>
                            <p class="text-sm text-gray-500">{{ $f['desc'] }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

    </div>
</section>
