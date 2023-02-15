    <div class="container-fluid">
    <div class="block-header">
        <div class="row justify-content-end">
            <div class="col-offset-2 col-lg-8 col-md-12 col-sm-12 text-lg-right">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item" aria-current="page"><a href="<?= site_url('dashboard') ?>">Beranda</a></li>
                        <li class="breadcrumb-item" aria-current="page"><a href="<?= site_url('setting/groups') ?>">User Groups</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?php echo $title ?></li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <form action="<?= site_url("setting/groups/assign/" . $this->uri->segment(4)) ?>" method="post" class="mb-4">
        <div class="row">
        <?php foreach ($groupAccess as $access) : ?>
            <div class="col-sm-6 col-md-3">
            <div class="card mb-3">
                <div class="card-body">
                <div class="custom-control custom-checkbox">
                    <input name="access[]" type="checkbox" class="custom-control-input" id="<?= $access->nama_modul ?>" <?= (isset($access->hak_akses)) ? 'checked' : '' ?> value="<?= $access->nama_modul ?>">
                    <label class="custom-control-label" for="<?= $access->nama_modul ?>"><?= $access->nama_modul ?></label>
                </div>
                </div>
            </div>
            </div>
        <?php endforeach; ?>
        </div>

        <div class="w-100 d-flex justify-content-sm-center justify-content-md-center justify-content-lg-end align-items-center">
        <button type="submit" class="mb-4 btn btn-primary">Simpan</button>
        </div>
    </form>
    </div>

    <?php $this->load->view('template/template-scripts') ?>
    <?php
    if (isset($output->js_files)) {
    foreach ($output->js_files as $file) {
        echo '<script src="' . $file . '"></script>';
    }
    }
    ?>