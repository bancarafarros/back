<?php

defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
// require APPPATH . '/libraries/Format.php';

use Restserver\Libraries\REST_Controller;

class Profile extends REST_Controller
{

    private $ok = '200';
    private $bad = '400';
    private $unauthorized = '401';
    private $error = '500';

    function __construct()
    {
        parent::__construct();

        $this->methods['data_post']['limit'] = 100; // 100 requests per hour per data/key
        $this->load->model('api/api_profile', 'profile');
    }

    public function detail_get()
    {
        $header = $this->input->request_headers();
        $header = array_change_key_case($header, CASE_LOWER);

        if (array_key_exists('api_key', $header) && !empty($header['api_key'])) {
            $token_key = $header['api_key'];
            $decoded_token = AUTHORIZATION::validateToken($token_key);
            if ($decoded_token != false && property_exists($decoded_token, 'id_user') && property_exists($decoded_token, 'id_group')) {
                $id = $decoded_token->id_user;
                $id_group = $decoded_token->id_group;

                if ($id_group == "2") {
                    $result = $this->profile->getProfile($id);
                } else {
                    $result = $this->profile->getProfile2($id);
                }
                if ($result) {
                    $this->response([
                        'code'      => REST_Controller::HTTP_OK,
                        'message'   => 'Berhasil Diambil',
                        'data'      => $result,
                    ], REST_Controller::HTTP_OK);
                } else {
                    $this->response([
                        'code'      =>  REST_Controller::HTTP_BAD_REQUEST,
                        'message'   => 'Data Tidak Ada',
                        'data'      => $result,
                    ], REST_Controller::HTTP_BAD_REQUEST);
                }
            } else {
                $this->response([
                    'status'    => $this->unauthorized,
                    'message'   => 'Unathorized/Invalid Api key',
                    'data'      => null,
                ], REST_Controller::HTTP_UNAUTHORIZED);
            }
        } else {
            $this->response([
                'status'     => $this->bad,
                'message'    => 'Api key tidak ditemukan.',
                'data'       => null,
            ], REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    function update_post()
    {
        $header = $this->input->request_headers();
        $header = array_change_key_case($header, CASE_LOWER);

        if (array_key_exists('api_key', $header) && !empty($header['api_key'])) {
            $token_key = $header['api_key'];
            $decoded_token = AUTHORIZATION::validateToken($token_key);
            if ($decoded_token != false && property_exists($decoded_token, 'id_user') && property_exists($decoded_token, 'id_group')) {
                $id = $decoded_token->id_user;
                $id_group = $decoded_token->id_group;

                if ($id_group == "2") {
                    $modul = 'peserta';
                    $tabel = 'peserta';
                    $foto = 'url_photo';
                } else {
                    $modul = 'mentor';
                    $tabel = 'mentor';
                    $foto = 'url_foto';
                }

                $images = $_FILES['url_photo']['name'];
                $path = "./public/uploads/" . $modul . "/" . $id;


                if (!is_dir($path)) {
                    mkdir($path, 0777);
                }

                if (!$images) {
                    $this->response([
                        'status' => REST_Controller::HTTP_BAD_REQUEST,
                        'message' => 'Tidak ada foto yang dimasukkan, periksa data anda',
                        'data' => null
                    ], REST_Controller::HTTP_BAD_REQUEST);
                    return;
                } else {
                    $config['upload_path']          = $path;
                    $config['allowed_types']        = 'gif|jpg|png';
                    $config['max_size']             = 2048;

                    $this->load->library('upload', $config);
                }

                if ($this->upload->do_upload('url_photo')) {

                    $old_foto = $this->profile->getPhoto($id, $foto, $tabel);
                    if ($old_foto != null) {
                        unlink("./public/uploads/" . $modul . "/" . $id . "/" . $old_foto);
                    }

                    $images = $this->upload->data('file_name');
                }

                if ($id_group == "2") {
                    $data = array(
                        'nama_lengkap' => $this->post('nama_lengkap'),
                        'tempat_lahir' => $this->post('tempat_lahir'),
                        'tanggal_lahir' => $this->post('tanggal_lahir'),
                        'jenis_kelamin' => $this->post('jenis_kelamin'),
                        'kode_provinsi' => $this->post('provinsi'),
                        'kode_kabupaten' => $this->post('kabupaten'),
                        'kode_kecamatan' => $this->post('kecamatan'),
                        'alamat' => $this->post('alamat'),
                        'email' => $this->post('email'),
                        'id_posisi' => $this->post('posisi'),
                        'url_portofolio' => 'url_portofolio',
                        'harapan' => 'harapan',
                        'url_photo' => $images,
                    );
                    $update = $this->profile->ubahProfile($id, $data, 'peserta');
                } else {
                    $data = array(
                        'nama_lengkap' => $this->post('nama_lengkap'),
                        'jenis_kelamin' => $this->post('jenis_kelamin'),
                        'kode_provinsi' => $this->post('provinsi'),
                        'kode_kabupaten' => $this->post('kabupaten'),
                        'kode_kecamatan' => $this->post('kecamatan'),
                        'alamat' => $this->post('alamat'),
                        'email' => $this->post('email'),
                        'id_posisi' => $this->post('posisi'),
                        'url_foto' => $images,
                    );
                    $update = $this->profile->ubahProfile($id, $data, 'mentor');
                }


                $keluaran = array(
                    'nama_lengkap' => $this->post('nama_lengkap'),
                    'nomor_identitas' => $this->post('nomor_identitas'),
                    'email' => $this->post('email'),
                );
                if ($update) {
                    $this->response([
                        'code'      => REST_Controller::HTTP_OK,
                        'message'   => 'Berhasil Diupdate',
                        'data'      => $keluaran,
                    ], REST_Controller::HTTP_OK);
                } else {
                    $this->response([
                        'code'      =>  REST_Controller::HTTP_BAD_REQUEST,
                        'message'   => 'Tidak dapat Diupdate',
                        'data'      => null,
                    ], REST_Controller::HTTP_BAD_REQUEST);
                }
            } else {
                $this->response([
                    'status'    => $this->unauthorized,
                    'message'   => 'Unathorized/Invalid Api key',
                    'data'      => null,
                ], REST_Controller::HTTP_UNAUTHORIZED);
            }
        } else {
            $this->response([
                'status'     => $this->bad,
                'message'    => 'Api key tidak ditemukan.',
                'data'       => null,
            ], REST_Controller::HTTP_BAD_REQUEST);
        }
    }
}
