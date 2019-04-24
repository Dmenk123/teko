<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class lap_per_pegawai extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model(['global_model', 'pegawai_model', 'instansi_model','data_mentah_model','log_laporan_model']);
	}

	public function index(){
		
		$this->load->library('ciqrcode');
		$this->load->library('encrypt_decrypt');
		
		$config['cacheable']    = true; //boolean, the default is true
		$config['cachedir']     = '/upload/'; //string, the default is application/cache/
		$config['errorlog']     = '/upload/'; //string, the default is application/logs/
		$config['imagedir']     = '/upload/qrcode/'; //direktori penyimpanan qr code
		$config['quality']      = true; //boolean, the default is true
		$config['size']         = '1024'; //interger, the default is 1024
		$config['black']        = array(224,255,255); // array, default is array(255,255,255)
		$config['white']        = array(70,130,180); // array, default is array(0,0,0)
		$this->ciqrcode->initialize($config);

		$url 			=	"asdasd";
		$image_name		=	time().'.png'; //buat name dari qr code sesuai dengan nim

		$currentURL = current_url(); //for simple URL
		//var_dump( $this->input->server('QUERY_STRING')); //for parameters
		$fullURL = $currentURL.'?'.$this->input->server('QUERY_STRING'); 

		$params['data'] 	= $fullURL; //data yang akan di jadikan QR CODE
		$params['level'] 	= 'H'; //H=High
		$params['size'] 	= 10;
		$params['savename'] = FCPATH.$config['imagedir'].$image_name; //simpan image QR CODE ke folder assets/images/
		$this->ciqrcode->generate($params); // fungsi untuk generate QR CODE
		
		$this->imageQrCode =	$image_name;
		
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
		
		$id_pegawai = $this->input->get('id_pegawai');
		$queryPegawai 		=	$this->db->query("
			select mp.id, mp.nama, mp.nip, pih.nama_instansi, pjh.nama_jenis_jabatan
			from m_pegawai mp
			LEFT JOIN LATERAL (
				SELECT mi.nama as nama_instansi
					FROM m_pegawai_unit_kerja_histori mpukh
					LEFT JOIN m_unit_organisasi_kerja muok ON mpukh.kode_unor = muok.kode
					LEFT JOIN m_instansi mi ON muok.kode_instansi = mi.kode 
					WHERE mpukh.tgl_mulai <= '$tanggal' AND mpukh.id_pegawai = '$id_pegawai'
					ORDER BY mpukh.tgl_mulai DESC
					LIMIT 1
			) pih ON TRUE
			LEFT JOIN LATERAL (
				SELECT mjj.nama as nama_jenis_jabatan
					FROM m_pegawai_jabatan_histori mpjh
					LEFT JOIN m_jenis_jabatan mjj ON mpjh.kode_jabatan = mjj.kode 
					WHERE mpjh.tgl_mulai <= '$tanggal' AND mpjh.id_pegawai = '$id_pegawai'
					ORDER BY mpjh.tgl_mulai DESC
					LIMIT 1
			) pjh ON TRUE
			where mp.id = '$id_pegawai'
		");

		$this->dataPegawai = 	$queryPegawai->row();
		
		// $select = "m_pegawai.*,  m_jenis_jabatan.nama as nama_jenis_jabatan, m_status_pegawai.nama as nama_status_pegawai";
		// $where = array('m_pegawai.id' => $this->input->get('id_pegawai'));
		// $join = array(
			
		// 	array(
		// 		"table" => "m_eselon",
		// 		"on"    => "m_pegawai.kode_eselon = m_eselon.kode"
		// 	),
		// 	array(
		// 		"table" => "m_jenis_jabatan",
		// 		"on"    => "m_pegawai.kode_jenis_jabatan = m_jenis_jabatan.kode"
		// 	),
		// 	array(
		// 		"table" => "m_status_pegawai",
		// 		"on"    => "m_pegawai.kode_status_pegawai = m_status_pegawai.kode"
		// 	),
		// 	array(
		// 		"table" => "m_golongan",
		// 		"on"    => "m_pegawai.kode_golongan_akhir = m_golongan.kode"
		// 	)
		// );
		// $this->dataPegawai = $this->pegawai_model->getDataJoin($where,$select,$join);
		//echo $this->db->last_query();
		
		$hari_ini 		= date($this->input->get("tahun")."-".$this->input->get("bulan")."-01"); 
		// Tanggal pertama pada bulan ini
		$this->tgl_pertama 	= date('Y-m-01', strtotime($hari_ini));
		// Tanggal terakhir pada bulan ini
		$this->tgl_terakhir 	= date('Y-m-t', strtotime($hari_ini));
		
		$this->sudahAda	=	$this->log_laporan_model->getData("kd_instansi = '".$this->input->get('id_instansi')."' and tgl_log = '".$this->tgl_terakhir."' ");
		
		$whereDataLaporan  = "id_pegawai='".$this->input->get('id_pegawai')."' and tanggal  <= '".$this->tgl_terakhir ."'  and 
						tanggal  >= '".$this->tgl_pertama ."' ";
		$this->dataLaporan = $this->data_mentah_model->showData($whereDataLaporan,"","tanggal");
		// var_dump ($this->db->last_query());
		//var_dump($this->dataLaporan);
		$this->load->view('cetak/lap_per_pegawai_view');
	}
}
