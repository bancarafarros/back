<div class="container-fluid">
    <!-- Page header section  -->
    <div class="block-header">
        <div class="row justify-content-end">
            <div class="col-offset-2 col-lg-8 col-md-12 col-sm-12 text-lg-right">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item" aria-current="page"><a href="<?= base_url('dashboard') ?>">Beranda</a></li>
                        <li class="breadcrumb-item" aria-current="page"><a href="<?= base_url('dashboard/profil') ?>">Profil</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?php echo $title ?></li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="text-center">
                        <div style="width: 150px; height:150px">
                            <img class="rounded" src="<?php echo base_url('public') ?>/back/images/user.png" alt="">
                        </div>
                    </div>
                </div>
                <div class="col-md-8 mx-auto">
                    <form id="formprofil" class="form" action="<?= site_url('dashboard/prosesedit') ?>" method="post">
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input class="form-control" name="username" id="username" type="text" placeholder="Username" value="<?= $profil->username ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label for="real_name">Nama Lengkap</label>
                            <input class="form-control" name="real_name" id="real_name" type="text" placeholder="Nama lengkap" value="<?= $profil->real_name ?>">
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input class="form-control" name="email" id="email" type="email" placeholder="Email" value="<?= $profil->email ?>">
                        </div>
                        <div class="form-group">
                            <label for="user_group">Group User</label>
                            <input class="form-control" id="user_group" type="text" placeholder="User group" value="<?= $profil->nama_group ?>" readonly>
                        </div>
                        <!-- <div class="form-group">
                            <div class="checkbox">
                                <input id="ubah-foto" value="1" type="checkbox">
                                <label for="ubah-foto">Ubah Foto</label>
                            </div>
                        </div>
                        <div class="form-group d-none" id="container-image">
                            <label for="user_group">Ubah Foto</label>
                            <div class="custom-file">
                                <input type="file" name="image" class="custom-file-input" id="image" onchange="cekFile(this)" accept="image/*">
                                <label class="custom-file-label" for="image">Pilih file</label>
                            </div>
                            <span class="file-info text-bold ml-10"></span>
                            <div class="hint-block text-muted mt-5">
                                <small>
                                    Jenis file yang diijinkan: <strong>PNG, JPEG, JPG, dan PDF</strong><br>
                                    Ukuran file maksimal: <strong>2 MB</strong>
                                </small>
                            </div>
                        </div> -->
                        <div class="form-group">
                            <a href="<?= site_url('dashboard/profil') ?>" role="button" class="btn btn-warning btn-sm"><i class="fa fa-angle-left"></i> Kembali ke Profil</a>
                            <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-save"></i> Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('template/template-scripts') ?>
<script>
    $(function() {
        // $('#ubah-foto').click(function() {
        //     if ($(this).val() == '1') {
        //         $(this).val('0');
        //         $('#container-image').removeClass('d-none');
        //         $('#image').attr('required', true);

        //     } else {
        //         $(this).val('1');
        //         $('#container-image').addClass('d-none');
        //         $('#image').attr('required', false);
        //     }
        // })
        $('#formprofil').submit(function(e) {
            // e.preventDefault();
            $.ajax({
                url: $('#formprofil').attr('action'),
                type: "POST",
                data: new FormData(this),
                dataType: "JSON",
                contentType: false,
                processData: false,
                beforeSend: () => {
                    showLoading()
                },
                success: function(response) {
                    hideLoading();
                    if (response.status) {
                        Swal.fire('Profil!', response.message, 'success').then((result) => {
                            location.reload();
                        });
                    } else {
                        Swal.fire('Profil', response.message, 'error').then((result) => {
                            location.reload();
                        });
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    hideLoading()
                    Swal.fire('Error!', 'Internal server error', 'error').then((result) => {
                        location.reload();
                    });
                }
            });

        })
    })
</script>