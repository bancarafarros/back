<?php

defined('BASEPATH') or exit('No direct script access allowed');

class api_kas extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    public function get_kas_log($id_kas)
    {
        $this->db->select('*');
        $this->db->from('masjid_kas_log');
        $this->db->where('id_kas', $id_kas);
        $this->db->order_by('created_at', 'DESC');
        $this->db->limit(1);

        $kas_log = $this->db->get();
        if ($kas_log->num_rows() > 0) {
            return $kas_log->row_array();
        } else {
            return null;
        }
    }

    public function isMasjidExist($id_masjid)
    {
        $this->db->where('id_masjid', $id_masjid);
        $masjid = $this->db->get('masjid_kas');

        if ($masjid->num_rows() > 0) {
            return true;
        }
        return false;
    }

    public function delete_kas($id_kas, $id_kas_log, $data)
    {
        $this->db->trans_start();


        $this->db->where('id', $id_kas);
        $this->db->update('masjid_kas', $data);
        $this->db->reset_query();


        $this->db->where('id', $id_kas_log);
        $this->db->update('masjid_kas_log', ['is_active' => '1']);
        $this->db->reset_query();

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            $this->db->trans_complete();
            return true;
        }
    }
    public function get_kas($id_kas)
    {
        $return['success'] = false;
        $return['data'] = null;

        $this->db->select('*');
        $this->db->from('masjid_kas');
        $this->db->where('id', $id_kas);
        $kas = $this->db->get();

        if ($kas->num_rows() > 0) {
            $return['success'] = true;
            $return['data'] = $kas->row_array();

            return $return;
        }

        return $return;
    }

    public function add_kas($kas, $kas_log)
    {
        $this->db->trans_start();

        // insert to kas
        $this->db->insert('masjid_kas', $kas);
        $this->db->reset_query();

        // insert to kas log
        $this->db->insert('masjid_kas_log', $kas_log);
        $this->db->reset_query();

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            $this->db->trans_complete();
            return true;
        }
    }
    public function update_kas($id_kas, $kas, $kas_log)
    {
        $this->db->trans_start();

        // insert to kas
        $this->db->where('id', $id_kas);
        $update = $this->db->update('masjid_kas', $kas);
        $this->db->reset_query();

        // insert to kas log
        $this->db->insert('masjid_kas_log', $kas_log);
        $this->db->reset_query();

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            $this->db->trans_complete();
            return true;
        }
    }
}
