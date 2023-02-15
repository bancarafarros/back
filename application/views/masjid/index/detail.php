<style>
    .marker {
        display: block;
        border: none;
        cursor: pointer;
        padding: 0;
        height: 38px;
        width: 32px;
    }
</style>
<link href="https://api.mapbox.com/mapbox-gl-js/v1.7.0/mapbox-gl.css" rel="stylesheet" />
<script src="https://api.mapbox.com/mapbox-gl-js/v1.7.0/mapbox-gl.js"></script>
<div class="container-fluid">
    <!-- Page header section  -->
    <div class="block-header">
        <div class="row justify-content-end">
            <div class="col-offset-2 col-lg-8 col-md-12 col-sm-12 text-lg-right">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item" aria-current="page"><a href="<?= site_url('dashboard') ?>">Beranda</a></li>
                        <li class="breadcrumb-item" aria-current="page"><a href="<?php echo site_url('masjid/index') ?>">Data Masjid</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?php echo $title ?></li>
                    </ol>
                </nav>

            </div>
        </div>
    </div>
    <div class="row clearfix">

        <div class="col-lg-12 col-md-12">
            <div class="card planned_task">
                <div class="header">
                    <h2><?php echo $title ?></h2>
                </div>
                <div class="body">
                    <ul class="nav nav-tabs3 white">
                        <li class="nav-item"><a class="nav-link show active" data-toggle="tab" href="#Home-new2">Profil Masjid</a></li>
                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#Profile-new2">Profil Pengurus</a></li>
                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#Contact-new2">Data Jamaah</a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane show active" id="Home-new2">
                            <h6>Profil Masjid</h6>
                            <?php $this->load->view('masjid/index/detail-profil') ?>
                        </div>
                        <div class="tab-pane" id="Profile-new2">
                            <h6>Profil Pengurus</h6>
                            <?php $this->load->view('masjid/index/detail-pengurus') ?>
                        </div>
                        <div class="tab-pane" id="Contact-new2">
                            <h6>Data Jamaah</h6>
                            <?php $this->load->view('masjid/index/detail-jamaah') ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal-masjid">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <b class="modal-title"></b>
            </div>
        </div>
    </div>
</div><?php $this->load->view('template/template-scripts') ?>
<script>
    const id_masjid = '<?php echo $id ?>';
    //unset data nama ini karena error jika ada nama masjid yang menggunakan petik(').
    <?php unset($data->nama) ?>;
    const data = '<?php echo json_encode($data) ?>';
    const referensi = JSON.parse(data);
</script>