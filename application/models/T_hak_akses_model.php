<?php

class T_hak_akses_model extends CI_Model {
	public function __construct() {
		parent::__construct();
		
		//$this->load->model('log_model');
	}	
	
	
	function getData($where){
		$this->db->select("*");		
		$this->db->where($where);	
		return $this->db->get("t_hak_akses")->row();
	}
	
	function insert($data){
		$this->db->insert('t_hak_akses', $data);		
	}
	
	function delete($where){		
		$this->db->where($where, $value);					
		$this->db->delete('t_hak_akses');		
	}
}

?>
