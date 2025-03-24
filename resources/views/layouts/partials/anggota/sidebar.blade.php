<!-- partial:partials/_sidebar.html -->
<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item">
            <a class="nav-link" href="{{ route('home-anggota') }}">
                <i class="icon-grid menu-icon"></i>
                <span class="menu-title">Dashboard</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#simpanan" aria-expanded="false" aria-controls="simpanan">
                <i class="fas fa-piggy-bank menu-icon"></i>
                <span class="menu-title">Simpanan</span>
            </a>
            <x-nav-link toggleId="simpanan" href="{{ route('index.simpanan-wajib') }}">Wajib</x-nav-link>
            <x-nav-link toggleId="simpanan" href="{{ route('simpanan-pokok') }}">Pokok</x-nav-link>
            <x-nav-link toggleId="simpanan" href="{{ route('simpanan-sukarela') }}">Sukarela</x-nav-link>
            <x-nav-link toggleId="simpanan" href="{{ route('simpanan-berjangka') }}">Berjangka</x-nav-link>

        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#penarikansimpanan" aria-expanded="false" aria-controls="penarikansimpanan">
                <i class="fas fa-piggy-bank menu-icon"></i>
                <span class="menu-title">Penarikan Simpanan</span>
            </a>

            <div class="collapse" id="penarikansimpanan">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('penarikan.view') }}">Penarikan Simpanan</a>
                    </li>
                </ul>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#pinjaman" aria-expanded="false" aria-controls="pinjaman">
                <i class="fas fa-wallet menu-icon"></i>
                <span class="menu-title">Pinjaman</span>
            </a>
            <x-nav-link toggleId="pinjaman" href="{{ route('data.emergency') }}">Emergency</x-nav-link>
            <x-nav-link toggleId="pinjaman" href="{{ route('data.angunan') }}">Angunan</x-nav-link>
            <x-nav-link toggleId="pinjaman" href="{{ route('data.nonangunan') }}">Tanpa Angunan</x-nav-link>
        </li>

    </ul>
</nav>
