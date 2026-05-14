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

        <button onclick="document.getElementById('deleteAccountModal').classList.remove('hidden')" class="px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-md transition-colors">
            Hapus Akun Secara Permanen
        </button>

        <!-- Modal -->
        <div id="deleteAccountModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
                <div class="fixed inset-0 transition-opacity bg-black/50" onclick="document.getElementById('deleteAccountModal').classList.add('hidden')"></div>
                <div class="relative bg-white dark:bg-[#2b2c40] rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:max-w-lg sm:w-full border border-[#d9dee3] dark:border-[#434463]">
                    <form method="POST" action="{{ route('profile.destroy') }}">
                        @csrf
                        @method('delete')
                        <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg font-medium text-[#566a7f] dark:text-[#d5d5e2] mb-2">Apakah Anda yakin ingin menghapus akun?</h3>
                            <p class="text-sm text-[#a1b0cb] mb-4">Silakan masukkan password Anda untuk mengonfirmasi.</p>
                            <div>
                                <input type="password" id="delete_password" name="password" placeholder="Password Anda" required
                                        class="sneat-input focus:!border-red-500 focus:!ring-red-500">
                                @error('password') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="bg-[#f5f5f9] dark:bg-[#232333] px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 sm:ml-3 sm:w-auto sm:text-sm">Hapus Akun</button>
                            <button type="button" onclick="document.getElementById('deleteAccountModal').classList.add('hidden')" class="mt-3 w-full inline-flex justify-center rounded-md border border-[#d9dee3] dark:border-[#434463] shadow-sm px-4 py-2 bg-white dark:bg-[#2b2c40] text-base font-medium text-[#697a8d] dark:text-[#a1b0cb] hover:bg-[#f5f5f9] dark:hover:bg-[#232333] sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif

</div>

@if($errors->has('password') && old('_method') === 'delete')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('deleteAccountModal').classList.remove('hidden');
    });
</script>
@endif

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
            const iconX = el.querySelector('.icon-x');
            const iconCheck = el.querySelector('.icon-check');

            if (isValid) {
                el.classList.remove('text-red-500');
                el.classList.add('text-green-500');
                iconX.classList.add('hidden');
                iconCheck.classList.remove('hidden');
            } else {
                el.classList.remove('text-green-500');
                el.classList.add('text-red-500');
                iconCheck.classList.add('hidden');
                iconX.classList.remove('hidden');
            }
        }

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

        confirmInput.addEventListener('input', checkMatch);

        function checkMatch() {
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
                    iconX.classList.add('hidden');
                    iconCheck.classList.remove('hidden');
                } else {
                    textSpan.textContent = 'Password tidak cocok';
                    textSpan.classList.replace('text-green-500', 'text-red-500');
                    iconCheck.classList.add('hidden');
                    iconX.classList.remove('hidden');
                }
            } else {
                matchBox.classList.add('hidden');
            }
        }
    });
    // Logika SweetAlert2 untuk Lupa Password
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
                    
                    // --- DUA BARIS INI ADALAH KUNCI FIX-NYA ---
                    scrollbarPadding: false, 
                    heightAuto: false,       
                    // ------------------------------------------

                    customClass: {
                        popup: 'rounded-xl',
                        confirmButton: 'px-5 py-2.5 text-white font-medium rounded-md shadow-sm'
                    }
                });
            });
        }
    }
</script>

@endsection
