<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Kendala_teknis extends CI_Controller {

	public function __construct() {
		parent::__construct();

		$this->load->model('absensi_log_model');
		$this->load->model('mesin_model');
		$this->load->model('data_mentah_model');
		$this->load->model('pegawai_model');
		$this->load->model('global_model');

	}

	public function index(){
		redirect($this->uri->segment(1)."/add");
	}

	public function add(){
		/*if ($this->session->userdata('id_kategori_karyawan') == '1') {
			$this->template_view->load_view('laporan/lap_skor_view');
		}else{
			$this->template_view->load_view('template/sedang-perbaikan');
		}*/
		$this->template_view->load_view('kendala_teknis/kendala_teknis_view');
	}

	public function edit(){
		$IdPrimaryKey	=	$this->input->get('id_log_absensi');
		$where 			=	"absensi_log.id = '".$IdPrimaryKey."' ";
		$this->oldData 	= 	$this->absensi_log_model->getData($where,"","");
		if(!$this->oldData){
			redirect($this->uri->segment(1));
		}

		$this->dataPegawai = $this->pegawai_model->getData2("m.id = '".$this->oldData->id_pegawai."'");


		$url 		= 	"https://". $_SERVER['SERVER_NAME'] . ":" . $_SERVER['REQUEST_URI'];
		$url 		=	explode("daftar_kendala_teknis",$url);
		$this->url 	=	$url[1];

		$this->template_view->load_view('kendala_teknis/kendala_teknis_edit_view');
	}

	public function add_data()
	{
		$tabel = 'absensi_log';
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
				// rizky jangan dihapus dulu kalo mau GAJIAN
				if ($this->session->userdata('id_kategori_karyawan') == '2' || $this->session->userdata('username') == 'rizky')
				{
					$cek_kunci = false;
				}else{
					//cek kunci upload
					$cek_kunci = $this->db->query("select * from t_kunci_upload where kode_instansi = '".$this->session->userdata('kode_instansi')."'")->row();
				}

				//batasan CUTOFF LAPORAN
				$tgl_batas 			= "2019-01-01";
				$time 				= strtotime($this->input->post('tanggal'));
				$batas_cutoff_time	= strtotime($tgl_batas);
				$tgl_mulai 			= date('Y-m-d',$time);
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

				// rizky jangan dihapus dulu kalo mau GAJIAN
				if($this->session->userdata('username') == 'rizky') {
					$kunci_bulanan = false;
					$kunci_ceklis  = false;
					$cutoff = false;
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

				if (!$cek_kunci)
				{
					if ($cutoff) {
						$status = array('status' => false , 'pesan' => 'Proses Simpan gagal, dikarenakan data sudah dikunci');
						echo(json_encode($status));
						exit;
					}

					$time 					= strtotime($this->input->post('tanggal'));
					$tgl_mulai 				= date('Y-m-d',$time);
					$where 					= "id_pegawai = '".$this->input->post('id_pegawai')."' ";
					$this->dataMesin 		= $this->mesin_model->getDataViewMesinPegawai($where,"","");
					if($this->dataMesin == null) {
						$this->dataMesin 	= $this->mesin_model->getDataViewMesinPegawai2($where,"","");
					}
					$tanggal 				= date('Y-m-d',$time);
					$tanggal 				= $tanggal." ".$this->input->post('jam').":".$this->input->post('menit').":00";
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

					//jika kategori user bukan KPI kominfo,
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

					// rizky jangan dihapus dulu kalo mau GAJIAN
					if($this->session->userdata('username') == 'rizky') {
						$cek_kunci_tiga_hari = false;
					}

					//==================================================== ATURAN BARU ===========================================================
					if ($this->session->userdata('id_kategori_karyawan') <> '11') {
						if ($cek_kunci_tiga_hari) {
							if (strtotime($finalMaxUpload) <= $harisekarang) {
								$status = array('status' => false , 'pesan' => 'Proses Simpan gagal, dikarenakan batas maksimal upload adalah '.date('d-m-Y', strtotime($finalMaxUpload)).' (3 hari kerja setelah aktif bekerja.).');
								echo(json_encode($status));
					 			exit;
							}
						}
					}

					$this->load->library('encrypt_decrypt');

					$data = array(
						'id' 					=> $this->encrypt_decrypt->new_id(),
						'otomatis' 				=> false,
						'tanggal' 				=> $tanggal,

						'user_upd' 				=> $_SESSION['id_karyawan'],
						'id_mesin' 				=> $this->dataMesin->id_mesin,
						'badgenumber' 			=> $this->dataMesin->user_id,

						'keterangan' 			=> $this->input->post('keterangan'),
						'dispensasi' 			=> $this->input->post('dispensasi'),
						'file_lampiran' 		=> $this->input->post('file_lampiran')
					);

					#LOG START
					$data_log = [
						'id_user'			=> $this->session->userdata()['id_karyawan'],
						'aksi'				=> 'ADD KENDALA TEKNIS',
						'tanggal'			=> date('Y-m-d H:i:s'),
						'data'				=> json_encode($data),
						'file_lampiran'		=> ($this->input->post('file_lampiran'))?$this->input->post('file_lampiran'):null
					];
					$this->global_model->save($data_log,'log_tekocak');
					#LOG FINISH

					$this->db->set('jam_download', 'current_timestamp', FALSE);
					$query = $this->absensi_log_model->insert($data, $tabel);

					$id_pegawai = $this->input->post('id_pegawai');
					$mulai = $tanggal;
					$selesai = date("Y-m-d", strtotime("+1 days", strtotime($mulai)));

					//$this->load->library('migrasi_data');
					$generate_kendala = $this->GeneratePerPegawaiKendala($id_pegawai, $mulai, $selesai);
					$status = array('status' => $generate_kendala , 'redirect_link' => base_url()."".$this->uri->segment(1));
				}//jika ada kuncian
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
		$this->form_validation->set_rules('dispensasi', '', 'trim|required');
		$this->form_validation->set_rules('file_lampiran', '', 'trim|required');

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
				$tgl_batas 			= "2018-12-01";
				$time 				= strtotime($this->input->post('tanggal'));
				$batas_cutoff_time	= strtotime($tgl_batas);
				$tgl_mulai 			= date('Y-m-d',$time);
				$tgl_akhir 			= date('Y-m-t',$time);

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

				if (!$cek_kunci)
				{
					$where 				=	"id_pegawai = '".$this->input->post('id_pegawai')."' ";
					$this->dataMesin 	= $this->mesin_model->getDataViewMesinPegawai($where,"","");
					if($this->dataMesin == null) {
						$this->dataMesin = $this->mesin_model->getDataViewMesinPegawai2($where,"","");
					}

					$time 		= strtotime($this->input->post('tanggal'));
					$tanggal 	= date('Y-m-d',$time);
					$tanggal 	= $tanggal." ".$this->input->post('jam').":".$this->input->post('menit').":00";
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
								$status = array('status' => false , 'pesan' => 'Proses Simpan gagal, dikarenakan batas maksimal upload adalah '.date('d-m-Y', strtotime($finalMaxUpload)).' (3 hari kerja kerja setelah aktif bekerja).');
								echo(json_encode($status));
					 			exit;
							}
						}
					}

					$this->load->library('encrypt_decrypt');

					$data = array(
						'tanggal' 				=> $tanggal,
						'user_upd' 				=> $_SESSION['id_karyawan'],
						'id_mesin' 				=> $this->dataMesin->id_mesin,
						'badgenumber' 			=> $this->dataMesin->user_id,
						'keterangan' 			=> $this->input->post('keterangan'),
						'dispensasi' 			=> $this->input->post('dispensasi'),
						'file_lampiran' 		=> $this->input->post('file_lampiran')
					);

					#LOG START
					$ijin		=	$this->global_model->get_by_id('absensi_log',['id' => $this->input->post('id_log_absensi')]);
					$data_log = [
							'id_user'			=> $this->session->userdata()['id_karyawan'],
							'aksi'				=> 'EDIT KENDALA TEKNIS',
							'tanggal'			=> date('Y-m-d H:i:s'),
							'data'				=> json_encode($ijin),
							'file_lampiran'		=> ($ijin->file_lampiran)?$ijin->file_lampiran:null,
							'lampiran_blob'		=> ($ijin->lampiran)?$ijin->lampiran:null,
							'lampiran_blob_type'=> ($ijin->lampiran_type)?$ijin->lampiran_type:null
						];
					$this->global_model->save($data_log,'log_tekocak');
					#LOG FINISH

					$this->db->set('jam_download', 'current_timestamp', FALSE);

					$where = array(
						'id' => $this->input->post('id_log_absensi')
					);
					$query = $this->absensi_log_model->update($where,$data);

					$id_pegawai = $this->input->post('id_pegawai');
					$mulai = $tgl_mulai;
					$selesai = date("Y-m-d", strtotime("+1 days", strtotime($mulai)));

					//$this->load->library('migrasi_data');
					$generate_kendala = $this->GeneratePerPegawaiKendala($id_pegawai, $mulai, $selesai);
					$status = array('status' => $generate_kendala , 'redirect_link' =>  base_url()."daftar_kendala_teknis".$this->input->post('redirect'));
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

	function GeneratePerPegawaiKendala($id_pegawai, $mulai, $akhir)
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

}
