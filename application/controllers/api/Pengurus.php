<?php

defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
// require APPPATH . '/libraries/Format.php';


use Restserver\Libraries\REST_Controller;

class Pengurus extends REST_Controller
{

    private $ok = '200';
    private $bad = '400';
    private $unauthorized = '401';
    private $error = '500';

    function __construct()
    {
        parent::__construct();
        $this->methods['data_post']['limit'] = 100; // 100 requests per hour per data/key
        $this->load->model('api/api_pengurus', 'pengurus');
    }

    public function get_pengurus_post()
    {
        $header = $this->input->request_headers();
        $header = array_change_key_case($header, CASE_LOWER);


        if (array_key_exists('tokenkey', $header) && !empty($header['tokenkey'])) {
            $token_key = $header['tokenkey'];

            $decoded_token = AUTHORIZATION::validateToken($token_key);
            if ($decoded_token != false && property_exists($decoded_token, 'id_user') && property_exists($decoded_token, 'id_group')) {
                $id_pengurus = $this->input->post('id_pengurus');
                if ($id_pengurus == null) {
                    $this->response([
                        'code'      => REST_Controller::HTTP_BAD_REQUEST,
                        'message'   => 'Id pengurus tidak boleh kosong',
                        'data'      => null,
                    ], REST_Controller::HTTP_BAD_REQUEST);
                    return;
                }

                $pengurus = $this->pengurus->get_pengurus($id_pengurus);

                if ($pengurus['success']) {
                    $this->response([
                        'code'      => REST_Controller::HTTP_OK,
                        'message'   => 'Data Ditemukan',
                        'data'      => $pengurus['data'],
                    ], REST_Controller::HTTP_OK);
                } else {
                    $this->response([
                        'code'      => REST_Controller::HTTP_NOT_FOUND,
                        'message'   => 'Data Tidak Ditemukan',
                        'data'      => null,
                    ], REST_Controller::HTTP_NOT_FOUND);
                }
            } else {
                $this->response([
                    'status'    => $this->unauthorized,
                    'message'   => 'Unathorized/Invalid Api key',
                    'data'      => null,
                ], REST_Controller::HTTP_UNAUTHORIZED);
                return;
            }
        } else {
            $this->response([
                'status'     => $this->bad,
                'message'    => 'Api key tidak ditemukan.',
                'data'       => null,
            ], REST_Controller::HTTP_BAD_REQUEST);
            return;
        }
    }

    public function get_pengurus_put()
    {
        $this->response([
            'status'    => $this->bad,
            'message'   => 'Bad Request',
            'data'      => null,
        ], REST_Controller::HTTP_BAD_REQUEST);
    }

    public function get_pengurus_delete()
    {
        $this->response([
            'status'    => $this->bad,
            'message'   => 'Bad Request',
            'data'      => null,
        ], REST_Controller::HTTP_BAD_REQUEST);
    }

    public function get_pengurus_list_get()
    {
        $header = $this->input->request_headers();
        $header = array_change_key_case($header, CASE_LOWER);


        if (array_key_exists('tokenkey', $header) && !empty($header['tokenkey'])) {
            $token_key = $header['tokenkey'];

            $decoded_token = AUTHORIZATION::validateToken($token_key);
            if ($decoded_token != false && property_exists($decoded_token, 'id_user') && property_exists($decoded_token, 'id_group')) {
                $pengurus = $this->pengurus->get_pengurus_list();

                if ($pengurus['success']) {
                    $this->response([
                        'code'      => REST_Controller::HTTP_OK,
                        'message'   => 'Data Ditemukan',
                        'data'      => $pengurus['data'],
                    ], REST_Controller::HTTP_OK);
                } else {
                    $this->response([
                        'code'      => REST_Controller::HTTP_NOT_FOUND,
                        'message'   => 'Data Tidak Ditemukan',
                        'data'      => null,
                    ], REST_Controller::HTTP_NOT_FOUND);
                }
            } else {
                $this->response([
                    'status'    => $this->unauthorized,
                    'message'   => 'Unathorized/Invalid Api key',
                    'data'      => null,
                ], REST_Controller::HTTP_UNAUTHORIZED);
                return;
            }
        } else {
            $this->response([
                'status'     => $this->bad,
                'message'    => 'Api key tidak ditemukan.',
                'data'       => null,
            ], REST_Controller::HTTP_BAD_REQUEST);
            return;
        }
    }

