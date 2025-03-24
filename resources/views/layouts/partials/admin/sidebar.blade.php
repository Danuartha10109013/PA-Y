<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item">
            <a class="nav-link" href="{{ route('home-admin') }}">
                <i class="icon-grid menu-icon"></i>
                <span class="menu-title">Dashboard</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#ui-basic" role="button" aria-expanded="false"
                aria-controls="ui-basic">
                <i class="bi bi-person-square menu-icon"></i>
                <span class="menu-title">Anggota</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="ui-basic">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('data-anggota') }}">Data Anggota</a>
                    </li>
                </ul>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#charts" aria-expanded="false" aria-controls="charts">
                <i class="fas fa-piggy-bank menu-icon"></i>
                <span class="menu-title">Mutasi Simpanan</span>
                <i class="menu-arrow"></i>
            </a>
            <x-nav-link toggleId="charts" href="">Wajib</x-nav-link>
            <x-nav-link toggleId="charts" href="">Pokok</x-nav-link>
            <x-nav-link toggleId="charts" href="{{ route('admin.mutasi.sukarela') }}">Sukarela</x-nav-link>
            <x-nav-link toggleId="charts" href="">Berjangka</x-nav-link>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#pinjaman" aria-expanded="false" aria-controls="pinjaman">
                <i class="fas fa-wallet menu-icon"></i>
                <span class="menu-title">Mutasi Pinjaman</span>
                <i class="menu-arrow"></i>
            </a>
            <x-nav-link toggleId="pinjaman" href="{{ route('admin.mutasi.emergency') }}">Emergency</x-nav-link>
            <x-nav-link toggleId="pinjaman" href="{{ route('admin.mutasi.angunan') }}">Dengan Angunan</x-nav-link>
            <x-nav-link toggleId="pinjaman" href="{{ route('admin.mutasi.nonangunan') }}">Tanpa Angunan</x-nav-link>
        </li>

        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#potongan" aria-expanded="false" aria-controls="potongan">
                <i class="fas fa-wallet menu-icon"></i>
                <span class="menu-title">Potongan Gaji</span>
                <i class="menu-arrow"></i>
            </a>
            <x-nav-link toggleId="potongan" href="{{ route('data.potongan.gaji') }}">Pinjaman</x-nav-link>
            <x-nav-link toggleId="potongan" href="">Simpanan</x-nav-link>
        </li>

    </ul>
    </li>

    <!-- Tambahkan elemen lain seperti di atas -->

    </ul>
</nav>
