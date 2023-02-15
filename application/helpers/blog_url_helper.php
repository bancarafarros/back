<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

function uploadImage($nama_file, $path_folder, $prefix)
{
	$ci = &get_instance();

	$response['success'] = false;
	$response['file_name'] = '';
	$response['file'] = '';
	$nama_foto = "";
	if (!empty($_FILES[$nama_file]['name'])) {
		list($width, $height) = getimagesize($_FILES[$nama_file]['tmp_name']);
		$config['upload_path'] = 'public/uploads/' . $path_folder; //path folder file upload
		$config['allowed_types'] = 'gif|jpg|jpeg|png|jpeg|bmp'; //type file yang boleh di upload
		$config['max_size'] = '3000';
		$config['file_name'] = $prefix . '_' . date('ymdhis'); //enkripsi file name upload
		$ci->load->library('upload');
		$ci->upload->initialize($config);
		if ($ci->upload->do_upload($nama_file)) {
			$file_foto = $ci->upload->data();
			// $con['image_library']='gd2';
			// $con['source_image']= './public/uploads/'.$path_folder.'/'.$file_foto['file_name'];
			// $con['create_thumb']= FALSE;
			// $con['maintain_ratio']= TRUE;
			// $con['quality']= '50%';
			// $con['width']= round($width/5);
			// $con['height']= round($height/5);
			// $con['new_image']= './public/uploads/'.$path_folder.'/'.$file_foto['file_name'];
			// $ci->load->library('image_lib');
			// $ci->image_lib->initialize($con);
			$nama_foto = '/public/uploads/' . $path_folder . '/' . $file_foto['file_name'];
			$response['success'] = true;
			$response['file_name'] = $nama_foto;
			$response['file'] = $file_foto['file_name'];
		}
	}
	return $response;
}

function uploadBerkas($nama_file, $path_folder, $prefix)
{
	$ci = &get_instance();

	$root_folder = 'public/uploads/' . $path_folder;
	// if(!file_exists($root_folder)){
	//   mkdir($root_folder,755);
	// }
	$response['success'] = false;
	$response['file_name'] = '';
	$nama_berkas = "";
	if (!empty($_FILES[$nama_file]['name'])) {
		list($width, $height) = getimagesize($_FILES[$nama_file]['tmp_name']);
		$file_type = explode("/", $_FILES[$nama_file]['type'])[0];
		$config['upload_path'] = $root_folder; //path folder file upload
		$config['allowed_types'] = 'pdf|gif|jpg|jpeg|png|jpeg|bmp'; //type file yang boleh di upload
		$config['max_size'] = '3000';
		$config['file_name'] = $prefix . '_' . date('ymdhis'); //enkripsi file name upload
		$ci->load->library('upload');
		$ci->upload->initialize($config);
		if ($ci->upload->do_upload($nama_file)) {
			$file_foto = $ci->upload->data();
			// if($file_type == 'image'){
			//   $config['image_library']='gd2';
			//   $config['source_image']=$root_folder.'/'.$file_foto['file_name'];
			//   $config['create_thumb']= FALSE;
			//   $config['maintain_ratio']= TRUE;
			//   $config['quality']= '50%';
			//   $config['width']= round($width/5);
			//   $config['height']= round($height/5);
			//   $config['new_image']= $root_folder.'/'.$file_foto['file_name'];
			//   $ci->load->library('image_lib');
			//   $ci->image_lib->initialize($config);
			//   $ci->image_lib->resize();
			//   $response['file_type'] = 'image';
			// }
			$nama_berkas = '/' . $root_folder . '/' . $file_foto['file_name'];
			$response['success'] = true;
			$response['file_name'] = $nama_berkas;
		}
	}
	return $response;
}

function getProvinsi($kode_provinsi = null)
{
	$ci  = &get_instance();
	if ($kode_provinsi != null) {
		$prov = $ci->db
			->where(['kode_provinsi' => $kode_provinsi, 'status' => '1'])
			->order_by("nama_provinsi", 'asc')
			->get('ref_provinsi');
		if ($prov->num_rows() > 0) {
			return $prov->row_array();
		} else {
			return null;
		}
	} else {
		$prov = $ci->db
			->where(['status' => '1'])
			->order_by("nama_provinsi", 'asc')
			->get('ref_provinsi');
		if ($prov->num_rows() > 0) {
			return $prov->result_array();
		} else {
			return null;
		}
	}
}

function getSelectPropinsi($kode_provinsi = null)
{
	$return = '<option value="">Pilih Provinsi</option>';
	$result = getProvinsi($kode_provinsi);
	if (!empty($result)) {
		foreach ($result as $rows) {
			$return .= '<option value="' . $rows['kode_provinsi'] . '" ' . ($rows['kode_provinsi'] == $kode_provinsi ? 'selected' : '') . '>' . ucwords(strtolower($rows['nama_provinsi'])) . '</option>';
		}
	}
	return $return;
}

