<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Simpanan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            /* Ukuran font lebih kecil */
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 12px;
            /* Ukuran font tabel lebih kecil */
        }

        th,
        td {
            border: 1px solid black;
            padding: 5px;
            /* Padding dikurangi agar lebih ringkas */
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        h2 {
            text-align: center;
            margin-bottom: 15px;
            /* Margin dikurangi */
            font-size: 14px;
            /* Sedikit lebih besar dari teks biasa */
        }
    </style>
</head>

<body>
    <h2>Mutasi Simpanan Sukarela</h2>
    <table>
        <thead>
            <tr>
                <th>Nomor Invoice</th>
                <th>Nama</th>
                <th>Nominal</th>
                <th>Status</th>
                <th>Virtual Account</th>
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
                </tr>
            @endforeach
        </tbody>
    </table>

    <h2>Mutasi Simpanan Berjangka</h2>
    <table>
        <thead>
            <tr>
                <th>Nomor Invoice</th>
                <th>Nama</th>
                <th>Nominal</th>
                <th>Jangka Waktu</th>
                <th>Jumlah Jasa</th>
                <th>Status</th>
                <th>Virtual Account</th>
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
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
