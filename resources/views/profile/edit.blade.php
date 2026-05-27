@extends('layouts.app')

@section('title', 'Pengaturan Akun')
@section('header_title', 'Pengaturan Profil & Akun')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Profile Information Section -->
    <div class="bg-white dark:bg-[#2b2c40] rounded-lg shadow-sneat dark:shadow-sneat-dark border border-[#d9dee3] dark:border-[#434463] p-6 sm:p-8 animate-fade-up">
        <div class="mb-6 border-b border-[#d9dee3] dark:border-[#434463] pb-4">
            <h2 class="text-xl font-heading font-bold text-[#566a7f] dark:text-[#d5d5e2] flex items-center gap-2">
                <i data-lucide="user" class="w-5 h-5 text-[#696cff]"></i> Profil Pengguna
            </h2>
            <p class="text-sm text-[#a1b0cb] mt-1">Perbarui nama, email, nomor telepon, dan foto profil Anda.</p>
        </div>

        <form method="POST" action="{{ route('profile.update') }}" class="space-y-5 max-w-xl" enctype="multipart/form-data">
            @csrf
            @method('patch')

            <!-- Avatar Upload -->
            <div>
                <label class="block text-sm font-medium text-[#566a7f] dark:text-[#d5d5e2] mb-2">Foto Profil</label>
                <div class="flex items-center gap-5">
                    <div class="relative group">
                        <img id="avatarPreview" src="{{ $user->avatar ? Storage::url($user->avatar) : asset('images/default-avatar.png') }}" alt="Avatar" class="w-20 h-20 rounded-full object-cover border-2 border-[#d9dee3] dark:border-[#434463] shadow-sm bg-[#f5f5f9] dark:bg-[#232333]">
                        <label for="avatarInput" class="absolute inset-0 flex items-center justify-center bg-black/40 rounded-full opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer">
                            <i data-lucide="camera" class="w-5 h-5 text-white"></i>
                        </label>
                    </div>
                    <div>
                        <label for="avatarInput" class="inline-flex items-center gap-2 px-3 py-1.5 bg-[#f5f5f9] dark:bg-[#232333] hover:bg-[#e7e7ff] dark:hover:bg-[#696cff]/20 text-[#697a8d] dark:text-[#a1b0cb] text-sm font-medium rounded-md transition-colors cursor-pointer">
                            <i data-lucide="upload" class="w-4 h-4"></i> Ganti Foto
                        </label>
                        <input type="file" id="avatarInput" name="avatar" accept="image/jpeg,image/png,image/jpg" class="hidden" onchange="previewAvatar(this)">
                        <p class="text-xs text-[#a1b0cb] mt-1">JPG, PNG. Max 2MB.</p>
                    </div>
                </div>
                @error('avatar') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="name" class="block text-sm font-medium text-[#566a7f] dark:text-[#d5d5e2] mb-1">Nama Lengkap</label>
                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required autofocus
                       class="sneat-input">
                @error('name') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-[#566a7f] dark:text-[#d5d5e2] mb-1">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                       class="sneat-input">
                @error('email') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="no_telpon" class="block text-sm font-medium text-[#566a7f] dark:text-[#d5d5e2] mb-1">No. Telepon / WhatsApp</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <i data-lucide="phone" class="w-4 h-4 text-[#a1b0cb]"></i>
                    </div>
                    <input type="text" id="no_telpon" name="no_telpon" value="{{ old('no_telpon', $user->no_telpon) }}" placeholder="08xxxxxxxxxx"
                            class="sneat-input w-full !pl-10">
                </div>
                @error('no_telpon') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
            </div>

            <div class="pt-4 flex items-center gap-4">
                <button type="submit" class="sneat-btn-primary">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

    <!-- Update Password Section -->
    <div class="bg-white dark:bg-[#2b2c40] rounded-lg shadow-sneat dark:shadow-sneat-dark border border-[#d9dee3] dark:border-[#434463] p-6 sm:p-8 animate-fade-up" style="animation-delay: 0.1s;">
        <div class="mb-6 border-b border-[#d9dee3] dark:border-[#434463] pb-4">
            <h2 class="text-xl font-heading font-bold text-[#566a7f] dark:text-[#d5d5e2] flex items-center gap-2">
                <i data-lucide="lock" class="w-5 h-5 text-[#696cff]"></i> Ubah Password
            </h2>
            <p class="text-sm text-[#a1b0cb] mt-1">Pastikan akun Anda menggunakan kata sandi yang panjang dan acak agar tetap aman.</p>
        </div>

        <form method="POST" action="{{ route('password.request') }}" class="space-y-4 max-w-xl">
            @csrf
            @method('put')

            <div>
                <div class="flex justify-between items-center mb-1">
                    <label for="current_password" class="block text-sm font-medium text-[#566a7f] dark:text-[#d5d5e2]">
                        Password Saat Ini
                    </label>

                    @if (Route::has('password.request'))
                        <a href="#" id="forgot-password-alert" class="text-[11px] text-[#696cff] hover:underline font-medium cursor-pointer">
                            Lupa password?
                        </a>
                    @endif
                </div>
                <div class="relative">
                    <input type="password" id="current_password" name="current_password" required 
                        class="sneat-input w-full pr-10" placeholder="············">
                    <button type="button" class="toggle-password absolute inset-y-0 right-0 flex items-center pr-3 text-[#a1b0cb] hover:text-[#696cff] focus:outline-none transition-colors" data-target="current_password">
                        <i data-lucide="eye" class="icon-eye w-4 h-4"></i>
                        <i data-lucide="eye-off" class="icon-eye-off w-4 h-4 hidden"></i>
                    </button>
                </div>
                @error('current_password') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-[#566a7f] dark:text-[#d5d5e2] mb-1">Password Baru</label>
                <div class="relative">
                    <input type="password" id="password" name="password" required class="sneat-input w-full pr-10">
                    <button type="button" class="toggle-password absolute inset-y-0 right-0 flex items-center pr-3 text-[#a1b0cb] hover:text-[#696cff] focus:outline-none transition-colors" data-target="password">
                        <i data-lucide="eye" class="icon-eye w-4 h-4"></i>
                        <i data-lucide="eye-off" class="icon-eye-off w-4 h-4 hidden"></i>
                    </button>
                </div>
                
                <ul id="password-rules" class="text-xs mt-2 space-y-1 hidden transition-all duration-300">
                    <li id="rule-length" class="text-red-500 flex items-center gap-1.5 transition-colors">
                        <i data-lucide="x" class="icon-x w-3 h-3"></i><i data-lucide="check" class="icon-check w-3 h-3 hidden"></i> Minimal 8 karakter
                    </li>
                    <li id="rule-upper" class="text-red-500 flex items-center gap-1.5 transition-colors">
                        <i data-lucide="x" class="icon-x w-3 h-3"></i><i data-lucide="check" class="icon-check w-3 h-3 hidden"></i> Minimal 1 huruf besar
                    </li>
                    <li id="rule-lower" class="text-red-500 flex items-center gap-1.5 transition-colors">
                        <i data-lucide="x" class="icon-x w-3 h-3"></i><i data-lucide="check" class="icon-check w-3 h-3 hidden"></i> Minimal 1 huruf kecil
                    </li>
                    <li id="rule-number" class="text-red-500 flex items-center gap-1.5 transition-colors">
                        <i data-lucide="x" class="icon-x w-3 h-3"></i><i data-lucide="check" class="icon-check w-3 h-3 hidden"></i> Minimal 1 angka
                    </li>
                </ul>
                @error('password') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-[#566a7f] dark:text-[#d5d5e2] mb-1">Konfirmasi Password Baru</label>
                <div class="relative">
                    <input type="password" id="password_confirmation" name="password_confirmation" required class="sneat-input w-full pr-10">
                    <button type="button" class="toggle-password absolute inset-y-0 right-0 flex items-center pr-3 text-[#a1b0cb] hover:text-[#696cff] focus:outline-none transition-colors" data-target="password_confirmation">
                        <i data-lucide="eye" class="icon-eye w-4 h-4"></i>
                        <i data-lucide="eye-off" class="icon-eye-off w-4 h-4 hidden"></i>
                    </button>
                </div>
                
                <div id="match-rule" class="text-xs mt-2 hidden flex items-center gap-1.5 transition-colors">
                    <i data-lucide="x" class="icon-x w-3 h-3 text-red-500"></i>
                    <i data-lucide="check" class="icon-check w-3 h-3 text-green-500 hidden"></i>
                    <span class="match-text text-red-500">Password tidak cocok</span>
                </div>
                @error('password_confirmation') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
            </div>

            <div class="pt-4 flex items-center gap-4">
                <button type="submit" id="btn-submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#696cff] hover:bg-[#5f61e6] text-white text-sm font-medium rounded-md transition-colors shadow-sm">
                    Perbarui Password
                </button>
            </div>
        </form>
    </div>

    <!-- Delete Account Section -->
    @if(auth()->user()->role === 'parent')
    <div class="bg-red-50/50 dark:bg-red-500/5 rounded-lg shadow-sneat dark:shadow-sneat-dark border border-red-200 dark:border-red-500/20 p-6 sm:p-8 animate-fade-up" style="animation-delay: 0.2s;">
        <div class="mb-6 border-b border-red-200 dark:border-red-500/20 pb-4">
            <h2 class="text-xl font-heading font-bold text-red-700 dark:text-red-400 flex items-center gap-2">
                <i data-lucide="alert-triangle" class="w-5 h-5"></i> Hapus Akun
            </h2>
            <p class="text-sm text-red-600 dark:text-red-400/80 mt-1">Setelah akun Anda dihapus, semua data dan riwayat pendaftaran anak Anda akan dihapus secara permanen. Tindakan ini tidak dapat dibatalkan.</p>
        </div>

        <button type="button" id="btnDeleteAccount" class="px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-md transition-colors shadow-sm">
            Hapus Akun Secara Permanen
        </button>

        <form id="deleteAccountForm" method="POST" action="{{ route('profile.destroy') }}" class="hidden">
            @csrf
            @method('delete')
            <input type="password" name="password" id="hidden_delete_password">
        </form>
    </div>
    @endif

</div>

<script>
    // --- 4. LOGIKA SWEETALERT2 UNTUK HAPUS AKUN ---
        const btnDeleteAccount = document.getElementById('btnDeleteAccount');
        const deleteAccountForm = document.getElementById('deleteAccountForm');
        const hiddenPasswordInput = document.getElementById('hidden_delete_password');

        if (btnDeleteAccount) {
            btnDeleteAccount.addEventListener('click', function () {
                Swal.fire({
                    title: 'Hapus Akun Permanen?',
                    // Menggunakan HTML kustom agar bisa memasukkan ikon mata
                    html: `
                        <p class="text-sm text-[#566a7f] dark:text-[#d5d5e2] mb-5">
                            Tindakan ini tidak bisa dibatalkan! Semua riwayat pendaftaran anak akan hilang. Masukkan password Anda untuk mengonfirmasi.
                        </p>
                        <div class="relative w-full max-w-xs mx-auto">
                            <input type="password" id="swal-input-password" class="sneat-input w-full pr-10 focus:!border-red-500 focus:!ring-red-500" placeholder="Masukkan password Anda..." autocapitalize="off" autocorrect="off">
                            <button type="button" id="swal-toggle-password" class="absolute inset-y-0 right-0 flex items-center pr-3 text-[#a1b0cb] hover:text-[#696cff] focus:outline-none transition-colors">
                                <i data-lucide="eye" id="swal-icon-eye" class="w-4 h-4"></i>
                                <i data-lucide="eye-off" id="swal-icon-eye-off" class="w-4 h-4 hidden"></i>
                            </button>
                        </div>
                    `,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#697a8d',
                    confirmButtonText: 'Ya, Hapus Akun!',
                    cancelButtonText: 'Batal',
                    scrollbarPadding: false,
                    heightAuto: false,
                    customClass: {
                        popup: 'rounded-xl',
                        confirmButton: 'px-4 py-2 text-white font-medium rounded-md shadow-sm',
                        cancelButton: 'px-4 py-2 text-white font-medium rounded-md shadow-sm'
                    },
                    // Fungsi ini akan dijalankan saat popup SweetAlert terbuka
                    didOpen: () => {
                        // Render ulang ikon Lucide agar ikon mata muncul di dalam popup
                        if (typeof lucide !== 'undefined') {
                            lucide.createIcons();
                        }

                        // Mengaktifkan fitur klik ikon mata
                        const toggleBtn = document.getElementById('swal-toggle-password');
                        const passwordInput = document.getElementById('swal-input-password');
                        const iconEye = document.getElementById('swal-icon-eye');
                        const iconEyeOff = document.getElementById('swal-icon-eye-off');

                        toggleBtn.addEventListener('click', () => {
                            if (passwordInput.type === 'password') {
                                passwordInput.type = 'text';
                                iconEye.classList.add('hidden');
                                iconEyeOff.classList.remove('hidden');
                            } else {
                                passwordInput.type = 'password';
                                iconEye.classList.remove('hidden');
                                iconEyeOff.classList.add('hidden');
                            }
                        });
                    },
                    // Fungsi ini memvalidasi kotak input sebelum tombol "Ya" diproses
                    preConfirm: () => {
                        const password = document.getElementById('swal-input-password').value;
                        if (!password) {
                            Swal.showValidationMessage('Password tidak boleh kosong!');
                        }
                        return password;
                    }
                }).then((result) => {
                    // Jika user mengisi password dan menekan konfirmasi
                    if (result.isConfirmed) {
                        hiddenPasswordInput.value = result.value;
                        deleteAccountForm.submit();
                    }
                });
            });
        }

        // Menangkap error dari Laravel jika password yang dimasukkan salah saat menghapus akun
        @if($errors->has('password') && old('_method') === 'delete')
            Swal.fire({
                title: 'Gagal!',
                text: '{{ $errors->first('password') }}',
                icon: 'error',
                confirmButtonColor: '#696cff',
                scrollbarPadding: false,
                heightAuto: false,
                customClass: {
                    popup: 'rounded-xl',
                    confirmButton: 'px-5 py-2.5 text-white font-medium rounded-md shadow-sm'
                }
            });
        @endif
</script>

<script>
function previewAvatar(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('avatarPreview').src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        
        // --- 1. LOGIKA TOGGLE PASSWORD (IKON MATA) ---
        const toggleButtons = document.querySelectorAll('.toggle-password');
        
        toggleButtons.forEach(button => {
            button.addEventListener('click', function () {
                const targetId = this.getAttribute('data-target');
                const inputField = document.getElementById(targetId);
                const iconEye = this.querySelector('.icon-eye');
                const iconEyeOff = this.querySelector('.icon-eye-off');

                // Ubah tipe input
                if (inputField.type === 'password') {
                    inputField.type = 'text';
                    iconEye.classList.add('hidden');
                    iconEyeOff.classList.remove('hidden');
                } else {
                    inputField.type = 'password';
                    iconEye.classList.remove('hidden');
                    iconEyeOff.classList.add('hidden');
                }
            });
        });

        // --- 2. LOGIKA VALIDASI REAL-TIME ---
        const passwordInput = document.getElementById('password');
        const confirmInput = document.getElementById('password_confirmation');
        const rulesBox = document.getElementById('password-rules');
        const matchBox = document.getElementById('match-rule');

        function toggleRule(id, isValid) {
            const el = document.getElementById(id);
            if (!el) return; // Mencegah error jika elemen tidak ada
            
            const iconX = el.querySelector('.icon-x');
            const iconCheck = el.querySelector('.icon-check');

            if (isValid) {
                el.classList.remove('text-red-500');
                el.classList.add('text-green-500');
                if(iconX) iconX.classList.add('hidden');
                if(iconCheck) iconCheck.classList.remove('hidden');
            } else {
                el.classList.remove('text-green-500');
                el.classList.add('text-red-500');
                if(iconCheck) iconCheck.classList.add('hidden');
                if(iconX) iconX.classList.remove('hidden');
            }
        }

        if (passwordInput) {
            passwordInput.addEventListener('input', function () {
                const val = this.value;
                
                if (val.length > 0) {
                    rulesBox.classList.remove('hidden');
                } else {
                    rulesBox.classList.add('hidden');
                }

                toggleRule('rule-length', val.length >= 8);
                toggleRule('rule-upper', /[A-Z]/.test(val));
                toggleRule('rule-lower', /[a-z]/.test(val));
                toggleRule('rule-number', /[0-9]/.test(val));

                checkMatch();
            });
        }

        if (confirmInput) {
            confirmInput.addEventListener('input', checkMatch);
        }

        function checkMatch() {
            if (!passwordInput || !confirmInput) return;
            
            const passVal = passwordInput.value;
            const confVal = confirmInput.value;

            if (confVal.length > 0) {
                matchBox.classList.remove('hidden');
                const iconX = matchBox.querySelector('.icon-x');
                const iconCheck = matchBox.querySelector('.icon-check');
                const textSpan = matchBox.querySelector('.match-text');

                if (passVal === confVal && passVal !== "") {
                    textSpan.textContent = 'Password cocok';
                    textSpan.classList.replace('text-red-500', 'text-green-500');
                    if(iconX) iconX.classList.add('hidden');
                    if(iconCheck) iconCheck.classList.remove('hidden');
                } else {
                    textSpan.textContent = 'Password tidak cocok';
                    textSpan.classList.replace('text-green-500', 'text-red-500');
                    if(iconCheck) iconCheck.classList.add('hidden');
                    if(iconX) iconX.classList.remove('hidden');
                }
            } else {
                matchBox.classList.add('hidden');
            }
        }

        // --- 3. LOGIKA SWEETALERT2 UNTUK LUPA PASSWORD ---
        const forgotPasswordBtn = document.getElementById('forgot-password-alert');
        if (forgotPasswordBtn) {
            forgotPasswordBtn.addEventListener('click', function(e) {
                e.preventDefault(); 
                
                Swal.fire({
                    title: 'Informasi Keamanan',
                    text: 'Untuk alasan keamanan, silakan Logout (Keluar) dari akun Anda terlebih dahulu, kemudian gunakan fitur Lupa Password di halaman Login.',
                    icon: 'info',
                    confirmButtonText: 'Mengerti',
                    confirmButtonColor: '#696cff',
                    scrollbarPadding: false, 
                    heightAuto: false,       
                    customClass: {
                        popup: 'rounded-xl',
                        confirmButton: 'px-5 py-2.5 text-white font-medium rounded-md shadow-sm'
                    }
                });
            });
        }

    });
</script>

@endsection
