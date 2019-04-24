<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Manual extends CI_Controller {



	public function __construct() {
		parent::__construct();

		$this->load->model('test_tarik_data_model');
		$this->load->model('mesin_model');
		$this->load->model('mesin_user_model');
		$this->load->model('absensi_log_model');

	}

	public function index(){
		
		$whereMesin =	"ip_address = '".$this->input->get('ip')."' ";
		$dataMesin 	= 	$this->mesin_model->getData($whereMesin);
		
		if($dataMesin){
			$data = json_decode(file_get_contents('php://input'), true);
			//$jumlah_data = count($data);

			$i = 0;
			foreach ($data as $k=>$v)
			{
				
				$jumlah_data = $i++;
				//if($i < 10){
				$this->load->library('encrypt_decrypt');
				
				$whereIDMesin 	=	"user_id = '".$v[1]."' ";
				$dataUserMesin 	= 	$this->mesin_user_model->getData($whereIDMesin);
				if($dataUserMesin){
					$IdDataUserMesin = $dataUserMesin->id;
				}
				else{
					$IdDataUserMesin = "";
				}
			
				/**
				$data = array(
					'id' 			=> $this->encrypt_decrypt->new_id(),
					'id_mesin'		=>	$dataMesin->id,
					'user_id_mesin'	=>	$IdDataUserMesin,
					'ip_address'	=>	$this->input->get('ip'),
					'badgenumber' 	=>	$v[1],
					'tanggal' 		=> 	$v[3],
					'jam_download'	=>	date('Y-m-d H:i:s')
				);				

				$query = $this->test_tarik_data_model->insert($data);
				
				**/
				$dataAbsensiLog = array(
					'id' 			=> $this->encrypt_decrypt->new_id(),
					'id_mesin'		=>	$dataMesin->id,
					'user_id_mesin'	=>	$IdDataUserMesin,
					'badgenumber' 	=>	$v[1],
					'tanggal' 		=> 	$v[3],
					'jam_download'	=>	date('Y-m-d H:i:s'),
					'otomatis'		=> 	't',
					'verify_mode'	=> 	'99'
				);	
				$dataAbsensiLog = $this->absensi_log_model->insert($dataAbsensiLog, 'absensi_log');
				
				
				//echo $this->db->last_query()."<br>";
				//}
			//$i++;	
			}
			
			if($dataAbsensiLog){
				$log = [
					'ip' 			=> $this->input->get('ip'),
					'tanggal_input' => date('Y-m-d H:i:s'),
					'id_mesin' 		=> $dataMesin->id,
					'jumlah_data' 	=> $i,
					'status'		=> 'sukses',
					'manual'		=> '1'
				];

				$update = [
					'jam_download' 		=> date('Y-m-d H:i:s'),
					'jumlah_data' 		=> $i,
					'status_penarikan'	=> 'sukses',
					'manual'			=> '1'
				];

				$this->absensi_log_model->insert($log, 't_log_penarikan');
				$this->absensi_log_model->update_date(['ip_address' => $this->input->get('ip')],$update, 'm_mesin');
				echo "sukses";
			}else{
				$log = [
					'ip' 			=> $this->input->get('ip'),
					'tanggal_input' => date('Y-m-d H:i:s'),
					'id_mesin' 		=> $dataMesin->id,
					'jumlah_data' 	=> $i,
					'status'		=> 'gagal',
					'manual'		=> '1'
				];

				$update = [
					'jam_download' 		=> date('Y-m-d H:i:s'),
					'jumlah_data' 		=> $i,
					'status_penarikan'	=> 'gagal',
					'manual'			=> '1'
				];

				$this->absensi_log_model->insert($log, 't_log_penarikan');
				$this->absensi_log_model->update_date(['ip_address' => $this->input->get('ip')],$update, 'm_mesin');
				echo "gagal";
			}

			// echo $jumlah_data;
		}
		else{
			echo "IP Tidak terdaftar";
		}
		
	}
	
	
}
