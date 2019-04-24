<?php

class Kategori_user_model extends CI_Model {
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
		return $this->db->get("m_kategori_user",$limit,$fromLimit)->result();
	}

	function getCount($where = null,$like = null,$order_by = null,$limit = null, $fromLimit=null){
		$this->db->select("*");
		if($where){
			$this->db->where($where);
		}
		if($like){
			$this->db->like($like);
		}
		return $this->db->get("m_kategori_user",$limit,$fromLimit)->num_rows();
	}

	function getData($where){
		$this->db->select("*");
		//$this->db->where($where);
		$this->db->where($where, NULL, FALSE);
		return $this->db->get("m_kategori_user")->row();
	}


	function getPrimaryKeyMax(){
		$query = $this->db->query('select max(id_kategori_user) as MAX from m_kategori_user') ;
		return $query->row();
	}

	function insert($data){
		$this->db->insert('m_kategori_user', $data);
	}
	function update($where,$data){
		$this->db->where($where);
		$this->db->update('m_kategori_user', $data);
	}
	function delete($where){
		$this->db->where($where);
		$this->db->delete('m_kategori_user');
	}

	function select_data($tabel){
		$this->db->select('*');
		$this->db->from($tabel);
		$this->db->order_by('nama','  asc');
		$query = $this->db->get();
		if($query){
			return $query->result();
		}else{
			return false;
		}
	}
}

?>
