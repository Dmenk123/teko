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
	$end_date 	= '2018-05-31';
	
	
	$jamMasukNormal 	= "07:30";
	$jamPulangNormal 	= "16:00";
	
	$keteranganLibur 	= "";
	$menitPulangCepat 	= false;
	$adaLembur 			= false;
	
	while (strtotime($start_date) <= strtotime($end_date)) {	

		// Hari Indonesia
		$namaHari = date('D', strtotime($start_date));	
	
		/** 		<!-----------  Start Cek Roster -----!>          ***/
		$querySatu	=	" 
		select 
			t_roster.id as id_t_roster,
			m_jenis_roster.id as id_jenis_roster,
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
			t_roster.id_pegawai = 'dc7ae9c2-200c-11e7-aa72-000c29766abb' and 
			t_roster.id_jenis_roster = m_jenis_roster.id and 
			m_jenis_roster.id_jam_kerja = m_jam_kerja.id and 
			m_jam_kerja.jam_masuk is not null and 
			m_jam_kerja.jam_pulang is not null 
		";
		$result 	= 	pg_query($connection, $querySatu);
		if (!$result) {
			echo "query cek Roster Salah";
			echo $querySatu;
			exit;
		}
		$row = pg_fetch_assoc($result);
		
		
		//////////// jika ada Roster
		if($row){		
			if($row['masuk_hari_sebelumnya']=='t'){				
				$masuk 		= date("Y-m-d", strtotime("-1 days", strtotime($start_date)))." ".$row['jam_pulang'];
				$scanMulaiMasuk 	= date("Y-m-d", strtotime("-1 days", strtotime($start_date)))." ".$row['jam_mulai_scan_masuk'];
				$scanAkhirMasuk 	= date("Y-m-d", strtotime("-1 days", strtotime($start_date)))." ".$row['jam_akhir_scan_masuk'];
			}
			else{
				$masuk 			= $start_date." ".$row['jam_masuk'];
				$scanMulaiMasuk 	= $start_date." ".$row['jam_mulai_scan_masuk'];
				$scanAkhirMasuk 	= $start_date." ".$row['jam_akhir_scan_masuk'];
				
			}
		
			if($row['pulang_hari_berikutnya']=='t'){				
				$pulang 	= date("Y-m-d", strtotime("+1 days", strtotime($start_date)))." ".$row['jam_pulang'];
				$scanMulaiPulang 	= date("Y-m-d", strtotime("+1 days", strtotime($start_date)))." ".$row['jam_mulai_scan_pulang'];
				$scanAkhirPulang 	= date("Y-m-d", strtotime("+1 days", strtotime($start_date)))." ".$row['jam_akhir_scan_pulang'];
			}
			else{
				$pulang 	= $start_date." ".$row['jam_pulang'];
				$scanMulaiPulang = $start_date." ".$row['jam_mulai_scan_pulang'];
				$scanAkhirPulang = $start_date." ".$row['jam_akhir_scan_pulang'];
			}			
		}
		else{			
			
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
			$rowHariLibur		= pg_fetch_assoc($resultHariLibur);
			if($rowHariLibur){
				$keteranganLibur = $rowHariLibur['nama'];
			}
			else{
				$keteranganLibur = "";
			}
			
			$masuk 		= $start_date." 07:30";
			$pulang 	= $start_date." 16:00";
			
			$scanMulaiMasuk 	= $start_date." 00:01";
			$scanAkhirMasuk 	= $start_date." 23:59";
			
			$scanMulaiPulang 	= $start_date." 00:01";
			$scanAkhirPulang 	= $start_date." 23:59";		
			
		}
		
		 /** 		<!-----------  		end Roster 		 
		 -----!>          ***/		 
		 
		/** 		<!-----------  start Penentuan Absen -----!>          ***/
		
		$queryMasuk	=	" 
			select 
				tanggal 
			from 
				absensi_log 
			where  
				tanggal >= '".$scanMulaiMasuk."'
				AND tanggal <=  '".$scanAkhirMasuk."' and 
				absensi_log.badgenumber in (SELECT user_id from mesin_user where id_pegawai = 'dc7ae9c2-200c-11e7-aa72-000c29766abb')
			order by 
				absensi_log.tanggal asc";
		$resultMasuk 	= 	pg_query($connection, $queryMasuk);
		$rowMasuk 		= pg_fetch_assoc($resultMasuk);
		
		$queryPulang =	" 
			select 
				tanggal 
			from 
				absensi_log 
			where  
				tanggal >= '".$scanMulaiPulang."'
				AND tanggal <=  '".$scanAkhirPulang."' and
				absensi_log.badgenumber in (SELECT user_id from mesin_user where id_pegawai = 'dc7ae9c2-200c-11e7-aa72-000c29766abb')
			order by 
				absensi_log.tanggal desc";
		$resultPulang  	= 	pg_query($connection, $queryPulang );
		$rowPulang 		= pg_fetch_assoc($resultPulang );
				
		/** 		<!-----------  End Penentuan Absen 	-----!>          ***/		
		
		/** 		<!-----------  		Start Lembur 	-----!>          ***/
		$queryLembur	=	" 
			select 
				id,
				to_char(t_lembur_pegawai.jam_lembur_akhir,'HH24:MI:SS') as jam_lembur_akhir ,	
				to_char(t_lembur_pegawai.jam_lembur_awal,'HH24:MI:SS') as jam_lembur_awal	
			from 
				t_lembur_pegawai
			where  
				to_char(t_lembur_pegawai.tgl_lembur  ,'yyyy-mm-dd')  = '".$start_date."'  and
				t_lembur_pegawai.id_pegawai = 'dc7ae9c2-200c-11e7-aa72-000c29766abb'
		";
		$resultLembur 	= 	pg_query($connection, $queryLembur);
		$rowLembur		= 	pg_fetch_assoc($resultLembur); 		
		
		if($rowLembur['id']!=''){
			$tglMulaiLembur = $start_date." ".$rowLembur['jam_lembur_awal'];
			$tglPulang 		= $start_date." ".$rowLembur['jam_lembur_akhir'];
		
			if($keteranganLibur !=''){
				$menitLembur	=	"";
				$keteranganLibur = "";
			}
			else{
				$to_time 		= strtotime($rowLembur['jam_lembur_awal']);
				$from_time 		= strtotime($rowLembur['jam_lembur_akhir']);
				$menitLembur 	= round(abs($to_time - $from_time) / 60,2);
				$keteranganLibur = "";
			}			
			
			if($rowMasuk['tanggal']!=''){
				$tglMasuk = $rowMasuk['tanggal'];
			}
			else{
				$tglMasuk = $start_date." ".$rowLembur['jam_lembur_awal'];				
			}
		}
		else{
			
			
			
			$tglMasuk 	= $rowMasuk['tanggal'];
			$tglPulang	= $rowPulang['tanggal'];			
			
			$waktuPulang 				= strtotime($tglPulang); 
			
			$tglPulangNormal	= $start_date." ".$jamPulangNormal;		
			$tglMasukNormal		= $start_date." ".$jamMasukNormal;		
			
			$tglPulangNormalCepat 		= strtotime($tglPulangNormal); 
			$tglMasukNormalCepat 		= strtotime($tglMasukNormal);
			
			if ($waktuPulang < $tglPulangNormalCepat){ 
				$menitLembur		=	"";
				$menitPulangCepat 	= round(abs($tglPulangNormalCepat - $tglMasukNormalCepat) / 60,2);
			}
			else{
				if($keteranganLibur !=''){
					$menitLembur	=	"";
				}
				else{
					$to_time 		= strtotime($tglPulang);
					$from_time 		= strtotime($tglPulangNormal);
					$menitLembur 	= round(abs($to_time - $from_time) / 60,2);
				}
				$menitPulangCepat 	= false;
			}			
		}
		
		/** 		<!-----------  end Lembur -----!>          ***/
		
		
		
		
		echo "<b>".$start_date."</b><br>";
		echo $masuk."<br>";
		echo $pulang."<br><br>";		
		
		if($keteranganLibur!=''){
			echo "Libur : ". $keteranganLibur."<br>";
		}
		else{
			if( $tglMasuk=='' ){
				if($namaHari=='Sun' || $namaHari=='Sat'){
					
					echo $dayList[$namaHari];
				}
				else{
					echo "Mangkir";
				}
			}
			else{
				echo "Masuk 	: ". $tglMasuk."<br>";		
				echo "Pulang 	: ". $tglPulang."<br>";
				$adaLembur = true;
				
				if($adaLembur){
					echo "<br>Menit Lembur : ".$menitLembur."<br>";
				}
				if($menitPulangCepat){
					echo "<br>Pulang Cepat : ".$menitPulangCepat."<br>";
				}
				
			}			
		}	
		echo "<hr>";
		
		
		
		/// looping
		$start_date = date ("Y-m-d", strtotime("+1 days", strtotime($start_date)));
	}

}


?>