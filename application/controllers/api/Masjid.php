<?php

defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
// require APPPATH . '/libraries/Format.php';


use Restserver\Libraries\REST_Controller;

class Masjid extends REST_Controller
{

    private $ok = '200';
    private $bad = '400';
    private $unauthorized = '401';
    private $error = '500';

    function __construct()
    {
        parent::__construct();
        $this->methods['data_post']['limit'] = 100; // 100 requests per hour per data/key
        $this->load->model('api/api_masjid', 'masjid');
    }

    public function getMasjid_get()
    {
        $masjid = $this->masjid->get_masjid_active();
        $this->response([
            'code'      => Rest_Controller::HTTP_OK,
            'message'   => 'Berhasil Diambil',
            'data'      => $masjid,
        ], REST_Controller::HTTP_OK);
        return;
    }

    public function getMasjid_post()
    {
        $this->response([
            'status'    => $this->bad,
            'message'   => 'Bad Request',
            'data'      => null,
        ], REST_Controller::HTTP_BAD_REQUEST);
    }

    public function getMasjid_put()
    {
        $this->response([
            'status'    => $this->bad,
            'message'   => 'Bad Request',
            'data'      => null,
        ], REST_Controller::HTTP_BAD_REQUEST);
    }

    public function getMasjid_delete()
    {
        $this->response([
            'status'    => $this->bad,
            'message'   => 'Bad Request',
            'data'      => null,
        ], REST_Controller::HTTP_BAD_REQUEST);
    }

    public function index_get()
    {
        $masjid = $this->masjid->get_all();
        $this->response([
            'code'      => REST_Controller::HTTP_OK,
            'message'   => 'Berhasil Diambil',
            'data'      => $masjid,
        ], REST_Controller::HTTP_OK);
        return;
    }

    public function index_put()
    {
        $this->response([
            'status'    => $this->bad,
            'message'   => 'Bad Request',
            'data'      => null,
        ], REST_Controller::HTTP_BAD_REQUEST);
    }

    public function index_delete()
    {
        $this->response([
            'status'    => $this->bad,
            'message'   => 'Bad Request',
            'data'      => null,
        ], REST_Controller::HTTP_BAD_REQUEST);
    }

