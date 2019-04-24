<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Lembur_pegawai extends CI_Controller {



	public function __construct() {
		parent::__construct();

		$this->load->model('jenis_ijin_cuti_model');
		$this->load->model('data_mentah_model');
		$this->load->model('lembur/t_lembur_model', 't_lembur');

	}

	public function index(){
		redirect($this->uri->segment(1)."/add");
	}

	public function add(){

		// $this->dataJenisIjinCuti 	= $this->jenis_ijin_cuti_model->showData("","","nama");

		$this->template_view->load_view('lembur_pegawai/lembur_pegawai_view');
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
		$this->form_validation->set_rules('id_pegawai', '', 'trim|required');

		if ($this->form_validation->run() == FALSE)	
		{
			$status = array('status' => FALSE, 'pesan' => 'Gagal menyimpan Data, pastikan telah mengisi semua inputan.');
		}
		else
		{
			$time 					= strtotime($this->input->post('tgl_lembur'));
			$tgl_mulai 				= date('Y-m-d',$time);

			$cekLog = $this->db->query("select * from log_laporan where tgl_log > '".$tgl_mulai."' and kd_instansi = '".$this->session->userdata('kode_instansi')."'")->row();
			
			if($cekLog){
				$status = array('status' => false , 'pesan' => 'Proses Simpan gagal, dikarenakan Laporan telah dikunci untuk Tanggal kejadian.');
				//echo(json_encode($status));
			}
			else
			{

				if($this->input->post('file_lampiran')=='')
				{
					$status = array('status' => false , 'pesan' => 'Silahkan Upload file Lampiran terlebih dahulu,');
				}
				else
				{



					$time 					= strtotime($this->input->post('tgl_lembur'));
					$tgl_lembur 		= date('Y-m-d',$time);
					$waktu1 		= $this->input->post('jam_mulai');
					$waktu2 		= $this->input->post('jam_selesai');
					$data11 = $tgl_lembur.' '.$waktu1.':00';
					$data22 = $tgl_lembur.' '.$waktu2.':00';
					$waktu_awal				= $data11;
					$waktu_akhir			= $data22;
					$time3 					= strtotime($this->input->post('tgl_surat'));
					$tgl_surat 			= date('Y-m-d',$time3);
					$tglInsert 			= date('Y-m-d h:i:s');

					// var_dump($tgl_lembur.' '.$waktu1.':00'); die();



					$harisekarang	=	strtotime( date("Y-m-d"));


					//// jika lebih harinya lebih dari sekarang
					// ####################################################################
						if ($time < $harisekarang ){
							$iSeninJumat=0;
							for ($i=$time; $i <= $harisekarang; $i += (60 * 60 * 24)) {
		
		 						if (date('w', $i) !== '0' && date('w', $i) !== '6') {
	 							$iSeninJumat++;
		 						}
								else{
									$iSeninJumat -= $iSeninJumat ;
								}
	
							}
	
	
							//echo $iSeninJumat;
							if($iSeninJumat > 4){
									$status = array('status' => false , 'pesan' => 'Proses Simpan gagal, dikarenakan Hari Kejadian sudah lebih dari 3 hari Kerja.');
	 							echo(json_encode($status));
									exit;
							}
		
						}

		//echo "asdasd";
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
						'jam_lembur_akhir' 		=>	$waktu_akhir,
						'timeupd'				=> $tglInsert,
						'status'				=> '1'
					);

					$query 			 			= $this->t_lembur->insert($data);
					$date_lembur 			= new DateTime( $tgl_lembur );
					$this->load->library('migrasi_data');
					
					$this->migrasi_data->cek_ulang_data_mentah($date_lembur->format("Y-m-d"), $this->input->post('id_pegawai'), "update", false);

					//// delete data mentah
					// $where ="tanggal ='".$tgl_lembur."' and id_pegawai='". $this->input->post('id_pegawai')."'";
					// $this->data_mentah_model->delete($where);

					$status = array('status' => true , 'redirect_link' => base_url()."".$this->uri->segment(1));
				}
			}
		}
		echo(json_encode($status));
	}


	public function edit_data(){
		$this->form_validation->set_rules('id_pegawai', '', 'trim|required');
		$this->form_validation->set_rules('id_t_lembur', '', 'trim|required');

		if ($this->form_validation->run() == FALSE)	{
			$status = array('status' => FALSE, 'pesan' => 'Gagal menyimpan Data, pastikan telah mengisi semua inputan.');
		}
		else{


			if($this->input->post('file_lampiran')==''){
				$status = array('status' => false , 'pesan' => 'Silahkan Upload file Lampiran terlebih dahulu,');
			}
			else{


				$time 					= strtotime($this->input->post('tgl_lembur'));
				$tgl_lembur 		= date('Y-m-d',$time);
				$waktu1 		= $this->input->post('jam_mulai');
				$waktu2 		= $this->input->post('jam_selesai');
				$data11 = $tgl_lembur.' '.$waktu1.':00';
				$data22 = $tgl_lembur.' '.$waktu2.':00';
				$waktu_awal				= $data11;
				$waktu_akhir			= $data22;
				$time3 					= strtotime($this->input->post('tgl_surat'));
				$tgl_surat 			= date('Y-m-d',$time3);
				$tglInsert 			= date('Y-m-d h:i:s');

				// var_dump($tgl_lembur.' '.$waktu1.':00'); die();

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
					'jam_lembur_awal' 	=> $waktu_awal,
					'jam_lembur_akhir' 	=>	$waktu_akhir,
					'timeupd'						=> $tglInsert
				);

				$where = array(
					'id' => $this->input->post('id_t_lembur')
				);
				$query = $this->t_lembur->update($where,$data);


				// $where ="tanggal ='".$tgl_lembur."' and id_pegawai='". $this->input->post('id_pegawai')."'";
				// $this->data_mentah_model->delete($where);
				$date_lembur 			= new DateTime( $tgl_lembur );
				$this->load->library('migrasi_data');
				$this->migrasi_data->cek_ulang_data_mentah($date_lembur->format("Y-m-d"), $this->input->post('id_pegawai'), "update", false);



				$status = array('status' => true , 'redirect_link' =>  base_url()."daftar_lembur_pegawai".$this->input->post('redirect'));
			}
		}

		echo(json_encode($status));
	}

}
