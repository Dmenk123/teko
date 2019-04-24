<?php

class c_security_user_model extends CI_Model {
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
		return $this->db->get("c_security_user_new",$limit,$fromLimit)->result();
	}
	
	function getCount($where = null,$like = null,$order_by = null,$limit = null, $fromLimit=null){
		$this->db->select("*");		
		if($where){
			$this->db->where($where);
		}		
		if($like){
			$this->db->like($like);
		}
		return $this->db->get("c_security_user_new",$limit,$fromLimit)->num_rows();
	}
	
	function getData($where){
		$this->db->select("*");		
		$this->db->where($where);		
		return $this->db->get("c_security_user_new")->row();
	}
	
	function insert($data){
		$this->db->insert('c_security_user_new', $data);	
	}
	function update($where,$data){		
		$this->db->where($where);		
		$this->db->update('c_security_user_new', $data);
	}
	function delete($where){
		$this->db->where($where);
		$this->db->delete('c_security_user_new');		
	}
}

?>
