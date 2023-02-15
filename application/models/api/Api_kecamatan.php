<?php

defined('BASEPATH') or exit('No direct script access allowed');

class api_kecamatan extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    function get_all()
    {
        $this->db->select('*');
        $this->db->from('ref_kecamatan');
        return $this->db->get();
    }

    function getByKabupaten($kode_kabupaten)
    {

        $this->db->select('*');
        $this->db->from('ref_kecamatan');
        $this->db->where('kode_kabupaten', $kode_kabupaten);
        return $this->db->get();
    }
}
