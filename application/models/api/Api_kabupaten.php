<?php

defined('BASEPATH') or exit('No direct script access allowed');

class api_kabupaten extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    function get_all()
    {
        $this->db->select('*');
        $this->db->from('ref_kabupaten');
        return $this->db->get();
    }

    function getByProvinsi($kode_provinsi)
    {

        $this->db->select('*');
        $this->db->from('ref_kabupaten');
        $this->db->where('kode_provinsi', $kode_provinsi);
        return $this->db->get();
    }
}
