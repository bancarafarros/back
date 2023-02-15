<?php

defined('BASEPATH') or exit('No direct script access allowed');

class api_payment extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    public function riwayat_transaksi_tabungan($id_user)
    {
        $this->db->select('*, rb.nama, rb.icon_url');
        $this->db->from('jamaah_tabungan_pembayaran jtp');
        $this->db->join('ref_bank rb', 'rb.id=jtp.ref_bank', 'left');
        $this->db->join('jamaah_tabungan jt', 'jt.id=jtp.tabungan_id', 'left');
        $this->db->join('ref_tabungan rt', 'rt.id=jt.ref_tabungan', 'left');
        $this->db->where('jtp.created_by', $id_user);
        $this->db->where('jtp.is_valid', '1');
        $tabungan = $this->db->get();

        if ($tabungan->num_rows() > 0) {
            return $tabungan->result_array();
        }

        return null;
    }

    public function channel_pembayaran()
    {
        $this->db->where('is_active', '1');
        $bank = $this->db->get('ref_bank');

        if ($bank->num_rows() > 0) {
            return $bank->result_array();
        }

        return null;
    }

    public function request_transaksi_tabungan($data)
    {
        $insert = $this->db->insert('jamaah_tabungan_pembayaran', $data);
        if ($insert) {
            return true;
        }

        return false;
    }

    public function ref_bank($kode)
    {
        $this->db->where('kode', $kode);
        $bank = $this->db->get('ref_bank');
        return $bank->row_array();
    }

    public function konfirmasi_pembayaran($data)
    {
        $return['success'] = false;
        $return['message'] = '';


        $this->db->trans_start();
        $this->db->insert('konfirmasi_pembayaran', $data);
        $this->db->reset_query();


        if ($data['status'] == '02') {
            $this->db->where('nomer_invoice', $data['nomor_invoice']);
            $this->db->update('jamaah_tabungan_pembayaran', ['is_valid' => '1']);
            $this->db->reset_query();

            $this->db->select('tabungan_id');
            $this->db->where('nomer_invoice', $data['nomor_invoice']);
            $jamaah_tabungan = $this->db->get('jamaah_tabungan_pembayaran')->row_array();
            $id_tabungan = $jamaah_tabungan['tabungan_id'];
            $this->db->reset_query();

            $this->db->select('sisa_target');
            $this->db->where('id', $id_tabungan);
            $tabungan = $this->db->get('jamaah_tabungan')->row_array();
            $this->db->reset_query();

            $sisa_target = intval($tabungan['sisa_target']) - intval($data['jumlah_dana']);
            $this->db->where('id', $id_tabungan);
            $this->db->update('jamaah_tabungan', ['sisa_target' => $sisa_target]);
            $this->db->reset_query();
        }

        if ($this->db->trans_status() == false) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            $this->db->trans_complete();
            return true;
        }
    }
}
