<?php

defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
// require APPPATH . '/libraries/Format.php';


use Restserver\Libraries\REST_Controller;

class Donasi extends REST_Controller
{

    private $ok = '200';
    private $bad = '400';
    private $unauthorized = '401';
    private $error = '500';

    function __construct()
    {
        parent::__construct();

        $this->methods['data_post']['limit'] = 100; // 100 requests per hour per data/key
        $this->load->model('api/api_donasi', 'donasi');
    }

    public function index_post()
    {
        $header = $this->input->request_headers();
        $header = array_change_key_case($header, CASE_LOWER);

        if (array_key_exists('tokenkey', $header) && !empty($header['tokenkey'])) {
            $token_key = $header['tokenkey'];

            $decoded_token = AUTHORIZATION::validateToken($token_key);
            if ($decoded_token != false && property_exists($decoded_token, 'id_user') && property_exists($decoded_token, 'id_group')) {

                $this->form_validation->set_rules('donasi_id', 'donasi_id', 'required');
                $this->form_validation->set_rules('metode_pembayaran_id', 'metode_pembayaran_id', 'required');
                $this->form_validation->set_rules('metode_pembayaran_type', 'metode_pembayaran_type', 'required');
                $this->form_validation->set_rules('nominal', 'nominal', 'required');
                $this->form_validation->set_rules('keterangan', 'keterangan', 'required');
                $this->form_validation->set_rules('is_anonim', 'is_anonim', 'required');
                $this->form_validation->set_error_delimiters('', '');
                if ($this->form_validation->run() == false) {
                    $this->response([
                        'status' => REST_Controller::HTTP_BAD_REQUEST,
                        'message' => rtrim(validation_errors(), "\n"),
                        'data' => null,
                    ], REST_Controller::HTTP_BAD_REQUEST);
                    return;
                }

                $date = new Datetime('now', new DateTimeZone('Asia/Jakarta'));
                $today = $date->format('Y-m-d');

                $donasi = [
                    'donasi_id' => $this->input->post('donasi_id'),
                    'metode_pembayaran_id' => $this->input->post('metode_pembayaran_id'),
                    'metode_pembayaran_type' => $this->input->post('metode_pembayaran_type'),
                    'nominal' => $this->input->post('nominal'),
                    'keterangan' => $this->input->post('keterangan'),
                    'is_anonim' => $this->input->post('is_anonim'),
                    'tanggal_donasi' => $today,
                    'created_by' => $decoded_token->id_user
                ];

                $bayar = $this->donasi->bayar_donasi($donasi);

                if ($bayar != null) {
                    $this->response([
                        'status' => REST_Controller::HTTP_OK,
                        'message' => "Berhasil memberikan donasi",
                        'data' => $bayar
                    ], REST_Controller::HTTP_OK);
                    return;
                } else {
                    $this->response([
                        'status' => REST_Controller::HTTP_BAD_REQUEST,
                        'message' => "Gagal memberikan donasi",
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

    public function index_get()
    {
        $header = $this->input->request_headers();
        $header = array_change_key_case($header, CASE_LOWER);

        if (array_key_exists('tokenkey', $header) && !empty($header['tokenkey'])) {
            $token_key = $header['tokenkey'];

            $decoded_token = AUTHORIZATION::validateToken($token_key);
            if ($decoded_token != false && property_exists($decoded_token, 'id_user') && property_exists($decoded_token, 'id_group')) {


                $donasi = $this->donasi->getDonasi();

                if ($donasi != null) {
                    $this->response([
                        'status' => REST_Controller::HTTP_OK,
                        'message' => "Berhasil mendapatkan donasi",
                        'data' => $donasi
                    ], REST_Controller::HTTP_OK);
                    return;
                } else {
                    $this->response([
                        'status' => REST_Controller::HTTP_BAD_REQUEST,
                        'message' => "Donasi Kosong",
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
}
