@if (auth()->user()->roles == 'manager')
    @extends('layouts.dashboard-layout')
    @section('title', $title)
    @section('content')
        <div class="content-background" style="background: white">
            <div class="search-bar">
                <input type="text" placeholder="Search" class="form-control mr-2" style="width: 200px;" />
                <div class="ml-auto d-flex">
                    <button type="button" class="btn btn-success" onclick="updateStatusPinjaman('Diterima')">Terima</button>
                    <button type="button" class="btn btn-danger" onclick="updateStatusPinjaman('Ditolak')">Tolak</button>
                </div>
                @csrf
            </div>
            <div class="filter-buttons d-flex mt-3">
                <button onclick="filterdata('all')" class="btn-link">All</button>
                <button onclick="filterdata('diterima')" class="btn-link">Diterima</button>
                <button onclick="filterdata('pengajuan')" class="btn-link">Belum Diterima</button>
                <button onclick="filterdata('ditolak')" class="btn-link">Ditolak</button>
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
                            <th>Nomor Pinjaman</th>
                            <th>Nama</th>
                            <th>Jenis Pinjaman</th>
                            <th>Nominal Pinjaman</th>
                            <th>Jangka Waktu Peminjaman</th>
                            <th>Tujuan Pinjaman</th>
                            <th>Nomor Rekening</th>
                            <th>Nominal Angsuran Perbulan</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pinjamans as $key => $data)
                            <tr>
                                <td>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input checkbox-item"
                                            id="checkbox-{{ $data->id }}" data-id="{{ $data->id }}">
                                        <label class="custom-control-label" for="checkbox-{{ $data->id }}"></label>
                                    </div>
                                </td>
                                <td>{{ $data->nomor_pinjaman }}</td>
                                <td>{{ $data->user->name }}</td>
                                <td>{{ ucwords(str_replace('_', ' ', $data->jenis_pinjaman)) }}</td>
                                <td>Rp. {{ number_format($data->amount, 2) }}</td>
                                <td>{{ $data->tenor->tenor }}</td>
                                <td>{{ $data->keterangan }}</td>
                                {{-- <td>{{ $data->rekening_id ? $data->rekening->nomor_rekening : 'N/A' }}</td> --}}
                                <td>
                                    @if ($data->virtualAccount)
                                        {{ $data->virtualAccount->virtual_account_number }}
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>Rp. {{ number_format($data->nominal_angsuran, 2) }}</td>
                                <x-status-badge :statusKetua="$data->status_ketua" :statusManager="$data->status_manager" :statusBendahara="$data->status_bendahara" />
                                <td class="action-icons">
                                    <i class="fas fa-edit edit"></i>
                                    <i class="fas fa-trash delete"></i>
                                    <i class="fas fa-eye text-success"></i>
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
            function updateStatusPinjaman(status) {
                const confirmMessage = status === 'Diterima' ? "Apakah anda yakin ingin menyetujui anggota ini?" :
                    "Apakah anda yakin ingin menolak anggota ini?";
                const successMessage = status === 'Diterima' ? "Anggota telah disetujui." : "Anggota telah ditolak.";

                Swal.fire({
                    title: "Approve Anggota?",
                    text: confirmMessage,
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
                                url: `/manager/pinjaman/${id}/${status}`, // URL dinamis
                                type: "POST",
                                data: {
                                    _token: "{{ csrf_token() }}",
                                    status: status
                                },
                                success: function(response) {
                                    Swal.fire({
                                        title: "Success!",
                                        text: successMessage,
                                        icon: "success"
                                    }).then(() => {
                                        location.reload();
                                    });
                                },
                                error: function(xhr) {
                                    Swal.fire("Error!", `Terjadi kesalahan: ${xhr.responseText}`,
                                        "error");
                                }
                            });
                        });
                    }
                });
            }

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

            //check box
            // Event listener untuk checkbox "Select All"
            document.getElementById('select-all').addEventListener('change', function() {
                var isChecked = this.checked;
                var checkboxes = document.querySelectorAll('.checkbox-item');

                // Set status semua checkbox berdasarkan checkbox "Select All"
                checkboxes.forEach(function(checkbox) {
                    checkbox.checked = isChecked;
                });

                console.log('All checkboxes ' + (isChecked ? 'checked' : 'unchecked'));
            });

            // Event listener untuk setiap checkbox individu
            document.querySelectorAll('.checkbox-item').forEach(function(checkbox) {
                checkbox.addEventListener('change', function() {
                    var id = this.dataset.id;

                    if (this.checked) {
                        console.log('Checkbox with ID ' + id + ' is checked.');
                    } else {
                        console.log('Checkbox with ID ' + id + ' is unchecked.');
                    }

                    // Perbarui status checkbox "Select All" jika ada yang tidak tercentang
                    var allChecked = Array.from(document.querySelectorAll('.checkbox-item')).every(function(
                        item) {
                        return item.checked;
                    });

                    document.getElementById('select-all').checked = allChecked;
                });
            });
        </script>
    @endsection
@endif
