<?php var_dump($aa); ?>

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
table {
    /* collapsed, because the bottom shadow on thead tr is hidden otherwise */
    border-collapse: collapse;
    padding:10px;
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
	margin:2px;
	outline:none;
}
table.cloth tr th{
	border:0.5px solid #000;
	font-size:6.5px;
  margin:2px;
	font-weight:normal;
}
table.cloth tr td{
	border:0.5px solid #000;
	font-size:6.5px;
  padding:20px;
}
tbody tr td {
    text-align: center;
    padding: 10px;
    margin-left : 90px;
}

.table-with-margin table {
    border-spacing: 0 1em !important;
    border-collapse: separate;
}
.margin{
  margin:2px;
}
.qr{
	width:80px;
}

</style>

<table width="100%" class="headingtext" cellspacing="0" cellpadding="0">
  <tr>
    <td width="11%">&nbsp;<img src="assets/img/logo_clean.png" width="95"></td>
    <td width="73%" align="center">PEMERINTAH KOTA SURABAYA<br />
      LAPORAN SKOR KEHADIRAN TENAGA KONTRAK<br />
      <br />
      <?php echo  date('d-m-Y', strtotime($this->tgl_pertama));?> s/d <?php echo date('d-m-Y', strtotime($this->tgl_terakhir));?> <br />
      <br />
      SKPD : <?php echo $this->dataInstansi->nama;?></td>
    <td width="16%" align="right"><img src="upload/qrcode/<?=$this->imageQrCode;?>" class="qr" /></td>
  </tr>
</table>
<!-- <?php
if(!$this->sudahAda){echo "<center><h2>Belum dikunci</h2></center><br>";}
?> -->
<table width="100%" class="cloth" cellspacing="0" cellpadding="0">
<thead>
  <tr>
    <th rowspan="4">NO.</th>
    <th rowspan="4">NAMA</th>
    <th rowspan="4">NIP</th>
    <th rowspan="4">JABATAN</th>

    <th colspan="8">KETERLAMBATAN</th>
    <th colspan="6">TIDAK HADIR</th>

    <th rowspan="4">Potongan<br />
    Total</th>
    <th rowspan="4">Dinas Luar / <br />
    Diklat</th>
    <th rowspan="4">Izin <br />
    Cuti </th>
    <th rowspan="4">izin / <br />
    Lainnya </th>
    <th rowspan="4">Hari <br />
    Kerja</th>
    <th rowspan="4">Jumlah <br />
    Hadir</th>
    <th rowspan="4">Skor <br />
    Total </th>
  </tr>
  <tr>
    <th colspan="2">
        &lt;=15 <br />menit 
    </th>
    <th colspan="2">
        &gt; 15 menit <br />
        s/d 1 jam 
    </th>
    <th colspan="2">
        &gt; 1 jam <br />
        s/d 2 jam 
    </th>
    <th colspan="2">
        &gt; 2 jam
    </th>
    <!-- tidak hadir -->
    <th colspan="2">Sakit</th>
    <th colspan="2">Dengan<br />
    Keterangan Sah</th>
    <th colspan="2">Tanpa <br />
    Keterangan Sah</th>

    
  </tr>
  <tr>
    <th colspan="2">Skor 1 </th>
    <th colspan="2">Skor 2 </th>
    <th colspan="2">Skor 3 </th>
    <th colspan="2">Skor 4 </th>
    <th colspan="2">Skor 5 </th>
    <th colspan="2">Skor 6 </th>
    <th colspan="2">Skor 7 </th>
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
  </tr>
  </thead>
  <tbody>
  <?php  echo $dataTable; ?>
  </tbody>
</table>



<br>
<br>
<br>

<!-- <table width="100%" border="0" cellspacing="0" cellpadding="0" class="title">
  <tr>
    <td width="75%">&nbsp;</td>
    <td min-width="25%" align="center">
        <?php echo $dataTtd->nama_instansi;?>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <u><?php echo $dataTtd->nama;?></u><br>
        <?php echo $dataTtd->nama_jenis_jabatan;?><br>
        <?php echo $dataTtd->nip;?><br>
    </td>
  </tr>
</table>
 -->
<table width="100%" cellspacing="0" cellpadding="0" class="title">
  <tr>
    <td width="75%"><sup style="font-size:9;"><?php #echo date('d-m-Y H:i') ?></sup></td>
    <td min-width="25%" align="center">
	</td>
  </tr>


</table>

<script>
screen.orientation.lock('landscape');

// window.print();
</script>
