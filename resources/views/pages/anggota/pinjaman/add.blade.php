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
            <h2>Pengajuan Pinjaman</h2>
        </div>

        <form action="{{ route('pengajuan-pinjaman.store') }}" method="POST" autocomplete="off" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="amount">Nominal Pinjaman (IDR)</label>
                <input type="text" id="amount" name="formatted_amount" class="form-control" required
                    placeholder="Masukan Nominal" oninput="formatIDR(this, 'raw_amount')">
                <input type="hidden" id="raw_amount" name="amount">
            </div>

            <div class="form-group">
                <label for="bank">Jangka Waktu Peminjaman</label>
                <select id="tenor_id" name="tenor_id" class="form-control" required>
                    <option value="">Pilih tenor</option>
                    @foreach ($tenors as $tenor)
                        <option value="{{ $tenor->id }}">{{ $tenor->tenor }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="keterangan">Tujuan Pinjaman</label>
                <input type="textarea" id="keterangan" name="keterangan" class="form-control" required
                    placeholder="Masukan Tujuan">
            </div>

            <div class="form-group">
                <label for="jenis_pinjaman">Jenis Pinjaman</label>
                <select id="jenis_pinjaman" name="jenis_pinjaman" class="form-control" required onchange="toggleFields()">
                    <option value="">-- Pilih Jenis Pinjaman --</option>
                    <option value="pinjaman_emergency">Pinjaman Emergency</option>
                    <option value="pinjaman_angunan">Pinjaman Angunan</option>
                    <option value="pinjaman_non_angunan">Pinjaman Non Angunan</option>
                </select>
            </div>

            <div class="form-group" id="jenis_angunan_group">
                <label for="jenis_angunan">Jenis Angunan</label>
                <select id="jenis_angunan" name="jenis_angunan" class="form-control">
                    <option value="">Jenis Angunan</option>
                    @foreach ($angunans as $item)
                        <option value="{{ $item }}" {{ old('jenis_angunan') == $item ? 'selected' : '' }}>
                            {{ ucfirst($item) }}
                        </option>
                    @endforeach
                </select>
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

            <div class="form-group" id="image_upload_group">
                <label for="image">Upload Dokumen</label>
                <input type="file" id="image" name="image"
                    class="form-control @error('image') is-invalid @enderror" onchange="previewImage()">
                @error('image')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="form-group" id="preview_image">
                <label>Preview Gambar</label>
                <div>
                    <img id="image-preview" src="" alt="Preview Gambar"
                        style="display: none; max-width: 200px; max-height: 200px; margin-top: 10px; border: 1px solid #ccc; padding: 5px;">
                </div>
            </div>

            <div class="form-group">
                <label for="nominal_angsuran">Nominal Angsuran Perbulan (IDR)</label>
                <input type="text" id="nominal_angsuran" name="formatted_nominal_angsuran" class="form-control" required
                    placeholder="Masukan Nominal" oninput="formatIDR(this, 'raw_nominal_angsuran')">
                <input type="hidden" id="raw_nominal_angsuran" name="nominal_angsuran">
            </div>

            <div class="form-check form-check-flat form-check-info">
                <label class="form-check-label">
                    <input type="checkbox" class="form-check-input" required>
                    Pembayaran akan otomatis dipotong dari gaji anda setiap bulan
                    selama jangka waktu yang telah ditentukan
                </label>
            </div>

            <div class="form-check form-check-flat form-check-info">
                <label class="form-check-label">
                    <input type="checkbox" class="form-check-input" required>
                    Saya telah membaca dan memahami syarat-syarat pinjaman yang
                    berlaku
                </label>
            </div>

            <div class="form-check form-check-flat form-check-info">
                <label class="form-check-label">
                    <input type="checkbox" class="form-check-input" required>
                    Saya menyetujui bahwa Pinjaman Emergency akan beralih menjadi
                    Pinjaman Reguler apabila jangka waktu 1 sampai dengan 3 bulan pinjaman belum dibayarkan lunas.
                </label>
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-primary mt-4">LANJUTKAN</button>
            </div>
        </form>
    </div>

    <script>
        // Function to toggle the visibility of input fields
        function toggleFields() {
            const jenisPinjaman = document.getElementById('jenis_pinjaman').value;
            const jenisAngunanGroup = document.getElementById('jenis_angunan_group');
            const imageUploadGroup = document.getElementById('image_upload_group');
            const previewImage = document.getElementById('preview_image');

            // Reset display styles
            jenisAngunanGroup.style.display = 'none';
            imageUploadGroup.style.display = 'none';
            previewImage.style.display = 'none';

            // Show fields only for "pinjaman_angunan"
            if (jenisPinjaman === 'pinjaman_angunan') {
                jenisAngunanGroup.style.display = 'block';
                imageUploadGroup.style.display = 'block';
                previewImage.style.display = 'block';
            }
        }

        function previewImage() {
            const fileInput = document.getElementById('image');
            const preview = document.getElementById('image-preview');

            if (fileInput.files && fileInput.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }
                reader.readAsDataURL(fileInput.files[0]);
            } else {
                preview.style.display = 'none';
            }
        }

        // Initial setup
        document.addEventListener('DOMContentLoaded', toggleFields);

        // Function to go back to the home page
        function goBackToHome(element) {
            const roles = element.getAttribute('data-roles');
            let route = '';

            if (roles === 'anggota') {
                route = "{{ route('home-anggota') }}";
            } else if (roles === 'admin') {
                route = "{{ route('home-admin') }}";
            }

            if (route) {
                window.location.href = route.replace(/&amp;/g, '&'); // Pastikan query string diubah ke format valid
            }
        }


        function showRekeningFields() {
            const virtualAccount = document.getElementById("virtual_account").value;
            const sectionRekeningBaru = document.getElementById("section_rekening_baru");

            if (virtualAccount === "0") {
                sectionRekeningBaru.style.display = "block";
            } else {
                sectionRekeningBaru.style.display = "none";

                // Reset nilai di dalam elemen rekening baru
                const inputs = sectionRekeningBaru.querySelectorAll("input, select");
                inputs.forEach(input => input.value = '');
            }
        }


        function validateForm() {
            const virtualAccount = document.getElementById("virtual_account").value;
            const rekeningBaru = document.getElementById("path").value;

            if (virtualAccount === "0" && !rekeningBaru) {
                alert("Silakan pilih atau masukkan data rekening baru.");
                return false;
            }

            return true;
        }

        document.getElementById("virtual_account").addEventListener("change", showRekeningFields);

        //Preview Gambar
        document.addEventListener('DOMContentLoaded', function() {
            const inputImage = document.getElementById('image');
            const imagePreview = document.getElementById('image-preview');

            inputImage.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        imagePreview.src = e.target.result;
                        imagePreview.style.display = 'block';
                    };

                    reader.readAsDataURL(this.files[0]);
                } else {
                    imagePreview.src = '';
                    imagePreview.style.display = 'none';
                }
            });
        });

        @include('layouts.partials.script-input')
    </script>
@endsection
