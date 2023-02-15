<?php

defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
// require APPPATH . '/libraries/Format.php';


use Restserver\Libraries\REST_Controller;

class Auth extends REST_Controller
{

    private $ok = '200';
    private $bad = '400';
    private $unauthorized = '401';
    private $error = '500';

    function __construct()
    {
        parent::__construct();

        $this->methods['data_post']['limit'] = 100; // 100 requests per hour per data/key
        $this->load->model('api/api_login', 'login');
        $this->load->library('session');
        $this->load->model('api/api_auth', 'auth');
    }


    public function registrasi_post()
    {

        $this->form_validation->set_rules('nama', 'nama', 'required');
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


        $isEmailUsed = $this->auth->cekEmail($this->input->post('email'));
        $isNomorUsed = $this->auth->cekNomorHP($this->input->post('no_hp'));

        if ($isEmailUsed['used']) {
            $this->response([
                'status' => REST_Controller::HTTP_BAD_REQUEST,
                'message' => $isEmailUsed['message'],
                'data' => null,
            ], REST_Controller::HTTP_BAD_REQUEST);
            return;
        }

        if ($isNomorUsed['used']) {
            $this->response([
                'status' => REST_Controller::HTTP_BAD_REQUEST,
                'message' => $isNomorUsed['message'],
                'data' => null,
            ], REST_Controller::HTTP_BAD_REQUEST);
            return;
        }

        // if all data valid, then send otp
        $data = [
            'nama' => $this->input->post('nama'),
            'email' => $this->input->post('email'),
            'no_hp' => $this->input->post('no_hp'),
            'password' => $this->input->post('password')
        ];

        $otp = substr(rand(), 0, 4);
        $jkt = new DateTimeZone('Asia/Jakarta');
        $tomorrow = new DateTime('now', $jkt);
        $tomorrow->modify('+1 minutes');
        $expired = $tomorrow->format('Y-m-d H:i:s');

        $user = [
            'username' => $data['email'],
            'email' => $data['email'],
            'real_name' => $data['nama'],
            'id_group' => 3,
            'password' => hash('sha256', $data['password']),
            'is_active' => '0'
        ];

        $jamaah = [
            'id' => getUUID(),
            'nama' => $data['nama'],
            'no_hp' => $data['no_hp'],
            'is_active' => '0'
        ];

        $regist = $this->auth->daftar_jamaah($jamaah, $user);

        if ($regist) {
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
            return;
        }
    }

    public function verifikasi_register_post()
    {
        $this->form_validation->set_rules('otp', 'otp', 'required');
        $this->form_validation->set_error_delimiters('', '');
        if ($this->form_validation->run() == false) {
            $this->response([
                'status' => REST_Controller::HTTP_BAD_REQUEST,
                'message' => rtrim(validation_errors(), "\n"),
                'data' => null,
            ], REST_Controller::HTTP_BAD_REQUEST);
            return;
        }

        // get id user from token
        $otp = $this->input->post('otp');
        $user = $this->auth->isvalidToken($otp);
        if ($user == null) {
            $this->response([
                'status' => REST_Controller::HTTP_NOT_FOUND,
                'message' => 'Token Tidak Ditemukan',
                'data' => null
            ], REST_Controller::HTTP_NOT_FOUND);
            return;
        }

        // user verification by change is active to 1
        $id_user = $user['user_id'];
        $verifikasi = $this->auth->verifikasi_register($id_user);
        if ($verifikasi['success']) {
            $this->response([
                'status' => REST_Controller::HTTP_OK,
                'message' => 'Registrasi Berhasil',
                'data' => null
            ], REST_Controller::HTTP_OK);
            return;
        } else {
            $this->response([
                'status' => REST_Controller::HTTP_BAD_REQUEST,
                'message' => 'Registrasi Gagal',
                'data' => null
            ], REST_Controller::HTTP_BAD_REQUEST);
            return;
        }
    }

