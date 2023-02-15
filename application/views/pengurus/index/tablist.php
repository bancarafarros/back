<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mg-b-0 mg-lg-b-0 mg-xl-b-0 mb-2">
        <div>
            <h4 class="mg-b-0 tx-spacing-1"><a href="<?php echo base_url('pengurus/index') ?>" class="btn btn-warning text-white btn-xs"><i class="fas fa-arrow-left"></i> Kembali</a> || <?= $title ?></h4>
        </div>
    </div>

    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="home-tab" data-toggle="tab" data-bs-toogle="tab" href="#home" role="tab" aria-controls="home" aria-selected="false">Detail Pengurus</a>
        </li>
        <!-- <li class="nav-item">
            <a class="nav-link" id="tabungan-tab" data-toggle="tab" href="#tabungan" role="tab" aria-controls="tabungan" aria-selected="false">Daftar Tabungan</a>
        </li> -->
    </ul>
    
    <div class="tab-content bd bd-gray-300 bd-t-0 pd-20" id="myTabContent">
        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
            <?php $this->load->view('pengurus/index/detail'); ?>
        </div>
    </div>
</div>
</div>
</div>
<!-- append theme customizer -->
<?php $this->load->view('template/template-scripts') ?>