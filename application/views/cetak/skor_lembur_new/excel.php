<style>
  table, th, td {
    height: 20px;
  }
  .border th, .border td {
    border: 1px solid black;
  }
</style>
<table border="1" width="100%" style="border-collapse: collapse;font-size:6px;">
  <thead>
    <tr>
      <td width="11%" colspan="2">&nbsp;<img src="<?=base_url()?>assets/img/logo_clean.png" width="95" height="100"></td>
      <td width="73%" align="center" colspan="<?=($hari+1)?>">PEMERINTAH KOTA SURABAYA<br />
        LAPORAN SKOR LEMBUR<br />
        BULAN : <?=$bulan?>
        <?=$tahun?><br />
        SKPD : <?=$instansi->nama?> <br />
      <td width="16%" align="right" colspan="3"><img src="<?=base_url()?>upload/qrcode/<?=$this->imageQrCode;?>" class="qr" height="80" /></td>
    </tr>
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
          <td <?php if($j==1) { ?>align="left"<?php } else { ?>align="center"<?php } ?> style="color:<?=$warna[$i][$j]?>;"><?php if($j==1 || $j==2) { ?>&nbsp;<?php }?><?= $isi[$i][$j] ?><?php if($j==1 || $j==2) { ?>&nbsp;<?php }?></td>
  <?php
      }
  ?>
      </tr>
  <?php
  }
  ?>
  </tbody>
</table>
