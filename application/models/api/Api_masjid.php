<?php

defined('BASEPATH') or exit('No direct script access allowed');

class api_masjid extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    public function simpan($params)
    {
        $return['status']  = 0;
        $return['message'] = '';
    }

    function get_all()
    {
        $this->db->select('*');
        $this->db->from('masjid');
        $this->db->where('is_verified', '1');
        $this->db->order_by('created_at', 'DESC');
        return $this->db->get()->result();
    }

    function get_masjid_active()
    {
        $this->db->select('*');
        $this->db->from('masjid');
        $this->db->where('status', '1');
        return $this->db->get()->result();
    }

    function profil($id_masjid)
    {
        $return['status'] = 0;
        $return['data']   = [];

        $this->db->select("");
        $this->db->where('id', $id_masjid);
        $masjid = $this->db->get('masjid');

        if ($masjid->row_array() > 0) {
            $response['success'] = true;
            $response['data'] = $masjid->row_array();
        } else {
            $response['success'] = false;
            $response['data'] = null;
        }

        return $response;
    }

    function getIdMasjid($id_user)
    {
        $this->db->select('id');
        $this->db->from('masjid');
        $this->db->where('id_user', $id_user);

        $masjid = $this->db->get()->row_array();

        return $masjid;
    }

    function update_profil($data, $id_masjid)
    {
        $this->db->where('id', $id_masjid['id']);
        $update = $this->db->update('masjid', $data);

        return $update;
    }

    public function register($params)
    {
        $return['status']  = 0;
        $return['message'] = '';
        $return['token_key'] = '';

        $check = checkAccount(['username' => $params['email']]);

        if ($check['status'] == 201) {
            $return['status']  = 500;
            $return['message'] = 'Email sudah terdaftar. Silahkan cek kembali data anda.';
        } else {
            $data = filterFieldsOfTable('masjid', $params);
            $data['id']          = getUUID();
            $data['is_verified'] = '0';

            $user['real_name']  = $params['nama'];
            $user['username']   = $params['email'];
            $user['email']      = $params['email'];
            $user['id_group']   = '2';
            $user['is_active']  = '1';
            $user['password']   = hash('sha256', $params['password']);

            $this->db->trans_begin();
            $this->db->insert("user", $user);
            $id_user = $this->db->insert_id();

            $data['id_user']    = $id_user;
            $this->db->insert('masjid', $data);
            $id_masjid = $this->db->get_where('masjid', array('nama' => $params['nama']))->row()->id;

            $pengurus['id'] = getUUID();
            $pengurus['id_masjid'] = $id_masjid;
            $pengurus['id_user'] = $id_user;
            $pengurus['nama'] = $params['nama_pj_takmir'];
            $pengurus['jenis_kelamin'] = 'Laki-Laki';
            $pengurus['no_hp'] = $params['no_hp'];
            $pengurus['email'] = $params['email'];
            $pengurus['kode_provinsi'] = $params['kode_provinsi'];
            $pengurus['kode_kabupaten'] = $params['kode_kabupaten'];
            $pengurus['kode_kecamatan'] = $params['kode_kecamatan'];
            $pengurus['jabatan'] = $params['jabatan_takmir'];

            $this->db->insert('masjid_pengurus', $pengurus);
            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();
                $return['status']  = 500;
                $return['message'] = 'Pendaftaran masjid gagal. Silahkan cek kembali data anda';
            } else {
                $this->db->trans_commit();
                $return['status']  = 201;
                $return['message'] = 'Pendaftaran masjid berhasil. Gunakan email dan password anda untuk login ke dalam apliasi dan melengkapi profil anda';
                $return['token_key'] = $data['id'];
            }
            $this->db->trans_complete();
        }
        return $return;
    }
}
