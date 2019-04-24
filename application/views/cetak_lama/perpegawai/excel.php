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
      <th align="center" colspan="16" style="font-size:13px;"><strong>PEMERINTAH KOTA SURABAYA<br/>LAPORAN PER PERIODE KEHADIRAN PEGAWAI</strong></th>
    </tr>
    <tr>
      <td colspan="6"></td>
      <td colspan="5" align="left"><?=$tgl_mulai?> s/d <?=$tgl_hingga?></td>
      <td rowspan="5" width="20%" class="barcodecell" colspan="5"><barcode code="<?=$url?>" type="QR" class="barcode" size="1" error="M" disableborder="1" /></td>
    </tr>
    <tr>
      <td>Nama</td>
      <td colspan="10"><?=$pegawai->nama?></td>
    </tr>
    <tr>
      <td>Nip</td>
      <td colspan="10" align="left"><?=$pegawai->nip?></td>
    </tr>
    <tr>
      <td>Instansi</td>
      <td colspan="10"><?=$pegawai->nama_instansi?></td>
    </tr>
    <tr>
      <td>Jabatan</td>
      <td colspan="10"><?=$pegawai->nama_jenis_jabatan?></td>
    </tr>
    <tr>
      <td colspan="16"></td>
    </tr>
  </thead>
  <tbody class="border">
    <tr>
      <th rowspan="3">Tanggal</th>
      <th rowspan="3" colspan="2">Jam Kerja</th>
      <th rowspan="3">Masuk</th>
      <th rowspan="2" colspan="2">Telat Masuk</th>
      <th rowspan="3">Pulang</th>
      <th rowspan="2" colspan="2">Cepat Pulang</th>
      <th rowspan="2" colspan="2">Overtime</th>
      <th colspan="4">Jumlah Lembur</th>
      <th rowspan="3">Keterangan</th>
    </tr>
    <tr>
      <th colspan="2">Sabtu</th>
      <th colspan="2">Minggu</th>
    </tr>
    <tr>
      <th>Jam</th>
      <th>Menit</th>
      <th>Jam</th>
      <th>Menit</th>
      <th>Jam</th>
      <th>Menit</th>
      <th>Jam</th>
      <th>Menit</th>
      <th>Jam</th>
      <th>Menit</th>
    </tr>
<?php
  $t_telat_jam = 0;
  $t_telat_menit = 0;
  $t_p_cepat_jam = 0;
  $t_p_cepat_menit = 0;
  $t_overtime_jam = 0;
  $t_overtime_menit = 0;
  $t_sabtu_jam = 0;
  $t_sabtu_menit = 0;
  $t_minggu_jam = 0;
  $t_minggu_menit = 0;
  foreach ($isi as $data) {
?>
    <tr>
      <td width="9%"><?=date("d/m/Y", strtotime($data['tanggal']))?></td>
      <td width="18%" align="center" colspan="2"><?=$data['jam_kerja']?></td>
      <td width="7%" align="center"><?php if($data['absen_masuk'] <> null) { echo date("h:i", strtotime($data['absen_masuk'])); } ?></td>
      <td align="center" width="3%"><?php if($data['telat_jam'] <> null && $data['telat_jam'] <> 0) { echo $data['telat_jam']; $t_telat_jam = $t_telat_jam + $data['telat_jam']; } else { ?>-<?php } ?></td>
      <td align="center" width="3%"><?php if($data['telat_menit'] <> null && $data['telat_menit'] <> 0) { echo $data['telat_menit']; $t_telat_menit = $t_telat_menit + $data['telat_menit'];  } else { ?>-<?php } ?></td>
      <td width="7%" align="center"><?php if($data['absen_pulang'] <> null) { echo date("h:i", strtotime($data['absen_pulang'])); } ?></td>
      <td align="center" width="3%"><?php if($data['pulang_cepat_jam'] <> null && $data['pulang_cepat_jam'] <> 0) { echo $data['pulang_cepat_jam']; $t_p_cepat_jam = $t_p_cepat_jam + $data['pulang_cepat_jam']; } else { ?>-<?php } ?></td>
      <td align="center" width="3%"><?php if($data['pulang_cepat_menit'] <> null && $data['pulang_cepat_menit'] <> 0) { echo $data['pulang_cepat_menit']; $t_p_cepat_menit = $t_p_cepat_menit + $data['pulang_cepat_menit']; } else { ?>-<?php } ?></td>
      <td align="center" width="3%"><?php if($data['overtime_jam'] <> null && $data['overtime_jam'] <> 0) { echo $data['overtime_jam']; $t_overtime_jam = $t_overtime_jam + $data['overtime_jam']; } else { ?>-<?php } ?></td>
      <td align="center" width="3%"><?php if($data['overtime_menit'] <> null && $data['overtime_menit'] <> 0) { echo $data['overtime_menit']; $t_overtime_menit = $t_overtime_menit + $data['overtime_menit']; } else { ?>-<?php } ?></td>
      <td align="center" width="3%"><?php if($data['sabtu_jam'] <> null && $data['sabtu_jam'] <> 0) { echo $data['sabtu_jam']; $t_sabtu_jam = $t_sabtu_jam + $data['sabtu_jam']; } else { ?>-<?php } ?></td>
      <td align="center" width="3%"><?php if($data['sabtu_menit'] <> null && $data['sabtu_menit'] <> 0) { echo $data['sabtu_menit']; $t_sabtu_menit = $t_sabtu_menit + $data['sabtu_menit']; } else { ?>-<?php } ?></td>
      <td align="center" width="3%"><?php if($data['minggu_jam'] <> null && $data['minggu_jam'] <> 0) { echo $data['minggu_jam']; $t_minggu_jam = $t_minggu_jam + $data['minggu_jam']; } else { ?>-<?php } ?></td>
      <td align="center" width="3%"><?php if($data['minggu_menit'] <> null && $data['minggu_menit'] <> 0) { echo $data['minggu_menit']; $t_minggu_menit = $t_minggu_menit + $data['minggu_menit']; } else { ?>-<?php } ?></td>
      <td align="center"><?=$data['keterangan']?></td>
    </tr>
<?php
  }
  if($t_telat_menit > 0) {
    $t_telat_jam = $t_telat_jam + floor($t_telat_menit / 60);
    $t_telat_menit = $t_telat_menit % 60;
  }
  if($t_p_cepat_menit > 0) {
    $t_p_cepat_jam = $t_p_cepat_jam + floor($t_p_cepat_menit / 60);
    $t_p_cepat_menit = $t_p_cepat_menit % 60;
  }
  if($t_overtime_menit > 0) {
    $t_overtime_jam = $t_overtime_jam + floor($t_overtime_menit / 60);
    $t_overtime_menit = $t_overtime_menit % 60;
  }
  if($t_sabtu_menit > 0) {
    $t_sabtu_jam = $t_sabtu_jam + floor($t_sabtu_menit / 60);
    $t_sabtu_menit = $t_sabtu_menit % 60;
  }
  if($t_minggu_menit > 0) {
    $t_minggu_jam = $t_minggu_jam + floor($t_minggu_menit / 60);
    $t_minggu_menit = $t_minggu_menit % 60;
  }
