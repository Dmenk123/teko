<style>
  table, th, td {
    height: 20px;
  }
  .border th, .border td {
    border: 1px solid black;
  }
</style>
<style>
  table, th, td {
    height: 15px;
  }
</style>
<?php
  $colspan = $hari + 1;
?>
<table border="0" width="100%" style="border-collapse: collapse;font-size:10px;">
  <thead style="font-size:12px;">
    <tr>
      <td width="11%" colspan="2">&nbsp;<img src="<?=base_url()?>assets/img/logo_clean.png" width="95" height="100"></td>
      <td colspan="<?=($hari/2)?>">PEMERINTAH KOTA SURABAYA<br><br><?=$instansi->nama?></td>
      <td colspan="<?=($hari/2)?>">Daftar : ABSEN UANG MAKAN KARYAWAN / WATI<br><br><br><br><br>Bulan : <?=$bulan?> <?=$tahun?></td>
      <td width="16%" align="right" colspan="<?=(3+($colspan%2))?>"><img src="<?=base_url()?>upload/qrcode/<?=$this->imageQrCode;?>" class="qr" height="80" /></td>
    </tr>
    <tr>
        <th width="2%">No</th>
        <th>Nama</th>
        <?php 
        $batas_nip = date('Y-m-d', strtotime('2019-04-01'));
        $tgl_pilih = $this->input->get('tahun').'-'.$this->input->get('bulan').'-'.'01';
        $dt_tgl_pilih = date('Y-m-d', strtotime($tgl_pilih));
        ?>
        <?php if (strtotime($dt_tgl_pilih) < strtotime($batas_nip)) { ?>
          <?php if ($this->input->get('id_instansi') == '5.09.00.93.00' || $this->input->get('id_instansi') == '5.02.00.00.00') { ?>
            <th>NIP</th>
          <?php } ?>
        <?php }else{ ?>
            <th>NIP</th>
        <?php } ?>
        <?php for($i=1;$i<=$hari;$i++)
        {
            ?>

            <th width="2%"><?=$i?></th>

            <?php
        }
        ?>

        <th width="5%">Jumlah Hari</th>
    </tr>
  </thead>
  <tbody>

    <?php
    for ($i = 0; $i < count($datanya); $i++) {
    ?>
        <tr>
    <?php
        for($j=0;$j< count($datanya[$i]); $j++) {
    ?>
        <td <?php if($j==1) { ?>align="left"<?php } else { ?>align="center"<?php } ?>><?php if($j==1 || $j == 2) { ?>&nbsp;<?php }?><?= $datanya[$i][$j] ?><?php if($j==1 || $j == 2) { ?>&nbsp;<?php }?></td>
    <?php
        }
    ?>
        </tr>
    <?php
    }
    ?>

  </tbody>
</table>
