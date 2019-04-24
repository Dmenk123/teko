

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
  border:0.5px solid #000;
  font-size:11px;
  padding:2px 1px;
  font-weight:normal;
}
table.cloth tr td{
  border:0.5px solid #000;
  font-size:11px;
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
    <td width="73%" align="center">PEMERINTAH KOTA SURABAYA<br />
      LAPORAN REKAPITULASI PER PERIODE KEHADIRAN PEGAWAI <br />
      <br />
      <?php echo  date('d-m-Y', strtotime($this->tgl_pertama));?> s/d <?php echo date('d-m-Y', strtotime($this->tgl_terakhir));?> <br />
      <br />
      <?php echo $this->dataInstansi->nama;?> </td>
    <td width="16%"><img src="<?=base_url();?>upload/qrcode/<?=$this->imageQrCode;?>" class="qr" /></td>
  </tr>
</table>
<br />
<!-- <?php
if(!$this->sudahAda){echo "<center><h2>Belum dikunci</h2></center><br>";}
?> -->
<table class="cloth" width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <th rowspan="3">NO</th>
    <th rowspan="3">NAMA</th>
    <th rowspan="3">NIP</th>
    <th rowspan="3">Jabatan</th>
    <th rowspan="3">KERJA</th>
    <th colspan="3">JUMLAH HARI </th>
    <th colspan="2">OVERTIME</th>
    <th colspan="4">JUMLAH LEMBUR </th>
    <th colspan="13">KETERANGAN</th>
  </tr>
  <tr>
    <th rowspan="2">HADIR</th>
    <th rowspan="2">TELAT</th>
    <th rowspan="2">PULANG CEPAT </th>
    <th rowspan="2">JAM</th>
    <th rowspan="2">MENIT</th>
    <th colspan="2">SABTU</th>
    <th colspan="2">MINGGU</th>
    <th rowspan="2">M</th>
    <th rowspan="2">CH</th>
    <th rowspan="2">CM</th>
    <th rowspan="2">CT</th>
    <th rowspan="2">CAP</th>
    <th rowspan="2">DK</th>
    <th rowspan="2">DL</th>
    <th rowspan="2">I</th>
    <th rowspan="2">LP</th>
    <th rowspan="2">MPP</th>
    <th rowspan="2">SK</th>
    <th rowspan="2">TB</th>
    <th rowspan="2">UFT</th>
  </tr>
  <tr>
    <th colspan="">JAM</th>
    <th colspan="">MENIT</th>
    <th colspan="">JAM</th>
    <th colspan="">MENIT</th>
  </tr>
  <?php echo $dataTable;  ?>
</table>

<br>
<br>
<br>

<!-- <table width="100%" border="0" cellspacing="0" cellpadding="0" class="title">
  <tr>
    <td width="75%">&nbsp;</td>
    <td min-width="25%" align="center">
        <?php echo $this->dataTtd->nama_instansi;?>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <u><?php echo $this->dataTtd->nama;?></u><br>
        <?php echo $this->dataTtd->nama_jenis_jabatan;?><br>
        <?php echo $this->dataTtd->nip;?><br>
    </td>
  </tr>
</table> -->

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="title">
  <tr>
    <td width="75%"><sup style="font-size:9;"><?php #echo date('d-m-Y H:i') ?></sup></td>
    <td min-width="25%" align="center"></td>
  </tr>
</table>


<script>
screen.orientation.lock('landscape');

window.print();
</script>