?>
    <tr>
      <td colspan="4" style="border:0px; background-color:gray;">Total</td>
      <td align="center" style="border:0px; background-color:gray;"><?php if($t_telat_jam <> 0) { echo $t_telat_jam; } else { ?>-<?php } ?></td>
      <td align="center" style="border:0px; background-color:gray;"><?php if($t_telat_menit <> 0) { echo $t_telat_menit; } else { ?>-<?php } ?></td>
      <td style="border:0px; background-color:gray;"></td>
      <td align="center" style="border:0px; background-color:gray;"><?php if($t_p_cepat_jam <> 0) { echo $t_p_cepat_jam; } else { ?>-<?php } ?></td>
      <td align="center" style="border:0px; background-color:gray;"><?php if($t_p_cepat_menit <> 0) { echo $t_p_cepat_menit; } else { ?>-<?php } ?></td>
      <td align="center" style="border:0px; background-color:gray;"><?php if($t_overtime_jam <> 0) { echo $t_overtime_jam; } else { ?>-<?php } ?></td>
      <td align="center" style="border:0px; background-color:gray;"><?php if($t_overtime_menit <> 0) { echo $t_overtime_menit; } else { ?>-<?php } ?></td>
      <td align="center" style="border:0px; background-color:gray;"><?php if($t_sabtu_jam <> 0) { echo $t_sabtu_jam; } else { ?>-<?php } ?></td>
      <td align="center" style="border:0px; background-color:gray;"><?php if($t_sabtu_menit <> 0) { echo $t_sabtu_menit; } else { ?>-<?php } ?></td>
      <td align="center" style="border:0px; background-color:gray;"><?php if($t_minggu_jam <> 0) { echo $t_minggu_jam; } else { ?>-<?php } ?></td>
      <td align="center" style="border:0px; background-color:gray;"><?php if($t_minggu_menit <> 0) { echo $t_minggu_menit; } else { ?>-<?php } ?></td>
      <td style="border:0px; background-color:gray;"></td>
    </tr>
  </tbody>
  <tfoot>
    <tr>
      <td colspan="16"></td>
    </tr>
    <tr>
      <td width="7%">Keterangan</td>
      <td width="3%">H</td>
      <td colspan="2">: Hadir</td>
      <td width="3%">M</td>
      <td colspan="3">: Mangkir/Alpha</td>
      <td width="3%">*</td>
      <td colspan="3">: Bebas/Libur</td>
      <td width="3%">?</td>
      <td colspan="3">: Ijin Per Jam</td>
    </tr>
    <tr>
      <td></td>
      <td>R</td>
      <td colspan="2">: Hari Libur</td>
      <td>NA</td>
      <td colspan="3">: Belum Aktif Absen</td>
      <td>J</td>
      <td colspan="3">: Ijin Per Jam</td>
      <td>CAP</td>
      <td colspan="3">: CUTI ALASAN PENTING</td>
    </tr>
    <tr>
      <td></td>
      <td>CH</td>
      <td colspan="2">: CUTI BESAR</td>
      <td>CM</td>
      <td colspan="3">: CUTI BERSALIN</td>
      <td>CS</td>
      <td colspan="3">: CUTI SAKIT</td>
      <td>CT</td>
      <td colspan="3">: CUTI TAHUNAN</td>
    </tr>
    <tr>
      <td></td>
      <td>DK</td>
      <td colspan="2">: DIKLAT</td>
      <td>DL</td>
      <td colspan="3">: DINAS LUAR</td>
      <td>DSP</td>
      <td colspan="3">: PENUGASAN</td>
      <td>I</td>
      <td colspan="3">: IJIN</td>
    </tr>
    <tr>
      <td></td>
      <td>LP</td>
      <td colspan="2">: LEPAS DINAS</td>
      <td>SK</td>
      <td colspan="3">: SAKIT</td>
      <td>TB</td>
      <td colspan="3">: TUGAS</td>
      <td></td>
      <td colspan="3"></td>
    </tr>
  </tfoot>
</table>
