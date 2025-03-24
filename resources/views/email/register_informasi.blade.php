<!DOCTYPE html>
<html>

<head>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .header {
            background-color: #26BCF2;
            color: #fff;
            padding: 20px;
            border-top-left-radius: 5px;
            border-top-right-radius: 5px;
            text-align: center;
        }

        .header .logo {
            display: block;
            margin: 0 auto;
            width: 150px;
            height: auto;
        }

        .content {
            padding: 20px;
        }

        .content h2 {
            margin-top: 0;
        }

        .content p {
            margin-bottom: 15px;
        }

        .button {
            display: block;
            width: 200px;
            padding: 10px 20px;
            margin: 20px auto;
            background-color: #26BCF2;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .footer {
            padding: 10px;
            text-align: center;
            font-size: 14px;
            color: #666;
        }

        .footer a {
            color: #007bff;
            text-decoration: none;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <img src="{{ asset('skydash/images/logo.png') }}" alt="Logo" class="logo">
        </div>

        <div class="content">
            <h2>Dear PLNers!</h2>
            <p>Hai {{$nama}} ! <span class="highlight"></span></p>
            <p>Selamat, pendaftaran Anggota Koperasi Karlisna Anda telah disetujui.</p>
            <p>Segera lakukan proses pembayaran Iuran Pertama agar Anda dapat menggunakan fitur-fitur Koperasi Karlisna di gadget Anda.</p>
            <p>Berikut adalah user yang dapat digunakan untuk melakukan proses Login:</p>
            <p>
                Username : {{$email_kantor}} <br>
                Password : {{$password}}<br>
                Selanjutnya, Anda dapat melakukan aktivasi akun melalui tautan: <a href="{{ route('landingpage') }}" class="link">Link Tautan</a>
            </p>

            <p>Untuk pertanyaan lebih lanjut dapat menghubungi Helpdesk Koperasi Karlisna.</p>
            <p>Demikian kami sampaikan, atas perhatian dan kerjasamanya kami ucapkan terima kasih.</p>
            <p>Hormat Kami,</p>
            <p>Tim <span class="highlight">Koperasi Konsumen Karlisna</span></p>
        </div>

        <div class="header">
        </div>

        <div class="footer">
            <div class="container d-flex justify-content-between align-items-center text-center mt-2">
                <p class="mb-0 flex-grow-1">
                    © 2024 All rights reserved. Koperasi Konsumen Karlisna PLN Yogyakarta powered by PT PLN Icon Plus
                </p>
                <div>
                    <a href="" class="text-dark mx-2"><i class="bi bi-facebook"></i></a>
                    <a href="" class="text-dark mx-2"><i class="bi bi-instagram"></i></a>
                    <a href="" class="text-dark mx-2"><i class="bi bi-twitter"></i></a>
                    <a href="" class="text-dark mx-2"><i class="bi bi-youtube"></i></a>
                </div>
            </div>
        </div>
    </div>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
</body>

</html>