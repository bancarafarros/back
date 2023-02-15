<?php

class M_faq extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    public function tambah($params)
    {
        $return['status']  = 0;
        $return['message'] = '';

        $this->db->insert('faq', $params);
        if ($this->db->affected_rows()) {
            $return['status']  = 201;
            $return['message'] = 'Data FAQ berhasil ditambahkan';
        } else {
            $return['status']  = 500;
            $return['message'] = 'Data FAQ gagal ditambahkan';
        }
        return $return;
    }

    public function DataTableFAQ($data)
    {
        $return = array('total' => 0, 'rows' => array());

        $this->db->start_cache();
        $this->db->select('f.*');

        if (!empty($data['sSearch']) || $data['sSearch'] != '') {
            $search = $this->db->escape_str($data['sSearch']);
            $this->db->where("(f.faq LIKE '%{$search}%')");
        }
        if (!empty($data['id']) || $data['id'] != '') {
            $id = $this->db->escape_str($data['id']);
            $this->db->where("f.id", $id);
        }
        if (!empty($data['answer']) || $data['answer'] != '') {
            $answer = $this->db->escape_str($data['answer']);
            $this->db->where("f.answer", $answer);
        }
        
        $this->db->stop_cache();
        $rs = $this->db->count_all_results('faq f');
        $return['total'] = $rs;
        if ($return['total'] > 0) {
            $this->db->limit($data['limit'], $data['start']);
            $rs = $this->db->get('faq f');
            if ($rs->num_rows())
                $return['rows'] = $rs->result_array();
        }
        $this->db->flush_cache();
        return $return;
    }

    public function simpan($params)
    {
        $return['status']  = 0;
        $return['message'] = '';

        $this->db->trans_begin();
        $this->db->insert('faq', $params);
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            $return['status']  = 500;
            $return['message'] = 'Data FAQ gagal ditambahkan';
        } else {
            $this->db->trans_commit();
            $return['status']  = 201;
            $return['message'] = 'Data FAQ berhasil ditambahkan';
        }
        $this->db->trans_complete();
        return $return;
    }

    public function edit($id)
    {
        $return['status'] = 0;
        $return['data']   = [];

        $this->db->select('*');
        $this->db->from('faq');
        $this->db->where('id', $id);

        $get = $this->db->get();

        if ($get->num_rows() != 0) {
            $return['status'] = 201;
            $return['data']   = $get->row_object();
        } else {
            $return['status'] = 500;
            $return['data']   = [];
        }
        return $return;
    }

    public function update($params)
    {
        $return['status']  = 0;
        $return['message'] = '';

        $data = $params;
        unset($data['id'], $data['id_user']);

        $this->db->trans_begin();
        $this->db->where('id', $params['id']);
        $this->db->update('faq', $data);
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            $return['status']  = 500;
            $return['message'] = 'Data FAQ gagal diperbarui';
        } else {
            $this->db->trans_commit();
            $return['status']  = 201;
            $return['message'] = 'Data FAQ berhasil diperbarui';
        }
        $this->db->trans_complete();
        return $return;
    }

    public function hapus($id)
    {
        $this->db->trans_start();

        $this->db->where('id', $id);
        $this->db->delete('faq');

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            $return['status']  = 500;
        } else {
            $this->db->trans_commit();
            $return['status']  = 200;
        }
        $this->db->trans_complete();

        return $return;
    }

    // public function getListMasjid()
    // {
    //     $this->db->order_by('nama', 'ASC');
    //     $results = $this->db->get('masjid')->result_array();
    //     $return = array();

    //     foreach ($results as $key => $value) {
    //         $return[$value['id']] = $value["nama"];
    //     }

    //     return $return;
    // }

    // public function getMasjid($id = null)
    // {
    //     $ci = &get_instance();
    //     if ($id != null) {
    //         $masjid = $ci->db
    //             ->where(['id' => $id, 'status' => '1'])
    //             ->order_by("nama", 'asc')
    //             ->get('masjid');
    //         if ($masjid->num_rows() > 0) {
    //             $return = $masjid->row_array();
    //             return $return["nama"];
    //         } else {
    //             return null;
    //         }
    //     } else {
    //         $masjid = $ci->db
    //             ->where(['status' => '1'])
    //             ->order_by("nama", 'asc')
    //             ->get('masjid');
    //         if ($masjid->num_rows() > 0) {
    //             return $masjid->result_array();
    //         } else {
    //             return null;
    //         }
    //     }
    // }

    // public function getNamaPengurus($id = null)
    // {
    //     $ci = &get_instance();
    //     if ($id != null) {
    //         $pengurus = $ci->db
    //             ->where(['id' => $id, 'is_active' => '1'])
    //             ->order_by("nama", 'asc')
    //             ->get('masjid_pengurus');
    //         if ($pengurus->num_rows() > 0) {
    //             $return = $pengurus->row_array();
    //             return $return["nama"];
    //         } else {
    //             return null;
    //         }
    //     } else {
    //         $pengurus = $ci->db
    //             ->where(['is_active' => '1'])
    //             ->order_by("nama", 'asc')
    //             ->get('masjid_pengurus');
    //         if ($pengurus->num_rows() > 0) {
    //             return $pengurus->result_array();
    //         } else {
    //             return null;
    //         }
    //     }
    // }

    // public function getWhereMasjid($id)
    // {
    //     $pengurus = $this->db
    //         ->where('id_masjid', $id)
    //         ->order_by("nama", 'asc')
    //         ->get('masjid_pengurus')
    //         ->num_rows();
    //     return $pengurus;
    // }

    // public function detail($id)
    // {
    //     $get = $this->db->query("SELECT a.*,
    //     CONCAT(a.alamat, ', Kecamatan ',IFNULL(d.nama_kecamatan,'-'),', Kabupaten ',IFNULL(c.nama_kab_kota,'-'),', Provinsi ',IFNULL(b.nama_provinsi,'-'))  AS asal
    //     FROM masjid_pengurus a
    //     LEFT JOIN ref_provinsi b ON b.kode_provinsi = a.kode_provinsi
    //     LEFT JOIN ref_kabupaten c ON c.kode_kab_kota = a.kode_kabupaten
    //     LEFT JOIN ref_kecamatan d ON d.kode_kecamatan = a.kode_kecamatan
    //     LEFT JOIN user h ON h.id_user = a.id_user
    //     WHERE a.id=?", array($id));

    //     // var_dump($get);die
    //     if ($get->num_rows() != 0) {
    //         return $get->row_array();
    //         var_dump($get->row_array());
    //         die;
    //     } else {
    //         return array();
    //     }
    // }
}
