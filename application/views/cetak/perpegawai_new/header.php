<table width="100%" style="font-size:13px;">
  <tr>
    <td align="center"><strong>PEMERINTAH KOTA SURABAYA<br/>LAPORAN PER PERIODE KEHADIRAN PEGAWAI</strong></td>
  </tr>
</table>
<table width="100%" style="font-size:12px;">
  <tr>
    <td></td>
    <td width="30%"></td>
    <td><?=$tgl_mulai?> s/d <?=$tgl_hingga?></td>
    <td rowspan="5" width="20%" class="barcodecell"><barcode code="<?=$url?>" type="QR" class="barcode" size="1" error="M" disableborder="1" /></td>
  </tr>
  <tr>
    <td width="9%">Nama</td>
    <td colspan="2"><?=$pegawai->nama?></td>
  </tr>
  <tr>
    <td>Nip</td>
    <td colspan="2"><?=$pegawai->nip?></td>
  </tr>
  <tr>
    <td>Instansi</td>
    <td colspan="2"><?=$pegawai->nama_instansi?></td>
  </tr>
  <tr>
    <td>Jabatan</td>
    <td colspan="2"><?=$pegawai->nama_jenis_jabatan?></td>
  </tr>
</table>
