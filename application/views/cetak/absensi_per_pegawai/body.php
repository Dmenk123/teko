<style>
    #isi th, #isi td
    {
        padding: 5px;
    }
</style>

<?php



?>

<table style="font-size: 15px; margin-bottom: 20px;">
    <tr>
        <td width="100px">NIP</td>
        <td>: <?= isset($pegawai->nip) ? $pegawai->nip : '-' ?></td>
    </tr>
    <tr>
        <td>Nama</td>
        <td>: <?= isset($pegawai->nama) ? $pegawai->nama : '-' ?></td>
    </tr>
    <tr>
        <td>Jabatan</td>
        <td>: <?= isset($pegawai->nama_jabatan) ? $pegawai->nama_jabatan : '-' ?></td>
    </tr>
    <tr>
        <td>Instansi</td>
        <td>: <?= isset($pegawai->nama_instansi) ? $pegawai->nama_instansi : '-'?></td>
    </tr>
</table>

<table id="isi" border="1" width="100%" style="border-collapse: collapse; font-size: 12px;">
    <thead>
        <tr>
            <th rowspan="2">Tanggal - Hari</th>
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

        if(empty($absensi))
        {
            ?>
        <tr>
            <td colspan="8" align="center" style="padding: 50px 0 200px 0;"><h4>Data Absensi Pegawai Tidak Ditemukan</h4></td>
        </tr>

            <?php
        }
        else
        {
            foreach ($absensi as $data) 
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
            <td><?= $data->tanggal_indo ?> - <?= $data->hari ?></td>
            <td align="center"><?= $data->finger_masuk_jam ? $data->finger_masuk_jam : '-' ?></td>
            <td align="center"><?= $data->finger_pulang_jam ? $data->finger_pulang_jam : '-' ?></td>
            <td align="center"><?= $data->jadwal_masuk_jam ? $data->jadwal_masuk_jam : '-' ?></td>
            <td align="center"><?= $data->jadwal_pulang_jam ? $data->jadwal_pulang_jam : '-' ?></td>
            <td align="center"><?= $format_masuk ?></td>
            <td align="center"><?= $format_pulang ?></td>
            <td><?php
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
