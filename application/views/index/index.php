<!-- ======= About Us Section ======= -->
<section id="about" class="about">
    <div class="container" data-aos="fade-up">
        <div class="section-header text-center">
            <p>Ayo download Sajada app!</p>
            <h2>Gabung Menjadi Jama'ah Masjid</h2>
        </div>
        <div class="row gy-4">
            <div class="col-lg-6">
                <img src="<?= base_url('public') ?>/images/img-gabung.png" class="img-fluid rounded-4 mb-4" alt="">
            </div>
            <div class="col-lg-6">
                <div class="content ps-0 ps-lg-5">
                    <p class="">
                        Dapatkan benefit tambahan saat menjadi jama'ah
                    </p>
                    <ul>
                        <li>
                            <div class="fs-2">
                                <img src="<?= base_url('public') ?>/icons/quran.svg">
                                Mengaji Bersama
                            </div>
                            <p style="padding-left: 45px;">
                                Kamu bisa mengikuti berbagai kajian yang disediakan di aplikasi ini, mulai dari kitab, zakat dan masih banyak lagi.
                            </p>
                        </li>
                        <li>
                            <div class="fs-2">
                                <img src="<?= base_url('public') ?>/icons/book.svg">
                                Belajar Baca Al-Qur'an
                            </div>
                            <p style="padding-left: 45px;">
                                Untuk kamu yang terhalang jarak untuk belajar AL-Qur'an di masjid, bisa banget memanfaatkan aplikasi ini dan kamu bisa belajar dari aplikasi.
                            </p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End About Us Section -->
<section id="fiturs" class="fiturs">
    <div class="container">
        <div class="section-header">
            <p>Sajada App</p>
            <h2>Fitur Utama Kami</h2>
        </div>
        <div class="row">
            <?php if (!empty($fiturs)) :
                foreach ($fiturs as $fitur) : ?>
                    <div class="col-md-3 mb-2">
                        <div class="card card-fitur">
                            <div class="card-body">
                                <div class="d-flex">
                                    <img src="<?= base_url('public/icons/' . $fitur['icon']) ?>" class="img-fitur img-fluid">
                                    <div class="deskripsi-fitur">
                                        <span class="fw-bolder"><?= $fitur['fitur'] ?></span><br>
                                        <small><?= $fitur['deskripsi'] ?></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            <?php endforeach;
            endif; ?>
        </div>
    </div>
</section>
<!-- ======= Stats Counter Section ======= -->
<section id="stats-counter" class="stats-counter">
    <div class="container" data-aos="fade-up">
        <div class="section-header">
            <p>Jadilah bagian dari sajada app</p>
            <h2>Cari Tahu Tentang Sajada</h2>
        </div>
        <div class="row gy-4 align-items-center">
            <div class="col-lg-6">
                <img src="<?= base_url('public') ?>/images/img-tentang.png" alt="" class="img-fluid">
            </div>
            <div class="col-lg-6">
                <div class="row">
                    <div class="col-md-6 mb-2">
                        <div class="stats-item text-center">
                            <span data-purecounter-start="0" data-purecounter-end="560" data-purecounter-duration="1" class="purecounter" style="display: inline; padding: 0;">
                            </span>
                            <span style="display: inline;">
                                <i class="fas fa-plus" aria-hidden="true"></i>
                                <p><b>User Rating</b></p>
                            </span>                            
                        </div>
                    </div>
                    <div class="col-md-6 mb-2">
                        <div class="stats-item text-center">
                            <span data-purecounter-start="0" data-purecounter-end="560" data-purecounter-duration="1" class="purecounter" style="display: inline; padding: 0;">
                            </span>
                            <span style="display: inline;">
                                <i class="fas fa-plus" aria-hidden="true"></i>
                                <p><b>Download</b></p>
                            </span>
                            
                        </div>
                    </div>
                    <div class="col-md-12 mt-4">
                        <p>
                            Kamu masih bingung cari masjid terdekat dimana? mau cari tempat sadaqah yang amanah dan bisa belajar Ilmu agama tanpa harus ribet keluar rumah?
                        </p>
                        <p>
                            Kini kami kenalkan dengan aplikasi yang bernama Sajada, yang bisa didownload di android masing-masing. Aplikasi ini di design bukan hanya untuk kalangan anak muda saja loh, tetapi untuk kalangan orang tua juga bisa menggunakannya, aplikasinya juga sangat mudah digunakan, penasaran gimana sama aplikasinya? buruan download aplikasinya ya.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="section-all-get">
    <div class="apalah" data-aos="fade-up">
        <img src="<?= base_url('public');?>/images/image-all-get.png" class="img-fluid  d-sm-block d-none" style="height: 100%; width: 100%; position: absolute;" >
        <div class="row gy-2">
            <div class="col-lg-6 order-2 order-lg-1 text-center text-lg-start carousel-caption-left" style="position:relative; padding: 10% ">
                <h2 style="color: #ffff;">Dapatkan Semuanya<br>di Sajada</h2>
                <p class="text-light">
                    Paket lengkap yang disediakan oleh aplikasi ini segera dapatkan di google play store dibawah ini. 
                </p>
                <div class="d-flex justify-content-center justify-content-lg-start">
                    <img src="<?= base_url('public') ?>/images/img-download-now.png" class="w-50 img-fluid" style="padding-top: 30px;">
                </div>
            </div>
        </div>
    </div>
