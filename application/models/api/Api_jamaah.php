<?php

defined('BASEPATH') or exit('No direct script access allowed');

class api_jamaah extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    public function delete_jamaah($id_user)
    {
        $return['success'] = false;
        $return['message'] = '';

        $data = [
            'is_active' => '0'
        ];

        // update on user table
        $this->db->trans_start();
        $this->db->where('id_user', $id_user);
        $this->db->update('user', $data);
        $this->db->reset_query();

        // update on jamaah table
        $this->db->where('id_user', $id_user);
        $this->db->update('jamaah', $data);
        $this->db->reset_query();

        if ($this->db->trans_status() == false) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            $this->db->trans_complete();
            return true;
        }
    }

    public function update_jamaah($id_user, $data)
    {

        $this->db->where('id_user', $id_user);
        $update = $this->db->update('jamaah', $data);

        if ($update) {
            return true;
        }

        return false;
    }

    public function get_jamaah($id_user)
    {
        $return['success'] = false;
        $return['data'] = null;

        $this->db->select('*');
        $this->db->where('id_user', $id_user);
        $this->db->where('is_active', '1');
        $jamaah =  $this->db->get('jamaah');

        if ($jamaah->num_rows() > 0) {
            $return['success'] = true;
            $return['data'] = $jamaah->row_array();

            return $return;
        } else {
            return $return;
        }
    }

    public function get_jamaah_by_masjid($id_masjid)
    {
        $return['success'] = false;
        $return['data'] = null;

        $this->db->select('*');
        $this->db->where('id_masjid', $id_masjid);
        $jamaah =  $this->db->get('jamaah');

        if ($jamaah->num_rows() > 0) {
            $return['success'] = true;
            $return['data'] = $jamaah->result_array();

            return $return;
        } else {
            return $return;
        }
    }
}