function getKotaByProv($id)
{
	$ci = &get_instance();

	$get = $ci->db->query("SELECT * FROM ref_kabupaten where kode_provinsi=? and status='1' order by nama_kabupaten ", array($id));

	if ($get->num_rows() != 0) {
		return $get->result_array();
	} else {
		return null;
	}
}

function getSelectKabupaten($kode_provinsi = null)
{
	$data = getKotaByProv($kode_provinsi);
	$html = '<option value="">Pilih Kabupaten</option>';
	if ($data != null) {
		foreach ($data as $rows) {
			$html .= '<option value="' . $rows['kode_kabupaten'] . '">' . ucwords(strtolower($rows['nama_kabupaten'])) . '</option>';
		}
	}
	$return = $html;
	return $return;
}

function getKecamatanByKota($id)
{
	$ci = &get_instance();

	$get = $ci->db->query("SELECT * FROM ref_kecamatan where kode_kabupaten=? order by nama_kecamatan", array($id));

	if ($get->num_rows() != 0) {
		return $get->result_array();
	} else {
		return null;
	}
}

function getSelectKecamatan($kode_kabupaten = null)
{
	$data = getKecamatanByKota($kode_kabupaten);
	$html = '<option value="">Pilih Kecamatan</option>';
	if ($data != null) {
		foreach ($data as $rows) {
			$html .= '<option value="' . $rows['kode_kecamatan'] . '">' . ucwords(strtolower($rows['nama_kecamatan'])) . '</option>';
		}
	}
	$return = $html;
	return $return;
}

function setFolder()
{
	$ci = &get_instance();
	$get = $ci->db->query("SELECT id from peserta where id_user=?", array($ci->session->userdata('intern_userId')));
	$row = ($get->num_rows() != 0 ? $get->row_array() : null);
	$return = 'pendaftar/' . $row['id'];
	return $return;
}

function listJenisInstansi()
{
	// data hard code
	$data = [
		'AKADEMI'           => 'AKADEMI',
		'SEKOLAH_TINGGI'    => 'SEKOLAH TINGGI',
		'UNIVERSITAS'       => 'UNIVERSITAS',
		'POLITEKNIK'        => 'POLITEKNIK',
		'INSTITUT'          => 'INSTITUT',
		'AKADEMI_KOMUNITAS' => 'AKADEMI KOMUNITAS'
	];
	return $data;

	// from db
	// $ci =&get_instance();
	// $get = $ci->db->query("SELECT distinct jenis FROM ref_instansi");
	// if ($get->num_rows() > 0) {
	//   return $get->result_array();
	// }else {
	//   return null;
	// }
}

function ceknik($nik)
{
	$return['pesan'] = '';
	$return['status'] = false;
	$ci = &get_instance();
	if (strlen($nik) != 16) {
		$return['pesan'] = 'NIK Harus 16 Digit';
		$return['status'] = false;
	} else {
		$return['pesan'] = '';
		$return['status'] = true;
	}
	return $return;
}

function setPronvisi()
{
	$return = [];
	$ci = &get_instance();
	$ci->db->order_by('kode_prop', 'ASC');
	$get = $ci->db->get('ref_propinsi');
	if ($get->num_rows()) {
		foreach ($get->result_array() as $key => $rows) {
			$return[$rows['kode_prop']] = $rows['nama_prop'];
		}
	}
	return $return;
}
function setKabupaten()
{
	$return = [];
	$ci = &get_instance();
	$get = $ci->db->query("SELECT * FROM ref_kabupaten order by nama_kab");
	if ($get->num_rows()) {
		foreach ($get->result_array() as $key => $rows) {
			$return[$rows['kode_kab']] = $rows['nama_kab'];
		}
	}
	return $return;
}
function setKecamatan()
{
	$return = [];
	$ci = &get_instance();
	$get = $ci->db->query("SELECT * FROM ref_kecamatan order by nama_kec");
	if ($get->num_rows()) {
		foreach ($get->result_array() as $key => $rows) {
			$return[$rows['kode_kec']] = $rows['nama_kec'];
		}
	}
	return $return;
}

function randomPassword()
{
	$alphabet = 'abcdefghijklmnopqrstuvwxyz1234567890';
	$pass = array();
	$alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
	for ($i = 0; $i < 8; $i++) {
		$n = rand(0, $alphaLength);
		$pass[] = $alphabet[$n];
	}
	return implode($pass); //turn the array into a string
}

function randomPassword_number()
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
