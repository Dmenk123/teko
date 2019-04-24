<style>
  table, th, td {
    height: 20px;
  }
  .border th, .border td {
    border: 1px solid black;
  }
</style>
<table border="0" width="100%" style="border-collapse: collapse;font-size:10px;">
  <thead style="font-size:12px;">
    <tr>
      <td width="11%" colspan="2">&nbsp;<img src="<?=base_url()?>assets/img/logo_clean.png" width="95" height="100"></td>
      <td width="73%" align="center" <?php if(($this->input->get("bulan") == 12 && $this->input->get("tahun") == 2018) || $this->input->get("tahun") == 2019) { ?> colspan="36" <?php } else { ?> colspan="34" <?php } ?>>PEMERINTAH KOTA SURABAYA<br />
        LAPORAN SKOR KEHADIRAN<br />
        <br />
        <?=$tgl_mulai?> s/d <?=$tgl_hingga?> <br />
        <br />
        SKPD : <?=$instansi->nama?></td>
      <td width="16%" align="right" colspan="3"><img src="<?=base_url()?>upload/qrcode/<?=$this->imageQrCode;?>" class="qr" height="80" /></td>
    </tr>
    <tr class="border">
      <th rowspan="4">No</th>
      <th rowspan="4">Nama</th>
      <th rowspan="4">NIP</th>
      <th rowspan="4">Gol</th>
      <th rowspan="4">Jabatan</th>
      <?php if(($this->input->get("bulan") == 12 && $this->input->get("tahun") == 2018) || $this->input->get("tahun") == 2019) { ?>
      <th colspan="12">Keterlambatan</th>
      <?php } else { ?>
      <th colspan="10">Keterlambatan</th>
      <?php } ?>
      <th colspan="10">Pulang Cepat</th>
      <th colspan="4">Cuti</th>
      <th colspan="4">Tidak Hadir</th>
      <th rowspan="4">Skor Total</th>
      <th rowspan="4">Hari Kerja</th>
      <th rowspan="4">Jumlah Hadir</th>
      <th rowspan="4">Dinas Luar / Diklat</th>
      <th rowspan="4">Cuti Tahunan</th>
      <th rowspan="4">Skor TPP</th>
    </tr>
    <tr class="border">
      <?php if(($this->input->get("bulan") == 12 && $this->input->get("tahun") == 2018) || $this->input->get("tahun") == 2019) { ?>
      <th colspan="2"><=<br/>5 menit</th>
      <?php } ?>
      <th colspan="2">
      <?php if(($this->input->get("bulan") == 12 && $this->input->get("tahun") == 2018) || $this->input->get("tahun") == 2019) { ?>
          > 5 menit <br/>s/d<br/> 15 menit
      <?php } else { ?>
          <=<br/>15 menit
      <?php } ?>
      </th>
      <th colspan="2">> 15 Menit s/d 1 Jam</th>
      <th colspan="2">> 1 jam s/d 2 Jam</th>
      <th colspan="2">> 2 jam s/d 3 Jam</th>
      <th colspan="2">Lebih Dari 3 Jam</th>
      <th colspan="2"><= 15 Menit</th>
      <th colspan="2">> 15 Menit s/d 1 Jam</th>
      <th colspan="2">> 1 jam s/d 2 Jam</th>
      <th colspan="2">> 2 jam s/d 3 Jam</th>
      <th colspan="2">Lebih Dari 3 Jam</th>
      <th colspan="2">Sakit</th>
      <th colspan="2">Cuti Besar, Cuti Alasan Penting, Cuti Bersalin</th>
      <th colspan="2">Dengan Keterangan Sah</th>
      <th colspan="2">Tanpa Keterangan Sah</th>
    </tr>
    <tr class="border">
      <th colspan="2">Skor 1</th>
      <th colspan="2">Skor 2</th>
      <th colspan="2">Skor 3</th>
      <th colspan="2">Skor 4</th>
      <th colspan="2">Skor 5</th>
      <th colspan="2">Skor 6</th>
      <th colspan="2">Skor 7</th>
      <th colspan="2">Skor 8</th>
      <th colspan="2">Skor 9</th>
      <th colspan="2">Skor 10</th>
      <th colspan="2">Skor 11</th>
      <th colspan="2">Skor 12</th>
      <th colspan="2">Skor 13</th>
      <th colspan="2">Skor 14</th>
      <?php if(($this->input->get("bulan") == 12 && $this->input->get("tahun") == 2018) || $this->input->get("tahun") == 2019) { ?>
      <th colspan="2">Skor 15</th>
      <?php } ?>
    </tr>
    <tr class="border">
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
  <tbody class="border">
