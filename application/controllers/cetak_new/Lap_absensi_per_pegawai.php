<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Lap_absensi_per_pegawai extends CI_Controller 
{
	public function __construct() 
	{
		parent::__construct();
		$this->load->model(['global_model', 'pegawai_model', 'instansi_model','data_mentah_model','log_laporan_model']);
	}

	public function index()
	{
		$bulan_array = array("JANUARI","FEBRUARI","MARET","APRIL","MEI","JUNI","JULI","AGUSTUS","SEPTEMBER","OKTOBER","NOVEMBER","DESEMBER");

		$this->id_pegawai 	= $_GET['id_pegawai'];
		$this->bulan 		= $_GET['bulan'];
		$this->nama_bulan 	= $bulan_array[($_GET['bulan'] - 1)];
		$this->tahun 		= $_GET['tahun'];

		$whereInstansi 		=	"kode = '".$this->input->get('id_instansi')."' ";
		$this->dataInstansi = 	$this->instansi_model->getData($whereInstansi,"","");

		$tglMulai			=	date($_GET['tahun']."-".$_GET['bulan']."-01");
		$this->tgl_terakhir 	= date('Y-m-t', strtotime($tglMulai));
		
		$this->sudahAda	=	$this->log_laporan_model->getData("kd_instansi = '".$this->input->get('id_instansi')."' and tgl_log = '".$this->tgl_terakhir."' ");
		
		
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
		//$this->pegawai 	= $this->pegawai_model->getDataJoin($where, $select, $join);

		$id_pegawai = $this->input->get('id_pegawai');
		$queryPegawai 		=	$this->db->query("
			select mp.id, mp.nama, mp.nip, pih.nama_instansi, pjh.nama_jenis_jabatan
			from m_pegawai mp
			LEFT JOIN LATERAL (
				SELECT mi.nama as nama_instansi
					FROM m_pegawai_unit_kerja_histori mpukh
					LEFT JOIN m_unit_organisasi_kerja muok ON mpukh.kode_unor = muok.kode
					LEFT JOIN m_instansi mi ON muok.kode_instansi = mi.kode 
					WHERE mpukh.tgl_mulai <= '$tglMulai' AND mpukh.id_pegawai = '$id_pegawai'
					ORDER BY mpukh.tgl_mulai DESC
					LIMIT 1
			) pih ON TRUE
			LEFT JOIN LATERAL (
				SELECT mjj.nama as nama_jenis_jabatan
					FROM m_pegawai_jabatan_histori mpjh
					LEFT JOIN m_jenis_jabatan mjj ON mpjh.kode_jabatan = mjj.kode 
					WHERE mpjh.tgl_mulai <= '$tglMulai' AND mpjh.id_pegawai = '$id_pegawai'
					ORDER BY mpjh.tgl_mulai DESC
					LIMIT 1
			) pjh ON TRUE
			where mp.id = '$id_pegawai'
		");

		$this->pegawai = 	$queryPegawai->row();

		// var_dump($this->pegawai);

		//$where = "id_pegawai = '".$_GET['id']."'";
		$where = array
		(
			'id_pegawai' 						=> $_GET['id_pegawai'],
			'extract(month from tanggal) = ' 	=> $_GET['bulan'],
			'extract(year from tanggal) = ' 	=> $_GET['tahun']
		);
		$order_by = 'tanggal asc';
		//$where = array();
		$this->absensi = $this->data_mentah_model->showData($where, '', $order_by);
		//echo $this->db->last_query();

		//var_dump($data['absensi']);

		//$this->load->view('cetak/lap_absensi_per_pegawai_view');
		$this->load->library('dompdf_gen');
		$this->load->view('cetak/lap_absensi_per_pegawai_view');
		$paper_size  = 'folio'; //paper size
		$orientation = 'portrait'; //tipe format kertas
		$html = $this->output->get_output();
		$this->dompdf->set_paper($paper_size, $orientation);
		//Convert to PDF
		$this->dompdf->load_html($html);
		$this->dompdf->render();
		$this->dompdf->stream("laporan_absensi_perpegawai.pdf", array('Attachment'=>0));	
	}
}