    public function get_pengurus_list_post()
    {
        $this->response([
            'status'    => $this->bad,
            'message'   => 'Bad Request',
            'data'      => null,
        ], REST_Controller::HTTP_BAD_REQUEST);
    }

    public function get_pengurus_list_put()
    {
        $this->response([
            'status'    => $this->bad,
            'message'   => 'Bad Request',
            'data'      => null,
        ], REST_Controller::HTTP_BAD_REQUEST);
    }

    public function get_pengurus_list_delete()
    {
        $this->response([
            'status'    => $this->bad,
            'message'   => 'Bad Request',
            'data'      => null,
        ], REST_Controller::HTTP_BAD_REQUEST);
    }

    public function get_jabatan_get()
    {
        $pengurus = $this->pengurus->get_jabatan();
        $this->response([
            'code'      => REST_Controller::HTTP_OK,
            'message'   => 'Data Ditemukan',
            'data'      => $pengurus['data'],
        ], REST_Controller::HTTP_OK);
    }

    public function get_jabatan_post()
    {
        $this->response([
            'status'    => $this->bad,
            'message'   => 'Bad Request',
            'data'      => null,
        ], REST_Controller::HTTP_BAD_REQUEST);
    }

    public function get_jabatan_put()
    {
        $this->response([
            'status'    => $this->bad,
            'message'   => 'Bad Request',
            'data'      => null,
        ], REST_Controller::HTTP_BAD_REQUEST);
    }

    public function get_jabatan_delete()
    {
        $this->response([
            'status'    => $this->bad,
            'message'   => 'Bad Request',
            'data'      => null,
        ], REST_Controller::HTTP_BAD_REQUEST);
    }

    public function get_pengurus_get()
    {
        $pengurus = $this->pengurus->pengurusData();
        $this->response([
            'code'      => REST_Controller::HTTP_OK,
            'message'   => 'Data Ditemukan',
            'data'      => $pengurus['data'],
        ], REST_Controller::HTTP_OK);
    }

    public function delete_pengurus_post()
    {
        $header = $this->input->request_headers();
        $header = array_change_key_case($header, CASE_LOWER);


        if (array_key_exists('tokenkey', $header) && !empty($header['tokenkey'])) {
            $token_key = $header['tokenkey'];
            $decoded_token = AUTHORIZATION::validateToken($token_key);
            if ($decoded_token != false && property_exists($decoded_token, 'id_user') && property_exists($decoded_token, 'id_group')) {
                if ($decoded_token->id_group != 1) {
                    $this->response([
                        'status' => REST_Controller::HTTP_FORBIDDEN,
                        'message' => "Akses Ditolak, Silahkan Login Sebagai Admin",
                        'data' => null,
                    ], REST_Controller::HTTP_FORBIDDEN);
                    return;
                }

                $this->form_validation->set_rules('id_pengurus', 'id_pengurus', 'required');

                $this->form_validation->set_error_delimiters('', '');
                if ($this->form_validation->run() == false) {
                    $this->response([
                        'status' => REST_Controller::HTTP_OK,
                        'message' => rtrim(validation_errors(), "\n"),
                        'data' => null,
                    ], REST_Controller::HTTP_OK);
                    return;
                } else {

                    $id_pengurus = $this->input->post('id_pengurus');

                    $isPengurus = $this->pengurus->get_pengurus($id_pengurus);

                    if ($isPengurus['data'] == null) {
                        $this->response([
                            'status' => REST_Controller::HTTP_NOT_FOUND,
                            'message' => "Pengurus Tidak Ditemukan",
                            'data' => null,
                        ], REST_Controller::HTTP_NOT_FOUND);
                        return;
                    }

                    $delete = $this->pengurus->delete_pengurus($id_pengurus);

                    if ($delete) {
                        $this->response([
                            'status' => REST_Controller::HTTP_OK,
                            'message' => "Data Pengurus Berhasil Dihapus",
                            'data' => null,
                        ], REST_Controller::HTTP_OK);
                        return;
                    } else {
                        $this->response([
                            'status' => REST_Controller::HTTP_BAD_REQUEST,
                            'message' => "Data Pengurus Gagal Dihapus",
                            'data' => null,
                        ], REST_Controller::HTTP_BAD_REQUEST);
                        return;
                    }
                }
            } else {
                $this->response([
                    'status'    => $this->unauthorized,
                    'message'   => 'Unathorized/Invalid Api key',
                    'data'      => null,
                ], REST_Controller::HTTP_UNAUTHORIZED);
                return;
            }
        } else {
            $this->response([
                'status'     => $this->bad,
                'message'    => 'Api key tidak ditemukan.',
                'data'       => null,
            ], REST_Controller::HTTP_BAD_REQUEST);
            return;
        }
    }

