

<style>
body{

	background-position:center 100px;
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
.bggrey td{
	background:#E5E5E5;
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
	border:0.3px solid #000;
	font-size:6.5px;
	padding:5px 1px;
	font-weight:normal;
}
table.cloth tr td{
	border:0.3px solid #000;
	font-size:6.5px;
	padding:2px;
}
.qr{
	width:80px;
}

</style>

<style type="text/css" media="print">
  @page { size: landscape; }
</style>
<table width="100%" class="headingtext" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="11%">&nbsp;</td>
    <td width="73%" align="center">PEMERINTAH KOTA SURABAYA<br />
      LAPORAN SKOR KEHADIRAN<br />
      <br />
      <?php echo  date('d-m-Y', strtotime($this->tgl_pertama));?> s/d <?php echo date('d-m-Y', strtotime($this->tgl_terakhir));?> <br />
      <br />
      SKPD : <?php echo $this->dataInstansi->nama;?></td>
    <td width="16%"><img src="<?=base_url();?>upload/qrcode/<?=$this->imageQrCode;?>" class="qr" /></td>
  </tr>
</table>
<?php
if(!$this->sudahAda){echo "<center><h2>Belum dikunci</h2></center><br>";}
?>
<table width="100%" class="cloth" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <th rowspan="4">NO.</th>
    <th rowspan="4">NAMA</th>
    <th rowspan="4">NIP</th>
    <th rowspan="4">GOL</th>
    <th rowspan="4">JABATAN</th>
    <th colspan="10">KETERLAMBATAN</th>
    <th colspan="10">PULANG CEPAT </th>
    <th colspan="4">CUTI</th>
    <th colspan="4">Tidak Hadir </th>
    <th rowspan="4">Skor <br />
    Total </th>
    <th rowspan="4">Hari <br />
    Kerja</th>
    <th rowspan="4">Jumlah <br />
    Hadir </th>
    <th rowspan="4">Dinas Luar / <br />
    Diklat </th>
    <th rowspan="4">Cuti <br />
    Tahunan </th>
    <th rowspan="4">Skor <br />
    TPP </th>
  </tr>
  <tr>
    <th colspan="2">&lt;=15 <br />
    menit </th>
    <th colspan="2">&gt; 15 menit<br />
    s/d 1 jam </th>
    <th colspan="2">&gt; 1 jam <br />
    s/d 2 jam </th>
    <th colspan="2">&gt; 2 jam <br />
    s/d 3 jam </th>
    <th colspan="2">lebih dari <br />
    3 jam </th>
    <th colspan="2">&lt;=15 <br />
    menit </th>
    <th colspan="2">&gt; 15 menit <br />
    s/d 1 jam </th>
    <th colspan="2">&gt; 1 jam <br />
    s/d 2 jam </th>
    <th colspan="2">&gt; 2 jam<br />
    s/d 3 jam </th>
    <th colspan="2">lebih dari <br />
    3 jam </th>
    <th colspan="2">Sakit</th>
    <th colspan="2">Cuti Besar,<br />
    Cuti alasan <br />
    penting, cuti bersalin </th>
    <th colspan="2">Dengan <br />
    Keterangan <br />
    Sah </th>
    <th colspan="2">Tanpa <br />
    Keterangan <br />
    Sah </th>
  </tr>
  <tr>
    <th colspan="2">Skor 1 </th>
    <th colspan="2">Skor 2 </th>
    <th colspan="2">Skor 3 </th>
    <th colspan="2">Skor 4 </th>
    <th colspan="2">Skor 5 </th>
    <th colspan="2">Skor 6 </th>
    <th colspan="2">Skor 7 </th>
    <th colspan="2">Skor 8 </th>
    <th colspan="2">Skor 9 </th>
    <th colspan="2">Skor 10 </th>
    <th colspan="2">Skor 11 </th>
    <th colspan="2">Skor 12 </th>
    <th colspan="2">Skor 13</th>
    <th colspan="2">Skor 14</th>
  </tr>
  <tr>
    <th>Frek</th>
    <th>Skor</th>
    <th>Frek</th>
    <th>Skor</th>
    <th>Frek</th>
    <th>Skor</th>
    <th>Frek</th>
    <th>Skor</th>
    <th>Frek</th>
    <th>Skor</th>
    <th>Frek</th>
    <th>Skor</th>
    <th>Frek</th>
    <th>Skor</th>
    <th>Frek</th>
    <th>Skor</th>
    <th>Frek</th>
    <th>Skor</th>
    <th>Frek</th>
    <th>Skor</th>
    <th>Frek</th>
    <th>Skor</th>
    <th>Frek</th>
    <th>Skor</th>
    <th>Frek</th>
    <th>Skor</th>
    <th>Frek</th>
    <th>Skor</th>
  </tr>
  <?php  echo $this->dataTable; ?>
</table>



<br>
<br>
<br>

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="title">
  <tr>
    <td width="75%"><sup style="font-size:9;"><?php echo date('d-m-Y H:i') ?></sup></td>
    <td min-width="25%" align="center">
	</td>
  </tr>


</table>

<script>
screen.orientation.lock('landscape');

window.print();
</script>
