<?php

defined('BASEPATH') or exit('No direct script access allowed');

class api_pengurus extends CI_Model
{
    protected $table = 'masjid_pengurus';

    function __construct()
    {
        parent::__construct();
    }

    function update_pengurus($data, $id_pengurus)
    {
        $this->db->where('id', $id_pengurus);
        $update = $this->db->update('masjid_pengurus', $data);

        if ($update) {
            return true;
        } else {
            return false;
        }
    }

    function delete_pengurus($id_pengurus)
    {
        $this->db->where('id', $id_pengurus);

        $data = [
            'is_active' => '0'
        ];
        $update = $this->db->update('masjid_pengurus', $data);

        if ($update) {
            return true;
        } else {
            return false;
        }
    }

    function getIdPengurus($id_user)
    {
        $this->db->select('id');
        $this->db->from('masjid_pengurus');
        $this->db->where('id_user', $id_user);
        $id = $this->db->get()->row_array();

        return $id['id'];
    }

    function get_pengurus($id_pengurus)
    {
        $return['success'] = false;
        $return['data'] = '';

        $this->db->select('*');
        $this->db->from('masjid_pengurus');
        $this->db->where('id', $id_pengurus);
        $pengurus = $this->db->get();

        if ($pengurus->num_rows() > 0) {
            $return['success'] = true;
            $return['data'] = $pengurus->row_array();
        } else {
            $return['success'] = false;
            $return['data'] = null;
        }

        return $return;
    }

    function get_pengurus_list()
    {
        $return['success'] = false;
        $return['data'] = '';

        $this->db->select('*');
        $this->db->from('masjid_pengurus');
        $pengurus = $this->db->get();

        if ($pengurus->num_rows() > 0) {
            $return['success'] = true;
            $return['data'] = $pengurus->result_array();
        } else {
            $return['success'] = false;
            $return['data'] = null;
        }

        return $return;
    }

    function get_jabatan()
    {
        $return['success'] = false;
        $return['data'] = '';

        $type = $this->db->query("SHOW COLUMNS FROM masjid_pengurus WHERE Field = 'jabatan'")->row(0)->Type;
        preg_match("/^enum\(\'(.*)\'\)$/", $type, $matches);
        $enum = explode("','", $matches[1]);
        $return['success'] = true;
        $return['data'] = $enum;

        return $return;
    }

    function add_pengurus($pengurus, $user)
    {
        $return['success'] = false;
        $return['message'] = '';

        $cekNomorHP = $this->cekNomorHP('masjid_pengurus', $pengurus['no_hp']);
        $isEmailUsed = $this->isEmailUsed('masjid_pengurus', $pengurus['email']);

        if ($cekNomorHP) {
            $return['success'] = false;
            $return['message'] = 'No HP telah digunakan';
            return $return;
        }

        if ($isEmailUsed) {
            $return['success'] = false;
            $return['message'] = 'Email telah digunakan';
            return $return;
        }


        $this->db->trans_start();
        // insert to user
        $this->db->insert('user', $user);
        $pengurus['id_user'] = $this->db->insert_id();
        $this->db->reset_query();

        // insert to jamaah
        $this->db->insert('masjid_pengurus', $pengurus);
        $this->db->reset_query();

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            $return['success'] = false;
            $return['message'] = 'Tambah Pengurus Gagal.';
        } else {
            $this->db->trans_commit();
            $return['success'] = true;
            $return['message'] = 'Tambah Pengurus Berhasil';
        }
        $this->db->trans_complete();


        return $return;
    }

    public function cekNomorHP($table, $nomor_hp)
    {
        $get = $this->db->where(['no_hp' => $nomor_hp])->get($table);
        if ($get->num_rows() > 0) {
            $return = true;
        } else {
            $return = false;
        }
        return $return;
    }

    public function isEmailUsed($table, $email)
    {

        $get = $this->db->where(['email' => $email])->get($table);
        if ($get->num_rows() > 0) {
            $return = true;
        } else {
            $return = false;
        }
        return $return;
    }
}
