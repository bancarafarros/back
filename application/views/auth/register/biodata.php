<div class="form-card">
  <div class="stepper-title-area">
    <h2 class="fs-title text-center">Data Diri</h2>
    <p class="text-center">Informasi data diri</p>
  </div>
  <div class="form-group">
    <label for="periode">Periode Magang <small class="text-danger">*</small></label>
    <select name="periode_magang" id="periode_magang" class="custom-select select2 form-control" style="width:100%;" required>
      <?=$periode?>
    </select>
  </div>   
  <div class="form-group">
    <label for="nama_lengkap">Nama Lengkap <small class="text-danger">*</small></label>
    <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" placeholder="Nama lengkap Anda" autofocus required>
  </div>
  <div class="form-group">
    <label for="tempat_lahir">Tempat Lahir <small class="text-danger">*</small></label>
    <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir" placeholder="Tempat Lahir Anda" required>
  </div>
  <div class="form-group">
    <label for="tanggal_lahir">Tanggal Lahir <small class="text-danger">*</small></label>
    <input type="text" class="form-control tanggal" id="tanggal_lahir" name="tanggal_lahir" placeholder="Tanggal Lahir Anda" required>
  </div>
  <div class="form-group">
    <label for="jenis_kelamin">Jenis Kelamin <small class="text-danger">*</small></label>
    <select name="jenis_kelamin" id="jenis_kelamin" class="custom-select" required>
      <option value="">Pilih</option>
      <option value="Laki-Laki">Laki-laki</option>
      <option value="Perempuan">Perempuan</option>
    </select>
  </div>
  <div class="form-group">
    <label for="email">Email <small class="text-danger">*</small></label>
    <input type="email" class="form-control" id="email" name="email" placeholder="Email Anda" required>
  </div>
  <div class="form-group">
    <label for="nomor_hp">Nomor HP <small class="text-danger">*</small></label>
    <input type="number" class="form-control" id="nomor_hp" name="nomor_hp" placeholder="Nomor HP Anda" required>
  </div>
  <div class="form-group">
    <label for="kode_prov">Provinsi <small class="text-danger">*</small></label>
    <select name="kode_provinsi" id="kode_provinsi" class="custom-select select2 form-control" style="width:100%;" required>
      <?=$propinsi?>
    </select>
  </div>                                    
  <div class="form-group">
    <label for="kode_kab">Kabupaten <small class="text-danger">*</small></label>
    <select name="kode_kabupaten" id="kode_kabupaten" class="custom-select select2 form-control" style="width:100%;" required>
      <option value="">Pilih Kabupaten</option>
    </select>
  </div>
  <div class="form-group">
    <label for="kode_kec">Kecamatan <small class="text-danger">*</small></label>
    <select name="kode_kecamatan" id="kode_kecamatan" class="custom-select select2 form-control" style="width:100%;" required>
      <option value="">Pilih Kecamatan</option>
    </select>
  </div>
  <div class="form-group">
    <label for="alamat">Alamat Lengkap <small class="text-danger">*</small></label>
    <textarea name="alamat" id="alamat" rows="3" class="form-control" required></textarea>
  </div>
  <!-- <div class="form-group">
    <label>Foto Tampak Wajah <small class="text-danger">*</small></label>
    <div class="custom-file fileUpload" id="new-image">
      <input type="file" class="custom-file-input"  id="file_image" name="gambar" onchange="cekFile(this)" required>
      <label class="custom-file-label">Choose file</label>
      <div class="hint-block">
        Jenis file yang diijinkan: <strong>PNG, JPEG, JPG</strong>. Ukuran file maksimal: <strong>2 MB</strong>
      </div>
    </div>
  </div> -->
  <div class="form-group">
    <span class="file-name text-muted text-bold ml-5"></span>
    <img src="" class="file-target mt-10 mb-10" width="0" style="display:inherit"/>
    <span class="file-alert-type text-danger text-bold"></span>
    <span class="file-alert-size text-danger text-bold"></span>
  </div>
  <div class="form-group text-center">
    <button type="button" class="btn bg-light-red rounded-lg py-2 px-4 text-light btn-next-step1" onclick="validation_step1()">Selanjutnya</button>
  </div>
</div>