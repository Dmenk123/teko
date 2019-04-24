<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Kendala_teknis extends CI_Controller {

	public function __construct() {
		parent::__construct();

		$this->load->model('absensi_log_model');
		$this->load->model('mesin_model');
		$this->load->model('data_mentah_model');
		$this->load->model('pegawai_model');
	}

	public function index(){
		redirect($this->uri->segment(1)."/add");
	}

	public function add(){
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
				// jika kategori administrator
				if ($this->session->userdata('id_kategori_karyawan') == '2') 
				{
					$cek_kunci = false;
				}else{
					//cek kunci upload, jika tidak dikunci maka aturan upload max h+ 3 di batalkan
					$cek_kunci = $this->db->query("select * from t_kunci_upload where kode_instansi = '".$this->session->userdata('kode_instansi')."'")->row();
				}

				if (!$cek_kunci) 
				{
					$time 					= strtotime($this->input->post('tanggal'));
					$tgl_mulai 				= date('Y-m-d',$time);
					$where 					= "id_pegawai = '".$this->input->post('id_pegawai')."' ";
					$this->dataMesin 		= $this->mesin_model->getDataViewMesinPegawai($where,"","");
					$tanggal 				= date('Y-m-d',$time);
					$tanggal 				= $tanggal." ".$this->input->post('jam').":".$this->input->post('menit').":00";
					
					/*$tgl_batas 				= "2018-12-01";
					$harisekarang			= strtotime($tgl_batas);
					
					// jika lebih harinya lebih dari sekarang
					if ($time < $harisekarang ){
				 		$status = array('status' => false , 'pesan' => 'Proses Simpan gagal, dikarenakan Buka Kunci hanya berlaku pada bulan Desember 2018.');
						echo(json_encode($status));
			 			exit;
					}*/

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

					$this->db->set('jam_download', 'current_timestamp', FALSE);
					$query = $this->absensi_log_model->insert($data, $tabel);

					$date_kendala_teknis 			= new DateTime( $tanggal );
					$this->load->library('migrasi_data');
					$this->migrasi_data->cek_ulang_data_mentah($date_kendala_teknis->format("Y-m-d"), $this->input->post('id_pegawai'), "update", false);
					$tanggal 	= date('Y-m-d',$time);
					// $where ="tanggal ='".$tanggal."' and id_pegawai='". $this->input->post('id_pegawai')."'";
					// $this->data_mentah_model->delete($where);
					$status = array('status' => true , 'redirect_link' => base_url()."".$this->uri->segment(1));
				}//jika ada kuncian
				else
				{
					// $time 					= strtotime($this->input->post('tanggal'));
					// $tgl_mulai 				= date('Y-m-d',$time);

					// $cekLog = $this->db->query("select * from log_laporan where tgl_log > '".$tgl_mulai."' and kd_instansi = '".$this->session->userdata('kode_instansi')."'")->row();

					// if($cekLog)
					// {
					// 	$status = array('status' => false , 'pesan' => 'Proses Simpan gagal, dikarenakan Laporan telah dikunci untuk Tanggal kejadian.');
					// }
					// else
					// {
						$time 		= strtotime($this->input->post('tanggal'));
						$tgl_mulai 				= date('Y-m-d',$time);
						$where 				=	"id_pegawai = '".$this->input->post('id_pegawai')."' ";
						$this->dataMesin 	= 	$this->mesin_model->getDataViewMesinPegawai($where,"","");

						
						$tanggal 	= date('Y-m-d',$time);
						$tanggal 	= $tanggal." ".$this->input->post('jam').":".$this->input->post('menit').":00";

						$harisekarang	=	strtotime( date("Y-m-d"));

						$tgl_batas 		= "2018-12-01";
						$hariBatas			=	strtotime($tgl_batas);

						// jika upload selain november maka gagal
						if ($time < $hariBatas ){
					 		$status = array('status' => false , 'pesan' => 'Proses Simpan gagal, dikarenakan Buka Kunci hanya berlaku pada bulan Desember 2018.');
							echo(json_encode($status));
				 			exit;
						
						}

						//// jika lebih harinya lebih dari sekarang
						/*if ($time < $harisekarang ){
						 	$iSeninJumat=0;
						 	for ($i=$time; $i <= $harisekarang; $i += (60 * 60 * 24)) {
						
						 		if (date('w', $i) !== '0' && date('w', $i) !== '6') {
						 			$iSeninJumat++;
						 		}
						
						 	}
						
			 				//	var_dump($iSeninJumat);
						 	if($iSeninJumat > 4){
								$status = array('status' => false , 'pesan' => 'Proses Simpan gagal, dikarenakan Hari Kejadian sudah lebih dari 3 hari Kerja.');
						 			echo(json_encode($status));
									exit;
						 	}
						
						}*/

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

						$this->db->set('jam_download', 'current_timestamp', FALSE);
						$query = $this->absensi_log_model->insert($data, $tabel);

						$date_kendala_teknis 			= new DateTime( $tanggal );
						$this->load->library('migrasi_data');
						$this->migrasi_data->cek_ulang_data_mentah($date_kendala_teknis->format("Y-m-d"), $this->input->post('id_pegawai'), "update", false);
						$tanggal 	= date('Y-m-d',$time);
						// $where ="tanggal ='".$tanggal."' and id_pegawai='". $this->input->post('id_pegawai')."'";
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
				// jika kategori administrator
				if ($this->session->userdata('id_kategori_karyawan') == '2') 
				{
					$cek_kunci = false;
				}else{
					//cek kunci upload, jika tidak dikunci maka aturan upload max h+ 3 di batalkan
					$cek_kunci = $this->db->query("select * from t_kunci_upload where kode_instansi = '".$this->session->userdata('kode_instansi')."'")->row();
				}

				if (!$cek_kunci) 
				{
					$where 				=	"id_pegawai = '".$this->input->post('id_pegawai')."' ";
					$this->dataMesin 	= 	$this->mesin_model->getDataViewMesinPegawai($where,"","");

					$time 		= strtotime($this->input->post('tanggal'));
					$tanggal 	= date('Y-m-d',$time);
					$tanggal 	= $tanggal." ".$this->input->post('jam').":".$this->input->post('menit').":00";

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
						'tanggal' 				=> $tanggal,
						'user_upd' 				=> $_SESSION['id_karyawan'],
						'id_mesin' 				=> $this->dataMesin->id_mesin,
						'badgenumber' 			=> $this->dataMesin->user_id,
						'keterangan' 			=> $this->input->post('keterangan'),
						'dispensasi' 			=> $this->input->post('dispensasi'),
						'file_lampiran' 		=> $this->input->post('file_lampiran')
					);

					$this->db->set('jam_download', 'current_timestamp', FALSE);

					$where = array(
						'id' => $this->input->post('id_log_absensi')
					);
					$query = $this->absensi_log_model->update($where,$data);


					$tanggal 	= 	date('Y-m-d',$time);
					// $where 		=	"tanggal ='".$tanggal."' and id_pegawai='". $this->input->post('id_pegawai')."'";
					// $this->data_mentah_model->delete($where);
					$date_kendala_teknis 			= new DateTime( $tanggal );
					$this->load->library('migrasi_data');
					$this->migrasi_data->cek_ulang_data_mentah($date_kendala_teknis->format("Y-m-d"), $this->input->post('id_pegawai'), "update", false);


					$status = array('status' => true , 'redirect_link' =>  base_url()."daftar_kendala_teknis".$this->input->post('redirect'));
				}
				else //jika ada kuncian
				{
					// $time 					= strtotime($this->input->post('tanggal'));
					// $tgl_mulai 				= date('Y-m-d',$time);

					// $cekLog = $this->db->query("select * from log_laporan where tgl_log > '".$tgl_mulai."' and kd_instansi = '".$this->session->userdata('kode_instansi')."'")->row();

					// if($cekLog)
					// {
					// 	$status = array('status' => false , 'pesan' => 'Proses Simpan gagal, dikarenakan Laporan telah dikunci untuk Tanggal kejadian.');
					// }
					// else
					// {
						$where 				=	"id_pegawai = '".$this->input->post('id_pegawai')."' ";
						$this->dataMesin 	= 	$this->mesin_model->getDataViewMesinPegawai($where,"","");

						$time 		= strtotime($this->input->post('tanggal'));
						$tanggal 	= date('Y-m-d',$time);
						$tanggal 	= $tanggal." ".$this->input->post('jam').":".$this->input->post('menit').":00";
						$harisekarang	=	strtotime( date("Y-m-d"));

						$tgl_batas 		= "2018-12-01";
						$hariBatas			=	strtotime($tgl_batas);

						// jika upload selain november maka gagal
						if ($time < $hariBatas ){
					 		$status = array('status' => false , 'pesan' => 'Proses Simpan gagal, dikarenakan Buka Kunci hanya berlaku pada bulan Desember 2018.');
							echo(json_encode($status));
				 			exit;
						
						}

						//#################jika lebih harinya lebih dari sekarang
						/*if ($time < $harisekarang ){
							$iSeninJumat=0;
							for ($i=$time; $i <= $harisekarang; $i += (60 * 60 * 24)) 
							{
		 						if (date('w', $i) !== '0' && date('w', $i) !== '6') {
	 								$iSeninJumat++;
		 						}
								else{
									$iSeninJumat -= $iSeninJumat ;
								}
							}
	
							//echo $iSeninJumat;
							if($iSeninJumat > 4)
							{
								$status = array('status' => false , 'pesan' => 'Proses Simpan gagal, dikarenakan Hari Kejadian sudah lebih dari 3 hari Kerja.');
	 							echo(json_encode($status));
									exit;
							}
		
						}*/

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

						$this->db->set('jam_download', 'current_timestamp', FALSE);

						$where = array(
							'id' => $this->input->post('id_log_absensi')
						);
						$query = $this->absensi_log_model->update($where,$data);


						$tanggal 	= 	date('Y-m-d',$time);
						// $where 		=	"tanggal ='".$tanggal."' and id_pegawai='". $this->input->post('id_pegawai')."'";
						// $this->data_mentah_model->delete($where);
						$date_kendala_teknis 			= new DateTime( $tanggal );
						$this->load->library('migrasi_data');
						$this->migrasi_data->cek_ulang_data_mentah($date_kendala_teknis->format("Y-m-d"), $this->input->post('id_pegawai'), "update", false);


						$status = array('status' => true , 'redirect_link' =>  base_url()."daftar_kendala_teknis".$this->input->post('redirect'));
					//}
				}
			}
		}

		echo(json_encode($status));
	}

}
