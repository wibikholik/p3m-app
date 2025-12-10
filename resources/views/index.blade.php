<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>P3M</title>
    <meta name="description" content="">
    <meta name="keywords" content="">

    <link href="assets/img/favicon.png" rel="icon">
    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Nunito:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/vendor/aos/aos.css" rel="stylesheet">
    <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
    <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

    <link href="assets/css/main.css" rel="stylesheet">
</head>

<body class="index-page">

    <header id="header" class="header semi-transparent d-flex align-items-center sticky-top">
        <div class="container position-relative d-flex align-items-center">

            <a href="{{ route('home') }}" class="logo d-flex align-items-center me-auto">
                <img src="assets/img/logo.png" alt="">
                <h1 class="sitename">P3M</h1><span>.</span>
            </a>

            <nav id="navmenu" class="navmenu">
                <ul>
                    <li><a href="#hero" class="active">Home</a></li>
                    <li class="dropdown"><a href="about.html"><span>About</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
                        <ul>
                            <li><a href="team.html">Team</a></li>
                            <li><a href="testimonials.html">Testimonials</a></li>
                            <li class="dropdown"><a href="#"><span>Deep Dropdown</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
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
                        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none">@csrf</form>
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

        <section id="hero" class="hero section dark-background">

            <div id="hero-carousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="5000">

                <div class="carousel-item active">
                    <img src="assets/img/hero-carousel/POLSUB.png" alt="">
                    <div class="container">
                        <h2>POLITEKNIK NEGERI SUBANG</h2>
                        <p>POLSUB Bersinar.</p>
                        <a href="" class="btn-get-started">Lebih Lanjut</a>
                    </div>
                </div><div class="carousel-item">
                    <img src="assets/img/hero-carousel/POLSUB.jpg" alt="">
                    <div class="container">
                        <h2>P3M</h2>
                        <p>Terbaik.</p>
                        <a href="about.html" class="btn-get-started">Lebih Lanjut</a>
                    </div>
                </div><div class="carousel-item">
                    <img src="assets/img/hero-carousel/JTM.png" alt="">
                    <div class="container">
                        <h2>POLSUB</h2>
                        <p>POLITEKNIK NEGERI SUBANG.</p>
                        <a href="about.html" class="btn-get-started">Lebih Lanjut</a>
                    </div>
                </div><div class="carousel-item">
                    <img src="assets/img/hero-carousel/JPT.png" alt="">
                    <div class="container">
                        <h2>POLITEKNIK NEGERI SUBANG</h2>
                        <p>Hebat.</p>
                        <a href="about.html" class="btn-get-started">Lebih Lanjut</a>
                    </div>
                </div><div class="carousel-item">
                    <img src="assets/img/hero-carousel/JTIK.png" alt="">
                    <div class="container">
                        <h2>POLITEKNIK NEGERI SUBANG</h2>
                        <p></p>
                        <a href="about.html" class="btn-get-started">Lebih Lanjut</a>
                    </div>
                </div><a class="carousel-control-prev" href="#hero-carousel" role="button" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon bi bi-chevron-left" aria-hidden="true"></span>
                </a>

                <a class="carousel-control-next" href="#hero-carousel" role="button" data-bs-slide="next">
                    <span class="carousel-control-next-icon bi bi-chevron-right" aria-hidden="true"></span>
                </a>

                <ol class="carousel-indicators"></ol>

            </div>

        </section><section id="portfolio" class="portfolio section">

            <div class="container section-title" data-aos="fade-up">
                <h2>Pengumuman Terbaru</h2>
                <p>Jadikan penelitian & pengabdian mu jadi lebih mudah</p>
            </div><div class="container">

                <div class="isotope-layout" data-default-filter="*" data-layout="masonry" data-sort="original-order">

                    <ul class="portfolio-filters isotope-filters" data-aos="fade-up" data-aos-delay="100">
                        <li data-filter="*" class="filter-active">All</li>
                        {{-- FILTER KATEGORI DIISI DARI BACKEND: $kategori --}}
                        @if(isset($kategori) && $kategori->count())
                        @foreach($kategori as $k)
                        <li data-filter=".filter-{{ \Illuminate\Support\Str::slug($k->nama_kategori) }}">{{ $k->nama_kategori }}</li>
                        @endforeach
                        @else
                        <li data-filter=".filter-umum">Umum</li>
                        @endif
                    </ul><div class="row gy-4 isotope-container" data-aos="fade-up" data-aos-delay="200">
                        {{-- DAFTAR PENGUMUMAN DIBATASI 3 ITEM TERBARU UNTUK HALAMAN DEPAN --}}
                        @if(isset($pengumuman) && $pengumuman->isNotEmpty())
                            @foreach($pengumuman->take(3) as $item) 
                                @php $slug = \Illuminate\Support\Str::slug($item->kategori->nama_kategori ?? 'umum'); @endphp
                                <div class="col-lg-4 col-md-6 portfolio-item isotope-item filter-{{ $slug }}">
                                    <div class="portfolio-image">
                                        <div class="ratio ratio-16x9">
                                            <img src="{{ $item->gambar ? asset('storage/' . $item->gambar) : asset('assets/img/masonry-portfolio/default.png') }}" class="img-fluid" alt="{{ $item->judul }}" style="object-fit:cover; width:100%; height:100%;">
                                        </div>
                                    </div>
                                    <div class="portfolio-info">
                                        <h4>{{ $item->judul }}</h4>
                                        {{-- Menampilkan maksimal 120 karakter isi pengumuman --}}
                                        <p>{{ \Illuminate\Support\Str::limit(strip_tags($item->isi), 120) }}</p>
                                        {{-- Preview link menggunakan gambar --}}
                                        <a href="{{ $item->gambar ? asset('storage/' . $item->gambar) : asset('assets/img/masonry-portfolio/default.png') }}" title="{{ $item->judul }}" data-gallery="portfolio-gallery-product" class="glightbox preview-link"><i class="bi bi-zoom-in"></i></a>
                                        {{-- Detail link menuju halaman pengumuman spesifik --}}
                                        <a href="{{ route('pengumuman.show', $item->id) }}" title="More Details" class="details-link"><i class="bi bi-link-45deg"></i></a>
                                    </div>
                                </div>@endforeach
                        @else
                            <div class="col-12">
                                <p class="text-center">Belum ada pengumuman terbaru.</p>
                            </div>
                        @endif
                    </div><br>
                </div>
                <div class="container text-center" data-aos="fade-up">
                    <h4><a href="{{ route('pengumuman.index') ?? '#' }}" class="btn btn-outline-primary btn-sm">Lihat pengumuman lainya...</a></h4>
                </div>
            </div>

        </section><section id="services" class="services section light-background">

            <div class="container">
                <div class="container section-title" data-aos="fade-up">
                    <h2>Berita Terbaru</h2>
                    <p>Jadikan penelitian & pengabdian mu jadi lebih mudah</p>
                </div>{{-- LOOP UNTUK BERITA TERBARU DIISI DARI BACKEND: $berita_terbaru (Ambil 3 terbaru) --}}
                @php
                    // Daftar kelas dan ikon untuk efek visual yang sama dengan template
                    $service_items = [
                        ['class' => 'item-cyan', 'icon' => 'bi-broadcast', 'delay' => 100],
                        ['class' => 'item-red', 'icon' => 'bi-easel', 'delay' => 200],
                        ['class' => 'item-teal', 'icon' => 'bi-activity', 'delay' => 300],
                    ];
                    $i = 0;
                @endphp

                <div class="row gy-4">
                    {{-- Menggunakan take(3) untuk membatasi hanya 3 item terbaru yang ditampilkan di halaman depan --}}
                    @if(isset($berita_terbaru) && $berita_terbaru->isNotEmpty())
                        @foreach($berita_terbaru->take(3) as $item)
                            @php
                                $style = $service_items[$i % count($service_items)];
                                // Memformat tanggal dengan asumsi Carbon/localized format tersedia
                                $tanggal_mulai = isset($item->tanggal_mulai) ? \Carbon\Carbon::parse($item->tanggal_mulai)->translatedFormat('d F Y') : 'N/A';
                                $tanggal_akhir = isset($item->tanggal_akhir) ? \Carbon\Carbon::parse($item->tanggal_akhir)->translatedFormat('d F Y') : 'N/A';
                            @endphp
                            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="{{ $style['delay'] }}">
                                <div class="service-item {{ $style['class'] }} position-relative">
                                    <div class="icon">
                                        <i class="bi {{ $style['icon'] }}"></i>
                                    </div>
                                    <a href="{{ route('pengumuman.show', $item->id) }}" class="stretched-link">
                                        <h3>{{ $item->judul }}</h3>
                                    </a>
                                    {{-- Menampilkan isi berita/program --}}
                                    <p>
                                        {{ \Illuminate\Support\Str::limit(strip_tags($item->isi), 100) }}
                                        @if($item->tanggal_mulai && $item->tanggal_akhir)
                                        <br>
                                        <strong>Periode program:</strong> {{ $tanggal_mulai }} sampai {{ $tanggal_akhir }}
                                        @endif
                                    </p>
                                </div>
                            </div>@php $i++; @endphp
                        @endforeach
                    @else
                        <div class="col-12">
                            <p class="text-center">Belum ada berita terbaru saat ini.</p>
                        </div>
                    @endif
                </div>

            </div>

        </section><section id="clients" class="clients section">

            <div class="container section-title" data-aos="fade-up">
                <h2>Tentang SIP3M</h2>
                <p>Jadikan penelitian & pengabdian mu jadi lebih mudah</p>
            </div><div class="container" data-aos="fade-up" data-aos-delay="100">
                <div class="row gy-4">

                    <div class="col-lg-4">
                        <div class="about-card text-center">
                            <div class="about-icon mb-3">
                                <i class="bi bi-file-earmark-text" style="font-size: 3rem; color: #ff9a56;"></i>
                            </div>
                            <h3>Pengusulan Proposal penelitian & pengabdian kepada masyarakat</h3>
                            <p>Dosen bisa dengan mudah upload usulan proposal di aplikasi SIP3M</p>
                        </div>
                    </div><div class="col-lg-4">
                        <div class="about-card text-center">
                            <div class="about-icon mb-3">
                                <i class="bi bi-star-fill" style="font-size: 3rem; color: #ffc107;"></i>
                            </div>
                            <h3>Proses Review Proposal langsung di Aplikasi SIP3M</h3>
                            <p>Reviewer bisa dengan mudah mereview proposal dalam aplikasi SIP3M</p>
                        </div>
                    </div><div class="col-lg-4">
                        <div class="about-card text-center">
                            <div class="about-icon mb-3">
                                <i class="bi bi-graph-up" style="font-size: 3rem; color: #2196f3;"></i>
                            </div>
                            <h3>Pelaporan Kemajuan & laporan akhir</h3>
                            <p>Pelaporan kemajuan dan laporlaporan akhir langsung bisa di laksanakan di aplikasi SIP3M</p>
                        </div>
                    </div></div>
            </div>

        </section></main>

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
                        <div class="newsletter-form"><input type="email" name="email"><input type="submit" value="Subscribe"></div>
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
                Designed by <a href="https://bootstrapmade.com/">Hyperion</a> Distributed by <a href="https://jtik.polsub.ac.id/">JTIK
            </div>
        </div>

    </footer>

    <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

    <div id="preloader"></div>

    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/php-email-form/validate.js"></script>
    <script src="assets/vendor/aos/aos.js"></script>
    <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
    <script src="assets/vendor/imagesloaded/imagesloaded.pkgd.min.js"></script>
    <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
    <script src="assets/vendor/waypoints/noframework.waypoints.js"></script>
    <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>

    <script src="assets/js/main.js"></script>

</body>

</html>