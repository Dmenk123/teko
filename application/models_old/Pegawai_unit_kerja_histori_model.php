<?php

class Pegawai_unit_kerja_histori_model extends CI_Model {
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
		return $this->db->get("m_pegawai_unit_kerja_histori",$limit,$fromLimit)->result();
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
		if($like){
			$this->db->like($like);
		}
		if($or_like){
			$this->db->or_like($or_like);
		}
		foreach($join as $j) :
			$this->db->join($j["table"], $j["on"],'left');
		endforeach;
		return $this->db->get("m_pegawai_unit_kerja_histori",$limit,$fromLimit)->num_rows();
	}

	function getPrimaryKeyMax(){
		$query = $this->db->query('select max(id) as MAX from m_pegawai_unit_kerja_histori') ;
		return $query->row();
	}

	function insert($data){
		$this->db->set('id', 'uuid_generate_v1()', FALSE);
		$this->db->insert('m_pegawai_unit_kerja_histori', $data);
		//return $this->db->insert_id();
	}
	function update($where,$data){
		$this->db->where($where);
		$this->db->update('m_pegawai_unit_kerja_histori', $data);
	}
	function delete($where){
		$this->db->where($where);
		return $this->db->delete('m_pegawai_unit_kerja_histori');
	}
}

?>
