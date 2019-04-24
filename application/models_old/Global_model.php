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
}
?>
