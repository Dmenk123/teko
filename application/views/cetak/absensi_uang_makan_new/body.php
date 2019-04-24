<style>
  table, th, td {
    height: 15px;
  }
</style>
<table width="100%" cellspacing="0" cellpadding="0" style="font-family:Arial, Helvetica, sans-serif; font-size:15px; padding:3px;">
  <tr>
    <td width="10%">&nbsp;<img src="<?=base_url()?>assets/img/logo_clean.png" width="95"></td>
    <td width="70%" valign="center">
    <table width='100%' height="50px" valign="center">
      <tr>
        <td><br><br>PEMERINTAH KOTA SURABAYA</td>
        <td width="40"></td>
        <td><br><br>Daftar : ABSEN UANG MAKAN KARYAWAN / WATI</td>
      </tr>
      <tr>
        <td><?=$instansi->nama?></td>
        <td width="40"></td>
        <td>Bulan : <?=$bulan?> <?=$tahun?></td>
      </tr>
    </table>
  </td>
    <td width="20%" align="right"><img src="<?=base_url()?>upload/qrcode/<?=$this->imageQrCode;?>" class="qr" height="80" /></td>
  </tr>
</table>
<table border="1" width="100%" style="border-collapse: collapse;font-size:6px;">
    <thead>
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
            <td <?php if($j==1) { ?>align="left"<?php } else { ?>align="center"<?php } ?>><?php if($j==1) { ?>&nbsp;<?php }?><?= $datanya[$i][$j] ?></td>
    <?php
        }
    ?>
        </tr>
    <?php
    }
    ?>

    </tbody>
</table>
