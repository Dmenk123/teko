<?php

/**
select untuk sabtu minggu
SELECT * 
  FROM data_mentah
 WHERE EXTRACT(ISODOW FROM tanggal) IN (6, 7)
 
**/

$conn_string = "host=172.18.1.219 port=5432 dbname=garbissby user=garbisbeta password=garbis@2018";
$connection = pg_connect($conn_string);
if (!$connection) {
	print("Connection Failed");
	exit;
}
else{
	
	
	
		
	function fungsiPenentuanFingerMasuk($idPegawaiUntukFungsi,$scanMulaiMasuk,$scanAkhirMasuk,$tglMasukUntukFungsi){
		//var_dump($tglMasukUntukFungsi);
		
		$conn_string = "host=172.18.1.219 port=5432 dbname=garbissby user=garbisbeta password=garbis@2018";
		$connection = pg_connect($conn_string);
		
		$queryMasuk	=	" 
			select 
				to_char(tanggal,'yyyy-mm-dd HH24:MI') as tanggal
			from 
				absensi_log 
			where  
				tanggal >= '".$scanMulaiMasuk."'
				AND tanggal <=  '".$scanAkhirMasuk."' and 
				absensi_log.badgenumber in (SELECT user_id from mesin_user where id_pegawai = '".$idPegawaiUntukFungsi."')
			order by 
				absensi_log.tanggal asc";
		$resultMasuk 	= 	pg_query($connection, $queryMasuk);
		$rowMasuk 		= pg_fetch_assoc($resultMasuk);
		//var_dump($rowMasuk );
		//var_dump($rowMasuk );
		if($rowMasuk){
			$returnArray['masuk'] = $rowMasuk['tanggal'];
		}
		else{
			$returnArray['masuk'] = null;
		}
		
		$tglMasukNormal 	= 	strtotime($tglMasukUntukFungsi);	
		$waktuMasuk 		= 	strtotime($rowMasuk['tanggal']); 
		
		if ($waktuMasuk > $tglMasukNormal){ 
			if($tglMasukUntukFungsi==''){					
				$returnArray['telat'] = "0";
			}
			else{
				$menitTelat = round(abs($waktuMasuk - $tglMasukNormal) / 60,2);			
				$returnArray['telat'] = $menitTelat;	
			}
		}
		else{
			$returnArray['telat'] = "0";
		}
		
		return $returnArray;
		
		
		
	}
	function fungsiPenentuanFingerPulang($idPegawaiUntukFungsi,$scanMulaiPulang,$scanAkhirPulang,$tglPulangUntukFungsi){
		
		$conn_string = "host=172.18.1.219 port=5432 dbname=garbissby user=garbisbeta password=garbis@2018";
		$connection = pg_connect($conn_string);
		
		$queryPulang	=	" 
			select 
				to_char(tanggal,'yyyy-mm-dd HH24:MI') as tanggal
			from 
				absensi_log 
			where  
				tanggal >= '".$scanMulaiPulang."'
				AND tanggal <=  '".$scanAkhirPulang."' and 
				absensi_log.badgenumber in (SELECT user_id from mesin_user where id_pegawai = '".$idPegawaiUntukFungsi."')
			order by 
				absensi_log.tanggal desc";
		$resultPulang 	= 	pg_query($connection, $queryPulang);
		$rowPulang 		= pg_fetch_assoc($resultPulang);
		
		if($rowPulang){
			$returnArray['pulang'] = $rowPulang['tanggal'];
		}
		else{
			$returnArray['pulang'] = null;
		}
		
		$tglPulangNormal 	= 	strtotime($tglPulangUntukFungsi);	
		$waktuPulang 		= 	strtotime($rowPulang['tanggal']); 
		
		if ($waktuPulang > $tglPulangNormal){ 
			
			$menitLembur = round(abs($tglPulangNormal - $waktuPulang) / 60,2);			
			$returnArray['lembur'] = $menitLembur;				
			$returnArray['cepatPulang'] = "0";
			
		}
		elseif ($waktuPulang < $tglPulangNormal){
			
			
			$menitPulangCepat = round(abs($waktuPulang - $tglPulangNormal) / 60,2);		
			
			$returnArray['cepatPulang'] = $menitPulangCepat;			
			$returnArray['lembur'] = "0";
		}
		else{
			$returnArray['cepatPulang'] = "0";
			$returnArray['lembur'] = "0";
		}
		
		return $returnArray;
	}
	
	
	//////////////////////////////////////////////////////////////////////////////////////////////////
	//////////////////////////////////////////////////////////////////////////////////////////////////
	
	
	$queryInstansi	=	" 
		select id,nama from m_pegawai where kode_status_pegawai = '1'
	";
	$resultInstansi 	= 	pg_query($connection, $queryInstansi);
	//$rowPegawai 		= 	pg_fetch_row($resultInstansi);
	//var_dump($rowPegawai);
	$i = 1;
	while ($rowPegawai 	= pg_fetch_assoc($resultInstansi)) {
	//var_dump($rowPegawai['id']);
	
		echo $rowPegawai->nama."<br>";
		echo $i."<br>";
		$dayList = array(
			'Sun' => 'Minggu',
			'Mon' => 'Senin',
			'Tue' => 'Selasa',
			'Wed' => 'Rabu',
			'Thu' => 'Kamis',
			'Fri' => 'Jumat',
			'Sat' => 'Sabtu'
		);
		
		$start_date = '2018-09-01';
		$end_date 	= '2018-09-02';
	
		$idPegawai		=	$rowPegawai['id'];
		//$idPegawai	=	'dc7ae9c2-200c-11e7-aa72-000c29766abb';
		//$idPegawai	=	'fbc2ae00-64d2-11e6-8aa3-4b67a158dd72';
		
		$queryJenisPegawai	=	" 
			select 
				kode_status_pegawai		
			from 
				m_pegawai
			where  
				id_pgawai = '".$idPegawai."'
			";
		$resultJenisPegawai 	= 	pg_query($connection, $queryJenisPegawai);
		$rowJenisPegawai		= pg_fetch_assoc($resultJenisPegawai);
		
		/// jika pegawai kontrak pulangnya Beda
		if($rowJenisPegawai['kode_status_pegawai'] == '5'){		
			$jamMasukNormal 	= "07:30";
			$jamPulangNormal 	= "16:00";
		}
		else{
			$jamMasukNormal 	= "07:30";
			$jamPulangNormal 	= "16:00";
		}
		
		
		while (strtotime($start_date) <= strtotime($end_date)) {	
		
			$harusnyaMasuk = "";
			$harusnyaPulang= "";
			$fingerMasuk = "";
			$fingerPulang = "";
		
		
			$queryCekData	=	" 
				select 
					count(id_pegawai) as jumlah
				from 
					data_mentah 
				where  
					tanggal 	= '".$start_date."' and 
					id_pegawai 	= '".$idPegawai."' 
					
			";
			$resultCekData	= 	pg_query($connection, $queryCekData);
			$rowCekData		= 	pg_fetch_assoc($resultCekData);
			
			///// jika belum ada data di data_mentah
			if($rowCekData['jumlah'] < 1){
		
				$menitPulangCepat 			= 	"0";
				$menitTelat 				= 	"0";
				$menitLembur 				= 	"0";
				$kodeMasuk		 			= 	"";
				$keteranganMasuk 			= 	"";
				$kodeTidakMasuk		 		= 	"";
				$keteranganTidakMasuk 		= 	"";	
			

				// Hari Indonesia
				$namaHari = date('D', strtotime($start_date));	
				echo $start_date."<br>";
				echo $dayList[$namaHari]."<br>";
			
				/** 		<!-----------  Start Ijin   -----!>          ***/		
				$queryIjin	=	" 
					select 
						t_ijin_cuti_pegawai.id as id_ijin_cuti_pegawai,
						m_jenis_ijin_cuti.kode,		
						m_jenis_ijin_cuti.nama		
					from 
						t_ijin_cuti_pegawai ,m_jenis_ijin_cuti
					where  
						t_ijin_cuti_pegawai.tgl_mulai  <= '".$start_date."'  and 
						t_ijin_cuti_pegawai.tgl_selesai  >= '".$start_date."'  and 				
						t_ijin_cuti_pegawai.id_jenis_ijin_cuti = m_jenis_ijin_cuti.id and 
						t_ijin_cuti_pegawai.id_pegawai = '".$idPegawai."' and 
						t_ijin_cuti_pegawai.status= '1'
					";
				$resultIjin 	= 	pg_query($connection, $queryIjin);
				$rowIjin		= pg_fetch_assoc($resultIjin);		
				/** 		<!-----------  End Ijin -----!>          ***/
			
				/** 		<!-----------  Start Cek Roster -----!>          ***/			
				$querySatu	=	" 
				select 
					t_roster.id as id_t_roster,
					m_jenis_roster.id as id_jenis_roster,
					m_jenis_roster.kode,
					m_jam_kerja.id as id_jam_kerja,
					
					to_char(m_jam_kerja.jam_mulai_scan_masuk,'HH24:MI') as jam_mulai_scan_masuk ,
					to_char(m_jam_kerja.jam_akhir_scan_masuk,'HH24:MI') as jam_akhir_scan_masuk ,
					to_char(m_jam_kerja.jam_masuk,'HH24:MI') as jam_masuk ,
					
					to_char(m_jam_kerja.jam_mulai_scan_pulang,'HH24:MI') as jam_mulai_scan_pulang ,			
					to_char(m_jam_kerja.jam_akhir_scan_pulang,'HH24:MI') as jam_akhir_scan_pulang ,
					to_char(m_jam_kerja.jam_pulang,'HH24:MI') as jam_pulang ,
					
					m_jam_kerja.pulang_hari_berikutnya,
					m_jam_kerja.masuk_hari_sebelumnya
				from 
					t_roster,  m_jenis_roster, m_jam_kerja
				where  
					to_char( t_roster.tanggal,'yyyy-mm-dd') = '".$start_date."' and  
					t_roster.id_pegawai = '".$idPegawai."' and 
					t_roster.id_jenis_roster = m_jenis_roster.id and 
					m_jenis_roster.id_jam_kerja = m_jam_kerja.id 
				";
				$result 	= 	pg_query($connection, $querySatu);
				$rowRoster	= 	pg_fetch_assoc($result);
				/** 		<!-----------  End Cek Roster -----!>          ***/			
					
				/** 		<!-----------  Start Cek hari libur -----!>          ***/		
				$queryHariLibur	=	" 
				select 
					s_hari_libur.id,
					m_hari_libur.id as id_hari_libur,
					m_hari_libur.nama				
				from 
					s_hari_libur ,m_hari_libur
				where  
					s_hari_libur.tanggal = '".$start_date."'  and
					s_hari_libur.id_libur = m_hari_libur.id
				";
				$resultHariLibur 	= 	pg_query($connection, $queryHariLibur);
				$rowHariLibur		= 	pg_fetch_assoc($resultHariLibur);		
				/** 		<!-----------  End Cek hari libur -----!>          ***/				
						
				/** 		<!-----------  Start Cek lembur  ----!>          ***/	
				$queryLembur	=	" 
				select 
					id,
					to_char(t_lembur_pegawai.jam_lembur_akhir,'HH24:MI') as jam_lembur_akhir ,	
					to_char(t_lembur_pegawai.jam_lembur_awal,'HH24:MI') as jam_lembur_awal	
				from 
					t_lembur_pegawai
				where  
					to_char(t_lembur_pegawai.tgl_lembur  ,'yyyy-mm-dd')  = '".$start_date."'  and
					t_lembur_pegawai.id_pegawai = '".$idPegawai."' and 
					t_lembur_pegawai.status = '1'
				";
				$resultLembur 	= 	pg_query($connection, $queryLembur);
				$rowLembur		= 	pg_fetch_assoc($resultLembur); 			
				/** 		<!-----------  End Cek lembur  ----!>          ***/
							
				/** 		<!-----------  Start Finger  ----!>          ***/	
				$tanggalBesok = date ("Y-m-d", strtotime("+1 days", strtotime($start_date)));
				$queryFinger	=	" 
					select 
						tanggal
					from 
						absensi_log 
					where  
						tanggal >= '".$start_date."'
						AND tanggal <=  '".$tanggalBesok."' and 
						absensi_log.badgenumber in (SELECT user_id from mesin_user where id_pegawai = '".$idPegawai."')";
				$resultFinger 	= 	pg_query($connection, $queryFinger);
				$rowAdaFinger 		= pg_fetch_assoc($resultFinger);		
				/** 		<!-----------  End Finger  ----!>          ***/			
						
				
				///////////// jika Ijin ... langsung Exit
				if($rowIjin){
					$menitPulangCepat 			= 	"0";			
					$fingerMasuk				=	"";
					$fingerPulang				=	"";
					$menitTelat 				= 	"0";
					$menitLembur 				= 	"0";
					$menitLemburDiakui 			= 	"0";
					$kodeMasuk 					= 	"*";
					$keteranganMasuk 			= 	"";
					$kodeTidakMasuk 			= 	$rowIjin['kode'];
					$keteranganTidakMasuk 		= 	$rowIjin['nama'];
					$jamKerja 					= 	$rowIjin['kode'];
				}
				else{
					/// jika ada Roster
					if($rowRoster){
						
						if($rowRoster['kode']=='LB'){
							$menitPulangCepat 			= 	"0";
							$fingerMasuk				=	"";
							$fingerPulang				=	"";
							$menitTelat 				= 	"0";
							$menitLembur 				= 	"0";
							$menitLemburDiakui 			= 	"0";
							$kodeMasuk 					= 	"*";
							$keteranganMasuk 			= 	"";
							$kodeTidakMasuk 			= 	"LB";
							$keteranganTidakMasuk 		= 	"LIBUR ROSTER";
							$jamKerja 					= 	"LIBUR ROSTER";
						}
						else{
							//echo "asdasd";
							if($rowRoster['masuk_hari_sebelumnya']=='t'){				
								$harusnyaMasuk 		= date("Y-m-d", strtotime("-1 days", strtotime($start_date)))." ".$rowRoster['jam_pulang'];
								$scanMulaiMasuk 	= date("Y-m-d", strtotime("-1 days", strtotime($start_date)))." ".$rowRoster['jam_mulai_scan_masuk'];
								$scanAkhirMasuk 	= date("Y-m-d", strtotime("-1 days", strtotime($start_date)))." ".$rowRoster['jam_akhir_scan_masuk'];
							}
							else{
								//echo "asdasd";
								$harusnyaMasuk		= $start_date." ".$rowRoster['jam_masuk'];
								$scanMulaiMasuk 	= $start_date." ".$rowRoster['jam_mulai_scan_masuk'];
								$scanAkhirMasuk 	= $start_date." ".$rowRoster['jam_akhir_scan_masuk'];
								
							}
						
							if($rowRoster['pulang_hari_berikutnya']=='t'){				
								$harusnyaPulang		= date("Y-m-d", strtotime("+1 days", strtotime($start_date)))." ".$rowRoster['jam_pulang'];
								$scanMulaiPulang 	= date("Y-m-d", strtotime("+1 days", strtotime($start_date)))." ".$rowRoster['jam_mulai_scan_pulang'];
								$scanAkhirPulang 	= date("Y-m-d", strtotime("+1 days", strtotime($start_date)))." ".$rowRoster['jam_akhir_scan_pulang'];
							}
							else{
								$harusnyaPulang		= $start_date." ".$rowRoster['jam_pulang'];
								$scanMulaiPulang 	= $start_date." ".$rowRoster['jam_mulai_scan_pulang'];
								$scanAkhirPulang 	= $start_date." ".$rowRoster['jam_akhir_scan_pulang'];
							}					
								
							
							$jamKerja 					= 	$rowRoster['kode'];
							$fingerMasukArray	= 	fungsiPenentuanFingerMasuk($idPegawai,$scanMulaiMasuk,$scanAkhirMasuk,$harusnyaMasuk);$fingerPulangArray	=	fungsiPenentuanFingerPulang($idPegawai,$scanMulaiPulang,$scanAkhirPulang,$harusnyaPulang );
							
							if(!$fingerMasukArray['masuk'] && !$fingerPulangArray['pulang']){
								
								$fingerMasuk				=	"";
								$fingerPulang				=	"";
								$menitPulangCepat 			= 	"0";
								$menitTelat 				= 	"0";
								$menitLembur 				= 	"0";
								$kodeMasuk 					= 	"M";
								$keteranganMasuk 			= 	"";
								$kodeTidakMasuk 			= 	"M";
								$keteranganTidakMasuk 		= 	"MANGKIR";
								$menitLemburDiakui 			= 	"0";
							}
							else{
								$fingerMasuk		= 	$fingerMasukArray['masuk'];
								$menitTelat			= 	$fingerMasukArray['telat'];
							
								$fingerPulang		= 	$fingerPulangArray['pulang'];
								$menitLembur		= 	$fingerPulangArray['lembur'];
								$menitPulangCepat	= 	$fingerPulangArray['cepatPulang'];
								
								if($menitLembur > 360){
									$menitLemburDiakui = 360;
								}
								else{
									$menitLemburDiakui = $menitLembur;
								}
								
								$kodeMasuk 					= 	"*";
								$keteranganMasuk 			= 	"";
								$kodeTidakMasuk 			= 	"";
								$keteranganTidakMasuk 		= 	"";
							}
							
						}
						
					}
					else{
						
						
						
						$scanMulaiMasuk 	= $start_date." 00:01";
						$scanAkhirMasuk 	= $start_date." 23:59";
						
						$scanMulaiPulang 	= $start_date." 00:01";
						$scanAkhirPulang 	= $start_date." 23:59";	
						
						/// Jika Libur Nasional
						if($rowHariLibur){
							
							$harusnyaMasuk	=	"";
							$harusnyaPulang	= 	"";
							
							$fingerMasuk				=	"";
							$fingerPulang				=	"";
						
							$menitPulangCepat 			= 	"0";
							$menitTelat 				= 	"0";
							$menitLembur 				= 	"0";
							$menitLemburDiakui			= 	"0";
							$kodeMasuk 					= 	"*";
							$keteranganMasuk 			= 	"";
							$kodeTidakMasuk 			= 	"LB";
							$keteranganTidakMasuk 		= 	$rowHariLibur['nama'];
							$jamKerja 					= 	$rowHariLibur['nama'];
						}
						else{
							
							/// jika Sabtu
							if($namaHari=='Sat'){	
							//	var_dump($rowLembur);
								if($rowLembur){
									$menitPulangCepat 			= 	"0";
									$menitTelat 				= 	"0";
									$kodeMasuk 					= 	"*";
									$kodeTidakMasuk 			= 	"";
									$keteranganMasuk 			= 	"LEMBUR SURAT";
									$fingerMasuk				= 	$start_date." ".$rowLembur['jam_lembur_awal'];						
									$fingerPulang				=	$start_date." ".$rowLembur['jam_lembur_akhir'];
									
									$fingerMasukHitungLembur 	= 	strtotime($fingerMasuk);	
									$fingerPulangHitungLembur 	= 	strtotime($fingerPulang); 
									
									$menitLembur				= round(abs($fingerMasukHitungLembur - $fingerPulangHitungLembur) / 60,2);
									$menitLemburDiakui 			= 	$menitLembur;
									
									$jamKerja 					= 	"";
								}
								elseif($rowAdaFinger && !$rowLembur){
									
									
									
									$harusnyaMasuk	=	"";
									$harusnyaPulang	= 	"";		
										
									$menitPulangCepat 			= 	"0";
									$kodeMasuk 					= 	"*";
									$keteranganMasuk 			= 	"LEMBUR SABTU";
									$kodeTidakMasuk 			= 	"";
									$keteranganTidakMasuk 		= 	"";
									
									$fingerMasukArray	= 	fungsiPenentuanFingerMasuk($idPegawai,$scanMulaiMasuk,$scanAkhirMasuk,$harusnyaMasuk);
									$fingerMasuk		= 	$fingerMasukArray['masuk'];
									$menitTelat			= 	$fingerMasukArray['telat'];
							
									$fingerPulangArray	=	fungsiPenentuanFingerPulang($idPegawai,$scanMulaiPulang,$scanAkhirPulang,"LEMBURSABTU" );
									$fingerPulang		= 	$fingerPulangArray['pulang'];
									$menitLembur		= 	$fingerPulangArray['lembur'];							
									$menitPulangCepat	= 	$fingerPulangArray['cepatPulang'];
									
									$fingerMasukHitungLembur 	= 	strtotime($fingerMasuk);	
									$fingerPulangHitungLembur 	= 	strtotime($fingerPulang); 
									
									$jamKerja 					= 	"";
									
									$menitLembur 		= round(abs($fingerMasukHitungLembur - $fingerPulangHitungLembur) / 60,2);	
									
									if($menitLembur > 360){
										$menitLemburDiakui = 360;
									}
									else{
										$menitLemburDiakui = $menitLembur;
									}
									
									
								}
								else{
									
									$fingerMasuk				=	"";
									$fingerPulang				=	"";
									$harusnyaMasuk				=	"";
									$harusnyaPulang				= 	"";
									$menitPulangCepat 			= 	"0";
									$menitTelat 				= 	"0";
									$menitLembur 				= 	"0";													
									$menitLemburDiakui 			= 	"0";	
									$kodeMasuk 					= 	"*";
									$keteranganMasuk 			= 	"";
									$kodeTidakMasuk 			= 	"LB";
									$keteranganTidakMasuk 		= 	"LIBUR SABTU";							
									$jamKerja 					= 	"LIBUR SABTU";	
								}
							}
							
							
							/// Jika Minggu
							elseif($namaHari=='Sun'){					
							
								if($rowLembur){
									$menitPulangCepat 			= 	"0";
									$menitTelat 				= 	"0";
									$kodeMasuk 					= 	"*";
									$kodeTidakMasuk 			= 	"";
									$keteranganMasuk 			= 	"LEMBUR SURAT";
									$fingerMasuk				= 	$start_date." ".$rowLembur['jam_lembur_awal'];						
									$fingerPulang				=	$start_date." ".$rowLembur['jam_lembur_akhir'];
									
									$fingerMasukHitungLembur 	= 	strtotime($fingerMasuk);	
									$fingerPulangHitungLembur 	= 	strtotime($fingerPulang); 
									
									$menitLembur 				= round(abs($fingerMasukHitungLembur - $fingerPulangHitungLembur) / 60,2);$menitLemburDiakui			= 	$menitLembur;
									
									$jamKerja 					= 	"";
								}
								else{
									$harusnyaMasuk	=	"";
									$harusnyaPulang	= 	"";
									
									$fingerMasuk				=	"";
									$fingerPulang				=	"";
									$menitPulangCepat 			= 	"0";
									$menitTelat 				= 	"0";
									$menitLembur 				= 	"0";
									$kodeMasuk 					= 	"*";
									$keteranganMasuk 			= 	"";
									$kodeTidakMasuk 			= 	"LB";
									$keteranganTidakMasuk 		= 	"LIBUR MINGGU";
									$menitLemburDiakui 			= 	"0";
									
									$jamKerja 					= 	"LIBUR MINGGU";
								}
							}
							else{
								//// Jika Hari Jumat Biasa tanpa Roster
								if($namaHari=='Fri'){	
									//echo $namaHari;
									$harusnyaMasuk	=	$start_date." ".$jamMasukNormal;
									$harusnyaPulang	= 	$start_date." 15:00";

									$jamKerja 					= 	$jamMasukNormal." - 15:00";							
								}
								//// Jika Hari Senin - Kamis Biasa tanpa Roster
								else{
									//echo $namaHari;
									$harusnyaMasuk	=	$start_date." ".$jamMasukNormal;
									$harusnyaPulang	= 	$start_date." ".$jamPulangNormal;	
									
									
									$jamKerja 					= 	$jamMasukNormal." - ".$jamPulangNormal;
									
								}
								
								
								
								if($rowLembur){
									$menitPulangCepat 			= 	"0";
									$menitTelat 				= 	"0";
									$kodeMasuk 					= 	"H";
									$kodeTidakMasuk 			= 	"";
									$keteranganMasuk 			= 	"LEMBUR SURAT";
									$fingerMasuk				= 	$start_date." ".$rowLembur['jam_lembur_awal'];						
									$fingerPulang				=	$start_date." ".$rowLembur['jam_lembur_akhir'];
									
									$fingerMasukHitungLembur 	= 	strtotime($fingerMasuk);	
									$fingerPulangHitungLembur 	= 	strtotime($fingerPulang); 
									
									$menitLembur 				= 	round(abs($fingerMasukHitungLembur - $fingerPulangHitungLembur) / 60,2);
									$menitLemburDiakui 			= 	$menitLembur;								
									
									$fingerMasuk				= 	$fingerMasukArray['masuk'];
								}
								else{
									
									$fingerMasukArray	= 	fungsiPenentuanFingerMasuk($idPegawai,$scanMulaiMasuk,$scanAkhirMasuk,$harusnyaMasuk);
									$fingerPulangArray	=	fungsiPenentuanFingerPulang($idPegawai,$scanMulaiPulang,$scanAkhirPulang,$harusnyaPulang );
									
									
									if(!$fingerMasukArray['masuk'] && !$fingerPulangArray['pulang']){
										
										$harusnyaMasuk	=	$start_date." ".$jamMasukNormal;
										$harusnyaPulang	= 	$start_date." ".$jamPulangNormal;	
										
										$fingerMasuk				=	"";
										$fingerPulang				=	"";
										$menitPulangCepat 			= 	"0";
										$menitTelat 				= 	"0";
										$menitLembur 				= 	"0";
										$kodeMasuk 					= 	"M";
										$keteranganMasuk 			= 	"";
										$kodeTidakMasuk 			= 	"M";
										$keteranganTidakMasuk 		= 	"MANGKIR";
										$menitLemburDiakui 			= 	"0";
									}
									else{
										
										$kodeMasuk 					= 	"H";								
										$fingerMasuk		= 	$fingerMasukArray['masuk'];
										$menitTelat			= 	$fingerMasukArray['telat'];							
										
										$fingerPulang		= 	$fingerPulangArray['pulang'];						
										$menitLembur		= 	$fingerPulangArray['lembur'];
										$menitPulangCepat	= 	$fingerPulangArray['cepatPulang'];
										
										if($menitLembur > 180){
											$menitLemburDiakui = 180;
										}
										else{
											$menitLemburDiakui = $menitLembur;
										}
									}
								
									
									
									//var_dump($menitLemburDiakui);
								}
								
								
							}
							
							
							
							
						}			
					}
				}
				
				
				echo "Jam Kerja : ".$jamKerja."<br>";
				echo "<br>";		
				echo "<br>"; 		
				echo "Jadwal Masuk : ".$harusnyaMasuk."<br>";
				echo "Jadwal Pulang : ".$harusnyaPulang."<br>";
				echo "Masuk : ".$fingerMasuk."<br>";
				echo "Pulang : ".$fingerPulang."<br>";		
				echo "<br>";
				echo "Pulang Cepat 		= ".$menitPulangCepat."<br>";
				echo "Datang Telat 		= ".$menitTelat."<br>";
				echo "Lembur 			= ".$menitLembur."<br>";
				echo "Lembur Diakui		= ".$menitLemburDiakui."<br>";
				echo "Kode Masuk : ".$kodeMasuk."<br>";
				echo "Keterangan Masuk : ".$keteranganMasuk."<br>";
				echo "Kode Tidak Masuk : ".$kodeTidakMasuk."<br>";
				echo "Keterangan Tidak Masuk : ".$keteranganTidakMasuk."<br>";	
						
				
				
				if($harusnyaMasuk==''){
					$harusnyaMasuk = 'null';
				}
				else{
					$harusnyaMasuk = "'".$harusnyaMasuk."'";
				}
				
				if($harusnyaPulang==''){
					$harusnyaPulang = 'null';
				}
				else{
					$harusnyaPulang = "'".$harusnyaPulang."'";
				}
				
				if($fingerMasuk==''){
					$fingerMasuk = 'null';
				}
				else{
					$fingerMasuk = "'".$fingerMasuk."'";
				}
				
				if($fingerPulang==''){
					$fingerPulang = 'null';
				}
				else{
					$fingerPulang = "'".$fingerPulang."'";
				}
			
				
				$insertDataMentah	=	" 
					insert into
						data_mentah
						(
							tanggal,
							id_pegawai,
							hari,
							jam_kerja,
							jadwal_masuk,
							jadwal_pulang,
							finger_masuk,
							finger_pulang,
							pulang_cepat,
							datang_telat,
							lembur,
							lembur_diakui,
							kode_masuk,
							keterangan_masuk,
							kode_tidak_masuk,
							keterangan_tidak_masuk				
						)
					values
						(
							'".$start_date."',
							'".$idPegawai."',
							'".$dayList[$namaHari]."',
							'".$jamKerja."',
							$harusnyaMasuk,
							$harusnyaPulang,
							$fingerMasuk,
							$fingerPulang,
							'".$menitPulangCepat."',
							'".$menitTelat."',
							'".$menitLembur."',
							'".$menitLemburDiakui."',
							'".$kodeMasuk."',
							'".$keteranganMasuk."',
							'".$kodeTidakMasuk."',
							'".$keteranganTidakMasuk."'
						)
				";
				$resultDataMentah	= 	pg_query($connection, $insertDataMentah);
				
				if($resultDataMentah){
					echo "<br>";
					echo "Sukses<br>";
				}
				else{
					echo "<br>";
					echo "Gagal Insert<br>";
				}
				echo $insertDataMentah;
				echo "<hr>";
			}
			else{
				echo "<br><br>";
				echo "Sudah Ada";
			}
			
			
			/// looping
			$start_date = date ("Y-m-d", strtotime("+1 days", strtotime($start_date)));
		}
		
		$i++;
	}
}
?>