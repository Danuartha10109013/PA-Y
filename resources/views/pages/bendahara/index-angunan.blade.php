@extends('layouts.dashboard-layout')
@section('title', $title)
@section('content')
    <div class="content-background" style="background: white">
        <div class="search-bar d-flex align-items-center">
            <input type="text" placeholder="Search" class="form-control mr-2" style="width: 200px;" />
            <div class="ml-auto d-flex">
                <x-action-button status="Diterima" class="btn-success">Terima</x-action-button>
                <x-action-button status="Ditolak" class="btn-danger">Tolak</x-action-button>
            </div>
            @csrf
        </div>

        <!-- Filter Buttons -->
        <div class="filter-buttons d-flex mt-3">
            <button onclick="filterData('all')" class="btn-link">All</button>
            <button onclick="filterData('diterima')" class="btn-link">Diterima</button>
            <button onclick="filterData('pengajuan')" class="btn-link">Belum Diterima</button>
            <button onclick="filterData('ditolak')" class="btn-link">Ditolak</button>
        </div>

        <!-- Data Table -->
        <div class="table-responsive pt-3">
            <table class="table table-bordered">
                <thead bgcolor="EEEEEE">
                    <h4>Tabel Pinjaman Angunan</h4>
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
                        <th>Nominal Pinjaman</th>
                        <th>Bukti Gaji</th>
                        <th>Bukti Angunan</th>
                        <th>Jangka Waktu Peminjaman</th>
                        <th>Status Peminjaman</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($angunans as $key => $data)
                        @if ($data->status_manager == 'Diterima')
                            <tr>
                                <td>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input checkbox-item"
                                            id="checkbox-{{ $data->id }}" data-id="{{ $data->id }}">
                                        <label class="custom-control-label" for="checkbox-{{ $data->id }}"></label>
                                    </div>
                                </td>
                                <td>{{ $data->nomor_pinjaman }}</td>
                                <td>{{ $data->user->name }}</td>
                                <td>{{ ucwords(str_replace('_', ' ', $data->jenis_pinjaman)) }}</td>
                                <td>Rp. {{ number_format($data->amount, 2) }}</td>
                                <td>
                                    @if (isset($data->bukti_gaji))
                                        <img src="{{ asset($data->bukti_gaji) }}" alt="image" style="cursor: pointer;"
                                            onclick="previewImage('{{ asset($data->bukti_gaji) }}')">
                                    @else
                                        No data available
                                    @endif
                                </td>
                                <td>
                                    @if (isset($data->image))
                                        <img src="{{ asset($data->image) }}" alt="image" style="cursor: pointer;"
                                            onclick="previewImage('{{ asset($data->image) }}')">
                                    @else
                                        No data available
                                    @endif
                                </td>
                                <td>{{ $data->jangka_waktu }}</td>
                                <x-status-badge-bendahara :statusKetua="$data->status_ketua" :statusBendahara="$data->status_bendahara" />
                                <td class="action-icons">
                                    <i class="fas fa-edit edit"></i>
                                    <i class="fas fa-trash delete"></i>
                                    <a href="{{ route('pinjaman.detail.uuid', $data->uuid) }}">
                                        <i class="fas fa-eye text-success"></i>
                                    </a>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
        <nav aria-label="Page navigation example">
            <ul class="pagination">
                <li class="page-item">
                    <a class="page-link" href="#" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                        <span class="sr-only">Previous</span>
                    </a>
                </li>
                <li class="page-item"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item">
                    <a class="page-link" href="#" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                        <span class="sr-only">Next</span>
                    </a>
                </li>
            </ul>
        </nav>


        <div class="showing-info">
            Showing <span id="start"></span> to <span id="end"></span> of <span id="total"></span> entries
        </div>


    </div>
    <x-script-bendahara />
@endsection