</section>
<!-- ======= Testimonials Section ======= -->
<section id="testimonials" class="testimonials text-center">
    <div class="container" data-aos="fade-up">
        <div class="section-header">
            <p>Testimoni</p>
            <h2>Testimoni</h2>
        </div>
        <div class="slides-3 swiper" data-aos="fade-up" data-aos-delay="100">
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <div class="testimonial-wrap">
                        <div class="testimonial-item">
                            <div class="d-flex align-items-center">
                                <p>
                                    <i class="bi bi-quote quote-icon-left"></i><br>
                                    Aplikasinya bagus banget, udah lengkap pula, jadi betah kalo berlama-lama di aplikasinya, aku akan rekomendasi in ke orang tua aku juga deh
                                    <!-- <i class="bi bi-quote quote-icon-right"></i> -->
                                </p>
                                <img src="<?= base_url('public') ?>/images/testimoni/testimoni1.png" class="testimonial-img flex-shrink-0" alt="">
                            </div>
                            <div class="text-start">
                                <h3>Pengguna 1</h3>
                                <h4>Tanggal</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="swiper-slide">
                    <div class="testimonial-wrap">
                        <div class="testimonial-item">
                            <div class="d-flex align-items-center">
                                <p>
                                    <i class="bi bi-quote quote-icon-left"></i><br>
                                    Aplikasinya bagus banget, udah lengkap pula, jadi betah kalo berlama-lama di aplikasinya, aku akan rekomendasi in ke orang tua aku juga deh
                                    <!-- <i class="bi bi-quote quote-icon-right"></i> -->
                                </p>
                                <img src="<?= base_url('public') ?>/images/testimoni/testimoni1.png" class="testimonial-img flex-shrink-0" alt="">
                            </div>
                            <div class="text-start">
                                <h3>Pengguna 2</h3>
                                <h4>Tanggal</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </div>
</section>
<!-- End Testimonials Section -->
<!-- ======= Frequently Asked Questions Section ======= -->
<section id="faq" class="faq">
    <div class="container" data-aos="fade-up">
        <div class="section-header text-center">
            <p>Bingung sama aplikasinya?</p>
            <h2>FAQ</h2>
        </div>
        <div class="row gy-4">
            <div class="accordion" id="faqlist" data-aos="fade-up" data-aos-delay="100">
                <?php if (!empty($faqs)) : $nomor = 1;
                    foreach ($faqs as $i => $faq) : ?>
                        <div class="accordion-item">
                            <h3 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-content-<?= $nomor ?>">
                                    <span class="num"><?= $nomor ?>.</span>
                                    <?= $faq['faq'] ?>
                                </button>
                            </h3>
                            <div id="faq-content-<?= $nomor ?>" class="accordion-collapse collapse" data-bs-parent="#faqlist">
                                <div class="accordion-body">
                                    <?= $faq['answer'] ?>
                                </div>
                            </div>
                        </div>
                <?php $nomor++;
                    endforeach;
                endif; ?>
            </div>
        </div>
    </div>
</section>