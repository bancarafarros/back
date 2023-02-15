<?php

defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
// require APPPATH . '/libraries/Format.php';


use Restserver\Libraries\REST_Controller;

class Jamaah extends REST_Controller
{

    private $ok = '200';
    private $bad = '400';
    private $unauthorized = '401';
    private $error = '500';

    function __construct()
    {
        parent::__construct();
        $this->methods['data_post']['limit'] = 100; // 100 requests per hour per data/key
        $this->load->model('api/api_auth', 'auth');
        $this->load->model('api/api_jamaah', 'jamaah');
    }

    public function delete_jamaah_delete()
    {
        $header = $this->input->request_headers();
        $header = array_change_key_case($header, CASE_LOWER);


        if (array_key_exists('tokenkey', $header) && !empty($header['tokenkey'])) {
            $token_key = $header['tokenkey'];

            $decoded_token = AUTHORIZATION::validateToken($token_key);
            if ($decoded_token != false && property_exists($decoded_token, 'id_user') && property_exists($decoded_token, 'id_group')) {

                $id_user = $decoded_token->id_user;

                $delete = $this->jamaah->delete_jamaah($id_user);

                if ($delete) {
                    $this->response([
                        'status' => REST_Controller::HTTP_OK,
                        'message' => "Jamaah Berhasil Dihapus",
                        'data' => null
                    ], REST_Controller::HTTP_OK);
                    return;
                } else {
                    $this->response([
                        'status' => REST_Controller::HTTP_BAD_REQUEST,
                        'message' => "Jamaah Gagal Dihapus",
                        'data' => null
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

    public function delete_jamaah_get()
    {
        $this->response([
            'status'    => $this->bad,
            'message'   => 'Bad Request',
            'data'      => null,
        ], REST_Controller::HTTP_BAD_REQUEST);
    }

    public function delete_jamaah_post()
    {
        $this->response([
            'status'    => $this->bad,
            'message'   => 'Bad Request',
            'data'      => null,
        ], REST_Controller::HTTP_BAD_REQUEST);
    }

    public function delete_jamaah_put()
    {
        $this->response([
            'status'    => $this->bad,
            'message'   => 'Bad Request',
            'data'      => null,
        ], REST_Controller::HTTP_BAD_REQUEST);
    }

    public function update_jamaah_post()
    {
        $header = $this->input->request_headers();
        $header = array_change_key_case($header, CASE_LOWER);


        if (array_key_exists('tokenkey', $header) && !empty($header['tokenkey'])) {
            $token_key = $header['tokenkey'];

            $decoded_token = AUTHORIZATION::validateToken($token_key);
            if ($decoded_token != false && property_exists($decoded_token, 'id_user') && property_exists($decoded_token, 'id_group')) {

                $id_user = $decoded_token->id_user;

                $jamaah = $this->jamaah->get_jamaah($id_user);

                (($this->input->post('id_masjid') == NULL) ?  $id_masjid = $jamaah['data']['id_masjid'] :  $id_masjid = $this->input->post('id_masjid'));
                (($this->input->post('nama') == NULL) ?  $nama = $jamaah['data']['nama'] :  $nama = $this->input->post('nama'));
                (($this->input->post('jenis_kelamin') == NULL) ?  $jenis_kelamin = $jamaah['data']['jenis_kelamin'] :  $jenis_kelamin = $this->input->post('jenis_kelamin'));
                (($this->input->post('no_hp') == NULL) ?  $no_hp = $jamaah['data']['no_hp'] :  $no_hp = $this->input->post('no_hp'));
                (($this->input->post('alamat') == NULL) ?  $alamat = $jamaah['data']['alamat'] :  $alamat = $this->input->post('alamat'));
                (($this->input->post('tanggal_lahir') == NULL) ?  $tanggal_lahir = $jamaah['data']['tanggal_lahir'] :  $tanggal_lahir = $this->input->post('tanggal_lahir'));
                (($this->input->post('tempat_lahir') == NULL) ?  $tempat_lahir = $jamaah['data']['tempat_lahir'] :  $tempat_lahir = $this->input->post('tempat_lahir'));
                (($this->input->post('url_foto') == NULL) ?  $url_foto = $jamaah['data']['url_foto'] :  $url_foto = '');
                (($this->input->post('kode_provinsi') == NULL) ?  $kode_provinsi = $jamaah['data']['kode_provinsi'] :  $kode_provinsi = $this->input->post('kode_provinsi'));
                (($this->input->post('kode_kabupaten') == NULL) ?  $kode_kabupaten = $jamaah['data']['kode_kabupaten'] :  $kode_kabupaten = $this->input->post('kode_kabupaten'));
                (($this->input->post('kode_kecamatan') == NULL) ?  $kode_kecamatan = $jamaah['data']['kode_kecamatan'] :  $kode_kecamatan = $this->input->post('kode_kecamatan'));
                (($this->input->post('is_active') == NULL) ?  $is_active = $jamaah['data']['is_active'] :  $is_active = $this->input->post('is_active'));


                if (isset($_FILES['url_foto']['name'])) {

                    if (file_exists($jamaah['data']['url_foto'])) {
                        unlink($jamaah['data']['url_foto']);
                    }

                    $path_root = 'jamaah/' . $jamaah['data']['id'];

                    $path = "./public/uploads/jamaah/" . $jamaah['data']['id'];
                    if (!is_dir($path)) {
                        mkdir($path, 0777);
                    }

                    $upload = uploadImage('url_foto', $path_root, 'jamaah');

                    if ($upload['success']) {
                        $url_foto = $upload['file_name'];
                    }
                }

                $data = [
                    'id_masjid' => $id_masjid,
                    'nama' => $nama,
                    'jenis_kelamin' => $jenis_kelamin,
                    'no_hp' => $no_hp,
                    'alamat' => $alamat,
                    'tanggal_lahir' => $tanggal_lahir,
                    'tempat_lahir' => $tempat_lahir,
                    'url_foto' => $url_foto,
                    'kode_provinsi' => $kode_provinsi,
                    'kode_kabupaten' => $kode_kabupaten,
                    'kode_kecamatan' => $kode_kecamatan,
                    'is_active' => $is_active,
                ];

                $update = $this->jamaah->update_jamaah($id_user, $data);

                if ($update) {
                    $this->response([
                        'status' => REST_Controller::HTTP_OK,
                        'message' => "Data Jamaah Berhasil Diupdate",
                        'data' => null,
                    ], REST_Controller::HTTP_OK);
                    return;
                } else {
                    $this->response([
                        'status' => REST_Controller::HTTP_BAD_REQUEST,
                        'message' => "Data Jamaah Gagal Diupdate",
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

    public function update_jamaah_put()
    {
        $this->response([
            'status'    => $this->bad,
            'message'   => 'Bad Request',
            'data'      => null,
        ], REST_Controller::HTTP_BAD_REQUEST);
    }

    public function update_jamaah_delete()
    {
        $this->response([
            'status'    => $this->bad,
            'message'   => 'Bad Request',
            'data'      => null,
        ], REST_Controller::HTTP_BAD_REQUEST);
    }

    public function update_jamaah_get()
    {
        $this->response([
            'status'    => $this->bad,
            'message'   => 'Bad Request',
            'data'      => null,
        ], REST_Controller::HTTP_BAD_REQUEST);
    }

    public function get_jamaah_get()
    {
        $header = $this->input->request_headers();
        $header = array_change_key_case($header, CASE_LOWER);


        if (array_key_exists('tokenkey', $header) && !empty($header['tokenkey'])) {
            $token_key = $header['tokenkey'];

            $decoded_token = AUTHORIZATION::validateToken($token_key);
            if ($decoded_token != false && property_exists($decoded_token, 'id_user') && property_exists($decoded_token, 'id_group')) {

                $id_user = $decoded_token->id_user;

                $jamaah = $this->jamaah->get_jamaah($id_user);

                if ($jamaah['success']) {
                    $this->response([
                        'status' => REST_Controller::HTTP_OK,
                        'message' => "Jamaah Ditemukan",
                        'data' => $jamaah['data'],
                    ], REST_Controller::HTTP_OK);
                    return;
                } else {
                    $this->response([
                        'status' => REST_Controller::HTTP_NOT_FOUND,
                        'message' => "Jamaah Tidak Ditemukan",
                        'data' => null,
                    ], REST_Controller::HTTP_NOT_FOUND);
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

    public function get_jamaah_by_masjid_get()
    {
        $header = $this->input->request_headers();
        $header = array_change_key_case($header, CASE_LOWER);


        if (array_key_exists('tokenkey', $header) && !empty($header['tokenkey'])) {
            $token_key = $header['tokenkey'];

            $decoded_token = AUTHORIZATION::validateToken($token_key);
            if ($decoded_token != false && property_exists($decoded_token, 'id_user') && property_exists($decoded_token, 'id_group')) {

                $id_masjid = $this->input->get('id_masjid');
                if (!isset($id_masjid) || $id_masjid == null) {
                    $this->response([
                        'status'     => $this->bad,
                        'message'    => 'Harap masukkan id masjid.',
                        'data'       => null,
                    ], REST_Controller::HTTP_BAD_REQUEST);
                    return;
                }
                $jamaah = $this->jamaah->get_jamaah_by_masjid($id_masjid);

                if ($jamaah['success']) {
                    $this->response([
                        'status' => REST_Controller::HTTP_OK,
                        'message' => "Jamaah Ditemukan",
                        'data' => $jamaah['data'],
                    ], REST_Controller::HTTP_OK);
                    return;
                } else {
                    $this->response([
                        'status' => REST_Controller::HTTP_NOT_FOUND,
                        'message' => "Jamaah Tidak Ditemukan",
                        'data' => null,
                    ], REST_Controller::HTTP_NOT_FOUND);
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

    public function get_jamaah_post()
    {
        $this->response([
            'status'    => $this->bad,
            'message'   => 'Bad Request',
            'data'      => null,
        ], REST_Controller::HTTP_BAD_REQUEST);
    }

    public function get_jamaah_put()
    {
        $this->response([
            'status'    => $this->bad,
            'message'   => 'Bad Request',
            'data'      => null,
        ], REST_Controller::HTTP_BAD_REQUEST);
    }

    public function get_jamaah_delete()
    {
        $this->response([
            'status'    => $this->bad,
            'message'   => 'Bad Request',
            'data'      => null,
        ], REST_Controller::HTTP_BAD_REQUEST);
    }

    public function add_jamaah_post()
    {
        $this->form_validation->set_rules('nama', 'nama', 'required');
        $this->form_validation->set_rules('alamat', 'alamat', 'required');
        $this->form_validation->set_rules('email', 'email', 'required');
        $this->form_validation->set_rules('no_hp', 'no_hp', 'required');
        $this->form_validation->set_rules('password', 'password', 'required');
        $this->form_validation->set_rules('confirm_password', 'confirm_password', 'required|matches[password]');

        $this->form_validation->set_error_delimiters('', '');
        if ($this->form_validation->run() == false) {
            $this->response([
                'status' => REST_Controller::HTTP_BAD_REQUEST,
                'message' => rtrim(validation_errors(), "\n"),
                'data' => null,
            ], REST_Controller::HTTP_BAD_REQUEST);
            return;
        }

        $id_jamaah = getUUID();

        $jamaah = [
            'id' => $id_jamaah,
            'id_masjid' => $this->input->post('id_masjid'),
            'nama' => $this->input->post('nama'),
            'email' => $this->input->post('email'),
            'jenis_kelamin' => $this->input->post('jenis_kelamin'),
            'no_hp' => $this->input->post('no_hp'),
            'alamat' => $this->input->post('alamat'),
            'tanggal_lahir' => $this->input->post('tanggal_lahir'),
            'tempat_lahir' => $this->input->post('tempat_lahir'),
            'kode_provinsi' => $this->input->post('kode_provinsi'),
            'kode_kabupaten' => $this->input->post('kode_kabupaten'),
            'kode_kecamatan' => $this->input->post('kode_kecamatan'),
            'is_active' => 1
            // butuh id user
        ];

        // jika upload image
        if (isset($_FILES['url_foto']['name'])) {

            $image = $_FILES['url_foto']['name'];
            $path = "./public/uploads/jamaah/" . $id_jamaah;

            if (!is_dir($path)) {
                mkdir($path, 755);
            }
            $path_root = 'jamaah/' . $id_jamaah;
            $upload = uploadImage('url_foto', $path_root, 'jamaah');

            if ($upload['success']) {
                $image_name  = $upload['file_name'];
                $jamaah['url_foto'] = $image_name;
            }
        }

        $user = [
            'username' => $jamaah['email'],
            'email' => $jamaah['email'],
            'password' => hash('sha256', $this->input->post('password')),
            'real_name' => $jamaah['nama'],
            'id_group' => 3,
            'is_active' => 1
        ];

        $otp = substr(rand(), 0, 4);
        $jkt = new DateTimeZone('Asia/Jakarta');
        $tomorrow = new DateTime('now', $jkt);
        $tomorrow->modify('+1 minutes');
        $expired = $tomorrow->format('Y-m-d H:i:s');

        $jamaah = $this->auth->daftar_jamaah($jamaah, $user);

        if ($jamaah['success']) {
            $id_user = $this->auth->getIdByEmail($user['email']);
            $token  = [
                'id' => getUUID(),
                'user_id' => $id_user,
                'token_key' => $otp,
                'expired_token' => $expired
            ];

            $this->auth->resetToken($token);

            $this->response([
                'status' => REST_Controller::HTTP_OK,
                'message' => 'Data berhasil diterima, silahkan cek otp yang dikirimkan ke email anda!',
                'data' => [
                    'otp' => $otp
                ],
            ], REST_Controller::HTTP_OK);
            // return;
            // $this->response([
            //     'status' => REST_Controller::HTTP_OK,
            //     'message' => $jamaah['message'],
            //     'data' => null,
            // ], REST_Controller::HTTP_OK);
            // return;
        } else {
            $this->response([
                'status' => REST_Controller::HTTP_BAD_REQUEST,
                'message' => $jamaah['message'],
                'data' => null,
            ], REST_Controller::HTTP_BAD_REQUEST);
            return;
        }
    }

    public function add_jamaah_put()
    {
        $this->response([
            'status'    => $this->bad,
            'message'   => 'Bad Request',
            'data'      => null,
        ], REST_Controller::HTTP_BAD_REQUEST);
    }

    public function add_jamaah_get()
    {
        $this->response([
            'status'    => $this->bad,
            'message'   => 'Bad Request',
            'data'      => null,
        ], REST_Controller::HTTP_BAD_REQUEST);
    }

    public function add_jamaah_delete()
    {
        $this->response([
            'status'    => $this->bad,
            'message'   => 'Bad Request',
            'data'      => null,
        ], REST_Controller::HTTP_BAD_REQUEST);
    }
}
