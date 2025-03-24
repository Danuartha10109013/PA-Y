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

        <div class="header d-flex align-items-center mb-4">
            <i class="fas fa-arrow-left mr-3" style="cursor: pointer;" onclick="goBackToHome(this)"
                data-roles="{{ auth()->user()->roles }}"></i>
            <h2>SPK</h2>
        </div>

        <form action="{{ route('spk.store', $pinjamans->uuid) }}" method="POST" autocomplete="off">
            @csrf

            <div class="form-group">
                <label for="kriteria_id">Pilih Rekening</label>
                <select id="kriteria_id" name="kriteria_id" class="form-control" required
                    onchange="showRekeningFields()">
                    <option value="">-- Pilih Rekening --</option>
                    @foreach ($kriterias as $kriteria)
                        <option value="{{ $kriteria->id }}">{{ $kriteria->nama_kriteria }}</option>
                    @endforeach
                </select>
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-primary mt-4">LANJUTKAN</button>
            </div>
        </form>
    </div>

    <x-script-anggota />
@endsection
