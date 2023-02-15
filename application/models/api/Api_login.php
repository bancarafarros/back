<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class api_login extends CI_Model {

    private $allowed_user_group = [5]; 

    function __construct() {
        parent::__construct();
    }

    function login($username, $password) {
        //cek username
        $get = $this->db->query("SELECT id_user, id_group FROM user WHERE ((username IS NOT NULL AND LOWER(username) = ?) OR (email IS NOT NULL AND LOWER(email) = ?))", array($username, $username));
        if ($get->num_rows() == 0) {
            return ["status" => "failed", "message" => "Login gagal. User tidak ditemukan."];
        }

        //cek password
        $get = $this->db->query("SELECT id_user, id_group FROM user WHERE ((username IS NOT NULL AND LOWER(username) = ?) OR (email IS NOT NULL AND LOWER(email) = ?)) AND password = SHA2(?,256)", array($username, $username, $password));
        if ($get->num_rows() == 0) {
            return ["status" => "failed", "message" => "Login gagal. Password tidak sesuai."];
        }

        //cek status
        $get = $this->db->query("SELECT id_user, id_group, is_active FROM user WHERE ((username IS NOT NULL AND LOWER(username) = ?) OR (email IS NOT NULL AND LOWER(email) = ?))", array($username, $username))->row_array();
        if ($get['is_active'] == '0') {
            return ["status" => "failed", "message" => "Login gagal. User telah dinonaktifkan."];
        } else if (!(in_array($get['id_group'], $this->allowed_user_group))) {
            return ["status" => "failed", "message" => "Login gagal. User role tidak diperbolehkan."];
        } else if ($get['id_group'] == null || $get['id_group'] == '0') {
            return ["status" => "failed", "message" => "Login gagal. User role tidak ditemukan."];
        }
        
        $id_user = $get['id_user'];
        $id_group = $get['id_group'];
        return ["status" => "ok", "data" => ['id_user' => $id_user, 'id_group' => $id_group]];
    }

    function check_user($username) {
        //cek username
        $get = $this->db->query("SELECT id_user, id_group FROM user WHERE ((username IS NOT NULL AND LOWER(username) = ?) OR (email IS NOT NULL AND LOWER(email) = ?))", array($username, $username));
        if ($get->num_rows() == 0) {
            return ["status" => "failed", "message" => "Refresh token gagal. User tidak ditemukan."];
        }

        //cek status
        $get = $this->db->query("SELECT id_user, id_group, is_active FROM user WHERE ((username IS NOT NULL AND LOWER(username) = ?) OR (email IS NOT NULL AND LOWER(email) = ?))", array($username, $username))->row_array();
        if ($get['is_active'] == '0') {
            return ["status" => "failed", "message" => "Refresh token gagal. User telah dinonaktifkan."];
        } else if (!(in_array($get['id_group'], $this->allowed_user_group))) {
            return ["status" => "failed", "message" => "Login gagal. User role tidak diperbolehkan."];
        } else if ($get['id_group'] == null || $get['id_group'] == '0') {
            return ["status" => "failed", "message" => "Login gagal. User role tidak ditemukan."];
        }
        
        $id_user = $get['id_user'];
        $id_group = $get['id_group'];
        return ["status" => "ok", "data" => ['id_user' => $id_user, 'id_group' => $id_group]];
    }
}