    public function request_otp_post()
    {
        $this->form_validation->set_rules('email', 'email', 'required');
        $this->form_validation->set_error_delimiters('', '');
        if ($this->form_validation->run() == false) {
            $this->response([
                'status' => REST_Controller::HTTP_BAD_REQUEST,
                'message' => rtrim(validation_errors(), "\n"),
                'data' => null,
            ], REST_Controller::HTTP_BAD_REQUEST);
            return;
        }
        $email = $this->input->post('email');
        $id_user = $this->auth->getIdByEmail($email);

        $otp = strval(rand(0000, 9999));
        $jkt = new DateTimeZone('Asia/Jakarta');
        $tomorrow = new DateTime('now', $jkt);
        $tomorrow->modify('+1 minutes');
        $expired = $tomorrow->format('Y-m-d H:i:s');

        $token  = [
            'id' => getUUID(),
            'user_id' => $id_user,
            'token_key' => $otp,
            'expired_token' => $expired
        ];

        $this->auth->resetToken($token);

        $this->response([
            'status' => REST_Controller::HTTP_OK,
            'message' => 'OTP Berhasil Dikirimkan',
            'data' => [
                'otp' => $otp
            ],
        ], REST_Controller::HTTP_OK);
        return;
    }

    public function validOTP()
    {
    }


    public function login_post()
    {
        $post     = $this->input->post();
        $username = isset($post['username']) ? $post['username'] : FALSE;
        $pass = isset($post['password']) ? $post['password'] : FALSE;

        if (!$username || !$pass) {
            $this->response([
                'code' => REST_Controller::HTTP_BAD_REQUEST,
                'message' => "Username atau Password Belum terisi",
                'data' => null
            ], REST_Controller::HTTP_BAD_REQUEST);
            return;
        }

        $password = hash('sha256', $pass);
        $result = $this->auth->ceklogin($username, $password);
        if ($result->num_rows() != 0) {
            $data = $result->row_array();

            if ($data['is_active'] == 0) {
                $this->response([
                    'code' => REST_Controller::HTTP_NOT_FOUND,
                    'message' => "Login gagal. User telah dinonaktifkan.",
                    'data' => null
                ], REST_Controller::HTTP_NOT_FOUND);
                return;
            }
            $token = $this->_create_token($data, $username);

            // var_dump($token);
            // die;
            $this->response([
                'code' => REST_Controller::HTTP_OK,
                'message' => "Login berhasil",
                'data' => $token,
            ]);
            return;
        }
        $this->response([
            'code' => REST_Controller::HTTP_NOT_FOUND,
            'message' => 'Username atau Password Salah',
            'data' => null
        ], REST_Controller::HTTP_NOT_FOUND);
        return;
    }

