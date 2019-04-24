<?php

class Absensi_log_model extends CI_Model {
	public function __construct() {
		parent::__construct();

		//$this->load->model('log_model');
	}

	function showData($where = null,$like = null,$order_by = null,$limit = null, $fromLimit=null){

		$this->db->select("m_pegawai.nip");
		$this->db->select("m_pegawai.nama as nama_pegawai");


		$this->db->select("absensi_log.file_lampiran");
		$this->db->select("absensi_log.id as id_log_absensi");
		$this->db->select("absensi_log.keterangan");
		$this->db->select("absensi_log.dispensasi");
		$this->db->select("to_char(absensi_log.tanggal, 'DD-MM-YYYY HH24:MI') as tanggal");
		$this->db->select("to_char(absensi_log.jam_download, 'DD-MM-YYYY HH24:MI') as tanggal_update");


		if($where){
			$this->db->where($where);
		}
		if($like){
			$this->db->like($like);
		}
		if($order_by){
			$this->db->order_by($order_by);
		}

		$this->db->join('mesin_user', 	'mesin_user.id_mesin 	= absensi_log.id_mesin and mesin_user.user_id=absensi_log.badgenumber', 'left');
		$this->db->join('m_pegawai', 	'm_pegawai.id 			= mesin_user.id_pegawai ', 'left');

		return $this->db->get("absensi_log",$limit,$fromLimit)->result();
	}

	function getData($where){
		$this->db->select("*");
		$this->db->select("mesin_user.id_pegawai");
		$this->db->select("absensi_log.id as id_log_absensi");
		$this->db->select("to_char(tanggal, 'MM/DD/YYYY') as tanggal_untuk_edit");
		$this->db->select("to_char(tanggal, 'yyyy-mm-dd') as tanggal_untuk_insert");
		$this->db->select("to_char(tanggal, 'HH24') as jam_untuk_edit");
		$this->db->select("to_char(tanggal, 'MI') as menit_untuk_edit");

		$this->db->join('mesin_user', 	'mesin_user.id_mesin 	= absensi_log.id_mesin and mesin_user.user_id=absensi_log.badgenumber', 'left');
		$this->db->where($where);
		return $this->db->get("absensi_log")->row();
	}

	function insert($data, $tabel){
		//$this->db->insert('absensi_log_duplicate', $data);
		$this->db->insert($tabel, $data);
		return $this->db->affected_rows();
	}
	function update($where,$data){
		$this->db->where($where);
		$this->db->update('absensi_log', $data);
	}
	function delete($where){
		$this->db->where($where);
		$this->db->delete('absensi_log');
	}

	function update_date($where,$data,$tabel){
		$this->db->where($where);
		$this->db->update($tabel, $data);
		return $this->db->affected_rows();
	}
}

?>
