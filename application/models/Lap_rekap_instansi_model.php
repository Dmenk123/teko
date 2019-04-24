<?php

class Lap_rekap_instansi_model extends CI_Model {
	public function __construct() {
		parent::__construct();

		//$this->load->model('log_model');
	}

	function insert($data){
		$this->db->insert('lap_rekap_instansi', $data);
	}
	function update($where,$data){
		$this->db->where($where);
		$this->db->update('lap_rekap_instansi', $data);
	}
	function delete($where){
		$this->db->where($where);
		$this->db->delete('lap_rekap_instansi');
	}
}

?>
