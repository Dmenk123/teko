<?php

$currentURL = current_url(); //for simple URL
$params = $_SERVER['QUERY_STRING']; //for parameters
//echo $fullURL = $currentURL . '?' . $params;

 ?>
<style>

body{
	background:url('logo_pemkot_watermark.png');
	background-position:center 100px;
	background-repeat:no-repeat;
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
	border:0.5px solid #000;
	font-size:10px;
	font-weight:normal;
}
table.cloth tr td{
	border:0.5px solid #000;
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

<style type="text/css" media="print">
  @page { size: landscape; }
</style>
<?php
$bulan = array(
			'01' => 'JANUARI',
			'02' => 'FEBRUARI',
			'03' => 'MARET',
			'04' => 'APRIL',
			'05' => 'MEI',
			'06' => 'JUNI',
			'07' => 'JULI',
			'08' => 'AGUSTUS',
			'09' => 'SEPTEMBER',
			'10' => 'OKTOBER',
			'11' => 'NOVEMBER',
			'12' => 'DESEMBER',
	);
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="10%"></td>
    <td width="70%">
		<table width='100%'>
			<tr>
				<td>PEMERINTAH KOTA SURABAYA</td>
				<td>Daftar : ABSEN UANG MAKAN KARYAWAN / WATI</td>
			</tr>
			<tr>
				<td><?php echo $this->dataInstansi->nama;?></td>
				<td>Bulan : <?php echo $bulan[$this->input->get('bulan')]." ".$this->input->get('tahun'); ?></td>
			</tr>
		</table>
	</td>
    <td width="20%"><img src="<?=base_url();?>upload/qrcode/<?=$this->imageQrCode;?>" class="qr" /></td>
  </tr>
</table>
<br />
<?php
if(!$this->sudahAda){echo "<center><h2>Belum dikunci</h2></center><br>";}
?>


<?php echo $this->dataLembur; ?>


<br>
<br>
<br>

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="title">
  <tr>
    <td width="75%"><sup style="text-align:right;"><?php echo date('d-m-Y H:i') ?></sup></td>
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
  
  
</table>

<script>
screen.orientation.lock('landscape');

window.print();
</script>
