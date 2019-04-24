<style>
  table, th, td {
    height: 20px;
  }
</style>
<table border="1" width="100%" style="border-collapse: collapse;font-size:8px;">
  <thead>
    <tr>
      <th rowspan="4">No</th>
      <th rowspan="4">Nama</th>
      <th rowspan="4">NIP</th>
      <th rowspan="4">Gol</th>
      <th rowspan="4">Jabatan</th>
      <th colspan="10">Keterlambatan</th>
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
    <tr>
      <th colspan="2"><= 15 Menit</th>
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
    <tr>
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
  </thead>
  <tbody>
<?php
  $no = 1;
  foreach ($isi as $data) {
?>
    <tr>
      <td width="1.5%" align="center"><?=$no++?></td>
      <td width="6%"><?=$data['nama']?></td>
      <td width="7.5%">&nbsp;<?=$data['nip']?>&nbsp;</td>
      <td width="2%">&nbsp;<?=$data['golongan']?>&nbsp;</td>
      <td width="4.8%"><?=$data['jabatan']?></td>
      <td width="2.3%" align="center"><?=$data['k_freq1']?></td>
      <td width="2.3%" align="center"><?=$data['k_skor1']?></td>
      <td width="2.3%" align="center"><?=$data['k_freq2']?></td>
      <td width="2.3%" align="center"><?=$data['k_skor2']?></td>
      <td width="2.3%" align="center"><?=$data['k_freq3']?></td>
      <td width="2.3%" align="center"><?=$data['k_skor3']?></td>
      <td width="2.3%" align="center"><?=$data['k_freq4']?></td>
      <td width="2.3%" align="center"><?=$data['k_skor4']?></td>
      <td width="2.3%" align="center"><?=$data['k_freq5']?></td>
      <td width="2.3%" align="center"><?=$data['k_skor5']?></td>
      <td width="2.3%" align="center"><?=$data['p_freq1']?></td>
      <td width="2.3%" align="center"><?=$data['p_skor1']?></td>
      <td width="2.3%" align="center"><?=$data['p_freq2']?></td>
      <td width="2.3%" align="center"><?=$data['p_skor2']?></td>
      <td width="2.3%" align="center"><?=$data['p_freq3']?></td>
      <td width="2.3%" align="center"><?=$data['p_skor3']?></td>
      <td width="2.3%" align="center"><?=$data['p_freq4']?></td>
      <td width="2.3%" align="center"><?=$data['p_skor4']?></td>
      <td width="2.3%" align="center"><?=$data['p_freq5']?></td>
      <td width="2.3%" align="center"><?=$data['p_skor5']?></td>
      <td width="2.3%" align="center"><?=$data['c_s_freq']?></td>
      <td width="2.3%" align="center"><?=$data['c_s_skor']?></td>
      <td width="2.3%" align="center"><?=$data['c_hms_freq']?></td>
      <td width="2.3%" align="center"><?=$data['c_hms_skor']?></td>
      <td width="2.3%" align="center"><?=$data['th_s_freq']?></td>
      <td width="2.3%" align="center"><?=$data['th_s_skor']?></td>
      <td width="2.3%" align="center"><?=$data['th_ts_freq']?></td>
      <td width="2.3%" align="center"><?=$data['th_ts_skor']?></td>
      <td width="2.3%" align="center"><?=($data['k_skor1'] + $data['k_skor2'] + $data['k_skor3'] + $data['k_skor4'] + $data['k_skor5'] + $data['p_skor1'] + $data['p_skor2'] + $data['p_skor3'] + $data['p_skor4'] + $data['p_skor5'] + $data['c_s_skor'] + $data['c_hms_skor'] + $data['th_s_skor'] + $data['th_ts_skor'])?></td>
      <td width="2.3%" align="center"><?=$data['jml_hari']?></td>
      <td width="2.3%" align="center"><?=$data['jml_hadir']?></td>
      <td width="2.3%" align="center"><?=$data['dl_freq']?></td>
      <td width="2.3%" align="center"><?=$data['ct_freq']?></td>
      <td width="2.3%" align="center"><?=(100 - (1400 - ($data['k_skor1'] + $data['k_skor2'] + $data['k_skor3'] + $data['k_skor4'] + $data['k_skor5'] + $data['p_skor1'] + $data['p_skor2'] + $data['p_skor3'] + $data['p_skor4'] + $data['p_skor5'] + $data['c_s_skor'] + $data['c_hms_skor'] + $data['th_s_skor'] + $data['th_ts_skor'])))?></td>
    </tr>
<?php
  }
?>
  </tbody>
</table>
