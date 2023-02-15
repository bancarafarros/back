<?php
defined('BASEPATH') or exit('No direct script access allowed');


function logActivities($table, $data)
{
    $ci = &get_instance();

    $ci->db->set($data);
    $ci->db->insert($table, $data);
    if ($ci->db->affected_rows()) {
        return true;
    } else {
        return false;
    }
}

function randomPasswordNumber()
{
    $alphabet = '1234567890';
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < 8; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
}

function getTahun($is_current = null)
{
    $ci  = &get_instance();

    $ci->db->where("is_active", "1");
    if ($is_current == 1) {
        $ci->db->where("is_current", "1");
    }
    $ci->db->order_by('tahun', 'asc');
    $prov = $ci->db->get('ref_tahun');

    if ($prov->num_rows() != 0) {
        if ($is_current == "1") {
            return $prov->row_array();
        } else {
            return $prov->result_array();
        }
    } else {
        return null;
    }
}

function formatPhoneNumber($phone)
{
    $pattern = '/^0/';

    $return = preg_replace($pattern, '62', $phone);

    return $return;
}

/**
 * getEnumValues
 * fungsi untuk mendapatkan nilai enum dari suatu tabel
 * @param  mixed $table tabel referensi
 * @param  mixed $field nama field yang diambil
 * @return void
 */
function getEnumValues($table, $field)
{
    $ci = &get_instance();

    $type = $ci->db->query("SHOW COLUMNS FROM {$table} WHERE Field = '{$field}'")->row(0)->Type;
    preg_match("/^enum\(\'(.*)\'\)$/", $type, $matches);
    $enum = explode("','", $matches[1]);
    return $enum;
}

/**
 * convertToJSONFormat
 * fungsi untuk mengubah field array menjadi json
 * @param  mixed $label => label identified jenis usaha
 * @param  mixed $data => data yang akan diformat menjadi json
 * @return void json string
 */
function convertToJSONFormat($label, $data)
{
    $return = [];

    if (!empty($data)) {
        for ($i = 0; $i < count($data); $i++) {
            $return[$i][$label] = $data[$i];
        }
        return json_encode($return);
    } else {
        return $return;
    }
}

/**
 * convertToArrayFormat
 * helper untuk mengembalikan data json menjadi data array
 * @param  mixed $data => data json misal : [{"id_jenis_usaha":"1"},{"id_jenis_usaha":"3"}]
 * @return array data
 */
if (!function_exists('convertToArrayFormat')) {
    function convertToArrayFormat($data)
    {
        $return = json_decode($data, true);
        return $return;
    }
}

if (!function_exists('getValueIdFromArray')) {
    function getValueIdFromArray($data, $label)
    {
        $array_data = convertToArrayFormat($data);

        $return = [];

        if (is_array($array_data)) {
            foreach ($array_data as $rows) {
                $return[] = $rows[$label];
            }
        } else {
            $return = [];
        }
        return $return;
    }
}


/**
 * getReferenceDataSource
 * fungsi untuk mendapatkan data referensi dari field json
 * @param  mixed $references_table : referensi tabel
 * @param  mixed $primary_key : primary key tabel
 * @param  mixed $data : data format json
 * @param  mixed $string_label : label json
 * @return void array
 */
function getReferenceDataSource($references_table, $primary_key, $data, $string_label)
{
    $ci = &get_instance();

    if ($data != '' || $data != null) {

        $data_source = json_decode($data, true);

        if (!empty($data_source)) {
            $params = [];

            foreach ($data_source as $key => $rows) {
                $params[$key] = $rows[$string_label];
            }

            $ci->db->where_in($primary_key, $params);
            $get = $ci->db->get($references_table);

            if ($get->num_rows() != 0) {
                return $get->result_array();
            } else {
                return [];
            }
        } else {
            return [];
        }
    }
}

if (!function_exists('getTableReference')) {
    /**
     * getTableReference
     * fungsi untuk mendapatkan data referensi tabel secara dinamis
     * @param  string $table = nama tabel referensi
     * @return array
     */
    function getTableReference($table)
    {
        $ci = &get_instance();

        $ci->db->select("*");
        $ci->db->from($table);
        $get = $ci->db->get();
        if ($get->num_rows() != 0) {
            return $get->result_array();
        } else {
            return [];
        }
    }
}

if (!function_exists('getValueSourceTable')) {
    /**
     * getValueSourceTable
     * function to get value from resource table
     * @param  string $table => table name
     * @param  array $params array parameter exp : [id => 1]
     * @return array
     */
    function getValueSourceTable($table, $params)
    {
        $ci = &get_instance();

        $ci->db->select("*");
        $ci->db->from($table);
        if (!empty($params)) {
            $ci->db->where($params);
        }
        $get = $ci->db->get();
        if ($get->num_rows() != 0) {
            return $get->row_array();
        } else {
            return [];
        }
    }
}

if (!function_exists('getDataSource')) {
    /**
     * getDataSource
     * fungsi untuk mendapatkan data referensi
     * @param  string $source_table = nama tabel referensi
     * @param  string $primary_key = primary key tabel
     * @param  array $params parameter array
     * @return array result array
     */
    function getDataSource($source_table, $primary_key, $params)
    {
        $ci = &get_instance();

        $ci->db->select("*");
        if (is_array($params)) {
            $ci->db->where_in($primary_key, $params);
        } else {
            $ci->db->where($primary_key, $params);
        }
        $get = $ci->db->get($source_table);

        if ($get->num_rows() != 0) {
            if (is_array($params)) {
                return $get->result_array();
            } else {
                return $get->row_array();
            }
        } else {
            return [];
        }
    }
}


