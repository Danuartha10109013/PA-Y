@extends('layouts.dashboard-layout')

@section('title', $title)

@section('content')
    <div class="content-background" style="background: white">
        <h1>{{ $title }}</h1>

        <!-- Dropdown untuk memilih jenis -->
        <div class="mb-4">
            <label for="jenis-simpanan" class="form-label">Pilih Jenis Simpanan</label>
            <select id="jenis-simpanan" class="form-select" onchange="changeJenisSimpanan()">
                <option value="wajib" {{ $jenis == 'wajib' ? 'selected' : '' }}>Simpanan Wajib</option>
                <option value="pokok" {{ $jenis == 'pokok' ? 'selected' : '' }}>Simpanan Pokok</option>
            </select>
        </div>

        <!-- Tabel data -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Anggota</th>
                    <th>Jenis Simpanan</th>
                    <th>Jumlah</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($simpanan as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->nama_anggota }}</td>
                        <td>{{ ucfirst($item->jenis) }}</td>
                        <td>{{ number_format($item->jumlah, 0, ',', '.') }}</td>
                        <td>{{ $item->status }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <script>
        function changeJenisSimpanan() {
            const jenis = document.getElementById('jenis-simpanan').value;
            window.location.href = `?jenis=${jenis}`;
        }
    </script>
@endsection
