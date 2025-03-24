@extends('layouts.dashboard-layout')
@section('title', $title)
@section('content')

    <div class="content-background">
        <div class="search-bar d-flex align-items-center mb-3">
            <input type="text" placeholder="Search" class="form-control mr-2" style="width: 200px;" />
            <div class="icons">
                <i class="fas fa-print"></i>
                <i class="fas fa-file-pdf"></i>
                <i class="fas fa-file-excel"></i>
            </div>
        </div>
        <div class="table-responsive">
            {{-- <h4>Mutasi Pinjaman Emergency</h4> --}}
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Nomor Pinjaman</th>
                        <th>Nama</th>
                        <th>Nominal</th>
                        <th>Jangka Waktu</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- @foreach ($pinjamanEmergency as $data)
                    <tr>
                        <td>{{ $data->nomor_pinjaman }}</td>
                        <td>{{ $data->user->name }}</td>
                        <td>Rp. {{ number_format($data->nominal, 2) }}</td>
                        <td>{{ $data->tenor->tenor }}</td>
                        <td>{{ $data->status }}</td>
                        <td class="action-icons">
                            <i class="fas fa-eye detail"></i>
                            <i class="fas fa-edit edit"></i>
                            <i class="fas fa-trash delete"></i>
                        </td>
                    </tr>
                @endforeach --}}
                </tbody>
            </table>
        </div>
    </div>

    <style>
        .icons i {
            font-size: 24px;
            color: #007bff;
            margin-right: 12px;
            transition: color 0.3s ease;
        }

        .icons i:hover {
            color: #007bff;
        }

        .action-icons i {
            font-size: 20px;
            cursor: pointer;
            margin-right: 8px;
            transition: color 0.3s ease;
        }

        .action-icons i.edit {
            color: #007bff;
        }

        .action-icons i.delete {
            color: #dc3545;
        }

        .action-icons i.detail {
            color: #007bff;
        }

        .action-icons i:hover {
            opacity: 0.7;
        }

        h4 {
            margin-bottom: 20px;
            text-align: center;
        }

        .table-responsive {
            overflow-x: auto;
            margin-bottom: 20px;
        }

        .action-icons {
            text-align: center;
            display: flex;
            justify-content: space-evenly;
            align-items: center;
        }

        .action-icons i {
            cursor: pointer;
            color: #007bff;
            font-size: 18px;
        }

        .action-icons i:hover {
            color: #dc3545;
        }

        .content-background {
            padding: 20px;
        }

        .search-bar {
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .search-bar input {
            max-width: 300px;
        }
    </style>
@endsection
