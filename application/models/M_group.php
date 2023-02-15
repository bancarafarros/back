<?php

class M_group extends CI_Model
{

	function __construct()
	{
		parent::__construct();
		$this->db->db_debug = false;
	}

	public function getUserGroup($group_id)
	{
		$this->db->where('id_group', $group_id);
		return $this->db->query($this->db->get_compiled_select('user_group'))->row();
	}

	public function getUserGroupAccess($group_id)
	{
		$sql = "SELECT id_modul, ms.nama_modul, hak_akses FROM modul_sistem ms LEFT JOIN (SELECT * FROM akses_group_modul WHERE id_group = $group_id) ag ON ms.nama_modul = ag.nama_modul WHERE is_active = 1 ORDER BY ms.nama_modul ASC";

		return $this->db->query($sql)->result();
	}

	public function setUserGroupAccess($group_id, $nama_modul, $hak)
	{
		$data = array(
			'id_group' => $group_id,
			'nama_modul' => $nama_modul,
			'hak_akses' => $hak
		);
		$this->db->insert('akses_group_modul', $data);
	}

	public function removeUserGroupAccess($group_id)
	{
		$this->db->where('id_group', $group_id);
		$this->db->delete('akses_group_modul');
	}

	public function isGroupAccessExist($group_id, $nama_modul)
	{
		$this->db->where('nama_modul', $nama_modul);
		$this->db->where('id_group', $group_id);

		if ($this->db->get('akses_group_modul')) {
			return true;
		}
		return false;
	}

	public function updateGroupAccess($group_id, $access)
	{

		$this->db->trans_start();
		$this->removeUserGroupAccess($group_id);

		foreach ($access as $key => $value) {
			$this->setUserGroupAccess($group_id, $value, 'access');
		}

		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
		}
	}

	public function canAccess($group_id, $nama_modul)
	{
		$this->db->where('id_group', $group_id);
		$this->db->where('nama_modul', $nama_modul);
		$query = $this->db->get('akses_group_modul')->result();

		if (isset($query)) {
			return true;
		}
		return false;
	}

	public function getDITKodeKab($id_user)
	{
		$this->db->select('kode_kab');
		$this->db->where('id_user', intval($id_user));

		return $this->db->get('ref_kabupaten')->first_row()->kode_kab;
	}

	public function getPPIUKodeProp($id_user)
	{
		$this->db->select('kode_prop');
		$this->db->where('id_user', intval($id_user));

		return $this->db->get('ref_propinsi')->first_row()->kode_prop;
	}
}
