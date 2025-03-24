<nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
    <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
        <a class="navbar-brand brand-logo mr-5">
            <img src="{{ asset('skydash') }}/images/logo.png" class="mr-1 img-circle" alt="logo">
        </a>
        <a class="navbar-brand brand-logo-mini">
            <img src="{{ asset('skydash') }}/images/logo.png" alt="logo">
        </a>
    </div>
    <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
        <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
            <span class="icon-menu"></span>
        </button>
        <!-- Dashboard Info -->
        <ul class="navbar-nav mr-lg-2">
            <div class="container-fluid">
                <div class="input-group flex-column">
                    <p class="main-text mb-0">Dashboard</p>
                    <p class="sub-text mb-0">Dashboard Koperasi Konsumen Karlisna PLN Yogyakarta</p>
                </div>
            </div>
        </ul>

        <!-- Notification Bell -->
        <ul class="navbar-nav navbar-nav-right">
        </ul>
        <div class="dropdown">
            <div class="profile">
                <img alt="Profile picture of the user" height="30"
                    src="https://storage.googleapis.com/a1aa/image/1KOd58eSuRzmWyfXAUpQEdjtfonRfybwZO4UpIxenaye3ce0JA.jpg"
                    width="30" />
                <span>
                    <span class="user-role">{{ Auth()->user()->roles }}</span>
                </span>
                <i class="fas fa-caret-down">
                </i>
            </div>
            <div class="dropdown-content">
                <div class="header">
                    <img alt="Profile picture of the user" height="40"
                        src="https://storage.googleapis.com/a1aa/image/1KOd58eSuRzmWyfXAUpQEdjtfonRfybwZO4UpIxenaye3ce0JA.jpg"
                        width="40" />
                    <div class="info">
                        <p class="name">
                        <h4>{{ Auth()->user()->name }}</h4>
                        </p>
                        <p class="email">
                        <p class="text-muted">{{ Auth()->user()->email }}</p>
                        </p>
                        <div class="view-profile">
                        </div>
                    </div>
                </div>
                <div class="menu">
                    <a href="{{ route('setting-profile') }}">
                        Setting
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button id="logout-button" class="btn" type="button">
                            Sign Out
                        </button>
                    </form>
                </div>

            </div>

            <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button"
                data-toggle="offcanvas">
                <span class="icon-menu"></span>
            </button>
        </div>
</nav>

<script>
    document.getElementById('logout-button').addEventListener('click', function(e) {
        e.preventDefault(); // Mencegah form dikirim langsung

        Swal.fire({
            title: 'Apakah Kamu ingin Keluar?',
            text: "Kamu akan keluar dari akunmu!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, log me out!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('logout-form').submit();
            }
        });
    });
</script>
