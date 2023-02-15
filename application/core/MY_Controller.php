<?php

class BaseController extends CI_Controller
{

    protected $template = "app";
    protected $module = "";
    protected $data = array();
    private $whitelistUrl = [
        '',
        'index',
        'forgot_password',
        'forgot_password_reset',
        'registrasi',
        'logout',
        'index/login',
    ];
    public $loginBehavior = true;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_activity_log');
        $this->load->model('M_auth');
        $userId = $this->session->userdata(PREFIX_SESS . '_userId');
        if (uri_string() == "" && $this->input->post("login-button") != null) {
            $cookie = get_cookie(PREFIX_SESS . '_auth');

            if ($cookie <> '') {

                $this->db->select([
                    'a.id_user',
                    'a.username', 'a.email', 'a.real_name',
                    'a.id_group', 'a.is_active', 'a.cookie', 'b.nama_group', 'b.keterangan', 'b.dbusername'
                ]);
                $this->db->join('user_group b', 'a.id_group = b.id_group', 'INNER');
                $this->db->where(['a.cookie' => $cookie, 'a.is_active' => '1']);
                $result = $this->db->get('user a');
            } else {
                if (trim($this->input->post('captcha')) != trim($this->session->userdata('captchaword'))) {
                    $this->session->set_flashdata('errorMessage', 'Gagal login, captcha tidak sesuai.');
                    redirect('auth');
                }
                $username = trim($this->input->post('username'));
                $password = hash('SHA256', $this->input->post('password'));

                $this->db->select([
                    'a.id_user', 'a.username', 'a.email', 'a.real_name', 'a.id_group', 'a.is_active', 'b.nama_group', 'b.keterangan', 'b.dbusername'
                ]);
                $this->db->join('user_group b', 'a.id_group = b.id_group', 'INNER');
                $this->db->where(['a.username' => $username, 'a.password' => $password, 'a.is_active' => '1']);
                $result = $this->db->get('user a');
            }

            if ($result->num_rows() == 0) {
                $this->session->set_flashdata('errorMessage', "Gagal login, silahkan periksa kembali informasi login Anda.");
                $this->template = "login";
                $this->data['title'] = 'Login';
                redirect('auth');
            } else {
                $row = $result->first_row();
                $id_user = $row->id_user;
                $nik = $row->username;
                $nama = $row->real_name;
                $email = $row->email;
                $id_group = $row->id_group;
                $nama_group = $row->nama_group;
                $is_active = $row->is_active;
                $dbusername = $row->dbusername;

                $this->session->set_userdata(PREFIX_SESS . "_userId", $id_user);
                $this->session->set_userdata(PREFIX_SESS . "_username", $nik);
                $this->session->set_userdata(PREFIX_SESS . "_nama", $nama);
                $this->session->set_userdata(PREFIX_SESS . "_email", $email);
                $this->session->set_userdata(PREFIX_SESS . "_idGroup", $id_group);
                $this->session->set_userdata(PREFIX_SESS . "_namaGroup", $nama_group);
                $this->session->set_userdata(PREFIX_SESS . "_isActive", $is_active);
                $this->session->set_userdata(PREFIX_SESS . "_dbUsername", $dbusername);
                $this->config->set_item('database_name', $this->session->userdata(PREFIX_SESS . '_dbUsername'));

                $remember = $this->input->post('remember_me');
                if ($remember) {
                    $key = random_string('alnum', 64);
                    set_cookie(PREFIX_SESS . '_auth', $key, 3600 * 24 * 30); // set expired 30 hari kedepan
                    // simpan key di database
                    $update_key = array(
                        'cookie' => $key
                    );

                    $this->M_auth->updateCookie($update_key, $id_user);
                }

                $this->setDatabase();
                //CHANGE DATABASE BASED ON USER

                $this->setUserData();
                $log = ['id_user' => $id_user, 'activity' => 'LOG IN', 'page_url' => site_url('auth')];
                $this->setLog($log);

                redirect('dashboard');
            }
        } else if (!$userId && uri_string() == "dashboard") { // Accessing index page and there is no user session (login form state)
            //$this->template = "app_front";
            // cookie
            $cookie = get_cookie(PREFIX_SESS . '_auth');

            if ($cookie <> '') {

                $this->db->select([
                    'a.id_user',
                    'a.username', 'a.email', 'a.real_name',
                    'a.id_group', 'a.is_active', 'a.cookie', 'b.nama_group', 'b.keterangan', 'b.dbusername'
                ]);
                $this->db->join('user_group b', 'a.id_group = b.id_group', 'INNER');
                $this->db->where(['a.cookie' => $cookie, 'a.is_active' => '1']);
                $result = $this->db->get('user a');

                if ($result->num_rows() == 0) {
                    $this->session->set_flashdata('errorMessage', "Gagal login, silahkan periksa kembali informasi login Anda.");
                    $this->template = "app_front";
                    $this->data['title'] = 'Login';
                    redirect('auth');
                } else {
                    $row = $result->first_row();
                    $id_user = $row->id_user;
                    $nik = $row->username;
                    $nama = $row->real_name;
                    $email = $row->email;
                    $id_group = $row->id_group;
                    $nama_group = $row->nama_group;
                    $is_active = $row->is_active;
                    $dbusername = $row->dbusername;

                    $this->session->set_userdata(PREFIX_SESS . "_userId", $id_user);
                    $this->session->set_userdata(PREFIX_SESS . "_username", $nik);
                    $this->session->set_userdata(PREFIX_SESS . "_nama", $nama);
                    $this->session->set_userdata(PREFIX_SESS . "_email", $email);
                    $this->session->set_userdata(PREFIX_SESS . "_idGroup", $id_group);
                    $this->session->set_userdata(PREFIX_SESS . "_namaGroup", $nama_group);
                    $this->session->set_userdata(PREFIX_SESS . "_isActive", $is_active);
                    $this->session->set_userdata(PREFIX_SESS . "_dbUsername", $dbusername);
                    $this->config->set_item('database_name', $this->session->userdata(PREFIX_SESS . '_dbUsername'));

                    $this->setDatabase();
                    //CHANGE DATABASE BASED ON USER

                    $this->setUserData();
                    $log = ['id_user' => $id_user, 'activity' => 'LOG IN', 'page_url' => site_url('auth')];
                    $this->setLog($log);

                    redirect('dashboard');
                }
            } else {
                $this->template = "app_front";
                if ($this->input->get("access_without_login") == "true") {
                    $this->data["errorMessage"] = "Session anda telah berakhir, silahkan login kembali untuk masuk ke dashboard.";
                } else if ($this->input->get("logout") == "true") {
                    $this->data["successMessage"] = "Anda telah keluar.";
                } else if ($this->input->get("forgot_password") == "true") {
                    $this->data["successMessage"] = "Password anda berhasil diperbarui. Silahkan login dengan password baru.";
                }
                $this->data['title'] = 'Login';
                redirect('index');
            }
            // end
        } else if (!$userId && !in_array(uri_string(), $this->whitelistUrl) && $this->loginBehavior) { // Accessing user page and there is no user session
            $this->template = "app_front";
            redirect("?access_without_login=true");
        } else if ($userId != null) { // Accessing user page and there is user session
            $this->setUserData();
        }


