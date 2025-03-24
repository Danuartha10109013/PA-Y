@extends('layouts.dashboard-layout')
@section('title', 'Hasil Simpanan Sukarela')
@section('content')
    <div class="content-background p-4">
        <!-- Header -->
        <div class="d-flex align-items-center mb-4">
            
            <h2 class="fs-4 fw-bold mb-0">Hasil Simpanan Sukarela</h2>
        </div>

        <!-- Informasi Simpanan --> 
        <div class="card shadow-sm border-0 rounded">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0 fw-bold">Detail Simpanan Anda</h5>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <strong>Nomor Simpanan:</strong>
                        <span>{{ $no_simpanan }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <strong>Jumlah Transfer:</strong>
                        <span>Rp. {{ number_format($nominal, 0, ',', '.') }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <strong>Bank Tujuan:</strong>
                        <span>{{ $bank }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <strong>Virtual Account:</strong>
                        <span>{{ $virtual_account }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <strong>Tanggal Kadaluarsa:</strong>
                        <span>{{ \Carbon\Carbon::parse($expired_at)->translatedFormat('d F Y H:i:s') }}</span>
                    </li>
                </ul>
            </div>
        </div>


    </div>
@endsection
