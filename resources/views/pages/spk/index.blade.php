@extends('layouts.dashboard-layout')
@section('title', $title)
@section('content')
    <div class="content-background" style="background: white">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead bgcolor="EEEEEE">
                    <tr>
                        <th>Nama Anggota</th>
                        <th>Kriteria</th>
                        <th>Nilai</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($spks as $key => $spk)
                        <tr>
                            <td>{{ $spk->pinjamans->nomor_pinjaman }}</td>
                            <td>{{ $spk->score }}</td>
                            <td>{{ $spk->level }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection