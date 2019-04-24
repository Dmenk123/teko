<?php

class Role_jam_kerja_model extends CI_Model {
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
		return $this->db->get("m_role_jam_kerja",$limit,$fromLimit)->result();
	}

	function showDataDetail($where = null,$like = null,$order_by = null,$limit = null, $fromLimit=null){

		$this->db->select("m_role_jam_kerja_detail.*");
		$this->db->select("m_jam_kerja.nama as nama_jam_kerja");
		$this->db->select("m_jam_kerja.jam_masuk");
		$this->db->select("m_jam_kerja.jam_pulang");
		$this->db->select("m_hari.nama as nama_hari");

		if($where){
			$this->db->where($where);
		}
		if($like){
			$this->db->like($like);
		}
		if($order_by){
			$this->db->order_by($order_by);
		}

		$this->db->join('m_jam_kerja', 'm_jam_kerja.id = m_role_jam_kerja_detail.id_jam_kerja', 'left');
		$this->db->join('m_hari', 'm_hari.id = m_role_jam_kerja_detail.id_hari', 'left');
		return $this->db->get("m_role_jam_kerja_detail",$limit,$fromLimit)->result();
	}

	function getCount($where = null,$like = null,$order_by = null,$limit = null, $fromLimit=null){
		$this->db->select("*");
		if($where){
			$this->db->where($where);
		}
		if($like){
			$this->db->like($like);
		}
		return $this->db->get("m_role_jam_kerja",$limit,$fromLimit)->num_rows();
	}

	function getData($where){
		$this->db->select("*");
		$this->db->where($where);
		return $this->db->get("m_role_jam_kerja")->row();
	}
	function getDataDetail($where){
		$this->db->select("*");
		$this->db->where($where);
		return $this->db->get("m_role_jam_kerja_detail")->row();
	}

	function insert($data){
		$this->db->insert('m_role_jam_kerja', $data);
	}
	function insertDetail($data){
		$this->db->insert('m_role_jam_kerja_detail', $data);
	}
	function update($where,$data){
		$this->db->where($where);
		$this->db->update('m_role_jam_kerja', $data);
	}
	function delete($where){
		$this->db->where($where);
		$this->db->delete('m_role_jam_kerja');
	}
	function deleteDetail($where){
		$this->db->where($where);
		$this->db->delete('m_role_jam_kerja_detail');
	}
}

?>
