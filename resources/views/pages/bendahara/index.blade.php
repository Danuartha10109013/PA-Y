@extends('layouts.dashboard-layout')
@section('title', $title)
@section('content')


    <!-- partial -->
    <div class="row my-3">
        <div class="col-md-12 grid-margin">
            <div class="row">
                <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                    <h3 class="font-weight-bold">Welcome {{ Auth()->user()->roles }}</h3>
                    <h6 class="font-weight-normal mb-0">Pada Dashboard Koperasi Konsumen Karlisna PLN
                        Yogyakarta</h6>
                </div>
                <div class="col-12 col-xl-4">
                    <div class="justify-content-end d-flex">
                        <div class="dropdown flex-md-grow-1 flex-xl-grow-0">
                            <button class="btn btn-sm btn-light bg-white" type="button" id="dropdownMenuDate2"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                <span id="currentDateTime">{{ $currentDateTime }}</span>
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Card Section -->
    <div class="row">
        <!-- Card Data Anggota -->
        <x-card-bendahara title="Data Anggota" icon="fas fa-user" :data="[
            [
                'label' => 'Pengajuan',
                'value' => $anggotas['pengajuan'] ?? 0,
                'color' => 'text-warning',
                // 'suffix' => 'Orang',
            ],
            [
                'label' => 'Diterima',
                'value' => $anggotas['diterima'] ?? 0,
                'color' => 'text-success',
                // 'suffix' => 'Orang',
            ],
            [
                'label' => 'Ditolak',
                'value' => $anggotas['ditolak'] ?? 0,
                'color' => 'text-danger',
                // 'suffix' => 'Orang',
            ],
        ]" />

        <x-card-bendahara title="Saldo Pinjaman" icon="fas fa-wallet" :data="$emergency" />
        <x-card-bendahara title="Saldo Pinjaman" icon="fas fa-wallet" :data="$angunan" />
        <x-card-bendahara title="Saldo Pinjaman" icon="fas fa-wallet" :data="$nonangunan" />
        <x-card-bendahara title="Total Pinjaman" icon="fas fa-wallet" :data="$totalLoans" />
        <x-card-bendahara title="Total Simpanan" icon="fas fa-wallet" :data="$totalSimpanans" />
        <x-card-bendahara title="Saldo Masuk" icon="fas fa-wallet" :data="$income" />
        <x-card-bendahara title="Saldo Keluar" icon="fas fa-wallet" :data="$outcome" />
    </div>
    <x-script-time />
@endsection
