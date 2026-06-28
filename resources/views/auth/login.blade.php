<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Masuk ke Portal SPMB PAUD Al Qur'an Az-Zahra Depok untuk melanjutkan proses pendaftaran peserta didik baru.">
    <title>Masuk | SPMB PAUD Al Qur'an Az-Zahra</title>

    <link rel="icon" href="{{ asset('images/azzahra_logo.png') }}" type="image/png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11" defer></script>

    <style>
        @media (min-width: 1024px) and (max-height: 850px) {
            .brand-panel { padding: 2rem 3rem; }
            .brand-copy { margin-top: 2rem; }
            .brand-heading { font-size: 2.5rem; }
            .brand-art { padding: 0.75rem; border-radius: 1.5rem; }
            .brand-illustration { height: 15rem; }
            .brand-trust { margin-top: 0.75rem; }
            .auth-panel { padding-top: 1.5rem; padding-bottom: 1.25rem; }
            .auth-content { padding-top: 1.5rem; padding-bottom: 1.5rem; }
            .auth-intro { margin-bottom: 1.5rem; }
            .auth-title { font-size: 2rem; }
            .login-form { gap: 1rem; }
            .auth-input, .auth-submit { height: 3.25rem; }
            .signup-card { margin-top: 1.25rem; padding: 0.75rem; }
        }
    </style>
