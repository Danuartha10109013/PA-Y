<!DOCTYPE html>
<html>

<head>
    @include('layout.load_css')
    <title>Data Anggota</title>
</head>

<body>
    <div class="wrapper">
        <div class="main-header">
            <div class="logo-header">
                <a href="index.html" class="logo">
                    <img src="{{asset('home1')}}/assets/img/logo.png" alt="Logo">
                </a>
                <button class="navbar-toggler sidenav-toggler ml-auto" type="button" data-toggle="collapse" data-target="collapse" aria-controls="sidebar" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>
            <div class="wrapper">
                <div class="main-header">
                    <div class="logo-header">
                        <a href="index.html" class="logo">
                            <img src="{{asset('home1')}}/assets/img/logo.png" alt="Logo">
                        </a>
                        <button class="navbar-toggler sidenav-toggler ml-auto" type="button" data-toggle="collapse" data-target="collapse" aria-controls="sidebar" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                    </div>
                    <nav class="navbar navbar-header navbar-expand-lg">
                        <div class="container-fluid">
                            <div class="input-group">
                                <p class="main-text">Laporan Data Registrasi</p>
                                <p class="sub-text">Data Registrasi</p>
                                <div class="d-flex"></div>
                            </div>

                            @include('layout.navbar')
                        </div>

                        <div class="content-wrapper">
                            @include('admin.layout.sidebar')

                            <head>
                                <link rel="stylesheet" href="{{ asset('admin/load.css') }}">
                            </head>

                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Detail Registrasi</h3>
                                </div>

                                <form>
                                    @foreach ($anggota as $data=>$anggota)
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label>Nama Anggota:</label>
                                            <div>{{ $anggota->nama }}</div>
                                        </div>
                                        <div class="form-group">
                                            <label>Tempat Lahir:</label>
                                            <div>{{ $anggota->tempat_lahir }}</div>
                                        </div>
                                        <div class="form-group">
                                            <label>Tanggal Lahir:</label>
                                            <div>{{ $anggota->tgl_lahir }}</div>
                                        </div>
                                        <div class="form-group">
                                            <label>NIK:</label>
                                            <div>{{ $anggota->nik }}</div>
                                        </div>
                                        <div class="form-group">
                                            <label>Email Kantor:</label>
                                            <div>{{ $anggota->email_kantor }}</div>
                                        </div>
                                        <div class="form-group">
                                            <label>No Handphone:</label>
                                            <div>{{ $anggota->no_handphone }}</div>
                                        </div>
                                        <div class="form-group">
                                            <label>Alamat Domisili:</label>
                                            <div>{{ $anggota->alamat_domisili }}</div>
                                        </div>
                                        <div class="form-group">
                                            <label>Alamat KTP:</label>
                                            <div>{{ $anggota->alamat_ktp }}</div>
                                        </div>
                                    </div>
                                    @endforeach
                                    <div class="form-group">
                                        <label>Status Manager:</label>
                                        <div>{{ $anggota->status_manager }}</div>
                                    </div>
                                    <div class="form-group">
                                        <label>Status Ketua:</label>
                                        <div>{{ $anggota->status_ketua }}</div>
                                    </div>
                                    <div class="form-group">
                                        <label>Status Pembayaran:</label>
                                        <div>{{ $anggota->status_manager }}</div>
                                    </div>
                                </form>
                            </div>

                            <!--/.card-body -->
                            <div class="card-footer">
                                <a href="{{ route ('laporan-regis')}}"><button type="button" class="btn btn-primary">Kembali</button></a>
                            </div>
                            </form>
                        </div>