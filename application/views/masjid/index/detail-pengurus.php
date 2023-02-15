<div class="row">
    <div class="col-md-6">
        <div class="form-inline">
            <div class="input-group">
                <select name="jabatan" id="jabatan" class="form-control">
                    <option value="">Semua Jabatan</option>
                    <option value="Ketua">Ketua</option>
                    <option value="Bendahara">Bendahara</option>
                    <option value="Sekretaris">Sekretaris</option>
                    <option value="Anggota">Anggota</option>
                </select>
                <input type="text" name="txt_cari" class="form-control" placeholder="Type here...">
                <span class="input-group-append">
                    <button class="btn btn-outline-success" id="btn-cari"><i class="fa fa-search"></i></button>
                </span>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <!-- <button class="btn btn-success float-right" id="btn-tambah-pengurus"><i class="fas fa-plus"></i> Tambah Pengurus</button> -->
    </div>
</div>
<div class="table-responsive mt-3">
    <table class="table table-striped table-hover table-bordered table-sm" id="tabel-pengurus">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama lengkap</th>
                <th>Jenis Kelamin</th>
                <th>Jabatan</th>
                <th>Nomor HP</th>
                <th>Email</th>
                <th>Asal Provinsi</th>
                <th>Asal Kabupaten</th>
                <th>Asal Kecamatan</th>
                <!-- <th>Kelola</th> -->
            </tr>
        </thead>
    </table>
</div>

<div class="modal fade" id="modal-pengurus">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        <form action="" id="form-pengurus" enctype="multipart/form-data" data-parsley-validate>
                <div class="modal-header">
                    <b class="modal-title">Form Modal</b>
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Lengkap <b class="text-danger">*</b></label>
                        <input type="text" name="nama" class="form-control" placeholder="Nama Lengkap" minlength="3" maxlength="100" required>
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
                        <div class="fancy-radio">
                            <label><input name="jenis_kelamin" value="Laki-Laki" required type="radio" data-parsley-errors-container="#jenis_kelamin-error"><span><i></i>Laki-laki</span></label>
                        </div>
                        <div class="fancy-radio">
                            <label><input name="jenis_kelamin" value="Perempuan" required type="radio" data-parsley-errors-container="#jenis_kelamin-error"><span><i></i>Perempuan</span></label>
                            <div id="jenis_kelamin-error"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Nomor HP <b class="text-danger">*</b></label>
                        <input type="text" name="no_hp" onkeypress="return onlyNumber(event)" minlength="10" maxlength="13" class="form-control" required placeholder="08..">
                    </div>
                    <div class="form-group">
                        <label>Email <b class="text-danger">*</b></label>
                        <input type="email" name="email" maxlength="100" class="form-control" required placeholder="Email">
                    </div>

                    <div class="form-group">
                        <label>Tempat Lahir <b class="text-danger">*</b></label>
                        <input type="text" name="tempat_lahir" minlength="3" maxlength="100" class="form-control" required placeholder="Tempat Lahir">
                    </div>
                    <div class="form-group">
                        <label>Tanggal Lahir <b class="text-danger">*</b></label>
                        <input data-provide="datepicker" name="tanggal_lahir" required data-date-autoclose="true" class="form-control" data-date-format="yyyy-mm-dd" placeholder="Pilih Tanggal Lahir">
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
                        <label>Asal Kecamatan <b class="text-danger">*</b></label>
                        <select name="kode_kecamatan" id="kode_kecamatan" class="form-control select2" style="min-width: 100%; width: 100%;" required data-parsley-errors-container="#kode_kecamatan-error">
                            <option selected disabled value="">Pilih Kabupaten Terlebih Dahulu</option>
                        </select>
                        <div id="kode_kecamatan-error"></div>
                    </div>
                    <div class="form-group">
                        <label>Alamat <b class="text-danger">*</b></label>
                        <textarea name="alamat" id="alamat" class="form-control" rows="4" placeholder="Alamat" required></textarea>
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
                <div class="modal-footer d-flex justify-content-center">
                    <div id="id-pengurus"></div>
                    <input type="hidden" name="id_masjid">
                    <input type="hidden" name="id_user">
                    <button class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times"></i> Tutup</button>
                    <button class="btn btn-success" type="submit"><i class="fas fa-save"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>