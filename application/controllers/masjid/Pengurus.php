<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Pengurus extends BaseController
{
    public $loginBehavior = true;
    protected $module = "masjid";
    protected $template = "app";

    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('M_pengurus', 'pengurus');
    }

    public function tambah()
    {
        $params = $this->input->post(null, true);
        $image = uploadImage('url_foto', 'pengurus', 'pengurus');

        if($image['success'] == true){
            $params['url_foto'] = $image['file'];
        }

        $data = filterFieldsOfTable('masjid_pengurus', $params);
        $result = $this->pengurus->simpan($data);
        responseJson($result['status'], $result);
    }

    public function DataTablePengurus()
    {
        $return = array();

        $field = array(
            'sSearch',
            'iSortCol_0',
            'sSortDir_0',
            'iDisplayStart',
            'iDisplayLength',
            'jabatan',
            'id_masjid',
        );

        foreach ($field as $v) {
            $$v = $this->input->get_post($v);
        }

        $return = array(
            "sEcho" => $this->input->post('sEcho'),
            "iTotalRecords" => 0,
            "iTotalDisplayRecords" => 0,
            "aaData" => array()
        );

        $params = array(
            'sSearch'           => $sSearch,
            'start'             => $iDisplayStart,
            'limit'             => $iDisplayLength,
            'jabatan'           => $jabatan,
            'id_masjid'         => $id_masjid,
        );

        $data = $this->pengurus->DataTablePengurus($params);
        if ($data['total'] > 0) {
            $return['iTotalRecords'] = $data['total'];
            $return['iTotalDisplayRecords'] = $return['iTotalRecords'];

            foreach ($data['rows'] as $k => $row) {

                $row['nomor'] = '<p class="text-center">' . ($iDisplayStart + ($k + 1)) . '</p>';
                $row['nama_lengkap'] = $row['nama'];
                $row['jenis_kelamin'] = $row['jenis_kelamin'];
                $row['jabatan'] = $row['jabatan'];
                $row['nomor_hp'] = $row['no_hp'];
                $row['email'] = $row['email'];
                $row['provinsi'] = ucwords(strtolower($row['provinsi']));
                $row['kabupaten'] = ucwords(strtolower($row['kabupaten']));
                $row['kecamatan'] = ucwords(strtolower($row['kecamatan']));
                $row['kelola'] = '<div class="btn-group">
                <button class="btn btn-info btn-icon" data="' . $row['id'] . '" id="btn-edit"><i class="fas fa-edit"></i></button>
                <button class="btn btn-danger btn-icon" data="' . $row['id'] . '" id="btn-hapus"><i class="fas fa-trash"></i></button>
                </div>';

                $return['aaData'][] = $row;
            }
        }
        $this->db->flush_cache();
        responseJson(200, $return);
    }

    public function edit()
    {
        $params = $this->input->post(null, true);

        $result = $this->pengurus->edit($params['kode']);

        responseJson($result['status'], $result);
    }

    public function update()
    {
        $params = $this->input->post(null, true);
        $image = uploadImage('url_foto', 'pengurus', 'pengurus');

        if($image['success'] == true){
            $params['url_foto'] = $image['file'];
            if(!empty($params['url_fotoh'])){
                $foto_lama = './public/uploads/pengurus/' . $params['url_fotoh'];
                unlink($foto_lama);
            }
        }

        $data = filterFieldsOfTable('masjid_pengurus', $params);
        $data['last_modified'] = date('Y-m-d H:i:s');

        $result = $this->pengurus->update($data);

        // menghapus file jika gagal menambahkan data
        if(!empty($image['file_name'])){
            if($result['status'] == '500'){
                $file = '.' . $image['file_name'];
                unlink($file);
            }
        }

        responseJson($result['status'], $result);
    }
    public function hapus()
    {
        $params = $this->input->post(null, true);

        $result = $this->pengurus->hapus($params['kode']);

        responseJson($result['status'], $result);
    }
}
