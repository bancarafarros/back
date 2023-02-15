<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Faq extends BaseController
{
    public $loginBehavior = true;
    protected $template = "app";

    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_master', 'master');
        $this->load->model('M_faq', 'faq');
    }

    public function index()
    {
        $this->data['title']    = 'Data FAQ';

        $crud = new Grid();
        $crud->setSkin('bootstrap-v4');
        $crud->unsetJquery();
        $crud->unsetFilters();
        $crud->unsetPrint();
        $crud->unsetExport();
        $crud->unsetDelete();
        $crud->setSubject('FAQ');
        $crud->setTable('faq');

        $crud->columns(['faq', 'answer', 'order', 'is_active',]);
        $crud->setTexteditor(['answer', 'full_text']);

        $crud->displayAs([
            'faq'       => 'FAQ',
            'answer'    => 'Jawaban',
            'order'     => 'Urutan',
            'is_active' => 'Aktif'
        ]);
        $crud->addFields([
            'faq',
            'answer',
            'order'
        ]);
        $crud->editFields([
            'faq',
            'answer',
            'order',
            'is_active'
        ]);
        $crud->requiredFields([
            'faq',
            'answer',
            'order'
        ]);
        $crud->fieldType('order', 'int');
        $crud->fieldType('is_active', 'dropdown', [
            '0' => 'Tidak Aktif',
            '1' => 'Aktif'
        ]);

        $crud->setActionButton('Detail', 'fas fa-search', function ($row) {
            return site_url('faq/detail?key=' . $row['id']);
        }, false);

        $crud->setActionButton('Hapus', 'fa fa-trash', function ($row) {
            return 'javascript:hapus(' . "'" . $row['id'] . "'" . ')';
        }, false);

        $output = $crud->render();
        $this->setOutput($output, 'index');
        $this->render('index');
    }

    public function detail()
    {
        $row = $this->input->get('key');

        $this->data['title']    = 'Detail FAQ';
        $data = $this->master->detailFaq($row);

        if (empty($data)) {
            redirect('faq');
        }
        $this->data['data']     = $data;
        $this->data['id']       = $row;
        $this->render('detail');
    }

    public function remove()
    {
        $input = $this->input->post();

        $result = $this->faq->hapus($input['id']);

        if($result['status'] == 200){
            return successResponseJson('Proses berhasil');
        }else{
            return internalServerErrorResponseJson('Hapus gagal');
        }
    }
}