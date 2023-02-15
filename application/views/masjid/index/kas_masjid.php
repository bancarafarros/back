<form id="form-kas-masjid">

    <div class="form-group row">
        <b for="inputNama" class="col-sm-3 col-form-label">Nama Masjid</b>
        <div class="col-sm-9">
            <input type="text" readonly class="form-control" id="id_masjid" name="id_masjid" value="<?php echo $data['nama'] ?>">
        </div>
    </div>

    <div class="form-group row">
        <b for="inputJumlah" class="col-sm-3 col-form-label">Nominal <small class="text-danger">*</small></b>
        <div class="col-sm-9">
            <input type="text" class="form-control" id="jumlah" name="jumlah" value="" required>
        </div>
    </div>

    <button type="submit" class="btn btn-primary">Simpan</button>

</form>

<?php $this->load->view('template/template_scripts') ?>
<script type="text/javascript">

$(function() {
    $('#form-kas-masjid').submit(function(e) {
        e.preventDefault();
        $.ajax({
            type: 'ajax',
            method: 'POST',
            url: site_url + '/masjid/data/createKasMasjid',
            data: $('#form-kas-masjid').serialize(),
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