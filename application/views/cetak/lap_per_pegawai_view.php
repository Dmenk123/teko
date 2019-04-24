<style>
body{
	background:url('assets/img/logo_pemkot_watermark2.png');
	background-position:center 100px;
	background-position-y: 155px;
	background-repeat:no-repeat;
	height: 1000px;
}
.title{
	font-size:13px;
	font-family:Arial, Helvetica, sans-serif;
}
.legend tr td{
	font-size:10px;
	font-family:Arial, Helvetica, sans-serif;
	padding:2px;
}
tr.bggrey td{
	background:#E5E5E5;
	border-bottom: none;
	border-TOP: 0px;
}
.headingtext tr td{
	font-family:Arial, Helvetica, sans-serif;
	font-size:12px;
	padding:3px;
}
table.cloth{
	font-family:Arial, Helvetica, sans-serif;
	border-collapse:collapse;
	padding:0px;
	outline:none;
  margin:2px;
}
table.cloth tr th{
	border:1px solid #000;
	font-size:10px;
	font-weight:normal;
}
table.cloth tr td{
	border:1px solid #000;
	font-size:10px;
	padding:2px 0;
	vertical-align:top;
}
.qr{
	width:80px;
}

</style>


<div align="center" class="title">
PEMERINTAH KOTA SURABAYA<br />
LAPORAN PER PERIODE KEHADIRAN PEGAWAI
<br />
<div style="font-size:11px;"><?php echo  date('d-m-Y', strtotime($this->tgl_pertama));?> s/d <?php echo date('d-m-Y', strtotime($this->tgl_terakhir));?> </div>
</div>

<!-- <?php
if(!$this->sudahAda){echo "<center><h2>Belum dikunci</h2></center><br>";}
?> -->
<table width="100%" class="headingtext" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="11%">Nama</td>
    <td width="73%"><?=$this->dataPegawai->nama;?></td>
    <td width="16%" align="right" rowspan="4"><img src="upload/qrcode/<?=$this->imageQrCode;?>" class="qr" /></td>
  </tr>
  <tr>
    <td>NIP</td>
    <td><?=$this->dataPegawai->nip;?></td>
  </tr>
  <tr>
    <td>Instansi</td>
    <td><?=$this->dataPegawai->nama_instansi;?></td>
  </tr>
  <tr>
    <td>Jabatan</td>
    <td><?=$this->dataPegawai->nama_jenis_jabatan;?></td>
  </tr>
