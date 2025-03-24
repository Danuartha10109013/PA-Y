<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redirecting to Payment</title>
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            // Ambil URL pembayaran dari backend
            var paymentUrl = "{{ $paymentUrl }}";

            // Redirect pengguna ke halaman pembayaran setelah 3 detik (opsional)
            setTimeout(function() {
                window.location.href = paymentUrl;
            }, 500); // 3 detik delay untuk user experience (opsional)
        });
    </script>
</head>

<body>
    <h2>Redirecting to Payment...</h2>
    <p>You are being redirected to the payment page. Please wait.</p>

    <!-- Jika pengguna tidak ter-redirect otomatis, bisa klik link di bawah -->
    <p>If you are not redirected automatically, <a href="{{ $paymentUrl }}">click here</a>.</p>
</body>

</html>