<?php

class Rumpun_jabatan_model extends CI_Model {
	public function __construct() {
		parent::__construct();

		//$this->load->model('log_model');
	}

	function showData($where = null,$like = null,$order_by = null,$limit = null,$fromLimit = null,$or_where = null,$or_like = null){

		$this->db->select("*");
		if($where){
			$this->db->where($where);
		}
		if($or_where){
			$this->db->or_where($or_where);
		}
		if($like){
			$this->db->like($like);
		}
		if($or_like){
			$this->db->or_like($or_like);
		}
		if($order_by){
			$this->db->order_by($order_by);
		}
		return $this->db->get("m_rumpun_jabatan",$limit,$fromLimit)->result();
	}

	function getCount($where = null,$like = null,$order_by = null,$limit = null,$fromLimit = null,$or_where = null,$or_like = null){
		$this->db->select("*");
		if($where){
			$this->db->where($where);
		}
		if($or_where){
			$this->db->or_where($or_where);
		}
		if($like){
			$this->db->like($like);
		}
		if($or_like){
			$this->db->or_like($or_like);
		}
		return $this->db->get("m_rumpun_jabatan",$limit,$fromLimit)->num_rows();
	}

	function getData($where){
		$this->db->select("*");
		$this->db->where($where);
		return $this->db->get("m_rumpun_jabatan")->row();
	}


	function getPrimaryKeyMax(){
		$query = $this->db->query('select max(id) as MAX from m_rumpun_jabatan') ;
		return $query->row();
	}

	function insert($data){
		$this->db->set('id', 'uuid_generate_v1()', FALSE);
		$this->db->insert('m_rumpun_jabatan', $data);
	}
	function update($where,$data){
		$this->db->where($where);
		$this->db->update('m_rumpun_jabatan', $data);
	}
	function delete($where){
		$this->db->where($where);
		$this->db->delete('m_rumpun_jabatan');
	}
}

?>
