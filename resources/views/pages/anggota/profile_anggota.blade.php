<html>

<head>
    @include('layout.navbar1')

    <title>Data Anggota</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @include('layout.load1_css')
    <link href="{{ asset('skydash/css/vertical-layout-light/anggota.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <div class="container-scroller">
        <!-- Navbar -->
        <div class="container-fluid page-body-wrapper">
            @include('anggota.layout.sidebar')

            <head>
                <title>
                    Profile Page
                </title>
                <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
                <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&amp;display=swap" rel="stylesheet" />
                <link href="{{ asset('Credit Card UI Design/style.css') }}" rel="stylesheet">
                <style>
                    body {
                        font-family: 'Roboto', sans-serif;
                        background-color: #f5f7fa;
                        margin: 0;
                        padding: 0;
                    }

                    .container {
                        width: 90%;
                        max-width: 1200px;
                        margin: 20px auto;
                        display: grid;
                        grid-template-columns: 1fr 1fr;
                        grid-template-rows: auto auto;
                        gap: 20px;
                    }

                    .card {
                        background-color: #fff;
                        border-radius: 10px;
                        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                        padding: 20px;
                    }

                    .card-header {
                        font-size: 18px;
                        font-weight: 500;
                        margin-bottom: 10px;
                    }

                    .card-content {
                        font-size: 16px;
                    }

                    .profile-card {
                        display: flex;
                        flex-direction: column;
                        align-items: center;
                        text-align: center;
                    }

                    .profile-card img {
                        border-radius: 50%;
                        width: 100px;
                        height: 100px;
                        object-fit: cover;
                        margin-bottom: 10px;
                    }

                    .profile-card .profile-info {
                        width: 100%;
                    }

                    .profile-card .profile-info div {
                        margin-bottom: 10px;
                    }

                    .profile-card .profile-info div span {
                        display: block;
                        font-weight: 500;
                    }

                    .profile-card .profile-info div i {
                        color: green;
                        margin-left: 5px;
                    }

                    .activity-list {
                        list-style: none;
                        padding: 0;
                        margin: 0;
                    }

                    .activity-list li {
                        margin-bottom: 10px;
                    }

                    .activity-list li span {
                        font-weight: 500;
                    }

                    .activity-list li .amount {
                        color: green;
                        font-weight: 700;
                    }

                    .card-type {
                        background: url('https://placehold.co/300x200') no-repeat center center;
                        background-size: cover;
                        color: #fff;
                        padding: 20px;
                        border-radius: 10px;
                        text-align: center;
                        font-size: 20px;
                        font-weight: 700;
                    }

                    .card-type .card-number {
                        margin-top: 20px;
                    }

                    .balance-card {
                        display: flex;
                        justify-content: space-between;
                        align-items: center;
                    }

                    .balance-card .balance {
                        font-size: 24px;
                        font-weight: 700;
                    }

                    .balance-card .label {
                        font-size: 16px;
                        font-weight: 500;
                    }

                    .card-type-container {
                        grid-column: 1 / 2;
                        grid-row: 1 / 2;
                    }

                    .savings-card-container {
                        grid-column: 2 / 3;
                        grid-row: 1 / 2;
                    }

                    .loan-card-container {
                        grid-column: 2 / 3;
                        grid-row: 2 / 3;
                    }

                    .profile-card-container {
                        grid-column: 1 / 2;
                        grid-row: 2 / 3;
                    }

                    .activity-card-container {
                        grid-column: 1 / 3;
                        grid-row: 3 / 4;
                    }
                </style>
            </head>

            <body>
                <div class="container">
                    <div class="card-type-container">
                        <header>
                            <div class="logo">
                                <img src="{{ asset('Credit Card UI Design') }}/images/logo.png" alt="" />
                                <h5>Master Card</h5>
                            </div>
                            <img src="{{ asset('Credit Card UI Design') }}/images/chip.png" alt="" class="chip" />
                        </header>

                        <div class="card-details">
                            <div class="name-number">
                                <h6>Card Number</h6>
                                <h5 class="number">8050 5040 2030 3020</h5>
                                <h5 class="name">Prem Kumar Shahi</h5>
                            </div>
                        </div>
                    </div>

                    <div class="card savings-card-container">
                        <div class="card-header">Simpanan</div>
                        <div class="card-content balance-card">
                            <div>
                                <div class="balance">Rp 2.500.000</div>
                                <div class="label">Saldo Simpanan</div>
                            </div>
                            <div>
                                <div class="balance">Rp 1.000.000</div>
                                <div class="label">Keluar</div>
                            </div>
                        </div>
                    </div>

                    <div class="card loan-card-container">
                        <div class="card-header">Pinjaman</div>
                        <div class="card-content balance-card">
                            <div>
                                <div class="balance">Rp 1.000.000</div>
                                <div class="label">Total Pembayaran</div>
                            </div>
                            <div>
                                <div class="balance">Rp 50.000.000</div>
                                <div class="label">Total Pinjaman</div>
                            </div>
                        </div>
                    </div>

                    <div class="card profile-card profile-card-container">
                        <img alt="Profile Picture" height="100" src="https://storage.googleapis.com/a1aa/image/4YW0dCidLvogK96SYBX7fkJ0nfTVuTzrl2HpaYOfEuH6fonOB.jpg" width="100" />
                        <div class="profile-info">
                            <div>
                                <span>Nama Anggota</span>
                                Indra Permana
                            </div>
                            <div>
                                <span>Status</span>
                                <i class="fas fa-check-circle"></i>
                                Aktif
                            </div>
                            <div>
                                <span>Posisi</span>
                                Pengurus
                            </div>
                            <div>
                                <span>Kantor</span>
                                Wilayah Yogyakarta
                            </div>
                            <div>
                                <span>Jenis Kelamin</span>
                                Laki-Laki
                            </div>
                            <div>
                                <span>No. Telepon</span>
                                0852 2122 8889
                            </div>
                            <div>
                                <span>Email</span>
                                indra.permana@ph.co.id
                            </div>
                            <div>
                                <span>Alamat</span>
                                Kantor
                            </div>
                            <div>
                                <span>Nomor Rekening</span>
                                0881 8888 9870 1120
                            </div>
                        </div>
                    </div>

                    <div class="card activity-card-container">
                        <div class="card-header">Aktifitas Terbaru</div>
                        <ul class="activity-list">
                            <li>
                                <span>1. Pendaftaran Anggota Baru</span>
                                <br />
                                Indra Permana telah mendaftar sebagai anggota baru Koperasi Konsumen Kertiasa melalui aplikasi, yang telah diverifikasi, dan mendapatkan akses yang disediakan secara digital.
                            </li>
                            <li>
                                <span>2. Pembayaran Iuran Anggota</span>
                                <br />
                                Indra Permana telah membuat pembayaran iuran wajib anggota baru sebesar <span class="amount">Rp 505.000</span>.
                            </li>
                            <li>
                                <span>3. Pengajuan Pinjaman Online</span>
                                <br />
                                Indra Permana telah mengajukan pinjaman secara online melalui aplikasi, sebesar <span class="amount">Rp 50.000.000</span>.
                            </li>
                            <li>
                                <span>4. Belanja di Toko Koperasi</span>
                                <br />
                                Indra Permana telah berbelanja online di Toko Koperasi, sebesar <span class="amount">Rp 100.000</span>.
                            </li>
                            <li>
                                <span>5. Melakukan Pembayaran Pinjaman</span>
                                <br />
                                Indra Permana telah membuat pembayaran cicilan pinjaman, sebesar <span class="amount">Rp 1.000.000</span>.
                            </li>
                        </ul>
                    </div>
                </div>
            </body>
        </div>
    </div>

</html>