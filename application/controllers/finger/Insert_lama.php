<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Insert extends CI_Controller {



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
			
			$i = 1;
			foreach ($data as $k=>$v){
				
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
				$queryAbsensiLog = $this->absensi_log_model->insert($dataAbsensiLog);
				
				
				//echo $this->db->last_query()."<br>";
				//}
			//$i++;	
			}			
			echo "sukses";
		}
		else{
			echo "IP Tidak terdaftar";
		}
		
	}
	
	
}
