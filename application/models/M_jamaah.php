<?php

class M_jamaah extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    public function tambah($params)
    {
        $return['status']  = 0;
        $return['message'] = '';

        $this->db->insert('jamaah', $params);
        if ($this->db->affected_rows()) {
            $return['status']  = 201;
            $return['message'] = 'Data jamaah masjid berhasil ditambahkan';
        } else {
            $return['status']  = 500;
            $return['message'] = 'Data jamaah masjid gagal ditambahkan';
        }
        return $return;
    }

    public function DataTableJamaah($data)
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
        $this->db->join('ref_provinsi rp', 'rp.kode_provinsi = m.kode_provinsi', 'left');
        $this->db->join('ref_kabupaten rk', 'rk.kode_kab_kota = m.kode_kabupaten', 'left');
        $this->db->join('ref_kecamatan rk2', 'rk2.kode_kecamatan = m.kode_kecamatan', 'left');

        $this->db->stop_cache();
        $rs = $this->db->count_all_results('jamaah m');
        $return['total'] = $rs;
        if ($return['total'] > 0) {
            $this->db->limit($data['limit'], $data['start']);
            $this->db->order_by('m.created_at', 'DESC');
            $rs = $this->db->get('jamaah m');
            if ($rs->num_rows())
                $return['rows'] = $rs->result_array();
        }
        $this->db->flush_cache();
        return $return;
    }

    public function edit($id)
    {
        $return['status'] = 0;
        $return['data']   = [];

        $this->db->select('*');
        $this->db->where('id', $id);

        $get = $this->db->get('jamaah');


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
            $this->db->update('jamaah', $data);
            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();
                $return['status']  = 500;
                $return['message'] = 'Update data jamaah gagal';
            } else {
                $this->db->trans_commit();
                $return['status']  = 201;
                $return['message'] = 'Update data jamaah berhasil';
            }
            $this->db->trans_complete();
        }
        return $return;
    }

    // public function update($data, $id)
    // {
    //     $return['status']  = 0;
    //     $return['message'] = '';

    //     $this->db->where('id', $id);
    //     $this->db->update('jamaah', $data);

    //     if ($this->db->affected_rows()) {
    //         $return['status']  = 201;
    //         $return['message'] = 'Data jamaah masjid berhasil diperbarui';
    //     } else {
    //         $return['status']  = 500;
    //         $return['message'] = 'Data jamaah masjid gagal diperbarui';
    //     }
    //     return $return;
    // }
    public function hapus($id)
    {
        $this->db->select('url_foto, id_user');
        $this->db->where('id', $id);
        $get = $this->db->get('jamaah')->row_array();

        $id_user = $get['id_user'];
        $foto = $get['url_foto'];

        if (!empty($foto)) {
            $file = "./public/uploads/jamaah/" . $foto;
            unlink($file);
        }

        $this->db->trans_start();

        $this->db->where('id', $id);
        $this->db->delete('jamaah');

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

    public function get_jamaah()
    {
        $this->db->select('id');
        $this->db->from('jamaah');
        $this->db->where('is_active', '1');
        $get = $this->db->get()->row_array();
        $id = $get['id'];

        return $id;
    }

    public function detail($id_jamaah)
    {
        $get = $this->db->query("SELECT a.*,
        CONCAT(a.alamat, ', Kecamatan ',IFNULL(d.nama_kecamatan,'-'),', Kabupaten ',IFNULL(c.nama_kab_kota,'-'),', Provinsi ',IFNULL(b.nama_provinsi,'-'))  AS asal
        FROM jamaah a
        LEFT JOIN ref_provinsi b ON b.kode_provinsi=a.kode_provinsi
        LEFT JOIN ref_kabupaten c ON c.kode_kab_kota=a.kode_kabupaten
        LEFT JOIN ref_kecamatan d ON d.kode_kecamatan=a.kode_kecamatan
        LEFT JOIN jamaah_tabungan e ON e.id=a.id
        LEFT JOIN user h ON h.id_user=a.id_user
        WHERE a.id=?", array($id_jamaah));

        // var_dump($get);die
        if ($get->num_rows() != 0) {
            return $get->row_array();
            var_dump($get->row_array());
            die;
        } else {
            return array();
        }
    }

    public function getNamajamaah($id = null)
    {
        $ci = &get_instance();
        if ($id != null) {
            $jamaah = $ci->db
                ->where(['id' => $id, 'is_active' => '1'])
                ->order_by("nama", 'asc')
                ->get('jamaah');
            if ($jamaah->num_rows() > 0) {
                $return = $jamaah->row_array();
                return $return["nama"];
            } else {
                return null;
            }
        } else {
            $jamaah = $ci->db
                ->where(['is_active' => '1'])
                ->order_by("nama", 'asc')
                ->get('jamaah');
            if ($jamaah->num_rows() > 0) {
                return $jamaah->result_array();
            } else {
                return null;
            }
        }
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

    public function getListMasjid()
    {
        $this->db->select('id, nama');
        $this->db->from('masjid');
        $this->db->where('status', '1');
        $results = $this->db->get()->result_array();

        // var_dump($results->result_array());
        // exit();
        $return = array();

        foreach ($results as $key => $value) {
            $return[$value['id']] = $value["nama"];
        }

        return $return;
    }

    public function getWhereMasjid($id)
    {
        $jamaah = $this->db
            ->where('id_masjid', $id)
            ->order_by("nama", 'asc')
            ->get('jamaah')
            ->num_rows();
        return $jamaah;
    }

    public function create_tabungan($data)
    {
        $response['success'] = false;
        $response['message'] = null;

        $insert = $this->db->insert('jamaah_tabungan', $data);

        if ($insert) {
            $response['success'] = true;
            $response['message'] = 'Tabungan Berhasil Ditambahkan.';
        } else {
            $response['success'] = false;
            $response['message'] = 'Tabungan Gagal Ditambahkan.';
        }
        return $response;
    }

    public function isJamaahHaveTabungan($id_jamaah)
    {
        $this->db->select('jamaah_id');
        $jamaah = $this->db->get_where('jamaah_tabungan', array('jamaah_id' => $id_jamaah))->num_rows();

        if ($jamaah != null) {
            return [
                "status"    => "error",
                "message"   => "Jamaah masih memiliki tabungan, sistem mencegah anda menghapus data jamaah."
            ];
        } else {
            return [
                "status"    => "success",
                "message"   => "Berhasil hapus data jamaah"
            ];
        }
    }

    public function isJamaahid($data, $data2)
    {
        $this->db->where('id_jamaah', $data);
        $this->db->where('id_masjid', $data2);
        $q = $this->db->get("jamaah_tabungan");
        $this->db->reset_query();

        // var_dump($q);
        // die;
        if ($q->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function get_namajamaah($id_jamaah)
    {
        $this->db->select('*');
        $this->db->from('jamaah');
        $this->db->where('id', $id_jamaah);
        $result = $this->db->get()->row_array();
        return $result;
    }

    public function simpanJamaah($params)
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
            $user['id_group']   = '3';

            $this->db->trans_begin();
            $this->db->insert('user', $user);
            $id_user = $this->db->insert_id();

            $params['id']           = getUUID();
            $params['id_user']      = $id_user;
            $params['created_by']   = getSessionID();

            $this->db->insert('jamaah', $params);
            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();
                $return['status']  = 500;
                $return['message'] = 'Data jamaah gagal ditambahkan';
            } else {
                $this->db->trans_commit();
                $return['status']  = 201;
                $return['message'] = 'Data jamaah berhasil ditambahkan';
            }
            $this->db->trans_complete();
        }
        return $return;
    }
}
