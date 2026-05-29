<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun Wali Murid - Sistem PPDB Az-Zahra</title>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.googleapis.com/css2?family=Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        .pw-rule { transition: all 0.25s ease; }
        .pw-rule.valid { color: #16a34a; }
        .pw-rule.valid .rule-icon { background-color: #16a34a; }
        .pw-rule.invalid { color: #dc2626; }
        .pw-rule.invalid .rule-icon { background-color: #dc2626; }
        .pw-rule .rule-icon {
            width: 16px; height: 16px; border-radius: 50%;
            display: inline-flex; align-items: center; justify-content: center;
            background-color: #d1d5db; transition: all 0.25s ease;
        }
        .pw-match { transition: all 0.25s ease; }
    </style>
</head>
<body class="bg-gray-50 font-body text-gray-800 antialiased h-screen flex overflow-hidden">

    <div class="w-full lg:w-1/2 flex flex-col justify-center px-8 sm:px-16 lg:px-24 bg-white animate-slide-right h-full">
        
        <!-- Back to Home -->
        <a href="{{ url('/') }}" class="absolute top-8 left-8 flex items-center gap-2 text-sm font-medium text-gray-500 hover:text-primary-600 transition-colors">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            Kembali
        </a>

        <div class="max-w-md w-full mx-auto">

            <div class="mb-6 text-left">
                <h2 class="text-2xl sm:text-3xl font-heading font-bold text-gray-900 mb-1">Buat Akun Baru</h2>
                <p class="text-sm text-gray-500">Daftarkan akun Wali Murid untuk memulai pendaftaran.</p>
            </div>

            <form method="POST" action="{{ route('register') }}" class="space-y-4" id="registerForm">
                @csrf

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap Wali Murid</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i data-lucide="user" class="w-4 h-4 text-gray-400"></i>
                        </div>
                        <input id="name" class="block w-full pl-9 py-2 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all text-sm @error('name') border-red-500 @enderror" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="Nama Lengkap" />
                    </div>
                    @error('name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i data-lucide="mail" class="w-4 h-4 text-gray-400"></i>
                        </div>
                        <input id="email" class="block w-full pl-9 py-2 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all text-sm @error('email') border-red-500 @enderror" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="email@contoh.com" />
                    </div>
                    @error('email') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i data-lucide="lock" class="w-4 h-4 text-gray-400"></i>
                        </div>
                        <input id="password" class="block w-full pl-9 pr-10 py-2 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all text-sm @error('password') border-red-500 @enderror" type="password" name="password" required autocomplete="new-password" placeholder="Minimal 8 karakter" />
                        
                        <button type="button" onclick="toggleVisibility('password', 'eyeIcon1')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-primary-600 focus:outline-none">
                            <i data-lucide="eye" id="eyeIcon1" class="w-4 h-4"></i>
                        </button>
                    </div>

                    <!-- Real-time Password Validation Feedback -->
                    <div id="passwordRules" class="mt-2 space-y-1 text-xs hidden">
                        <div class="pw-rule invalid flex items-center gap-2" id="ruleLength">
                            <span class="rule-icon"><i data-lucide="x" class="w-2.5 h-2.5 text-white rule-x"></i><i data-lucide="check" class="w-2.5 h-2.5 text-white rule-check hidden"></i></span>
                            <span>Minimal 8 karakter</span>
                        </div>
                        <div class="pw-rule invalid flex items-center gap-2" id="ruleUpper">
                            <span class="rule-icon"><i data-lucide="x" class="w-2.5 h-2.5 text-white rule-x"></i><i data-lucide="check" class="w-2.5 h-2.5 text-white rule-check hidden"></i></span>
                            <span>Mengandung huruf besar (A-Z)</span>
                        </div>
                        <div class="pw-rule invalid flex items-center gap-2" id="ruleLower">
                            <span class="rule-icon"><i data-lucide="x" class="w-2.5 h-2.5 text-white rule-x"></i><i data-lucide="check" class="w-2.5 h-2.5 text-white rule-check hidden"></i></span>
                            <span>Mengandung huruf kecil (a-z)</span>
                        </div>
                        <div class="pw-rule invalid flex items-center gap-2" id="ruleNumber">
                            <span class="rule-icon"><i data-lucide="x" class="w-2.5 h-2.5 text-white rule-x"></i><i data-lucide="check" class="w-2.5 h-2.5 text-white rule-check hidden"></i></span>
                            <span>Mengandung angka (0-9)</span>
                        </div>
                    </div>
                    @error('password') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i data-lucide="lock" class="w-4 h-4 text-gray-400"></i>
                        </div>
                        <input id="password_confirmation" class="block w-full pl-9 pr-10 py-2 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all text-sm" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Ulangi password" />
                        
                        <button type="button" onclick="toggleVisibility('password_confirmation', 'eyeIcon2')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-primary-600 focus:outline-none">
                            <i data-lucide="eye" id="eyeIcon2" class="w-4 h-4"></i>
                        </button>
                    </div>

                    <!-- Real-time Confirm Password Match Feedback -->
                    <div id="passwordMatchFeedback" class="mt-1.5 text-xs hidden pw-match"></div>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full flex justify-center items-center gap-2 py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                        Daftar Akun Sekarang
                    </button>
                </div>
            </form>

            <div class="mt-5 text-center lg:text-left">
                <p class="text-sm text-gray-600">
                    Sudah punya akun? 
                    <a href="{{ route('login') }}" class="font-semibold text-primary-600 hover:text-primary-500 transition-colors">Masuk disini</a>
                </p>
            </div>
        </div>
    </div>

    <div class="hidden lg:flex lg:w-1/2 relative bg-gradient-to-br from-primary-900 via-primary-800 to-secondary-900 overflow-hidden items-center justify-center animate-fade-in h-full">
        
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden opacity-20">
            <div class="absolute -top-24 -left-24 w-96 h-96 rounded-full bg-primary-500 blur-3xl"></div>
            <div class="absolute bottom-10 right-10 w-80 h-80 rounded-full bg-secondary-400 blur-3xl"></div>
        </div>

        <div class="relative z-10 text-center px-12 text-white max-w-lg">
            <div class="w-20 h-20 bg-white/10 backdrop-blur-md rounded-3xl mx-auto flex items-center justify-center mb-8 border border-white/20 shadow-2xl animate-float">
                <i data-lucide="shield-check" class="w-10 h-10 text-white"></i>
            </div>
            
            <h1 class="text-4xl font-heading font-bold mb-6 leading-tight">
                Pendaftaran Aman<br>
                <span class="text-secondary-300">& Terpercaya</span>
            </h1>
            
            <p class="text-base text-primary-100 leading-relaxed opacity-90">
                Data Anda dilindungi dengan enkripsi keamanan tinggi. Lengkapi pendaftaran dan pantau status penerimaan putra-putri Anda secara langsung.
            </p>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            lucide.createIcons();

            // =============================================
            // Real-time Password Validation (Vanilla JS)
            // =============================================
            var passwordInput = document.getElementById('password');
            var confirmInput = document.getElementById('password_confirmation');
            var rulesContainer = document.getElementById('passwordRules');
            var matchFeedback = document.getElementById('passwordMatchFeedback');

            var rules = {
                ruleLength: function(pw) { return pw.length >= 8; },
                ruleUpper:  function(pw) { return /[A-Z]/.test(pw); },
                ruleLower:  function(pw) { return /[a-z]/.test(pw); },
                ruleNumber: function(pw) { return /[0-9]/.test(pw); }
            };

            function updateRule(ruleId, isValid) {
                var el = document.getElementById(ruleId);
                if (!el) return;
                var xIcon = el.querySelector('.rule-x');
                var checkIcon = el.querySelector('.rule-check');

                if (isValid) {
                    el.classList.remove('invalid');
                    el.classList.add('valid');
                    if (xIcon) xIcon.classList.add('hidden');
                    if (checkIcon) checkIcon.classList.remove('hidden');
                } else {
                    el.classList.remove('valid');
                    el.classList.add('invalid');
                    if (xIcon) xIcon.classList.remove('hidden');
                    if (checkIcon) checkIcon.classList.add('hidden');
                }
            }

            function validatePassword() {
                var pw = passwordInput.value;

                // Show rules container on first input
                if (pw.length > 0) {
                    rulesContainer.classList.remove('hidden');
                } else {
                    rulesContainer.classList.add('hidden');
                }

                // Check each rule
                for (var ruleId in rules) {
                    updateRule(ruleId, rules[ruleId](pw));
                }

                // Re-validate confirm match
                validateConfirmPassword();
            }

            function validateConfirmPassword() {
                var pw = passwordInput.value;
                var confirm = confirmInput.value;

                if (confirm.length === 0) {
                    matchFeedback.classList.add('hidden');
                    return;
                }

                matchFeedback.classList.remove('hidden');

                if (pw === confirm) {
                    matchFeedback.innerHTML = '<span class="flex items-center gap-1.5 text-green-600 font-medium"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg> Password cocok</span>';
                    confirmInput.classList.remove('border-red-500');
                    confirmInput.classList.add('border-green-500');
                } else {
                    matchFeedback.innerHTML = '<span class="flex items-center gap-1.5 text-red-500 font-medium"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg> Password tidak cocok</span>';
                    confirmInput.classList.remove('border-green-500');
                    confirmInput.classList.add('border-red-500');
                }
            }

            passwordInput.addEventListener('input', validatePassword);
            confirmInput.addEventListener('input', validateConfirmPassword);

            // Re-create icons after dynamic content
            setTimeout(function() { lucide.createIcons(); }, 100);

            // =============================================
            // SweetAlert2 — Flash Messages (if any)
            // =============================================
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

        // Toggle Password Visibility
        function toggleVisibility(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            
            if(input.type === 'password') {
                input.type = 'text';
                icon.setAttribute('data-lucide', 'eye-off');
            } else {
                input.type = 'password';
                icon.setAttribute('data-lucide', 'eye');
            }
            lucide.createIcons();
        }
    </script>
</body>
</html>