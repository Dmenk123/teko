<?php

class Global_model extends CI_Model {
	public function __construct() {
		parent::__construct();

		//$this->load->model('log_model');
	}

  function getData($query) {
    $query = $this->db->query($query) ;
		return $query->result_array();
  }

	function getDataOne($query) {
    $query = $this->db->query($query) ;
		return $query->row_array();
	}
	
	public function save($data, $tabel){
		$this->db->insert($tabel, $data);
		return true;
	}

	public function updatedata($where,$data,$table){
		$this->db->where($where);
		$this->db->update($table,$data);
	}

	public function select_data($tabel,$where){
		$this->db->select('*');
	  $this->db->from($tabel);
	  $this->db->where($where);
	  $this->db->limit(1);
	 
	  $query = $this->db->get();
	  if($query -> num_rows() == 1){
			return $query->result();
	  }
	}

	public function get_by_id($tabel,$where){
		$this->db->from($tabel);
		$this->db->where($where);
		$query = $this->db->get();
		return $query->row();
	}
}
?>
