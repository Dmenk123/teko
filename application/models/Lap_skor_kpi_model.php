<?php

class Lap_skor_kpi_model extends CI_Model {
	public function __construct() {
		parent::__construct();

		//$this->load->model('log_model');
	}

	function insert($data){
		$this->db->insert('lap_skor_kpi', $data);
	}
	function update($where,$data){
		$this->db->where($where);
		$this->db->update('lap_skor_kpi', $data);
	}
	function delete($where){
		$this->db->where($where);
		$this->db->delete('lap_skor_kpi');
	}
}

?>
