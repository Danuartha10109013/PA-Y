    <!-- jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- plugins:js -->
    {{-- <script src="{{ asset('skydash/vendors/js/vendor.bundle.base.js') }}"></script> --}}
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <script src="{{ asset('skydash/vendors/chart.js/Chart.min.js') }}"></script>
    <script src="{{ asset('skydash/vendors/datatables.net/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('skydash/vendors/datatables.net-bs4/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ asset('skydash/js/dataTables.select.min.js') }}"></script>

    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="{{ asset('skydash/js/off-canvas.js') }}"></script>
    <script src="{{ asset('skydash/js/hoverable-collapse.js') }}"></script>
    <script src="{{ asset('skydash/js/template.js') }}"></script>
    <script src="{{ asset('skydash/js/settings.js') }}"></script>
    <script src="{{ asset('skydash/js/todolist.js') }}"></script>
    <!-- endinject -->
    <!-- Custom js for this page-->
    <script src="{{ asset('skydash/js/dashboard.js') }}"></script>
    <script src="{{ asset('skydash/js/Chart.roundedBarCharts.js') }}"></script>
  
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.15.0/dist/sweetalert2.all.min.js"></script>
    <!-- End custom js for this page-->

    <!-- Tambahkan CSS untuk padding ikon -->
<style>
    /* Atur jarak ikon dan elemen lainnya */
    .swal2-icon.swal2-success {
        padding: 0; /* Hilangkan padding bawaan */
        margin-top: 20px; /* Beri jarak atas */
        margin-bottom: 20px; /* Beri jarak bawah */
        width: 80px; /* Atur ulang lebar ikon */
        height: 80px; /* Atur ulang tinggi ikon */
        border-radius: 50%; /* Pastikan tetap berbentuk lingkaran */
        background-color: rgba(220, 255, 220, 0.5); /* Opsional: Warna latar tambahan untuk estetika */
    }

    .swal2-title {
        font-size: 18px; /* Ukuran font untuk judul */
        margin-bottom: 10px; /* Jarak bawah judul */
    }

    .swal2-content {
        font-size: 14px; /* Ukuran font untuk konten */
    }

    .swal2-popup.swal-wide {
        padding: 20px; /* Atur padding pop-up */
        max-width: 400px; /* Atur lebar maksimal pop-up */
    }

    .swal2-confirm {
        background-color: #3085d6; /* Warna tombol konfirmasi */
        color: white; /* Warna teks tombol */
    }
    .custom-icon-padding {
        margin-top: 40px; /* Tambahkan jarak lebih besar dari atas */
        margin-bottom: 30px; /* Tambahkan jarak antara ikon dan teks di bawahnya */
        display: flex;
        justify-content: center; /* Ikon tetap berada di tengah */
        align-items: center;
    }

</style>

@if (session('success'))
    <script>
        Swal.fire({
            title: "Success!",
            text: "{{ session('success') }}",
            icon: "success",
            iconHtml: '<i class="fas fa-check-circle"></i>', // Ikon alternatif jika diperlukan
            customClass: {
                popup: 'swal-wide', // Class untuk pop-up
            },
            confirmButtonText: 'OK',
        });
    </script>
@endif
