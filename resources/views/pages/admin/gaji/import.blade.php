@extends('layouts.dashboard-layout')
@section('title', $title)
@section('content')
    <div class="content-background col-md-6">
        <form action="{{ route('potongan.gaji.store', $pinjamanAktif->uuid) }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="bukti_pembayaran">Upload Bukti Pemotongan Gaji</label>
                <input type="file" id="bukti_pembayaran" name="bukti_pembayaran" class="form-control">
            </div>
            <div class="text-center">
                <a href="{{ route('data.potongan.gaji') }}" class="btn btn-secondary">Batalkan</a>
                <button type="submit" class="btn btn-success">Upload</button>
            </div>
        </form>        
    </div>
@endsection