</table>
<br />
<table width="100%" class="cloth" cellspacing="0" cellpadding="0">
  <tr>
    <th width="9%" rowspan="3">TANGGAL</th>
    <th width="14%"  rowspan="3">JAM KERJA </th>
    <th rowspan="3">MASUK</th>
    <th rowspan="2" colspan='2'>TELAT MASUK </th>
    <th rowspan="3">PULANG</th>
    <th rowspan="2" colspan='2'>CEPAT PULANG </th>
    <th rowspan="2" colspan='2'>OVERTIME</th>
    <th colspan="4">JUMLAH LEMBUR </th>
    <th rowspan="3">KETERANGAN</th>
  </tr>
  <tr>
    <th  colspan='2'>SABTU</th>
    <th  colspan='2'>MINGGU</th>
  </tr>
  <tr>
    <th>JAM</th>
    <th>MENIT</th>
    <th>JAM</th>
    <th>MENIT</th>
     <th>JAM</th>
    <th>MENIT</th>
	 <th>JAM</th>
    <th>MENIT</th>
	 <th>JAM</th>
    <th>MENIT</th>
  </tr>
	<?php
		$telatMasuk 	= 0;
		$totPulangCepat = 0;
		$totLembur 		= 0;
		$totLemburMINGGU 		= 0;
		$totLemburSABTU 		= 0;
		$overTimeSemua		= 0;
		$sabtu		 	= 0;
		$minggu		 	= 0;

	foreach($this->dataLaporan as $data){
		if($data->datang_telat> 480){
			$telatMasukBenar = 480;
		}
		else{
			$telatMasukBenar = $data->datang_telat;
		}

		$datangTelat	=	$this->konversi_menit->hitung($telatMasukBenar);

		if ($data->pulang_cepat > 480) {
			$pulangCepat	=	$this->konversi_menit->hitung(480);
			$pulangCepatRaw = 480;
		}else{
			$pulangCepat	=	$this->konversi_menit->hitung($data->pulang_cepat);
			$pulangCepatRaw = $data->pulang_cepat;
		}

		// if ($data->lembur > 180)
		// {
		$arr_cek = array('DL','DK');
			if(in_array($data->jam_kerja,$arr_cek)) {
				$dataLembur		=	$this->konversi_menit->hitung($data->lembur);
				$dataLemburRaw	=	$data->lembur;
			}
			else if ($data->finger_pulang == NULL) {
				$dataLembur		=	0;
				$dataLemburRaw	=	0;
			}
			else{
				$dataLembur		=	$this->konversi_menit->hitung($data->lembur);
				$dataLemburRaw	=	$data->lembur;
			}
		// }
		/*else
		{
			$dataLembur		=	$this->konversi_menit->hitung($data->lembur);
			$dataLemburRaw	=	$data->lembur;
		}*/

	?>
		<tr>
		<td align="center"> <?php echo $data->tanggal_indo; ?> </td>
		<td align="center"><?php echo $data->jam_kerja; ?></td>
		<td align="center"><?php echo $data->finger_masuk_jam; ?></td>
		<td align="center"><?php echo $datangTelat['jam']; ?></td>
		<td align="center"><?php echo $datangTelat['menit']; ?></td>
		<td align="center"><?php echo $data->finger_pulang_jam; ?></td>
		<td align="center"><?php echo $pulangCepat['jam']; ?></td>
		<td align="center"><?php echo $pulangCepat['menit']; ?></td>

		<td align="center"><?php if($data->hari != 'SABTU' && $data->hari != 'MINGGU'){ echo $dataLembur['jam']; } else {echo "-";}?>	</td>
		<td align="center"><?php if($data->hari != 'SABTU' && $data->hari != 'MINGGU'){ echo $dataLembur['menit']; } else {echo "-";}?>	</td>


		<?php
		if($data->hari=='SABTU'){
		?>
			<td align="center"><?php echo $dataLembur['jam']; ?></td>
			<td align="center"><?php echo $dataLembur['menit']; ?></td>
		<?php
		}
		else{
		?>
		<td align="center">-</td>
		<td align="center">-</td>
		<?php
		}
		?>


		<?php
		if($data->hari=='MINGGU'){
		?>
			<td align="center"><?php echo $dataLembur['jam']; ?></td>
			<td align="center"><?php echo $dataLembur['menit']; ?></td>
		<?php
		}
		else{
		?>
			<td align="center">-</td>
			<td align="center">-</td>
		<?php
		}
		?>

		<td align="center"><?php echo $data->kode_masuk; ?></td>
		</tr>
		<?php

		$telatMasuk		+= $telatMasukBenar;
		$totPulangCepat	+= $pulangCepatRaw;

		if($data->hari!='MINGGU' && $data->hari!='SABTU'){

			$totLembur		+= $dataLemburRaw;
		}

		if($data->hari=='MINGGU' ){

			$totLemburMINGGU		+= $dataLemburRaw;
		}

		if( $data->hari=='SABTU'){

			$totLemburSABTU		+= $dataLemburRaw;
		}
	}

	//echo $telatMasuk;



		$totalTelatMasuk		=	$this->konversi_menit->hitung($telatMasuk);



		$totalPulangCepat		=	$this->konversi_menit->hitung($totPulangCepat);


		$totalLembur			=	$this->konversi_menit->hitung($totLembur);
		$totLemburSABTUArray	=	$this->konversi_menit->hitung($totLemburSABTU);
		$totLemburMINGGUArray	=	$this->konversi_menit->hitung($totLemburMINGGU);
	?>



	<tr class="bggrey" BORDER='0'>
		<td><strong>TOTAL</strong></td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td align="center"><?php echo $totalTelatMasuk['jam']; ?></td>
		<td align="center"><?php echo $totalTelatMasuk['menit']; ?></td>
		<td>&nbsp;</td>
		<td align="center"><?php echo $totalPulangCepat['jam']; ?></td>
		<td align="center"><?php echo $totalPulangCepat['menit']; ?></td>

		<td align="center"><?php echo $totalLembur['jam']; ?></td>
		<td align="center"><?php echo $totalLembur['menit']; ?></td>
		<td align="center"><?php echo $totLemburSABTUArray['jam']; ?></td>
		<td align="center"><?php echo $totLemburSABTUArray['menit']; ?></td>

		<td align="center"><?php echo $totLemburMINGGUArray['jam']; ?></td>
		<td align="center"><?php echo $totLemburMINGGUArray['menit']; ?></td>
		<td>&nbsp;</td>
	</tr>

</table>
<br />
<table class="legend" width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>Keterangan</td>
    <td>H</td>
    <td>:</td>
    <td>Hadir</td>
    <td>M</td>
    <td>:</td>
    <td>Mangkir/Alpha</td>
    <td>*</td>
    <td>:</td>
    <td>Bebas/Libur</td>
    <td>?</td>
    <td>:</td>
    <td>Ijin Per Jam </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>R</td>
    <td>:</td>
    <td>Hari Libur </td>
    <td>NA</td>
    <td>:</td>
    <td>Belum Aktif Absen </td>
    <td>J</td>
    <td>:</td>
    <td>Ijin Per Jam </td>
    <td>CAP</td>
    <td>:</td>
    <td>CUTI ALASAN PENTING </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>CH</td>
    <td>:</td>
    <td>CUTI BESAR </td>
    <td>CM</td>
    <td>:</td>
    <td>CUTI BERSALIN </td>
    <td>CS</td>
    <td>:</td>
    <td>CUTI SAKIT </td>
    <td>CT</td>
    <td>:</td>
    <td>CUTI TAHUNAN </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>DK</td>
    <td>:</td>
    <td>DIKLAT</td>
    <td>DL</td>
    <td>:</td>
    <td>DINAS LUAR </td>
    <td>DSP</td>
    <td>:</td>
    <td>PENUGASAN</td>
    <td>I</td>
    <td>:</td>
    <td>IJIN</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>LP</td>
    <td>:</td>
    <td>LEPAS DINAS </td>
    <td>SK</td>
    <td>:</td>
    <td>SAKIT</td>
    <td>TB</td>
    <td>:</td>
    <td>TUGAS</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>

<br>
<br>
<br>

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="title">
  <tr>
    <td width="75%"><sup style="font-size:9;"><?php #echo date('d-m-Y H:i') ?></sup></td>
    <td min-width="25%" align="center">
	</td>
  </tr>


</table>
<script>
	//window.print();
</script>
