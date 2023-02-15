<?php

defined('BASEPATH') or exit('No direct script access allowed');

class api_task extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    function getAll()
    {
        $this->db->select('project_tasks.id, project_tasks.project_task_owner, project_tasks.creator_type, project_tasks.task, project_tasks.start_date, project_tasks.due_date, project_tasks.desciption, project_tasks.url_bukti, project_tasks.level, project_tasks.status, project_tasks.percentage_taks');
        $this->db->from('project_tasks');
        $result = $this->db->get()->result();
        return $result;
    }

    function ambil_idpeserta($id)
    {
        $this->db->select('id');
        $this->db->from('peserta');
        $this->db->where('id_user', $id);
        $result = $this->db->get()->row_array();
        return $result;
    }

    function isi_task($data, $data2, $id_task)
    {
        $this->db->trans_start();
        $this->db->where('id', $id_task);
        $this->db->update('project_tasks', $data);
        $this->db->reset_query();
        $id = getUUID();
        $data2["id"] = $id;
        $this->db->insert('project_task_logs', $data2);
        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            $return['success'] = false;
            $return['message'] = 'Daftar gagal.';
        } else {
            $this->db->trans_commit();
            $return['success'] = true;
            $return['message'] = 'Daftar berhasil.';
        }
        return $return;
    }

    function getAll2($id)
    {
        $this->db->select('project_tasks.id, project_tasks.project_task_owner, project_tasks.creator_type, project_tasks.task, project_tasks.start_date, project_tasks.due_date, project_tasks.desciption, project_tasks.url_bukti, project_tasks.level, project_tasks.status, project_tasks.percentage_taks');
        $this->db->from('project_tasks');
        $this->db->join('projects', 'projects.id = project_tasks.project_id', 'left');
        $this->db->where('projects.id', $id);
        $result = $this->db->get()->result();
        return $result;
    }

    function tambah_task($data, $data3, $id_peserta)
    {
        $this->db->trans_start();
        $id = getUUID();
        $data["id"] = $id;
        $data3["task_id"] = $id;
        $this->db->insert('project_tasks', $data);
        $jumlah_member = count($id_peserta);
        for ($i = 0; $i < $jumlah_member; $i++) {
            $id3 = getUUID();
            $data3["id"] = $id3;
            $data3['peserta_id'] = $id_peserta[$i];
            $this->db->insert('project_task_members', $data3);
        }
        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            $return['success'] = false;
            $return['message'] = 'Daftar gagal.';
        } else {
            $this->db->trans_commit();
            $return['success'] = true;
            $return['message'] = 'Daftar berhasil.';
        }
        return $return;
    }

    function getIdPeserta($nama_peserta)
    {
        $this->db->select('id');
        $this->db->from('peserta');
        $this->db->where('nama_lengkap', $nama_peserta);
        $result = $this->db->get()->row_array();
        return $result;
    }

    function getIdGroup($id)
    {
        $this->db->select('id_group, real_name');
        $this->db->from('user');
        $this->db->where('id_user', $id);
        $result = $this->db->get()->row_array();
        return $result;
    }

    function getRevisi($user_id)
    {
        $this->db->select('project_task_revisions.id, project_task_revisions.task_id, project_task_revisions.note, project_task_revisions.url_file');
        $this->db->from('project_task_revisions');
        $this->db->join('project_task_members', 'project_task_members.task_id = project_task_revisions.task_id', 'left');
        $this->db->join('peserta', 'peserta.id = project_task_members.peserta_id', 'left');
        $this->db->where('peserta.id_user', $user_id);
        $result = $this->db->get()->result();
        return $result;
    }

    function getallnotes($id)
    {
        $this->db->select('project_member_notes.id, project_member_notes.member_id,  project_member_notes.title,  project_member_notes.date,  project_member_notes.description,  project_member_notes.support_image');
        $this->db->from('project_member_notes');
        $this->db->join('project_members', 'project_member_notes.member_id = project_members.id', 'left');
        $this->db->join('peserta', 'peserta.id = project_members.peserta_id', 'left');
        $this->db->where('peserta.id_user', $id);
        return $this->db->get()->result();
    }

    function getdetailnotes($id)
    {
        $this->db->select('id, member_id, title, date, description, support_image');
        $this->db->from('project_member_notes');
        $this->db->where('id', $id);
        return $this->db->get()->result();
    }

    public function tambahNotes($tabel, $data)
    {
        $id = getUUID();
        $data["id"] = $id;
        return $this->db->insert($tabel, $data);
    }

    public function hapusNotes($id)
    {
        $where = array('id' => $id);
        $this->db->where($where);
        return $this->db->delete('project_member_notes');
    }

    public function ubahNotes($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('project_member_notes', $data);
    }

    public function idProjek($id)
    {
        $this->db->select('project_members.id');
        $this->db->from('project_members');
        $this->db->join('peserta', 'peserta.id = project_members.peserta_id', 'left');
        $this->db->where('peserta.id_user', $id);
        $result = $this->db->get()->row_array();
        return $result;
    }

    function getPhoto($id)
    {
        $this->db->select('support_image');
        $this->db->from('project_member_notes');
        $this->db->where('id', $id);
        $result = $this->db->get();
        $data = $result->row_array();

        return $data;
    }
}
