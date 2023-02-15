<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Mitra extends BaseController
{

  public $loginBehavior = true;
  //protected $module = "mitra";

  public function __construct()
  {
    parent::__construct();
    $this->load->library('form_validation');
    $this->load->library('arkatama');
    $this->load->model('M_wilayah', 'wilayah');
    $this->load->model(['M_master']);
  }

  public function index()
  {
    $this->data['title'] = 'Data Mitra Client';
    $crud = new Grid();
    $crud->setSkin('bootstrap-v4');
    $crud->unsetJquery();
    $crud->setSubject('Mitra');

    $crud->unsetAdd();
    $crud->unsetEdit();
    $crud->unsetDelete();

    $crud->setTable('ref_mitra');
    $crud->columns(['nama','singkatan', 'logo', 'is_active']);

    $crud->displayAs([
      'nama'            => 'Nama Institusi',
      'singkatan'       => 'Nama Pendek',
      'logo'            => 'Logo',
      'is_active'       => 'Status',
    ]);
    $crud->requiredFields(['nama','singkatan', 'logo']);

    $crud->addFields(['nama','singkatan', 'logo']);
    $crud->editFields(['nama','singkatan', 'logo', 'is_active']);

    $crud->fieldType('is_active', 'dropdown_search', [
      '1'=> 'Aktif',
      '0'=> 'Tidak Aktif'
    ]);
    //callback
    $crud->callbackColumn('logo', ([$this, '_callbackLogo']));
    // $crud->callbackInsert([$this, '_callbackInsert']);
    // $crud->callbackUpdate([$this, '_callbackUpdate']);
    // $crud->callbackDelete([$this, '_callbackDelete']);

    $output = $crud->render();
    $this->setOutput($output, 'index');
  }

  function _callbackLogo($value, $row)
  {
    $data = $this->M_master->getMitra($row->id);
    if(!empty($data)){
      $path = 'public/assets/img/intern/mitra/';
      return '<img src="'.base_url().$path.$data['logo'].'" width="50px">';
    }
  }
}