<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ijin_cuti_pegawai extends CI_Controller {



	public function __construct() {
		parent::__construct();

		$this->load->model('jenis_ijin_cuti_model');
		$this->load->model('t_ijin_cuti_model');
		$this->load->model('data_mentah_model');

	}

	public function index(){
		redirect($this->uri->segment(1)."/add");
	}

	public function add(){

		$this->dataJenisIjinCuti 	= $this->jenis_ijin_cuti_model->showData("","","nama");
		$this->template_view->load_view('ijin_cuti_pegawai/ijin_cuti_pegawai_view');
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
				// jika kategori administrator
				if ($this->session->userdata('id_kategori_karyawan') == '2') 
				{
					$cek_kunci = false;
				}else{
					//cek kunci upload, jika tidak dikunci maka aturan upload max h+ 3 di batalkan
					$cek_kunci = $this->db->query("select * from t_kunci_upload where kode_instansi = '".$this->session->userdata('kode_instansi')."'")->row();
				}

				if (!$cek_kunci) {
					$time 					= strtotime($this->input->post('tgl_mulai'));
					$tgl_mulai 				= date('Y-m-d',$time);
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

					/*$tgl_batas = "2018-12-01";

					$harisekarang			=	strtotime($tgl_batas);

					// jika lebih harinya lebih dari sekarang
					if ($time < $harisekarang ){
				 		$status = array('status' => false , 'pesan' => 'Proses Simpan gagal, dikarenakan Buka Kunci hanya berlaku pada bulan Desember 2018.');
						echo(json_encode($status));
			 			exit;
					
					}*/

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

					$query = $this->t_ijin_cuti_model->insert($data);
					$this->load->library('migrasi_data');
					
					$begin = new DateTime( $tgl_mulai );
					$end   = new DateTime( $tgl_selesai_insert );

					for($i = $begin; $i <= $end; $i->modify('+1 day')){
						$this->migrasi_data->cek_ulang_data_mentah($i->format("Y-m-d"), $this->input->post('id_pegawai'), "update", false);
					}

					$status = array('status' => true , 'redirect_link' => base_url()."".$this->uri->segment(1));
				}
				else
				{
					//cek kunci upload, jika dikunci maka aturan upload max h+ 3 dan kunci perbulan akan dijalankan
					//$time 					= strtotime($this->input->post('tgl_mulai'));
					//$tgl_mulai 				= date('Y-m-d',$time);
					//$cekLog = $this->db->query("select * from log_laporan where tgl_log > '".$tgl_mulai."' and kd_instansi = '".$this->session->userdata('kode_instansi')."'")->row();
					
					//if($cekLog)
					//{
						//$status = array('status' => false , 'pesan' => 'Proses Simpan gagal, dikarenakan Laporan telah dikunci untuk Tanggal kejadian.');
						//echo(json_encode($status));
					//}
					//else
					//{
						$time 					= strtotime($this->input->post('tgl_mulai'));
						$tgl_mulai 				= date('Y-m-d',$time);
						$time2 					= strtotime($this->input->post('tgl_selesai'));
						$tgl_selesai 			= date('Y-m-d',$time2);
						$tgl_selesai_insert 	= date('Y-m-d',$time2)." 23:59:00";
						$time3 					= strtotime($this->input->post('tgl_surat'));
						$tgl_surat 				= date('Y-m-d',$time3);
						$tglInsert 				= date('Y-m-d h:i:s');

						$dt1 			= new DateTime($tgl_mulai);
						$dt2 			= new DateTime($tgl_selesai);

						$sekarang				= date("Y-m-d");
						$dt3 					= new DateTime($sekarang);
						$jumlahHari 			= $dt1->diff($dt2);
						$jumlahHariUpload 		= $dt1->diff($dt3);

						$harisekarang	=	strtotime( date("Y-m-d"));
						$tgl_batas 		= "2018-12-01";
						$hariBatas			=	strtotime($tgl_batas);

						// jika upload selain november maka gagal
						if ($time < $hariBatas ){
					 		$status = array('status' => false , 'pesan' => 'Proses Simpan gagal, dikarenakan Buka Kunci hanya berlaku pada bulan Desember 2018.');
							echo(json_encode($status));
				 			exit;
						
						}

						// jika lebih harinya lebih dari sekarang
						/*if ($time < $harisekarang ){
						 	$iSeninJumat=0;
						 	for ($i=$time; $i <= $harisekarang; $i += (60 * 60 * 24)) {
						
						 		if (date('w', $i) !== '0' && date('w', $i) !== '6') {
						 			$iSeninJumat++;
						 		}
						
						 	}
						
						 	if($iSeninJumat > 4){
						 			$status = array('status' => false , 'pesan' => 'Proses Simpan gagal, dikarenakan Hari Kejadian sudah lebih dari 3 hari Kerja.');
									echo(json_encode($status));
						 			exit;
							}
						
						}*/


						/**var_dump($tgl_mulai);
						var_dump($tgl_selesai);
						var_dump($tgl_surat);
						echo $jumlahHari->days;**/

						//$this->data_mentah_model->delete();


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
							'status'					=> '1'
						);

						$query = $this->t_ijin_cuti_model->insert($data);
						$this->load->library('migrasi_data');
						// foreach($data_pegawai as $temp){
							$begin = new DateTime( $tgl_mulai );
							$end   = new DateTime( $tgl_selesai_insert );

							for($i = $begin; $i <= $end; $i->modify('+1 day')){
								$this->migrasi_data->cek_ulang_data_mentah($i->format("Y-m-d"), $this->input->post('id_pegawai'), "update", false);
							}
						// }

						//// delete data mentah
						// $where ="tanggal between '".$tgl_mulai."' and '".$tgl_selesai."' and id_pegawai='". $this->input->post('id_pegawai')."'";
						// $this->data_mentah_model->delete($where);

						$status = array('status' => true , 'redirect_link' => base_url()."".$this->uri->segment(1));
					//}
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
				// jika kategori administrator
				if ($this->session->userdata('id_kategori_karyawan') == '2') 
				{
					$cek_kunci = false;
				}else{
					//cek kunci upload, jika tidak dikunci maka aturan upload max h+ 3 di batalkan
					$cek_kunci = $this->db->query("select * from t_kunci_upload where kode_instansi = '".$this->session->userdata('kode_instansi')."'")->row();
				}

				if (!$cek_kunci) {
					$time 					= strtotime($this->input->post('tgl_mulai'));
					$tgl_mulai 				= date('Y-m-d',$time);
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

					/*$tgl_batas = "2018-12-01";

					$harisekarang			=	strtotime($tgl_batas);

					// jika lebih harinya lebih dari sekarang
					if ($time < $harisekarang ){
				 		$status = array('status' => false , 'pesan' => 'Proses Simpan gagal, dikarenakan Buka Kunci hanya berlaku pada bulan Desember 2018.');
						echo(json_encode($status));
			 			exit;
					}*/

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

					$this->load->library('migrasi_data');

					$dataIjin	=	$this->t_ijin_cuti_model->getData($where);
					
					// $begin = new DateTime( $tgl_mulai );
					// $end   = new DateTime( $tgl_selesai_insert );
					$begin = new DateTime( $dataIjin->tgl_mulai_insert );
					$end   = new DateTime( $dataIjin->tgl_selesai_insert );

					for($i = $begin; $i <= $end; $i->modify('+1 day')){
						$this->migrasi_data->cek_ulang_data_mentah($i->format("Y-m-d"), $this->input->post('id_pegawai'), "update", false);
					}

					$begin = new DateTime( $tgl_mulai );
					$end   = new DateTime( $tgl_selesai_insert );

					for($i = $begin; $i <= $end; $i->modify('+1 day')){
						$this->migrasi_data->cek_ulang_data_mentah($i->format("Y-m-d"), $this->input->post('id_pegawai'), "update", false);
					}

					$query = $this->t_ijin_cuti_model->update($where,$data);

					$status = array('status' => true , 'redirect_link' =>  base_url()."daftar_ijin_cuti_pegawai".$this->input->post('redirect'));
					
				}
				else //jika ada kuncian
				{
					//cek kunci upload, jika dikunci maka aturan upload max h+ 3 dan kunci perbulan akan dijalankan
					// $time 					= strtotime($this->input->post('tgl_mulai'));
					// $tgl_mulai 				= date('Y-m-d',$time);
					// $cekLog = $this->db->query("select * from log_laporan where tgl_log > '".$tgl_mulai."' and kd_instansi = '".$this->session->userdata('kode_instansi')."'")->row();

					// if($cekLog)
					// {
					// 	$status = array('status' => false , 'pesan' => 'Proses Simpan gagal, dikarenakan Laporan telah dikunci untuk Tanggal kejadian.');
					// 	//echo(json_encode($status));
					// }
					// else
					// {
						$time 					= strtotime($this->input->post('tgl_mulai'));
						$tgl_mulai 				= date('Y-m-d',$time);
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

						$harisekarang	=	strtotime( date("Y-m-d"));
						$tgl_batas 		= "2018-12-01";
						$hariBatas			=	strtotime($tgl_batas);

						// jika upload selain november maka gagal
						if ($time < $hariBatas ){
					 		$status = array('status' => false , 'pesan' => 'Proses Simpan gagal, dikarenakan Buka Kunci hanya berlaku pada bulan Desember 2018.');
							echo(json_encode($status));
				 			exit;
						
						}
						
						/*// jika lebih harinya lebih dari sekarang
						if ($time < $harisekarang ){
						 	$iSeninJumat=0;
						 	for ($i=$time; $i <= $harisekarang; $i += (60 * 60 * 24)) {
						
						 		if (date('w', $i) !== '0' && date('w', $i) !== '6') {
						 			$iSeninJumat++;
						 		}
						
						 	}
						
						 	if($iSeninJumat > 4){
					 			$status = array('status' => false , 'pesan' => 'Proses Simpan gagal, dikarenakan Hari Kejadian sudah lebih dari 3 hari Kerja.');
								echo(json_encode($status));
					 			exit;
							}
						}// end cek*/ 

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

						$this->load->library('migrasi_data');

						$dataIjin	=	$this->t_ijin_cuti_model->getData($where);

						// $begin = new DateTime( $tgl_mulai );
						// $end   = new DateTime( $tgl_selesai_insert );
						$begin = new DateTime( $dataIjin->tgl_mulai_insert );
						$end   = new DateTime( $dataIjin->tgl_selesai_insert );

						for($i = $begin; $i <= $end; $i->modify('+1 day')){
							$this->migrasi_data->cek_ulang_data_mentah($i->format("Y-m-d"), $this->input->post('id_pegawai'), "update", false);
						}

						$begin = new DateTime( $tgl_mulai );
						$end   = new DateTime( $tgl_selesai_insert );

						for($i = $begin; $i <= $end; $i->modify('+1 day')){
							$this->migrasi_data->cek_ulang_data_mentah($i->format("Y-m-d"), $this->input->post('id_pegawai'), "update", false);
						}

						$query = $this->t_ijin_cuti_model->update($where,$data);

						$status = array('status' => true , 'redirect_link' =>  base_url()."daftar_ijin_cuti_pegawai".$this->input->post('redirect'));

					//}
				}				
			}
		}

		echo(json_encode($status));
	}

}
