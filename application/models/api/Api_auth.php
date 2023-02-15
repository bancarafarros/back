<?php

defined('BASEPATH') or exit('No direct script access allowed');

class api_auth extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }


    public function getIdByEmail($email)
    {
        $this->db->select('id_user');
        $this->db->where('email', $email);
        $id = $this->db->get('user')->row_array();

        return $id['id_user'];
    }

    function daftar_jamaah($jamaah, $user)
    {
        $return['success'] = false;
        $return['message'] = '';

        $this->db->trans_start();
        // insert to user
        $data = $this->db->get_where('user', array('email' => $user['email']))->num_rows();
        if ($data == 0) {
            $this->db->insert('user', $user);
            $jamaah['id_user'] = $this->db->insert_id();
            $this->db->reset_query();

            // insert to jamaah
            $this->db->insert('jamaah', $jamaah);
            $this->db->reset_query();

            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();
                $return['success'] = false;
                $return['message'] = 'Pendaftaran Jamaah Gagal.';
            } else {
                $this->db->trans_commit();
                $return['success'] = true;
                $return['message'] = 'Pendaftaran Jamaah Berhasil';
            }
        } else {
            $this->db->trans_rollback();
            $return['success'] = false;
            $return['message'] = 'Pendaftaran jamaah gagal. Email yang didaftarkan tidak boleh sama dengan yang sudah ada.';
        }
        $this->db->trans_complete();

        return $return;
    }

    public function verifikasi_register($id_user)
    {
        $return['success'] = false;
        $return['message'] = '';

        $this->db->trans_start();
        // insert to user
        $this->db->where('id_user', $id_user);
        $this->db->update('user', ['is_active' => '1']);
        $this->db->reset_query();

        // insert to jamaah
        $this->db->where('id_user', $id_user);
        $this->db->update('jamaah', ['is_active' => '1']);
        $this->db->reset_query();

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            $return['success'] = false;
            $return['message'] = 'Pendaftaran Jamaah Gagal.';
        } else {
            $this->db->trans_commit();
            $return['success'] = true;
            $return['message'] = 'Pendaftaran Jamaah Berhasil';
        }
        $this->db->trans_complete();

        return $return;
    }

    function validEmailToReset($email)
    {
        $this->db->select('id_user');
        $this->db->from('user');
        $this->db->where('email', $email);
        $user = $this->db->get();

        // var_dump($user->num_rows());
        // die;
        if ($user->num_rows() > 0) {
            return [
                'valid' => true,
                'data' => $user->row_array()
            ];
        }
        return [
            'valid' => false,
            'data' => null
        ];
    }

    public function reset_password($id_user, $password)
    {
        $this->db->where('id_user', $id_user);
        $update = $this->db->update('user', ['password' => $password]);

        if ($update) {
            return true;
        }

        return false;
    }

    function resetToken($data)
    {
        return $this->db->insert('reset_tokens', $data);
    }

    function isvalidToken($token)
    {
        $this->db->select('*');
        $this->db->from('reset_tokens');
        $this->db->where('token_key', $token);

        $token = $this->db->get();

        if ($token->num_rows() > 0) {
            return $token->row_array();
        }

        return null;
    }



    function setTokenValid($is_valid,  $id_token)
    {
        $data = [];

        if ($is_valid == '1') {
            $data = [
                'is_valid' => '1'
            ];
        } else {
            $data = [
                'is_valid' => '2'
            ];
        }

        $this->db->where('id', $id_token);
        $update = $this->db->update('reset_tokens', $data);

        if ($update) {
            return true;
        }

        return false;
    }

    public function getUserByToken($token)
    {
        $this->db->select('user_id');
        $this->db->from('reset_tokens');
        $this->db->where('token_key', $token);

        $user = $this->db->get();

        if ($user->num_rows() > 0) {
            return $user->row_array();
        } else {
            return null;
        }
    }




    public function cekEmail($email)
    {
        $return['used'] = false;
        $return['message'] = '';
        $get = $this->db->where(['email' => $email])->get('user');
        if ($get->num_rows() > 0) {
            $return['used'] = true;
            $return['message'] = 'Email telah digunakan';
        } else {
            $return['used'] = false;
            $return['message'] = null;
        }
        return $return;
    }

    public function cekNomorHP($nomor_hp)
    {
        $return['used'] = false;
        $return['message'] = '';
        $get = $this->db->where(['no_hp' => $nomor_hp])->get('jamaah');
        if ($get->num_rows() > 0) {
            $return['used'] = true;
            $return['message'] = 'Nomor HP / Username telah digunakan';
        } else {
            $return['used'] = false;
            $return['message'] = null;
        }
        return $return;
    }


    public function ceklogin($username, $password)
    {
        $this->db->select('user.id_user, user.username, user.email, user.real_name, user.id_group, user.is_active, user_group.nama_group, user_group.keterangan');
        $this->db->from('user');
        $this->db->join('user_group', 'user_group.id_group = user.id_group', 'left');
        $this->db->where("(user.email = '$username' OR user.username = '$username')");
        $this->db->where('user.password', $password);
        $result = $this->db->get();
        return $result;
    }

    public function cekpass($id_user, $password)
    {
        $this->db->select('id_user');
        $this->db->from('user');
        $this->db->where('id_user', $id_user);
        $this->db->where('password', $password);
        $result = $this->db->get();
        return $result;
    }

    public function ubahPass($id_user, $data)
    {
        $this->db->where('id_user', $id_user);
        return $this->db->update('user', $data);
    }

    public function getToken($token)
    {
        $this->db->select('*');
        $this->db->from('reset_tokens');
        $this->db->where('token', $token);
        $result = $this->db->get()->row_array();
        return $result;
    }

    public function hapusToken($token)
    {
        $this->db->where('token', $token);
        return $this->db->delete('reset_tokens');
    }

    public function resetPass($user_id, $data)
    {
        $this->db->where('id_user', $user_id);
        return $this->db->update('user', $data);
    }


    public function getUser($id_user)
    {
        $this->db->select('email, real_name');
        $this->db->from('user');
        $this->db->where('id_user', $id_user);

        $user = $this->db->get();

        if ($user->num_rows() > 0) {
            return $user->row_array();
        }

        return null;
    }
}
