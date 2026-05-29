<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email - Sistem PPDB PAUD Az-Zahra</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.googleapis.com/css2?family=Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-gray-50 font-body text-gray-800 antialiased h-screen flex items-center justify-center p-4">

    <div class="max-w-md w-full bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden animate-fade-up">
        <div class="p-8 sm:p-10">
            
            <!-- Header -->
            <div class="mb-8 text-center">
                <div class="w-16 h-16 rounded-2xl bg-secondary-50 flex items-center justify-center mx-auto mb-6">
                    <i data-lucide="mail-check" class="w-8 h-8 text-secondary-600"></i>
                </div>
                <h2 class="text-2xl font-heading font-bold text-gray-900 mb-2">Verifikasi Email</h2>
                <p class="text-gray-500 text-sm">Terima kasih telah mendaftar! Sebelum memulai, silakan verifikasi email Anda dengan mengklik tautan yang baru saja kami kirimkan.</p>
            </div>

            <!-- Session Status -->
            @if (session('status') == 'verification-link-sent')
                <div class="mb-6 p-4 rounded-lg bg-green-50 border border-green-200 text-green-600 text-sm flex items-start gap-3">
                    <i data-lucide="check-circle" class="w-5 h-5 flex-shrink-0"></i>
                    <p>Tautan verifikasi baru telah dikirim ke alamat email yang Anda berikan saat pendaftaran.</p>
                </div>
            @endif

            <div class="space-y-4">
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit" class="w-full flex justify-center items-center gap-2 py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                        Kirim Ulang Email Verifikasi
                        <i data-lucide="refresh-cw" class="w-4 h-4"></i>
                    </button>
                </form>

                <div class="flex items-center justify-between gap-4 pt-2">
                    <a href="{{ route('profile.edit') }}" class="text-sm font-medium text-gray-500 hover:text-gray-800 transition-colors">
                        Edit Profil
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-sm font-medium text-red-600 hover:text-red-500 transition-colors flex items-center gap-2">
                            Keluar
                            <i data-lucide="log-out" class="w-4 h-4"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            lucide.createIcons();
        });
    </script>
</body>
</html>
