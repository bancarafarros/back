<?php

defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
// require APPPATH . '/libraries/Format.php';


use Restserver\Libraries\REST_Controller;

class Payment extends REST_Controller
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

    public function index_post()
    {
        $pembayaran = json_decode(trim(file_get_contents('php://input')), true);

        $bank = $this->payment->ref_bank($pembayaran['payment_method_code']);

        $status_pembayaran = '';

        if ($pembayaran['status'] == 'PAID') {
            $status_pembayaran = '02';
        } elseif ($pembayaran['status'] == 'REFUND') {
            $status_pembayaran = '03';
        } elseif ($pembayaran['status'] == 'EXPIRED') {
            $status_pembayaran = '03';
        } elseif ($pembayaran['status'] == 'FAILED') {
            $status_pembayaran = '03';
        } else {
            $status_pembayaran = '01';
        }

        $data = [
            'id_konfirmasi' => getUUID(),
            'nomor_invoice' => $pembayaran['merchant_ref'],
            'tanggal_transfer' => date('Y-m-d', $pembayaran['paid_at']),
            'ref_bank_tujuan' => $bank['id'],
            'jumlah_dana' => $pembayaran['total_amount'],
            'status' => $status_pembayaran
        ];

        $pembayaran = $this->payment->konfirmasi_pembayaran($data);
        if ($pembayaran) {
            $this->response([
                'status' => REST_Controller::HTTP_OK,
                'message' => 'Pembayaran Berhasil',
                'data' => null,
            ], REST_Controller::HTTP_OK);
            return;
        } else {
            $this->response([
                'status' => REST_Controller::HTTP_BAD_REQUEST,
                'message' => 'Pembayaran Gagal',
                'data' => null,
            ], REST_Controller::HTTP_BAD_REQUEST);
            return;
        }
    }


    public function instruksi_pembayaran_post()
    {

        $apiKey = 'DEV-eqYH9MwixlXwFuZ8wF0zRjXteHFZtT6ECs9uY6xM';

        $payload = ['code' => $this->input->post('pembayaran')];

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_FRESH_CONNECT  => true,
            CURLOPT_URL            => 'https://tripay.co.id/api-sandbox/payment/instruction?' . http_build_query($payload),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER         => false,
            CURLOPT_HTTPHEADER     => ['Authorization: Bearer ' . $apiKey],
            CURLOPT_FAILONERROR    => false,
            CURLOPT_IPRESOLVE      => CURL_IPRESOLVE_V4
        ]);

        $response = curl_exec($curl);
        $error = curl_error($curl);

        curl_close($curl);

        echo empty($error) ? $response : $error;
    }

    public function channel_pembayaran_get()
    {
        $channel_pembayaran = $this->payment->channel_pembayaran();

        if ($channel_pembayaran != null) {
            $this->response([
                'status' => REST_Controller::HTTP_OK,
                'message' => "Channel Pembayaran ditemukan",
                'data' => $channel_pembayaran
            ], REST_Controller::HTTP_OK);
            return;
        } else {
            $this->response([
                'status' => REST_Controller::HTTP_BAD_REQUEST,
                'message' => "Channel Pembayaran Kosong",
                'data' => $channel_pembayaran
            ], REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    public function request_transaksi_post()
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

                // echo empty($error) ? $response : $error;

                if (empty($error)) {


                    $bank = $this->payment->ref_bank($this->input->post('metode_pembayaran'));
                    $response_data = json_decode($response, true);

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

                    $donasi = $this->payment->request_transaksi_donasi($data);

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
}
