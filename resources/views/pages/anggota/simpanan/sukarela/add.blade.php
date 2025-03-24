@extends('layouts.dashboard-layout')
@section('title', $title)
@section('content')
    <div class="content-background">
        <!-- Error Display -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Main Panel -->
        <!-- Header -->
        <div class="header d-flex align-items-center mb-4">
            <i class="fas fa-arrow-left mr-3" style="cursor: pointer;" onclick="goBackToHome(this)"
                data-roles="{{ auth()->user()->roles }}"></i>
            <h2>Simpanan Sukarela</h2>
        </div>
        <!-- Form -->
        <form id="simpanan-form" action="{{ route('payment.store.sukarela') }}" method="POST">
            @csrf

            <!-- Jumlah Transfer -->
            <div class="form-group">
                <label for="nominal">Jumlah Transfer (IDR)</label>
                <input type="text" id="nominal" name="nominal" class="form-control" placeholder="Masukan Nominal"
                    oninput="formatIDR(this, 'raw_amount')" required>
            </div>

            <!-- Pilihan Bank Transfer -->
            <div class="form-group">
                <label for="bank">Pilih Bank Transfer</label>
                <select id="bank" name="bank" class="form-control" required>
                    <option value="BRI">BANK BRI</option>
                    <option value="BNI">BANK BNI</option>
                    <option value="BCA">BANK BCA</option>
                    <option value="MANDIRI">BANK MANDIRI</option>
                </select>
            </div>

            <!-- Tombol Kirim -->
            <button type="submit" class="btn btn-primary mt-4">LANJUTKAN</button>
        </form>
    </div>

    <!-- Modal Pop-Up -->
    <div class="modal fade" id="responseModal" tabindex="-1" role="dialog" aria-labelledby="responseModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="responseModalLabel">Informasi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="responseMessage">
                    <!-- Pesan akan diisi oleh JavaScript -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Script Format Jumlah Transfer -->
    <script>
        document.getElementById('simpanan-form').addEventListener('submit', function(e) {
            e.preventDefault(); // Mencegah submit default

            const nominalField = document.getElementById('nominal');
            const form = e.target;
            const formData = new FormData(form);
            const url = form.action;

            // Hapus format 'Rp.' dan tanda titik sebelum submit
            const rawNominal = nominalField.value.replace(/[^0-9]/g, '');
            formData.set('nominal', rawNominal);

            fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                },
            })
            .then(response => {
                const modal = new bootstrap.Modal(document.getElementById('responseModal'));
                const responseMessage = document.getElementById('responseMessage');

                if (response.status === 201) {
                    responseMessage.textContent = 'Terima Kasih, Transaksi Anda Sedang Diproses.';
                    modal.show();
                } else if (response.status === 202) {
                    responseMessage.textContent = 'Mohon Maaf, Anda Masih Memiliki Transaksi yang Belum Selesai.';
                    modal.show();
                } else if (response.status === 203) {
                    window.location.href = "{{ route('hasil.simpanan.sukarela') }}";
                } else {
                    responseMessage.textContent = 'Terjadi kesalahan, silakan coba lagi.';
                    modal.show();
                }
            })
            .catch(error => {
                const modal = new bootstrap.Modal(document.getElementById('responseModal'));
                const responseMessage = document.getElementById('responseMessage');
                responseMessage.textContent = 'Gagal mengirim permintaan: ' + error.message;
                modal.show();
            });
        });

        document.getElementById('nominal').addEventListener('input', function(e) {
            let value = e.target.value.replace(/[^0-9]/g, ''); // Hanya angka
            value = value.replace(/\B(?=(\d{3})+(?!\d))/g, "."); // Tambahkan titik sebagai pemisah ribuan
            e.target.value = 'Rp. ' + value;
        });
    </script>

    <!-- Script untuk Navigasi Kembali -->
    <script>
        function goBackToHome(element) {
            const roles = element.getAttribute('data-roles');
            let route = '';

            if (roles === 'anggota') {
                route = "{{ route('home-anggota') }}";
            } else if (roles === 'admin') {
                route = "{{ route('home-admin') }}";
            }

            if (route) {
                window.location.href = route;
            }
        }
    </script>
@endsection
