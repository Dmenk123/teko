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

	/*function getPrimaryKeyMax(){
		$query = $this->db->query('select max(id_log_laporan) as MAX from log_laporan') ;
		return $query->row();
	}*/

	function insert($data){
		$this->db->insert('log_laporan', $data);
	}

	function update($where,$data){
		$this->db->where($where);
		$this->db->update('log_laporan', $data);
		return $this->db->affected_rows();
	}
	
	function delete($where){
		$this->db->where($where);
		$this->db->delete('log_laporan');
	}

	public function cek_list_kecamatan($where)
	{
		$this->db->select('kode, nama');
		$this->db->from('m_instansi');
		$this->db->where($where);
		$this->db->order_by('nama', 'asc');
		$query = $this->db->get();
		return $query->result_array();
	}

	public function cek_kunci_kecamatan($where)
	{
		$this->db->select("ll.id_log_laporan,
			ll.kd_instansi,
			mi.nama,
			to_char(ll.tgl_log, 'YYYY') as tahun_kunci,
			to_char(ll.tgl_log, 'MM') as bulan_kunci,
			ll.time_stamp as timestamp,
			ll.time_stamp_buka as timestamp_buka
		");
		$this->db->from('log_laporan ll');
		$this->db->join('m_instansi mi', 'll.kd_instansi = mi.kode', 'left');
		$this->db->where($where);
		$this->db->order_by('mi.nama', 'asc');
		$query = $this->db->get();
		return $query->result_array();
	}

	public function get_tgl_akhir_skor($instansi, $bln, $thn)
	{
		$this->db->select('finished_at');
		$this->db->from('lap_skor_kehadiran');
		$this->db->where('bulan', $bln);
		$this->db->where('tahun', $thn);
		$this->db->where('id_instansi', $instansi);
		$this->db->where('deleted_at is null');
		$this->db->order_by('finished_at', 'desc');
		$this->db->limit(1);
		$query = $this->db->get();
		return $query->result_array();
	} 

	public function get_tgl_akhir_makan($instansi, $bln, $thn)
	{
		$this->db->select('created_at');
		$this->db->from('lap_uang_makan');
		$this->db->where('bulan', $bln);
		$this->db->where('tahun', $thn);
		$this->db->where('id_instansi', $instansi);
		$this->db->where('deleted_at is null');
		$this->db->order_by('finished_at', 'desc');
		$this->db->limit(1);
		$query = $this->db->get();
		return $query->result_array();
	}

	public function get_tgl_akhir_lembur($instansi, $bln, $thn)
	{
		$this->db->select('finished_at');
		$this->db->from('lap_absensi_lembur');
		$this->db->where('bulan', $bln);
		$this->db->where('tahun', $thn);
		$this->db->where('id_instansi', $instansi);
		$this->db->where('deleted_at is null');
		$this->db->order_by('finished_at', 'desc');
		$this->db->limit(1);
		$query = $this->db->get();
		return $query->result_array();
	}

	public function get_tgl_akhir_skor2($instansi, $bln, $thn)
	{
		$this->db->select('sk.finished_at, sk.id_instansi, mi.nama');
		$this->db->from('lap_skor_kehadiran sk');
		$this->db->join('m_instansi mi', 'sk.id_instansi = mi.kode', 'left');
		$this->db->where('sk.bulan', $bln);
		$this->db->where('sk.tahun', $thn);
		$this->db->where('sk.id_instansi', $instansi);
		$this->db->where('sk.deleted_at is null');
		$this->db->order_by('sk.finished_at', 'desc');
		$this->db->limit(1);
		$query = $this->db->get();
		return $query->result_array();
	} 

	public function get_tgl_akhir_makan2($instansi, $bln, $thn)
	{
		$this->db->select('um.created_at, um.id_instansi, mi.nama');
		$this->db->from('lap_uang_makan um');
		$this->db->join('m_instansi mi', 'um.id_instansi = mi.kode', 'left');
		$this->db->where('um.bulan', $bln);
		$this->db->where('um.tahun', $thn);
		$this->db->where('um.id_instansi', $instansi);
		$this->db->where('um.deleted_at is null');
		$this->db->order_by('um.created_at', 'desc');
		$this->db->limit(1);
		$query = $this->db->get();
		return $query->result_array();
	}

	public function get_tgl_akhir_lembur2($instansi, $bln, $thn)
	{
		$this->db->select('al.finished_at, al.id_instansi, mi.nama');
		$this->db->from('lap_absensi_lembur al');
		$this->db->join('m_instansi mi', 'al.id_instansi = mi.kode', 'left');
		$this->db->where('al.bulan', $bln);
		$this->db->where('al.tahun', $thn);
		$this->db->where('al.id_instansi', $instansi);
		$this->db->where('al.deleted_at is null');
		$this->db->order_by('al.finished_at', 'desc');
		$this->db->limit(1);
		$query = $this->db->get();
		return $query->result_array();
	}

}

?>
