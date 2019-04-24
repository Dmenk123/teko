<style>
  table, th, td {
    height: 15px;
  }
</style>
<?php
	$hari_array     = array("MINGGU","SENIN","SELASA","RABU","KAMIS","JUMAT","SABTU");
  $tgl_hari_ini   = date('d-m-Y');
  $waktu_hari_ini = date('H:i:s');
?>
<table width="100%" style="font-size:12px;">
  <tr>
    <td colspan="4" align="center" style="font-size:13px;"><strong>PEMERINTAH KOTA SURABAYA<br/>LAPORAN PER PERIODE KEHADIRAN PEGAWAI</strong></td>
  </tr>
  <tr>
    <td></td>
    <td width="<?php if($this->input->get("type") == 'pdf') { ?>30%<?php } else { ?>37%<?php } ?>"></td>
    <td><?php echo  date('d-m-Y', strtotime($this->tgl_pertama));?> s/d <?php echo date('d-m-Y', strtotime($this->tgl_terakhir));?></td>
    <td rowspan="5" width="16%" align="right"><img src="<?=base_url()?>upload/qrcode/<?=$this->imageQrCode;?>" class="qr" height="80" /></td>
  </tr>
  <tr>
    <td width="9%">Nama</td>
    <td colspan="2"><?=$this->dataPegawai->nama;?></td>
  </tr>
  <tr>
    <td>Nip</td>
    <td colspan="2"><?=$this->dataPegawai->nip;?></td>
  </tr>
  <tr>
    <td>Instansi</td>
    <td colspan="2"><?=$this->dataPegawai->nama_instansi;?></td>
  </tr>
  <tr>
    <td>Jabatan</td>
    <td colspan="2"><?=$this->dataPegawai->nama_jenis_jabatan;?></td>
  </tr>
</table>
<table border="1" width="100%" style="border-collapse: collapse;font-size:10px;">
  <thead>
    <tr>
      <th width="9%" rowspan="2">HARI</th>
      <th width="9%" rowspan="2">TANGGAL</th>
      <th width="18%" rowspan="2">JAM KERJA</th>
      <th width="7%" rowspan="2">MASUK</th>
      <th colspan="2">TELAT MASUK</th>
      <th width="7%" rowspan="2">PULANG</th>
      <th colspan="2">CEPAT PULANG</th>
      <th colspan="2">LEMBUR RIIL</th>
      <th colspan="2">LEMBUR TERHITUNG</th>
      <th rowspan="2">KETERANGAN</th>
    </tr>
    <tr>
      <th>JAM</th>
      <th>MENIT</th>
      <th>JAM</th>
      <th>MENIT</th>
      <th>JAM</th>
      <th>MENIT</th>
      <th>JAM</th>
      <th>MENIT</th>
    </tr>
  </thead>
  <tbody>
<?php
  $menit_telat_masuk     = 0;
  $jam_telat_masuk       = 0;
  $menit_cepat_pulang    = 0;
  $jam_cepat_pulang      = 0;
  $menit_overtime        = 0;
  $jam_overtime          = 0;
  $menit_overtime_diakui = 0;
  $jam_overtime_diakui   = 0;

  foreach($this->dataLaporan as $data) {
    $telat_masuk     = json_decode($data->telat_masuk);
    $cepat_pulang    = json_decode($data->cepat_pulang);
    $overtime        = json_decode($data->overtime);
    $overtime_diakui = json_decode($data->overtime_diakui);
    $jumlah_lembur   = json_decode($data->jumlah_lembur);

    if(is_numeric($telat_masuk->menit)) {
      $menit_telat_masuk += $telat_masuk->menit;
    }
    if(is_numeric($telat_masuk->jam)) {
      $jam_telat_masuk += $telat_masuk->jam;
    }

    if(is_numeric($cepat_pulang->menit)) {
      $menit_cepat_pulang += $cepat_pulang->menit;
    }
    if(is_numeric($cepat_pulang->jam)) {
      $jam_cepat_pulang += $cepat_pulang->jam;
    }

    if(isset($overtime->menit) and is_numeric($overtime->menit)) {
      $menit_overtime += $overtime->menit;
    }
    if(isset($overtime->jam) and is_numeric($overtime->jam)) {
      $jam_overtime += $overtime->jam;
    }

    if(isset($overtime_diakui->menit) and is_numeric($overtime_diakui->menit)) {
      $menit_overtime_diakui += $overtime_diakui->menit;
    }
    if(isset($overtime_diakui->jam) and is_numeric($overtime_diakui->jam)) {
      $jam_overtime_diakui += $overtime_diakui->jam;
    }
?>
    <tr>
      <td align="center"><?php echo $hari_array[date("w", strtotime($data->tanggal))]; ?></td>
  		<td align="center"><?php echo date('d-m-Y', strtotime($data->tanggal)); ?></td>
  		<td align="center"><?php echo $data->jam_kerja; ?></td>
  		<td align="center"><?php echo $data->masuk; ?></td>
      <td align="center"><?php echo $telat_masuk->jam; ?></td>
  		<td align="center"><?php echo $telat_masuk->menit; ?></td>
  		<td align="center"><?php echo $data->pulang; ?></td>
  		<td align="center"><?php echo $cepat_pulang->jam; ?></td>
  		<td align="center"><?php echo $cepat_pulang->menit; ?></td>
  		<td align="center"><?php if(isset($overtime->jam)){ echo $overtime->jam; } else {echo "-";}?>	</td>
  		<td align="center"><?php if(isset($overtime->menit)){ echo $overtime->menit; } else {echo "-";}?>	</td>
  		<td align="center"><?php if(isset($overtime_diakui->jam)){ echo $overtime_diakui->jam; } else {echo "-";}?>	</td>
  		<td align="center"><?php if(isset($overtime_diakui->menit)){ echo $overtime_diakui->menit; } else {echo "-";}?>	</td>
  		<td align="center"><?php echo $data->keterangan; ?></td>
    </tr>
<?php
	}
