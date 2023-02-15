<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Dashboard extends BaseController
{

    protected $template = "app";

    public function __construct()
    {
        parent::__construct();
        $this->load->library('tanggalindo');
        $this->load->model('M_master');
        $this->load->model('M_user', 'm_user');
    }

    public function index()
    {
        $this->data['title'] = 'Dashboard';
        $this->data['nama'] = $this->session->userdata('sajada_nama');
        $this->data['sum_masjid'] = $this->M_master->sum_masjid();
        $this->data['sum_pengurus'] = $this->M_master->sum_pengurus();
        $this->data['sum_jamaah'] = $this->M_master->sum_jamaah();
        if(getSessionRole() == 2){
            $this->data['jamaah'] = $this->M_master->masjid_jamaah();
            $this->data['pengurus'] = $this->M_master->masjid_pengurus();
        }
        $this->render('index');
    }

    public function profil()
    {
        $this->data['title'] = 'Profil';
        $this->data['profil'] = $this->m_user->getDataUser(getSessionID());
        $this->render('profil');
    }

    public function profilEdit()
    {
        $this->data['title'] = 'Edit Profil';
        $this->data['profil'] = $this->m_user->getDataUser(getSessionID());
        $this->render('profil_edit');
    }

    public function prosesedit()
    {
        $param = $this->input->post();
        $id_user = getSessionID();
        $userData = $this->m_user->getDataUser($id_user);
        $data = $param;
        unset($data['username']);
        // if (!empty($_FILES['image']['name'])) {
        //     $siap = uploadBerkas('image', 'user', 'user');
        //     if ($siap['success']) {
        //         if (!empty($userData->image)) {
        //             if (file_exists('.' . $userData->image)) {
        //                 unlink('.' . $userData->image);
        //             }
        //         }
        //         $data['image'] = $siap['file_name'];
        //     }
        // }
        $proses = $this->m_user->ubah($id_user, $data);
        if ($proses) {
            $this->session->set_userdata(PREFIX_SESS . "_nama", $data['real_name']);
            $this->session->set_userdata(PREFIX_SESS . "_email", $data['email']);
            // if (!empty($data['image'])) {
            //     $this->setSession('image', $data['image']);
            // }
            $this->setUserData();
        }
        if ($this->input->is_ajax_request()) {
            echo json_encode($proses);
        } else {
            redirect("dashboard/profil");
        }
    }
}
