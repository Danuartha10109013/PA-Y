@if (auth()->user()->roles == 'anggota')
@extends('layouts.dashboard-layout')
@section('title', $title)
@section('content')
<div class="content-background" style="background: white">
    <div class="search-bar">
        <input type="text" placeholder="Search" class="form-control mr-2" style="width: 200px;" />
        @csrf
    </div>
    <hr>

    <div class="table-responsive">
        <table class="table table-bordered">
            <thead bgcolor="EEEEEE">
                <tr>
                    <th>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="select-all">
                            <label class="custom-control-label" for="select-all"></label>
                        </div>
                    </th>
                    <th>Nomor Simpanan</th>
                    <th>Nama</th>
                    <th>Jenis Simpanan</th>
                    <th>Nominal</th>
                    <th>Tanggal Simpanan</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($simpanans as $simpanan)
                <tr>
                    <td>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input checkbox-item" id="checkbox"
                                data-id="{{ $simpanan->id }}">
                            <label class="custom-control-label" for="checkbox"></label>
                        </div>
                    </td>
                    <td>{{ $simpanan->nomor_simpanan }}</td>
                    <td>{{ $simpanan->user->name }}</td>
                    <td>{{ $simpanan->jenis_simpanan }}</td>
                    <td>Rp. {{ number_format($simpanan->nominal, 2) }}</td>
                    <td>{{ $simpanan->tanggal_simpanan }}</td>
                    <td>
                        @if ($simpanan->status == 'Diterima')
                        <span class="badge badge-border-success">Diterima</span>
                        @elseif($simpanan->status == 'Ditolak')
                        <span class="badge badge-border-danger">Ditolak</span>
                        @else
                        <span class="badge badge-border-warning">Menunggu</span>
                        @endif
                    </td>
                    <td class="action-icons">
                        @if ($simpanan->status == 'Menunggu')
                        <form action="{{ route('simpanan.approve', $simpanan->id) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-sm btn-success">Approve</button>
                        </form>
                        <form action="{{ route('simpanan.reject', $simpanan->id) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-sm btn-danger">Reject</button>
                        </form>
                        @endif
                        <a href="/anggota/simpanan/{{ $simpanan->uuid }}"><i class="fa fa-solid fa-eye"></i></a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection
@endif
