<div class="container-fluid">
    <!-- Page header section  -->
    <div class="block-header">
        <div class="row clearfix">
            <div class="col-lg-4 col-md-12 col-sm-12">
                <h1>Selamat Datang <b><?= $nama ?></b></h1>
                <span>Semoga hari anda menyenangkan</span>
            </div>
        </div>
    </div>
    <?php echo $this->session->userdata('message'); ?>
    <div class="row clearfix">
        <div class="col-12">
            <div class="card theme-bg gradient">
                <div class="card-body">
                    <?php if(getSessionRole() == 1) {?>
                    <div class="row">
                        <div class="col-lg-4 col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <div id="slider1" class="carousel vert slide" data-ride="carousel" data-interval="2700">
                                        <div class="carousel-inner">
                                            <div class="carousel-item active">
                                                <div>Jumlah Masjid</div>
                                                <div class="mt-3 h1"><i class="fas fa-mosque"></i> <?= $sum_masjid ?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <div id="slider2" class="carousel vert slide" data-ride="carousel" data-interval="2800">
                                        <div class="carousel-inner">
                                            <div class="carousel-item active">
                                                <div>Jumlah Pengurus</div>
                                                <div class="mt-3 h1"><i class="fa-solid fa-user"></i> <?= $sum_pengurus ?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <div id="slider3" class="carousel vert slide" data-ride="carousel" data-interval="3000">
                                        <div class="carousel-inner">
                                            <div class="carousel-item active">
                                                <div>Jumlah Jamaah</div>
                                                <div class="mt-3 h1"><i class="fa-solid fa-users"></i> <?= $sum_jamaah ?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php } else if(getSessionRole() == 2){?>
                    <div class="row">
                        <div class="col-lg-6 col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <div id="slider2" class="carousel vert slide" data-ride="carousel" data-interval="2800">
                                        <div class="carousel-inner">
                                            <div class="carousel-item active">
                                                <div>Jumlah Pengurus</div>
                                                <div class="mt-3 h1"><i class="fa-solid fa-user"></i> <?= $pengurus ?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <div id="slider3" class="carousel vert slide" data-ride="carousel" data-interval="3000">
                                        <div class="carousel-inner">
                                            <div class="carousel-item active">
                                                <div>Jumlah Jamaah</div>
                                                <div class="mt-3 h1"><i class="fa-solid fa-users"></i> <?= $jamaah ?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('template/template-scripts') ?>