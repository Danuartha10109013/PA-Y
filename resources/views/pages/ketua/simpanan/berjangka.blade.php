@if (auth()->user()->roles == 'ketua')
    @extends('layouts.dashboard-layout')
    @section('title', $title)
    @section('content')
        <div class="content-background" style="background: white">
            @include('layouts.partials.simpanan.berjangka') <!-- Pastikan file ini ada -->
        </div>

        <script>
            function updateStatusSimpananBerjangka(type, status) {
                const confirmMessage = status === 'Diterima' ? "Apakah anda yakin ingin menyetujui simpanan ini?" :
                    "Apakah anda yakin ingin menolak simpanan ini?";
                const successMessage = status === 'Diterima' ? "Simpanan telah disetujui." : "Simpanan telah ditolak.";

                Swal.fire({
                    title: "Approve Simpanan?",
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
                                url: `/ketua/simpanan/berjangka/${type}/${id}/${status}`, // URL dinamis
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
        </script>

        <script>
            document.getElementById('select-all').addEventListener('change', function() {
                var isChecked = this.checked;
                var checkboxes = document.querySelectorAll('.checkbox-item');

                checkboxes.forEach(function(checkbox) {
                    checkbox.checked = isChecked;
                });
            });

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
                    url: '/count-simpanan-berjangka/all',
                    type: 'GET',
                    success: function(response) {
                        $('.btn-wrapper .count').eq(0).text(response.count);
                    }
                });

                // Fetch count for Terima
                $.ajax({
                    url: '/count-simpanan-berjangka/diterima',
                    type: 'GET',
                    success: function(response) {
                        $('.btn-wrapper .count').eq(1).text(response.count);
                    }
                });

                // Fetch count for Pengajuan (Belum Diterima)
                $.ajax({
                    url: '/count-simpanan-berjangka/pengajuan',
                    type: 'GET',
                    success: function(response) {
                        $('.btn-wrapper .count').eq(2).text(response
                            .count);
                    }
                });

                // Fetch count for Ditolak
                $.ajax({
                    url: '/count-simpanan-berjangka/ditolak',
                    type: 'GET',
                    success: function(response) {
                        $('.btn-wrapper .count').eq(3).text(response.count);
                    }
                });
            });

            function filterdata(status) {
                $.ajax({
                    url: `/simpanan-berjangka/filter/${status}`,
                    type: 'GET',
                    success: function(response) {
                        $('tbody').html(response);
                    }
                });
            }
        </script>

        <script>
            const totaldata = 24;
            const perPage = 8;
            let currentPage = 1;

            function updateShowingInfo() {
                const start = (currentPage - 1) * perPage + 1;
                const end = Math.min(currentPage * perPage, totaldata);
                document.getElementById('start').innerText = start;
                document.getElementById('end').innerText = end;
                document.getElementById('total').innerText = totaldata;
            }

            updateShowingInfo();
        </script>
    @endsection
@endif
