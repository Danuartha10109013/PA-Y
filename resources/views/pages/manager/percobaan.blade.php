<!DOCTYPE html>
<html lang="en">

<head>
    @include('layout.load1_css')
    <title>Data Anggota</title>
</head>
</div>
@include('layout.navbar1')
</div>

<body>
    <div class="container-scroller">

        <!-- partial:partials/_navbar.html -->
        <!-- partial -->
        <div class="container-fluid page-body-wrapper">
            @include('manager.layout.sidebar2')
            <div id="right-sidebar" class="settings-panel">
                <i class="settings-close ti-close"></i>
                <ul class="nav nav-tabs border-top" id="setting-panel" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="todo-tab" data-toggle="tab" href="#todo-section" role="tab" aria-controls="todo-section" aria-expanded="true">TO DO LIST</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="chats-tab" data-toggle="tab" href="#chats-section" role="tab" aria-controls="chats-section">CHATS</a>
                    </li>
                </ul>
                <div class="tab-content" id="setting-content">
                    <div class="tab-pane fade show active scroll-wrapper" id="todo-section" role="tabpanel" aria-labelledby="todo-section">
                        <div class="add-items d-flex px-3 mb-0">
                            <form class="form w-100">
                                <div class="form-group d-flex">
                                    <input type="text" class="form-control todo-list-input" placeholder="Add To-do">
                                    <button type="submit" class="add btn btn-primary todo-list-add-btn" id="add-task">Add</button>
                                </div>
                            </form>
                        </div>
                        <div class="list-wrapper px-3">
                            <ul class="d-flex flex-column-reverse todo-list">
                                <li>
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input class="checkbox" type="checkbox">
                                            Team review meeting at 3.00 PM
                                        </label>
                                    </div>
                                    <i class="remove ti-close"></i>
                                </li>
                                <li>
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input class="checkbox" type="checkbox">
                                            Prepare for presentation
                                        </label>
                                    </div>
                                    <i class="remove ti-close"></i>
                                </li>
                                <li>
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input class="checkbox" type="checkbox">
                                            Resolve all the low priority tickets due today
                                        </label>
                                    </div>
                                    <i class="remove ti-close"></i>
                                </li>
                                <li class="completed">
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input class="checkbox" type="checkbox" checked>
                                            Schedule meeting for next week
                                        </label>
                                    </div>
                                    <i class="remove ti-close"></i>
                                </li>
                                <li class="completed">
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input class="checkbox" type="checkbox" checked>
                                            Project review
                                        </label>
                                    </div>
                                    <i class="remove ti-close"></i>
                                </li>
                            </ul>
                        </div>
                        <h4 class="px-3 text-muted mt-5 font-weight-light mb-0">Events</h4>
                        <div class="events pt-4 px-3">
                            <div class="wrapper d-flex mb-2">
                                <i class="ti-control-record text-primary mr-2"></i>
                                <span>Feb 11 2018</span>
                            </div>
                            <p class="mb-0 font-weight-thin text-gray">Creating component page build a js</p>
                            <p class="text-gray mb-0">The total number of sessions</p>
                        </div>
                        <div class="events pt-4 px-3">
                            <div class="wrapper d-flex mb-2">
                                <i class="ti-control-record text-primary mr-2"></i>
                                <span>Feb 7 2018</span>
                            </div>
                            <p class="mb-0 font-weight-thin text-gray">Meeting with Alisa</p>
                            <p class="text-gray mb-0 ">Call Sarah Graves</p>
                        </div>
                    </div>
                    <!-- To do section tab ends -->
                    <div class="search-bar">
                        <!-- <input type="text" placeholder="Search" /> -->
                        <input type="text" placeholder="Search" class="form-control mr-2" style="width: 200px;" />
                        <button type="submit" class="btn btn-success mr-2" onclick="Diterima('')">Diterima</button>
                        <button type="submit" class="btn btn-danger mr-2" onclick="Ditolak('')">Ditolak</button>
                        <form action="" method="POST" class="d-inline">
                            @csrf
                            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

                    </div>
                    <div class="filter-buttons">
                        <div class="btn-wrapper">
                            <span class="count">5</span>
                            <button>All</button>
                        </div>
                        <div class="btn-wrapper">
                            <span class="count">3</span>
                            <button>Terima</button>
                        </div>
                        <div class="btn-wrapper">
                            <span class="count">2</span>
                            <button>Belum Diterima</button>
                        </div>
                        <div class="btn-wrapper">
                            <span class="count">1</span>
                            <button>Ditolak</button>
                        </div>
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
                                    <th>Tempat Lahir</th>
                                    <th>Tgl Lahir</th>
                                    <th>NIK</th>
                                    <th>Email Kantor</th>
                                    <th>No Handphone</th>
                                    <th>Alamat Domisili</th>
                                    <th>Alamat KTP</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>

                                <tr>
                                    <td>
                                        <div class="custom-control custom-checkbox">


                                        </div>
                                    </td>

                                    <a href="" type="submit" class="button button-blue">Detail</a>
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- content-wrapper ends -->
                <!-- Footer -->
                <footer class="footer">
                    <div class="container-fluid text-center">
                        <nav class="d-flex justify-content-center">
                            <ul class="nav">
                                <li class="nav-item">
                                    <a class="nav-link" href="http://www.themekita.com">
                                        Copyright © 2024 Koperasi Konsumen Karlisna PLN Yogyakarta. All Rights Reserved.
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </footer>
            </div>
            <!-- main-panel ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->

    <!-- plugins:js -->
    <script src="{{asset('skydash')}}/vendors/js/vendor.bundle.base.js"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <script src="{{asset('skydash')}}/vendors/chart.js/Chart.min.js"></script>
    <script src="{{asset('skydash')}}/vendors/datatables.net/jquery.dataTables.js"></script>
    <script src="{{asset('skydash')}}/vendors/datatables.net-bs4/dataTables.bootstrap4.js"></script>
    <script src="{{asset('skydash')}}/js/dataTables.select.min.js"></script>

    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="{{asset('skydash')}}/js/off-canvas.js"></script>
    <script src="{{asset('skydash')}}/js/hoverable-collapse.js"></script>
    <script src="{{asset('skydash')}}/js/template.js"></script>
    <script src="{{asset('skydash')}}/js/settings.js"></script>
    <script src="{{asset('skydash')}}/js/todolist.js"></script>
    <!-- endinject -->
    <!-- Custom js for this page-->
    <script src="{{asset('skydash')}}/js/dashboard.js"></script>
    <script src="{{asset('skydash')}}/js/Chart.roundedBarCharts.js"></script>
    <!-- End custom js for this page-->
</body>

</html>