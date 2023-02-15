<div class="container-fluid mt-2">
  <div class="d-sm-flex align-items-center justify-content-between mg-b-0 mg-lg-b-0 mg-xl-b-0">
    <div>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb breadcrumb-style1 mg-b-10">
          <li class="breadcrumb-item active"><a href="<?= site_url() ?>">Dashboard</a></li>
          <li class="breadcrumb-item active"><a href="#"><?= $title ?></a></li>
        </ol>
      </nav>
      <h4 class="mg-b-0 tx-spacing-1"><?= $title ?></h4>
    </div>
  </div>
  <hr>


  <div class="row mb-4 pb-4">
    <div class="mb-4 w-100">
      <button class="btn btn-sm btn-secondary" id="expand">
        <i class="fa fa-fw fa-expand-arrows-alt"></i>
        <span>Expand All</span>
      </button>
      <button class="btn btn-sm btn-secondary" id="collapse">
        <i class="fa fa-fw fa-compress-arrows-alt"></i>
        <span>Collapse All</span>
      </button>
      <button class="btn btn-sm btn-primary" id="add" data-toggle="modal" data-target="#modal-tambah">
        <i class="fa fa-fw fa-plus"></i>
        <span>Add Menu</span>
      </button>
    </div>


    <div class="dd w-100">
      <ol class="dd-list w-100">
        <?php
        foreach ($sidemenus as $sn => $sidemenu) :
          if (!isset($sidemenu['children'])) {
            $this->load->view("setting/menu-management/components/item", $sidemenu);
          } else {
            if ($sidemenu['level'] == 1) {
              $this->load->view("setting/menu-management/components/item", $sidemenu);
              if (isset($sidemenu['children'])) {
                foreach ($sidemenu['children'] as $j => $child) {
                  $this->load->view("setting/menu-management/components/list", $child);
                }
              } else {
                $this->load->view("setting/menu-management/components/item", $sidemenu);
              }
            } else if ($sidemenu['level'] == 2) {
              $this->load->view("setting/menu-management/components/list", $sidemenu);
            }
          }

        endforeach;
        ?>
      </ol>
    </div>

  </div><!-- row -->
</div><!-- container -->


<!-- Modal -->
<div class="modal fade" id="modal-tambah" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add Menu</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form>
          <div class="form-group">
            <label for="add-menu-name">Nama Menu</label>
            <input type="text" class="form-control" id="add-menu-name" placeholder="Masukkan nama menu...">
          </div>
          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="icon">Icon</label>
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text input-group-addon" id="basic-addon1">@</span>
                </div>
                <input id="icon" type="text" class="form-control icp fa-icon-picker" value="fas fa-heart">
              </div>


            </div>
            <div class="form-group col-md-6">
              <label for="inputState">Level</label>
              <select id="inputState" class="form-control">
                <option value="1" selected>Text Label/ Level 1</option>
                <option value="2">Level 2</option>
                <option value="3">Level 3</option>
              </select>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>

<?php $this->load->view('template/template_scripts') ?>