
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

$con_db_garbis_live = "host=172.18.1.34 port=5432 dbname=garbis_sby user=egov1 password=EGOVPass";
$con_db_teko_cak    = "host=172.18.1.59 port=5432 dbname=garbis_sby user=sofi password=sofigarbis@2018";

$connection_garbis_live = pg_connect($con_db_garbis_live);
$connection_tekocak     = pg_connect($con_db_teko_cak);
if (!$connection_garbis_live || !$connection_tekocak) {
	print("Connection Failed");
	exit;
}
else{
	
	// echo "CUK";
			$queryDataMentah	= "select * from data_mentah";
			$resultData 		= pg_query($connection_tekocak, $queryDataMentah);
			// var_dump($resultData); die;
			// $dataMentah			= pg_fetch_assoc($resultData); 

	$no = 0;		
	while ($dataMentah			= pg_fetch_assoc($resultData)) {

			$no++;

			$queryCekDataMentah	= "select count(*) from data_mentah 
								   where id_pegawai = '".$dataMentah['id_pegawai']."' 
								   AND 	tanggal = '".$dataMentah['tanggal']."'";

			$resultCekData 		= pg_query($connection_garbis_live, $queryCekDataMentah);
			if(!empty(pg_num_rows($resultCekData))){
				
				$insertDataMentah	=	" 
					insert into data_mentah
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
							'".$dataMentah['tanggal']."',
							'".$dataMentah['id_pegawai']."',
							'".$dataMentah['hari']."',
							'".$dataMentah['jam_kerja']."',
							'".$dataMentah['jadwal_masuk']."',
							'".$dataMentah['jadwal_pulang']."',
							'".$dataMentah['finger_masuk']."',
							'".$dataMentah['finger_pulang']."',
							'".$dataMentah['pulang_cepat']."',
							'".$dataMentah['datang_telat']."',
							'".$dataMentah['lembur']."',
							'".$dataMentah['lembur_diakui']."',
							'".$dataMentah['kode_masuk']."',
							'".$dataMentah['keterangan_masuk']."',
							'".$dataMentah['kode_tidak_masuk']."',
							'".$dataMentah['keterangan_tidak_masuk']."',
							'".$dataMentah['keterangan']."'							
						)";

						// echo $insertDataMentah;

			$resultDataMentah	= 	pg_query($connection_garbis_live, $insertDataMentah);	
			echo "row Data Ke - ".$no." <br>";
			}
			
	}

	echo "SUKSES MIGRASI";
			
}
?>