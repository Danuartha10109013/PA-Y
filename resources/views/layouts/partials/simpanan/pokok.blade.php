<div class="search-bar">
    <input type="text" placeholder="Search" class="form-control mr-2" style="width: 200px;" />
    <div class="ml-auto d-flex">
        <x-action-button type="pokok" status="Diterima" class="btn-success">Terima</x-action-button>
        <x-action-button type="pokok" status="Ditolak" class="btn-danger">Tolak</x-action-button>
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
            <h4>Tabel Simpanan Pokok</h4>
            <tr>
                <th>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="select-all-pokok">
                        <label class="custom-control-label" for="select-all-pokok"></label>
                    </div>
                </th>
                <th>Nomor Simpanan</th>
                <th>Nama</th>
                <th>Nominal Simpanan</th>
                <th>Tanggal Simpanan</th>
                <th>Nomor Rekening</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($simpananPokok as $key => $data)
                <tr>
                    <td>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input checkbox-item"
                                id="checkbox-pokok-{{ $data->id }}" data-id="{{ $data->id }}">
                            <label class="custom-control-label" for="checkbox-pokok-{{ $data->id }}"></label>
                        </div>
                    </td>
                    <td>{{ $data->nomor_simpanan }}</td>
                    <td>{{ $data->user->name }}</td>
                    <td>Rp. {{ number_format($data->nominal, 2) }}</td>
                    <td>{{ \Carbon\Carbon::parse($data->tanggal_simpanan)->format('d-m-Y') }}</td>
                    <td>
                        @if ($data->virtualAccount)
                            {{ $data->virtualAccount->nama_bank }} -
                            {{ $data->virtualAccount->no_rekening }}
                        @else
                            N/A
                        @endif
                    </td>
                    <x-status-badge-ketua :statusKetua="$data->status_ketua" />
                    <td class="action-icons">
                        <i class="fas fa-edit edit"></i>
                        <i class="fas fa-trash delete"></i>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script>
    document.getElementById('select-all-pokok').addEventListener('change', function() {
        var isChecked = this.checked;
        var checkboxes = document.querySelectorAll('input[id^="checkbox-pokok-"]');
        checkboxes.forEach(function(checkbox) {
            checkbox.checked = isChecked;
        });
    });
</script>
