<script src="<?php echo base_url("public/lib/jquery/jquery.min.js") ?>"></script>

    <div class="col-md-12">
        <div class="card social theme-bg gradient">
            <div class="profile-header d-sm-flex justify-content-between justify-content-center">
                <div class="d-flex">
                    <div class="mr-3">
                        <?php if ($data['url_foto'] != null) : ?>
                            <img style="height: 100px; width: 100px;" src="<?php echo base_url('public/uploads/pengurus/' . $data['url_foto']) ?>" class="rounded-circle" alt="">
                        <?php else : ?>
                            <img style="height: 85px; width: 95px;" src="<?= base_url('public/back/images/user.png') ?>" class="rounded-circle" alt="">
                        <?php endif; ?>
                    </div>
                    <div class="detail">
                        <h5 class="mb-0 text-white"><?php echo ucwords($data['nama']) ?></h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="alert alert-success bg-success text-white rounded-xl" role="alert">
            <b>DETAIL PENGURUS</b> 
        </div>
        <table class="table table-sm borderless table-hover">
            <tbody>
                <tr>
                    <th width="200px">Nama Pengurus</th>
                    <th>:</th>
                    <td><?php echo $data['nama'] ?></td>
                </tr>
                <tr>
                    <th>Jenis Kelamin</th>
                    <th>:</th>
                    <td><?php echo $data['jenis_kelamin'] ?></td>
                </tr>
                <tr>
                    <th>Tempat, Tanggal Lahir</th>
                    <th>:</th>
                    <td><?php echo ($data['tempat_lahir'] != null ? $data['tempat_lahir'] : '- ') . ', ' . ($data['tanggal_lahir'] != '0000-00-00' ? $this->tanggalindo->konversi($data['tanggal_lahir']) : '') ?></td>
                </tr>
                <tr>
                    <th>Alamat Lengkap</th>
                    <th>:</th>
                    <td><?php echo $data['asal'] ?></td>
                </tr>
                <tr>
                    <th>Kontak</th>
                    <th>:</th>
                    <td><?php echo $data['no_hp'] ?></td>
                </tr>
            </tbody>
        </table>
    </div>


<!-- container -->
<!-- append theme customizer -->
<?php $this->load->view('template/template-scripts') ?>
<script>
    $(function() {
        initSync();
        $('.select2').select2();
    })
</script>

</body>

</html>