    public function ubahPass_post()
    {
        $header = $this->input->request_headers();
        $header = array_change_key_case($header, CASE_LOWER);

        if (array_key_exists('api_key', $header) && !empty($header['api_key'])) {
            $token_key = $header['api_key'];
            $decoded_token = AUTHORIZATION::validateToken($token_key);
            if ($decoded_token != false && property_exists($decoded_token, 'id_user') && property_exists($decoded_token, 'id_group')) {
                $id_user = $decoded_token->id_user;
                $old_pass = $this->post('old_pass');
                $new_pass = $this->post('new_pass');
                $confirm_pass = $this->post('confirm_pass');
                $password = hash('sha256', $old_pass);
                $result = $this->auth->cekpass($id_user, $password);

                if ($new_pass != $confirm_pass) {
                    $this->response([
                        'status'    => REST_Controller::HTTP_BAD_REQUEST,
                        'message'   => 'Password baru dan Confirm password berbeda',
                        'data'      => null,
                    ], REST_Controller::HTTP_BAD_REQUEST);
                } else {
                    if ($result->num_rows() != 0) {
                        $data = array(
                            'password' => hash("sha256", $new_pass),
                        );
                        $insert = $this->auth->ubahPass($id_user, $data);
                        if ($insert) {
                            $this->response([
                                'code'      => REST_Controller::HTTP_OK,
                                'message'   => 'Password Berhasil Diganti',
                                'data'      => $data,
                            ], REST_Controller::HTTP_OK);
                        } else {
                            $this->response([
                                'code'      =>  REST_Controller::HTTP_BAD_REQUEST,
                                'message'   => 'Password Gagal Diganti',
                                'data'      => null,
                            ], REST_Controller::HTTP_BAD_REQUEST);
                        }
                    } else {
                        $this->response([
                            'status'    => REST_Controller::HTTP_BAD_REQUEST,
                            'message'   => 'Password lama anda salah',
                            'data'      => null,
                        ], REST_Controller::HTTP_BAD_REQUEST);
                    }
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

    // get /master always disabled
    public function index_get()
    {
        $this->response([
            'status'    => $this->bad,
            'message'   => 'Bad Request',
            'data'      => null,
        ], REST_Controller::HTTP_BAD_REQUEST);
    }

    public function index_post()
    {
        // check on posted data
        $data = json_decode(trim(file_get_contents('php://input')), true);

        // init result
        $result = null;

        // check wether is login, or refresh token
        if ($data != null && array_key_exists('username', $data) && array_key_exists('password', $data)) { // general login
            $username = $data['username'];
            $password = $data['password'];

            $result = $this->login->login($username, $password);
        } else if ($data != null && array_key_exists('refresh_token', $data)) { // refresh token
            $refresh = $data['refresh_token'];

            $check_token = AUTHORIZATION::validateToken($refresh);
            if ($check_token == false) {
                $this->response([
                    'status' => $this->error,
                    'message' => 'Internal Server Error',
                    'data'  => null,
                ], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
                return;
            }

            // get username based on token
            $username = $check_token->username;

            $result = $this->login->check_user($username);
        } else {
            $this->response([
                'status' => $this->bad,
                'message' => 'Bad Request',
                'data' => null
            ], REST_Controller::HTTP_BAD_REQUEST);
            return;
        }

        if (is_array($result) && $result != null) {
            if ($result['status'] == 'ok') {
                // create jwt token
                $token = $this->_create_token($result['data'], $username);

                $this->response([
                    'status' => $this->ok,
                    'message' => 'Auth berhasil',
                    'data' => $token
                ], REST_Controller::HTTP_OK);
            } else {
                $this->response([
                    'status' => $this->unauthorized,
                    'message' => $result['message'],
                    'data'  => null,
                ], REST_Controller::HTTP_UNAUTHORIZED);
            }
        } else {
            $this->response([
                'status' => $this->error,
                'message' => 'Internal Server Error',
                'data' => null,
            ], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
    }





    private function _create_token($data, $username)
    {
        $token = array();
        $token['id_user'] = $data['id_user'];
        $token['id_group'] = $data['id_group'];

        $refresh_token = array();
        $refresh_token['username'] = $username;

        try {
            $jwt = AUTHORIZATION::generateToken($token);
            $refresh = AUTHORIZATION::generateToken($refresh_token);
        } catch (Exception $error) {
            $jwt = null;
            $refresh = null;
        }

        $CI = &get_instance();
        $result = array();
        $result['api_key'] = $jwt;
        $result['expires'] = ($CI->config->item('token_timeout') > 0) ? ($CI->config->item('token_timeout') * 60) : 'never';
        $result['refresh_token'] = $refresh;

        return $result;
    }

    public function request_reset_password_post()
    {
        $email = $this->post('email');
        $user = $this->auth->validEmailToReset($email);

        if ($user['valid'] == false) {
            $this->response([
                'code'      =>  REST_Controller::HTTP_BAD_REQUEST,
                'message'   => 'Email tidak ditemukan',
                'data'      => null,
            ], REST_Controller::HTTP_BAD_REQUEST);
            return;
        }

        $token = substr(rand(), 0, 4);
        $tomorrow = new DateTime('now');
        $tomorrow->modify('+1 day');
        $expired = $tomorrow->format('Y-m-d H:i:s');

        $email = array(
            'token' => $token,
            'link' => $token,
            'user_id' => $user['data']["id_user"],
            'expired_at' => $expired,
        );

        $reset_tokens = array(
            'id' => getUUID(),
            'token_key' => $token,
            'user_id' => $user['data']["id_user"],
            'expired_token' => $expired,
            'is_valid' => '0'
        );

        $insert = $this->auth->resetToken($reset_tokens);

        // sendEmail($email, "Lupa Password", $this->load->view('email_templates/auth/send_password_reset_token', $email, true));

        if ($insert) {
            $this->response([
                'code'      => REST_Controller::HTTP_OK,
                'message'   => 'Token Berhasil Dikirimkan',
                'data'      => $reset_tokens,
            ], REST_Controller::HTTP_OK);
            return;
        } else {
            $this->response([
                'code'      =>  REST_Controller::HTTP_BAD_REQUEST,
                'message'   => 'Token Gagal Dikirimkan',
                'data'      => null,
            ], REST_Controller::HTTP_BAD_REQUEST);
            return;
        }
    }

    public function isValidToken_post()
    {
        $token = $this->input->post('token_key');

        $validToken = $this->auth->isValidToken($token);

        if ($validToken != null) {
            // validate token
            if ($validToken['is_valid'] == 1) {
                $this->response([
                    'code'      =>  REST_Controller::HTTP_BAD_REQUEST,
                    'message'   => 'Token Telah Digunakan',
                    'data'      => null,
                ], REST_Controller::HTTP_BAD_REQUEST);
                return;
            }

            if ($validToken['is_valid'] == 2) {
                $this->response([
                    'code'      =>  REST_Controller::HTTP_BAD_REQUEST,
                    'message'   => 'Token Telah Expired',
                    'data'      => null,
                ], REST_Controller::HTTP_BAD_REQUEST);
                return;
            }

            $jkt = new DateTimeZone('Asia/Jakarta');
            $today = new DateTime('now', $jkt);
            $now = $today->format('Y-m-d H:i:s');
            if (strtotime($validToken['expired_token']) < strtotime($now)) {
                $expiredToken = $this->auth->setTokenValid('2', $validToken['id']);

                if ($expiredToken) {
                    $this->response([
                        'code'      =>  REST_Controller::HTTP_BAD_REQUEST,
                        'message'   => 'Token Telah Expired, Silahkan Request Token Baru',
                        'data'      => null,
                    ], REST_Controller::HTTP_BAD_REQUEST);
                    return;
                }
            }
            $TokenUsed = $this->auth->setTokenValid('1', $validToken['id']);
            if ($TokenUsed) {
                $this->response([
                    'code'      =>  REST_Controller::HTTP_OK,
                    'message'   => 'Token Valid',
                    'data'      => ['token_key' => $validToken['token_key']],
                ], REST_Controller::HTTP_OK);
                return;
            }
        } else {
            $this->response([
                'code'      =>  REST_Controller::HTTP_NOT_FOUND,
                'message'   => 'Token Tidak Ditemukan',
                'data'      => null,
            ], REST_Controller::HTTP_NOT_FOUND);
            return;
        }
    }

    public function reset_password_post()
    {
        $token = $this->input->post('token_key');
        $password_baru = $this->input->post('password');

        $user = $this->auth->getUserByToken($token);

        if ($user != null) {

            $password = hash('sha256', $password_baru);
            $update = $this->auth->reset_password($user['user_id'], $password);

            if ($update) {
                $this->response([
                    'code'      =>  REST_Controller::HTTP_OK,
                    'message'   => 'Password Berhasil Direset',
                    'data'      => null,
                ], REST_Controller::HTTP_OK);
                return;
            } else {
                $this->response([
                    'code'      =>  REST_Controller::HTTP_BAD_REQUEST,
                    'message'   => 'Password Gagal Direset',
                    'data'      => null,
                ], REST_Controller::HTTP_BAD_REQUEST);
                return;
            }
        } else {
            $this->response([
                'code'      =>  REST_Controller::HTTP_BAD_REQUEST,
                'message'   => 'Token key tidak ditemukan',
                'data'      => null,
            ], REST_Controller::HTTP_BAD_REQUEST);
            return;
        }
    }
}
