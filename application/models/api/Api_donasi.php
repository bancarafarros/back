<?php

defined('BASEPATH') or exit('No direct script access allowed');

class api_donasi extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    public function bayar_donasi($data)
    {
        $donasi = $this->db->insert('donasi_histories', $data);

        if ($donasi) {
            return $data;
        }

        return null;
    }

    public function getDonasi()
    {
        $this->db->select('d.id as id_donasi, d.nama_donasi, d.deskripsi_donasi as cerita, d.donasi_dibuka, d.donasi_ditutup, d.target_donasi, SUM(dh.nominal) as terkumpul, u.real_name as pembuat ');
        $this->db->from('donasi d');
        $this->db->join('user u', 'd.created_by=u.id_user', 'left');
        $this->db->join('donasi_histories dh', 'dh.donasi_id=d.id', 'left');
        $this->db->group_by('d.id');
        $donasi = $this->db->get();


        if ($donasi->num_rows() > 0) {
            return $donasi->result_array();
        }

        return null;
    }
}
