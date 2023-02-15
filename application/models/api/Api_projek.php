<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class api_projek extends CI_Model {

    function __construct() {
        parent::__construct();
    }
    
    function getAll() 
    {
        $this->db->select('*');
        $this->db->from('projects');
        return $this->db->get()->result();
    }

    function getProjek($id) 
    {
        $this->db->select('a.id, a.mentor_id1, a.mentor_id2, a.name, a.description, b.task, b.project_task_owner, b.start_date, b.due_date, b.desciption, d.nama_lengkap');
        $this->db->from('projects a');
        $this->db->join('project_tasks b', 'b.project_id = a.id', 'left');
        $this->db->join('project_task_members c', 'c.task_id = b.id', 'left');
        $this->db->join('peserta d', 'd.id = c.peserta_id', 'left');
        $this->db->where('a.id', $id);
        $result = $this->db->get()->result();
        return $result;
    }

    function ambilPeserta($id_user)
    {
        $id = $this->get_peserta($id_user);
        $this->db->select('MAX(p.name) AS nama_proyek, MAX(p.description) AS deskripsi,
        MAX(m1.nama_lengkap) AS mentor1, MAX(m2.nama_lengkap) AS mentor2');
        $this->db->from('project_members pm');
        $this->db->join('projects p', 'p.id=pm.project_id', 'left');
        $this->db->join('mentor m1', 'm1.id=p.mentor_id1', 'left');
        $this->db->join('mentor m2', 'm2.id=p.mentor_id2', 'left');
        $this->db->where('pm.peserta_id', $id);
        $result = $this->db->get()->result();
        return $result;
    }

    public function get_peserta($id_user)
    {
        $this->db->select('id');
        $this->db->from('peserta');
        $this->db->where('id_user', $id_user);
        $get = $this->db->get()->row_array();
        $id = $get['id'];

        return $id;
    }

    public function ambilMentor($id_user)
    {
        $id = $this->get_mentor($id_user);
        $this->db->select('MAX(p.name) AS nama_proyek, MAX(p.description) AS deskripsi,
        MAX(m1.nama_lengkap) AS mentor1, MAX(m2.nama_lengkap) AS mentor2');
        $this->db->from('projects p');
        $this->db->join('mentor m1', 'm1.id=p.mentor_id1', 'left');
        $this->db->join('mentor m2', 'm2.id=p.mentor_id2', 'left');
        $this->db->where("(p.mentor_id1 = '$id' OR p.mentor_id2 = '$id')");
        $result = $this->db->get()->result();
        return $result;
    }

    public function get_mentor($id_user)
    {
        $this->db->select('id');
        $this->db->from('mentor');
        $this->db->where('id_user', $id_user);
        $get = $this->db->get()->row_array();
        $id = $get['id'];

        return $id;
    }

}
