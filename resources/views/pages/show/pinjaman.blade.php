@extends('layouts.dashboard-layout')
@section('title', $title)
@section('content')
    <div class="content-background" style="background: white">
        <div class="table-responsive">
            @foreach ($pinjamans as $pinjaman)
                <table class="table table-bordered">
                    <tr>
                        <th>Nomor Pinjaman</th>
                        <td>{{ $pinjaman->nomor_pinjaman }}</td>
                    </tr>
                    <tr>
                        <th>Nama</th>
                        <td>{{ $pinjaman->user ? $pinjaman->user->name : 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Jenis Pinjaman</th>
                        <td>{{ ucwords(str_replace('_', ' ', $pinjaman->jenis_pinjaman)) }}</td>
                    </tr>
                    <tr>
                        <th>Nominal Pinjaman</th>
                        <td>{{ $pinjaman->amount }}</td>
                    </tr>
                    <tr>
                        <th>Jangka Waktu</th>
                        <td>{{ $pinjaman->jangka_waktu }}</td>
                    </tr>
                    <tr>
                        <th>Tujuan Pinjaman</th>
                        <td>{{ $pinjaman->keterangan }}</td>
                    </tr>
                    <tr>
                        <th>Nomor Rekening</th>
                        <td>{{ $pinjaman->virtualAccount ? $pinjaman->virtualAccount->virtual_account_number : 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Nominal Angsuran</th>
                        <td>Rp. {{ number_format($pinjaman->nominal_angsuran, 2) }}</td>
                    </tr>
                    {{-- <tr>
                        <th>Skor SPK</th>
                        <td>{{ $pinjaman->score }}</td>
                    </tr>
                    <tr>
                        <th>Level SPK</th>
                        <td>{{ $pinjaman->level }}</td>
                    </tr> --}}
                </table>
            @endforeach

        </div>
        @if (auth()->check() && auth()->user()->roles == 'manager')
            <a href="{{ route('home.manager') }}" class="btn btn-primary">Kembali</a>
        @endif

        @if (auth()->check() && auth()->user()->roles == 'bendahara')
            <a href="{{ route('bendahara.index') }}" class="btn btn-primary">Kembali</a>
        @endif

        @if (auth()->check() && auth()->user()->roles == 'ketua')
            <a href="{{ route('home-ketua') }}" class="btn btn-primary">Kembali</a>
        @endif

        @if (auth()->check() && auth()->user()->roles == 'admin')
            <a href="{{ route('home-admin') }}" class="btn btn-primary">Kembali</a>
        @endif

    </div>
@endsection
