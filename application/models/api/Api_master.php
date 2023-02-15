<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class api_master extends CI_Model {

    function __construct() {
        parent::__construct();
    }
    
    function provinsi_data() {
        $return['success'] = false;
        $return['message'] = '';
        $return['data']    = null;
        $get = $this->db->query("
            SELECT kode_provinsi, nama_provinsi 
            FROM ref_provinsi
            ORDER BY nama_provinsi ASC
        ");
        if ($get->num_rows() > 0) {
            $result = null;
            foreach ($get->result() as $key => $r) {
                $result[$key]['kode_provinsi'] = $r->kode_provinsi;
                $result[$key]['nama_provinsi'] = $r->nama_provinsi;
            }
            $return['success'] = true;
            $return['message'] = 'Data ditemukan';
            $return['data']    = $result;
        }else{
            $return['success'] = false;
            $return['message'] = 'Data tidak ditemukan.';
            $return['data'] = null;
        }
        return $return;
    }

    function kabupatenkota_data($kode_provinsi = null) {
        $return['success'] = false;
        $return['message'] = '';
        $return['data']    = null;

        if ($kode_provinsi == null) {
            $get = $this->db->query("
                SELECT 
                id, kode_provinsi, 
                kode_kabupaten as kode_kabupaten_kota, 
                nama_kabupaten as nama_kabupaten_kota
                FROM ref_kabupaten 
                ORDER BY kode_kabupaten ASC
            ");
        } else {
            $get = $this->db->query("
                SELECT 
                id, kode_provinsi, 
                kode_kabupaten as kode_kabupaten_kota, 
                nama_kabupaten as nama_kabupaten_kota
                FROM ref_kabupaten 
                WHERE kode_provinsi = ?
                ORDER BY kode_kabupaten_kota ASC, nama_kabupaten_kota ASC
            ", array($kode_provinsi));
        }

        if ($get->num_rows() > 0) {
            $result = null;
            foreach ($get->result() as $key => $r) {
                $result[$key]['kode_kabupaten_kota'] = $r->kode_kabupaten_kota;
                $result[$key]['kode_provinsi']       = $r->kode_provinsi;
                $result[$key]['nama_kabupaten_kota'] = $r->nama_kabupaten_kota;
            }
            $return['success'] = true;
            $return['message'] = 'Data ditemukan';
            $return['data']    = $result;
        }else{
            $return['success'] = false;
            $return['message'] = 'Data tidak ditemukan.';
            $return['data'] = null;
        }

        return $return;
    }

    function kecamatan_data($kode_kabupaten_kota = null) {
        $return['success'] = false;
        $return['message'] = '';
        $return['data']    = null;
        if ($kode_kabupaten_kota == null) {
            $get = $this->db->query("
                SELECT 
                id, 
                kode_kabupaten as kode_kabupaten_kota,
                kode_kecamatan, nama_kecamatan 
                FROM ref_kecamatan 
                ORDER BY kode_kecamatan ASC, nama_kecamatan ASC
            ");
        } else {
            $get = $this->db->query("
                SELECT 
                id, 
                kode_kabupaten as kode_kabupaten_kota,
                kode_kecamatan, nama_kecamatan 
                FROM ref_kecamatan 
                WHERE kode_kabupaten =?
                ORDER BY kode_kecamatan ASC, nama_kecamatan ASC
            ", array($kode_kabupaten_kota));
        }

        if ($get->num_rows() > 0) {
            $result = null;
            foreach ($get->result() as $key => $r) {
                $result[$key]['kode_kecamatan']      = $r->kode_kecamatan;
                $result[$key]['kode_kabupaten_kota'] = $r->kode_kabupaten_kota;
                $result[$key]['nama_kecamatan']      = $r->nama_kecamatan;
            }

            $return['success'] = true;
            $return['message'] = 'Data ditemukan.';
            $return['data']    = $result;
        }else{
            $return['success'] = false;
            $return['message'] = 'Data ditemukan.';
            $return['data']    = null;
        }

        return $return;
    }

    function universitas_data() {
        $return['success'] = false;
        $return['message'] = '';
        $return['data']    = null;
        $get = $this->db->query("
            SELECT npsn, nama, jenis, status, wilayah, kode_provinsi, kode_kabupaten
            FROM ref_instansi
            ORDER BY nama ASC
        ");
        if ($get->num_rows() > 0) {
            $result = null;
            foreach ($get->result() as $key => $r) {
                $result[$key]['npsn'] = $r->npsn;
                $result[$key]['nama'] = $r->nama;
                $result[$key]['jenis'] = $r->jenis;
                $result[$key]['status'] = $r->status;
                $result[$key]['wilayah'] = $r->wilayah;
                $result[$key]['kode_provinsi'] = $r->kode_provinsi;
                $result[$key]['kode_kabupaten'] = $r->kode_kabupaten;
            }
            $return['success'] = true;
            $return['message'] = 'Data ditemukan';
            $return['data']    = $result;
        }else{
            $return['success'] = false;
            $return['message'] = 'Data tidak ditemukan.';
            $return['data'] = null;
        }
        return $return;
    }

    function periodemagang_data() {
        $return['success'] = false;
        $return['message'] = '';
        $return['data']    = null;
        $get = $this->db->query("
            SELECT id, nama, periode, deskripsi, tanggal_mulai, tanggal_selesai, url_banner, kategori 
            FROM ref_periode_magang
            ORDER BY nama ASC
        ");
        if ($get->num_rows() > 0) {
            $result = null;
            foreach ($get->result() as $key => $r) {
                $result[$key]['id'] = $r->id;
                $result[$key]['nama'] = $r->nama;
                $result[$key]['periode'] = $r->periode;
                $result[$key]['deskripsi'] = $r->deskripsi;
                $result[$key]['tanggal_mulai'] = $r->tanggal_mulai;
                $result[$key]['tanggal_selesai'] = $r->tanggal_selesai;
                $result[$key]['url_banner'] = $r->url_banner;
                $result[$key]['kategori'] = $r->kategori;
            }
            $return['success'] = true;
            $return['message'] = 'Data ditemukan';
            $return['data']    = $result;
        }else{
            $return['success'] = false;
            $return['message'] = 'Data tidak ditemukan.';
            $return['data'] = null;
        }
        return $return;
    }

    function posisimagang_data() {
        $return['success'] = false;
        $return['message'] = '';
        $return['data']    = null;
        $get = $this->db->query("
            SELECT id, nama, is_for_magang 
            FROM ref_posisi
            WHERE is_for_magang = 1
            ORDER BY id ASC
        ");
        if ($get->num_rows() > 0) {
            $result = null;
            foreach ($get->result() as $key => $r) {
                $result[$key]['id'] = $r->id;
                $result[$key]['nama'] = $r->nama;
            }
            $return['success'] = true;
            $return['message'] = 'Data ditemukan';
            $return['data']    = $result;
        }else{
            $return['success'] = false;
            $return['message'] = 'Data tidak ditemukan.';
            $return['data'] = null;
        }
        return $return;
    }
}
