@extends('layouts.dashboard-layout')
@section('title', $title)

@section('content')
    <div class="d-flex mt-3">
        <i class="fas fa-arrow-left mr-3 mt-1" style="cursor: pointer;" data-role="{{ Auth::user()->roles }}"
            onclick="goBackToHome(this)"></i>
        <h3 class="">Settings</h3>
    </div>
    <div class="container-fluid p-3"> <!-- Menggunakan container-fluid dan padding yang lebih baik -->
        <div class="row">
            <div class="col-md-5 col-xl-4">

                <div class="card">
                    <div class="card-header">

                        <h5 class="card-title mb-0">Profile Settings</h5>
                    </div>

                    <div class="list-group list-group-flush" role="tablist">
                        <a class="list-group-item list-group-item-action active" data-toggle="list" href="#account"
                            role="tab">
                            Account
                        </a>
                        <a class="list-group-item list-group-item-action" data-toggle="list" href="#password"
                            role="tab">
                            Password
                        </a>
                        @if (Auth::user()->roles === 'anggota')
                            <a class="list-group-item list-group-item-action" data-toggle="list" href="#virtualAcount"
                                role="tab">
                                Virtual Account
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-7 col-xl-8">
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="account" role="tabpanel">

                        <div class="card">
                            <div class="card-header">
                                <div class="card-actions float-right">
                                    <div class="dropdown show">
                                        <a href="#" data-toggle="dropdown" data-display="static">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="feather feather-more-horizontal align-middle">
                                                <circle cx="12" cy="12" r="1"></circle>
                                                <circle cx="19" cy="12" r="1"></circle>
                                                <circle cx="5" cy="12" r="1"></circle>
                                            </svg>
                                        </a>

                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a class="dropdown-item" href="#">Action</a>
                                            <a class="dropdown-item" href="#">Another action</a>
                                            <a class="dropdown-item" href="#">Something else here</a>
                                        </div>
                                    </div>
                                </div>
                                <h5 class="card-title mb-0">Public info</h5>
                            </div>
                            <div class="card-body">
                                <form>
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label for="inputUsername">Username</label>
                                                <input type="text" class="form-control" value="{{ Auth::user()->name }}"
                                                    id="inputUsername" placeholder="Username">
                                            </div>
                                            <div class="form-group">
                                                <label for="inputUsername">Email</label>
                                                <!-- <textarea rows="2" class="form-control" id="inputBio" value="{{ Auth::user()->email }}"
                                                    placeholder="Tell something about yourself"></textarea> -->
                                                <input type="text" class="form-control" value="{{ Auth::user()->email }}"
                                                    id="inputEmail" placeholder="Email">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="text-center">
                                                <img alt="Profile picture of the user"
                                                    src="https://storage.googleapis.com/a1aa/image/1KOd58eSuRzmWyfXAUpQEdjtfonRfybwZO4UpIxenaye3ce0JA.jpg"
                                                    width="128" height="128">
                                                <div class="mt-2">
                                                    <span class="btn btn-primary"><i class="fa fa-upload"></i></span>
                                                </div>
                                                <small>For best results, use an image at least 128px by 128px in .jpg
                                                    format</small>
                                            </div>
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                </form>

                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <div class="card-actions float-right">
                                    <div class="dropdown show">
                                        <a href="#" data-toggle="dropdown" data-display="static">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="feather feather-more-horizontal align-middle">
                                                <circle cx="12" cy="12" r="1"></circle>
                                                <circle cx="19" cy="12" r="1"></circle>
                                                <circle cx="5" cy="12" r="1"></circle>
                                            </svg>
                                        </a>

                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a class="dropdown-item" href="#">Action</a>
                                            <a class="dropdown-item" href="#">Another action</a>
                                            <a class="dropdown-item" href="#">Something else here</a>
                                        </div>
                                    </div>
                                </div>
                                <h5 class="card-title mb-0">Private info</h5>
                            </div>
                            <div class="card-body">
                                <form>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="inputFirstName">First name</label>
                                            <input type="text" class="form-control" id="inputFirstName"
                                                placeholder="First name">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="inputLastName">Last name</label>
                                            <input type="text" class="form-control" id="inputLastName"
                                                placeholder="Last name">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail4">Email</label>
                                        <input type="email" class="form-control" id="inputEmail4" placeholder="Email">
                                    </div>
                                    <div class="form-group">
                                        <label for="inputAddress">Address</label>
                                        <input type="text" class="form-control" id="inputAddress"
                                            placeholder="1234 Main St">
                                    </div>
                                    <div class="form-group">
                                        <label for="inputAddress2">Address 2</label>
                                        <input type="text" class="form-control" id="inputAddress2"
                                            placeholder="Apartment, studio, or floor">
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="inputCity">City</label>
                                            <input type="text" class="form-control" id="inputCity">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="inputState">State</label>
                                            <select id="inputState" class="form-control">
                                                <option selected="">Choose...</option>
                                                <option>...</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label for="inputZip">Zip</label>
                                            <input type="text" class="form-control" id="inputZip">
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                </form>

                            </div>
                        </div>

                    </div>
                    <div class="tab-pane fade" id="password" role="tabpanel">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Password</h5>

                                @if (session('success'))
                                    <div class="alert alert-success">{{ session('success') }}</div>
                                @endif

                                <form action="{{ route('forgot-password', ['id' => auth()->user()->id]) }}"
                                    method="POST">
                                    @csrf
                                    <div class="form-group position-relative">
                                        <label for="current_password">Current password</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" id="current_password"
                                                name="current_password" required>
                                            <span class="input-group-text">
                                                <i class="bi bi-eye-slash toggle-password" data-target="current_password"
                                                    style="cursor: pointer;"></i>
                                            </span>
                                        </div>
                                        <small><a href="#">Forgot your password?</a></small>
                                        @error('current_password')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group position-relative">
                                        <label for="new_password">New password</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" id="new_password"
                                                name="new_password" required>
                                            <span class="input-group-text">
                                                <i class="bi bi-eye-slash toggle-password" data-target="new_password"
                                                    style="cursor: pointer;"></i>
                                            </span>
                                        </div>
                                        @error('new_password')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group position-relative">
                                        <label for="new_password_confirmation">Verify password</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" id="new_password_confirmation"
                                                name="new_password_confirmation" required>
                                            <span class="input-group-text">
                                                <i class="bi bi-eye-slash toggle-password"
                                                    data-target="new_password_confirmation" style="cursor: pointer;"></i>
                                            </span>
                                        </div>
                                    </div>


                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    {{-- <-- Virtual Account --> --}}
                    @if (Auth::user()->roles === 'anggota')
                        {{-- <-- Virtual Account --> --}}
                        <div class="tab-pane fade" id="virtualAcount" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead style="background-color: #EEEEEE;">
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Bank</th>
                                            <th>Rekening</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($virtualAccounts as $key => $virtualAccount)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $virtualAccount->nama_bank }}</td>
                                                <td>{{ $virtualAccount->virtual_account_number }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Virtual Account</h5>

                                    @if (session('success'))
                                        <div class="alert alert-success">{{ session('success') }}</div>
                                    @endif

                                    <form action="{{ route('virtual-account', ['id' => auth()->user()->id]) }}"
                                        method="POST">
                                        @csrf
                                        <div class="form-group position-relative">
                                            <label for="nama_bank">Nama Bank</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="nama_bank"
                                                    name="nama_bank" required>
                                            </div>
                                            @error('nama_bank')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group position-relative">
                                            <label for="virtual_account_number">No Rekening</label>
                                            <div class="input-group">
                                                <input type="number" class="form-control" id="virtual_account_number"
                                                    name="virtual_account_number" required>
                                            </div>
                                            @error('virtual_account_number')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <button type="submit" class="btn btn-primary">Save changes</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif


                </div>
            </div>
        </div>

    </div>
    <script>
        document.querySelectorAll('.toggle-password').forEach(item => {
            item.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const targetField = document.getElementById(targetId);
                const isPassword = targetField.getAttribute('type') === 'password';

                targetField.setAttribute('type', isPassword ? 'text' : 'password');
                this.classList.toggle('bi-eye');
                this.classList.toggle('bi-eye-slash');
            });
        });
    </script>
    <script>
        function goBackToHome(element) {
            // Get the user's role from the data-role attribute
            const roles = element.getAttribute('data-role');
            let route = '';

            // Determine the route based on the user's role
            if (roles === 'anggota') {
                route = "{{ route('home-anggota') }}";
            } else if (roles === 'manager') {
                route = "{{ route('home.manager') }}";
            } else if (roles === 'ketua') {
                route = "{{ route('home-ketua') }}";
            } else if (roles === 'admin') {
                route = "{{ route('home-admin') }}";
            }

            // Redirect to the determined route
            if (route) {
                window.location.href = route;
            }
        }
    </script>


@endsection
