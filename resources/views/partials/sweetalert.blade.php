@if(session('swal_success') || session('swal_error') || session('swal_warning') || session('swal_info'))
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {

        @if(session('swal_success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: @json(session('swal_success')),
                confirmButtonColor: '#102A43',
                confirmButtonText: 'OK',
            });
        @endif

        @if(session('swal_error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: @json(session('swal_error')),
                confirmButtonColor: '#102A43',
                confirmButtonText: 'OK',
            });
        @endif

        @if(session('swal_warning'))
            Swal.fire({
                icon: 'warning',
                title: 'Perhatian',
                text: @json(session('swal_warning')),
                confirmButtonColor: '#102A43',
                confirmButtonText: 'OK',
            });
        @endif

        @if(session('swal_info'))
            Swal.fire({
                icon: 'info',
                title: 'Info',
                text: @json(session('swal_info')),
                confirmButtonColor: '#102A43',
                confirmButtonText: 'OK',
            });
        @endif

    });
</script>
@endif
