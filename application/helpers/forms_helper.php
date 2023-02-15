<?php
defined('BASEPATH') or exit('No direct script access allowed');

if (!function_exists('filterFieldsOfTable')) {
	/**
	 * fungsi untuk melakukan pencocokan field tabel dengan form
	 *
	 * @param  mixed $table_name = referensi tabel
	 * @param  mixed $data = data yang dicek
	 * @return void $return
	 */
	function filterFieldsOfTable($table_name, $data)
	{
		$return = [];
		$ci = &get_instance();

		if ($ci->db->table_exists($table_name)) {
			$get = $ci->db->list_fields($table_name);

			foreach ($get as $key => $rows) {
				if (array_key_exists($rows, $data)) {
					$return[$rows] = $data[$rows];
				}
			}
		} else {
			$return = [];
		}
		return $return;
	}
}

if (!function_exists('filterFields')) {
	function filterFields($params)
	{
		$return['status']  = 0;
		$return['message'] = '';

		$error_code = [];
		foreach ($params as $k => $v) {
			if ($v == "" || $v == null) {
				array_push($error_code, 0);
			} else {
				array_push($error_code, 1);
			}
		}
		if (in_array(0, $error_code)) {
			$return['status']  = 500;
			$return['message'] = 'Form isian tidak lengkap. Silahkan lengkap formulir anda';
		} else {
			$return['status']  = 201;
			$return['message'] = 'Form isian lengkap.';
		}
		return $return;
	}
}

if (!function_exists('checkAccount')) {
	/**
	 * checkAccount
	 * fungsi untuk melakukan validasi akun
	 * @param  array $data
	 * @param  int $id
	 * @return array
	 */
	function checkAccount($data, $id = "")
	{
		$return['status'] = 0;
		$return['data']   = [];

		$ci = &get_instance();

		$ci->db->select("*");
		$ci->db->from("user");
		$ci->db->where($data);
		if ($id != "") {
			$ci->db->where("id_user !=", $id);
		}
		$get = $ci->db->get();
		if ($get->num_rows() != 0) {
			$get->row_array();
			$return['status'] = 201;
			$return['data']   = $get->row_object();
		} else {
			$return['status'] = 500;
			$return['data']   = [];
		}
		return $return;
	}
}
