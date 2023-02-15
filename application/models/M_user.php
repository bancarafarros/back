<?php

class M_user extends CI_Model
{

	function __construct()
	{
		parent::__construct();
		$this->db->db_debug = false;
	}

	public function createUser($group_id, $data)
	{

		$table = $this->getTableName($group_id);

		$isEmailUsed = $this->isEmailUsed($data['email']);
		$isUsernameUsed = $this->isUsernameUsed($data['nomor_hp']);
		$isNIMUsed = false;
		$isNIKUsed = false;

		if (!isset($data['nik'])) {
			$isNIMUsed = $this->isNIMUsed($table, $data['username']);
		} else {
			$isNIKUsed = $this->isNIKUsed($table, $data['nik']);
		}

		if ($isNIMUsed || $isNIKUsed || $isUsernameUsed) {
			return [
				"status"  => "error",
				"message" => "NIK/ Username sudah digunakan."
			];
		}

		if ($isEmailUsed) {
			return [
				"status"  => "error",
				"message" => "Email sudah digunakan."
			];
		}

		$this->db->trans_start();

		$insert_user = $this->filterFields('user', $data);
		$this->db->insert('user', $insert_user);
		$user_id = $this->db->insert_id();

		$data["id_user"] = $user_id;
		$data["registered_time"] = date('Y-m-d H:i:s');

		$insert_data = $this->filterFields($table, $data);


		$inser = $this->db->insert($table, $insert_data);
		// var_dump($inser);
		// die;
		$id = $this->db->insert_id();

		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			return [
				"status"    => "error",
				"message"   => "Terjadi kesalahan server. Silahkan ulangi sesaat lagi."
			];
		}


		return [
			"status"  => "success",
			"message" => "Berhasil membuat user.",
			"id"      => $id,
		];
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

