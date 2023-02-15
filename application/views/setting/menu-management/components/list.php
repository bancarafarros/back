<li class="dd-item dd3-item" data-id="<?= $id ?>" data-nama="<?= $nama ?>" data-level="<?= $level ?>" data-url="<?= $url ?>" data-parent_id="<?= $parent_id ?>" data-order="<?= $order ?>" data-is_label="<?= $is_label ?>">
  <div class="dd-handle dd3-handle">Drag</div>
  <div class="dd3-content d-flex justify-content-between align-items-center">
    <div class="d-flex align-items-center">
      <i class="mr-2 fa-fw <?= $icon ?>"></i>
      <span>
        <?= $nama ?>
      </span>

      <?php if ($is_label == 1) : ?>
        <span class="ml-2 badge badge-pill badge-primary">Text Label</span>
      <?php endif; ?>
    </div>

    <div>
      <button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="top" title="Hapus Menu">
        <i class="fa fa-trash fa-fw"></i>
      </button>
      <button type="button" class="btn btn-xs btn-success" data-toggle="tooltip" data-placement="top" title="Edit Menu">
        <i class="fa fa-edit fa-fw"></i>
      </button>
      <?php if($level != 3): ?>
      <button type="button" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="top" title="Tambah Menu">
        <i class="fa fa-plus fa-fw"></i>
      </button>
      <?php endif;?>
    </div>
  </div>

  <?php if (isset($children)) : ?>
    <ol class="dd-list">
      <?php
      foreach ($children as $i => $child) {
        $this->load->view("setting/menu-management/components/item", $child);
      }
      ?>

    </ol>
  <?php endif; ?>


</li>