</head>
<body class="min-h-screen bg-[#f7f8fc] font-body text-slate-900 antialiased">
    <main class="min-h-screen lg:grid lg:grid-cols-[minmax(430px,0.92fr)_minmax(560px,1.08fr)]">
        {{-- Brand panel --}}
        <section class="brand-panel relative hidden min-h-screen overflow-hidden bg-[#3538a6] p-8 text-white lg:flex lg:flex-col xl:p-12" aria-label="Tentang SPMB Az-Zahra">
            <div class="pointer-events-none absolute inset-0" aria-hidden="true">
                <div class="absolute -left-28 -top-32 h-80 w-80 rounded-full border-[70px] border-white/[0.04]"></div>
                <div class="absolute -right-20 top-[30%] h-64 w-64 rounded-full bg-[#6ee7b7]/10 blur-3xl"></div>
                <div class="absolute bottom-0 left-0 right-0 h-1/2 bg-gradient-to-t from-[#25277f]/70 to-transparent"></div>
                <div class="absolute inset-0 opacity-[0.07]" style="background-image: radial-gradient(circle at 1px 1px, white 1px, transparent 0); background-size: 28px 28px;"></div>
            </div>

            <a href="{{ url('/') }}" class="relative z-10 inline-flex w-fit items-center gap-3 rounded-2xl focus:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-offset-2 focus-visible:ring-offset-[#3538a6]">
                <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white shadow-lg shadow-slate-950/10">
                    <img src="{{ asset('images/azzahra_logo.png') }}" alt="Logo PAUD Al Qur'an Az-Zahra" class="h-9 w-9 object-contain">
                </span>
                <span class="leading-tight">
                    <span class="block text-[10px] font-bold uppercase tracking-[0.22em] text-indigo-200">Portal SPMB</span>
                    <span class="mt-1 block text-sm font-extrabold tracking-tight">PAUD Al Qur'an Az-Zahra</span>
                </span>
            </a>

            <div class="brand-copy relative z-10 mt-10 max-w-xl xl:mt-14">
                <span class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-3.5 py-2 text-xs font-bold text-indigo-50 backdrop-blur-sm">
                    <span class="relative flex h-2 w-2">
                        <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-300 opacity-75"></span>
                        <span class="relative inline-flex h-2 w-2 rounded-full bg-emerald-300"></span>
                    </span>
                    Pendaftaran peserta didik baru
                </span>
                <h1 class="brand-heading mt-6 max-w-lg text-4xl font-extrabold leading-[1.15] tracking-[-0.04em] text-white xl:text-5xl">
                    Awal baik untuk perjalanan belajar si kecil.
                </h1>
                <p class="mt-5 max-w-lg text-sm leading-7 text-indigo-100 xl:text-base">
                    Kelola pendaftaran, lengkapi dokumen, dan pantau setiap tahap penerimaan dalam satu portal yang mudah digunakan.
                </p>
            </div>

            <div class="relative z-10 mt-auto pt-8">
                <div class="brand-art relative mx-auto max-w-xl overflow-hidden rounded-[2rem] border border-white/15 bg-white p-4 shadow-2xl shadow-indigo-950/30 xl:p-5">
                    <div class="absolute left-5 top-5 z-10 rounded-full bg-[#eef2ff] px-3 py-1.5 text-[10px] font-extrabold uppercase tracking-[0.16em] text-[#3538a6]">
                        Tumbuh · Belajar · Berakhlak
                    </div>
                    <img src="{{ asset('images/about-3.png') }}"
                         alt="Ilustrasi keluarga mendampingi anak belajar"
                         class="brand-illustration mx-auto h-[280px] w-full object-contain object-bottom xl:h-[320px]"
                         loading="eager">
                </div>

                <div class="brand-trust mt-5 flex items-center justify-center gap-6 text-xs font-semibold text-indigo-100 xl:justify-start">
                    <span class="inline-flex items-center gap-2"><i data-lucide="shield-check" class="h-4 w-4 text-emerald-300"></i> Data terlindungi</span>
                    <span class="inline-flex items-center gap-2"><i data-lucide="circle-check" class="h-4 w-4 text-emerald-300"></i> Proses transparan</span>
                </div>
            </div>
        </section>

        {{-- Authentication panel --}}
        <section class="auth-panel relative flex min-h-screen flex-col bg-[#f7f8fc] px-5 py-6 sm:px-10 sm:py-8 lg:px-12 xl:px-20">
            <div class="pointer-events-none absolute right-0 top-0 h-64 w-64 overflow-hidden" aria-hidden="true">
                <div class="absolute -right-24 -top-28 h-64 w-64 rounded-full bg-indigo-100/70 blur-3xl"></div>
            </div>

            <header class="relative z-10 flex items-center justify-between">
                <a href="{{ url('/') }}"
                    class="inline-flex items-center gap-2 rounded-xl px-2 py-2 text-sm font-semibold text-slate-500 transition-colors hover:bg-white hover:text-[#4b4ecc] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#696cff]">
                    <i data-lucide="arrow-left" class="h-4 w-4"></i>
                    <span>Kembali ke beranda</span>
                </a>

                <div class="flex items-center gap-2 lg:hidden">
                    <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-white shadow-sm ring-1 ring-slate-200">
                        <img src="{{ asset('images/azzahra_logo.png') }}" alt="" class="h-7 w-7 object-contain">
                    </span>
                    <span class="hidden text-xs font-extrabold text-slate-700 sm:block">Az-Zahra Depok</span>
                </div>
            </header>

            <div class="auth-content relative z-10 my-auto w-full py-10 sm:py-12">
                <div class="mx-auto w-full max-w-[460px]">
                    <div class="auth-intro mb-8">
                        <div class="mb-5 inline-flex items-center gap-2 rounded-full border border-indigo-100 bg-white px-3 py-1.5 text-[11px] font-extrabold uppercase tracking-[0.16em] text-[#5a5de6] shadow-sm lg:hidden">
                            <i data-lucide="graduation-cap" class="h-3.5 w-3.5"></i>
                            Portal SPMB
                        </div>
                        <p class="text-sm font-bold text-[#5a5de6]">Selamat datang kembali</p>
                        <h2 class="auth-title mt-2 text-3xl font-extrabold tracking-[-0.035em] text-slate-950 sm:text-4xl">Masuk ke akun Anda</h2>
                        <p class="mt-3 max-w-md text-sm leading-6 text-slate-500">
                            Gunakan akun orang tua atau admin untuk melanjutkan ke dashboard.
                        </p>
                    </div>

                    @if ($errors->any())
                        <div class="mb-6 flex items-start gap-3 rounded-2xl border border-red-200 bg-red-50 px-4 py-3.5 text-sm text-red-700" role="alert">
                            <span class="mt-0.5 flex h-8 w-8 flex-none items-center justify-center rounded-xl bg-red-100">
                                <i data-lucide="circle-alert" class="h-4 w-4"></i>
                            </span>
                            <div>
                                <p class="font-bold">Login belum berhasil</p>
                                <ul class="mt-1 space-y-0.5 text-xs leading-5 text-red-600">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    <form id="loginForm" method="POST" action="{{ route('login') }}" class="login-form space-y-5">
                        @csrf

                        <div>
                            <label for="email" class="mb-2 block text-sm font-bold text-slate-700">Alamat email</label>
                            <div class="group relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 transition-colors group-focus-within:text-[#696cff]">
                                    <i data-lucide="mail" class="h-5 w-5"></i>
                                </span>
                                <input id="email"
                                       type="email"
                                       name="email"
                                       value="{{ old('email') }}"
                                       required
                                       autofocus
                                       autocomplete="username"
                                       inputmode="email"
                                       @class([
                                           'auth-input block h-14 w-full rounded-2xl border bg-white pl-12 pr-4 text-sm font-medium text-slate-900 outline-none transition placeholder:text-slate-400 hover:border-slate-300 focus:ring-4',
                                           'border-red-300 focus:border-red-400 focus:ring-red-100' => $errors->has('email'),
                                           'border-slate-200 focus:border-[#696cff] focus:ring-indigo-100' => ! $errors->has('email'),
                                       ])
                                       @if($errors->has('email')) aria-invalid="true" @endif
                                       placeholder="nama@email.com">
                            </div>
                        </div>

                        <div>
                            <div class="mb-2 flex items-center justify-between gap-4">
                                <label for="password" class="block text-sm font-bold text-slate-700">Kata sandi</label>
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="text-xs font-bold text-[#5a5de6] transition-colors hover:text-[#3d40b3] hover:underline">
                                        Lupa kata sandi?
                                    </a>
                                @endif
                            </div>
                            <div class="group relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 transition-colors group-focus-within:text-[#696cff]">
                                    <i data-lucide="lock-keyhole" class="h-5 w-5"></i>
                                </span>
                                <input id="password"
                                       type="password"
                                       name="password"
                                       required
                                       autocomplete="current-password"
                                       @class([
                                           'auth-input block h-14 w-full rounded-2xl border bg-white pl-12 pr-14 text-sm font-medium text-slate-900 outline-none transition placeholder:text-slate-400 hover:border-slate-300 focus:ring-4',
                                           'border-red-300 focus:border-red-400 focus:ring-red-100' => $errors->has('password'),
                                           'border-slate-200 focus:border-[#696cff] focus:ring-indigo-100' => ! $errors->has('password'),
                                       ])
                                       @if($errors->has('password')) aria-invalid="true" @endif
                                       placeholder="Masukkan kata sandi">
                                <button type="button"
                                        id="togglePassword"
                                        class="absolute inset-y-0 right-0 flex w-14 items-center justify-center rounded-r-2xl text-slate-400 transition-colors hover:text-[#5a5de6] focus:outline-none focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-[#696cff]"
                                        aria-label="Tampilkan kata sandi"
                                        aria-pressed="false">
                                    <i data-lucide="eye" id="eyeIcon" class="h-5 w-5"></i>
                                </button>
                            </div>
                        </div>

                        <label for="remember_me" class="flex w-fit cursor-pointer items-center gap-2.5 text-sm font-medium text-slate-600">
                            <input id="remember_me"
                                   type="checkbox"
                                   name="remember"
                                   @checked(old('remember'))
                                   class="h-[18px] w-[18px] rounded border-slate-300 text-[#696cff] focus:ring-[#696cff]">
                            Biarkan saya tetap masuk
                        </label>

                        <button id="submitButton"
                                type="submit"
                                class="auth-submit group flex h-14 w-full items-center justify-center gap-2.5 rounded-2xl bg-[#5a5de6] px-5 text-sm font-extrabold text-white shadow-lg shadow-indigo-600/20 transition-all hover:-translate-y-0.5 hover:bg-[#4b4ecc] hover:shadow-xl hover:shadow-indigo-600/25 focus:outline-none focus-visible:ring-4 focus-visible:ring-indigo-200 disabled:cursor-wait disabled:opacity-70 disabled:hover:translate-y-0">
                            <span id="submitLabel">Masuk ke dashboard</span>
                            <i data-lucide="arrow-right" id="submitIcon" class="h-[18px] w-[18px] transition-transform group-hover:translate-x-0.5"></i>
                            <span id="submitSpinner" class="hidden h-4 w-4 animate-spin rounded-full border-2 border-white/40 border-t-white" aria-hidden="true"></span>
                        </button>
                    </form>

                    <div class="signup-card mt-7 flex items-center justify-between gap-4 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                        <a href="{{ route('register') }}" class="flex min-w-0 items-center gap-3">
                            <span class="flex h-10 w-10 flex-none items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                                <i data-lucide="user-round-plus" class="h-5 w-5"></i>
                            </span>
                            <div class="min-w-0">
                                <p class="text-xs text-slate-500">Belum memiliki akun?</p>
                                <p class="truncate text-sm font-bold text-slate-800">Daftar sebagai orang tua</p>
                            </div>
                        
                            <a href="{{ route('register') }}"
                                class="inline-flex flex-none items-center gap-1 rounded-xl bg-indigo-50 px-3 py-2 text-xs font-extrabold text-[#5a5de6] transition-colors hover:bg-indigo-100 focus:outline-none focus-visible:ring-2 focus-visible:ring-[#696cff]">
                                Daftar
                                <i data-lucide="arrow-up-right" class="h-3.5 w-3.5"></i>
                            </a>
                        </a>
                    </div>
                </div>
            </div>

            <footer class="relative z-10 flex flex-wrap items-center justify-center gap-x-3 gap-y-1 text-center text-[12px] text-slate-400 sm:justify-between">
                <p>&copy; {{ date('Y') }} PAUD Al Qur'an Az-Zahra</p>
                <nav class="flex items-center gap-2" aria-label="Tautan legal">
                    <a href="{{ route('terms') }}" class="transition-colors hover:text-[#5a5de6]">Syarat & Ketentuan</a>
                    <span aria-hidden="true">&bull;</span>
                    <a href="{{ route('privacy') }}" class="transition-colors hover:text-[#5a5de6]">Kebijakan Privasi</a>
                </nav>
            </footer>
        </section>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (window.lucide) {
                window.lucide.createIcons();
            }

            const togglePassword = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');

            if (togglePassword && passwordInput) {
                togglePassword.addEventListener('click', function () {
                    const isVisible = passwordInput.type === 'text';

                    passwordInput.type = isVisible ? 'password' : 'text';
                    togglePassword.setAttribute('aria-pressed', String(!isVisible));
                    togglePassword.setAttribute('aria-label', isVisible ? 'Tampilkan kata sandi' : 'Sembunyikan kata sandi');
                    togglePassword.innerHTML = `<i data-lucide="${isVisible ? 'eye' : 'eye-off'}" class="h-5 w-5"></i>`;

                    if (window.lucide) {
                        window.lucide.createIcons();
                    }
                });
            }

            const loginForm = document.getElementById('loginForm');
            const submitButton = document.getElementById('submitButton');
            const submitLabel = document.getElementById('submitLabel');
            const submitIcon = document.getElementById('submitIcon');
            const submitSpinner = document.getElementById('submitSpinner');

            if (loginForm && submitButton && submitLabel) {
                loginForm.addEventListener('submit', function () {
                    if (!loginForm.checkValidity()) {
                        return;
                    }

                    submitButton.disabled = true;
                    submitLabel.textContent = 'Memproses...';
                    submitIcon?.classList.add('hidden');
                    submitSpinner?.classList.remove('hidden');
                });
            }

            @if(session('success'))
                if (window.Swal) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: @json(session('success')),
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 4000,
                        timerProgressBar: true,
                        showCloseButton: true
                    });
                }
            @endif

            @if(session('error'))
                if (window.Swal) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: @json(session('error')),
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 5000,
                        timerProgressBar: true,
                        showCloseButton: true
                    });
                }
            @endif
        });
    </script>
</body>
</html>
