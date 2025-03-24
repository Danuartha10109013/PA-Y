@if (auth()->user()->roles == 'bendahara')
    @extends('layouts.dashboard-layout')
    @section('title', $title)
    @section('content')
        <div class="content-background" style="background: white">

            <a href="{{ route('check-status.create') }}">
                <button class="btn btn-primary my-4">Cek Pembayaran</button>
            </a>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead style="background-color: #EEEEEE;">
                        <tr>
                            <th>No</th>
                            <th>Invoice Number</th>
                            <th>Amount</th>
                            <th>Bank</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transactions as $key => $transaction)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $transaction->nomor_pinjaman }}</td>
                                <td>Rp. {{ number_format($transaction->amount, 2) }}</td>
                                <td>{{ $transaction->bank }}</td>
                                <td>
                                    @if ($transaction->status === 'SUCCESS')
                                        <label class="badge badge-border-success">{{ $transaction->status }}</label>
                                    @else
                                        <label class="badge badge-border-warning">{{ $transaction->status }}</label>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endsection
@endif
