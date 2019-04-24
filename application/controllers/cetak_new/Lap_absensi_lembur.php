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

	// ################################################################################################################
	// CRONTAB SERVER (BUAT BACKUP lap_absensi_lembur_opt)
	function MigrasiPerbagian_insert01(){
		ini_set("max_execution_time", 0);
		$this->load->model('monitoring_tarik_model');
		$this->load->library('migrasi_data');
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
				if($this->migrasi_data->count_data_mentah_pegawai( date("Y-m-d"), $temp->id)->jumlah == 0 ){
					$this->migrasi_data->cek_ulang_data_mentah( date("Y-m-d"), $temp->id, "insert", false);
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
		$this->load->library('migrasi_data');
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
				if($this->migrasi_data->count_data_mentah_pegawai( date("Y-m-d"), $temp->id)->jumlah == 0 ){
					$this->migrasi_data->cek_ulang_data_mentah( date("Y-m-d"), $temp->id, "insert", false);
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
		$this->load->library('migrasi_data');
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
				if($this->migrasi_data->count_data_mentah_pegawai( date("Y-m-d"), $temp->id)->jumlah == 0 ){
					$this->migrasi_data->cek_ulang_data_mentah( date("Y-m-d"), $temp->id, "insert", false);
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
		$this->load->library('migrasi_data');
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
				if($this->migrasi_data->count_data_mentah_pegawai( date("Y-m-d"), $temp->id)->jumlah == 0 ){
					$this->migrasi_data->cek_ulang_data_mentah( date("Y-m-d"), $temp->id, "insert", false);
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
		$this->load->library('migrasi_data');
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
				if($this->migrasi_data->count_data_mentah_pegawai( date("Y-m-d"), $temp->id)->jumlah == 0 ){
					$this->migrasi_data->cek_ulang_data_mentah( date("Y-m-d"), $temp->id, "insert", false);
				}
				else{
					$this->migrasi_data->cek_ulang_data_mentah( date("Y-m-d"), $temp->id, "update", false);
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
		$this->load->library('migrasi_data');
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
				if($this->migrasi_data->count_data_mentah_pegawai( date("Y-m-d"), $temp->id)->jumlah == 0 ){
					$this->migrasi_data->cek_ulang_data_mentah( date("Y-m-d"), $temp->id, "insert", false);
				}
				else{
					$this->migrasi_data->cek_ulang_data_mentah( date("Y-m-d"), $temp->id, "update", false);
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
		$this->load->library('migrasi_data');
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
				if($this->migrasi_data->count_data_mentah_pegawai( date("Y-m-d"), $temp->id)->jumlah == 0 ){
					$this->migrasi_data->cek_ulang_data_mentah( date("Y-m-d"), $temp->id, "insert", false);
				}
				else{
					$this->migrasi_data->cek_ulang_data_mentah( date("Y-m-d"), $temp->id, "update", false);
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
		$this->load->library('migrasi_data');
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
				if($this->migrasi_data->count_data_mentah_pegawai( date("Y-m-d"), $temp->id)->jumlah == 0 ){
					$this->migrasi_data->cek_ulang_data_mentah( date("Y-m-d"), $temp->id, "insert", false);
				}
				else{
					$this->migrasi_data->cek_ulang_data_mentah( date("Y-m-d"), $temp->id, "update", false);
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
	// ################################################################################################################
	// END CRONTAB SERVER (BUAT BACKUP lap_absensi_lembur_opt)

	// ==============================================fungsi generate data mentah======================
	function GeneratePerPegawaiManual()
	{
		$this->load->library('migrasi_data');
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

			for($i = $begin; $i <= $end; $i->modify('+1 day')){

				if($this->migrasi_data->count_data_mentah_pegawai( $i->format("Y-m-d"), $id_pegawai)->jumlah == 0){
					$this->migrasi_data->cek_ulang_data_mentah($i->format("Y-m-d"), $id_pegawai, "insert", false, $dt_pegawai->meninggal, $dt_pegawai->tgl_meninggal);
				}
				else{
					$this->migrasi_data->cek_ulang_data_mentah($i->format("Y-m-d"), $id_pegawai, "update", false, $dt_pegawai->meninggal, $dt_pegawai->tgl_meninggal);
				}
			}
			//return true;
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

			if($generate) {
				$this->load->model('monitoring_tarik_model');
				$tgl_mulai   = $this->input->post('tgl_mulai');
				$tgl_selesai = $this->input->post('tgl_akhir');

				//jika login sebagai dispendik, jadikan dispendik tpp
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
							h.kode_unor, h.tgl_mulai, muok.nama as nama_unor, mi.kode as kode_instansi, mi.nama as nama_instansi, h.excel
							FROM
							m_pegawai_unit_kerja_histori h
							LEFT JOIN m_unit_organisasi_kerja muok ON  h.kode_unor =  muok.kode
							LEFT JOIN m_instansi mi ON  muok.kode_instansi = mi.kode WHERE h.tgl_mulai <= '".date('Y-m-d')."' and m.id = h.id_pegawai
							ORDER BY h.tgl_mulai DESC LIMIT 1
							)
							pukh ON true
						where
							pukh.kode_instansi = '".$instansiRaw."'".$whereStatus." and pukh.excel = 't'
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
				$this->antrian_generate_model->insert($dt_ins);

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
		$tanggal_mulai_kunci = $this->input->get('tahun') . '-' . $this->input->get('bulan') . '-' . '01';
		$tanggal_akhir_kunci = date("Y-m-t", strtotime($tanggal_mulai_kunci));

		//hardcode tidak bisa update jika tahun 2018
		//batasan CUTOFF LAPORAN
		$tgl_batas2 	= "2019-01-01";
		$hariBatas2		= strtotime($tgl_batas2);
		$hari_generate = strtotime($tanggal_akhir_kunci);

		if ($hari_generate < $hariBatas2 ){
			if ($this->session->userdata('id_kategori_karyawan') == '1') {
				$laporanTerkunci = false;
			}else{
				$laporanTerkunci = true;
			}
		}
		else
		{
			$laporanTerkunci	= $this->db->query("
				select * from log_laporan
				where to_char(tgl_log, 'YYYY-MM') = '$whereTahunBulan'
				and kd_instansi = '$id_instansi_get'
				and is_kunci = 'Y'
			")->row_array();
		}

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
            $wherePns 	= " and m.kode_status_pegawai < '5'";
        }
        else{

            $wherePns 	= " and m.kode_status_pegawai >='5'";
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

		/** JIKA DINAS PENDIDIKAN!!! */
		if ($this->input->get('id_instansi') == '5.09.00.93.00') {
			# start nambah status meninggal
			$queryPegawai 	=	$this->db->query("
				select
					m.id as id_pegawai,m.nama, m.nip, m.meninggal, m.tgl_meninggal,
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
							h.kode_unor, h.tgl_mulai, muok.nama as nama_unor, mi.kode as kode_instansi, mi.nama as nama_instansi, h.excel, h.langsung_pindah
						FROM
							m_pegawai_unit_kerja_histori h
							LEFT JOIN m_unit_organisasi_kerja muok ON  h.kode_unor =  muok.kode
							LEFT JOIN m_instansi mi ON  muok.kode_instansi = mi.kode 
							WHERE h.tgl_mulai <= '".$tanggal."' and m.id = h.id_pegawai or (h.langsung_pindah = 't' and h.id_pegawai = m.id)
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
					".$whereQuery." and pukh.excel = 't'
				order by
					pjh.urut,
					peh.kode_eselon,
					pgh.kode_golongan desc,
					m.nip

			");
			# end nambah status meninggal
		} else {
			# start nambah status meninggal
			$queryPegawai 	=	$this->db->query("
				select
					m.id as id_pegawai,m.nama, m.nip, m.meninggal, m.tgl_meninggal,
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
							h.kode_unor, h.tgl_mulai, muok.nama as nama_unor, mi.kode as kode_instansi, mi.nama as nama_instansi, h.langsung_pindah
						FROM
							m_pegawai_unit_kerja_histori h
							LEFT JOIN m_unit_organisasi_kerja muok ON  h.kode_unor =  muok.kode
							LEFT JOIN m_instansi mi ON  muok.kode_instansi = mi.kode 
							WHERE h.tgl_mulai <= '".$tanggal."' and m.id = h.id_pegawai or (h.langsung_pindah = 't' and h.id_pegawai = m.id)
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
			# end nambah status meninggal
		}

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

			$query_lembur	= "
				SELECT tanggal, lembur,lembur_diakui
				FROM data_mentah
				WHERE id_pegawai = '$id_pegawai'
				AND tanggal >= '$mulai' AND tanggal <= '$akhir'
				order by tanggal asc
			";

			$data_lembur = $this->db->query($query_lembur)->result();

			$h_lembur = array();
			foreach ($data_lembur as $l) {
				$h_lembur[$l->tanggal] = $l;
			}
			for($i = $begin; $i <= $end; $i->modify('+1 day')){

                //$queryJumlahLembur	=	$this->db->query("select lembur,lembur_diakui from data_mentah where id_pegawai='".$id_pegawai."' and tanggal='".$i->format("Y-m-d")."'");
                //$dataHasilLembur	=	$queryJumlahLembur->row();
				$dataHasilLembur	=	$h_lembur[$i->format("Y-m-d")];

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
                $where	=	"and jenis = 'BIASA-2019'";
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
                'urut'			=> $this->input->post('urut2')
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

	function info(){
		phpinfo();
	}
}
