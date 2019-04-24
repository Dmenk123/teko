<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include APPPATH . 'third_party/line_bot_sdk/src/LINEBot.php';
include APPPATH . 'third_party/line_bot_sdk/src/LINEBot/Constant/Meta.php';
include APPPATH . 'third_party/line_bot_sdk/src/LINEBot/Constant/MessageType.php';
include APPPATH . 'third_party/line_bot_sdk/src/LINEBot/HTTPClient.php';
include APPPATH . 'third_party/line_bot_sdk/src/LINEBot/HTTPClient/Curl.php';
include APPPATH . 'third_party/line_bot_sdk/src/LINEBot/HTTPClient/CurlHTTPClient.php';
include APPPATH . 'third_party/line_bot_sdk/src/LINEBot/MessageBuilder.php';
include APPPATH . 'third_party/line_bot_sdk/src/LINEBot/MessageBuilder/TextMessageBuilder.php';
include APPPATH . 'third_party/line_bot_sdk/src/LINEBot/Response.php';

class Linebot extends CI_Controller {
    protected $_ci;

    function __construct(){
        $this->_ci = &get_instance();
        // parent::__construct();
        $this->_ci->load->database();
        $this->_ci->load->model(['pegawai_model', 'global_model']);
    }

    public function post($idLine=null, $idPegawai=null, $jenkel=null, $namaPeg=null, $tgl=null)
    {
        if ($idLine == null || $idPegawai == null || $namaPeg == null || $tgl == null) {
            return false;
        }
        
        foreach ($idLine as $val) {
            $tgl = date('Y-m-d');
            $tglDoang = date('d');
            $blnDoang = date('m');
            $thnDoang = date('Y');

            $nama_hari = $this->hari_indo($this->dayOfWeek($tgl));
            $nama_bulan = $this->bulan_indo(date('m'));

            $whereDmentah = "tanggal = '".$tgl."' and id_pegawai = '".$idPegawai."'";
            $data = $this->_ci->global_model->get_by_id('data_mentah', $whereDmentah);
            
            //cek finger masuk atau pulang yg akan di push ke linebot
            $periodeFinger = $this->cek_data_mentah($idPegawai, $tgl);
            //cek jika saat pengiriman pesan merupakan masa saat pagi/siang/malam
            $masa = $this->cek_pagi_siang_malam(date('H'));

            if ($jenkel == 'L') {
                $gender = 'Bapak';
            }elseif($jenkel == 'P'){
                $gender = 'Ibu';
            }else{
                $gender = 'Bapak / Ibu';
            }
            
            if ($periodeFinger['pukul']) {
                $jamHandKey = date('H:i:s', strtotime($periodeFinger['pukul']));
            }else{
                return false;
            }
            
            $pesan_notifikasi_line = "TEKO-CAK\n\nSelamat ".$masa." ".$gender." ".$namaPeg.". \nberikut merupakan jam finger Pada hari ".$nama_hari. " Tanggal ".$tglDoang." ".$nama_bulan." tahun ".$thnDoang." \nPukul : ".$jamHandKey." WIB \nUntuk info lebih detail, agar dibuka di ".base_url()." \nMenggunakan user dan password NIP ".$gender.". \nTerima Kasih.";
            
            $data_notifikasi_line = array(
                'id_pegawai'    => $idPegawai,
                'id_line'       => $val['id'],
                'pesan'         => $pesan_notifikasi_line,
            );

            if ($periodeFinger['periode'] == 'Masuk') {
                $waktu = 'M';
            }else{
                $waktu = 'P';
            }
            
            $lineid = $val['id'];
            // $query = "SELECT * FROM t_notifikasi_line WHERE id_line = '$lineid' and id_pegawai = '$idPegawai' and created::date = '$tgl' and status = '1' and waktu = '$waktu'";
            $query = "SELECT * FROM t_notifikasi_line WHERE id_line = '$lineid' and id_pegawai = '$idPegawai' and tanggal = '$tgl' and status = '1' and waktu = '$waktu'";
            $log_notif = $this->_ci->db->query($query)->row_array();

            /* if (!$log_notif) {
                // //================================================ LINE ==================================================       
                $httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient(LINE_CHANNEL_ACCESS_TOKEN);
                $bot  = new \LINE\LINEBot($httpClient, ['channelSecret' => LINE_CHANNEL_SECRET]);

                $textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($pesan_notifikasi_line);
                $response_notifikasi_line = $bot->pushMessage($val['id'], $textMessageBuilder);
                // // ================================================ END LINE ==============================================
                //var_dump($response_notifikasi_line->getJSONDecodedBody());exit;

                if($response_notifikasi_line->isSucceeded()) {
                    $data_notifikasi_line['status'] = 1;
                }
                else {
                    $data_notifikasi_line['status'] = 0;
                }

                $data_notifikasi_line['waktu'] = $waktu;
                $this->_ci->global_model->save($data_notifikasi_line,'t_notifikasi_line');
                return true;
            }else{
                return false;
            } */
            

            if (!$log_notif) {
                // //================================================ LINE ==================================================       
                $httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient(LINE_CHANNEL_ACCESS_TOKEN);
                $bot  = new \LINE\LINEBot($httpClient, ['channelSecret' => LINE_CHANNEL_SECRET]);

                $textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($pesan_notifikasi_line);
                $response_notifikasi_line = $bot->pushMessage($val['id'], $textMessageBuilder);
                // // ================================================ END LINE ==============================================
                //var_dump($response_notifikasi_line->getJSONDecodedBody());exit;

                if($response_notifikasi_line->isSucceeded()) {
                    $data_notifikasi_line['status'] = 1;
                }
                else {
                    $data_notifikasi_line['status'] = 0;
                }

                $data_notifikasi_line['waktu'] = $waktu;
                $data_notifikasi_line['tanggal'] = $tgl;
                $this->_ci->global_model->save($data_notifikasi_line,'t_notifikasi_line');
                //return $response_notifikasi_line->getJSONDecodedBody();
            }
        }

    }
    
