@extends('layouts.dashboard-layout')
@section('title', 'Penarikan Simpanan')

@section('content')
<div class="container">
    <h1>Penarikan Simpanan</h1>
    <p>Saldo Simpanan Anda: <strong>Rp {{ number_format($saldoSimpanan, 0, ',', '.') }}</strong></p>
    <!-- Form Penarikan -->
    <form action="{{ route('penarikan.verifikasi') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="jumlah">Jumlah Penarikan</label>
            <input type="number" name="jumlah" id="jumlah" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Ajukan Penarikan</button>
    </form>
</div>
@endsection
