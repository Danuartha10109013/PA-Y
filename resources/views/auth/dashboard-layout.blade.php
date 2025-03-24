<!DOCTYPE html>
<html lang="en">

@include('layouts.partials.meta')

<body>
    <div class="container-scroller">
        <!-- partial:partials/_navbar.html -->

        <!-- partial -->
        <div class="container-fluid page-body-wrapper mt-5">
            <!-- partial:partials/_sidebar.html -->


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
