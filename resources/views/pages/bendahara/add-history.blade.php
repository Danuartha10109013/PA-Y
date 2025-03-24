@extends('layouts.dashboard-layout')
@section('title', $title)
@section('content')
    <div class="content-background col-md-6">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        <form action="{{ route('check-status.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="nomor_pinjaman">Masukan Invoice Number</label>
                <input type="text" id="nomor_pinjaman" name="nomor_pinjaman" class="form-control" required
                    placeholder="Masukan Invoice Number">
            </div>
            <div class="form-group">
                <label for="payment_proof">Masukan Bukti Pembayaran</label>
                <input type="file" id="nomor_pinjaman" name="payment_proof" class="form-control" required>
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-primary mt-4">Submit</button>
            </div>
        </form>
    </div>
@endsection
