<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class lap_absensi_lembur_opt extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model(['global_model', 'pegawai_model', 'instansi_model','data_mentah_model','log_laporan_model']);
	}

	function cektanggal($date){
		$dayofweek = date('w', strtotime($date));
		echo date("w", strtotime("2018-02-25"));
	}

	function cekMigrasi(){
		$this->load->library('migrasi_data_opt');
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
				// $this->migrasi_data_opt->cek_ulang_data_mentah($i->format("Y-m-d"), $temp->id, "update", true);
				if($this->migrasi_data_opt->count_data_mentah_pegawai( $i->format("Y-m-d"), $temp->id)->jumlah == 0 ){
					$this->migrasi_data_opt->cek_ulang_data_mentah($i->format("Y-m-d"), $temp->id, "insert", true);
				}
			}
		}
		echo "<h1>SUKKSESSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSS</h1>";

	}

	function cronTabPerhariPerPegawai(){
		$this->load->library('migrasi_data_opt');
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
		foreach($data_pegawai as $temp){
			// $begin = new DateTime( "2018-10-01" );
			// $end   = new DateTime( "2018-11-26" );

			// for($i = $begin; $i <= $end; $i->modify('+1 day')){
				// $this->migrasi_data_opt->cek_ulang_data_mentah($i->format("Y-m-d"), $temp->id, "update", true);
				if($this->migrasi_data_opt->count_data_mentah_pegawai( date("Y-m-d"), $temp->id)->jumlah == 0 ){
					$this->migrasi_data_opt->cek_ulang_data_mentah(date("Y-m-d"), $temp->id, "insert", true);
				}
			// }
		}
		echo "<h1>SUKKSESSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSS</h1>";

	}

	function lanjutkan_crontab_insert(){
		$this->load->library('migrasi_data_opt');
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

				$this->migrasi_data_opt->cek_ulang_data_mentah(date("Y-m-d"), $temp->id, "update", true);

			}
		}
		echo "<h1>SUKKSESSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSS</h1>";

	}

	function UpdateNovemberPegawai(){

		$this->load->library('migrasi_data_opt');
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

			$begin = new DateTime( "2018-12-01 ");
			$end   = new DateTime( date("Y-m-d") );

			for($i = $begin; $i <= $end; $i->modify('+1 day')){

				if($this->migrasi_data_opt->count_data_mentah_pegawai( $i->format("Y-m-d"), $temp->id)->jumlah == 0){
					$this->migrasi_data_opt->cek_ulang_data_mentah($i->format("Y-m-d"), $temp->id, "insert", true);
				}
				else{
					$this->migrasi_data_opt->cek_ulang_data_mentah($i->format("Y-m-d"), $temp->id, "update", true);
				}
			}
		}

		return true;

	}


	function cronTabUpdatePerPegawai(){
		$this->load->library('migrasi_data_opt');
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
				// $this->migrasi_data_opt->cek_ulang_data_mentah($i->format("Y-m-d"), $temp->id, "update", true);
				// if($this->migrasi_data_opt->count_data_mentah_pegawai( $i->format("Y-m-d"), $temp->id)->jumlah == 0 ){
					$this->migrasi_data_opt->cek_ulang_data_mentah($i->format("Y-m-d"), $temp->id, "update", true);
				// }
			}
		}
		echo "<h1>SUKKSESSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSS</h1>";

	}

	function UpdatePerPegawai(){

		$this->load->library('migrasi_data_opt');
		$id_pegawai = $this->input->post('id_pegawai');

		if ($id_pegawai != '') {
			$begin = new DateTime( date("Y-m-d", strtotime("-7 day", strtotime(date("Y-m-d")))) );
			$end   = new DateTime( date("Y-m-d") );

			for($i = $begin; $i <= $end; $i->modify('+1 day')){

				if($this->migrasi_data_opt->count_data_mentah_pegawai( $i->format("Y-m-d"), $id_pegawai)->jumlah == 0){
					$this->migrasi_data_opt->cek_ulang_data_mentah($i->format("Y-m-d"), $id_pegawai, "insert", false);
				}
				else{
					$this->migrasi_data_opt->cek_ulang_data_mentah($i->format("Y-m-d"), $id_pegawai, "update", false);
				}
			}
			//return true;
			echo json_encode(['status' => true]);
		}else{
			echo json_encode(['status' => false]);
		}

	}

	function UpdatePerPegawai2($id_pegawai){

		$this->load->library('migrasi_data_opt');
		// $id_pegawai = $this->input->post('id_pegawai');

		if ($id_pegawai != '') {
			$begin = new DateTime( "2018-01-01 ");
			$end   = new DateTime( "2018-12-31" );

			// $begin = new DateTime( date("Y-m-d", strtotime("-31 day", strtotime(date("Y-m-d")))) );
			// $end   = new DateTime( date("Y-m-d") );

			for($i = $begin; $i <= $end; $i->modify('+1 day')){

				if($this->migrasi_data_opt->count_data_mentah_pegawai( $i->format("Y-m-d"), $id_pegawai)->jumlah == 0){
					$this->migrasi_data_opt->cek_ulang_data_mentah($i->format("Y-m-d"), $id_pegawai, "insert", false);
				}
				else{
					$this->migrasi_data_opt->cek_ulang_data_mentah($i->format("Y-m-d"), $id_pegawai, "update", false);
				}
			}
			//return true;
			echo json_encode(['status' => true]);
		}else{
			echo json_encode(['status' => false]);
		}

	}

	function cekMigrasiInstansi(){
		$this->load->library('migrasi_data_opt');
		$kode 		    = $this->input->get("kode");
		$queryInstansi	= "
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
				 pukh.kode_instansi = '".$kode."' and kode_status_pegawai = '1'";

		$data_pegawai = $this->db->query($queryInstansi)->result();
		foreach($data_pegawai as $temp){
			$tgl = date('Y-m-d');
			$begin = new DateTime( "2018-12-01" );
			$end   = new DateTime( $tgl );

			for($i = $begin; $i <= $end; $i->modify('+1 day')){

				if($this->migrasi_data_opt->count_data_mentah_pegawai( $i->format("Y-m-d"), $temp->id)->jumlah == 0){
					$this->migrasi_data_opt->cek_ulang_data_mentah($i->format("Y-m-d"), $temp->id, "insert", true);
				}
				else{
					$this->migrasi_data_opt->cek_ulang_data_mentah($i->format("Y-m-d"), $temp->id, "update", true);
				}
			}

		}
		echo "<h1>SUKSES</h1>";

	}

	function cekMigrasiKominfo(){
		$this->load->library('migrasi_data_opt');
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
				 pukh.kode_instansi IN ('5.09.00.00.00', '5.09.00.92.00', '5.09.00.91.00')  and kode_status_pegawai = '1'";
				 // 3.02.00.00.00
				 // 6.04.02.00.00 kapasan
				 // 6.01.01.00.00 embong kaliasinn

		$data_pegawai = $this->db->query($queryInstansi)->result();
		foreach($data_pegawai as $temp){
			$begin = new DateTime( "2018-01-01" );
			$end   = new DateTime( "2018-11-01" );

			for($i = $begin; $i <= $end; $i->modify('+1 day')){

				if($this->migrasi_data_opt->count_data_mentah_pegawai( $i->format("Y-m-d"), $temp->id)->jumlah == 0){
					$this->migrasi_data_opt->cek_ulang_data_mentah($i->format("Y-m-d"), $temp->id, "insert", true);
				}
				else{
					$this->migrasi_data_opt->cek_ulang_data_mentah($i->format("Y-m-d"), $temp->id, "update", true);
				}
			}
		}
		echo "<h1>SUKKSESSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSS</h1>";

	}

	function cekMigrasiKominfod(){
		$this->load->library('migrasi_data_opt');
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
				 pukh.kode_instansi ='5.01.00.00.00'";
				 // 3.02.00.00.00
				 // 6.04.02.00.00 kapasan
				 // 6.01.01.00.00 embong kaliasinn

		$data_pegawai = $this->db->query($queryInstansi)->result();
		foreach($data_pegawai as $temp){
			$begin = new DateTime( "2018-01-01" );
			$end   = new DateTime( "2018-12-01" );

			for($i = $begin; $i <= $end; $i->modify('+1 day')){

				if($this->migrasi_data_opt->count_data_mentah_pegawai( $i->format("Y-m-d"), $temp->id)->jumlah == 0){
					$this->migrasi_data_opt->cek_ulang_data_mentah($i->format("Y-m-d"), $temp->id, "insert", true);
				}
				else{
					$this->migrasi_data_opt->cek_ulang_data_mentah($i->format("Y-m-d"), $temp->id, "update", true);
				}
			}
		}
		echo "<h1>SUKKSESSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSS</h1>";

	}

	function cekMigrasiKecamatan(){
		$this->load->library('migrasi_data_opt');
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
				 pukh.kode_instansi = '5.01.00.00.00' ";
				 // 3.02.00.00.00
				 // 6.04.02.00.00 kapasan
				 // 6.01.01.00.00 embong kaliasinn

		$data_pegawai = $this->db->query($queryInstansi)->result();
		foreach($data_pegawai as $temp){
			$begin = new DateTime( "2018-01-01" );
			$end   = new DateTime( "2018-12-01" );

			for($i = $begin; $i <= $end; $i->modify('+1 day')){

				if($this->migrasi_data_opt->count_data_mentah_pegawai( $i->format("Y-m-d"), $temp->id)->jumlah == 0){
					$this->migrasi_data_opt->cek_ulang_data_mentah($i->format("Y-m-d"), $temp->id, "insert", true);
				}
				else{
					$this->migrasi_data_opt->cek_ulang_data_mentah($i->format("Y-m-d"), $temp->id, "update", true);
				}
			}
		}
		echo "<h1>SUKKSESSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSS</h1>";

	}

	function cekMigrasiPerPegawai_update(){
		$this->load->library('migrasi_data_opt');
		$queryInstansi	=	"
			select
				m.id,m.nama, m.nip
			from
				m_pegawai m
			where
				 m.id = 'f982a104-64d2-11e6-b08d-c31b6f674fb3'
		";
		$data_pegawai = $this->db->query($queryInstansi)->result();
		foreach($data_pegawai as $temp){
			$begin = new DateTime( "2018-01-01" );
			$end   = new DateTime( "2018-12-01" );

			for($i = $begin; $i <= $end; $i->modify('+1 day')){
				if($this->migrasi_data_opt->count_data_mentah_pegawai( $i->format("Y-m-d"), $temp->id)->jumlah == 0){
					$this->migrasi_data_opt->cek_ulang_data_mentah($i->format("Y-m-d"), $temp->id, "insert", true);
				}
				else{
					$this->migrasi_data_opt->cek_ulang_data_mentah($i->format("Y-m-d"), $temp->id, "update", true);
				}
			}
		}
		echo "<h1>SUKKSESSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSS</h1>";

	}

	function cekMigrasiSatpol(){
		$this->load->library('migrasi_data_opt');
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
				 pukh.kode_instansi = '5.17.00.00.00' and kode_status_pegawai = '1'";
				 // 3.02.00.00.00
				 // 6.04.02.00.00 kapasan
				 // 6.01.01.00.00 embong kaliasinn

		$data_pegawai = $this->db->query($queryInstansi)->result();
		foreach($data_pegawai as $temp){
			$begin = new DateTime( "2018-03-01" );
			$end   = new DateTime( "2018-04-01" );

			for($i = $begin; $i <= $end; $i->modify('+1 day')){

				if($this->migrasi_data_opt->count_data_mentah_pegawai( $i->format("Y-m-d"), $temp->id)->jumlah == 0){
					$this->migrasi_data_opt->cek_ulang_data_mentah($i->format("Y-m-d"), $temp->id, "insert", true);
				}
				else{
					$this->migrasi_data_opt->cek_ulang_data_mentah($i->format("Y-m-d"), $temp->id, "update", true);
				}
			}
		}
		echo "<h1>SUKKSESSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSS</h1>";

	}

	function cekMigrasiSatpol1(){
		$this->load->library('migrasi_data_opt');
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
				 pukh.kode_instansi = '1.04.01.00.00' and kode_status_pegawai = '1'";
				 // 3.02.00.00.00
				 // 6.04.02.00.00 kapasan
				 // 6.01.01.00.00 embong kaliasinn

		$data_pegawai = $this->db->query($queryInstansi)->result();
		foreach($data_pegawai as $temp){
			$begin = new DateTime( "2018-12-01" );
			$end   = new DateTime( "2018-12-31" );

			for($i = $begin; $i <= $end; $i->modify('+1 day')){

				if($this->migrasi_data_opt->count_data_mentah_pegawai( $i->format("Y-m-d"), $temp->id)->jumlah == 0){
					$this->migrasi_data_opt->cek_ulang_data_mentah($i->format("Y-m-d"), $temp->id, "insert", true);
				}
				else{
					$this->migrasi_data_opt->cek_ulang_data_mentah($i->format("Y-m-d"), $temp->id, "update", true);
				}
			}
		}
		echo "<h1>SUKKSESSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSS</h1>";

	}

	function cekMigrasiSatpol2(){
		$this->load->library('migrasi_data_opt');
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
				 pukh.kode_instansi = '5.19.00.00.00' and kode_status_pegawai = '1'";
				 // 3.02.00.00.00
				 // 6.04.02.00.00 kapasan
				 // 6.01.01.00.00 embong kaliasinn

		$data_pegawai = $this->db->query($queryInstansi)->result();
		foreach($data_pegawai as $temp){
			$begin = new DateTime( "2018-11-16" );
			$end   = new DateTime( "2018-11-30" );

			for($i = $begin; $i <= $end; $i->modify('+1 day')){

				if($this->migrasi_data_opt->count_data_mentah_pegawai( $i->format("Y-m-d"), $temp->id)->jumlah == 0){
					$this->migrasi_data_opt->cek_ulang_data_mentah($i->format("Y-m-d"), $temp->id, "insert", true);
				}
				else{
					$this->migrasi_data_opt->cek_ulang_data_mentah($i->format("Y-m-d"), $temp->id, "update", true);
				}
			}
		}
		echo "<h1>SUKKSESSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSS</h1>";

	}

	// ################################################################################################################
	// CRONTAB SERVER

	function MigrasiPerbagian_insert01(){
		ini_set("max_execution_time", 0);
		$this->load->model('monitoring_tarik_model');
		$this->load->library('migrasi_data_opt');
		$inisial  		  = $this->input->get("inisial");
		$kategori  		  = $this->input->get("kategori");
		$dataInstansi = "select * from m_instansi WHERE
							nama NOT LIKE 'Penghapusan%'
							AND nama NOT LIKE 'Pensiun%'
							AND nama NOT LIKE 'Perusahaan%'
							AND nama NOT LIKE 'SDLB%'
							AND nama NOT LIKE 'SDN %'
							AND nama NOT LIKE 'SLB %'
							AND nama NOT LIKE 'SLB/%'
							AND nama NOT LIKE 'SLB-%'
							AND nama NOT LIKE 'SMA %'
							AND nama NOT LIKE 'SMALB %'
							AND nama NOT LIKE 'SMAN %'
							AND nama NOT LIKE 'SMK %'
							AND nama NOT LIKE 'SMKN %'
							AND nama NOT LIKE 'SMP %'
							AND nama NOT LIKE 'SMPN %'
							AND nama NOT LIKE 'TK %'
							AND nama NOT LIKE 'TKL%' order by nama asc limit 75 offset 0";

		$dataDinas = $this->db->query($dataInstansi)->result();

		foreach($dataDinas as $temp_dinas){
			$kode_dinas = $temp_dinas->kode;
			$nama_dinas = $temp_dinas->nama;
			$queryInstansi	= "
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
									pukh.kode_instansi = '".$kode_dinas."'";

			$data_pegawai = $this->db->query($queryInstansi)->result();

			//INSERT KE t_cron_scheduling
			$tsch['id_upd'] = $temp_dinas->kode;
			$tsch['nama_upd'] = $temp_dinas->nama;
			$tsch['date'] = date('Y-m-d');
			$tsch['start_at'] = date('Y-m-d H:i:s');
			$tsch['status'] = 'N';
			$tsch['running_by'] = 'cron1';
			$this->monitoring_tarik_model->insert($tsch);

			foreach($data_pegawai as $temp){
				if($this->migrasi_data_opt->count_data_mentah_pegawai( date("Y-m-d"), $temp->id)->jumlah == 0 ){
					$this->migrasi_data_opt->cek_ulang_data_mentah( date("Y-m-d"), $temp->id, "insert", false);
				}
			}

			// INSERT KODE DINAS
			echo "<h1>KODE INSTANSI : $kode_dinas </h1> <h1>NAMA INSTANSI : $nama_dinas </h1><br>";

			//UPDATE JIKA FINISH
			$tsch2 = ['status' => 'Y', 'finish_at' => date('Y-m-d H:i:s')];
			$where_upd = ['id_upd' => $temp_dinas->kode, 'status' => 'N'];
			$this->monitoring_tarik_model->update($where_upd, $tsch2);
		}

		echo "<h1>SUKKSESSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSS</h1>";
	}

	function MigrasiPerbagian_insert02(){
		ini_set("max_execution_time", 0);
		$this->load->model('monitoring_tarik_model');
		$this->load->library('migrasi_data_opt');
		$inisial  		  = $this->input->get("inisial");
		$kategori  		  = $this->input->get("kategori");
		$dataInstansi = "select * from m_instansi WHERE
							nama NOT LIKE 'Penghapusan%'
							AND nama NOT LIKE 'Pensiun%'
							AND nama NOT LIKE 'Perusahaan%'
							AND nama NOT LIKE 'SDLB%'
							AND nama NOT LIKE 'SDN %'
							AND nama NOT LIKE 'SLB %'
							AND nama NOT LIKE 'SLB/%'
							AND nama NOT LIKE 'SLB-%'
							AND nama NOT LIKE 'SMA %'
							AND nama NOT LIKE 'SMALB %'
							AND nama NOT LIKE 'SMAN %'
							AND nama NOT LIKE 'SMK %'
							AND nama NOT LIKE 'SMKN %'
							AND nama NOT LIKE 'SMP %'
							AND nama NOT LIKE 'SMPN %'
							AND nama NOT LIKE 'TK %'
							AND nama NOT LIKE 'TKL%'  order by nama asc limit 75 offset 75";

		$dataDinas = $this->db->query($dataInstansi)->result();

		foreach($dataDinas as $temp_dinas){
			$kode_dinas = $temp_dinas->kode;
			$nama_dinas = $temp_dinas->nama;
			$queryInstansi	= "select
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
									pukh.kode_instansi = '".$kode_dinas."'";

			$data_pegawai = $this->db->query($queryInstansi)->result();

			//INSERT KE t_cron_scheduling
			$tsch['id_upd'] = $temp_dinas->kode;
			$tsch['nama_upd'] = $temp_dinas->nama;
			$tsch['date'] = date('Y-m-d');
			$tsch['start_at'] = date('Y-m-d H:i:s');
			$tsch['status'] = 'N';
			$tsch['running_by'] = 'cron2';
			$this->monitoring_tarik_model->insert($tsch);

			foreach($data_pegawai as $temp){
				if($this->migrasi_data_opt->count_data_mentah_pegawai( date("Y-m-d"), $temp->id)->jumlah == 0 ){
					$this->migrasi_data_opt->cek_ulang_data_mentah( date("Y-m-d"), $temp->id, "insert", false);
				}
			}

			// INSERT KODE DINAS
			echo "<h1>KODE INSTANSI : $kode_dinas </h1> <h1>NAMA INSTANSI : $nama_dinas </h1><br>";

			//UPDATE JIKA FINISH
			$tsch2 = ['status' => 'Y', 'finish_at' => date('Y-m-d H:i:s')];
			$where_upd = ['id_upd' => $temp_dinas->kode, 'status' => 'N'];
			$this->monitoring_tarik_model->update($where_upd, $tsch2);
		}

		echo "<h1>SUKKSESSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSS</h1>";
	}

	function MigrasiPerbagian_insert03(){
		ini_set("max_execution_time", 0);
		$this->load->model('monitoring_tarik_model');
		$this->load->library('migrasi_data_opt');
		$inisial  		  = $this->input->get("inisial");
		$kategori  		  = $this->input->get("kategori");
		$dataInstansi = "select * from m_instansi WHERE
							nama NOT LIKE 'Penghapusan%'
							AND nama NOT LIKE 'Pensiun%'
							AND nama NOT LIKE 'Perusahaan%'
							AND nama NOT LIKE 'SDLB%'
							AND nama NOT LIKE 'SDN %'
							AND nama NOT LIKE 'SLB %'
							AND nama NOT LIKE 'SLB/%'
							AND nama NOT LIKE 'SLB-%'
							AND nama NOT LIKE 'SMA %'
							AND nama NOT LIKE 'SMALB %'
							AND nama NOT LIKE 'SMAN %'
							AND nama NOT LIKE 'SMK %'
							AND nama NOT LIKE 'SMKN %'
							AND nama NOT LIKE 'SMP %'
							AND nama NOT LIKE 'SMPN %'
							AND nama NOT LIKE 'TK %'
							AND nama NOT LIKE 'TKL%'  order by nama asc limit 75 offset 150";

		$dataDinas = $this->db->query($dataInstansi)->result();

		foreach($dataDinas as $temp_dinas){
			$kode_dinas = $temp_dinas->kode;
			$nama_dinas = $temp_dinas->nama;
			$queryInstansi	= "select
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
									pukh.kode_instansi = '".$kode_dinas."'";

			$data_pegawai = $this->db->query($queryInstansi)->result();
			//INSERT KE t_cron_scheduling
			$tsch['id_upd'] = $temp_dinas->kode;
			$tsch['nama_upd'] = $temp_dinas->nama;
			$tsch['date'] = date('Y-m-d');
			$tsch['start_at'] = date('Y-m-d H:i:s');
			$tsch['status'] = 'N';
			$tsch['running_by'] = 'cron3';
			$this->monitoring_tarik_model->insert($tsch);

			foreach($data_pegawai as $temp){
				if($this->migrasi_data_opt->count_data_mentah_pegawai( date("Y-m-d"), $temp->id)->jumlah == 0 ){
					$this->migrasi_data_opt->cek_ulang_data_mentah( date("Y-m-d"), $temp->id, "insert", false);
				}
			}

			// INSERT KODE DINAS
			echo "<h1>KODE INSTANSI : $kode_dinas </h1> <h1>NAMA INSTANSI : $nama_dinas </h1><br>";

			//UPDATE JIKA FINISH
			$tsch2 = ['status' => 'Y', 'finish_at' => date('Y-m-d H:i:s')];
			$where_upd = ['id_upd' => $temp_dinas->kode, 'status' => 'N'];
			$this->monitoring_tarik_model->update($where_upd, $tsch2);
		}

		echo "<h1>SUKKSESSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSS</h1>";
	}

	function MigrasiPerbagian_insert04(){
		ini_set("max_execution_time", 0);
		$this->load->model('monitoring_tarik_model');
		$this->load->library('migrasi_data_opt');
		$inisial  		  = $this->input->get("inisial");
		$kategori  		  = $this->input->get("kategori");
		$dataInstansi = "select * from m_instansi WHERE
							nama NOT LIKE 'Penghapusan%'
							AND nama NOT LIKE 'Pensiun%'
							AND nama NOT LIKE 'Perusahaan%'
							AND nama NOT LIKE 'SDLB%'
							AND nama NOT LIKE 'SDN %'
							AND nama NOT LIKE 'SLB %'
							AND nama NOT LIKE 'SLB/%'
							AND nama NOT LIKE 'SLB-%'
							AND nama NOT LIKE 'SMA %'
							AND nama NOT LIKE 'SMALB %'
							AND nama NOT LIKE 'SMAN %'
							AND nama NOT LIKE 'SMK %'
							AND nama NOT LIKE 'SMKN %'
							AND nama NOT LIKE 'SMP %'
							AND nama NOT LIKE 'SMPN %'
							AND nama NOT LIKE 'TK %'
							AND nama NOT LIKE 'TKL%' order by nama asc limit 82 offset 225";

		$dataDinas = $this->db->query($dataInstansi)->result();

		foreach($dataDinas as $temp_dinas){
			$kode_dinas = $temp_dinas->kode;
			$nama_dinas = $temp_dinas->nama;
			$queryInstansi	= "select
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
									pukh.kode_instansi = '".$kode_dinas."'";

			$data_pegawai = $this->db->query($queryInstansi)->result();

			//INSERT KE t_cron_scheduling
			$tsch['id_upd'] = $temp_dinas->kode;
			$tsch['nama_upd'] = $temp_dinas->nama;
			$tsch['date'] = date('Y-m-d');
			$tsch['start_at'] = date('Y-m-d H:i:s');
			$tsch['status'] = 'N';
			$tsch['running_by'] = 'cron4';
			$this->monitoring_tarik_model->insert($tsch);

			foreach($data_pegawai as $temp){
				if($this->migrasi_data_opt->count_data_mentah_pegawai( date("Y-m-d"), $temp->id)->jumlah == 0 ){
					$this->migrasi_data_opt->cek_ulang_data_mentah( date("Y-m-d"), $temp->id, "insert", false);
				}
			}

			// INSERT KODE DINAS
			echo "<h1>KODE INSTANSI : $kode_dinas </h1> <h1>NAMA INSTANSI : $nama_dinas </h1><br>";

			//UPDATE JIKA FINISH
			$tsch2 = ['status' => 'Y', 'finish_at' => date('Y-m-d H:i:s')];
			$where_upd = ['id_upd' => $temp_dinas->kode, 'status' => 'N'];
			$this->monitoring_tarik_model->update($where_upd, $tsch2);
		}
		echo "<h1>SUKKSESSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSS</h1>";
	}

	function MigrasiPerbagian_update01(){
		ini_set("max_execution_time", 0);
		$this->load->model('monitoring_tarik_model');
		$this->load->library('migrasi_data_opt');
		$inisial  		  = $this->input->get("inisial");
		$kategori  		  = $this->input->get("kategori");
		$dataInstansi = "select * from m_instansi WHERE
							nama NOT LIKE 'Penghapusan%'
							AND nama NOT LIKE 'Pensiun%'
							AND nama NOT LIKE 'Perusahaan%'
							AND nama NOT LIKE 'SDLB%'
							AND nama NOT LIKE 'SDN %'
							AND nama NOT LIKE 'SLB %'
							AND nama NOT LIKE 'SLB/%'
							AND nama NOT LIKE 'SLB-%'
							AND nama NOT LIKE 'SMA %'
							AND nama NOT LIKE 'SMALB %'
							AND nama NOT LIKE 'SMAN %'
							AND nama NOT LIKE 'SMK %'
							AND nama NOT LIKE 'SMKN %'
							AND nama NOT LIKE 'SMP %'
							AND nama NOT LIKE 'SMPN %'
							AND nama NOT LIKE 'TK %'
							AND nama NOT LIKE 'TKL%'  order by nama asc limit 75 offset 0";

		$dataDinas = $this->db->query($dataInstansi)->result();

		foreach($dataDinas as $temp_dinas){
			$kode_dinas = $temp_dinas->kode;
			$nama_dinas = $temp_dinas->nama;
			$queryInstansi	= "select
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
									pukh.kode_instansi = '".$kode_dinas."'";

			$data_pegawai = $this->db->query($queryInstansi)->result();

			//INSERT KE t_cron_scheduling
			$tsch['id_upd'] = $temp_dinas->kode;
			$tsch['nama_upd'] = $temp_dinas->nama;
			$tsch['date'] = date('Y-m-d');
			$tsch['start_at'] = date('Y-m-d H:i:s');
			$tsch['status'] = 'N';
			$tsch['running_by'] = 'cron1';
			$this->monitoring_tarik_model->insert($tsch);

			foreach($data_pegawai as $temp){
				if($this->migrasi_data_opt->count_data_mentah_pegawai( date("Y-m-d"), $temp->id)->jumlah == 0 ){
					$this->migrasi_data_opt->cek_ulang_data_mentah( date("Y-m-d"), $temp->id, "insert", false);
				}
				else{
					$this->migrasi_data_opt->cek_ulang_data_mentah( date("Y-m-d"), $temp->id, "update", false);
				}

			}

			// INSERT KODE DINAS
			echo "<h1>KODE INSTANSI : $kode_dinas </h1> <h1>NAMA INSTANSI : $nama_dinas </h1><br>";

			//UPDATE JIKA FINISH
			$tsch2 = ['status' => 'Y', 'finish_at' => date('Y-m-d H:i:s')];
			$where_upd = ['id_upd' => $temp_dinas->kode, 'status' => 'N'];
			$this->monitoring_tarik_model->update($where_upd, $tsch2);
		}

		echo "<h1>SUKKSESSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSS</h1>";
	}

	function MigrasiPerbagian_update02(){
		ini_set("max_execution_time", 0);
		$this->load->model('monitoring_tarik_model');
		$this->load->library('migrasi_data_opt');
		$inisial  		  = $this->input->get("inisial");
		$kategori  		  = $this->input->get("kategori");
		$dataInstansi = "select * from m_instansi WHERE
							nama NOT LIKE 'Penghapusan%'
							AND nama NOT LIKE 'Pensiun%'
							AND nama NOT LIKE 'Perusahaan%'
							AND nama NOT LIKE 'SDLB%'
							AND nama NOT LIKE 'SDN %'
							AND nama NOT LIKE 'SLB %'
							AND nama NOT LIKE 'SLB/%'
							AND nama NOT LIKE 'SLB-%'
							AND nama NOT LIKE 'SMA %'
							AND nama NOT LIKE 'SMALB %'
							AND nama NOT LIKE 'SMAN %'
							AND nama NOT LIKE 'SMK %'
							AND nama NOT LIKE 'SMKN %'
							AND nama NOT LIKE 'SMP %'
							AND nama NOT LIKE 'SMPN %'
							AND nama NOT LIKE 'TK %'
							AND nama NOT LIKE 'TKL%'  order by nama asc limit 75 offset 75";

		$dataDinas = $this->db->query($dataInstansi)->result();

		foreach($dataDinas as $temp_dinas){
			$kode_dinas = $temp_dinas->kode;
			$nama_dinas = $temp_dinas->nama;
			$queryInstansi	= "select
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
									pukh.kode_instansi = '".$kode_dinas."'";

			$data_pegawai = $this->db->query($queryInstansi)->result();

			//INSERT KE t_cron_scheduling
			$tsch['id_upd'] = $temp_dinas->kode;
			$tsch['nama_upd'] = $temp_dinas->nama;
			$tsch['date'] = date('Y-m-d');
			$tsch['start_at'] = date('Y-m-d H:i:s');
			$tsch['status'] = 'N';
			$tsch['running_by'] = 'cron2';
			$this->monitoring_tarik_model->insert($tsch);

			foreach($data_pegawai as $temp){
				if($this->migrasi_data_opt->count_data_mentah_pegawai( date("Y-m-d"), $temp->id)->jumlah == 0 ){
					$this->migrasi_data_opt->cek_ulang_data_mentah( date("Y-m-d"), $temp->id, "insert", false);
				}
				else{
					$this->migrasi_data_opt->cek_ulang_data_mentah( date("Y-m-d"), $temp->id, "update", false);
				}

			}

			// INSERT KODE DINAS
			echo "<h1>KODE INSTANSI : $kode_dinas </h1> <h1>NAMA INSTANSI : $nama_dinas </h1><br>";

			//UPDATE JIKA FINISH
			$tsch2 = ['status' => 'Y', 'finish_at' => date('Y-m-d H:i:s')];
			$where_upd = ['id_upd' => $temp_dinas->kode, 'status' => 'N'];
			$this->monitoring_tarik_model->update($where_upd, $tsch2);
		}

		echo "<h1>SUKKSESSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSS</h1>";
	}

	function MigrasiPerbagian_update03(){
		ini_set("max_execution_time", 0);
		$this->load->model('monitoring_tarik_model');
		$this->load->library('migrasi_data_opt');
		$inisial  		  = $this->input->get("inisial");
		$kategori  		  = $this->input->get("kategori");
		$dataInstansi = "select * from m_instansi WHERE
							nama NOT LIKE 'Penghapusan%'
							AND nama NOT LIKE 'Pensiun%'
							AND nama NOT LIKE 'Perusahaan%'
							AND nama NOT LIKE 'SDLB%'
							AND nama NOT LIKE 'SDN %'
							AND nama NOT LIKE 'SLB %'
							AND nama NOT LIKE 'SLB/%'
							AND nama NOT LIKE 'SLB-%'
							AND nama NOT LIKE 'SMA %'
							AND nama NOT LIKE 'SMALB %'
							AND nama NOT LIKE 'SMAN %'
							AND nama NOT LIKE 'SMK %'
							AND nama NOT LIKE 'SMKN %'
							AND nama NOT LIKE 'SMP %'
							AND nama NOT LIKE 'SMPN %'
							AND nama NOT LIKE 'TK %'
							AND nama NOT LIKE 'TKL%'  order by nama asc limit 75 offset 150";

		$dataDinas = $this->db->query($dataInstansi)->result();

		foreach($dataDinas as $temp_dinas){
			$kode_dinas = $temp_dinas->kode;
			$nama_dinas = $temp_dinas->nama;
			$queryInstansi	= "select
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
									pukh.kode_instansi = '".$kode_dinas."'";

			$data_pegawai = $this->db->query($queryInstansi)->result();

			//INSERT KE t_cron_scheduling
			$tsch['id_upd'] = $temp_dinas->kode;
			$tsch['nama_upd'] = $temp_dinas->nama;
			$tsch['date'] = date('Y-m-d');
			$tsch['start_at'] = date('Y-m-d H:i:s');
			$tsch['status'] = 'N';
			$tsch['running_by'] = 'cron3';
			$this->monitoring_tarik_model->insert($tsch);

			foreach($data_pegawai as $temp){
				if($this->migrasi_data_opt->count_data_mentah_pegawai( date("Y-m-d"), $temp->id)->jumlah == 0 ){
					$this->migrasi_data_opt->cek_ulang_data_mentah( date("Y-m-d"), $temp->id, "insert", false);
				}
				else{
					$this->migrasi_data_opt->cek_ulang_data_mentah( date("Y-m-d"), $temp->id, "update", false);
				}

			}

			// INSERT KODE DINAS
			echo "<h1>KODE INSTANSI : $kode_dinas </h1> <h1>NAMA INSTANSI : $nama_dinas </h1><br>";

			//UPDATE JIKA FINISH
			$tsch2 = ['status' => 'Y', 'finish_at' => date('Y-m-d H:i:s')];
			$where_upd = ['id_upd' => $temp_dinas->kode, 'status' => 'N'];
			$this->monitoring_tarik_model->update($where_upd, $tsch2);
		}

		echo "<h1>SUKKSESSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSS</h1>";
	}

	function MigrasiPerbagian_update04(){
		ini_set("max_execution_time", 0);
		$this->load->model('monitoring_tarik_model');
		$this->load->library('migrasi_data_opt');
		$inisial  		  = $this->input->get("inisial");
		$kategori  		  = $this->input->get("kategori");
		$dataInstansi = "select * from m_instansi WHERE
							nama NOT LIKE 'Penghapusan%'
							AND nama NOT LIKE 'Pensiun%'
							AND nama NOT LIKE 'Perusahaan%'
							AND nama NOT LIKE 'SDLB%'
							AND nama NOT LIKE 'SDN %'
							AND nama NOT LIKE 'SLB %'
							AND nama NOT LIKE 'SLB/%'
							AND nama NOT LIKE 'SLB-%'
							AND nama NOT LIKE 'SMA %'
							AND nama NOT LIKE 'SMALB %'
							AND nama NOT LIKE 'SMAN %'
							AND nama NOT LIKE 'SMK %'
							AND nama NOT LIKE 'SMKN %'
							AND nama NOT LIKE 'SMP %'
							AND nama NOT LIKE 'SMPN %'
							AND nama NOT LIKE 'TK %'
							AND nama NOT LIKE 'TKL%'  order by nama asc limit 81 offset 225";

		$dataDinas = $this->db->query($dataInstansi)->result();

		foreach($dataDinas as $temp_dinas){
			$kode_dinas = $temp_dinas->kode;
			$nama_dinas = $temp_dinas->nama;
			$queryInstansi	= "select
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
									pukh.kode_instansi = '".$kode_dinas."'";

			$data_pegawai = $this->db->query($queryInstansi)->result();

			//INSERT KE t_cron_scheduling
			$tsch['id_upd'] = $temp_dinas->kode;
			$tsch['nama_upd'] = $temp_dinas->nama;
			$tsch['date'] = date('Y-m-d');
			$tsch['start_at'] = date('Y-m-d H:i:s');
			$tsch['status'] = 'N';
			$tsch['running_by'] = 'cron4';
			$this->monitoring_tarik_model->insert($tsch);

			foreach($data_pegawai as $temp){
				if($this->migrasi_data_opt->count_data_mentah_pegawai( date("Y-m-d"), $temp->id)->jumlah == 0 ){
					$this->migrasi_data_opt->cek_ulang_data_mentah( date("Y-m-d"), $temp->id, "insert", false);
				}
				else{
					$this->migrasi_data_opt->cek_ulang_data_mentah( date("Y-m-d"), $temp->id, "update", false);
				}

			}

			// INSERT KODE DINAS
			echo "<h1>KODE INSTANSI : $kode_dinas </h1> <h1>NAMA INSTANSI : $nama_dinas </h1><br>";

			//UPDATE JIKA FINISH
			$tsch2 = ['status' => 'Y', 'finish_at' => date('Y-m-d H:i:s')];
			$where_upd = ['id_upd' => $temp_dinas->kode, 'status' => 'N'];
			$this->monitoring_tarik_model->update($where_upd, $tsch2);
		}

		echo "<h1>SUKKSESSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSS</h1>";
	}

	//tambahan lembur 09-12-2018
	function MigrasiPerbagian_InsertManual(){
		if ($this->input->post('tgl_mulai') == "" || $this->input->post('tgl_akhir') == "") {
			echo json_encode([
				'status' => 'gagal',
				'pesan' => 'Mohon Isi tanggal terlebih dahulu'
			]);
		}else{
			//ini_set("max_execution_time", 0);
			$this->load->model('monitoring_tarik_model');
			$this->load->library('migrasi_data_opt');

			$tgl_mulai   = $this->input->post('tgl_mulai');
			$tgl_selesai = $this->input->post('tgl_akhir');
			$instansiRaw 	  = $this->input->post("id_instansi");
			//$instansi = str_replace("-", ".", $instansiRaw);

			$tmulai = explode('/', $tgl_mulai);
			$thingga = explode('/', $tgl_selesai);
			$akhir =	$thingga[2]."-".$thingga[1]."-".$thingga[0];
			$mulai =	$tmulai[2]."-".$tmulai[1]."-".$tmulai[0];

			$dataInstansi = "select * from m_instansi where kode = '".$instansiRaw."' order by nama asc limit 1 offset 0";
			$dataDinas = $this->db->query($dataInstansi)->row();
			$kode_dinas = $dataDinas->kode;
			// 	$nama_dinas = $temp_dinas->nama;
			$queryInstansi	= "
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
									pukh.kode_instansi = '".$kode_dinas."'";

			$data_pegawai = $this->db->query($queryInstansi)->result();

			//INSERT KE t_cron_scheduling
			$tsch['id_upd'] = $instansiRaw;
			$tsch['nama_upd'] = $dataDinas->nama;
			$tsch['date'] = date('Y-m-d');
			$tsch['start_at'] = date('Y-m-d H:i:s');
			$tsch['status'] = 'N';
			$tsch['running_by'] = 'manual';
			$this->monitoring_tarik_model->insert($tsch);

			foreach($data_pegawai as $temp){
				$begin = new DateTime($mulai);
				$end   = new DateTime($akhir);

				for($i = $begin; $i <= $end; $i->modify('+1 day')){
					if($this->migrasi_data_opt->count_data_mentah_pegawai( date("Y-m-d"), $temp->id)->jumlah == 0 ){
						$this->migrasi_data_opt->cek_ulang_data_mentah( $i->format("Y-m-d"), $temp->id, "insert", false);
					}
					else{
						$this->migrasi_data_opt->cek_ulang_data_mentah( $i->format("Y-m-d"), $temp->id, "update", false);
					}
				}

			}

			//UPDATE JIKA FINISH
			$tsch2 = array(
		        'status' => 'Y',
		        'finish_at' => date('Y-m-d H:i:s')
			);

			$where_upd = array(
		        'id_upd' => $instansiRaw,
		        'status' => 'N'
			);

			$this->monitoring_tarik_model->update($where_upd, $tsch2);
			echo json_encode([
				'status' => 'sukses'
			]);
		}

	}
	//end tambahan lembur 09-12-2018

	//via url (fungsi update semua pegawai instansi dari nov sampai 15 des 2018)
	function cek_migrasi_instansi($instansi){
		$this->load->library('migrasi_data_opt');
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
				 pukh.kode_instansi = '".$instansi."'";
				 // 3.02.00.00.00
				 // 6.04.02.00.00 kapasan
				 // 6.01.01.00.00 embong kaliasinn

		$data_pegawai = $this->db->query($queryInstansi)->result();
		echo "######################### START KODE INSTANSI : ".$temp_dinas->nama." ########################### \n";
		foreach($data_pegawai as $temp){
			$begin = new DateTime( "2018-11-01" );
			$end   = new DateTime( "2018-12-15" );

			for($i = $begin; $i <= $end; $i->modify('+1 day')){

				if($this->migrasi_data_opt->count_data_mentah_pegawai( $i->format("Y-m-d"), $temp->id)->jumlah == 0){
					$this->migrasi_data_opt->cek_ulang_data_mentah($i->format("Y-m-d"), $temp->id, "insert", true);
				}
				else{
					$this->migrasi_data_opt->cek_ulang_data_mentah($i->format("Y-m-d"), $temp->id, "update", true);
				}
			}
		}
		// INSERT KODE DINAS
		echo "------------------------ END KODE INSTANSI : ".$temp_dinas->nama." ------------------------------ \n";

	}

	//tambahan lembur 09-12-2018
	function MigrasiPerbagian_UpdateManual(){
		ini_set("max_execution_time", 0);
		$this->load->library('migrasi_data_opt');
		$inisial  		  = $this->input->get("inisial");
		$kategori  		  = $this->input->get("kategori");
		$instansiRaw 	  = $this->input->get("instansi");
		$instansi = str_replace("-", ".", $instansiRaw);

		$dataInstansi = "select * from m_instansi where kode = '".$instansi."' order by nama asc limit 1 offset 0";

		$dataDinas = $this->db->query($dataInstansi)->result();

		foreach($dataDinas as $temp_dinas){
			$kode_dinas = $temp_dinas->kode;
			$nama_dinas = $temp_dinas->nama;
			$queryInstansi	= "select
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
									pukh.kode_instansi = '".$kode_dinas."'";

		$data_pegawai = $this->db->query($queryInstansi)->result();
		//var_dump($data_pegawai);exit;
			foreach($data_pegawai as $temp){
				if($this->migrasi_data_opt->count_data_mentah_pegawai( date("Y-m-d"), $temp->id)->jumlah == 0 ){
					$this->migrasi_data_opt->cek_ulang_data_mentah( date("Y-m-d"), $temp->id, "insert", true);
				}
				else{
					$this->migrasi_data_opt->cek_ulang_data_mentah( date("Y-m-d"), $temp->id, "update", true);
				}

			}
			// INSERT KODE DINAS
			echo "<h1>KODE INSTANSI : $kode_dinas </h1> <h1>NAMA INSTANSI : $nama_dinas </h1><br>";
		}

		echo "<h1>SUKKSESSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSS</h1>";
	}
	//end tambahan lembur 09-12-2018

	function MigrasiPerbagian_InsertManualPns(){
		if ($this->input->post('tgl_mulai_pns') == "" || $this->input->post('tgl_akhir_pns') == "") {
			echo json_encode([
				'status' => 'gagal',
				'pesan' => 'Mohon Isi tanggal terlebih dahulu'
			]);
		}else{
			//ini_set("max_execution_time", 0);
			$this->load->model('monitoring_tarik_model');
			$this->load->library('migrasi_data_opt');

			$tgl_mulai   = $this->input->post('tgl_mulai_pns');
			$tgl_selesai = $this->input->post('tgl_akhir_pns');
			$instansiRaw 	  = $this->input->post("id_instansi_pns");
			//$instansi = str_replace("-", ".", $instansiRaw);

			$tmulai = explode('/', $tgl_mulai);
			$thingga = explode('/', $tgl_selesai);
			$akhir =	$thingga[2]."-".$thingga[1]."-".$thingga[0];
			$mulai =	$tmulai[2]."-".$tmulai[1]."-".$tmulai[0];

			$dataInstansi = "select * from m_instansi where kode = '".$instansiRaw."' order by nama asc limit 1 offset 0";
			$dataDinas = $this->db->query($dataInstansi)->row();
			$kode_dinas = $dataDinas->kode;
			// 	$nama_dinas = $temp_dinas->nama;
			$queryInstansi	= "
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
									pukh.kode_instansi = '".$kode_dinas."'
									AND m.kode_status_pegawai != '5'
								";

			$data_pegawai = $this->db->query($queryInstansi)->result();

			//INSERT KE t_cron_scheduling
			$tsch['id_upd'] = $instansiRaw;
			$tsch['nama_upd'] = $dataDinas->nama;
			$tsch['date'] = date('Y-m-d');
			$tsch['start_at'] = date('Y-m-d H:i:s');
			$tsch['status'] = 'N';
			$tsch['running_by'] = 'manual-pns';
			$this->monitoring_tarik_model->insert($tsch);

			foreach($data_pegawai as $temp){
				$begin = new DateTime($mulai);
				$end   = new DateTime($akhir);

				for($i = $begin; $i <= $end; $i->modify('+1 day')){
					if($this->migrasi_data_opt->count_data_mentah_pegawai( date("Y-m-d"), $temp->id)->jumlah == 0 ){
						$this->migrasi_data_opt->cek_ulang_data_mentah( $i->format("Y-m-d"), $temp->id, "insert", false);
					}
					else{
						$this->migrasi_data_opt->cek_ulang_data_mentah( $i->format("Y-m-d"), $temp->id, "update", false);
					}
				}

			}

			//UPDATE JIKA FINISH
			$tsch2 = array(
		        'status' => 'Y',
		        'finish_at' => date('Y-m-d H:i:s')
			);

			$where_upd = array(
		        'id_upd' => $instansiRaw,
		        'status' => 'N'
			);

			$this->monitoring_tarik_model->update($where_upd, $tsch2);
			echo json_encode([
				'status' => 'sukses'
			]);
		}

	}

	function MigrasiPerbagian_InsertManualOs(){
		if ($this->input->post('tgl_mulai_os') == "" || $this->input->post('tgl_akhir_os') == "") {
			echo json_encode([
				'status' => 'gagal',
				'pesan' => 'Mohon Isi tanggal terlebih dahulu'
			]);
		}else{
			//ini_set("max_execution_time", 0);
			$this->load->model('monitoring_tarik_model');
			$this->load->library('migrasi_data_opt');

			$tgl_mulai   = $this->input->post('tgl_mulai_os');
			$tgl_selesai = $this->input->post('tgl_akhir_os');
			$instansiRaw 	  = $this->input->post("id_instansi_os");
			//$instansi = str_replace("-", ".", $instansiRaw);

			$tmulai = explode('/', $tgl_mulai);
			$thingga = explode('/', $tgl_selesai);
			$akhir =	$thingga[2]."-".$thingga[1]."-".$thingga[0];
			$mulai =	$tmulai[2]."-".$tmulai[1]."-".$tmulai[0];

			$dataInstansi = "select * from m_instansi where kode = '".$instansiRaw."' order by nama asc limit 1 offset 0";
			$dataDinas = $this->db->query($dataInstansi)->row();
			$kode_dinas = $dataDinas->kode;
			// 	$nama_dinas = $temp_dinas->nama;
			$queryInstansi	= "
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
									pukh.kode_instansi = '".$kode_dinas."'
									AND m.kode_status_pegawai != '5'
								";

			$data_pegawai = $this->db->query($queryInstansi)->result();

			//INSERT KE t_cron_scheduling
			$tsch['id_upd'] = $instansiRaw;
			$tsch['nama_upd'] = $dataDinas->nama;
			$tsch['date'] = date('Y-m-d');
			$tsch['start_at'] = date('Y-m-d H:i:s');
			$tsch['status'] = 'N';
			$tsch['running_by'] = 'manual-os';
			$this->monitoring_tarik_model->insert($tsch);

			foreach($data_pegawai as $temp){
				$begin = new DateTime($mulai);
				$end   = new DateTime($akhir);

				for($i = $begin; $i <= $end; $i->modify('+1 day')){
					if($this->migrasi_data_opt->count_data_mentah_pegawai( date("Y-m-d"), $temp->id)->jumlah == 0 ){
						$this->migrasi_data_opt->cek_ulang_data_mentah( $i->format("Y-m-d"), $temp->id, "insert", false);
					}
					else{
						$this->migrasi_data_opt->cek_ulang_data_mentah( $i->format("Y-m-d"), $temp->id, "update", false);
					}
				}

			}

			//UPDATE JIKA FINISH
			$tsch2 = array(
		        'status' => 'Y',
		        'finish_at' => date('Y-m-d H:i:s')
			);

			$where_upd = array(
		        'id_upd' => $instansiRaw,
		        'status' => 'N'
			);

			$this->monitoring_tarik_model->update($where_upd, $tsch2);
			echo json_encode([
				'status' => 'sukses'
			]);
		}

	}

	function GeneratePerPegawaiManual()
	{
		$this->load->library('migrasi_data_opt');
		$id_pegawai = $this->input->post('id_pegawai');
		$tgl_mulai   = $this->input->post('tgl_mulai_peg');
		$tgl_selesai = $this->input->post('tgl_akhir_peg');
		$instansiRaw 	  = $this->input->post("id_instansi_peg");
		//$instansi = str_replace("-", ".", $instansiRaw);

		$where         = "id = '".$id_pegawai."' ";
		$dt_pegawai = $this->pegawai_model->getData($where);

		$tmulai = explode('/', $tgl_mulai);
		$thingga = explode('/', $tgl_selesai);
		$akhir =	$thingga[2]."-".$thingga[1]."-".$thingga[0];
		$mulai =	$tmulai[2]."-".$tmulai[1]."-".$tmulai[0];

		if ($id_pegawai != '') {
			$begin = new DateTime($mulai);
			$end   = new DateTime($akhir);

			$query_lembur	= "
				SELECT tgl_lembur::date as tanggal, to_char(jam_lembur_awal,'HH24:MI') as jam_lembur_awal, to_char(jam_lembur_akhir,'HH24:MI') as jam_lembur_akhir
				FROM t_lembur_pegawai
				WHERE t_lembur_pegawai.is_delete = 0
				AND id_pegawai = '$id_pegawai'
				AND tgl_lembur::date >= '$mulai' AND tgl_lembur::date <= '$akhir'
				order by tanggal asc
			";

			$data_lembur = $this->db->query($query_lembur)->result_array();
			$h_lembur = array();
			foreach ($data_lembur as $l) {
				$h_lembur[$l['tanggal']][] = $l;
			}

			$query_izin	= "
				SELECT tgl::date as tanggal, extract(dow from tgl) as hari_tgl, mjic.kode, mjic.nama
				FROM generate_series('$mulai', '$akhir', '1 day'::interval) tgl
				JOIN t_ijin_cuti_pegawai as ticp ON tgl::date >= ticp.tgl_mulai::date AND tgl::date <= ticp.tgl_selesai::date
				JOIN m_jenis_ijin_cuti as mjic ON ticp.id_jenis_ijin_cuti = mjic.id
				WHERE ticp.is_delete = 0 AND
				ticp.id_pegawai = '$id_pegawai'
				order by tanggal asc
			";

			$data_izin = $this->db->query($query_izin)->result_array();
			$h_izin = array();
			foreach ($data_izin as $i) {
				$h_izin[$i['tanggal']][] = $i;
			}

			$query_jadwal	= "
				SELECT tgl::date as tanggal, extract(dow from tgl) as hari_tgl, rjkh.tgl_mulai, jk.id as id_jam_kerja, jk.nama as nama_role_jam_kerja, rjkd.id_hari, jk.jam_mulai_scan_masuk, jk.jam_akhir_scan_masuk, jk.jam_mulai_scan_pulang, jk.jam_akhir_scan_pulang, jk.jam_masuk, jk.jam_pulang, jk.toleransi_terlambat, jk.toleransi_pulang_cepat, jk.masuk_hari_sebelumnya, jk.pulang_hari_berikutnya , shl.id as id_hari_libur, shl.keterangan, jkr.id as jkr_id_jam_kerja, jr.kode as jkr_kode_role_jam_kerja, jkr.nama as jkr_nama_role_jam_kerja, jkr.jam_mulai_scan_masuk as jkr_jam_mulai_scan_masuk, jkr.jam_akhir_scan_masuk as jkr_jam_akhir_scan_masuk, jkr.jam_mulai_scan_pulang as jkr_jam_mulai_scan_pulang, jkr.jam_akhir_scan_pulang as jkr_jam_akhir_scan_pulang, jkr.jam_masuk as jkr_jam_masuk, jkr.jam_pulang as jkr_jam_pulang, jkr.toleransi_terlambat as jkr_toleransi_terlambat, jkr.toleransi_pulang_cepat as jkr_toleransi_pulang_cepat, jkr.masuk_hari_sebelumnya as jkr_masuk_hari_sebelumnya, jkr.pulang_hari_berikutnya as jkr_pulang_hari_berikutnya
				FROM generate_series('$mulai', '$akhir', '1 day'::interval) tgl
				LEFT JOIN LATERAL (
					SELECT tgl_mulai, id_role_jam_kerja
				    FROM m_pegawai_role_jam_kerja_histori
				    WHERE tgl_mulai <= tgl AND id_pegawai = '$id_pegawai'
				    ORDER BY tgl_mulai DESC
				    LIMIT 1
				) rjkh ON TRUE
				LEFT JOIN m_role_jam_kerja_detail as rjkd ON rjkh.id_role_jam_kerja = rjkd.id_role AND extract(dow from tgl) = rjkd.id_hari
				LEFT JOIN m_jam_kerja as jk ON rjkd.id_jam_kerja = jk.id
				LEFT JOIN s_hari_libur as shl ON tgl = shl.tanggal
				LEFT JOIN t_roster as tr ON tr.id_pegawai = '$id_pegawai' AND tr.tanggal = tgl
				LEFT JOIN m_jenis_roster as jr ON tr.id_jenis_roster = jr.id
				LEFT JOIN m_jam_kerja as jkr ON jr.id_jam_kerja = jkr.id
				order by tgl
			";

			$data_jadwal = $this->db->query($query_jadwal)->result_array();
			$h_jadwal = array();
			foreach ($data_jadwal as $j) {
				$h_jadwal[$j['tanggal']] = $j;
			}

			for($i = $begin; $i <= $end; $i->modify('+1 day')){
				if(isset($h_lembur[$i->format("Y-m-d")][0])) {
					$lbr = $h_lembur[$i->format("Y-m-d")][0];
				}
				else {
					$lbr = null;
				}
				if(isset($h_izin[$i->format("Y-m-d")][0])){
					$iz = $h_izin[$i->format("Y-m-d")][0];
				}
				else {
					$iz = null;
				}
				if(isset($h_jadwal[$i->format("Y-m-d")])){
					$jdwl = $h_jadwal[$i->format("Y-m-d")];
				}
				else {
					$jdwl = null;
				}
				if($this->migrasi_data_opt->count_data_mentah_pegawai2( $i->format("Y-m-d"), $id_pegawai)){
					$this->migrasi_data_opt->cek_ulang_data_mentah($i->format("Y-m-d"), $id_pegawai, "update", false, $lbr, $iz, $jdwl, $dt_pegawai->meninggal, $dt_pegawai->tgl_meninggal);
				}
				else{
					$this->migrasi_data_opt->cek_ulang_data_mentah($i->format("Y-m-d"), $id_pegawai, "insert", false, $lbr, $iz, $jdwl, $dt_pegawai->meninggal, $dt_pegawai->tgl_meninggal);
				}
			}
			//return true;
			//$this->output->enable_profiler(TRUE);
			echo json_encode(['status' => true, 'pesan' => 'Sukses Generate per pegawai']);
		}else{
			echo json_encode(['status' => false, 'pesan' => 'Gagal Generate per pegawai']);
		}
	}

	 function ambil_selisih_menit($date_mulai, $date_akhir){
        $masuk         = strtotime($date_mulai);
        $pulang        = strtotime($date_akhir);
        $menitLembur   = round(abs($pulang - $masuk) / 60,2);
        return $menitLembur;
  	}

  	//=============FUNGSI INI DIPAKE DI MONITORING GENERATE DATA=======
	function get_pegawai_garbos($jenis="all") {
		if ($this->input->post('tgl_mulai') == "" || $this->input->post('tgl_akhir') == "")
		{
			$ret = array(
				'status' => 'gagal',
				'pesan' => 'Mohon Isi tanggal terlebih dahulu'
			);
		}
		else
		{
			$this->load->model('antrian_generate_model');
			$where = "finish_at is null";

			$cek = $this->antrian_generate_model->getDataWhere($where);

			$generate = false;
			//limit antrian = 10
			if(count($cek) < 10 && isset($cek)) {
				$generate = true;
			}
			else {
				if($this->ambil_selisih_menit(date('Y-m-d H:i:s'), $cek[0]['start_at']) > 60) {
					$dt_update = array(
						'keterangan' => 'Kemungkinan Proses Dihentikan User',
						'finish_at'  => date('Y-m-d H:i:s')
					);

					$dt_where = "id_user = '".$cek[0]['id_user']."' and kode_instansi = '".$cek[0]['kode_instansi']."' and finish_at is null";

					$this->antrian_generate_model->update($dt_where, $dt_update);
					$generate = true;
				}
			}

			if ($this->session->userdata('username') == 'rizky') {
				$generate = true;
			}

			if($generate) {
				$this->load->model('monitoring_tarik_model');
				$tgl_mulai   = $this->input->post('tgl_mulai');
				$tgl_selesai = $this->input->post('tgl_akhir');
				$dispendik_case = "";

				if ($this->input->post("id_instansi") == '5.09.00.00.00') {
					$instansiRaw = '5.09.00.93.00';
				}else{
					$instansiRaw = $this->input->post("id_instansi");
				}

				$tmulai  = explode('/', $tgl_mulai);
				$thingga = explode('/', $tgl_selesai);
				$akhir   = $thingga[2]."-".$thingga[1]."-".$thingga[0];
				$mulai   = $tmulai[2]."-".$tmulai[1]."-".$tmulai[0];

				if ($jenis == 'pns') {
					$whereStatus = "and m.kode_status_pegawai != '5'";
					$dispendik_case = "and h.excel = 't'";
				}elseif ($jenis == 'os'){
					$whereStatus = "and m.kode_status_pegawai = '5'";
				}else{
					$whereStatus = "";
				}

				$dataInstansi  = "select * from m_instansi where kode = '".$instansiRaw."' order by nama asc limit 1 offset 0";
				$dataDinas     = $this->db->query($dataInstansi)->row();
				$kode_dinas    = $dataDinas->kode;
				if ($instansiRaw == '5.09.00.93.00') {
					$queryInstansi = "
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
							LEFT JOIN m_instansi mi ON  muok.kode_instansi = mi.kode WHERE h.tgl_mulai <= '".date('Y-m-d')."' and m.id = h.id_pegawai ".$dispendik_case."
							ORDER BY h.tgl_mulai DESC LIMIT 1
							)
							pukh ON true
						where
							pukh.kode_instansi = '".$instansiRaw."'".$whereStatus."
					";
				}else{
					$queryInstansi = "
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
							pukh.kode_instansi = '".$instansiRaw."'".$whereStatus."
					";
				}
				$data_pegawai = $this->db->query($queryInstansi)->result_array();

				//INSERT KE t_cron_scheduling
				$tsch['id_upd']     = $instansiRaw;
				$tsch['nama_upd']   = $dataDinas->nama;
				$tsch['date']       = date('Y-m-d');
				$tsch['start_at']   = date('Y-m-d H:i:s');
				$tsch['status']     = 'N';
				$tsch['running_by'] = 'manual';
				$this->monitoring_tarik_model->insert($tsch);

				//INSERT KE antrian_generate
				$dt_ins['id_user']       = $this->session->userdata('id_karyawan');
				$dt_ins['kode_instansi'] = $instansiRaw;
				$dt_ins['nama_instansi'] = $dataDinas->nama;
				$dt_ins['start_at']      = date('Y-m-d H:i:s');
				$dt_ins['keterangan']    = '-';
				if ($this->session->userdata('username') != 'rizky') {
					$this->antrian_generate_model->insert($dt_ins);
				}
				
				$ret = array(
					'status'      		=> 'sukses',
					'pesan'       		=> $data_pegawai,
					'kd_instansi' 		=> $instansiRaw,
					'tgl_mulai'   		=> $tgl_mulai,
					'tgl_selesai' 		=> $tgl_selesai,
					'id_user_upd' 		=> $dt_ins['id_user'],
					'kode_instansi_upd' => $dt_ins['kode_instansi'],
					'start_at_upd'		=> $dt_ins['start_at']
				);
			}
			else {
				$ret = array(
					'status' => 'antrian',
					'pesan'  => $cek
				);
			}

			echo json_encode($ret);
		}
	}

	function update_selesai(){
		$this->load->model('antrian_generate_model');
		$this->load->model('monitoring_tarik_model');
		$id_user   	   = $this->input->post('id_user');
		$kode_instansi = $this->input->post('kode_instansi');
		$start_at	   = $this->input->post("start_at");

		$dt_update = array(
			'finish_at'  => date('Y-m-d H:i:s')
		);

		$dt_where = "id_user = '".$id_user."' and kode_instansi = '".$kode_instansi."' and start_at = '".$start_at."'";

		$this->antrian_generate_model->update($dt_where, $dt_update);

		//UPDATE T CRON SCHEDULER
		$tsch2 = array( 'status' => 'Y', 'finish_at' => date('Y-m-d H:i:s') );
		$where_upd = array( 'id_upd' => $kode_instansi, 'status' => 'N' );
		$this->monitoring_tarik_model->update($where_upd, $tsch2);

		$ret = array(
			'status' => 'sukses',
			'pesan'  => 'Sukses'
		);
	}
	//==================================================================


	//=========START FUNGSI GENERATE LAPORAN ============================
	public function generate() {
        $this->load->library('konversi_menit');

        /** CEK APAKAH ADA LAPORAN SUDAH DIKUNCI */
		$whereTahunBulan = $this->input->get('tahun') . '-' . $this->input->get('bulan');
		$id_instansi_get = $this->input->get('id_instansi');

		$laporanTerkunci	= $this->db->query("
			select * from log_laporan
			where to_char(tgl_log, 'YYYY-MM') = '$whereTahunBulan'
			and kd_instansi = '$id_instansi_get'
			and is_kunci = 'Y'
		")->row_array();

		if($laporanTerkunci) {
			$ret = array(
				'status' => 'gagal',
				'pesan' => 'Maaf, Laporan telah terkunci.'
			);

			echo json_encode($ret);

			return;
		}
		#END

		/** CEK APAKAH PERNAH PRINT LAPORAN */
		$bulan_get = $this->input->get('bulan') ? $this->input->get('bulan') : 0;
		$tahun_get = $this->input->get('tahun') ? $this->input->get('tahun') : '';
		$id_instansi_get = $this->input->get('id_instansi') ? $this->input->get('id_instansi') : '';
		$pns_get = $this->input->get('pns_get') ? $this->input->get('pns_get') : '';
		$queryCekSudahPrintLaporan	=	$this->db->query("
            select * from lap_absensi_lembur
            where bulan = '$bulan_get'
            and tahun = '$tahun_get'
			and id_instansi = '$id_instansi_get'
			and pns = '$pns_get'
            and deleted_at is null
		");

		$this->load->model(['Lap_absensi_lembur_model', 'Lap_absensi_lembur_detil_model']);
		if($this->input->get("pns_get") == 'y'){
            $wherePns 	= " and m.kode_status_pegawai !='5'";
        }
        else{

            $wherePns 	= " and m.kode_status_pegawai ='5'";
        }

        $query_kode_sik = $this->db->query("select kode_sik, nama from m_instansi where kode = '".$this->input->get('id_instansi')."'");
        $data_kode_sik = $query_kode_sik->row();

        if (substr($data_kode_sik->nama, 0, 9) != 'Kecamatan') {
            $kode_instansi_all = $this->input->get('id_instansi');
            $whereQuery = "pukh.kode_instansi = '".$kode_instansi_all."'".$wherePns;

        }else{
            $kode_instansi_all = substr($this->input->get('id_instansi'), 0, 5);
            $whereQuery = "pukh.kode_instansi LIKE '".$kode_instansi_all.'%'."'".$wherePns;
        }

        $tanggal	=	$tahun_get."-".$bulan_get."-01";
        $tglSelesai 	= date('Y-m-t', strtotime($tanggal));

        $tanggal2	=	"01/".$bulan_get."/".$tahun_get;
        $tglSelesai2 	= date('t/m/Y', strtotime($tanggal));

		$queryPegawai 	=	$this->db->query("
            select
                m.id as id_pegawai,m.nama, m.nip,
                pukh.nama_unor,
                pukh.nama_instansi,
                pjh.nama_jabatan, pjh.urut,
                pgh.nama_golongan,
                peh.nama_eselon,
                prjh.nama_rumpun_jabatan,
                pjh.urut
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
                ".$whereQuery."
            order by
                pjh.urut,
                peh.kode_eselon,
                pgh.kode_golongan desc,
                m.nip
        ");
        $data_pegawai	=	$queryPegawai->result();
        //echo $this->db->last_query();

        /** CEK APAKAH ADA PROSES GENERATE DI USER LAINNYA */
        $cek = $this->cek_proses_gen_user_lain($bulan_get, $tahun_get, $id_instansi_get, $pns_get, $data_pegawai);
        if($cek['status'])
        {
        	$ret = array(
				'status' => 'antri',
				'pesan'  => $cek['pesan'],
				'uri' => $cek['uri'],
				'data_generate' => $cek['data_generate'],
				'jml_pegawai' => $cek['jml_pegawai'],
				'jml_tergenerate' => $cek['jml_tergenerate']
			);
        }
        else
        {
			if(! $queryCekSudahPrintLaporan->row())
			{
				$ret = array(
					'status' => 'gagal',
					'pesan' => 'Laporan Absensi Lembur belum pernah dibuat. Silahkan Klik Tampilkan'
				);
			}
			else
			{
	            $dt_ins['id_user']       = $this->session->userdata('id_karyawan');
				$dt_ins['kode_instansi'] = $id_instansi_get;
				$dt_ins['start_at']      = date('Y-m-d H:i:s');

				/** UPDATE IS_DELETE JADI NULL */
				$where = [
					'bulan'			=> $bulan_get,
					'tahun'			=> $tahun_get,
					'id_instansi'	=> $id_instansi_get,
					'pns'			=> $pns_get,
					'deleted_at'	=> null,
				];

				$this->Lap_absensi_lembur_model->update($where, ['deleted_at' => date('Y-m-d H:i:s')]);
				$this->Lap_absensi_lembur_detil_model->update($where, ['deleted_at' => date('Y-m-d H:i:s')]);

				/** INSERT KE LAP_ABSENSI_LEMBUR */
	            $data_absensi_lembur = [
					'bulan'			=> $bulan_get,
					'tahun'			=> $tahun_get,
					'id_instansi'	=> $id_instansi_get,
					'pns'			=> $pns_get,
					'id_pegawai'	=> $this->session->userdata('id_karyawan'),
				];

	            $this->Lap_absensi_lembur_model->insert($data_absensi_lembur);
				#end

	            $ret = array(
					'status'      		=> 'sukses',
					'pesan'       		=> $data_pegawai,
					'pns'				=> $pns_get,
					'kd_instansi' 		=> $id_instansi_get,
					'bulan'				=> $bulan_get,
					'tahun'				=> $tahun_get,
					'tgl_mulai'   		=> $tanggal2,
					'tgl_selesai' 		=> $tglSelesai2,
					'id_user_upd' 		=> $dt_ins['id_user'],
					'kode_instansi_upd' => $dt_ins['kode_instansi'],
					'start_at_upd'		=> $dt_ins['start_at']
				);
	        }
	    }

        echo json_encode($ret);
		#end
	}

	public function proses_generate_perpegawai()
	{
		$this->load->model(['Lap_absensi_lembur_model', 'Lap_absensi_lembur_detil_model']);
		$this->load->library('konversi_menit');
		$id_pegawai = $this->input->post('id_pegawai');
		$tgl_mulai   = $this->input->post('tgl_mulai_peg');
		$tgl_selesai = $this->input->post('tgl_akhir_peg');
		$instansiRaw 	  = $this->input->post("id_instansi_peg");
		$pns = $this->input->post('pns');

		$tmulai = explode('/', $tgl_mulai);
		$thingga = explode('/', $tgl_selesai);
		$akhir =	$thingga[2]."-".$thingga[1]."-".$thingga[0];
		$mulai =	$tmulai[2]."-".$tmulai[1]."-".$tmulai[0];

		/** INSERT LAPORAN BARU */
        $dataLembur = '';
        if ($id_pegawai != '')
        {
			$begin = new DateTime($mulai);
			$end   = new DateTime($akhir);

			$skor = [];
			$totalLemburJumlahDiakui = 0;
			for($i = $begin; $i <= $end; $i->modify('+1 day')){

                $queryJumlahLembur	=	$this->db->query("select lembur,lembur_diakui from data_mentah where id_pegawai='".$id_pegawai."' and tanggal='".$i->format("Y-m-d")."'");
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

                //$dataLembur .= "<td align='center' ><span style='color:".$color."'>".sprintf("%02d", $lembur['jam_angka'])." : ".sprintf("%02d",$lembur['menit_angka'])."</span></td>";

                // untuk insert ke lap_absensi_lembur_detil
                $skor[] = [
                    'color' => $color,
                    'value' => sprintf("%02d", $lembur['jam_angka'])." : ".sprintf("%02d",$lembur['menit_angka'])
                ];
                $totalLemburJumlahDiakui += $lemburJumlahDiakui;
			}

			//return true;
			$jumlahLembur		=	$this->konversi_menit->hitung($totalLemburJumlahDiakui);

			$bulan 		=	date('Y-m', strtotime($akhir));
            if($bulan =='2018-05' || $bulan =='2018-06'){
                $where	=	"and jenis = 'RAMADHAN'";
            }
            else{
                $where	=	"and jenis = 'BIASA'";
            }

			$queryPersen 	=	$this->db->query("select skor from m_skor_lembur where menit_mulai <='".$totalLemburJumlahDiakui."' and menit_akhir >= '".$totalLemburJumlahDiakui."' $where");
                $dataPersen		=	$queryPersen->row();

			/** INSERT LAP REKAP LEMBUR DETIL */
            $data = [
                'nip'			=> $this->input->post('nip'),
                'nama'			=> $this->input->post('nama'),
                'skor'			=> json_encode($skor),
                'bulan'			=> $thingga[1],
                'tahun'			=> $thingga[2],
                'id_instansi'	=> $instansiRaw,
                'pns'			=> $pns,
                'total'		    => sprintf("%02d", $jumlahLembur['jam_angka'])." : ".sprintf("%02d", $jumlahLembur['menit_angka']),
                'skor_persen'	=> $dataPersen->skor,
                'urut'			=> $this->input->post('urut')
            ];

            $this->Lap_absensi_lembur_detil_model->insert($data);
			echo json_encode(['status' => 'sukses', 'pesan' => 'Sukses Generate per pegawai']);
		}
		else
		{
			echo json_encode(['status' => 'gagal', 'pesan' => 'Gagal Generate per pegawai']);
		}
	}

	public function cek_proses_gen_user_lain($bulan_get, $tahun_get, $id_instansi_get, $pns_get, $data_pegawai)
	{
		/** CEK APAKAH ADA PROSES GENERATE DI USER LAINNYA */
		$data_uri = [
			'bulan' => $bulan_get,
			'tahun' => $tahun_get,
			'id_instansi' => $id_instansi_get,
			'pns' => $pns_get,
		];

		$queryGeneratingLaporan	= $this->db->query("
			select m.*, u.fullname from lap_absensi_lembur m
			join c_security_user_new u on m.id_pegawai = u.id
			where bulan = '$bulan_get'
			and tahun = '$tahun_get'
			and id_instansi = '$id_instansi_get'
			and pns = '$pns_get'
			and deleted_at is null
			and finished_at is null
		")->row_array();

		if($queryGeneratingLaporan)
		{
			$laporanTergenerate	= $this->db->query("
				select * from lap_absensi_lembur_detil
				where bulan = '$bulan_get'
				and tahun = '$tahun_get'
				and id_instansi = '$id_instansi_get'
				and pns = '$pns_get'
				and deleted_at is null
			")->result();

			$antri = [
				'status' => TRUE,
				'pesan' => 'Terdapat antrian pada proses generate',
				'uri' => $data_uri,
				'data_generate' => $queryGeneratingLaporan,
				'jml_pegawai' => count($data_pegawai),
				'jml_tergenerate' => count($laporanTergenerate)
			];
		}
		else
		{
			$antri = [
				'status' => FALSE,
				'pesan' => 'loss',
				'uri' => $data_uri
			];
		}

		return $antri;
	}

	public function update_selesai_gen_laporan(){
		$this->load->model(['Lap_absensi_lembur_model', 'Lap_absensi_lembur_detil_model']);

		/** UPDATE FINISHED_AT JADI NOT NULL */
		$where = [
			'bulan'			=> $this->input->post('bulan_update'),
			'tahun'			=> $this->input->post('tahun_update'),
			'id_instansi'	=> $this->input->post('id_instansi_update'),
			'pns'			=> $this->input->post('pns_update'),
			'deleted_at'	=> null,
		];

		$this->Lap_absensi_lembur_model->update($where, ['finished_at' => date('Y-m-d H:i:s')]);
		#end

		echo json_encode(['status' => 'sukses', 'pesan' => 'Sukses Generate data']);
	}
	//=========END FUNGSI GENERATE LAPORAN ==============================

	// END CRONTAB
	// ####################################################################################################################


	function MigrasiPerbagian(){

		ini_set("max_execution_time", 0);

		$this->load->library('migrasi_data_opt');
		$inisial  		  = $this->input->get("inisial");
		$kategori  		  = $this->input->get("kategori");

		if($inisial == 0){
			$dataInstansi = "select * from m_instansi  order by nama asc limit 133 offset 0";
		}
		else if($inisial == 1){
			$dataInstansi = "select * from m_instansi  order by nama asc limit 133 offset 133";
		}
		else if($inisial == 2){
			$dataInstansi = "select * from m_instansi  order by nama asc limit 133 offset 266";
		}
		else if($inisial == 3){
			$dataInstansi = "select * from m_instansi  order by nama asc limit 133 offset 399";
		}
		else{
			exit;
		}

		// echo "<h1>$dataInstansi</h1><br>$kategori";
		$dataDinas = $this->db->query($dataInstansi)->result();

		foreach($dataDinas as $temp_dinas){
			$kode_dinas = $temp_dinas->kode;
			$nama_dinas = $temp_dinas->nama;
			$queryInstansi	= "
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
									pukh.kode_instansi = '".$kode_dinas."'";

		$data_pegawai = $this->db->query($queryInstansi)->result();

			if($kategori == "insert"){
				if($this->migrasi_data_opt->count_data_mentah_pegawai( date("Y-m-d"), $temp->id)->jumlah == 0 ){
					$this->migrasi_data_opt->cek_ulang_data_mentah( date("Y-m-d"), $temp->id, "insert", true);
				}
			}
			else{
				if($this->migrasi_data_opt->count_data_mentah_pegawai( date("Y-m-d"), $temp->id)->jumlah == 0 ){
					$this->migrasi_data_opt->cek_ulang_data_mentah( date("Y-m-d"), $temp->id, "insert", true);
				}
				else{
					$this->migrasi_data_opt->cek_ulang_data_mentah( date("Y-m-d"), $temp->id, "update", true);
				}
			}

			// insert monitoring

		}

		echo "<h1>KODE INSTANSI : $kode_dinas </h1> <h1>NAMA INSTANSI : $nama_dinas </h1><br>";


		echo "<h1>SUKKSESSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSS</h1>";
	}

	function cekMigrasiKominfo_Des(){
		$this->load->library('migrasi_data_opt');
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
				LEFT JOIN m_instansi mi ON  muok.kode_instansi = mi.kode WHERE h.tgl_mulai <= '2018-12-06' and m.id = h.id_pegawai
				ORDER BY h.tgl_mulai DESC LIMIT 1
				)
				pukh ON true
			where
				 pukh.kode_instansi = '5.16.00.00.00'";

		$data_pegawai = $this->db->query($queryInstansi)->result();
		foreach($data_pegawai as $temp){
			$begin = new DateTime( "2018-12-01" );
			$end   = new DateTime( "2018-12-07" );

			for($i = $begin; $i <= $end; $i->modify('+1 day')){

				if($this->migrasi_data_opt->count_data_mentah_pegawai( $i->format("Y-m-d"), $temp->id)->jumlah == 0){
					$this->migrasi_data_opt->cek_ulang_data_mentah($i->format("Y-m-d"), $temp->id, "insert", true);
				}
				else{
					$this->migrasi_data_opt->cek_ulang_data_mentah($i->format("Y-m-d"), $temp->id, "update", true);
				}
			}
		}
		echo "<h1>SUKKSESSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSS</h1>";

	}

	function log_history($kode_instansi){
		// $this->db->query("INSERT INTO");
	}

	function cekMigrasiKominfo_instansi(){
		$this->load->library('migrasi_data_opt');
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
				 pukh.kode_instansi = '1.03.01.00.00' and kode_status_pegawai = '1'";

		$data_pegawai = $this->db->query($queryInstansi)->result();
		foreach($data_pegawai as $temp){
			$begin = new DateTime( "2018-03-01" );
			$end   = new DateTime( "2018-04-01" );

			for($i = $begin; $i <= $end; $i->modify('+1 day')){

				if($this->migrasi_data_opt->count_data_mentah_pegawai( $i->format("Y-m-d"), $temp->id)->jumlah == 0){
					$this->migrasi_data_opt->cek_ulang_data_mentah($i->format("Y-m-d"), $temp->id, "insert", true);
				}
				else{
					$this->migrasi_data_opt->cek_ulang_data_mentah($i->format("Y-m-d"), $temp->id, "update", true);
				}
			}
		}
		echo "<h1>SUKKSESSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSS</h1>";

	}

	function cekMigrasiPerPegawai(){
		$this->load->library('migrasi_data_opt');
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
				$this->migrasi_data_opt->cek_ulang_data_mentah($i->format("Y-m-d"), $temp->id, "update", true);
			}
		}
		echo "<h1>SUKKSESSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSS</h1>";

	}


	function tampil_per_pegawai(){
		$this->load->library('migrasi_data_opt');
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
				$this->migrasi_data_opt->cek_ulang_data_mentah($i->format("Y-m-d"), $temp->id, "tampil", true);
			}
		}
	}

	function MigrasiDevToLive(){
		ini_set("max_execution_time", 0);
		$awal  = $this->input->get("awal");
		$akhir = $this->input->get("akhir");
		$offset = $this->input->get("offset");

		// echo $awal." ".$akhir." ".$limit;
		$this->load->library('migrasi_data_opt');
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
				if($this->migrasi_data_opt->count_data_mentah_pegawai( $i->format("Y-m-d"), $temp->id)->jumlah == 0 ){
					$this->migrasi_data_opt->cek_ulang_data_mentah($i->format("Y-m-d"), $temp->id, "insert", true);
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
	// 	$this->load->library('migrasi_data_opt');
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
	// 			if($this->migrasi_data_opt->count_data_mentah_pegawai( $i->format("Y-m-d"), $temp->id)->jumlah == 0 ){
	// 				$this->migrasi_data_opt->cek_ulang_data_mentah($i->format("Y-m-d"), $temp->id, "insert", true);
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
	// 	$this->load->library('migrasi_data_opt');
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
	// 			if($this->migrasi_data_opt->count_data_mentah_pegawai( $i->format("Y-m-d"), $temp->id)->jumlah == 0 ){
	// 				$this->migrasi_data_opt->cek_ulang_data_mentah($i->format("Y-m-d"), $temp->id, "insert", true);
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
	// 	$this->load->library('migrasi_data_opt');
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
	// 			if($this->migrasi_data_opt->count_data_mentah_pegawai( $i->format("Y-m-d"), $temp->id)->jumlah == 0 ){
	// 				$this->migrasi_data_opt->cek_ulang_data_mentah($i->format("Y-m-d"), $temp->id, "insert", true);
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
	// 	$this->load->library('migrasi_data_opt');
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
	// 			if($this->migrasi_data_opt->count_data_mentah_pegawai( $i->format("Y-m-d"), $temp->id)->jumlah == 0 ){
	// 				$this->migrasi_data_opt->cek_ulang_data_mentah($i->format("Y-m-d"), $temp->id, "insert", true);
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
	// 	$this->load->library('migrasi_data_opt');
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
	// 			if($this->migrasi_data_opt->count_data_mentah_pegawai( $i->format("Y-m-d"), $temp->id)->jumlah == 0 ){
	// 				$this->migrasi_data_opt->cek_ulang_data_mentah($i->format("Y-m-d"), $temp->id, "insert", true);
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
	// 	$this->load->library('migrasi_data_opt');
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
	// 	// 		$this->migrasi_data_opt->cek_ulang_data_mentah($i->format("Y-m-d"), $temp->id, "insert", false);
	// 	// 	}
	// 	// }
	// 	echo "<h1>KODE INSTANSI : $kode_dinas </h1> <h1>NAMA INSTANSI : $nama_dinas </h1><br>";

	// 	}
	// 	echo "<h1>SUKKSESSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSS</h1>";
	// }

	// function MigrasiDevToLive_03(){
	// 	ini_set("max_execution_time", 0);
	// 	$this->load->library('migrasi_data_opt');
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
	// 			$this->migrasi_data_opt->cek_ulang_data_mentah($i->format("Y-m-d"), $temp->id, "insert", true);
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
	// 	$this->load->library('migrasi_data_opt');
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
	// 			$this->migrasi_data_opt->cek_ulang_data_mentah($i->format("Y-m-d"), $temp->id, "insert", true);
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
	// 	$this->load->library('migrasi_data_opt');
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
	// 			$this->migrasi_data_opt->cek_ulang_data_mentah($i->format("Y-m-d"), $temp->id, "insert", true);
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
	// 	$this->load->library('migrasi_data_opt');
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
	// 			$this->migrasi_data_opt->cek_ulang_data_mentah($i->format("Y-m-d"), $temp->id, "insert", true);
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
	// 	$this->load->library('migrasi_data_opt');
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
	// 			$this->migrasi_data_opt->cek_ulang_data_mentah($i->format("Y-m-d"), $temp->id, "insert", true);
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
	// 	$this->load->library('migrasi_data_opt');
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
	// 			$this->migrasi_data_opt->cek_ulang_data_mentah($i->format("Y-m-d"), $temp->id, "insert", true);
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
	// 	$this->load->library('migrasi_data_opt');
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
	// 			$this->migrasi_data_opt->cek_ulang_data_mentah($i->format("Y-m-d"), $temp->id, "insert", true);
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
	// 	$this->load->library('migrasi_data_opt');
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
	// 			$this->migrasi_data_opt->cek_ulang_data_mentah($i->format("Y-m-d"), $temp->id, "insert", true);
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
	// 	$this->load->library('migrasi_data_opt');
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
	// 			$this->migrasi_data_opt->cek_ulang_data_mentah($i->format("Y-m-d"), $temp->id, "insert", true);
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
	// 	$this->load->library('migrasi_data_opt');
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
	// 			$this->migrasi_data_opt->cek_ulang_data_mentah($i->format("Y-m-d"), $temp->id, "insert", true);
	// 		}
	// 	}
	// 	echo "<h1>KODE INSTANSI : $kode_dinas </h1> <h1>NAMA INSTANSI : $nama_dinas </h1><br>";
	//
	// 	}
	// 	echo "<h1>SUKKSESSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSS</h1>";
	// }

	function info(){
			phpinfo();

	}

	function CekMigrasiDevToLive(){
			$begin = new DateTime( "2018-10-01" );
			$end   = new DateTime( "2018-11-01" );
			$this->load->library('migrasi_data_opt');
			// $this->migrasi_data_opt->cek_ulang_data_mentah("2018-09-30", "d5b8fa0e-dbea-4ab0-9e03-8f963733c594", "insert", true);

			for($i = $begin; $i <= $end; $i->modify('+1 day')){
				if($this->migrasi_data_opt->count_data_mentah_pegawai( $i->format("Y-m-d"), "facd8042-64d2-11e6-b55c-134bb56512e1")->jumlah == 0 ){
						$this->migrasi_data_opt->cek_ulang_data_mentah( $i->format("Y-m-d"), "facd8042-64d2-11e6-b55c-134bb56512e1", "insert", true);
					}
					else{
						$this->migrasi_data_opt->cek_ulang_data_mentah( $i->format("Y-m-d"), "facd8042-64d2-11e6-b55c-134bb56512e1", "update", true);
					}
				// $this->migrasi_data_opt->cek_ulang_data_mentah($i->format("Y-m-d"), "d5b8fa0e-dbea-4ab0-9e03-8f963733c594", "update", true);
			}

	}

	function MigrasiDataMentah(){
		$this->load->library('migrasi_data_opt');
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
				LEFT JOIN m_instansi mi ON  muok.kode_instansi = mi.kode WHERE h.tgl_mulai <= '2018-12-18' and m.id = h.id_pegawai
				ORDER BY h.tgl_mulai DESC LIMIT 1
				)
				pukh ON true
			where
				pukh.kode_instansi = '5.03.99.00.00'
		";
		$data_pegawai = $this->db->query($queryInstansi)->result();
		foreach($data_pegawai as $temp){
			$begin = new DateTime( "2018-01-01" );
			$end   = new DateTime( "2018-12-18" );

			for($i = $begin; $i <= $end; $i->modify('+1 day')){
				if($this->migrasi_data_opt->count_data_mentah_pegawai( $i->format("Y-m-d"), $temp->id)->jumlah == 0 ){
					$this->migrasi_data_opt->cek_ulang_data_mentah( $i->format("Y-m-d"), $temp->id, "insert", true);
				}
				else{
					$this->migrasi_data_opt->cek_ulang_data_mentah( $i->format("Y-m-d"), $temp->id, "update", true);
				}
			}
		}

	}

	public function cek_lagi(){
		$this->load->library('migrasi_data_opt');
		// $tanggal = "10/12/2019";
		// $date = new DateTime( $tanggal );
		// echo $date->format("Y-m-d");
		$tgl_mulai 					= "2018-05-01";
		$tgl_selesai_insert = "2018-06-30";
		$begin 							= new DateTime( $tgl_mulai );
		$end   							= new DateTime( $tgl_selesai_insert );

		for($i = $begin; $i <= $end; $i->modify('+1 day')){
			$this->migrasi_data_opt->cek_ulang_data_mentah($i->format("Y-m-d"), 'f2da47bc-64d2-11e6-baf8-b7b54aac853d', "insert", true);
		}

	}

	public function cek_sync(){
		echo "KAKAKA";
		// exit;
		$this->load->library('migrasi_data_opt');

		$this->migrasi_data_opt->cek_ulang_data_mentah('2018-11-28', 'd5b8fa0e-dbea-4ab0-9e03-8f963733c594', "update", true);

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
		<th>NAMA</th>
		<th>NIP</th>';

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

		$query_kode_sik = $this->db->query("select kode_sik, nama from m_instansi where kode = '".$this->input->get('id_instansi')."'");
		$data_kode_sik = $query_kode_sik->row();

		/*highlight_string("<?php\n\$data =\n" . var_export($data_kode_sik->kode_sik, true) . ";\n?>");exit;*/

		if (substr($data_kode_sik->nama, 0, 9) != 'Kecamatan') {
			$kode_instansi_all = $this->input->get('id_instansi');
			$whereQuery = "pukh.kode_instansi = '".$kode_instansi_all."'".$wherePns;

		}else{
			$kode_instansi_all = substr($this->input->get('id_instansi'), 0, 5);
			$whereQuery = "pukh.kode_instansi LIKE '".$kode_instansi_all.'%'."'".$wherePns;
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
				".$whereQuery."
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
			$this->dataLembur .= "<td>".$dataPegawai->nip."</td>";

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

	function MigrasiAll_01(){
		ini_set("max_execution_time", 0);
		$this->load->model('monitoring_tarik_model');
		$this->load->library('migrasi_data_opt');
		$dataInstansi = "select * from m_instansi
						 WHERE
							nama NOT LIKE 'Penghapusan%'
							AND nama NOT LIKE 'Pensiun%'
							AND nama NOT LIKE 'Perusahaan%'
							AND nama NOT LIKE 'SDLB%'
							AND nama NOT LIKE 'SDN %'
							AND nama NOT LIKE 'SLB %'
							AND nama NOT LIKE 'SLB/%'
							AND nama NOT LIKE 'SLB-%'
							AND nama NOT LIKE 'SMA %'
							AND nama NOT LIKE 'SMALB %'
							AND nama NOT LIKE 'SMAN %'
							AND nama NOT LIKE 'SMK %'
							AND nama NOT LIKE 'SMKN %'
							AND nama NOT LIKE 'SMP %'
							AND nama NOT LIKE 'SMPN %'
							AND nama NOT LIKE 'TK %'
							AND nama NOT LIKE 'TKL%'
						order by nama asc limit 75 offset 8";

		$dataDinas = $this->db->query($dataInstansi)->result();

		foreach($dataDinas as $temp_dinas){
			$kode_dinas = $temp_dinas->kode;
			$nama_dinas = $temp_dinas->nama;
			$queryInstansi	= "select
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
									pukh.kode_instansi = '".$kode_dinas."'";

			$data_pegawai = $this->db->query($queryInstansi)->result();

			//INSERT KE t_cron_scheduling
			$tsch['id_upd'] = $temp_dinas->kode;
			$tsch['nama_upd'] = $temp_dinas->nama;
			$tsch['date'] = date('Y-m-d');
			$tsch['start_at'] = date('Y-m-d H:i:s');
			$tsch['status'] = 'N';
			$tsch['running_by'] = 'M_ALL_01';
			$this->monitoring_tarik_model->insert($tsch);
			echo "######################### START KODE INSTANSI : ".$temp_dinas->nama." ########################### \n";

			foreach($data_pegawai as $temp){

			$begin = new DateTime( "2018-01-01" );
			$end   = new DateTime( "2018-12-13" );

				for($i = $begin; $i <= $end; $i->modify('+1 day')){
					if($this->migrasi_data_opt->count_data_mentah_pegawai( $i->format("Y-m-d"), $temp->id)->jumlah == 0 ){
						$this->migrasi_data_opt->cek_ulang_data_mentah( $i->format("Y-m-d"), $temp->id, "insert", false);
					}
					else{
						$this->migrasi_data_opt->cek_ulang_data_mentah( $i->format("Y-m-d"), $temp->id, "update", false);
					}

				}


			}

			// INSERT KODE DINAS
			echo "------------------------ END KODE INSTANSI : ".$temp_dinas->nama." ------------------------------ \n";

			//UPDATE JIKA FINISH
			$tsch2 = ['status' => 'Y', 'finish_at' => date('Y-m-d H:i:s')];
			$where_upd = ['id_upd' => $temp_dinas->kode, 'status' => 'N'];
			$this->monitoring_tarik_model->update($where_upd, $tsch2);
		}

		echo "<h1>SUKKSESSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSS</h1>";
	}

	function MigrasiAll_02(){
		ini_set("max_execution_time", 0);
		$this->load->model('monitoring_tarik_model');
		$this->load->library('migrasi_data_opt');
		$dataInstansi = "select * from m_instansi WHERE
							nama NOT LIKE 'Penghapusan%'
							AND nama NOT LIKE 'Pensiun%'
							AND nama NOT LIKE 'Perusahaan%'
							AND nama NOT LIKE 'SDLB%'
							AND nama NOT LIKE 'SDN %'
							AND nama NOT LIKE 'SLB %'
							AND nama NOT LIKE 'SLB/%'
							AND nama NOT LIKE 'SLB-%'
							AND nama NOT LIKE 'SMA %'
							AND nama NOT LIKE 'SMALB %'
							AND nama NOT LIKE 'SMAN %'
							AND nama NOT LIKE 'SMK %'
							AND nama NOT LIKE 'SMKN %'
							AND nama NOT LIKE 'SMP %'
							AND nama NOT LIKE 'SMPN %'
							AND nama NOT LIKE 'TK %'
							AND nama NOT LIKE 'TKL%' order by nama asc limit 75 offset 126";

		$dataDinas = $this->db->query($dataInstansi)->result();

		foreach($dataDinas as $temp_dinas){
			$kode_dinas = $temp_dinas->kode;
			$nama_dinas = $temp_dinas->nama;
			$queryInstansi	= "select
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
									pukh.kode_instansi = '".$kode_dinas."'";

			$data_pegawai = $this->db->query($queryInstansi)->result();

			//INSERT KE t_cron_scheduling
			$tsch['id_upd'] = $temp_dinas->kode;
			$tsch['nama_upd'] = $temp_dinas->nama;
			$tsch['date'] = date('Y-m-d');
			$tsch['start_at'] = date('Y-m-d H:i:s');
			$tsch['status'] = 'N';
			$tsch['running_by'] = 'M_ALL_02';
			$this->monitoring_tarik_model->insert($tsch);

			echo "######################### START KODE INSTANSI : ".$temp_dinas->nama." ########################### \n";

			foreach($data_pegawai as $temp){

				$begin = new DateTime( "2018-01-01" );
				$end   = new DateTime( "2018-12-13" );

				for($i = $begin; $i <= $end; $i->modify('+1 day')){
					if($this->migrasi_data_opt->count_data_mentah_pegawai( $i->format("Y-m-d"), $temp->id)->jumlah == 0 ){
						$this->migrasi_data_opt->cek_ulang_data_mentah( $i->format("Y-m-d"), $temp->id, "insert", false);
					}
					else{
						$this->migrasi_data_opt->cek_ulang_data_mentah( $i->format("Y-m-d"), $temp->id, "update", false);
					}
				}
			}


			// INSERT KODE DINAS
			echo "------------------------ END KODE INSTANSI : ".$temp_dinas->nama." ------------------------------ \n";

			//UPDATE JIKA FINISH
			$tsch2 = ['status' => 'Y', 'finish_at' => date('Y-m-d H:i:s')];
			$where_upd = ['id_upd' => $temp_dinas->kode, 'status' => 'N'];
			$this->monitoring_tarik_model->update($where_upd, $tsch2);
		}

		echo "<h1>SUKKSESSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSS</h1>";
	}

	function MigrasiAll_03(){
		ini_set("max_execution_time", 0);
		$this->load->model('monitoring_tarik_model');
		$this->load->library('migrasi_data_opt');
		$dataInstansi = "select * from m_instansi WHERE
							nama NOT LIKE 'Penghapusan%'
							AND nama NOT LIKE 'Pensiun%'
							AND nama NOT LIKE 'Perusahaan%'
							AND nama NOT LIKE 'SDLB%'
							AND nama NOT LIKE 'SDN %'
							AND nama NOT LIKE 'SLB %'
							AND nama NOT LIKE 'SLB/%'
							AND nama NOT LIKE 'SLB-%'
							AND nama NOT LIKE 'SMA %'
							AND nama NOT LIKE 'SMALB %'
							AND nama NOT LIKE 'SMAN %'
							AND nama NOT LIKE 'SMK %'
							AND nama NOT LIKE 'SMKN %'
							AND nama NOT LIKE 'SMP %'
							AND nama NOT LIKE 'SMPN %'
							AND nama NOT LIKE 'TK %'
							AND nama NOT LIKE 'TKL%'  order by nama asc limit 75 offset 150";

		$dataDinas = $this->db->query($dataInstansi)->result();

		foreach($dataDinas as $temp_dinas){
			$kode_dinas = $temp_dinas->kode;
			$nama_dinas = $temp_dinas->nama;
			$queryInstansi	= "select
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
									pukh.kode_instansi = '".$kode_dinas."'";

			$data_pegawai = $this->db->query($queryInstansi)->result();

			//INSERT KE t_cron_scheduling
			$tsch['id_upd'] = $temp_dinas->kode;
			$tsch['nama_upd'] = $temp_dinas->nama;
			$tsch['date'] = date('Y-m-d');
			$tsch['start_at'] = date('Y-m-d H:i:s');
			$tsch['status'] = 'N';
			$tsch['running_by'] = 'M_ALL_03';
			$this->monitoring_tarik_model->insert($tsch);
			echo "######################### START KODE INSTANSI : ".$temp_dinas->nama." ########################### \n";

			foreach($data_pegawai as $temp){

				$begin = new DateTime( "2018-01-01" );
				$end   = new DateTime( "2018-12-13" );

				for($i = $begin; $i <= $end; $i->modify('+1 day')){
					if($this->migrasi_data_opt->count_data_mentah_pegawai( $i->format("Y-m-d"), $temp->id)->jumlah == 0 ){
						$this->migrasi_data_opt->cek_ulang_data_mentah( $i->format("Y-m-d"), $temp->id, "insert", false);
					}
					else{
						$this->migrasi_data_opt->cek_ulang_data_mentah( $i->format("Y-m-d"), $temp->id, "update", false);
					}

				}


			}

			// INSERT KODE DINAS
			echo "------------------------ END KODE INSTANSI : ".$temp_dinas->nama." ------------------------------ \n";

			//UPDATE JIKA FINISH
			$tsch2 = ['status' => 'Y', 'finish_at' => date('Y-m-d H:i:s')];
			$where_upd = ['id_upd' => $temp_dinas->kode, 'status' => 'N'];
			$this->monitoring_tarik_model->update($where_upd, $tsch2);
		}

		echo "<h1>SUKKSESSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSS</h1>";
	}

	function MigrasiAll_04(){
		ini_set("max_execution_time", 0);
		$this->load->model('monitoring_tarik_model');
		$this->load->library('migrasi_data_opt');
		$dataInstansi = "select * from m_instansi WHERE
							nama NOT LIKE 'Penghapusan%'
							AND nama NOT LIKE 'Pensiun%'
							AND nama NOT LIKE 'Perusahaan%'
							AND nama NOT LIKE 'SDLB%'
							AND nama NOT LIKE 'SDN %'
							AND nama NOT LIKE 'SLB %'
							AND nama NOT LIKE 'SLB/%'
							AND nama NOT LIKE 'SLB-%'
							AND nama NOT LIKE 'SMA %'
							AND nama NOT LIKE 'SMALB %'
							AND nama NOT LIKE 'SMAN %'
							AND nama NOT LIKE 'SMK %'
							AND nama NOT LIKE 'SMKN %'
							AND nama NOT LIKE 'SMP %'
							AND nama NOT LIKE 'SMPN %'
							AND nama NOT LIKE 'TK %'
							AND nama NOT LIKE 'TKL%'  order by nama asc limit 81 offset 225";

		$dataDinas = $this->db->query($dataInstansi)->result();
		echo "######################### START KODE INSTANSI : ".$temp_dinas->nama." ########################### \n";
		foreach($dataDinas as $temp_dinas){
			$kode_dinas = $temp_dinas->kode;
			$nama_dinas = $temp_dinas->nama;
			$queryInstansi	= "select
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
									pukh.kode_instansi = '".$kode_dinas."'";

			$data_pegawai = $this->db->query($queryInstansi)->result();

			//INSERT KE t_cron_scheduling
			$tsch['id_upd'] = $temp_dinas->kode;
			$tsch['nama_upd'] = $temp_dinas->nama;
			$tsch['date'] = date('Y-m-d');
			$tsch['start_at'] = date('Y-m-d H:i:s');
			$tsch['status'] = 'N';
			$tsch['running_by'] = 'M_ALL_04';
			$this->monitoring_tarik_model->insert($tsch);

			foreach($data_pegawai as $temp){

			$begin = new DateTime( "2018-01-01" );
			$end   = new DateTime( "2018-12-13" );

				for($i = $begin; $i <= $end; $i->modify('+1 day')){
					if($this->migrasi_data_opt->count_data_mentah_pegawai( $i->format("Y-m-d"), $temp->id)->jumlah == 0 ){
						$this->migrasi_data_opt->cek_ulang_data_mentah( $i->format("Y-m-d"), $temp->id, "insert", false);
					}
					else{
						$this->migrasi_data_opt->cek_ulang_data_mentah( $i->format("Y-m-d"), $temp->id, "update", false);
					}

				}

			}

			// INSERT KODE DINAS
			echo "------------------------ END KODE INSTANSI : ".$temp_dinas->nama." ------------------------------ \n";

			//UPDATE JIKA FINISH
			$tsch2 = ['status' => 'Y', 'finish_at' => date('Y-m-d H:i:s')];
			$where_upd = ['id_upd' => $temp_dinas->kode, 'status' => 'N'];
			$this->monitoring_tarik_model->update($where_upd, $tsch2);
		}

		echo "<h1>SUKKSESSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSS</h1>";
	}

	// function MigrasiAll_02(){
	// 	ini_set("max_execution_time", 0);
	// 	$this->load->model('monitoring_tarik_model');
	// 	$this->load->library('migrasi_data_opt');
	// 	$inisial  		  = $this->input->get("inisial");
	// 	$kategori  		  = $this->input->get("kategori");
	// 	$dataInstansi = "select * from m_instansi  order by nama asc limit 133 offset 133";

	// 	$dataDinas = $this->db->query($dataInstansi)->result();

	// 	foreach($dataDinas as $temp_dinas){
	// 		$kode_dinas = $temp_dinas->kode;
	// 		$nama_dinas = $temp_dinas->nama;
	// 		$queryInstansi	= "select
	// 								m.id,m.nama, m.nip
	// 							from
	// 								m_pegawai m
	// 								LEFT JOIN LATERAL (
	// 								SELECT
	// 								h.kode_unor, h.tgl_mulai, muok.nama as nama_unor, mi.kode as kode_instansi, mi.nama as nama_instansi
	// 								FROM
	// 								m_pegawai_unit_kerja_histori h
	// 								LEFT JOIN m_unit_organisasi_kerja muok ON  h.kode_unor =  muok.kode
	// 								LEFT JOIN m_instansi mi ON  muok.kode_instansi = mi.kode WHERE h.tgl_mulai <= '".date('Y-m-d')."' and m.id = h.id_pegawai
	// 								ORDER BY h.tgl_mulai DESC LIMIT 1
	// 								)
	// 								pukh ON true
	// 							where
	// 								pukh.kode_instansi = '".$kode_dinas."'";

	// 		$data_pegawai = $this->db->query($queryInstansi)->result();
	// 		foreach($data_pegawai as $temp){
	// 			if($this->migrasi_data_opt->count_data_mentah_pegawai( date("Y-m-d"), $temp->id)->jumlah == 0 ){
	// 				$this->migrasi_data_opt->cek_ulang_data_mentah( date("Y-m-d"), $temp->id, "insert", false);
	// 			}
	// 			else{
	// 				$this->migrasi_data_opt->cek_ulang_data_mentah( date("Y-m-d"), $temp->id, "update", false);
	// 			}

	// 		}

	// 		//INSERT KE t_cron_scheduling
	// 		$tsch['id_upd'] = $temp_dinas->kode;
	// 		$tsch['nama_upd'] = $temp_dinas->nama;
	// 		$tsch['date'] = date('Y-m-d');
	// 		$tsch['start_at'] = date('Y-m-d H:i:s');
	// 		$tsch['status'] = 'N';
	// 		$tsch['running_by'] = 'cron';
	// 		$this->monitoring_tarik_model->insert($tsch);

	// 		// INSERT KODE DINAS
	// 		echo "<h1>KODE INSTANSI : $kode_dinas </h1> <h1>NAMA INSTANSI : $nama_dinas </h1><br>";

	// 		//UPDATE JIKA FINISH
	// 		$tsch2 = ['status' => 'Y', 'finish_at' => date('Y-m-d H:i:s')];
	// 		$where_upd = ['id_upd' => $temp_dinas->kode, 'status' => 'N'];
	// 		$this->monitoring_tarik_model->update($where_upd, $tsch2);
	// 	}

	// 	echo "<h1>SUKKSESSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSS</h1>";
	// }

	// function MigrasiAll_03(){
	// 	ini_set("max_execution_time", 0);
	// 	$this->load->model('monitoring_tarik_model');
	// 	$this->load->library('migrasi_data_opt');
	// 	$inisial  		  = $this->input->get("inisial");
	// 	$kategori  		  = $this->input->get("kategori");
	// 	$dataInstansi = "select * from m_instansi  order by nama asc limit 133 offset 266";

	// 	$dataDinas = $this->db->query($dataInstansi)->result();

	// 	foreach($dataDinas as $temp_dinas){
	// 		$kode_dinas = $temp_dinas->kode;
	// 		$nama_dinas = $temp_dinas->nama;
	// 		$queryInstansi	= "select
	// 								m.id,m.nama, m.nip
	// 							from
	// 								m_pegawai m
	// 								LEFT JOIN LATERAL (
	// 								SELECT
	// 								h.kode_unor, h.tgl_mulai, muok.nama as nama_unor, mi.kode as kode_instansi, mi.nama as nama_instansi
	// 								FROM
	// 								m_pegawai_unit_kerja_histori h
	// 								LEFT JOIN m_unit_organisasi_kerja muok ON  h.kode_unor =  muok.kode
	// 								LEFT JOIN m_instansi mi ON  muok.kode_instansi = mi.kode WHERE h.tgl_mulai <= '".date('Y-m-d')."' and m.id = h.id_pegawai
	// 								ORDER BY h.tgl_mulai DESC LIMIT 1
	// 								)
	// 								pukh ON true
	// 							where
	// 								pukh.kode_instansi = '".$kode_dinas."'";

	// 		$data_pegawai = $this->db->query($queryInstansi)->result();
	// 		foreach($data_pegawai as $temp){
	// 			if($this->migrasi_data_opt->count_data_mentah_pegawai( date("Y-m-d"), $temp->id)->jumlah == 0 ){
	// 				$this->migrasi_data_opt->cek_ulang_data_mentah( date("Y-m-d"), $temp->id, "insert", false);
	// 			}
	// 			else{
	// 				$this->migrasi_data_opt->cek_ulang_data_mentah( date("Y-m-d"), $temp->id, "update", false);
	// 			}

	// 		}

	// 		//INSERT KE t_cron_scheduling
	// 		$tsch['id_upd'] = $temp_dinas->kode;
	// 		$tsch['nama_upd'] = $temp_dinas->nama;
	// 		$tsch['date'] = date('Y-m-d');
	// 		$tsch['start_at'] = date('Y-m-d H:i:s');
	// 		$tsch['status'] = 'N';
	// 		$tsch['running_by'] = 'cron';
	// 		$this->monitoring_tarik_model->insert($tsch);

	// 		// INSERT KODE DINAS
	// 		echo "<h1>KODE INSTANSI : $kode_dinas </h1> <h1>NAMA INSTANSI : $nama_dinas </h1><br>";

	// 		//UPDATE JIKA FINISH
	// 		$tsch2 = ['status' => 'Y', 'finish_at' => date('Y-m-d H:i:s')];
	// 		$where_upd = ['id_upd' => $temp_dinas->kode, 'status' => 'N'];
	// 		$this->monitoring_tarik_model->update($where_upd, $tsch2);
	// 	}

	// 	echo "<h1>SUKKSESSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSS</h1>";
	// }

	// function MigrasiAll_04(){
	// 	ini_set("max_execution_time", 0);
	// 	$this->load->model('monitoring_tarik_model');
	// 	$this->load->library('migrasi_data_opt');
	// 	$inisial  		  = $this->input->get("inisial");
	// 	$kategori  		  = $this->input->get("kategori");
	// 	$dataInstansi = "select * from m_instansi  order by nama asc limit 133 offset 399";

	// 	$dataDinas = $this->db->query($dataInstansi)->result();

	// 	foreach($dataDinas as $temp_dinas){
	// 		$kode_dinas = $temp_dinas->kode;
	// 		$nama_dinas = $temp_dinas->nama;
	// 		$queryInstansi	= "select
	// 								m.id,m.nama, m.nip
	// 							from
	// 								m_pegawai m
	// 								LEFT JOIN LATERAL (
	// 								SELECT
	// 								h.kode_unor, h.tgl_mulai, muok.nama as nama_unor, mi.kode as kode_instansi, mi.nama as nama_instansi
	// 								FROM
	// 								m_pegawai_unit_kerja_histori h
	// 								LEFT JOIN m_unit_organisasi_kerja muok ON  h.kode_unor =  muok.kode
	// 								LEFT JOIN m_instansi mi ON  muok.kode_instansi = mi.kode WHERE h.tgl_mulai <= '".date('Y-m-d')."' and m.id = h.id_pegawai
	// 								ORDER BY h.tgl_mulai DESC LIMIT 1
	// 								)
	// 								pukh ON true
	// 							where
	// 								pukh.kode_instansi = '".$kode_dinas."'";

	// 		$data_pegawai = $this->db->query($queryInstansi)->result();
	// 		foreach($data_pegawai as $temp){
	// 			if($this->migrasi_data_opt->count_data_mentah_pegawai( date("Y-m-d"), $temp->id)->jumlah == 0 ){
	// 				$this->migrasi_data_opt->cek_ulang_data_mentah( date("Y-m-d"), $temp->id, "insert", false);
	// 			}
	// 			else{
	// 				$this->migrasi_data_opt->cek_ulang_data_mentah( date("Y-m-d"), $temp->id, "update", false);
	// 			}

	// 		}

	// 		//INSERT KE t_cron_scheduling
	// 		$tsch['id_upd'] = $temp_dinas->kode;
	// 		$tsch['nama_upd'] = $temp_dinas->nama;
	// 		$tsch['date'] = date('Y-m-d');
	// 		$tsch['start_at'] = date('Y-m-d H:i:s');
	// 		$tsch['status'] = 'N';
	// 		$tsch['running_by'] = 'cron';
	// 		$this->monitoring_tarik_model->insert($tsch);

	// 		// INSERT KODE DINAS
	// 		echo "<h1>KODE INSTANSI : $kode_dinas </h1> <h1>NAMA INSTANSI : $nama_dinas </h1><br>";

	// 		//UPDATE JIKA FINISH
	// 		$tsch2 = ['status' => 'Y', 'finish_at' => date('Y-m-d H:i:s')];
	// 		$where_upd = ['id_upd' => $temp_dinas->kode, 'status' => 'N'];
	// 		$this->monitoring_tarik_model->update($where_upd, $tsch2);
	// 	}

	// 	echo "<h1>SUKKSESSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSS</h1>";
	// }


}
