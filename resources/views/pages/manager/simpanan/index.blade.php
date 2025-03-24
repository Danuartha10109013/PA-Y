    @if (auth()->user()->roles == 'manager')
        @extends('layouts.dashboard-layout')
        @section('title', $title)
        @section('content')
            <!-- Bootstrap -->
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" rel="stylesheet">

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
            <style>
                .swal2-icon.swal2-success {
                    padding: 0;
                    /* Hilangkan padding bawaan */
                    margin-top: 20px;
                    /* Beri jarak atas */
                    margin-bottom: 20px;
                    /* Beri jarak bawah */
                    width: 80px;
                    /* Atur ulang lebar ikon */
                    height: 80px;
                    /* Atur ulang tinggi ikon */
                    border-radius: 50%;
                    /* Pastikan tetap berbentuk lingkaran */
                    background-color: rgba(220, 255, 220, 0.5);
                    /* Opsional: Warna latar tambahan untuk estetika */
                }

                .swal2-title {
                    font-size: 18px;
                    /* Ukuran font untuk judul */
                    margin-bottom: 10px;
                    /* Jarak bawah judul */
                }

                .swal2-content {
                    font-size: 14px;
                    /* Ukuran font untuk konten */
                }

                .swal2-popup.swal-wide {
                    padding: 20px;
                    /* Atur padding pop-up */
                    max-width: 400px;
                    /* Atur lebar maksimal pop-up */
                }
            </style>
            <div class="content-background" style="background: white">
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
                            <li><a class="dropdown-item" href="javascript:void(0);"
                                    onclick="filterData('Ditolak')">Ditolak</a>
                            </li>
                        </ul>
                    </div>

                    <div class="ml-auto d-flex">
                        <button id="acceptButton" type="submit" class="btn btn-success"
                            onclick="updateStatusSimpanan('approved')" disabled>Terima</button>
                        <button id="rejectButton" type="submit" class="btn btn-danger"
                            onclick="updateStatusSimpanan('rejected')">Tolak</button>

                    </div>
                </div>
                @csrf
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead bgcolor="EEEEEE">
                            <tr>
                                <th>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="select-all">
                                        <label class="custom-control-label" for="select-all"></label>
                                    </div>
                                </th>
                                <th>No Simpanan</th>
                                <th>Nama</th>
                                <th>Nominal</th>
                                <th>Bank</th>
                                <th>Rekening Simpanan</th>
                                <th>Status Payment</th>
                                <th>Virtual Account</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($simpananSukarelas as $key => $data)
                                <tr>
                                    <td>
                                        <div class="custom-control custom-checkbox">
                                            @if ($data->rekeningSimpananSukarela)
                                                <!-- Pastikan relasi tidak null -->
                                                <input type="checkbox" class="custom-control-input checkbox-item"
                                                    id="checkbox-{{ $data->rekeningSimpananSukarela->id }}"
                                                    data-id="{{ $data->rekeningSimpananSukarela->id }}">
                                                <label class="custom-control-label"
                                                    for="checkbox-{{ $data->rekeningSimpananSukarela->id }}"></label>
                                            @else
                                                <span>Data rekening tidak ditemukan</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>{{ $data->no_simpanan }}</td>
                                    <td>{{ $data->user->name }}</td>
                                    <td>Rp. {{ number_format($data->nominal, 2) }}</td>
                                    <td>{{ $data->bank }}</td>
                                    <td>{{ $data->rekeningSimpananSukarela->status ?? 'N/A' }}</td>
                                    <td>{{ $data->status_payment }}</td>
                                    <td>{{ $data->virtual_account ?? 'N/A' }}</td>
                                    <td>
                                        @if ($data->rekeningSimpananSukarela->approval_ketua == 'approved')
                                            <span class="badge badge-border-success">Diterima Ketua</span>
                                        @elseif ($data->rekeningSimpananSukarela->approval_ketua == 'rejected')
                                            <span class="badge badge-border-danger">Ditolak Ketua</span>
                                        @elseif ($data->rekeningSimpananSukarela->approval_bendahara == 'approved')
                                            <span class="badge badge-border-warning">Menunggu Approve Ketua
                                            @elseif ($data->rekeningSimpananSukarela->approval_bendahara == 'rejected')
                                                <span class="badge badge-border-danger">Ditolak Bendahara</span>
                                            @elseif ($data->rekeningSimpananSukarela->approval_manager == 'approved')
                                                <span class="badge badge-border-warning">Menunggu Approve Bendahara
                                                </span>
                                            @elseif ($data->rekeningSimpananSukarela->approval_manager == 'rejected')
                                                <span class="badge badge-border-danger">Ditolak Manager</span>
                                            @else
                                                <span class="badge badge-border-warning">Pengajuan</span>
                                        @endif
                                    </td>

                                    <td class="action-icons">
                                        <i class="fas fa-edit edit"></i>
                                        <i class="fas fa-trash delete"></i>
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


                <div class="showing-info">
                    Showing <span id="start"></span> to <span id="end"></span> of <span id="total"></span>
                    entries
                </div>
            </div>

            <script>
                function updateStatusSimpanan(status) {
                    console.log("Status yang diterima: ", status); // Debugging status

                    const confirmMessage = status === 'approved' ?
                        "Apakah anda yakin ingin menyetujui anggota ini?" :
                        "Apakah anda yakin ingin menolak anggota ini?";

                    const successMessage = status === 'approved' ?
                        "Anggota telah disetujui." :
                        "Anggota telah ditolak.";

                    Swal.fire({
                        title: "Approve Anggota?",
                        text: confirmMessage,
                        icon: "question",
                        iconHtml: '<i class="fas fa-question-circle"></i>', // Ikon question
                        customClass: {
                            popup: 'swal-wide', // Class untuk pop-up
                        },
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Yes!"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const selectedCheckboxes = document.querySelectorAll('.checkbox-item:checked');
                            if (selectedCheckboxes.length === 0) {
                                Swal.fire("Warning!", "Pilih setidaknya satu anggota!", "warning");
                                return;
                            }

                            selectedCheckboxes.forEach(checkbox => {
                                const id = checkbox.getAttribute('data-id');
                                console.log("ID yang dikirim: ", id); // Debugging ID

                                $.ajax({
                                    url: "{{ route('status.simpanan.sukarela', ['id' => 'ID', 'status' => 'STATUS']) }}"
                                        .replace('ID', id)
                                        .replace('STATUS', status),
                                    type: "POST",
                                    data: {
                                        _token: "{{ csrf_token() }}"
                                    },
                                    success: function(response) {
                                        console.log("Response sukses: ", response); // Debugging respons
                                        Swal.fire({
                                            title: "Success!",
                                            text: successMessage,
                                            icon: "success",
                                            iconHtml: '<i class="fas fa-check-circle"></i>', // Ikon alternatif jika diperlukan
                                            customClass: {
                                                popup: 'swal-wide', // Class untuk pop-up
                                            },
                                        }).then(() => {
                                            location.reload();
                                        });
                                    },
                                    error: function(xhr) {
                                        console.log("Response error: ", xhr); // Debugging error
                                        Swal.fire("Error!", `Terjadi kesalahan: ${xhr.responseText}`,
                                            "error");
                                    }
                                });
                            });
                        }
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
                        url: '/count-data-simpanan-sukarela/all',
                        type: 'GET',
                        success: function(response) {
                            $('.btn-wrapper .count').eq(0).text(response.count);
                        }
                    });

                    // Fetch count for Terima
                    $.ajax({
                        url: '/count-data-simpanan-sukarela/approved',
                        type: 'GET',
                        success: function(response) {
                            $('.btn-wrapper .count').eq(1).text(response.count);
                        }
                    });

                    // Fetch count for Pengajuan (Belum Diterima)
                    $.ajax({
                        url: '/count-data-simpanan-sukarela/pending',
                        type: 'GET',
                        success: function(response) {
                            $('.btn-wrapper .count').eq(2).text(response
                                .count); // Update angka di tombol "Belum Diterima"
                        }
                    });


                    // Fetch count for Ditolak
                    $.ajax({
                        url: '/count-data-simpanan-sukarela/rejected',
                        type: 'GET',
                        success: function(response) {
                            $('.btn-wrapper .count').eq(3).text(response.count);
                        }
                    });
                });

                // function filterdata(status) {
                //     // Lakukan filter berdasarkan status
                //     $.ajax({
                //         url: `/data/filter/${status}`,
                //         type: 'GET',
                //         success: function(response) {
                //             // Update tabel dengan data yang difilter
                //             $('tbody').html(response); // Sesuaikan dengan respon yang diberikan dari server
                //         }
                //     });
                // }
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
    
                function rejectionMessageModal(element) {
                    const message = element.getAttribute('data-message');
                    $('#rejectionMessage').text(message);
                }
            </script>
            <script>
                // Misalkan ada 24 data total dan hanya menampilkan 8 per halaman
                const totaldata = 24;
                const perPage = 8;
                let currentPage = 1; // Anda bisa mengubah nilai ini sesuai nomor halaman

                function updateShowingInfo() {
                    const start = (currentPage - 1) * perPage + 1;
                    const end = Math.min(currentPage * perPage, totaldata);
                    document.getElementById('start').innerText = start;
                    document.getElementById('end').innerText = end;
                    document.getElementById('total').innerText = totaldata;
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
        @endsection
    @endif
