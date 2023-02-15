<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Index extends BaseController
{
    public $loginBehavior = true;
    protected $module = "pengurus";
    protected $template = "app";
    private $groupId = '2';

    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->library('arkatama');
        $this->load->model('M_pengurus', 'pengurus');
        $this->load->model('M_wilayah', 'wilayah');
        $this->load->model('M_user', 'user');
        $this->load->model('M_masjid', 'masjid');
        $this->load->helpers('function_helper');
    }

    public function index()
    {
        $this->data['title'] = 'Data Pengurus';
        $crud = new Grid();
        $crud->setSkin('bootstrap-v4');
        $crud->unsetJquery();
        $crud->unsetAdd();
        $crud->unsetEdit();
        $crud->unsetDelete();
        $crud->unsetPrint();
        $crud->unsetFilters();
        $crud->unsetExport();
        $crud->setSubject('Pengurus');
        $crud->setTable('masjid_pengurus');
        $crud->setRelation('kode_provinsi', 'ref_provinsi', 'nama_provinsi');
        $crud->setRelation('kode_kabupaten', 'ref_kabupaten', 'nama_kab_kota');
        $crud->setRelation('kode_kecamatan', 'ref_kecamatan', 'nama_kecamatan');

        $listMasjid = $this->pengurus->getListMasjid();
        $listProvinsi = $this->wilayah->getListProvinsi();
        $listKabupaten = $this->wilayah->getListKabupaten();
        $listKecamatan = $this->wilayah->getListKecamatan();
        $listJenisKelamin = [
            'Laki-laki'   => 'Laki-laki',
            'Perempuan'  => 'Perempuan',
        ];
        $listJabatan = [
            'Ketua'         => 'Ketua',
            'Sekretaris'    => 'Sekretaris',
            'Bendahara'     => 'Bendahara',
            'Anggota'       => 'Anggota',
        ];

        $crud->columns(['id_masjid', 'nama', 'jabatan', 'jenis_kelamin', 'no_hp', 'alamat', 'tanggal_lahir', 'tempat_lahir', 'email', 'url_foto', 'kode_provinsi', 'kode_kabupaten', 'kode_kecamatan']);

        $crud->fieldType('kode_provinsi', 'dropdown_search', $listProvinsi);
        $crud->fieldType('kode_kabupaten', 'dropdown_search', $listKabupaten);
        $crud->fieldType('kode_kecamatan', 'dropdown_search', $listKecamatan);
        $crud->fieldType('jenis_kelamin', 'dropdown_search', $listJenisKelamin);
        $crud->fieldType('id_masjid', 'dropdown_search', $listMasjid);
        $crud->fieldType('jabatan', 'dropdown_search', $listJabatan);
        $crud->fieldType('no_hp', 'numeric');
        $crud->fieldType('email', 'email');

        $crud->setRule('no_hp', 'lengthBetween', ['10','13']);

        $uploadValidations = [
            'maxUploadSize' => '3M', // 3 Mega Bytes
            'minUploadSize' => '1K', // 1 Kilo Byte
            'allowedFileTypes' => [
                'gif', 'jpeg', 'jpg', 'png', 'tiff'
            ]
        ];

        $crud->setFieldUpload(
            'url_foto',
            'public/uploads/pengurus',
            base_url() . 'public/uploads/pengurus',
            $uploadValidations
        );

        $crud->callbackAddField('tanggal_lahir', function ($fieldType, $fieldName) {
            $tampil = '
            <div class="input-group mb-3">
                <input type="date" class="form-control tanggal" id="inputTanggalSelesai" name="' . $fieldName . '" value="" >
            </div>
            ';
            return $tampil;
        });

        $crud->fieldType('no_hp', 'numeric');
        $crud->fieldType('email', 'email');

        $crud->displayAs([
            'nama'              => 'Nama Pengurus',
            'jabatan'           => 'Jabatan',
            'jenis_kelamin'     => 'Jenis Kelamin',
            'no_hp'             => 'No HP',
            'alamat'            => 'Alamat',
            'email'             => 'Email',
            'id_masjid'         => 'Nama Masjid',
            'tanggal_lahir'     => 'Tanggal Lahir',
            'tempat_lahir'      => 'Tempat Lahir',
            'kode_provinsi'     => 'Provinsi',
            'kode_kabupaten'    => 'Kabupaten',
            'kode_kecamatan'    => 'Kecamatan',
            'url_foto'          => 'Foto',
        ]);

        if (getSessionRole() == 2) {
            $id_masjid = $this->pengurus->getIdByIdUser(getSessionID())->id_masjid;
            $crud->where([
                'id_masjid' => $id_masjid
            ]);
        }

        $crud->setActionButton('Edit', 'fas fa-edit', function ($row) {
            return site_url('pengurus/index/edit?key='. $row['id']) ;
        }, false);

        $crud->setActionButton('Detail', 'fas fa-search', function ($row) {
            return site_url('pengurus/index/detPengurus/' . $row['id']);
        }, false);

        $crud->setActionButton('Hapus', 'fa fa-trash', function ($row) {
            return 'javascript:hapus(' . "'" . $row['id'] . "'" . ')';
        }, false);

        $crud->addFields(['nama', 'jabatan', 'jenis_kelamin', 'no_hp', 'alamat', 'email', 'id_masjid', 'tanggal_lahir', 'tempat_lahir', 'kode_provinsi', 'kode_kabupaten', 'kode_kecamatan', 'url_foto']);

        $crud->editFields(['nama', 'jabatan', 'jenis_kelamin', 'no_hp', 'alamat', 'email', 'id_masjid', 'tanggal_lahir', 'tempat_lahir', 'kode_provinsi', 'kode_kabupaten', 'kode_kecamatan', 'url_foto']);

        

        $crud->requiredFields(['nama', 'jabatan', 'jenis_kelamin', 'no_hp', 'alamat', 'email', 'id_masjid', 'tanggal_lahir', 'tempat_lahir', 'kode_provinsi', 'kode_kabupaten', 'kode_kecamatan']);

        //callback

        $crud->callbackInsert([$this, '_callbackInsert']);
        $crud->callbackBeforeUpdate([$this, '_callbackBeforeUpdate']);
        $crud->callbackDelete([$this, '_callbackDelete']);

        $output = $crud->render();
        $this->setOutput($output, 'index');

        // $this->data['scripts']  = ['pengurus/js/index.js'];
        $this->data['scripts']  = ['template/js/const.js', 'pengurus/js/index.js'];
    }

    // private function _callbackUpdate($stateParameters)
    // {
    //     $data = $stateParameters->data['id'];
    //     var_dump($data);die;
    //     $stateParameters = site_url('masjid/index/edit?key='. $data);

    //     return $stateParameters;
    // }

    function _callbackInsert($stateParameters)
    {
        $data = [
            "email"           => $stateParameters->data['email'],
            "username"        => $stateParameters->data['email'],
            "real_name"       => $stateParameters->data['nama'],
            "id_group"        => $this->groupId,
            "password"        => hash('sha256', $stateParameters->data['email']),
            "id_masjid"       => $stateParameters->data['id_masjid'],
            "no_hp"           => $stateParameters->data['no_hp'],
            "nama"            => $stateParameters->data['nama'],
            "jenis_kelamin"   => $stateParameters->data['jenis_kelamin'],
            "alamat"          => $stateParameters->data['alamat'],
            "tanggal_lahir"   => $stateParameters->data['tanggal_lahir'],
            "tempat_lahir"    => $stateParameters->data['tempat_lahir'],
            "url_foto"        => $stateParameters->data['url_foto'],
            "kode_provinsi"   => $stateParameters->data['kode_provinsi'],
            "kode_kabupaten"  => $stateParameters->data['kode_kabupaten'],
            "jabatan"         => $stateParameters->data['jabatan'],
            "kode_kecamatan"  => $stateParameters->data['kode_kecamatan']
        ];

        $insert = $this->user->createUserPengurus($this->groupId, $data);
        $stateParameters->insertId = $insert['id'];

        return $stateParameters;
    }

    function _callbackDelete($stateParameters)
    {
        $update = $this->user->destroyUserPengurus($this->groupId, intval($stateParameters->primaryKeyValue));
        if ($update['status'] == "error") {
            $errorMessage = new \GroceryCrud\Core\Error\ErrorMessage();
            $message = "<div class='alert alert-danger'>" . $update['message'] . "</div>";
            return $errorMessage->setMessage($message);
        }
        return $stateParameters;
    }

    function _callBeforeInsert($stateParameters)
    {
        $stateParameters->data['id'] = getUUID();
        $stateParameters->data['created_by'] = $this->session->userdata('sajada_userId');
        $stateParameters->data['id_user'] = $this->session->userdata('sajada_userId');
        return $stateParameters;
    }

    function _callbackBeforeUpdate($stateParameters)
    {
        return $stateParameters;
    }

    // function _callbackBeforeDelete($stateParameters)
    // {
    //     return $stateParameters;
    // }

    function getSelectMasjid($id = null)
    {
        $return = '<option value="">Pilih Masjid</option>';
        $result = $this->pengurus->getMasjid($id);
        if (!empty($result)) {
            foreach ($result as $rows) {
                $return .= '<option value="' . $rows['id'] . '" ' . ($rows['id'] == $id ? 'selected' : '') . '>' . ucwords(strtolower($rows['nama'])) . '</option>';
            }
        }
        return $return;
    }

    public function detPengurus($row)
    {
        $this->data['title'] = 'Detail Pengurus';
        $this->data['data'] = $data = $this->pengurus->detail($row);
        if (empty($data)) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Data pengurus tidak ditemukan</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
            </div>');
            redirect('pengurus/index/');
        }
        $this->data['nama_pengurus'] = $this->pengurus->getNamaPengurus($row);
        $this->data['masjid'] = $this->pengurus->getMasjid($row);
        $this->data['id'] = $row;
        $this->data['pilihmasjid'] = $this->getSelectMasjid();
        $this->render('tablist');
    }

    public function tambah()
    {
        $this->data['title']    = 'Tambah Data Pengurus';
        if (getSessionRole() == 2) {
            $id_masjid = $this->pengurus->getIdByIdUser(getSessionID())->id_masjid;
            $this->data['id'] = $id_masjid;
        }
        $this->data['scripts']  = ['template/js/const.js', 'pengurus/js/tambah.js'];
        $this->render('tambah');
    }

    public function simpan()
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

    public function edit()
    {
        $this->data['title']    = 'Edit Data Pengurus';
        $edit = $this->pengurus->edit($this->input->get('key'))['data'];
        if(empty($edit)){
            $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Data pengurus tidak ditemukan</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
            </div>');
            redirect('pengurus');
        }
        $this->data['data']     = $edit;
        $this->data['scripts']  = ['template/js/const.js', 'pengurus/js/edit.js'];
        $this->render('edit');
    }

    public function update()
    {
        $params = $this->input->post(null, true);
        $image = uploadImage('url_foto_edit', 'pengurus', 'pengurus');

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

    public function remove()
    {
        $input = $this->input->post();

        $result = $this->pengurus->hapus($input['id']);

        if($result['status'] == 200){
            return successResponseJson('Proses berhasil');
        }else{
            return internalServerErrorResponseJson('Hapus gagal');
        }
    }

}
