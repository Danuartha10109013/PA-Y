@if (auth()->user()->roles == 'manager')
    @extends('layouts.dashboard-layout')
    @section('title', $title)
    @section('content')
        <div class="content-background" style="background: white">
            <div class="search-bar d-flex">
                <input type="text" placeholder="Search" class="form-control mr-2" style="width: 200px;" />
                <div class="ml-auto d-flex">
                    <x-action-button status="Diterima" class="btn-success" id="btn-terima" disabled>
                        Terima
                    </x-action-button>
                    <x-action-button status="Ditolak" class="btn-danger" id="btn-tolak" disabled>
                        Tolak
                    </x-action-button>
                </div>

                @csrf
            </div>

            <div class="filter-buttons d-flex mt-3">
                <button onclick="filterData('all')" class="btn-link">All</button>
                <button onclick="filterData('diterima')" class="btn-link">Diterima</button>
                <button onclick="filterData('pengajuan')" class="btn-link">Belum Diterima</button>
                <button onclick="filterData('ditolak')" class="btn-link">Ditolak</button>
            </div>

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
                            <th>Nominal Pinjaman</th>
                            <th>Jangka Waktu Peminjaman</th>
                            <th>Status Peminjaman</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pinjamanEmergency as $data)
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
                                <td>{{ $data->jangka_waktu }}</td>
                                <x-status-badge :statusKetua="$data->status_ketua" :statusBendahara="$data->status_bendahara" :statusManager="$data->status_manager" />
                                <td class="action-icons">
                                    <i class="fas fa-edit edit"></i>
                                    <i class="fas fa-trash delete"></i>
                                    <a href="{{ route('pinjaman.detail.uuid', $data->uuid) }}">
                                        <i class="fas fa-eye text-success"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <x-script-manager />
    @endsection
@endif
