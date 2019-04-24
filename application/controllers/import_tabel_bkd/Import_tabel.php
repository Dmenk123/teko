<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Import_tabel extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model(['import_bkd_excel_model']);
	}
	
	// ################################################################################################################
	// FUNGSI IMPORT
	
	function import_m_pegawai_eselon_history(){
		//$this->load->library('migrasi_data');
		$tabel = 'm_pegawai_eselon_histori';
		$query = "select * from temp_m_pegawai_eselon_histori";
		$hasilTemp = $this->db->query($query)->result();
		$i=0;
		foreach ($hasilTemp as $val) {
			// $id = $val->id;date("YW", strtotime("2011-01-07"))
			$queryServer =	"select * 
							 from 
							 	m_pegawai_eselon_histori
							 where 
							  	to_char(tgl_mulai, 'YYYY-mm-dd') = '".date("Y-m-d", strtotime($val->tgl_mulai))."' and
							  	to_char(tgl_upd, 'YYYY-mm-dd') = '".date("Y-m-d", strtotime($val->tgl_upd))."' and
							  	id_pegawai = '$val->id_pegawai' and
							  	kode_eselon = '$val->kode_eselon'
							 ";
							 // echo $queryServer;exit;
			$hasilServer = $this->db->query($queryServer)->num_rows();
			$i++;
			echo "$i => $val->id_pegawai<br>";

			if ($hasilServer == 0) {
				$data = array(
					"tgl_mulai" => "$val->tgl_mulai",
					"tgl_upd" => "$val->tgl_upd",
					"user_upd" => "1",
					"id_pegawai" => "$val->id_pegawai",
					"kode_eselon" => "$val->kode_eselon"
				);


				$insert = $this->import_bkd_excel_model->insert($data, $tabel);
				if ($insert) {
					echo "<h3>$val->tgl_mulai, $val->tgl_upd, $val->id_pegawai => Tersimpan</h3>";
				}
				else{
					echo "fail <br>";
				}
			}
			else{
				echo "Sudah Ada<br>";
			}
		}
	}

	function import_m_pegawai_golongan_history(){
		//$this->load->library('migrasi_data');
		$tabel = 'm_pegawai_golongan_histori';
		$query = "select * from temp_m_pegawai_golongan_histori";
		$hasilTemp = $this->db->query($query)->result();
		foreach ($hasilTemp as $val) {
			// $id = $val->id;
			$queryServer =	"select * 
							 from 
							 	m_pegawai_golongan_histori
							 where 
							  	to_char(tgl_mulai, 'YYYY-mm-dd') = '".date("Y-m-d", strtotime($val->tgl_mulai))."' and
							  	to_char(tgl_upd, 'YYYY-mm-dd') = '".date("Y-m-d", strtotime($val->tgl_upd))."' and
							  	id_pegawai = '$val->id_pegawai' and
							  	kode_golongan = '$val->kode_golongan'
							 ";
			$hasilServer = $this->db->query($queryServer)->num_rows();
			if ($hasilServer == 0) {
				$data = [
					'tgl_mulai' => $val->tgl_mulai,
					'tgl_upd' => $val->tgl_upd,
					'user_upd' => $val->user_upd,
					'id_pegawai' => $val->id_pegawai,
					'kode_golongan' => $val->kode_golongan,
				];

				$insert = $this->import_bkd_excel_model->insert($data, $tabel);
				if ($insert) {
					echo "<h3>$val->tgl_mulai, $val->tgl_upd, $val->id_pegawai Tersimpan</h3>";
				}
			}
			else{
				echo "Sudah Ada<br>";
			}
		}
	}

	function import_m_pegawai_instansi_history(){
		//$this->load->library('migrasi_data');
		$tabel = 'm_pegawai_instansi_histori';
		$query = "select * from temp_m_pegawai_instansi_histori";
		$hasilTemp = $this->db->query($query)->result();
		foreach ($hasilTemp as $val) {
			// $id = $val->id;
			$queryServer =	"select * 
							 from 
							 	m_pegawai_instansi_histori
							 where 
							  	to_char(tgl_mulai, 'YYYY-mm-dd') = '".date("Y-m-d", strtotime($val->tgl_mulai))."' and
							  	to_char(tgl_upd, 'YYYY-mm-dd') = '".date("Y-m-d", strtotime($val->tgl_upd))."' and
							  	id_pegawai = '$val->id_pegawai' and
							  	kode_instansi = '$val->kode_instansi'
							 ";
			$hasilServer = $this->db->query($queryServer)->num_rows();
			if ($hasilServer == 0) {
				$data = [
					'tgl_mulai' => $val->tgl_mulai,
					'tgl_upd' => $val->tgl_upd,
					'user_upd' => $val->user_upd,
					'id_pegawai' => $val->id_pegawai,
					'kode_instansi' => $val->kode_instansi,
				];

				$insert = $this->import_bkd_excel_model->insert($data, $tabel);
				if ($insert) {
					echo "<h3>$val->tgl_mulai, $val->tgl_upd, $val->id_pegawai Tersimpan</h3>";
				}
			}
			else{
				echo "Sudah Ada<br>";
			}
		}
	}

	function import_m_pegawai_jabatan_history(){
		$tabel = 'm_pegawai_jabatan_histori';
		$query = "select * from temp_m_pegawai_jabatan_histori";
		$hasilTemp = $this->db->query($query)->result();
		foreach ($hasilTemp as $val) {
			// $id = $val->id;
			$queryServer =	"select * 
							 from 
							 	m_pegawai_jabatan_histori
							 where 
							  	to_char(tgl_mulai, 'YYYY-mm-dd') = '".date("Y-m-d", strtotime($val->tgl_mulai))."' and
							  	to_char(tgl_upd, 'YYYY-mm-dd') = '".date("Y-m-d", strtotime($val->tgl_upd))."' and
							  	id_pegawai = '$val->id_pegawai' and
							  	kode_jabatan = '$val->kode_jabatan'
							 ";
			$hasilServer = $this->db->query($queryServer)->num_rows();
			if ($hasilServer == 0) {
				$data = [
					'tgl_mulai' => $val->tgl_mulai,
					'tgl_upd' => $val->tgl_upd,
					'user_upd' => $val->user_upd,
					'id_pegawai' => $val->id_pegawai,
					'kode_jabatan' => $val->kode_jabatan,
				];

				$insert = $this->import_bkd_excel_model->insert($data, $tabel);
				if ($insert) {
					echo "<h3>$val->tgl_mulai, $val->tgl_upd, $val->id_pegawai Tersimpan</h3>";
				}
			}
		}
	}

	function import_m_pegawai_role_jam_kerja_history(){
		$tabel = 'm_pegawai_role_jam_kerja_histori';
		$query = "select * from temp_m_pegawai_role_jam_kerja_histori";
		$hasilTemp = $this->db->query($query)->result();
		foreach ($hasilTemp as $val) {
			// $id = $val->id;
			$queryServer =	"select * 
							 from 
							 	m_pegawai_role_jam_kerja_history
							 where 
							  	to_char(tgl_mulai, 'YYYY-mm-dd') = '".date("Y-m-d", strtotime($val->tgl_mulai))."' and
							  	to_char(tgl_upd, 'YYYY-mm-dd') = '".date("Y-m-d", strtotime($val->tgl_upd))."' and
							  	id_pegawai = '$val->id_pegawai' and
							  	id_role_jam_kerja = '$val->id_role_jam_kerja'
							 ";
			$hasilServer = $this->db->query($queryServer)->num_rows();
			if ($hasilServer == 0) {
				$data = [
					'tgl_mulai' => $val->tgl_mulai,
					'tgl_upd' => $val->tgl_upd,
					'user_upd' => $val->user_upd,
					'id_pegawai' => $val->id_pegawai,
					'id_role_jam_kerja' => $val->id_role_jam_kerja,
				];

				$insert = $this->import_bkd_excel_model->insert($data, $tabel);
				if ($insert) {
					echo "<h3>$val->tgl_mulai, $val->tgl_upd, $val->id_pegawai Tersimpan</h3>";
				}
			}
		}
	}

	function import_m_pegawai_rumpun_jabatan_history(){
		$tabel = 'm_pegawai_rumpun_jabatan_histori';
		$query = "select * from temp_m_pegawai_rumpun_jabatan_histori";
		$hasilTemp = $this->db->query($query)->result();
		foreach ($hasilTemp as $val) {
			// $id = $val->id;
			$queryServer =	"select * 
							 from 
							 	m_pegawai_rumpun_jabatan_histori
							 where 
							  	to_char(tgl_mulai, 'YYYY-mm-dd') = '".date("Y-m-d", strtotime($val->tgl_mulai))."' and
							  	to_char(tgl_upd, 'YYYY-mm-dd') = '".date("Y-m-d", strtotime($val->tgl_upd))."' and
							  	id_pegawai = '$val->id_pegawai' and
							  	id_rumpun_jabatan = '$val->id_rumpun_jabatan'
							 ";
			$hasilServer = $this->db->query($queryServer)->num_rows();
			if ($hasilServer == 0) {
				$data = [
					'tgl_mulai' => $val->tgl_mulai,
					'tgl_upd' => $val->tgl_upd,
					'user_upd' => $val->user_upd,
					'id_pegawai' => $val->id_pegawai,
					'id_rumpun_jabatan' => $val->id_rumpun_jabatan,
				];

				$insert = $this->import_bkd_excel_model->insert($data, $tabel);
				if ($insert) {
					echo "<h3>$val->tgl_mulai, $val->tgl_upd, $val->id_pegawai Tersimpan</h3>";
				}
			}
		}
	}

	function import_m_pegawai_unit_kerja_history(){
		$tabel = 'm_pegawai_unit_kerja_history';
		$query = "select * from temp_m_pegawai_unit_kerja_history";
		$hasilTemp = $this->db->query($query)->result();
		foreach ($hasilTemp as $val) {
			// $id = $val->id;
			$queryServer =	"select * 
							 from 
							 	m_pegawai_unit_kerja_history
							 where 
							  	to_char(tgl_mulai, 'YYYY-mm-dd') = '".date("Y-m-d", strtotime($val->tgl_mulai))."' and
							  	to_char(tgl_upd, 'YYYY-mm-dd') = '".date("Y-m-d", strtotime($val->tgl_upd))."' and
							  	id_pegawai = '$val->id_pegawai' and
							  	kode_unor = '$val->kode_unor'
							 ";
			$hasilServer = $this->db->query($queryServer)->num_rows();
			if ($hasilServer == 0) {
				$data = [
					'tgl_mulai' => $val->tgl_mulai,
					'tgl_upd' => $val->tgl_upd,
					'user_upd' => $val->user_upd,
					'id_pegawai' => $val->id_pegawai,
					'kode_unor' => $val->kode_unor,
				];

				$insert = $this->import_bkd_excel_model->insert($data, $tabel);
				if ($insert) {
					echo "<h3>$val->tgl_mulai, $val->tgl_upd, $val->id_pegawai Tersimpan</h3>";
				}
			}
		}
	}

}
