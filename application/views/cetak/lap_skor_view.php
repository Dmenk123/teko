

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
      LAPORAN SKOR KEHADIRAN<br />
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
    <th width="2%" rowspan="4">NO.</th>
    <th width="10%"  rowspan="4">NAMA</th>
    <th width="6%" rowspan="4">NIP</th>
    <th width="2%" rowspan="4">GOL</th>
    <th width="9%" rowspan="4">JABATAN</th>
    <?php if(($this->input->get("bulan") == 12 && $this->input->get("tahun") == 2018) || $this->input->get("tahun") == 2019) { ?>
    <th colspan="12">KETERLAMBATAN</th>
    <?php } else { ?>
    <th colspan="10">KETERLAMBATAN</th>
    <?php } ?>
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
    <?php if(($this->input->get("bulan") == 12 && $this->input->get("tahun") == 2018) || $this->input->get("tahun") == 2019) { ?>
    <th colspan="2">&lt;=5</th>
    <?php } ?>
    <th colspan="2">
    <?php if(($this->input->get("bulan") == 12 && $this->input->get("tahun") == 2018) || $this->input->get("tahun") == 2019) { ?>
        &gt; 5 menit<br /> s/d &lt;=15 menit
    <?php } else { ?>
        &lt;=15 <br />menit 
    <?php } ?>
    </th>
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
    <?php if(($this->input->get("bulan") == 12 && $this->input->get("tahun") == 2018) || $this->input->get("tahun") == 2019) { ?>
    <th colspan="2">Skor 15</th>
    <?php } ?>
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
    <?php if(($this->input->get("bulan") == 12 && $this->input->get("tahun") == 2018) || $this->input->get("tahun") == 2019) { ?>
    <th>Frek</th>
    <th>Skor</th>
    <?php } ?>
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
//screen.orientation.lock('landscape');

// window.print();
</script>
