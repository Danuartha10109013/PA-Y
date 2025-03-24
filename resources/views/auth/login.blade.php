@extends('layouts.auth-layout')
@section('title', $title)
@section('content')
<!-- Login 4 - Bootstrap Brain Component -->
<section class="p-3 p-md-4 p-xl-5">
    <div class="container">
        <div class="card border-light-subtle shadow-sm">
            <div class="row g-0">
                <div class="col-12 col-md-6 position-relative">
                    <img class="img-fluid rounded-start w-100 h-100 object-fit-cover" loading="lazy"
                        src="{{ asset('skydash') }}/images/Login.jpg" alt="BootstrapBrain Logo">
                    <div class="overlay"></div>
                </div>

                <div class="col-12 col-md-6 position-relative">
                    <button type="button" class="close-button position-absolute top-0 end-0"
                        style="background: none; border: none; font-size: 24px; color: black; padding: 10px;">&times;</button>
                    <div class="card-body p-3 p-md-4 p-xl-5">
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-5">
                                    <img src="{{ asset('Landingpace') }}/img/logo.png" lt="Logo"
                                        class="mb-3 logo-class img-fluid" style="max-width: 150px;">
                                    <h3>Log in</h3>
                                </div>
                            </div>
                        </div>
                        <form action="{{ route('login-verifikasi') }}" method="POST">
                            @csrf
                            <div class="row gy-3 gy-md-4 overflow-hidden">
                                <div class="col-12">
                                    <label for="email" class="form-label">Email Kantor <span
                                            class="text-danger">*</span></label>
                                    <input type="email" class="form-control" name="email" id="email"
                                        placeholder="name@example.com" required autofocus>
                                </div>
                                <div class="col-12">
                                    <label for="password" class="form-label">Password <span
                                            class="text-danger">*</span></label>
                                    <div class="password-wrapper">
                                        <input type="password" class="form-control" name="password" id="password"
                                            placeholder="Masukan password anda" required>
                                        <i class="bi bi-eye-slash" id="togglePassword"></i>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value=""
                                            name="remember_me" id="remember_me">
                                        <label class="form-check-label text-secondary" for="remember_me">
                                            Keep me logged in
                                        </label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="d-grid">
                                        <button class="btn bsb-btn-xl btn-primary" type="submit">Log in</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="row">
                            <div class="col-12">
                                <hr class="mt-5 mb-4 border-secondary-subtle">
                                <div class="d-flex gap-2 gap-md-4 flex-column flex-md-row justify-content-md-end">
                                    <a href="{{ route('register') }}" class="link-secondary text-decoration-none">Create new account</a>
                                    <a href="#!" class="link-secondary text-decoration-none">Forgot password</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
