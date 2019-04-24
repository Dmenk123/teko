<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hari_libur extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('Global_model','global');
	}

	/*------------------------------------------------------------ 
		WEB SERVICE GARBIS - SIPK
		PUNGKI DWI P.
		19 Ok 2018

	--------------------------------------------------------------*/
	private function required($data){
		$val = isset(
				$data->tahun,
				$data->tanggal
		);
		
		return $val;
	}
	
	public function index(){
		$v 			= $_SERVER;
		$user		= 'liburan';
		$pass		= 'libur_telah_tiba';
		if( isset($v['HTTP_PASSWORD']) != $pass || $v['HTTP_USERNAME'] != $user){
			header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
				// echo json_encode(array('status' => false, 'pesan' => 'Invalid Token'));
				exit;
			}
		if(isset($v['HTTP_USERNAME']) && $v['HTTP_USERNAME'] == $user && isset($v['HTTP_PASSWORD']) && $v['HTTP_PASSWORD'] == $pass  && isset($v['CONTENT_TYPE']) == 'application/json'){			
			$input 			= file_get_contents("php://input");
			$input_array	= json_decode($input);
			
			$set_array	= 	 $this->required($input_array);
			
			if($set_array){
					
				$tahun 		= $input_array->tahun;
				$tanggal 	= $input_array->tanggal;
				
				$tgl_awal 	= $tahun.'-01-01';
				$tgl_akhir 	= $tahun.'-12-31';
				
				if(!preg_match('/^[a-zA-Z0-9 ]+$/', $tahun)){
					echo json_encode(array('status' => false, 'pesan' => 'Karakter tidak di izinkan'));
					exit;
				}

				$tanggal = ($tanggal == '') ? null : $tanggal;

				if ($tanggal === null) {
					$whereQuery = "where s_hari_libur.tanggal between '".$tgl_awal."' and '".$tgl_akhir."'";
				}else{
					$whereQuery = "where s_hari_libur.tanggal between '".$tgl_awal."' and '".$tgl_akhir."' and s_hari_libur.tanggal = '".$tanggal."'";
				}
				
				$query = "
							select s_hari_libur.id, m_hari_libur.nama, s_hari_libur.tanggal, s_hari_libur.keterangan 
						  	from s_hari_libur 
							left join m_hari_libur on s_hari_libur.id_libur = m_hari_libur.id ".$whereQuery."
						";				
				$data_libur 		 = $this->global->getData($query);
				// echo $this->db->last_query();
				echo json_encode(["thnAwal" => $tgl_awal, "thnAkhir" => $tgl_akhir,"dataSource" => $data_libur]);
					
				}else{
				echo json_encode(array('status' => false, 'pesan' => 'invalid data, value harus dalam bentuk string'));
			}
		}else{
			header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
		}
	}

	/* public function absensi_pegawai(){
		$v 			= $_SERVER;
		$user		= 'sipk';
		$pass		= '8281bab9438763f7e1ab0e58583ab5dd';
		if( isset($v['HTTP_PASSWORD']) != $pass || $v['HTTP_USERNAME'] != $user){
			header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
				// echo json_encode(array('status' => false, 'pesan' => 'Invalid Token'));
				exit;
			}
		if(isset($v['HTTP_USERNAME']) && $v['HTTP_USERNAME'] == $user && isset($v['HTTP_PASSWORD']) && $v['HTTP_PASSWORD'] == $pass  && isset($v['CONTENT_TYPE']) == 'application/json'){			
			$input 			= file_get_contents("php://input");
			$input_array	= json_decode($input);
			$set_array	= $this->required($input_array);
			
			
			// var_dump($input_array);
			if($set_array){
					
				$kode 		= $input_array->kode_instansi;
				$bulan 		= $input_array->bulan;
				$tahun 		= $input_array->tahun;
				$pns 		= $input_array->pns;
				
				if(!preg_match('/^[a-zA-Z0-9 ]+$/', $kode)){
					echo json_encode(array('status' => false, 'pesan' => 'Karanter tidak di izinkan'));
					exit;
				}

				$unit 		= $this->api->unit_kerja($input_array->kode_instansi);
				$kode_unit 	= $unit['0']->kode;
				#TAMBAHAN CASE KHUSUS
				#$where 		= ['bulan' => $input_array->bulan, 'tahun' => $input_array->tahun, 'pns' => $input_array->pns,'id_instansi' => $kode_unit,'deleted_at' => null];
				#$lap_skor 		 = $this->api->skor_lembur('lap_skor_kehadiran_detil',$where) ; 
				if($input_array->kode_instansi == "1 20 0308"){
					$where 		= ['bulan' => $input_array->bulan, 'tahun' => $input_array->tahun, 'pns' => $input_array->pns,'deleted_at' => null];
					$where_kunci = ["to_char(tgl_log, 'YYYY-MM' ) =" => $input_array->tahun.'-'.$input_array->bulan, 'is_kunci' => 'Y'];
					$where_in 	= ["1.00.00.00.00","1.00.01.00.00","1.00.02.00.00","1.00.03.00.00","1.00.11.00.00","1.00.12.00.00","1.00.13.00.00","1.03.01.00.00"];
					#KUNCIAN LAPORAN
				}else{
					$where 		= ['bulan' => $input_array->bulan, 'tahun' => $input_array->tahun, 'pns' => $input_array->pns,'id_instansi' => $kode_unit,'deleted_at' => null];
					$where_in 	= null ;
					$where_kunci = ['kd_instansi' => $kode_unit,"to_char(tgl_log, 'YYYY-MM' ) =" => $input_array->tahun.'-'.$input_array->bulan, 'is_kunci' => 'Y'];
				}

				$lap_skor 		 = $this->api->skor_lembur_detail('lap_skor_kehadiran_detil',$where,$where_in);
				//echo $this->db->last_query();
				//var_dump($lap_skor); 
				$cek_kunci 		 = $this->api->skor_lembur('log_laporan',$where_kunci,$where_in) ;
				
				if($cek_kunci){
					if($cek_kunci[0]->is_kunci == 'N'){
						echo json_encode(array('status' => false, 'pesan' => 'Data Belum Di Kunci oleh OPD'));
						exit;
					}
				}else{
					echo json_encode(array('status' => false, 'pesan' => 'Data Belum Di Kunci oleh OPD'));
					exit;
				}
				#var_dump($cek_kunci['0']->is_kunci);

				if($lap_skor){
					foreach($lap_skor as $value){
						$dencode_skor= json_decode($value->skor);
						
						$totalSkor = 0;
						foreach( $dencode_skor as $sukur){
							$totalSkor += $sukur->skor; 
						
						
						
						}

						$v_skor_total = 1400;
						# start perwali desember 2018
						if(($input_array->bulan == 12 && $input_array->tahun == 2018) || $input_array->tahun == 2019) {
							$v_skor_total = 1500;
						}

						$skorTPP = 100 - ($v_skor_total - $totalSkor);

						# start tambah (if seda otomatis 100)
						if($skorTPP < 0) {
							$skorTPP = 0;
						}
						# end tambah

						# start perwali desember 2018
						if(($input_array->bulan == 12 && $input_array->tahun == 2018) || $input_array->tahun == 2019) {
							if($value->meninggal == 't'){
								$skorTPP = 100;
							}
						}
						#TAMBAHAN CASE KHUSUS
						if($input_array->kode_instansi == "1 20 0308"){
							$where1 		= ['nip' => $value->nip,'bulan' => $input_array->bulan, 'tahun' => $input_array->tahun, 'pns' => $input_array->pns,'deleted_at' => null];
							#$where_in1 		= ["1.00.00.00.00","1.00.01.00.00","1.00.02.00.00","1.00.03.00.00","1.00.11.00.00","1.00.12.00.00","1.00.13.00.00","1.03.01.00.00"]; 
						}else{
							$where1 		= ['nip' => $value->nip,'bulan' => $input_array->bulan, 'tahun' => $input_array->tahun, 'pns' => $input_array->pns,'id_instansi' => $kode_unit,'deleted_at' => null];
							#$where_in1 = null;
						}
						$lap_skor_lembur = $this->api->skor_lembur_detail('lap_absensi_lembur_detil',$where1,$where_in) ;
						//var_dump($this->db->last_query());exit;
						if(empty($lap_skor_lembur)){
							// echo $this->db->last_query();
							echo json_encode(array('status' => false, 'nip'=> $value->nip, 'nama' => $value->nama, 'pesan' => 'Laporan Skor Lembur Belum di Cetak'));
							exit;
						} 
						#$where1 		= ['nip' => $value->nip,'bulan' => $input_array->bulan, 'tahun' => $input_array->tahun, 'pns' => $input_array->pns,'id_instansi' => $kode_unit,'deleted_at' => null];
						#$lap_skor_lembur = $this->api->skor_lembur('lap_absensi_lembur_detil',$where1) ; 
						// var_dump($lap_skor_lembur);

						$data[] = [
							'kode_instansi' 	=> $input_array->kode_instansi,
							'nama' 				=> $value->nama, 
							'nip' 				=> $value->nip, 
							'golongan' 			=> $value->golongan, 
							'jabatan' 			=> $value->jabatan, 
							'instansi' 			=> $unit['0']->nama, 
							'skor_kehadiran' 	=> $skorTPP,
							'jml_hadir' 		=> $value->jml_hadir,
							'skor_lembur' 		=> $lap_skor_lembur['0']->skor_persen
						];
						# end perwali desember 2018
						}
						
						$data_log = [
							'ip_address'	=> $_SERVER["HTTP_X_REAL_IP"],
							'tanggal_tarik'	=> date('Y-m-d H:i:s'),
							'kode_instansi'	=> $kode_unit,
							'kode_sik'		=> $input_array->kode_instansi,
							'tahun'			=> $input_array->tahun,
							'bulan'			=> $input_array->bulan,
							'id_user'		=> $user
						];
						
						$this->global->save($data_log,'log_sipk');

						echo json_encode(["bulan" => $input_array->bulan, "tahun" => $input_array->tahun ,"dataSource" => $data]);
					}else{
						echo json_encode(array('status' => false, 'pesan' => 'Data Belum Di cetak oleh OPD'));
					}
				}else{
				echo json_encode(array('status' => false, 'pesan' => 'invalid data'));
			}
		}else{
			header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
		}
	} */
}
