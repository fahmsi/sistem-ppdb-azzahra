<script>
    document.addEventListener('DOMContentLoaded', function () {
        const isDark = document.documentElement.classList.contains('dark');
        const swalBg = isDark ? '#2b2c40' : '#fff';
        const swalColor = isDark ? '#d5d5e2' : '#566a7f';

        @if(request()->query('upload_error') === 'too_large')
            const cleanUrl = new URL(window.location.href);
            cleanUrl.searchParams.delete('upload_error');
            window.history.replaceState({}, document.title, cleanUrl.toString());
        @endif

        // Global Alert Configuration
        const Alert = Swal.mixin({ 
            showConfirmButton: true,
            confirmButtonColor: '#696cff',
            timer: 3000,
            timerProgressBar: true,
            background: swalBg,
            color: swalColor,
            didOpen: (modal) => {
                modal.addEventListener('mouseenter', Swal.stopTimer)
                modal.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        @if(session('swal'))
            Swal.fire({
                icon: @json(session('swal.icon', 'success')),
                title: @json(session('swal.title', 'Berhasil!')),
                text: @json(session('swal.text')),
                background: swalBg,
                color: swalColor,
                confirmButtonColor: @json(session('swal.confirmButtonColor', '#696cff')),
                confirmButtonText: @json(session('swal.confirmButtonText', 'Selesai')),
                allowOutsideClick: false
            });
        @endif

        // Flash Messages to Modal Alert
        @if(session('success'))
            @if(session('success') === 'Akun Anda telah berhasil dihapus.')
                localStorage.removeItem('spmb_theme');
                document.documentElement.classList.remove('dark');
                document.documentElement.classList.add('light');
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: @json(session('success')),
                    background: '#fff',
                    color: '#566a7f',
                    confirmButtonColor: '#696cff',
                    timer: 3000,
                    timerProgressBar: true
                });
            @else
                Alert.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: @json(session('success'))
                });
            @endif
        @endif

        @if(session('error'))
            Alert.fire({
                icon: 'error',
                title: 'Gagal!',
                text: @json(session('error'))
            });
        @elseif(request()->query('upload_error') === 'too_large')
            Alert.fire({
                icon: 'error',
                title: 'Ukuran File Terlalu Besar',
                text: 'Total ukuran file terlalu besar. Pastikan setiap file maksimal 2 MB.'
            });
        @endif

        @if(session('warning'))
            Alert.fire({
                icon: 'warning',
                title: 'Peringatan!',
                text: @json(session('warning'))
            });
        @endif

        @if(session('info'))
            Alert.fire({
                icon: 'info',
                title: 'Informasi',
                text: @json(session('info'))
            });
        @endif

        // Global Delete Confirmation
        document.querySelectorAll('.form-delete').forEach(function (form) {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                Swal.fire({
                    title: form.dataset.confirmTitle || 'Hapus data ini?',
                    text: form.dataset.confirmText || 'Data yang dihapus tidak dapat dikembalikan.',
                    icon: 'warning',
                    background: swalBg,
                    color: swalColor,
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#697a8d',
                    confirmButtonText: form.dataset.confirmButton || 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                    focusCancel: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Memproses...',
                            text: 'Mohon tunggu sebentar.',
                            background: swalBg,
                            color: swalColor,
                            allowOutsideClick: false,
                            showConfirmButton: false,
                            didOpen: () => Swal.showLoading()
                        });
                        form.submit();
                    }
                });
            });
        });
    });
</script>
