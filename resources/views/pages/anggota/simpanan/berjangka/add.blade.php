@extends('layouts.dashboard-layout')
@section('title', 'Simpanan-Berjangka')
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

    <!-- Header -->
    <div class="header d-flex align-items-center mb-4">
        <i class="fas fa-arrow-left mr-3" style="cursor: pointer;" onclick="goBackToHome(this)"
            data-roles="{{ auth()->user()->roles }}"></i>
        <h2>Simpanan Berjangka</h2>
    </div>

    <!-- Form -->
    <form action="{{ route('payment.store') }}" method="POST" id="simpanan-berjangka-form">
        @csrf

        <!-- Jumlah Transfer -->
        <div class="form-group">
            <label for="nominal">Jumlah Transfer (IDR)</label>
            <input type="text" id="nominal" name="nominal" class="form-control" placeholder="Masukan Nominal"
                value="{{ old('nominal') }}" required>
        </div>

        <!-- Jangka Waktu -->
        <div class="form-group">
            <label for="jangka_waktu">Jangka Waktu (Bulan)</label>
            <select id="jangka_waktu" name="jangka_waktu" class="form-control" required>
                <option value="">Pilih Jangka Waktu</option>
                <option value="3" {{ old('jangka_waktu') == 3 ? 'selected' : '' }}>3 Bulan</option>
                <option value="6" {{ old('jangka_waktu') == 6 ? 'selected' : '' }}>6 Bulan</option>
                <option value="12" {{ old('jangka_waktu') == 12 ? 'selected' : '' }}>12 Bulan</option>
                <option value="24" {{ old('jangka_waktu') == 24 ? 'selected' : '' }}>24 Bulan</option>
            </select>
        </div>

        <!-- Jumlah Jasa -->
        <div class="form-group">
            <label for="jumlah_jasa_perbulan">Jumlah Jasa (IDR)</label>
            <input type="text" id="jumlah_jasa_perbulan" name="jumlah_jasa_perbulan" class="form-control"
                placeholder="Jumlah Jasa Akan Dihitung Otomatis" readonly>
        </div>

        <!-- Pilihan Bank -->
        <div class="form-group">
            <label for="bank">Pilih Bank</label>
            <select id="bank" name="bank" class="form-control" required>
                <option value="">Pilih Bank</option>
                <option value="BRI" {{ old('bank') == 'BANK BRI' ? 'selected' : '' }}>BANK BRI</option>
                <option value="BNI" {{ old('bank') == 'BANK BNI' ? 'selected' : '' }}>BANK BNI</option>
                <option value="BCA" {{ old('bank') == 'BANK BCA' ? 'selected' : '' }}>BANK BCA</option>
                <option value="MANDIRI" {{ old('bank') == 'BANK MANDIRI' ? 'selected' : '' }}>BANK MANDIRI</option>
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

<!-- Script untuk Format Input Jumlah dan Perhitungan Jasa -->
<script>
    document.querySelectorAll('#nominal').forEach((element) => {
        element.addEventListener('input', function (e) {
            let value = e.target.value.replace(/[^0-9]/g, ''); // Hapus karakter selain angka
            value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.'); // Format ribuan
            e.target.value = 'Rp. ' + value; // Tambahkan prefix "Rp."

            // Perhitungan jumlah jasa otomatis
            const nominal = parseInt(value.replace(/\D/g, '')) || 0;
            const bungaPerTahun = 0.05; // 5% bunga per tahun
            const jangkaWaktu = parseInt(document.getElementById('jangka_waktu').value) || 0;

            if (jangkaWaktu > 0 && nominal > 0) {
                const jasaPerBulan = (nominal * bungaPerTahun) / 12; // Bunga bulanan
                document.getElementById('jumlah_jasa_perbulan').value = 'Rp. ' + jasaPerBulan.toLocaleString('id-ID');
            } else {
                document.getElementById('jumlah_jasa_perbulan').value = '';
            }
        });
    });

    document.getElementById('jangka_waktu').addEventListener('change', function () {
        const nominalField = document.getElementById('nominal');
        const nominal = parseInt(nominalField.value.replace(/\D/g, '')) || 0;
        const bungaPerTahun = 0.05; // 5% bunga per tahun
        const jangkaWaktu = parseInt(this.value) || 0;

        if (jangkaWaktu > 0 && nominal > 0) {
            const jasaPerBulan = (nominal * bungaPerTahun) / 12; // Bunga bulanan
            document.getElementById('jumlah_jasa_perbulan').value = 'Rp. ' + jasaPerBulan.toLocaleString('id-ID');
        } else {
            document.getElementById('jumlah_jasa_perbulan').value = '';
        }
    });

    document.getElementById('simpanan-berjangka-form').addEventListener('submit', function (e) {
        e.preventDefault(); // Mencegah submit default

        document.querySelectorAll('#nominal, #jumlah_jasa_perbulan').forEach((element) => {
            element.value = element.value.replace(/[^0-9]/g, ''); // Hapus "Rp." dan titik
        });

        const form = e.target;
        const formData = new FormData(form);
        const url = form.action;

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
                responseMessage.textContent = 'Terima Kasih, Simpanan Berjangka Anda Sedang Diajukan. Mohon Menunggu Persetujuan.';
                modal.show();
            } else if (response.status === 202) {
                responseMessage.textContent = 'Mohon Maaf, Anda Masih Memiliki Pengajuan yang Belum Disetujui.';
                modal.show();
            } else if (response.status === 203) {
                window.location.href = "{{ route('hasil.simpanan') }}";
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
</script>
@endsection
