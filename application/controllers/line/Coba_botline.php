<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Coba_botline extends CI_Controller {

	function __construct()
	{
    	parent::__construct();
		$this->load->model('pegawai_model');
		$this->load->model('global_model');
	    $this->load->library('linebot');
	}

	public function index(){
		// $this->template_view->load_view('template/dashboard_view');
		$where = "id = '7db8ebaa-ee7b-4ee6-8d66-ccd89d75a719'";
		$user_data = $this->global_model->get_by_id('m_pegawai', $where);
		
		if($user_data->id_line != null) {
			$tgl = date('Y-m-d');
			$tglDoang = date('d');
			$blnDoang = date('m');
			$thnDoang = date('Y');

			$nama_hari = $this->hari_indo($this->dayOfWeek($tgl));
			$nama_bulan = $this->bulan_indo(date('m'));

			$whereDmentah = "tanggal = '".$tgl."' and id_pegawai = '7db8ebaa-ee7b-4ee6-8d66-ccd89d75a719'";
			$data = $this->global_model->get_by_id('data_mentah', $whereDmentah);
			$skrg = new DateTime();
			$jmDua = new DateTime();
			$jmDua->setTime(14,0);
			$periodeFinger = ($skrg < $jmDua) ? 'Masuk' : 'Pulang' ;
			
			if ($user_data->kode_jenis_kelamin == 'L') {
				$gender = 'Bapak';
			}elseif($user_data->kode_jenis_kelamin == 'P'){
				$gender = 'Ibu';
			}else{
				$gender = 'Bapak / Ibu';
			}

			if ($periodeFinger == 'Masuk') {
				$psnCeklog = date('H:i:s', strtotime($data->finger_masuk));
			}else{
				$psnCeklog = date('H:i:s', strtotime($data->finger_pulang));
			}
			
			$pesan_notifikasi_line = "Salam satu nyali ".$gender." ".$user_data->nama." \n\nberikut merupakan jam finger ".$periodeFinger." Pada hari ".$nama_hari. " Tanggal ".$tglDoang." ".$nama_bulan." tahun ".$thnDoang." \nPukul : ".$psnCeklog." WIB \nUntuk info lebih detail, agar dibuka di https://teko-cak.surabaya.go.id";

			// print_r($pesan_notifikasi);exit;
			// //================================================ LINE ==================================================
			$data_notifikasi_line = array(
				'id_pegawai' => $user_data->id,
				'id_line' => $user_data->id_line,
				'pesan'   => $pesan_notifikasi_line,
			);
			// $data_notifikasi_line2 = array('created' => "'".date('Y-m-d H:i:s')."'");

			$httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient(LINE_CHANNEL_ACCESS_TOKEN);
			$bot  = new \LINE\LINEBot($httpClient, ['channelSecret' => LINE_CHANNEL_SECRET]);

			$textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($pesan_notifikasi_line);
			$response_notifikasi_line = $bot->pushMessage($user_data->id_line, $textMessageBuilder);
			
			// // ================================================ END LINE ==================================================
			
			if($response_notifikasi_line->isSucceeded()) {
				$data_notifikasi_line['status'] = 1;
			}
			else {
				$data_notifikasi_line['status'] = 0;
			}

			$this->global_model->save($data_notifikasi_line,'t_notifikasi_line');
		}
	}

	public function bulan_indo($bln)
	{
		$arrBulan = array(
			'01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
			'05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
			'09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
		);
		return $arrBulan[$bln];
	}

	function hari_indo($day){
    if($day == 1){
      return "SENIN";
    }
    else if($day == 2){
      return "SELASA";
    }
    else if($day == 3){
        return "RABU";
    }
    else if($day == 4){
        return "KAMIS";
    }
    else if($day == 5){
        return "JUMAT";
    }
    else if($day == 6){
        return "SABTU";
    }
    else{
        return "MINGGU";
    }
	}
	
	function dayOfWeek($date){
    return date("w", strtotime($date));
  }
}
