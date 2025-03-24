@if (auth()->user()->hasRole('anggota'))
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
                <i class="fas fa-arrow-left mr-3" style="cursor: pointer;" onclick="goBackToHome()"></i>
                <h2>Simpanan Wajib</h2>
            </div>

            <!-- Form -->
            <form action="{{ route('createPayment') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="nama">Nama</label>
                    <input type="text" id="nama" name="nama" class="form-control"
                        value="{{ Auth::user()->name }}" readonly>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="text" id="email" name="email" class="form-control"
                        value="{{ Auth::user()->email }}" readonly>
                </div>
                <div class="form-group">
                    <label for="amount">Jumlah Transfer (IDR)</label>
                    <input type="number" id="amount" name="amount" class="form-control" min="10000" required
                        placeholder="Masukan Nominal">
                </div>
                <div class="form-group">
                    <label for="bank">Pilih Bank Transfer</label>
                    <select id="payment_method" name="payment_method" class="form-control" required>
                        <option value="VIRTUAL_ACCOUNT_BRI">Bank BRI</option>
                        <option value="VIRTUAL_ACCOUNT_BCA">Bank BCA</option>
                        <option value="VIRTUAL_ACCOUNT_MANDIRI">Bank MANDIRI</option>
                        <option value="VIRTUAL_ACCOUNT_BNI">BANK BNI</option>
                    </select>
                </div>

                <div class="info-text mt-3">
                    Simpanan akan dibayar melalui Virtual Account dan pembayaran maksimal 1 x 24 JAM setelah Anda Mengajukan
                    Simpanan.
                </div>

                <button type="submit" class="btn btn-primary mt-4">LANJUTKAN</button>
            </form>
        </div>
        <script>
            function goBackToHome() {
                window.location.href = "{{ route('home-anggota') }}";
            }
        </script>
    @endsection
@endif
