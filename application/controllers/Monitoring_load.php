<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Monitoring_load extends CI_Controller {



	public function __construct() {
		parent::__construct();

		$this->load->model('c_security_user_model');
		$this->load->model('m_log_load');
		$this->load->helper('indonesiandate');
		$this->load->model('absensi_log_model');
		$this->load->model('global_model');
    }
    

    public function index(){
		// $query = 'select t.finish_download, t.ip, m.nama, t.jumlah_data from t_log_penarikan t join m_mesin m on t.id_mesin = m.id where finish_download is not null and tanggal_load_selesai is null order by finish_download asc limit 50';
		// $this->data = $this->global_model->getData($query);
		$query2 = 'select count(*) as jml from t_log_penarikan t join m_mesin m on t.id_mesin = m.id where finish_download is not null and tanggal_load_selesai is null';
		$this->data2 = $this->global_model->getData($query2);
		$this->template_view->load_view('monitoring_load/monitoring_load_view');
	}

	public function get_data(){
		$sess = $this->session->userdata();
		$list = $this->m_log_load->get_datatables();
						
		$data 		= array();
		$no 		= $this->input->post('start');
			
		foreach ($list as $pages) {			
			$no++;
			$row = array(); 
			$row[] = $no;
			$row[] = $pages->ip;
			$row[] = $pages->nama;
			$row[] = ($pages->finish_download)?indonesian_date_full($pages->finish_download):$pages->finish_download;
			$row[] = ($pages->tanggal_load_mulai)?indonesian_date_full($pages->tanggal_load_mulai):$pages->tanggal_load_mulai;
			$row[] = ($pages->tanggal_load_selesai)?indonesian_date_full($pages->tanggal_load_selesai):$pages->tanggal_load_selesai;
			$row[] = $pages->jumlah_data;
			$data[] = $row;
		}
	
		$output = array(
			"draw" 				=> $this->input->post('draw'),
			"recordsTotal" 		=> $this->m_log_load->count_all(),
			"recordsFiltered" 	=> $this->m_log_load->count_filtered(),
			"data" 				=> $data,
		);
		//output to json format
		echo json_encode($output);
	}
	
	function tes($id){
		$enkripsi = $this->encrypt($id);
		var_dump($enkripsi);
	}
	
	private function encrypt($string) {
		$output = false;
		$encrypt_method = "AES-256-CBC";
		$secret_key = 'k0M1nfo2018!';
		$secret_iv 	= 'pungky';
	 
		// hash
		$key = hash('sha256', $secret_key);
		 
		// iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
		$iv = substr(hash('sha256', $secret_iv), 0, 16);
	 
		$output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
		$output = base64_encode($output);
	 
		return $output;
	}

	private function decrypt($string) {

		$output = false;
		$encrypt_method = "AES-256-CBC";
		$secret_key 	= 'k0M1nfo2018!';
		$secret_iv 		= 'pungky';

		$key = hash('sha256', $secret_key);
		$iv = substr(hash('sha256', $secret_iv), 0, 16);

		$output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);

		return $output;

	}


}