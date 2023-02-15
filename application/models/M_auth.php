<?php

class M_auth extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    public function getUsersBy($by, $fields = '*', $limit = null)
    {
        $this->db->select($fields);
        $this->db->where($by);

        if ($limit != null || $limit > 0) {
            $this->db->limit($limit);
        }

        $query = $this->db->get('user');


        if ($limit == 1) {
            return $query->row();
        }

        return $query->result();
    }

    public function requestResetToken($user_id, $expired_in)
    {
        $token = substr(sha1(rand()), 0, 30);
        $hours = $expired_in * 60 * 60;
        $expired_at = date("Y-m-d H:i:s", (time() + $hours));

        $data = array(
            'token' => $token,
            'user_id' => $user_id,
            'expired_at' => $expired_at,
        );
        $this->db->insert('reset_tokens', $data);

        return $data;
    }

    public function statusResetToken($token)
    {
        $this->db->where('token', $token);
        $query = $this->db->get('reset_tokens');
        $row = $query->row();

        if ($row == null) {
            return null;
        }

        $isValid = ($row->is_valid == 1) ? true : false;
        $isExpired = ($row->expired_at < date("Y-m-d H:i:s")) ? true : false;
        $isUsed =  !empty($row->is_used) ? true : false;

        return [
            "is_valid"      => $isValid,
            "is_expired"    => $isExpired,
            "is_used"       => $isUsed,
            "user_id"       => $row->user_id
        ];
    }

    public function invalidateResetToken($token)
    {
        $this->db->set('is_valid', 0);
        $this->db->where('token', $token);
        $this->db->update('reset_tokens');
    }

    public function expireResetToken($token)
    {
        $this->db->set('used_at', date("Y-m-d H:i:s"));
        $this->db->where('token', $token);
        $this->db->update('reset_tokens');
    }

    public function changePassword($user_id, $password)
    {
        $this->db->set('password', $password);
        $this->db->where('id_user', $user_id);
        $this->db->update('user');
    }

    public function updateCookie($data, $id_user)
    {
        $this->db->where('id_user', $id_user);
        $this->db->update('user', $data);
    }

    public function get_by_cookie($cookie)
    {
        $this->db->where('cookie', $cookie);
        return $this->db->get("user");
    }

    public function getAllMagang($id)
    {
        $this->db->select('id, nama_persyaratan, is_required, url_template');
        $this->db->from('ref_periode_magang_persyaratan');
        $this->db->where('id_magang', $id);
        $result = $this->db->get()->result();
        return $result;
    }

    public function getDurasi($id)
    {
        $this->db->select('tanggal_mulai, tanggal_selesai');
        $this->db->from('ref_periode_magang');
        // $this->db->where('id_magang', $id);
        $result = $this->db->get()->result();
        return $result;
    }

    public function register($input)
    {
        $return['success'] = false;
        $return['message'] = '';

        // Data User
        // $user['username']           = $input['email'];
        // $user['email']              = $input['email'];
        // $user['real_name']          = $input['nama_lengkap'];
        // $user['id_group']           = '2';
        // $user['password']           = hash('sha256', $input['password']);

        // Hitung Durasi
        // $pendaftar = $this->db->get_where('pendaftar', array('id' => $id_pendaftar))->row_array();
        // $tanggal = $this->db->where(['id' => $id])->get('ref_periode_magang')->row_array();
        // $tglawal = date_create($tanggal["tanggal_mulai"]);
        // $tglakhir = date_create($tanggal["tanggal_selesai"]);
        // $diff = date_diff($tglawal, $tglakhir);
        // $durasi = $diff->format('%m');

        // Data Pendaftar
        $data['id_batch_magang']     = $input['periode_magang'];
        $data['nama_lengkap']        = $input['nama_lengkap'];
        $data['nim']                 = $input['nim'];
        $data['tempat_lahir']        = $input['tempat_lahir'];
        $data['tanggal_lahir']       = $input['tanggal_lahir'];
        $data['jenis_kelamin']       = $input['jenis_kelamin'];
        $data['kode_provinsi']       = $input['kode_provinsi'];
        $data['kode_kabupaten']      = $input['kode_kabupaten'];
        $data['kode_kecamatan']      = $input['kode_kecamatan'];
        $data['alamat']              = $input['alamat'];
        $data['email']               = $input['email'];
        $data['nomor_hp']            = $input['nomor_hp'];
        $data['asal_universitas']    = $input['asal_universitas'];
        $data['tahun_angkatan']      = $input['tahun_angkatan'];
        $data['durasi_magang']       = $input['durasi_magang'];
        $data['id_posisi']           = $input['id_posisi'];
        $data['harapan']             = $input['harapan'];
        $data['tanggal_mulai']       = $input['tanggal_mulai'];
        $data['id_jenjang_pendidikan'] = $input['id_jenjang_pendidikan'];
        $data['prodi']               = $input['prodi'];
        $data['url_portofolio']      = $input['url_portofolio'];
        // $data['created_by']          = $this->session->userdata('intern_userId');

        // if (!empty($_FILES['gambar']['name'])) {
        //     $url = uploadBerkas('gambar', 'pendaftar', 'pendaftar');
        //     if ($url['success']) {
        //         $data['url_photo'] = $url['file_name'];
        //     }else {
        //         $data['url_photo'] = null;
        //     }
        // }else{
        //     $data['url_photo'] = null;
        // }

        $cekEmailUser = $this->cekEmail('user', $input['email']);
        $cekEmailPendaftar = $this->cekEmail('pendaftar', $input['email']);
        $cekNomorHP = $this->cekNomorHP('pendaftar', $input['nomor_hp']);

        if ($cekEmailUser['used'] == true || $cekEmailPendaftar['used'] == true || $cekNomorHP['used'] == true) {
            $return['success'] = false;
            $return['message'] = 'No HP / Email telah digunakan';
        } else {
            $this->db->trans_begin();
            $this->db->insert('pendaftar', $data);
            $id_pendaftar = $this->db->insert_id();

            $this->db->reset_query();

            $porto['nama']          = $input['url_portofolio'];
            $porto['id_pendaftar']  = $id_pendaftar;
            $this->db->insert('pendaftar_portofolio', $porto);

            $this->db->reset_query();
            // $this->db->insert('user', $user);
            // $data['created_by'] = $id_user;

            // $this->db->trans_start();

            // $this->db->insert('user', $user);
            // $id_user = $this->db->insert_id();

            // $data += ['id_user' => $id_user];
            // $this->db->insert('pendaftar', $data);
            // $id_pendaftar=$this->db->insert_id();
            // $this->db->reset_query();

            $sosmed = array();
            $sosialmedia = count($input['id_sosmed']);
            for ($i = 0; $i < $sosialmedia; $i++) {
                $sosmed[$i]['id_sosmed'] = $input['id_sosmed'][$i];
                $sosmed[$i]['id_pendaftar'] = $id_pendaftar;
                $sosmed[$i]['url'] = $input['url'][$i];
                // $sosmed[$i]['created_by'] = $id_user;
                $this->db->insert('pendaftar_sosmed', $sosmed[$i]);
            }
            $this->db->reset_query();

            // $pendaftar = $this->db->get_where('pendaftar', array('id' => $id_pendaftar))->row_array();
            // $magang_syarat = $this->getAllMagang($pendaftar["id_batch_magang"]);
            // foreach ($magang_syarat as $data) {
            //     $magang = array(
            //         'id_pendaftar' => $id_pendaftar,
            //         'id_berkas' => $data->id,
            //     );
            //     $this->db->insert('pendaftar_persyaratan', $magang);
            // }

            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();
                $return['success'] = false;
                $return['message'] = 'Daftar gagal.';
            } else {
                $this->db->trans_commit();
                $return['success'] = true;
                $return['message'] = 'Daftar berhasil.';
            }
            $this->db->trans_complete();
        }

        return $return;
    }

    public function cekEmail($table, $email)
    {
        $return['used'] = false;
        $return['message'] = '';
        $get = $this->db->where(['email' => $email])->get($table);
        if ($get->num_rows() > 0) {
            $return['used'] = true;
            $return['message'] = 'Email telah digunakan';
        } else {
            $return['used'] = false;
            $return['message'] = null;
        }
        return $return;
    }

    public function cekNomorHP($table, $nomor_hp)
    {
        $return['used'] = false;
        $return['message'] = '';
        $get = $this->db->where(['nomor_hp' => $nomor_hp])->get($table);
        if ($get->num_rows() > 0) {
            $return['used'] = true;
            $return['message'] = 'Nomor HP / Username telah digunakan';
        } else {
            $return['used'] = false;
            $return['message'] = null;
        }
        return $return;
    }
}
