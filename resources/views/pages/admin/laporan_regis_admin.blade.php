@extends('layouts.dashboard-layout')
@section('title', $title)
@section('content')
<div class="content-background">
    <div class="search-bar">
        <input type="text" placeholder="Search" class="form-control mr-2" style="width: 200px;" />
        <div class="icon-group">
            <button class="icon-btn" title="Print">
                <i class="fa fa-print"></i>
            </button>
            <button class="icon-btn" title="Upload">
                <i class="fa fa-upload"></i>
            </button>
            <button class="icon-btn" title="Download">
                <i class="fa fa-download"></i>
            </button>
            <button class="icon-btn" title="Load">
                <i class="fa fa-circle-notch"></i>
            </button>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>

                    <th>No</th>
                    <th>Nama</th>
                    <th>NIK</th>
                    <th>Email Kantor</th>
                    <th>No Handphone</th>
                    <th>Status</th>
                    <th>Status Pembayaran</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($anggota as $data)
                <tr>
                    </td>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $data->nama }}</td>
                    <td>{{ $data->nik }}</td>
                    <td>{{ $data->email_kantor }}</td>
                    <td>{{ $data->no_handphone }}</td>
                    <td>
                        @if ($data->status_manager == 'Ditolak')
                        <span class="badge badge-border-danger">ditolak manager</span>
                        @elseif($data->status_ketua == 'Diterima')
                        <span class="badge badge-border-success">diterima ketua</span>
                        @elseif($data->status_ketua == 'Ditolak')
                        <span class="badge badge-border-danger">ditolak ketua</span>
                        @elseif($data->status_manager == 'Pengajuan')
                        <span class="badge badge-border-warning">pengajuan manager</span>
                        @elseif($data->status_ketua == 'Pengajuan')
                        <span class="badge badge-border-warning">pengajuan ketua</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge badge-border-warning">{{ $data->status_pembayaran }}</span>
                    </td>
                    <td>
                        <!-- Tombol Edit berubah menjadi tombol Detail dengan ikon mata -->
                        <a class="action-icons" data-toggle="modal"
                            data-target="#exampleModalLong{{ $data->id }}">
                            <i class="fas fa-eye"></i>
                        </a>
                        <!-- Tombol Edit berubah menjadi tombol Detail dengan ikon mata -->
                        <a class="action-icons" data-toggle="modal" data-target="#editModal{{ $data->id }}">
                            <i class="fas fa-edit edit"></i>
                        </a>
                        <a href="#" class="action-icons delete-btn" data-id="{{ $data->id }}">
                            <i class="fas fa-trash delete"></i>
                        </a>

                        <!-- Modal Edit Anggota -->
                        <div class="modal fade" id="editModal{{ $data->id }}" tabindex="-1" role="dialog" aria-labelledby="editModalTitle" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Anggota</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form action="{{ route('anggota.update', $data->id) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label for="nama">Nama Anggota:</label>
                                                <input type="text" class="form-control" name="nama" value="{{ $data->nama }}" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="tempat_lahir">Tempat Lahir:</label>
                                                <input type="text" class="form-control" name="tempat_lahir" value="{{ $data->tempat_lahir }}" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="tgl_lahir">Tanggal Lahir:</label>
                                                <input type="date" class="form-control" name="tgl_lahir" value="{{ $data->tgl_lahir }}" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="nik">NIK:</label>
                                                <input type="text" class="form-control" name="nik" value="{{ $data->nik }}" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="email_kantor">Email Kantor:</label>
                                                <input type="email" class="form-control" name="email_kantor" value="{{ $data->email_kantor }}" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="no_handphone">No Handphone:</label>
                                                <input type="text" class="form-control" name="no_handphone" value="{{ $data->no_handphone }}" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="alamat_domisili">Alamat Domisili:</label>
                                                <input type="text" class="form-control" name="alamat_domisili" value="{{ $data->alamat_domisili }}" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="alamat_ktp">Alamat KTP:</label>
                                                <input type="text" class="form-control" name="alamat_ktp" value="{{ $data->alamat_ktp }}" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="status_manager">Status Manager:</label>
                                                <input type="text" class="form-control" name="status_manager" value="{{ $data->status_manager }}" required disabled>
                                            </div>
                                            <div class="form-group">
                                                <label for="status_manager">Status Ketua:</label>
                                                <input type="text" class="form-control" name="status_manager" value="{{ $data->status_ketua }}" required disabled>
                                            </div>
                                            <div class="form-group">
                                                <label for="status_ketua">Status Pembayaran:</label>
                                                <input type="text" class="form-control" name="status_ketua" value="{{ $data->status_pembayaran }}" required disabled>
                                            </div>

                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn custom-blue-button ml-2">Update</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>


                        <!-- Modal for each member detail -->
                        <div class="modal fade" id="exampleModalLong{{ $data->id }}" tabindex="-1"
                            role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLongTitle">Detail Laporan Registrasi
                                        </h5>
                                        <button type="button" class="close" data-dismiss="modal"
                                            aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form id="detail-form-{{ $data->id }}">
                                            <div class="card-body">
                                                <table class="table-detail">
                                                    <tr>
                                                        <td><strong>Nama Anggota:</strong></td>
                                                        <td>{{ $data->nama }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Tempat Lahir:</strong></td>
                                                        <td>{{ $data->tempat_lahir }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Tanggal Lahir:</strong></td>
                                                        <td>{{ $data->tgl_lahir }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>NIK:</strong></td>
                                                        <td>{{ $data->nik }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Email Kantor:</strong></td>
                                                        <td>{{ $data->email_kantor }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>No Handphone:</strong></td>
                                                        <td>{{ $data->no_handphone }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Alamat Domisili:</strong></td>
                                                        <td>{{ $data->alamat_domisili }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Alamat KTP:</strong></td>
                                                        <td>{{ $data->alamat_ktp }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Status Manager:</strong></td>
                                                        <td>{{ $data->status_manager }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Status Ketua:</strong></td>
                                                        <td>{{ $data->status_ketua }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Status Pembayaran:</strong></td>
                                                        <td>{{ $data->status_pembayaran }}</td>
                                                    </tr>
                                                </table>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">Close</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deleteBtns = document.querySelectorAll('.delete-btn');
            deleteBtns.forEach(btn => {
                btn.addEventListener('click', function(event) {
                    event.preventDefault(); // Mencegah aksi default dari tautan

                    const id = this.getAttribute('data-id'); // Ambil ID anggota
                    const confirmMessage = "Apakah Anda yakin ingin menghapus anggota ini?"; // Pesan konfirmasi

                    // Menampilkan SweetAlert2
                    Swal.fire({
                        title: "Hapus Anggota?",
                        text: confirmMessage,
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Ya, Hapus!",
                        cancelButtonText: "Batal"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Buat form untuk penghapusan
                            const form = document.createElement('form');
                            form.method = 'POST';
                            form.action = "{{ url('admin') }}/" + id + "/delete"; // Ganti url sesuai rute Anda

                            const csrfField = document.createElement('input');
                            csrfField.type = 'hidden';
                            csrfField.name = '_token';
                            csrfField.value = "{{ csrf_token() }}"; // Tambahkan token CSRF

                            const methodField = document.createElement('input');
                            methodField.type = 'hidden';
                            methodField.name = '_method';
                            methodField.value = 'DELETE'; // Metode penghapusan

                            form.appendChild(csrfField);
                            form.appendChild(methodField);

                            document.body.appendChild(form);
                            form.submit(); // Kirim form
                        }
                    });
                });
            });
        });
    </script>
    @endsection