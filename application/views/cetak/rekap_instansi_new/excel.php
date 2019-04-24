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
      <td colspan="7" align="center"><?=$tgl_mulai?> s/d <?=$tgl_hingga?></td>
      <td colspan="12"></td>
      <td colspan="5" align="right"><img src="<?=base_url()?>upload/qrcode/<?=$this->imageQrCode;?>" class="qr" height="80" /></td>
    </tr>
    <tr>
      <td colspan="3"></td>
      <td colspan="7" align="center"><?=$instansi->nama?></td>
      <br>
    </tr>
    <tr>
      <td colspan="27"></td>
    </tr>
    <tr>
      <td colspan="27"></td>
    </tr>
  </thead>
  <tr class="border">
    <th rowspan="3">No</th>
    <th rowspan="3">Nama</th>
    <th rowspan="3">NIP</th>
    <th rowspan="3">Jabatan</th>
    <th rowspan="3">Kerja</th>
    <th colspan="3">Jumlah Hari</th>
    <th colspan="2">Overtime</th>
    <th colspan="4">Jumlah Lembur</th>
    <th colspan="13">Keterangan</th>
  </tr>
  <tr class="border">
    <th rowspan="2">Hadir</th>
    <th rowspan="2">Telat</th>
    <th rowspan="2">Pulang Cepat</th>
    <th rowspan="2">Jam</th>
    <th rowspan="2">Menit</th>
    <th colspan="2">Sabtu</th>
    <th colspan="2">Minggu</th>
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
    <th rowspan="2">UPT</th>
  </tr>
  <tr class="border">
    <th>Jam</th>
    <th>Menit</th>
    <th>Jam</th>
    <th>Menit</th>
  </tr>
<tbody class="border">
<?php
  $no = 1;
  foreach ($datanya as $data) {
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
