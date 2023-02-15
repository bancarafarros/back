<table class="table table-striped table-sm">
    <tr>
        <th>Nama Masjid/Mushola</th>
        <th>:</th>
        <td><?php echo $data['nama'] ?></td>
    </tr>
    <tr>
        <th>Nama Takmir</th>
        <th>:</th>
        <td><?php echo $data['nama_pj_takmir'] ?></td>
    </tr>
    <tr>
        <th>Jenis</th>
        <th>:</th>
        <td><?php echo $data['type'] ?></td>
    </tr>
    <tr>
        <th>Tahun Berdiri</th>
        <th>:</th>
        <td><?php echo $data['tahun_berdiri'] ?></td>
    </tr>
    <tr>
        <th>Organisasi Afiliasi</th>
        <th>:</th>
        <td><?php echo $data['afiliasi'] ?></td>
    </tr>
    <tr>
        <th>Typologi Bangunan</th>
        <th>:</th>
        <td><?php echo $data['typologi'] ?></td>
    </tr>
    <tr>
        <th>Provinsi</th>
        <th>:</th>
        <td><?php echo ucwords(strtolower($data['provinsi'])) ?></td>
    </tr>
    <tr>
        <th>Kabupaten</th>
        <th>:</th>
        <td><?php echo ucwords(strtolower($data['kabupaten'])) ?></td>
    </tr>
    <tr>
        <th>Kecamatan</th>
        <th>:</th>
        <td><?php echo ucwords(strtolower($data['kecamatan'])) ?></td>
    </tr>
    <tr>
        <th>Alamat Lengkap</th>
        <th>:</th>
        <td><?php echo $data['alamat'] ?></td>
    </tr>
    <tr>
        <th>Status Tanah</th>
        <th>:</th>
        <td><?php echo $data['status_tanah'] ?></td>
    </tr>
    <tr>
        <th>Luas Tanah</th>
        <th>:</th>
        <td><?php echo $data['luas_tanah'] ?> m<sup>2</sup></td>
    </tr>
    <tr>
        <th>Luas Bangunan</th>
        <th>:</th>
        <td><?php echo $data['luas_bangunan'] ?> m<sup>2</sup></td>
    </tr>
    <tr>
        <th>Kondisi Bangunan</th>
        <th>:</th>
        <td><?php echo $data['kondisi'] ?></td>
    </tr>
    <tr>
        <th>Latitude</th>
        <th>:</th>
        <td><input type="text" class="form-control" name="latitude" id="latitude" value="<?php echo $data['latitude'] ?>" readonly></td>
    </tr>
    <tr>
        <th>Longitude</th>
        <th>:</th>
        <td><input type="text" class="form-control" name="latitude" id="latitude" value="<?php echo $data['longitude'] ?>" readonly></td>
    </tr>
    <tr>
        <th>Lokasi Masjid</th>
        <th>:</th>
        <td>
        </td>
    </tr>
    <tr>
        <td colspan="3">
            <div id="map" style="width: 100%; height: 400px;"></div>
        </td>
    </tr>
</table>