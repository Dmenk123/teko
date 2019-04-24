<?php

class Usernip_model extends CI_Model {
	public function __construct() {
		parent::__construct();
		
		//$this->load->model('log_model');
	}	
	
	function showData($where = null,$like = null,$order_by = null,$limit = null, $fromLimit=null){
		
		$this->db->select("m_pegawai.*");		
		// $this->db->join('m_kategori_user', 'm_kategori_user.id_kategori_user = c_security_user_new.id_kategori_user');	
		if($where){
			$this->db->where($where);
		}		
		if($like){
			$this->db->like($like);
		}		
		if($order_by){
			$this->db->order_by($order_by);
		}			
		return $this->db->get("m_pegawai",$limit,$fromLimit)->result();
	}
	
	function getCount($where = null,$like = null,$order_by = null,$limit = null, $fromLimit=null){
		$this->db->select("*");		
		if($where){
			$this->db->where($where);
		}		
		if($like){
			$this->db->like($like);
		}
		/*$this->db->join('m_kategori_user', 'm_kategori_user.id_kategori_user = c_security_user_new.id_kategori_user');*/
		return $this->db->get("m_pegawai",$limit,$fromLimit)->num_rows();
	}
	
	function getData($where){
		$this->db->select("*");		
		$this->db->where($where);		
		return $this->db->get("m_pegawai")->row();
	}

	public function updateData($data, $where, $table)
	{
		$this->db->where($where);
		$this->db->update($table, $data);
		//return $this->db->affected_rows();
	}
}

?>
