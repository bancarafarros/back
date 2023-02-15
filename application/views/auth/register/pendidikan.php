<div class="text-left">
  <div class="stepper-title-area text-center">
    <h2 class="fs-title text-center">Data Pendidikan</h2>
    <p class="">Informasi data pendidikan</p>
  </div>
  <div>
  <div class="form-group">
      <label for="provinsi_universitas">Provinsi Perguruan Tinggi <small class="text-danger">*</small></label>
      <select name="provinsi_universitas" class="form-control select2" style="width:100%;" required>
        <?=$propinsi?>
      </select>
    </div>
    <div class="form-group">
      <label for="asal_universitas">Asal Perguruan Tinggi <small class="text-danger">*</small></label>
      <select name="asal_universitas" id="asal_universitas" class="form-control custom-select select2" style="width:100%;" required>
        <option value="">Pilih Perguruan Tinggi</option>
      </select>
    </div>
    <div class="form-group">
      <label for="nim">Nomor Induk Mahasiswa (NIM) <small class="text-danger">*</small></label>
      <input type="text" name="nim" class="form-control" id="nim" placeholder="Nomor Induk Mahasiswa (NIM) Anda" required>
    </div>
    <div class="form-group">
      <label for="tahun_angkatan">Tahun Angkatan <small class="text-danger">*</small></label>
      <input type="number" name="tahun_angkatan" class="form-control" id="tahun_angkatan" placeholder="Tahun Angkatan" required>
    </div>
    <div class="form-group">
      <label for="id_jenjang_pendidikan">Jenjang Studi <small class="text-danger">*</small></label>
      <select class="custom-select form-control select2" id="id_jenjang_pendidikan" placeholder="Jenjang Pendidikan" name="id_jenjang_pendidikan" required>
      <?=$jenjang?>
    </select>
    </div>
    <div class="form-group">
      <label for="prodi">Program Studi <small class="text-danger">*</small></label>
      <input type="text" class="form-control" id="prodi" placeholder="Program Studi" name="prodi" required>
    </div>
  </div>
  <div class="form-group text-center">
    <button type="button" class="btn bg-light-red rounded-lg py-2 px-4 text-light prev">Sebelumnya</button>
    <button type="button" class="btn bg-light-red rounded-lg py-2 px-4 text-light" onclick="validation_step2()">Selanjutnya</button>
  </div>
</div>