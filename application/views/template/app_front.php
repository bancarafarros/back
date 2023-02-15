<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title><?= SITENAME ?> | <?= (!empty($title) ? $title : 'index') ?></title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="<?= base_url('public') ?>/images/sajada-logo-teal.png" rel="icon">
    <link href="<?= base_url('public') ?>/images/sajada-logo-teal.png" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,600;1,700&display=swap" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="<?= base_url('public') ?>/libs/bootstrap5/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= base_url('public') ?>/libs/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="<?= base_url('public') ?>/libs/aos/aos.css" rel="stylesheet">
    <link href="<?= base_url('public') ?>/libs/glightbox/css/glightbox.min.css" rel="stylesheet">
    <link href="<?= base_url('public') ?>/libs/swiper/swiper-bundle.min.css" rel="stylesheet">

    <!-- font awesome -->
    <link href="<?= base_url('public') ?>/libs/fontawesome/css/all.min.css" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="<?= base_url('public') ?>/front/css/main.css" rel="stylesheet">
</head>

<body>
    <!-- <section id="topbar" class="topbar d-flex align-items-center">
        <div class="container d-flex justify-content-center justify-content-md-between">
            <div class="contact-info d-flex align-items-center">
                <i class="bi bi-envelope d-flex align-items-center"><a href="mailto:contact@example.com">contact@example.com</a></i>
                <i class="bi bi-phone d-flex align-items-center ms-4"><span>+1 5589 55488 55</span></i>
            </div>
            <div class="social-links d-none d-md-flex align-items-center">
                <a href="#" class="twitter"><i class="bi bi-twitter"></i></a>
                <a href="#" class="facebook"><i class="bi bi-facebook"></i></a>
                <a href="#" class="instagram"><i class="bi bi-instagram"></i></a>
                <a href="#" class="linkedin"><i class="bi bi-linkedin"></i></i></a>
            </div>
        </div>
    </section> -->
    <!-- End Top Bar -->

    <header id="header" class="header d-flex align-items-center">
        <div class="container-fluid container-xl d-flex align-items-center justify-content-between">
            <a href="<?= site_url() ?>" class="logo d-flex align-items-center">
                <img src="<?= base_url('public') ?>/images/sajada-white.png" alt="sajada logo">
            </a>
            <nav id="navbar" class="navbar">
                <ul>
                    <li><a href="#hero">Home</a></li>
                    <li><a href="#fiturs">Fitur</a></li>
                    <li><a href="#services">Hubungi Kami</a></li>
                    <li>
                        <button type="button" class="btn text-success btn-unduh px-4">Unduh</button>
                    </li>
                </ul>
            </nav>
            <!-- .navbar -->
            <i class="mobile-nav-toggle mobile-nav-show bi bi-list"></i>
            <i class="mobile-nav-toggle mobile-nav-hide d-none bi bi-x"></i>
        </div>
    </header>
    <!-- End Header -->

    <!-- ======= Hero Section ======= -->
    <section id="hero" class="hero container">
        <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="false">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1" aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2" aria-label="Slide 3"></button>
                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="3" aria-label="Slide 4"></button>
            </div>
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="<?= base_url('public') ?>/images/bg-slider1.png" class="d-block w-100" alt="...">
                    <div class="carousel-caption">
                        <div class="row" data-aos="fade-in">
                            <div class="col-lg-6 order-2 order-lg-1 text-center text-lg-start carousel-caption-left">
                                <h2>Cari Masjid Terdekat<br>Dengan Aplikasi Sajada</h2>
                                <p class="text-light">Cara mudah untuk mencari masjid terdekat, hanya dengan menggunakan aplikasi Sajadah ini!</p>
                                <div class="d-flex justify-content-center justify-content-lg-start">
                                    <!-- <a href="#" role="button" class="btn btn-selengkapnya text-light py-2">Lihat Selengkapnya</a> -->
                                    <img src="<?= base_url('public') ?>/images/img-download-now.png" class="w-50 img-fluid">
                                </div>
                            </div>
                            <div class="col-lg-6 order-1 order-lg-2">
                                <img src="<?= base_url('public') ?>/images/img-slider1.png" class="img-slider d-none d-sm-block" alt="" data-aos="zoom-out" data-aos-delay="100">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="<?= base_url('public') ?>/images/bg-slider1.png" class="d-block w-100" alt="...">
                    <div class="carousel-caption">
                        <div class="row" data-aos="fade-in">
                            <div class="col-lg-6 order-2 order-lg-1 text-center text-lg-start carousel-caption-left">
                                <h2>Sajada, agar semua orang<br>lebih dekat dengan masjid</h2>
                                <p class="text-light">Mengkolaborasikan dan menyinergikan program masjid di seluruh Kota Malang untuk mendekatkan generasi muslim kepada masjid</p>
                                <div class="d-flex justify-content-center justify-content-lg-start">
                                    <a href="#" role="button" class="btn btn-selengkapnya text-light py-2">Segera dapatkan aplikasinya</a>
                                </div>
                            </div>
                            <div class="col-lg-6 order-1 order-lg-2">
                                <img src="<?= base_url('public') ?>/images/img-slider2.png" class="img-slider d-none d-sm-block" alt="" data-aos="zoom-out" data-aos-delay="100">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="<?= base_url('public') ?>/images/bg-slider1.png" class="d-block w-100" alt="...">
                    <div class="carousel-caption">
                        <div class="row" data-aos="fade-in">
                            <div class="col-lg-6 order-2 order-lg-1 text-center text-lg-start carousel-caption-left">
                                <h2>Temukan Masjid Terdekat hanya dengan satu kali klik</h2>
                                <p class="text-light">Dengan aplikasi ini kita lebih mudah untuk mencari masjid terdekat di wilayah kita, tanpa perlu keluar rumah.</p>
                                <div class="d-flex justify-content-center justify-content-lg-start">
                                    <a href="#" role="button" class="btn btn-selengkapnya text-light py-2">Lihat Selengkapnya</a>
                                </div>
                            </div>
                            <div class="col-lg-6 order-1 order-lg-2">
                                <img src="<?= base_url('public') ?>/images/img-slider3.png" class="img-slider d-none d-sm-block" alt="" data-aos="zoom-out" data-aos-delay="100">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="<?= base_url('public') ?>/images/bg-slider1.png" class="d-block w-100" alt="...">
                    <div class="carousel-caption">
                        <div class="row" data-aos="fade-in">
                            <div class="col-lg-6 order-2 order-lg-1 text-center text-lg-start carousel-caption-left">
                                <h2>Bantu Yatim Piatu dan Guru Mengaji melalui donasi</h2>
                                <p class="text-light">Anda dapat memberikan donasi melalui aplikasi dan donasi akan disalurkan kepada Yatim Piatu dan Guru Mengaji yang membutuhkan di sekitar anda</p>
                                <div class="d-flex justify-content-center justify-content-lg-start">
                                    <a href="#" role="button" class="btn btn-selengkapnya text-light py-2">Lihat Selengkapnya</a>
                                </div>
                            </div>
                            <div class="col-lg-6 order-1 order-lg-2">
                                <img src="<?= base_url('public') ?>/images/img-slider4.png" class="img-slider d-none d-sm-block" alt="" data-aos="zoom-out" data-aos-delay="100">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button> -->
        </div>
    </section>
    <!-- <section id="hero" class="hero container">
        <div class="container position-relative">
            <div class="row gy-5" data-aos="fade-in">
                <div class="col-lg-6 order-2 order-lg-1 d-flex flex-column justify-content-center text-center text-lg-start">
                    <h2>Cari Masjid Terdekat<br>Dengan Aplikasi Sajada</h2>
                    <p class="text-light">Cara mudah untuk mencari masjid terdekat, hanya dengan menggunakan aplikasi Sajadah ini!</p>
                    <div class="d-flex justify-content-center justify-content-lg-start">
                        <a href="#" role="button" class="btn btn-selengkapnya text-light py-2">Lihat Selengkapnya</a>
                    </div>
                </div>
                <div class="col-lg-6 order-1 order-lg-2">
                    <img src="<?= base_url('public') ?>/images/bg-slider.png" class="img-fluid" alt="" data-aos="zoom-out" data-aos-delay="100">
                </div>
            </div>
        </div>
    </section> -->
    <!-- End Hero Section -->

    <main id="main">
        {CONTENT}
    </main>

    <footer id="footer" class="footer">
        <div class="container">
            <div class="row gy-4">
                <div class="col-lg-4 col-md-12 footer-info">
                    <a href="<?= site_url() ?>" class="logo d-flex align-items-center">
                        <img src="<?= base_url('public') ?>/images/sajada-white.png" class="img-fluid">
                    </a>
                    <p>Aplikasi sajada akan membantu kamu agar lebih dekat lagi dengan masjid, fitur yang lengkap akan lebih membuat harimu lebih produktif dan berkah.</p>
                </div>

                <div class="col-lg-3 col-6 footer-links">
                    <h4>Fitur</h4>
                    <ul>
                        <li><a href="#">Ikuti Kajian Keilmuan</a></li>
                        <li><a href="#">Pencarian Masjid us</a></li>
                        <li><a href="#">Mendaftar Jamaah Masjid</a></li>
                        <li><a href="#">Buat Tabungan</a></li>
                        <li><a href="#">Donasi</a></li>
                    </ul>
                </div>

                <div class="col-lg-2 col-6 footer-links">
                    <h4>Follow Us</h4>
                    <ul>
                        <li><a href="#"><i class="bi bi-facebook text-light fs-5"></i> Facebook</a></li>
                        <li><a href="#"><i class="bi bi-instagram text-light fs-5"></i> Instagram</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-12 footer-info text-center text-md-start">
                    <h4>Download Now!</h4>
                    <img src="<?= base_url('public') ?>/images/img-download-now.png" class="logo img-fluid">
                </div>
            </div>
        </div>

        <div class="container mt-4">
            <div class="copyright">
                &copy; Copyright <b class="text-light"><?= SITENAME ?></b>. All Rights Reserved
            </div>
        </div>

    </footer>

    <a href="#" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

    <div id="preloader"></div>

    <!-- Vendor JS Files -->
    <script src="<?= base_url('public') ?>/libs/bootstrap5/js/bootstrap.bundle.min.js"></script>
    <script src="<?= base_url('public') ?>/libs/aos/aos.js"></script>
    <script src="<?= base_url('public') ?>/libs/glightbox/js/glightbox.min.js"></script>
    <script src="<?= base_url('public') ?>/libs/purecounter/purecounter_vanilla.js"></script>
    <script src="<?= base_url('public') ?>/libs/swiper/swiper-bundle.min.js"></script>
    <script src="<?= base_url('public') ?>/libs/isotope-layout/isotope.pkgd.min.js"></script>
    <script src="<?= base_url('public') ?>/libs/php-email-form/validate.js"></script>

    <!-- Template Main JS File -->
    <script src="<?= base_url('public') ?>/front/js/main.js"></script>

</body>

</html>