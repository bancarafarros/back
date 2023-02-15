<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Api_referensi extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    public function typologi($id = "", $type_for = "")
    {
        $return['status'] = 0;
        $return['data']   = [];

        $this->db->select("*");
        $this->db->from("ref_typologi");
        if ($id != "") {
            $this->db->where("id", $id);
        }

        if ($type_for != "") {
            $this->db->where("type_for", $type_for);
        }

        $get = $this->db->get();

        if ($get->num_rows() != 0) {
            $data = [];
            if ($id != "") {
                $data = $get->row_object();
            } else {
                $data = $get->result_object();
            }

            $return['status'] = 201;
            $return['data']   = $data;
        } else {
            $return['status'] = 500;
            $return['data']   = [];
        }
        return $return;
    }

    function getKabupaten($kode_provinsi = '')
    {
        $return['status'] = 0;
        $return['data']   = [];


        $this->db->select('*');
        $this->db->from('ref_kabupaten');
        if ($kode_provinsi != '') {
            $this->db->where('kode_provinsi', $kode_provinsi);
        }
        $get = $this->db->get();
        if ($get->num_rows() != 0) {
            $return['status'] = 201;
            $return['data']   = $get->result_object();
        } else {
            $return['status'] = 500;
            $return['data']   = [];
        }
        return $return;
    }

    function getKecamatan($kode_kabupaten = '')
    {
        $return['status'] = 0;
        $return['data']   = [];


        $this->db->select('*');
        $this->db->from('ref_kecamatan');
        if ($kode_kabupaten != '') {
            $this->db->where('kode_kab_kota', $kode_kabupaten);
        }
        $get = $this->db->get();
        if ($get->num_rows() != 0) {
            $return['status'] = 201;
            $return['data']   = $get->result_object();
        } else {
            $return['status'] = 500;
            $return['data']   = [];
        }
        return $return;
    }
    function afiliasi()
    {
        $return['status'] = 0;
        $return['data']   = [];


        $this->db->select('*');
        $this->db->from('ref_afiliasi');
        $get = $this->db->get();
        if ($get->num_rows() != 0) {
            $return['status'] = 201;
            $return['data']   = $get->result_object();
        } else {
            $return['status'] = 500;
            $return['data']   = [];
        }
        return $return;
    }

    function statusTanah()
    {
        $return['status'] = 0;
        $return['data']   = [];


        $this->db->select('*');
        $this->db->from('ref_status_tanah');
        $get = $this->db->get();
        if ($get->num_rows() != 0) {
            $return['status'] = 201;
            $return['data']   = $get->result_object();
        } else {
            $return['status'] = 500;
            $return['data']   = [];
        }
        return $return;
    }

}
