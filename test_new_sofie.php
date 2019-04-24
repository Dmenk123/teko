
<!--
<form method="get">
	Kode Instansi = <input name='id_instansi'><br>
	Tgl Mulai = <input name='tgl_mulai' placeholder='yyyy-mm-dd'><br>
	Tgl Selesai = <input name='tgl_selesai' placeholder='yyyy-mm-dd'><br>
	<button type='submit'>Submit</button>
</form>

-->


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
		//echo $tglMasukUntukFungsi;
		if($tglMasukUntukFungsi == 'LEMBURSURATMINGGU'){
			
			//echo "werwer";
			
			$tanggalAjah	=	explode(' ',$scanMulaiMasuk);
			
			
			$queryLembur	=	" 
			select 
				id,
				to_char(t_lembur_pegawai.jam_lembur_akhir,'HH24:MI') as jam_lembur_akhir ,	
				to_char(t_lembur_pegawai.jam_lembur_akhir,'yyyy-mm-dd HH24:MI') as tanggal_jam_lembur_akhir ,	
				to_char(t_lembur_pegawai.jam_lembur_awal,'HH24:MI') as jam_lembur_awal	,				
				to_char(t_lembur_pegawai.jam_lembur_awal,'yyyy-mm-dd HH24:MI') as tanggal_jam_lembur_awal 
			from 
				t_lembur_pegawai
			where  
				to_char(t_lembur_pegawai.tgl_lembur  ,'yyyy-mm-dd')  = '".$tanggalAjah[0]."'  and
				t_lembur_pegawai.id_pegawai = '".$idPegawaiUntukFungsi."' 
				
			";
			$resultLembur 	= 	pg_query($connection, $queryLembur);
			$rowLembur		= 	pg_fetch_assoc($resultLembur); 	
			
			
			 $queryPulangCek	=	" 
					select 
						count(distinct(tanggal))  as jumlah
					from 
						absensi_log 
					where  
						tanggal >= '".$tanggalAjah[0]." 00:01'
						AND tanggal <=  '".$tanggalAjah[0]." 23:59' and 
						absensi_log.badgenumber in (SELECT user_id from mesin_user where id_pegawai = '".$idPegawaiUntukFungsi."')  
						and absensi_log.id_mesin in (SELECT id_mesin from mesin_user where id_pegawai = '".$idPegawaiUntukFungsi."')
				";
				$resultPulangCek 	= 	pg_query($connection, $queryPulangCek);
				$rowPulangCek 		= pg_fetch_assoc($resultPulangCek);
			
			//echo 
			//var_dump($rowLembur);
			//echo $rowPulangCek[jumlah];
			if($rowPulangCek[jumlah] > 1){		

				$queryMasuk	=	" 
				select 
					to_char(tanggal,'yyyy-mm-dd HH24:MI') as tanggal
				from 
					absensi_log 
				where  
					tanggal >= '".$tanggalAjah[0]." 00:01'
					AND tanggal <=  '".$tanggalAjah[0]." 23:59' and 
					absensi_log.badgenumber in (SELECT user_id from mesin_user where id_pegawai = '".$idPegawaiUntukFungsi."')  
					and absensi_log.id_mesin in (SELECT id_mesin from mesin_user where id_pegawai = '".$idPegawaiUntukFungsi."')
				order by 
					absensi_log.tanggal asc";
			$resultMasuk 	= 	pg_query($connection, $queryMasuk);
			$rowMasuk 		= pg_fetch_assoc($resultMasuk);
			
				$returnArray['masuk'] = $rowMasuk['tanggal'];				
			}
			else{
				
				$returnArray['masuk'] = $rowLembur['tanggal_jam_lembur_awal'];
				var_dump();
			}
			
				//var_dump($rowLembur);

			
			
			//var_dump($returnArray['masuk']);
		}
		else{
		
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
		}
		return $returnArray;
		
		
		
	}
	function fungsiPenentuanFingerPulang($idPegawaiUntukFungsi,$scanMulaiPulang,$scanAkhirPulang,$tglPulangUntukFungsi){
		
			$conn_string = "host=172.18.1.219 port=5432 dbname=garbissby user=garbisbeta password=garbis@2018";
			$connection = pg_connect($conn_string);
		if($tglPulangUntukFungsi == 'LEMBURSURATMINGGU'){
			
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
			
			
			
			//var_dump($rowLembur);
			
				 $queryPulangCek	=	" 
					select 
						count(distinct(tanggal))  as jumlah
					from 
						absensi_log 
					where  
						tanggal >= '".$tanggalAjah[0]." 00:01'
						AND tanggal <=  '".$tanggalAjah[0]." 23:59' and 
						absensi_log.badgenumber in (SELECT user_id from mesin_user where id_pegawai = '".$idPegawaiUntukFungsi."')  
						and absensi_log.id_mesin in (SELECT id_mesin from mesin_user where id_pegawai = '".$idPegawaiUntukFungsi."')
				";
				$resultPulangCek 	= 	pg_query($connection, $queryPulangCek);
				$rowPulangCek 		= pg_fetch_assoc($resultPulangCek);
			//var_dump($rowPulangCek);
			if($rowPulangCek[jumlah] > 1){				
			
				 $queryPulang	=	" 
					select 
						to_char(tanggal,'yyyy-mm-dd HH24:MI') as tanggal
					from 
						absensi_log 
					where  
						tanggal >= '".$tanggalAjah[0]." 00:01'
						AND tanggal <=  '".$tanggalAjah[0]." 23:59' and 
						absensi_log.badgenumber in (SELECT user_id from mesin_user where id_pegawai = '".$idPegawaiUntukFungsi."')  
						and absensi_log.id_mesin in (SELECT id_mesin from mesin_user where id_pegawai = '".$idPegawaiUntukFungsi."')
					order by 
						absensi_log.tanggal desc";
				$resultPulang 	= 	pg_query($connection, $queryPulang);
				$rowPulang 		= pg_fetch_assoc($resultPulang);
			
				$returnArray['pulang'] = $rowPulang['tanggal'];				
			}
			else{
				
				$returnArray['pulang'] = $rowLembur['tanggal_jam_lembur_akhir'];
				
			}
			
			
			//var_dump($returnArray['pulang']);
			
			echo "lembur minggu";
		}
		else{
		
			
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
			
			//var_dump($rowPulang );
			
			if($rowPulang){
				$returnArray['pulang'] = $rowPulang['tanggal'];
			}
			else{
				$returnArray['pulang'] = null;
			}
			
			
			//var_dump($tglPulangUntukFungsi);
			
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
				//	echo count($rowPulang);
					 $queryPulangCek	=	" 
						select 
							count(distinct(tanggal))  as jumlah
						from 
							absensi_log 
						where  
							tanggal >= '".$tanggalAjah[0]." 00:01'
							AND tanggal <=  '".$tanggalAjah[0]." 23:59' and 
							absensi_log.badgenumber in (SELECT user_id from mesin_user where id_pegawai = '".$idPegawaiUntukFungsi."')  
							and absensi_log.id_mesin in (SELECT id_mesin from mesin_user where id_pegawai = '".$idPegawaiUntukFungsi."')
					";
					$resultPulangCek 	= 	pg_query($connection, $queryPulangCek);
					$rowPulangCek 		= pg_fetch_assoc($resultPulangCek);
			//var_dump($rowPulangCek);
			if($rowPulangCek[jumlah] > 1){		
						$tglPulangLemburSurat 	= 	strtotime($rowPulang['tanggal']);
					
						$menitLembur = round(abs( $tglPulangNormal - $tglPulangLemburSurat ) / 60,2);		
						$returnArray['lembur'] = $menitLembur;
						
						
						$menitPulangCepat = round(abs($waktuPulang - $tglPulangNormal) / 60,2);					
						$returnArray['cepatPulang'] = "0";		
						
						$returnArray['pulang'] = $rowPulang['tanggal'];
						
								
						//echo $returnArray['pulang'];
						
					}
					else{
						$tglPulangLemburSurat 	= 	strtotime($rowLembur['tanggal_jam_lembur_akhir']);
					
						$menitLembur = round(abs( $tglPulangNormal - $tglPulangLemburSurat ) / 60,2);		
						$returnArray['lembur'] = $menitLembur;
						
						
						$menitPulangCepat = round(abs($waktuPulang - $tglPulangNormal) / 60,2);					
						$returnArray['cepatPulang'] = "0";		
						
						$returnArray['pulang'] = $rowLembur['tanggal_jam_lembur_akhir'];
					}
					
					

					//	echo $menitLembur;
					
					//echo "ada lembur";
						
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
		}
		
		return $returnArray;
	}
	
	
	//////////////////////////////////////////////////////////////////////////////////////////////////
	//////////////////////////////////////////////////////////////////////////////////////////////////
	
	
	//$queryInstansi	=	" 
	//	select id,nama from m_pegawai where kode_status_pegawai = '1' and id in ('fa9b8362-64d2-11e6-affc-0b770ff76709', 'faa76a42-64d2-11e6-b56b-ab790e8b8ec9' , 'fa9de4c2-64d2-11e6-9dd1-df2e7510762d', 'fa9de4c2-64d2-11e6-9729-33f94cc2c256' , 'f1b0bbbe-64d2-11e6-bbc8-03f02e1b59e8' , ) ;
	//";
	
	
		/**
PMK,
bappeko, 
Bpkpd
Adpem
Bkd
Bag Hukum
Kec wiyung
Kel bubutan 
	

	
	
		
	
 $queryInstansi	=	" 
		select
			m.id,m.nama, m.nip
		from
			m_pegawai m
			LEFT JOIN LATERAL (
				SELECT
					h.kode_unor, h.tgl_mulai, muok.nama as nama_unor, mi.kode as kode_instansi, mi.nama as nama_instansi
				FROM
					m_pegawai_unit_kerja_histori h
					LEFT JOIN m_unit_organisasi_kerja muok ON  h.kode_unor =  muok.kode
					LEFT JOIN m_instansi mi ON  muok.kode_instansi = mi.kode WHERE h.tgl_mulai <= '2018-10-01' and m.id = h.id_pegawai
				ORDER BY h.tgl_mulai DESC LIMIT 1
			)
			pukh ON true			
		where
			pukh.kode_instansi = '3.02.00.00.00'
		
	";**/
		
	$queryInstansi	=	" 
		select id,nama from m_pegawai where id in ('f0128c4c-64d2-11e6-aea9-4f05d4c7c32a' ) ;
	";

	$resultInstansi 	= 	pg_query($connection, $queryInstansi); 
	//var_dump($resultInstansi);
	$i = 1;
	while ($rowPegawai 	= pg_fetch_assoc($resultInstansi)) {
		
		$start_date = '2018-06-01';
		$end_date 	= '2018-07-01';
	
		echo $rowPegawai[nama]."<br>";
		echo $i."<br>";
		$dayList = array(
			'Sun' => 'MINGGU',
			'Mon' => 'SENIN',
			'Tue' => 'SELASA',
			'Wed' => 'RABU',
			'Thu' => 'KAMIS',
			'Fri' => 'JUMAT',
			'Sat' => 'SABTU'
		);
		
	
		$idPegawai		=	$rowPegawai['id'];

		
		while (strtotime($start_date) <= strtotime($end_date)) {	
		echo $start_date."<br>";
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
			//if($rowCekData['jumlah'] < 1){
		
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
						absensi_log.badgenumber in (SELECT user_id from mesin_user where id_pegawai = '".$idPegawai."')
						and absensi_log.id_mesin in (SELECT id_mesin from mesin_user where id_pegawai = '".$idPegawai."')
						";
				$resultFinger 	= 	pg_query($connection, $queryFinger);
				$rowAdaFinger 		= pg_fetch_assoc($resultFinger);		
				/** 		<!-----------  End Finger  ----!>          ***/			
						
				
				///////////// jika Ijin 
				if($rowIjin){
					
					
					///////////// Jika ijin dengna kode DK atau DL
					if($rowIjin['kode'] == 'DK' || $rowIjin['kode'] == 'DL'){
						
						$queryFingerMasukPulang	=	" 
							select 
								count(distinct(tanggal))  as jumlah
							from 
								absensi_log 
							where  
								tanggal >= '".$start_date." 00:01'
								AND tanggal <=  '".$start_date." 23:59' and 
								absensi_log.badgenumber in (SELECT user_id from mesin_user where id_pegawai = '".$idPegawai."')  
								and absensi_log.id_mesin in (SELECT id_mesin from mesin_user where id_pegawai = '".$idPegawai."')
						";
						$resultFingerMasukPulang	= 	pg_query($connection, $queryFingerMasukPulang);
						$rowFingerMasukPulang 		= 	pg_fetch_assoc($resultFingerMasukPulang);
						
						/**if($rowFingerMasukPulang['jumlah'] > 1){
							
							$scanMulaiMasuk 	= 	$start_date." 00:01";
							$scanAkhirMasuk 	= 	$start_date." 23:59";
							
							$scanMulaiPulang 	= 	$start_date." 00:01";
							$scanAkhirPulang 	= 	$start_date." 23:59";	
							
							$fingerMasukArray	= 	fungsiPenentuanFingerMasuk($idPegawai,$scanMulaiMasuk,$scanAkhirMasuk,"LEMBURSURATMINGGU");
							$fingerMasuk		= 	$fingerMasukArray['masuk'];

							$fingerPulangArray	=	fungsiPenentuanFingerPulang($idPegawai,$scanMulaiPulang,$scanAkhirPulang,"LEMBURSURATMINGGU" );
							$fingerPulang		= 	$fingerPulangArray['pulang'];

							$fingerMasukHitungLembur 	= 	strtotime($fingerMasuk);	
							$fingerPulangHitungLembur 	= 	strtotime($fingerPulang); 

							$menitLembur 				= 	round(abs($fingerMasukHitungLembur - $fingerPulangHitungLembur) / 60,2);	
							
							
							$menitTelat 				= 	"0";
							$kodeMasuk 					= 	"*";
							$keteranganMasuk 			= 	"";
							$kodeTidakMasuk 			= 	"";
							$keteranganTidakMasuk 		= 	"";
							$jamKerja 					= 	$rowIjin['kode'];
							
							
							
							if($rowHariLibur || $namaHari=='Sat' || $namaHari=='Sun'){
								if($menitLembur > 360){
									$menitLemburDiakui = 360;
								}
								else{
									$menitLemburDiakui = $menitLembur;
								}
								
							echo	$keterangan 				=	$rowIjin['kode']." ADA FINGER DI HARI LIBUR, DAPAT LEMBUR  MAX 6 JAM";
							}else{
								if($menitLembur > 180){
									$menitLemburDiakui = 180;
								}
								else{
									$menitLemburDiakui = $menitLembur;
								}
								
							echo	$keterangan 				=	$rowIjin['kode']." ADA FINGER DI HARI LIBUR, DAPAT LEMBUR MAX 3 JAM";
							}
							
						}
						else{**/
						
							/////////// jika hari sabtu minggu atau hari libur
							if($rowHariLibur || $namaHari=='Sat' || $namaHari=='Sun'){
								$menitPulangCepat 			= 	"0";			
								$fingerMasuk				=	"";
								$fingerPulang				=	"";
								$menitTelat 				= 	"0";
								$menitLembur 				= 	"360";
								$menitLemburDiakui 			= 	"360";
								$kodeMasuk 					= 	"*";
								$keteranganMasuk 			= 	"";
								$kodeTidakMasuk 			= 	$rowIjin['kode'];
								$keteranganTidakMasuk 		= 	"";
								$jamKerja 					= 	$rowIjin['kode'];
								
								$keterangan 				=	$rowIjin['kode']." DI HARI LIBUR, DAPAT LEMBUR 6 JAM";
							}
							
							
							////////// jika hari biasa
							else{
								$menitPulangCepat 			= 	"0";			
								$fingerMasuk				=	"";
								$fingerPulang				=	"";
								$menitTelat 				= 	"0";
								$menitLembur 				= 	"180";
								$menitLemburDiakui 			= 	"180";
								$kodeMasuk 					= 	"*";
								$keteranganMasuk 			= 	"";
								$kodeTidakMasuk 			= 	$rowIjin['kode'];
								$keteranganTidakMasuk 		= 	"";
								$jamKerja 					= 	$rowIjin['kode'];
								
								$keterangan 				=	$rowIjin['kode']." DI HARI ".$dayList[$namaHari].", DAPAT LEMBUR 3 JAM";
							}
						//}
						
						
					}
					
					
					////////////// jika Ijin biasa atau selain DK, DL
					else{
					
					
						/////////////////// jika hari libur atau hari sabtu minggu
						if($rowHariLibur || $namaHari=='Sat' || $namaHari=='Sun'){
							$menitPulangCepat 			= 	"0";			
							$fingerMasuk				=	"";
							$fingerPulang				=	"";
							$menitTelat 				= 	"0";
							$menitLembur 				= 	"0";
							$menitLemburDiakui 			= 	"0";
							$kodeMasuk 					= 	"*";
							$keteranganMasuk 			= 	"";
							$kodeTidakMasuk 			= 	$rowIjin['kode'];
							$keteranganTidakMasuk 		= 	"";
							$jamKerja 					= 	$rowIjin['kode'];
							
							$keterangan 				=	"IJIN ".$rowIjin['kode']." DI HARI LIBUR";
						}
						
						/////////////// jika hari biasa
						else{
							
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
							
							
							$keterangan 				=	"IJIN ".$rowIjin['kode']." DI HARI LIBUR";
						}
					}	
				}
				
				
				
				////// jika tidak ada ijin
				else{
					/// jika ada Roster
					if($rowRoster){
						
						
						///// jika roster Libur
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
							$keteranganTidakMasuk 		= 	"";
							$jamKerja 					= 	"";
							
							
							$keterangan 				=	"LIBUR ROSTER";
						}
						
						
						////// jika roster bukan Libur
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
							
							
							//////////// jika tidak ada finger masuk dan pulang sesuai data Master Roster
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
								
								
								$keterangan 				=	"MANGKIR, TIDAK ADA FINGER MASUK DAN PULANG";
							}
							
							//////////// jika ada finger masuk dan pulang sesuai data Master Roster
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
								
								$kodeMasuk 					= 	"H";
								$keteranganMasuk 			= 	"";
								$kodeTidakMasuk 			= 	"";
								$keteranganTidakMasuk 		= 	"";
								
								
								$keterangan 				=	"HADIR ROSTER";
							}
							
						}
						
					}
					
					
					
					///// jika tidak ada roster
					else{
						
						
						
						$scanMulaiMasuk 	= $start_date." 00:01";
						$scanAkhirMasuk 	= $start_date." 23:59";
						
						$scanMulaiPulang 	= $start_date." 00:01";
						$scanAkhirPulang 	= $start_date." 23:59";	
						
						/// Jika Libur Nasional
						if($rowHariLibur){
							
							
							//// jika ada surat lembur
								if($rowLembur){
									
									//echo "asd";
									$harusnyaMasuk	=	"";
									$harusnyaPulang	= 	"";
									$menitPulangCepat 			= 	"0";
									$menitTelat 				= 	"0";
									$kodeMasuk 					= 	"*";
									$kodeTidakMasuk 			= 	"";
									$keteranganMasuk 			= 	$rowHariLibur[nama];
									
									
									$fingerMasukArray	= 	fungsiPenentuanFingerMasuk($idPegawai,$scanMulaiMasuk,$scanAkhirMasuk,"LEMBURSURATMINGGU");
									 $fingerMasuk		= 	$fingerMasukArray['masuk'];
							
									$fingerPulangArray	=	fungsiPenentuanFingerPulang($idPegawai,$scanMulaiPulang,$scanAkhirPulang,"LEMBURSURATMINGGU" );
									 $fingerPulang		= 	$fingerPulangArray['pulang'];
									
									$fingerMasukHitungLembur 	= 	strtotime($fingerMasuk);	
									$fingerPulangHitungLembur 	= 	strtotime($fingerPulang); 
									
									
									
									$menitLembur 		= round(abs($fingerMasukHitungLembur - $fingerPulangHitungLembur) / 60,2);	
									$menitLemburDiakui = $menitLembur;
									
									$jamKerja 					= 	$rowHariLibur[nama];
									
									
									$keterangan 				=	"LEMBUR DI HARI LIBUR NASIONAL";
								}
								
								
								//// jika tidak ada surat lembur
								else{
									
								//echo "werwerw Tidak Lembur<br><br>";	
									
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
									
									
									$keterangan 				=	"FINGER DI HARI LIBUR NASIONAL";
								}
							
							
						}
						
						
						//////////// jika tidak ada libur
						else{
							
							//var_dump($rowAdaFinger );
							
							/// jika Sabtu
							if($namaHari=='Sat'){	
							//	var_dump($rowLembur);
							
							
								/////// jika sabtu ada lembur
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
									
									
									 $keterangan 				=	"LEMBUR DI HARI SABTU DENGAN SURAT";
									
								}
								
								/////// jika sabtu tidak ada lembur , ada finger
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
									
									
									 $keterangan 				=	"LEMBUR DI HARI SABTU DENGAN FINGER";
								}
								 
								//// jika tidak ada finger dan tidak ada surat lembur
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
									$keteranganTidakMasuk 		= 	"";							
									$jamKerja 					= 	"";	
									
									
									$keterangan 				=	"LIBUR DI HARI SABTU";
								}
							}
							
							
							/// Jika Minggu
							elseif($namaHari=='Sun'){					
							
							
								//echo "werwerw Lembuar<br><br>";
								//var_dump($rowLembur);
								
								
								/// jika ada surat lembur
								if($rowLembur){
									
									//echo "asd";
									
									$menitPulangCepat 			= 	"0";
									$menitTelat 				= 	"0";
									$kodeMasuk 					= 	"*";
									$kodeTidakMasuk 			= 	"";
									$keteranganMasuk 			= 	"";
									
									
									$fingerMasukArray	= 	fungsiPenentuanFingerMasuk($idPegawai,$scanMulaiMasuk,$scanAkhirMasuk,"LEMBURSURATMINGGU");
									 $fingerMasuk		= 	$fingerMasukArray['masuk'];
							
									$fingerPulangArray	=	fungsiPenentuanFingerPulang($idPegawai,$scanMulaiPulang,$scanAkhirPulang,"LEMBURSURATMINGGU" );
									 $fingerPulang		= 	$fingerPulangArray['pulang'];
									
									$fingerMasukHitungLembur 	= 	strtotime($fingerMasuk);	
									$fingerPulangHitungLembur 	= 	strtotime($fingerPulang); 
									
									
									
									$menitLembur 		= round(abs($fingerMasukHitungLembur - $fingerPulangHitungLembur) / 60,2);	
									$menitLemburDiakui = $menitLembur;
									
									$jamKerja 					= 	"";
									
									
									$keterangan 				=	"LEMBUR DI HARI MINGGU DENGAN SURAT";
								}
								
								
								/// jika tidak ada surat lembur
								else{
									
								//echo "werwerw Tidak Lembur<br><br>";	
									
									$harusnyaMasuk	=	"";
									$harusnyaPulang	= 	"";
									
									$fingerMasukArray	= 	fungsiPenentuanFingerMasuk($idPegawai,$scanMulaiMasuk,$scanAkhirMasuk,$harusnyaMasuk);
									$fingerMasuk		= 	$fingerMasukArray['masuk'];
							
									$fingerPulangArray	=	fungsiPenentuanFingerPulang($idPegawai,$scanMulaiPulang,$scanAkhirPulang,"" );
									$fingerPulang		= 	$fingerPulangArray['pulang'];
									
									$menitPulangCepat 			= 	"0";
									$menitTelat 				= 	"0";
									$menitLembur 				= 	"0";
									$kodeMasuk 					= 	"*";
									$keteranganMasuk 			= 	"";
									$kodeTidakMasuk 			= 	"LB";
									$keteranganTidakMasuk 		= 	"";
									$menitLemburDiakui 			= 	"0";
									
									$jamKerja 					= 	"";
									
									
									$keterangan 				=	"LIBUR DI HARI MINGGU";
								}
							}
							
							
							//// jika hari senin - jumat
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
									m_pegawai_role_jam_kerja_histori.tgl_mulai <= '".$start_date."' 
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
							
								
								
								//// jika ada lembur
								if($rowLembur){
									$fingerMasukArray	= 	fungsiPenentuanFingerMasuk($idPegawai,$scanMulaiMasuk,$scanAkhirMasuk,$harusnyaMasuk);
									$fingerPulangArray	=	fungsiPenentuanFingerPulang($idPegawai,$scanMulaiPulang,$scanAkhirPulang,$harusnyaPulang );
									
									
									//// jika tidak finger masuk dan pulang
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
										
										
										$keterangan 				=	"LEMBUR DI HARI ".$dayList[$namaHari]." DENGAN SURAT TANPA FINGER";
									}
									
									
									//// jika ada finger masuk dan pulang
									else{
										
									//echo "werwer";
									
									
										$kodeMasuk 			= 	"H";								
										$fingerMasuk		= 	$fingerMasukArray['masuk'];
										$menitTelat			= 	$fingerMasukArray['telat'];							
										
										$fingerPulang		= 	$fingerPulangArray['pulang'];						
										$menitLembur		= 	$fingerPulangArray['lembur'];
										$menitPulangCepat	= 	$fingerPulangArray['cepatPulang'];
										
										
										$menitLemburDiakui 	= $menitLembur;
										
										
										$keterangan 				=	"LEMBUR DI HARI ".$dayList[$namaHari]." DENGAN SURAT DISERTAI FINGER";
										
										
									}
									
									
								}
								
								/// jika tidak ada lembur
								else{
									
									
									
									
									$fingerMasukArray	= 	fungsiPenentuanFingerMasuk($idPegawai,$scanMulaiMasuk,$scanAkhirMasuk,$harusnyaMasuk);
									$fingerPulangArray	=	fungsiPenentuanFingerPulang($idPegawai,$scanMulaiPulang,$scanAkhirPulang,$harusnyaPulang );
									
									////////// jika tidak ada finger masuk dan finger pulang
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
										$keteranganTidakMasuk 		= 	"";
										$menitLemburDiakui 			= 	"0";
										
										
										$keterangan 				=	"MANGKIR DI HARI ".$dayList[$namaHari];
									}
									
									////////// jika ada finger masuk dan finger pulang
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
										
										
										$keterangan 				=	"HADIR DI HARI ".$dayList[$namaHari];
									}
								
									
									
									//var_dump($fingerPulang);
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
							keterangan_tidak_masuk,
							keterangan
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
							'".$keteranganTidakMasuk."',
							'".$keterangan."'
						)
				";
		//	$resultDataMentah	= 	pg_query($connection, $insertDataMentah);
				
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
			//}
			//else{
			//	echo "<br><br>";
			//	echo "Sudah Ada";
			//}
			
			
			/// looping
			$start_date = date ("Y-m-d", strtotime("+1 days", strtotime($start_date)));
		}
		
		$i++;
	}
}
?>