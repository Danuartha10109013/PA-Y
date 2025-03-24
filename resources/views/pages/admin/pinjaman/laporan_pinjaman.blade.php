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
            <thead style="background-color: #EEEEEE;">
                <tr>
                    <th>No</th>
                    <th>Nomor Pinjaman</th>
                    <th>Nama</th>
                    <th>Nominal Pinjaman</th>
                    <th>Jangka Waktu Peminjaman</th>
                    <th>Tujuan Pinjaman</th>
                    <th>Nomor Rekening</th>
                    <th>Nominal Angsuran Perbulan</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pinjamans as $pinjaman)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $pinjaman->nomor_pinjaman }}</td>
                    <td>{{ $pinjaman->user->name }}</td>
                    <td>Rp. {{ number_format($pinjaman->nominal_pinjaman, 2) }}</td>
                    <td>{{ $pinjaman->jangka_waktu }}</td>
                    <td>{{ $pinjaman->keterangan }}</td>
                    <td>{{ $pinjaman->rekening_id ? $pinjaman->rekening->nomor_rekening : 'N/A' }}</td>
                    <td>Rp. {{ number_format($pinjaman->nominal_angsuran, 2) }}</td>
                    <td>
                        @if ($pinjaman->status_ketua == 'Diterima')
                        <span class="badge badge-success">Diterima Ketua</span>
                        @elseif($pinjaman->status_ketua == 'Ditolak')
                        <span class="badge badge-danger">Ditolak Ketua</span>
                        @elseif($pinjaman->status_manager == 'Diterima')
                        <span class="badge badge-warning">Menunggu Approve Ketua</span>
                        @elseif($pinjaman->status_manager == 'Ditolak')
                        <span class="badge badge-danger">Ditolak Manager</span>
                        @else
                        <span class="badge badge-info">Pengajuan</span>
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