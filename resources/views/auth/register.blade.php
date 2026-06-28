<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Buat akun orang tua di Portal SPMB PAUD Al Qur'an Az-Zahra Depok untuk memulai pendaftaran peserta didik baru.">
    <title>Daftar Akun | SPMB PAUD Al Qur'an Az-Zahra</title>

    <link rel="icon" href="{{ asset('images/azzahra_logo.png') }}" type="image/png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11" defer></script>

    <style>
        .password-rule { color: #64748b; transition: color 180ms ease; }
        .password-rule .rule-icon { background: #e2e8f0; color: #64748b; transition: all 180ms ease; }
        .password-rule.valid { color: #047857; }
        .password-rule.valid .rule-icon { background: #d1fae5; color: #059669; }
        .password-rule.invalid { color: #64748b; }
        .confirm-valid { border-color: #34d399 !important; }
        .confirm-invalid { border-color: #f87171 !important; }

        @media (min-width: 1024px) and (max-height: 850px) {
            .brand-panel { padding: 2rem 3rem; }
            .brand-copy { margin-top: 2rem; }
            .brand-heading { font-size: 2.5rem; }
            .brand-art { padding: 0.75rem; border-radius: 1.5rem; }
            .brand-illustration { height: 15rem; }
            .brand-trust { margin-top: 0.75rem; }
        }
    </style>
</head>
<body class="min-h-screen bg-[#f7f8fc] font-body text-slate-900 antialiased">
    <main class="min-h-screen lg:grid lg:grid-cols-[minmax(430px,0.92fr)_minmax(560px,1.08fr)]">
        {{-- Brand panel --}}
        <section class="brand-panel relative hidden h-screen overflow-hidden bg-[#3538a6] p-8 text-white lg:sticky lg:top-0 lg:flex lg:flex-col xl:p-12" aria-label="Tentang pendaftaran Az-Zahra">
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
                    <i data-lucide="sparkles" class="h-3.5 w-3.5 text-emerald-300"></i>
                    Langkah awal pendaftaran
                </span>
                <h1 class="brand-heading mt-6 max-w-lg text-4xl font-extrabold leading-[1.15] tracking-[-0.04em] text-white xl:text-5xl">
                    Satu akun untuk seluruh proses pendaftaran.
                </h1>
                <p class="mt-5 max-w-lg text-sm leading-7 text-indigo-100 xl:text-base">
                    Buat akun orang tua, lengkapi data calon siswa, lalu pantau status penerimaan dengan lebih mudah.
                </p>
            </div>

            <div class="relative z-10 mt-auto pt-8">
                <div class="brand-art relative mx-auto max-w-xl overflow-hidden rounded-[2rem] border border-white/15 bg-white p-4 shadow-2xl shadow-indigo-950/30 xl:p-5">
                    <div class="absolute left-5 top-5 z-10 rounded-full bg-[#eef2ff] px-3 py-1.5 text-[10px] font-extrabold uppercase tracking-[0.16em] text-[#3538a6]">
                        Mudah · Aman · Terarah
                    </div>
                    <img src="{{ asset('images/about-3.png') }}"
                         alt="Ilustrasi keluarga mendampingi anak belajar"
                         class="brand-illustration mx-auto h-[280px] w-full object-contain object-bottom xl:h-[320px]"
                         loading="eager">
                </div>

                <div class="brand-trust mt-5 flex items-center justify-center gap-6 text-xs font-semibold text-indigo-100 xl:justify-start">
                    <span class="inline-flex items-center gap-2"><i data-lucide="shield-check" class="h-4 w-4 text-emerald-300"></i> Data terlindungi</span>
                    <span class="inline-flex items-center gap-2"><i data-lucide="clock-3" class="h-4 w-4 text-emerald-300"></i> Daftar kapan saja</span>
                </div>
            </div>
        </section>

        {{-- Registration panel --}}
        <section class="relative min-h-screen bg-[#f7f8fc] px-5 py-6 sm:px-10 sm:py-8 lg:px-12 xl:px-20">
            <div class="pointer-events-none absolute right-0 top-0 h-64 w-64 overflow-hidden" aria-hidden="true">
                <div class="absolute -right-24 -top-28 h-64 w-64 rounded-full bg-indigo-100/70 blur-3xl"></div>
            </div>

            <header class="relative z-20 flex items-center justify-between">
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

            <div class="relative z-10 mx-auto w-full max-w-[520px] pb-10 pt-12 sm:pb-12 sm:pt-14 lg:pt-10">
                <div class="mb-8">
                    <div class="mb-5 inline-flex items-center gap-2 rounded-full border border-indigo-100 bg-white px-3 py-1.5 text-[11px] font-extrabold uppercase tracking-[0.16em] text-[#5a5de6] shadow-sm lg:hidden">
                        <i data-lucide="graduation-cap" class="h-3.5 w-3.5"></i>
                        Portal SPMB
                    </div>
                    <p class="text-sm font-bold text-[#5a5de6]">Mulai pendaftaran</p>
                    <h2 class="mt-2 text-3xl font-extrabold tracking-[-0.035em] text-slate-950 sm:text-4xl">Buat akun orang tua</h2>
                    <p class="mt-3 max-w-lg text-sm leading-6 text-slate-500">
                        Isi data akun di bawah ini. Setelah terdaftar, Anda dapat melengkapi data calon siswa dari dashboard.
                    </p>
                </div>

                @if ($errors->any())
                    <div class="mb-6 flex items-start gap-3 rounded-2xl border border-red-200 bg-red-50 px-4 py-3.5 text-sm text-red-700" role="alert">
                        <span class="mt-0.5 flex h-8 w-8 flex-none items-center justify-center rounded-xl bg-red-100">
                            <i data-lucide="circle-alert" class="h-4 w-4"></i>
                        </span>
                        <div>
                            <p class="font-bold">Beberapa data belum sesuai</p>
                            <p class="mt-1 text-xs leading-5 text-red-600">Periksa kembali kolom yang ditandai sebelum melanjutkan.</p>
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}" class="space-y-5" id="registerForm">
                    @csrf

                    <div>
                        <label for="name" class="mb-2 block text-sm font-bold text-slate-700">Nama lengkap orang tua</label>
                        <div class="group relative">
                            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 transition-colors group-focus-within:text-[#696cff]">
                                <i data-lucide="user-round" class="h-5 w-5"></i>
                            </span>
                            <input id="name"
                                   type="text"
                                   name="name"
                                   value="{{ old('name') }}"
                                   required
                                   autofocus
                                   autocomplete="name"
                                   @class([
                                       'block h-14 w-full rounded-2xl border bg-white pl-12 pr-4 text-sm font-medium text-slate-900 outline-none transition placeholder:text-slate-400 hover:border-slate-300 focus:ring-4',
                                       'border-red-300 focus:border-red-400 focus:ring-red-100' => $errors->has('name'),
                                       'border-slate-200 focus:border-[#696cff] focus:ring-indigo-100' => ! $errors->has('name'),
                                   ])
                                   @if($errors->has('name')) aria-invalid="true" aria-describedby="name-error" @endif
                                   placeholder="Nama lengkap sesuai identitas">
                        </div>
                        @error('name')
                            <p id="name-error" class="mt-1.5 flex items-center gap-1.5 text-xs font-medium text-red-600">
                                <i data-lucide="alert-circle" class="h-3.5 w-3.5"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

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
                                   autocomplete="username"
                                   inputmode="email"
                                   @class([
                                       'block h-14 w-full rounded-2xl border bg-white pl-12 pr-4 text-sm font-medium text-slate-900 outline-none transition placeholder:text-slate-400 hover:border-slate-300 focus:ring-4',
                                       'border-red-300 focus:border-red-400 focus:ring-red-100' => $errors->has('email'),
                                       'border-slate-200 focus:border-[#696cff] focus:ring-indigo-100' => ! $errors->has('email'),
                                   ])
                                   @if($errors->has('email')) aria-invalid="true" aria-describedby="email-error" @endif
                                   placeholder="nama@email.com">
                        </div>
                        @error('email')
                            <p id="email-error" class="mt-1.5 flex items-center gap-1.5 text-xs font-medium text-red-600">
                                <i data-lucide="alert-circle" class="h-3.5 w-3.5"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <div class="mb-2 flex items-center justify-between gap-4">
                            <label for="password" class="block text-sm font-bold text-slate-700">Kata sandi</label>
                            <span class="text-[11px] font-medium text-slate-400">Minimal 8 karakter</span>
                        </div>
                        <div class="group relative">
                            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 transition-colors group-focus-within:text-[#696cff]">
                                <i data-lucide="lock-keyhole" class="h-5 w-5"></i>
                            </span>
                            <input id="password"
                                   type="password"
                                   name="password"
                                   required
                                   minlength="8"
                                   autocomplete="new-password"
                                   @class([
                                       'block h-14 w-full rounded-2xl border bg-white pl-12 pr-14 text-sm font-medium text-slate-900 outline-none transition placeholder:text-slate-400 hover:border-slate-300 focus:ring-4',
                                       'border-red-300 focus:border-red-400 focus:ring-red-100' => $errors->has('password'),
                                       'border-slate-200 focus:border-[#696cff] focus:ring-indigo-100' => ! $errors->has('password'),
                                   ])
                                   @if($errors->has('password')) aria-invalid="true" aria-describedby="password-error" @endif
                                   placeholder="Buat kata sandi yang kuat">
                            <button type="button"
                                    class="password-toggle absolute inset-y-0 right-0 flex w-14 items-center justify-center rounded-r-2xl text-slate-400 transition-colors hover:text-[#5a5de6] focus:outline-none focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-[#696cff]"
                                    data-password-toggle="password"
                                    aria-label="Tampilkan kata sandi"
                                    aria-pressed="false">
                                <i data-lucide="eye" class="h-5 w-5"></i>
                            </button>
                        </div>

                        <div id="passwordRules" class="mt-3 hidden rounded-2xl border border-slate-200 bg-white p-3">
                            <p class="mb-2 text-[11px] font-bold uppercase tracking-[0.12em] text-slate-400">Kata sandi harus memiliki</p>
                            <div class="grid grid-cols-1 gap-2 sm:grid-cols-2">
                                <div class="password-rule invalid flex items-center gap-2 text-xs" id="ruleLength">
                                    <span class="rule-icon flex h-5 w-5 flex-none items-center justify-center rounded-full"><span class="rule-symbol text-[10px] font-extrabold" aria-hidden="true">&bull;</span></span>
                                    <span>Minimal 8 karakter</span>
                                </div>
                                <div class="password-rule invalid flex items-center gap-2 text-xs" id="ruleUpper">
                                    <span class="rule-icon flex h-5 w-5 flex-none items-center justify-center rounded-full"><span class="rule-symbol text-[10px] font-extrabold" aria-hidden="true">&bull;</span></span>
                                    <span>Satu huruf besar</span>
                                </div>
                                <div class="password-rule invalid flex items-center gap-2 text-xs" id="ruleLower">
                                    <span class="rule-icon flex h-5 w-5 flex-none items-center justify-center rounded-full"><span class="rule-symbol text-[10px] font-extrabold" aria-hidden="true">&bull;</span></span>
                                    <span>Satu huruf kecil</span>
                                </div>
                                <div class="password-rule invalid flex items-center gap-2 text-xs" id="ruleNumber">
                                    <span class="rule-icon flex h-5 w-5 flex-none items-center justify-center rounded-full"><span class="rule-symbol text-[10px] font-extrabold" aria-hidden="true">&bull;</span></span>
                                    <span>Satu angka</span>
                                </div>
                            </div>
                        </div>

                        @error('password')
                            <p id="password-error" class="mt-1.5 flex items-center gap-1.5 text-xs font-medium text-red-600">
                                <i data-lucide="alert-circle" class="h-3.5 w-3.5"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="mb-2 block text-sm font-bold text-slate-700">Konfirmasi kata sandi</label>
                        <div class="group relative">
                            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 transition-colors group-focus-within:text-[#696cff]">
                                <i data-lucide="shield-check" class="h-5 w-5"></i>
                            </span>
                            <input id="password_confirmation"
                                   type="password"
                                   name="password_confirmation"
                                   required
                                   minlength="8"
                                   autocomplete="new-password"
                                   class="block h-14 w-full rounded-2xl border border-slate-200 bg-white pl-12 pr-14 text-sm font-medium text-slate-900 outline-none transition placeholder:text-slate-400 hover:border-slate-300 focus:border-[#696cff] focus:ring-4 focus:ring-indigo-100"
                                   placeholder="Ulangi kata sandi">
                            <button type="button"
                                    class="password-toggle absolute inset-y-0 right-0 flex w-14 items-center justify-center rounded-r-2xl text-slate-400 transition-colors hover:text-[#5a5de6] focus:outline-none focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-[#696cff]"
                                    data-password-toggle="password_confirmation"
                                    aria-label="Tampilkan konfirmasi kata sandi"
                                    aria-pressed="false">
                                <i data-lucide="eye" class="h-5 w-5"></i>
                            </button>
                        </div>
                        <p id="passwordMatchFeedback" class="mt-1.5 hidden text-xs font-semibold" aria-live="polite"></p>
                    </div>

                    <div @class([
                        'rounded-2xl border bg-white p-4 shadow-sm',
                        'border-red-300 ring-4 ring-red-50' => $errors->has('terms_accepted'),
                        'border-slate-200' => ! $errors->has('terms_accepted'),
                    ])>
                        <label for="terms_accepted" class="flex cursor-pointer items-start gap-3">
                            <input id="terms_accepted"
                                   type="checkbox"
                                   name="terms_accepted"
                                   value="1"
                                   required
                                   @checked(old('terms_accepted'))
                                   class="mt-0.5 h-[18px] w-[18px] flex-none rounded border-slate-300 text-[#696cff] focus:ring-[#696cff]">
                            <span class="text-xs leading-5 text-slate-600">
                                Saya setuju dengan
                                <a href="{{ route('terms') }}" target="_blank" rel="noopener noreferrer" class="font-bold text-[#5a5de6] hover:underline">Syarat & Ketentuan</a>
                                dan
                                <a href="{{ route('privacy') }}" target="_blank" rel="noopener noreferrer" class="font-bold text-[#5a5de6] hover:underline">Kebijakan Privasi</a>
                                SPMB PAUD Az-Zahra.
                            </span>
                        </label>
                        @error('terms_accepted')
                            <p class="mt-2 flex items-start gap-1.5 text-xs font-medium leading-5 text-red-600">
                                <i data-lucide="alert-circle" class="mt-0.5 h-3.5 w-3.5 flex-none"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    <button id="submitButton"
                            type="submit"
                            class="group flex h-14 w-full items-center justify-center gap-2.5 rounded-2xl bg-[#5a5de6] px-5 text-sm font-extrabold text-white shadow-lg shadow-indigo-600/20 transition-all hover:-translate-y-0.5 hover:bg-[#4b4ecc] hover:shadow-xl hover:shadow-indigo-600/25 focus:outline-none focus-visible:ring-4 focus-visible:ring-indigo-200 disabled:cursor-wait disabled:opacity-70 disabled:hover:translate-y-0">
                        <span id="submitLabel">Buat akun dan lanjutkan</span>
                        <i data-lucide="arrow-right" id="submitIcon" class="h-[18px] w-[18px] transition-transform group-hover:translate-x-0.5"></i>
                        <span id="submitSpinner" class="hidden h-4 w-4 animate-spin rounded-full border-2 border-white/40 border-t-white" aria-hidden="true"></span>
                    </button>
                </form>

                <div class="mt-7 flex items-center justify-between gap-4 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                    <a href="{{ route('login') }}" class="flex min-w-0 items-center gap-3">
                        <span class="flex h-10 w-10 flex-none items-center justify-center rounded-xl bg-indigo-50 text-[#5a5de6]">
                            <i data-lucide="log-in" class="h-5 w-5"></i>
                        </span>
                        <div class="min-w-0">
                            <p class="text-xs text-slate-500">Sudah memiliki akun?</p>
                            <p class="truncate text-sm font-bold text-slate-800">Lanjutkan proses Anda</p>
                        </div>
                        <a href="{{ route('login') }}" class="inline-flex flex-none items-center gap-1 rounded-xl bg-indigo-50 px-3 py-2 text-xs font-extrabold text-[#5a5de6] transition-colors hover:bg-indigo-100 focus:outline-none focus-visible:ring-2 focus-visible:ring-[#696cff]">
                            Masuk
                            <i data-lucide="arrow-up-right" class="h-3.5 w-3.5"></i>
                        </a>
                    </a>
                </div>

                <footer class="mt-8 flex flex-wrap items-center justify-center gap-x-3 gap-y-1 text-center text-[12px] text-slate-400 sm:justify-between">
                    <p>&copy; {{ date('Y') }} PAUD Al Qur'an Azzahra</p>
                    <nav class="flex items-center gap-2" aria-label="Tautan legal">
                        <a href="{{ route('terms') }}" class="transition-colors hover:text-[#5a5de6]">Syarat & Ketentuan</a>
                        <span aria-hidden="true">&bull;</span>
                        <a href="{{ route('privacy') }}" class="transition-colors hover:text-[#5a5de6]">Kebijakan Privasi</a>
                    </nav>
                </footer>
            </div>
        </section>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (window.lucide) {
                window.lucide.createIcons();
            }

            const passwordInput = document.getElementById('password');
            const confirmInput = document.getElementById('password_confirmation');
            const rulesContainer = document.getElementById('passwordRules');
            const matchFeedback = document.getElementById('passwordMatchFeedback');

            const rules = {
                ruleLength: function (password) { return password.length >= 8; },
                ruleUpper: function (password) { return /[A-Z]/.test(password); },
                ruleLower: function (password) { return /[a-z]/.test(password); },
                ruleNumber: function (password) { return /[0-9]/.test(password); }
            };

            function updateRule(ruleId, isValid) {
                const rule = document.getElementById(ruleId);
                const symbol = rule?.querySelector('.rule-symbol');

                if (!rule || !symbol) {
                    return;
                }

                rule.classList.toggle('valid', isValid);
                rule.classList.toggle('invalid', !isValid);
                symbol.textContent = isValid ? '✓' : '•';
            }

            function validatePassword() {
                const password = passwordInput.value;
                rulesContainer.classList.toggle('hidden', password.length === 0);

                Object.keys(rules).forEach(function (ruleId) {
                    updateRule(ruleId, rules[ruleId](password));
                });

                validateConfirmation();
            }

            function validateConfirmation() {
                const confirmation = confirmInput.value;

                if (confirmation.length === 0) {
                    matchFeedback.classList.add('hidden');
                    confirmInput.classList.remove('confirm-valid', 'confirm-invalid');
                    return;
                }

                const matches = passwordInput.value === confirmation;
                matchFeedback.classList.remove('hidden', 'text-emerald-600', 'text-red-600');
                matchFeedback.classList.add(matches ? 'text-emerald-600' : 'text-red-600');
                matchFeedback.textContent = matches ? '✓ Kata sandi cocok' : '× Kata sandi belum cocok';
                confirmInput.classList.toggle('confirm-valid', matches);
                confirmInput.classList.toggle('confirm-invalid', !matches);
            }

            passwordInput?.addEventListener('input', validatePassword);
            confirmInput?.addEventListener('input', validateConfirmation);

            document.querySelectorAll('.password-toggle').forEach(function (button) {
                button.addEventListener('click', function () {
                    const input = document.getElementById(button.dataset.passwordToggle);
                    const isVisible = input.type === 'text';

                    input.type = isVisible ? 'password' : 'text';
                    button.setAttribute('aria-pressed', String(!isVisible));
                    button.setAttribute('aria-label', isVisible ? 'Tampilkan kata sandi' : 'Sembunyikan kata sandi');
                    button.innerHTML = `<i data-lucide="${isVisible ? 'eye' : 'eye-off'}" class="h-5 w-5"></i>`;

                    if (window.lucide) {
                        window.lucide.createIcons();
                    }
                });
            });

            const registerForm = document.getElementById('registerForm');
            const submitButton = document.getElementById('submitButton');
            const submitLabel = document.getElementById('submitLabel');
            const submitIcon = document.getElementById('submitIcon');
            const submitSpinner = document.getElementById('submitSpinner');

            registerForm?.addEventListener('submit', function () {
                if (!registerForm.checkValidity()) {
                    return;
                }

                submitButton.disabled = true;
                submitLabel.textContent = 'Membuat akun...';
                submitIcon?.classList.add('hidden');
                submitSpinner?.classList.remove('hidden');
            });

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
