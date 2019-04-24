<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Lap_absensi_per_pegawai extends CI_Controller 
{
	public function __construct() 
	{
		parent::__construct();
		$this->load->model(['global_model', 'pegawai_model', 'instansi_model','data_mentah_sebelum_update_model']);
	}

	public function index()
	{
		$bulan_array = array("JANUARI","FEBRUARI","MARET","APRIL","MEI","JUNI","JULI","AGUSTUS","SEPTEMBER","OKTOBER","NOVEMBER","DESEMBER");

		$this->id_pegawai 	= $_GET['id_pegawai'];
		$this->bulan 		= $_GET['bulan'];
		$this->nama_bulan 	= $bulan_array[($_GET['bulan'] - 1)];
		$this->tahun 		= $_GET['tahun'];

		$select  	= 'm_pegawai.nama, m_pegawai.nip, m_jenis_jabatan.nama as nama_jabatan, m_instansi.nama as nama_instansi';
		$where   	= "m_pegawai.id = '".$_GET['id_pegawai']."' ";
		$join = array
		(
			array(
				"table" => "m_instansi",
				"on"    => "m_instansi.kode = m_pegawai.kode_instansi"
			),
			array(
				"table" => "m_jenis_jabatan",
				"on"    => "m_jenis_jabatan.kode = m_pegawai.kode_jenis_jabatan"
			)
		);
		$this->pegawai 	= $this->pegawai_model->getDataJoin($where, $select, $join);

		// var_dump($this->pegawai);

		//$where = "id_pegawai = '".$_GET['id']."'";
		$where = array
		(
			'id_pegawai' 						=> $_GET['id_pegawai'],
			'extract(month from tanggal) = ' 	=> $_GET['bulan'],
			'extract(year from tanggal) = ' 	=> $_GET['tahun']
		);
		//$where = array();
		$this->absensi = $this->data_mentah_sebelum_update_model->showData($where);
		//echo $this->db->last_query();

		//var_dump($data['absensi']);

		$this->load->view('cetak/lap_absensi_per_pegawai_view');
	}
}