    public function profil_post()
    {
        $header = $this->input->request_headers();
        $header = array_change_key_case($header, CASE_LOWER);


        if (array_key_exists('tokenkey', $header) && !empty($header['tokenkey'])) {
            $token_key = $header['tokenkey'];

            $decoded_token = AUTHORIZATION::validateToken($token_key);
            if ($decoded_token != false && property_exists($decoded_token, 'id_user') && property_exists($decoded_token, 'id_group')) {

                $masjid = $this->masjid->profil($decoded_token->id_user);

                if ($masjid['success']) {
                    $this->response([
                        'code'      => REST_Controller::HTTP_OK,
                        'message'   => 'Masjid Ditemukan',
                        'data'      => $masjid['data'],
                    ], REST_Controller::HTTP_OK);
                    return;
                } else {
                    $this->response([
                        'code'      => REST_Controller::HTTP_NOT_FOUND,
                        'message'   => 'Masjid Tidak Ditemukan',
                        'data'      => $masjid['data'],
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

    public function profil_put()
    {
        $this->response([
            'status'    => $this->bad,
            'message'   => 'Bad Request',
            'data'      => null,
        ], REST_Controller::HTTP_BAD_REQUEST);
    }

    public function profil_delete()
    {
        $this->response([
            'status'    => $this->bad,
            'message'   => 'Bad Request',
            'data'      => null,
        ], REST_Controller::HTTP_BAD_REQUEST);
    }

    public function profil_get()
    {
        $this->response([
            'status'    => $this->bad,
            'message'   => 'Bad Request',
            'data'      => null,
        ], REST_Controller::HTTP_BAD_REQUEST);
    }

    public function updateProfil_post()
    {
        $header = $this->input->request_headers();
        $header = array_change_key_case($header, CASE_LOWER);


        if (array_key_exists('tokenkey', $header) && !empty($header['tokenkey'])) {
            $token_key = $header['tokenkey'];

            $decoded_token = AUTHORIZATION::validateToken($token_key);
            if ($decoded_token != false && property_exists($decoded_token, 'id_user') && property_exists($decoded_token, 'id_group')) {

                // get masjid profil
                $id_user = $decoded_token->id_user;
                $id_masjid = $this->masjid->getIdMasjid($id_user);
                $masjid = $this->masjid->profil($id_masjid['id']);

                // if no input, replace with profil
                (($this->input->post('nama') == NULL) ?  $nama = $masjid['data']['nama'] :  $nama = $this->input->post('nama'));
                (($this->input->post('longitude') == NULL) ?  $longitude = $masjid['data']['longitude'] :  $longitude = $this->input->post('longitude'));
                (($this->input->post('latitude') == NULL) ?  $latitude = $masjid['data']['latitude'] :  $latitude = $this->input->post('latitude'));
                (($this->input->post('alamat') == NULL) ?  $alamat = $masjid['data']['alamat'] :  $alamat = $this->input->post('alamat'));
                (($this->input->post('kode_provinsi') == NULL) ?  $kode_provinsi = $masjid['data']['kode_provinsi'] :  $kode_provinsi = $this->input->post('kode_provinsi'));
                (($this->input->post('kode_kabupaten') == NULL) ?  $kode_kabupaten = $masjid['data']['kode_kabupaten'] :  $kode_kabupaten = $this->input->post('kode_kabupaten'));
                (($this->input->post('kode_kecamatan') == NULL) ?  $kode_kecamatan = $masjid['data']['kode_kecamatan'] :  $kode_kecamatan = $this->input->post('kode_kecamatan'));
                (($this->input->post('no_telp') == NULL) ?  $no_telp = $masjid['data']['no_telp'] :  $no_telp = $this->input->post('no_telp'));
                (($this->input->post('alamat_website') == NULL) ?  $alamat_website = $masjid['data']['alamat_website'] :  $alamat_website = $this->input->post('alamat_website'));
                (($this->input->post('email') == NULL) ?  $email = $masjid['data']['email'] :  $email = $this->input->post('email'));
                (($this->input->post('status') == NULL) ?  $status = $masjid['data']['status'] :  $status = $this->input->post('status'));

                $data = [
                    'nama' => $nama,
                    'longitude' => $longitude,
                    'latitude' => $latitude,
                    'alamat' => $alamat,
                    'kode_provinsi' => $kode_provinsi,
                    'kode_kabupaten' => $kode_kabupaten,
                    'kode_kecamatan' => $kode_kecamatan,
                    'no_telp' => $no_telp,
                    'alamat_website' => $alamat_website,
                    'email' => $email,
                    'status' => $status,
                ];

                $profil = $this->masjid->update_profil($data, $id_masjid);

                if ($profil) {
                    $this->response([
                        'code'      => REST_Controller::HTTP_OK,
                        'message'   => 'Masjid Berhasil Di Update',
                        'data'      => null,
                    ], REST_Controller::HTTP_OK);
                    return;
                } else {
                    $this->response([
                        'code'      => REST_Controller::HTTP_BAD_REQUEST,
                        'message'   => 'Masjid Gagal Di Update',
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

    public function updateProfil_put()
    {
        $this->response([
            'status'    => $this->bad,
            'message'   => 'Bad Request',
            'data'      => null,
        ], REST_Controller::HTTP_BAD_REQUEST);
    }

    public function updateProfil_delete()
    {
        $this->response([
            'status'    => $this->bad,
            'message'   => 'Bad Request',
            'data'      => null,
        ], REST_Controller::HTTP_BAD_REQUEST);
    }

    public function updateProfil_get()
    {
        $this->response([
            'status'    => $this->bad,
            'message'   => 'Bad Request',
            'data'      => null,
        ], REST_Controller::HTTP_BAD_REQUEST);
    }

    public function index_post()
    {
        $data = json_decode(trim(file_get_contents('php://input')), true);

        $checked = filterFields($data);

        if ($checked['status'] == 500) {
            $this->response([
                'status'     => REST_Controller::HTTP_BAD_REQUEST,
                'message'    => $checked['message'],
                'data'       => 0,
            ], REST_Controller::HTTP_BAD_REQUEST);
        } else {
            $result = $this->masjid->register($data);

            if ($result['status'] == 500) {
                $this->response([
                    'status'     => REST_Controller::HTTP_INTERNAL_SERVER_ERROR,
                    'message'    => $result['message'],
                    'data'       => 0,
                ], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
            } else {
                $this->response([
                    'status'     => REST_Controller::HTTP_OK,
                    'message'    => $result['message'],
                    'data'       => $result['token_key'],
                ], REST_Controller::HTTP_OK);
            }

        }
    }
}