        if (is_array($this->input->get())) {
            foreach ($this->input->get() as $key => $value) {
                $this->data[$key] = $value;
            }
        }

        if (is_array($this->input->post())) {
            foreach ($this->input->post() as $key => $value) {
                if ($key == "description" || $key == "email" || $key == "is_active" || $key == "is_soft_delete" || $key == "username" || $key == "real_name") {
                    $this->data[$key . "Input"] = $value;
                } else {
                    $this->data[$key] = $value;
                }
            }
        }

        // trim field from form submit
        if (is_array($_POST)) {
            $_POST = array_map(function ($row) {
                $row = is_string($row) ? trim($row) : $row;
                $row = $row === '' ? NULL : $row;
                return $row;
            }, $this->input->post());
        }
    }

    protected function setUserData()
    {
        $this->data[PREFIX_SESS . "_userId"] = $this->session->userdata(PREFIX_SESS . "_userId");
        $this->data[PREFIX_SESS . "_username"] = $this->session->userdata(PREFIX_SESS . "_username");
        $this->data[PREFIX_SESS . "_nama"] = $this->session->userdata(PREFIX_SESS . "_nama");
        $this->data[PREFIX_SESS . "_email"] = $this->session->userdata(PREFIX_SESS . "_email");
        $this->data[PREFIX_SESS . "_idGroup"] = $this->session->userdata(PREFIX_SESS . "_idGroup");
        $this->data[PREFIX_SESS . "_isActive"] = $this->session->userdata(PREFIX_SESS . "_isActive");
        $this->data[PREFIX_SESS . "_dbUsername"] = $this->session->userdata(PREFIX_SESS . "_dbUsername");
        $this->db->where(['id_user' => $this->data[PREFIX_SESS . "_userId"]])->update('user', ['last_login_time' => date('Y-m-d H:i:s')]);

        $result = $this->db->query("SELECT DISTINCT nama_modul, hak_akses FROM akses_group_modul WHERE id_group = ? ORDER BY nama_modul", array($this->session->userdata(PREFIX_SESS . "_idGroup")));

        $this->data["userMenus"] = array();
        if ($result) {
            foreach ($result->result() as $row) {
                $this->data["userMenus"][] = $row->nama_modul . "." . $row->hak_akses;
            }
        }
    }

    protected function create_captcha()
    {
        $files = glob('./public/captcha/*.jpg'); // get all file names
        foreach ($files as $file) {
            if (is_file($file))
                unlink($file); // delete file
        }

        $number = ' ' . rand(1000, 5000) . ' ';
        $vals = array(
            'word'          => $number,
            'img_path'      => './public/captcha/',
            'img_url'       => base_url('public/captcha'),
            'font_path'     => FCPATH . 'public/fonts/poppins/Poppins-SemiBold.ttf',
            'img_width'     => '250',
            'img_height'    => 50,
            'expiration'    => 3600,
            'word_length'   => 12,
            'font_size'     => 24,
            'img_id'        => 'imageid',
            'pool'          => '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',

            // White background and border, black text and red grid
            'colors'        => array(
                'background' => array(255, 255, 255),
                'border' => array(255, 255, 255),
                'text' => array(0, 0, 0),
                'grid' => array(255, 40, 40)
            )
        );

        $cap = create_captcha($vals);
        $this->session->set_userdata('captchaword', $cap['word']);
        return $cap['image'];
    }

    protected function unSetUserData()
    {
        $this->session->unset_userdata(PREFIX_SESS . "_userId");
        $this->session->unset_userdata(PREFIX_SESS . "_username");
        $this->session->unset_userdata(PREFIX_SESS . "_nama");
        $this->session->unset_userdata(PREFIX_SESS . "_email");
        $this->session->unset_userdata(PREFIX_SESS . "_idGroup");
        $this->session->unset_userdata(PREFIX_SESS . "_isActive");
        $this->session->unset_userdata(PREFIX_SESS . "_dbUsername");
        delete_cookie(PREFIX_SESS . '_auth');
    }

    protected function render($filename = null)
    {
        // if (empty($this->session->userdata(PREFIX_SESS . "_userId"))) {
        //     $this->template = "app_front";
        // }

        if (strtolower($this->uri->segment(1)) == 'auth') {
            $this->template = 'login';
        }

        $template = $this->load->view("template/" . $this->template, $this->data, true);

        $content = $this->load->view(($this->module != "" ? $this->module . "/" : "") . strtolower(get_class($this)) . "/" . $filename, $this->data, true);

        if ($this->module != NULL) {
            if (in_array($this->module . ".access", $this->data["userMenus"]) == 0) {
                $this->session->set_flashdata('false', 'Maaf, Anda tidak memiliki akses ke halaman tersebut.');
                redirect('dashboard');
            }
        }
        exit(str_replace("{CONTENT}", $content, $template));
    }

    // utkgrocery crud render
    protected function setOutput($output = null, $view = 'index')
    {
        if (isset($output->isJSONResponse) && $output->isJSONResponse) {
            header('Content-Type: application/json; charset=utf-8');
            echo $output->output;
            exit;
        }

        $content = (($this->module != "" ? $this->module . "/" : "") . strtolower(get_class($this)) . "/" . $view);
        $x = array_merge($this->data, ['output' => $output]);
        $this->layout->set_template('template/app');

        $this->layout->CONTENT->view($content, $x);
        if ($this->module != NULL) {
            if (in_array($this->module . ".access", $this->data["userMenus"]) == 0) {
                $this->session->set_flashdata('false', 'Maaf, Anda tidak memiliki akses ke halaman tersebut.');
                redirect('dashboard');
            }
        }

        $this->layout->publish();
    }

    protected function cek_hak_akses($hak_akses)
    {
        $cek = $this->db->query("SELECT * FROM `akses_group_modul` WHERE nama_modul=? AND hak_akses=? AND id_group=?", array($this->module, $hak_akses, $this->session->userdata(PREFIX_SESS . "_idGroup")))->row_array();
        if (empty($cek)) {
            $message = "Maaf, Anda tidak memiliki akses ke halaman ini.";
            echo "<script type='text/javascript'>alert('$message');</script>";
            redirect();
        } else {
            $hak_akses = $this->db->query("SELECT hak_akses FROM `akses_group_modul` WHERE nama_modul=? AND id_group=?", array($this->module, $this->session->userdata(PREFIX_SESS . "_idGroup")))->result_array();
            foreach ($hak_akses as $row) {
                $hasil[] = $row['hak_akses'];
            }
            return $hasil;
        }
    }

    protected function setDatabase()
    {
        $dbUsername = $this->session->userdata(PREFIX_SESS . '_dbUsername');
        $this->load->database($dbUsername, FALSE, TRUE); //CHANGE DATABASE BASED ON USER
    }

    protected function setLog($params)
    {
        $this->m_activity_log->setLog($params);
    }
}
