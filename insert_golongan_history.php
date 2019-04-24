<?php
$conn_string = "host=172.18.1.219 port=5432 dbname=garbissby user=garbisbeta password=garbis@2018";
$connection = pg_connect($conn_string);
 $queryPegawai	=	"
  select id,kode_golongan_akhir from m_pegawai where id not in (select id_pegawai from m_pegawai_golongan_histori)
";

$resultInstansi 	= 	pg_query($connection, $queryPegawai);

//var_dump($resultInstansi );

$i = 1;
while ($rowPegawai 	= pg_fetch_assoc($resultInstansi)) {

	$tglJabatanTerakhir	=	"
		select tgl_mulai from m_pegawai_unit_kerja_histori where id_pegawai='".$rowPegawai['id']."' order by tgl_mulai desc limit 1
	";

	$resultjabatan 	= 	pg_query($connection, $tglJabatanTerakhir);
	$rowjabatan 	= pg_fetch_assoc($resultjabatan);
	
   $queryInsert	=	"
    insert into m_pegawai_golongan_histori
    (
      id,
      tgl_mulai,
      tgl_upd,
      user_upd,
      id_pegawai,
      kode_golongan
    )
    VALUES
    (
      (select * from uuid_generate_v1()),
      '".$rowjabatan[tgl_mulai]."',
      current_timestamp	,
      '1',
      '".$rowPegawai['id']."',
      '".$rowPegawai['kode_golongan_akhir']."'

    )
  ";

  $resultInsert 	= 	pg_query($connection, $queryInsert);
	  if($resultInsert ){
	  echo "sukses";
  }
  else{
	   echo "gagal";
  }
echo "<br>";

}
?>
