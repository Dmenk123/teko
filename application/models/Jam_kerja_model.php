<?php

class Jam_kerja_model extends CI_Model {
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
		return $this->db->get("m_jam_kerja",$limit,$fromLimit)->result();
	}

	function getCount($where = null,$like = null,$order_by = null,$limit = null, $fromLimit=null){
		$this->db->select("*");
		if($where){
			$this->db->where($where);
		}
		if($like){
			$this->db->like($like);
		}
		return $this->db->get("m_jam_kerja",$limit,$fromLimit)->num_rows();
	}

	function getData($where){
		$this->db->select('id, nama, jml_hari_kerja, pulang_hari_berikutnya, masuk_hari_sebelumnya, toleransi_terlambat,  toleransi_pulang_cepat');
		$this->db->select("to_char(jam_masuk,'HH24:MI') as jam_masuk");
		$this->db->select("to_char(jam_pulang,'HH24:MI') as jam_pulang");
		$this->db->select("to_char(jam_akhir_scan_masuk,'HH24:MI') as jam_akhir_scan_masuk");
		$this->db->select("to_char(jam_akhir_scan_pulang,'HH24:MI') as jam_akhir_scan_pulang");
		$this->db->select("to_char(jam_mulai_scan_masuk,'HH24:MI') as jam_mulai_scan_masuk");
		$this->db->select("to_char(jam_mulai_scan_pulang,'HH24:MI') as jam_mulai_scan_pulang");
		$this->db->where($where);
		return $this->db->get("m_jam_kerja")->row();
	}

	function insert($data){
		$this->db->insert('m_jam_kerja', $data);
	}
	function update($where,$data){
		$this->db->where($where);
		$this->db->update('m_jam_kerja', $data);
	}
	function delete($where){
		$this->db->where($where);
		$this->db->delete('m_jam_kerja');
	}
}

?>
