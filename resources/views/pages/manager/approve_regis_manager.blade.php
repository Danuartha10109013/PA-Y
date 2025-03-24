@if (auth()->user()->hasRole('manager'))
    @extends('layouts.dashboard-layout')
    @section('title', $title)
    @section('content')
        @if (session('swal'))
            <script>
                Swal.fire({
                    title: "{{ session('swal.title') }}",
                    text: "{{ session('swal.text') }}",
                    icon: "{{ session('swal.icon') }}",
                });
            </script>
        @endif

        <head>
            <!-- Bootstrap -->
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" rel="stylesheet">


        </head>
        <style>
            /* Gaya untuk tombol disabled */
            .btn:disabled {
                cursor: not-allowed;
                /* Mengubah cursor menjadi tanda larangan */
                opacity: 0.6;
                /* Membuat tombol terlihat tidak aktif */
            }

            .dropdown-menu {
                min-width: 200px;
                /* Lebar dropdown */
            }

            .dropdown-item:hover {
                background-color: #007bff;
                /* Warna hover */
                color: #fff;
            }

            .dropdown-toggle {

                text-align: left;
                /* Teks rata kiri */
            }

            .input-group-text {
                background-color: #f8f9fa;


            }

            .input-group-text i {
                color: #6c757d;
                /* Warna ikon pencarian */
            }

            .input-group {
                width: 300px;
                margin-right: 10px;
                border-radius: 5px;
                /* Membuat tepi lebih halus */
            }
        </style>


        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>


        <div class="content-background">
            <div class="search-bar d-flex align-items-center">
                <div class="input-group" style="width: 300px;">
                    <input type="text" class="form-control" placeholder="search" aria-label="Search"
                        aria-describedby="search-icon" id="searchInput" oninput="searchData()" />
                    <span class="input-group-text" id="search-icon">
                        <i class="fa-solid fa-magnifying-glass"></i> <!-- Tes apakah ikon muncul -->
                        <!-- Ikon pencarian -->
                    </span>
                </div>

                <div class="dropdown mr-2">
                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="filterDropdownMenu"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        Filter Status
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="filterDropdownMenu">
                        <li><a class="dropdown-item" href="javascript:void(0);" onclick="filterData('all')">All</a></li>
                        <li><a class="dropdown-item" href="javascript:void(0);"
                                onclick="filterData('Diterima')">Diterima</a></li>
                        <li><a class="dropdown-item" href="javascript:void(0);" onclick="filterData('Pengajuan')">Belum
                                Diterima</a></li>
                        <li><a class="dropdown-item" href="javascript:void(0);" onclick="filterData('Ditolak')">Ditolak</a>
                        </li>
                    </ul>
                </div>

                <div class="ml-auto d-flex">
                    <button id="acceptButton" type="submit" class="btn btn-success" onclick="updateStatus('Diterima')"
                        disabled>Terima</button>
                    <button id="rejectButton" type="submit" class="btn btn-danger" onclick="updateStatus('Ditolak')"
                        disabled>Tolak</button>
                </div>

                @csrf


            </div>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="select-all">
                                    <label class="custom-control-label" for="select-all"></label>
                                </div>
                            </th>
                            <th>Nama</th>
                            <th>Tempat Lahir</th>
                            <th>Tgl Lahir</th>
                            <th>NIK</th>
                            <th>Email Kantor</th>
                            <th>No Handphone</th>
                            <th>Alamat Domisili</th>
                            <th>Alamat KTP</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($anggota as $data)
                            <tr>
                                <td>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input checkbox-item"
                                            id="checkbox{{ $loop->index }}" data-id="{{ $data->id }}">
                                        <label class="custom-control-label" for="checkbox{{ $loop->index }}"></label>
                                    </div>
                                </td>
                                <td>{{ $data->nama }}</td>
                                <td>{{ $data->tempat_lahir }}</td>
                                <td>{{ $data->tgl_lahir }}</td>
                                <td>{{ $data->nik }}</td>
                                <td>{{ $data->email_kantor }}</td>
                                <td>{{ $data->no_handphone }}</td>
                                <td>{{ $data->alamat_domisili }}</td>
                                <td>{{ $data->alamat_ktp }}</td>
                                <td>
                                    @if ($data->status_ketua == 'Diterima')
                                        <span class="badge badge-border-success">diterima ketua</span>
                                    @elseif($data->status_ketua == 'Ditolak')
                                        <span class="badge badge-border-danger">ditolak ketua</span>
                                    @elseif($data->status_manager == 'Diterima')
                                        <span class="badge badge-border-warning">menunggu approve ketua</span>
                                    @elseif($data->status_manager == 'Ditolak')
                                        <span class="badge badge-border-danger">ditolak manager</span>
                                    @else
                                        <span class="badge badge-border-warning">Pengajuan</span>
                                    @endif
                                </td>
                                <td class="action-icons">
                                    <a href="#" class="action-icons" data-id="{{ $data->id }}"
                                        onclick="viewDetail(this)">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    <i class="fas fa-edit edit" data-id="{{ $data->id }}"
                                        onclick="openEditModal(this)"></i>
                                    <i class="fas fa-trash delete" data-id="{{ $data->id }}"
                                        onclick="deleteData(this)"></i>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <nav aria-label="Page navigation example">
                <ul class="pagination">
                    <li class="page-item">
                        <a class="page-link" href="#" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                            <span class="sr-only">Previous</span>
                        </a>
                    </li>
                    <li class="page-item"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item">
                        <a class="page-link" href="#" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                            <span class="sr-only">Next</span>
                        </a>
                    </li>
                </ul>
            </nav>

            <!-- Showing data details -->
            <div class="showing-info">
                Showing <span id="start"></span> to <span id="end"></span> of <span id="total"></span>
                entries
            </div>
        </div>
        <div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="detailModalLabel">Detail Anggota</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th>ID</th>
                                    <td id="modalId"></td>
                                </tr>
                                <tr>
                                    <th>Nama</th>
                                    <td id="modalNama"></td>
                                </tr>
                                <tr>
                                    <th>Alamat Domisili</th>
                                    <td id="modalAlamatDomisili"></td>
                                </tr>
                                <tr>
                                    <th>Tempat Lahir</th>
                                    <td id="modalTempatLahir"></td>
                                </tr>
                                <tr>
                                    <th>Tanggal Lahir</th>
                                    <td id="modalTglLahir"></td>
                                </tr>
                                <tr>
                                    <th>Alamat KTP</th>
                                    <td id="modalAlamatKTP"></td>
                                </tr>
                                <tr>
                                    <th>NIK</th>
                                    <td id="modalNik"></td>
                                </tr>
                                <tr>
                                    <th>Email Kantor</th>
                                    <td id="modalEmailKantor"></td>
                                </tr>
                                <tr>
                                    <th>No Handphone</th>
                                    <td id="modalNoHandphone"></td>
                                </tr>
                                <tr>
                                    <th>Status Manager</th>
                                    <td id="modalStatusManager"></td>
                                </tr>
                                <tr>
                                    <th>Status Ketua</th>
                                    <td id="modalStatusKetua"></td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td id="modalStatus"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Edit Anggota</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="editForm">
                            @csrf
                            <input type="hidden" id="editId">
                            <div class="form-group">
                                <label for="editNama">Nama</label>
                                <input type="text" class="form-control" id="editNama" name="nama" required>
                            </div>
                            <div class="form-group">
                                <label for="editAlamatDomisili">Alamat Domisili</label>
                                <input type="text" class="form-control" id="editAlamatDomisili"
                                    name="alamat_domisili" required>
                            </div>
                            <div class="form-group">
                                <label for="editTempatLahir">Tempat Lahir</label>
                                <input type="text" class="form-control" id="editTempatLahir" name="tempat_lahir"
                                    required>
                            </div>
                            <div class="form-group">
                                <label for="editTglLahir">Tanggal Lahir</label>
                                <input type="date" class="form-control" id="editTglLahir" name="tgl_lahir" required>
                            </div>
                            <div class="form-group">
                                <label for="editNik">NIK</label>
                                <input type="text" class="form-control" id="editNik" name="nik" required>
                            </div>
                            <div class="form-group">
                                <label for="editEmailKantor">Email Kantor</label>
                                <input type="email" class="form-control" id="editEmailKantor" name="email_kantor"
                                    required>
                            </div>
                            <div class="form-group">
                                <label for="editNoHandphone">No Handphone</label>
                                <input type="text" class="form-control" id="editNoHandphone" name="no_handphone"
                                    required>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        <button type="button" class="btn btn-primary" onclick="saveEdit()">Simpan</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="rejectReasonModal" tabindex="-1" role="dialog"
            aria-labelledby="rejectReasonModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="rejectReasonModalLabel">Masukkan Alasan Penolakan</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="rejectForm">
                            @csrf
                            <input type="hidden" id="rejectId">
                            <div class="form-group">
                                <label for="rejectReason">Alasan Penolakan</label>
                                <textarea class="form-control" id="rejectReason" name="alasan_ditolak" rows="3" required></textarea>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        <button type="button" class="btn btn-danger save-reject">Tolak</button>
                    </div>

                </div>
            </div>
        </div>






        <script>
            $('#rejectReasonModal').on('show.bs.modal', function() {
                console.log("Modal Dibuka!");
            });

            function updateStatus(status) {
                if (status === "Ditolak") {
                    // Tampilkan modal alasan penolakan
                    const selectedCheckboxes = document.querySelectorAll('.checkbox-item:checked');
                    if (selectedCheckboxes.length === 1) {
                        const id = selectedCheckboxes[0].getAttribute('data-id');
                        document.getElementById('rejectId').value = id;
                        $('#rejectReasonModal').modal('show');
                    } else {
                        alert("Silakan pilih satu anggota untuk menolak.");
                    }
                } else {
                    let title = "Approve Anggota?";
                    let text = "Apakah anda yakin ingin menyetujui anggota ini?";
                    let successTitle = "Approved!";
                    let successText = "Anggota telah disetujui.";

                    Swal.fire({
                        title: title,
                        text: text,
                        icon: "question",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Yes!"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const selectedCheckboxes = document.querySelectorAll('.checkbox-item:checked');
                            selectedCheckboxes.forEach(checkbox => {
                                const id = checkbox.getAttribute('data-id');
                                $.ajax({
                                    url: "{{ route('approve.update-status-manager', ['id' => 'ID', 'status' => 'STATUS']) }}"
                                        .replace('ID', id)
                                        .replace('STATUS', status),
                                    type: "POST",
                                    data: {
                                        _token: "{{ csrf_token() }}",
                                        status: status
                                    },
                                    success: function(response) {
                                        Swal.fire({
                                            title: successTitle,
                                            text: successText,
                                            icon: "success"
                                        }).then(() => {
                                            location.reload();
                                        });
                                    },
                                    error: function(xhr) {
                                        Swal.fire("Error!", "Terjadi kesalahan: ", "error");
                                    }
                                });
                            });
                        }
                    });
                }

                $(document).ready(function() {
                    // Event listener untuk tombol "Tolak"
                    $(document).on('click', '.save-reject', function() {
                        const id = $('#rejectId').val(); // Ambil ID anggota dari input hidden
                        const alasan = $('#rejectReason').val(); // Ambil alasan dari textarea

                        if (!alasan.trim()) {
                            alert("Alasan penolakan tidak boleh kosong.");
                            return;
                        }

                        // Kirim data melalui AJAX
                        $.ajax({
                            url: `/manager/anggota/reject/${id}`, // Endpoint Laravel
                            type: "POST",
                            data: {
                                _token: "{{ csrf_token() }}", // CSRF token
                                alasan_ditolak: alasan,
                                status: "Ditolak"
                            },
                            success: function(response) {
                                console.log("Respons server:", response); // Debugging
                                $('#rejectReasonModal').modal('hide'); // Tutup modal
                                Swal.fire("Berhasil!", "Anggota telah ditolak.", "success").then(
                                () => {
                                        location.reload(); // Muat ulang halaman
                                    });
                            },
                            error: function(xhr) {
                                console.error("Error dari server:", xhr); // Debugging
                                Swal.fire("Error!", "Terjadi kesalahan saat menolak anggota.",
                                    "error");
                            }
                        });
                    });
                });



            }
        </script>

        <Script>
            document.getElementById('select-all').addEventListener('change', function() {
                var isChecked = this.checked;
                var checkboxes = document.querySelectorAll('.checkbox-item');

                checkboxes.forEach(function(checkbox) {
                    checkbox.checked = isChecked;
                });
            });

            // Event listener untuk mengambil ID dari checkbox yang dipilih
            document.querySelectorAll('.checkbox-item').forEach(function(checkbox) {
                checkbox.addEventListener('change', function() {
                    var id = this.dataset.id;
                    if (this.checked) {
                        console.log('Checkbox with ID ' + id + ' is checked.');
                    } else {
                        console.log('Checkbox with ID ' + id + ' is unchecked.');
                    }
                });
            });
        </script>
        <script>
            $(document).ready(function() {
                // Fetch count for All
                $.ajax({
                    url: '/count-data/all',
                    type: 'GET',
                    success: function(response) {
                        $('.btn-wrapper .count').eq(0).text(response.count);
                    }
                });

                // Fetch count for Terima
                $.ajax({
                    url: '/count-data/diterima',
                    type: 'GET',
                    success: function(response) {
                        $('.btn-wrapper .count').eq(1).text(response.count);
                    }
                });

                // Fetch count for Pengajuan (Belum Diterima)
                $.ajax({
                    url: '/count-data/pengajuan',
                    type: 'GET',
                    success: function(response) {
                        $('.btn-wrapper .count').eq(2).text(response
                            .count); // Update angka di tombol "Belum Diterima"
                    }
                });


                // Fetch count for Ditolak
                $.ajax({
                    url: '/count-data/ditolak',
                    type: 'GET',
                    success: function(response) {
                        $('.btn-wrapper .count').eq(3).text(response.count);
                    }
                });
            });

            function filterData(status) {
                // Lakukan filter berdasarkan status
                $.ajax({
                    url: `/data/filter/${status}`,
                    type: 'GET',
                    success: function(response) {
                        // Update tabel dengan data yang difilter
                        $('tbody').html(response); // Sesuaikan dengan respon yang diberikan dari server
                    }
                });
            }
        </script>
        <script>
            // Misalkan ada 24 data total dan hanya menampilkan 8 per halaman
            const totalData = 24;
            const perPage = 8;
            let currentPage = 1; // Anda bisa mengubah nilai ini sesuai nomor halaman

            function updateShowingInfo() {
                const start = (currentPage - 1) * perPage + 1;
                const end = Math.min(currentPage * perPage, totalData);
                document.getElementById('start').innerText = start;
                document.getElementById('end').innerText = end;
                document.getElementById('total').innerText = totalData;
            }

            // Panggil fungsi saat halaman dimuat atau saat pagination diubah
            updateShowingInfo();
        </script>
        <script>
            // Function to update the disabled state of the buttons
            function updateButtonState() {
                const checkboxes = document.querySelectorAll('.checkbox-item');
                const acceptButton = document.getElementById('acceptButton');
                const rejectButton = document.getElementById('rejectButton');

                // Check if any checkbox is checked
                const anyChecked = Array.from(checkboxes).some(checkbox => checkbox.checked);

                // Enable or disable buttons based on the checkbox state
                acceptButton.disabled = !anyChecked;
                rejectButton.disabled = !anyChecked;
            }

            // Add event listeners to checkboxes
            document.querySelectorAll('.checkbox-item').forEach(checkbox => {
                checkbox.addEventListener('change', updateButtonState);
            });

            // Ensure buttons are disabled initially
            document.addEventListener('DOMContentLoaded', updateButtonState);
        </script>
        <script>
            function filterData(status) {
                // Tampilkan loader jika diperlukan
                console.log(`Filtering data with status: ${status}`);

                // Kirim request AJAX untuk mendapatkan data berdasarkan status
                $.ajax({
                    url: `/manager/data/filter`, // URL endpoint filter
                    type: 'GET',
                    data: {
                        status: status
                    }, // Kirim status sebagai parameter query
                    success: function(response) {
                        console.log(response); // Log respon dari server
                        $('tbody').html(response);
                    },

                    error: function(xhr) {
                        console.error("Error occurred while filtering data:", xhr);
                        alert("Terjadi kesalahan saat memuat data filter. Silakan coba lagi.");
                        console.log(`Filtering data with status: ${status}`);

                    }

                });
            }

            function searchData() {
                const query = document.getElementById('searchInput').value;

                // Kirim permintaan AJAX ke server untuk mencari data
                $.ajax({
                    url: '/manager/data/search', // Endpoint Laravel
                    type: 'GET',
                    data: {
                        query: query
                    }, // Kirim query pencarian sebagai parameter
                    success: function(response) {
                        // Update tabel dengan hasil pencarian
                        $('tbody').html(response);
                    },
                    error: function(xhr) {
                        console.error("Error occurred while searching:", xhr);
                        alert("Terjadi kesalahan saat memuat data pencarian. Silakan coba lagi.");
                    }
                });
            }


            function deleteData(element) {
                const dataId = element.getAttribute('data-id');

                // Konfirmasi sebelum menghapus
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: 'Data yang dihapus tidak dapat dikembalikan!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, hapus!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Kirim permintaan AJAX untuk menghapus data
                        $.ajax({
                            url: `/manager/data/delete/${dataId}`, // Endpoint Laravel untuk menghapus data
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}' // Token CSRF untuk keamanan
                            },
                            success: function(response) {
                                Swal.fire(
                                    'Terhapus!',
                                    'Data telah berhasil dihapus.',
                                    'success'
                                ).then(() => {
                                    location.reload(); // Muat ulang halaman untuk memperbarui tabel
                                });
                            },
                            error: function(xhr) {
                                Swal.fire(
                                    'Error!',
                                    'Terjadi kesalahan saat menghapus data.',
                                    'error'
                                );
                                console.error(xhr);
                            }
                        });
                    }
                });
            }




            // Event listener untuk tombol detail
            function viewDetail(element) {
                const dataId = element.getAttribute('data-id'); // Ambil ID dari atribut data-id

                // Kirim permintaan AJAX untuk mengambil data detail anggota
                $.ajax({
                    url: `/manager/anggota/detail/${dataId}`, // Endpoint Laravel untuk mengambil data
                    type: 'GET',
                    success: function(response) {
                        // Isi data ke dalam modal
                        document.getElementById('modalId').textContent = response.id;
                        document.getElementById('modalNama').textContent = response.nama;
                        document.getElementById('modalAlamatDomisili').textContent = response.alamat_domisili;
                        document.getElementById('modalTempatLahir').textContent = response.tempat_lahir;
                        document.getElementById('modalTglLahir').textContent = response.tgl_lahir;
                        document.getElementById('modalAlamatKTP').textContent = response.alamat_ktp;
                        document.getElementById('modalNik').textContent = response.nik;
                        document.getElementById('modalEmailKantor').textContent = response.email_kantor;
                        document.getElementById('modalNoHandphone').textContent = response.no_handphone;
                        document.getElementById('modalStatusManager').textContent = response.status_manager;
                        document.getElementById('modalStatusKetua').textContent = response.status_ketua;
                        document.getElementById('modalStatus').textContent = response.status;

                        // Tampilkan modal
                        $('#detailModal').modal('show');
                    },

                });
            }

            // Event listener untuk tombol "Tutup" atau "X"
            $('#detailModal .close, #detailModal .btn-secondary').on('click', function() {
                $('#detailModal').modal('hide'); // Tutup modal
            });

            // Event listener untuk tombol detail
            $(document).on('click', '.action-icons', function(e) {
                e.preventDefault(); // Mencegah aksi default
                viewDetail(this); // Panggil fungsi untuk menampilkan detail
            });


            $(document).ready(function() {
                // Event listener untuk membuka modal edit
                $(document).on('click', '.edit', function() {
                    const dataId = $(this).data('id');
                    openEditModal(dataId);
                });

                // Event listener untuk tombol "Tutup" dan ikon "X"
                $('#editModal .close, #editModal .btn-secondary').on('click', function() {
                    $('#editModal').modal('hide');
                });
            });


            function openEditModal(element) {
                const dataId = element.getAttribute('data-id'); // Ambil ID anggota

                // Kirim permintaan AJAX untuk mendapatkan data anggota
                $.ajax({
                    url: `/manager/anggota/detail/${dataId}`, // Endpoint untuk mendapatkan data anggota
                    type: 'GET',
                    success: function(response) {
                        // Isi data ke dalam modal edit
                        $('#editId').val(response.id);
                        $('#editNama').val(response.nama);
                        $('#editAlamatDomisili').val(response.alamat_domisili);
                        $('#editTempatLahir').val(response.tempat_lahir);
                        $('#editTglLahir').val(response.tgl_lahir);
                        $('#editNik').val(response.nik);
                        $('#editEmailKantor').val(response.email_kantor);
                        $('#editNoHandphone').val(response.no_handphone);

                        // Tampilkan modal edit
                        $('#editModal').modal('show');
                    },
                    error: function(xhr) {
                        console.error("Error fetching data:", xhr);
                        alert("Gagal memuat data untuk edit.");
                    }
                });
            }

            function saveEdit() {
                const dataId = $('#editId').val(); // Ambil ID dari input hidden
                const formData = $('#editForm').serialize(); // Ambil data dari form

                // Kirim permintaan AJAX untuk menyimpan perubahan
                $.ajax({
                    url: `/manager/anggota/update/${dataId}`, // Endpoint untuk menyimpan data
                    type: 'PUT',
                    data: formData,
                    success: function(response) {
                        // Tampilkan pesan sukses
                        alert("Data berhasil diperbarui.");

                        // Tutup modal edit
                        $('#editModal').modal('hide');

                        // Muat ulang halaman untuk memperbarui tabel
                        location.reload();
                    },
                    error: function(xhr) {
                        console.error("Error saving data:", xhr);
                        alert("Gagal menyimpan perubahan.");
                    }
                });

            }
        </script>


    @endsection
@endif