    public function delete_pengurus_put()
    {
        $this->response([
            'status'    => $this->bad,
            'message'   => 'Bad Request',
            'data'      => null,
        ], REST_Controller::HTTP_BAD_REQUEST);
    }

    public function delete_pengurus_get()
    {
        $this->response([
            'status'    => $this->bad,
            'message'   => 'Bad Request',
            'data'      => null,
        ], REST_Controller::HTTP_BAD_REQUEST);
    }

    public function delete_pengurus_delete()
    {
        $this->response([
            'status'    => $this->bad,
            'message'   => 'Bad Request',
            'data'      => null,
        ], REST_Controller::HTTP_BAD_REQUEST);
    }

    public function update_pengurus_post()
    {

        $header = $this->input->request_headers();
        $header = array_change_key_case($header, CASE_LOWER);


        if (array_key_exists('tokenkey', $header) && !empty($header['tokenkey'])) {
            $token_key = $header['tokenkey'];
            $decoded_token = AUTHORIZATION::validateToken($token_key);
            if ($decoded_token != false && property_exists($decoded_token, 'id_user') && property_exists($decoded_token, 'id_group')) {
                if ($decoded_token->id_group != 1) {
                    $this->response([
                        'status' => REST_Controller::HTTP_FORBIDDEN,
                        'message' => "Akses Ditolak",
                        'data' => null,
                    ], REST_Controller::HTTP_FORBIDDEN);
                    return;
                }
                $id_user = $decoded_token->id_user;
                $id_pengurus = $this->pengurus->getIdPengurus($id_user);


                $pengurus = $this->pengurus->get_pengurus($id_pengurus);

                (($this->input->post('nama') == NULL) ?  $nama = $pengurus['data']['nama'] :  $nama = $this->input->post('nama'));
                (($this->input->post('jenis_kelamin') == NULL) ?  $jenis_kelamin = $pengurus['data']['jenis_kelamin'] :  $jenis_kelamin = $this->input->post('jenis_kelamin'));
                (($this->input->post('no_hp') == NULL) ?  $no_hp = $pengurus['data']['no_hp'] :  $no_hp = $this->input->post('no_hp'));
                (($this->input->post('alamat') == NULL) ?  $alamat = $pengurus['data']['alamat'] :  $alamat = $this->input->post('alamat'));
                (($this->input->post('tanggal_lahir') == NULL) ?  $tanggal_lahir = $pengurus['data']['tanggal_lahir'] :  $tanggal_lahir = $this->input->post('tanggal_lahir'));
                (($this->input->post('tempat_lahir') == NULL) ?  $tempat_lahir = $pengurus['data']['tempat_lahir'] :  $tempat_lahir = $this->input->post('tempat_lahir'));
                (($this->input->post('email') == NULL) ?  $email = $pengurus['data']['email'] :  $email = $this->input->post('email'));
                (($this->input->post('kode_provinsi') == NULL) ?  $kode_provinsi = $pengurus['data']['kode_provinsi'] :  $kode_provinsi = $this->input->post('kode_provinsi'));
                (($this->input->post('kode_kabupaten') == NULL) ?  $kode_kabupaten = $pengurus['data']['kode_kabupaten'] :  $kode_kabupaten = $this->input->post('kode_kabupaten'));
                (($this->input->post('kode_kecamatan') == NULL) ?  $kode_kecamatan = $pengurus['data']['kode_kecamatan'] :  $kode_kecamatan = $this->input->post('kode_kecamatan'));
                (($this->input->post('url_foto') == NULL) ?  $url_foto = $pengurus['data']['url_foto'] :  $url_foto = $this->input->post('url_foto'));

                if (isset($_FILES['url_foto']['name'])) {
                    if (file_exists($pengurus['data']['url_foto'])) {
                        unlink($pengurus['data']['url_foto']);
                    }

                    $path = "./public/uploads/jamaah/" . $pengurus['data']['id'];
                    if (!is_dir($path)) {
                        mkdir($path, 0777);
                    }

                    $path_root = 'pengurus/' . $pengurus['data']['id'];
                    $upload = uploadImage('url_foto', $path_root, 'pengurus');

                    if ($upload['success']) {
                        $url_foto = $upload['file_name'];
                    }
                }

                $pengurus = [
                    'nama' => $nama,
                    'jenis_kelamin' => $jenis_kelamin,
                    'no_hp' => $no_hp,
                    'alamat' => $alamat,
                    'tanggal_lahir' => $tanggal_lahir,
                    'tempat_lahir' => $tempat_lahir,
                    'email' => $email,
                    'url_foto' => $url_foto,
                    'kode_provinsi' => $kode_provinsi,
                    'kode_kabupaten' => $kode_kabupaten,
                    'kode_kecamatan' => $kode_kecamatan,
                ];

                $update = $this->pengurus->update_pengurus($pengurus, $id_pengurus);

                if ($update) {
                    $this->response([
                        'status' => REST_Controller::HTTP_OK,
                        'message' => "Data Pengurus Berhasil Diupdate",
                        'data' => null,
                    ], REST_Controller::HTTP_OK);
                    return;
                } else {
                    $this->response([
                        'status' => REST_Controller::HTTP_BAD_REQUEST,
                        'message' => "Data Pengurus Gagal Diupdate",
                        'data' => null,
                    ], REST_Controller::HTTP_BAD_REQUEST);
                    return;
                }
            } else {
                $this->response([
                    'status'    => $this->unauthorized,
                    'message'   => 'Unathorized/Invalid Api key',
                    'data'      => null,
                ], REST_Controller::HTTP_UNAUTHORIZED);
                return;
            }
        } else {
            $this->response([
                'status'     => $this->bad,
                'message'    => 'Api key tidak ditemukan.',
                'data'       => null,
            ], REST_Controller::HTTP_BAD_REQUEST);
            return;
        }
    }

