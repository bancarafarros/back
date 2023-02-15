<?php

defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
// require APPPATH . '/libraries/Format.php';


use Restserver\Libraries\REST_Controller;

class Berita extends REST_Controller
{

    private $ok = '200';
    private $bad = '400';
    private $unauthorized = '401';
    private $error = '500';

    function __construct()
    {
        parent::__construct();

        $this->methods['data_post']['limit'] = 100; // 100 requests per hour per data/key
        $this->load->model('api/api_berita', 'berita');
    }

    public function berita_get()
    {
        $header = $this->input->request_headers();
        $header = array_change_key_case($header, CASE_LOWER);

        if (array_key_exists('tokenkey', $header) && !empty($header['tokenkey'])) {
            $token_key = $header['tokenkey'];

            $decoded_token = AUTHORIZATION::validateToken($token_key);
            if ($decoded_token != false && property_exists($decoded_token, 'id_user') && property_exists($decoded_token, 'id_group')) {

                $berita = $this->berita->getBerita();

                if ($berita != null) {
                    $this->response([
                        'status' => REST_Controller::HTTP_OK,
                        'message' => "Berhasil mendapatkan berita",
                        'data' => $berita
                    ], REST_Controller::HTTP_OK);
                    return;
                } else {
                    $this->response([
                        'status' => REST_Controller::HTTP_BAD_REQUEST,
                        'message' => "Berita Kosong",
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

    public function add_berita_post()
    {
        $header = $this->input->request_headers();
        $header = array_change_key_case($header, CASE_LOWER);

        if (array_key_exists('tokenkey', $header) && !empty($header['tokenkey'])) {
            $token_key = $header['tokenkey'];

            $decoded_token = AUTHORIZATION::validateToken($token_key);
            if ($decoded_token != false && property_exists($decoded_token, 'id_user') && property_exists($decoded_token, 'id_group')) {

                $this->form_validation->set_rules('judul', 'judul', 'required');
                $this->form_validation->set_rules('deskripsi', 'deskripsi', 'required');

                $this->form_validation->set_error_delimiters('', '');
                if ($this->form_validation->run() == false) {
                    $this->response([
                        'status' => REST_Controller::HTTP_BAD_REQUEST,
                        'message' => rtrim(validation_errors(), "\n"),
                        'data' => null,
                    ], REST_Controller::HTTP_BAD_REQUEST);
                    return;
                }


                $image_name = '';
                if (isset($_FILES['image_berita']['name'])) {

                    $image = $_FILES['image_berita']['name'];
                    $path = "./public/uploads/berita";
                    if (!is_dir($path)) {
                        mkdir($path, 755);
                    }
                    $path_root = 'berita/';
                    $upload = uploadImage('image_berita', $path_root, 'berita');

                    if ($upload['success']) {
                        $image_name  = $upload['file_name'];
                    }
                }

                $slug = str_replace(' ', '-', $this->input->post('judul'));
                $isSlugExist = $this->berita->isSlugExist($this->input->post('judul'));
                if ($isSlugExist != null) {
                    for ($i = 0; $i < $isSlugExist; $i++) {
                        $slug = str_replace(' ', '-', $this->input->post('judul')) . "-$i";
                    }
                }

                $data = [
                    'judul' => $this->input->post('judul'),
                    'info_berita' => $this->input->post('deskripsi'),
                    'image_berita' => $image_name,
                    'slug' => $slug,
                    'id_user' => $decoded_token->id_user
                ];

                $berita = $this->berita->addBerita($data);
                if ($berita) {
                    $this->response([
                        'status' => REST_Controller::HTTP_OK,
                        'message' => "Berhasil menambahkan berita",
                        'data' => $berita
                    ], REST_Controller::HTTP_OK);
                    return;
                } else {
                    $this->response([
                        'status' => REST_Controller::HTTP_BAD_REQUEST,
                        'message' => "Gagal menambahkan berita",
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

    public function update_berita_post()
    {
        $header = $this->input->request_headers();
        $header = array_change_key_case($header, CASE_LOWER);

        if (array_key_exists('tokenkey', $header) && !empty($header['tokenkey'])) {
            $token_key = $header['tokenkey'];

            $decoded_token = AUTHORIZATION::validateToken($token_key);
            if ($decoded_token != false && property_exists($decoded_token, 'id_user') && property_exists($decoded_token, 'id_group')) {

                $this->form_validation->set_rules('id_berita', 'id_berita', 'required');
                $this->form_validation->set_error_delimiters('', '');
                if ($this->form_validation->run() == false) {
                    $this->response([
                        'status' => REST_Controller::HTTP_BAD_REQUEST,
                        'message' => rtrim(validation_errors(), "\n"),
                        'data' => null,
                    ], REST_Controller::HTTP_BAD_REQUEST);
                    return;
                }

                $id_berita = $this->input->post('id_berita');
                $berita = $this->berita->getBeritaById($id_berita);

                if ($berita == null) {
                    $this->response([
                        'status' => REST_Controller::HTTP_BAD_REQUEST,
                        'message' => "Berita tidak ditemukan",
                        'data' => $berita
                    ], REST_Controller::HTTP_BAD_REQUEST);
                    return;
                }

                (($this->input->post('judul') == NULL) ?  $judul = $berita['judul'] :  $judul = $this->input->post('judul'));
                (($this->input->post('deskripsi') == NULL) ?  $deskripsi = $berita['info_berita'] :  $deskripsi = $this->input->post('deskripsi'));
                (($this->input->post('image_berita') == NULL) ?  $image_berita = $berita['image_berita'] :  $image_berita = '');
                (($this->input->post('is_dummy') == NULL) ?  $is_dummy = $berita['is_dummy'] :  $is_dummy = $this->input->post('is_dummy'));
                (($this->input->post('is_active') == NULL) ?  $is_active = $berita['is_active'] :  $is_active = $this->input->post('is_active'));

                $slug = $berita['slug'];
                if ($this->input->post('judul') != NULL) {
                    $isSlugExist = $this->berita->isSlugExist($this->input->post('judul'));
                    if ($isSlugExist != null) {
                        for ($i = 0; $i < $isSlugExist; $i++) {
                            $slug = str_replace(' ', '-', $this->input->post('judul')) . "-$i";
                        }
                    } else {
                        $slug = str_replace(' ', '-', $this->input->post('judul'));
                    }
                }

                if (isset($_FILES['image_berita']['name'])) {

                    $image = $_FILES['image_berita']['name'];
                    $path = "./public/uploads/berita";
                    if (!is_dir($path)) {
                        mkdir($path, 755);
                    }
                    $path_root = 'berita/';
                    $upload = uploadImage('image_berita', $path_root, 'berita');

                    if ($upload['success']) {
                        $image_berita  = $upload['file_name'];
                    }
                }

                $data = [
                    'judul' => $judul,
                    'info_berita' => $deskripsi,
                    'image_berita' => $image_berita,
                    'slug' => $slug,
                    'is_dummy' => $is_dummy,
                    'is_active' => $is_active
                ];

                $update = $this->berita->update_berita($id_berita, $data);

                if ($update) {
                    $this->response([
                        'status'    => REST_Controller::HTTP_OK,
                        'message'   => 'Berhasil Update Berita',
                        'data'      => null,
                    ], REST_Controller::HTTP_OK);
                    return;
                } else {
                    $this->response([
                        'status'    => REST_Controller::HTTP_BAD_REQUEST,
                        'message'   => 'Gagal Update Berita',
                        'data'      => null,
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

    public function delete_berita_post()
    {
        $header = $this->input->request_headers();
        $header = array_change_key_case($header, CASE_LOWER);

        if (array_key_exists('tokenkey', $header) && !empty($header['tokenkey'])) {
            $token_key = $header['tokenkey'];

            $decoded_token = AUTHORIZATION::validateToken($token_key);
            if ($decoded_token != false && property_exists($decoded_token, 'id_user') && property_exists($decoded_token, 'id_group')) {

                $this->form_validation->set_rules('id_berita', 'id_berita', 'required');
                $this->form_validation->set_error_delimiters('', '');
                if ($this->form_validation->run() == false) {
                    $this->response([
                        'status' => REST_Controller::HTTP_BAD_REQUEST,
                        'message' => rtrim(validation_errors(), "\n"),
                        'data' => null,
                    ], REST_Controller::HTTP_BAD_REQUEST);
                    return;
                }

                $id_berita = $this->input->post('id_berita');

                $delete = $this->berita->deleteBerita($id_berita);
                if ($delete) {
                    $this->response([
                        'status'    => REST_Controller::HTTP_OK,
                        'message'   => 'Berhasil delete berita',
                        'data'      => null,
                    ], REST_Controller::HTTP_OK);
                    return;
                } else {
                    $this->response([
                        'status'    => REST_Controller::HTTP_BAD_REQUEST,
                        'message'   => 'Gagal delete berita',
                        'data'      => null,
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
}
