<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Log_penarikan extends CI_Controller {



	public function __construct() {
		parent::__construct();

		$this->load->model('c_security_user_model');
		$this->load->model('m_log_penarikan','model_log');
		$this->load->helper('indonesiandate');
		$this->load->model('absensi_log_model');
    }
    

    public function index(){
		$this->template_view->load_view('log_penarikan/log_penarikan_view');
	}

	public function get_data(){
		$sess = $this->session->userdata();
		$list = $this->model_log->get_datatables();
				
		$data 		= array();
		$no 		= $this->input->post('start');
			
		foreach ($list as $pages) {
			$ip_add = explode(".",$pages->ip_address);
			#$mesin 		= $this->cek_mesin($pages->ip);
			$last_gen = $this->model_log->get_last_gen($pages->kode_instansi);
			
			$no++;
			$row = array(); 
			$row[] = $no;
			$row[] = $pages->nama;
			if((int)$sess['id_kategori_karyawan'] == 15){
				$row[] = $pages->ip_address;
			}elseif((int)$sess['id_kategori_karyawan'] > 2){
				$row[] = $ip_add['0'].".".$ip_add[1].".XXX.XXX";
			}else{
				$row[] = $pages->ip_address;
			}
			$row[] = ($pages->jam_selesai_download)?indonesian_date_full($pages->jam_selesai_download):'-';
			$row[] = number_format($pages->array_terakhir_mesin,0,'','.');
			$row[] = ($pages->jam_selesai_load)?indonesian_date_full($pages->jam_selesai_load):'-';
			$row[] = ($last_gen)?indonesian_date_full($last_gen->finish_at):'-';
			
			// $row[] = ($pages->manual)?'manual':'otomatis';
			//$row[] = $pages->status_penarikan;
			$row[] = ($pages->status_mesin == '1')?'<p class="text-success">Connected</p>':'<p class="text-danger">Not Connected</p>';
			#$row[] = ($mesin)?'<p class="text-success">Connected</p>':'<p class="text-danger">Not Connected</p>';
						
			if ((int)$sess['id_kategori_karyawan'] < 3) {
				$row[] = '
					<a class="btn btn-xs btn-success" href="javascript:void(0)" title="Tarik Data" onclick="tarik_finger('."'".trim($pages->ip_address)."'".')"><i class="fa fa-cloud-download"></i></a>
					<a class="btn btn-xs btn-info" href="javascript:void(0)" title="Load Data" onclick="load_data('."'".trim($pages->ip_address)."'".')"><i class="fa fa-cloud-upload"></i></a>
					<a class="btn btn-xs btn-primary" href="javascript:void(0)" title="Generate Data" onclick="generate_data('."'".trim($pages->kode_instansi)."'".')"><i class="fa fa-exchange"></i></a>
					<a class="btn btn-xs btn-danger" href="javascript:void(0)" title="Hapus Data Mesin" onclick="hapus_mesin('."'".trim($pages->ip_address)."'".')"><i class="fa fa-trash-o"></i></a>
				';
			}else{
				$row[] = '-';
			}

			$data[] = $row;
		}
	
		$output = array(
						"draw" 				=> $this->input->post('draw'),
						"recordsTotal" 		=> $this->model_log->count_all(),
						"recordsFiltered" 	=> $this->model_log->count_filtered(),
						"data" 				=> $data,
				);
		//output to json format
		echo json_encode($output);
	}

	public function get_nama_instansi($id_ins)
	{
		$nama = $this->model_log->get_nama_instansi($id_ins);
		echo json_encode([
			'nama_ins' => $nama
		]);
	}

	public function get_data_old(){
		$sess = $this->session->userdata();
		$list = $this->model_log->get_datatables();
				
		$data = array();
		$no = $this->input->post('start');
		foreach ($list as $pages) {
		$mesin 		= $this->cek_mesin($pages->ip);
		$ip_address = explode(".",$pages->ip);
			$no++;
			$row = array(); 
			$row[] = $no;
			$row[] = $pages->nama;
			if((int)$sess['id_kategori_karyawan'] >= 2){
				$row[] = $ip_address['0'].".".$ip_address[1].".XXX.XXX";
			}else{
				$row[] = $pages->ip;
			}
			$row[] = indonesian_date_full($pages->tanggal_input);
			$row[] = ($pages->manual)?'manual':'otomatis';
			$row[] = $pages->jumlah_data;
			$row[] = ($pages->status_mesin == '1')?'Connected':'Not Connected';
			#$row[] = "-";
			$row[] = ($mesin)?'<p class="text-success">Connected</p>':'<p class="text-danger">Not Connected</p>';
			
			#$row[] = '<a class="btn btn-xs btn-success" href="javascript:void(0)" title="Tarik Data" onclick="tarik_finger('."'".$this->encrypt($pages->ip)."'".')"><i class="fa fa-cloud-download"></i></a>';
			
			
			//add html for action
			if ($this->session->userdata(id_kategori_karyawan) < 3) {
				$row[] = ($mesin)?'<a class="btn btn-xs btn-success" href="javascript:void(0)" title="Tarik Data" onclick="tarik_finger('."'".$this->encrypt($pages->ip)."'".')"><i class="fa fa-cloud-download"></i></a>':'';
			}else{
				$row[] = ($mesin)?'<a class="btn btn-xs btn-success" href="javascript:void(0)" title="Tarik Data" onclick="pop_biasa('."'".$this->encrypt($pages->ip)."'".')"><i class="fa fa-cloud-download"></i></a>':'';
			}
			// $row[] = '<a class="btn btn-xs btn-success" href="javascript:void(0)" title="Survey Rumah Tangga" onclick="survey('."'".$pages->id_prelist."'".')"><i class="glyphicon glyphicon-edit"></i></a>';
	
			$data[] = $row;
		}
	
		$output = array(
						"draw" 				=> $this->input->post('draw'),
						"recordsTotal" 		=> $this->model_log->count_all(),
						"recordsFiltered" 	=> $this->model_log->count_filtered(),
						"data" 				=> $data,
				);
		//output to json format
		echo json_encode($output);
	}

	// public function ping_mesin(){
	// 	$list = $this->model_log->get_datatables();
	// 	foreach ($list as $pages) {
	// 		$mesin 		= $this->cek_mesin($pages->ip_address);
	// 		$ping_data 	= [
	// 						'tgl_ping' 		=> date('Y-m-d H:i:s'),
	// 						'status_mesin'	=> ($mesin)?1:0
	// 						];
	// 		$this->absensi_log_model->update_date(['ip_address' => $pages->ip_address],$ping_data, 'm_mesin');
	// 	}
	// 	if($ping_data){
	// 		echo json_encode(['status' => true,'pesan' => 'berhasil mengupdate status mesin']);
	// 	}else{
	// 		echo json_encode(['status' => false,'pesan' => 'gagal mengupdate status mesin']);
	// 	}
	// }

	public function ping_mesin($param = null){
		if($param){
			$mesin = $this->cek_mesin($this->decrypt($param));

			if($mesin){
				$ping_data 	= [
								'tgl_ping' 		=> date('Y-m-d H:i:s'),
								'status_mesin'	=> ($mesin == true)?1:0
								];
				$this->absensi_log_model->update_date(['ip_address' => $this->decrypt($param)],$ping_data, 'm_mesin');
				echo json_encode(['status' => true,'pesan' => 'Status Mesin Konek']);
			}else{
				$ping_data 	= [
								'tgl_ping' 		=> date('Y-m-d H:i:s'),
								'status_mesin'	=> ($mesin == true)?1:0
								];
				$this->absensi_log_model->update_date(['ip_address' => $this->decrypt($param)],$ping_data, 'm_mesin');
				echo json_encode(['status' => false,'pesan' => 'Status Mesin Tidak Konek']);
			}
		}else{
			$list = $this->model_log->get_datatables();
			$i 		= 0;
			$yes 	= 0;
			$no 	= 0;
			foreach ($list as $pages) {
				$i++;
				$total 		= $i;
				$mesin 		= $this->cek_mesin($pages->ip_address);
				if($mesin == true){
					$yes++;
					$berhasil = $yes;
				}else{
					$no++;
					$gagal    = $no;
				}
				
				$ping_data 	= [
								'tgl_ping' 		=> date('Y-m-d H:i:s'),
								'status_mesin'	=> ($mesin == true)?1:0
								];
				$this->absensi_log_model->update_date(['ip_address' => $pages->ip_address],$ping_data, 'm_mesin');
			}
			if($ping_data){
				echo json_encode(['status' => true,'pesan' => 'Berhasil mengupdate '.$total.' status mesin <br> '.$berhasil.' Connected, '.$gagal.' Not Connected']);
			}else{
				echo json_encode(['status' => false,'pesan' => 'gagal mengupdate status mesin']);
			}
		}
		
	}

	public function tarik_finger($ip = null){
		$ch = curl_init('https://downloader-tekocak.surabaya.go.id/autolog/downloader/tarik_finger_ajax/'.$ip);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_NOSIGNAL, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 1000);
		$data = curl_exec($ch);
		$curl_errno = curl_errno($ch);
		$curl_error = curl_error($ch);
		curl_close($ch);

		if ($curl_errno > 0) {
			echo json_encode(['status' => false,'pesan' => 'connection timeout']);
		}else{
			echo $data;
		}
	}


	private function cek_mesin($ip){
		$ch = curl_init('https://downloader-tekocak.surabaya.go.id/cek_mesin.php?ip_address='.$ip);
		// $ch = curl_init('http://127.0.0.1:81/teko_cuk/cek_mesin.php?ip_address='.$ip);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_NOSIGNAL, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT_MS, 4000);
		$data = curl_exec($ch);
		$curl_errno = curl_errno($ch);
		$curl_error = curl_error($ch);
		curl_close($ch);

		if ($curl_errno > 0) {
			return false;
		}else{
			$decode 	= json_decode($data);
			if($decode){
				// var_dump($decode);
				if($decode->status == '200'){
					return true;
					// echo 'konek<bR>';
				}else{
					return false;
					// echo 'tidak konek<br>';
				}
			}else{
				return false;
			}
		}
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