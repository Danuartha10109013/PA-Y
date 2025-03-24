@extends('layouts.dashboard-layout')
@section('content')
<div class="content-background">
    <div class="pl-2 pb-8">
        <h3 class="font-weight-bold">Data Anggota</h3>
    </div>
    <!-- Search Bar and Add Button -->
    <div class="search-bar d-flex align-items-center mb-3">
        <input type="text" placeholder="Search" class="form-control mr-2" style="width: 200px;" />
        <button class="btn custom-blue-button ml-2" data-toggle="modal" data-target="#tambahDataModal">Tambah Data</button>
    </div>

    <!-- Modal for Adding New Data -->
    <div class="modal fade" id="tambahDataModal" tabindex="-1" role="dialog" aria-labelledby="tambahDataModalTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahDataModalTitle">Tambah Data Anggota</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form id="tambahDataForm" action="{{ route('anggota.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="card-body">
                            <table class="table-detail">
                                <tr>
                                    <td><strong>Nama Anggota:</strong></td>
                                    <td><input type="text" name="nama" class="form-control" required></td>
                                </tr>
                                <tr>
                                    <td><strong>Tempat Lahir:</strong></td>
                                    <td><input type="text" name="tempat_lahir" class="form-control" required></td>
                                </tr>
                                <tr>
                                    <td><strong>Tanggal Lahir:</strong></td>
                                    <td><input type="date" name="tgl_lahir" class="form-control" required></td>
                                </tr>
                                <tr>
                                    <td><strong>NIK:</strong></td>
                                    <td><input type="text" name="nik" class="form-control" required></td>
                                </tr>
                                <tr>
                                    <td><strong>Email Kantor:</strong></td>
                                    <td><input type="email" name="email_kantor" class="form-control" required></td>
                                </tr>
                                <tr>
                                    <td><strong>No Handphone:</strong></td>
                                    <td><input type="text" name="no_handphone" class="form-control" required></td>
                                </tr>
                                <tr>
                                    <td><strong>Alamat Domisili:</strong></td>
                                    <td><input type="text" name="alamat_domisili" class="form-control" required></td>
                                </tr>
                                <tr>
                                    <td><strong>Alamat KTP:</strong></td>
                                    <td><input type="text" name="alamat_ktp" class="form-control" required></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn custom-blue-button ml-2">Simpan Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Icon Group -->
    <!--<div class="icon-group mt-2 mb-3">
        <button class="icon-btn" title="Print"><i class="fa fa-print"></i></button>
        <button class="icon-btn" title="Upload"><i class="fa fa-upload"></i></button>
        <button class="icon-btn" title="Download"><i class="fa fa-download"></i></button>
        <button class="icon-btn" title="Load"><i class="fa fa-circle-notch"></i></button>
    </div>-->

    <!-- Table -->
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>NIK</th>
                    <th>Email Kantor</th>
                    <th>No Handphone</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($anggota as $data)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $data->nama }}</td>
                    <td>{{ $data->nik }}</td>
                    <td>{{ $data->email_kantor }}</td>
                    <td>{{ $data->no_handphone }}</td>
                    <td>
                        <a href="#" class="action-icons" data-toggle="modal" data-target="#detailModal{{$data->id}}"><i class="fas fa-eye"></i></a>
                        <a href="#" class="action-icons" data-toggle="modal" data-target="#editModal{{ $data->id }}"><i class="fas fa-edit edit"></i></a>
                        <a href="#" class="action-icons delete-btn" data-id="{{ $data->id }}">
                            <i class="fas fa-trash delete"></i>
                        </a>

                    </td>
                </tr>
                <!-- Detail Modal -->
                @endforeach
            </tbody>
        </table>
    </div>
    @foreach ($anggota as $data)
    <div class="modal fade" id="detailModal{{$data->id}}" tabindex="-1" role="dialog" aria-labelledby="modalTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Anggota</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
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
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    @endforeach

    @foreach ($anggota as $data)
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
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn custom-blue-button ml-2">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endforeach


    <!-- Pagination -->
    <nav aria-label="Page navigation example">
        <ul class="pagination">
            <li class="page-item"><a class="page-link" href="#" aria-label="Previous">&laquo;</a></li>
            <li class="page-item"><a class="page-link" href="#">1</a></li>
            <li class="page-item"><a class="page-link" href="#">2</a></li>
            <li class="page-item"><a class="page-link" href="#">3</a></li>
            <li class="page-item"><a class="page-link" href="#" aria-label="Next">&raquo;</a></li>
        </ul>
    </nav>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deleteBtns = document.querySelectorAll('.delete-btn');
        deleteBtns.forEach(btn => {
            btn.addEventListener('click', function(event) {
                event.preventDefault(); // Prevent default link action

                const id = this.getAttribute('data-id'); // Get member ID
                const confirmMessage = "Apakah Anda yakin ingin menghapus anggota ini?"; // Confirmation message

                // Display SweetAlert2 for confirmation
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
                        // Create a form for deletion
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = `/anggota/${id}/delete`;; // Use the correct route URL

                        // CSRF token
                        const csrfField = document.createElement('input');
                        csrfField.type = 'hidden';
                        csrfField.name = '_token';
                        csrfField.value = "{{ csrf_token() }}"; // Add CSRF token

                        // DELETE method field
                        const methodField = document.createElement('input');
                        methodField.type = 'hidden';
                        methodField.name = '_method';
                        methodField.value = 'DELETE';

                        form.appendChild(csrfField);
                        form.appendChild(methodField);

                        document.body.appendChild(form);
                        form.submit(); // Submit the form
                    }
                });
            });
        });
    });
</script>
@endsection