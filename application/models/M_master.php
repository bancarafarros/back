<?php

class M_master extends CI_Model
{

	function __construct()
	{
		parent::__construct();
	}

	public function sum_masjid()
	{
		$get = $this->db
			->where('status', '1')
			->get('masjid');
		if ($get->num_rows() > 0) {
			return count($get->result_array());
		} else {
			return 0;
		}
	}
	public function sum_pengurus()
	{
		$get = $this->db->get('masjid_pengurus');
		if ($get->num_rows() > 0) {
			return count($get->result_array());
		} else {
			return 0;
		}
	}

	public function sum_jamaah()
	{
		$get = $this->db->get('jamaah');
		if ($get->num_rows() > 0) {
			return count($get->result_array());
		} else {
			return 0;
		}
	}

	public function masjid_jamaah()
	{
		$get = $this->db->get_where('masjid_pengurus', array('email' => getSessionEmail()))->row_array();
		
		$this->db->where('id_masjid', $get['id_masjid']);
		$data = $this->db->get('jamaah');
		if($data->num_rows() > 0 ){
			return count($data->result_array());
		}else{
			return 0;
		}
	}

	public function masjid_pengurus()
	{
		$get = $this->db->get_where('masjid_pengurus', array('email' => getSessionEmail()))->row_array();

		$this->db->where('id_masjid', $get['id_masjid']);
		$data = $this->db->get('masjid_pengurus');

		if($data->num_rows() > 0){
			return count($data->result_array());
		}else{
			return 0;
		}
	}

	public function listFitur()
	{
		$data = $this->db->get('ref_fitur')->result_array();
		return $data;
	}

	public function listFaq()
	{
		$this->db->order_by('order', 'ASC');
		$this->db->where('is_active', '1');
		$data = $this->db->get('faq')->result_array();
		return $data;
	}

	public function detailFaq($id)
	{
		$this->db->select('*');
		$this->db->from('faq');
		$this->db->where('id', $id);
		$get = $this->db->get();
		if ($get->num_rows() != 0) {
			return $get->row_array();
		} else {
			return null;
		}
	}
}
