<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Search extends CI_Controller {



	public function __construct() {
		parent::__construct();

		$this->load->model('pegawai_model');

	}

	public function pegawai(){


		/*
		$select = "m_pegawai.nip, ,m_pegawai.id ,m_pegawai.nama, m_instansi.nama as nama_instansi, m_jenis_jabatan.nama as nama_jenis_jabatan";
		$join = array(
			array(
				"table" => "m_instansi",
				"on"    => "m_pegawai.kode_instansi = m_instansi.kode"
			),
			array(
				"table" => "m_jenis_jabatan",
				"on"    => "m_pegawai.kode_jenis_jabatan = m_jenis_jabatan.kode"
			)
		);

		$order_by		=	'm_pegawai.nama';


		//var_dump($this->session->userdata('id_kategori_karyawan'));
		if($this->input->post('kode_instansi')){
			//$wherePegawai 	=	"(m_pegawai.nama ilike '%".$this->input->post('term')."%' or m_pegawai.nip ilike '%".$this->input->post('term')."%') and m_instansi.kode='".$this->input->post('kode_instansi')."' ";
			$wherePegawai 	=	"m_pegawai.nama ilike '%".$this->input->post('term')."%' or m_pegawai.nip ilike '%".$this->input->post('term')."%'";
		}
		else{
			$wherePegawai 	=	"m_pegawai.nama ilike '%".$this->input->post('term')."%' or m_pegawai.nip ilike '%".$this->input->post('term')."%'";
		}
		$dataPegawai 	= 	$this->pegawai_model->showData($wherePegawai , "", $order_by, null, null, null, "", $select,$join);
		//echo $this->db->last_query();
		*/
		//jika instansi sekretariat dewan
		if ($this->input->post('kode_instansi') == '2.00.00.00.00') {
			$kodeAwalDinas	=	substr($this->input->post('kode_instansi'),0,3);
		}else{
			$kodeAwalDinas	=	substr($this->input->post('kode_instansi'),0,4);
		}

		if($this->input->post('bulan') && $this->input->post('tahun')){
			$tanggal = $this->input->post('tahun')."-".$this->input->post('bulan')."-01";
		}
		else if(str_replace(' ', '', $this->input->post('tanggal')) <> '') {
			$tanggal = date('Y-m-d',strtotime(str_replace(' ', '', $this->input->post('tanggal'))));
		}
		else{
			$tanggal = date('Y-m-d');
		}

		$queryPegawai 	=	$this->db->query("
			select 
				m.id as id_pegawai,
				m.nip,m.nama, 
				pukh.kode_unor as unor ,
				mjb.nama as nama_unor,
				m.aktif,
				mjb.nama as nama_jabatan
				
			from 
				m_pegawai m
				LEFT JOIN m_jenis_jabatan mjb ON mjb.kode=m.kode_jenis_jabatan 
				LEFT JOIN LATERAL (
					SELECT kode_unor, langsung_pindah
					FROM m_pegawai_unit_kerja_histori h
					WHERE  tgl_mulai <= '".$tanggal."' and m.id = h.id_pegawai or (h.langsung_pindah = 't' and h.id_pegawai = m.id)
					ORDER BY tgl_mulai DESC
					LIMIT 1
				) pukh ON true
			where 
				pukh.kode_unor like '".$kodeAwalDinas."%'
				and ( m.nama ilike '%".$this->input->post('term')."%' or m.nip ilike '%".$this->input->post('term')."%' )
				
			order by 
				mjb.urut
		");

		/*$queryPegawai 	=	$this->db->query("
			select
				m.id as id_pegawai,
				m.nip,
				m.nama
			from
				m_pegawai m
			where
			m.nama ilike '%".$this->input->post('term')."%' or m.nip ilike '%".$this->input->post('term')."%'
		");*/

		$dataPegawai	=	$queryPegawai->result();
		//	echo $this->db->last_query();
		echo '[';
		$i=1;
		foreach($dataPegawai as $data){

			if($i > 1){echo ",";}
			echo '{ "label":"'.$data->nip.' - '.$data->nama.'", "id_pegawai":"'.$data->id_pegawai.'", "nip":"'.$data->nip.'", "nama":"'.$data->nama.'" } ';
			$i++;
		}
		echo ']';
	}

}
