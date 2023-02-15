<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Index extends BaseController
{
    public $loginBehavior = true;
    protected $module = "jamaah";
    private $groupId = 3;

    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->library('arkatama');
        $this->load->model('M_masjid', 'masjid');
        $this->load->model('M_pengurus', 'pengurus');
        $this->load->model('M_jamaah', 'jamaah');
        $this->load->model('M_wilayah', 'wilayah');
        $this->load->model('M_user', 'user');
        $this->load->helpers('function_helper');
    }

    public function index()
    {
        $this->data['title'] = 'Data Jamaah';
        $crud = new Grid();
        $crud->setSkin('bootstrap-v4');
        $crud->unsetJquery();
        $crud->unsetPrint();
        $crud->unsetFilters();
        $crud->unsetExport();
        $crud->unsetEdit();
        $crud->unsetAdd();
        $crud->unsetDelete();
        $crud->setSubject('Jamaah');
        $crud->setTable('jamaah');
        $crud->setRelation('id_masjid', 'masjid', 'nama');

        $listProvinsi = $this->wilayah->getListProvinsi();
        $listKabupaten = $this->wilayah->getListKabupaten();
        $listKecamatan = $this->wilayah->getListKecamatan();

        if (getSessionRole() == 2) {
            $id_masjid = $this->pengurus->getIdByIdUser(getSessionID())->id_masjid;
            $crud->where([
                'id_masjid' => $id_masjid
            ]);
        }

        // $crud->columns(['id_masjid', 'nama', 'jenis_kelamin', 'email','no_hp', 'alamat', 'url_foto', 'tanggal_lahir', 'tempat_lahir', 'kode_provinsi', 'kode_kabupaten', 'kode_kecamatan']);
        $crud->columns(['id_masjid', 'nama', 'email','no_hp', 'alamat', 'url_foto']);

        $listJenisKelamin = [
            'Laki-laki'   => 'Laki-laki',
            'Perempuan'  => 'Perempuan',
        ];

        $crud->fieldType('kode_provinsi', 'dropdown_search', $listProvinsi);
        $crud->fieldType('kode_kabupaten', 'dropdown_search', $listKabupaten);
        $crud->fieldType('kode_kecamatan', 'dropdown_search', $listKecamatan);
        $crud->fieldType('jenis_kelamin', 'dropdown_search', $listJenisKelamin);
        

        $crud->displayAs([
            'id_masjid'         => 'Nama Masjid',
            'nama'              => 'Nama Jamaah',
            'jenis_kelamin'     => 'Jenis Kelamin',
            'email'             => 'Email',
            'no_hp'             => 'No HP',
            'alamat'            => 'Alamat',
            'url_foto'          => 'Foto',
            'tanggal_lahir'     => 'Tanggal Lahir',
            'tempat_lahir'      => 'Tempat Lahir',
            // 'kode_provinsi'     => 'Provinsi',
            // 'kode_kabupaten'    => 'Kabupaten',
            // 'kode_kecamatan'    => 'Kecamatan',
        ]);

        // button set manual

        $crud->setActionButton('Edit', 'fas fa-edit', function ($row) {
            return site_url('jamaah/index/edit?key=' . $row['id']);
        }, false);

        $crud->setActionButton('Detail', 'fa fa-search', function ($row) {
            return site_url('jamaah/index/detJamaah/' . $row['id']);
        }, false);

        $crud->setActionButton('Hapus', 'fa fa-trash', function ($row) {
            return 'javascript:hapus(' . "'" . $row['id'] . "'" . ')';
        }, false);

        $uploadValidations = [
            'maxUploadSize' => '3M', // 3 Mega Bytes
            'minUploadSize' => '1K', // 1 Kilo Byte
            'allowedFileTypes' => [
                'gif', 'jpeg', 'jpg', 'png', 'tiff'
            ]
        ];

        $crud->setFieldUpload(
            'url_foto',
            'public/uploads/jamaah',
            base_url() . '/public/uploads/jamaah',
            $uploadValidations
        );

        $output = $crud->render();
        $this->setOutput($output, 'index');
    }

    public function detJamaah($row)
    {
        $this->data['title'] = 'Detail Jamaah';
        $this->data['data'] = $data = $this->jamaah->detail($row);
        $this->data['nama_jamaah'] = $this->jamaah->getNamajamaah($row);
        $this->data['masjid'] = $this->jamaah->getMasjid($row);
        $this->data['id'] = $row;
        $this->data['pilihmasjid'] = $this->getSelectMasjid();
        if(empty($data)){
            $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Data jamaah tidak ditemukan</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
            </div>');
            redirect('jamaah/index');
        }
        $this->render('tablist');
    }

    function getSelectMasjid($id = null)
    {
        $return = '<option value="">Pilih Masjid</option>';
        $result = $this->jamaah->getMasjid($id);
        if (!empty($result)) {
            foreach ($result as $rows) {
                $return .= '<option value="' . $rows['id'] . '" ' . ($rows['id'] == $id ? 'selected' : '') . '>' . ucwords(strtolower($rows['nama'])) . '</option>';
            }
        }
        return $return;
    }

    public function dataTabungan()
    {
        $searchTermtask = $this->input->post();
        $response = $this->project->get_TaskPeserta($searchTermtask);
        echo json_encode($response);
    }

    public function createTabungan()
    {
        $data = [
            'id' => getUUID(),
            'id_jamaah' => $this->jamaah->get_jamaah(),
            'id_masjid' => $this->input->post('id_masjid'),
            'jumlah' => $this->input->post('jumlah'),
        ];
        // var_dump($data);
        // exit();

        $response = $this->jamaah->create_tabungan($data);

        echo json_encode($response);
    }

    public function tambah()
    {
        $this->data['title']    = 'Tambah Data Jamaah';
        if (getSessionRole() == 2) {
            $id_masjid = $this->pengurus->getIdByIdUser(getSessionID())->id_masjid;
            $this->data['id'] = $id_masjid;
        }
        $this->data['scripts']  = ['template/js/const.js', 'jamaah/js/tambah.js'];
        $this->render('tambah');
    }

    public function simpan()
    {
        $params = $this->input->post(null, true);

        $image = uploadImage('url_foto', 'jamaah', 'jamaah_');

        // penambahan parameter url foto untuk data jamaah
        if($image['success'] == true){
            $params['url_foto'] = $image['file'];
        }

        $data = filterFieldsOfTable('jamaah', $params);

        $result = $this->jamaah->simpanJamaah($data);

        // menghapus file jika gagal menambahkan data
        if(!empty($image['file_name'])){
            if($result['status'] == '500'){
                $file = '.' . $image['file_name'];
                unlink($file);
            }
        }

        responseJson($result['status'], $result);
    }

    public function edit()
    {
        $this->data['title']    = 'Edit Data Jamaah';
        $edit = $this->jamaah->edit($this->input->get('key'))['data'];
        if(empty($edit)){
            $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Data jamaah tidak ditemukan</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
            </div>');
            redirect('jamaah/index');
        }
        $this->data['data']     = $edit;
        $this->data['scripts']  = ['template/js/const.js', 'jamaah/js/edit.js'];
        $this->render('edit');
    }

    public function update()
    {
        $params = $this->input->post(null, true);

        $image = uploadImage('url_foto_edit', 'jamaah', 'jamaah_');

        if($image['success'] == true){
            $params['url_foto'] = $image['file'];
            if(!empty($params['foto'])){
                $foto_lama = './public/uploads/jamaah/' . $params['foto'];
                unlink($foto_lama);
            }
        }

        $data = filterFieldsOfTable('jamaah', $params);
        $data['last_modified'] = date('Y-m-d H:i:s');

        $result = $this->jamaah->update($data);

        // menghapus file jika gagal menambahkan data
        if(!empty($image['file_name'])){
            if($result['status'] == '500'){
                $file = '.' . $image['file_name'];
                unlink($file);
            }
        }

        responseJson($result['status'], $result);
    }

    public function remove()
    {
        $input = $this->input->post();

        $result = $this->jamaah->hapus($input['id']);

        if($result['status'] == 200){
            return successResponseJson('Proses berhasil');
        }else{
            return internalServerErrorResponseJson('Hapus gagal');
        }
    }

}
