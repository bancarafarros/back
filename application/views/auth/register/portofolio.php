<div class="text-left">
  <div class="stepper-title-area text-center">
    <h2 class="fs-title text-center">Data Portofolio dan Media Sosial</h2>
    <p class="">Informasi data portofolio dan media sosial</p>
  </div>
  <div>
    <div class="form-group">
      <label for="url_portofolio">Link Portofolio <small class="text-danger">*</small></label>
      <input type="text" name="url_portofolio" class="form-control" id="url_portofolio" placeholder="Link portofolio" required>
    </div>
    <legend class="badge bg-danger text-white">Media Sosial</legend>
    <small class="mb-5">Media sosial bersifat opsional. Misal : <b class="badge bg-success text-white">https://www.instagram.com/username</b></small><hr>
    <?php echo $sosmed; ?>
  </div>
  <div class="form-group text-center">
    <button type="button" class="btn bg-light-red rounded-lg py-2 px-4 text-light prev">Sebelumnya</button>
    <button type="button" class="btn bg-light-red rounded-lg py-2 px-4 text-light" onclick="validation_step3()">Selanjutnya</button>
  </div>
</div>