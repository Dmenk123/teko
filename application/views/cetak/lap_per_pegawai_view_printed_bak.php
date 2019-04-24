<style>
body{
	background:url('<?=base_url();?>assets/img/logo_pemkot_watermark.png');
	background-position:center 100px;
	background-position-y: 160px;
	background-repeat:no-repeat;
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
}
table.cloth tr th{
	border:0.5px solid #000;
	font-size:10px;
	font-weight:normal;
}
table.cloth tr td{
	border:0.5px solid #000;
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
    <td width="16%" rowspan="4"><img src="<?=base_url();?>upload/qrcode/<?=$this->imageQrCode;?>" class="qr" /></td>
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
<table width="100%" class="cloth" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <th rowspan="3">TANGGAL</th>
    <th rowspan="3">JAM KERJA </th>
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
        $menit_telat_masuk = 0;
        $jam_telat_masuk = 0;
        $menit_cepat_pulang = 0;
        $jam_cepat_pulang = 0;
        $menit_overtime = 0;
        $jam_overtime = 0;
        $menit_lembur_sabtu = 0;
        $jam_lembur_sabtu = 0;
        $menit_lembur_minggu = 0;
        $jam_lembur_minggu = 0;

	foreach($this->dataLaporan as $data){
        $telat_masuk = json_decode($data->telat_masuk);
        $cepat_pulang = json_decode($data->cepat_pulang);
        $overtime = json_decode($data->overtime);
        $jumlah_lembur = json_decode($data->jumlah_lembur);

        /** COUNT TOTAL */
        if(is_numeric($telat_masuk->menit)) {
            $menit_telat_masuk += $telat_masuk->menit;
        }
        if(is_numeric($telat_masuk->jam)) {
            $jam_telat_masuk += $telat_masuk->jam;
        }

        if(is_numeric($cepat_pulang->menit)) {
            $menit_cepat_pulang += $cepat_pulang->menit;
        }
        if(is_numeric($cepat_pulang->jam)) {
            $jam_cepat_pulang += $cepat_pulang->jam;
        }

        if(isset($overtime->menit) and is_numeric($overtime->menit)) {
            $menit_overtime += $overtime->menit;
        }
        if(isset($overtime->jam) and is_numeric($overtime->jam)) {
            $jam_overtime += $overtime->jam;
        }

        if(isset($jumlah_lembur->sabtu->menit) and is_numeric($jumlah_lembur->sabtu->menit)) {
            $menit_lembur_sabtu += $jumlah_lembur->sabtu->menit;
        }
        if(isset($jumlah_lembur->sabtu->jam) and is_numeric($jumlah_lembur->sabtu->jam)) {
            $jam_lembur_sabtu += $jumlah_lembur->sabtu->jam;
        }

        if(isset($jumlah_lembur->minggu->menit) and is_numeric($jumlah_lembur->minggu->menit)) {
            $menit_lembur_minggu += $jumlah_lembur->minggu->menit;
        }
        if(isset($jumlah_lembur->minggu->jam) and is_numeric($jumlah_lembur->minggu->jam)) {
            $jam_lembur_minggu += $jumlah_lembur->minggu->jam;
        }
        #end
	?>
		<tr>
		<td align="center"> <?php echo date('d-m-Y', strtotime($data->tanggal)); ?> </td>
		<td align="center"><?php echo $data->jam_kerja; ?></td>
		<td align="center"><?php echo $data->masuk; ?></td>
		<td align="center"><?php echo $telat_masuk->jam; ?></td>
		<td align="center"><?php echo $telat_masuk->menit; ?></td>
		<td align="center"><?php echo $data->pulang; ?></td>
		<td align="center"><?php echo $cepat_pulang->jam; ?></td>
		<td align="center"><?php echo $cepat_pulang->menit; ?></td>

		<td align="center"><?php if(isset($overtime->jam)){ echo $overtime->jam; } else {echo "-";}?>	</td>
		<td align="center"><?php if(isset($overtime->menit)){ echo $overtime->menit; } else {echo "-";}?>	</td>

        <td align="center"><?php echo isset($jumlah_lembur->sabtu->jam) ? $jumlah_lembur->sabtu->jam : '-'; ?></td>
        <td align="center"><?php echo isset($jumlah_lembur->sabtu->menit) ? $jumlah_lembur->sabtu->menit : '-'; ?></td>

        <td align="center"><?php echo isset($jumlah_lembur->minggu->jam) ? $jumlah_lembur->minggu->jam : '-'; ?></td>
        <td align="center"><?php echo isset($jumlah_lembur->minggu->menit) ? $jumlah_lembur->minggu->menit : '-'; ?></td>


		<td align="center"><?php echo $data->keterangan; ?></td>
		</tr>
	<?php
    }
    ?>



	<tr class="bggrey" BORDER='0'>
		<td><strong>TOTAL</strong></td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td align="center"><?php echo $jam_telat_masuk + floor($menit_telat_masuk/60) ?></td>
		<td align="center"><?php echo $menit_telat_masuk % 60 ?></td>
		<td>&nbsp;</td>
		<td align="center"><?php echo $jam_cepat_pulang + floor($menit_cepat_pulang/60) ?></td>
		<td align="center"><?php echo $menit_cepat_pulang % 60 ?></td>

		<td align="center"><?php echo $jam_overtime + floor($menit_overtime/60) ?></td>
		<td align="center"><?php echo $menit_overtime % 60 ?></td>

		<td align="center"><?php echo $jam_lembur_sabtu + floor($menit_lembur_sabtu/60) ?></td>
		<td align="center"><?php echo $menit_lembur_sabtu % 60 ?></td>
		<td align="center"><?php echo $jam_lembur_minggu + floor($menit_lembur_minggu/60) ?></td>
		<td align="center"><?php echo $menit_lembur_minggu % 60 ?></td>
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
	window.print();
</script>
