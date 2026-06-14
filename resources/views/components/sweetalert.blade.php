<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Global Alert Configuration
        const Alert = Swal.mixin({ 
            showConfirmButton: true,
            confirmButtonColor: '#696cff',
            timer: 3000,
            timerProgressBar: true,
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
                confirmButtonColor: @json(session('swal.confirmButtonColor', '#696cff')),
                confirmButtonText: @json(session('swal.confirmButtonText', 'Selesai')),
                allowOutsideClick: false
            });
        @endif

        // Flash Messages to Modal Alert
        @if(session('success'))
            Alert.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: @json(session('success'))
            });
        @endif

        @if(session('error'))
            Alert.fire({
                icon: 'error',
                title: 'Gagal!',
                text: @json(session('error'))
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
