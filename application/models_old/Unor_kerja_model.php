<?php

class Unor_kerja_model extends CI_Model {
	public function __construct() {
		parent::__construct();

		//$this->load->model('log_model');
	}

	function showData($where = null,$like = null,$order_by = null,$limit = null, $fromLimit=null,$or_where = null,$or_like = null,$select = null,$join = array()){
		if($select){
			$this->db->select($select);
		}
		else {
			$this->db->select("*");
		}
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
		foreach($join as $j) :
			$this->db->join($j["table"], $j["on"],'left');
		endforeach;
		if($order_by){
			$this->db->order_by($order_by);
		}
		return $this->db->get("m_unit_organisasi_kerja",$limit,$fromLimit)->result();
	}

	function showDataArray($where = null,$like = null,$order_by = null,$limit = null, $fromLimit=null,$or_where = null,$or_like = null,$select = null,$join = array()){
		if($select){
			$this->db->select($select);
		}
		else {
			$this->db->select("*");
		}
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
		foreach($join as $j) :
			$this->db->join($j["table"], $j["on"],'left');
		endforeach;
		if($order_by){
			$this->db->order_by($order_by);
		}
		return $this->db->get("m_unit_organisasi_kerja",$limit,$fromLimit)->result_array();
	}

	function getCount($where = null,$like = null,$order_by = null,$limit = null, $fromLimit=null,$or_where = null,$or_like = null,$select = null,$join = array()){
		if($select){
			$this->db->select($select);
		}
		else {
			$this->db->select("*");
		}
		if($where){
			$this->db->where($where);
		}
		if($or_where){
			$this->db->or_where($or_where);
		}
		if($or_like){
			$this->db->or_like($or_like);
		}
		if($like){
			$this->db->like($like);
		}
		foreach($join as $j) :
			$this->db->join($j["table"], $j["on"],'left');
		endforeach;
		return $this->db->get("m_unit_organisasi_kerja",$limit,$fromLimit)->num_rows();
	}

	function getData($where){
		$this->db->select("*");
		$this->db->where($where);
		return $this->db->get("m_unit_organisasi_kerja")->row();
	}


	function getPrimaryKeyMax(){
		$query = $this->db->query('select max(kode) as MAX from m_unit_organisasi_kerja') ;
		return $query->row();
	}

	function insert($data){
		$this->db->insert('m_unit_organisasi_kerja', $data);
	}
	function update($where,$data){
		$this->db->where($where);
		$this->db->update('m_unit_organisasi_kerja', $data);
	}
	function delete($where){
		$this->db->where($where);
		$this->db->delete('m_unit_organisasi_kerja');
	}
}

?>
