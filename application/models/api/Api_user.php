<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class api_user extends CI_Model {

    // alun alun malang
    private $default_lat = '-7.982211';
    private $default_lon = '112.630811';

    function __construct() {
        parent::__construct();
    }

    function user_data($id_user) {
        $get = $this->db->query("
            SELECT 
            ug.id_group, ug.nama_group, 
            u.id_user, u.username, u.email, u.real_name, u.last_modified_time
            FROM user u 
            LEFT JOIN user_group ug ON ug.id_group = u.id_group 
            WHERE u.id_user = ? AND u.is_active = '1' 
            LIMIT 1
        ", array($id_user));

        return $get;
    }

    function pembimbing_data($id_user) {
        // Define what to return
        $result = array();
        $result['user'] = null;
        $result['roles'] = null;
        $result['profile'] = null;
        $result['member'] = array();

        // data user first
        $get = $this->user_data($id_user);
        if ($get->num_rows() == 0) {
            return ["status" => "failed", "message" => "Data User tidak ditemukan."];
        }

        // user roles - saat ini selalu hanya 1 role 1 user
        $result['roles'][0]['id'] = $get->row()->id_group;
        $result['roles'][0]['nama_role'] = $get->row()->nama_group;

        // override and give role codes
        $result['roles'] = $this->set_role_code($result['roles']);

        // user
        $result['user']['id'] = $get->row()->id_user;
        $result['user']['username'] = $get->row()->username;
        $result['user']['email'] = $get->row()->email;
        $result['user']['real_name'] = $get->row()->real_name;
        $result['user']['last_modified_time'] = $get->row()->last_modified_time;

        // Get Profile
        $get = $this->db->query("
            SELECT p.id_pembimbing, p.nama, p.email, p.no_hp, p.id_pelaksana, p.url_photo, p.status, p.last_modified_time, 
            rp.nama nama_pelaksana 
            FROM pembimbing p 
            LEFT JOIN ref_pelaksana rp ON p.id_pelaksana = rp.id_pelaksana 
            WHERE p.id_user = ? AND p.is_active = '1'
            ORDER BY p.id_pembimbing DESC 
            LIMIT 1
        ", array($id_user));
        if ($get->num_rows() == 0) {
            return ["status" => "ok", "data" => $result];
        }
        
        $r = $get->row();
        // profile
        $result['profile']['id'] = $r->id_pembimbing;
        $result['profile']['nama'] = $r->nama;
        $result['profile']['email'] = $r->email;
        $result['profile']['no_hp'] = $r->no_hp;
        $result['profile']['url_photo'] = $r->url_photo;
        $result['profile']['id_asal_institusi'] = $r->id_pelaksana;
        $result['profile']['asal_institusi'] = $r->nama_pelaksana;
        $result['profile']['status'] = $r->status == 1 ? 'diterima' : 'pengusulan';
        $result['profile']['last_modified_time'] = $r->last_modified_time;

        // serve
        return ["status" => "ok", "data" => $result];
    }

    function mentor_data($id_user) {
        // Define what to return
        $result = array();
        $result['user'] = null;
        $result['roles'] = null;
        $result['profile'] = null;
        $result['member'] = array();

        // data user first
        $get = $this->user_data($id_user);
        if ($get->num_rows() == 0) {
            return ["status" => "failed", "message" => "Data User tidak ditemukan."];
        }

        // user roles - saat ini selalu hanya 1 role 1 user
        $result['roles'][0]['id'] = $get->row()->id_group;
        $result['roles'][0]['nama_role'] = $get->row()->nama_group;

        // override and give role codes
        $result['roles'] = $this->set_role_code($result['roles']);

        // user
        $result['user']['id'] = $get->row()->id_user;
        $result['user']['username'] = $get->row()->username;
        $result['user']['email'] = $get->row()->email;
        $result['user']['real_name'] = $get->row()->real_name;
        $result['user']['last_modified_time'] = $get->row()->last_modified_time;

        // Get Profile
        $get = $this->db->query("
            SELECT p.id_mentor, p.nama, p.email, p.no_hp, p.bidang_usaha, p.lama_usaha, p.pengalaman, p.status_pengajuan, p.url_photo, p.last_modified_time 
            FROM mentor p 
            WHERE p.id_user = ? AND p.is_active = '1'
            ORDER BY p.id_mentor DESC 
            LIMIT 1
        ", array($id_user));
        if ($get->num_rows() == 0) {
            return ["status" => "ok", "data" => $result];
        }
        
        $r = $get->row();
        // profile
        $result['profile']['id'] = $r->id_mentor;
        $result['profile']['nama'] = $r->nama;
        $result['profile']['email'] = $r->email;
        $result['profile']['no_hp'] = $r->no_hp;
        $result['profile']['bidang_usaha'] = $r->bidang_usaha;
        $result['profile']['lama_usaha'] = $r->lama_usaha;
        $result['profile']['pengalaman'] = $r->pengalaman;
        $result['profile']['url_photo'] = $r->url_photo;
        $result['profile']['status'] = $r->status_pengajuan == 1 ? 'diterima' : 'pengusulan';
        $result['profile']['last_modified_time'] = $r->last_modified_time;

        // serve
        return ["status" => "ok", "data" => $result];
    }

    function peserta_data($id_user) {
        // Define what to return
        $result = array();
        $result['user'] = null;
        $result['roles'] = null;
        $result['profile'] = null;
        $result['member'] = array();

        // data user first
        $get = $this->user_data($id_user);
        if ($get->num_rows() == 0) {
            return ["status" => "failed", "message" => "Data User tidak ditemukan."];
        }

        // user roles - saat ini selalu hanya 1 role 1 user
        $result['roles'][0]['id'] = $get->row()->id_group;
        $result['roles'][0]['nama_role'] = $get->row()->nama_group;

        // override and give role codes
        $result['roles'] = $this->set_role_code($result['roles']);

        // user
        $result['user']['id'] = $get->row()->id_user;
        $result['user']['username'] = $get->row()->username;
        $result['user']['email'] = $get->row()->email;
        $result['user']['real_name'] = $get->row()->real_name;
        $result['user']['last_modified_time'] = $get->row()->last_modified_time;

        // Get Profile
        $get = $this->db->query("
            SELECT 
            p.id_peserta, p.ref_tahun, p.nama_kelompok, p.email, p.id_pelaksana, 
            p.deskripsi, p.no_hp_usaha, p.bidang_usaha, p.komoditas_usaha, p.sub_sektor, 
            p.badan_usaha, p.alamat, p.kode_kecamatan, 
            p.tanggal_mulai_usaha, p.url_logo, p.is_user_activated, 
            p.user_activation_time, p.is_gabungan, p.kelas_usaha, p.modal_awal_pwmp, 
            p.id_pembimbing, p.id_mentor, p.status_usaha, 
            p.longitude, p.latitude, 
            p.registration_time, p.last_modified_time,
            rju.id_jenis, rju.nama_jenis,
            rp.nama nama_pelaksana,
            pm.nama nama_pembimbing,
            mtr.nama nama_mentor, mtr.status_pengajuan status_mentor
            FROM peserta p 
            LEFT JOIN ref_jenis_usaha rju ON p.id_jenis_usaha = rju.id_jenis 
            LEFT JOIN ref_pelaksana rp ON p.id_pelaksana = rp.id_pelaksana 
            LEFT JOIN pembimbing pm ON p.id_pembimbing = pm.id_pembimbing AND pm.is_active = '1'
            LEFT JOIN mentor mtr ON p.id_mentor = mtr.id_mentor AND mtr.is_active = '1'
            WHERE p.id_user = ? AND p.is_active = '1' 
            ORDER BY p.id_peserta DESC
            LIMIT 1
        ", array($id_user));
        if ($get->num_rows() == 0) {
            return ["status" => "ok", "data" => $result];
        }

        $provinsi = ""; $id_provinsi = ""; $kabkota = ""; $id_kabkota = ""; $kecamatan = ""; $id_kecamatan = "";
        if ($get->row()->kode_kecamatan != null && $get->row()->kode_kecamatan != '') {
            $address = $this->db->query("
                SELECT 
                d.id id_kecamatan, d.name kecamatan, r.id id_kabkota, r.name kabkota, prv.id id_provinsi, prv.name provinsi 
                FROM districts d 
                LEFT JOIN regencies r ON r.id = d.regency_id 
                LEFT JOIN provinces prv ON prv.id = r.province_id 
                WHERE d.id = ?
            ", array($get->row()->kode_kecamatan));
            if ($address->num_rows() > 0) {
                $id_kecamatan = $address->row()->id_kecamatan;
                $kecamatan = $address->row()->kecamatan;
                $id_kabkota = $address->row()->id_kabkota;
                $kabkota = $address->row()->kabkota;
                $id_provinsi = $address->row()->id_provinsi;
                $provinsi = $address->row()->provinsi;
            }
        }
        
        $r = $get->row();
        // profile
        $result['profile']['id'] = $r->id_peserta;
        $result['profile']['nama_kelompok'] = $r->nama_kelompok;
        $result['profile']['tahun_kepesertaan'] = $r->ref_tahun;
        $result['profile']['id_asal_institusi'] = $r->id_pelaksana;
        $result['profile']['asal_institusi'] = $r->nama_pelaksana;
        $result['profile']['kelas_usaha'] = $r->kelas_usaha;
        $result['profile']['email'] = $r->email;
        $result['profile']['deskripsi'] = $r->deskripsi != null ? $r->deskripsi : "";
        $result['profile']['no_hp_usaha'] = $r->no_hp_usaha;
        $result['profile']['bidang_usaha'] = $r->bidang_usaha;
        $result['profile']['komoditas_usaha'] = $r->komoditas_usaha;
        $result['profile']['sub_sektor'] = $r->sub_sektor;
        $result['profile']['badan_usaha'] = strtolower($r->badan_usaha);
        $result['profile']['modal_awal_pwmp'] = $r->modal_awal_pwmp;
        $result['profile']['url_logo'] = $r->url_logo;
        $result['profile']['tanggal_mulai_usaha'] = $r->tanggal_mulai_usaha;
        $result['profile']['pembimbing'] = $r->nama_pembimbing;
        $result['profile']['mentor'] = $r->status_mentor != null && $r->status_mentor == '2' ? $r->nama_mentor : null; // only approved mentor
        $result['profile']['status_usaha'] = $r->status_usaha;
        $result['profile']['alamat']['nama'] = $r->alamat;
        $result['profile']['alamat']['id_provinsi'] = $id_provinsi;
        $result['profile']['alamat']['provinsi'] = $provinsi;
        $result['profile']['alamat']['id_kabkota'] = $id_kabkota;
        $result['profile']['alamat']['kabkota'] = $kabkota;
        $result['profile']['alamat']['id_kecamatan'] = $id_kecamatan;
        $result['profile']['alamat']['kecamatan'] = $kecamatan;
        $result['profile']['map']['latitude'] = $r->latitude != null ? $r->latitude : $this->default_lat;
        $result['profile']['map']['longitude'] = $r->longitude != null ? $r->longitude : $this->default_lon;
        $result['profile']['is_gabungan'] = $r->is_gabungan != 0;
        $result['profile']['registration_time'] = $r->registration_time;
        $result['profile']['last_modified_time'] = $r->last_modified_time;

        // jenis usaha
        if ($r->id_jenis != null) {
            $result['profile']['jenis_usaha']['id'] = $r->id_jenis != null ? $r->id_jenis : "";
            $result['profile']['jenis_usaha']['nama_jenis'] = $r->nama_jenis != null ? $r->nama_jenis : "";
        } else {
            $result['profile']['jenis_usaha'] = null;
        }
        
        // member
        $result['member'] = $this->member_peserta($result['profile']['id']);

        // serve
        return ["status" => "ok", "data" => $result];
    }

    function member_peserta($id_profile) {
        $result = array();
        // member
        $get = $this->db->query("
            SELECT 
            pa.id_anggota, pa.nik, pa.nama, pa.tempat_lahir, pa.tanggal_lahir, 
            pa.posisi, pa.no_hp, pa.alamat, 
            pa.status_keanggotaan, pa.url_photo, pa.last_modified_time
            FROM peserta p 
            LEFT JOIN peserta_anggota pa 
            ON p.id_peserta = pa.id_peserta 
            WHERE pa.id_peserta = ? 
            AND pa.status_keanggotaan = '1' 
            AND pa.is_active = '1' 
            ORDER BY pa.posisi ASC, pa.nama ASC
        ", array($id_profile));
        if ($get->num_rows() > 0) {
            $i = 0;
            foreach ($get->result() as $key => $r) {
                $result[$i]['id'] = $r->id_anggota;
                $result[$i]['nik'] = $r->nik != null ? $r->nik : "";
                $result[$i]['nama'] = $r->nama != null ? $r->nama : "";
                $result[$i]['tempat_lahir'] = $r->tempat_lahir;
                $result[$i]['tanggal_lahir'] = $r->tanggal_lahir;
                $result[$i]['posisi'] = $r->posisi != null ? ($r->posisi == 1 ? "Ketua" : "Anggota") : "";
                $result[$i]['no_hp'] = $r->no_hp != null ? $r->no_hp : "";
                $result[$i]['alamat'] = $r->alamat != null ? $r->alamat : "";
                $result[$i]['status_keanggotaan'] = $r->status_keanggotaan != 0 ? 'aktif' : 'tidak aktif';
                $result[$i]['url_photo'] = $r->url_photo;
                $result[$i]['last_modified_time'] = $r->last_modified_time;
                $i++;
            }
        }

        return $result;
    }

    function user_group($id_group) {
        //cek username
        $get = $this->db->query("
            SELECT nama_group FROM user_group WHERE id_group = ?
        ", array($id_group));
        if ($get->num_rows() == 0) {
            return false;
        }
        
        return $get->row()->nama_group;
    }

    function set_role_code($data) {
        $ci =& get_instance();
        $ci->load->helper('api');

        $index = 0;
        $overriden = [];
        foreach($data as $value) {
            $overriden[$index]['role_code'] = APIHELPER::role_code($value);
            $overriden[$index]['id'] = $value['id'];
            $overriden[$index]['nama_role'] = $value['nama_role'];
            $index++;
        }
        return $overriden;
    }

    function update_password($data, $id_user) {
        if ($data == null) {
            return ["status" => "failed", "message" => "Tidak ditemukan data."];
        }

        // cek permission - only allow for their own data
        $get = $this->db->query("SELECT id_user FROM user WHERE id_user = ?", array($id_user));
        if ($get->num_rows() == 0 || $get->row_array()['id_user'] != $id_user) {
            return ["status" => "failed", "message" => "Anda tidak diperbolehkan untuk mengubah data milik orang lain."];
        }

        $key = ['old_password', 'new_password'];
        foreach($key as $check) {
            if (!array_key_exists($check, $data)) {
                return ["status" => "failed", "message" => "Parameter '" . $check . "' tidak ditemukan."];
            }
        }

        $old = $data['old_password'];
        $new = $data['new_password'];

        // check old password must match..
        $get = $this->db->query("SELECT id_user FROM user WHERE id_user = ? AND password = SHA2(?, 256)", array($id_user, $old));

        if ($get->num_rows() == 0) {
            return ["status" => "failed", "message" => "Password lama tidak sesuai."];
        }

        $this->db->trans_begin();

        // update user
        $this->db->query("
            UPDATE user SET 
            password = SHA2(?, 256),
            last_modified_user = ?, 
            last_modified_time = NOW() 
            WHERE id_user = ?
        ", array($new, $id_user, $id_user));

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return ["status" => "failed", "message" => "Gagal proses data. Hubungi administrator."];
        } else {
            $this->db->trans_commit();
            return ["status" => "ok", "data" => null];
        }
    }

    function change_leader($data, $id_user) {
        if ($data == null) {
            return ["status" => "failed", "message" => "Tidak ditemukan data."];
        }

        // Check only Peserta
        $get = $this->db->query("
            SELECT p.id_peserta
            FROM peserta p 
            WHERE p.id_user = ?
        ", array($id_user));
        if ($get->num_rows() == 0) {
            return ["status" => "failed", "message" => "Anda bukan Peserta!"];
        }

        $key = ['id_profile', 'id_anggota'];
        foreach($key as $check) {
            if (!array_key_exists($check, $data)) {
                return ["status" => "failed", "message" => "Parameter '" . $check . "' tidak ditemukan."];
            }
        }

        $id_profile = $data['id_profile'];
        $id_anggota = $data['id_anggota'];

        $this->db->trans_begin();

        // Set Anggota
        $this->db->query("
            UPDATE peserta_anggota SET posisi = '2' 
            WHERE id_anggota <> ?
        ", array($id_anggota));

        // Set Ketua
        $this->db->query("
            UPDATE peserta_anggota SET posisi = '1' 
            WHERE id_anggota = ?
        ", array($id_anggota));

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return ["status" => "failed", "message" => "Gagal proses data. Hubungi administrator."];
        } else {
            $this->db->trans_commit();
            $result = $this->member_peserta($id_profile);
            return ["status" => "ok", "data" => $result];
        }
    }

    function delete_member($id_anggota, $id_user) {
        if ($id_anggota == null) {
            return ["status" => "failed", "message" => "Tidak ditemukan data."];
        }

        // Check only Peserta
        $get = $this->db->query("
            SELECT p.id_peserta
            FROM peserta p 
            WHERE p.id_user = ?
        ", array($id_user));
        if ($get->num_rows() == 0) {
            return ["status" => "failed", "message" => "Anda bukan Peserta!"];
        }

        $get = $this->db->query("
            SELECT id_peserta, posisi 
            FROM peserta_anggota 
            WHERE id_anggota = ?
        ", array($id_anggota));

        $id_profile = $get->num_rows() > 0 ? $get->row()->id_peserta : 0;
        $posisi = $get->num_rows() > 0 ? $get->row()->posisi : 0;

        if ($id_profile == 0 || $posisi == 0) {
            return ["status" => "failed", "message" => "Tidak ditemukan data profile peserta!"];
        }

        if ($posisi == '1') {
            return ["status" => "failed", "message" => "Tidak bisa menghapus Ketua Anggota!"];
        }

        $this->db->trans_begin();

        // Set Ketua
        $this->db->query("
            UPDATE peserta_anggota SET is_active = '0' 
            WHERE id_anggota = ?
        ", array($id_anggota));

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return ["status" => "failed", "message" => "Gagal proses data. Hubungi administrator."];
        } else {
            $this->db->trans_commit();
            $result = $this->member_peserta($id_profile);
            return ["status" => "ok", "data" => $result];
        }
    }

    function set_latlon($data, $id_user) {
        if ($data == null) {
            return ["status" => "failed", "message" => "Tidak ditemukan data."];
        }

        // Check only Peserta
        $get = $this->db->query("
            SELECT p.id_peserta
            FROM peserta p 
            WHERE p.id_user = ?
        ", array($id_user));
        if ($get->num_rows() == 0) {
            return ["status" => "failed", "message" => "Anda bukan Peserta!"];
        }

        $id_peserta = $get->num_rows() > 0 ? $get->row()->id_peserta : 0;

        $key = ['latitude', 'longitude'];
        foreach($key as $check) {
            if (!array_key_exists($check, $data)) {
                return ["status" => "failed", "message" => "Parameter '" . $check . "' tidak ditemukan."];
            }
        }

        $latitude = $data['latitude'];
        $longitude = $data['longitude'];

        $this->db->trans_begin();

        // Set LatLon
        $this->db->query("
            UPDATE peserta 
            SET latitude = ?, longitude = ? 
            WHERE id_peserta = ?
        ", array($latitude, $longitude, $id_peserta));

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return ["status" => "failed", "message" => "Gagal proses data. Hubungi administrator."];
        } else {
            $this->db->trans_commit();
            return ["status" => "ok", "data" => "Pin Map berhasil disimpan."];
        }
    }

    function update_peserta($data, $id_user) {
        if ($data == null) {
            return ["status" => "failed", "message" => "Tidak ditemukan data."];
        }

        // check for peserta
        $get = $this->db->query("
            SELECT 
            id_peserta
            FROM peserta 
            WHERE id_user = ?
        ", array($id_user));
        if ($get->num_rows() == 0) {
            return ["status" => "failed", "message" => "Anda bukan peserta."];
        }

        $id_peserta = $get->row()->id_peserta;

        $key = ['email', 'id_jenis_usaha', 'id_asal_institusi', 'no_hp_usaha', 'bidang_usaha', 'komoditas_usaha', 'sub_sektor', 'badan_usaha', 'alamat', 'kode_kecamatan', 'tanggal_mulai_usaha'];
        foreach($key as $check) {
            if (!array_key_exists($check, $data)) {
                return ["status" => "failed", "message" => "Parameter '" . $check . "' tidak ditemukan."];
            }
        }

        $email = $data['email'];
        $id_jenis = $data['id_jenis_usaha'];
        $asal = $data['id_asal_institusi'];
        $no_hp = $data['no_hp_usaha'];
        $bidang = $data['bidang_usaha'];
        $komoditas = $data['komoditas_usaha'];
        $sub_sektor = $data['sub_sektor'];
        $badan = $data['badan_usaha'];
        $alamat = $data['alamat'];
        $kode_kecamatan = $data['kode_kecamatan'];
        $tgl_mulai = $data['tanggal_mulai_usaha'];
        $deskripsi = array_key_exists('deskripsi', $data) ? $data['deskripsi'] : null;

        if (!in_array(strtoupper($sub_sektor), ['TAN', 'HOR', 'NAK', 'BUN', 'LAINNYA'])) {
            return ["status" => "failed", "message" => "Field 'sub_sektor' harus diisi dengan salah satu value: 'TAN' / 'HOR' / 'NAK' / 'BUN' / 'Lainnya'"];
        }

        if (strtolower($sub_sektor) == 'lainnya') {
            $sub_sektor = ucwords(strtolower($sub_sektor));
        }

        if (!in_array(strtolower($badan), ['belum ada', 'pt', 'cv', 'persero', 'ud', 'firma', 'koperasi'])) {
            return ["status" => "failed", "message" => "Field 'badan_usaha' harus diisi dengan salah satu value: 'belum ada' / 'pt' / 'cv' / 'persero' / 'ud' / 'firma' / 'koperasi'"];
        }

        if (in_array(strtolower($badan), ['belum ada', 'persero', 'firma', 'koperasi'])) {
            $badan = ucwords(strtolower($badan));
        } else {
            $badan = strtoupper($badan);
        }

        // Get Province + Kabkota
        $id_provinsi = null; $id_kabkota = null;
        $address = $this->db->query("
            SELECT 
            d.id id_kecamatan, d.name kecamatan, r.id id_kabkota, r.name kabkota, prv.id id_provinsi, prv.name provinsi 
            FROM districts d 
            LEFT JOIN regencies r ON r.id = d.regency_id 
            LEFT JOIN provinces prv ON prv.id = r.province_id 
            WHERE d.id = ?
        ", array($kode_kecamatan));
        if ($address->num_rows() > 0) {
            $id_kabkota = $address->row()->id_kabkota;
            $id_provinsi = $address->row()->id_provinsi;
        }

        // Process it..
        $this->db->trans_begin();
        
        // update
        $this->db->query("
            UPDATE peserta 
            SET email = ?, id_jenis_usaha = ?, id_pelaksana = ?, 
            no_hp_usaha = ?, bidang_usaha = ?, komoditas_usaha = ?, 
            sub_sektor = ?, badan_usaha = ?, alamat = ?, kode_provinsi = ?, kode_kab = ?, 
            kode_kecamatan = ?, tanggal_mulai_usaha = ?, deskripsi = ? 
            WHERE id_peserta = ?
        ", array($email, $id_jenis, $asal, $no_hp, $bidang, $komoditas, $sub_sektor, $badan, $alamat, $id_provinsi, $id_kabkota, $kode_kecamatan, $tgl_mulai, $deskripsi, $id_peserta));

        // update email
        $this->db->query("
            UPDATE user 
            SET email = ? 
            WHERE id_user = ?
        ", array($email, $id_user));

        // get data again to show to client
        $get = $this->peserta_data($id_user);

        $result = null;
        if ($get['status'] == 'ok') {
            $result = $get['data'];
        }
        // end get data

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return ["status" => "failed", "message" => "Gagal proses data. Hubungi administrator."];
        } else {
            $this->db->trans_commit();
            return ["status" => "ok", "data" => $result];
        }
    }

    function get_detail_anggota($id) {
        $result = null;
        // member
        $get = $this->db->query("
            SELECT 
            pa.id_anggota, pa.nik, pa.nama, pa.tempat_lahir, pa.tanggal_lahir, 
            pa.posisi, pa.no_hp, pa.alamat, 
            pa.status_keanggotaan, pa.url_photo, pa.last_modified_time
            FROM peserta p 
            LEFT JOIN peserta_anggota pa 
            ON p.id_peserta = pa.id_peserta 
            WHERE pa.id_anggota = ?
        ", array($id));
        if ($get->num_rows() > 0) {
            $r = $get->row();
            $result['id'] = $r->id_anggota;
            $result['nik'] = $r->nik != null ? $r->nik : "";
            $result['nama'] = $r->nama != null ? $r->nama : "";
            $result['tempat_lahir'] = $r->tempat_lahir;
            $result['tanggal_lahir'] = $r->tanggal_lahir;
            $result['posisi'] = $r->posisi != null ? ($r->posisi == 1 ? "Ketua" : "Anggota") : "";
            $result['no_hp'] = $r->no_hp != null ? $r->no_hp : "";
            $result['alamat'] = $r->alamat != null ? $r->alamat : "";
            $result['status_keanggotaan'] = $r->status_keanggotaan != 0 ? 'aktif' : 'tidak aktif';
            $result['url_photo'] = $r->url_photo;
            $result['last_modified_time'] = $r->last_modified_time;
        }

        return ["status" => "ok", "data" => $result];
    }

    function update_anggota($data, $id_user, $id) {
        if ($data == null) {
            return ["status" => "failed", "message" => "Tidak ditemukan data."];
        }

        // check for peserta
        $get = $this->db->query("
            SELECT 
            id_peserta
            FROM peserta 
            WHERE id_user = ?
        ", array($id_user));
        if ($get->num_rows() == 0) {
            return ["status" => "failed", "message" => "Anda bukan peserta."];
        }

        $key = ['nama', 'tempat_lahir', 'tanggal_lahir', 'no_hp', 'alamat'];
        foreach($key as $check) {
            if (!array_key_exists($check, $data)) {
                return ["status" => "failed", "message" => "Parameter '" . $check . "' tidak ditemukan."];
            }
        }

        $nama = $data['nama'];
        $tempat_lahir = $data['tempat_lahir'];
        $tanggal_lahir = $data['tanggal_lahir'];
        $no_hp = $data['no_hp'];
        $alamat = $data['alamat'];

        // Process it..
        $this->db->trans_begin();
        
        // update
        $this->db->query("
            UPDATE peserta_anggota 
            SET nama = ?, tempat_lahir = ?, tanggal_lahir = ?, 
            no_hp = ?, alamat = ? 
            WHERE id_anggota = ?
        ", array($nama, $tempat_lahir, $tanggal_lahir, $no_hp, $alamat, $id));

        // get data again to show to client
        $get = $this->get_detail_anggota($id);

        $result = null;
        if ($get['status'] == 'ok') {
            $result = $get['data'];
        }
        // end get data

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return ["status" => "failed", "message" => "Gagal proses data. Hubungi administrator."];
        } else {
            $this->db->trans_commit();
            return ["status" => "ok", "data" => $result];
        }
    }

    function update_mentor($data, $id_user) {
        if ($data == null) {
            return ["status" => "failed", "message" => "Tidak ditemukan data."];
        }

        // check for mentor
        $get = $this->db->query("
            SELECT 
            id_mentor
            FROM mentor 
            WHERE id_user = ?
        ", array($id_user));
        if ($get->num_rows() == 0) {
            return ["status" => "failed", "message" => "Anda bukan mentor."];
        }

        $id_mentor = $get->row()->id_mentor;

        $key = ['nama', 'email', 'no_hp', 'bidang_usaha', 'lama_usaha', 'pengalaman'];
        foreach($key as $check) {
            if (!array_key_exists($check, $data)) {
                return ["status" => "failed", "message" => "Parameter '" . $check . "' tidak ditemukan."];
            }
        }

        $nama = $data['nama'];
        $email = $data['email'];
        $no_hp = $data['no_hp'];
        $bidang = $data['bidang_usaha'];
        $lama = $data['lama_usaha'];
        $pengalaman = $data['pengalaman'];

        // Process it..
        $this->db->trans_begin();
        
        // update
        $this->db->query("
            UPDATE mentor 
            SET nama = ?, email = ?, no_hp = ?, 
            bidang_usaha = ?, lama_usaha = ?, pengalaman = ? 
            WHERE id_mentor = ?
        ", array($nama, $email, $no_hp, $bidang, $lama, $pengalaman, $id_mentor));

        // update email
        $this->db->query("
            UPDATE user 
            SET email = ?, real_name = ? 
            WHERE id_user = ?
        ", array($email, $nama, $id_user));

        // get data again to show to client
        $get = $this->mentor_data($id_user);

        $result = null;
        if ($get['status'] == 'ok') {
            $result = $get['data'];
        }
        // end get data

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return ["status" => "failed", "message" => "Gagal proses data. Hubungi administrator."];
        } else {
            $this->db->trans_commit();
            return ["status" => "ok", "data" => $result];
        }
    }

    function update_pembimbing($data, $id_user) {
        if ($data == null) {
            return ["status" => "failed", "message" => "Tidak ditemukan data."];
        }

        // check for pembimbing
        $get = $this->db->query("
            SELECT 
            id_pembimbing
            FROM pembimbing 
            WHERE id_user = ?
        ", array($id_user));
        if ($get->num_rows() == 0) {
            return ["status" => "failed", "message" => "Anda bukan pembimbing."];
        }

        $id_pembimbing = $get->row()->id_pembimbing;

        $key = ['nama', 'email', 'no_hp', 'id_asal_institusi'];
        foreach($key as $check) {
            if (!array_key_exists($check, $data)) {
                return ["status" => "failed", "message" => "Parameter '" . $check . "' tidak ditemukan."];
            }
        }

        $nama = $data['nama'];
        $email = $data['email'];
        $no_hp = $data['no_hp'];
        $id_pelaksana = $data['id_asal_institusi'];

        // Process it..
        $this->db->trans_begin();
        
        // update
        $this->db->query("
            UPDATE pembimbing 
            SET nama = ?, email = ?, no_hp = ?, 
            id_pelaksana = ?  
            WHERE id_pembimbing = ?
        ", array($nama, $email, $no_hp, $id_pelaksana, $id_pembimbing));

        // update email
        $this->db->query("
            UPDATE user 
            SET email = ?, real_name = ?  
            WHERE id_user = ?
        ", array($email, $nama, $id_user));

        // get data again to show to client
        $get = $this->pembimbing_data($id_user);

        $result = null;
        if ($get['status'] == 'ok') {
            $result = $get['data'];
        }
        // end get data

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return ["status" => "failed", "message" => "Gagal proses data. Hubungi administrator."];
        } else {
            $this->db->trans_commit();
            return ["status" => "ok", "data" => $result];
        }
    }

    function update_photo($id_user, $id_group) {
        if (sizeof($_FILES) == 0) {
            return ["status" => "failed", "message" => "No File Founded"];
        }

        // Front End Path
        // $front_url = $this->config->item('front_url');
        $front_url = base_url();
        $front_url = substr($front_url, -1) == DIRECTORY_SEPARATOR ? $front_url : $front_url . DIRECTORY_SEPARATOR;

        try {
            date_default_timezone_set('Asia/Jakarta');
		
            $raw_data = $_FILES['file']['name'];
            
            $url = rtrim(base_url(), "/");
            $url2 = explode('/', $url);
            $folder = end($url2);
            $directory = $this->direktori($folder, $id_user);

            $raw_location = $directory['fpath'];
            $dynamic_location = $directory['dpath'];
            
            $ori_name = $raw_data;
            $ex = explode(".", $ori_name);
            $extension = end($ex);
            
            $file_name = underscore("photo_" . date("YmdHis") . "." . $extension);
            
            $uploadfile = $raw_location . DIRECTORY_SEPARATOR . $file_name;
            $webpath = $dynamic_location . DIRECTORY_SEPARATOR . $file_name;

            if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
                // check group user
                $group = $this->user_group($id_group);
                
                // Set to DB
                $this->db->trans_begin();

                $query = null;
                if (strpos(strtolower($group), 'pembimbing') !== false) {
                    $query = "
                        UPDATE pembimbing 
                        SET url_photo = ?, is_active = '1', last_modified_time = NOW() 
                        WHERE id_user = ?
                    ";
                } else if (strpos(strtolower($group), 'mentor') !== false) {
                    $query = "
                        UPDATE mentor 
                        SET url_photo = ?, is_active = '1', last_modified_time = NOW() 
                        WHERE id_user = ?
                    ";
                } else if (strpos(strtolower($group), 'peserta') !== false) {
                    $query = "
                        UPDATE peserta 
                        SET url_logo = ?, is_active = '1', last_modified_time = NOW() 
                        WHERE id_user = ?
                    ";
                }

                if ($query != null) {
                    $this->db->query($query, array($front_url . $webpath, $id_user));
                }
                // End set to DB

                if ($query == null || $this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    return ["status" => "failed", "message" => "Gagal Upload Data. Silahkan hubungi Administrator."];
                } else {
                    $this->db->trans_commit();
                    $result = array('path' => $front_url . $webpath);
                    
                    return ["status" => "ok", "data" => $result];
                }
            } else {
                return ["status" => "failed", "message" => "Failed upload file."];
            }
        } catch (Exception $e) {
            return ["status" => "failed", "message" => $e];
        }
    }

    function update_photo_anggota($id_user, $id_member) {
        if (sizeof($_FILES) == 0) {
            return ["status" => "failed", "message" => "No File Founded"];
        }

        // Front End Path
        // $front_url = $this->config->item('front_url');
        $front_url = base_url();
        $front_url = substr($front_url, -1) == DIRECTORY_SEPARATOR ? $front_url : $front_url . DIRECTORY_SEPARATOR;

        try {
            date_default_timezone_set('Asia/Jakarta');
		
            $raw_data = $_FILES['file']['name'];
            
            $url = rtrim(base_url(), "/");
            $url2 = explode('/', $url);
            $folder = end($url2);
            $directory = $this->direktori($folder, $id_user);

            $raw_location = $directory['fpath'];
            $dynamic_location = $directory['dpath'];
            
            $ori_name = $raw_data;
            $ex = explode(".", $ori_name);
            $extension = end($ex);
            
            $file_name = underscore("photo_" . date("YmdHis") . "." . $extension);
            
            $uploadfile = $raw_location . DIRECTORY_SEPARATOR . $file_name;
            $webpath = $dynamic_location . DIRECTORY_SEPARATOR . $file_name;

            if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
                // Set to DB
                $this->db->trans_begin();

                $this->db->query("
                    UPDATE peserta_anggota 
                    SET url_photo = ?, is_active = '1', last_modified_time = NOW() 
                    WHERE id_anggota = ?
                ", array($front_url . $webpath, $id_member));
                // End set to DB

                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    return ["status" => "failed", "message" => "Gagal Upload Data. Silahkan hubungi Administrator."];
                } else {
                    $this->db->trans_commit();
                    $result = array('path' => $front_url . $webpath);
                    
                    return ["status" => "ok", "data" => $result];
                }
            } else {
                return ["status" => "failed", "message" => "Failed upload file."];
            }
        } catch (Exception $e) {
            return ["status" => "failed", "message" => $e];
        }
    }
    
    function direktori($folder, $id){
        $full_path = $this->config->item('dir_upload');
        $full_path = substr($full_path, -1) == DIRECTORY_SEPARATOR ? $full_path : $full_path . DIRECTORY_SEPARATOR;
        $dynamic_path = 'public' . DIRECTORY_SEPARATOR . 'dokumen' . DIRECTORY_SEPARATOR . $id;
        
        // $location = $full_path . $dynamic_path;
        $location = $full_path . $id;

		if (!file_exists($location)) {
            mkdir($location, 0777, true);
        }
		
		return array("fpath" => $location, "dpath" => $dynamic_path);
    }

}
