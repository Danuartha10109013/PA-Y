@extends('auth.dashboard-layout')

@section('content')
<div class="content-background">
    <!-- Error Display -->
    @if($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Main Panel -->
    <!-- Header -->
    <title>Metode Iuran</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            margin-left: 250px
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
        }
        .header button {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
        }
        .title {
            text-align: center;
            font-size: 23px;
            font-weight: bold;
            margin-bottom: 16px;
        }
        .step-indicator {
            display: flex;
            justify-content: space-around;
            align-items: center;
            margin-bottom: 16px;
        }
        .step-indicator .circle {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background-color: grey;
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
            font-weight: bold;
        }
        .step-indicator .line {
            flex: 1;
            height: 2px;
            background-color: green;
            margin: 0 8px;
        }
        .step-indicator .active {
            background-color: green;
        }
        .field {
            margin-bottom: 16px;
        }
        .field label {
            display: block;
            color: #6c757d;
            margin-bottom: 8px;
        }
        .field input, .field select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            background-color: #f8f9fa;
        }
       .info-box {
    background-color: #ffffff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
    margin-bottom: 20px;
}

.info-box h3 {
    font-size: 18px;
    font-weight: 700;
    color: #333;
    margin-bottom: 16px;
    text-align: center;
}

.info-item {
    display: flex;
    flex-direction: column; /* Ubah layout agar label dan value tampil vertikal */
    margin-bottom: 12px;
    border-bottom: 1px solid #ddd;
    padding-bottom: 8px;
}

.info-item .label {
    font-size: 14px;
    font-weight: bold;
    color: #555;

}

