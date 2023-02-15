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
                        <li class="breadcrumb-item" aria-current="page"><a href="<?= site_url('masjid/index') ?>">Data Masjid</a></li>
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
                    <form action="" id="form-masjid" data-parsley-validate>
                        <div class="row clearfix">
                            <div class="col-md-6">
                                <h4>Informasi Dasar Data Masjid</h4>
                                <div class="form-group">
                                    <label>Nama Masjid/Mushola <b class="text-danger">*</b></label>
                                    <input type="text" name="nama" class="form-control" required minlength="3" maxlength="100" placeholder="Nama Masjid/Mushola">
                                </div>
                                <div class="form-group">
                                    <label>Nama PJ Takmir <b class="text-danger">*</b></label>
                                    <input type="text" name="nama_pj_takmir" class="form-control" placeholder="Nama PJ takmir" required minlength="3" maxlength="100">
                                </div>
                                <div class="form-group">
                                    <label>Jabatan Takmir <b class="text-danger">*</b></label>
                                    <select name="jabatan_takmir" id="jabatan_takmir" class="form-control select2" data-parsley-errors-container="#error-jabatan-takmir" required>
                                        <option value="">Pilih Jabatan</option>
                                    </select>
                                    <div id="error-jabatan-takmir"></div>
                                </div>
                                <div class="form-group">
                                    <label>Nomor HP Takmir <b class="text-danger">*</b></label>
                                    <input type="text" id="no_hp" name="no_hp" onkeypress="return onlyNumber(event)" class="form-control" placeholder="No HP takmir" required minlength="11" maxlength="13">
                                </div>
                                <div class="form-group">
                                    <label>Email Takmir <b class="text-danger">*</b></label>
                                    <input type="email" name="email" class="form-control" placeholder="Email takmir" required maxlength="100">
                                </div>
                                <div class="form-group">
                                    <label>Asal Provinsi <b class="text-danger">*</b></label>
                                    <select name="kode_provinsi" id="kode_provinsi" class="form-control select2" style="width: 100%;" data-parsley-errors-container="#error-kode-provinsi" required>
                                        <option selected disabled value="">Pilih Provinsi</option>
                                    </select>
                                    <div id="error-kode-provinsi"></div>
                                </div>
                                <div class="form-group">
                                    <label>Asal Kabupaten <b class="text-danger">*</b></label>
                                    <select name="kode_kabupaten" id="kode_kabupaten" class="form-control select2" style="width: 100%;" data-parsley-errors-container="#error-kode-kabupaten" required>
                                        <option selected disabled value="">Pilih Provinsi Terlebih Dahulu</option>
                                    </select>
                                    <div id="error-kode-kabupaten"></div>
                                </div>
                                <div class="form-group">
                                    <label>Asal Kecamatan<b class="text-danger">*</b></label>
                                    <select name="kode_kecamatan" id="kode_kecamatan" class="form-control select2" style="width: 100%;" data-parsley-errors-container="#error-kode-kecamatan" required>
                                        <option selected disabled value="">Pilih Kabupaten Terlebih Dahulu</option>
                                    </select>
                                    <div id="error-kode-kecamatan"></div>
                                </div>
                                <div class="form-group">
                                    <label>Alamat Lengkap <b class="text-danger">*</b></label>
                                    <textarea name="alamat" id="alamat" class="form-control" maxlength="255" rows="6" placeholder="Alamat lengkap" required></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h4>Informasi Detail Bangunan</h4>
                                <div class="form-group">
                                    <label>Kategori Bangunan<b class="text-danger">*</b></label>
                                    <!-- <br> -->
                                    <div class="mb-3">
                                        <label class="fancy-radio">
                                            <input name="type" value="Masjid" required type="radio" data-parsley-errors-container="#error-tipe-masjid"><span><i></i>Masjid</span>
                                        </label>
                                        <label class="fancy-radio">
                                            <input name="type" value="Mushola" required type="radio"><span><i></i>Mushola</span>
                                        </label>
                                        <div id="error-tipe-masjid"></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Typologi Bangunan<b class="text-danger">*</b></label>
                                    <select name="id_ref_typologi" id="id_ref_typologi" class="form-control select2" required style="width:100%;" data-parsley-errors-container="#error-typologi">
                                        <option value="">Pilih Typologi</option>
                                    </select>
                                    <div id="error-typologi"></div>
                                </div>
                                <div class="form-group">
                                    <label>Tahun Berdiri <b class="text-danger">*</b></label>
                                    <input type="text" name="tahun_berdiri" onkeypress="return onlyNumber(event)" class="form-control" required placeholder="Tahun berdiri" minlength="4" maxlength="4">
                                </div>
                                <div class="form-group">
                                    <label>Kondisi bangunan <b class="text-danger">*</b></label>
                                    <select name="kondisi" id="kondisi" class="form-control select2" style="width:100%;" data-parsley-errors-container="#error-kondisi-bangunan" required>
                                        <option value="">Pilih Kondisi Bangunan</option>
                                        <option value="Baik">Baik</option>
                                        <option value="Rusak Ringan">Rusak Ringan</option>
                                        <option value="Kurang Baik">Kurang Baik</option>
                                        <option value="Rusak Berat">Rusak Berat</option>
                                    </select>
                                    <div id="error-kondisi-bangunan"></div>
                                </div>
                                <div class="form-group">
                                    <label>Daya Tampung <b class="text-danger">*</b></label>
                                    <div class="row">
                                        <div class="col-sm-8">
                                            <input type="text" name="daya_tampung" onkeypress="return onlyNumber(event)" class="form-control" required placeholder="Daya tampung orang" minlength="1" maxlength="4">
                                        </div>
                                        <div class="col-sm-4">
                                            <b class="help-block">/ Orang</b>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Luas Tanah <b class="text-danger">*</b></label>
                                    <div class="row">
                                        <div class="col-sm-8">
                                            <input type="text" name="luas_tanah" onkeypress="return onlyNumber(event)" class="form-control" required placeholder="Luas Tanah" minlength="1" maxlength="4">
                                        </div>
                                        <div class="col-sm-4">
                                            <b class="help-block">Dalam m<sup>2</sup></b>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Luas Bangunan <b class="text-danger">*</b></label>
                                    <div class="row">
                                        <div class="col-sm-8">
                                            <input type="text" name="luas_bangunan" onkeypress="return onlyNumber(event)" class="form-control" required placeholder="Luas Bangunan" minlength="1" maxlength="4">
                                        </div>
                                        <div class="col-sm-4">
                                            <b class="help-block">Dalam m<sup>2</sup></b>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Status Tanah <b class="text-danger">*</b></label>
                                    <select name="ref_id_status_tanah" id="ref_id_status_tanah" style="width:100%;" class="form-control select2" data-parsley-errors-container="#error-status-tanah" required>
                                        <option value="">Pilih Status Tanah</option>
                                    </select>
                                    <div id="error-status-tanah"></div>
                                </div>
                                <div class="form-group">
                                    <label>Organisasi Afiliasi <b class="text-danger">*</b></label>
                                    <select name="ref_id_afiliasi" id="ref_id_afiliasi" class="form-control select2" style="width:100%;" data-parsley-errors-container="#error-afiliasi" required>
                                        <option value="">Pilih Afiliasi</option>
                                    </select>
                                    <div id="error-afiliasi"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Latitude <b class="text-danger">*</b></label>
                                    <input type="text" name="latitude" id="latitude" class="form-control" readonly required placeholder="latitude">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Longitude <b class="text-danger">*</b></label>
                                    <input type="text" name="longitude" id="longitude" class="form-control" readonly required placeholder="longitude">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Lokasi Masjid <b class="text-danger">*</b></label>
                            <div id="map" style="width: 100%; height: 400px;"></div>
                        </div>
                        <div class="form-group d-flex justify-content-center">
                            <a href="<?php echo site_url('masjid/index') ?>" class="btn btn-danger"><i class="fas fa-times"></i> Kembali</a>&nbsp;
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