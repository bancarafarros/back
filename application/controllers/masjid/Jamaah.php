<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Jamaah extends BaseController
{
    public $loginBehavior = true;
    protected $module = "masjid";
    protected $template = "app";

    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('M_jamaah', 'jamaah');
    }

    public function tambah()
    {
        $params = $this->input->post(null, true);

        $current_date = date('Y-m-d');
        if ($params['tanggal_lahir'] >= $current_date) {
            responseJson(500, ['status' => 500, 'message' => 'Tanggal lahir tidak boleh lebih dari hari ini.']);
        } else {
            $data       = filterFieldsOfTable('jamaah', $params);
            $data['id'] = getUUID();
            $data['created_by'] = getSessionID();

            $result = $this->jamaah->tambah($data);

            responseJson($result['status'], $result);
        }
    }

    public function DataTableJamaah()
    {
        $return = array();

        $field = array(
            'sSearch',
            'iSortCol_0',
            'sSortDir_0',
            'iDisplayStart',
            'iDisplayLength',
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
            'id_masjid'         => $id_masjid,
        );

        $data = $this->jamaah->DataTableJamaah($params);
        if ($data['total'] > 0) {
            $return['iTotalRecords'] = $data['total'];
            $return['iTotalDisplayRecords'] = $return['iTotalRecords'];

            foreach ($data['rows'] as $k => $row) {

                $row['nomor'] = '<p class="text-center">' . ($iDisplayStart + ($k + 1)) . '</p>';
                $row['nama_lengkap'] = $row['nama'];
                // $row['jenis_kelamin'] = $row['jenis_kelamin'];
                $row['nomor_hp'] = $row['no_hp'];
                $row['email'] = $row['email'];
                // $row['provinsi'] = ucwords(strtolower($row['provinsi']));
                // $row['kabupaten'] = ucwords(strtolower($row['kabupaten']));
                // $row['kecamatan'] = ucwords(strtolower($row['kecamatan']));
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

        $result = $this->jamaah->edit($params['kode']);

        responseJson($result['status'], $result);
    }

    public function update()
    {
        $params = $this->input->post(null, true);

        $data = filterFieldsOfTable('jamaah', $params);
        unset($data['id']);

        $result = $this->jamaah->update($data, $params['id']);

        responseJson($result['status'], $result);
    }
    public function hapus()
    {
        $params = $this->input->post(null, true);

        $result = $this->jamaah->hapus($params['kode']);

        responseJson($result['status'], $result);
    }
}
