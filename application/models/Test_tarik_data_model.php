<?php

class Test_tarik_data_model extends CI_Model {
	public function __construct() {
		parent::__construct();

		//$this->load->model('log_model');
	}

	
	function insert($data){
		$this->db->insert('test_tarik_data', $data);
	}
	
}

?>
