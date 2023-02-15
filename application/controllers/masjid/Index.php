<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Index extends BaseController
{
    public $loginBehavior = true;
    protected $module = "masjid";
    protected $template = "app";

    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('M_masjid', 'masjid');
        $this->load->model('M_pengurus', 'pengurus');
        $this->load->model('M_jamaah', 'jamaah');
    }

    public function index()
    {
        $this->data['title']    = 'Data Masjid';

        $crud = new Grid();
        $crud->setSkin('bootstrap-v4');
        $crud->unsetJquery();
        $crud->unsetAdd();
        $crud->unsetEdit();
        $crud->unsetFilters();
        $crud->unsetPrint();
        $crud->unsetExport();
        $crud->unsetDelete();
        $crud->setSubject('Masjid');
        $crud->setTable('masjid');
        $crud->setRelation('kode_provinsi', 'ref_provinsi', 'nama_provinsi');
        $crud->setRelation('kode_kabupaten', 'ref_kabupaten', 'nama_kab_kota');
        $crud->setRelation('kode_kecamatan', 'ref_kecamatan', 'nama_kecamatan');

        $listAfiliasi = $this->masjid->getListAfiliasi();

        $crud->columns(['nama', 'nama_pj_takmir', 'type', 'kode_provinsi', 'kode_kabupaten', 'kode_kecamatan', 'alamat', 'ref_id_afiliasi']);

        $crud->fieldType('ref_id_afiliasi', 'dropdown_search', $listAfiliasi);

        $crud->where([
            'is_verified' => '1'
        ]);

        $crud->defaultOrdering('created_at', 'desc');

        if (getSessionRole() == 2) {
            $id_masjid = $this->pengurus->getIdByIdUser(getSessionID())->id_masjid;
            $crud->where([
                'id' => $id_masjid
            ]);
        }

        $crud->setActionButton('Edit', 'fas fa-edit', function ($row) {
            return site_url('masjid/index/edit?key=' . $row['id']);
        }, false);

        $crud->setActionButton('Detail', 'fas fa-search', function ($row) {
            return site_url('masjid/index/detail?key=' . $row['id']);
        }, false);

        // $crud->callbackDelete(function ($stateParameters) {
        //     // Making sure that we skip the delete functionality first!
        //     return $stateParameters;
        // });

        // $crud->callbackAfterDelete(function ($stateParameters) {
        //     $result = $this->masjid->hapus($stateParameters->primaryKeyValue);
        //     if ($result['status'] == 201) {
        //         return $stateParameters;
        //     } else {
        //         $errorMessage = new \GroceryCrud\Core\Error\ErrorMessage();
        //         return $errorMessage->setMessage($result['message']);
        //     }
        // });

        $type = [
            'Masjid'   => 'Masjid',
            'Mushola'  => 'Mushola',
        ];


        $crud->fieldType('type', 'dropdown_search', $type);

        $crud->displayAs([
            'nama'          => 'Nama Masjid',
            'nama_pj_takmir'    => 'Takmir',
            'type'              => 'Jenis Masjid',
            'kode_provinsi'     => 'Asal Provinsi',
            'kode_kabupaten'    => 'Asal Kabupaten',
            'kode_kecamatan'    => 'Asal Kecamatan',
            'alamat'            => 'Alamat Lengkap',
            'ref_id_afiliasi'   => 'Afiliasi'

        ]);

        if (getSessionRole() == 1) {
            $crud->setActionButton('Hapus', 'fa fa-trash', function ($row) {
                return 'javascript:hapus(' . "'" . $row['id'] . "'" . ')';
            }, false);
        }

        $output = $crud->render();
        $this->setOutput($output, 'index');
    }

    public function getMasjidNaktif()
    {
        $data = $this->masjid->getMasjidNaktif();

        echo json_encode($data);
    }

    public function remove()
    {
        $input = $this->input->post();

        $cekPengurus = $this->pengurus->getWhereMasjid($input['id']);
        $cekJamaah = $this->jamaah->getWhereMasjid($input['id']);
        if ($cekPengurus == 0 && $cekJamaah == 0) {
            $result = $this->masjid->hapus($input['id']);

            if ($result == true) {
                return successResponseJson('Proses berhasil');
            } else {
                return internalServerErrorResponseJson('Hapus gagal');
            }
        } else {
            return internalServerErrorResponseJson('Masjid tidak bisa dihapus karena terdapat data pengurus dan jamaah');
        }
    }

    private function _callbackUpdate($stateParameters)
    {
        $data = $stateParameters->data['id'];
        var_dump($data);
        die;
        $stateParameters = site_url('masjid/index/edit?key=' . $data);

        return $stateParameters;
    }

    public function verifikasi($id=null)
    {
        $result = $this->masjid->verifikasiMasjid($id);

        if ($result == true) {
            return successResponseJson('Proses berhasil');
        } else {
            return internalServerErrorResponseJson('server error');
        }        
    }

    public function DataTableMasjid()
    {
        $return = array();

        $field = array(
            'sSearch',
            'iSortCol_0',
            'sSortDir_0',
            'iDisplayStart',
            'iDisplayLength',
            'jenis_masjid',
            'typologi_masjid',
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
            'jenis_masjid'      => $jenis_masjid,
            'typologi_masjid'   => $typologi_masjid,
        );

        $data = $this->masjid->DataTableMasjid($params);
        if ($data['total'] > 0) {
            $return['iTotalRecords'] = $data['total'];
            $return['iTotalDisplayRecords'] = $return['iTotalRecords'];

            foreach ($data['rows'] as $k => $row) {

                $row['nomor'] = '<p class="text-center">' . ($iDisplayStart + ($k + 1)) . '</p>';
                $row['nama_masjid'] = $row['nama'];
                $row['takmir'] = $row['nama_pj_takmir'];
                $row['jenis_masjid'] = $row['type'];
                $row['provinsi'] = ucwords(strtolower($row['provinsi']));
                $row['kabupaten'] = ucwords(strtolower($row['kabupaten']));
                $row['kecamatan'] = ucwords(strtolower($row['kecamatan']));
                $row['alamat'] = $row['alamat'];
                $row['kelola'] = '<div class="btn-group">
                <a class="btn btn-info btn-icon" href="' . site_url('masjid/index/edit?key=' . $row['id']) . '"><i class="fas fa-edit"></i></a> <a class="btn btn-warning text-white btn-icon" href="' . site_url('masjid/index/detail?key=' . $row['id']) . '" id="btn-edit"><i class="fas fa-search"></i></a>
                <button class="btn btn-danger btn-icon" data="' . $row['id'] . '" id="btn-hapus"><i class="fas fa-trash"></i></button>
                </div>';

                $return['aaData'][] = $row;
            }
        }
        $this->db->flush_cache();
        responseJson(200, $return);
    }

    public function tambah()
    {
        $this->data['title']    = 'Tambah Data Masjid';
        $this->data['scripts']  = ['template/js/const.js', 'masjid/js/tambah.js'];
        $this->render('tambah');
    }

    public function simpan()
    {
        $params = $this->input->post(null, true);

        $data = filterFieldsOfTable('masjid', $params);

        $result = $this->masjid->simpan($data);

        responseJson($result['status'], $result);
    }

    public function detail()
    {
        $row = $this->input->get('key');

        $this->data['title']    = 'Detail Masjid';
        $data = $this->masjid->detail($row);

        if (empty($data)) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Data masjid tidak ditemukan</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
            </div>');
            redirect('masjid');
        }
        $this->data['data']     = $data;
        $this->data['id']       = $row;
        $this->data['scripts']  = ['template/js/const.js', 'masjid/js/detail.js', 'masjid/js/jamaah.js'];
        $this->render('detail');
    }

    public function edit()
    {
        $this->data['title']    = 'Edit data Masjid';
        $edit = $this->masjid->edit($this->input->get('key'))['data'];
        if (empty($edit)) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Data masjid tidak ditemukan</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
            </div>');
            redirect('masjid');
        }

        $this->data['data']     = $edit;
        $this->data['scripts']  = ['template/js/const.js', 'masjid/js/edit.js'];
        $this->render('edit');
    }

    public function update()
    {
        $params = $this->input->post(null, true);

        $data = filterFieldsOfTable('masjid', $params);
        $data['last_modified'] = date('Y-m-d H:i:s');

        $result = $this->masjid->update($data, $params['id']);

        responseJson($result['status'], $result);
    }

    public function hapus()
    {
        $params = $this->input->post(null, true);

        $result = $this->masjid->hapus($params['kode']);

        responseJson($result['status'], $result);
    }
}
