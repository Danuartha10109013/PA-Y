
@extends('layouts.dashboard-layout')
@section('title', $title)
@section('content')
<style>
    .swal2-icon.swal2-success {
        padding: 0;
        margin-top: 20px;
        margin-bottom: 20px;
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background-color: rgba(220, 255, 220, 0.5);
    }

    .swal2-title {
        font-size: 18px;
        margin-bottom: 10px;
    }

    .swal2-content {
        font-size: 14px;
    }

    .swal2-popup.swal-wide {
        padding: 20px;
        max-width: 400px;
    }
</style>

<div class="content-background" style="background: white">
    <div class="search-bar">
        <input type="text" placeholder="Search" class="form-control mr-2" style="width: 200px;" />
    </div>

    <div class="table-responsive">
        <table class="table table-bordered">
            <thead bgcolor="EEEEEE">
                <tr>
                    <th>ID Simpanan</th>
                    <th>Nama Anggota</th>
                    <th>Nominal</th>
                    <th>Metode Pembayaran</th>
                    <th>Tanggal Pembayaran</th>
                    <th>Status Pembayaran</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @if($simpananWajib->isEmpty())
                    <tr>
                        <td colspan="7" class="text-center">Tidak ada data simpanan wajib</td>
                    </tr>
                @else
                    @foreach ($simpananWajib as $data)
                        <tr>
                            <td>{{ $data->id }}</td>
                            <td>{{ $data->anggota->name ?? 'N/A' }}</td>
                            <td>Rp. {{ number_format($data->nominal, 2) }}</td>
                            <td>{{ $data->metode_pembayaran }}</td>
                            <td>{{ date('d-m-Y', strtotime($data->tanggal_pembayaran)) }}</td>
                            <td>{{ $data->status_pembayaran }}</td>
                        </tr>
                    @endforeach
                @endif
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
    $(document).ready(function() {
        function updateDataCount(url, index) {
            $.ajax({
                url: url,
                type: 'GET',
                success: function(response) {
                    $('.btn-wrapper .count').eq(index).text(response.count);
                }
            });
        }

        updateDataCount('/count-data-simpanan-wajib/all', 0);
        updateDataCount('/count-data-simpanan-wajib/lunas', 1);
        updateDataCount('/count-data-simpanan-wajib/pending', 2);
        updateDataCount('/count-data-simpanan-wajib/tolak', 3);
    });

    function filterdata(status) {
        $.ajax({
            url: `/data/filter/${status}`,
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

