<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ijin_cuti_pegawai extends CI_Controller {



	public function __construct() {
		parent::__construct();

		$this->load->model('jenis_ijin_cuti_model');
		$this->load->model('t_ijin_cuti_model');
		$this->load->model('data_mentah_model');
		$this->load->model('global_model');
	}

	public function index(){
		redirect($this->uri->segment(1)."/add");
	}

	public function add(){

		if ($this->session->userdata('kode_instansi') == '5.16.00.00.00') {
			$where = "";
		}else{
			$where = "os is null";
		}

		$this->dataJenisIjinCuti 	= $this->jenis_ijin_cuti_model->showData("$where","","nama");
		$this->template_view->load_view('ijin_cuti_pegawai/ijin_cuti_pegawai_view');
		// if ($this->session->userdata('id_kategori_karyawan') == '1') {
		// 	$this->template_view->load_view('ijin_cuti_pegawai/ijin_cuti_pegawai_view');
		// }else{
		// 	$this->template_view->load_view('template/sedang-perbaikan');
		// }
	}

	public function edit(){
		$IdPrimaryKey	=	$this->input->get('id_t_ijin');
		$where 			=	"t_ijin_cuti_pegawai.id = '".$IdPrimaryKey."' ";
		$this->oldData 	= $this->t_ijin_cuti_model->getData($where,"","");
		if(!$this->oldData){
			redirect($this->uri->segment(1));
		}

		$this->dataJenisIjinCuti 	= $this->jenis_ijin_cuti_model->showData("","","nama");

		$url 	= 	"https://". $_SERVER['SERVER_NAME'] . ":" . $_SERVER['REQUEST_URI'];
		$url 	=	explode("daftar_ijin_cuti_pegawai",$url);
		$this->url 			=	$url[1];
		$this->instansi = $this->db->query("
			select
				m.id as id_pegawai,m.nama, m.nip, m.meninggal, m.tgl_meninggal,
					pukh.nama_unor,
					pukh.nama_instansi
			from
				m_pegawai m
				LEFT JOIN m_jenis_jabatan mjb ON mjb.kode=m.kode_jenis_jabatan
				LEFT JOIN LATERAL (
					SELECT
						h.kode_unor, h.tgl_mulai, muok.nama as nama_unor, mi.kode as kode_instansi, mi.nama as nama_instansi
					FROM
						m_pegawai_unit_kerja_histori h
						LEFT JOIN m_unit_organisasi_kerja muok ON  h.kode_unor =  muok.kode
						LEFT JOIN m_instansi mi ON  muok.kode_instansi = mi.kode WHERE h.tgl_mulai <= '".$this->oldData->tgl_mulai_insert."' and m.id = h.id_pegawai
					ORDER BY h.tgl_mulai DESC LIMIT 1
				)pukh on true
			where
				m.id = '".$this->oldData->id_pegawai."'
		")->row();


		$this->template_view->load_view('ijin_cuti_pegawai/ijin_cuti_pegawai_edit_view');
	}

	public function add_data(){
		$this->form_validation->set_rules('id_pegawai', '', 'trim|required');

		if ($this->form_validation->run() == FALSE)
		{
			$status = array('status' => FALSE, 'pesan' => 'Gagal menyimpan Data, pastikan telah mengisi semua inputan.');
		}
		else
		{
			//jika lampiran kosong
			if($this->input->post('file_lampiran')=='')
			{
				$status = array('status' => false , 'pesan' => 'Silahkan Upload file Lampiran terlebih dahulu,');
			}
			else
			{
				// jika kategori administrator (pak HADI BKD)
				if ($this->session->userdata('id_kategori_karyawan') == '2' || $this->session->userdata('username') == 'rizky')
				{
					$cek_kunci = false;
				}else{
					//cek kunci upload
					$cek_kunci = $this->db->query("select * from t_kunci_upload where kode_instansi = '".$this->session->userdata('kode_instansi')."'")->row();
				}

				//batasan CUTOFF LAPORAN
				$tgl_batas 		= "2019-01-01";
				$time 			= strtotime($this->input->post('tgl_mulai'));
				$batas_cutoff_time	= strtotime($tgl_batas);
				$tgl_mulai 		= date('Y-m-d',$time);
				$tgl_akhir 		= date('Y-m-t',$time);

				//jika tanggal ijin lebih dari tgl cut off tentukan method kunci
				if ($time >= $batas_cutoff_time ){
					$kunci_bulanan = true;
					$kunci_ceklis  = false;
					$cutoff = false;
				} else {
					$kunci_bulanan = false;
					$kunci_ceklis  = true;
					$cutoff = true;
				}

				if($this->session->userdata('username') == 'rizky') {
					$kunci_bulanan = false;
					$kunci_ceklis  = false;
					$cutoff = false;
				}

				//jika kunci
				if ($kunci_ceklis)
				{
					$cek_kunci = $this->db->query("select * from t_kunci_upload where kode_instansi = '".$this->session->userdata('kode_instansi')."'")->row();
				}
				elseif ($kunci_bulanan)
				{
					$cek_kunci = $this->db->query("
				 		select * from log_laporan
				 		where tgl_log = '".$tgl_akhir."' and
				 		kd_instansi = '".$this->session->userdata('kode_instansi')."' and
				 		is_kunci = 'Y' and
				 		time_stamp_buka is null
				 	")->row();
				}

				// jika tidak dikunci
				if (!$cek_kunci)
				{
					if ($cutoff) {
						$status = array('status' => false , 'pesan' => 'Proses Simpan gagal, dikarenakan data sudah dikunci');
						echo(json_encode($status));
						exit;
					}

					$time2 					= strtotime($this->input->post('tgl_selesai'));
					$tgl_selesai 			= date('Y-m-d',$time2);
					$tgl_selesai_insert 	= date('Y-m-d',$time2)." 23:59:00";
					$time3 					= strtotime($this->input->post('tgl_surat'));
					$tgl_surat 				= date('Y-m-d',$time3);
					$tglInsert 				= date('Y-m-d h:i:s');
					$dt1 					= new DateTime($tgl_mulai);
					$dt2 					= new DateTime($tgl_selesai);
					$sekarang				= date("Y-m-d");
					$dt3 					= new DateTime($sekarang);
					$jumlahHari 			= $dt1->diff($dt2);
					$jumlahHariUpload 		= $dt1->diff($dt3);
					$harisekarang			= strtotime(date("Y-m-d"));
					
					$counter				= 0;
					$btsMaxUpload 			= date('Y-m-d', $time2 + ((60 * 60 * 24) * 4));
					for ($i=$time2; $i <= strtotime($btsMaxUpload); $i += (60 * 60 * 24)) 
				 	{
				 		$cek_hari_besar = $this->cek_hari_libur(date('Y-m-d',$i));
				 		if ($cek_hari_besar) {
				 			$counter++;
				 		}elseif (date('w', $i) == '0'){
				 			$counter++;
				 		}elseif (date('w', $i) == '6'){
				 			$counter = $counter + 2;
				 		}
				 	}

				 	$finalMaxUpload 			= date('Y-m-d', $time2 + (60 * 60 * 24) * (4 + $counter));
				 	
				 	//cek jika hari maks di sabtu / minggu
				 	if (date('w',strtotime($finalMaxUpload)) == '0') {
				 		$counter = $counter + 1; 
				 		$maxUploadExisting = new DateTime($finalMaxUpload);
						$maxUploadExisting->modify('+1 day');
						$finalMaxUpload = $maxUploadExisting->format('Y-m-d');
				 	}elseif (date('w',strtotime($finalMaxUpload)) == '6') {
				 		$counter = $counter + 2;
				 		$maxUploadExisting = new DateTime($finalMaxUpload);
						$maxUploadExisting->modify('+2 day');
						$finalMaxUpload = $maxUploadExisting->format('Y-m-d');
				 	}

				 	/*if (date('w',strtotime($finalMaxUpload)) == '6') {
				 		$counter = $counter + 2; 
				 		$maxUploadExisting = new DateTime($finalMaxUpload);
						$maxUploadExisting->modify('+2 day');
						$finalMaxUpload = $maxUploadExisting->format('Y-m-d');
				 	}elseif (date('w',strtotime($finalMaxUpload)) == '0') {
				 		$counter = $counter + 1;
				 		$maxUploadExisting = new DateTime($finalMaxUpload);
						$maxUploadExisting->modify('+1 day');
						$finalMaxUpload = $maxUploadExisting->format('Y-m-d');
				 	}*/

					// batasan untuk aturan h+3 harian
					$tgl_batas2 	= "2019-01-01";
					$hariBatas2		= strtotime($tgl_batas2);

					$cek_kunci_tiga_hari = $this->db->query("select * from t_kunci_tiga_hari where kode_instansi = '".$this->session->userdata('kode_instansi')."'")->row();

					//==================================================== ATURAN LAMA ===========================================================
					
					/*if ($this->session->userdata('id_kategori_karyawan') <> '11') {
						if ($cek_kunci_tiga_hari) {
							if ($time >= $hariBatas2 ){
								// jika lebih harinya lebih dari sekarang
								if ($time < $harisekarang)
								{
								 	$iSeninJumat=0;
								 	for ($i=$time; $i <= $harisekarang; $i += (60 * 60 * 24)) 
								 	{
								 		$cek_hari_besar = $this->cek_hari_libur(date('Y-m-d',$i));
								 		if (!$cek_hari_besar) {
								 			if (date('w', $i) !== '0' && date('w', $i) !== '6') {
									 			$iSeninJumat++;
									 		}
								 		}
								 	}

								 	if($iSeninJumat > 4){
							 			$status = array('status' => false , 'pesan' => 'Proses Simpan gagal, dikarenakan Hari Kejadian sudah lebih dari 3 hari Kerja.');
										echo(json_encode($status));
							 			exit;
									}
								}
							}
						}
					}*/
					
					//==================================================== ATURAN BARU ===========================================================
					if ($this->session->userdata('id_kategori_karyawan') <> '11') {
						if ($cek_kunci_tiga_hari) {
							if (strtotime($finalMaxUpload) <= $harisekarang) {
								$status = array('status' => false , 'pesan' => 'Proses Simpan gagal, dikarenakan batas maksimal upload adalah '.date('d-m-Y', strtotime($finalMaxUpload)).' (3 hari kerja setelah aktif bekerja).');
								echo(json_encode($status));
					 			exit;
							}
						}
					}

					$this->load->library('encrypt_decrypt');

					$data = array(
						'id' 					=> $this->encrypt_decrypt->new_id(),
						'jml_hari' 				=> $jumlahHari->days,
						'keterangan' 			=> $this->input->post('keterangan'),
						'kode_usulan' 			=> $this->input->post('kode_usulan'),
						'no_surat' 				=> $this->input->post('no_surat'),
						'tgl_mulai' 			=> $tgl_mulai,
						'tgl_selesai' 			=> $tgl_selesai_insert,
						'tgl_surat' 			=> $tgl_surat,
						'userupd' 				=> $_SESSION['id_karyawan'],
						'id_jenis_ijin_cuti' 	=> $this->input->post('id_jenis_ijin_cuti'),
						'id_pegawai' 			=> $this->input->post('id_pegawai'),
						'file_lampiran' 		=> $this->input->post('file_lampiran'),
						'status'				=> '1'
					);

					#LOG START
					$data_log = [
						'id_user'			=> $this->session->userdata()['id_karyawan'],
						'aksi'				=> 'ADD IJIN CUTI',
						'tanggal'			=> date('Y-m-d H:i:s'),
						'data'				=> json_encode($data),
						'file_lampiran'		=> ($this->input->post('file_lampiran'))?$this->input->post('file_lampiran'):null
					];
					$this->global_model->save($data_log,'log_tekocak');
					#LOG FINISH

					$query = $this->t_ijin_cuti_model->insert($data);
					$id_pegawai = $this->input->post('id_pegawai');
					if ($time == $time2)
					{
						$mulai = $tgl_mulai;
						$selesai = date("Y-m-d", strtotime("+1 days", strtotime($tgl_selesai)));
					}else{
						$mulai = $tgl_mulai;
						$selesai = $tgl_selesai;
					}
					//$this->load->library('migrasi_data');
					$generate_ijin = $this->GeneratePerPegawaiIjin($id_pegawai, $mulai, $selesai);
					$status = array('status' => $generate_ijin , 'redirect_link' => base_url()."".$this->uri->segment(1));
				}
				//jika dikunci ceklis
				else
				{
					$status = array('status' => false , 'pesan' => 'Proses Simpan gagal, dikarenakan data sudah dikunci.');
					echo(json_encode($status));
		 			exit;
				}

			}
		}

		echo(json_encode($status));
	}


	public function edit_data(){
		$this->form_validation->set_rules('id_pegawai', '', 'trim|required');
		$this->form_validation->set_rules('id_t_ijin', '', 'trim|required');

		if ($this->form_validation->run() == FALSE)	{
			$status = array('status' => FALSE, 'pesan' => 'Gagal menyimpan Data, pastikan telah mengisi semua inputan.');
		}
		else
		{
			if($this->input->post('file_lampiran')=='')
			{
				$status = array('status' => false , 'pesan' => 'Silahkan Upload file Lampiran terlebih dahulu,');
			}
			else
			{
				// jika kategori administrator (pak HADI BKD)
				if ($this->session->userdata('id_kategori_karyawan') == '2')
				{
					$cek_kunci = false;
				}else{
					//cek kunci upload
					$cek_kunci = $this->db->query("select * from t_kunci_upload where kode_instansi = '".$this->session->userdata('kode_instansi')."'")->row();
				}

				//batasan CUTOFF LAPORAN
				$tgl_batas 		= "2018-12-01";
				$time 			= strtotime($this->input->post('tgl_mulai'));
				$batas_cutoff_time	= strtotime($tgl_batas);
				$tgl_mulai 		= date('Y-m-d',$time);
				$tgl_akhir 		= date('Y-m-t',$time);

				//jika tanggal ijin lebih dari tgl cut off tentukan method kunci
				if ($time > $batas_cutoff_time ){
					$kunci_bulanan = true;
					$kunci_ceklis  = false;
				} else {
					$kunci_bulanan = false;
					$kunci_ceklis  = true;
				}

				//jika kunci
				if ($kunci_ceklis)
				{
					$cek_kunci = $this->db->query("select * from t_kunci_upload where kode_instansi = '".$this->session->userdata('kode_instansi')."'")->row();
				}
				elseif ($kunci_bulanan)
				{
					$cek_kunci = $this->db->query("
				 		select * from log_laporan
				 		where tgl_log = '".$tgl_akhir."' and
				 		kd_instansi = '".$this->session->userdata('kode_instansi')."' and
				 		is_kunci = 'Y' and
				 		time_stamp_buka is null
				 	")->row();
				}

				// jika tidak dikunci
				if (!$cek_kunci) {
					$time2 					= strtotime($this->input->post('tgl_selesai'));
					$tgl_selesai 			= date('Y-m-d',$time2);
					$tgl_selesai_insert 	= date('Y-m-d',$time2)." 23:59:00";
					$time3 					= strtotime($this->input->post('tgl_surat'));
					$tgl_surat 				= date('Y-m-d',$time3);
					$tglInsert 				= date('Y-m-d h:i:s');
					$dt1 					= new DateTime($tgl_mulai);
					$dt2 					= new DateTime($tgl_selesai);
					$sekarang				= date("Y-m-d");
					$dt3 					= new DateTime($sekarang);
					$jumlahHari 			= $dt1->diff($dt2);
					$jumlahHariUpload 		= $dt1->diff($dt3);
					$harisekarang			= strtotime(date("Y-m-d"));
					
					$counter				= 0;
					$btsMaxUpload 			= date('Y-m-d', $time2 + ((60 * 60 * 24) * 4));
					for ($i=$time2; $i <= strtotime($btsMaxUpload); $i += (60 * 60 * 24)) 
				 	{
				 		$cek_hari_besar = $this->cek_hari_libur(date('Y-m-d',$i));
				 		if ($cek_hari_besar) {
				 			$counter++;
				 		}elseif (date('w', $i) == '0'){
				 			$counter++;
				 		}elseif (date('w', $i) == '6'){
				 			$counter = $counter + 2;
				 		}
				 	}

				 	$finalMaxUpload 			= date('Y-m-d', $time2 + (60 * 60 * 24) * (4 + $counter));
				 	
				 	//cek jika hari maks di sabtu / minggu
				 	if (date('w',strtotime($finalMaxUpload)) == '0') {
				 		$counter = $counter + 1; 
				 		$maxUploadExisting = new DateTime($finalMaxUpload);
						$maxUploadExisting->modify('+1 day');
						$finalMaxUpload = $maxUploadExisting->format('Y-m-d');
				 	}elseif (date('w',strtotime($finalMaxUpload)) == '6') {
				 		$counter = $counter + 2;
				 		$maxUploadExisting = new DateTime($finalMaxUpload);
						$maxUploadExisting->modify('+2 day');
						$finalMaxUpload = $maxUploadExisting->format('Y-m-d');
				 	}

					// batasan untuk aturan h+3 harian
					$tgl_batas2 	= "2019-01-01";
					$hariBatas2		= strtotime($tgl_batas2);

					$cek_kunci_tiga_hari = $this->db->query("select * from t_kunci_tiga_hari where kode_instansi = '".$this->session->userdata('kode_instansi')."'")->row();

					//==================================================== ATURAN LAMA ===========================================================

					//jika tgl inputan diatas januari 2019 maka aturan h+3 akan dijalankan
					/*if ($this->session->userdata('id_kategori_karyawan') <> '11') {
						if ($cek_kunci_tiga_hari) {
							if ($time >= $hariBatas2 ){
								// jika lebih harinya lebih dari sekarang
								if ($time < $harisekarang)
								{
								 	$iSeninJumat=0;
								 	for ($i=$time; $i <= $harisekarang; $i += (60 * 60 * 24)) 
								 	{
								 		$cek_hari_besar = $this->cek_hari_libur(date('Y-m-d',$i));
								 		if (!$cek_hari_besar) {
								 			if (date('w', $i) !== '0' && date('w', $i) !== '6') {
									 			$iSeninJumat++;
									 		}
								 		}
								 	}

								 	if($iSeninJumat > 4){
							 			$status = array('status' => false , 'pesan' => 'Proses Simpan gagal, dikarenakan batas maksimal upload adalah '.date('d-m-Y', strtotime($finalMaxUpload)).' (3 hari kerja setelah aktif bekerja.).');
										echo(json_encode($status));
							 			exit;
									}
								}
							}
						}
					}*/

					//==================================================== ATURAN BARU ===========================================================
					if ($this->session->userdata('id_kategori_karyawan') <> '11') {
						if ($cek_kunci_tiga_hari) {
							if (strtotime($finalMaxUpload) <= $harisekarang) {
								$status = array('status' => false , 'pesan' => 'Proses Simpan gagal, dikarenakan batas maksimal upload adalah '.date('d-m-Y', strtotime($finalMaxUpload)).' (3 hari kerja dari tanggal akhir kejadian).');
								echo(json_encode($status));
					 			exit;
							}
						}
					}

					$this->load->library('encrypt_decrypt');

					$data = array(
						'jml_hari' 				=> $jumlahHari->days,
						'keterangan' 			=> $this->input->post('keterangan'),
						'kode_usulan' 			=> $this->input->post('kode_usulan'),
						'no_surat' 				=> $this->input->post('no_surat'),
						'tgl_mulai' 			=> $tgl_mulai,
						'tgl_selesai' 			=> $tgl_selesai_insert,
						'tgl_surat' 			=> $tgl_surat,
						'userupd' 				=> $_SESSION['id_karyawan'],
						'id_jenis_ijin_cuti' 	=> $this->input->post('id_jenis_ijin_cuti'),
						'id_pegawai' 			=> $this->input->post('id_pegawai'),
						'file_lampiran' 		=> $this->input->post('file_lampiran')
					);

					$where = array(
						't_ijin_cuti_pegawai.id' => $this->input->post('id_t_ijin')
					);

					//$this->load->library('migrasi_data');

					$dataIjin	=	$this->t_ijin_cuti_model->getData($where);

					#LOG START
					$ijin		=	$this->global_model->get_by_id('t_ijin_cuti_pegawai',$where);
					$data_log = [
							'id_user'			=> $this->session->userdata()['id_karyawan'],
							'aksi'				=> 'EDIT IJIN CUTI',
							'tanggal'			=> date('Y-m-d H:i:s'),
							'data'				=> json_encode($ijin),
							'file_lampiran'		=> ($ijin->file_lampiran)?$ijin->file_lampiran:null,
							'lampiran_blob'		=> ($ijin->lampiran)?$ijin->lampiran:null,
							'lampiran_blob_type'=> ($ijin->lampiran_type)?$ijin->lampiran_type:null
						];
					$this->global_model->save($data_log,'log_tekocak');
					#LOG FINISH

					$query = $this->t_ijin_cuti_model->update($where,$data);

					$id_pegawai = $this->input->post('id_pegawai');
					if ($time == $time2)
					{
						$mulai = $tgl_mulai;
						$selesai = date("Y-m-d", strtotime("+1 days", strtotime($tgl_selesai)));
					}else{
						$mulai = $tgl_mulai;
						$selesai = $tgl_selesai;
					}
					//$this->load->library('migrasi_data');
					$generate_ijin = $this->GeneratePerPegawaiIjin($id_pegawai, $mulai, $selesai);
					$status = array('status' => $generate_ijin , 'redirect_link' => base_url()."".$this->uri->segment(1));
				}
				else //jika ada kuncian
				{
					$status = array('status' => false , 'pesan' => 'Proses Simpan gagal, dikarenakan data sudah dikunci.');
					echo(json_encode($status));
		 			exit;
				}
			}
		}

		echo(json_encode($status));
	}


	function GeneratePerPegawaiIjin($id_pegawai, $mulai, $akhir)
	{
		$this->load->library('migrasi_data');

		$where         = "id = '".$id_pegawai."' ";
		$dt_pegawai = $this->pegawai_model->getData($where);

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
				if($this->migrasi_data->count_data_mentah_pegawai( $i->format("Y-m-d"), $id_pegawai)->jumlah == 0){
					$this->migrasi_data->cek_ulang_data_mentah($i->format("Y-m-d"), $id_pegawai, "insert", false, $lbr, $iz, $jdwl, $dt_pegawai->meninggal, $dt_pegawai->tgl_meninggal);
				}
				else{
					$this->migrasi_data->cek_ulang_data_mentah($i->format("Y-m-d"), $id_pegawai, "update", false, $lbr, $iz, $jdwl, $dt_pegawai->meninggal, $dt_pegawai->tgl_meninggal);
				}
			}
			return true;
		}else{
			return false;
		}
	}

	function cek_hari_libur($date){
	    $cek_hari_libur =   $this->db->query("select
	        s_hari_libur.id,
	        m_hari_libur.id as id_hari_libur,
	        m_hari_libur.nama
	      from
	        s_hari_libur ,m_hari_libur
	      where
	        s_hari_libur.tanggal = '".$date."'  and
	        s_hari_libur.id_libur = m_hari_libur.id");

	    return $cek_hari_libur->row();
	}

	public function cek_jadwal($mulai, $akhir, $id_pegawai)
	{
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
	}
		

}
