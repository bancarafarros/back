<?php

defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
// require APPPATH . '/libraries/Format.php';


use Restserver\Libraries\REST_Controller;

class Kas extends REST_Controller
{

    private $ok = '200';
    private $bad = '400';
    private $unauthorized = '401';
    private $error = '500';

    function __construct()
    {
        parent::__construct();
        $this->methods['data_post']['limit'] = 100; // 100 requests per hour per data/key
        $this->load->model('api/api_kas', 'kas');
    }

    public function add_kas_post()
    {
        $header = $this->input->request_headers();
        $header = array_change_key_case($header, CASE_LOWER);


        if (array_key_exists('tokenkey', $header) && !empty($header['tokenkey'])) {
            $token_key = $header['tokenkey'];

            $decoded_token = AUTHORIZATION::validateToken($token_key);
            if ($decoded_token != false && property_exists($decoded_token, 'id_user') && property_exists($decoded_token, 'id_group')) {

                if ($decoded_token->id_group != 2) {
                    $this->response([
                        'code'      => REST_Controller::HTTP_FORBIDDEN,
                        'message'   => 'Akses Ditolak',
                        'data'      => null,
                    ], REST_Controller::HTTP_FORBIDDEN);
                    return;
                }
                // set rules for the input
                $this->form_validation->set_rules('id_masjid', 'id_masjid', 'required');
                $this->form_validation->set_rules('nominal', 'nominal', 'required');
                $this->form_validation->set_rules('tanggal', 'tanggal', 'required');
                $this->form_validation->set_rules('tipe_transaksi', 'tipe_transaksi', 'required');

                $this->form_validation->set_error_delimiters('', '');
                if ($this->form_validation->run() == false) {
                    $this->response([
                        'code'      => REST_Controller::HTTP_BAD_REQUEST,
                        'message'   => rtrim(validation_errors(), "\n"),
                        'data'      => null,
                    ], REST_Controller::HTTP_BAD_REQUEST);
                    return;
                }

                $id_masjid = $this->input->post('id_masjid');

                $masjid = $this->kas->isMasjidExist($id_masjid);

                if ($masjid) {
                    $this->response([
                        'code'      => REST_Controller::HTTP_BAD_REQUEST,
                        'message'   => 'Kas Ditolak, Masjid Sudah Mempunyai Kas',
                        'data'      => null,
                    ], REST_Controller::HTTP_BAD_REQUEST);
                    return;
                }

                $id_kas = getUUID();

                $kas = [
                    'id' => $id_kas,
                    'id_masjid' => $this->input->post('id_masjid'),
                    'nominal' => $this->input->post('nominal'),
                    'created_by' => $decoded_token->id_user
                ];

                $kas_log = [
                    'id_kas' => $id_kas,
                    'id_masjid' => $this->input->post('id_masjid'),
                    'jumlah' => $this->input->post('nominal'),
                    'tanggal' => $this->input->post('tanggal'),
                    'keterangan' => $this->input->post('keterangan'),
                    'url_bukti' => $this->input->post('url_bukti'),
                    'tipe_transaksi' => $this->input->post('tipe_transaksi'),
                    'created_by' => $decoded_token->id_user
                ];

                $kas = $this->kas->add_kas($kas, $kas_log);

                if ($kas) {
                    $this->response([
                        'code'      => REST_Controller::HTTP_OK,
                        'message'   => 'Kas Berhasil Ditambahkan',
                        'data'      => null,
                    ], REST_Controller::HTTP_OK);
                    return;
                } else {
                    $this->response([
                        'code'      => REST_Controller::HTTP_BAD_REQUEST,
                        'message'   => 'Kas Gagal Ditambahkan',
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

    public function get_kas_post()
    {
        $header = $this->input->request_headers();
        $header = array_change_key_case($header, CASE_LOWER);


        if (array_key_exists('tokenkey', $header) && !empty($header['tokenkey'])) {
            $token_key = $header['tokenkey'];

            $decoded_token = AUTHORIZATION::validateToken($token_key);
            if ($decoded_token != false && property_exists($decoded_token, 'id_user') && property_exists($decoded_token, 'id_group')) {

                $id_kas = $this->input->post('id_kas');

                $this->form_validation->set_error_delimiters('', '');
                if ($id_kas == null) {
                    $this->response([
                        'code'      => REST_Controller::HTTP_BAD_REQUEST,
                        'message'   => rtrim(validation_errors(), "\n"),
                        'data'      => null,
                    ], REST_Controller::HTTP_BAD_REQUEST);
                    return;
                }

                $kas = $this->kas->get_kas($id_kas);

                if ($kas['success']) {
                    $this->response([
                        'code'      => REST_Controller::HTTP_OK,
                        'message'   => 'Kas Ditemukan',
                        'data'      => $kas['data'],
                    ], REST_Controller::HTTP_OK);
                    return;
                } else {
                    $this->response([
                        'code'      => REST_Controller::HTTP_NOT_FOUND,
                        'message'   => 'Kas Tidak Ditemukan',
                        'data'      => null,
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

    public function delete_kas_post()
    {
        $header = $this->input->request_headers();
        $header = array_change_key_case($header, CASE_LOWER);


        if (array_key_exists('tokenkey', $header) && !empty($header['tokenkey'])) {
            $token_key = $header['tokenkey'];

            $decoded_token = AUTHORIZATION::validateToken($token_key);
            if ($decoded_token != false && property_exists($decoded_token, 'id_user') && property_exists($decoded_token, 'id_group')) {

                $this->form_validation->set_rules('id_kas', 'id_kas', 'required');
                $this->form_validation->set_error_delimiters('', '');

                $id_kas = $this->input->post('id_kas');

                if ($this->form_validation->run() == false) {
                    $this->response([
                        'code'      => REST_Controller::HTTP_BAD_REQUEST,
                        'message'   => rtrim(validation_errors(), "\n"),
                        'data'      => null,
                    ], REST_Controller::HTTP_BAD_REQUEST);
                    return;
                }

                $kas = $this->kas->get_kas($id_kas);
                $kas_log = $this->kas->get_kas_log($id_kas);
                if (($kas_log != null) && ($kas['success'])) {
                    if ($kas_log['tipe_transaksi'] == 'IN') {
                        $nominal = $kas['data']['nominal'] - $kas_log['jumlah'];
                    } else {
                        $nominal = $kas['data']['nominal'] + $kas_log['jumlah'];
                    }

                    $data = [
                        'nominal' => $nominal
                    ];
                }

                $id_kas_log = $kas_log['id'];

                $kas = $this->kas->delete_kas($id_kas, $id_kas_log, $data);

                if ($kas) {
                    $this->response([
                        'code'      => REST_Controller::HTTP_OK,
                        'message'   => 'Kas Berhasil Dihapus',
                        'data'      => null,
                    ], REST_Controller::HTTP_OK);
                    return;
                } else {
                    $this->response([
                        'code'      => REST_Controller::HTTP_BAD_REQUEST,
                        'message'   => 'Kas Gagal Dihapus',
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

    public function update_kas_post()
    {
        $header = $this->input->request_headers();
        $header = array_change_key_case($header, CASE_LOWER);


        if (array_key_exists('tokenkey', $header) && !empty($header['tokenkey'])) {
            $token_key = $header['tokenkey'];

            $decoded_token = AUTHORIZATION::validateToken($token_key);
            if ($decoded_token != false && property_exists($decoded_token, 'id_user') && property_exists($decoded_token, 'id_group')) {

                // set rules for the input
                $this->form_validation->set_rules('id_kas', 'id_kas', 'required');
                $this->form_validation->set_rules('id_masjid', 'id_masjid', 'required');
                $this->form_validation->set_rules('nominal', 'nominal', 'required');
                $this->form_validation->set_rules('tanggal', 'tanggal', 'required');
                $this->form_validation->set_rules('tipe_transaksi', 'tipe_transaksi', 'required');


                $this->form_validation->set_error_delimiters('', '');
                if ($this->form_validation->run() == false) {
                    $this->response([
                        'code'      => REST_Controller::HTTP_BAD_REQUEST,
                        'message'   => rtrim(validation_errors(), "\n"),
                        'data'      => null,
                    ], REST_Controller::HTTP_BAD_REQUEST);
                    return;
                }

                $id_kas = $this->input->post('id_kas');

                $get_kas = $this->kas->get_kas($id_kas);

                if ($get_kas['data'] == null) {
                    $this->response([
                        'code'      => REST_Controller::HTTP_NOT_FOUND,
                        'message'   => "Kas tidak ditemukan",
                        'data'      => null,
                    ], REST_Controller::HTTP_NOT_FOUND);
                    return;
                }

                if ($this->input->post('tipe_transaksi') == 'IN') {
                    $nominal = $get_kas['data']['nominal'] + $this->input->post('nominal');
                } elseif ($this->input->post('tipe_transaksi') == 'OUT') {
                    $nominal = $get_kas['data']['nominal'] - $this->input->post('nominal');
                }

                $kas = [
                    'id_masjid' => $this->input->post('id_masjid'),
                    'nominal' => $nominal,
                    'created_by' => $decoded_token->id_user
                ];
                $kas_log = [
                    'id_kas' => $id_kas,
                    'id_masjid' => $this->input->post('id_masjid'),
                    'jumlah' => $this->input->post('nominal'),
                    'tanggal' => $this->input->post('tanggal'),
                    'keterangan' => $this->input->post('keterangan'),
                    'url_bukti' => $this->input->post('url_bukti'),
                    'tipe_transaksi' => $this->input->post('tipe_transaksi'),
                    'created_by' => $decoded_token->id_user
                ];

                $kas = $this->kas->update_kas($id_kas, $kas, $kas_log);


                if ($kas) {
                    $this->response([
                        'code'      => REST_Controller::HTTP_OK,
                        'message'   => 'Kas Berhasil Diupdate',
                        'data'      => null,
                    ], REST_Controller::HTTP_OK);
                    return;
                } else {
                    $this->response([
                        'code'      => REST_Controller::HTTP_BAD_REQUEST,
                        'message'   => 'Kas Gagal Diupdate',
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
