<?php

class Roster_model extends CI_Model {
	public function __construct() {
		parent::__construct();

		//$this->load->model('log_model');
	}

	function getData($where){
		$this->db->select("t_roster.id");
		$this->db->select("t_roster.tanggal");
		$this->db->select("t_roster.id_jenis_roster");
		$this->db->select("t_roster.id_pegawai");
		$this->db->select("t_roster.time_upd");
		$this->db->select("t_roster.user_upd");
		$this->db->select("t_roster.timeupd");
		$this->db->select("t_roster.userupd");
		$this->db->select("t_roster.time_ins");
		$this->db->select("t_roster.user_ins");
		$this->db->select("m_jenis_roster.kode");

		$this->db->where($where);

		$this->db->join('m_jenis_roster', 't_roster.id_jenis_roster = m_jenis_roster.id', 'left');

		return $this->db->get("t_roster")->row();
	}
	function insert($data){
		$this->db->insert('t_roster', $data);
	}
	function update($where,$data){
		$this->db->where($where);
		$this->db->update('t_roster', $data);
	}
	function delete($where){
		$this->db->where($where);
		$this->db->delete('t_roster');
	}
}

?>
