<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Auth extends BaseController
{
    public $loginBehavior = false;
    protected $template = "app";

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        if (!empty($this->session->userdata('sajada_userId'))) {
            redirect('dashboard');
        }
        $this->data['title'] = 'Login';
        $this->render('index');
    }

    public function fetchCaptha()
    {
        $response['data'] = $this->create_captcha();
        echo json_encode($response);
    }

    public function lupa_password()
    {
        $this->data['title'] = 'Lupa Password';
        $this->render('lupa_password/index');
    }

    public function logout()
    {
        $log = [
            'id_user'   => getSessionID(),
            'activity'  => "LOG OUT",
            'page_url'  => site_url("auth/logout=true")
        ];
        $this->setLog($log);
        $this->unSetUserData();
        $this->load->database("default", FALSE, TRUE); //CHANGE DB TO DEFAULT sialogin
        if ($this->session->flashdata('changepassword')) {
            $this->session->set_flashdata('true', 'Ubah password berhasil, silahkan login kembali.');
            redirect("auth/logout=true");
        }
        redirect('index?logout=true');
    }
}
