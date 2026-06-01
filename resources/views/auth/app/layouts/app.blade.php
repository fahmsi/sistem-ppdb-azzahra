<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="PSB PAUD Al Qur'an Az-Zahra - Pendaftaran Siswa Baru. Lembaga pendidikan anak usia dini berbasis Islam yang mengedepankan nilai-nilai Qur'ani dan perkembangan holistik.">
    <meta name="keywords" content="PAUD, Az-Zahra, PSB, Pendaftaran Siswa Baru, Pendidikan Islam, Al-Quran, Depok">

    <title>PSB PAUD Al Qur'an Az-Zahra — Pendaftaran Siswa Baru</title>

    {{-- Google Fonts: Plus Jakarta Sans --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <script src="https://unpkg.com/lucide@latest"></script>

</head>
<body class="bg-gray-50 font-body antialiased">

    @include('app.components.navbar')

    <main>
        @yield('content')
    </main>

    @include('app.components.footer')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            lucide.createIcons();
        });
    </script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @include('components.sweetalert')
</body>
</html>