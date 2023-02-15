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
                    <form id="form-jamaah" enctype="multipart/form-data" data-parsley-validate>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nama Jamaah <b class="text-danger">*</b></label>
                                    <input type="text" name="nama" id="nama" class="form-control" placeholder="Nama Jamaah" required>
                                </div>
                                <div class="form-group">
                                    <label>Email <b class="text-danger">*</b></label>
                                    <input type="text" name="email" id="email" class="form-control" placeholder="Email Jamaah" required data-parsley-type="email">
                                </div>
                                <!-- <div class="form-group">
                                    <label>Jenis Kelamin <b class="text-danger">*</b></label><br>
                                    <label class="fancy-radio"><input name="jenis_kelamin" value="Laki-laki" type="radio" data-parsley-errors-container="#error_jenkel" required><span><i></i>Laki-laki</span></label>
                                    <label class="fancy-radio"><input name="jenis_kelamin" value="Perempuan" type="radio" required><span><i></i>Perempuan</span></label>
                                    <div id="error_jenkel"></div>
                                </div> -->
                                <!-- <div class="form-group">
                                    <label>Tempat, tanggal lahir <b class="text-danger">*</b></label>
                                    <div class="row">
                                        <div class="col-sm-7">
                                            <input type="text" class="form-control" placeholder="Tempat Lahir" name="tempat_lahir" required>
                                        </div>
                                        <div class="col-sm-5">
                                            <div class="input-group">
                                                <input type="text" name="tanggal_lahir" placeholder="Tanggal Lahir" class="form-control date datepicker" maxlength="10" data-parsley-errors-container="#error_tglLahir" required Readonly>
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
                                    <input type="number" name="no_hp" id="no_hp" class="form-control" placeholder="Nomor Telepon" minlength="10" maxlength="15" onkeyup="validasi_nohp(this.value)" required>
                                </div>
                                <div class="form-group">
                                    <label>Foto</label><br>
                                    <button class="btn" type="button" id="yes" value="yes" name="yes" style="color: #008080;">Tambah Foto</button><br><br>
                                    <div id="tambah_foto" hidden="true">
                                        <input type="file" name="url_foto" id="url_foto" class="form-control" placeholder="Nama Jamaah" accept="image/png, image/jpg, image/jpeg"><br>
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
                                        <input type="hidden" name="id_masjid" value="<?= $id ?>">
                                    </div>
                                <?php } ?>
                                <!-- <div class="form-group">
                                    <label>Asal Provinsi <b class="text-danger">*</b></label>
                                    <select name="kode_provinsi" id="kode_provinsi" class="form-control select2" style="width: 100%;" data-parsley-errors-container="#error_prov" required>
                                        <option selected disabled>Pilih Provinsi</option>
                                    </select>
                                    <div id="error_prov"></div>
                                </div>
                                <div class="form-group">
                                    <label>Asal Kabupaten <b class="text-danger">*</b></label>
                                    <select name="kode_kabupaten" id="kode_kabupaten" class="form-control select2" style="width: 100%;" data-parsley-errors-container="#error_kab" required>
                                        <option selected disabled>Pilih Kabupaten</option>
                                    </select>
                                    <div id="error_kab"></div>
                                </div>
                                <div class="form-group">
                                    <label>Asal Kecamatan <b class="text-danger">*</b></label>
                                    <select name="kode_kecamatan" id="kode_kecamatan" class="form-control select2" style="width: 100%;" data-parsley-errors-container="#error_kec" required>
                                        <option selected disabled>Pilih Kecamatan</option>
                                    </select>
                                    <div id="error_kec"></div>
                                </div> -->
                                <div class="form-group">
                                    <label>Alamat Lengkap <b class="text-danger">*</b></label>
                                    <textarea name="alamat" id="alamat" class="form-control" maxlength="255" rows="3" required></textarea>
                                </div>
                            </div>
                        </div><br>
                        <div class="form-group d-flex justify-content-center">
                            <a href="<?php echo site_url('jamaah/index') ?>" class="btn btn-danger"><i class="fas fa-times"></i> Kembali</a>&nbsp;
                            <button class="btn text-light" style="background-color: #008080!important;" type="submit"><i class="fas fa-save"></i> Simpan</button>
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