<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>P3M</title>
    <meta name="description" content="">
    <meta name="keywords" content="">

    <!-- Favicons -->
    <link href="assets/img/favicon.png" rel="icon">
    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Nunito:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/vendor/aos/aos.css" rel="stylesheet">
    <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
    <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

    <!-- Main CSS File -->
    <link href="assets/css/main.css" rel="stylesheet">
</head>

<body class="index-page">

    <header id="header" class="header d-flex align-items-center sticky-top">
        <div class="container position-relative d-flex align-items-center">

            <a href="{{ route('home') }}" class="logo d-flex align-items-center me-auto">
                <img src="assets/img/logo.png" alt="">
                <h1 class="sitename">P3M</h1><span>.</span>
            </a>

            <nav id="navmenu" class="navmenu">
                <ul>
                    <li><a href="#hero" class="active">Home</a></li>
                    <li class="dropdown"><a href="about.html"><span>About</span> <i
                                class="bi bi-chevron-down toggle-dropdown"></i></a>
                        <ul>
                            <li><a href="team.html">Team</a></li>
                            <li><a href="testimonials.html">Testimonials</a></li>
                            <li class="dropdown"><a href="#"><span>Deep Dropdown</span> <i
                                        class="bi bi-chevron-down toggle-dropdown"></i></a>
                                <ul>
                                    <li><a href="#">Deep Dropdown 1</a></li>
                                    <li><a href="#">Deep Dropdown 2</a></li>
                                    <li><a href="#">Deep Dropdown 3</a></li>
                                    <li><a href="#">Deep Dropdown 4</a></li>
                                    <li><a href="#">Deep Dropdown 5</a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    {{-- Auth links in navbar: Login/Register (links to separate pages) --}}
                    @guest
                        <li><a href="{{ route('login') }}">Login</a></li>
                        @if (Route::has('register'))
                            <li><a href="{{ route('register') }}">Register</a></li>
                        @endif
                    @else
                        <li><a href="{{ route('redirect.role') }}">Dashboard</a></li>
                        <li>
                            <a href="#"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none">@csrf
                            </form>
                        </li>
                    @endguest
                </ul>
                <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
            </nav>

            <div class="header-social-links">
                <a href="#" class="twitter"><i class="bi bi-twitter-x"></i></a>
                <a href="#" class="facebook"><i class="bi bi-facebook"></i></a>
                <a href="#" class="instagram"><i class="bi bi-instagram"></i></a>
                <a href="#" class="linkedin"><i class="bi bi-linkedin"></i></a>
            </div>

        </div>
    </header>

    <main class="main">

        <!-- Hero Section -->
        <section id="hero" class="hero section dark-background">

            <div id="hero-carousel" class="carousel slide carousel-fade" data-bs-ride="carousel"
                data-bs-interval="5000">

                <div class="carousel-item active">
                    <img src="assets/img/hero-carousel/POLSUB.png" alt="">
                    <div class="container">
                        <h2>POLITEKNIK NEGERI SUBANG</h2>
                        <p>POLSUB Bersinar.</p>
                        <a href="" class="btn-get-started">Lebih Lanjut</a>
                    </div>
                </div><!-- End Carousel Item -->

                <div class="carousel-item">
                    <img src="assets/img/hero-carousel/POLSUB.jpg" alt="">
                    <div class="container">
                        <h2>P3M</h2>
                        <p>Terbaik.</p>
                        <a href="about.html" class="btn-get-started">Lebih Lanjut</a>
                    </div>
                </div><!-- End Carousel Item -->

                <div class="carousel-item">
                    <img src="assets/img/hero-carousel/JTM.png" alt="">
                    <div class="container">
                        <h2>POLSUB</h2>
                        <p>Politeknik Negeri Subang.</p>
                        <a href="about.html" class="btn-get-started">Lebih Lanjut</a>
                    </div>
                </div><!-- End Carousel Item -->

                <div class="carousel-item">
                    <img src="assets/img/hero-carousel/JPT.png" alt="">
                    <div class="container">
                        <h2>Temporibus autem quibusdam</h2>
                        <p>Hebat.</p>
                        <a href="about.html" class="btn-get-started">Lebih Lanjut</a>
                    </div>
                </div><!-- End Carousel Item -->

                <div class="carousel-item">
                    <img src="assets/img/hero-carousel/JTIK.png" alt="">
                    <div class="container">
                        <h2>Temporibus autem quibusdam</h2>
                        <p>JTIK dianaktirikan jir.</p>
                        <a href="about.html" class="btn-get-started">Lebih Lanjut</a>
                    </div>
                </div><!-- End Carousel Item -->

                <a class="carousel-control-prev" href="#hero-carousel" role="button" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon bi bi-chevron-left" aria-hidden="true"></span>
                </a>

                <a class="carousel-control-next" href="#hero-carousel" role="button" data-bs-slide="next">
                    <span class="carousel-control-next-icon bi bi-chevron-right" aria-hidden="true"></span>
                </a>

                <ol class="carousel-indicators"></ol>

            </div>

        </section><!-- /Hero Section -->

        {{-- <!-- About Section -->
        <section id="about" class="about section">

            <div class="container">

                <div class="row position-relative">

                    <div class="col-lg-7 about-img" data-aos="zoom-out" data-aos-delay="200"><img
                            src="assets/img/about.jpg"></div>

                    <div class="col-lg-7" data-aos="fade-up" data-aos-delay="100">
                        <h2 class="inner-title">a</h2>
                        <div class="our-story">
                            <h4>Est 1988</h4>
                            <h3>Our Story</h3>
                            <p>Inventore aliquam beatae at et id alias. Ipsa dolores amet consequuntur minima quia
                                maxime autem. Quidem id sed ratione. Tenetur provident autem in reiciendis rerum at
                                dolor. Aliquam consectetur laudantium temporibus dicta minus dolor.</p>
                            <ul>
                                <li><i class="bi bi-check-circle"></i> <span>Ullamco laboris nisi ut aliquip ex ea
                                        commo</span></li>
                                <li><i class="bi bi-check-circle"></i> <span>Duis aute irure dolor in reprehenderit
                                        in</span></li>
                                <li><i class="bi bi-check-circle"></i> <span>Ullamco laboris nisi ut aliquip ex
                                        ea</span></li>
                            </ul>
                            <p>Vitae autem velit excepturi fugit. Animi ad non. Eligendi et non nesciunt suscipit
                                repellendus porro in quo eveniet. Molestias in maxime doloremque.</p>

                            <div class="watch-video d-flex align-items-center position-relative">
                                <i class="bi bi-play-circle"></i>
                                <a href="https://www.youtube.com/watch?v=Y7f98aduVJ8"
                                    class="glightbox stretched-link">Watch Video</a>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

        </section><!-- /About Section --> --}}

        <!-- Portfolio Section -->
        <section id="portfolio" class="portfolio section">

            <!-- Section Title -->
            <div class="container section-title" data-aos="fade-up">
                <h2>Berita Terbaru</h2>
                <p>Jadikan penelitian & pengabdian mu jadi lebih mudah</p>
            </div><!-- End Section Title -->

            <div class="container">

                <div class="isotope-layout" data-default-filter="*" data-layout="masonry" data-sort="original-order">

                    <ul class="portfolio-filters isotope-filters" data-aos="fade-up" data-aos-delay="100">
                        <li data-filter="*" class="filter-active">All</li>
                        <li data-filter=".filter-pengabdian">Pengabdian</li>
                        <li data-filter=".filter-pkm">PKM</li>
                        <li data-filter=".filter-penelitian">Penelitian</li>
                    </ul><!-- End Portfolio Filters -->

                    <div class="row gy-4 isotope-container" data-aos="fade-up" data-aos-delay="200">

                        <div class="col-lg-4 col-md-6 portfolio-item isotope-item filter-pkm">
                            <img src="assets/img/masonry-portfolio/Rekt.jpg" class="img-fluid" alt="">
                            <div class="portfolio-info">
                                <h4>Rapat senat terbuka untuk transparansi visi misi politeknik negeri subang.</h4>
                                <p>Periode program dari tanggal 25 september 2025 sampai 10 september 2025</p>
                                <a href="assets/img/masonry-portfolio/Rekt.jpg" title="Product 1"
                                    data-gallery="portfolio-gallery-product" class="glightbox preview-link"><i
                                        class="bi bi-zoom-in"></i></a>
                                <a href="portfolio-details.html" title="More Details" class="details-link"><i
                                        class="bi bi-link-45deg"></i></a>
                            </div>
                        </div><!-- End Portfolio Item -->

                        <div class="col-lg-4 col-md-6 portfolio-item isotope-item filter-penelitian">
                            <img src="assets/img/masonry-portfolio/POLSUB.png" class="img-fluid" alt="">
                            <div class="portfolio-info">
                                <h4>Program Penelitian dan pengabdian bidang teknik Mesin </h4>
                                <p>Periode program dari tanggal 25 september 2025 sampai 10 september 2025</p>
                                <a href="assets/img/masonry-portfolio/POLSUB.png" title="Product 1"
                                    data-gallery="portfolio-gallery-product" class="glightbox preview-link"><i
                                        class="bi bi-zoom-in"></i></a>
                                <a href="portfolio-details.html" title="More Details" class="details-link"><i
                                        class="bi bi-link-45deg"></i></a>
                            </div>
                        </div><!-- End Portfolio Item -->

                        <div class="col-lg-4 col-md-6 portfolio-item isotope-item filter-penelitian">
                            <img src="assets/img/masonry-portfolio/gkb.jpeg" class="img-fluid" alt="">
                            <div class="portfolio-info">
                                <h4>Program Penelitian dan pengabdian bidang kesehatan berbasis teknologi</h4>
                                <p>Periode program dari tanggal 25 september 2025 sampai 10 september 2025</p>
                                <a href="assets/img/masonry-portfolio/gkb.jpeg" title="Product 1"
                                    data-gallery="portfolio-gallery-product" class="glightbox preview-link"><i
                                        class="bi bi-zoom-in"></i></a>
                                <a href="portfolio-details.html" title="More Details" class="details-link"><i
                                        class="bi bi-link-45deg"></i></a>
                            </div>
                        </div><!-- End Portfolio Item -->

                    </div><!-- End Portfolio Container -->
                    <br>
                </div>
                <div class="container section-title" data-aos="fade-up">
                    <h4>Lihat pengumuman lainya...</h4>
                </div>

            </div>

        </section><!-- /Portfolio Section -->

        <!-- Services Section -->
        <section id="services" class="services section light-background">

            <div class="container">
                <!-- Section Title -->
                <div class="container section-title" data-aos="fade-up">
                    <h2>Pengumuman Terbaru</h2>
                    <p>Jadikan penelitian & pengabdian mu jadi lebih mudah</p>
                </div><!-- End Section Title -->
                <div class="row gy-4">

                    <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                        <div class="service-item item-cyan position-relative">
                            <div class="icon">
                                <svg width="100" height="100" viewBox="0 0 600 600" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke="none" stroke-width="0" fill="#f5f5f5"
                                        d="M300,521.0016835830174C376.1290562159157,517.8887921683347,466.0731472004068,529.7835943286574,510.70327084640275,468.03025145048787C554.3714126377745,407.6079735673963,508.03601936045806,328.9844924480964,491.2728898941984,256.3432110539036C474.5976632858925,184.082847569629,479.9380746630129,96.60480741107993,416.23090153303,58.64404602377083C348.86323505073057,18.502131276798302,261.93793281208167,40.57373210992963,193.5410806939664,78.93577620505333C130.42746243093433,114.334589627462,98.30271207620316,179.96522072025542,76.75703585869454,249.04625023123273C51.97151888228291,328.5150500222984,13.704378332031375,421.85034740162234,66.52175969318436,486.19268352777647C119.04800174914682,550.1803526380478,217.28368757567262,524.383925680826,300,521.0016835830174">
                                    </path>
                                </svg>
                                <i class="bi bi-broadcast"></i>
                            </div>
                            <a href="service-details.html" class="stretched-link">
                                <h3>Program Penelitian dan pengabdian bidang teknologi informasi dan komputer.</h3>
                            </a>
                            <p>Periode program dari tanggal 25 september 2025 sampai 10 september 2025</p>
                        </div>
                    </div><!-- End Service Item -->

                    <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                        <div class="service-item item-red position-relative">
                            <div class="icon">
                                <svg width="100" height="100" viewBox="0 0 600 600" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke="none" stroke-width="0" fill="#f5f5f5"
                                        d="M300,582.0697525312426C382.5290701553225,586.8405444964366,449.9789794690241,525.3245884688669,502.5850820975895,461.55621195738473C556.606425686781,396.0723002908107,615.8543463187945,314.28637112970534,586.6730223649479,234.56875336149918C558.9533121215079,158.8439757836574,454.9685369536778,164.00468322053177,381.49747125262974,130.76875717737553C312.15926192815925,99.40240125094834,248.97055460311594,18.661163978235184,179.8680185752513,50.54337015887873C110.5421016452524,82.52863877960104,119.82277516462835,180.83849132639028,109.12597500060166,256.43424936330496C100.08760227029461,320.3096726198365,92.17705696193138,384.0621239912766,124.79988738764834,439.7174275375508C164.83382741302287,508.01625554203684,220.96474134820875,577.5009287672846,300,582.0697525312426">
                                    </path>
                                </svg>
                                <i class="bi bi-easel"></i>
                            </div>
                            <a href="service-details.html" class="stretched-link">
                                <h3>Program Penelitian dan pengabdian bidang teknik Mesin </h3>
                            </a>
                            <p>Periode program dari tanggal 25 september 2025 sampai 10 september 2025.</p>
                        </div>
                    </div><!-- End Service Item -->

                    <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                        <div class="service-item item-teal position-relative">
                            <div class="icon">
                                <svg width="100" height="100" viewBox="0 0 600 600" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke="none" stroke-width="0" fill="#f5f5f5"
                                        d="M300,541.5067337569781C382.14930387511276,545.0595476570109,479.8736841581634,548.3450877840088,526.4010558755058,480.5488172755941C571.5218469581645,414.80211281144784,517.5187510058486,332.0715597781072,496.52539010469104,255.14436215662573C477.37192572678356,184.95920475031193,473.57363656557914,105.61284051026155,413.0603344069578,65.22779650032875C343.27470386102294,18.654635553484475,251.2091493199835,5.337323636656869,175.0934190732945,40.62881213300186C97.87086631185822,76.43348514350839,51.98124368387456,156.15599469081315,36.44837278890362,239.84606092416172C21.716077023791087,319.22268207091537,43.775223500013084,401.1760424656574,96.891909868211,461.97329694683043C147.22146801428983,519.5804099606455,223.5754009179313,538.201503339737,300,541.5067337569781">
                                    </path>
                                </svg>
                                <i class="bi bi-activity"></i>
                            </div>
                            <a href="service-details.html" class="stretched-link">
                                <h3>Program Penelitian dan pengabdian bidang kesehatan berbasis teknologi </h3>
                            </a>
                            <p>Periode program dari tanggal 25 september 2025 sampai 10 september 2025.</p>
                        </div>
                    </div><!-- End Service Item -->

                </div>

            </div>

        </section><!-- /Services Section -->

        <!-- Clients Section -->
        <section id="clients" class="clients section">

            <!-- Section Title -->
            <div class="container section-title" data-aos="fade-up">
                <h2>Tentang SIP3M</h2>
                <p>Jadikan penelitian & pengabdian mu jadi lebih mudah</p>
            </div><!-- End Section Title -->

            <div class="container" data-aos="fade-up" data-aos-delay="100">
                <div class="row gy-4">

                    <!-- Card 1: Pengusulan Proposal -->
                    <div class="col-lg-4">
                        <div class="about-card text-center">
                            <div class="about-icon mb-3">
                                <i class="bi bi-file-earmark-text" style="font-size: 3rem; color: #ff9a56;"></i>
                            </div>
                            <h3>Pengusulan Proposal penelitian & pengabdian kepada masyarakat</h3>
                            <p>Dosen bisa dengan mudah upload usulan proposal di aplikasi SIP3M</p>
                        </div>
                    </div><!-- End About Card 1 -->

                    <!-- Card 2: Proses Review Proposal -->
                    <div class="col-lg-4">
                        <div class="about-card text-center">
                            <div class="about-icon mb-3">
                                <i class="bi bi-star-fill" style="font-size: 3rem; color: #ffc107;"></i>
                            </div>
                            <h3>Proses Review Proposal langsung di Aplikasi SIP3M</h3>
                            <p>Reviewer bisa dengan mudah mereview proposal dalam aplikasi SIP3M</p>
                        </div>
                    </div><!-- End About Card 2 -->

                    <!-- Card 3: Pelaporan Kemajuan -->
                    <div class="col-lg-4">
                        <div class="about-card text-center">
                            <div class="about-icon mb-3">
                                <i class="bi bi-graph-up" style="font-size: 3rem; color: #2196f3;"></i>
                            </div>
                            <h3>Pelaporan Kemajuan & laporan akhir</h3>
                            <p>Pelaporan kemajuan dan laporlaporan akhir langsung bisa di laksanakan di aplikasi SIP3M
                            </p>
                        </div>
                    </div><!-- End About Card 3 -->

                </div>
            </div>

        </section><!-- /Clients Section -->

    </main>

    <footer id="footer" class="footer dark-background">

        <div class="container footer-top">
            <div class="row gy-4">
                <div class="col-lg-4 col-md-6 footer-about">
                    <a href="{{ route('home') }}" class="logo d-flex align-items-center">
                        <span class="sitename">P3M</span>
                    </a>
                    <div class="footer-contact pt-3">
                        <p>Cibogo, Padaasih</p>
                        <p>POLSUB, Subang</p>
                        <p class="mt-3"><strong>Phone:</strong> <span>+62 838 7957 7305</span></p>
                        <p><strong>Email:</strong> <span>hyperion@gmail.com</span></p>
                    </div>
                    <div class="social-links d-flex mt-4">
                        <a href=""><i class="bi bi-twitter-x"></i></a>
                        <a href=""><i class="bi bi-facebook"></i></a>
                        <a href=""><i class="bi bi-instagram"></i></a>
                        <a href=""><i class="bi bi-linkedin"></i></a>
                    </div>
                </div>

                <div class="col-lg-2 col-md-3 footer-links">
                    <h4>Useful Links</h4>
                    <ul>
                        <li><a href="#">Home</a></li>
                        <li><a href="#">About us</a></li>
                        <li><a href="#">Services</a></li>
                        <li><a href="#">Terms of service</a></li>
                        <li><a href="#">Privacy policy</a></li>
                    </ul>
                </div>

                <div class="col-lg-2 col-md-3 footer-links">
                    <h4>Our Services</h4>
                    <ul>
                        <li><a href="#">Web Design</a></li>
                        <li><a href="#">Web Development</a></li>
                        <li><a href="#">Product Management</a></li>
                        <li><a href="#">Marketing</a></li>
                        <li><a href="#">Graphic Design</a></li>
                    </ul>
                </div>

                <div class="col-lg-4 col-md-12 footer-newsletter">
                    <h4>Our Newsletter</h4>
                    <p>Subscribe to our newsletter and receive the latest news about our products and services!</p>
                    <form action="forms/newsletter.php" method="post" class="php-email-form">
                        <div class="newsletter-form"><input type="email" name="email"><input type="submit"
                                value="Subscribe"></div>
                        <div class="loading">Loading</div>
                        <div class="error-message"></div>
                        <div class="sent-message">Your subscription request has been sent. Thank you!</div>
                    </form>
                </div>

            </div>
        </div>

        <div class="container copyright text-center mt-4">
            <p>© <span>Copyright</span> <strong class="px-1 sitename">P3M</strong> <span>All Rights Reserved</span></p>
            <div class="credits">
                Designed by <a href="https://bootstrapmade.com/">Hyperion</a> Distributed by <a
                    href="https://jtik.polsub.ac.id/">JTIK
            </div>
        </div>

    </footer>

    <!-- Scroll Top -->
    <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Preloader -->
    <div id="preloader"></div>

    <!-- Vendor JS Files -->
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/php-email-form/validate.js"></script>
    <script src="assets/vendor/aos/aos.js"></script>
    <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
    <script src="assets/vendor/imagesloaded/imagesloaded.pkgd.min.js"></script>
    <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
    <script src="assets/vendor/waypoints/noframework.waypoints.js"></script>
    <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>

    <!-- Main JS File -->
    <script src="assets/js/main.js"></script>

</body>

</html>