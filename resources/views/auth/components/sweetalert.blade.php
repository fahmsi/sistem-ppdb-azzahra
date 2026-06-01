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
                    title: 'Apakah Anda yakin ingin menghapus data ini?',
                    text: 'Data yang dihapus tidak dapat dikembalikan.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#697a8d',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });
</script>
