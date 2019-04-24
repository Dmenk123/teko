<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pindah_sidik_jari extends CI_Controller {

	public function __construct() {
		parent::__construct();

		$this->load->model('c_security_user_model');
		$this->load->model('m_pegawai_permesin','model_pegawai');
		$this->load->helper('indonesiandate');
		$this->load->model('absensi_log_model');
		$this->load->model('instansi_model');
    }

    public function index(){
    	if($this->session->userdata('id_kategori_karyawan')=='4' || $this->session->userdata('id_kategori_karyawan')=='6'){
			if ($this->session->userdata('kode_instansi') == '5.09.00.00.00') {
				$whereInstansi =	"m_instansi.kode='5.09.00.00.00' or m_instansi.kode='5.09.00.91.00'";
			}else{
				$whereInstansi =	"m_instansi.kode='".$this->session->userdata('kode_instansi')."' ";
			}
		}
		else{
			$whereInstansi =	"";
		}
		$this->dataInstansi = $this->instansi_model->showData($whereInstansi,"","nama");

		$this->template_view->load_view('mesin_finger/pindah_sidik_jari');
	}

	public function list_pegawai(){
		$list = $this->model_pegawai->get_datatables();
		$data 		= array();
		$no 		= $this->input->post('start');
			
		foreach ($list as $pages) {
			$no++;
			$row = array(); 
			$row[] = $no;
			$row[] = $pages->nama;
			$row[] = $pages->nama_instansi;
			$row[] = $pages->nama_instansi_mesin;
			$row[] = $pages->ip_address;
			
			//add html for action
			$row[] = '<a class="btn btn-xs btn-success" href="javascript:void(0)" title="Pindah Sidik Jari" onclick="pindah_sidikjari('."'".$pages->id_usermesin."'".','."'".$pages->nama."'".')"><i class="fa fa-hand-peace-o"></i></a>';
			// $row[] = '<a class="btn btn-xs btn-success" href="javascript:void(0)" title="Survey Rumah Tangga" onclick="survey('."'".$pages->id_prelist."'".')"><i class="glyphicon glyphicon-edit"></i></a>';
	
			$data[] = $row;
		}
	
		$output = array(
						"draw" 				=> $this->input->post('draw'),
						"recordsTotal" 		=> $this->model_pegawai->count_all(),
						"recordsFiltered" 	=> $this->model_pegawai->count_filtered(),
						"data" 				=> $data,
				);
		//output to json format
		echo json_encode($output);
	}

	public function instansi_mesin($id_instansi = null){
		$mesin = $this->model_pegawai->select_mesin($id_instansi);
		echo json_encode($mesin);
	}

	public function detail_pegawai($id_pegawai = null){
		$user = $this->model_pegawai->select_data('mesin_user',['mesin_user.id' => $id_pegawai]);
		// var_dump($user);
		if($user){
			$data = [
					'user_id' 				=> $user[0]->user_id,
					'ip_address' 			=> $user[0]->ip_address,
					'nama_pegawai' 			=> $user[0]->nama,
					'nama_instansi_mesin' 	=> $user[0]->nama_instansi_mesin
					];
			echo json_encode($data);
		}
	}

	public function get_handkey($id_pegawai = null,$ip = null, $arr = false){
		#CURL
		$ch = curl_init('https://downloader-tekocak.surabaya.go.id/autolog/fungsi_rdp/get_sidik_jari/'.$ip.'/'.$id_pegawai.'/'.$arr);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_NOSIGNAL, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT_MS, 15000);
		$data = curl_exec($ch);
		$curl_errno = curl_errno($ch);
		$curl_error = curl_error($ch);
		curl_close($ch);

		if ($curl_errno > 0) {
						echo json_encode(array('status' => 'failed'));
		}else{
			$decode 	= json_decode($data);
			// var_dump($decode);
			
			$data = array();
			foreach($decode->data as $key){
		
				$data[] = [
					'fn'			=> $key->fingerID,
					'handkey'	=> $key->fingerID
				];
			}
		
			echo json_encode($data);
		}
	}

	public function get_one_handkey($id_pegawai = null,$ip = null, $arr){
		#CURL
        $ch = curl_init('https://downloader-tekocak.surabaya.go.id/autolog/fungsi_rdp/get_sidik_jari/'.$ip.'/'.$id_pegawai.'/'.$arr);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_NOSIGNAL, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT_MS, 15000);
        $data = curl_exec($ch);
        $curl_errno = curl_errno($ch);
        $curl_error = curl_error($ch);
        curl_close($ch);

        if ($curl_errno > 0) {
            echo json_encode(array('status' => 'failed'));
        } else {
            $decode 	= json_decode($data);
            // var_dump($decode);
            
            $data = array();
            foreach ($decode->data as $key) {
                $data[] = [
                    'fn'			=> $key->fingerID,
                    'handkey'	=> $key->template
                ];
            }
        
            echo json_encode($data);
        }

	}

	public function pindah_data(){
		$id_pegawai 	= $this->input->post('badgenumber');
		$ip_origin 		= $this->input->post('ip_origin');
		$sidik_jari 	= $this->input->post('data_sidik_jari');
		$ip_target 		= $this->input->post('lokasi_mesin');
		$fn 					= $this->input->post('sidik_jari');
		$nama 				= $this->input->post('nama_user');

		$data = array(
			'badge_number' 	=> $id_pegawai,
			'fn' 						=> $fn,
			'ip' 						=> $ip_origin,
			'temp' 					=> $sidik_jari,
			'ip_target' 		=> $ip_target,
			'fn_target' 		=> $fn_target,
			'nama'					=> $nama,
		);
	

		// $ch = curl_init('https://downloader-tekocak.surabaya.go.id/index_download_sidik_jari.php?upload=yes');
		$ch = curl_init('https://downloader-tekocak.surabaya.go.id/autolog/fungsi_rdp/insert_sidik_jari');
		// curl_setopt($ch, CURLOPT_POSTFIELDS,
		// "badge_number=".$id_pegawai."&fn=".$fn."&ip=".$ip_origin."&temp=".$sidik_jari."&ip_target=".$ip_target."&fn_target=".$fn_target);
		curl_setopt ($ch, CURLOPT_POST, 1);
		// curl_setopt($ch, CURLOPT_POSTFIELDS,
		// 		"badge_number=$id_pegawai&fn=$fn&ip=$ip_origin&temp=$sidik_jari&ip_target=$ip_target&fn_target=$fn_target");
		curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
		// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		// curl_setopt($ch, CURLOPT_NOSIGNAL, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT_MS, 15000);
		$data = curl_exec($ch);
		$curl_errno = curl_errno($ch);
		$curl_error = curl_error($ch);
		curl_close($ch);

		if ($curl_errno > 0) {
				echo json_encode(array('status' => false,'pesan' => 'gateway tomeout'));
		}else{
			echo json_encode(array('status' => true,'pesan' => 'Sidik Jari Berhasil Di Pindahkan'));
			// echo $data;
			// echo '<br>';
			// echo 'badge_number :'.$id_pegawai.'<br>';
			// echo 'fn :'.$fn.'<br>';
			// echo 'ip :'.$ip_origin.'<br>';
			// echo 'temp :'.$sidik_jari.'<br>';
			// echo 'ip_target :'.$ip_target.'<br>';
			// echo 'fn_target :'.$fn_target.'<br>';
		}
		
	}

}