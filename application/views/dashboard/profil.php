<div class="container-fluid">
    <!-- Page header section  -->
    <div class="block-header">
        <div class="row justify-content-end">
            <div class="col-offset-2 col-lg-8 col-md-12 col-sm-12 text-lg-right">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item" aria-current="page"><a href="<?= base_url('dashboard') ?>">Beranda</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?php echo $title ?></li>
                    </ol>
                </nav>

            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="d-md-flex align-items-center">
                        <div class="text-center text-sm-left ">
                            <div style="width: 150px; height:150px">
                                <img class="rounded" src="<?php echo base_url('public') ?>/back/images/user.png" alt="">
                            </div>
                        </div>
                        <div class="text-center text-sm-left m-v-15 p-l-30">
                            <h2 class="m-b-5"><?= $profil->real_name ?></h2>
                            <p class="text-opacity font-size-13">@<?= $profil->username ?></p>
                            <p class="text-dark font-size-13"><?= $profil->nama_group ?></p>
                            <p class="text-dark font-size-13"><?= $profil->email ?></p>
                            <p class="text-dark font-size-13">Terakhir Login: <?= $this->tanggalindo->konversi_tgl_jam($profil->last_login_time) ?></p>
                            <a href="<?= site_url('dashboard/profiledit') ?>" role="button" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> Ubah Profil</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('template/template-scripts') ?>