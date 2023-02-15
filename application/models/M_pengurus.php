<?php

class M_pengurus extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    public function tambah($params)
    {
        $return['status']  = 0;
        $return['message'] = '';

        $this->db->insert('masjid_pengurus', $params);
        if ($this->db->affected_rows()) {
            $return['status']  = 201;
            $return['message'] = 'Data pengurus berhasil ditambahkan';
        } else {
            $return['status']  = 500;
            $return['message'] = 'Data pengurus gagal ditambahkan';
        }
        return $return;
    }

    public function DataTablePengurus($data)
    {
        $return = array('total' => 0, 'rows' => array());

        $this->db->start_cache();
        $this->db->select('m.*, rp.nama_provinsi as provinsi, rk.nama_kab_kota as kabupaten, rk2.nama_kecamatan as kecamatan');

        if (!empty($data['sSearch']) || $data['sSearch'] != '') {
            $search = $this->db->escape_str($data['sSearch']);
            $this->db->where("(m.nama LIKE '%{$search}%')");
        }
        if (!empty($data['id_masjid']) || $data['id_masjid'] != '') {
            $id_masjid = $this->db->escape_str($data['id_masjid']);
            $this->db->where("m.id_masjid", $id_masjid);
        }
        if (!empty($data['jabatan']) || $data['jabatan'] != '') {
            $jabatan = $this->db->escape_str($data['jabatan']);
            $this->db->where("m.jabatan", $jabatan);
        }
        $this->db->join('ref_provinsi rp', 'rp.kode_provinsi = m.kode_provinsi', 'left');
        $this->db->join('ref_kabupaten rk', 'rk.kode_kab_kota = m.kode_kabupaten', 'left');
        $this->db->join('ref_kecamatan rk2', 'rk2.kode_kecamatan = m.kode_kecamatan', 'left');

        $this->db->stop_cache();
        $rs = $this->db->count_all_results('masjid_pengurus m');
        $return['total'] = $rs;
        if ($return['total'] > 0) {
            $this->db->limit($data['limit'], $data['start']);
            $this->db->order_by('m.created_at', 'DESC');
            $rs = $this->db->get('masjid_pengurus m');
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
            $user['password']   = hash('sha256', $params['email']);
            $user['id_group']   = '2';

            $this->db->trans_begin();
            $this->db->insert('user', $user);
            $id_user = $this->db->insert_id();

            $params['id']           = getUUID();
            $params['id_user']      = $id_user;
            $params['created_by']   = getSessionID();

            $this->db->insert('masjid_pengurus', $params);
            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();
                $return['status']  = 500;
                $return['message'] = 'Data pengurus gagal ditambahkan';
            } else {
                $this->db->trans_commit();
                $return['status']  = 201;
                $return['message'] = 'Data pengurus berhasil ditambahkan';
            }
            $this->db->trans_complete();
        }
        return $return;
    }

    public function edit($id)
    {
        $return['status'] = 0;
        $return['data']   = [];

        $this->db->select('*');
        $this->db->from('masjid_pengurus');
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
            $this->db->update('masjid_pengurus', $data);
            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();
                $return['status']  = 500;
                $return['message'] = 'Data pengurus gagal diperbarui';
            } else {
                $this->db->trans_commit();
                $return['status']  = 201;
                $return['message'] = 'Data pengurus berhasil diperbarui';
            }
            $this->db->trans_complete();
        }
        return $return;
    }

    public function hapus($id)
    {
        $this->db->select('url_foto, id_user');
        $this->db->where('id', $id);
        $get = $this->db->get('masjid_pengurus')->row_array();

        $id_user = $get['id_user'];
        $foto = $get['url_foto'];

        if (!empty($foto)) {
            $file = "./public/uploads/pengurus/" . $foto;
            unlink($file);
        }

        $this->db->trans_start();

        $this->db->where('id', $id);
        $this->db->delete('masjid_pengurus');

        $this->db->where('id_user', $id_user);
        $this->db->delete('user');

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

    public function getListMasjid()
    {
        $this->db->order_by('nama', 'ASC');
        $results = $this->db->get('masjid')->result_array();
        $return = array();

        foreach ($results as $key => $value) {
            $return[$value['id']] = $value["nama"];
        }

        return $return;
    }

    public function getMasjid($id = null)
    {
        $ci = &get_instance();
        if ($id != null) {
            $masjid = $ci->db
                ->where(['id' => $id, 'status' => '1'])
                ->order_by("nama", 'asc')
                ->get('masjid');
            if ($masjid->num_rows() > 0) {
                $return = $masjid->row_array();
                return $return["nama"];
            } else {
                return null;
            }
        } else {
            $masjid = $ci->db
                ->where(['status' => '1'])
                ->order_by("nama", 'asc')
                ->get('masjid');
            if ($masjid->num_rows() > 0) {
                return $masjid->result_array();
            } else {
                return null;
            }
        }
    }

    public function getNamaPengurus($id = null)
    {
        $ci = &get_instance();
        if ($id != null) {
            $pengurus = $ci->db
                ->where(['id' => $id, 'is_active' => '1'])
                ->order_by("nama", 'asc')
                ->get('masjid_pengurus');
            if ($pengurus->num_rows() > 0) {
                $return = $pengurus->row_array();
                return $return["nama"];
            } else {
                return null;
            }
        } else {
            $pengurus = $ci->db
                ->where(['is_active' => '1'])
                ->order_by("nama", 'asc')
                ->get('masjid_pengurus');
            if ($pengurus->num_rows() > 0) {
                return $pengurus->result_array();
            } else {
                return null;
            }
        }
    }

    public function getWhereMasjid($id)
    {
        $pengurus = $this->db
            ->where('id_masjid', $id)
            ->order_by("nama", 'asc')
            ->get('masjid_pengurus')
            ->num_rows();
        return $pengurus;
    }

    public function detail($id)
    {
        $get = $this->db->query("SELECT a.*,
        CONCAT(a.alamat, ', Kecamatan ',IFNULL(d.nama_kecamatan,'-'),', Kabupaten ',IFNULL(c.nama_kab_kota,'-'),', Provinsi ',IFNULL(b.nama_provinsi,'-'))  AS asal
        FROM masjid_pengurus a
        LEFT JOIN ref_provinsi b ON b.kode_provinsi = a.kode_provinsi
        LEFT JOIN ref_kabupaten c ON c.kode_kab_kota = a.kode_kabupaten
        LEFT JOIN ref_kecamatan d ON d.kode_kecamatan = a.kode_kecamatan
        LEFT JOIN user h ON h.id_user = a.id_user
        WHERE a.id=?", array($id));

        // var_dump($get);die
        if ($get->num_rows() != 0) {
            return $get->row_array();
            var_dump($get->row_array());
            die;
        } else {
            return array();
        }
    }

    public function getIdByIdUser($id_user)
    {
        $get = $this->db->get_where('masjid_pengurus', array('id_user' => $id_user));
        if ($get->num_rows() != 0) {
            return $get->row();
        } else {
            return array();
        }
    }
}
