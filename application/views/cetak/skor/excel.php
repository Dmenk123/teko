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
      <th align="center" colspan="39" style="font-size:13px;"><strong>PEMERINTAH KOTA SURABAYA<br/>LAPORAN SKOR KEHADIRAN</strong></th>
    </tr>
    <tr>
      <td align="center" colspan="39"><?=$tgl_mulai?> s/d <?=$tgl_hingga?></td>
      <!-- <td colspan="13" rowspan="2" width="20%" class="barcodecell" colspan="5"><barcode code="<?=$url?>" type="QR" class="barcode" size="1" error="M" disableborder="1" /></td> -->
      <td colspan="5" align="right"><img src="<?=base_url()?>upload/qrcode/<?=$this->imageQrCode;?>" class="qr" height="80" /></td>
    </tr>
    <tr>
      <td align="center" colspan="39"><?=$instansi->nama?></td>
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
        <th colspan="2"><= 5 Menit</th>
        <th colspan="2">> 5 Menit s/d 15 Menit</th>
      <?php }else{ ?>
        <th colspan="2"><= 15 Menit</th>
      <?php } ?>
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
      <?php if(($this->input->get("bulan") == 12 && $this->input->get("tahun") == 2018) || $this->input->get("tahun") == 2019) { ?>
        <th>Frek</th>
        <th>Skor</th>
      <?php } ?>
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
  <tbody class="border">
<?php
  $no = 1;
  foreach ($datanya as $data) {
?>
    <tr>
      <td width="1.5%" align="center"><?=$no++?></td>
      <td width="6%"><?=$data['nama']?></td>
      <td width="7.5%">&nbsp;<?=$data['nip']?>&nbsp;</td>
      <td width="2%">&nbsp;<?=$data['nama_golongan']?>&nbsp;</td>
      <td width="4.8%"><?=$data['unor']?></td>

      <!-- skor 1 -->
      <?php if(($this->input->get("bulan") == 12 && $this->input->get("tahun") == 2018) || $this->input->get("tahun") == 2019) { ?>
        <td width="2.3%" align="center"><?=$data['jumlahlambatkuranglima']?></td>
        <td width="2.3%" align="center"><?=$data['skorLambatKurangLima']?></td>
      <?php } ?>
     
      <td width="2.3%" align="center"><?=$data['jumlahlambatkuranglimabelas']?></td>
      <td width="2.3%" align="center"><?=$data['skorLambatKurangLimaBelas']?></td>

      <td width="2.3%" align="center"><?=$data['jumlahlambatkurangsatujam']?></td>
      <td width="2.3%" align="center"><?=$data['skorLambatKurangSatuJam']?></td>
      
      <td width="2.3%" align="center"><?=$data['jumlahlambatkurangduajam']?></td>
      <td width="2.3%" align="center"><?=$data['skorLambatKurangDuaJam']?></td>
      
      <td width="2.3%" align="center"><?=$data['jumlahlambatkurangtigajam']?></td>
      <td width="2.3%" align="center"><?=$data['skorLambatKurangTigaJam']?></td>
       
      <td width="2.3%" align="center"><?=$data['jumlahlambatkurangfull']?></td>
      <td width="2.3%" align="center"><?=$data['skorLambatKurangFull']?></td>
      
      <td width="2.3%" align="center"><?=$data['jumlahcepatkuranglimabelas']?></td>
      <td width="2.3%" align="center"><?=$data['skorCepatKurangLimaBelas']?></td>
       
      <td width="2.3%" align="center"><?=$data['jumlahcepatkurangsatujam']?></td>
      <td width="2.3%" align="center"><?=$data['skorCepatKurangSatuJam']?></td>
    
      <td width="2.3%" align="center"><?=$data['jumlahcepatkurangduajam']?></td>
      <td width="2.3%" align="center"><?=$data['skorCepatKurangDuaJam']?></td>
      
      <td width="2.3%" align="center"><?=$data['jumlahcepatkurangtigajam']?></td>
      <td width="2.3%" align="center"><?=$data['skorCepatKurangTigaJam']?></td>
       
      <td width="2.3%" align="center"><?=$data['jumlahcepatkurangfull']?></td>
      <td width="2.3%" align="center"><?=$data['skorCepatKurangFull']?></td>
       
      <td width="2.3%" align="center"><?=$data['jumlahsakit']?></td>
      <td width="2.3%" align="center"><?=$data['skorJumlahSakit']?></td>
       
      <td width="2.3%" align="center"><?=$data['jumlahcuti']?></td>
      <td width="2.3%" align="center"><?=$data['skorJumlahCutiBesar']?></td>
      
      <td width="2.3%" align="center"><?=$data['jumlahtidakhadirsah']?></td>
      <td width="2.3%" align="center"><?=$data['skorJumlahTidakHadirSah']?></td>
        
      <td width="2.3%" align="center"><?=$data['jumlahtidakhadirtidaksah']?></td>
      <td width="2.3%" align="center"><?=$data['skorJumlahTidakHadirTidakSah']?></td>
        
      <td width="2.3%" align="center"><?=$data['skorTotal']?></td>
      <td width="2.3%" align="center"><?=$data['jumlahMasuk']?></td>

      <td width="2.3%" align="center"><?=$data['jumlahhadirtotal']?></td>
      <td width="2.3%" align="center"><?=$data['jumlahdinasluar']?></td>
      
      <td width="2.3%" align="center"><?=$data['jumlahcutitahunan']?></td>
      <td width="2.3%" align="center"><?=$data['skorTPP']?></td>
    </tr>
<?php
  }
?>
  </tbody>
</table>
