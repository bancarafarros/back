<?php

defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
// require APPPATH . '/libraries/Format.php';


use Restserver\Libraries\REST_Controller;

class Tabungan extends REST_Controller
{

    private $ok = '200';
    private $bad = '400';
    private $unauthorized = '401';
    private $error = '500';

    function __construct()
    {
        parent::__construct();
        $this->methods['data_post']['limit'] = 100; // 100 requests per hour per data/key
        $this->load->model('api/api_tabungan', 'tabungan');
        $this->load->model('api/api_jamaah', 'jamaah');
        $this->load->model('api/api_auth', 'auth');
        $this->load->model('api/api_payment', 'payment');
    }

    public function riwayat_transaksi_tabungan_get()
    {
        $header = $this->input->request_headers();
        $header = array_change_key_case($header, CASE_LOWER);


        if (array_key_exists('tokenkey', $header) && !empty($header['tokenkey'])) {
            $token_key = $header['tokenkey'];

            $decoded_token = AUTHORIZATION::validateToken($token_key);
            if ($decoded_token != false && property_exists($decoded_token, 'id_user') && property_exists($decoded_token, 'id_group')) {

                $tabungan = $this->payment->riwayat_transaksi_tabungan($decoded_token->id_user);

                if ($tabungan != null) {
                    $this->response([
                        'status' => REST_Controller::HTTP_OK,
                        'message' => "Riwayat Pembayaran Tabungan Ditemukan",
                        'data' => $tabungan
                    ], REST_Controller::HTTP_OK);
                    return;
                } else {
                    $this->response([
                        'status' => REST_Controller::HTTP_BAD_REQUEST,
                        'message' => "Riwayat Pembayaran Tabungan Kosong",
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

    public function bayar_tagihan_post()
    {
        $header = $this->input->request_headers();
        $header = array_change_key_case($header, CASE_LOWER);

        if (array_key_exists('tokenkey', $header) && !empty($header['tokenkey'])) {
            $token_key = $header['tokenkey'];

            $decoded_token = AUTHORIZATION::validateToken($token_key);
            if ($decoded_token != false && property_exists($decoded_token, 'id_user') && property_exists($decoded_token, 'id_group')) {

                $this->form_validation->set_rules('nominal', 'nominal', 'required');
                $this->form_validation->set_rules('tabungan_id', 'tabungan_id', 'required');
                $this->form_validation->set_rules('metode_pembayaran', 'metode_pembayaran', 'required');
                $this->form_validation->set_rules('transaksi', 'transaksi', 'required');

                $this->form_validation->set_error_delimiters('', '');
                if ($this->form_validation->run() == false) {
                    $this->response([
                        'status' => REST_Controller::HTTP_BAD_REQUEST,
                        'message' => rtrim(validation_errors(), "\n"),
                        'data' => null,
                    ], REST_Controller::HTTP_BAD_REQUEST);
                    return;
                }

                $jamaah = $this->auth->getUser($decoded_token->id_user);

                $apiKey       = 'DEV-eqYH9MwixlXwFuZ8wF0zRjXteHFZtT6ECs9uY6xM';
                $privateKey   = 'iHN16-0O2e6-lfT0u-a90Om-sE7gq';
                $merchantCode = 'T12619';
                $merchantRef  = auto_code('INV-' . date('His'));
                $amount       = $this->input->post('nominal');

                $data = [
                    'method'         => $this->input->post('metode_pembayaran'),
                    'merchant_ref'   => $merchantRef,
                    'amount'         => $amount,
                    'customer_name'  => $jamaah['real_name'],
                    'customer_email' => $jamaah['email'],
                    'order_items'    => [
                        [
                            'name'        => $this->input->post('transaksi'),
                            'price'       => $amount,
                            'quantity'    => 1,
                        ],
                    ],
                    'expired_time' => (time() + (24 * 60 * 60)), // 24 jam
                    'signature'    => hash_hmac('sha256', $merchantCode . $merchantRef . $amount, $privateKey)
                ];
                
                if ($this->input->post('no_hp') != null) {
                    $data['customer_phone'] = $this->input->post('no_hp');
                }


                $curl = curl_init();
                curl_setopt_array($curl, [
                    CURLOPT_FRESH_CONNECT  => true,
                    CURLOPT_URL            => 'https://tripay.co.id/api-sandbox/transaction/create',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_HEADER         => false,
                    CURLOPT_HTTPHEADER     => ['Authorization: Bearer ' . $apiKey],
                    CURLOPT_FAILONERROR    => false,
                    CURLOPT_POST           => true,
                    CURLOPT_POSTFIELDS     => http_build_query($data),
                    CURLOPT_IPRESOLVE      => CURL_IPRESOLVE_V4
                ]);

                $response = curl_exec($curl);
                $error = curl_error($curl);

                curl_close($curl);
                if (empty($error)) {


                    $bank = $this->payment->ref_bank($this->input->post('metode_pembayaran'));
                    $response_data = json_decode($response, true);

                    var_dump($response_data);
                    die;

                    $data = [
                        'tabungan_id' => $this->input->post('tabungan_id'),
                        'ref_bank' => $bank['id'],
                        'nomer_invoice' => $merchantRef,
                        'kode_pembayaran' => $response_data['data']['pay_code'],
                        'nama_pengirim' => $jamaah['real_name'],
                        'nominal' => $amount,
                        'is_valid' => '0',
                        'expired_time' => date('Y-m-d H:i:s', $response_data['data']['expired_time']),
                        'created_by' => $decoded_token->id_user
                    ];

                   
                    $donasi = $this->payment->request_transaksi_tabungan($data);

                    if ($donasi) {
                        $this->response([
                            'status' => REST_Controller::HTTP_OK,
                            'message' => "Request Transaksi Berhasil",
                            'data' => $response_data['data']
                        ], REST_Controller::HTTP_OK);
                        return;
                    } else {
                        $this->response([
                            'status' => REST_Controller::HTTP_BAD_REQUEST,
                            'message' => "Request Transaksi Gagal",
                            'data' => $error
                        ], REST_Controller::HTTP_BAD_REQUEST);
                        return;
                    }
                } else {
                    echo $error;
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

    public function addTabungan_post()
    {
        $header = $this->input->request_headers();
        $header = array_change_key_case($header, CASE_LOWER);


        if (array_key_exists('tokenkey', $header) && !empty($header['tokenkey'])) {
            $token_key = $header['tokenkey'];

            $decoded_token = AUTHORIZATION::validateToken($token_key);
            if ($decoded_token != false && property_exists($decoded_token, 'id_user') && property_exists($decoded_token, 'id_group')) {

                $this->form_validation->set_rules('ref_tabungan', 'ref_tabungan', 'required');
                $this->form_validation->set_rules('id_user', 'id_user', 'required');
                $this->form_validation->set_rules('jumlah_hewan', 'jumlah_hewan', 'required');
                $this->form_validation->set_rules('batas_pelunasan', 'batas_pelunasan', 'required');
                $this->form_validation->set_rules('harga_qurban', 'harga_qurban', 'required');
                $this->form_validation->set_error_delimiters('', '');
                if ($this->form_validation->run() == false) {
                    $this->response([
                        'status' => REST_Controller::HTTP_BAD_REQUEST,
                        'message' => rtrim(validation_errors(), "\n"),
                        'data' => null,
                    ], REST_Controller::HTTP_BAD_REQUEST);
                    return;
                }

                $data = [
                    'ref_tabungan' => $this->input->post('ref_tabungan'),
                    'jamaah_id' => $this->jamaah->get_jamaah($this->input->post('id_user'))['data']['id'],
                    'ref_tabungan_detail' => $this->tabungan->get_detail_tabungan($this->input->post('ref_tabungan'))['id'],
                    'nomer_pendaftaran' => auto_code('REG', '-'),
                    'batas_pelunasan' => date('Y-m-d H:i:s', strtotime($this->input->post('batas_pelunasan'))),
                    'jumlah_qurban' => $this->input->post('jumlah_hewan'),
                    'sisa_target' => $this->input->post('harga_qurban'),
                    'register_by' => $this->input->post('id_user')
                ];


                $tabungan = $this->tabungan->add_tabungan($data);

                if ($tabungan) {
                    $this->response([
                        'status' => REST_Controller::HTTP_OK,
                        'message' => "Berhasil menambahkan tabungan",
                        'data' => $data
                    ], REST_Controller::HTTP_OK);
                    return;
                } else {
                    $this->response([
                        'status' => REST_Controller::HTTP_BAD_REQUEST,
                        'message' => "Gagal menambahkan tabungan",
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

    public function getTabungan_get()
    {
        $header = $this->input->request_headers();
        $header = array_change_key_case($header, CASE_LOWER);


        if (array_key_exists('tokenkey', $header) && !empty($header['tokenkey'])) {
            $token_key = $header['tokenkey'];

            $decoded_token = AUTHORIZATION::validateToken($token_key);
            if ($decoded_token != false && property_exists($decoded_token, 'id_user') && property_exists($decoded_token, 'id_group')) {

                $tabungan = $this->tabungan->get_tabungan();

                if ($tabungan != null) {
                    $i = 0;
                    foreach ($tabungan as $qurban) {
                        $jkt  = new DateTimeZone('Asia/Jakarta');
                        $periode = new DateTime('now', $jkt);
                        $akhir = $periode->modify('+' . $qurban['lama_bulan'] . ' months');
                        $akhir_periode = $akhir->format('M Y');
                        $awal = new DateTime('now', $jkt);
                        $awal_periode = $awal->format('M Y');

                        $tabungan[$i]['periode'] = $awal_periode . ' - ' . $akhir_periode;
                        $i++;
                    }

                    $this->response([
                        'status' => REST_Controller::HTTP_OK,
                        'message' => "Tabungan ditemukan",
                        'data' => $tabungan
                    ], REST_Controller::HTTP_OK);
                    return;
                } else {
                    $this->response([
                        'status' => REST_Controller::HTTP_BAD_REQUEST,
                        'message' => "Tabungan kosong",
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

    public function getTagihan_get()
    {
        $header = $this->input->request_headers();
        $header = array_change_key_case($header, CASE_LOWER);


        if (array_key_exists('tokenkey', $header) && !empty($header['tokenkey'])) {
            $token_key = $header['tokenkey'];

            $decoded_token = AUTHORIZATION::validateToken($token_key);
            if ($decoded_token != false && property_exists($decoded_token, 'id_user') && property_exists($decoded_token, 'id_group')) {


                $tabungan = $this->tabungan->get_tagihan($decoded_token->id_user);

                if ($tabungan != null) {
                    $index = 0;

                    foreach ($tabungan as $qurban) {

                        $jkt  = new DateTimeZone('Asia/Jakarta');
                        $periode = new DateTime('now', $jkt);
                        $akhir = $periode->modify('+' . $qurban['lama_bulan'] . ' months');
                        $akhir_periode = $akhir->format('M Y');
                        $awal_periode = date('M Y', strtotime($qurban['register_time']));



                        $tabungan[$index]['total_pembayaran'] = $qurban['total_pembayaran'] ?? '0';
                        $tabungan[$index]['periode'] = $awal_periode . ' - ' . $akhir_periode;
                        $tabungan[$index]['register_time'] = date('d M Y', strtotime($qurban['register_time']));
                        $tabungan[$index]['jatuh_tempo'] = 'Tanggal ' . date('d', strtotime($qurban['register_time'])) . '/bulan';
                        $tabungan[$index]['batas_pembayaran'] = $akhir->format('d M Y');

                        $index++;
                    }
                    $this->response([
                        'status' => REST_Controller::HTTP_OK,
                        'message' => "Tagihan ditemukan",
                        'data' => $tabungan
                    ], REST_Controller::HTTP_OK);
                    return;
                } else {
                    $this->response([
                        'status' => REST_Controller::HTTP_BAD_REQUEST,
                        'message' => "Tidak ada tagihan",
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
