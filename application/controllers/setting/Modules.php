<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Modules extends BaseController
{

  public $loginBehavior = true;
  public function __construct()
  {
    parent::__construct();
  }

  public function index()
  {
    $this->data['title'] = 'System Modules';
    $crud = new Grid();
    $crud->setSkin('bootstrap-v4');
    $crud->unsetJquery();
    $crud->unsetPrint();
    $crud->unsetFilters();
    $crud->unsetExport();
    $crud->setSubject('System Modules');

    $crud->setTable('modul_sistem');

    $crud->columns([
      'nama_modul',
      'keterangan',
      'is_active',
    ]);

    $crud->displayAs([
      'nama_modul' => 'Nama Modul',
      'keterangan' => 'Keterangan',
      'is_active' => 'Aktif'
    ]);

    $crud->addFields([
      'nama_modul',
      'keterangan',
      'is_active',
    ]);

    $crud->editFields([
      'nama_modul',
      'keterangan',
      'is_active',
    ]);

    $crud->requiredFields([
      'nama_modul',
    ]);

    $crud->fieldType('is_active', 'dropdown', [
      '0' => 'Tidak Aktif',
      '1' => 'Aktif'
    ]);

    $crud->fieldType('id_modul', 'hidden');

    $output = $crud->render();
    $this->setOutput($output, 'index');
  }


}
