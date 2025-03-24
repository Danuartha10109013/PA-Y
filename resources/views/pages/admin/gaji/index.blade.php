@if (auth()->user()->roles == 'admin')
    @extends('layouts.dashboard-layout')
    @section('title', $title)
    @section('content')
        <div class="content-background" style="background: white">
            <h3>Potongan Gaji Pinjaman</h3>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead style="background-color: #EEEEEE;">
                        <tr>
                            <th>No</th>
                            <th>Nama Anggota</th>
                            <th>Nomor Pinjaman</th>
                            <th>Jenis Pinjaman</th>
                            <th>Pokok Pinjaman</th>
                            <th>Angsuran Pokok</th>
                            <th>Tenor</th>
                            <th>Sisa Angsuran</th>
                            <th>Sisa Bayar (Bulan)</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pinjamanAktif as $key => $data)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $data->user->name }}</td>
                                <td>{{ $data->nomor_pinjaman }}</td>
                                <td>{{ ucwords(str_replace('_', ' ', $data->jenis_pinjaman)) }}</td>
                                <td>Rp {{ number_format($data->amount, 2) }}</td>
                                <td>Rp {{ number_format($data->nominal_angsuran, 2) }}</td>
                                <td><span class="badge badge-border-success">{{ $data->jangka_waktu }} Bulan </span></td>
                                <td>Rp {{ number_format($data->sisa_pinjaman, 2) }}</td>
                                <td><span class="badge badge-border-warning">{{ $data->sisa_jangka_waktu }} Bulan</span>
                                </td>
                                <td>
                                    <span
                                        class="badge {{ $data->status_pembayaran === 'Aktif' ? 'badge-border-warning' : 'badge-border-success' }}">
                                        {{ $data->status_pembayaran }}
                                    </span>
                                </td>
                                <td class="action-icons">
                                    <a href="{{ route('detail.potongan.gaji', $data->uuid) }}">
                                        <i class="fa fa-solid fa-eye"></i>
                                    </a>
                                    <a href="{{ route('input.potongan.gaji.create', $data->uuid) }}">
                                        <i class="fa fa-solid fa-edit"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endsection
@endif
