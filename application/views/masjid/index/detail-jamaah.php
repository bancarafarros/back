<div class="row">
    <div class="col-md-6">
        <div class="form-inline">
            <div class="input-group">
                <input type="text" name="txt_cari_jamaah" class="form-control" placeholder="Type here...">
                <span class="input-group-append">
                    <button class="btn btn-outline-success" id="btn-cari-jamaah"><i class="fa fa-search"></i></button>
                </span>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <!-- <button class="btn btn-success float-right" id="btn-tambah-jamaah"><i class="fas fa-plus"></i> Tambah Jamaah</button> -->
    </div>
</div>
<div class="table-responsive mt-3">
    <table class="table table-striped table-hover table-bordered table-sm" id="tabel-jamaah">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama lengkap</th>
                <!-- <th>Jenis Kelamin</th> -->
                <th>Nomor HP</th>
                <th>Email</th>
                <!-- <th>Asal Provinsi</th>
                <th>Asal Kabupaten</th>
                <th>Asal Kecamatan</th> -->
                <!-- <th>Kelola</th> -->
            </tr>
        </thead>
    </table>
</div>

<div class="modal fade" id="modal-jamaah">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="form-jamaah" enctype="multipart/form-data" data-parsley-validate>
                <div class="modal-header">
                    <b class="modal-title">Form Modal</b>
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Nama lengkap <b class="text-danger">*</b></label>
                                <input type="text" name="nama" class="form-control" placeholder="nama lengkap" minlength="3" maxlength="100" required>
                            </div>
                            <div class="form-group">
                                <label>Tempat, tanggal lahir <b class="text-danger">*</b></label>
                                <div class="row">
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" name="tempat_lahir" required>
                                    </div>
                                    <div class="col-sm-5">
                                        <div class="input-group">
                                            <input type="text" name="tanggal_lahir" class="form-control date datepicker" maxlength="10" data-parsley-errors-container="#error_tglLahir" required Readonly>
                                            <div class="input-group-append">
                                                <span class="input-group-text">
                                                    <i class="fa fa-calendar"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <div id="error_tglLahir"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Email <b class="text-danger">*</b></label>
                                <input type="email" name="email" maxlength="100" class="form-control" required placeholder="Email">
                            </div>
                            <div class="form-group">
                                <label>Jenis Kelamin <b class="text-danger">*</b></label><br>
                                <label class="fancy-radio"><input name="jenis_kelamin" value="Laki-Laki"  type="radio" data-parsley-errors-container="#error_jenkel" required><span><i></i>Laki-laki</span></label>
                                <label class="fancy-radio"><input name="jenis_kelamin" value="Perempuan"  type="radio" required><span><i></i>Perempuan</span></label>
                                <div id="error_jenkel"></div>
                            </div>
                            <div class="form-group" id="tambah-foto">
                                <label>Foto</label><br>
                                <button class="btn" type="button" id="tambah" value="yes" name="yes" style="color: #008080;">Tambah Foto</button><br><br>
                                <div id="tambah_foto" hidden="true">
                                    <input type="file" name="url_foto" id="url-foto-tambah" class="form-control" placeholder="Nama Jamaah" accept="image/png, image/jpg, image/jpeg"><br>
                                    <div class="row">
                                        <div class="col-sm-8">
                                            <div class="alert alert-secondary" role="alert">
                                                <p style="color: #008080;">Ketentuan Foto : <br> - Tipe File : jpg, jpeg, png <br> - Maksimal 3MB</p>                                    
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group" id="edit-foto">
                                <label for="foto">Foto</label>
                                <div id="gambar"></div>
                                <br><button type="button" class="btn btn-md"  id="yes" value="yes">Ubah Foto</button>
                                <button type="button" class="btn btn-md text-white" id="no" style="background-color: #008080!important;" value="no">Tidak Ubah Foto</button><br><br>
                                <div id="ubah_foto" hidden="true">
                                    <input type="file" name="url_foto_edit" class="form-control" id="url-foto-edit"><br>   
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
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Nomor HP <b class="text-danger">*</b></label>
                                <input type="text" name="no_hp" id="no_hp_jamaah" onkeypress="return onlyNumber(event)" minlength="10" maxlength="13" class="form-control" required placeholder="08..">
                            </div>
                            <div class="form-group">
                                <label>Asal Provinsi <b class="text-danger">*</b></label>
                                <select name="kode_provinsi" id="kode_provinsi_jamaah" class="form-control select2" style="min-width: 100%; width: 100%;" data-parsley-errors-container="#error-provinsi-jamaah" required>
                                    <option selected disabled>Pilih Provinsi</option>
                                </select>
                                <div id="error-provinsi-jamaah"></div>
                            </div>
                            <div class="form-group">
                                <label>Asal Kabupaten <b class="text-danger">*</b></label>
                                <select name="kode_kabupaten" id="kode_kabupaten_jamaah" class="form-control select2" style="min-width: 100%; width: 100%;" data-parsley-errors-container="#error-kabupaten-jamaah" required>
                                    <option selected disabled>Pilih Kabupaten</option>
                                </select>
                                <div id="error-kabupaten-jamaah"></div>
                            </div>
                            <div class="form-group">
                                <label>Asal Kecamatan <b class="text-danger">*</b></label>
                                <select name="kode_kecamatan" id="kode_kecamatan_jamaah" class="form-control select2" style="min-width: 100%; width: 100%;" data-parsley-errors-container="#error-kecamatan-jamaah" required>
                                    <option selected disabled>Pilih Kecamatan</option>
                                </select>
                                <div id="error-kecamatan-jamaah"></div>
                            </div>
                            <div class="form-group">
                                <label>Alamat <b class="text-danger">*</b></label>
                                <textarea name="alamat" id="alamat" class="form-control" rows="4" required></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <div id="id-jamaah"></div>
                    <input type="hidden" name="id_masjid">
                    <input type="hidden" name="id_user">
                    <button class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times"></i> Tutup</button>
                    <button class="btn text-white" style="background-color: #008080;" id="simpan" type="submit"><i class="fas fa-save"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>