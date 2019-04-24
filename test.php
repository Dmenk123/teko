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
				and absensi_log.id_mesin in (SELECT id_mesin from mesin_user where id_pegawai = '".$idPegawaiUntukFungsi."')
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
				and absensi_log.id_mesin in (SELECT id_mesin from mesin_user where id_pegawai = '".$idPegawaiUntukFungsi."')
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
		
			$tanggalAjah	=	explode(' ',$scanMulaiPulang);
			$queryLembur	=	" 
			select 
				id,
				to_char(t_lembur_pegawai.jam_lembur_akhir,'HH24:MI') as jam_lembur_akhir ,	
				to_char(t_lembur_pegawai.jam_lembur_akhir,'yyyy-mm-dd HH24:MI') as tanggal_jam_lembur_akhir ,	
				to_char(t_lembur_pegawai.jam_lembur_awal,'HH24:MI') as jam_lembur_awal	
			from 
				t_lembur_pegawai
			where  
				to_char(t_lembur_pegawai.tgl_lembur  ,'yyyy-mm-dd')  = '".$tanggalAjah[0]."'  and
				t_lembur_pegawai.id_pegawai = '".$idPegawaiUntukFungsi."' 
				
			";
			$resultLembur 	= 	pg_query($connection, $queryLembur);
			$rowLembur		= 	pg_fetch_assoc($resultLembur); 	
		
			if($rowLembur){
				
				$tglPulangLemburSurat 	= 	strtotime($rowLembur['tanggal_jam_lembur_akhir']);
				
				$menitLembur = round(abs( $tglPulangNormal - $tglPulangLemburSurat ) / 60,2);		
				$returnArray['lembur'] = $menitLembur;
				
				
				$menitPulangCepat = round(abs($waktuPulang - $tglPulangNormal) / 60,2);					
				$returnArray['cepatPulang'] = "0";		


					echo $menitLembur;
			}
			else{
			
				$menitLembur = round(abs($tglPulangNormal - $waktuPulang) / 60,2);	
							
				$returnArray['cepatPulang'] = "0";
				$returnArray['lembur'] = $menitLembur;	
				
				//echo "gag lembur gag finger";
			}
		
		
			
			
			
			//echo "pulang Lebih";
			
		}
		elseif ($waktuPulang < $tglPulangNormal){

				
		
			if($rowLembur){
				
				$tglPulangLemburSurat 	= 	strtotime($rowLembur['tanggal_jam_lembur_akhir']);
				
				$menitLembur = round(abs( $tglPulangNormal - $tglPulangLemburSurat ) / 60,2);		
				$returnArray['lembur'] = $menitLembur;
				
				
				$menitPulangCepat = round(abs($waktuPulang - $tglPulangNormal) / 60,2);					
				$returnArray['cepatPulang'] = $menitPulangCepat;		


				//	echo "lembuuur";
			}
			else{
			
				$menitPulangCepat = round(abs($waktuPulang - $tglPulangNormal) / 60,2);		
				
				$returnArray['cepatPulang'] = $menitPulangCepat;			
				$returnArray['lembur'] = "0";
				
				//echo "gag lembur gag finger";
			}
			
			//echo "pulang Kecil";
		}
		else{
			$returnArray['cepatPulang'] = "0";
			$returnArray['lembur'] = "0";
			
			//echo "pulang Else";
		}
		echo "pulang waktu";
		return $returnArray;
	}
	
	
	//////////////////////////////////////////////////////////////////////////////////////////////////
	//////////////////////////////////////////////////////////////////////////////////////////////////
	
	
	//$queryInstansi	=	" 
	//	select id,nama from m_pegawai where kode_status_pegawai = '1' and id = 'facb1ee2-64d2-11e6-9754-8bf6a6025cb0' ;
	//";
	

