@extends('layout.master')

<!DOCTYPE html>
<html>

<head>
    <title>Tambah Simpanan</title>
    <!-- Bootstrap and Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<body>
    <div class="container-fluid">

        <div class="row">
            @include('anggota.layout.sidebar')
            <!-- Main Content -->
            <div class="col-md-9 main-content">
                <div class="row justify-content-start">
                    <div class="col-md-10">
                        <div class="card form-container">
                            <div class="form-header">
                                <h2>Tambah Simpanan</h2>
                            </div>
                            <form action="{{ route('tambah-simpanan') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="nik" class="required-field">NIK</label>
                                        <input type="text" class="form-control" id="nik" name="nik" placeholder="Masukkan Nomor NIK" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="name" class="required-field">Nama Anggota</label>
                                        <input type="text" class="form-control" id="name" name="name" placeholder="Masukkan nama anggota" required>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="department_code" class="required-field">Kode Bagian</label>
                                        <select class="form-control" id="department_code" name="department_code" required>
                                            <option value="">Pilih kode bagian</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="loan_number">Nomor Urut</label>
                                        <input type="text" class="form-control" id="loan_number" name="loan_number" value="442" readonly>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="old_department" class="required-field">Bagian Lama</label>
                                        <select class="form-control" id="old_department" name="old_department" required>
                                            <option value="">Pilih kode bagian</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="member_code">Kode Anggota</label>
                                        <input type="text" class="form-control" id="member_code" name="member_code" value="442" readonly>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="transfer_date" class="required-field">Tgl Pindah</label>
                                        <input type="date" class="form-control" id="transfer_date" name="transfer_date" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="description" class="required-field">Wajib</label>
                                        <textarea class="form-control" id="description" name="description" placeholder="Masukkan deskripsi" rows="3" required></textarea>
                                    </div>
                                </div>

                                <div class="submit-section">
                                    <button type="reset" class="btn btn-secondary">Reset</button>
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @include('layout.footer')
            </div>
        </div>
    </div>
</body>

</html>