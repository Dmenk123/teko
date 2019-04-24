<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class lap_absensi_lembur extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model(['global_model', 'pegawai_model', 'instansi_model','data_mentah_model','log_laporan_model']);
	}

	function cektanggal($date){
		$dayofweek = date('w', strtotime($date));
		echo date("w", strtotime("2018-02-25"));
	}

	function cekMigrasi(){
		$this->load->library('migrasi_data');
		$queryInstansi	=	"
			select
				m.id,m.nama, m.nip
			from
				m_pegawai m
				LEFT JOIN LATERAL (
				SELECT
				h.kode_unor, h.tgl_mulai, muok.nama as nama_unor, mi.kode as kode_instansi, mi.nama as nama_instansi
				FROM
				m_pegawai_unit_kerja_histori h
				LEFT JOIN m_unit_organisasi_kerja muok ON  h.kode_unor =  muok.kode
				LEFT JOIN m_instansi mi ON  muok.kode_instansi = mi.kode WHERE h.tgl_mulai <= '".date('Y-m-d')."' and m.id = h.id_pegawai
				ORDER BY h.tgl_mulai DESC LIMIT 1
				)
				pukh ON true
			where
				kode_status_pegawai = '1'
		";
		$data_pegawai = $this->db->query($queryInstansi)->result();
		foreach($data_pegawai as $temp){
			$begin = new DateTime( "2018-10-01" );
			$end   = new DateTime( "2018-11-26" );

			for($i = $begin; $i <= $end; $i->modify('+1 day')){
				// $this->migrasi_data->cek_ulang_data_mentah($i->format("Y-m-d"), $temp->id, "update", true);
				if($this->migrasi_data->count_data_mentah_pegawai( $i->format("Y-m-d"), $temp->id)->jumlah == 0 ){
					$this->migrasi_data->cek_ulang_data_mentah($i->format("Y-m-d"), $temp->id, "insert", true);
				}
			}
		}
		echo "<h1>SUKKSESSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSS</h1>";

	}

		function cronTabPerhariPerPegawai(){
			// crontab
		$this->load->library('migrasi_data');
		$queryInstansi	=	"
			select
				m.id,m.nama, m.nip
			from
				m_pegawai m
				LEFT JOIN LATERAL (
				SELECT
				h.kode_unor, h.tgl_mulai, muok.nama as nama_unor, mi.kode as kode_instansi, mi.nama as nama_instansi
				FROM
				m_pegawai_unit_kerja_histori h
				LEFT JOIN m_unit_organisasi_kerja muok ON  h.kode_unor =  muok.kode
				LEFT JOIN m_instansi mi ON  muok.kode_instansi = mi.kode WHERE h.tgl_mulai <= '".date('Y-m-d')."' and m.id = h.id_pegawai
				ORDER BY h.tgl_mulai DESC LIMIT 1
				)
				pukh ON true
		";
		$data_pegawai = $this->db->query($queryInstansi)->result();
		foreach($data_pegawai as $temp){
			// $begin = new DateTime( "2018-10-01" );
			// $end   = new DateTime( "2018-11-26" );

			// for($i = $begin; $i <= $end; $i->modify('+1 day')){
				// $this->migrasi_data->cek_ulang_data_mentah($i->format("Y-m-d"), $temp->id, "update", true);
				if($this->migrasi_data->count_data_mentah_pegawai( date("Y-m-d"), $temp->id)->jumlah == 0 ){
					$this->migrasi_data->cek_ulang_data_mentah(date("Y-m-d"), $temp->id, "insert", true);
				}
			// }
		}
		echo "<h1>SUKKSESSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSS</h1>";

	}

	function lanjutkan_crontab_insert(){
		// crontab
		$this->load->library('migrasi_data');
		$queryInstansi	=	"
			select
				m.id,m.nama, m.nip
			from
				m_pegawai m
				LEFT JOIN LATERAL (
				SELECT
				h.kode_unor, h.tgl_mulai, muok.nama as nama_unor, mi.kode as kode_instansi, mi.nama as nama_instansi
				FROM
				m_pegawai_unit_kerja_histori h
				LEFT JOIN m_unit_organisasi_kerja muok ON  h.kode_unor =  muok.kode
				LEFT JOIN m_instansi mi ON  muok.kode_instansi = mi.kode WHERE h.tgl_mulai <= '".date('Y-m-d')."' and m.id = h.id_pegawai
				ORDER BY h.tgl_mulai DESC LIMIT 1
				)
				pukh ON true";
				
		$data_pegawai = $this->db->query($queryInstansi)->result();
		$kemarin = date('Y-m-d', strtotime("-3 day", strtotime(date("Y-m-d"))));
		foreach($data_pegawai as $temp){
			$begin = new DateTime( $kemarin );
			$end   = new DateTime( date("Y-m-d") );

			for($i = $begin; $i <= $end; $i->modify('+1 day')){				
				
				$this->migrasi_data->cek_ulang_data_mentah(date("Y-m-d"), $temp->id, "update", true);
				
			}
		}
		echo "<h1>SUKKSESSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSS</h1>";

	}


	function cronTabUpdatePerPegawai(){
		$this->load->library('migrasi_data');
		$queryInstansi	=	"
			select
				m.id,m.nama, m.nip
			from
				m_pegawai m
				LEFT JOIN LATERAL (
				SELECT
				h.kode_unor, h.tgl_mulai, muok.nama as nama_unor, mi.kode as kode_instansi, mi.nama as nama_instansi
				FROM
				m_pegawai_unit_kerja_histori h
				LEFT JOIN m_unit_organisasi_kerja muok ON  h.kode_unor =  muok.kode
				LEFT JOIN m_instansi mi ON  muok.kode_instansi = mi.kode WHERE h.tgl_mulai <= '".date('Y-m-d')."' and m.id = h.id_pegawai
				ORDER BY h.tgl_mulai DESC LIMIT 1
				)
				pukh ON true
				where kode_status_pegawai = '1'
		";
		$data_pegawai = $this->db->query($queryInstansi)->result();
		foreach($data_pegawai as $temp){

			$begin = new DateTime( "2018-11-15" );
			$end   = new DateTime( date("Y-m-d") );

			for($i = $begin; $i <= $end; $i->modify('+1 day')){
				// $this->migrasi_data->cek_ulang_data_mentah($i->format("Y-m-d"), $temp->id, "update", true);
				// if($this->migrasi_data->count_data_mentah_pegawai( $i->format("Y-m-d"), $temp->id)->jumlah == 0 ){
					$this->migrasi_data->cek_ulang_data_mentah($i->format("Y-m-d"), $temp->id, "update", true);
				// }
			}
		}
		echo "<h1>SUKKSESSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSS</h1>";

	}

	// function cekMigrasiKominfo(){

	// }

	function cekMigrasiKominfo(){
		$this->load->library('migrasi_data');
		$queryInstansi	=	"
			select
				m.id,m.nama, m.nip
			from
				m_pegawai m
				LEFT JOIN LATERAL (
				SELECT
				h.kode_unor, h.tgl_mulai, muok.nama as nama_unor, mi.kode as kode_instansi, mi.nama as nama_instansi
				FROM
				m_pegawai_unit_kerja_histori h
				LEFT JOIN m_unit_organisasi_kerja muok ON  h.kode_unor =  muok.kode
				LEFT JOIN m_instansi mi ON  muok.kode_instansi = mi.kode WHERE h.tgl_mulai <= '2018-12-01' and m.id = h.id_pegawai
				ORDER BY h.tgl_mulai DESC LIMIT 1
				)
				pukh ON true
			where
				 pukh.kode_instansi = '5.16.00.00.00'
		";
		$data_pegawai = $this->db->query($queryInstansi)->result();
		foreach($data_pegawai as $temp){
			$begin = new DateTime( "2018-10-01" );
			$end   = new DateTime( "2018-11-26" );

			for($i = $begin; $i <= $end; $i->modify('+1 day')){
				$this->migrasi_data->cek_ulang_data_mentah($i->format("Y-m-d"), $temp->id, "insert", true);
			}
		}
		echo "<h1>SUKKSESSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSS</h1>";

	}

	function cekMigrasiBkd(){
		$this->load->library('migrasi_data');
		$queryInstansi	=	"
			select
				m.id,m.nama, m.nip
			from
				m_pegawai m
				LEFT JOIN LATERAL (
				SELECT
				h.kode_unor, h.tgl_mulai, muok.nama as nama_unor, mi.kode as kode_instansi, mi.nama as nama_instansi
				FROM
				m_pegawai_unit_kerja_histori h
				LEFT JOIN m_unit_organisasi_kerja muok ON  h.kode_unor =  muok.kode
				LEFT JOIN m_instansi mi ON  muok.kode_instansi = mi.kode WHERE h.tgl_mulai <= '2018-12-01' and m.id = h.id_pegawai
				ORDER BY h.tgl_mulai DESC LIMIT 1
				)
				pukh ON true
			where
				 pukh.kode_instansi = '5.08.00.00.00'
		";
		$data_pegawai = $this->db->query($queryInstansi)->result();
		foreach($data_pegawai as $temp){
			$begin = new DateTime( "2018-11-28" );
			$end   = new DateTime( "2018-11-30" );

			for($i = $begin; $i <= $end; $i->modify('+1 day')){
				// if($this->migrasi_data->count_data_mentah_pegawai( $i->format("Y-m-d"), $temp->id)->jumlah == 0 ){
				// 	$this->migrasi_data->cek_ulang_data_mentah($i->format("Y-m-d"), $temp->id, "insert", true);
				// }
				// else{
					$this->migrasi_data->cek_ulang_data_mentah($i->format("Y-m-d"), $temp->id, "update", true);
				// }
			}
		}
		echo "<h1>SUKKSESSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSS</h1>";

	}

	function cekMigrasiPerPegawai(){
		$this->load->library('migrasi_data');
		$queryInstansi	=	"
			select
				m.id,m.nama, m.nip
			from
				m_pegawai m
			where
				 m.id = 'facb1ee2-64d2-11e6-9754-8bf6a6025cb0'
		";
		$data_pegawai = $this->db->query($queryInstansi)->result();
		foreach($data_pegawai as $temp){
			$begin = new DateTime( "2018-11-01" );
			$end   = new DateTime( "2018-11-10" );

			for($i = $begin; $i <= $end; $i->modify('+1 day')){
				$this->migrasi_data->cek_ulang_data_mentah($i->format("Y-m-d"), $temp->id, "update", true);
			}
		}
		echo "<h1>SUKKSESSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSS</h1>";

	}
	
	
	function tampil_per_pegawai(){
		$this->load->library('migrasi_data');
		$queryInstansi	=	"
			select
				m.id,m.nama, m.nip
			from
				m_pegawai m
			where
				 m.id = 'f179fc14-64d2-11e6-b20a-777c85633d16'
		";
		$data_pegawai = $this->db->query($queryInstansi)->result();
		foreach($data_pegawai as $temp){
			$begin = new DateTime( "2018-05-01" );
			$end   = new DateTime( "2018-11-10" );

			for($i = $begin; $i <= $end; $i->modify('+1 day')){
				$this->migrasi_data->cek_ulang_data_mentah($i->format("Y-m-d"), $temp->id, "tampil", true);
			}
		}
	}

	function MigrasiDevToLive(){
		ini_set("max_execution_time", 0);
		$awal  = $this->input->get("awal");
		$akhir = $this->input->get("akhir");
		$offset = $this->input->get("offset");

		// echo $awal." ".$akhir." ".$limit;
		$this->load->library('migrasi_data');
		$dataInstansi = "select * from m_instansi where nama like '%Dinas%' order by kode asc limit 2 offset $offset";
		$dataDinas = $this->db->query($dataInstansi)->result();
		foreach($dataDinas as $temp_dinas){
			$kode_dinas = $temp_dinas->kode;
			$nama_dinas = $temp_dinas->nama;
		//	$kode_dinas = "5.16.00.00.00";
			$queryInstansi	=	"
				select
					m.id,m.nama, m.nip
				from
					m_pegawai m
					LEFT JOIN LATERAL (
					SELECT
					h.kode_unor, h.tgl_mulai, muok.nama as nama_unor, mi.kode as kode_instansi, mi.nama as nama_instansi
					FROM
					m_pegawai_unit_kerja_histori h
					LEFT JOIN m_unit_organisasi_kerja muok ON  h.kode_unor =  muok.kode
					LEFT JOIN m_instansi mi ON  muok.kode_instansi = mi.kode WHERE h.tgl_mulai <= '2018-11-02' and m.id = h.id_pegawai
					ORDER BY h.tgl_mulai DESC LIMIT 1
					)
					pukh ON true
				where
					pukh.kode_instansi = '".$kode_dinas."'";
		$data_pegawai = $this->db->query($queryInstansi)->result();
		foreach($data_pegawai as $temp){
			$begin = new DateTime( $awal );
			$end   = new DateTime( $akhir );

			for($i = $begin; $i <= $end; $i->modify('+1 day')){
				if($this->migrasi_data->count_data_mentah_pegawai( $i->format("Y-m-d"), $temp->id)->jumlah == 0 ){
					$this->migrasi_data->cek_ulang_data_mentah($i->format("Y-m-d"), $temp->id, "insert", true);
				}

			}
		}
		echo "<h1>KODE INSTANSI : $kode_dinas </h1> <h1>NAMA INSTANSI : $nama_dinas </h1><br>";

		}
		echo "<h1>SUKKSESSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSS</h1>";
	}

	// function MigrasiDevToLive_like(){
	// 	ini_set("max_execution_time", 0);
	// 	$awal  = $this->input->get("awal");
	// 	$akhir = $this->input->get("akhir");
	// 	$offset = $this->input->get("offset");
	// 	$like = $this->input->get("like");

	// 	// echo $awal." ".$akhir." ".$limit;
	// 	$this->load->library('migrasi_data');
	// 	$dataInstansi = "select * from m_instansi where nama like '%$like%' order by kode asc limit 2 offset $offset";
	// 	$dataDinas = $this->db->query($dataInstansi)->result();
	// 	foreach($dataDinas as $temp_dinas){
	// 		$kode_dinas = $temp_dinas->kode;
	// 		$nama_dinas = $temp_dinas->nama;
	// 	//	$kode_dinas = "5.16.00.00.00";
	// 		$queryInstansi	=	"
	// 			select
	// 				m.id,m.nama, m.nip
	// 			from
	// 				m_pegawai m
	// 				LEFT JOIN LATERAL (
	// 				SELECT
	// 				h.kode_unor, h.tgl_mulai, muok.nama as nama_unor, mi.kode as kode_instansi, mi.nama as nama_instansi
	// 				FROM
	// 				m_pegawai_unit_kerja_histori h
	// 				LEFT JOIN m_unit_organisasi_kerja muok ON  h.kode_unor =  muok.kode
	// 				LEFT JOIN m_instansi mi ON  muok.kode_instansi = mi.kode WHERE h.tgl_mulai <= '2018-11-02' and m.id = h.id_pegawai
	// 				ORDER BY h.tgl_mulai DESC LIMIT 1
	// 				)
	// 				pukh ON true
	// 			where
	// 				pukh.kode_instansi = '".$kode_dinas."' AND kode_status_pegawai = '1'";
	// 	$data_pegawai = $this->db->query($queryInstansi)->result();
	// 	foreach($data_pegawai as $temp){
	// 		$begin = new DateTime( $awal );
	// 		$end   = new DateTime( $akhir );

	// 		for($i = $begin; $i <= $end; $i->modify('+1 day')){
	// 			if($this->migrasi_data->count_data_mentah_pegawai( $i->format("Y-m-d"), $temp->id)->jumlah == 0 ){
	// 				$this->migrasi_data->cek_ulang_data_mentah($i->format("Y-m-d"), $temp->id, "insert", true);
	// 			}

	// 		}
	// 	}
	// 	echo "<h1>KODE INSTANSI : $kode_dinas </h1> <h1>NAMA INSTANSI : $nama_dinas </h1><br>";

	// 	}
	// 	echo "<h1>SUKKSESSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSS</h1>";
	// }

	// function MigrasiDevToLive_curl(){
	// 	ini_set("max_execution_time", 0);
	// 	$awal  = "2018-01-01";
	// 	$akhir = "2018-11-05";
	// 	$offset = 10;
	// 	$like = "Kecamatan";

	// 	// echo $awal." ".$akhir." ".$limit;
	// 	$this->load->library('migrasi_data');
	// 	$dataInstansi = "select * from m_instansi where nama like '%$like%' order by kode asc limit 2 offset $offset";
	// 	$dataDinas = $this->db->query($dataInstansi)->result();
	// 	foreach($dataDinas as $temp_dinas){
	// 		$kode_dinas = $temp_dinas->kode;
	// 		$nama_dinas = $temp_dinas->nama;
	// 	//	$kode_dinas = "5.16.00.00.00";
	// 		$queryInstansi	=	"
	// 			select
	// 				m.id,m.nama, m.nip
	// 			from
	// 				m_pegawai m
	// 				LEFT JOIN LATERAL (
	// 				SELECT
	// 				h.kode_unor, h.tgl_mulai, muok.nama as nama_unor, mi.kode as kode_instansi, mi.nama as nama_instansi
	// 				FROM
	// 				m_pegawai_unit_kerja_histori h
	// 				LEFT JOIN m_unit_organisasi_kerja muok ON  h.kode_unor =  muok.kode
	// 				LEFT JOIN m_instansi mi ON  muok.kode_instansi = mi.kode WHERE h.tgl_mulai <= '2018-11-02' and m.id = h.id_pegawai
	// 				ORDER BY h.tgl_mulai DESC LIMIT 1
	// 				)
	// 				pukh ON true
	// 			where
	// 				pukh.kode_instansi = '".$kode_dinas."' AND kode_status_pegawai = '1'";
	// 	$data_pegawai = $this->db->query($queryInstansi)->result();
	// 	foreach($data_pegawai as $temp){
	// 		$begin = new DateTime( $awal );
	// 		$end   = new DateTime( $akhir );

	// 		for($i = $begin; $i <= $end; $i->modify('+1 day')){
	// 			if($this->migrasi_data->count_data_mentah_pegawai( $i->format("Y-m-d"), $temp->id)->jumlah == 0 ){
	// 				$this->migrasi_data->cek_ulang_data_mentah($i->format("Y-m-d"), $temp->id, "insert", true);
	// 			}
	// 		}
	// 	}
	// 	echo "<h1>KODE INSTANSI : $kode_dinas </h1> <h1>NAMA INSTANSI : $nama_dinas </h1><br>";

	// 	}
	// 	echo "<h1>SUKKSESSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSS</h1>";
	// }

	// function MigrasiDevToLive_adi(){
	// 	ini_set("max_execution_time", 0);
	// 	$awal  = "2018-01-01";
	// 	$akhir = "2018-11-05";
	// 	$offset = 10;
	// 	$like = "Kecamatan";

	// 	// echo $awal." ".$akhir." ".$limit;
	// 	$this->load->library('migrasi_data');
	// 	// 500
	// 	$dataInstansi = "SELECT *
	// 				FROM
	// 					m_instansi
	// 				WHERE
	// 					nama NOT LIKE '%Penghapusan%'
	// 					OR nama NOT LIKE '%Perusahaan %'
	// 					OR nama NOT LIKE '%Satuan%'
	// 					OR nama NOT LIKE '%Sekretariat %'
	// 					OR nama NOT LIKE '%UPTD%'
	// 				ORDER BY
	// 					nama LIMIT 20
	// 					OFFSET 480";
	// 	$dataDinas = $this->db->query($dataInstansi)->result();
	// 	foreach($dataDinas as $temp_dinas){
	// 		$kode_dinas = $temp_dinas->kode;
	// 		$nama_dinas = $temp_dinas->nama;
	// 	//	$kode_dinas = "5.16.00.00.00";
	// 		$queryInstansi	=	"
	// 			select
	// 				m.id,m.nama, m.nip
	// 			from
	// 				m_pegawai m
	// 				LEFT JOIN LATERAL (
	// 				SELECT
	// 				h.kode_unor, h.tgl_mulai, muok.nama as nama_unor, mi.kode as kode_instansi, mi.nama as nama_instansi
	// 				FROM
	// 				m_pegawai_unit_kerja_histori h
	// 				LEFT JOIN m_unit_organisasi_kerja muok ON  h.kode_unor =  muok.kode
	// 				LEFT JOIN m_instansi mi ON  muok.kode_instansi = mi.kode WHERE h.tgl_mulai <= '2018-11-02' and m.id = h.id_pegawai
	// 				ORDER BY h.tgl_mulai DESC LIMIT 1
	// 				)
	// 				pukh ON true
	// 			where
	// 				pukh.kode_instansi = '".$kode_dinas."' AND kode_status_pegawai = '1'";
	// 	$data_pegawai = $this->db->query($queryInstansi)->result();
	// 	foreach($data_pegawai as $temp){
	// 		$begin = new DateTime( $awal );
	// 		$end   = new DateTime( $akhir );

	// 		for($i = $begin; $i <= $end; $i->modify('+1 day')){
	// 			if($this->migrasi_data->count_data_mentah_pegawai( $i->format("Y-m-d"), $temp->id)->jumlah == 0 ){
	// 				$this->migrasi_data->cek_ulang_data_mentah($i->format("Y-m-d"), $temp->id, "insert", true);
	// 			}

	// 		}
	// 	}
	// 	echo "<h1>KODE INSTANSI : $kode_dinas </h1> <h1>NAMA INSTANSI : $nama_dinas </h1><br>";

	// 	}
	// 	echo "<h1>SUKKSESSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSS</h1>";
	// }

	// function MigrasiDevToLive_all(){
	// 	ini_set("max_execution_time", 0);
	// 	$awal  = "2018-01-01";
	// 	$akhir = "2018-11-05";
	// 	$offset = 10;
	// 	$like = "Kecamatan";

	// 	// echo $awal." ".$akhir." ".$limit;
	// 	$this->load->library('migrasi_data');
	// 	$dataInstansi = " SELECT *
	// 										FROM
	// 											m_instansi
	// 										ORDER BY
	// 											nama ASC";


	// 										// WHERE
	// 										// 	nama NOT LIKE '%Penghapusan%'
	// 										// 	OR nama NOT LIKE '%Perusahaan %'
	// 										// 	OR nama NOT LIKE '%Satuan%'
	// 										// 	OR nama NOT LIKE '%Sekretariat %'
	// 										// 	OR nama NOT LIKE '%UPTD%'
	// 	$dataDinas = $this->db->query($dataInstansi)->result();
	// 	foreach($dataDinas as $temp_dinas){
	// 		$kode_dinas = $temp_dinas->kode;
	// 		$nama_dinas = $temp_dinas->nama;
	// 	//	$kode_dinas = "5.16.00.00.00";
	// 		$queryInstansi	=	"
	// 			select
	// 				m.id,m.nama, m.nip
	// 			from
	// 				m_pegawai m
	// 				LEFT JOIN LATERAL (
	// 				SELECT
	// 				h.kode_unor, h.tgl_mulai, muok.nama as nama_unor, mi.kode as kode_instansi, mi.nama as nama_instansi
	// 				FROM
	// 				m_pegawai_unit_kerja_histori h
	// 				LEFT JOIN m_unit_organisasi_kerja muok ON  h.kode_unor =  muok.kode
	// 				LEFT JOIN m_instansi mi ON  muok.kode_instansi = mi.kode WHERE h.tgl_mulai <= '2018-11-02' and m.id = h.id_pegawai
	// 				ORDER BY h.tgl_mulai DESC LIMIT 1
	// 				)
	// 				pukh ON true
	// 			where
	// 				pukh.kode_instansi = '".$kode_dinas."' AND kode_status_pegawai = '1'";
	// 	$data_pegawai = $this->db->query($queryInstansi)->result();
	// 	foreach($data_pegawai as $temp){
	// 		$begin = new DateTime( $awal );
	// 		$end   = new DateTime( $akhir );

	// 		for($i = $begin; $i <= $end; $i->modify('+1 day')){
	// 			if($this->migrasi_data->count_data_mentah_pegawai( $i->format("Y-m-d"), $temp->id)->jumlah == 0 ){
	// 				$this->migrasi_data->cek_ulang_data_mentah($i->format("Y-m-d"), $temp->id, "insert", true);
	// 			}

	// 		}
	// 	}
	// 	echo "<h1>KODE INSTANSI : $kode_dinas </h1> <h1>NAMA INSTANSI : $nama_dinas </h1><br>";

	// 	}
	// 	echo "<h1>SUKKSESSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSS</h1>";
	// }

	// function MigrasiDevToLive_all_nonpns(){
	// 	ini_set("max_execution_time", 0);
	// 	$awal  = "2018-01-01";
	// 	$akhir = "2018-11-05";
	// 	$offset = 10;
	// 	$like = "Kecamatan";

	// 	// echo $awal." ".$akhir." ".$limit;
	// 	$this->load->library('migrasi_data');
	// 	$dataInstansi = " SELECT *
	// 										FROM
	// 											m_instansi
	// 										ORDER BY
	// 											nama ASC";


	// 										// WHERE
	// 										// 	nama NOT LIKE '%Penghapusan%'
	// 										// 	OR nama NOT LIKE '%Perusahaan %'
	// 										// 	OR nama NOT LIKE '%Satuan%'
	// 										// 	OR nama NOT LIKE '%Sekretariat %'
	// 										// 	OR nama NOT LIKE '%UPTD%'
	// 	$dataDinas = $this->db->query($dataInstansi)->result();
	// 	foreach($dataDinas as $temp_dinas){
	// 		$kode_dinas = $temp_dinas->kode;
	// 		$nama_dinas = $temp_dinas->nama;
	// 	//	$kode_dinas = "5.16.00.00.00";
	// 		$queryInstansi	=	"
	// 			select
	// 				m.id,m.nama, m.nip
	// 			from
	// 				m_pegawai m
	// 				LEFT JOIN LATERAL (
	// 				SELECT
	// 				h.kode_unor, h.tgl_mulai, muok.nama as nama_unor, mi.kode as kode_instansi, mi.nama as nama_instansi
	// 				FROM
	// 				m_pegawai_unit_kerja_histori h
	// 				LEFT JOIN m_unit_organisasi_kerja muok ON  h.kode_unor =  muok.kode
	// 				LEFT JOIN m_instansi mi ON  muok.kode_instansi = mi.kode WHERE h.tgl_mulai <= '2018-11-02' and m.id = h.id_pegawai
	// 				ORDER BY h.tgl_mulai DESC LIMIT 1
	// 				)
	// 				pukh ON true
	// 			where
	// 				pukh.kode_instansi = '".$kode_dinas."' AND kode_status_pegawai != '1'";
	// 	$data_pegawai = $this->db->query($queryInstansi)->result();
	// 	foreach($data_pegawai as $temp){
	// 		$begin = new DateTime( $awal );
	// 		$end   = new DateTime( $akhir );

	// 		for($i = $begin; $i <= $end; $i->modify('+1 day')){
	// 			if($this->migrasi_data->count_data_mentah_pegawai( $i->format("Y-m-d"), $temp->id)->jumlah == 0 ){
	// 				$this->migrasi_data->cek_ulang_data_mentah($i->format("Y-m-d"), $temp->id, "insert", true);
	// 			}

	// 		}
	// 	}
	// 	echo "<h1>KODE INSTANSI : $kode_dinas </h1> <h1>NAMA INSTANSI : $nama_dinas </h1><br>";

	// 	}
	// 	echo "<h1>SUKKSESSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSS</h1>";
	// }

	// function MigrasiDevToLive_02(){
	// 	ini_set("max_execution_time", 0);
	// 	$awal  = $this->input->get("awal");
	// 	$akhir = $this->input->get("akhir");
	// 	$limit = $this->input->get("limit");

	// 	// echo $awal." ".$akhir." ".$limit;
	// 	$this->load->library('migrasi_data');
	// 	$dataInstansi = "select * from m_pegawai where id not in (select id_pegawai from data_mentah) and kode_status_pegawai = '1' limit 30";
	// 	$dataDinas = $this->db->query($dataInstansi)->result();
	// 	foreach($dataDinas as $temp_dinas){
	// 		$kode_dinas = $temp_dinas->id;
	// 		$nama_dinas = $temp_dinas->nama;
	// 	// 	$queryInstansi	=	"
	// 	// 		select
	// 	// 			m.id,m.nama, m.nip
	// 	// 		from
	// 	// 			m_pegawai m
	// 	// 			LEFT JOIN LATERAL (
	// 	// 			SELECT
	// 	// 			h.kode_unor, h.tgl_mulai, muok.nama as nama_unor, mi.kode as kode_instansi, mi.nama as nama_instansi
	// 	// 			FROM
	// 	// 			m_pegawai_unit_kerja_histori h
	// 	// 			LEFT JOIN m_unit_organisasi_kerja muok ON  h.kode_unor =  muok.kode
	// 	// 			LEFT JOIN m_instansi mi ON  muok.kode_instansi = mi.kode WHERE h.tgl_mulai <= '2018-11-02' and m.id = h.id_pegawai
	// 	// 			ORDER BY h.tgl_mulai DESC LIMIT 1
	// 	// 			)
	// 	// 			pukh ON true
	// 	// 		where
	// 	// 			pukh.kode_instansi = '$kode_dinas' AND kode_status_pegawai = '1'
	// 	// 	";
	// 	// $data_pegawai = $this->db->query($queryInstansi)->result();
	// 	// foreach($data_pegawai as $temp){
	// 	// 	$begin = new DateTime( $awal );
	// 	// 	$end   = new DateTime( $akhir );
	// 	//
	// 	// 	for($i = $begin; $i <= $end; $i->modify('+1 day')){
	// 	// 		$this->migrasi_data->cek_ulang_data_mentah($i->format("Y-m-d"), $temp->id, "insert", false);
	// 	// 	}
	// 	// }
	// 	echo "<h1>KODE INSTANSI : $kode_dinas </h1> <h1>NAMA INSTANSI : $nama_dinas </h1><br>";

	// 	}
	// 	echo "<h1>SUKKSESSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSS</h1>";
	// }

	// function MigrasiDevToLive_03(){
	// 	ini_set("max_execution_time", 0);
	// 	$this->load->library('migrasi_data');
	// 	$dataInstansi = "select * from m_instansi order by kode asc limit 11 offset 9";
	// 	$dataDinas = $this->db->query($dataInstansi)->result();
	// 	foreach($dataDinas as $temp_dinas){
	// 		$kode_dinas = $temp_dinas->kode;
	// 		$nama_dinas = $temp_dinas->nama;
	// 		$queryInstansi	=	"
	// 			select
	// 				m.id,m.nama, m.nip
	// 			from
	// 				m_pegawai m
	// 				LEFT JOIN LATERAL (
	// 				SELECT
	// 				h.kode_unor, h.tgl_mulai, muok.nama as nama_unor, mi.kode as kode_instansi, mi.nama as nama_instansi
	// 				FROM
	// 				m_pegawai_unit_kerja_histori h
	// 				LEFT JOIN m_unit_organisasi_kerja muok ON  h.kode_unor =  muok.kode
	// 				LEFT JOIN m_instansi mi ON  muok.kode_instansi = mi.kode WHERE h.tgl_mulai <= '2018-11-02' and m.id = h.id_pegawai
	// 				ORDER BY h.tgl_mulai DESC LIMIT 1
	// 				)
	// 				pukh ON true
	// 			where
	// 				pukh.kode_instansi = '$kode_dinas' AND kode_status_pegawai = '1'
	// 		";
	// 	$data_pegawai = $this->db->query($queryInstansi)->result();
	// 	foreach($data_pegawai as $temp){
	// 		$begin = new DateTime( "2018-02-01" );
	// 		$end   = new DateTime( "2018-03-01" );
	//
	// 		for($i = $begin; $i <= $end; $i->modify('+1 day')){
	// 			$this->migrasi_data->cek_ulang_data_mentah($i->format("Y-m-d"), $temp->id, "insert", true);
	// 		}
	// 	}
	// 	echo "<h1>KODE INSTANSI : $kode_dinas </h1> <h1>NAMA INSTANSI : $nama_dinas </h1><br>";
	//
	// 	}
	// 	echo "<h1>SUKKSESSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSS</h1>";
	// }
	//
	// function MigrasiDevToLive_04(){
	// 	ini_set("max_execution_time", 0);
	// 	$this->load->library('migrasi_data');
	// 	$dataInstansi = "select * from m_instansi order by kode asc limit 11 offset 9";
	// 	$dataDinas = $this->db->query($dataInstansi)->result();
	// 	foreach($dataDinas as $temp_dinas){
	// 		$kode_dinas = $temp_dinas->kode;
	// 		$nama_dinas = $temp_dinas->nama;
	// 		$queryInstansi	=	"
	// 			select
	// 				m.id,m.nama, m.nip
	// 			from
	// 				m_pegawai m
	// 				LEFT JOIN LATERAL (
	// 				SELECT
	// 				h.kode_unor, h.tgl_mulai, muok.nama as nama_unor, mi.kode as kode_instansi, mi.nama as nama_instansi
	// 				FROM
	// 				m_pegawai_unit_kerja_histori h
	// 				LEFT JOIN m_unit_organisasi_kerja muok ON  h.kode_unor =  muok.kode
	// 				LEFT JOIN m_instansi mi ON  muok.kode_instansi = mi.kode WHERE h.tgl_mulai <= '2018-11-02' and m.id = h.id_pegawai
	// 				ORDER BY h.tgl_mulai DESC LIMIT 1
	// 				)
	// 				pukh ON true
	// 			where
	// 				pukh.kode_instansi = '$kode_dinas' AND kode_status_pegawai = '1'
	// 		";
	// 	$data_pegawai = $this->db->query($queryInstansi)->result();
	// 	foreach($data_pegawai as $temp){
	// 		$begin = new DateTime( "2018-02-01" );
	// 		$end   = new DateTime( "2018-03-01" );
	//
	// 		for($i = $begin; $i <= $end; $i->modify('+1 day')){
	// 			$this->migrasi_data->cek_ulang_data_mentah($i->format("Y-m-d"), $temp->id, "insert", true);
	// 		}
	// 	}
	// 	echo "<h1>KODE INSTANSI : $kode_dinas </h1> <h1>NAMA INSTANSI : $nama_dinas </h1><br>";
	//
	// 	}
	// 	echo "<h1>SUKKSESSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSS</h1>";
	// }
	//
	// function MigrasiDevToLive_05(){
	// 	ini_set("max_execution_time", 0);
	// 	$this->load->library('migrasi_data');
	// 	$dataInstansi = "select * from m_instansi order by kode asc limit 11 offset 9";
	// 	$dataDinas = $this->db->query($dataInstansi)->result();
	// 	foreach($dataDinas as $temp_dinas){
	// 		$kode_dinas = $temp_dinas->kode;
	// 		$nama_dinas = $temp_dinas->nama;
	// 		$queryInstansi	=	"
	// 			select
	// 				m.id,m.nama, m.nip
	// 			from
	// 				m_pegawai m
	// 				LEFT JOIN LATERAL (
	// 				SELECT
	// 				h.kode_unor, h.tgl_mulai, muok.nama as nama_unor, mi.kode as kode_instansi, mi.nama as nama_instansi
	// 				FROM
	// 				m_pegawai_unit_kerja_histori h
	// 				LEFT JOIN m_unit_organisasi_kerja muok ON  h.kode_unor =  muok.kode
	// 				LEFT JOIN m_instansi mi ON  muok.kode_instansi = mi.kode WHERE h.tgl_mulai <= '2018-11-02' and m.id = h.id_pegawai
	// 				ORDER BY h.tgl_mulai DESC LIMIT 1
	// 				)
	// 				pukh ON true
	// 			where
	// 				pukh.kode_instansi = '$kode_dinas' AND kode_status_pegawai = '1'
	// 		";
	// 	$data_pegawai = $this->db->query($queryInstansi)->result();
	// 	foreach($data_pegawai as $temp){
	// 		$begin = new DateTime( "2018-02-01" );
	// 		$end   = new DateTime( "2018-03-01" );
	//
	// 		for($i = $begin; $i <= $end; $i->modify('+1 day')){
	// 			$this->migrasi_data->cek_ulang_data_mentah($i->format("Y-m-d"), $temp->id, "insert", true);
	// 		}
	// 	}
	// 	echo "<h1>KODE INSTANSI : $kode_dinas </h1> <h1>NAMA INSTANSI : $nama_dinas </h1><br>";
	//
	// 	}
	// 	echo "<h1>SUKKSESSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSS</h1>";
	// }
	//
	// function MigrasiDevToLive_06(){
	// 	ini_set("max_execution_time", 0);
	// 	$this->load->library('migrasi_data');
	// 	$dataInstansi = "select * from m_instansi order by kode asc limit 11 offset 9";
	// 	$dataDinas = $this->db->query($dataInstansi)->result();
	// 	foreach($dataDinas as $temp_dinas){
	// 		$kode_dinas = $temp_dinas->kode;
	// 		$nama_dinas = $temp_dinas->nama;
	// 		$queryInstansi	=	"
	// 			select
	// 				m.id,m.nama, m.nip
	// 			from
	// 				m_pegawai m
	// 				LEFT JOIN LATERAL (
	// 				SELECT
	// 				h.kode_unor, h.tgl_mulai, muok.nama as nama_unor, mi.kode as kode_instansi, mi.nama as nama_instansi
	// 				FROM
	// 				m_pegawai_unit_kerja_histori h
	// 				LEFT JOIN m_unit_organisasi_kerja muok ON  h.kode_unor =  muok.kode
	// 				LEFT JOIN m_instansi mi ON  muok.kode_instansi = mi.kode WHERE h.tgl_mulai <= '2018-11-02' and m.id = h.id_pegawai
	// 				ORDER BY h.tgl_mulai DESC LIMIT 1
	// 				)
	// 				pukh ON true
	// 			where
	// 				pukh.kode_instansi = '$kode_dinas' AND kode_status_pegawai = '1'
	// 		";
	// 	$data_pegawai = $this->db->query($queryInstansi)->result();
	// 	foreach($data_pegawai as $temp){
	// 		$begin = new DateTime( "2018-02-01" );
	// 		$end   = new DateTime( "2018-03-01" );
	//
	// 		for($i = $begin; $i <= $end; $i->modify('+1 day')){
	// 			$this->migrasi_data->cek_ulang_data_mentah($i->format("Y-m-d"), $temp->id, "insert", true);
	// 		}
	// 	}
	// 	echo "<h1>KODE INSTANSI : $kode_dinas </h1> <h1>NAMA INSTANSI : $nama_dinas </h1><br>";
	//
	// 	}
	// 	echo "<h1>SUKKSESSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSS</h1>";
	// }
	//
	// function MigrasiDevToLive_07(){
	// 	ini_set("max_execution_time", 0);
	// 	$this->load->library('migrasi_data');
	// 	$dataInstansi = "select * from m_instansi order by kode asc limit 11 offset 9";
	// 	$dataDinas = $this->db->query($dataInstansi)->result();
	// 	foreach($dataDinas as $temp_dinas){
	// 		$kode_dinas = $temp_dinas->kode;
	// 		$nama_dinas = $temp_dinas->nama;
	// 		$queryInstansi	=	"
	// 			select
	// 				m.id,m.nama, m.nip
	// 			from
	// 				m_pegawai m
	// 				LEFT JOIN LATERAL (
	// 				SELECT
	// 				h.kode_unor, h.tgl_mulai, muok.nama as nama_unor, mi.kode as kode_instansi, mi.nama as nama_instansi
	// 				FROM
	// 				m_pegawai_unit_kerja_histori h
	// 				LEFT JOIN m_unit_organisasi_kerja muok ON  h.kode_unor =  muok.kode
	// 				LEFT JOIN m_instansi mi ON  muok.kode_instansi = mi.kode WHERE h.tgl_mulai <= '2018-11-02' and m.id = h.id_pegawai
	// 				ORDER BY h.tgl_mulai DESC LIMIT 1
	// 				)
	// 				pukh ON true
	// 			where
	// 				pukh.kode_instansi = '$kode_dinas' AND kode_status_pegawai = '1'
	// 		";
	// 	$data_pegawai = $this->db->query($queryInstansi)->result();
	// 	foreach($data_pegawai as $temp){
	// 		$begin = new DateTime( "2018-02-01" );
	// 		$end   = new DateTime( "2018-03-01" );
	//
	// 		for($i = $begin; $i <= $end; $i->modify('+1 day')){
	// 			$this->migrasi_data->cek_ulang_data_mentah($i->format("Y-m-d"), $temp->id, "insert", true);
	// 		}
	// 	}
	// 	echo "<h1>KODE INSTANSI : $kode_dinas </h1> <h1>NAMA INSTANSI : $nama_dinas </h1><br>";
	//
	// 	}
	// 	echo "<h1>SUKKSESSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSS</h1>";
	// }
	//
	// function MigrasiDevToLive_08(){
	// 	ini_set("max_execution_time", 0);
	// 	$this->load->library('migrasi_data');
	// 	$dataInstansi = "select * from m_instansi order by kode asc limit 11 offset 9";
	// 	$dataDinas = $this->db->query($dataInstansi)->result();
	// 	foreach($dataDinas as $temp_dinas){
	// 		$kode_dinas = $temp_dinas->kode;
	// 		$nama_dinas = $temp_dinas->nama;
	// 		$queryInstansi	=	"
	// 			select
	// 				m.id,m.nama, m.nip
	// 			from
	// 				m_pegawai m
	// 				LEFT JOIN LATERAL (
	// 				SELECT
	// 				h.kode_unor, h.tgl_mulai, muok.nama as nama_unor, mi.kode as kode_instansi, mi.nama as nama_instansi
	// 				FROM
	// 				m_pegawai_unit_kerja_histori h
	// 				LEFT JOIN m_unit_organisasi_kerja muok ON  h.kode_unor =  muok.kode
	// 				LEFT JOIN m_instansi mi ON  muok.kode_instansi = mi.kode WHERE h.tgl_mulai <= '2018-11-02' and m.id = h.id_pegawai
	// 				ORDER BY h.tgl_mulai DESC LIMIT 1
	// 				)
	// 				pukh ON true
	// 			where
	// 				pukh.kode_instansi = '$kode_dinas' AND kode_status_pegawai = '1'
	// 		";
	// 	$data_pegawai = $this->db->query($queryInstansi)->result();
	// 	foreach($data_pegawai as $temp){
	// 		$begin = new DateTime( "2018-02-01" );
	// 		$end   = new DateTime( "2018-03-01" );
	//
	// 		for($i = $begin; $i <= $end; $i->modify('+1 day')){
	// 			$this->migrasi_data->cek_ulang_data_mentah($i->format("Y-m-d"), $temp->id, "insert", true);
	// 		}
	// 	}
	// 	echo "<h1>KODE INSTANSI : $kode_dinas </h1> <h1>NAMA INSTANSI : $nama_dinas </h1><br>";
	//
	// 	}
	// 	echo "<h1>SUKKSESSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSS</h1>";
	// }
	//
	// function MigrasiDevToLive_09(){
	// 	ini_set("max_execution_time", 0);
	// 	$this->load->library('migrasi_data');
	// 	$dataInstansi = "select * from m_instansi order by kode asc limit 11 offset 9";
	// 	$dataDinas = $this->db->query($dataInstansi)->result();
	// 	foreach($dataDinas as $temp_dinas){
	// 		$kode_dinas = $temp_dinas->kode;
	// 		$nama_dinas = $temp_dinas->nama;
	// 		$queryInstansi	=	"
	// 			select
	// 				m.id,m.nama, m.nip
	// 			from
	// 				m_pegawai m
	// 				LEFT JOIN LATERAL (
	// 				SELECT
	// 				h.kode_unor, h.tgl_mulai, muok.nama as nama_unor, mi.kode as kode_instansi, mi.nama as nama_instansi
	// 				FROM
	// 				m_pegawai_unit_kerja_histori h
	// 				LEFT JOIN m_unit_organisasi_kerja muok ON  h.kode_unor =  muok.kode
	// 				LEFT JOIN m_instansi mi ON  muok.kode_instansi = mi.kode WHERE h.tgl_mulai <= '2018-11-02' and m.id = h.id_pegawai
	// 				ORDER BY h.tgl_mulai DESC LIMIT 1
	// 				)
	// 				pukh ON true
	// 			where
	// 				pukh.kode_instansi = '$kode_dinas' AND kode_status_pegawai = '1'
	// 		";
	// 	$data_pegawai = $this->db->query($queryInstansi)->result();
	// 	foreach($data_pegawai as $temp){
	// 		$begin = new DateTime( "2018-02-01" );
	// 		$end   = new DateTime( "2018-03-01" );
	//
	// 		for($i = $begin; $i <= $end; $i->modify('+1 day')){
	// 			$this->migrasi_data->cek_ulang_data_mentah($i->format("Y-m-d"), $temp->id, "insert", true);
	// 		}
	// 	}
	// 	echo "<h1>KODE INSTANSI : $kode_dinas </h1> <h1>NAMA INSTANSI : $nama_dinas </h1><br>";
	//
	// 	}
	// 	echo "<h1>SUKKSESSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSS</h1>";
	// }
	//
	// function MigrasiDevToLive_10(){
	// 	ini_set("max_execution_time", 0);
	// 	$this->load->library('migrasi_data');
	// 	$dataInstansi = "select * from m_instansi order by kode asc limit 11 offset 9";
	// 	$dataDinas = $this->db->query($dataInstansi)->result();
	// 	foreach($dataDinas as $temp_dinas){
	// 		$kode_dinas = $temp_dinas->kode;
	// 		$nama_dinas = $temp_dinas->nama;
	// 		$queryInstansi	=	"
	// 			select
	// 				m.id,m.nama, m.nip
	// 			from
	// 				m_pegawai m
	// 				LEFT JOIN LATERAL (
	// 				SELECT
	// 				h.kode_unor, h.tgl_mulai, muok.nama as nama_unor, mi.kode as kode_instansi, mi.nama as nama_instansi
	// 				FROM
	// 				m_pegawai_unit_kerja_histori h
	// 				LEFT JOIN m_unit_organisasi_kerja muok ON  h.kode_unor =  muok.kode
	// 				LEFT JOIN m_instansi mi ON  muok.kode_instansi = mi.kode WHERE h.tgl_mulai <= '2018-11-02' and m.id = h.id_pegawai
	// 				ORDER BY h.tgl_mulai DESC LIMIT 1
	// 				)
	// 				pukh ON true
	// 			where
	// 				pukh.kode_instansi = '$kode_dinas' AND kode_status_pegawai = '1'
	// 		";
	// 	$data_pegawai = $this->db->query($queryInstansi)->result();
	// 	foreach($data_pegawai as $temp){
	// 		$begin = new DateTime( "2018-02-01" );
	// 		$end   = new DateTime( "2018-03-01" );
	//
	// 		for($i = $begin; $i <= $end; $i->modify('+1 day')){
	// 			$this->migrasi_data->cek_ulang_data_mentah($i->format("Y-m-d"), $temp->id, "insert", true);
	// 		}
	// 	}
	// 	echo "<h1>KODE INSTANSI : $kode_dinas </h1> <h1>NAMA INSTANSI : $nama_dinas </h1><br>";
	//
	// 	}
	// 	echo "<h1>SUKKSESSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSS</h1>";
	// }
	//
	// function MigrasiDevToLive_11(){
	// 	ini_set("max_execution_time", 0);
	// 	$this->load->library('migrasi_data');
	// 	$dataInstansi = "select * from m_instansi order by kode asc limit 11 offset 9";
	// 	$dataDinas = $this->db->query($dataInstansi)->result();
	// 	foreach($dataDinas as $temp_dinas){
	// 		$kode_dinas = $temp_dinas->kode;
	// 		$nama_dinas = $temp_dinas->nama;
	// 		$queryInstansi	=	"
	// 			select
	// 				m.id,m.nama, m.nip
	// 			from
	// 				m_pegawai m
	// 				LEFT JOIN LATERAL (
	// 				SELECT
	// 				h.kode_unor, h.tgl_mulai, muok.nama as nama_unor, mi.kode as kode_instansi, mi.nama as nama_instansi
	// 				FROM
	// 				m_pegawai_unit_kerja_histori h
	// 				LEFT JOIN m_unit_organisasi_kerja muok ON  h.kode_unor =  muok.kode
	// 				LEFT JOIN m_instansi mi ON  muok.kode_instansi = mi.kode WHERE h.tgl_mulai <= '2018-11-02' and m.id = h.id_pegawai
	// 				ORDER BY h.tgl_mulai DESC LIMIT 1
	// 				)
	// 				pukh ON true
	// 			where
	// 				pukh.kode_instansi = '$kode_dinas' AND kode_status_pegawai = '1'
	// 		";
	// 	$data_pegawai = $this->db->query($queryInstansi)->result();
	// 	foreach($data_pegawai as $temp){
	// 		$begin = new DateTime( "2018-02-01" );
	// 		$end   = new DateTime( "2018-03-01" );
	//
	// 		for($i = $begin; $i <= $end; $i->modify('+1 day')){
	// 			$this->migrasi_data->cek_ulang_data_mentah($i->format("Y-m-d"), $temp->id, "insert", true);
	// 		}
	// 	}
	// 	echo "<h1>KODE INSTANSI : $kode_dinas </h1> <h1>NAMA INSTANSI : $nama_dinas </h1><br>";
	//
	// 	}
	// 	echo "<h1>SUKKSESSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSS</h1>";
	// }
	//
	// function MigrasiDevToLive_12(){
	// 	ini_set("max_execution_time", 0);
	// 	$this->load->library('migrasi_data');
	// 	$dataInstansi = "select * from m_instansi order by kode asc limit 11 offset 9";
	// 	$dataDinas = $this->db->query($dataInstansi)->result();
	// 	foreach($dataDinas as $temp_dinas){
	// 		$kode_dinas = $temp_dinas->kode;
	// 		$nama_dinas = $temp_dinas->nama;
	// 		$queryInstansi	=	"
	// 			select
	// 				m.id,m.nama, m.nip
	// 			from
	// 				m_pegawai m
	// 				LEFT JOIN LATERAL (
	// 				SELECT
	// 				h.kode_unor, h.tgl_mulai, muok.nama as nama_unor, mi.kode as kode_instansi, mi.nama as nama_instansi
	// 				FROM
	// 				m_pegawai_unit_kerja_histori h
	// 				LEFT JOIN m_unit_organisasi_kerja muok ON  h.kode_unor =  muok.kode
	// 				LEFT JOIN m_instansi mi ON  muok.kode_instansi = mi.kode WHERE h.tgl_mulai <= '2018-11-02' and m.id = h.id_pegawai
	// 				ORDER BY h.tgl_mulai DESC LIMIT 1
	// 				)
	// 				pukh ON true
	// 			where
	// 				pukh.kode_instansi = '$kode_dinas' AND kode_status_pegawai = '1'
	// 		";
	// 	$data_pegawai = $this->db->query($queryInstansi)->result();
	// 	foreach($data_pegawai as $temp){
	// 		$begin = new DateTime( "2018-02-01" );
	// 		$end   = new DateTime( "2018-03-01" );
	//
	// 		for($i = $begin; $i <= $end; $i->modify('+1 day')){
	// 			$this->migrasi_data->cek_ulang_data_mentah($i->format("Y-m-d"), $temp->id, "insert", true);
	// 		}
	// 	}
	// 	echo "<h1>KODE INSTANSI : $kode_dinas </h1> <h1>NAMA INSTANSI : $nama_dinas </h1><br>";
	//
	// 	}
	// 	echo "<h1>SUKKSESSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSS</h1>";
	// }

	function CekMigrasiDevToLive(){
			$this->load->library('Migrasi_data');
			$this->migrasi_data->cek_ulang_data_mentah("2018-09-30", "f179fc14-64d2-11e6-b20a-777c85633d16", "insert", true);
	}

	function MigrasiDataMentah(){
		$this->load->library('Migrasi_data');
		$queryInstansi	=	"
			select
				m.id,m.nama, m.nip
			from
				m_pegawai m
				LEFT JOIN LATERAL (
				SELECT
				h.kode_unor, h.tgl_mulai, muok.nama as nama_unor, mi.kode as kode_instansi, mi.nama as nama_instansi
				FROM
				m_pegawai_unit_kerja_histori h
				LEFT JOIN m_unit_organisasi_kerja muok ON  h.kode_unor =  muok.kode
				LEFT JOIN m_instansi mi ON  muok.kode_instansi = mi.kode WHERE h.tgl_mulai <= '2018-11-01' and m.id = h.id_pegawai
				ORDER BY h.tgl_mulai DESC LIMIT 1
				)
				pukh ON true
			where
				pukh.kode_instansi = '5.19.00.00.00' 
		";
		$data_pegawai = $this->db->query($queryInstansi)->result();
		foreach($data_pegawai as $temp){
			$begin = new DateTime( "2018-10-01" );
			$end   = new DateTime( "2018-11-01" );

			for($i = $begin; $i <= $end; $i->modify('+1 day')){
				$this->migrasi_data->cek_ulang_data_mentah($i->format("Y-m-d"), $temp->id, "insert", false);
			}
		}

	}

	public function cek_lagi(){
		$this->load->library('migrasi_data');
		// $tanggal = "10/12/2019";
		// $date = new DateTime( $tanggal );
		// echo $date->format("Y-m-d");
		$tgl_mulai 					= "2018-05-01";
		$tgl_selesai_insert = "2018-06-30";
		$begin 							= new DateTime( $tgl_mulai );
		$end   							= new DateTime( $tgl_selesai_insert );

		for($i = $begin; $i <= $end; $i->modify('+1 day')){
			$this->migrasi_data->cek_ulang_data_mentah($i->format("Y-m-d"), 'f2da47bc-64d2-11e6-baf8-b7b54aac853d', "insert", true);
		}

	}

	public function cek_sync(){
		$this->load->library('migrasi_data_sync');
		// $tanggal = "10/12/2019";
		// $date = new DateTime( $tanggal );
		// echo $date->format("Y-m-d");
		// $tgl_mulai 					= "2018-05-01";
		// $tgl_selesai_insert = "2018-06-30";
		// $begin 							= new DateTime( $tgl_mulai );
		// $end   							= new DateTime( $tgl_selesai_insert );
		//
		// for($i = $begin; $i <= $end; $i->modify('+1 day')){
			$this->migrasi_data_sync->cek_ulang_data_mentah('2018-05-03', 'faa508e2-64d2-11e6-9539-2706f1cb028b', "update");
			// $this->migrasi_data->cek_ulang_data_mentah('2018-06-25', 'fa9de4c2-64d2-11e6-b269-0f2b2d8201b8', "update");

		// }
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

		$whereInstansi 		=	"kode = '".$this->input->get('id_instansi')."' ";
		$this->dataInstansi = 	$this->instansi_model->getData($whereInstansi,"","");

		$namaBulan = array(
			'01' => 'JANUARI',
			'02' => 'FEBRUARI',
			'03' => 'MARET',
			'04' => 'APRIL',
			'05' => 'MEI',
			'06' => 'JUNI',
			'07' => 'JULI',
			'08' => 'AGUSTUS',
			'09' => 'SEPTEMBER',
			'10' => 'OKTOBER',
			'11' => 'NOVEMBER',
			'12' => 'DESEMBER'
        );

		
		
			
		

		$hari_ini 		= date($this->input->get("tahun")."-".$this->input->get("bulan")."-01");
		// Tanggal pertama pada bulan ini
		$this->tgl_pertama 	= date('Y-m-01', strtotime($hari_ini));
		// Tanggal terakhir pada bulan ini
		$this->tgl_terakhir 	= date('Y-m-t', strtotime($hari_ini));
		
		$this->sudahAda	=	$this->log_laporan_model->getData("kd_instansi = '".$this->input->get('id_instansi')."' and tgl_log = '".$this->tgl_terakhir."' ");

		$this->dataLembur = "";
		$this->dataLembur .= '
		<table width="100%" class="cloth" border="0" cellspacing="0" cellpadding="0">
		<tr>
		<th>NO</th>
		<th>NAMA</th>';

		while (strtotime($this->tgl_pertama) <= strtotime($this->tgl_terakhir )) {

			$this->dataLembur .= '<th>'.date ("d", strtotime($this->tgl_pertama)).'</th>';
			$this->tgl_pertama = date ("Y-m-d", strtotime("+1 days", strtotime($this->tgl_pertama)));
		}



		$this->dataLembur .= '<th>Total</th><th>Skor Lembur (%)</th></tr>';



		/**$select = "m_pegawai.nama,m_pegawai.id as id_pegawai,m_jenis_jabatan.nama as nama_jenis_jabatan";
		if($this->input->get("pns") == 'y'){
			$where 	= "m_pegawai.kode_instansi = '".$this->input->get('id_instansi')."' and m_pegawai.kode_status_pegawai='1'";
		}
		else{

			$where 	= "m_pegawai.kode_instansi = '".$this->input->get('id_instansi')."' and m_pegawai.kode_status_pegawai!='1'";
		}

		$join 	= array(
			array(
				"table" => "m_jenis_jabatan",
				"on"    => "m_pegawai.kode_jenis_jabatan = m_jenis_jabatan.kode"
			)
		);
		$orderBy 			= "m_jenis_jabatan.urut,m_pegawai.nama";
		$this->dataPegawai 	= $this->pegawai_model->showData($where,'',$orderBy,'','','','',$select,$join);
		**/

		$kodeAwalDinas	=	substr($this->input->get('id_instansi'),0,4);

		if($this->input->get("pns") == 'y'){
			$wherePns 	= " and m.kode_status_pegawai='1'";
		}
		else{

			$wherePns 	= " and m.kode_status_pegawai!='1'";
		}

		$tanggal	=	$this->input->get('tahun')."-".$this->input->get('bulan')."-01";


		$tglSelesai 	= date('Y-m-t', strtotime($tanggal));

		$queryPegawai 	=	$this->db->query("
		select
			m.id as id_pegawai,m.nama, m.nip,
			pukh.nama_unor,
			pukh.nama_instansi,
			pjh.nama_jabatan, pjh.urut,
			pgh.nama_golongan,
			peh.nama_eselon,
			prjh.nama_rumpun_jabatan
		from
			m_pegawai m
			LEFT JOIN LATERAL (
				SELECT
					h.kode_unor, h.tgl_mulai, muok.nama as nama_unor, mi.kode as kode_instansi, mi.nama as nama_instansi
				FROM
					m_pegawai_unit_kerja_histori h
					LEFT JOIN m_unit_organisasi_kerja muok ON  h.kode_unor =  muok.kode
					LEFT JOIN m_instansi mi ON  muok.kode_instansi = mi.kode WHERE h.tgl_mulai <= '".$tglSelesai."' and m.id = h.id_pegawai
				ORDER BY h.tgl_mulai DESC LIMIT 1
			)
			pukh ON true
			LEFT JOIN LATERAL (
				SELECT h.kode_jabatan, h.tgl_mulai, mjj.nama as nama_jabatan, mjj.urut FROM m_pegawai_jabatan_histori h LEFT JOIN m_jenis_jabatan mjj ON  h.kode_jabatan =  mjj.kode WHERE h.tgl_mulai <=  '".$tglSelesai."' and m.id = h.id_pegawai ORDER BY h.tgl_mulai DESC LIMIT 1
			)
			pjh ON true
			LEFT JOIN LATERAL (
				SELECT h.kode_golongan, h.tgl_mulai, mg.nama as nama_golongan FROM m_pegawai_golongan_histori h LEFT JOIN m_golongan mg ON  h.kode_golongan =  mg.kode WHERE h.tgl_mulai <=  '".$tglSelesai."' and m.id = h.id_pegawai  ORDER BY h.tgl_mulai DESC LIMIT 1
			)
			pgh ON true
			LEFT JOIN LATERAL (
				SELECT h.kode_eselon, h.tgl_mulai, me.nama_eselon FROM m_pegawai_eselon_histori h LEFT JOIN m_eselon me ON  h.kode_eselon =  me.kode WHERE h.tgl_mulai <=  '".$tglSelesai."' and m.id = h.id_pegawai  ORDER BY h.tgl_mulai DESC LIMIT 1
			)
			peh ON true
			LEFT JOIN LATERAL (
				SELECT h.id_rumpun_jabatan, h.tgl_mulai, mrj.nama as nama_rumpun_jabatan FROM m_pegawai_rumpun_jabatan_histori h LEFT JOIN m_rumpun_jabatan mrj ON  h.id_rumpun_jabatan =  mrj.id WHERE h.tgl_mulai <=  '".$tglSelesai."' and m.id = h.id_pegawai  ORDER BY h.tgl_mulai DESC LIMIT 1
			)
			prjh ON true
		where
			pukh.kode_instansi = '".$this->input->get('id_instansi')."' $wherePns
		order by
			pjh.urut,
			peh.kode_eselon,
			pgh.kode_golongan desc,
			m.nip
			");
		$this->dataPegawai	=	$queryPegawai->result();
		//echo $this->db->last_query();
		$i=1;
		foreach($this->dataPegawai as $dataPegawai){


			$hari_ini 		= date($this->input->get("tahun")."-".$this->input->get("bulan")."-01");
			// Tanggal pertama pada bulan ini
			$this->tgl_pertama 	= date('Y-m-01', strtotime($hari_ini));
			// Tanggal terakhir pada bulan ini
			$this->tgl_terakhir 	= date('Y-m-t', strtotime($hari_ini));

			$this->dataLembur .= "<tr><td align='center'>".$i."</td>";
			$this->dataLembur .= "<td>".$dataPegawai->nama."</td>";


			$totalLemburJumlah 			= 0;
			$totalLemburJumlahDiakui 	= 0;

			while (strtotime($this->tgl_pertama) <= strtotime($this->tgl_terakhir )) {


				$queryJumlahLembur	=	$this->db->query("select lembur,lembur_diakui from data_mentah where id_pegawai='".$dataPegawai->id_pegawai."' and tanggal='".$this->tgl_pertama."'");
				$dataHasilLembur	=	$queryJumlahLembur->row();

				if($dataHasilLembur){
					$lemburJumlah = $dataHasilLembur->lembur;
					$lemburJumlahDiakui = $dataHasilLembur->lembur_diakui;
				}
				else{
					$lemburJumlah = "0";
					$lemburJumlahDiakui = "0";
				}
				$lembur = $this->konversi_menit->hitung($lemburJumlah);

				if($lemburJumlah == 0){
					$color="red";
				}
				elseif($lemburJumlah != $lemburJumlahDiakui ){
					$color="red";
				}
				else{
					$color="";
				}

				//$this->dataLembur .= "<td align='center' >".$lemburJumlahDiakui." -- ".$lemburJumlah." -- <span style='color:".$color."'>".sprintf("%02d", $lembur['jam_angka'])." : ".sprintf("%02d",$lembur['menit_angka'])."</span></td>";
				$this->dataLembur .= "<td align='center' ><span style='color:".$color."'>".sprintf("%02d", $lembur['jam_angka'])." : ".sprintf("%02d",$lembur['menit_angka'])."</span></td>";


				$totalLemburJumlah += $lemburJumlah;
				$totalLemburJumlahDiakui += $lemburJumlahDiakui;

				$this->tgl_pertama = date ("Y-m-d", strtotime("+1 days", strtotime($this->tgl_pertama)));
			}


				$jumlahPersen 	= round(($totalLemburJumlah / 1800) * 100);
				if($jumlahPersen > 99){
					$jumlahPersen = 100;
				}
				else{
					$jumlahPersen = "<span style='color:orange;'>".$jumlahPersen."</span>";
				}

			$jumlahLembur			=	$this->konversi_menit->hitung($totalLemburJumlahDiakui);

			$bulan 		=	date('Y-m', strtotime($hari_ini));
			if($bulan =='2018-05' || $bulan =='2018-06'){
				$where	=	"and jenis = 'RAMADHAN'";
			}
			else{
				$where	=	"and jenis = 'BIASA'";
			}

			$queryPersen 	=	$this->db->query("select skor from m_skor_lembur where menit_mulai <='".$totalLemburJumlahDiakui."' and menit_akhir >= '".$totalLemburJumlahDiakui."' $where");
			$dataPersen		=	$queryPersen->row();


			$this->dataLembur .= "<td align='center' ><b>".sprintf("%02d", $jumlahLembur['jam_angka'])." : ".sprintf("%02d", $jumlahLembur['menit_angka'])."</b></td>";
			$this->dataLembur .= "<td align='center'>".$dataPersen->skor."</td>";
			$this->dataLembur .= "</tr>";
			$i++;
		}

		$this->dataLembur .= '</table>';

		$this->bulan 	=	$namaBulan[$this->input->get('bulan')];

		$this->load->view('cetak/lap_absensi_lembur_view');
	}



}
