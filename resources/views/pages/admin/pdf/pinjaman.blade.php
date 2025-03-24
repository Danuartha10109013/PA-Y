<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
        }

        h1 {
            text-align: center;
            margin-top: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color: #f4f4f4;
        }

        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .table-container {
            padding: 0 20px;
        }
    </style>
</head>

<body>
    <h1>{{ $title }}</h1>
    <p style="text-align: center;">Tanggal: {{ $date }}</p>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Nama Anggota</th>
                    <th>Nomor Pinjaman</th>
                    <th>Jenis Pinjaman</th>
                    <th>Pokok Pinjaman</th>
                    <th>Tenor</th>
                    <th>Angsuran Pokok</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($content as $key => $data)
                    <tr>
                        <td>{{ $data->user->name }}</td>
                        <td>{{ $data->nomor_pinjaman }}</td>
                        <td>{{ ucwords(str_replace('_', ' ', $data->jenis_pinjaman)) }}</td>
                        <td>Rp. {{ number_format($data->amount, 2) }}</td>
                        <td>{{ $data->jangka_waktu }}</td>
                        <td>Rp. {{ number_format($data->nominal_angsuran, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>

</html>
