<?php

$currentURL = current_url(); //for simple URL
$params = $_SERVER['QUERY_STRING']; //for parameters
//echo $fullURL = $currentURL . '?' . $params;

 ?>
<style>

body{
	background:url('assets/img/logo_pemkot_watermark2.png');
	background-position:center 100px;
	background-repeat:no-repeat;
	height: 1000px;
}
.title{
	font-size:13px;
	font-family:Arial, Helvetica, sans-serif;
}
table.cloth{
	font-family:Arial, Helvetica, sans-serif;
	border-collapse:collapse;
	padding:0px;
	outline:none;
}
table.cloth tr th{
	text-align:center;
	border:1px solid #000;
	font-size:10px;
	font-weight:normal;
}
table.cloth tr td{
	text-align:center;
	border:1px solid #000;
	font-size:8px;
	padding:2px;
	vertical-align:top;
}
.qr {	width:80px;
}
.cuilik{
	font-size:8px;
}
</style>


<table width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td width="5%">&nbsp;<img src="assets/img/logo_clean.png" width="95"></td>
    <td width="85%"><div align="center" class="title"> <br><br>PEMERINTAH KOTA SURABAYA<br />
      LAPORAN SKOR LEMBUR<br />
      BULAN : <?php echo $bulan;?>
      <?php echo $this->input->get('tahun');?><br />
      SKPD : <?php echo $this->dataInstansi->nama;?> <br />
    </div></td>
    
    <td width="10%"><img src="upload/qrcode/<?=$this->imageQrCode;?>" class="qr" /></td>
  </tr>
</table>
<br />
<!-- <?php
if(!$this->sudahAda){echo "<center><h2>Belum dikunci</h2></center><br>";}
?> -->


<?php echo $dataLembur; ?>

<!-- <table width="100%" border="0" cellspacing="0" cellpadding="0" class="title">
  <tr>
    <td width="75%">&nbsp;</td>
    <td min-width="25%" align="center">
		<?php echo $this->dataInstansi->instansi_tdd;?>
		<br>
		<br>
		<br>
		<br>
		<br>
		<br>
		<u><?php echo $this->dataInstansi->nama_tdd;?></u><br>
		<?php echo $this->dataInstansi->pangkat_tdd;?><br>
		<?php echo $this->dataInstansi->nip_tdd;?><br>
	</td>
  </tr>
</table> -->
<br>
<br>
<br>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="title">
  <tr>
    <td width="75%"><sup style="font-size:9;"><?php #echo date('d-m-Y H:i') ?></sup></td>
    <td min-width="25%" align="center">
	</td>
  </tr>

<script>
screen.orientation.lock('landscape');

//window.print();
</script>