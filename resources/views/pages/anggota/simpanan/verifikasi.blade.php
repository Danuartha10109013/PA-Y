@extends('layouts.dashboard-layout')
@section('title', 'Verifikasi Penarikan')

@section('content')
<div class="content-background">
    <div class="container-scroller">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0">Verifikasi Penarikan</h3>
                    </div>
                    <div class="card-body">
                        <p>Anda akan menarik dana sebesar: <strong>Rp {{ number_format($jumlahPenarikan, 0, ',', '.') }}</strong></p>
                        <form action="{{ route('penarikan.ajukan') }}" method="POST">
                            @csrf
                            <input type="hidden" name="jumlah" value="{{ $jumlahPenarikan }}">
                            <button type="submit" class="btn btn-success btn-block">Konfirmasi Penarikan</button>
                        </form>
                        <a href="{{ url()->previous() }}" class="btn btn-secondary btn-block">Kembali</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
