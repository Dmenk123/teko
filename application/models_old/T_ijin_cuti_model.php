<?php

class T_ijin_cuti_model extends CI_Model {
	public function __construct() {
		parent::__construct();

		//$this->load->model('log_model');
	}

	function showData($where = null,$like = null,$order_by = null,$limit = null, $fromLimit=null){

		$this->db->select("m_pegawai.nip");
		$this->db->select("m_pegawai.nama as nama_pegawai");

		$this->db->select("m_jenis_ijin_cuti.kode as kode_ijin_cuti");
		$this->db->select("m_jenis_ijin_cuti.nama as nama_ijin_cuti");

		$this->db->select("t_ijin_cuti_pegawai.id as id_t_ijin");
		$this->db->select("t_ijin_cuti_pegawai.file_lampiran");
		$this->db->select("t_ijin_cuti_pegawai.no_surat");
		$this->db->select("t_ijin_cuti_pegawai.keterangan");
		$this->db->select("t_ijin_cuti_pegawai.status");
		$this->db->select("to_char(t_ijin_cuti_pegawai.tgl_mulai, 'DD-MM-YYYY') as tgl_mulai");
		$this->db->select("to_char(t_ijin_cuti_pegawai.tgl_selesai, 'DD-MM-YYYY') as tgl_selesai");


		if($where){
			$this->db->where($where);
		}
		if($like){
			$this->db->like($like);
		}
		if($order_by){
			$this->db->order_by($order_by);
		}

		$this->db->join('m_pegawai', 'm_pegawai.id = t_ijin_cuti_pegawai.id_pegawai', 'left');
		$this->db->join('m_instansi', 'm_instansi.kode = m_pegawai.kode_instansi', 'left');
		$this->db->join('m_jenis_ijin_cuti', 'm_jenis_ijin_cuti.id = t_ijin_cuti_pegawai.id_jenis_ijin_cuti', 'left');
		return $this->db->get("t_ijin_cuti_pegawai",$limit,$fromLimit)->result();
	}

	function getCount($where = null,$like = null,$order_by = null,$limit = null, $fromLimit=null){
		$this->db->select("*");
		if($where){
			$this->db->where($where);
		}
		if($like){
			$this->db->like($like);
		}
		return $this->db->get("t_ijin_cuti_pegawai",$limit,$fromLimit)->num_rows();
	}

	function getData($where = null,$like = null,$order_by = null,$limit = null, $fromLimit=null){

		$this->db->select("m_pegawai.nip");
		$this->db->select("m_pegawai.nama as nama_pegawai");
		$this->db->select("m_pegawai.id as id_pegawai");

		$this->db->select("m_instansi.nama as nama_instansi");

		$this->db->select("m_jenis_jabatan.nama as nama_jabatan");

		$this->db->select("m_jenis_ijin_cuti.kode as kode_ijin_cuti");
		$this->db->select("m_jenis_ijin_cuti.nama as nama_ijin_cuti");
		$this->db->select("m_jenis_ijin_cuti.id as id_cuti");

		$this->db->select("t_ijin_cuti_pegawai.id as id_t_ijin");
		$this->db->select("t_ijin_cuti_pegawai.file_lampiran");
		$this->db->select("t_ijin_cuti_pegawai.no_surat");
		$this->db->select("t_ijin_cuti_pegawai.keterangan");
		$this->db->select("t_ijin_cuti_pegawai.status");

		$this->db->select("to_char(t_ijin_cuti_pegawai.tgl_mulai, 'YYYY-mm-dd') as tgl_mulai_insert");
		$this->db->select("to_char(t_ijin_cuti_pegawai.tgl_selesai, 'YYYY-mm-dd') as tgl_selesai_insert");

		$this->db->select("to_char(t_ijin_cuti_pegawai.tgl_mulai, 'DD-MM-YYYY') as tgl_mulai");
		$this->db->select("to_char(t_ijin_cuti_pegawai.tgl_selesai, 'DD-MM-YYYY') as tgl_selesai");

		$this->db->select("to_char(t_ijin_cuti_pegawai.tgl_selesai, 'MM/DD/YYYY') as tgl_selesai_form");
		$this->db->select("to_char(t_ijin_cuti_pegawai.tgl_mulai, 'MM/DD/YYYY') as tgl_mulai_form");
		$this->db->select("to_char(t_ijin_cuti_pegawai.tgl_surat, 'MM/DD/YYYY') as tgl_surat_form");


		if($where){
			$this->db->where($where);
		}
		if($like){
			$this->db->like($like);
		}
		if($order_by){
			$this->db->order_by($order_by);
		}

		$this->db->join('m_pegawai', 'm_pegawai.id = t_ijin_cuti_pegawai.id_pegawai', 'left');
		$this->db->join('m_instansi', 'm_instansi.kode = m_pegawai.kode_instansi', 'left');
		$this->db->join('m_jenis_jabatan', 'm_jenis_jabatan.kode = m_pegawai.kode_jenis_jabatan', 'left');
		$this->db->join('m_jenis_ijin_cuti', 'm_jenis_ijin_cuti.id = t_ijin_cuti_pegawai.id_jenis_ijin_cuti', 'left');


		return $this->db->get("t_ijin_cuti_pegawai",$limit,$fromLimit)->row();
	}


	function insert($data){
		$this->db->insert('t_ijin_cuti_pegawai', $data);
	}
	function update($where,$data){
		$this->db->where($where);
		$this->db->update('t_ijin_cuti_pegawai', $data);
	}
	function delete($where){
		$this->db->where($where);
		$this->db->delete('t_ijin_cuti_pegawai');
	}
}

?>