.info-item .value {
    font-size: 14px;
    color: #333;
    text-align: left;
    word-break: break-word;
}

        .agreement {
            display: flex;
            align-items: flex-start;
            margin-bottom: 16px;
        }
        .agreement input {
            margin-right: 8px;
        }
        .agreement p {
            color: #6c757d;
        }
        .button {
            display: block;
            width: 100%;
            padding: 12px;
            background-color: grey;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
             cursor: not-allowed; /* Cursor tetap sama */
            transition: none; /* Pastikan tidak ada transisi saat hover */
        }

        .button:hover {
            background-color: grey; /* Tidak ada perubahan warna saat hover */
            color: white; /* Warna tetap */
             cursor: not-allowed; /* Cursor tetap sama */

        }

        .button.active {
            background-color: green;
            cursor: pointer;
        }

        .popup-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        .popup-content {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }

        .popup-button {
            background-color: #4caf50;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }

        .popup-button:hover {
            background-color: #45a049;
        }
        .close-button {
    position: absolute; /* Memastikan tombol bisa diposisikan secara absolut */
    top: 10px; /* Jarak dari bagian atas */
    right: 10px; /* Jarak dari bagian kanan */
    background: transparent; /* Menghapus latar belakang tombol */
    border: none; /* Menghapus border tombol */
    font-size: 24px; /* Ukuran teks */
    cursor: pointer; /* Mengubah kursor menjadi pointer */
}

.close-button:hover {
    color: red; /* Mengubah warna tombol saat dihover */
}

    </style>

    <div class="title">Metode Iuran</div>
    <button type="button" class="close-button">&times;</button>
    <form action="{{ route('register-verifikasi') }}" method="POST">
        @csrf
        <div class="info-box">
    <h3>Informasi Anggota</h3>
    <div class="info-item">
        <span class="label">Email Kantor:</span>
        <span class="value">{{ $data['email_kantor'] }}</span>
        <input type="hidden" name="email_kantor" value="{{ $data['email_kantor'] }}" readonly>
    </div>
    <div class="info-item">
        <span class="label">Nama:</span>
        <span class="value">{{ $data['nama'] }}</span>
        <input type="hidden" name="nama" value="{{ $data['nama'] }}" readonly>
    </div>
    <div class="info-item">
        <span class="label">Alamat Domisili:</span>
        <span class="value">{{ $data['alamat_domisili'] }}</span>
        <input type="hidden" name="alamat_domisili" value="{{ $data['alamat_domisili'] }}" readonly>
    </div>
    <div class="info-item">
        <span class="label">Tempat Lahir:</span>
        <span class="value">{{ $data['tempat_lahir'] }}</span>
        <input type="hidden" name="tempat_lahir" value="{{ $data['tempat_lahir'] }}" readonly>
    </div>
    <div class="info-item">
        <span class="label">Tanggal Lahir:</span>
        <span class="value">{{ $data['tgl_lahir'] }}</span>
        <input type="hidden" name="tgl_lahir" value="{{ $data['tgl_lahir'] }}" readonly>
    </div>
    <div class="info-item">
        <span class="label">Alamat KTP:</span>
        <span class="value">{{ $data['alamat_ktp'] }}</span>
        <input type="hidden" name="alamat_ktp" value="{{ $data['alamat_ktp'] }}" readonly>

    </div>
    <div class="info-item">
        <span class="label">NIK:</span>
        <span class="value">{{ $data['nik'] }}</span>
        <input type="hidden" name="nik" value="{{ $data['nik'] }}" readonly>
    </div>
    <div class="info-item">
        <span class="label">No Handphone:</span>
        <span class="value">{{ $data['no_handphone'] }}</span>
        <input type="hidden" name="no_handphone" value="{{ $data['no_handphone'] }}" readonly>
    </div>
</div>


        <div class="field">
            <label for="simpanan_pokok">Simpanan Pokok</label>
            <input type="text" name="simpanan_pokok" value="1000000" readonly>
        </div>
        <div class="info-box">
            <p><strong>Metode Pembayaran Simpanan Pokok</strong></p>
            <p>Syarat & Ketentuan:</p>
            <ol>
                <li>Pembayaran Manual</li>
                <li>Potong Gaji Otomatis</li>
            </ol>
            <a href="#">Lihat Selengkapnya</a>
        </div>
        <div class="field">
            <label for="metode_pembayaran">Pilih Metode Pembayaran</label>
            <select name="metode_pembayaran" required>
                <option>Pembayaran Manual</option>
                <option>Potong Gaji Otomatis</option>
            </select>
        </div>

        <div class="field">
            <label for="simpanan_wajib">Simpanan Wajib</label>
            <input type="text" name="simpanan_wajib" value="50000" readonly>
        </div>
        <div class="info-box">
            <p><strong>Metode Pembayaran Simpanan Wajib</strong></p>
            <p>Syarat & Ketentuan:</p>
            <p>Simpanan Wajib akan dibayarkan melalui potongan otomatis pada gaji pegawai setiap bulannya.</p>
        </div>
        <div class="agreement">
            <input type="checkbox" id="agreement" onchange="toggleButton()">
            <p>Saya sanggup memenuhi kewajiban sebagai anggota dan bersedia memenuhi AD/ART dan segala aturan yang berlaku di Koperasi Karlisna Yogyakarta.</p>
        </div>

        <button class="button" id="submitButton" type="submit">Kirim Data</button>

    </form>

    <div id="popup" class="popup-overlay" style="display: none;">
    <div class="popup-content">
        <p>Data berhasil dikirim! Klik OK untuk menuju dashboard.</p>
        <button id="popup-ok" class="popup-button">OK</button>
    </div>
</div>


    <script>
        function toggleButton() {
            const checkbox = document.getElementById('agreement');
            const button = document.getElementById('submitButton');
            if (checkbox.checked) {
                button.classList.add('active');
                button.disabled = false;
                button.style.cursor = "pointer";
            } else {
                button.classList.remove('active');
                button.disabled = true;
            }
        }
        const closeButton = document.querySelector('.close-button');
            if (closeButton) {
                closeButton.addEventListener('click', function() {
                    window.location.href = "{{ route('landingpage') }}";
                });
            }

            // NIK Validation
            const nikField = document.querySelector('input[name="nik"]');
            if (nikField) {
                nikField.addEventListener('input', function() {
                    this.value = this.value.replace(/[^0-9]/g, '').slice(0, 16);
                });
            }



    </script>
</div>
@endsection

