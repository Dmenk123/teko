<?php

class Data_mentah_model extends CI_Model {
	public function __construct() {
		parent::__construct();

		//$this->load->model('log_model');
	}

	function showData($where = null,$like = null,$order_by = null,$limit = null, $fromLimit=null){

		$this->db->select("data_mentah.*");
		$this->db->select("to_char(data_mentah.tanggal,'dd-mm-yyyy') as tanggal_indo ");
		$this->db->select("to_char(data_mentah.jadwal_masuk,'HH24:MI') as jadwal_masuk_jam ");
		$this->db->select("to_char(data_mentah.jadwal_pulang,'HH24:MI') as jadwal_pulang_jam ");
		$this->db->select("to_char(data_mentah.finger_masuk,'HH24:MI') as finger_masuk_jam ");
		$this->db->select("to_char(data_mentah.finger_pulang,'HH24:MI') as finger_pulang_jam ");
		if($where){
			$this->db->where($where);
		}
		if($like){
			$this->db->like($like);
		}
		if($order_by){
			$this->db->order_by($order_by);
		}
		return $this->db->get("data_mentah",$limit,$fromLimit)->result();
	}

	function getCount($where = null,$like = null,$order_by = null,$limit = null, $fromLimit=null){
		$this->db->select("*");
		if($where){
			$this->db->where($where);
		}
		if($like){
			$this->db->like($like);
		}
		return $this->db->get("data_mentah",$limit,$fromLimit)->num_rows();
	}

	function getData($where){
		$this->db->select("data_mentah.*");
		$this->db->select("to_char(data_mentah.tanggal,'dd-mm-yyyy') as tanggal_indo ");
		$this->db->select("to_char(data_mentah.jadwal_masuk,'HH24:MI') as jadwal_masuk_jam ");
		$this->db->select("to_char(data_mentah.jadwal_pulang,'HH24:MI') as jadwal_pulang_jam ");
		$this->db->select("to_char(data_mentah.finger_masuk,'HH24:MI') as finger_masuk_jam ");
		$this->db->select("to_char(data_mentah.finger_pulang,'HH24:MI') as finger_pulang_jam ");
		$this->db->where($where);
		return $this->db->get("data_mentah")->row();
	}

	function getDataAll($where){
		$this->db->select("data_mentah.*");
		$this->db->select("to_char(data_mentah.tanggal,'dd-mm-yyyy') as tanggal_indo ");
		$this->db->select("to_char(data_mentah.jadwal_masuk,'HH24:MI') as jadwal_masuk_jam ");
		$this->db->select("to_char(data_mentah.jadwal_pulang,'HH24:MI') as jadwal_pulang_jam ");
		$this->db->select("to_char(data_mentah.finger_masuk,'HH24:MI') as finger_masuk_jam ");
		$this->db->select("to_char(data_mentah.finger_pulang,'HH24:MI') as finger_pulang_jam ");
		$this->db->where($where);
		return $this->db->get("data_mentah")->result_array();
	}


	function insert($data){
		$this->db->insert('data_mentah', $data);
	}
	function delete($where){
		$this->db->where($where);
		$this->db->delete('data_mentah');
	}
}

?>
