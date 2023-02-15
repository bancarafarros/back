<div class="container-fluid mt-2">
    <div class="block-header">
        <div class="row clearfix">
            <div class="col-lg-4 col-md-12 col-sm-12"></div>
            <div class="col-lg-8 col-md-12 col-sm-12 text-lg-right">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item" aria-current="page"><a href="<?= site_url('dashboard') ?>">Beranda</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?php echo $title ?></li>
                    </ol>
                </nav>

            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
        <small>
            <?php echo $output->output; ?>
        </small>
        </div>
    </div><!-- row -->
</div><!-- container -->

<?php $this->load->view('template/template-scripts') ?>