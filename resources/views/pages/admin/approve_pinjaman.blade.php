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
    {{-- <div class="filter-buttons d-flex mt-3">
                <button onclick="filterData('all')" class="btn-link">All</button>
                <button onclick="filterData('diterima')" class="btn-link">Diterima</button>
                <button onclick="filterData('pengajuan')" class="btn-link">Belum Diterima</button>
                <button onclick="filterData('ditolak')" class="btn-link">Ditolak</button>
            </div> --}}

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
                    <th>Nomor Pinjaman</th>
                    <th>Nama</th>
                    <th>Jenis Pinjaman</th>
                    <th>No Rekening</th>
                    <th>Nominal</th>
                    <th>Tanggal Pinjam</th>
                    <th>Tenor</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pinjamans as $key => $pinjaman)
                <tr>
                    <td>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input checkbox-item" id="checkbox"
                                data-id="">
                            <label class="custom-control-label" for="checkbox"></label>
                        </div>
                    </td>
                    <td>{{ $pinjaman->nomor_pinjaman }}</td>
                    <td>{{ $pinjaman->user->name }}</td>
                    <td>{{ $pinjaman->jenis_pinjaman }}</td>
                    <td>{{ $pinjaman->user->no_rekening }}</td>
                    <td>Rp. {{ number_format($pinjaman->nominal, 2) }}</td>
                    <td>{{ $pinjaman->tanggal_pinjaman }}</td>
                    <td>{{ $pinjaman->tenor }}</td>
                    <td>
                        @if ($pinjaman->status_ketua == 'Diterima')
                        <span class="badge badge-border-success">diterima ketua</span>
                        @elseif($pinjaman->status_ketua == 'Ditolak')
                        <span class="badge badge-border-danger">ditolak ketua</span>
                        @elseif($pinjaman->status_manager == 'Diterima')
                        <span class="badge badge-border-warning">menunggu approve ketua</span>
                        @elseif($pinjaman->status_manager == 'Ditolak')
                        <span class="badge badge-border-danger">ditolak manager</span>
                        @else
                        <span class="badge badge-border-warning">Pengajuan</span>
                        @endif
                    </td>
                    <td class="action-icons">
                        <a href="/anggota/pinjaman/{{ $pinjaman->uuid }}"><i class="fa fa-solid fa-eye"></i></a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection
@endif