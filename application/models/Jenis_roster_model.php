<?php

class Jenis_roster_model extends CI_Model {
	public function __construct() {
		parent::__construct();

		//$this->load->model('log_model');
	}

	function showData($where = null,$like = null,$order_by = null,$limit = null, $fromLimit=null){


		$this->db->select("m_jenis_roster.id");
		$this->db->select("m_jenis_roster.nama as nama_jenis_roster");
		$this->db->select("m_jenis_roster.kode");
		$this->db->select("m_jenis_roster.keterangan");
		$this->db->select("m_jenis_roster.label");
		$this->db->select("m_jam_kerja.nama as nama_jam_kerja");
		$this->db->select("m_jam_kerja.jam_masuk");
		$this->db->select("m_jam_kerja.jam_pulang");
		$this->db->select("m_jam_kerja.pulang_hari_berikutnya");
		$this->db->select("m_jam_kerja.masuk_hari_sebelumnya");

		if($where){
			$this->db->where($where);
		}
		if($like){
			$this->db->like($like);
		}
		if($order_by){
			$this->db->order_by($order_by);
		}

		$this->db->join('m_jam_kerja', 'm_jenis_roster.id_jam_kerja = m_jam_kerja.id', 'left');
		return $this->db->get("m_jenis_roster",$limit,$fromLimit)->result();
	}

	function getCount($where = null,$like = null,$order_by = null,$limit = null, $fromLimit=null){
		$this->db->select("*");
		if($where){
			$this->db->where($where);
		}
		if($like){
			$this->db->like($like);
		}
		return $this->db->get("m_jenis_roster",$limit,$fromLimit)->num_rows();
	}

	function getData($where){
		$this->db->select("*");
		$this->db->where($where);
		return $this->db->get("m_jenis_roster")->row();
	}


	function insert($data){
		$this->db->insert('m_jenis_roster', $data);
	}
	function update($where,$data){
		$this->db->where($where);
		$this->db->update('m_jenis_roster', $data);
	}
	function delete($where){
		$this->db->where($where);
		$this->db->delete('m_jenis_roster');
	}
}

?>
