<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="@yield('description', 'Informasi legal SPMB PAUD Al-Qur’an Azzahra Depok.')">

    <title>@yield('title', 'Informasi Legal - PAUD Al-Qur’an Azzahra')</title>

    <link rel="icon" href="{{ asset('images/azzahra_logo.png') }}" type="image/png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest" defer></script>
</head>
<body class="min-h-screen bg-[#f7f8fc] font-body text-slate-900 antialiased">
    <a href="{{ url('/') }}"
       class="fixed left-4 top-4 z-50 inline-flex items-center gap-2 rounded-2xl border border-slate-200 bg-white/95 px-4 py-3 text-sm font-bold text-slate-600 shadow-lg shadow-slate-900/[0.07] backdrop-blur-md transition-all hover:-translate-y-0.5 hover:border-indigo-200 hover:text-[#5a5de6] hover:shadow-xl focus:outline-none focus-visible:ring-4 focus-visible:ring-indigo-100 sm:left-6 sm:top-6"
       aria-label="Kembali ke beranda">
        <i data-lucide="arrow-left" class="h-4 w-4"></i>
        <span>Kembali ke Beranda</span>
    </a>

    <main>
        @yield('content')
    </main>
    @include('app.components.footer')

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (window.lucide) {
                window.lucide.createIcons();
            }
        });
    </script>
</body>
</html>
