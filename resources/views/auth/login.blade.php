<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem PPDB PAUD Az-Zahra</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.googleapis.com/css2?family=Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-50 font-body text-gray-800 antialiased h-screen flex">

    <!-- Left Side: Login Form -->
    <div class="w-full lg:w-1/2 flex flex-col justify-center px-8 sm:px-16 lg:px-24 xl:px-32 relative bg-white animate-slide-right">
        
        <!-- Back to Home -->
        <a href="{{ url('/') }}" class="absolute top-8 left-8 flex items-center gap-2 text-sm font-medium text-gray-500 hover:text-primary-600 transition-colors">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            Kembali
        </a>

        <div class="max-w-md w-full mx-auto">
            
            <!-- Header -->
            <div class="mb-10 text-center lg:text-left">
                <!-- <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-primary-500 to-secondary-500 flex items-center justify-center shadow-lg mx-auto lg:mx-0 mb-6">
                    <i data-lucide="book-open" class="w-7 h-7 text-white"></i>
                </div> -->
                <h2 class="text-3xl font-heading font-bold text-gray-900 mb-2">Selamat Datang</h2>
                <p class="text-gray-500">Masuk untuk melanjutkan pendaftaran peserta didik baru.</p>
            </div>

            <!-- Global Error -->
            @if ($errors->any())
                <div class="mb-6 p-4 rounded-lg bg-red-50 border border-red-200 text-red-600 text-sm flex items-start gap-3">
                    <i data-lucide="alert-circle" class="w-5 h-5 flex-shrink-0"></i>
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Form -->
            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <!-- Email Address -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i data-lucide="mail" class="w-5 h-5 text-gray-400"></i>
                        </div>
                        <input id="email" class="block w-full pl-10 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all sm:text-sm" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="Masukkan email Anda" />
                    </div>
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i data-lucide="lock" class="w-5 h-5 text-gray-400"></i>
                        </div>
                        <input id="password" class="block w-full pl-10 pr-10 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all sm:text-sm" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
                        
                        <!-- Toggle Password Visibility (Vanilla JS handled below) -->
                        <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-primary-600 focus:outline-none">
                            <i data-lucide="eye" id="eyeIcon" class="w-5 h-5"></i>
                        </button>
                    </div>
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="flex items-center justify-between mt-4">
                    <label for="remember_me" class="flex items-center cursor-pointer group">
                        <input id="remember_me" type="checkbox" class="w-4 h-4 rounded border-gray-300 text-primary-600 focus:ring-primary-500 transition-colors cursor-pointer" name="remember">
                        <span class="ml-2 text-sm text-gray-600 group-hover:text-gray-900 transition-colors">Ingat saya</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a class="text-sm font-medium text-primary-600 hover:text-primary-500 transition-colors" href="{{ route('password.request') }}">
                            Lupa password?
                        </a>
                    @endif
                </div>

                <!-- Submit Button -->
                <div class="pt-2">
                    <button type="submit" class="w-full flex justify-center items-center gap-2 py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                        Masuk Sekarang
                        <i data-lucide="log-in" class="w-4 h-4"></i>
                    </button>
                </div>
            </form>

            <div class="mt-8 text-center lg:text-left">
                <p class="text-sm text-gray-600">
                    Belum punya akun? 
                    <a href="{{ route('register') }}" class="font-semibold text-primary-600 hover:text-primary-500 transition-colors">Daftar disini</a>
                </p>
            </div>
        </div>
    </div>

    <!-- Right Side: Branded Graphic -->
    <div class="hidden lg:flex lg:w-1/2 relative bg-gradient-to-br from-primary-900 via-primary-800 to-secondary-900 overflow-hidden items-center justify-center animate-fade-in">
        
        <!-- Decorative Elements -->
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden opacity-20">
            <div class="absolute -top-24 -left-24 w-96 h-96 rounded-full bg-primary-500 blur-3xl"></div>
            <div class="absolute bottom-10 right-10 w-80 h-80 rounded-full bg-secondary-400 blur-3xl"></div>
        </div>

        <div class="relative z-10 text-center px-12 text-white max-w-lg">
            <div class="w-24 h-24 bg-white/10 backdrop-blur-md rounded-3xl mx-auto flex items-center justify-center mb-8 border border-white/20 shadow-2xl animate-float">
                <i data-lucide="graduation-cap" class="w-12 h-12 text-white"></i>
            </div>
            
            <h1 class="text-3xl font-heading font-bold mb-6 leading-tight">
                Sistem PPDB<br>
                <span class="text-secondary-300">PAUD Al Qur'an Az-Zahra</span>
            </h1>
            
            <p class="text-lg text-primary-100 leading-relaxed opacity-90">
                Langkah awal untuk pendidikan yang berkualitas, berlandaskan nilai-nilai Islami, dan berorientasi pada masa depan.
            </p>

            <div class="mt-10 flex items-center justify-center gap-4 text-sm text-primary-200">
                <div class="flex items-center gap-2 bg-white/10 px-4 py-2 rounded-full backdrop-blur-sm border border-white/10">
                    <i data-lucide="check-circle-2" class="w-4 h-4 text-secondary-400"></i> Mudah
                </div>
                <div class="flex items-center gap-2 bg-white/10 px-4 py-2 rounded-full backdrop-blur-sm border border-white/10">
                    <i data-lucide="check-circle-2" class="w-4 h-4 text-secondary-400"></i> Cepat
                </div>
                <div class="flex items-center gap-2 bg-white/10 px-4 py-2 rounded-full backdrop-blur-sm border border-white/10">
                    <i data-lucide="check-circle-2" class="w-4 h-4 text-secondary-400"></i> Transparan
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            lucide.createIcons();

            // Toggle Password Visibility
            const togglePassword = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');

            if(togglePassword && passwordInput) {
                togglePassword.addEventListener('click', function() {
                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);
                    
                    // Toggle Icon
                    if(type === 'password') {
                        eyeIcon.setAttribute('data-lucide', 'eye');
                    } else {
                        eyeIcon.setAttribute('data-lucide', 'eye-off');
                    }
                    lucide.createIcons(); // Re-render icon
                });
            }

            // SweetAlert2 Flash Messages
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: @json(session('success')),
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 4000,
                    timerProgressBar: true,
                    showCloseButton: true
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: @json(session('error')),
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 5000,
                    timerProgressBar: true,
                    showCloseButton: true
                });
            @endif
        });
    </script>
</body>
</html>