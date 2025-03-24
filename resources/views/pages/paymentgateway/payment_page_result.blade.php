@extends('layouts.dashboard-layout')
@section('content')

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Payment Result</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <style>
            body {
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 0;
                display: flex;
                justify-content: flex-start;
                align-items: flex-start;
                height: 100vh;
                background-color: #f5f5f5;
            }

            .container {
                margin-left: 260px;
                /* To create space for the sidebar */
                width: calc(100% - 260px);
                /* Adjust content container width */
                background-color: #ffffff;
                padding: 40px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                border-radius: 10px;
                text-align: center;
                max-width: 100%;
                /* Ensure the container takes full width */
            }

            .title {
                font-size: 24px;
                font-weight: bold;
                margin-bottom: 10px;
            }

            .subtitle {
                font-size: 16px;
                color: #888888;
                margin-bottom: 20px;
            }

            .transaction-number {
                font-size: 20px;
                font-weight: bold;
                margin-bottom: 10px;
            }

            .label {
                font-size: 16px;
                color: #888888;
                margin-bottom: 10px;
            }

            .amount {
                font-size: 24px;
                font-weight: bold;
                color: #00b300;
                border: 1px solid #00b300;
                border-radius: 5px;
                padding: 15px;
                margin-bottom: 20px;
            }

            .bank-info {
                font-size: 16px;
                color: #888888;
                margin-bottom: 10px;
            }

            .account-number {
                font-size: 18px;
                font-weight: bold;
                border: 1px solid #cccccc;
                border-radius: 5px;
                padding: 15px;
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 20px;
            }

            .account-number i {
                font-size: 18px;
                color: #888888;
            }

            .instructions {
                font-size: 16px;
                color: #888888;
                border-top: 1px solid #eeeeee;
                padding: 15px 0;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }

            .instructions i {
                font-size: 18px;
                color: #888888;
            }

            .ok-button {
                background-color: #00b300;
                color: #ffffff;
                font-size: 18px;
                font-weight: bold;
                border: none;
                border-radius: 5px;
                padding: 15px;
                width: 100%;
                cursor: pointer;
            }
        </style>
    </head>

    <div class="container mx-auto mt-20">

        <div class="title">Simpanan Sukarela</div>
        <div class="subtitle">Pengajuan Simpanan berhasil dilakukan dengan nomor transaksi simpanan</div>
        <div class="transaction-number">S03 - 23082024001</div>
        <div class="label">Nominal Simpanan</div>
        <div class="amount"><strong>Rp. {{ number_format($amount, 0, ',', '.') }}</strong></div>

        <!-- Informasi Bank -->
        <div class="bank-info" style="text-align: center; font-weight: bold;">Bank yang dipilih akan muncul di sini</div>

        <div class="bank-info">No. Rek/Virtual Account</div>
        <div class="account-number">
            {{ $virtualAccount }}
            <i class="fas fa-copy"></i>
        </div>
        <div class="instructions">
            Petunjuk Transfer Mbanking
            <i class="fas fa-chevron-down"></i>
        </div>
        <div class="instructions">
            Petunjuk Transfer iBanking
            <i class="fas fa-chevron-down"></i>
        </div>
        <div class="instructions">
            Petunjuk Transfer ATM
            <i class="fas fa-chevron-down"></i>
        </div>
        <form action="{{ route('home-anggota') }}" method="GET">
            <button type="submit" class="ok-button">OK</button>
        </form>

    </div>

    <script>
        // Data mapping antara value DOKU, nama bank, dan nomor virtual account
        const bankData = {
            "/bri-virtual-account/v2/payment-code": {
                name: "BANK BRI",
                virtualAccount: "1900800000122169"
            },
            "/bni-virtual-account/v2/payment-code": {
                name: "BANK BNI",
                virtualAccount: "1900800000123456"
            },
            "/bca-virtual-account/v2/payment-code": {
                name: "BANK BCA",
                virtualAccount: "1900800000127890"
            },
            "/mandiri-virtual-account/v2/payment-code": {
                name: "BANK MANDIRI",
                virtualAccount: "84308703"
            }
        };

        // Event listener untuk perubahan dropdown
        document.getElementById('path').addEventListener('change', function() {
            // Ambil elemen yang akan diperbarui
            let bankInfo = document.querySelector('.bank-info');
            let virtualAccountInput = document.getElementById('virtualAccount');

            // Ambil value dari dropdown yang dipilih
            let selectedValue = this.value;

            // Cek apakah value ada di mapping data
            if (bankData[selectedValue]) {
                // Update informasi bank dan virtual account
                bankInfo.textContent = bankData[selectedValue].name;
                virtualAccountInput.value = bankData[selectedValue].virtualAccount;
            } else {
                // Jika value tidak ditemukan di mapping, tampilkan default
                bankInfo.textContent = "Bank tidak diketahui";
                virtualAccountInput.value = "";
            }
        });

        // Inisialisasi tampilan awal saat halaman dimuat
        document.addEventListener('DOMContentLoaded', function() {
            let selectElement = document.getElementById('path');
            let bankInfo = document.querySelector('.bank-info');
            let virtualAccountInput = document.getElementById('virtualAccount');

            // Ambil nilai awal
            let initialValue = selectElement.value;

            // Set nilai awal berdasarkan mapping data
            if (bankData[initialValue]) {
                bankInfo.textContent = bankData[initialValue].name;
                virtualAccountInput.value = bankData[initialValue].virtualAccount;
            } else {
                bankInfo.textContent = "Bank tidak diketahui";
                virtualAccountInput.value = "";
            }
        });
    </script>
@endsection
