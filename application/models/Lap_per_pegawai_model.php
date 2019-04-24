<?php

class Lap_per_pegawai_model extends CI_Model {
	public function __construct() {
		parent::__construct();

		//$this->load->model('log_model');
	}

	function insert($data){
		$this->db->insert('lap_per_pegawai', $data);
	}
	function update($where,$data){
		$this->db->where($where);
		$this->db->update('lap_per_pegawai', $data);
	}
	function delete($where){
		$this->db->where($where);
		$this->db->delete('lap_per_pegawai');
	}
}

?>
