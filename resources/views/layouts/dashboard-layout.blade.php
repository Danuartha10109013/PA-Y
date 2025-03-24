<!DOCTYPE html>
<html lang="en">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">


@include('layouts.partials.meta')

<body>
    <div class="container-scroller">
        <!-- partial:partials/_navbar.html -->
        @include('layouts.partials.navbar')
        <!-- partial -->
        <div class="container-fluid page-body-wrapper mt-5">
            <!-- partial:partials/_sidebar.html -->
            @if (auth()->user()->hasRole('manager'))
                @include('layouts.partials.manager.sidebar')
            @elseif (auth()->user()->hasRole('ketua'))
                @include('layouts.partials.ketua.sidebar')
            @elseif (auth()->user()->hasRole('admin'))
                @include('layouts.partials.admin.sidebar')
            @elseif (auth()->user()->hasRole('anggota'))
                @include('layouts.partials.anggota.sidebar')
            @elseif (auth()->user()->hasRole('bendahara'))
                @include('layouts.partials.bendahara.sidebar')
            @endif

            <!-- partial -->
            <div class="main-panel">
                <div class="content-wrapper pt-3">
                    @yield('content')
                </div>
                @include('layouts.partials.footer')
            </div>
            <!-- main-panel ends -->
        </div>
    </div>
    <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    @include('layouts.partials.script')
</body>

</html>
