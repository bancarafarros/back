<!-- <form id="form-tabungan">

    <div class="form-group row">
        <b for="inputNama" class="col-sm-3 col-form-label">Nama Jamaah</b>
        <div class="col-sm-9">
            <input type="text" readonly class="form-control" id="id_jamaah" name="id_jamaah" value="<?php echo $data['nama'] ?>">
        </div>
    </div>
    
    <div class="form-group row">
        <b for="InputMasjid" class="col-sm-3 col-form-label">Asal Masjid <small class="text-danger">*</small></b>
        <div class="col-sm-9">
            <select name="id_masjid" id="id_masjid" class="custom-select form-control" style="width:100%;" required>
                <?=$pilihmasjid?>
            </select>
        </div> 
    </div>

    <div class="form-group row">
        <b for="inputJumlah" class="col-sm-3 col-form-label">Nominal yang akan ditabung <small class="text-danger">*</small></b>
        <div class="col-sm-9">
            <input type="text" class="form-control" id="jumlah" name="jumlah" value="" required>
        </div>
    </div>

    <button type="submit" class="btn btn-primary">Simpan</button>

</form> -->

<?php $this->load->view('template/template-scripts') ?>
<script type="text/javascript">

$(function() {
    $('#form-tabungan').submit(function(e) {
        e.preventDefault();
        $.ajax({
            type: 'ajax',
            method: 'POST',
            url: site_url + '/jamaah/data/createTabungan',
            data: $('#form-tabungan').serialize(),
            dataType: 'json',
            success: function(response) {
                console.log(response);
                if (response.success) {
                    Swal.fire('Proses Berhasil!', response.message, 'success').then(function() {
                        window.location.reload();
                    })
                } else {
                    Swal.fire('Proses Gagal!', response.message, 'error');
                }
            },
            error: function(xmlresponse) {
                console.log(xmlresponse);
            }
        });
    })
});

</script>