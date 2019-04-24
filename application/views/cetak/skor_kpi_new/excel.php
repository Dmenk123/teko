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
      <td width="73%" align="center" colspan="20">PEMERINTAH KOTA SURABAYA<br />
        LAPORAN SKOR KEHADIRAN TENAGA KONTRAK<br />
        <br />
        <?=$tgl_mulai?> s/d <?=$tgl_hingga?> <br />
        <br />
        SKPD : <?=$instansi->nama?></td>
      <td width="16%" align="right" colspan="3"><img src="<?=base_url()?>upload/qrcode/<?=$this->imageQrCode;?>" class="qr" height="80" /></td>
    </tr>
    <tr class="border">
      <th rowspan="4">NO.</th>
      <th rowspan="4">NAMA</th>
      <th rowspan="4">NIP</th>
      <th rowspan="4">Unit Organisasi</th>

      <th colspan="8">KETERLAMBATAN</th>
      <th colspan="6">TIDAK HADIR</th>

      <th rowspan="4">Potongan<br />
      Total</th>
      <th rowspan="4">Dinas Luar / <br />
      Diklat</th>
      <th rowspan="4">izin / <br />
      Lainnya </th>
      <th rowspan="4">Hari <br />
      Kerja</th>
      <th rowspan="4">Jumlah <br />
      Hadir</th>
      <th rowspan="4">Skor <br />
      Total </th>
    </tr>
    <tr class="border">
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
    <tr class="border">
      <th colspan="2">Skor 1</th>
      <th colspan="2">Skor 2</th>
      <th colspan="2">Skor 3</th>
      <th colspan="2">Skor 4</th>
      <th colspan="2">Skor 5</th>
      <th colspan="2">Skor 6</th>
      <th colspan="2">Skor 7</th>
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
    </tr>
  </thead>
  <tbody class="border">
  <?php
  $no = 1;
  foreach ($isi as $data) {
  ?>
  <tr>
      <td width="2.5%" align="center"><?=$no++?></td>
      <td width="17%"><?=$data[1]?></td>
      <td width="5%">&nbsp;<?=$data[2]?>&nbsp;</td>
      <td width="8%">&nbsp;<?=$data[3]?>&nbsp;</td>
      <td width="2.3%"><?=$data[4]?></td>
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
    </tr>
  <?php
  }
  ?>
  </tbody>
</table>
