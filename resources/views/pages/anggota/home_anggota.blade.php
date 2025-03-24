@if (auth()->user()->hasRole('anggota'))
@extends('layouts.dashboard-layout')
@section('title', $title)

@section('content')
    <div class="content-background">
        <div class="container-scroller">
            <!-- Row for Simpanan and Pinjaman Cards -->
            <div class="row">
                <!-- Simpanan Card -->
                <div class="col-xl-6 col-md-6 mb-3">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body d-flex align-items-center">
                            <i class="fas fa-piggy-bank fa-2x mr-3"></i>
                            <div>
                                <h3 class="card-title">Simpanan</h3>
                                <p class="card-text">
                                    Saldo Simpanan: <strong>{{ number_format($totalSimpanan, 0, ',', '.') }}</strong>
                                </p>
                                <p class="card-text">Keluar: <strong>Rp 0</strong></p>
                                <p class="card-text">Pending: <strong>Rp {{ number_format($totalPending, 0, ',', '.') }}</strong></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pinjaman Card -->
                <div class="col-xl-6 col-md-6 mb-3">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body d-flex align-items-center">
                            <i class="fas fa-wallet fa-2x mr-3"></i>
                            <div>
                                <h3 class="card-title">Pinjaman</h3>
                                <p class="card-text">Saldo Pinjaman Emergency: <strong>Rp {{ number_format($saldoEmergency, 0, ',', '.') }}</strong></p>
                                <p class="card-text">Saldo Pinjaman Reguler: <strong>Rp {{ number_format($saldoRegular, 0, ',', '.') }}</strong></p>
                                <p class="card-text">Total Pinjaman: <strong>Rp 0</strong></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <!-- Tampilkan jika metode_pembayaran adalah Pembayaran Manual -->
         @php
    // Ambil data anggota dari relasi user -> anggota
    $anggota = auth()->user()->anggota;
    // Ambil data simpanan pokok dari relasi anggota -> simpananPokok
    $simpananPokok = $anggota ? $anggota->simpananPokok : null;
    // Dapatkan status pembayaran dari simpanan pokok
    $statusPembayaran = $simpananPokok ? $simpananPokok->status_pembayaran : null;
@endphp



<!-- Tampilkan elemen hanya jika status pembayaran adalah 'pending' -->
@if ($simpananPokok && strtolower($simpananPokok->status_pembayaran) === 'pending')
            <!-- Warning Message -->
            <div class="warning">
                &#9888; Silahkan Lakukan Pembayaran Iuran Pertama
            </div>

            <!-- Payment Info -->
            <div class="payment-info">Rp</div>
            <div class="payment-due">*Pembayaran paling lambat tanggal</div>

            <!-- Button for Payment -->
            <div class="button-container">
                <a href="{{ route('simpanan-pokok') }}" class="btn btn-bayar btn-uniform">Bayar</a>
                <a href="{{ route('penarikan.view') }}" class="btn btn-warning btn-uniform">Tarik</a>
            </div>
            <style>
                .btn-uniform {
                    display: inline-block;
                    width: 150px;
                    height: 50px;
                    text-align: center;
                    line-height: 25px;
                    font-size: 16px;
                }

                .btn-bayar {
                    background-color: #4caf50; /* Warna tombol Bayar */
                    color: white;
                    border: none;
                    border-radius: 5px;
                }

                .btn-warning {
                    background-color: #ff9800; /* Warna tombol Tarik Simpanan */
                    color: white;
                    border: none;
                    border-radius: 5px;
                }

                .btn-uniform:hover {
                    opacity: 0.9; /* Efek hover */
                }
            </style>

            <!-- Payment Instructions -->
            <div class="card-content">
                <p>Berikut adalah daftar cara pembayaran menggunakan Virtual Account untuk pembayaran pertama sebagai member Koperasi Konsumen Karlisna PLN Yogyakarta:</p>

                <div class="instructions">
                    <!-- Payment Method: ATM -->
                    <h4>1. Melalui ATM:</h4>
                    <ol>
                        <li>Masukkan kartu ATM dan PIN Anda.</li>
                        <li>Pilih menu Pembayaran/Pembelian.</li>
                        <li>Pilih MultiPayment atau Pembayaran Lainnya.</li>
                        <li>Masukkan Virtual Account 26163563708710.</li>
                        <li>Masukkan jumlah pembayaran.</li>
                        <li>Ikuti instruksi untuk menyelesaikan transaksi.</li>
                    </ol>

                    <!-- Payment Method: Internet Banking -->
                    <h4>2. Melalui Internet Banking:</h4>
                    <ol>
                        <li>Login ke akun Internet Banking Anda.</li>
                        <li>Pilih menu Pembayaran/Pembelian.</li>
                        <li>Pilih MultiPayment atau Pembayaran Lainnya.</li>
                        <li>Masukkan Virtual Account 26163563708710.</li>
                        <li>Masukkan jumlah pembayaran.</li>
                        <li>Ikuti instruksi untuk menyelesaikan transaksi.</li>
                    </ol>

                    <!-- Payment Method: Mobile Banking -->
                    <h4>3. Melalui Mobile Banking:</h4>
                    <ol>
                        <li>Login ke aplikasi Mobile Banking Anda.</li>
                        <li>Pilih menu Pembayaran/Pembelian.</li>
                        <li>Pilih MultiPayment atau Pembayaran Lainnya.</li>
                        <li>Masukkan Virtual Account 26163563708710.</li>
                        <li>Masukkan jumlah pembayaran.</li>
                        <li>Ikuti instruksi untuk menyelesaikan transaksi.</li>
                    </ol>

                    <!-- Payment Method: Teller Bank -->
                    <h4>4. Melalui Teller Bank:</h4>
                    <ol>
                        <li>Kunjungi cabang bank yang bekerja sama dengan Koperasi Karlisna.</li>
                        <li>Isi formulir setoran dengan informasi berikut:</li>
                        <ul>
                            <li>Nama: Koperasi Konsumen Karlisna</li>
                            <li>Virtual Account: 26163563708710</li>
                            <li>Jumlah: Rp 505.000</li>
                        </ul>
                        <li>Serahkan formulir dan uang tunai kepada teller.</li>
                    </ol>

                    <!-- Payment Method: e-Wallet -->
                    <h4>5. Melalui Aplikasi e-Wallet:</h4>
                    <ol>
                        <li>Login ke aplikasi e-Wallet Anda.</li>
                        <li>Pilih menu Pembayaran/Pembelian.</li>
                        <li>Pilih MultiPayment atau Pembayaran Lainnya.</li>
                        <li>Masukkan Virtual Account 26163563708710.</li>
                        <li>Masukkan jumlah pembayaran.</li>
                        <li>Ikuti instruksi untuk menyelesaikan transaksi.</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
@endif
@endsection
@endif
