<div class="container-fluid mt-2">
  <div class="d-sm-flex align-items-center justify-content-between mg-b-0 mg-lg-b-0 mg-xl-b-0">
    <div>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb breadcrumb-style1 mg-b-10">
          <li class="breadcrumb-item active"><a href="<?=site_url()?>">Dashboard</a></li>
        </ol>
      </nav>
      <h4 class="mg-b-0 tx-spacing-1"><?=$title?></h4>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12 col-sm-12">
      <?php if($this->session->flashdata('true')):?>
        <div class="alert alert-success alert-dismissible fade show" role="alert"> 
          <?= $this->session->flashdata('true');?>
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
      <?php elseif ($this->session->flashdata('false')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert"> 
          <?= $this->session->flashdata('false');?>
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
      <?php endif; ?>
    </div>
  </div>
  <div class="greeting-card">
    <h4 class="mg-b-0 tx-spacing--1">Selamat Datang <br><?php echo $this->session->userdata('esip_nama') ?></h4>
    <small>Semoga harimu menyenangkan di tempat kerja</small>
  </div>   
  <div class="card">
    <div class="card-header">
      <b class="card-title">Data Profil</b>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-sm table-striped table-hover">
          <tr>
            <th>NIK</th>
            <th>:</th>
            <td><?php echo $data['nik'] ?></td>
          </tr>
          <tr>
            <th>Nama Lengkap</th>
            <th>:</th>
            <td><?php echo $data['nama'] ?></td>
          </tr>
          <tr>
            <th>Pendidikan Terakhir</th>
            <th>:</th>
            <td><?php echo $data['jenjang'] ?></td>
          </tr>
          <tr>
            <th>Email</th>
            <th>:</th>
            <td><?php echo $data['email'] ?></td>
          </tr>
          <tr>
            <th>Tempat, Tanggal lahir</th>
            <th>:</th>
            <td><?php echo $data['tempat_lahir'].', '.$this->tanggalindo->konversi($data['tanggal_lahir']) ?></td>
          </tr>
          <tr>
            <th>Jenis Kelamin</th>
            <th>:</th>
            <td><?php echo $data['jenis_kelamin'] ?></td>
          </tr>
          <tr>
            <th>Alamat Lengkap</th>
            <th>:</th>
            <td><?php echo $data['asal'].'<br><b>Kecamatan : </b>'.$data['kecamatan'].'<br><b>Kelurahan : </b>'.$data['desa'].'<br><b>Alamat Detail : </b>'.$data['alamat'] ?></td>
          </tr>
          <tr>
            <th>Asal Instansi</th>
            <th>:</th>
            <td><?php echo $data['instansi'] ?></td>
          </tr>
          <tr>
            <th>Profil</th>
            <th>:</th>
            <td><a href="<?php echo site_url('index/ubah?key='.$data['id_member']) ?>" class="btn btn-warning text-white btn-sm"><i class="fas fa-edit"></i> Ubah Profil</a></td>
          </tr>
          <tr>
            <th>Pendamping</th>
            <th>:</th>
            <td>
              <?php if ($data['id_pendamping']==null): ?>
                <a href="<?php echo site_url('index/pendamping') ?>" class="btn btn-warning text-white btn-sm"><i class="fas fa-user"></i> Pilih Pendamping</a>
              <?php else: ?>
              <b>NIM : </b><?php echo $pendamping['nim'] ?><br>
              <b>Nama : </b><?php echo $pendamping['nama_mhs'] ?>
              <?php
                if ($data['is_pend_accepted']=='0') {
                  echo '<span class="badge bg-warning text-white">Menunggu persetujuan pendamping</span>';
                }else if ($data['is_pend_accepted']=='1') {
                  echo '<span class="badge bg-success text-white">Diterima</span>';
                }else if ($data['is_pend_accepted']=='2') {
                  echo '<span class="badge bg-danger text-white">Ditolak</span>';
                  echo '<br><a href="'.site_url('index/pendamping').'" class="btn btn-info btn-sm"><i class="fas fa-user"></i> Pilih Pendamping</a>';
                }
               ?>
              <?php endif ?>
            </td>
          </tr>

        </table>
      </div>
      <hr>
      <h4>Daftar Pelatihan Yang diikuti</h4>
      <div class="row">
        <div class="col">
          <a href="<?php echo site_url('pelatihan/index') ?>" class="btn btn-success btn-sm mb-3 float-right"><i class="fas fa-plus"></i> Tambah Data</a>
        </div>
      </div>
      <div id="container">
        <?php echo $output->output; ?>
      </div>
    </div>
  </div>
</div>
<!-- container -->
<!-- append theme customizer -->
<?php $this->load->view('template/template_scripts') ?>
<?php
if (isset($output->js_files)) {
    foreach ($output->js_files as $file) {
        echo '<script src="' . $file . '"></script>';
    }
}
?>

</body>
</html>