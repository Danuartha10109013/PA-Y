<!-- partial:partials/_sidebar.html -->
<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item">
            <a class="nav-link" href="{{ route('bendahara.index') }}">
                <i class="icon-grid menu-icon"></i>
                <span class="menu-title">Dashboard</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
                <i class="bi bi-person-square menu-icon"></i>
                <span class="menu-title">Anggota</span>

            </a>
            <div class="collapse" id="ui-basic">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link" href="{{route('approve-bendahara')}}">Registrasi</a></li>
                </ul>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#charts" aria-expanded="false" aria-controls="charts">
                <i class="fas fa-piggy-bank menu-icon"></i>
                <span class="menu-title">Simpanan</span>

            </a>
            <div class="collapse" id="charts">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link" href="{{ route('data.simpanan.sukarela.bendahara') }}">Sukarela</a></li>
                    <li class="nav-item"> <a class="nav-link" href="{{ route('data.simpanan.berjangka.bendahara') }}">Berjangka</a></li>
                </ul>
            </div>
        </li>
                        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#penarikan" aria-expanded="false" aria-controls="penarikan">
                <i class="fas fa-money-bill-wave menu-icon"></i>
                <span class="menu-title">Penarikan</span>
            </a>
            <div class="collapse" id="penarikan">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('penarikan.sukarela.approval.bendahara') }}">Simpanan Sukarela</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('penarikan.berjangka.approval.bendahara') }}">Simpanan Berjangka</a>
                    </li>
                </ul>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#approvalPinjaman" aria-expanded="false" aria-controls="pinjaman">
                <i class="fas fa-wallet menu-icon"></i>
                <span class="menu-title">Pinjaman</span>
            </a>
            <x-nav-link toggleId="approvalPinjaman" href="{{ route('approve.bendahara.emergency') }}">Emergency</x-nav-link>
            <x-nav-link toggleId="approvalPinjaman" href="{{ route('approve.bendahara.angunan') }}">Angunan</x-nav-link>
            <x-nav-link toggleId="approvalPinjaman" href="{{ route('approve.bendahara.nonangunan') }}">Non Angunan</x-nav-link>
        </li>

        {{-- <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#spk" aria-expanded="false" aria-controls="spk">
                <i class="fas fa-wallet menu-icon"></i>
                <span class="menu-title">SPK</span>
            </a>
            <x-nav-link toggleId="spk" href="{{ route('spk.calculate', ['id' => $currentSpkId]) }}">SPK</x-nav-link>
        </li> --}}

        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#pembayaran" aria-expanded="false" aria-controls="pinjaman">
                <i class="fas fa-wallet menu-icon"></i>
                <span class="menu-title">Pembayaran</span>
            </a>
            <x-nav-link toggleId="pembayaran" href="{{ route('status.index') }}">History Pembayaran</x-nav-link>
        </li>

    </ul>
</nav>
