<?php

use backend\models\Terbilang;
?>


<div>
    <img src="<?php echo Yii::getAlias('@root') ?>/images/logo.svg" alt="">
</div>



<table class="table table-striped table-bordered w-100 ">
    <tr class="text-center">
        <th class="text-start text-capitalize">nama</th>
        <th class="text-center">:</th>
        <td class="text-start" style='height: 20px; padding:10px'><?= $model['nama'] ?> </td>
    </tr>
    <tr class="text-center">
        <th class="text-start text-capitalize">kode karyawan</th>
        <th class="text-center">:</th>
        <td class="text-start" style='height: 20px; padding:10px'><?= $model['kode_karyawan'] ?> </td>
    </tr>
    <tr class="text-center">
        <th class="text-start text-capitalize">nomer identitas</th>
        <th class="text-center">:</th>
        <td class="text-start" style='height: 20px; padding:10px'><?= $model['nomer_identitas'] ?> </td>
    </tr>
    <tr class="text-center">
        <th class="text-start text-capitalize">bagian</th>
        <th class="text-center">:</th>
        <td class="text-start" style='height: 20px; padding:10px'><?= $model['bagian'] ?> </td>
    </tr>
    <tr class="text-center">
        <th class="text-start text-capitalize">jabatan</th>
        <th class="text-center">:</th>
        <td class="text-start" style='height: 20px; padding:10px'><?= $model['jabatan'] ?> </td>
    </tr>
    <tr class="text-center">
        <th class="text-start text-capitalize">jam kerja</th>
        <th class="text-center">:</th>
        <td class="text-start" style='height: 20px; padding:10px'><?= $jamKerja['nama_jam_kerja'] ?> </td>
    </tr>
    <tr class="text-center">
        <th class="text-start text-capitalize">status karyawan</th>
        <th class="text-center">:</th>
        <td class="text-start" style='height: 20px; padding:10px'><?= $model['status_karyawan'] ?> </td>
    </tr>
    <tr class="text-center">
        <th class="text-start text-capitalize">jumlah hari kerja</th>
        <th class="text-center">:</th>
        <td class="text-start" style='height: 20px; padding:10px'><?= $model['jumlah_hari_kerja'] ?> Hari</td>
    </tr>
    <tr class="text-center">
        <th class="text-start text-capitalize">jumlah hadir</th>
        <th class="text-center">:</th>
        <td class="text-start" style='height: 20px; padding:10px'><?= $model['jumlah_hadir'] ?> Hari</td>
    </tr>
    <tr class="text-center">
        <th class="text-start text-capitalize">jumlah sakit</th>
        <th class="text-center">:</th>
        <td class="text-start" style='height: 20px; padding:10px'><?= $model['jumlah_sakit'] ?> Hari</td>
    </tr>
    <tr class="text-center">
        <th class="text-start text-capitalize">jumlah WFH</th>
        <th class="text-center">:</th>
        <td class="text-start" style='height: 20px; padding:10px'><?= $model['jumlah_wfh'] ?> Hari</td>
    </tr>
    <tr class="text-center">
        <th class="text-start text-capitalize">jumlah cuti</th>
        <th class="text-center">:</th>
        <td class="text-start" style='height: 20px; padding:10px'><?= $model['jumlah_cuti'] ?> Hari</td>
    </tr>
    <tr class="text-center">
        <th class="text-start text-capitalize">gaji pokok</th>
        <th class="text-center">:</th>
        <td class="text-start" style='height: 20px; padding:10px'><?= 'Rp ' . number_format($model['gaji_pokok'], 0, ',', '.'); ?> </td>
    </tr>
    <tr class="text-center">
        <th class="text-start text-capitalize">jumlah jam lembur</th>
        <th class="text-center">:</th>
        <td class="text-start" style='height: 20px; padding:10px'><?= 'Rp ' . number_format($model['jumlah_jam_lembur'], 0, ',', '.'); ?> </td>
    </tr>
    <tr class="text-center">
        <th class="text-start text-capitalize">lembur perjam</th>
        <th class="text-center">:</th>
        <td class="text-start" style='height: 20px; padding:10px'><?= 'Rp ' . number_format($model['lembur_perjam'], 0, ',', '.'); ?> </td>
    </tr>
    <tr class="text-center">
        <th class="text-start text-capitalize">total lembur</th>
        <th class="text-center">:</th>
        <td class="text-start" style='height: 20px; padding:10px'><?= 'Rp ' . number_format($model['total_lembur'], 0, ',', '.'); ?> </td>
    </tr>
    <tr class="text-center">
        <th class="text-start text-capitalize">jumlah tunjangan</th>
        <th class="text-center">:</th>
        <td class="text-start" style='height: 20px; padding:10px'><?= 'Rp ' . number_format($model['jumlah_tunjangan'], 0, ',', '.'); ?> </td>
    </tr>
    <tr class="text-center">
        <th class="text-start text-capitalize">jumlah potongan</th>
        <th class="text-center">:</th>
        <td class="text-start" style='height: 20px; padding:10px'><?= 'Rp ' . number_format($model['jumlah_potongan'], 0, ',', '.'); ?> </td>
    </tr>
    <tr class="text-center">
        <th class="text-start text-capitalize">potongan wfh hari</th>
        <th class="text-center">:</th>
        <td class="text-start" style='height: 20px; padding:10px'><?= 'Rp ' . number_format($model['potongan_wfh_hari'], 0, ',', '.'); ?> </td>
    </tr>
    <tr class="text-center">
        <th class="text-start text-capitalize">jumlahpotonganwfh</th>
        <th class="text-center">:</th>
        <td class="text-start" style='height: 20px; padding:10px'><?= 'Rp ' . number_format($model['jumlah_potongan_wfh'], 0, ',', '.'); ?> </td>
    </tr>
    <tr class="text-center">
        <th class="text-start text-capitalize">gaji diterima</th>
        <th class="text-center">:</th>
        <td class="text-start" style='height: 20px; padding:10px'><?= 'Rp ' . number_format($model['gaji_diterima'], 0, ',', '.'); ?> </td>
    </tr>
    <tr class="text-center">
        <th class="text-start text-capitalize">Terbilang</th>
        <th class="text-center">:</th>
        <?php
        $terbilang = Terbilang::toTerbilang($model['gaji_diterima']) . ' Rupiah';
        ?>
        <td class="text-start" style='height: 20px; padding:10px'><strong><?= $terbilang ?></strong></td>
    </tr>
</table>