<!DOCTYPE html>
<html lang="id">
<!-- Vendor CSS Files -->
<link href="{{ asset('Landingpace') }}/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link href="{{ asset('Landingpace') }}/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
<link href="{{ asset('Landingpace') }}/vendor/aos/aos.css" rel="stylesheet">
<link href="{{ asset('Landingpace') }}/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
<link href="{{ asset('Landingpace') }}/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

<!-- Main CSS File -->
<link href="{{ asset('Landingpace/css/register.css') }}" rel="stylesheet">

<link rel="stylesheet" href="https://code.jquery.com/ui/1.14.0/themes/base/jquery-ui.css">
<link rel="stylesheet" href="/resources/demos/style.css">
<!-- intl-tel-input CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css">

<!-- jQuery and jQuery UI -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://code.jquery.com/ui/1.14.0/jquery-ui.js"></script>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
    integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
    integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGaHxs3f4RbWEpbqS5Z7lDaI8A" crossorigin="anonymous">
</script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
    integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIyL9nI6zQTuBe6RZ+jWI47lclp5HIWdBvNDg9WE" crossorigin="anonymous">
</script>

<!-- intl-tel-input JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js"></script>
</head>

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Registrasi</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <div class="register-container">
        <div class="register-header">
            <h2>Register</h2>
            <button class="close-button">&times;</button>
        </div>
        <form class="register-form" action="{{ route('regis') }}" method="POST">
            @csrf
            <div class="register-form">
                <!-- Baris pertama: Dua kolom, Nama dan Alamat Domisili -->
                <div class="form-row form-row-double">
                    <div class="form-group">
                        <label for="nama">Nama <span class="required">*</span></label>
                        <input type="text" name='nama' placeholder="Masukkan nama Anda">
                    </div>
                    <div class="form-group">
                        <label for="alamat_domisili">Alamat Domisili <span class="required">*</span></label>
                        <input type="text" name='alamat_domisili' placeholder="Masukkan alamat domisili Anda">
                    </div>
                </div>

                <!-- Baris kedua: Dua kolom, Tempat Lahir dan Alamat KTP -->
                <div class="form-row form-row-double">
                    <div class="form-group">
                        <label for="tempat_lahir">Tempat Lahir <span class="required">*</span></label>
                        <input type="text" name='tempat_lahir' placeholder="Masukkan tempat lahir Anda">
                    </div>
                    <div class="form-group">
                        <label for="alamat_ktp">Alamat KTP <span class="required">*</span></label>
                        <input type="text" name='alamat_ktp' placeholder="Masukkan alamat KTP Anda">
                    </div>
                </div>

                <!-- Baris berikutnya: Satu kolom per baris -->
                <div class="form-group">
                    <label for="tgl_lahir">Tgl Lahir <span class="required">*</span></label>
                    <input type="date" name='tgl_lahir' placeholder="Masukkan tgl lahir Anda">
                </div>

                <div class="form-group">
                    <label for="nik">NIK <span class="required">*</span></label>
                    <input type="number" name='nik' placeholder="Masukkan NIK Anda">
                </div>

                <div class="form-group">
                    <label for="email_kantor">Email Kantor <span class="required">*</span></label>
                    <input type="email" name='email_kantor' placeholder="Masukkan email kantor Anda">
                </div>

                <div class="form-group">
                    <label for="no_handphone">No Handphone <span class="required">*</span></label>
                    <div class="phone-input">
                        <span class="country-code">+62</span>
                        <input id="mobile_code" type="tel" name='no_handphone'
                            placeholder="Masukkan no handphone Anda"
                            onkeypress="return event.charCode >= 48 && event.charCode <= 57">
                    </div>
                </div>

                <!-- <div class="form-group">
                    <label for="password">Password <span class="required">*</span></label>
                    <div class="password-wrapper">
                        <input type="password" id="password" name='password' placeholder="Masukkan password Anda">
                        <span class="password-toggle-icon">
                            <i class="bi bi-eye-slash" id="togglePassword"></i>
                        </span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="konfirmasi_password">Konfirmasi Password <span class="required">*</span></label>
                    <div class="password-wrapper">
                        <input type="password" id="password_confirmation" name='password_confirmation' placeholder="Konfirmasi password Anda">
                        <span class="password-toggle-icon">
                            <i class="bi bi-eye-slash" id="togglePasswordConfirmation"></i>
                        </span>
                    </div>
                </div> -->

                <div class="form-row">
                    <button type="submit" class="register-button">Register</button>
                </div>
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
        </form>
    </div>
    <script>
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');
        togglePassword.addEventListener('click', function() {
            // Toggle the type attribute
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);

            // Toggle the icon
            this.classList.toggle('bi-eye');
            this.classList.toggle('bi-eye-slash');
        });

        const togglePasswordConfirmation = document.querySelector('#togglePasswordConfirmation');
        const passwordConfirmation = document.querySelector('#password_confirmation');
        togglePasswordConfirmation.addEventListener('click', function() {
            // Toggle the type attribute
            const type = passwordConfirmation.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordConfirmation.setAttribute('type', type);

            // Toggle the icon
            this.classList.toggle('bi-eye');
            this.classList.toggle('bi-eye-slash');
        });
    </script>
    <script>
        document.querySelector('.close-button').addEventListener('click', function() {
            // Redirect to the landing page route
            window.location.href = "{{ route('landingpage') }}";
        });
    </script>

</body>

</html>
