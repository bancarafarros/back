<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class api_profile extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function getProfile($id) 
    {
        $this->db->select('m.id, m.nim, m.nama_lengkap, m.tanggal_lahir, m.tempat_lahir, m.durasi_magang, m.url_portofolio, e1.nama_lengkap AS nama_mentor1, e2.nama_lengkap AS nama_mentor2, m.harapan, m.nomor_hp, m.email, p.nama_provinsi, k.nama_kabupaten, t.nama_kecamatan, s.nama, m.alamat, m.url_photo, m.jenis_kelamin, m.created_by');
        $this->db->from('peserta m');
        $this->db->join('ref_provinsi p', 'p.kode_provinsi = m.kode_provinsi', 'left');
        $this->db->join('ref_kabupaten k', 'k.kode_kabupaten = m.kode_kabupaten', 'left');
        $this->db->join('ref_kecamatan t', 't.kode_kecamatan = m.kode_kecamatan', 'left');
        $this->db->join('mentor e1', 'e1.id=m.id_mentor1', 'left');
        $this->db->join('mentor e2', 'e2.id=m.id_mentor2', 'left');
        $this->db->join('ref_posisi s', 's.id = m.id_posisi', 'left');
        $this->db->where('m.id_user', $id);
        $query = $this->db->get()->result();
        return $query;
    }

    function getProfile2($id) 
    {
        $this->db->select('m.id, m.nik, m.nama_lengkap, m.nomor_hp, m.email, p.nama_provinsi, k.nama_kabupaten, t.nama_kecamatan, s.nama, m.alamat, m.url_foto, m.jenis_kelamin, m.is_active, m.created_by');
        $this->db->from('mentor m');
        $this->db->join('ref_provinsi p', 'p.kode_provinsi = m.kode_provinsi', 'left');
        $this->db->join('ref_kabupaten k', 'k.kode_kabupaten = m.kode_kabupaten', 'left');
        $this->db->join('ref_kecamatan t', 't.kode_kecamatan = m.kode_kecamatan', 'left');
        $this->db->join('ref_posisi s', 's.id = m.id_posisi', 'left');
        $this->db->where('id_user', $id);
        $query = $this->db->get()->result();
        return $query;
    }

    function getIdGroup($id)
    {
        $this->db->select('id_group');
        $this->db->from('user');
        $this->db->where('id_user', $id);
        return $this->db->get()->row_array();
    }

    public function ubahProfile($id, $data, $tabel)
    {
        $this->db->where('id_user', $id);
        return $this->db->update($tabel, $data);
    }

    function getPhoto($id, $foto, $tabel)
    {
        $this->db->select($foto);
        $this->db->from($tabel);
        $this->db->where('id_user', $id);
        $result = $this->db->get();

        $data = $result->row_array();

        return $data[$foto];
    }

    public function getProvinsi($nama)
    {
        $this->db->select('kode_provinsi');
        $this->db->from('ref_provinsi');
        $this->db->where('nama_provinsi', $nama);
        $result = $this->db->get()->row_array();
        return  $result;
    }

    public function getKabupaten($nama)
    {
        $this->db->select('kode_kabupaten');
        $this->db->from('ref_kabupaten');
        $this->db->where('nama_kabupaten', $nama);
        return $this->db->get()->row_array();
    }

    public function getKecamatan($nama)
    {
        $this->db->select('kode_kecamatan');
        $this->db->from('ref_kecamatan');
        $this->db->where('nama_kecamatan', $nama);
        return $this->db->get()->row_array();
    }

    public function getPosisi($nama)
    {
        $this->db->select('id');
        $this->db->from('ref_posisi');
        $this->db->where('nama', $nama);
        return $this->db->get()->row_array();
    }
}
