<?php

class Antrian_generate_model extends CI_Model {
	public function __construct() {
		parent::__construct();
	}

	function getCount($where = null,$like = null,$order_by = null,$limit = null, $fromLimit=null){
		$this->db->select("*");
		if($where){
			$this->db->where($where);
		}
		if($like){
			$this->db->like($like);
		}
		return $this->db->get("antrian_generate",$limit,$fromLimit)->num_rows();
	}
	
	function getDataWhere($where){
		$this->db->select("*");
		$this->db->where($where);
		$this->db->order_by('start_at', 'asc');
		return $this->db->get("antrian_generate")->result_array();
	}

	function getData($where){
		$this->db->select("*");
		$this->db->where($where);
		return $this->db->get("antrian_generate")->row();
	}

	function insert($data){
		$this->db->insert('antrian_generate', $data);
	}

	function update($where,$data){
		$this->db->where($where);
		$this->db->update('antrian_generate', $data);
	}

	function delete($where){
		$this->db->where($where);
		$this->db->delete('antrian_generate');
	}
}

?>
