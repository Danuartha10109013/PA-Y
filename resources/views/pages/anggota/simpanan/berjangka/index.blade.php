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
    <a href="{{ route('anggota.berjangka.add') }}">
        <button class="btn btn-primary my-4">Ajukan Pinjaman</button>
    </a>

    <div class="table-responsive">
        <table class="table table-bordered">
            <thead style="background-color: #EEEEEE;">
                <tr>
                    <th>No</th>
                    <th>Nomor Simpanan</th>
                    <th>Nama</th>
                    <th>Bank</th>
                    <th>Nominal Simpanan</th>
                    <th>Virtual Account</th>
                    <th>Expired Payment</th>
                    <th>Status Payment</th>
                    <th>Jangka Waktu (Bulan)</th>
                    <th>Jumlah Jasa/Bulan</th>
                    <th>Tanggal Pengajuan</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($simpanan_berjangka as $key => $simpanan)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $simpanan->no_simpanan }}</td>
                        <td>{{ $simpanan->user->name }}</td>
                        <td>{{ $simpanan->bank }}</td>
                        <td>Rp. {{ number_format($simpanan->nominal, 2) }}</td>
                        <td>{{ $simpanan->virtual_account ?? 'N/A' }}</td>
                        {{-- <td>{{ $simpanan->expired_at ? $simpanan->expired_at->format('d-m-Y H:i') : 'N/A' }}</td> --}}
                        <td>
                            {{ $simpanan->expired_at ? \Carbon\Carbon::parse($simpanan->expired_at)->format('d-m-Y H:i') : 'N/A' }}
                        </td>                        
                        <td>{{ $simpanan->status_payment }}</td>
                        <td>{{ $simpanan->jangka_waktu }}</td>
                        <td>Rp. {{ number_format($simpanan->jumlah_jasa_perbulan, 2) }}</td>
                        <td>{{ $simpanan->tanggal_pengajuan }}</td>
                        <td class="action-icons">
                            <a href="/simpanan/{{ $simpanan->id }}"><i class="fa fa-solid fa-eye"></i></a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
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
