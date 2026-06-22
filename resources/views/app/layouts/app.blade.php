<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="SPMB PAUD Al Qur'an Az-Zahra - Sistem Penerimaan Murid Baru. Lembaga pendidikan anak usia dini berbasis Islam yang mengedepankan nilai Qur'ani dan perkembangan holistik.">
    <meta name="keywords" content="PAUD, Az-Zahra, SPMB, Sistem Penerimaan Murid Baru, Pendidikan Islam, Al-Quran, Depok">

    <title>PAUD Al Qur'an Az-Zahra Depok</title>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('images/azzahra_logo.png') }}" type="image/png">

    {{-- Google Fonts: Plus Jakarta Sans + Fredoka for hero display --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <script src="https://unpkg.com/lucide@latest" defer></script>

</head>
<body class="bg-gray-50 font-body antialiased text-gray-900">

    @include('app.components.navbar')

    <main>
        @yield('content')
    </main>

    @include('app.components.footer')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (window.lucide) {
                lucide.createIcons();
            }
        });
    </script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @include('components.sweetalert')
</body>
</html>
