<?php

defined('BASEPATH') or exit('No direct script access allowed');

class api_berita extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }


    public function deleteBerita($id_berita)
    {
        $this->db->where('id_berita', $id_berita);
        $delete = $this->db->update('berita', ['is_active' => '0']);

        if ($delete) {
            return true;
        }

        return false;
    }

    public function update_berita($id_berita, $data)
    {
        // var_dump($id_berita);
        // var_dump($data);
        // die;
        $this->db->where('id_berita', $id_berita);
        $update = $this->db->update('berita', $data);
        if ($update) {
            return true;
        }
        return false;
    }
    public function getBerita()
    {
        $this->db->select('rk.kategori, b.*, u.real_name as author');
        $this->db->from('berita b');
        $this->db->join('ref_kategori rk', 'rk.id=b.ref_kategori', 'left');
        $this->db->join('user u', 'u.id_user=b.id_user', 'left');
        $berita = $this->db->get();

        if ($berita->num_rows() > 0) {
            return $berita->result_array();
        }

        return null;
    }

    public function getBeritaById($id_berita)
    {
        $this->db->where('id_berita', $id_berita);
        $berita = $this->db->get('berita');

        if ($berita->num_rows() > 0) {
            return $berita->row_array();
        }

        return null;
    }

    public function addBerita($berita)
    {
        $berita = $this->db->insert('berita', $berita);
        if ($berita) {
            return true;
        }

        return false;
    }

    public function isSlugExist($judul)
    {
        $this->db->where("judul", $judul);
        $q = $this->db->get("berita");
        $this->db->reset_query();

        if ($q->num_rows() > 0) {
            return $q->num_rows();
        } else {
            return null;
        }
    }
}
