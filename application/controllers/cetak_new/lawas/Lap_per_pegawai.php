<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class lap_per_pegawai extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model(['global_model', 'pegawai_model', 'instansi_model','data_mentah_sebelum_update_model']);
	}

	public function index(){
		
		$this->load->library('konversi_menit');
		
		$tanggal	=	$this->input->get('tahun')."-".$this->input->get('bulan')."-01";
		$queryInstansi 		=	$this->db->query("
		SELECT 
			h.kode_unor, (select m_instansi.nama from m_instansi where m_instansi.kode = (select kode_instansi from m_unit_organisasi_kerja c where c.kode = h.kode_unor) )  as nama_instansi 
		FROM 
			m_pegawai_unit_kerja_histori h 
		WHERE 
			h.tgl_mulai <=  '".$tanggal."'  
			and h.id_pegawai =  '".$this->input->get('id_pegawai')."'
		ORDER BY tgl_mulai desc 
			limit 1
	
		");	
		$this->dataInstansi = 	$queryInstansi->row();
		
		$select = "m_pegawai.*,  m_jenis_jabatan.nama as nama_jenis_jabatan, m_status_pegawai.nama as nama_status_pegawai";
		$where = array('m_pegawai.id' => $this->input->get('id_pegawai'));
		$join = array(
			
			array(
				"table" => "m_eselon",
				"on"    => "m_pegawai.kode_eselon = m_eselon.kode"
			),
			array(
				"table" => "m_jenis_jabatan",
				"on"    => "m_pegawai.kode_jenis_jabatan = m_jenis_jabatan.kode"
			),
			array(
				"table" => "m_status_pegawai",
				"on"    => "m_pegawai.kode_status_pegawai = m_status_pegawai.kode"
			),
			array(
				"table" => "m_golongan",
				"on"    => "m_pegawai.kode_golongan_akhir = m_golongan.kode"
			)
		);
		$this->dataPegawai = $this->pegawai_model->getDataJoin($where,$select,$join);
		//echo $this->db->last_query();
		
		$hari_ini 		= date($this->input->get("tahun")."-".$this->input->get("bulan")."-01"); 
		// Tanggal pertama pada bulan ini
		$this->tgl_pertama 	= date('Y-m-01', strtotime($hari_ini));
		// Tanggal terakhir pada bulan ini
		$this->tgl_terakhir 	= date('Y-m-t', strtotime($hari_ini));
		
		$whereDataLaporan  = "id_pegawai='".$this->input->get('id_pegawai')."' and tanggal  <= '".$this->tgl_terakhir ."'  and 
						tanggal  >= '".$this->tgl_pertama ."' ";
		$this->dataLaporan = $this->data_mentah_sebelum_update_model->showData($whereDataLaporan,"","tanggal");
		//echo $this->db->last_query();
		//var_dump($this->dataLaporan);
		$this->load->view('cetak/lap_per_pegawai_view');
	}
	
	
	
}
