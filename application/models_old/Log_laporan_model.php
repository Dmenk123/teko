<?php

class Log_laporan_model extends CI_Model {
	public function __construct() {
		parent::__construct();

	}

	function showData($where = null,$like = null,$order_by = null,$limit = null, $fromLimit=null){

		$this->db->select("log_laporan.*");
		$this->db->select("m_instansi.nama");
		$this->db->select("extract(month from log_laporan.tgl_log) as bulan");
		$this->db->select("to_char(log_laporan.tgl_log, 'yyyy') as tahun");
		$this->db->select("to_char(log_laporan.time_stamp,'dd-mm-yyyy') as time_stamp_indo");
		if($where){
			$this->db->where($where);
		}
		if($like){
			$this->db->like($like);
		}
		if($order_by){
			$this->db->order_by($order_by);
		}
		$this->db->join('m_instansi','m_instansi.kode = log_laporan.kd_instansi');
		return $this->db->get("log_laporan",$limit,$fromLimit)->result();
	}

	function getCount($where = null,$like = null,$order_by = null,$limit = null, $fromLimit=null){
		$this->db->select("*");
		if($where){
			$this->db->where($where);
		}
		if($like){
			$this->db->like($like);
		}
		return $this->db->get("log_laporan",$limit,$fromLimit)->num_rows();
	}

	function getData($where){
		$this->db->select("*");
		$this->db->where($where);
		return $this->db->get("log_laporan")->row();
	}


	function getPrimaryKeyMax(){
		$query = $this->db->query('select max(id_log_laporan) as MAX from log_laporan') ;
		return $query->row();
	}
	function insert($data){
		$this->db->insert('log_laporan', $data);
	}
	function update($where,$data){
		$this->db->where($where);
		$this->db->update('log_laporan', $data);
	}
	function delete($where){
		$this->db->where($where);
		$this->db->delete('log_laporan');
	}
}

?>
