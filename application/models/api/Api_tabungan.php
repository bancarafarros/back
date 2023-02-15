<?php

defined('BASEPATH') or exit('No direct script access allowed');

class api_tabungan extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    public function bayar_tabungan($data)
    {
        $return['success'] = false;
        $return['message'] = '';


        // update on user table
        $this->db->trans_start();

        $this->db->insert('jamaah_tabungan_pembayaran', $data);
        $this->db->reset_query();

        // update on jamaah table
        $this->db->select('sisa_target');
        $this->db->where('id', $data['tabungan_id']);
        $sisa = $this->db->get('jamaah_tabungan')->row_array();
        $sisa_pembayaran = $sisa['sisa_target'];

        $this->db->select('nominal');
        $this->db->where('tabungan_id', $data['tabungan_id']);
        $this->db->order_by('id', 'DESC');
        // $this->db->get
        $this->db->limit(1);
        $total = $this->db->get('jamaah_tabungan_pembayaran')->row_array();

        // var_dump($total);
        // die;


        $total_pembayaran = [
            'sisa_target' => intval($sisa_pembayaran) - intval($total['nominal'])
        ];

        $this->db->where('id', $data['tabungan_id']);
        $this->db->update('jamaah_tabungan', $total_pembayaran);

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

    public function get_detail_tabungan($ref_tabungan)
    {
        $this->db->where('ref_tabungan', $ref_tabungan);
        $detail = $this->db->get('ref_tabungan_detail');


        return $detail->row_array();
    }

    public function add_tabungan($data)
    {
        $insert = $this->db->insert('jamaah_tabungan', $data);

        if ($insert) {
            return true;
        }

        return false;
    }

    public function get_tabungan()
    {
        $this->db->select('rt.*, rtd.*');
        $this->db->from('ref_tabungan rt');
        $this->db->join('ref_tabungan_detail rtd', 'rtd.ref_tabungan=rt.id');
        $tabungan = $this->db->get();
        if ($tabungan->num_rows() > 0) {
            return $tabungan->result_array();
        }

        return null;
    }

    public function get_tagihan($id_user)
    {
        $this->db->select('jt.id, jt.jumlah_qurban, jt.sisa_target, jt.register_time, SUM(jtp.nominal) as total_pembayaran, rt.nama, rt.nominal as total_tagihan, rtd.lama_bulan, rtd.setoran_perbulan');
        $this->db->from('jamaah_tabungan jt');
        $this->db->join('jamaah_tabungan_pembayaran jtp', 'jtp.tabungan_id=jt.id', 'left');
        $this->db->join('ref_tabungan rt', 'rt.id=jt.ref_tabungan', 'left');
        $this->db->join('ref_tabungan_detail rtd', 'rtd.id=jt.ref_tabungan_detail', 'left');
        $this->db->join('jamaah j', 'j.id=jt.jamaah_id', 'left');
        $this->db->where('j.id_user', $id_user);
        $this->db->group_by('jt.id');
        $tagihan = $this->db->get();

        if ($tagihan->num_rows() > 0) {
            return $tagihan->result_array();
        }

        return null;
    }
}
