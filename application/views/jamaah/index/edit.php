<div class="container-fluid">
    <!-- Page header section  -->
    <div class="block-header">
        <div class="row justify-content-end">
            <div class="col-offset-2 col-lg-8 col-md-12 col-sm-12 text-lg-right">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item" aria-current="page"><a href="<?= site_url('dashboard') ?>">Beranda</a></li>
                        <li class="breadcrumb-item" aria-current="page"><a href="<?= site_url('jamaah/index') ?>">Data Jamaah</a></li>
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
                    <form id="form-jamaah-edit" enctype="multipart/form-data" data-parsley-validate>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="hidden" name="id" value="<?= $data->id ?>">
                                    <input type="hidden" name="id_user" value="<?= $data->id_user ?>">
                                    <label>Nama Jamaah <b class="text-danger">*</b></label>
                                    <input type="text" name="nama" value="<?= $data->nama ?>" id="nama" class="form-control" placeholder="Nama Jamaah" required>
                                </div>
                                <div class="form-group">
                                    <label>Email <b class="text-danger">*</b></label>
                                    <input type="text" name="email" value="<?= $data->email ?>" id="email" class="form-control"  placeholder="Email Jamaah" data-parsley-type="email" required>
                                </div>
                                <!-- <div class="form-group">
                                    <label>Jenis Kelamin <b class="text-danger">*</b></label><br>
                                    <label class="fancy-radio"><input name="jenis_kelamin" value="Laki-laki" <?php echo ($data->jenis_kelamin == 'Laki-Laki' ? 'checked': '') ?> required type="radio"><span><i></i>Laki-laki</span></label>
                                    <label class="fancy-radio"><input name="jenis_kelamin" value="Perempuan" <?php echo ($data->jenis_kelamin == 'Perempuan' ? 'checked': '') ?> required type="radio"><span><i></i>Perempuan</span></label>
                                    <div id="error_jenkel"></div>
                                </div>
                                <div class="form-group">
                                    <label>Tempat, tanggal lahir <b class="text-danger">*</b></label>
                                    <div class="row">
                                        <div class="col-sm-7">
                                            <input type="text" class="form-control" placeholder="Tempat Lahir" value="<?= $data->tempat_lahir ?>" name="tempat_lahir" required>
                                        </div>
                                        <div class="col-sm-5">
                                            <div class="input-group">
                                                <input type="text" name="tanggal_lahir" value="<?= $data->tanggal_lahir ?>" class="form-control date datepicker" maxlength="10" data-parsley-errors-container="#error_tglLahir" required Readonly>
                                                <div class="input-group-append">
                                                    <span class="input-group-text">
                                                        <i class="fa fa-calendar"></i>
                                                    </span>
                                                </div>
                                            </div>
                                            <div id="error_tglLahir"></div>
                                        </div>
                                    </div>
                                </div> -->
                                <div class="form-group">
                                    <label>Nomor Telepon <b class="text-danger">*</b></label>
                                    <input type="number" name="no_hp" id="no_hp" value="<?= $data->no_hp ?>" onkeyup="validasi_nohp(this.value)" class="form-control" minlength="10" maxlength="15" placeholder="Nomor Telepon" required>
                                </div>
                                <div class="form-group">
                                    <label>Foto </label>
                                    <?php if($data->url_foto == null){ ?>
                                        <br><img class="img-thumbnail" src="<?= site_url('public/images/avatar.jpeg')?>" width="100px" height="100px" />
                                    <?php }else{ ?>
                                        <br><img class="img-thumbnail" src="<?= site_url('public/uploads/jamaah/') . $data->url_foto ?>" width="100px" height="100px" />
                                    <?php } ?>
                                    <input type="hidden" name="foto" value="<?= $data->url_foto ?>" id="foto">
                                    <br><br><button type="button" class="btn btn-md"  id="yes" value="yes">Ubah Foto</button>
                                    <button type="button" class="btn btn-md text-white" id="no" style="background-color: #008080!important;" value="no">Tidak Ubah Foto</button><br><br>
                                    <div id="ubah_foto" hidden="true">
                                        <input type="file" name="url_foto_edit" class="form-control" id="url_foto"><br>
                                        <div class="row">
                                            <div class="col-sm-8">
                                                <div class="alert alert-secondary" role="alert">
                                                    <p style="color: #008080;">Ketentuan Foto : <br> - Tipe File : jpg, jpeg, png <br> - Maksimal 3MB</p>                                    
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <?php if(getSessionRole() == 1) {?>
                                    <div class="form-group">
                                        <label>Nama Masjid <b class="text-danger">*</b></label>
                                        <select name="id_masjid" id="id_masjid" class="form-control select2" style="width: 100%;" data-parsley-errors-container="#error_id" required>
                                            <option selected disabled>Pilih Masjid</option>
                                        </select>
                                        <div id="error_id"></div>
                                    </div>
                                <?php }else{ ?>
                                    <div class="form-group">
                                        <input type="hidden" name="id_masjid" value="<?= $data->id_masjid ?>">
                                    </div>
                                <?php } ?>
                                <!-- <div class="form-group">
                                    <label>Asal Provinsi <b class="text-danger">*</b></label>
                                    <select name="kode_provinsi" id="kode_provinsi" class="form-control select2" style="width: 100%;" data-parsley-errors-container="#error_prov" required>
                                        <option disabled>Pilih Provinsi</option>
                                    </select>
                                    <div id="error_prov"></div>
                                </div>
                                <div class="form-group">
                                    <label>Asal Kabupaten <b class="text-danger">*</b></label>
                                    <select name="kode_kabupaten" id="kode_kabupaten" class="form-control select2" style="width: 100%;" data-parsley-errors-container="#error_kab" required>
                                        <option disabled>Pilih Kabupaten</option>
                                    </select>
                                    <div id="error_kab"></div>
                                </div>
                                <div class="form-group">
                                    <label>Asal Kecamatan <b class="text-danger">*</b></label>
                                    <select name="kode_kecamatan" id="kode_kecamatan" class="form-control select2" style="width: 100%;" data-parsley-errors-container="#error_kec" required>
                                        <option disabled>Pilih Kecamatan</option>
                                    </select>
                                    <div id="error_kec"></div>
                                </div> -->
                                <div class="form-group">
                                    <label>Alamat Lengkap <b class="text-danger">*</b></label>
                                    <textarea name="alamat" id="alamat" class="form-control" maxlength="255" rows="3" required><?= $data->alamat ?></textarea>
                                </div>
                            </div>
                        </div><br>
                        <div class="form-group d-flex justify-content-center">
                            <a href="<?php echo site_url('jamaah/index') ?>" class="btn btn-danger"><i class="fas fa-times"></i> Kembali</a>&nbsp;
                            <button class="btn text-light" style="background-color: #008080!important;" type="submit"><i class="fa-solid fa-pen-to-square"></i> Update</button>
                        </div>
                    </form>
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
    const data = '<?php echo json_encode($data) ?>';
    const referensi = JSON.parse(data);
</script>