    public function cek_data_mentah($id_pegawai, $tanggal)
    {
        $q = "SELECT * FROM data_mentah where id_pegawai = '$id_pegawai' and tanggal = '$tanggal'";
        $data = $this->_ci->db->query($q)->row_array();
        $hasil = [];
        
        //jika finger pulang tidak kosong, cek apa sudah mulai scan, jika tidak nullkan
        if ($data['finger_pulang'] !== null) {
            $jadwal = $this->cek_jam_diperbolehkan_finger(date('Y-m-d'), $id_pegawai);
            if (strtotime($jadwal['jam_mulai_scan_pulang']) > strtotime($data['finger_pulang'])) {
                $data['finger_pulang'] = null;
            }
        }
        //end cek

        if ($data['finger_pulang'] !== null) {
            $hasil['pukul'] = $data['finger_pulang'];
            $hasil['periode'] = 'Pulang';
        }else{
            if($data['finger_masuk'] !== null) {    
                $hasil['pukul'] = $data['finger_masuk'];
                $hasil['periode'] = 'Masuk';
            }else{
                $hasil['pukul'] = false;
                $hasil['periode'] = false;
            }
        }
        return $hasil;
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

	public function hari_indo($day){
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
	
	public function dayOfWeek($date){
        return date("w", strtotime($date));
    }
    
    public function cek_pagi_siang_malam($hournow)
    {
        if ($hournow < "12") {
            return "Pagi";
        } elseif ($hournow >= "12" && $hournow < "15") {
            return "Siang";
        } elseif ($hournow >= "15" && $hournow < "18") {
            return "Sore";
        } elseif ($hournow >= "18") {
            return "Malam";
        }
    }

    function cek_jam_diperbolehkan_finger($date, $id_pegawai){
      return $this->_ci->db->query(
        "SELECT
            d.nama,
            A.id_role_jam_kerja,
            A.tgl_mulai,
            A.id_pegawai,
            b.id_jam_kerja,
            b.id_hari,
            C.jam_akhir_scan_masuk,
            C.jam_akhir_scan_pulang,
            C.jam_mulai_scan_masuk,
            C.jam_mulai_scan_pulang,
            C.jam_masuk,
            C.jam_pulang
        FROM
            m_pegawai_role_jam_kerja_histori AS A,
            m_role_jam_kerja_detail AS B,
            m_jam_kerja AS C,
            m_hari as d
        WHERE
            A.id_pegawai = '$id_pegawai'
            AND A.id_role_jam_kerja = b.id_role
            AND b.id_jam_kerja = c.id
            AND d.id = b.id_hari
            and a.tgl_mulai <= '$date'
            and b.id_hari = (select extract(isodow from date '$date'))
        order by a.tgl_mulai desc limit 1")->row_array();
  }
}
