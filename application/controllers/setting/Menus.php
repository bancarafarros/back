<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Menus extends BaseController
{

  public $loginBehavior = true;
  public function __construct()
  {
    parent::__construct();
    $this->load->library('form_validation');
  }

  public function index()
  {
    $this->data['title'] = 'Menu Management';

    $this->data['loadSortableJs'] = true;
    $this->data['loadFontAwesomePicker'] = true;
    $this->data['scripts'] = [
      'setting/menu-management/js/index.js',
    ];
    $this->renderTo('setting/menu-management/index');
  }
}
