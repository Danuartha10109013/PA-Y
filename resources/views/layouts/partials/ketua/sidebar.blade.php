<!-- partial:partials/_sidebar.html -->
<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item">
            <a class="nav-link" href="{{ route('home-ketua') }}">
                <i class="icon-grid menu-icon"></i>
                <span class="menu-title">Dashboard</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
                <i class="bi bi-person-square menu-icon"></i>
                <span class="menu-title">Anggota</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="ui-basic">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link" href="{{ route('approve-ketua') }}">Registrasi</a></li>

                </ul>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#simpanan" aria-expanded="false"
                aria-controls="pinjaman">
                <i class="fas fa-wallet menu-icon"></i>
                <span class="menu-title">Simpanan</span>
                <i class="menu-arrow"></i>
            </a>
            <x-nav-link toggleId="simpanan" href="{{ route('data.simpanan.berjangka.ketua') }}">Berjangka</x-nav-link>
            <x-nav-link toggleId="simpanan" href="{{ route('data.simpanan.sukarela.ketua') }}">Sukarela</x-nav-link>
        </li>
                                <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#penarikan" aria-expanded="false" aria-controls="penarikan">
                <i class="fas fa-money-bill-wave menu-icon"></i>
                <span class="menu-title">Penarikan</span>
            </a>
            <div class="collapse" id="penarikan">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('penarikan.sukarela.approval.ketua') }}">Simpanan Sukarela</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('penarikan.berjangka.approval.ketua') }}">Simpanan Berjangka</a>
                    </li>
                </ul>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#pinjaman" aria-expanded="false"
                aria-controls="pinjaman">
                <i class="fas fa-wallet menu-icon"></i>
                <span class="menu-title">Pinjaman</span>
                <i class="menu-arrow"></i>
            </a>
            <x-nav-link toggleId="pinjaman" href="{{ route('approve.ketua.emergency') }}">Emergency</x-nav-link>
            <x-nav-link toggleId="pinjaman" href="{{ route('approve.ketua.angunan') }}">Angunan</x-nav-link>
            <x-nav-link toggleId="pinjaman" href="{{ route('approve.ketua.nonangunan') }}">Non Angunan</x-nav-link>
        </li>
    </ul>
</nav>
