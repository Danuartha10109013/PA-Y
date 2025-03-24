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
        <a href="{{ route('anggota.sukarela.create') }}">
            <button class="btn btn-primary my-4">Ajukan Pinjaman</button>
        </a>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead style="background-color: #EEEEEE;">
                    <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nomor Simpanan</th>
                                <th>Nama</th>
                                <th>Bank</th>
                                <th>Nominal Simpanan</th>
                                <th>Virtual Account</th>
                                <th>Expired Payment</th>
                                <th>Status Payment</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($simpanan_sukarela as $key => $simpanan)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $simpanan->no_simpanan }}</td>
                                    <td>{{ $simpanan->user->name }}</td>
                                    <td>{{ $simpanan->bank }}</td>
                                    <td>Rp. {{ number_format($simpanan->nominal, 2) }}</td>
                                    <td>{{ $simpanan->virtual_account ?? 'N/A' }}</td>
                                    <td>{{ $simpanan->expired_at ? $simpanan->expired_at->format('d-m-Y H:i') : 'N/A' }}</td>
                                    <td>{{ $simpanan->status_payment }}</td>
                                    <td class="action-icons">
                                        <a href="/simpanan/{{ $simpanan->id }}"><i class="fa fa-solid fa-eye"></i></a>
                                    </td>
                                </tr>
                    @endforeach
                </tbody>
            </table>
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

            if (response.status === 202) {
                responseMessage.textContent = 'Mohon Maaf Tunggu Pengajuan Simpanan Sukarela Sebelumnya Disetujui Sebelum Mengajukan Simpanan Sukarela Lagi.';
                modal.show();
            } else if (response.status === 201) {
                responseMessage.textContent = 'Terima Kasih Simpanan Sukarela Sedang Diajukan Mohon Menunggu Untuk Disetujui.';
                modal.show();
            } else if (response.status === 203) {
                window.location.href = '{{ route("hasil.simpanan.sukarela") }}';
            } else if (response.status === 200) {
                response.json().then(data => {
                    if (data.approval_ketua === 'approved') {
                        window.location.href = '{{ route("hasil.simpanan.sukarela") }}';
                    } else {
                        responseMessage.textContent = 'Pengajuan Anda belum disetujui. Mohon tunggu.';
                        modal.show();
                    }
                });
            } else if (!response.ok) {
                return response.json().then(data => {
                    if (data.errors) {
                        responseMessage.textContent = 'Terjadi kesalahan:\n' + Object.values(data.errors).join('\n');
                    } else {
                        responseMessage.textContent = 'Terjadi kesalahan, silakan coba lagi.';
                    }
                    modal.show();
                });
            } else {
                response.json().then(data => {
                    if (data.redirect) {
                        window.location.href = data.redirect;
                    }
                });
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
