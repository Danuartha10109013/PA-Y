<div class="search-bar">
    <input type="text" placeholder="Search" class="form-control mr-2" style="width: 200px;" />
    <div class="ml-auto d-flex">
        <x-action-button type="regular" status="Diterima" class="btn-success">Terima</x-action-button>
        <x-action-button type="regular" status="Ditolak" class="btn-danger">Tolak</x-action-button>

    </div>
    @csrf
</div>
<div class="filter-buttons d-flex mt-3">
    <button onclick="filterdata('all')" class="btn-link">All</button>
    <button onclick="filterdata('diterima')" class="btn-link">Diterima</button>
    <button onclick="filterdata('pengajuan')" class="btn-link">Belum Diterima</button>
    <button onclick="filterdata('ditolak')" class="btn-link">Ditolak</button>
</div>
<div class="table-responsive pt-3">
    <table class="table table-bordered">
        <thead bgcolor="EEEEEE">
            <h4>Tabel Pinjaman Regular</h4>
            <tr>
                <th>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="select-all-regular">
                        <label class="custom-control-label" for="select-all-regular"></label>
                    </div>
                </th>
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
            @foreach ($pinjamanRegular as $key => $data)
                <tr>
                    <td>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input checkbox-item"
                                id="checkbox-regular-{{ $data->id }}" data-id="{{ $data->id }}">
                            <label class="custom-control-label" for="checkbox-regular-{{ $data->id }}"></label>
                        </div>
                    </td>
                    <td>{{ $data->nomor_pinjaman }}</td>
                    <td>{{ $data->user->name }}</td>
                    <td>Rp. {{ number_format($data->nominal, 2) }}</td>
                    <td>{{ $data->tenor->tenor }}</td>
                    <td>{{ $data->keterangan }}</td>
                    <td>
                        @if ($data->virtualAccount)
                            {{ $data->virtualAccount->nama_bank }} -
                            {{ $data->virtualAccount->no_rekening }}
                        @else
                            N/A
                        @endif
                    </td>
                    <td>Rp. {{ number_format($data->nominal_angsuran, 2) }}</td>
                    <x-status-badge :statusKetua="$data->status_ketua" :statusManager="$data->status_manager" />
                    <td class="action-icons">
                        <i class="fas fa-edit edit"></i>
                        <i class="fas fa-trash delete"></i>
                    </td>
                </tr>
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
