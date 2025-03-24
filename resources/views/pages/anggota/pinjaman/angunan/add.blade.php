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
            <h2>Pinjaman Dengan Angunan</h2>
        </div>

        <form action="{{ route('pengajuan-pinjaman.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
            @csrf
            <div class="form-group">
                <input type="hidden" id="jenis_pinjaman" name="jenis_pinjaman" class="form-control"
                    value="pinjaman_angunan">
            </div>

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
                    @foreach (auth()->user()->virtualAccount as $key => $va)
                        <option value="{{ $va->id }}">{{ $va->nama_bank }} {{ $va->virtual_account_number }}</option>
                    @endforeach
                </select>

            </div>

            <div class="form-group" id="section_rekening_baru" style="display: none;">
                <label for="path">Nama Bank</label>
                <select name="path" id="path" class="form-control">
                    @php
                        $banks = [
                            ['value' => '/bca-virtual-account/v2/payment-code', 'name' => 'BCA'],
                            ['value' => '/mandiri-virtual-account/v2/payment-code', 'name' => 'Bank Mandiri'],
                            ['value' => '/bsm-virtual-account/v2/payment-code', 'name' => 'Bank Syariah Mandiri'],
                            ['value' => '/doku-virtual-account/v2/payment-code', 'name' => 'Doku'],
                            ['value' => '/bri-virtual-account/v2/payment-code', 'name' => 'BRI'],
                            ['value' => '/bni-virtual-account/v2/payment-code', 'name' => 'BNI'],
                        ];
                    @endphp
                    <option value="">-- Pilih Bank --</option>
                    @foreach ($banks as $bank)
                        <option value="{{ $bank['value'] }}">{{ $bank['name'] }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="nominal_angsuran">Nominal Angsuran Perbulan (IDR)</label>
                <input type="text" id="nominal_angsuran_display" class="form-control" readonly>
                <input type="hidden" id="nominal_angsuran" name="nominal_angsuran">
            </div>

            <div class="form-group">
                <label for="jenis_angunan">Jenis Angunan</label>
                <select id="jenis_angungan" name="jenis_angunan" class="form-control" required>
                    <option value="">Jenis Angunan</option>
                    @php
                        $types = [
                            ['value' => 'sertifikat_tanah', 'name' => 'SERTIFIKAT TANAH'],
                            ['value' => 'sertifikat_rumah', 'name' => 'SERTIFIKAT RUMAH'],
                            ['value' => 'bpkb_kendaraan', 'name' => 'BPKB KENDARAAN'],
                            ['value' => 'surat_berharga_lainnya', 'name' => 'SURAT BERHARGA LAINNYA'],
                        ];
                    @endphp
                    @foreach ($types as $type)
                        <option value="{{ $type['value'] }}">{{ $type['name'] }}</option>
                    @endforeach
                </select>
            </div>


            <div class="form-group">
                <label for="image">Upload Dokumen</label>
                <input type="file" id="image" name="image"
                    class="form-control @error('image') is-invalid @enderror"
                    onchange="previewImage(this, 'image-preview')">
                @error('image')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <img id="image-preview" src="" alt="Preview Gambar"
                    style="display: none; max-width: 200px; max-height: 200px; margin-top: 10px; border: 1px solid #ccc; padding: 5px;">
            </div>


            <div class="form-group">
                <label>Preview Gambar</label>
                <div>
                    <img id="image-preview" src="" alt="Preview Gambar"
                        style="display: none; max-width: 200px; max-height: 200px; margin-top: 10px; border: 1px solid #ccc; padding: 5px;">
                </div>
            </div>

            @foreach (['Pembayaran akan otomatis dipotong dari gaji anda setiap bulan selama jangka waktu yang telah ditentukan', 'Saya telah membaca dan memahami syarat-syarat pinjaman yang berlaku'] as $text)
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