    public function update_pengurus_put()
    {
        $this->response([
            'status'    => $this->bad,
            'message'   => 'Bad Request',
            'data'      => null,
        ], REST_Controller::HTTP_BAD_REQUEST);
    }

    public function update_pengurus_delete()
    {
        $this->response([
            'status'    => $this->bad,
            'message'   => 'Bad Request',
            'data'      => null,
        ], REST_Controller::HTTP_BAD_REQUEST);
    }

    public function update_pengurus_get()
    {
        $this->response([
            'status'    => $this->bad,
            'message'   => 'Bad Request',
            'data'      => null,
        ], REST_Controller::HTTP_BAD_REQUEST);
    }

    public function add_pengurus_post()
    {
        $header = $this->input->request_headers();
        $header = array_change_key_case($header, CASE_LOWER);


        if (array_key_exists('tokenkey', $header) && !empty($header['tokenkey'])) {
            $token_key = $header['tokenkey'];

            $decoded_token = AUTHORIZATION::validateToken($token_key);
            if ($decoded_token != false && property_exists($decoded_token, 'id_user') && property_exists($decoded_token, 'id_group')) {
                if ($decoded_token->id_group != 1) {
                    $this->response([
                        'code'      => REST_Controller::HTTP_FORBIDDEN,
                        'message'   => 'Akses Ditolak, Silahkan Login Sebagai Admin',
                        'data'      => null,
                    ], REST_Controller::HTTP_FORBIDDEN);
                    return;
                } else {

                    // input validation 
                    $this->form_validation->set_rules('id_masjid', 'id_masjid', 'required');
                    $this->form_validation->set_rules('nama', 'nama', 'required');
                    $this->form_validation->set_error_delimiters('', '');
                    if ($this->form_validation->run() == false) {
                        $this->response([
                            'status' => REST_Controller::HTTP_BAD_REQUEST,
                            'message' => rtrim(validation_errors(), "\n"),
                            'data' => null,
                        ], REST_Controller::HTTP_BAD_REQUEST);
                        return;
                    }

                    $id_pengurus = getUUID();

                    if (isset($_FILES['url_foto']['name'])) {

                        $image = $_FILES['url_foto']['name'];
                        $path = "./public/uploads/pengurus/" . $id_pengurus;

                        if (!is_dir($path)) {
                            mkdir($path, 755);
                        }
                        $path_root = 'pengurus/' . $id_pengurus;
                        $upload = uploadImage('url_foto', $path_root, 'Pengurus');

                        if ($upload['success']) {
                            $image_name  = $upload['file_name'];
                        }
                    }
                    $pengurus = [
                        'id' => $id_pengurus,
                        'id_masjid' => $this->input->post('id_masjid'),
                        'nama' => $this->input->post('nama'),
                        'jenis_kelamin' => $this->input->post('jenis_kelamin'),
                        'no_hp' => $this->input->post('no_hp'),
                        'alamat' => $this->input->post('alamat'),
                        'tanggal_lahir' => $this->input->post('tanggal_lahir'),
                        'tempat_lahir' => $this->input->post('tempat_lahir'),
                        'email' => $this->input->post('email'),
                        'url_foto' => $image_name,
                        'kode_provinsi' => $this->input->post('kode_provinsi'),
                        'kode_kabupaten' => $this->input->post('kode_kabupaten'),
                        'kode_kecamatan' => $this->input->post('kode_kecamatan'),
                        'created_by' => $decoded_token->id_user,
                        'is_active' => 1
                        // butuh id user
                    ];

                    $password = $this->randomPassword();

                    $user = [
                        'username' => $pengurus['no_hp'],
                        'email' => $pengurus['no_hp'],
                        // change password to randomPassword function
                        'password' => hash('sha256', $password),
                        'real_name' => $pengurus['nama'],
                        'id_group' => 2,
                        'is_active' => 1
                    ];
                    $jamaah = $this->pengurus->add_pengurus($pengurus, $user);

                    if ($jamaah['success']) {
                        $this->response([
                            'status' => REST_Controller::HTTP_OK,
                            'message' => $jamaah['message'],
                            'data' => [
                                'username' => $pengurus['no_hp'],
                                'password' => $password
                            ],
                        ], REST_Controller::HTTP_OK);
                        return;
                    } else {
                        $this->response([
                            'status' => REST_Controller::HTTP_BAD_REQUEST,
                            'message' => $jamaah['message'],
                            'data' => null,
                        ], REST_Controller::HTTP_BAD_REQUEST);
                        return;
                    }
                }
            } else {
                $this->response([
                    'status'    => $this->unauthorized,
                    'message'   => 'Unathorized/Invalid Api key',
                    'data'      => null,
                ], REST_Controller::HTTP_UNAUTHORIZED);
                return;
            }
        } else {
            $this->response([
                'status'     => $this->bad,
                'message'    => 'Api key tidak ditemukan.',
                'data'       => null,
            ], REST_Controller::HTTP_BAD_REQUEST);
            return;
        }
    }

    public function add_pengurus_put()
    {
        $this->response([
            'status'    => $this->bad,
            'message'   => 'Bad Request',
            'data'      => null,
        ], REST_Controller::HTTP_BAD_REQUEST);
    }

    public function add_pengurus_delete()
    {
        $this->response([
            'status'    => $this->bad,
            'message'   => 'Bad Request',
            'data'      => null,
        ], REST_Controller::HTTP_BAD_REQUEST);
    }

    public function add_pengurus_get()
    {
        $this->response([
            'status'    => $this->bad,
            'message'   => 'Bad Request',
            'data'      => null,
        ], REST_Controller::HTTP_BAD_REQUEST);
    }

    public function randomPassword()
    {
        $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
        for ($i = 0; $i < 5; $i++) {
            $n = rand(0, strlen($alphabet) - 1);
            $pass[$i] = $alphabet[$n];
        }

        $password = implode($pass);


        return $password;
    }
}
