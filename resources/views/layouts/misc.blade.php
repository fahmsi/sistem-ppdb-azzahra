<!DOCTYPE html>
<html lang="id" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="@yield('meta_description', 'Portal SPMB PAUD Al Qur\'an Az-Zahra Depok')">
    <title>@yield('title', 'Informasi | SPMB PAUD Al Qur\'an Az-Zahra')</title>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('images/azzahra_logo.png') }}" type="image/png">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest" defer></script>

    <script>
        (function () {
            const saved = localStorage.getItem('spmb_theme');
            if (saved === 'dark' || (!saved && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
                document.documentElement.classList.remove('light');
            } else {
                document.documentElement.classList.add('light');
                document.documentElement.classList.remove('dark');
            }
        })();
    </script>
</head>
<body class="min-h-screen bg-slate-50 font-body text-slate-800 antialiased dark:bg-[#1e1f2e] dark:text-slate-100 selection:bg-[#696cff] selection:text-white flex flex-col justify-between">

    <!-- Decorative background elements -->
    <div class="pointer-events-none fixed inset-0 overflow-hidden z-0" aria-hidden="true">
        <div class="absolute -left-32 -top-32 h-96 w-96 rounded-full bg-[#696cff]/10 blur-3xl dark:bg-[#696cff]/20"></div>
        <div class="absolute -right-32 top-1/3 h-96 w-96 rounded-full bg-[#10b981]/10 blur-3xl dark:bg-[#10b981]/15"></div>
        <div class="absolute bottom-0 left-1/4 h-80 w-80 rounded-full bg-indigo-500/10 blur-3xl dark:bg-indigo-500/15"></div>
        <div class="absolute inset-0 opacity-[0.03] dark:opacity-[0.05]" style="background-image: radial-gradient(circle at 1px 1px, currentColor 1px, transparent 0); background-size: 32px 32px;"></div>
    </div>

    <!-- Header Navigation -->
    <header class="relative z-10 w-full border-b border-slate-200/80 bg-white/80 backdrop-blur-md dark:border-slate-800/80 dark:bg-[#252636]/80">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3.5 sm:px-6 lg:px-8">
            <!-- Brand Logo & Name -->
            <a href="{{ route('home') }}" class="group flex items-center gap-3 transition-opacity hover:opacity-90 focus:outline-none focus-visible:ring-2 focus-visible:ring-[#696cff] rounded-lg p-1">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-50 p-1.5 shadow-sm dark:bg-indigo-950/50">
                    <img src="{{ asset('images/azzahra_logo.png') }}" alt="Logo PAUD Az-Zahra" class="h-full w-full object-contain">
                </div>
                <div class="flex flex-col">
                    <span class="text-xs font-bold uppercase tracking-wider text-[#696cff] dark:text-indigo-400">SPMB Azzahra</span>
                    <span class="text-sm font-extrabold text-slate-800 dark:text-white">PAUD Al Qur'an Az-Zahra</span>
                </div>
            </a>

            <!-- Direct Navigation Links -->
            <nav class="flex items-center gap-2 sm:gap-3">
                <a href="{{ route('home') }}"
                   class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-xs sm:text-sm font-semibold text-slate-700 shadow-sm transition-all duration-200 ease-in-out hover:bg-slate-50 hover:border-slate-300 hover:text-[#696cff] dark:border-slate-700 dark:bg-[#2b2c40] dark:text-slate-200 dark:hover:bg-[#32344d] dark:hover:text-indigo-400 focus:outline-none focus-visible:ring-2 focus-visible:ring-[#696cff] active:scale-[0.98]">
                    <i data-lucide="home" class="h-4 w-4 text-[#696cff] dark:text-indigo-400"></i>
                    <span>Beranda</span>
                </a>
                <a href="{{ route('login') }}"
                   class="inline-flex items-center gap-2 rounded-xl bg-[#696cff] px-4 py-2 text-xs sm:text-sm font-semibold text-white shadow-md shadow-[#696cff]/20 transition-all duration-200 ease-in-out hover:bg-[#5b5ebd] hover:shadow-lg hover:shadow-[#696cff]/30 focus:outline-none focus-visible:ring-2 focus-visible:ring-[#696cff] focus-visible:ring-offset-2 active:scale-[0.98]">
                    <i data-lucide="log-in" class="h-4 w-4"></i>
                    <span>Login</span>
                </a>
            </nav>
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="relative z-10 my-auto py-12 px-4 sm:px-6 lg:px-8">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="relative z-10 w-full border-t border-slate-200/80 bg-white/60 py-4 text-center text-xs text-slate-500 backdrop-blur-md dark:border-slate-800/80 dark:bg-[#252636]/60 dark:text-slate-400">
        <div class="mx-auto max-w-7xl px-4 flex flex-col sm:flex-row items-center justify-between gap-2">
            <p>&copy; {{ date('Y') }} PAUD Al Qur'an Az-Zahra Depok. All rights reserved.</p>
            <div class="flex items-center gap-4">
                <a href="{{ route('terms') }}" class="hover:text-[#696cff] transition-colors">Ketentuan Layanan</a>
                <span>•</span>
                <a href="{{ route('privacy') }}" class="hover:text-[#696cff] transition-colors">Kebijakan Privasi</a>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (window.lucide) {
                lucide.createIcons();
            }
        });
    </script>
</body>
</html>