if (!function_exists('getRandomWord')) {
    function getRandomWord($len = 5)
    {
        $word = array_merge(range('a', 'z'), range('A', 'Z'));
        shuffle($word);
        return substr(implode($word), 0, $len);
    }
}

if (!function_exists('checkUserAccess')) {
    /**
     * checkUserAccess
     * check user exists access role application
     * @param  array $params
     * @param  integer $id
     * @return array
     */
    function checkUserAccess($params, $id = "")
    {
        $return['status'] = 0;
        $return['data']   = [];

        $ci = &get_instance();

        $ci->db->where($params);
        if ($id != '') {
            $ci->db->where('id_user !=', $id);
        }

        $get = $ci->db->get('user');

        if ($get->num_rows() != 0) {
            $return['status'] = 201;
            $return['data']   = $get->row_object();
        } else {
            $return['status'] = 500;
            $return['data']   = [];
        }
        return $return;
    }
}

function active_page($page, $class)
{
    $_this = &get_instance();
    if ($page == $_this->uri->segment(1)) {
        return $class;
    }
}

// function getUUID()
// {
//     $ci = &get_instance();
//     $ci->load->library('Uuid', 'uuid');

//     $result = $ci->db->query("SELECT UUID()")->row_array()['UUID()'];
//     return $result;
// }

function getUUID($versi = 4)
{
    $ci = &get_instance();
    $ci->load->library(["Uuid" => 'uuid']);
    // if ($versi == 1) {
    //     $result = $ci->db->query("SELECT uuid_generate_v1() as uuid")->row_array();
    // } else {
    //     $result = $ci->db->query("SELECT uuid_generate_v4() as uuid")->row_array();
    // }
    // $return = $result['uuid'];
    if ($versi == 4) {
        $return = $ci->uuid->v4();
    } else {
        $return = $ci->uuid->v4();
    }
    return $return;
}

function isPDF($param)
{
    $file = 'gambar';
    $panjang =  strlen($param);
    if (strpos($param, '.pdf')) {
        $file = 'pdf';
    }
    return $file;
}

function tampil_sebagian($param, $panjang)
{
    //$panjang = strlen($param);
    $tampil = substr($param, 0, $panjang);
    return $tampil;
}

function activeYear()
{
    $ci = &get_instance();
    $get = $ci->db->query("SELECT * FROM ref_tahun where is_current='1'");
    $return = ($get->num_rows() != 0) ? $get->row_array()['tahun'] : null;
    return $return;
}

function getDayName($day_of_week)
{
    switch ($day_of_week) {
        case 1:
            return 'Senin';
            break;

        case 2:
            return 'Selasa';
            break;

        case 3:
            return 'Rabu';
            break;

        case 4:
            return 'Kamis';
            break;

        case 5:
            return 'Jumat';
            break;

        case 6:
            return 'Sabtu';
            break;

        case 0:
            return 'Minggu';
            break;

        default:
            return 'Senin';
            break;
    }
}

function getMonthName($month)
{
    switch ($month) {
        case 1:
            return 'Januari';
            break;

        case 2:
            return 'Februari';
            break;

        case 3:
            return 'Maret';
            break;

        case 4:
            return 'April';
            break;

        case 5:
            return 'Mei';
            break;

        case 6:
            return 'Juni';
            break;

        case 7:
            return 'Juli';
            break;

        case 8:
            return 'Agustus';
            break;

        case 9:
            return 'September';
            break;

        case 10:
            return 'Oktober';
            break;

        case 11:
            return 'November';
            break;

        case 12:
            return 'Desember';
            break;

        default:
            # code...
            break;
    }
}

function parseTanggal($date)
{
    date_default_timezone_set('Asia/Jakarta');
    $day_name = getDayName(date('w', strtotime($date)));
    $day = date('d', strtotime($date));
    $month = getMonthName(date('m', strtotime($date)));
    $year = date('Y', strtotime($date));
    return "$day_name, $day $month $year";
}

if (!function_exists('auto_code')) {
    function auto_code($prefix, $delim = "", $position = "append")
    {
        $ci  = &get_instance();
        $values = array($prefix);
        $ci->db->query("INSERT INTO auto_code (prefix, sequence ) VALUES ( ?, 1 ) ON DUPLICATE KEY UPDATE sequence  =  sequence + 1", $values);
        $result  =  $ci->db->query("SELECT sequence FROM auto_code WHERE prefix = ?", $values);
        $row  =  $result->row();
        if ($position == "append") {
            $result  =  strtoupper($prefix) . $delim . str_pad($row->sequence, 5, '0', STR_PAD_LEFT);
        } else {
            $result  =  str_pad($row->sequence, 5, '0', STR_PAD_LEFT) . $delim . strtoupper($prefix);
        }

        return $result;
    }
}

function isBrowser($userAgent)
{
	$browsers = ['msie', 'trident', 'firefox', 'chrome', 'opera mini', 'safari'];
	$isBrowser = false;
	for ($i = 0; $i < count($browsers); $i++) {
		if (strpos(strtolower($userAgent), $browsers[$i]) !== FALSE) {
			$bro = $browsers[$i];
			if (in_array($bro, $browsers)) {
				$isBrowser = true;
				break;
			}
		}
	}

	return $isBrowser;
}