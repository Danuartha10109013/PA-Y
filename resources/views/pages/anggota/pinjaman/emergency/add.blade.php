@extends('layouts.dashboard-layout')
@section('title', $title)
@section('content')
    <div class="content-background col-md-6">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="header d-flex align-items-center mb-4">
            <i class="fas fa-arrow-left mr-3" style="cursor: pointer;" onclick="goBackToHome(this)"
                data-roles="{{ auth()->user()->roles }}"></i>
            <h2>Pinjaman Emergency</h2>
        </div>

        <form action="{{ route('pengajuan-pinjaman.store') }}" method="POST" autocomplete="off" enctype="multipart/form-data">
            @csrf

            <input type="hidden" id="jenis_pinjaman" name="jenis_pinjaman" value="pinjaman_emergency">




            <div class="form-group">
                <label for="amount">Nominal Pinjaman (IDR)</label>
                <input type="text" id="amount" name="formatted_amount" class="form-control" required
                    placeholder="Masukan Nominal" oninput="formatIDR(this, 'raw_amount')">
                <input type="hidden" id="raw_amount" name="amount">
            </div>


            <div class="form-group">
                <label for="jangka_waktu">Jangka Waktu Peminjaman</label>
                <select id="jangka_waktu" name="jangka_waktu" class="form-control" onchange="calculateAngsuran()" required>
                    <option value="">Pilih tenor</option>
                    @foreach ([3, 6, 9, 12, 18, 24] as $tenor)
                        <option value="{{ $tenor }}">{{ $tenor }} Bulan</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="keterangan">Tujuan Pinjaman</label>
                <input type="textarea" id="keterangan" name="keterangan" class="form-control" required
                    placeholder="Masukan Tujuan">
            </div>

            <div class="form-group">
                <label for="virtual_account">Pilih Rekening</label>
                <select id="virtual_account" name="virtual_account" class="form-control" required
                    onchange="showRekeningFields()">
                    <option value="pilih">-- Pilih Rekening --</option>
                    {{-- <option value="0">-- Pilih Rekening Baru --</option> --}}
                    @foreach (auth()->user()->virtualAccount as $va)
                        <option value="{{ $va->id }}">{{ $va->nama_bank }} {{ $va->virtual_account_number }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group" id="section_rekening_baru" style="display: none;">
                <label for="path">Nama Bank</label>
                <select name="path" id="path" class="form-control">
                    <option value="">-- Pilih Bank --</option>
                    @foreach ([
            '/bca-virtual-account/v2/payment-code' => 'BCA',
            '/mandiri-virtual-account/v2/payment-code' => 'Bank Mandiri',
            '/bsm-virtual-account/v2/payment-code' => 'Bank Syariah Mandiri',
            '/doku-virtual-account/v2/payment-code' => 'Doku',
            '/bri-virtual-account/v2/payment-code' => 'BRI',
            '/bni-virtual-account/v2/payment-code' => 'BNI',
        ] as $value => $name)
                        <option value="{{ $value }}">{{ $name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="nominal_angsuran">Nominal Angsuran Perbulan (IDR)</label>
                <input type="text" id="nominal_angsuran_display" class="form-control" readonly>
                <input type="hidden" id="nominal_angsuran" name="nominal_angsuran">
            </div>

            @foreach (['Pembayaran akan otomatis dipotong dari gaji anda setiap bulan selama jangka waktu yang telah ditentukan', 'Saya telah membaca dan memahami syarat-syarat pinjaman yang berlaku', 'Saya menyetujui bahwa Pinjaman Emergency akan beralih menjadi Pinjaman Reguler apabila jangka waktu 1 sampai dengan 3 bulan pinjaman belum dibayarkan lunas.'] as $text)
                <div class="form-check form-check-flat form-check-info">
                    <label class="form-check-label">
                        <input type="checkbox" class="form-check-input" required>
                        {{ $text }}
                    </label>
                </div>
            @endforeach

            <div class="text-center">
                <button type="submit" class="btn btn-primary mt-4">LANJUTKAN</button>
            </div>
        </form>
    </div>

    <script>
        function previewImage(input, previewId) {
            const preview = document.getElementById(previewId);
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(input.files[0]);
            } else {
                preview.style.display = 'none';
                preview.src = '';
            }
        }
    </script>

    <x-script-anggota />
@endsection