$queryInstansi	=	" 
							select
			m.id as id,m.nip,m.nama, pukh.kode_unor as unor ,mjb.nama as nama_unor,m.aktif
		from m_pegawai m,m_jenis_jabatan mjb
			LEFT JOIN LATERAL (
				SELECT kode_unor
				FROM m_pegawai_unit_kerja_histori h
				WHERE tgl_mulai <= '2018-10-01' and m.id = h.id_pegawai
				ORDER BY tgl_mulai DESC
				LIMIT 1
			) pukh ON true
		where pukh.kode_unor like '3.02%'
		and mjb.kode=m.kode_jenis_jabatan  and m.kode_status_pegawai = '1' 
		order by mjb.urut
	";
	
	$resultInstansi 	= 	pg_query($connection, $queryInstansi);
	
	$i = 1;
	while ($rowPegawai 	= pg_fetch_assoc($resultInstansi)) {
	
		echo $rowPegawai[nama]."<br>";
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
		
		$start_date = '2018-01-01';
		$end_date 	= '2018-10-15';
	
		$idPegawai		=	$rowPegawai['id'];

		
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
				$namaHari 	= date('D', strtotime($start_date));	
				$urutanHari = date('N', strtotime($start_date));	
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
						t_ijin_cuti_pegawai.id_pegawai = '".$idPegawai."' 
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
					t_lembur_pegawai.id_pegawai = '".$idPegawai."' 
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
							
							//$fingerMasuk				=	"";
							//$fingerPulang				=	"";
							
							$fingerMasukArray	= 	fungsiPenentuanFingerMasuk($idPegawai,$scanMulaiMasuk,$scanAkhirMasuk,$harusnyaMasuk);
							$fingerMasuk		= 	$fingerMasukArray['masuk'];
					
							$fingerPulangArray	=	fungsiPenentuanFingerPulang($idPegawai,$scanMulaiPulang,$scanAkhirPulang,"LEMBUR SABTU" );
							$fingerPulang		= 	$fingerPulangArray['pulang'];
						
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
									$keteranganMasuk 			= 	"LEMBUR SURAT SABTU";
									
									$fingerMasukArray	= 	fungsiPenentuanFingerMasuk($idPegawai,$scanMulaiMasuk,$scanAkhirMasuk,$harusnyaMasuk);
									$fingerMasuk		= 	$fingerMasukArray['masuk'];
									$menitTelat			= 	$fingerMasukArray['telat'];
							
									$fingerPulangArray	=	fungsiPenentuanFingerPulang($idPegawai,$scanMulaiPulang,$scanAkhirPulang,"LEMBUR SABTU" );
									$fingerPulang		= 	$fingerPulangArray['pulang'];
									$menitLembur		= 	$fingerPulangArray['lembur'];							
									$menitPulangCepat	= 	$fingerPulangArray['cepatPulang'];
									
									$fingerMasukHitungLembur 	= 	strtotime($fingerMasuk);	
									$fingerPulangHitungLembur 	= 	strtotime($fingerPulang); 
									
									$jamKerja 					= 	"";
									
									$menitLembur 		= round(abs($fingerMasukHitungLembur - $fingerPulangHitungLembur) / 60,2);	
									$menitLemburDiakui = $menitLembur;
									
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
							
									$fingerPulangArray	=	fungsiPenentuanFingerPulang($idPegawai,$scanMulaiPulang,$scanAkhirPulang,"LEMBUR SABTU" );
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
									
									
								//echo "werwerw Lembuar<br><br>";
									
									$menitPulangCepat 			= 	"0";
									$menitTelat 				= 	"0";
									$kodeMasuk 					= 	"*";
									$kodeTidakMasuk 			= 	"";
									$keteranganMasuk 			= 	"LEMBUR SURAT MINGGU";
									
									$fingerMasukArray	= 	fungsiPenentuanFingerMasuk($idPegawai,$scanMulaiMasuk,$scanAkhirMasuk,"LEMBUR MINGGU");
									$fingerMasuk		= 	$fingerMasukArray['masuk'];
							
									$fingerPulangArray	=	fungsiPenentuanFingerPulang($idPegawai,$scanMulaiPulang,$scanAkhirPulang,"LEMBUR SABTU" );
									$fingerPulang		= 	$fingerPulangArray['pulang'];
									
									$fingerMasukHitungLembur 	= 	strtotime($fingerMasuk);	
									$fingerPulangHitungLembur 	= 	strtotime($fingerPulang); 
									
									
									
									$menitLembur 		= round(abs($fingerMasukHitungLembur - $fingerPulangHitungLembur) / 60,2);	
									$menitLemburDiakui = $menitLembur;
									
									$jamKerja 					= 	"";
									
									
								}
								else{
									
								//echo "werwerw Tidak Lembur<br><br>";	
									
									$harusnyaMasuk	=	"";
									$harusnyaPulang	= 	"";
									
									$fingerMasukArray	= 	fungsiPenentuanFingerMasuk($idPegawai,$scanMulaiMasuk,$scanAkhirMasuk,$harusnyaMasuk);
									$fingerMasuk		= 	$fingerMasukArray['masuk'];
							
									$fingerPulangArray	=	fungsiPenentuanFingerPulang($idPegawai,$scanMulaiPulang,$scanAkhirPulang,"LEMBUR SABTU" );
									$fingerPulang		= 	$fingerPulangArray['pulang'];
									
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
								
								
								
								$queryRoleJamKerja	=	" 
								select  
									m_pegawai.nama as nama_peg,
									m_role_jam_kerja.nama ,
									m_pegawai_role_jam_kerja_histori.tgl_mulai,
									m_role_jam_kerja.id as id_role_jam_kerja,
									m_role_jam_kerja_detail.id_hari,
									to_char(m_jam_kerja.jam_masuk,'HH24:MI')  as jam_masuk,
									to_char(m_jam_kerja.jam_pulang,'HH24:MI')  as jam_pulang,
									to_char(m_jam_kerja.jam_mulai_scan_masuk,'HH24:MI')  as jam_mulai_scan_masuk,
									to_char(m_jam_kerja.jam_mulai_scan_pulang,'HH24:MI')  as jam_mulai_scan_pulang,
									to_char(m_jam_kerja.jam_akhir_scan_masuk,'HH24:MI')  as jam_akhir_scan_masuk,
									to_char(m_jam_kerja.jam_akhir_scan_pulang,'HH24:MI')  as jam_akhir_scan_pulang
								from 
									m_pegawai_role_jam_kerja_histori ,m_pegawai, m_role_jam_kerja, m_role_jam_kerja_detail,m_jam_kerja
								where 
									m_pegawai_role_jam_kerja_histori.id_pegawai =  m_pegawai.id and 
									m_pegawai_role_jam_kerja_histori.id_role_jam_kerja=m_role_jam_kerja.id and 
									m_role_jam_kerja_detail.id_role = m_role_jam_kerja.id and 
									m_jam_kerja.id = m_role_jam_kerja_detail.id_jam_kerja and 
									m_pegawai.id='".$idPegawai."' and 
									m_role_jam_kerja_detail.id_hari = '".$urutanHari."' and 
									m_pegawai_role_jam_kerja_histori.tgl_mulai < '".$start_date."' 
								order by 
									m_pegawai_role_jam_kerja_histori.tgl_mulai desc 
								limit 1
								";
								$resultRoleJamKerja 	= 	pg_query($connection, $queryRoleJamKerja);
								$rowRoleJamKerja		= 	pg_fetch_assoc($resultRoleJamKerja);
												
								//var_dump($rowRoleJamKerja);				
												
								//// Jika Hari Jumat Biasa tanpa Roster
									//echo $namaHari;
									
								$harusnyaMasuk	=	$start_date." ".$rowRoleJamKerja['jam_masuk'];
								$harusnyaPulang	= 	$start_date." ".$rowRoleJamKerja['jam_pulang'];

								$jamKerja 					= 	$rowRoleJamKerja['jam_masuk']." - ".$rowRoleJamKerja['jam_pulang'];							
							
								
								
								
								if($rowLembur){
									$fingerMasukArray	= 	fungsiPenentuanFingerMasuk($idPegawai,$scanMulaiMasuk,$scanAkhirMasuk,$harusnyaMasuk);
									$fingerPulangArray	=	fungsiPenentuanFingerPulang($idPegawai,$scanMulaiPulang,$scanAkhirPulang,$harusnyaPulang );
									
									
									if(!$fingerMasukArray['masuk'] && !$fingerPulangArray['pulang']){
										
										$harusnyaMasuk	=	$start_date." ".$rowRoleJamKerja['jam_masuk'];
										$harusnyaPulang	= 	$start_date." ".$rowRoleJamKerja['jam_pulang'];
										
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
										
										$kodeMasuk 			= 	"H";								
										$fingerMasuk		= 	$fingerMasukArray['masuk'];
										$menitTelat			= 	$fingerMasukArray['telat'];							
										
										$fingerPulang		= 	$fingerPulangArray['pulang'];						
										$menitLembur		= 	$fingerPulangArray['lembur'];
										$menitPulangCepat	= 	$fingerPulangArray['cepatPulang'];
										
										
										$menitLemburDiakui 	= $menitLembur;
										
									}
									
									
								}
								else{
									
									$fingerMasukArray	= 	fungsiPenentuanFingerMasuk($idPegawai,$scanMulaiMasuk,$scanAkhirMasuk,$harusnyaMasuk);
									$fingerPulangArray	=	fungsiPenentuanFingerPulang($idPegawai,$scanMulaiPulang,$scanAkhirPulang,$harusnyaPulang );
									
									
									if(!$fingerMasukArray['masuk'] && !$fingerPulangArray['pulang']){
										
										$harusnyaMasuk	=	$start_date." ".$rowRoleJamKerja['jam_masuk'];
										$harusnyaPulang	= 	$start_date." ".$rowRoleJamKerja['jam_pulang'];
										
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