<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Landingpage Karlisna</title>
    <meta name="description" content="">
    <meta name="keywords" content="">

    <!-- Favicons -->
    <link href="{{ asset('Landingpace') }}/img/logo.png" rel="icon">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">

    <!-- {{ asset('Landingpace') }}/Vendor CSS Files -->
    <link href="{{ asset('Landingpace') }}/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('Landingpace') }}/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('Landingpace') }}/vendor/aos/aos.css" rel="stylesheet">
    <link href="{{ asset('Landingpace') }}/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
    <link href="{{ asset('Landingpace') }}/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

    <!-- Main CSS File -->
    <link href="{{ asset('Landingpace') }}/css/main.css" rel="stylesheet">

    <!-- =======================================================
  * Template Name: BizLand
  * Template URL: https://bootstrapmade.com/bizland-bootstrap-business-template/
  * Updated: Aug 07 2024 with Bootstrap v5.3.3
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body class="index-page">

    <header id="header" class="header sticky-top">

        <!-- <div class="topbar d-flex align-items-center">
            <div class="container d-flex justify-content-center justify-content-md-between">
                <div class="contact-info d-flex align-items-center">
                    <i class="bi bi-envelope d-flex align-items-center"><a href="mailto:contact@example.com">contact@example.com</a></i>
                    <i class="bi bi-phone d-flex align-items-center ms-4"><span>+1 5589 55488 55</span></i>
                </div>
                <div class="social-links d-none d-md-flex align-items-center">
                    <a href="#" class="twitter"><i class="bi bi-twitter-x"></i></a>
                    <a href="#" class="facebook"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="instagram"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="linkedin"><i class="bi bi-linkedin"></i></a>
                </div>
            </div>
        </div>  -->

        <!-- End Top Bar -->

        <div class="branding d-flex align-items-cente">

            <div class="container position-relative d-flex align-items-center justify-content-between">
                <a href="index.html" class="logo d-flex align-items-center">
                    <!-- Uncomment the line below if you also wish to use an image logo -->
                    <img src="{{ asset('Landingpace') }}/img/logo.png" alt="">
                    <!-- <h1 class="sitename">Yuni</h1> -->
                </a>

                <nav id="navmenu" class="navmenu">
                    <ul>
                        <li><a href="#bg" class="active">Home</a></li>
                        <li><a href="#about">Tentang Kami</a></li>
                        <li><a href="#faq">FAQ</a></li>
                        <li><a href="#featured-services">Layanan</a></li>
                        @auth
                        {{-- <li><a href="{{ route('home-anggota') }}">Dashboard</a></li> --}}
                        <form id="logout-form" action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button id="logout-button" class="btn" type="submit">
                                Sign Out
                            </button>
                        </form>
                        @else
                        <li><a href="/login" class="btn btn-login">Login</a></li>
                        <li><a href="{{ route('register') }}" class="btn btn-register">Register</a></li>
                        @endauth
                    </ul>
                    <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>

                </nav>
            </div>

        </div>

    </header>

    <main class="main">

        <!-- bg Section -->
        <section id="bg" class="bg section drak-background">
            <div class="container">
                <div class="row gy-4">
                    <div class="col-lg-6 order-2 order-lg-1 d-flex flex-column justify-content-center"
                        data-aos="zoom-out">
                        <h1>Ekosistem Digital <span>Koperasi</span></h1>
                        <p>Mari Bersinergi Serta Berkolaborasi Membangun Ekosistem Koperasi yang Lebih Efektif, Efisien
                            dan Inovatif</p>
                        <div class="d-flex">
                            <a href="{{ route('login') }}" class="btn-get-started">Login</a>
                            <a href="{{ route('register') }}" class="btn-get-started">Registrasi</a>
                        </div>
                    </div>
                </div>
            </div>

        </section><!-- /bg Section -->
        <!-- About Section -->
        <section id="about" class="about section light-background">

            <!-- Section Title -->
            <<div class="row justify-content-center" data-aos="fade-up" data-aos-delay="100">
                <div class="col-lg-8 text-center">
                    <div class="container page-title" data-aos="fade-up">
                        <h1>Tentang Kami</h1>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut.
                            Labore et dolore magna aliqua.</p>
                    </div>
                </div>
                </div>

                <div class="container">

                    <div class="row gy-3">

                        <!-- Text Column -->
                        <div class="col-lg-6 d-flex flex-column justify-content-center" data-aos="fade-up"
                            data-aos-delay="200">
                            <div class="about-content ps-0 ps-lg-3">
                                <h3>Koperasi Karlisna</h3>
                                <div>
                                    <p>Koperasi Karlisna merupakan koperasi karyawan yang bergerak di bidang usaha
                                        simpan pinjam dan penjualan obat melalui unit usaha apotek, berdiri sejak tahun
                                        1968.</p>
                                </div>
                                <div>
                                    <p><br>Koperasi yang didirikan untuk anggota karyawan PLN (Perusahaan Listrik
                                        Negara) di Yogyakarta. Koperasi ini bertujuan untuk meningkatkan kesejahteraan
                                        anggotanya melalui berbagai layanan, seperti simpan pinjam, pembelian barang
                                        kebutuhan sehari-hari, dan berbagai program lainnya yang mendukung kesejahteraan
                                        anggota. Selain itu, koperasi ini juga berfungsi sebagai wadah untuk memperkuat
                                        tali silaturahmi dan kerjasama antar anggota. Koperasi semacam ini biasanya
                                        berorientasi pada pelayanan anggota dan komunitas.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Image Column -->
                        <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
                            <img src="{{ asset('Landingpace') }}/img/about.jpeg" alt="" class="img-fluid">
                        </div>

                    </div>

                </div>

        </section><!-- /About Section -->



        <!-- Image Content -->
        <div class="col-lg-6 order-lg-2 order-1" data-aos="fade-up" data-aos-delay="100">
            <!-- <img src="{{ asset('img') }}/about.jpeg" alt="" class="img-fluid about-image"> -->
        </div>
        </div>
        </div>


        <!-- FAQ Section -->
        <section id="faq" class="faq section white-background">
            <div class="container">
                <!-- Text Content Section - Centered -->
                <div class="row justify-content-center" data-aos="fade-up" data-aos-delay="100">
                    <div class="col-lg-8 text-center">
                        <div class="container page-title" data-aos="fade-up">
                            <h1>F.A.Q</h1>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt
                                ut. Labore et dolore magna aliqua.</p>
                        </div>
                    </div>
                </div>

                <!-- Image and FAQ Content -->
                <div class="row align-items-center">
                    <!-- Image Section -->
                    <div class="col-lg-6" data-aos="fade-right" data-aos-delay="200">
                        <div class="faq-image-wrapper">
                            <img src="{{ asset('Landingpace') }}/img/faq.png" alt="FAQ Image"
                                class="img-fluid faq-image">
                        </div>
                    </div>

                    <!-- FAQ Items -->
                    <div class="col-lg-6" data-aos="fade-left" data-aos-delay="100">
                        <div class="faq-container">
                            <!-- FAQ Item 1 -->
                            <div class="faq-item">
                                <div class="faq-question">
                                    <span class="faq-icon"><i class="bi bi-check-circle"></i></span>
                                    <h3>Lorem ipsum dolor sit amet, consectetur adipiscing elit?</h3>
                                    <span class="faq-toggle"><i class="bi bi-chevron-down"></i></span>
                                </div>
                                <div class="faq-answer">
                                    <p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut
                                        aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in
                                        voluptate velit esse cillum dolore eu fugiat.</p>
                                </div>
                            </div>

                            <!-- FAQ Item 2 -->
                            <div class="faq-item">
                                <div class="faq-question">
                                    <span class="faq-icon"><i class="bi bi-check-circle"></i></span>
                                    <h3>Lorem ipsum dolor sit amet, consectetur adipiscing elit?</h3>
                                    <span class="faq-toggle"><i class="bi bi-chevron-down"></i></span>
                                </div>
                            </div>

                            <!-- FAQ Item 3 -->
                            <div class="faq-item">
                                <div class="faq-question">
                                    <span class="faq-icon"><i class="bi bi-check-circle"></i></span>
                                    <h3>Lorem ipsum dolor sit amet, consectetur adipiscing elit?</h3>
                                    <span class="faq-toggle"><i class="bi bi-chevron-down"></i></span>
                                </div>
                            </div>

                            <!-- FAQ Item 4 -->
                            <div class="faq-item">
                                <div class="faq-question">
                                    <span class="faq-icon"><i class="bi bi-check-circle"></i></span>
                                    <h3>Lorem ipsum dolor sit amet, consectetur adipiscing elit?</h3>
                                    <span class="faq-toggle"><i class="bi bi-chevron-down"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Featured Services Section -->
        <section id="featured-services" class="featured-services section light-background">
            <div class="container">
                <div class="row gy-4">
                    <div class="row justify-content-center" data-aos="fade-up" data-aos-delay="100">
                        <div class="col-lg-8 text-center">
                            <div class="container page-title" data-aos="fade-up">
                                <h1>Layanan</h1>
                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut. Labore et dolore magna aliqua.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 d-flex" data-aos="fade-up" data-aos-delay="100">
                        <div class="service-item position-relative text-center">
                            <a href="https://www.example.com" target="_blank" class="d-flex flex-column align-items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="49" viewBox="0 0 48 49" fill="none">
                                    <path d="M23.4 40.841C22.89 40.841 22.4625 40.6695 22.1175 40.3265C21.7725 39.9835 21.6 39.5585 21.6 39.0515C21.6 38.5445 21.7725 38.116 22.1175 37.766C22.4625 37.416 22.89 37.241 23.4 37.241H37.2V23.891C37.2 20.2244 35.9203 17.0994 33.361 14.516C30.8017 11.9327 27.685 10.641 24.011 10.641C20.337 10.641 17.2167 11.9327 14.65 14.516C12.0833 17.0994 10.8 20.2195 10.8 23.8765V33.441C10.8 33.951 10.6275 34.3785 10.2825 34.7235C9.93749 35.0685 9.50999 35.241 8.99999 35.241H8.39999C7.40999 35.241 6.56249 34.8787 5.85749 34.154C5.15249 33.4294 4.79999 32.5584 4.79999 31.541V27.841C4.79999 27.0744 5.01665 26.391 5.44999 25.791C5.88332 25.191 6.46665 24.8077 7.19999 24.641L7.34999 22.091C7.64999 19.991 8.28332 18.0327 9.24999 16.216C10.2167 14.3994 11.4417 12.8244 12.925 11.491C14.4083 10.1577 16.0998 9.11603 17.9995 8.36603C19.8992 7.61603 21.8992 7.24103 23.9995 7.24103C26.0998 7.24103 28.0967 7.61703 29.99 8.36903C31.8837 9.12069 33.5753 10.1624 35.065 11.494C36.555 12.8254 37.7833 14.391 38.75 16.191C39.7167 17.991 40.35 19.941 40.65 22.041L40.8 24.641C41.5 24.8077 42.075 25.166 42.525 25.716C42.975 26.266 43.2 26.9077 43.2 27.641V31.841C43.2 32.5744 42.975 33.216 42.525 33.766C42.075 34.316 41.5 34.6744 40.8 34.841V37.241C40.8 38.231 40.4475 39.0785 39.7425 39.7835C39.0375 40.4885 38.19 40.841 37.2 40.841H23.4ZM18.6105 27.641C18.1035 27.641 17.675 27.4695 17.325 27.1265C16.975 26.7835 16.8 26.3585 16.8 25.8515C16.8 25.3445 16.9715 24.916 17.3145 24.566C17.6575 24.216 18.0825 24.041 18.5895 24.041C19.0965 24.041 19.525 24.2125 19.875 24.5555C20.225 24.8985 20.4 25.3235 20.4 25.8305C20.4 26.3375 20.2285 26.766 19.8855 27.116C19.5425 27.466 19.1175 27.641 18.6105 27.641ZM29.4105 27.641C28.9035 27.641 28.475 27.4695 28.125 27.1265C27.775 26.7835 27.6 26.3585 27.6 25.8515C27.6 25.3445 27.7715 24.916 28.1145 24.566C28.4575 24.216 28.8825 24.041 29.3895 24.041C29.8965 24.041 30.325 24.2125 30.675 24.5555C31.025 24.8985 31.2 25.3235 31.2 25.8305C31.2 26.3375 31.0285 26.766 30.6855 27.116C30.3425 27.466 29.9175 27.641 29.4105 27.641ZM13.25 25.141C12.95 21.9077 13.8783 19.116 16.035 16.766C18.1917 14.416 20.88 13.241 24.1 13.241C26.8333 13.241 29.2083 14.1577 31.225 15.991C33.2417 17.8244 34.4 20.1077 34.7 22.841C31.8667 22.841 29.2917 22.066 26.975 20.516C24.6583 18.966 22.8667 16.941 21.6 14.441C21.1333 16.8077 20.175 18.9327 18.725 20.816C17.275 22.6994 15.45 24.141 13.25 25.141Z" fill="#29BE6B" />
                                </svg>
                                <h4><a href="" class="stretched-link">Contact Center</a></h4>
                            </a>
                            <p>150678</p>
                        </div>
                    </div><!-- End Service Item -->
                    <div class="col-xl-3 col-md-6 d-flex" data-aos="fade-up" data-aos-delay="100">
                        <div class="service-item position-relative text-center">
                            <a href="https://www.example.com" target="_blank" class="d-flex flex-column align-items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="41" viewBox="0 0 40 41" fill="none">
                                    <g clip-path="url(#clip0_71_106)">
                                        <path d="M-3.5 -3.45898H43.5V43.541H-3.5V-3.45898Z" stroke="#29BE6B" />
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M2 38.041L5.3 30.441C0.076364 23.0259 1.18607 12.8824 7.88942 6.77206C14.5928 0.661717 24.7953 0.493598 31.6964 6.37977C38.5974 12.2659 40.0407 22.3673 35.0642 29.9506C30.0877 37.5338 20.2463 40.2296 12.1 36.241L2 38.041Z" stroke="#29BE6B" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M14 16.041C14 16.5933 14.4477 17.041 15 17.041C15.5523 17.041 16 16.5933 16 16.041V14.041C16 13.4887 15.5523 13.041 15 13.041C14.4477 13.041 14 13.4887 14 14.041V16.041ZM14 16.041C14 21.5639 18.4772 26.041 24 26.041M24 26.041H26C26.5523 26.041 27 25.5933 27 25.041C27 24.4887 26.5523 24.041 26 24.041H24C23.4477 24.041 23 24.4887 23 25.041C23 25.5933 23.4477 26.041 24 26.041Z" stroke="#29BE6B" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" />
                                    </g>
                                    <defs>
                                        <clipPath id="clip0_71_106">
                                            <rect width="40" height="40" fill="white" transform="translate(0 0.0410156)" />
                                        </clipPath>
                                    </defs>
                                </svg>
                                <h4><a href="" class="stretched-link">Whatsapp</a></h4>
                            </a>
                            <p>0812-1100-1100</p>
                        </div>
                    </div><!-- End Service Item -->
                    <div class="col-xl-3 col-md-6 d-flex" data-aos="fade-up" data-aos-delay="100">
                        <div class="service-item position-relative text-center">
                            <a href="https://www.example.com" target="_blank" class="d-flex flex-column align-items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="41" viewBox="0 0 40 41" fill="none">
                                    <g clip-path="url(#clip0_71_129)">
                                        <path d="M28.8889 2.26324H11.1111C6.20191 2.26324 2.22222 6.24294 2.22222 11.1521V28.9299C2.22222 33.8391 6.20191 37.8188 11.1111 37.8188H28.8889C33.7981 37.8188 37.7778 33.8391 37.7778 28.9299V11.1521C37.7778 6.24294 33.7981 2.26324 28.8889 2.26324Z" stroke="#29BE6B" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M20 26.7077C23.6819 26.7077 26.6667 23.7229 26.6667 20.041C26.6667 16.3591 23.6819 13.3743 20 13.3743C16.3181 13.3743 13.3333 16.3591 13.3333 20.041C13.3333 23.7229 16.3181 26.7077 20 26.7077Z" stroke="#29BE6B" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M30 10.041V10.043" stroke="#29BE6B" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
                                    </g>
                                    <defs>
                                        <clipPath id="clip0_71_129">
                                            <rect width="40" height="40" fill="white" transform="translate(0 0.0410156)" />
                                        </clipPath>
                                    </defs>
                                </svg>
                                <h4><a href="">Instagram</a></h4>
                            </a>
                            <p>@karlisna_pln_yogyakarta</p>
                        </div>
                    </div><!-- End Service Item -->
                    <div class="col-xl-3 col-md-6 d-flex" data-aos="fade-up" data-aos-delay="100">
                        <div class="service-item position-relative text-center">
                            <a href="https://www.example.com" target="_blank" class="d-flex flex-column align-items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="41" viewBox="0 0 40 41" fill="none">
                                    <path d="M7 32.041C6.19444 32.041 5.49306 31.7424 4.89583 31.1452C4.29861 30.548 4 29.8466 4 29.041V11.041C4 10.2355 4.29861 9.53407 4.89583 8.93685C5.49306 8.33963 6.19444 8.04102 7 8.04102H33C33.825 8.04102 34.5312 8.33963 35.1187 8.93685C35.7062 9.53407 36 10.2355 36 11.041V29.041C36 29.8466 35.7062 30.548 35.1187 31.1452C34.5312 31.7424 33.825 32.041 33 32.041H7ZM33 14.5827L20.7917 21.5827C20.6661 21.666 20.5342 21.7285 20.3958 21.7702C20.2575 21.8118 20.1256 21.8327 20 21.8327C19.8611 21.8327 19.7361 21.8118 19.625 21.7702C19.5139 21.7285 19.39 21.6702 19.2533 21.5952L7 14.5827V29.041H33V14.5827ZM20 18.4993L32.9583 11.041H7.08333L20 18.4993ZM7 14.0452V14.4198V12.2285V12.2493V11.041V12.2473V12.2285V14.4235V14.0485V29.041V14.0452Z" fill="#29BE6B" />
                                </svg>
                                <h4><a href="" class="stretched-link">Email</a></h4>
                            </a>
                            <p>koperasikarlisna@pln.com</p>
                        </div>
                    </div><!-- End Service Item -->

                </div>

            </div>

        </section><!-- /Featured Services Section -->

        <!-- Clients Section -->
        <section id="clients" class="clients section light-background">

            <div class="container">

                <div class="swiper init-swiper">
                    <script type="application/json" class="swiper-config">
                        {
                            "loop": true,
                            "speed": 600,
                            "autoplay": {
                                "delay": 5000
                            },
                            "slidesPerView": "auto",
                            "pagination": {
                                "el": ".swiper-pagination",
                                "type": "bullets",
                                "clickable": true
                            },
                            "breakpoints": {
                                "320": {
                                    "slidesPerView": 2,
                                    "spaceBetween": 40
                                },
                                "480": {
                                    "slidesPerView": 3,
                                    "spaceBetween": 60
                                },
                                "640": {
                                    "slidesPerView": 4,
                                    "spaceBetween": 80
                                },
                                "992": {
                                    "slidesPerView": 6,
                                    "spaceBetween": 120
                                }
                            }
                        }
                    </script>
                    <div class="swiper-wrapper align-items-center">
                        <div class="swiper-slide"><img src="img/clients/client-1.png" class="img-fluid"
                                alt=""></div>
                        <div class="swiper-slide"><img src="img/clients/client-2.png" class="img-fluid"
                                alt=""></div>
                        <div class="swiper-slide"><img src="img/clients/client-3.png" class="img-fluid"
                                alt=""></div>
                        <div class="swiper-slide"><img src="img/clients/client-4.png" class="img-fluid"
                                alt=""></div>
                        <div class="swiper-slide"><img src="img/clients/client-5.png" class="img-fluid"
                                alt=""></div>
                        <div class="swiper-slide"><img src="img/clients/client-6.png" class="img-fluid"
                                alt=""></div>
                        <div class="swiper-slide"><img src="img/clients/client-7.png" class="img-fluid"
                                alt=""></div>
                        <div class="swiper-slide"><img src="img/clients/client-8.png" class="img-fluid"
                                alt=""></div>
                    </div>
                </div>

            </div>

        </section><!-- /Clients Section -->


        <!-- <footer id="footer" class="footer">

        <div class="footer-newsletter">
            <div class="container">
                <div class="row justify-content-center text-center">
                    <div class="col-lg-6">
                        <h4>Join Our Newsletter</h4>
                        <p>Subscribe to our newsletter and receive the latest news about our products and services!</p>
                        <form action="forms/newsletter.php" method="post" class="php-email-form">
                            <div class="newsletter-form"><input type="email" name="email"><input type="submit" value="Subscribe"></div>
                            <div class="loading">Loading</div>
                            <div class="error-message"></div>
                            <div class="sent-message">Your subscription request has been sent. Thank you!</div>
                        </form>
                    </div>
                </div>
            </div>
        </div> -->
        <section id="" class="section white-background">
            <div class="container d-flex justify-content-between align-items-center text-center mt-2">
                <p class="mb-0 flex-grow-1">© 2024 All rights reserved. Koperasi Konsumen Karlisna PLN Yogyakarta
                    powered by PT PLN Icon Plus</p>
                <div>
                    <a href="" class="text-dark mx-2"><i class="bi bi-facebook"></i></a>
                    <a href="" class="text-dark mx-2"><i class="bi bi-instagram"></i></a>
                    <a href="" class="text-dark mx-2"><i class="bi bi-twitter-x"></i></a>
                    <a href="" class="text-dark mx-2"><i class="bi bi-youtube"></i></a>
                </div>

            </div>
        </section>


        <div class="credits">
            <!-- All the links in the footer should remain intact. -->
            <!-- You can delete the links only if you've purchased the pro version. -->
            <!-- Licensing information: https://bootstrapmade.com/license/ -->
            <!-- Purchase the pro version with working PHP/AJAX contact form: [buy-url] -->
        </div>
        </div>

        </footer>

        <!-- Scroll Top -->
        <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i
                class="bi bi-arrow-up-short"></i></a>

        <!-- Preloader -->
        <div id="preloader">
            <div></div>
            <div></div>
            <div></div>
            <div></div>
        </div>

        <!-- {{ asset('Landingpace') }}/Vendor JS Files -->
        <script src="{{ asset('Landingpace') }}/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="{{ asset('Landingpace') }}/vendor/php-email-form/validate.js"></script>
        <script src="{{ asset('Landingpace') }}/vendor/aos/aos.js"></script>
        <script src="{{ asset('Landingpace') }}/vendor/glightbox/js/glightbox.min.js"></script>
        <script src="{{ asset('Landingpace') }}/vendor/waypoints/noframework.waypoints.js"></script>
        <script src="{{ asset('Landingpace') }}/vendor/purecounter/purecounter_vanilla.js"></script>
        <script src="{{ asset('Landingpace') }}/vendor/swiper/swiper-bundle.min.js"></script>
        <script src="{{ asset('Landingpace') }}/vendor/imagesloaded/imagesloaded.pkgd.min.js"></script>
        <script src="{{ asset('Landingpace') }}/vendor/isotope-layout/isotope.pkgd.min.js"></script>

        <!-- Main JS File -->
        <script src="{{ asset('Landingpace') }}/js/main.js"></script>

</body>

</html>