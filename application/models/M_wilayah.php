<?php

class M_wilayah extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    public function getListProvinsi()
    {
        $this->db->order_by('nama_provinsi', 'ASC');

        $results = $this->db->get('ref_provinsi')->result_array();
        $return = array();

        foreach ($results as $key => $value) {
            $return[$value['kode_provinsi']] = $value["nama_provinsi"];
        }

        return $return;
    }

    public function getListKabupaten()
    {
        $this->db->order_by('nama_kab_kota', 'ASC');
        $results = $this->db->get('ref_kabupaten')->result_array();
        $return = array();

        foreach ($results as $key => $value) {
            $return[$value['kode_kab_kota']] = $value["nama_kab_kota"];
        }

        return $return;
    }

    public function getListKecamatan()
    {
        $this->db->select(['kode_kecamatan', 'nama_kecamatan']);
        $this->db->order_by('nama_kecamatan', 'ASC');
        $this->db->join('ref_kabupaten kab', 'kec.kode_kab_kota = kab.kode_kab_kota');
        $results = $this->db->get('ref_kecamatan kec')->result_array();
        $return = array();

        foreach ($results as $key => $value) {
            $return[$value['kode_kecamatan']] = $value["nama_kecamatan"];
        }

        return $return;
    }
}
