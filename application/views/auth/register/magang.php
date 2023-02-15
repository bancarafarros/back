<div class="text-left">
  <div class="stepper-title-area text-center">
    <h2 class="fs-title text-center">Data Magang / Internship</h2>
    <p class="">Informasi data magang / internship</p>
  </div>
  <div class="form-group">
    <label for="id_posisi">Bidang Yang Diambil <small class="text-danger">*</small></label>
    <select class="custom-select form-control select2" id="id_posisi" placeholder="Bidang yang diambil" name="id_posisi" required>
      <?=$posisi?>
    </select>
  </div>

    <input type="hidden" class="form-control" id="durasi_magang" name="durasi_magang" required readonly>

    <input type="hidden" class="form-control" id="tanggal_mulai" name="tanggal_mulai" required readonly>
    
  <div class="form-group">
    <label for="harapan">Harapan Saat Magang</label>
    <textarea name="harapan" class="form-control" id="harapan" rows="3"></textarea>
  </div>
  <div class="form-group text-center">
    <button type="button" class="btn bg-light-red rounded-lg py-2 px-4 text-light prev">Sebelumnya</button>
    <button type="submit" id="button-daftar" class="btn bg-light-red rounded-lg py-2 px-4 text-light">Mendaftar</button>
    <!-- <button type="button" class="btn bg-light-red rounded-lg py-2 px-4 text-light" onclick="validation_step4()">Selanjutnya</button> -->
  </div>
</div>