?>
    <tr>
      <td colspan="4" style="background-color:#E5E5E5;"><strong>TOTAL</strong></td>
      <td align="center" style="background-color:#E5E5E5;"><?php echo $jam_telat_masuk + floor($menit_telat_masuk/60) ?></td>
  		<td align="center" style="background-color:#E5E5E5;"><?php echo $menit_telat_masuk % 60 ?></td>
  		<td style="background-color:#E5E5E5;">&nbsp;</td>
  		<td align="center" style="background-color:#E5E5E5;"><?php echo $jam_cepat_pulang + floor($menit_cepat_pulang/60) ?></td>
  		<td align="center" style="background-color:#E5E5E5;"><?php echo $menit_cepat_pulang % 60 ?></td>
  		<td align="center" style="background-color:#E5E5E5;"><?php echo $jam_overtime + floor($menit_overtime/60) ?></td>
  		<td align="center" style="background-color:#E5E5E5;"><?php echo $menit_overtime % 60 ?></td>
  		<td align="center" style="background-color:#E5E5E5;"><?php echo $jam_overtime_diakui + floor($menit_overtime_diakui/60) ?></td>
  		<td align="center" style="background-color:#E5E5E5;"><?php echo $menit_overtime_diakui % 60 ?></td>
      <td style="background-color:#E5E5E5;"></td>
    </tr>
  </tbody>
</table>
<br />
<table width="100%" border="0" style="font-size:10px;">
  <tbody>
    <tr>
      <td width="12%">Keterangan</td>
      <td width="5%">H</td>
      <td width="17%">: HADIR</td>
      <td width="5%">M</td>
      <td width="17%">: MANGKIR/ALPHA</td>
      <td width="5%">*</td>
      <td width="17%">: BEBAS/LIBUR</td>
      <td width="5%">R</td>
      <td width="17%">: HARI LIBUR</td>
    </tr>
    <tr>
      <td></td>
      <td>J</td>
      <td>: IJIN PER JAM</td>
      <td>CAP</td>
      <td>: CUTI ALASAN PENTING</td>
      <td>CB</td>
      <td>: CUTI BESAR</td>
      <td>CM</td>
      <td>: CUTI MELAHIRKAN</td>
    </tr>
    <tr>
      <td></td>
      <td>CS</td>
      <td>: CUTI SAKIT</td>
      <td>CT</td>
      <td>: CUTI TAHUNAN</td>
      <td>DK</td>
      <td>: DIKLAT</td>
      <td>DL</td>
      <td>: DINAS LUAR</td>
    </tr>
    <tr>
      <td></td>
      <td>DSP</td>
      <td>: PENUGASAN</td>
      <td>I</td>
      <td>: IZIN</td>
      <td>SK</td>
      <td>: SAKIT</td>
      <td>TB</td>
      <td>: TUGAS BELAJAR</td>
    </tr>
    <tr>
      <td></td>
      <td>MD</td>
      <td>: MENINGGAL</td>
      <td colspan="6"></td>
    </tr>
  </tbody>
</table>
<br />
<br />
<br />
<table width="100%" border="1" style="border-collapse: collapse; font-size:10px; ">
  <tbody>
    <tr>
  		<td colspan="3" align="left" style="font-size:15px; border-bottom:0px;">Informasi Lembur <sup>s</sup>/<sub>d</sub> Periode Cetak :</td>
  	</tr>
    <tr>
      <td width="5px" align="left" style="font-size:15px; border-top:0px; border-right:0px; border-bottom:0px;">Cetak&nbsp;</td>
      <td width="5px" align="left" style="font-size:15px; border-top:0px; border-right:0px; border-left:0px; border-bottom:0px;">Tanggal&nbsp;</td>
  		<td align="left" style="font-size:15px; border-top:0px; border-left:0px; border-bottom:0px;">: <?=$tgl_hari_ini?> Hari <?=ucfirst(strtolower($hari_array[date("w", strtotime($tgl_hari_ini))]))?></td>
  	</tr>
    <tr>
      <td width="5px" align="left" style="font-size:15px; border-top:0px; border-right:0px; border-bottom:0px;">&nbsp;</td>
      <td align="left" style="font-size:15px; border-top:0px; border-right:0px; border-left:0px; border-bottom:0px;">Waktu&nbsp;</td>
  		<td align="left" style="font-size:15px; border-top:0px; border-left:0px; border-bottom:0px;">: <?=$waktu_hari_ini?></td>
  	</tr>
    <tr>
  		<td colspan="3" align="left" style="font-size:15px; border-top:0px; border-bottom:0px;">Lembur Riil <sup>s</sup>/<sub>d</sub> Waktu Cetak : <?php echo $jam_overtime + floor($menit_overtime/60) ?> jam <?php echo $menit_overtime % 60 ?> menit</td>
  	</tr>
    <tr>
  		<td colspan="3" align="left" style="font-size:15px; border-top:0px;">Lembur Terhitung Untuk Skor Kehadiran : <?php echo $jam_overtime_diakui + floor($menit_overtime_diakui/60) ?> jam <?php echo $menit_overtime_diakui % 60 ?> menit</td>
  	</tr>
  </tbody>
</table>
