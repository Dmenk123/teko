<?php

class Lap_uang_makan_model extends CI_Model {
	public function __construct() {
		parent::__construct();

		//$this->load->model('log_model');
	}

	function insert($data){
		$this->db->insert('lap_uang_makan', $data);
	}
	function update($where,$data){
		$this->db->where($where);
		$this->db->update('lap_uang_makan', $data);
	}
	function delete($where){
		$this->db->where($where);
		$this->db->delete('lap_uang_makan');
	}
}

?>
