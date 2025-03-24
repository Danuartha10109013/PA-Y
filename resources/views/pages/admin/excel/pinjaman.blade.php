<table class="table table-bordered">
    <thead bgcolor="EEEEEE">
        <tr>
            <th>User ID</th>
            <th>Nama</th>
            <th>Nomor Pinjaman</th>
            <th>Jenis Pinjaman</th>
            <th>Nominal Pinjaman</th>
            <th>Jangka Waktu</th>
            <th>Nominal Angsuran</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($pinjaman as $key => $data)
            <tr>
                <td>{{ $data->user->id }}</td>
                <td>{{ $data->user->name }}</td>
                <td>{{ $data->nomor_pinjaman }}</td>
                <td>{{ ucwords(str_replace('_', ' ', $data->jenis_pinjaman)) }}</td>
                <td>{{ $data->amount }}</td>
                <td>{{ $data->jangka_waktu }}</td>
                <td>{{ $data->nominal_angsuran }}</td>
                <td>{{ $data->status }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
