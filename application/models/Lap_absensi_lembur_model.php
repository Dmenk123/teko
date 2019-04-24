<?php

class Lap_absensi_lembur_model extends CI_Model {
	public function __construct() {
		parent::__construct();

		//$this->load->model('log_model');
	}

	function insert($data){
		$this->db->insert('lap_absensi_lembur', $data);
	}
	function update($where,$data){
		$this->db->where($where);
		$this->db->update('lap_absensi_lembur', $data);
	}
	function delete($where){
		$this->db->where($where);
		$this->db->delete('lap_absensi_lembur');
	}
}

?>
