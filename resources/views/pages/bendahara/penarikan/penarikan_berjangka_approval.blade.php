@if (auth()->user()->roles == 'bendahara')
@extends('layouts.dashboard-layout')
@section('title', $title)
@section('content')
<style>
    .swal2-icon.swal2-success {
        padding: 0; /* Hilangkan padding bawaan */
        margin-top: 20px; /* Beri jarak atas */
        margin-bottom: 20px; /* Beri jarak bawah */
        width: 80px; /* Atur ulang lebar ikon */
        height: 80px; /* Atur ulang tinggi ikon */
        border-radius: 50%; /* Pastikan tetap berbentuk lingkaran */
        background-color: rgba(220, 255, 220, 0.5); /* Opsional: Warna latar tambahan untuk estetika */
    }

    .swal2-title {
        font-size: 18px; /* Ukuran font untuk judul */
        margin-bottom: 10px; /* Jarak bawah judul */
    }

    .swal2-content {
        font-size: 14px; /* Ukuran font untuk konten */
    }

    .swal2-popup.swal-wide {
        padding: 20px; /* Atur padding pop-up */
        max-width: 400px; /* Atur lebar maksimal pop-up */
    }
</style>
<div class="content-background" style="background: white">
    <div class="search-bar">
        <input type="text" placeholder="Search" class="form-control mr-2" style="width: 200px;" />
        <div class="ml-auto d-flex">
            <button type="button" class="btn btn-success" onclick="updateStatusSimpanan('diterima')">Terima</button>
            <button type="button" class="btn btn-danger" onclick="updateStatusSimpanan('ditolak')">Tolak</button>
        </div>
        @csrf   
    </div>
    <div class="filter-buttons d-flex mt-3">
        <button onclick="filterdata('all')" class="btn-link">All</button>
        <button onclick="filterdata('approved')" class="btn-link">Diterima</button>
        <button onclick="filterdata('pending')" class="btn-link">Belum Diterima</button>
        <button onclick="filterdata('rejected')" class="btn-link">Ditolak</button>
    </div>
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
                    <th>No Penarikan</th>
                    <th>Nama</th>
                    <th>Nominal</th>
                    <th>No Rekening</th>
                    <th>Rekening Simpanan</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                      @foreach ($data as $key => $data)
                <tr>
                    <td>
                        <div class="custom-control custom-checkbox">
                      
                                <input type="checkbox" class="custom-control-input checkbox-item" id="checkbox-{{ $data->id }}" data-id="{{ $data->id }}">
                                <label class="custom-control-label" for="checkbox-{{ $data->id }}"></label>
                     

                      
                        </div>
                    </td>
                    <td>{{ $data->no_penarikan }}</td>
                    <td>{{ $data->user->name }}</td>
                    <td>Rp. {{ number_format($data->nominal, 2) }}</td>
                    <td>{{ $data->bank }}</td>
                    <td>{{ $data->status_manager }}</td>
                    <td>
                        @if ($data->status_ketua == 'diterima')
                            <span class="badge badge-border-success">Diterima Ketua</span>
                        @elseif ($data->status_ketua == 'ditolak')
                            <span class="badge badge-border-danger">Ditolak Ketua</span>
                        @elseif ($data->status_manager == 'diterima')
                            <span class="badge badge-border-warning">Menunggu Approve Ketua</span>
                        @elseif ($data->status_manager == 'ditolak')
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
        Showing <span id="start"></span> to <span id="end"></span> of <span id="total"></span> entries
    </div>
</div>

<script>
function updateStatusSimpanan(status) {
    console.log("Status yang diterima: ", status); // Debugging status

    const confirmMessage = status === 'diterima' ?
        "Apakah anda yakin ingin menyetujui anggota ini?" :
        "Apakah anda yakin ingin menolak anggota ini?";

    const successMessage = status === 'diterima' ?
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
                    url: "{{ route('status.penarikan.berjangka.bendahara', ['id' => 'ID', 'status' => 'STATUS']) }}"
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
                        Swal.fire("Error!", `Terjadi kesalahan: ${xhr.responseText}`, "error");
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
            url: '/count-data-penarikan-berjangka/all',
            type: 'GET',
            success: function(response) {
                $('.btn-wrapper .count').eq(0).text(response.count);
            }
        });

        // Fetch count for Terima
        $.ajax({
            url: '/count-data-penarikan-berjangka/approved',
            type: 'GET',
            success: function(response) {
                $('.btn-wrapper .count').eq(1).text(response.count);
            }
        });

        // Fetch count for Pengajuan (Belum Diterima)
        $.ajax({
            url: '/count-data-penarikan-berjangka/pending',
            type: 'GET',
            success: function(response) {
                $('.btn-wrapper .count').eq(2).text(response
                    .count); // Update angka di tombol "Belum Diterima"
            }
        });


        // Fetch count for Ditolak
        $.ajax({
            url: '/count-data-penarikan-berjangka/rejected',
            type: 'GET',
            success: function(response) {
                $('.btn-wrapper .count').eq(3).text(response.count);
            }
        });
    });

    function filterdata(status) {
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
@endsection
@endif
