<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Lembur_pegawai extends CI_Controller {



	public function __construct() {
		parent::__construct();
		$this->load->model('jenis_ijin_cuti_model');
		$this->load->model('data_mentah_model');
		$this->load->model('lembur/t_lembur_model', 't_lembur');
		$this->load->model('global_model');

	}

	public function index(){
		redirect($this->uri->segment(1)."/add");
	}

	public function add(){

		// $this->dataJenisIjinCuti 	= $this->jenis_ijin_cuti_model->showData("","","nama");

		$this->template_view->load_view('lembur_pegawai/lembur_pegawai_view');
		/*if ($this->session->userdata('id_kategori_karyawan') == '1') {
			$this->template_view->load_view('lembur_pegawai/lembur_pegawai_view');
		}else{
			$pesan = array(
				'header' => 'Mohon Maaf sedang dalam proses update fitur baru.',
				'isi'	=> "Mohon kembali lagi kurang lebih <strong>15-30 menit</strong>. Terima Kasih" 
			);
			$this->pesan = $pesan;
			$this->template_view->load_view('template/sedang-perbaikan');
		}*/
	}

	public function edit(){
		$IdPrimaryKey	=	$this->input->get('id_t_ijin');
		$where 			=	"t_lembur_pegawai.id = '".$IdPrimaryKey."' ";
		$this->oldData 	= $this->t_lembur->getData($where,"","");
		if(!$this->oldData){
			redirect($this->uri->segment(1));
		}


		$this->dataJenisIjinCuti 	= $this->jenis_ijin_cuti_model->showData("","","nama");

		$url 	= 	"https://". $_SERVER['SERVER_NAME'] . ":" . $_SERVER['REQUEST_URI'];
		$url 	=	explode("daftar_lembur_pegawai",$url);
		$this->url 			=	$url[1];

		$this->template_view->load_view('lembur_pegawai/lembur_pegawai_edit_view');
	}

	public function add_data()
	{
		$pulang_esok = $this->input->post('pulang_besoknya');
		$pulang_esok = ($pulang_esok == '') ? false : true ;

		$this->form_validation->set_rules('id_pegawai', '', 'trim|required');

		if ($this->form_validation->run() == FALSE)
		{
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
				$tgl_batas 			= "2019-01-01";
				$time 				= strtotime($this->input->post('tgl_lembur'));
				$batas_cutoff_time	= strtotime($tgl_batas);
				$tgl_lembur 		= date('Y-m-d',$time);
				$tgl_akhir 			= date('Y-m-t',$time);

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

				//cek method mana yg dipakai
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
					
					$waktu1 		= $this->input->post('jam_mulai');
					$waktu2 		= $this->input->post('jam_selesai');
					$data11 		= $tgl_lembur.' '.$waktu1.':00';

					if ($pulang_esok) {
						$tgl_lembur_esok 	= date('Y-m-d',strtotime($tgl_lembur . "+1 days"));
						$data22 					= $tgl_lembur_esok.' '.$waktu2.':00';

						//cek jam scan pulang per tanggal lembur
						$cek_jadwal_esok = $this->cek_scan_jam_kerja($this->input->post('id_pegawai'), $tgl_lembur, $tgl_lembur_esok, $data22);
						if ($cek_jadwal_esok['status'] === false) {
							$status = array('status' => false , 'pesan' => $cek_jadwal_esok['pesan']);
							echo(json_encode($status));
							exit;
						}
						//end cek jam scan pulang per tanggal lembur
					}else{
						//jika jam akhir lembur lebih kecil dari awal dan tidak ada ceklis
						if (strtotime($this->input->post('jam_mulai')) >= strtotime($this->input->post('jam_selesai'))) {
							$status = array('status' => false , 'pesan' => 'Proses Simpan gagal, dikarenakan jam akhir lembur lebih kecil dari jam awal dan tidak memilih pilihan pulang hari besoknya.');
							echo(json_encode($status));
							exit;
						}
						$data22 		= $tgl_lembur.' '.$waktu2.':00';
					}

					$waktu_awal		= $data11;
					$waktu_akhir	= $data22;
					$time3 			= strtotime($this->input->post('tgl_surat'));
					$tgl_surat 		= date('Y-m-d',$time3);
					$tglInsert 		= date('Y-m-d h:i:s');
					$harisekarang			= strtotime(date("Y-m-d"));
					//batasan untuk aturan h+3 harian
					$tgl_batas2 	= "2019-01-01";
					$hariBatas2		= strtotime($tgl_batas2);

					$counter				= 0;
					$btsMaxUpload 			= date('Y-m-d', $time + ((60 * 60 * 24) * 4));
					for ($i=$time; $i <= strtotime($btsMaxUpload); $i += (60 * 60 * 24)) 
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

				 	$finalMaxUpload 			= date('Y-m-d', $time + (60 * 60 * 24) * (4 + $counter));
				 	
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

				 	$cek_kunci_tiga_hari = $this->db->query("select * from t_kunci_tiga_hari where kode_instansi = '".$this->session->userdata('kode_instansi')."'")->row();
					//==================================================== ATURAN LAMA ===========================================================

					//jika tgl inputan diatas januari 2019 maka aturan h+3 akan dijalankan
					//jika kategori user bukan KPI kominfo, 
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
								$status = array('status' => false , 'pesan' => 'Proses Simpan gagal, dikarenakan batas maksimal upload adalah '.date('d-m-Y', strtotime($finalMaxUpload)).' (3 hari kerja dari tanggal akhir kejadian).');
								echo(json_encode($status));
					 			exit;
							}
						}
					}

					$this->load->library('encrypt_decrypt');

					$data = array(
						'id' 					=> $this->encrypt_decrypt->new_id(),
						'keterangan' 			=> $this->input->post('keterangan'),
						'no_surat' 				=> $this->input->post('no_surat'),
						'tgl_lembur' 			=> $tgl_lembur,
						'tgl_surat' 			=> $tgl_surat,
						'userupd' 				=> $_SESSION['id_karyawan'],
						'id_pegawai' 			=> $this->input->post('id_pegawai'),
						'file_lampiran' 		=> $this->input->post('file_lampiran'),
						'jam_lembur_awal' 		=> $waktu_awal,
						'jam_lembur_akhir' 		=> $waktu_akhir,
						'timeupd'				=> $tglInsert,
						'status'				=> '1',
						'pulang_besoknya' 		=> $this->input->post('pulang_besoknya')
					);

					#LOG START
					$data_log = [
						'id_user'			=> $this->session->userdata()['id_karyawan'],
						'aksi'				=> 'ADD LEMBUR PEGAWAI',
						'tanggal'			=> date('Y-m-d H:i:s'),
						'data'				=> json_encode($data),
						'file_lampiran'		=> ($this->input->post('file_lampiran'))?$this->input->post('file_lampiran'):null
					];
					$this->global_model->save($data_log,'log_tekocak');
					#LOG FINISH

					/*$query 			 			= $this->t_lembur->insert($data);
					$date_lembur 				= new DateTime( $tgl_lembur );
					$this->load->library('migrasi_data');

					$this->migrasi_data->cek_ulang_data_mentah($date_lembur->format("Y-m-d"), $this->input->post('id_pegawai'), "update", false);
					$status = array('status' => true , 'redirect_link' => base_url()."".$this->uri->segment(1));*/
					$query = $this->t_lembur->insert($data);
					$id_pegawai = $this->input->post('id_pegawai');
					$mulai = $tgl_lembur;
					$selesai = date("Y-m-d", strtotime("+1 days", strtotime($mulai)));

					//$this->load->library('migrasi_data');
					$generate_ijin = $this->GeneratePerPegawaiLembur($id_pegawai, $mulai, $selesai);
					$status = array('status' => $generate_ijin , 'redirect_link' => base_url()."".$this->uri->segment(1));
				}
				else  //jika ada kuncian
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
		$pulang_esok = $this->input->post('pulang_besoknya');
		$pulang_esok = ($pulang_esok == '') ? false : true ;
		$this->form_validation->set_rules('id_pegawai', '', 'trim|required');
		$this->form_validation->set_rules('id_t_lembur', '', 'trim|required');

		if ($this->form_validation->run() == FALSE)
		{
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
				$time 			= strtotime($this->input->post('tgl_lembur'));
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


				//jika tidak dikunci
				if (!$cek_kunci)
				{
					$time 					= strtotime($this->input->post('tgl_lembur'));
					$tgl_lembur 			= date('Y-m-d',$time);
					$waktu1 				= $this->input->post('jam_mulai');
					$waktu2 				= $this->input->post('jam_selesai');
					$data11 				= $tgl_lembur.' '.$waktu1.':00';

					if ($pulang_esok) {
						$tgl_lembur_esok 	= date('Y-m-d',strtotime($tgl_lembur . "+1 days"));
						$data22 					= $tgl_lembur_esok.' '.$waktu2.':00';
						//cek jam scan pulang per tanggal lembur
						$cek_jadwal_esok = $this->cek_scan_jam_kerja($this->input->post('id_pegawai'), $tgl_lembur, $tgl_lembur_esok, $data22);
						if ($cek_jadwal_esok['status'] === false) {
							$status = array('status' => false , 'pesan' => $cek_jadwal_esok['pesan']);
							echo(json_encode($status));
							exit;
						}
						//end cek jam scan pulang per tanggal lembur
					}else{
						//jika jam akhir lembur lebih kecil dari awal dan tidak ada ceklis
						if (strtotime($this->input->post('jam_mulai')) >= strtotime($this->input->post('jam_selesai'))) {
							$status = array('status' => false , 'pesan' => 'Proses Simpan gagal, dikarenakan jam akhir lembur lebih kecil dari jam awal dan tidak memilih pilihan pulang hari besoknya.');
							echo(json_encode($status));
							exit;
						}
						$data22 					= $tgl_lembur.' '.$waktu2.':00';
					}

					$waktu_awal				= $data11;
					$waktu_akhir			= $data22;
					$time3 					= strtotime($this->input->post('tgl_surat'));
					$tgl_surat 				= date('Y-m-d',$time3);
					$tglInsert 				= date('Y-m-d h:i:s');
					$harisekarang			= strtotime(date("Y-m-d"));
					//batasan untuk aturan h+3 harian
					$tgl_batas2 			= "2019-01-01";
					$hariBatas2				= strtotime($tgl_batas2);

					$counter				= 0;
					$btsMaxUpload 			= date('Y-m-d', $time + ((60 * 60 * 24) * 4));
					for ($i=$time; $i <= strtotime($btsMaxUpload); $i += (60 * 60 * 24)) 
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

				 	$finalMaxUpload 			= date('Y-m-d', $time + (60 * 60 * 24) * (4 + $counter));
				 	
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
								$status = array('status' => false , 'pesan' => 'Proses Simpan gagal, dikarenakan batas maksimal upload adalah '.date('d-m-Y', strtotime($finalMaxUpload)).' (3 hari kerja dari tanggal akhir kejadian).');
								echo(json_encode($status));
					 			exit;
							}
						}
					}

					$this->load->library('encrypt_decrypt');

					$data = array(
						'keterangan' 			=> $this->input->post('keterangan'),
						'no_surat' 				=> $this->input->post('no_surat'),
						'tgl_lembur' 			=> $tgl_lembur,
						'tgl_surat' 			=> $tgl_surat,
						'userupd' 				=> $_SESSION['id_karyawan'],
						'id_pegawai' 			=> $this->input->post('id_pegawai'),
						'file_lampiran' 		=> $this->input->post('file_lampiran'),
						'jam_lembur_awal'		=> $waktu_awal,
						'jam_lembur_akhir'		=> $waktu_akhir,
						'timeupd'				=> $tglInsert,
						'status'				=> '1',
						'pulang_besoknya' 		=> $this->input->post('pulang_besoknya')
					);

					$where = array(
						'id' => $this->input->post('id_t_lembur')
					);

					$ijin		=	$this->global_model->get_by_id('t_lembur_pegawai',$where);
					#LOG START
					$data_log = [
						'id_user'			=> $this->session->userdata()['id_karyawan'],
						'aksi'				=> 'ADD LEMBUR PEGAWAI',
						'tanggal'			=> date('Y-m-d H:i:s'),
						'data'				=> json_encode($data),
						'file_lampiran'		=> ($this->input->post('file_lampiran'))?$this->input->post('file_lampiran'):null
					];
					$this->global_model->save($data_log,'log_tekocak');
					#LOG FINISH

					$query = $this->t_lembur->update($where,$data);
					$id_pegawai = $this->input->post('id_pegawai');
					$mulai = $tgl_lembur;
					$selesai = date("Y-m-d", strtotime("+1 days", strtotime($mulai)));
					//$this->load->library('migrasi_data');
					$generate_ijin = $this->GeneratePerPegawaiLembur($id_pegawai, $mulai, $selesai);
					$status = array('status' => $generate_ijin , 'redirect_link' =>  base_url()."daftar_lembur_pegawai".$this->input->post('redirect'));
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

	public function cek_scan_jam_kerja($id_pegawai, $mulai, $akhir, $dtakhirlembur)
	{
		$dtmulai = $mulai." 00:01";
		$dtakhir = $akhir." 23:59";
		$query_jadwal = "
			SELECT
				tgl::date as tanggal, jml_finger.tanggal as tgl_cek_jml_finger, extract(dow from tgl) as hari_tgl, rjkh.tgl_mulai, jk.id as id_jam_kerja, jk.nama as nama_role_jam_kerja, rjkd.id_hari, 
				jk.jam_mulai_scan_masuk, jk.jam_akhir_scan_masuk, jk.jam_mulai_scan_pulang, jk.jam_akhir_scan_pulang, jk.jam_masuk, jk.jam_pulang, jk.toleransi_terlambat, jk.toleransi_pulang_cepat, 
				jk.masuk_hari_sebelumnya, jk.pulang_hari_berikutnya, shl.id as id_hari_libur, shl.keterangan, jkr.id as jkr_id_jam_kerja, jr.kode as jkr_kode_role_jam_kerja, jkr.nama as jkr_nama_role_jam_kerja, 
				jkr.jam_mulai_scan_masuk as jkr_jam_mulai_scan_masuk, jkr.jam_akhir_scan_masuk as jkr_jam_akhir_scan_masuk, jkr.jam_mulai_scan_pulang as jkr_jam_mulai_scan_pulang, jkr.jam_akhir_scan_pulang as jkr_jam_akhir_scan_pulang, jkr.jam_masuk as jkr_jam_masuk, jkr.jam_pulang as jkr_jam_pulang, jkr.toleransi_terlambat as jkr_toleransi_terlambat, jkr.toleransi_pulang_cepat as jkr_toleransi_pulang_cepat, 
				jkr.masuk_hari_sebelumnya as jkr_masuk_hari_sebelumnya, jkr.pulang_hari_berikutnya as jkr_pulang_hari_berikutnya
			FROM generate_series('".$mulai."', '".$akhir."', '1 day'::interval) tgl
			LEFT JOIN LATERAL (
			SELECT tgl_mulai, id_role_jam_kerja
				FROM m_pegawai_role_jam_kerja_histori
				WHERE tgl_mulai <= tgl AND id_pegawai = '".$id_pegawai."'
				ORDER BY tgl_mulai DESC
				LIMIT 1
			) rjkh ON TRUE
			LEFT JOIN m_role_jam_kerja_detail as rjkd ON rjkh.id_role_jam_kerja = rjkd.id_role AND extract(dow from tgl) = rjkd.id_hari
			LEFT JOIN m_jam_kerja as jk ON rjkd.id_jam_kerja = jk.id
			LEFT JOIN s_hari_libur as shl ON tgl::date = shl.tanggal
			LEFT JOIN t_roster as tr ON tr.id_pegawai = '".$id_pegawai."' AND tr.tanggal = tgl::date
			LEFT JOIN m_jenis_roster as jr ON tr.id_jenis_roster = jr.id
			LEFT JOIN m_jam_kerja as jkr ON jr.id_jam_kerja = jkr.id
			LEFT JOIN (
				SELECT 
					count(distinct(tanggal)) as jumlah, tanggal::date
				FROM 
					absensi_log 
				WHERE
					tanggal BETWEEN '".$dtmulai."' AND '".$dtakhir."'
					and absensi_log.badgenumber || absensi_log.id_mesin in (SELECT user_id || id_mesin from mesin_user where id_pegawai = '".$id_pegawai."')
				GROUP BY tanggal::date
			) jml_finger ON jml_finger.tanggal::date = tgl
			order by tgl desc
		";
		
		$data_jadwal_esok = $this->db->query($query_jadwal)->row();
		//jika tidak ada jadwal kerja reguler/role
		if (!$data_jadwal_esok->id_jam_kerja) {
			//jika ada jadwal roster
			if ($data_jadwal_esok->jkr_id_jam_kerja) {
				$tgl_jadwal_esok = $akhir.' '.$data_jadwal_esok->jkr_jam_mulai_scan_masuk;
				//jika jam scan mulai roster lebih kecil dari jam akhir lembur
				if (strtotime($tgl_jadwal_esok) <= strtotime($dtakhirlembur)) {
					$psn_waktu = date('d-m-Y h:i', strtotime($tgl_jadwal_esok));
					return [ 
						'status' => false,
						'pesan' =>  'Proses Simpan gagal, dikarenakan jam akhir lembur melebihi jam scan awal roster yaitu pada : '.$psn_waktu
					];
				}else{
					return [ 'status' => true ];
				}
			}else{
				return [ 'status' => true ];
			}
		}else{
			$tgl_jadwal_esok = $akhir.' '.$data_jadwal_esok->jam_mulai_scan_masuk;
			//jika jam scan mulai kerja/role lebih kecil dari jam akhir lembur
			if (strtotime($tgl_jadwal_esok) <= strtotime($dtakhirlembur)) {
				$psn_waktu = date('d-m-Y h:i', strtotime($tgl_jadwal_esok));
				return [ 
						'status' => false,
						'pesan' =>  'Proses Simpan gagal, dikarenakan jam akhir lembur melebihi jam scan awal kerja yaitu pada : '.$psn_waktu
					];
			}else{
				return [ 'status' => true ];
			}
		}
	}

	function GeneratePerPegawaiLembur($id_pegawai, $mulai, $akhir)
	{
		$this->load->library('migrasi_data_opt');

		$where         = "id = '".$id_pegawai."' ";
		$dt_pegawai = $this->pegawai_model->getData($where);

		if ($id_pegawai != '') {
			$begin = new DateTime($mulai);
			$end   = new DateTime($akhir);
			$dt_mulai = $mulai." 00:01";
			$dt_akhir = $akhir." 23:59";

			$queryCekDataMentah = "select tanggal from data_mentah where tanggal >= '".$mulai."' and tanggal <= '".$akhir."' and id_pegawai  = '".$id_pegawai."'";
			$d_data_mentah = $this->db->query($queryCekDataMentah)->result_array();

			$h_data_mentah = array();
			foreach ($d_data_mentah as $dm) {
				$h_data_mentah[$dm['tanggal']][] = $dm;
			}

			$query_lembur	= "
				SELECT tgl_lembur::date as tanggal, to_char(jam_lembur_awal,'YYYY-MM-DD HH24:MI') as jam_lembur_awal, to_char(jam_lembur_akhir,'YYYY-MM-DD HH24:MI') as jam_lembur_akhir, pulang_besoknya
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
						SELECT 
							tgl::date as tanggal, 
							t_masuk.finger_masuk, 
							t_keluar.finger_pulang,
							jml_finger.jumlah as jumlah_finger,
							jml_finger.tanggal as tgl_cek_jml_finger,
							extract(dow from tgl) as hari_tgl, 
							rjkh.tgl_mulai, jk.id as id_jam_kerja, 
							jk.nama as nama_role_jam_kerja, 
							rjkd.id_hari, 
							jk.jam_mulai_scan_masuk, 
							jk.jam_akhir_scan_masuk, 
							jk.jam_mulai_scan_pulang, 
							jk.jam_akhir_scan_pulang, 
							jk.jam_masuk, jk.jam_pulang, 
							jk.toleransi_terlambat, 
							jk.toleransi_pulang_cepat, 
							jk.masuk_hari_sebelumnya, 
							jk.pulang_hari_berikutnya, 
							shl.id as id_hari_libur, 
							shl.keterangan, 
							jkr.id as jkr_id_jam_kerja, 
							jr.kode as jkr_kode_role_jam_kerja, 
							jkr.nama as jkr_nama_role_jam_kerja, 
							jkr.jam_mulai_scan_masuk as jkr_jam_mulai_scan_masuk, 
							jkr.jam_akhir_scan_masuk as jkr_jam_akhir_scan_masuk, 
							jkr.jam_mulai_scan_pulang as jkr_jam_mulai_scan_pulang, 
							jkr.jam_akhir_scan_pulang as jkr_jam_akhir_scan_pulang, 
							jkr.jam_masuk as jkr_jam_masuk, 
							jkr.jam_pulang as jkr_jam_pulang, 
							jkr.toleransi_terlambat as jkr_toleransi_terlambat, 
							jkr.toleransi_pulang_cepat as jkr_toleransi_pulang_cepat, 
							jkr.masuk_hari_sebelumnya as jkr_masuk_hari_sebelumnya, 
							jkr.pulang_hari_berikutnya as jkr_pulang_hari_berikutnya
						FROM generate_series('".$mulai."', '".$akhir."', '1 day'::interval) tgl
						LEFT JOIN LATERAL (
							SELECT tgl_mulai, id_role_jam_kerja
							FROM m_pegawai_role_jam_kerja_histori
							WHERE tgl_mulai <= tgl AND id_pegawai = '".$id_pegawai."'
							ORDER BY tgl_mulai DESC
							LIMIT 1
						) rjkh ON TRUE
						LEFT JOIN m_role_jam_kerja_detail as rjkd ON rjkh.id_role_jam_kerja = rjkd.id_role AND extract(dow from tgl) = rjkd.id_hari
						LEFT JOIN m_jam_kerja as jk ON rjkd.id_jam_kerja = jk.id
						LEFT JOIN s_hari_libur as shl ON tgl::date = shl.tanggal
						LEFT JOIN t_roster as tr ON tr.id_pegawai = '".$id_pegawai."' AND tr.tanggal = tgl::date
						LEFT JOIN m_jenis_roster as jr ON tr.id_jenis_roster = jr.id
						LEFT JOIN m_jam_kerja as jkr ON jr.id_jam_kerja = jkr.id
						LEFT JOIN (
							SELECT
								to_char ( min( tanggal ), 'yyyy-mm-dd HH24:MI' ) AS finger_masuk 
							FROM absensi_log 
							WHERE tanggal BETWEEN '".$dt_mulai."' 
							AND '".$dt_akhir."' 
							AND badgenumber || id_mesin IN ( SELECT user_id || id_mesin FROM mesin_user WHERE id_pegawai = '".$id_pegawai."' ) 
							GROUP BY
								to_char ( tanggal, 'yyyy-mm-dd' ) 
							ORDER BY
									to_char ( tanggal, 'yyyy-mm-dd' ) ASC 
						) t_masuk ON t_masuk.finger_masuk::date = tgl
						LEFT JOIN (
							SELECT
								to_char ( max( tanggal ), 'yyyy-mm-dd HH24:MI' ) AS finger_pulang 
							FROM absensi_log 
							WHERE tanggal BETWEEN '".$dt_mulai."' 
							AND '".$dt_akhir."' 
							AND badgenumber || id_mesin IN ( SELECT user_id || id_mesin FROM mesin_user WHERE id_pegawai = '".$id_pegawai."' ) 
							GROUP BY
								to_char ( tanggal, 'yyyy-mm-dd' ) 
							ORDER BY
									to_char ( tanggal, 'yyyy-mm-dd' ) ASC 
						) t_keluar ON t_keluar.finger_pulang::date = tgl
						LEFT JOIN (
							SELECT 
								count(distinct(tanggal)) as jumlah, tanggal::date
							FROM 
								absensi_log 
							WHERE
								tanggal BETWEEN '".$dt_mulai."' AND '".$dt_akhir."'
								and absensi_log.badgenumber || absensi_log.id_mesin in (SELECT user_id || id_mesin from mesin_user where id_pegawai = '".$id_pegawai."')
							GROUP BY tanggal::date
						) jml_finger ON jml_finger.tanggal::date = tgl
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
				//data mentah
				if(isset($h_data_mentah[$i->format("Y-m-d")])){
					$dt_mentah = $h_data_mentah[$i->format("Y-m-d")];
				}
				else {
					$dt_mentah = null;
				}
				if($dt_mentah){
					$this->migrasi_data_opt->cek_ulang_data_mentah(
						$i->format("Y-m-d"), $id_pegawai, "update", false, $lbr, $iz, $jdwl, $dt_pegawai->meninggal, $dt_pegawai->tgl_meninggal
					);
				}
				else{
					$this->migrasi_data_opt->cek_ulang_data_mentah(
						$i->format("Y-m-d"), $id_pegawai, "insert", false, $lbr, $iz, $jdwl, $dt_pegawai->meninggal, $dt_pegawai->tgl_meninggal
					);
				}
			}
			return true;
		}else{
			return false;
		}
	}

	/*function GeneratePerPegawaiLembur($id_pegawai, $mulai, $akhir)
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
	}*/
}
