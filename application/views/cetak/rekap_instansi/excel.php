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
      <th align="center" colspan="27" style="font-size:13px;"><strong>PEMERINTAH KOTA SURABAYA<br/>LAPORAN REKAPITULASI PER PERIODE KEHADIRAN PEGAWAI</strong></th>
    </tr>
    <tr>
      <td colspan="3"></td>
      <td colspan="11" align="left"><?=$tgl_mulai?> s/d <?=$tgl_hingga?></td>
      <td colspan="13" rowspan="2" width="20%" class="barcodecell" colspan="5"><barcode code="<?=$url?>" type="QR" class="barcode" size="1" error="M" disableborder="1" /></td>
    </tr>
    <tr>
      <td colspan="3"></td>
      <td colspan="11" align="left"><?=$instansi->nama?></td>
    </tr>
  </thead>
  <tbody class="border">
<?php
  $no = 1;
  foreach ($isi as $data) {
?>
    <tr>
      <td width="2%" align="center"><?=$no++?></td>
      <td><?=$data['nama']?></td>
      <td>&nbsp;<?=$data['nip']?>&nbsp;</td>
      <td><?=$data['jabatan']?></td>
      <td width="3%" align="center"><?=$data['kerja']?></td>
      <td width="3%" align="center"><?=$data['jml_hari_hadir']?></td>
      <td width="3%" align="center"><?=$data['jml_hari_telat']?></td>
      <td width="3%" align="center"><?=$data['jml_hari_pulang_cepat']?></td>
      <td width="3%" align="center"><?=$data['overtime_jam']?></td>
      <td width="3%" align="center"><?=$data['overtime_menit']?></td>
      <td width="3%" align="center"><?=$data['sabtu_jam']?></td>
      <td width="3%" align="center"><?=$data['sabtu_menit']?></td>
      <td width="3%" align="center"><?=$data['minggu_jam']?></td>
      <td width="3%" align="center"><?=$data['minggu_menit']?></td>
      <td width="2.5%" align="center"><?=$data['ket_m']?></td>
      <td width="2.5%" align="center"><?=$data['ket_ch']?></td>
      <td width="2.5%" align="center"><?=$data['ket_cm']?></td>
      <td width="2.5%" align="center"><?=$data['ket_ct']?></td>
      <td width="2.5%" align="center"><?=$data['ket_cap']?></td>
      <td width="2.5%" align="center"><?=$data['ket_dk']?></td>
      <td width="2.5%" align="center"><?=$data['ket_dl']?></td>
      <td width="2.5%" align="center"><?=$data['ket_i']?></td>
      <td width="2.5%" align="center"><?=$data['ket_lp']?></td>
      <td width="2.5%" align="center"><?=$data['ket_mpp']?></td>
      <td width="2.5%" align="center"><?=$data['ket_sk']?></td>
      <td width="2.5%" align="center"><?=$data['ket_tb']?></td>
      <td width="2.5%" align="center"><?=$data['ket_upt']?></td>
    </tr>
<?php
  }
?>
  </tbody>
</table>
