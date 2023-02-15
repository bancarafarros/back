<?php

class M_masjid extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    public function getMasjidNaktif()
    {
        $this->db->select('id, nama, no_hp');
        $rs = $this->db->get_where('masjid', array('is_verified' => '0'))->result();

        return $rs;
    }

    public function DataTableMasjid($data)
    {
        $return = array('total' => 0, 'rows' => array());

        $this->db->start_cache();
        $this->db->select('m.*, rp.nama_provinsi as provinsi, rk.nama_kab_kota as kabupaten, rk2.nama_kecamatan as kecamatan');

        if (!empty($data['sSearch']) || $data['sSearch'] != '') {
            $search = $this->db->escape_str($data['sSearch']);
            $this->db->where("(m.nama LIKE '%{$search}%')");
        }
        if (!empty($data['jenis_masjid']) || $data['jenis_masjid'] != '') {
            $jenis_masjid = $this->db->escape_str($data['jenis_masjid']);
            $this->db->where("m.type", $jenis_masjid);
        }
        if (!empty($data['typologi_masjid']) || $data['typologi_masjid'] != '') {
            $typologi_masjid = $this->db->escape_str($data['typologi_masjid']);
            $this->db->where("m.id_ref_typologi", $typologi_masjid);
        }
        $this->db->join('ref_provinsi rp', 'rp.kode_provinsi = m.kode_provinsi', 'left');
        $this->db->join('ref_kabupaten rk', 'rk.kode_kab_kota = m.kode_kabupaten', 'left');
        $this->db->join('ref_kecamatan rk2', 'rk2.kode_kecamatan = m.kode_kecamatan', 'left');

        $this->db->stop_cache();
        $rs = $this->db->count_all_results('masjid m');
        $return['total'] = $rs;
        if ($return['total'] > 0) {
            $this->db->limit($data['limit'], $data['start']);
            $this->db->order_by('m.created_at', 'DESC');
            $rs = $this->db->get('masjid m');
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

        $checkAccount = checkUserAccess(['email' => $params['email']]);
        if ($checkAccount['status'] == 201) {
            $return['status']  = 500;
            $return['message'] = 'Akun sudah digunakan. Silahkan cek kembali email anda';
        } else {
            $user['real_name']  = $params['nama'];
            $user['username']   = $params['email'];
            $user['email']      = $params['email'];
            $user['password']   = hash('sha256', $params['no_hp']);
            $user['id_group']   = '2';

            $this->db->trans_begin();
            $this->db->insert('user', $user);
            $id_user = $this->db->insert_id();

            $params['id']           = getUUID();
            $params['id_user']      = $id_user;
            $params['created_by']   = getSessionID();

            $this->db->insert('masjid', $params);
            $id_masjid = $this->db->get_where('masjid', array('nama' => $params['nama']))->row()->id;

            $pengurus['id'] = getUUID();
            $pengurus['id_masjid'] = $id_masjid;
            $pengurus['id_user'] = $id_user;
            $pengurus['nama'] = $params['nama_pj_takmir'];
            $pengurus['jenis_kelamin'] = 'Laki-Laki';
            $pengurus['no_hp'] = $params['no_hp'];
            $pengurus['email'] = $params['email'];
            $pengurus['kode_provinsi'] = $params['kode_provinsi'];
            $pengurus['kode_kabupaten'] = $params['kode_kabupaten'];
            $pengurus['kode_kecamatan'] = $params['kode_kecamatan'];
            $pengurus['jabatan'] = $params['jabatan_takmir'];
            $pengurus['created_by'] = getSessionID();

            $this->db->insert('masjid_pengurus', $pengurus);
            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();
                $return['status']  = 500;
                $return['message'] = 'Data masjid gagal ditambahkan';
            } else {
                $this->db->trans_commit();
                $return['status']  = 201;
                $return['message'] = 'Data masjid berhasil ditambahkan';
            }
            $this->db->trans_complete();
        }
        return $return;
    }

    public function detail($id_masjid)
    {
        $this->db->select('m.*, prov.nama_provinsi as provinsi, kab.nama_kab_kota as kabupaten, kec.nama_kecamatan as kecamatan, tan.name as status_tanah, aff.name as afiliasi, typo.name as typologi');
        $this->db->from('masjid m');
        $this->db->join('ref_provinsi prov', 'prov.kode_provinsi = m.kode_provinsi', 'left');
        $this->db->join('ref_kabupaten kab', 'kab.kode_kab_kota  = m.kode_kabupaten', 'left');
        $this->db->join('ref_kecamatan kec', 'kec.kode_kecamatan = m.kode_kecamatan', 'left');
        $this->db->join('ref_status_tanah tan', 'tan.id = m.ref_id_status_tanah', 'left');
        $this->db->join('ref_typologi typo', 'typo.id = m.id_ref_typologi', 'left');
        $this->db->join('ref_afiliasi aff', 'aff.id = m.ref_id_afiliasi', 'left');
        $this->db->where('m.id', $id_masjid);
        $get = $this->db->get();
        if ($get->num_rows() != 0) {
            return $get->row_array();
        } else {
            return null;
        }
    }

    public function edit($id)
    {
        $return['status'] = 0;
        $return['data']   = [];

        $this->db->select('*');
        $this->db->from('masjid');
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


    // public function hapus($id)
    // {
    //     $return['status']  = 0;
    //     $return['message'] = '';

    //     $current = $this->edit($id);

    //     $this->db->trans_begin();

    //     if ($current['status'] == 201) {
    //         $this->db->where('id_user', $current['data']->id_user);
    //         $this->db->delete('user');
    //     }

    //     $this->db->where('id_masjid', $id);
    //     $this->db->delete('masjid_kas');

    //     $this->db->where('id_masjid', $id);
    //     $this->db->delete('masjid_kas_pemasukan');

    //     $this->db->where('id_masjid', $id);
    //     $this->db->delete('masjid_kas_pengeluaran');

    //     $this->db->where('id_masjid', $id);
    //     $this->db->delete('masjid_pengurus');

    //     $this->db->where('id', $id);
    //     $this->db->delete('masjid');

    //     if ($this->db->trans_status() === true) {
    //         $this->db->trans_commit();
    //         $return['status']  = 201;
    //         $return['message'] = 'Data masjid berhasil dihapus';
    //     } else {
    //         $this->db->trans_rollback();
    //         $return['status']  = 500;
    //         $return['message'] = 'Data masjid gagal dihapus';
    //     }
    //     $this->db->trans_complete();
    //     return $return;
    // }

    public function hapus($param)
    {

        $this->db->where('id', $param);
        $result = $this->db->delete('masjid');

        return $result;
    }

    public function verifikasiMasjid($param)
    {
        $this->db->where('id', $param);
        $data = array('is_verified' => '1');
        $result = $this->db->update('masjid', $data);

        return $result;
    }

    public function update($params, $id)
    {
        $return['status']  = 0;
        $return['message'] = '';

        $data = $params;
        unset($data['id'], $data['id_user']);

        $check = checkUserAccess(['email' => $data['email']], $params['id_user']);
        if ($check['status'] == 201) {
            $return['status']  = 500;
            $return['message'] = 'Data sudah digunakan sebagai akun. Silakan cek kembali data anda';
        } else {
            $user['real_name']  = $params['nama'];
            $user['username']   = $params['email'];
            $user['email']      = $params['email'];

            $this->db->trans_begin();
            $this->db->where('id_user', $params['id_user']);
            $this->db->update('user', $user);

            $this->db->where('id', $params['id']);
            $this->db->update('masjid', $data);
            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();
                $return['status']  = 500;
                $return['message'] = 'Update data masjid gagal';
            } else {
                $this->db->trans_commit();
                $return['status']  = 201;
                $return['message'] = 'Update data masjid berhasil';
            }
            $this->db->trans_complete();
        }
        return $return;
    }

    public function getListAfiliasi()
    {
        $this->db->order_by('name', 'ASC');
        $results = $this->db->get('ref_afiliasi')->result_array();
        $return = array();

        foreach ($results as $key => $value) {
            $return[$value['id']] = $value["name"];
        }

        return $return;
    }
}
