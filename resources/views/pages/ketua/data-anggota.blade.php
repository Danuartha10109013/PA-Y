@if (auth()->user()->roles == 'ketua')
    @extends('layouts.dashboard-layout')
    @section('title', $title)
    @section('content')
        <div class="content-background">
            <div class="search-bar">
                <input type="text" placeholder="Search" class="form-control mr-2" style="width: 200px;" />
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
                            <th>NIK</th>
                            <th>Email Kantor</th>
                            <th>No Handphone</th>
                            <th>Alamat Domisili</th>
                            <th>Alamat KTP</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($anggota as $data)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $data->user->name }}</td>
                                    <td>{{ $data->nik }}</td>
                                    <td>{{ $data->email_kantor }}</td>
                                    <td>{{ $data->no_handphone }}</td>
                                    <td>{{ $data->alamat_domisili }}</td>
                                    <td>{{ $data->alamat_ktp }}</td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endsection
@endif
