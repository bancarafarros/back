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
<div class="container-fluid">
    <!-- Page header section  -->
    <div class="block-header">
        <div class="row justify-content-end">
            <div class="col-offset-2 col-lg-8 col-md-12 col-sm-12 text-lg-right">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item" aria-current="page"><a href="<?= base_url('dashboard') ?>">Beranda</a></li>
                        <li class="breadcrumb-item" aria-current="page"><a href="<?= base_url('pengurus') ?>">Data Pengurus</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?php echo $title ?></li>
                    </ol>
                </nav>

            </div>
        </div>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12">
            <div class="card planned_task">
                <div class="body">
                    <!-- <form action="" id="form-pengurus"> -->
                    <form action="" id="form-pengurus" enctype="multipart/form-data" data-parsley-validate>
                        <div class="form-group">
                            <label>Nama Pengurus <b class="text-danger">*</b></label>
                            <input type="text" name="nama" class="form-control" placeholder="Nama Pengurus" minlength="3" maxlength="100" required>
                        </div>
                        <div class="form-group">
                            <label>Jabatan <b class="text-danger">*</b></label>
                            <select name="jabatan" id="jabatan" class="form-control" required>
                                <option value="">Pilih Jabatan</option>
                                <option value="Ketua">Ketua</option>
                                <option value="Bendahara">Bendahara</option>
                                <option value="Sekretaris">Sekretaris</option>
                                <option value="Anggota">Anggota</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Jenis Kelamin <b class="text-danger">*</b></label>
                            <select name="jenis_kelamin" id="jenis_kelamin" class="form-control" required>
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="Laki-laki">Laki-laki</option>
                                <option value="Perempuan">Perempuan</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>No HP <b class="text-danger">*</b></label>
                            <input type="text" class="form-control numeric no_hp" id="no_hp" name="no_hp" onkeypress="return onlyNumber(event)" onkeyup="validasi_nohp(this.value)" minlength="10" maxlength="13" required placeholder="08..">
                        </div>
                        <div class="form-group">
                            <label>Alamat<b class="text-danger">*</b></label>
                            <input type="text" name="alamat" id="alamat" class="form-control" rows="4" required placeholder="Alamat">
                        </div>
                        <div class="form-group">
                            <label>Email <b class="text-danger">*</b></label>
                            <input type="email" name="email" maxlength="100" class="form-control" required placeholder="Email">
                        </div>
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
                        <div class="form-group">
                            <label>Tanggal Lahir <b class="text-danger">*</b></label>
                            <input data-provide="datepicker" name="tanggal_lahir" required readonly data-date-autoclose="true" class="form-control datepicker" data-date-format="yyyy-mm-dd" placeholder="Pilih Tanggal Lahir">
                        </div>
                        <div class="form-group">
                            <label>Tempat Lahir <b class="text-danger">*</b></label>
                            <input type="text" name="tempat_lahir" minlength="3" maxlength="100" class="form-control" required placeholder="Tempat Lahir">
                        </div>
                        <div class="form-group">
                            <label>Asal Provinsi <b class="text-danger">*</b></label>
                            <select name="kode_provinsi" id="kode_provinsi" class="form-control select2" style="min-width: 100%; width: 100%;" required data-parsley-errors-container="#kode_provinsi-error">
                                <option value="">Pilih Provinsi</option>
                            </select>
                            <div id="kode_provinsi-error"></div>
                        </div>
                        <div class="form-group">
                            <label>Asal Kabupaten <b class="text-danger">*</b></label>
                            <select name="kode_kabupaten" id="kode_kabupaten" class="form-control select2" style="min-width: 100%; width: 100%;" required data-parsley-errors-container="#kode_kabupaten-error">
                                <option selected disabled value="">Pilih Provinsi Terlebih Dahulu</option>
                            </select>
                            <div id="kode_kabupaten-error"></div>
                        </div>
                        <div class="form-group">
                            <label>Asal Kecamatan<b class="text-danger">*</b></label>
                            <select name="kode_kecamatan" id="kode_kecamatan" class="form-control select2" style="min-width: 100%; width: 100%;" required data-parsley-errors-container="#kode_kecamatan-error">
                                <option selected disabled value="">Pilih Kabupaten Terlebih Dahulu</option>
                            </select>
                            <div id="kode_kecamatan-error"></div>
                        </div>
                        <div class="form-group">
                            <label>Foto </label><br>
                            <button class="btn" type="button" id="yes" value="yes" name="yes" style="color: #008080;">Tambah Foto</button><br><br>
                            <div id="tambah_foto" hidden="true">
                                <input type="file" name="url_foto" id="url_foto" class="form-control" placeholder="Foto" style="min-width: 100%; width: 100%;">
                                <small class="help-block">Ukuran foto maksimal 3 MB dan tipe foto yang diizinkan hanya PNG, JPG, JPEG, serta PDF</small>
                            </div>
                        </div>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <!-- <div id="id-pengurus"></div> -->
                    <!-- <input type="hidden" name="id_masjid"> -->
                    <a class="btn btn-danger" href="<?php echo site_url('pengurus/index') ?>" data-dismiss="modal"><i class="fas fa-times"></i> Tutup</a>
                    <button class="btn btn-success" type="submit"><i class="fas fa-save"></i> Simpan</button>
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