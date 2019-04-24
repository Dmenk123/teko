<?php

$conn_string = "host=172.18.1.219 port=5432 dbname=garbisbeta user=garbisbeta password=garbis@2018";
$connection = pg_connect($conn_string);
if (!$connection) {
	print("Connection Failed");
	exit;
}
else{
	
	$dayList = array(
		'Sun' => 'Minggu',
		'Mon' => 'Senin',
		'Tue' => 'Selasa',
		'Wed' => 'Rabu',
		'Thu' => 'Kamis',
		'Fri' => 'Jumat',
		'Sat' => 'Sabtu'
	);
	
	$start_date = '2018-05-01';
	$end_date 	= '2018-06-03';
	
	// adi $idPegawai	=	'dc7ae9c2-200c-11e7-aa72-000c29766abb';
	$idPegawai	=	'fbc2ae00-64d2-11e6-8aa3-4b67a158dd72';
	
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
		$jamMasukNormal 	= "07:30:00";
		$jamPulangNormal 	= "16:00:00";
	}
	else{
		$jamMasukNormal 	= "07:30:00";
		$jamPulangNormal 	= "16:00:00";
	}
	
	
	
	
	
	while (strtotime($start_date) <= strtotime($end_date)) {	
	
		$menitPulangCepat 		= 	"0";
		$menitTelat 			= 	"0";
		$menitLembur 			= 	"0";
		$keteranganTidakMasuk 	= 	"";	
		$masukRoster 			= 	"";	
		$scanMulaiMasuk			=	true;
	

		// Hari Indonesia
		$namaHari = date('D', strtotime($start_date));	
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
		
		//echo $rowIjin['id_ijin_cuti_pegawai'];
		
		/** 		<!-----------  Start Ijin -----!>          ***/
		if($rowIjin['id_ijin_cuti_pegawai']!=''){
			$scanMulaiMasuk			= 	false;
			$tanggalMasuk			=	"";
			$tanggalPulang			=	"";
			$menitPulangCepat 		= 	"0";
			$menitTelat 			= 	"0";
			$menitLembur 			= 	"0";
			$keteranganTidakMasuk 	= 	$rowIjin['nama'];	
			
			//echo "asdasdasd";
			
			//echo $keteranganTidakMasuk;
		}
		else{
		
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
			$row 		= 	pg_fetch_assoc($result);
			
			
			//////////// jika ada Roster
			
			if($row){
				
				// jika Rosternya adalah Libur
				if($row['kode']=='LB'){
					$scanMulaiMasuk			=	false;
					$tanggalMasuk			=	"";
					$tanggalPulang			=	"";
					$menitPulangCepat 		= 	"0";
					$menitTelat 			= 	"0";
					$menitLembur 			= 	"0";
					$keteranganTidakMasuk 	= 	"LIBUR ROSTER";	
				}
				else{
					if($row['masuk_hari_sebelumnya']=='t'){				
						$masuk 		= date("Y-m-d", strtotime("-1 days", strtotime($start_date)))." ".$row['jam_pulang'];
						$masukRoster 		= date("Y-m-d", strtotime("-1 days", strtotime($start_date)))." ".$row['jam_pulang'];
						$scanMulaiMasuk 	= date("Y-m-d", strtotime("-1 days", strtotime($start_date)))." ".$row['jam_mulai_scan_masuk'];
						$scanAkhirMasuk 	= date("Y-m-d", strtotime("-1 days", strtotime($start_date)))." ".$row['jam_akhir_scan_masuk'];
					}
					else{
						$masuk 			= $start_date." ".$row['jam_masuk'];
						$masukRoster 			= $start_date." ".$row['jam_masuk'];
						$scanMulaiMasuk 	= $start_date." ".$row['jam_mulai_scan_masuk'];
						$scanAkhirMasuk 	= $start_date." ".$row['jam_akhir_scan_masuk'];
						
					}
				
					if($row['pulang_hari_berikutnya']=='t'){				
						$pulang 		= date("Y-m-d", strtotime("+1 days", strtotime($start_date)))." ".$row['jam_pulang'];
						$pulangRoster 	= date("Y-m-d", strtotime("+1 days", strtotime($start_date)))." ".$row['jam_pulang'];
						$scanMulaiPulang 	= date("Y-m-d", strtotime("+1 days", strtotime($start_date)))." ".$row['jam_mulai_scan_pulang'];
						$scanAkhirPulang 	= date("Y-m-d", strtotime("+1 days", strtotime($start_date)))." ".$row['jam_akhir_scan_pulang'];
					}
					else{
						$pulang 	= $start_date." ".$row['jam_pulang'];
						$pulangRoster 	= $start_date." ".$row['jam_pulang'];
						$scanMulaiPulang = $start_date." ".$row['jam_mulai_scan_pulang'];
						$scanAkhirPulang = $start_date." ".$row['jam_akhir_scan_pulang'];
					}			
					
					
					$keteranganTidakMasuk 	= 	"";	
				}
				
			}
			else{	

				$masuk 		= $start_date." 07:30";
				$pulang 	= $start_date." 16:00";
			
				/// cek hari libur
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
				
				
				///// jika hari libur
				if($rowHariLibur[id]!=''){
					
					$queryLembur	=	" 
					select 
						id,
						to_char(t_lembur_pegawai.jam_lembur_akhir,'HH24:MI:SS') as jam_lembur_akhir ,	
						to_char(t_lembur_pegawai.jam_lembur_awal,'HH24:MI:SS') as jam_lembur_awal	
					from 
						t_lembur_pegawai
					where  
						to_char(t_lembur_pegawai.tgl_lembur  ,'yyyy-mm-dd')  = '".$start_date."'  and
						t_lembur_pegawai.id_pegawai = '".$idPegawai."'
					";
					$resultLembur 	= 	pg_query($connection, $queryLembur);
					$rowLembur		= 	pg_fetch_assoc($resultLembur); 		
					
					//// jika Hari libur dan tidak ada Lembur .. maka jika tidak ada finger dianggap Mangkir
					if($rowLembur[id]==''){					
						$scanMulaiMasuk		=	false;
						$keteranganTidakMasuk 	= 	$rowHariLibur[nama];	
					}
					
					//// jika Hari libur dan ada Lembur .. maka jika tidak ada finger dianggap Mangkir
					else{
						$scanMulaiMasuk 	= $start_date." 00:01" ;
						$scanAkhirMasuk 	= $start_date." 23:59";
					
						$scanMulaiPulang 	= $start_date." 00:01";
						$scanAkhirPulang 	= $start_date." 23:59";						
					
						$keteranganTidakMasuk 	= 	"";	
					}
					
				}
				else{
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
				
					/// jika sabtu minggu
					if($namaHari=='Sat' || $namaHari=='Sun'){
						if($rowAdaFinger[tanggal]!=''){		
							$scanMulaiMasuk 	= $start_date." 00:01";
							$scanAkhirMasuk 	= $start_date." 23:59";
						
							$scanMulaiPulang 	= $start_date." 00:01";
							$scanAkhirPulang 	= $start_date." 23:59";						
						
							$keteranganTidakMasuk 	= 	"";	
						}		
						else{							
							$scanMulaiMasuk		=	false;				
						}
					}
					else{
						//var_dump($rowAdaFinger[jumlah]);
						
						
						
						
						//var_dump($rowAdaFinger[tanggal]);
						
						if($rowAdaFinger[tanggal]==''){						
							$scanMulaiMasuk			=	false;
							$keteranganTidakMasuk 	= 	"M";
						}
						else{
							$scanMulaiMasuk 	= $start_date." 00:01";
							$scanAkhirMasuk 	= $start_date." 23:59";
						
							$scanMulaiPulang 	= $start_date." 00:01";
							$scanAkhirPulang 	= $start_date." 23:59";						
						
							$keteranganTidakMasuk 	= 	"";	
						}
						
						
					}
					
				}		
				
			}
			
			
			//var_dump($masukRoster);
			/** 		<!-----------  		end Roster 		 -----!>          ***/		 
			// var_dump($scanMulaiMasuk);
			 
			if(!$scanMulaiMasuk){
				$tanggalMasuk			=	"";
				$tanggalPulang			=	"";
				$menitPulangCepat 		= 	"0";
				$menitTelat 			= 	"0";
				$menitLembur 			= 	"0";
				if($namaHari=='Sat' || $namaHari=='Sun'){					
					$keteranganTidakMasuk 	= 	"Libur ".$dayList[$namaHari];	
				}
				else{		
					if($keteranganTidakMasuk==''){
						$keteranganTidakMasuk 	= 	"M";
					}
				}
			}
			else{
				/** 		<!-----------  start Penentuan Absen -----!>          ***/
			
				$queryMasuk	=	" 
					select 
						to_char(tanggal,'yyyy-mm-dd HH24:MI') as tanggal
					from 
						absensi_log 
					where  
						tanggal >= '".$scanMulaiMasuk."'
						AND tanggal <=  '".$scanAkhirMasuk."' and 
						absensi_log.badgenumber in (SELECT user_id from mesin_user where id_pegawai = '".$idPegawai."')
					order by 
						absensi_log.tanggal asc";
				$resultMasuk 	= 	pg_query($connection, $queryMasuk);
				$rowMasuk 		= pg_fetch_assoc($resultMasuk);
				
				$queryPulang =	" 
					select 
						to_char(tanggal,'yyyy-mm-dd HH24:MI') as tanggal
					from 
						absensi_log 
					where  
						tanggal >= '".$scanMulaiPulang."'
						AND tanggal <=  '".$scanAkhirPulang."' and
						absensi_log.badgenumber in (SELECT user_id from mesin_user where id_pegawai = '".$idPegawai."')
					order by 
						absensi_log.tanggal desc";
				$resultPulang  	= 	pg_query($connection, $queryPulang );
				$rowPulang 		= pg_fetch_assoc($resultPulang );
						
				/** 		<!-----------  End Penentuan Absen 	-----!>          ***/		
				
				
				
				
				/** 		<!-----------  		Start Lembur 	-----!>          ***/
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
				
				/** 		<!-----------  		End Lembur 	-----!>          ***/
				
				//// jika ada Lembur Surat
				if($rowLembur['id']!=''){
					$tglMulaiLembur 	= $start_date." ".$rowLembur['jam_lembur_awal'];
					$tanggalPulang 		= $start_date." ".$rowLembur['jam_lembur_akhir'];
				
					if($keteranganLibur ==''){
						$to_time 			= strtotime($rowLembur['jam_lembur_awal']);
						$from_time 			= strtotime($rowLembur['jam_lembur_akhir']);
						$menitLembur 		= round(abs($to_time - $from_time) / 60,2);
					}			
					else{
						$menitLembur 		= "0";
					}
					
					
					if($rowMasuk['tanggal']!=''){
						$tanggalMasuk = $rowMasuk['tanggal'];
					}
					else{
						$tanggalMasuk = $start_date." ".$rowLembur['jam_lembur_awal'];				
					}
					
					$menitPulangCepat 		= 	"0";
					$menitTelat 			= 	"0";
					
				}
				else{					
					
					$tanggalMasuk 	= $rowMasuk['tanggal'];
					$tanggalPulang	= $rowPulang['tanggal'];			
					
					$waktuPulang 				= strtotime($tanggalPulang); 
					if($masukRoster!=''){
						
						$tglPulangNormal	= $pulangRoster;		
						$tglMasukNormal		= $masukRoster;	
						
					}
					else{
						$tglPulangNormal	= $start_date." ".$jamPulangNormal;		
						$tglMasukNormal		= $start_date." ".$jamMasukNormal;		
					}
										
					
					$tglPulangNormalCepat 		= strtotime($tglPulangNormal); 
					$tglMasukNormalCepat 		= strtotime($tglMasukNormal);
					
					if ($waktuPulang < $tglPulangNormalCepat){ 
						$menitLembur		=	"0";
						$menitPulangCepat 	= 	round(abs($tglPulangNormalCepat - $waktuPulang) / 60,2);
					}
					else{
						if($keteranganLibur !=''){
							$menitLembur	=	"0";
						}
						else{								
							$menitLembur 	= round(abs($waktuPulang - $tglPulangNormalCepat) / 60,2);
						}
						
					}		
					
					
					$waktuMasuk = strtotime($tanggalMasuk); 
					
				//	var_dump($waktuMasuk);
					//var_dump($tglMasukNormalCepat);
					
					if ($waktuMasuk > $tglMasukNormalCepat){ 
						$menitTelat = round(abs($waktuMasuk - $tglMasukNormalCepat) / 60,2);
					}
					else{
						$menitTelat = "0";
					}
				}
				
			}			
		}
		
		
		
		
		echo "<b>".$start_date."</b><br>";
		echo $masuk."<br>";
		echo $pulang."<br><br>";
		
		
		if($tanggalMasuk){
			echo 	"Tanggal Finger Masuk : ".$tanggalMasuk."<br>" ;
			echo 	"Tanggal Finger Pulang : ".$tanggalPulang."<br><br>" ;
		}
		
		
		
		echo 	"Pulang Cepat (Menit) = ".$menitPulangCepat."<br>" ;
		echo	"Telat (Menit) = ".$menitTelat."<br>" ;
		echo	"Lembur (Menit) = ".$menitLembur."<br>" ;
		echo	"Keterangan Tidak Masuk = ".$keteranganTidakMasuk."<br>" ;
		
		echo "<hr>";
		
		
		
		
		
		
		
		/// looping
		$start_date = date ("Y-m-d", strtotime("+1 days", strtotime($start_date)));
	}

}


?>