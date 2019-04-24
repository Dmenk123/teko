<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('sipk/mod_api','api');
		$this->load->model('Global_model','global');
	}

	/*------------------------------------------------------------ 
		WEB SERVICE GARBIS - SIPK
		PUNGKI DWI P.
		19 Ok 2018

	--------------------------------------------------------------*/

	// public function absensi_pegawai(){
		// $v 			= $_SERVER;
		// $user		= 'sipk';
		// $pass		= 'sipk';
		 // if(isset($v['HTTP_USERNAME']) && $v['HTTP_USERNAME'] == $user && isset($v['HTTP_PASSWORD']) && $v['HTTP_PASSWORD'] == $pass  && isset($v['CONTENT_TYPE']) == 'application/json'){			
			// $input 			= file_get_contents("php://input");
			// $input_array	= json_decode($input);
			// $set_array	= $this->required($input_array);
			
			#var_dump($input_array);
			// if($set_array){
				
					
				// $kode 		= $input_array->kode_instansi;
					
				// $tanggal 	= $input_array->tanggal;
				// if (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$tanggal)) {
					// echo json_encode(array('status' => false, 'pesan' => 'invalid date'));
					// exit;
				// }

				// if(!preg_match('/^[a-zA-Z0-9.]+$/', $kode)){
					// echo json_encode(array('status' => false, 'pesan' => 'Karanter tidak di izinkan'));
					// exit;
				// }
				// $data 		= $this->api->pegawai2(null,null,$kode,$tanggal);
				// if($data){
					// foreach ($data as $pegawai) {
						
						// $peg[] = array(
							// "kode_instasi"		=> $pegawai->kode_sik,
							#"skor_lembur"		=> 100,
							// "nama"				=> $pegawai->nama,
							// "nip"				=> $pegawai->nip,
							#"jml_hadir"			=> 20.0,
							#"skor_kehadiran"	=> 99.0,
							// "golongan" 			=> $pegawai->nama_golongan,
							// "jabatan" 			=> $pegawai->nama_jabatan,
							// "instansi" 			=> $pegawai->nama_instansi
						// );
					// }
					// echo json_encode(array('dataSource' => $peg, JSON_PRETTY_PRINT));
				// }else{
					// echo json_encode(array('status' => false, 'pesan' => 'kode instansi tidak di temukan'));
				// }
			// }else{
				// echo json_encode(array('status' => false, 'pesan' => 'invalid data'));
			// }
		// }else{
			// header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
		// }
	// }

	private function required($data){
		$val = isset(
					$data->kode_instansi,
					// $data->tanggal,
					$data->bulan,
					$data->tahun,
					$data->pns
		);
		
		return $val;
	}
	
	public function absensi_pegawai(){
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
	}

	public function export_excel(){	
			$this->load->helper('download');
			$this->load->library('PHPReport');
			$get = $this->global->select_data('cron_excel',['kirim' => null]);
			#foreach mulai
			if($get){
			foreach ($get as $data) {
				$kode	= $data->kode_sik;
				$tahun 	= $data->tahun;
				$bulan 	= $data->bulan;
				$pns 	= 'y';
				
			
									
			
			// $kode 		= str_replace('%20',' ',$kode_instansi);
			// $bulan 		= $bulan;
			// $tahun 		= $tahun;
			// $pns 		= 'y';
		// 	var_dump(str_replace('%20',' ',$kode));
		// exit;
			$unit 		= $this->api->unit_kerja($kode);
			
			$kode_unit 	= $unit['0']->kode;
			#TAMBAHAN CASE KHUSUS
			#$where 		= ['bulan' => $input_array->bulan, 'tahun' => $input_array->tahun, 'pns' => $input_array->pns,'id_instansi' => $kode_unit,'deleted_at' => null];
			#$lap_skor 		 = $this->api->skor_lembur('lap_skor_kehadiran_detil',$where) ; 
			if($kode == "1 20 0308"){		
				$where 		= ['bulan' => $bulan, 'tahun' => $tahun, 'pns' => $pns,'deleted_at' => null];
				$where_in 	= ["1.00.00.00.00","1.00.01.00.00","1.00.02.00.00","1.00.03.00.00","1.00.11.00.00","1.00.12.00.00","1.00.13.00.00","1.03.01.00.00"];

				#KUNCIAN LAPORAN
			}else{
				$where 		= ['bulan' => $bulan, 'tahun' => $tahun, 'pns' => $pns,'id_instansi' => $kode_unit,'deleted_at' => null];
				$where_in 	= null ;

				$where_kunci = ['kd_instansi' => $kode_unit,"to_char(tgl_log, 'YYYY-MM' ) =" => $tahun.'-'.$bulan, 'is_kunci' => 'Y'];
			}

			$lap_skor 		 = $this->api->skor_lembur('lap_skor_kehadiran_detil',$where,$where_in) ; 
			
// 			var_dump($lap_skor);
// exit;
			if($lap_skor){
				foreach($lap_skor as $value){
					$dencode_skor= json_decode($value->skor);
					
					$totalSkor = 0;
					foreach( $dencode_skor as $sukur){
						$totalSkor += $sukur->skor; 					
					}

					$v_skor_total = 1400;
					# start perwali desember 2018
					if(($bulan == 12 && $tahun == 2018) || $tahun == 2019) {
						$v_skor_total = 1500;
					}

					$skorTPP = 100 - ($v_skor_total - $totalSkor);

					# start tambah (if seda otomatis 100)
					if($skorTPP < 0) {
						$skorTPP = 0;
					}
					# end tambah

					# start perwali desember 2018
					if(($bulan == 12 && $tahun == 2018) || $tahun == 2019) {
						if($value->meninggal == 't'){
							$skorTPP = 100;
						}
					}
					#TAMBAHAN CASE KHUSUS
					if($kode == "1 20 0308"){
						$where1 		= ['nip' => $value->nip,'bulan' => $bulan, 'tahun' => $tahun, 'pns' => $pns,'deleted_at' => null];
						#$where_in1 		= ["1.00.00.00.00","1.00.01.00.00","1.00.02.00.00","1.00.03.00.00","1.00.11.00.00","1.00.12.00.00","1.00.13.00.00","1.03.01.00.00"]; 
					}else{
						$where1 		= ['nip' => $value->nip,'bulan' => $bulan, 'tahun' => $tahun, 'pns' => $pns,'id_instansi' => $kode_unit,'deleted_at' => null];
						#$where_in1 = null;
					}
					$lap_skor_lembur = $this->api->skor_lembur('lap_absensi_lembur_detil',$where1,$where_in) ;
					
	
					$data2[] = [
						'kode_instansi' 	=> $kode,
						'nama' 				=> $value->nama, 
						'nip' 				=> $value->nip, 
						'golongan' 			=> $value->golongan, 
						'jabatan' 			=> $value->jabatan, 
						'instansi' 			=> $unit['0']->nama, 
						'skor_kehadiran' 	=> $skorTPP,
						'jml_hadir' 		=> $value->jml_hadir,
						'skor_lembur' 		=> $lap_skor_lembur['0']->skor_persen
					];

					#var_dump($data2);
					#exit;

					# end perwali desember 2018
					}

					$heading=array('No','Kode Instansi','Nama','NIP','Golongan','Jabatan','Intansi','Skor Kehadiran','Jumlah Hadir','Skor Lembur');
				    //Create a new Object
				    $objPHPExcel = new PHPExcel();
				    $objPHPExcel->getActiveSheet()->setTitle('LAPORAN SIPK');
				    //Loop Heading
				    $rowNumberH = 1;
				    $colH = 'A';
				    foreach($heading as $h){
				        $objPHPExcel->getActiveSheet()->setCellValue($colH.$rowNumberH,$h);
				        $colH++;    
				    }
				    //Loop Result
					$row = 2;
					$no = 1;
					$property_types = array();
			        foreach($data2 as $skm){
						if ( in_array($skm['nip'], $property_types) ) {
							continue;
						}
						// var_dump($skm['kode_instansi']);
			            $objPHPExcel->getActiveSheet()->setCellValue('A'.$row,$no);
			            $objPHPExcel->getActiveSheet()->setCellValue('B'.$row,$skm['kode_instansi']);
			            $objPHPExcel->getActiveSheet()->setCellValue('C'.$row,$skm['nama']);
			            $objPHPExcel->getActiveSheet()->setCellValueExplicit('D'.$row,$skm['nip'],PHPExcel_Cell_DataType::TYPE_STRING);
			            $objPHPExcel->getActiveSheet()->setCellValue('E'.$row,$skm['golongan']);
			            $objPHPExcel->getActiveSheet()->setCellValue('F'.$row,$skm['jabatan']);
			            $objPHPExcel->getActiveSheet()->setCellValue('G'.$row,$skm['instansi']);
			            $objPHPExcel->getActiveSheet()->setCellValue('H'.$row,$skm['skor_kehadiran']);
			            $objPHPExcel->getActiveSheet()->setCellValue('I'.$row,$skm['jml_hadir']);
			            $objPHPExcel->getActiveSheet()->setCellValue('J'.$row,$skm['skor_lembur']);
			           
			           
			            $row++;
			            $no++;
			        }
				    //Freeze pane
				    $objPHPExcel->getActiveSheet()->freezePane('A2');
				    //Cell Style
				    $styleArray = array(
				        'borders' => array(
				            'allborders' => array(
				                'style' => PHPExcel_Style_Border::BORDER_THIN
				            )
				        )
				    );
				    foreach(range('A','J') as $columnID) {
						    $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
						        ->setAutoSize(true);
						}
						

					
					    $objPHPExcel->getActiveSheet();
					    //Save as an Excel BIFF (xls) file
						$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');
						$nama = '"SIPK_'.$unit['0']->nama.'_'.$bulan.'-'.$tahun.'.xlsx"';

					    // header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
					   	// header('Content-Disposition: attachment;filename="SIPK_'.$unit['0']->nama.'_'.$bulan.'-'.$tahun.'.xlsx"');
						// header('Cache-Control: max-age=0');
						
						// $objWriter->save('php://output');
						$objWriter->save(str_replace(__FILE__,'upload/laporan_excel/SIPK_'.$unit['0']->nama.'_'.$bulan.'-'.$tahun.'.xlsx',__FILE__));

						$this->global->updatedata(['kode_sik' => $kode,'tahun' => $tahun, 'bulan' => $bulan],['status' => 1,'kirim' => 1,'jam_insert' => date('Y-m-d H:i:s')],'cron_excel');
						


					#echo json_encode(["bulan" => $input_array->bulan, "tahun" => $input_array->tahun ,"dataSource" => $data]);
				 }else{
					#echo 'ss';
					$this->global->updatedata(['kode_sik' => $kode,'tahun' => $tahun, 'bulan' => $bulan],['status' => null,'kirim' => 1,'jam_insert' => date('Y-m-d H:i:s')],'cron_excel');
					#echo $this->db->last_query();
					echo json_encode(array('status' => false, 'pesan' => 'Data Belum Di cetak oleh OPD'));
				}


			}
		}
	}

	public function absensi_pegawai2(){
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
				// if (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$tanggal)) {
				// 	echo json_encode(array('status' => false, 'pesan' => 'invalid date'));
				// 	exit;
				// }

				if(!preg_match('/^[a-zA-Z0-9 ]+$/', $kode)){
					echo json_encode(array('status' => false, 'pesan' => 'Karanter tidak di izinkan'));
					exit;
				}

					$this->load->model(['global_model', 'pegawai_model', 'instansi_model','data_mentah_model']);
		
					$whereInstansi 		=	"kode = '".$kode."' ";
					$this->dataInstansi = 	$this->instansi_model->getData($whereInstansi,"","");


					$this->load->library('konversi_menit');

					$whereInstansi 		=	"kode = '".$kode."' ";
					$this->dataInstansi = 	$this->instansi_model->getData($whereInstansi,"","");

					
							$namaBulan = array(
								'01' => 'JANUARI',
								'02' => 'FEBRUARI',
								'03' => 'MARET',
								'04' => 'APRIL',
								'05' => 'MEI',
								'06' => 'JUNI',
								'07' => 'JULI',
								'08' => 'AGUSTUS',
								'09' => 'SEPTEMBER',
								'10' => 'OKTOBER',
								'11' => 'NOVEMBER',
								'12' => 'DESEMBER'
							);


					$hari_ini 		= date($tahun."-".$bulan."-01");
					// Tanggal pertama pada bulan ini
					$tglMulai 	= date('Y-m-01', strtotime($hari_ini));
					// Tanggal terakhir pada bulan ini
					$tglSelesai 	= date('Y-m-t', strtotime($hari_ini));


					$hari_ini 		= date($tahun."-".$bulan."-01");
					// Tanggal pertama pada bulan ini
					$this->tgl_pertama 	= date('Y-m-01', strtotime($hari_ini));
					// Tanggal terakhir pada bulan ini
					$this->tgl_terakhir 	= date('Y-m-t', strtotime($hari_ini));


					$awal 		= strtotime($tglMulai);
					$akhir 		= strtotime($tglSelesai);

					$dt1 		= new DateTime($tglMulai);
					$dt2 		= new DateTime($tglSelesai);
					$jumlahHari = $dt1->diff($dt2) ;
					$jumlahHari = $jumlahHari->days + 1 ;


					$diff 			= abs($akhir-$awal);
					//$jumlahHari 	= $diff/86400;

					//var_dump($telat);

					$selainMinggu 	= array();
					$sabtuminggu 	= array();

					$tanggalsabtu 	= "";
					$tanggalminggu 	= "";
					$tanggalSeninJumat 	= "";


							$sabtuMinggu2 	= "";

					$iMinggu=0;
					$iMinggu2=1;
					$iSabtu=0;
					$iSeninJumat=0;
					for ($i=$awal; $i <= $akhir; $i += (60 * 60 * 24)) {




						if (date('w', $i) !== '0' && date('w', $i) !== '6') {

							$selainMinggu[] = $i;

							if($iSeninJumat == '1'){
								$tanggalSeninJumat .= "'".date('Y-m-d',$i)."'";
							}
							else{
								$tanggalSeninJumat .= ",'".date('Y-m-d',$i)."'";
							}
							$iSeninJumat++;


							$start_date = date ("Y-m-d", $i);
							$queryLibur 	=	$this->db->query("
							select
								s_hari_libur.id,
								m_hari_libur.id as id_hari_libur,
								m_hari_libur.nama
							from
								s_hari_libur ,m_hari_libur
							where
								s_hari_libur.tanggal = '".$start_date."'  and
								s_hari_libur.id_libur = m_hari_libur.id
							");
							$this->dataLibur	=	$queryLibur->row();
							if($this->dataLibur){
								if($iMinggu2 == 1){
									$sabtuMinggu2 .=  "'".date('Y-m-d',$i)."'";
								}
								else{
									$sabtuMinggu2 .=  ",'".date('Y-m-d',$i)."'";
								}

							$iMinggu2++;
							}

						} else {
							//$sabtuminggu2[] 	= date('Y-m-d',strtotime($i));
							//echo $iMinggu2;

							$sabtuminggu[] 	= $i;


							if($iMinggu2 == 1){
								$sabtuMinggu2 .=  "'".date('Y-m-d',$i)."'";
							}
							else{
								$sabtuMinggu2 .=  ",'".date('Y-m-d',$i)."'";
							}

							//echo $ii;
								if(date('w', $i) == '6'){
									if($iSabtu == '1'){
										$tanggalsabtu .= "'".date('Y-m-d',$i)."'";
									}
									else{
										$tanggalsabtu .= ",'".date('Y-m-d',$i)."'";
									}
									$iSabtu++;
								}


								if(date('w', $i) == '0'){
									if($iMinggu == '1'){
										$tanggalminggu .= "'".date('Y-m-d',$i)."'";
									}
									else{
										$tanggalminggu .= ",'".date('Y-m-d',$i)."'";
									}
									$iMinggu++;

								}
							$iMinggu2++;
						}
					}

				//	var_dump($sabtuMinggu2);

					$jumlahSabtuMinggu = $iSabtu + $iMinggu;

					$queryJumlahHariLibur 	=	$this->db->query("
					select
						count(*) as jumlah
					from
						s_hari_libur
					where
						tanggal >= '".$tglMulai."'
						AND tanggal <=  '".$tglSelesai."' and
						id not in (SELECT id FROM s_hari_libur WHERE EXTRACT(ISODOW FROM tanggal) IN (6, 7))
					");
					$dataJumlahHariLibur	=	$queryJumlahHariLibur->row();
					//var_dump($this->db->last_query());

					$jumlahSeninJumat	=	$jumlahHari - count($sabtuminggu);
					$jumlahMasuk		=	$jumlahSeninJumat - $dataJumlahHariLibur->jumlah;

					$jumlahLibur		=	$jumlahSabtuMinggu + $dataJumlahHariLibur->jumlah;




					$kodeAwalDinas	=	substr($this->input->get('id_instansi'),0,4);

					if($pns == 'y'){
						$wherePns 	= " and m.kode_status_pegawai='1'";
					}
					else{

						$wherePns 	= " and m.kode_status_pegawai!='1'";
					}

					$queryDataInstansi		=	$this->db->query("select * from m_instansi where kode_sik = '".$kode."'")->row();
				
				// REVISI SHOFI
				if (substr($queryDataInstansi->nama, 0, 9) != 'Kecamatan') {
					$kode_instansi_all = $queryDataInstansi->kode;
					$whereQuery = "pukh.kode_instansi = '".$kode_instansi_all."'".$wherePns;
					
				}else{
					$kode_instansi_all = substr($queryDataInstansi->kode, 0, 5);
					$whereQuery = "pukh.kode_instansi LIKE '".$kode_instansi_all.'%'."'".$wherePns;
				}

				// END

					$kode_instansi_all = substr($queryDataInstansi->kode, 0, 5);


					$queryPegawai 	=	$this->db->query("
					select
						m.id as id_pegawai,m.nama, m.nip,
						pukh.nama_unor,
						pukh.nama_instansi,
						pukh.kode_sik,
						pjh.nama_jabatan, pjh.urut,
						pgh.nama_golongan,
						peh.nama_eselon,
						prjh.nama_rumpun_jabatan
					from
						m_pegawai m
						LEFT JOIN LATERAL (
							SELECT
									h.kode_unor, h.tgl_mulai, muok.nama as nama_unor, mi.kode as kode_instansi, mi.nama as nama_instansi, mi.kode_sik AS kode_sik
							FROM
								m_pegawai_unit_kerja_histori h
								LEFT JOIN m_unit_organisasi_kerja muok ON  h.kode_unor =  muok.kode
								LEFT JOIN m_instansi mi ON  muok.kode_instansi = mi.kode WHERE h.tgl_mulai <= '".$tglSelesai."' and m.id = h.id_pegawai
							ORDER BY h.tgl_mulai DESC LIMIT 1
						)
						pukh ON true
						LEFT JOIN LATERAL (
							SELECT h.kode_jabatan, h.tgl_mulai, mjj.nama as nama_jabatan, mjj.urut FROM m_pegawai_jabatan_histori h LEFT JOIN m_jenis_jabatan mjj ON  h.kode_jabatan =  mjj.kode WHERE h.tgl_mulai <=  '".$tglSelesai."' and m.id = h.id_pegawai ORDER BY h.tgl_mulai DESC LIMIT 1
						)
						pjh ON true
						LEFT JOIN LATERAL (
							SELECT h.kode_golongan, h.tgl_mulai, mg.nama as nama_golongan FROM m_pegawai_golongan_histori h LEFT JOIN m_golongan mg ON  h.kode_golongan =  mg.kode WHERE h.tgl_mulai <=  '".$tglSelesai."' and m.id = h.id_pegawai  ORDER BY h.tgl_mulai DESC LIMIT 1
						)
						pgh ON true
						LEFT JOIN LATERAL (
							SELECT h.kode_eselon, h.tgl_mulai, me.nama_eselon FROM m_pegawai_eselon_histori h LEFT JOIN m_eselon me ON  h.kode_eselon =  me.kode WHERE h.tgl_mulai <=  '".$tglSelesai."' and m.id = h.id_pegawai  ORDER BY h.tgl_mulai DESC LIMIT 1
						)
						peh ON true
						LEFT JOIN LATERAL (
							SELECT h.id_rumpun_jabatan, h.tgl_mulai, mrj.nama as nama_rumpun_jabatan FROM m_pegawai_rumpun_jabatan_histori h LEFT JOIN m_rumpun_jabatan mrj ON  h.id_rumpun_jabatan =  mrj.id WHERE h.tgl_mulai <=  '".$tglSelesai."' and m.id = h.id_pegawai  ORDER BY h.tgl_mulai DESC LIMIT 1
						)
						prjh ON true
					where
						$whereQuery 
					order by
						pjh.urut,
						peh.kode_eselon,
						pgh.kode_golongan desc,
						m.nip

					");
					$this->dataPegawai	=	$queryPegawai->result();
					#PEGAWAI
					if($this->dataPegawai){
			//echo $this->db->last_query();
					$this->dataTable = "";
					// var_dump($this->dataPegawai);
					$i=1;
					foreach($this->dataPegawai as $dataPegawai){
						if($dataPegawai->nama_jabatan =='Staf'){
							$unor = $dataPegawai->nama_jabatan." - ".$dataPegawai->nama_rumpun_jabatan;
						}
						else{
							$unor = $dataPegawai->nama_jabatan;
						}
						
						

						//echo $jumlahLibur."<br>";
						$this->dataTable .= "<tr>";
						$this->dataTable .= "<td>".$i."</td>";
						$this->dataTable .= "<td>".$dataPegawai->nama."</td>";
						$this->dataTable .= "<td>".$dataPegawai->nip."</td>";
						$this->dataTable .= "<td>".$dataPegawai->nama_golongan."</td>";

						

						$this->dataTable .= "<td>".$unor." </td>";



						$queryJumlahLambatKurangLimaBelas	=	$this->db->query("
						select
							count(*) as jumlah
						from
							data_mentah
						where
							tanggal >= '".$tglMulai."'
							AND tanggal <=  '".$tglSelesai."' and
							id_pegawai = '".$dataPegawai->id_pegawai."' and
							datang_telat > 0 and datang_telat <= 15
						");

						$dataJumlahLambatKurangLimaBelas	=	$queryJumlahLambatKurangLimaBelas->row();
						$skorLambatKurangLimaBelas			=	100 - ($dataJumlahLambatKurangLimaBelas->jumlah * 1);

						$queryJumlahLambatKurangSatuJam		=	$this->db->query("
						select
							count(*) as jumlah
						from
							data_mentah
						where
							tanggal >= '".$tglMulai."'
							AND tanggal <=  '".$tglSelesai."' and
							id_pegawai = '".$dataPegawai->id_pegawai."' and
							datang_telat > 15 and datang_telat <= 60
						");

						$dataJumlahLambatKurangSatuJam	=	$queryJumlahLambatKurangSatuJam->row();
						$skorLambatKurangSatuJam		=	100 - ($dataJumlahLambatKurangSatuJam->jumlah * 2);

						$queryJumlahLambatKurangDuaJam		=	$this->db->query("
						select
							count(*) as jumlah
						from
							data_mentah
						where
							tanggal >= '".$tglMulai."'
							AND tanggal <=  '".$tglSelesai."' and
							id_pegawai = '".$dataPegawai->id_pegawai."' and
							datang_telat > 60 and datang_telat <= 120
						");

						$dataJumlahLambatKurangDuaJam	=	$queryJumlahLambatKurangDuaJam->row();
						$skorLambatKurangDuaJam		=	100 - ($dataJumlahLambatKurangDuaJam->jumlah * 3);

						$queryJumlahLambatKurangTigaJam		=	$this->db->query("
						select
							count(*) as jumlah
						from
							data_mentah
						where
							tanggal >= '".$tglMulai."'
							AND tanggal <=  '".$tglSelesai."' and
							id_pegawai = '".$dataPegawai->id_pegawai."' and
							datang_telat > 120 and datang_telat <= 180
						");

						$dataJumlahLambatKurangTigaJam	=	$queryJumlahLambatKurangTigaJam->row();
						$skorLambatKurangTigaJam		=	100 - ($dataJumlahLambatKurangTigaJam->jumlah * 4);

						$queryJumlahLambatKurangFull	=	$this->db->query("
						select
							count(*) as jumlah
						from
							data_mentah
						where
							tanggal >= '".$tglMulai."'
							AND tanggal <=  '".$tglSelesai."' and
							id_pegawai = '".$dataPegawai->id_pegawai."' and
							datang_telat > 180
						");

						$dataJumlahLambatKurangFull	=	$queryJumlahLambatKurangFull->row();
						$skorLambatKurangFull		=	100 - ($dataJumlahLambatKurangFull->jumlah * 5);



						//// pulang cepat

						$queryJumlahCepatKurangLimaBelas	=	$this->db->query("
						select
							count(*) as jumlah
						from
							data_mentah
						where
							tanggal >= '".$tglMulai."'
							AND tanggal <=  '".$tglSelesai."' and
							id_pegawai = '".$dataPegawai->id_pegawai."' and
							pulang_cepat > 0 and pulang_cepat <= 15
						");

						$dataJumlahCepatKurangLimaBelas	=	$queryJumlahCepatKurangLimaBelas->row();
						$skorCepatKurangLimaBelas			=	100 - ($dataJumlahCepatKurangLimaBelas->jumlah * 1);

						$queryJumlahCepatKurangSatuJam		=	$this->db->query("
						select
							count(*) as jumlah
						from
							data_mentah
						where
							tanggal >= '".$tglMulai."'
							AND tanggal <=  '".$tglSelesai."' and
							id_pegawai = '".$dataPegawai->id_pegawai."' and
							pulang_cepat > 15 and pulang_cepat <=60
						");

						$dataJumlahCepatKurangSatuJam	=	$queryJumlahCepatKurangSatuJam->row();
						$skorCepatKurangSatuJam		=	100 - ($dataJumlahCepatKurangSatuJam->jumlah * 2);

						$queryJumlahCepatKurangDuaJam		=	$this->db->query("
						select
							count(*) as jumlah
						from
							data_mentah
						where
							tanggal >= '".$tglMulai."'
							AND tanggal <=  '".$tglSelesai."' and
							id_pegawai = '".$dataPegawai->id_pegawai."' and
							pulang_cepat > 60 and pulang_cepat <= 120
						");

						$dataJumlahCepatKurangDuaJam	=	$queryJumlahCepatKurangDuaJam->row();
						$skorCepatKurangDuaJam		=	100 - ($dataJumlahCepatKurangDuaJam->jumlah * 3);

						$queryJumlahCepatKurangTigaJam		=	$this->db->query("
						select
							count(*) as jumlah
						from
							data_mentah
						where
							tanggal >= '".$tglMulai."'
							AND tanggal <=  '".$tglSelesai."' and
							id_pegawai = '".$dataPegawai->id_pegawai."' and
							pulang_cepat > 120 and pulang_cepat <=180
						");

						$dataJumlahCepatKurangTigaJam	=	$queryJumlahCepatKurangTigaJam->row();
						$skorCepatKurangTigaJam		=	100 - ($dataJumlahCepatKurangTigaJam->jumlah * 4);

						$queryJumlahCepatKurangFull	=	$this->db->query("
						select
							count(*) as jumlah
						from
							data_mentah
						where
							tanggal >= '".$tglMulai."'
							AND tanggal <=  '".$tglSelesai."' and
							id_pegawai = '".$dataPegawai->id_pegawai."' and
							pulang_cepat > 180
						");

						$dataJumlahCepatKurangFull	=	$queryJumlahCepatKurangFull->row();
						$skorCepatKurangFull		=	100 - ($dataJumlahCepatKurangFull->jumlah * 5);

						$this->dataTable .= "<td>".$dataJumlahCepatKurangLimaBelas->jumlah."</td>";
						$this->dataTable .= "<td>".$skorCepatKurangLimaBelas."</td>";
						$this->dataTable .= "<td>".$dataJumlahCepatKurangSatuJam->jumlah."</td>";
						$this->dataTable .= "<td>".$skorCepatKurangSatuJam."</td>";
						$this->dataTable .= "<td>".$dataJumlahCepatKurangDuaJam->jumlah."</td>";
						$this->dataTable .= "<td>".$skorCepatKurangDuaJam."</td>";
						$this->dataTable .= "<td>".$dataJumlahCepatKurangTigaJam->jumlah."</td>";
						$this->dataTable .= "<td>".$skorCepatKurangTigaJam."</td>";
						$this->dataTable .= "<td>".$dataJumlahCepatKurangFull->jumlah."</td>";
						$this->dataTable .= "<td>".$skorCepatKurangFull."</td>";



						$queryJumlahSakit	=	$this->db->query("
						select
							count(*) as jumlah
						from
							data_mentah
						where
							tanggal >= '".$tglMulai."'
							AND tanggal <=  '".$tglSelesai."' and
							id_pegawai = '".$dataPegawai->id_pegawai."' and
							JAM_KERJA in ('SK','CS')  and
							tanggal not in (
								select
									tanggal
								from
									s_hari_libur
								where
									tanggal >= '".$tglMulai."'
									AND tanggal <=  '".$tglSelesai."'
							) and
							EXTRACT(ISODOW FROM tanggal) not in (6, 7)
						");

						$dataJumlahSakit	=	$queryJumlahSakit->row();
						$skorJumlahSakit		=	100 - ($dataJumlahSakit->jumlah * 2);


						//////////////////////////

						$queryJumlahCutiBesar	=	$this->db->query("
						select
							count(*) as jumlah
						from
							data_mentah
						where
							tanggal >= '".$tglMulai."'
							AND tanggal <=  '".$tglSelesai."' and
							id_pegawai = '".$dataPegawai->id_pegawai."' and
							JAM_KERJA in ('CAP','CM','CH')	and
							tanggal not in (
								select
									tanggal
								from
									s_hari_libur
								where
									tanggal >= '".$tglMulai."'
									AND tanggal <=  '".$tglSelesai."'
							) and
							EXTRACT(ISODOW FROM tanggal) not in (6, 7)
						");
						//echo $dataJumlahCutiBesar->jumlah;
						$dataJumlahCutiBesar	=	$queryJumlahCutiBesar->row();
						$skorJumlahCutiBesar	=	100 - ($dataJumlahCutiBesar->jumlah * 4);

						$queryJumlahTidakHadirSah	=	$this->db->query("
						select
							count(*) as jumlah
						from
							data_mentah
						where
							tanggal >= '".$tglMulai."'
							AND tanggal <=  '".$tglSelesai."' and
							id_pegawai = '".$dataPegawai->id_pegawai."' and
							JAM_KERJA in ('I') and
							tanggal not in (
								select
									tanggal
								from
									s_hari_libur
								where
									tanggal >= '".$tglMulai."'
									AND tanggal <=  '".$tglSelesai."'
							) and
							EXTRACT(ISODOW FROM tanggal) not in (6, 7)
						");

						$dataJumlahTidakHadirSah	=	$queryJumlahTidakHadirSah->row() ;
						$skorJumlahTidakHadirSah	=	100 - ($dataJumlahTidakHadirSah->jumlah * 5);

						$queryJumlahTidakHadirTidakSah	=	$this->db->query("
						select
							count(*) as jumlah
						from
							data_mentah
						where
							tanggal >= '".$tglMulai."'
							AND tanggal <=  '".$tglSelesai."' and
							id_pegawai = '".$dataPegawai->id_pegawai."' and
							kode_masuk in ('M') and
							tanggal not in (
								select
									tanggal
								from
									s_hari_libur
								where
									tanggal >= '".$tglMulai."'
									AND tanggal <=  '".$tglSelesai."'
							) and
							EXTRACT(ISODOW FROM tanggal) not in (6, 7)
						");

						$dataJumlahTidakHadirTidakSah	=	$queryJumlahTidakHadirTidakSah->row();
						$skorJumlahTidakHadirTidakSah	=	100 - ($dataJumlahTidakHadirTidakSah->jumlah * 6);


						$skorTotal =
									$skorLambatKurangLimaBelas +
									$skorLambatKurangSatuJam +
									$skorLambatKurangDuaJam  +
									$skorLambatKurangTigaJam +
									$skorLambatKurangFull +

									$skorCepatKurangLimaBelas +
									$skorCepatKurangSatuJam +
									$skorCepatKurangDuaJam +
									$skorCepatKurangTigaJam +
									$skorCepatKurangFull +

									$skorJumlahSakit +
									$skorJumlahCutiBesar +
									$skorJumlahTidakHadirSah +
									$skorJumlahTidakHadirTidakSah ;




						/** JUMLAH HADIR TOTAL **/

						// $queryJumlahHadirTotal	=	$this->db->query("
						// select
						// 	count(*) as jumlah
						// from
						// 	data_mentah
						// where
						// 	tanggal >= '".$tglMulai."'
						// 	AND tanggal <=  '".$tglSelesai."' and
						// 	id_pegawai = '".$dataPegawai->id_pegawai."' and
						// 	finger_masuk is not null  and kode_masuk ='H' and
						// 	tanggal not in (
						// 		select
						// 			tanggal
						// 		from
						// 			s_hari_libur
						// 		where
						// 			tanggal >= '".$tglMulai."'
						// 			AND tanggal <=  '".$tglSelesai."'
						// 	) and
						// 	EXTRACT(ISODOW FROM tanggal) not in (6, 7)

						// ");
						$queryJumlahHadirTotal	=	$this->db->query("
							select
								count(*) as jumlah
							from
								data_mentah
							where
								tanggal >= '".$tglMulai."'
								AND tanggal <=  '".$tglSelesai."' and
								id_pegawai = '".$dataPegawai->id_pegawai."' and
								kode_masuk ='H'

							");
						$jumlahMasukTotal	=	$queryJumlahHadirTotal->row();

						$skorTPP = 100 - (1400 - $skorTotal);

						$this->dataTable .= "<td>".$skorTPP."</td>";
						$this->dataTable .= "</tr>";

						/** SEKOR LEMBUR **/
					
							// Tanggal pertama pada bulan ini
							$this->tgl_pertama 	= date('Y-m-01', strtotime($hari_ini));
							// Tanggal terakhir pada bulan ini
							$this->tgl_terakhir 	= date('Y-m-t', strtotime($hari_ini));
						$totalLemburJumlah 			= 0;
							$totalLemburJumlahDiakui 	= 0;

							while (strtotime($this->tgl_pertama) <= strtotime($this->tgl_terakhir )) {


								$queryJumlahLembur	=	$this->db->query("select lembur,lembur_diakui from data_mentah where id_pegawai='".$dataPegawai->id_pegawai."' and tanggal='".$this->tgl_pertama."'");
								$dataHasilLembur	=	$queryJumlahLembur->row();

								if($dataHasilLembur){
									$lemburJumlah = $dataHasilLembur->lembur;
									$lemburJumlahDiakui = $dataHasilLembur->lembur_diakui;
								}
								else{
									$lemburJumlah = "0";
									$lemburJumlahDiakui = "0";
								}
								$lembur = $this->konversi_menit->hitung($lemburJumlah);

								if($lemburJumlah == 0){
									$color="red";
								}
								elseif($lemburJumlah != $lemburJumlahDiakui ){
									$color="red";
								}
								else{
									$color="";
								}

						
								$totalLemburJumlah += $lemburJumlah;
								$totalLemburJumlahDiakui += $lemburJumlahDiakui;

								$this->tgl_pertama = date ("Y-m-d", strtotime("+1 days", strtotime($this->tgl_pertama)));
							}


								$jumlahPersen 	= round(($totalLemburJumlah / 1800) * 100);
								if($jumlahPersen > 99){
									$jumlahPersen = 100;
								}
								else{
									$jumlahPersen = "<span style='color:orange;'>".$jumlahPersen."</span>";
								}

							$jumlahLembur			=	$this->konversi_menit->hitung($totalLemburJumlahDiakui);

							$bulan 		=	date('Y-m', strtotime($hari_ini));
							if($bulan =='2018-05' || $bulan =='2018-06'){
								$where	=	"and jenis = 'RAMADHAN'";
							}
							else{
								$where	=	"and jenis = 'BIASA'";
							}

							$queryPersen 	=	$this->db->query("select skor from m_skor_lembur where menit_mulai <='".$totalLemburJumlahDiakui."' and menit_akhir >= '".$totalLemburJumlahDiakui."' $where");
							$dataPersen		=	$queryPersen->row();

						$i++;
						$peg[] = array(
										"kode_instasi"		=> ($dataPegawai->kode_sik)?$dataPegawai->kode_sik:$input_array->kode_instansi,
										// "skor_lembur"		=> 100,
										"nama"				=> $dataPegawai->nama,
										"nip"				=> $dataPegawai->nip,
										// "jml_hadir"			=> 20.0,
										// "skor_kehadiran"	=> 99.0,
										"golongan" 			=> $dataPegawai->nama_golongan,
										"jabatan" 			=> $unor,
										"instansi" 			=> $dataPegawai->nama_instansi,
										"skor_kehadiran" 	=> $skorTPP,
										"jml_hadir" 		=> $jumlahMasukTotal->jumlah,
										"skor_lembur" 		=> $dataPersen->skor
									);
					}
					echo json_encode(array('dataSource' => $peg));
				}else{
					echo json_encode(array('status' => false, 'pesan' => 'Data tidak di temukan'));
				}
			}else{
				echo json_encode(array('status' => false, 'pesan' => 'invalid data'));
			}
		}else{
			header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
		}
	}

// 	public function coba(){
// 		$this->load->model(['global_model', 'pegawai_model', 'instansi_model','data_mentah_model']);
		
// 		$whereInstansi 		=	"kode = '".$this->input->get('id_instansi')."' ";
// 		$this->dataInstansi = 	$this->instansi_model->getData($whereInstansi,"","");


// 		$this->load->library('konversi_menit');

// 		$whereInstansi 		=	"kode = '".$this->input->get('id_instansi')."' ";
// 		$this->dataInstansi = 	$this->instansi_model->getData($whereInstansi,"","");


// 		$this->dataTables = "";

// 		$hari_ini 		= date($this->input->get("tahun")."-".$this->input->get("bulan")."-01");
// 		// Tanggal pertama pada bulan ini
// 		$tglMulai 	= date('Y-m-01', strtotime($hari_ini));
// 		// Tanggal terakhir pada bulan ini
// 		$tglSelesai 	= date('Y-m-t', strtotime($hari_ini));


// 		$hari_ini 		= date($this->input->get("tahun")."-".$this->input->get("bulan")."-01");
// 		// Tanggal pertama pada bulan ini
// 		$this->tgl_pertama 	= date('Y-m-01', strtotime($hari_ini));
// 		// Tanggal terakhir pada bulan ini
// 		$this->tgl_terakhir 	= date('Y-m-t', strtotime($hari_ini));


// 		$awal 		= strtotime($tglMulai);
// 		$akhir 		= strtotime($tglSelesai);

// 		$dt1 		= new DateTime($tglMulai);
// 		$dt2 		= new DateTime($tglSelesai);
// 		$jumlahHari = $dt1->diff($dt2) ;
// 		$jumlahHari = $jumlahHari->days + 1 ;


// 		$diff 			= abs($akhir-$awal);
// 		//$jumlahHari 	= $diff/86400;

// 		//var_dump($telat);

// 		$selainMinggu 	= array();
// 		$sabtuminggu 	= array();

// 		$tanggalsabtu 	= "";
// 		$tanggalminggu 	= "";
// 		$tanggalSeninJumat 	= "";


// 				$sabtuMinggu2 	= "";

// 		$iMinggu=0;
// 		$iMinggu2=1;
// 		$iSabtu=0;
// 		$iSeninJumat=0;
// 		for ($i=$awal; $i <= $akhir; $i += (60 * 60 * 24)) {




// 			if (date('w', $i) !== '0' && date('w', $i) !== '6') {

// 				$selainMinggu[] = $i;

// 				if($iSeninJumat == '1'){
// 					$tanggalSeninJumat .= "'".date('Y-m-d',$i)."'";
// 				}
// 				else{
// 					$tanggalSeninJumat .= ",'".date('Y-m-d',$i)."'";
// 				}
// 				$iSeninJumat++;


// 				$start_date = date ("Y-m-d", $i);
// 				$queryLibur 	=	$this->db->query("
// 				select
// 					s_hari_libur.id,
// 					m_hari_libur.id as id_hari_libur,
// 					m_hari_libur.nama
// 				from
// 					s_hari_libur ,m_hari_libur
// 				where
// 					s_hari_libur.tanggal = '".$start_date."'  and
// 					s_hari_libur.id_libur = m_hari_libur.id
// 				");
// 				$this->dataLibur	=	$queryLibur->row();
// 				if($this->dataLibur){
// 					if($iMinggu2 == 1){
// 						$sabtuMinggu2 .=  "'".date('Y-m-d',$i)."'";
// 					}
// 					else{
// 						$sabtuMinggu2 .=  ",'".date('Y-m-d',$i)."'";
// 					}

// 				$iMinggu2++;
// 				}

// 			} else {
// 				//$sabtuminggu2[] 	= date('Y-m-d',strtotime($i));
// 				//echo $iMinggu2;

// 				$sabtuminggu[] 	= $i;


// 				if($iMinggu2 == 1){
// 					 $sabtuMinggu2 .=  "'".date('Y-m-d',$i)."'";
// 				}
// 				else{
// 					$sabtuMinggu2 .=  ",'".date('Y-m-d',$i)."'";
// 				}

// 				//echo $ii;
// 					if(date('w', $i) == '6'){
// 						if($iSabtu == '1'){
// 							$tanggalsabtu .= "'".date('Y-m-d',$i)."'";
// 						}
// 						else{
// 							$tanggalsabtu .= ",'".date('Y-m-d',$i)."'";
// 						}
// 						$iSabtu++;
// 					}


// 					if(date('w', $i) == '0'){
// 						if($iMinggu == '1'){
// 							$tanggalminggu .= "'".date('Y-m-d',$i)."'";
// 						}
// 						else{
// 							$tanggalminggu .= ",'".date('Y-m-d',$i)."'";
// 						}
// 						$iMinggu++;

// 					}
// 				$iMinggu2++;
// 			}
// 		}

// 	//	var_dump($sabtuMinggu2);

// 		$jumlahSabtuMinggu = $iSabtu + $iMinggu;

// 		$queryJumlahHariLibur 	=	$this->db->query("
// 		select
// 			count(*) as jumlah
// 		from
// 			s_hari_libur
// 		where
// 			tanggal >= '".$tglMulai."'
// 			AND tanggal <=  '".$tglSelesai."' and
// 			id not in (SELECT id FROM s_hari_libur WHERE EXTRACT(ISODOW FROM tanggal) IN (6, 7))
// 		");
// 		$dataJumlahHariLibur	=	$queryJumlahHariLibur->row();
// 		//var_dump($this->db->last_query());

// 	 	$jumlahSeninJumat	=	$jumlahHari - count($sabtuminggu);
// 	 	$jumlahMasuk		=	$jumlahSeninJumat - $dataJumlahHariLibur->jumlah;

// 		$jumlahLibur		=	$jumlahSabtuMinggu + $dataJumlahHariLibur->jumlah;




// 		$kodeAwalDinas	=	substr($this->input->get('id_instansi'),0,4);

// 		if($this->input->get("pns") == 'y'){
// 			$wherePns 	= " and m.kode_status_pegawai='1'";
// 		}
// 		else{

// 			$wherePns 	= " and m.kode_status_pegawai!='1'";
// 		}


// 		$queryPegawai 	=	$this->db->query("
// 		select
// 			m.id as id_pegawai,m.nama, m.nip,
// 			pukh.nama_unor,
// 			pukh.nama_instansi,
// 			pjh.nama_jabatan, pjh.urut,
// 			pgh.nama_golongan,
// 			peh.nama_eselon,
// 			prjh.nama_rumpun_jabatan
// 		from
// 			m_pegawai m
// 			LEFT JOIN LATERAL (
// 				SELECT
// 					h.kode_unor, h.tgl_mulai, muok.nama as nama_unor, mi.kode as kode_instansi, mi.nama as nama_instansi
// 				FROM
// 					m_pegawai_unit_kerja_histori h
// 					LEFT JOIN m_unit_organisasi_kerja muok ON  h.kode_unor =  muok.kode
// 					LEFT JOIN m_instansi mi ON  muok.kode_instansi = mi.kode WHERE h.tgl_mulai <= '".$tglSelesai."' and m.id = h.id_pegawai
// 				ORDER BY h.tgl_mulai DESC LIMIT 1
// 			)
// 			pukh ON true
// 			LEFT JOIN LATERAL (
// 				SELECT h.kode_jabatan, h.tgl_mulai, mjj.nama as nama_jabatan, mjj.urut FROM m_pegawai_jabatan_histori h LEFT JOIN m_jenis_jabatan mjj ON  h.kode_jabatan =  mjj.kode WHERE h.tgl_mulai <=  '".$tglSelesai."' and m.id = h.id_pegawai ORDER BY h.tgl_mulai DESC LIMIT 1
// 			)
// 			pjh ON true
// 			LEFT JOIN LATERAL (
// 				SELECT h.kode_golongan, h.tgl_mulai, mg.nama as nama_golongan FROM m_pegawai_golongan_histori h LEFT JOIN m_golongan mg ON  h.kode_golongan =  mg.kode WHERE h.tgl_mulai <=  '".$tglSelesai."' and m.id = h.id_pegawai  ORDER BY h.tgl_mulai DESC LIMIT 1
// 			)
// 			pgh ON true
// 			LEFT JOIN LATERAL (
// 				SELECT h.kode_eselon, h.tgl_mulai, me.nama_eselon FROM m_pegawai_eselon_histori h LEFT JOIN m_eselon me ON  h.kode_eselon =  me.kode WHERE h.tgl_mulai <=  '".$tglSelesai."' and m.id = h.id_pegawai  ORDER BY h.tgl_mulai DESC LIMIT 1
// 			)
// 			peh ON true
// 			LEFT JOIN LATERAL (
// 				SELECT h.id_rumpun_jabatan, h.tgl_mulai, mrj.nama as nama_rumpun_jabatan FROM m_pegawai_rumpun_jabatan_histori h LEFT JOIN m_rumpun_jabatan mrj ON  h.id_rumpun_jabatan =  mrj.id WHERE h.tgl_mulai <=  '".$tglSelesai."' and m.id = h.id_pegawai  ORDER BY h.tgl_mulai DESC LIMIT 1
// 			)
// 			prjh ON true
// 		where
// 			pukh.kode_instansi = '".$this->input->get('id_instansi')."' $wherePns
// 		order by
// 			pjh.urut,
// 			peh.kode_eselon,
// 			pgh.kode_golongan desc,
// 			m.nip

// 		");
// 		$this->dataPegawai	=	$queryPegawai->result();
// //echo $this->db->last_query();
// 		$this->dataTable = "";

// 		$i=1;
// 		foreach($this->dataPegawai as $dataPegawai){



// 			//echo $jumlahLibur."<br>";
// 			$this->dataTable .= "<tr>";
// 			$this->dataTable .= "<td>".$i."</td>";
// 			$this->dataTable .= "<td>".$dataPegawai->nama."</td>";
// 			$this->dataTable .= "<td>".$dataPegawai->nip."</td>";
// 			$this->dataTable .= "<td>".$dataPegawai->nama_golongan."</td>";

// 			if($dataPegawai->nama_jabatan =='Staf'){
// 				$unor = $dataPegawai->nama_jabatan." - ".$dataPegawai->nama_rumpun_jabatan;
// 			}
// 			else{
// 				$unor = $dataPegawai->nama_jabatan;
// 			}

// 			$this->dataTable .= "<td>".$unor." </td>";



// 			$queryJumlahLambatKurangLimaBelas	=	$this->db->query("
// 			select
// 				count(*) as jumlah
// 			from
// 				data_mentah
// 			where
// 				tanggal >= '".$tglMulai."'
// 				AND tanggal <=  '".$tglSelesai."' and
// 				id_pegawai = '".$dataPegawai->id_pegawai."' and
// 				datang_telat > 0 and datang_telat <= 15
// 			");

// 			$dataJumlahLambatKurangLimaBelas	=	$queryJumlahLambatKurangLimaBelas->row();
// 			$skorLambatKurangLimaBelas			=	100 - ($dataJumlahLambatKurangLimaBelas->jumlah * 1);

// 			$queryJumlahLambatKurangSatuJam		=	$this->db->query("
// 			select
// 				count(*) as jumlah
// 			from
// 				data_mentah
// 			where
// 				tanggal >= '".$tglMulai."'
// 				AND tanggal <=  '".$tglSelesai."' and
// 				id_pegawai = '".$dataPegawai->id_pegawai."' and
// 				datang_telat > 15 and datang_telat <= 60
// 			");

// 			$dataJumlahLambatKurangSatuJam	=	$queryJumlahLambatKurangSatuJam->row();
// 			$skorLambatKurangSatuJam		=	100 - ($dataJumlahLambatKurangSatuJam->jumlah * 2);

// 			$queryJumlahLambatKurangDuaJam		=	$this->db->query("
// 			select
// 				count(*) as jumlah
// 			from
// 				data_mentah
// 			where
// 				tanggal >= '".$tglMulai."'
// 				AND tanggal <=  '".$tglSelesai."' and
// 				id_pegawai = '".$dataPegawai->id_pegawai."' and
// 				datang_telat > 60 and datang_telat <= 120
// 			");

// 			$dataJumlahLambatKurangDuaJam	=	$queryJumlahLambatKurangDuaJam->row();
// 			$skorLambatKurangDuaJam		=	100 - ($dataJumlahLambatKurangDuaJam->jumlah * 3);

// 			$queryJumlahLambatKurangTigaJam		=	$this->db->query("
// 			select
// 				count(*) as jumlah
// 			from
// 				data_mentah
// 			where
// 				tanggal >= '".$tglMulai."'
// 				AND tanggal <=  '".$tglSelesai."' and
// 				id_pegawai = '".$dataPegawai->id_pegawai."' and
// 				datang_telat > 120 and datang_telat <= 180
// 			");

// 			$dataJumlahLambatKurangTigaJam	=	$queryJumlahLambatKurangTigaJam->row();
// 			$skorLambatKurangTigaJam		=	100 - ($dataJumlahLambatKurangTigaJam->jumlah * 4);

// 			$queryJumlahLambatKurangFull	=	$this->db->query("
// 			select
// 				count(*) as jumlah
// 			from
// 				data_mentah
// 			where
// 				tanggal >= '".$tglMulai."'
// 				AND tanggal <=  '".$tglSelesai."' and
// 				id_pegawai = '".$dataPegawai->id_pegawai."' and
// 				datang_telat > 180
// 			");

// 			$dataJumlahLambatKurangFull	=	$queryJumlahLambatKurangFull->row();
// 			$skorLambatKurangFull		=	100 - ($dataJumlahLambatKurangFull->jumlah * 5);





// 			$this->dataTable .= "<td>".$dataJumlahLambatKurangLimaBelas->jumlah."</td>";
// 			$this->dataTable .= "<td>".$skorLambatKurangLimaBelas."</td>";
// 			$this->dataTable .= "<td>".$dataJumlahLambatKurangSatuJam->jumlah."</td>";
// 			$this->dataTable .= "<td>".$skorLambatKurangSatuJam."</td>";
// 			$this->dataTable .= "<td>".$dataJumlahLambatKurangDuaJam->jumlah."</td>";
// 			$this->dataTable .= "<td>".$skorLambatKurangDuaJam."</td>";
// 			$this->dataTable .= "<td>".$dataJumlahLambatKurangTigaJam->jumlah."</td>";
// 			$this->dataTable .= "<td>".$skorLambatKurangTigaJam."</td>";
// 			$this->dataTable .= "<td>".$dataJumlahLambatKurangFull->jumlah."</td>";
// 			$this->dataTable .= "<td>".$skorLambatKurangFull."</td>";



// 			//// pulang cepat

// 			$queryJumlahCepatKurangLimaBelas	=	$this->db->query("
// 			select
// 				count(*) as jumlah
// 			from
// 				data_mentah
// 			where
// 				tanggal >= '".$tglMulai."'
// 				AND tanggal <=  '".$tglSelesai."' and
// 				id_pegawai = '".$dataPegawai->id_pegawai."' and
// 				pulang_cepat > 0 and pulang_cepat <= 15
// 			");

// 			$dataJumlahCepatKurangLimaBelas	=	$queryJumlahCepatKurangLimaBelas->row();
// 			$skorCepatKurangLimaBelas			=	100 - ($dataJumlahCepatKurangLimaBelas->jumlah * 1);

// 			$queryJumlahCepatKurangSatuJam		=	$this->db->query("
// 			select
// 				count(*) as jumlah
// 			from
// 				data_mentah
// 			where
// 				tanggal >= '".$tglMulai."'
// 				AND tanggal <=  '".$tglSelesai."' and
// 				id_pegawai = '".$dataPegawai->id_pegawai."' and
// 				pulang_cepat > 15 and pulang_cepat <=60
// 			");

// 			$dataJumlahCepatKurangSatuJam	=	$queryJumlahCepatKurangSatuJam->row();
// 			$skorCepatKurangSatuJam		=	100 - ($dataJumlahCepatKurangSatuJam->jumlah * 2);

// 			$queryJumlahCepatKurangDuaJam		=	$this->db->query("
// 			select
// 				count(*) as jumlah
// 			from
// 				data_mentah
// 			where
// 				tanggal >= '".$tglMulai."'
// 				AND tanggal <=  '".$tglSelesai."' and
// 				id_pegawai = '".$dataPegawai->id_pegawai."' and
// 				pulang_cepat > 60 and pulang_cepat <= 120
// 			");

// 			$dataJumlahCepatKurangDuaJam	=	$queryJumlahCepatKurangDuaJam->row();
// 			$skorCepatKurangDuaJam		=	100 - ($dataJumlahCepatKurangDuaJam->jumlah * 3);

// 			$queryJumlahCepatKurangTigaJam		=	$this->db->query("
// 			select
// 				count(*) as jumlah
// 			from
// 				data_mentah
// 			where
// 				tanggal >= '".$tglMulai."'
// 				AND tanggal <=  '".$tglSelesai."' and
// 				id_pegawai = '".$dataPegawai->id_pegawai."' and
// 				pulang_cepat > 120 and pulang_cepat <=180
// 			");

// 			$dataJumlahCepatKurangTigaJam	=	$queryJumlahCepatKurangTigaJam->row();
// 			$skorCepatKurangTigaJam		=	100 - ($dataJumlahCepatKurangTigaJam->jumlah * 4);

// 			$queryJumlahCepatKurangFull	=	$this->db->query("
// 			select
// 				count(*) as jumlah
// 			from
// 				data_mentah
// 			where
// 				tanggal >= '".$tglMulai."'
// 				AND tanggal <=  '".$tglSelesai."' and
// 				id_pegawai = '".$dataPegawai->id_pegawai."' and
// 				pulang_cepat > 180
// 			");

// 			$dataJumlahCepatKurangFull	=	$queryJumlahCepatKurangFull->row();
// 			$skorCepatKurangFull		=	100 - ($dataJumlahCepatKurangFull->jumlah * 5);

// 			$this->dataTable .= "<td>".$dataJumlahCepatKurangLimaBelas->jumlah."</td>";
// 			$this->dataTable .= "<td>".$skorCepatKurangLimaBelas."</td>";
// 			$this->dataTable .= "<td>".$dataJumlahCepatKurangSatuJam->jumlah."</td>";
// 			$this->dataTable .= "<td>".$skorCepatKurangSatuJam."</td>";
// 			$this->dataTable .= "<td>".$dataJumlahCepatKurangDuaJam->jumlah."</td>";
// 			$this->dataTable .= "<td>".$skorCepatKurangDuaJam."</td>";
// 			$this->dataTable .= "<td>".$dataJumlahCepatKurangTigaJam->jumlah."</td>";
// 			$this->dataTable .= "<td>".$skorCepatKurangTigaJam."</td>";
// 			$this->dataTable .= "<td>".$dataJumlahCepatKurangFull->jumlah."</td>";
// 			$this->dataTable .= "<td>".$skorCepatKurangFull."</td>";



// 			$queryJumlahSakit	=	$this->db->query("
// 			select
// 				count(*) as jumlah
// 			from
// 				data_mentah
// 			where
// 				tanggal >= '".$tglMulai."'
// 				AND tanggal <=  '".$tglSelesai."' and
// 				id_pegawai = '".$dataPegawai->id_pegawai."' and
// 				JAM_KERJA in ('SK','CS')  and
// 				tanggal not in (
// 					select
// 						tanggal
// 					from
// 						s_hari_libur
// 					where
// 						tanggal >= '".$tglMulai."'
// 						AND tanggal <=  '".$tglSelesai."'
// 				) and
// 				EXTRACT(ISODOW FROM tanggal) not in (6, 7)
// 			");

// 			$dataJumlahSakit	=	$queryJumlahSakit->row();
// 			$skorJumlahSakit		=	100 - ($dataJumlahSakit->jumlah * 2);

// 			$this->dataTable .= "<td>".$dataJumlahSakit->jumlah."</td>";
// 			$this->dataTable .= "<td>".$skorJumlahSakit."</td>";

// 			//////////////////////////

// 			$queryJumlahCutiBesar	=	$this->db->query("
// 			select
// 				count(*) as jumlah
// 			from
// 				data_mentah
// 			where
// 				tanggal >= '".$tglMulai."'
// 				AND tanggal <=  '".$tglSelesai."' and
// 				id_pegawai = '".$dataPegawai->id_pegawai."' and
// 				JAM_KERJA in ('CAP','CM','CH')	and
// 				tanggal not in (
// 					select
// 						tanggal
// 					from
// 						s_hari_libur
// 					where
// 						tanggal >= '".$tglMulai."'
// 						AND tanggal <=  '".$tglSelesai."'
// 				) and
// 				EXTRACT(ISODOW FROM tanggal) not in (6, 7)
// 			");
// 			//echo $dataJumlahCutiBesar->jumlah;
// 			$dataJumlahCutiBesar	=	$queryJumlahCutiBesar->row();
// 			$skorJumlahCutiBesar	=	100 - ($dataJumlahCutiBesar->jumlah * 4);


// 			$this->dataTable .= "<td>".$dataJumlahCutiBesar->jumlah."</td>";
// 			$this->dataTable .= "<td>".$skorJumlahCutiBesar."</td>";


// 			////////////////////////

// 			$queryJumlahTidakHadirSah	=	$this->db->query("
// 			select
// 				count(*) as jumlah
// 			from
// 				data_mentah
// 			where
// 				tanggal >= '".$tglMulai."'
// 				AND tanggal <=  '".$tglSelesai."' and
// 				id_pegawai = '".$dataPegawai->id_pegawai."' and
// 				JAM_KERJA in ('I') and
// 				tanggal not in (
// 					select
// 						tanggal
// 					from
// 						s_hari_libur
// 					where
// 						tanggal >= '".$tglMulai."'
// 						AND tanggal <=  '".$tglSelesai."'
// 				) and
// 				EXTRACT(ISODOW FROM tanggal) not in (6, 7)
// 			");

// 			$dataJumlahTidakHadirSah	=	$queryJumlahTidakHadirSah->row() ;
// 			$skorJumlahTidakHadirSah	=	100 - ($dataJumlahTidakHadirSah->jumlah * 5);





// 			$this->dataTable .= "<td>".$dataJumlahTidakHadirSah->jumlah."</td>";
// 			$this->dataTable .= "<td>".$skorJumlahTidakHadirSah."</td>";


// 			////////////////////////

// 			$queryJumlahTidakHadirTidakSah	=	$this->db->query("
// 			select
// 				count(*) as jumlah
// 			from
// 				data_mentah
// 			where
// 				tanggal >= '".$tglMulai."'
// 				AND tanggal <=  '".$tglSelesai."' and
// 				id_pegawai = '".$dataPegawai->id_pegawai."' and
// 				kode_masuk in ('M') and
// 				tanggal not in (
// 					select
// 						tanggal
// 					from
// 						s_hari_libur
// 					where
// 						tanggal >= '".$tglMulai."'
// 						AND tanggal <=  '".$tglSelesai."'
// 				) and
// 				EXTRACT(ISODOW FROM tanggal) not in (6, 7)
// 			");

// 			$dataJumlahTidakHadirTidakSah	=	$queryJumlahTidakHadirTidakSah->row();
// 			$skorJumlahTidakHadirTidakSah	=	100 - ($dataJumlahTidakHadirTidakSah->jumlah * 6);


// 			$this->dataTable .= "<td>".$dataJumlahTidakHadirTidakSah->jumlah."</td>";
// 			$this->dataTable .= "<td>".$skorJumlahTidakHadirTidakSah."</td>";

// 			/////


// 			$skorTotal =
// 						$skorLambatKurangLimaBelas +
// 						$skorLambatKurangSatuJam +
// 						$skorLambatKurangDuaJam  +
// 						$skorLambatKurangTigaJam +
// 						$skorLambatKurangFull +

// 						$skorCepatKurangLimaBelas +
// 						$skorCepatKurangSatuJam +
// 						$skorCepatKurangDuaJam +
// 						$skorCepatKurangTigaJam +
// 						$skorCepatKurangFull +

// 						$skorJumlahSakit +
// 						$skorJumlahCutiBesar +
// 						$skorJumlahTidakHadirSah +
// 						$skorJumlahTidakHadirTidakSah ;




// 			$this->dataTable .= "<td>".number_format($skorTotal)."</td>";




// 			$this->dataTable .= "<td>".$jumlahMasuk."</td>";

// 			/////////////////////////

// 			$queryJumlahHadirTotal	=	$this->db->query("
// 			select
// 				count(*) as jumlah
// 			from
// 				data_mentah
// 			where
// 				tanggal >= '".$tglMulai."'
// 				AND tanggal <=  '".$tglSelesai."' and
// 				id_pegawai = '".$dataPegawai->id_pegawai."' and
// 				finger_masuk is not null  and kode_masuk ='H' and
// 				tanggal not in (
// 					select
// 						tanggal
// 					from
// 						s_hari_libur
// 					where
// 						tanggal >= '".$tglMulai."'
// 						AND tanggal <=  '".$tglSelesai."'
// 				) and
// 				EXTRACT(ISODOW FROM tanggal) not in (6, 7)

// 			");
// 			$jumlahMasukTotal	=	$queryJumlahHadirTotal->row();

// 			$this->dataTable .= "<td>".$jumlahMasukTotal->jumlah."</td>";

// 			//////////////////////


// 			$queryJumlahDinasLuar	=	$this->db->query("
// 			select
// 				count(*) as jumlah
// 			from
// 				data_mentah
// 			where
// 				tanggal >= '".$tglMulai."'
// 				AND tanggal <=  '".$tglSelesai."' and
// 				id_pegawai = '".$dataPegawai->id_pegawai."' and
// 				JAM_KERJA in ('DL','DK') and
// 				tanggal not in (
// 					select
// 						tanggal
// 					from
// 						s_hari_libur
// 					where
// 						tanggal >= '".$tglMulai."'
// 						AND tanggal <=  '".$tglSelesai."'
// 				) and
// 				EXTRACT(ISODOW FROM tanggal) not in (6, 7)
// 			");

// 			$dataJumlahDinasLuar	=	$queryJumlahDinasLuar->row();

// 			$this->dataTable .= "<td>".$dataJumlahDinasLuar->jumlah."</td>";


// 			//////////////////////


// 			$queryJumlahCutiTahunan	=	$this->db->query("
// 			select
// 				count(*) as jumlah
// 			from
// 				data_mentah
// 			where
// 				tanggal >= '".$tglMulai."'
// 				AND tanggal <=  '".$tglSelesai."' and
// 				id_pegawai = '".$dataPegawai->id_pegawai."' and
// 				JAM_KERJA in ('CT') and
// 				tanggal not in (
// 					select
// 						tanggal
// 					from
// 						s_hari_libur
// 					where
// 						tanggal >= '".$tglMulai."'
// 						AND tanggal <=  '".$tglSelesai."'
// 				) and
// 				EXTRACT(ISODOW FROM tanggal) not in (6, 7)
// 			");

// 			$dataJumlahCutiTahunan	=	$queryJumlahCutiTahunan->row();

// 			$this->dataTable .= "<td>".$dataJumlahCutiTahunan->jumlah."</td>";


// 			///////


// 			$skorTPP = 100 - (1400 - $skorTotal);

// 			$this->dataTable .= "<td>".$skorTPP."</td>";
// 			$this->dataTable .= "</tr>";

// 			$i++;
// 		}

// 		$this->load->view('cetak/lap_skor_view');
// 	}

}
