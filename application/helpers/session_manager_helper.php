<?php
defined('BASEPATH') or exit('No direct script access allowed');

if (!function_exists('isHasSessionData')) {
    /**
     * isHasSessionData
     * fungsi untuk melakukan pengecekan session data
     * @return boolean
     * jika mempunyai session return true
     * jika mempunyai session return false
     */
    function isHasSessionData()
    {
        $ci = &get_instance();

        if ($ci->session->has_userdata(PREFIX_SESS . '_userId')) {
            return true;
        } else {
            return false;
        }
    }
}

if (!function_exists('getSessionGroup')) {
    /**
     * getSessionGroup : fungsi untuk mendapatkan session data
     *PREFIX_SESS. _namaGroup
     *PREFIX_SESS. _userId
     *PREFIX_SESS. _nik
     *PREFIX_SESS. _nama
     *PREFIX_SESS. _email
     *PREFIX_SESS. _idGroup
     *PREFIX_SESS. _isActive
     *PREFIX_SESS. _refTahun
     *PREFIX_SESS. _dbUsername
     * @return void array
     */
    function getSessionGroup()
    {
        $ci = &get_instance();
        if ($ci->session->has_userdata(PREFIX_SESS . "_userId")) {
            $group_id = $ci->session->all_userdata();
            return $group_id;
        } else {
            redirect(site_url(''));
        }
    }
}

if (!function_exists('getSessionID')) {
    /**
     * getSessionID
     * fungsi untuk mendapatkan id user yang sedang login
     * @return String id user
     */
    function getSessionID()
    {
        return getSessionGroup()[PREFIX_SESS . '_userId'];
    }
}

if (!function_exists('getSessionActiveYears')) {
    /**
     * getSessionActiveYears
     *  fungsi untuk mendapatkan session tahun aktif
     * @return string PREFIX_SESS. _refTahun
     */
    function getSessionActiveYears()
    {
        $sessions = getSessionGroup();
        return $sessions[PREFIX_SESS . '_refTahun'];
    }
}

if (!function_exists('getSessionName')) {
    /**
     * getSessionName
     * fungsi untuk mendapatkan nama user yang sedang login
     * @return String id user
     */
    function getSessionName()
    {
        return getSessionGroup()[PREFIX_SESS . '_nama'];
    }
}
if (!function_exists('getSessionEmail')) {
    /**
     * getSessionEmail
     * fungsi untuk mendapatkan email user yang sedang login
     * @return String id user
     */
    function getSessionEmail()
    {
        return getSessionGroup()[PREFIX_SESS . '_email'];
    }
}
if (!function_exists('getSessionRole')) {
    /**
     * getSessionRole
     * fungsi untuk mendapatkan nama user yang sedang login
     * @return String id user
     */
    function getSessionRole()
    {
        return getSessionGroup()[PREFIX_SESS . '_idGroup'];
    }
}
if (!function_exists('getSessionRoleAccess')) {
    /**
     * getSessionRoleAccess
     *
     * @param  mixed $param_modul
     * @return void
     */
    function getSessionRoleAccess($param_modul = "")
    {
        $ci = &get_instance();

        if ($param_modul != "") {
            $modul = $param_modul;
        } else {
            $modul = strtolower($ci->uri->segment(1));
        }
        if (!getSessionID()) {
            if ($modul != 'login' && $modul != '') {
                redirect(site_url());
            }
        } else {
            $id_group = getSessionRole();
            if ($modul != "index" && ($modul != '' || $modul != null)) {
                $query = $ci->db->get_where('akses_group_modul', ['id_group' => $id_group, 'nama_modul' => $modul])->row_array();
                if (empty($query) || $query['hak_akses'] != "access") {
                    $message = "Maaf, Anda tidak memiliki akses ke halaman tersebut.";
                    $ci->session->set_flashdata('false', $message);
                    responseJson(403, ['status' => 403, 'message' => $ci->session->flashdata('false')]);
                }
            }
        }
    }
}
