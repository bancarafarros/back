<div class="container-fluid mb-4">
  <div class="d-sm-flex align-items-center justify-content-between mg-b-0 mg-lg-b-0 mg-xl-b-0 mb-2">
    <div>
      <h4 class="mg-b-0 tx-spacing-1"><?= $title ?></h4>
    </div>
  </div>

  <div class="card border-0 bg-white-9 rounded-xl p-0 mb-3">
    <div class="card-body">
      <?php echo $output->output; ?>
    </div>
  </div>
  <br>
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


<script>
$(document).on('click','.status_checks',function()
 { 

var is_active = ($(this).hasClass("btn-success")) ? '1' : '0'; 
// var msg = (status=='0')? 'Tidak Aktif' : 'Aktif'; 
if(confirm("Are you sure to "+ msg))
{ 
    var current_element = $(this); 
    var id = $(current_element).attr('data');
    url = "<?php echo base_url().'persyaratan/data/aktif'?>"; 
        $.ajax({
          type:"POST",
          url: url, 
          data: {"id":id,"is_active":is_active}, 
          success: function(data) { 
          // if you want reload the page
          // location.reload();
          //if you want without reload
          if(is_active == '1'){
            current_element.removeClass('btn-success');
            current_element.addClass('btn-danger');
            current_element.html('Tidak Aktif');
          }else{
            current_element.removeClass('btn-danger');
            current_element.addClass('btn-success');
            current_element.html('Aktif');
          }
    } });
 }  
 });
</script>