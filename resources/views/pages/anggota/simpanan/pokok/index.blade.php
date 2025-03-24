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
            <h2>Simpanan Pokok</h2>
        </div>
        <!-- Form -->
        <form id="simpanan-form" action="{{ route('payment.store.pokok') }}" method="POST">
            @csrf

            <!-- Jumlah Transfer -->
            @if ($simpananPokok)
    <div class="form-group">
        <label for="nominal">Jumlah Transfer (IDR)</label>
        <input type="text" id="nominal" name="nominal" class="form-control"
               value="Rp. {{ number_format($simpananPokok->nominal, 2) }}" readonly>
    </div>
@else
    <p>Tidak ada data simpanan pokok.</p>
@endif






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
                if (response.ok) {
                    // Redirect langsung ke halaman hasil_simpanan
                    window.location.href = '{{ route("hasil.simpanan.pokok") }}';
                } else {
                    return response.json().then(data => {
                        alert(data.errors ? Object.values(data.errors).join('\n') : 'Terjadi kesalahan, silakan coba lagi.');
                    });
                }
            })
            .catch(error => {
                alert('Gagal mengirim permintaan: ' + error.message);
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
