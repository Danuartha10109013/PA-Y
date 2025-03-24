@extends('layouts.dashboard-layout')
@section('title', $title)

@section('content')
    <div class="content-background">
        <div class="search-bar d-flex align-items-center mb-3">
            <input type="text" placeholder="Search" class="form-control mr-2" style="width: 200px;" />
            <div class="icons">
                <a href="{{ route('export.pdf.simpanan') }}">
                    <i class="fas fa-file-pdf"></i>
                </a>
                <i class="fas fa-file-excel"></i>
            </div>
        </div>

        <h4>Mutasi Simpanan Sukarela</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nomor Invoice</th>
                    <th>Nama</th>
                    <th>Nominal</th>
                    <th>Status</th>
                    <th>Virtual Account</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($sukarela as $item)
                    <tr>
                        <td>{{ $item->no_simpanan }}</td>
                        <td>{{ $item->user->name }}</td>
                        <td>Rp. {{ number_format($item->nominal, 2) }}</td>
                        <td>{{ ucfirst($item->status_payment) }}</td>
                        <td>{{ $item->virtual_account ?? 'Tidak Ada' }}</td>
                        <td class="action-icons">
                            <i class="fas fa-eye detail"></i>
                            <i class="fas fa-edit edit"></i>
                            <i class="fas fa-trash delete"></i>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <h4>Mutasi Simpanan Berjangka</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nomor Invoice</th>
                    <th>Nama</th>
                    <th>Nominal</th>
                    <th>Jangka Waktu</th>
                    <th>Jumlah Jasa</th>
                    <th>Status</th>
                    <th>Virtual Account</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($berjangka as $item)
                    <tr>
                        <td>{{ $item->no_simpanan }}</td>
                        <td>{{ $item->user->name }}</td>
                        <td>Rp. {{ number_format($item->nominal, 2) }}</td>
                        <td>{{ $item->jangka_waktu }} Bulan</td>
                        <td>Rp. {{ number_format($item->jumlah_jasa_perbulan, 2) }}</td>
                        <td>{{ ucfirst($item->status_payment) }}</td>
                        <td>{{ $item->virtual_account ?? 'Tidak Ada' }}</td>
                        <td class="action-icons">
                            <i class="fas fa-eye detail"></i>
                            <i class="fas fa-edit edit"></i>
                            <i class="fas fa-trash delete"></i>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
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

        .search-bar {
            margin-top: 20px;
        }
    </style>
@endsection
