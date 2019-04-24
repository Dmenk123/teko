<style>
  table, th, td {
    height: 15px;
  }
</style>
<table width="100%" class="headingtext" cellspacing="0" cellpadding="0" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; padding:3px;">
  <tr>
    <td width="11%">&nbsp;<img src="<?=base_url()?>assets/img/logo_clean.png" width="95"></td>
    <td width="73%" align="center">PEMERINTAH KOTA SURABAYA<br />
      LAPORAN SKOR LEMBUR<br />
      BULAN : <?=$bulan?>
      <?=$tahun?><br />
      SKPD : <?=$instansi->nama?> <br />
    <td width="16%" align="right"><img src="<?=base_url()?>upload/qrcode/<?=$this->imageQrCode;?>" class="qr" height="80" /></td>
  </tr>
</table>

<table border="1" width="100%" style="border-collapse: collapse;font-size:6px;">
  <thead>
    <tr>
      <th width="2%">No</th>
      <th>Nama</th>
      <th width="8%">NIP</th>
      <?php for($i=1;$i<=$hari;$i++) { ?>
      <th width="2%"><?=$i?></th>
      <?php } ?>
      <th>Total</th>
      <th width="5%">Skor Lembur (%)</th>
    </tr>
  </thead>
  <tbody>
  <?php
  for ($i = 0; $i < count($isi); $i++) {
  ?>
      <tr>
  <?php
      for($j=0;$j< count($isi[$i]); $j++) {
  ?>
          <td <?php if($j==1) { ?>align="left"<?php } else { ?>align="center"<?php } ?> style="color:<?=$warna[$i][$j]?>;"><?php if($j==1) { ?>&nbsp;<?php }?><?= $isi[$i][$j] ?></td>
  <?php
      }
  ?>
      </tr>
  <?php
  }
  ?>
  </tbody>
</table>
