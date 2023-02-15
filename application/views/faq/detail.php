<div class="container-fluid">
    <!-- Page header section  -->
    <div class="block-header">
        <div class="row justify-content-end">
            <div class="col-offset-2 col-lg-8 col-md-12 col-sm-12 text-lg-right">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item" aria-current="page"><a href="<?= base_url('dashboard') ?>">Beranda</a></li>
                        <li class="breadcrumb-item" aria-current="page"><a href="<?php echo site_url('faq') ?>">FAQ</a></li>
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
                    <table class="table table-striped table-sm">
                        <tr>
                            <th>FAQ</th>
                            <th>:</th>
                            <td><?php echo $data['faq'] ?></td>
                        </tr>
                        <tr>
                            <th>Jawaban</th>
                            <th>:</th>
                            <td><?php echo $data['answer'] ?></td>
                        </tr>
                        <tr>
                            <th>Aktif</th>
                            <th>:</th>
                            <td><?php echo ($data['is_active'] == 1) ? 'Aktif' : 'Tidak Aktif'; ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('template/template-scripts') ?>