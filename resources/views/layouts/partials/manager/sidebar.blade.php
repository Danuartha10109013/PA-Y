<!-- partial:partials/_sidebar.html -->
<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item">
            <a class="nav-link" href="{{ route('home.manager') }}">
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
                    <li class="nav-item"> <a class="nav-link" href="{{route('approve.manager')}}">Registrasi</a></li>
                </ul>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#charts" aria-expanded="false" aria-controls="charts">
                <i class="fas fa-piggy-bank menu-icon"></i>
                <span class="menu-title">Simpanan</span>
            </a>
            <x-nav-link toggleId="charts" href="{{ route('data.simpanan.sukarela') }}">Sukarela</x-nav-link>
            <x-nav-link toggleId="charts" href="{{ route('data.simpanan.berjangka') }}">Berjangka</x-nav-link>
        </li>

                <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#penarikan" aria-expanded="false" aria-controls="penarikan">
                <i class="fas fa-money-bill-wave menu-icon"></i>
                <span class="menu-title">Penarikan</span>
            </a>
            <div class="collapse" id="penarikan">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('penarikan.sukarela.approval.manager') }}">Simpanan Sukarela</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('penarikan.berjangka.approval.manager') }}">Simpanan Berjangka</a>
                    </li>
                </ul>
            </div>
        </li>

        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#pinjaman" aria-expanded="false" aria-controls="form-elements">
                <i class="fas fa-wallet menu-icon"></i>
                <span class="menu-title">Pinjaman</span>

            </a>
            <x-nav-link toggleId="pinjaman" href="{{ route('approve.manager.emergency') }}">Pinjaman Emergency</x-nav-link>
            <x-nav-link toggleId="pinjaman" href="{{ route('approve.manager.angunan') }}">Pinjaman Angunan</x-nav-link>
            <x-nav-link toggleId="pinjaman" href="{{ route('approve.manager.nonangunan') }}">Pinjaman Non Angunan</x-nav-link>
        </li>
    </ul>
</nav>
