<?php

defined('BASEPATH') or exit('No direct script access allowed');

class api_provinsi extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    function get_all()
    {
        $this->db->select('*');
        $this->db->from('ref_provinsi');
        $this->db->order_by('nama_provinsi', 'asc');
        return $this->db->get();
    }
}