	public function createUserJamaah($group_id, $data)
	{

		$table = $this->getTableName($group_id);
		$isEmailUsed = $this->isEmailUsed($data['email']);

		if ($isEmailUsed) {
			return [
				"status"  => "error",
				"message" => "Email sudah digunakan."
			];
		}

		$this->db->trans_start();

		$insert_user = $this->filterFields('user', $data);
		$this->db->insert('user', $insert_user);

		$user_id = $this->db->insert_id();

		$data["id"] = getUUID();
		$data["id_user"] = $user_id;
		$data['is_active'] = '1';
		$data["created_by"] = $this->session->userdata('sajada_userId');

		$insert_data = $this->filterFields('jamaah', $data);
		// var_dump($insert_data);
		// exit();
		$inser = $this->db->insert('jamaah', $insert_data);
		$id = $this->db->insert_id();

		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			return [
				"status"    => "error",
				"message"   => "Terjadi kesalahan server. Silahkan ulangi sesaat lagi."
			];
		}
		return [
			"status"  => "success",
			"message" => "Berhasil membuat user.",
			"id"      => $id,
		];
	}

	public function createUserMasjid($group_id, $data)
	{

		$table = $this->getTableName($group_id);
		$isEmailUsed = $this->isEmailUsed($data['email']);

		if ($isEmailUsed) {
			return [
				"status"  => "error",
				"message" => "Email sudah digunakan."
			];
		}

		$this->db->trans_start();

		$insert_user = $this->filterFields('user', $data);
		$this->db->insert('user', $insert_user);

		$user_id = $this->db->insert_id();

		// $masjid_id = $this->getMasjid($data);
		// var_dump($masjid_id);
		// exit();

		$data["id"] = getUUID();
		// $data["id_masjid"] = $masjid_id;
		$data["id_user"] = $user_id;
		$data["created_by"] = $user_id;
		// $data["registered_time"] = date('Y-m-d H:i:s');
		$insert_data = $this->filterFields('masjid', $data);
		// var_dump($insert_data);
		// exit();
		$inser = $this->db->insert('masjid', $insert_data);

		$id = $this->db->insert_id();

		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			return [
				"status"    => "error",
				"message"   => "Terjadi kesalahan server. Silahkan ulangi sesaat lagi."
			];
		}

		return [
			"status"  => "success",
			"message" => "Berhasil membuat user.",
			"id"      => $id,
		];
	}

	public function createUserPengurus($group_id, $data)
	{

		$table = $this->getTableName($group_id);
		$isEmailUsed = $this->isEmailUsed($data['email']);

		if ($isEmailUsed) {
			return [
				"status"  => "error",
				"message" => "Email sudah digunakan."
			];
		}

		$this->db->trans_start();

		$insert_user = $this->filterFields('user', $data);
		$this->db->insert('user', $insert_user);

		$user_id = $this->db->insert_id();

		$data["id"] = getUUID();
		$data["id_user"] = $user_id;
		$data['is_active'] = '1';
		$data["created_by"] = $this->session->userdata('sajada_userId');

		$insert_data = $this->filterFields('masjid_pengurus', $data);
		// var_dump($insert_data);
		// exit();
		$inser = $this->db->insert('masjid_pengurus', $insert_data);
		$id = $this->db->insert_id();

		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			return [
				"status"    => "error",
				"message"   => "Terjadi kesalahan server. Silahkan ulangi sesaat lagi."
			];
		}
		return [
			"status"  => "success",
			"message" => "Berhasil membuat user.",
			"id"      => $id,
		];
	}

	public function getUser($id)
	{
		$this->db->where('id_user', $id);
		$query = $this->db->get('user');
		if ($query->num_rows() > 0) {
			return $query->row_array();
		} else {
			return null;
		}
	}

	function getDataUser($id_user, $username = null)
	{
		$this->db->select('user.*, user_group.nama_group');
		$this->db->join('user_group', 'user.id_group = user_group.id_group');
		$this->db->where('id_user', $id_user);
		if (!empty($username)) {
			$this->db->where('username', $username);
		}
		$data = $this->db->get('user');
		return $data->row();
	}

	public function ubah($id_user, $data)
	{
		$return['status'] = null;
		$return['message'] = null;
		$user = $this->getDataUser($id_user);
		$lanjut = true;
		if ($data['email'] != $user->email) {
			$cek = $this->isUsed('email', $data['email']);
			if ($cek) {
				$lanjut = false;
				$return['status'] = false;
				$return['message'] = 'Email telah digunakan, silahkan masukkan email yang lain';
			}
		}
		if ($lanjut) {
			$this->db->where(['id_user' => $id_user]);
			$proses =  $this->db->update('user', $data);
			if ($proses) {
				$return['status'] = true;
				$return['message'] = 'Ubah profil berhasil';
			} else {
				$return['status'] = false;
				$return['message'] = 'Ubah profil gagal';
			}
		}

		return $return;
	}

	public function updateUser($group_id, $data, $id_data)
	{
		$table = $this->getTableName($group_id);

		$isEmailUsed = $this->isEmailUsed($data['email'], $data['id_user']);
		$isUsernameUsed = $this->isUsernameUsed($data['nik'], $data['id_user']);
		$isNIMUsed = $this->isNIKUsed($table, $data['nik'], $id_data);

		if ($isNIMUsed || $isUsernameUsed) {
			return [
				"status"  => "error",
				"message" => "NIK/ Username sudah digunakan."
			];
		}

		if ($isEmailUsed) {
			return [
				"status"  => "error",
				"message" => "Email sudah digunakan."
			];
		}

		$this->db->trans_start();

		$update_user = $this->filterUpdateFields('user', $data);

		$this->db->where('id_user', $data['id_user']);
		$this->db->update('user', $update_user);

		$update_data = $this->filterUpdateFields($table, $data);

		$this->db->where('id', $id_data);
		$this->db->update($table, $update_data);

		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			return [
				"status"    => "error",
				"message"   => "Terjadi kesalahan server. Silahkan ulangi sesaat lagi."
			];
		}

		return [
			"status"  => "success",
			"message" => "Berhasil membuat user.",
		];
	}

	public function updateUserJamaah($group_id, $data)
	{
		$table = $this->getTableName($group_id);

		$isEmailUsed = $this->isEmailUsed($data['email']);
		$isUsernameUsed = $this->isUsernameUsed($data['nik']);

		if ($isUsernameUsed) {
			return [
				"status"  => "error",
				"message" => "Username sudah digunakan."
			];
		}

		if ($isEmailUsed) {
			return [
				"status"  => "error",
				"message" => "Email sudah digunakan."
			];
		}

		$this->db->trans_start();

		$update_data = $this->filterUpdateFields('jamaah', $data);
		// var_dump($update_data);
		// exit();
		$this->db->where('id_user', $data['id_user']);
		$this->db->update('jamaah', $update_data);

		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			return [
				"status"    => "error",
				"message"   => "Terjadi kesalahan server. Silahkan ulangi sesaat lagi."
			];
		}
		return [
			"status"  => "success",
			"message" => "Berhasil update data jamaah.",
		];
	}

	protected function filterFields($table, $data)
	{
		$fields = $this->db->list_fields($table);
		// var_dump($fields);
		// exit();
		$return = array();

		foreach ($fields as $field) {
			$return[$field] = isset($data[$field]) ? $data[$field] : '';
		}


		return $return;
	}

	protected function filterUpdateFields($table, $data)
	{
		$fields = $this->db->list_fields($table);
		$return = array();

		foreach ($fields as $field) {
			if (isset($data[$field])) {
				$return[$field] = $data[$field];
			}
		}

		return $return;
	}

	public function isNIKUsed($table, $nik, $id = null)
	{
		if ($id != null) {
			$this->db->where('id !=', $id);
		}
		$this->db->where('nik', $nik);
		$q = $this->db->get($table);

		if ($q->num_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function isNIMUsed($table, $nim, $id = null)
	{
		if ($id != null) {
			$this->db->where('id !=', $id);
		}
		$this->db->where('nim', $nim);
		$q = $this->db->get($table);

		// var_dump($table);
		// die;

		if ($q->num_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function isUsernameUsed($username, $id = null)
	{
		if ($id != null) {
			$this->db->where('id_user !=', $id);
		}
		$this->db->where('username', $username);
		$q = $this->db->get('user');
		$this->db->reset_query();

		if ($q->num_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function isEmailUsed($email, $id = null)
	{
		if ($id != null) {
			$this->db->where('id_user !=', $id);
		}
		$this->db->where("email", $email);
		$q = $this->db->get("user");
		$this->db->reset_query();

		if ($q->num_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	private function getGroupName($group_id)
	{

		if ($group_id == 81) {
			return 'cpm';
		} else if ($group_id == 4) {
			return 'mobilizer';
		} else if ($group_id == 5) {
			return 'fasilitator';
		} else if ($group_id == 12) {
			return 'mentor';
		} else if ($group_id == 82) {
			return 'verifikator_ppiu';
		} else if ($group_id == 83) {
			return 'verifikator_dit';
		} else if ($group_id == 82) {
			return 'verifikator_ppiu';
		} else if ($group_id == 83) {
			return 'verifikator_dit';
		} else if ($group_id == 80) {
			return 'hk_tim_seleksi';
		}
	}

	public function destroyUserGroup($group_id, $id)
	{
		$table = $this->getTableName($group_id);

		$this->db->where('id', $id);
		$user_group = $this->db->get($table)->row();
		$this->db->reset_query();

		$this->db->trans_start();
		$this->db->where('id', $id);
		$this->db->delete($table);
		$this->db->reset_query();

		$this->db->where('id_user', $user_group->id_user);
		$this->db->where('id_group', $group_id);
		$this->db->delete('user');
		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			return [
				"status"    => "error",
				"message"   => "Terjadi kesalahan server. Silahkan ulangi sesaat lagi."
			];
		}

		return [
			"status"    => "success",
			"message"   => "Berhasil hapus data user."
		];
	}

	public function destroyUserJamaah($group_id, $id)
	{
		$table = $this->getTableName($group_id);

		$this->db->where('id', $id);
		$user_group = $this->db->get('jamaah')->row();
		$this->db->reset_query();

		$this->db->trans_start();
		if(!empty($user_group->url_foto)){
			$file = './public/uploads/jamaah/' . $user_group->url_foto;
			unlink($file); // delete file
		}
		$this->db->where('id', $id);
		$this->db->delete('jamaah');
		$this->db->reset_query();

		$this->db->where('id_user', $user_group->id_user);
		$this->db->where('id_group', $group_id);
		$this->db->delete('user');
		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			return [
				"status"    => "error",
				"message"   => "Terjadi kesalahan server. Silahkan ulangi sesaat lagi."
			];
		}

		return [
			"status"    => "success",
			"message"   => "Berhasil hapus data user."
		];
	}

	public function destroyUserPengurus($group_id, $id)
	{
		$table = $this->getTableName($group_id);

		$this->db->where('id', $id);
		$user_group = $this->db->get('masjid_pengurus')->row();
		$this->db->reset_query();

		$this->db->trans_start();
		if(!empty($user_group->url_foto)){
			$file = './public/uploads/pengurus/' . $user_group->url_foto;
			unlink($file); // delete file
		}
		$this->db->where('id', $id);
		$this->db->delete('masjid_pengurus');
		$this->db->reset_query();

		$this->db->where('id_user', $user_group->id_user);
		$this->db->where('id_group', $group_id);
		$this->db->delete('user');
		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			return [
				"status"    => "error",
				"message"   => "Terjadi kesalahan server. Silahkan ulangi sesaat lagi."
			];
		}

		return [
			"status"    => "success",
			"message"   => "Berhasil hapus data user."
		];
	}

	public function getTableName($group_id)
	{
		$this->db->select('table_name');
		$this->db->where('id_group', $group_id);
		$result = $this->db->get('user_group')->row();
		if (isset($result)) {
			return $result->table_name;
		}

		return null;
	}

	public function getGroceryAdmins($group_id)
	{
		$this->db->where('id_group', $group_id);
		$this->db->order_by('real_name', 'ASC');

		$results =  $this->db->get('user')->result_array();
		$return = array();

		foreach ($results as $key => $value) {
			$return[$value['id_user']] = $value["real_name"];
		}

		return $return;
	}
}
