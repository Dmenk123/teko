<?php

class s_hari_libur_model extends CI_Model {
	public function __construct() {
		parent::__construct();

		//$this->load->model('log_model');
	}

	function showData($where = null,$like = null,$order_by = null,$limit = null, $fromLimit=null){

		$this->db->select("*");
		if($where){
			$this->db->where($where);
		}
		if($like){
			$this->db->like($like);
		}
		if($order_by){
			$this->db->order_by($order_by);
		}
		return $this->db->get("s_har_libur",$limit,$fromLimit)->result();
	}

	function getCount($where = null,$like = null,$order_by = null,$limit = null, $fromLimit=null){
		$this->db->select("*");
		if($where){
			$this->db->where($where);
		}
		if($like){
			$this->db->like($like);
		}
		return $this->db->get("s_har_libur",$limit,$fromLimit)->num_rows();
	}

	function getData($where){
		$this->db->select("*");
		$this->db->where($where);
		return $this->db->get("s_har_libur")->row();
	}


	function getPrimaryKeyMax(){
		$query = $this->db->query('select max(kode) as MAX from s_har_libur') ;
		return $query->row();
	}

	function insert($data){
		$this->db->insert('s_har_libur', $data);
	}
	function update($where,$data){
		$this->db->where($where);
		$this->db->update('s_har_libur', $data);
	}
	function delete($where){
		$this->db->where($where);
		$this->db->delete('s_har_libur');
	}
}

?>