<?php
  $no = 1;
  foreach ($isi as $data) {
?>
  <tr>
    <td width="2.5%" align="center"><?=$no++?></td>
    <td width="10%"><?=$data[1]?></td>
    <td width="7%">&nbsp;<?=$data[2]?>&nbsp;</td>
    <td width="2%">&nbsp;<?=$data[3]?>&nbsp;</td>
    <td width="15%"><?=$data[4]?></td>
    <td width="2.3%" align="center"><?=$data[5]?></td>
    <td width="2.3%" align="center"><?=$data[6]?></td>
    <td width="2.3%" align="center"><?=$data[7]?></td>
    <td width="2.3%" align="center"><?=$data[8]?></td>
    <td width="2.3%" align="center"><?=$data[9]?></td>
    <td width="2.3%" align="center"><?=$data[10]?></td>
    <td width="2.3%" align="center"><?=$data[11]?></td>
    <td width="2.3%" align="center"><?=$data[12]?></td>
    <td width="2.3%" align="center"><?=$data[13]?></td>
    <td width="2.3%" align="center"><?=$data[14]?></td>
    <td width="2.3%" align="center"><?=$data[15]?></td>
    <td width="2.3%" align="center"><?=$data[16]?></td>
    <td width="2.3%" align="center"><?=$data[17]?></td>
    <td width="2.3%" align="center"><?=$data[18]?></td>
    <td width="2.3%" align="center"><?=$data[19]?></td>
    <td width="2.3%" align="center"><?=$data[20]?></td>
    <td width="2.3%" align="center"><?=$data[21]?></td>
    <td width="2.3%" align="center"><?=$data[22]?></td>
    <td width="2.3%" align="center"><?=$data[23]?></td>
    <td width="2.3%" align="center"><?=$data[24]?></td>
    <td width="2.3%" align="center"><?=$data[25]?></td>
    <td width="2.3%" align="center"><?=$data[26]?></td>
    <td width="2.3%" align="center"><?=$data[27]?></td>
    <td width="2.3%" align="center"><?=$data[28]?></td>
    <td width="2.3%" align="center"><?=$data[29]?></td>
    <td width="2.3%" align="center"><?=$data[30]?></td>
    <td width="2.3%" align="center"><?=$data[31]?></td>
    <td width="2.3%" align="center"><?=$data[32]?></td>
    <?php if(($this->input->get("bulan") == 12 && $this->input->get("tahun") == 2018) || $this->input->get("tahun") == 2019) { ?>
    <td width="2.3%" align="center"><?=$data[33]?></td>
    <td width="2.3%" align="center"><?=$data[34]?></td>
    <td width="2.3%" align="center"><?=$data[35]?></td>
    <td width="2.3%" align="center"><?=$data[36]?></td>
    <td width="3%" align="center"><?=$data[37]?></td>
    <td width="2.3%" align="center"><?=$data[38]?></td>
    <td width="3.5%" align="center"><?=$data[39]?></td>
    <td width="2.3%" align="center"><?=$data[40]?></td>
    <?php } else { ?>
    <td width="2.3%" align="center"><?=$data[33]?></td>
    <td width="2.3%" align="center"><?=$data[34]?></td>
    <td width="3%" align="center"><?=$data[35]?></td>
    <td width="2.3%" align="center"><?=$data[36]?></td>
    <td width="3.5%" align="center"><?=$data[37]?></td>
    <td width="2.3%" align="center"><?=$data[38]?></td>
    <?php } ?>
  </tr>
<?php
  }
?>
  </tbody>
</table>
