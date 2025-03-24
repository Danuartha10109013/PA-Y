<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Pembayaran</title>
</head>

<body>
    <h2>Konfirmasi Detail Pembayaran</h2>
    <p><strong>Nama:</strong> {{ Auth()->user()->name }}</p>
    <p><strong>Email:</strong> {{ Auth()->user()->email }}</p>
    <p><strong>Jumlah Transfer:</strong> IDR {{ number_format($transferData['amount'], 0, ',', '.') }}</p>
    <p><strong>Bank Pilihan:</strong> {{ strtoupper($transferData['bank']) }}</p>

    <!-- Form untuk melanjutkan proses pembayaran -->
    <form action="{{ route('createTransfer') }}" method="POST">
        @csrf
        <!-- Informasi pelanggan yang disembunyikan (hidden) -->
        <input type="hidden" name="name" value="{{ $transferData['name'] }}">
        <input type="hidden" name="email" value="{{ $transferData['email'] }}">
        <input type="hidden" name="amount" value="{{ $transferData['amount'] }}">
        <input type="hidden" name="bank" value="{{ $transferData['bank'] }}">

        <button type="submit">Konfirmasi dan Lanjutkan Pembayaran</button>
    </form>
</body>

</html>