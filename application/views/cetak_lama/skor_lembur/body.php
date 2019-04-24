<style>
  table, th, td {
    height: 20px;
  }
</style>
<table border="1" width="100%" style="border-collapse: collapse;font-size:8px;">
  <thead>
    <tr>
      <th>No</th>
      <th>Nama</th>
      <?php for($i=1;$i<=$hari;$i++) { ?>
      <th><?=$i?></th>
      <?php } ?>
      <th>Total</th>
      <th width="5%">Skor Lembur (%)</th>
    </tr>
  </thead>
  <tbody>
<?php
  $no = 1;
  foreach ($isi as $data) {
?>
    <tr>
      <td width="1.5%" align="center"><?=$no++?></td>
      <td><?=$data['nama']?></td>
      <?php for($i=1;$i<=$hari;$i++) { $angka = $i; if($i < 10) { $angka = "0".$angka; } ?>
      <td align="center" <?php if($data["approve_$angka"] == 'f') { ?>style="color:red;"<?php } ?>>&nbsp;<?=$data["lembur_$angka"]?>&nbsp;</td>
      <?php } ?>
      <td align="center"><?=$data['total']?></td>
      <td align="center"><?=$data['skor']?></td>
    </tr>
<?php
  }
?>
  </tbody>
</table>
