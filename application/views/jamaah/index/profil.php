<table class="table table-striped table-sm">
<tr>
    <th width="200px">Nama Jamaah</th>
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
</table>