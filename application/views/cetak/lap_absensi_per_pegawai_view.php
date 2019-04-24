<style>
body{
	background:url('assets/img/logo_pemkot_watermark2.png');
	background-position:center 100px;
	background-position-y: 160px;
	background-repeat:no-repeat;
}
.title{
	font-size:13px;
	font-family:Arial, Helvetica, sans-serif;
}
.legend tr td{
	font-size:10px;
	font-family:Arial, Helvetica, sans-serif;
	padding:2px;
}
tr.bggrey td{
	background:#E5E5E5;
	border-bottom: none;
	border-TOP: 0px;
}
.headingtext tr td{
	font-family:Arial, Helvetica, sans-serif;
	font-size:12px;
	padding:3px;
}
table.cloth{
	font-family:Arial, Helvetica, sans-serif;
	border-collapse:collapse;
	padding:0px;
	outline:none;
}
table.cloth tr th{
	border:0.5px solid #000;
	font-size:10px;
	font-weight:normal;
}
table.cloth tr td{
	border:0.5px solid #000;
	font-size:10px;
	padding:2px 0;
	vertical-align:top;
}
.qr{
	width:80px;
}

</style>


<div align="center" class="title">
	PEMERINTAH KOTA SURABAYA<br />
	LAPORAN PER PERIODE KEHADIRAN PEGAWAI
	<br />
	<div style="font-size:13px;"><?= $this->nama_bulan ?> <?= $this->tahun ?></div>
</div>
<br>

<table style="font-size: 15px; margin-bottom: 20px;">
    <tr>
        <td width="100px">NIP</td>
        <td>: <?= isset($this->pegawai->nip) ? $this->pegawai->nip : '-' ?></td>
    </tr>
    <tr>
        <td>Nama</td>
        <td>: <?= isset($this->pegawai->nama) ? $this->pegawai->nama : '-' ?></td>
    </tr>
    <tr>
        <td>Jabatan</td>
        <td>: <?= isset($this->pegawai->nama_jenis_jabatan) ? $this->pegawai->nama_jenis_jabatan : '-' ?></td>
    </tr>
    <tr>
        <td>Instansi</td>
        <td>: <?= isset($this->pegawai->nama_instansi) ? $this->pegawai->nama_instansi : '-'?></td>
    </tr>
</table>
<!-- <?php
if(!$this->sudahAda){echo "<center><h2>Belum dikunci</h2></center><br>";}
?> -->
<table id="isi" border="1" width="100%" style="border-collapse: collapse; font-size: 12px;">
    <thead>
        <tr>
            <th width="20%" rowspan="2">Tanggal - Hari</th>
            <th colspan="2">Jam Absen</th>
            <th colspan="2">Jadwal</th>
            <th colspan="2">Selisih</th>
            <th rowspan="2">Keterangan</th>
        </tr>
        <tr>
            <th>Datang</th>
            <th>Pulang</th>
            <th>Datang</th>
            <th>Pulang</th>
            <th>Datang Terlambat</th>
            <th>Pulang Sebelum Jam/ Tepat Waktu/ Melebihi Jam</th>
        </tr>
    </thead>
    <tbody>
        
        <?php

        if(empty($this->absensi))
        {
            ?>
        <tr>
            <td colspan="8" align="center" style="padding: 50px 0 200px 0;"><h4>Data Absensi Pegawai Tidak Ditemukan</h4></td>
        </tr>

            <?php
        }
        else
        {
            foreach ($this->absensi as $data) 
            {
                $format_masuk = $format_pulang = '-';
                if($data->kode_tidak_masuk == '' && $data->hari != 'Sabtu' && $data->hari != 'Minggu')
                {
                    if(strtotime($data->finger_masuk_jam) > strtotime($data->jadwal_masuk_jam))
                    {
                        $start  = new DateTime($data->finger_masuk_jam);
                        $end    = new DateTime($data->jadwal_masuk_jam);
                        $diff   = $start->diff( $end );
                        $format_masuk = $diff->format('%H:%I');
                    }
                    if(strtotime($data->jadwal_pulang_jam) > strtotime($data->finger_pulang_jam))
                    {
                        $start  = new DateTime($data->finger_pulang_jam);
                        $end    = new DateTime($data->jadwal_pulang_jam);
                        $diff   = $start->diff( $end );
                        $format_pulang = $diff->format('%H:%I');
                    }
                }
                ?>

        <tr>
            <td>&nbsp; <?= $data->tanggal_indo ?> - <?= $data->hari ?></td>
            <td align="center"><?= $data->finger_masuk_jam ? $data->finger_masuk_jam : '-' ?></td>
            <td align="center"><?= $data->finger_pulang_jam ? $data->finger_pulang_jam : '-' ?></td>
            <td align="center"><?= $data->jadwal_masuk_jam ? $data->jadwal_masuk_jam : '-' ?></td>
            <td align="center"><?= $data->jadwal_pulang_jam ? $data->jadwal_pulang_jam : '-' ?></td>
            <td align="center"><?= $format_masuk ?></td>
            <td align="center"><?= $format_pulang ?></td>
            <td align="center"><?php
                if($data->keterangan_masuk != ''){ echo $data->keterangan_masuk; } else if($data->keterangan_tidak_masuk != '') { echo $data->keterangan_tidak_masuk; } else { echo '-'; }
                ?>                    
            </td>
        </tr>

                <?php
            }
        }

        ?>

    </tbody>
</table>


<br>
<br>
<br>

<!-- <table width="100%" border="0" cellspacing="0" cellpadding="0" class="title">
  <tr>
    <td width="75%">&nbsp;</td>
    <td min-width="25%" align="center">
        <?php echo $this->dataInstansi->instansi_tdd;?>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <u><?php echo $this->dataInstansi->nama_tdd;?></u><br>
        <?php echo $this->dataInstansi->pangkat_tdd;?><br>
        <?php echo $this->dataInstansi->nip_tdd;?><br>
    </td>
  </tr>
</table> -->

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="title">
  <tr>
    <td width="75%"><sup style="font-size:7;"><?php echo date('d-m-Y H:i') ?></sup></td>
    <td min-width="25%" align="center">
	</td>
  </tr>
</table>

<script>
	//window.print();
</script>