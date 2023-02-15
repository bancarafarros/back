<div class="container-fluid">
  <div class="row justify-content-center mt-0">
    <div class="col-11 col-sm-10 col-md-10 col-lg-8 text-center p-0 mt-3 mb-2">
      <div class="card border-0 px-0 pt-0 pb-0 mt-3 mb-3">
        <h3>Form Peserta Magang / Internship</h3>
        <span>Isian bertanda <small class="text-danger">*</small> wajib diisi.</span>
        <div id="smartwizard">
          <ul class="nav">
            <li>
              <a class="nav-link" href="#step-1">
                1
              </a>
            </li>
            <li>
              <a class="nav-link" href="#step-2">
                2
              </a>
            </li>
            <li>
              <a class="nav-link" href="#step-3">
                3
              </a>
            </li>
            <li>
              <a class="nav-link" href="#step-4">
                4
              </a>
            </li>
            <!-- <li>
              <a class="nav-link" href="#step-5">
                5
              </a>
            </li> -->
          </ul>
          <form id="form-registrasi" enctype="multipart/form-data" novalidate>
            <div class="tab-content text-left">
              <div id="step-1" class="tab-pane" role="tabpanel">
                <?php $this->load->view('auth/register/biodata'); ?>
              </div>
              <div id="step-2" class="tab-pane" role="tabpanel">
                <?php $this->load->view('auth/register/pendidikan');?>
              </div>
              <div id="step-3" class="tab-pane" role="tabpanel">
                <?php $this->load->view('auth/register/portofolio');?>
              </div>
              <div id="step-4" class="tab-pane" role="tabpanel">
                <?php $this->load->view('auth/register/magang');?>
              </div>
              <!-- <div id="step-5" class="tab-pane" role="tabpanel">
                <?php $this->load->view('auth/register/akun');?>
              </div> -->
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<?php 
$this->load->view('template/template_scripts.php');
?>
<!-- <script src="https://cdn.jsdelivr.net/npm/smartwizard@5/dist/js/jquery.smartWizard.min.js"></script>  -->
<script type="text/javascript">
  //var site_url = "<?=site_url()?>";

  
  $('[name=kode_provinsi]').change(function() {
    var kode = $(this).val();
    $('[name=kode_kabupaten]').val("").trigger('change');
    $.ajax({
      type : 'ajax',
      method : 'POST',
      url : site_url+'/auth/getkabupaten',
      data : {kode_prop : kode},
      dataType : 'json',
      success: function(response) {
        $('[name=kode_kabupaten]').html(response.data);
      },
      error: function(xmlresponse) {
        console.log(xmlresponse);
      }
    })
  })

  $('[name=kode_kabupaten]').change(function() {
    var kode = $(this).val();
    $('[name=kode_kecamatan]').val('').trigger('change');
    $.ajax({
      type : 'ajax',
      method : 'POST',
      url : site_url+'/auth/getkecamatan',
      data : {kode_kab : kode},
      dataType : 'json',
      success: function(response) {
        $('[name=kode_kecamatan]').html(response.data);
      },
      error: function(xmlresponse) {
        console.log(xmlresponse);
      }
    })
  })

  $('[name=provinsi_universitas]').change(function() {
    var kode = $(this).val();
    $('[name=asal_universitas]').val("").trigger('change');
    $.ajax({
      type : 'ajax',
      method : 'POST',
      url : site_url+'/auth/getInstansi',
      data : {kode_provinsi : kode},
      dataType : 'json',
      success: function(response) {
        $('[name=asal_universitas]').html(response.data);
      },
      error: function(xmlresponse) {
        console.log(xmlresponse);
      }
    })
  })

    function isEmail(email) {
      var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
      return regex.test(email);
    }

    function validation_step1(){
      var nama_lengkap = $("#nama_lengkap").val();
      var tempat_lahir = $("#tempat_lahir").val();
      var tanggal_lahir = $("#tanggal_lahir").val();
      var jenis_kelamin = $("#jenis_kelamin").val();
      var email = $("#email").val();
      var nomor_hp = $("#nomor_hp").val();
      var kode_provinsi = $("#kode_provinsi").val();
      var kode_kabupaten = $("#kode_kabupaten").val();
      var kode_kecamatan = $("#kode_kecamatan").val();
      var alamat = $("#alamat").val();
      // var file_image = $("#file_image").val();

      const isFilled = (nama_lengkap != "" && tempat_lahir != "" && tanggal_lahir != "" && email != "" && nomor_hp != "" && kode_provinsi != "" && kode_kabupaten != "" && kode_kecamatan != "" && alamat != "");
      if(isFilled){
        if(!isEmail(email) || (email == null || email == '')){
          Swal.fire({
            confirmButtonColor: '#3ab50d',
            icon: 'error',
            title: 'Peringatan',
            text: 'Format email salah',
          })
        }else{
          $('#smartwizard').smartWizard("next");
        }
        // $('#smartwizard').smartWizard("next");
      }else {
        Swal.fire({
          confirmButtonColor: '#3ab50d',
          icon: 'error',
          title: 'Peringatan',
          text: 'Isian bertanda bintang wajib diisi',
        })
        
      }
    }

    function validation_step2(){
      var asal_universitas = $("#asal_universitas").val();
      var nim = $("#nim").val();
      var tahun_angkatan = $("#tahun_angkatan").val();
      var id_jenjang_pendidikan = $("#id_jenjang_pendidikan").val();
      var prodi = $("#prodi").val();

      const isFilled = (asal_universitas != "" && nim != "" && tahun_angkatan != ""  && id_jenjang_pendidikan != ""  && prodi != "");
      if(isFilled){
        $('#smartwizard').smartWizard("next");
      }else{
        Swal.fire({
          confirmButtonColor: '#3ab50d',
          icon: 'error',
          title: 'Peringatan',
          text: 'Isian bertanda bintang wajib diisi',
        })
      }
    } 

    function validation_step3(){
      var url_portofolio = $("#url_portofolio").val();
      
      const isFilled = (url_portofolio != "");
      if(isFilled){
        $('#smartwizard').smartWizard("next");
      }else{
        Swal.fire({
          confirmButtonColor: '#3ab50d',
          icon: 'error',
          title: 'Peringatan',
          text: 'Isian bertanda bintang wajib diisi',
        })
      }
    }

    function validation_step4(){
      var id_posisi = $("#id_posisi").val();
      var durasi_magang = $("#durasi_magang").val();
      var tanggal_mulai = $("#tanggal_mulai").val();
      
      const isFilled = (id_posisi != "" && durasi_magang != "" && tanggal_mulai != "");
      if(isFilled){
        $('#smartwizard').smartWizard("next");
      }else{
        Swal.fire({
          confirmButtonColor: '#3ab50d',
          icon: 'error',
          title: 'Peringatan',
          text: 'Isian bertanda bintang wajib diisi',
        })
      }
    }
</script>