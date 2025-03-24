<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Registrasi</title>

    <!-- Vendor CSS Files -->
    <link href="{{ asset('Landingpace/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('Landingpace/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('Landingpace/vendor/aos/aos.css') }}" rel="stylesheet">
    <link href="{{ asset('Landingpace/vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet">
    <link href="{{ asset('Landingpace/vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet">

    <!-- Main CSS File -->
    <link href="{{ asset('Landingpace/css/register.css') }}" rel="stylesheet">

    <!-- Additional CSS -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.14.0/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css">
</head>

<body>
    <div class="register-container">
        <div class="register-header">
            <h2>Register</h2>
            <button type="button" class="close-button">&times;</button>
        </div>
        <form class="register-form" action="{{ route('iuran-register') }}" method="any">
            @csrf
            <!-- Informasi Akun -->
            <div class="form-group">
                <label for="email_kantor">Email Kantor <span class="required">*</span></label>
                <input type="email" name="email_kantor" value="{{ old('email_kantor') }}"
                    placeholder="Masukkan email kantor Anda" required>
                @error('email_kantor')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            {{--  <div class="form-group">
                <label for="password">Password <span class="required">*</span></label>
                <div class="password-wrapper">
                    <input type="password"
                           id="password"
                           name="password"
                           placeholder="Masukkan password Anda"
                           required>
                    <span class="password-toggle-icon">
                        <i class="bi bi-eye-slash" id="togglePassword"></i>
                    </span>
                </div>
                @error('password')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>  --}}

            <!-- Informasi Pribadi -->
            <div class="form-row form-row-double">
                <div class="form-group">
                    <label for="nama">Nama <span class="required">*</span></label>
                    <input type="text" name="nama" value="{{ old('nama') }}" placeholder="Masukkan nama Anda"
                        required>
                    @error('nama')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="alamat_domisili">Alamat Domisili <span class="required">*</span></label>
                    <input type="text" name="alamat_domisili" value="{{ old('alamat_domisili') }}"
                        placeholder="Masukkan alamat domisili Anda" required>
                    @error('alamat_domisili')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-row form-row-double">
                <div class="form-group">
                    <label for="tempat_lahir">Tempat Lahir <span class="required">*</span></label>
                    <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir') }}"
                        placeholder="Masukkan tempat lahir Anda" required>
                    @error('tempat_lahir')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="tgl_lahir">Tanggal Lahir <span class="required">*</span></label>
                    <input type="date" name="tgl_lahir" value="{{ old('tgl_lahir') }}" required>
                    @error('tgl_lahir')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="alamat_ktp">Alamat KTP <span class="required">*</span></label>
                <input type="text" name="alamat_ktp" value="{{ old('alamat_ktp') }}"
                    placeholder="Masukkan alamat KTP Anda" required>
                @error('alamat_ktp')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="nik">NIK <span class="required">*</span></label>
                <input type="number" name="nik" value="{{ old('nik') }}" placeholder="Masukkan NIK Anda"
                    maxlength="16" pattern="[0-9]{16}" required>
                @error('nik')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="no_handphone">No Handphone <span class="required">*</span></label>
                <div class="phone-input">
                    <span class="country-code">+62</span>
                    <input id="mobile_code" type="tel" name="no_handphone" value="{{ old('no_handphone') }}"
                        placeholder="Masukkan no handphone Anda" pattern="[0-9]*" required>
                </div>
                @error('no_handphone')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <!-- Alert Messages -->
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="form-row">
                <button type="submit" class="register-button">Register</button>
            </div>
        </form>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://code.jquery.com/ui/1.14.0/jquery-ui.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Password Toggle
            const togglePassword = document.querySelector('#togglePassword');
            const passwordField = document.querySelector('#password');

            if (togglePassword && passwordField) {
                togglePassword.addEventListener('click', function() {
                    const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordField.setAttribute('type', type);
                    this.classList.toggle('bi-eye');
                    this.classList.toggle('bi-eye-slash');
                });
            }

            // Close Button
            const closeButton = document.querySelector('.close-button');
            if (closeButton) {
                closeButton.addEventListener('click', function() {
                    window.location.href = "{{ route('landingpage') }}";
                });
            }

            // NIK Validation
            const nikField = document.querySelector('input[name="nik"]');
            if (nikField) {
                nikField.addEventListener('input', function() {
                    this.value = this.value.replace(/[^0-9]/g, '').slice(0, 16);
                });
            }

            // Phone Number Validation
            const mobileCodeField = document.querySelector('#mobile_code');
            if (mobileCodeField) {
                mobileCodeField.addEventListener('input', function() {
                    this.value = this.value.replace(/[^0-9]/g, '');
                });
            }
        });
    </script>
</body>

</html>
