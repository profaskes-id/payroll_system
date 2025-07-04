<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<?php
ob_start(); // start output buffering
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=Rekapan-data-karyawan.xls");
?>


<style>
    td,
    th {
        mso-number-format: "\@";
    }
</style>


<?php

use backend\models\Tanggal;
use yii\helpers\Html;

$tanggalFormater = new Tanggal();
?>

<div style="width: 100%; overflow-x: auto; margin-top: 20px;">
    <table style="width: 100%; border-collapse: collapse; border: 1px solid #ccc; font-size: 13px;">
        <thead>
            <tr style="background-color: #f2f2f2;">
                <th style="border: 1px solid #ccc; padding: 6px;">No</th>
                <th style="border: 1px solid #ccc; padding: 6px;">Kode</th>
                <th style="border: 1px solid #ccc; padding: 6px;">Nama</th>
                <th style="border: 1px solid #ccc; padding: 6px;">Jenis Kelamin</th>
                <th style="border: 1px solid #ccc; padding: 6px;">Tempat Lahir</th>
                <th style="border: 1px solid #ccc; padding: 6px;">Tanggal Lahir</th>
                <th style="border: 1px solid #ccc; padding: 6px;">Status Nikah</th>
                <th style="border: 1px solid #ccc; padding: 6px;">Agama</th>
                <th style="border: 1px solid #ccc; padding: 6px;">Suku</th>
                <th style="border: 1px solid #ccc; padding: 6px;">Negara</th>

                <!-- Identitas -->
                <th style="border: 1px solid #ccc; padding: 6px;">Provinsi Identitas</th>
                <th style="border: 1px solid #ccc; padding: 6px;">Kabupaten Identitas</th>
                <th style="border: 1px solid #ccc; padding: 6px;">Kecamatan Identitas</th>
                <th style="border: 1px solid #ccc; padding: 6px;">Desa Identitas</th>
                <th style="border: 1px solid #ccc; padding: 6px;">Alamat Identitas</th>
                <th style="border: 1px solid #ccc; padding: 6px;">RT/RW Identitas</th>
                <th style="border: 1px solid #ccc; padding: 6px;">Kode Pos Identitas</th>

                <!-- Domisili -->
                <th style="border: 1px solid #ccc; padding: 6px;">Provinsi Domisili</th>
                <th style="border: 1px solid #ccc; padding: 6px;">Kabupaten Domisili</th>
                <th style="border: 1px solid #ccc; padding: 6px;">Kecamatan Domisili</th>
                <th style="border: 1px solid #ccc; padding: 6px;">Desa Domisili</th>
                <th style="border: 1px solid #ccc; padding: 6px;">Alamat Domisili</th>
                <th style="border: 1px solid #ccc; padding: 6px;">RT/RW Domisili</th>
                <th style="border: 1px solid #ccc; padding: 6px;">Kode Pos Domisili</th>

                <th style="border: 1px solid #ccc; padding: 6px;">Informasi Lain</th>
                <th style="border: 1px solid #ccc; padding: 6px;">status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($dataProvider->getModels() as $index => $model): ?>
                <tr>
                    <td style="border: 1px solid #ccc; padding: 6px;"><?= $index + 1 ?></td>
                    <td style="border: 1px solid #ccc; padding: 6px;"><?= Html::encode($model->kode_karyawan) ?></td>
                    <td style="border: 1px solid #ccc; padding: 6px;"><?= Html::encode($model->nama) ?></td>
                    <td style="border: 1px solid #ccc; padding: 6px;"><?= $model->kode_jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' ?></td>
                    <td style="border: 1px solid #ccc; padding: 6px;"><?= Html::encode($model->tempat_lahir) ?></td>
                    <td style="border: 1px solid #ccc; padding: 6px;"><?= $tanggalFormater->getIndonesiaFormatTanggal($model->tanggal_lahir) ?></td>
                    <td style="border: 1px solid #ccc; padding: 6px;"><?= Html::encode($model->statusNikah->nama_kode) ?></td>
                    <td style="border: 1px solid #ccc; padding: 6px;"><?= Html::encode($model->masterAgama->nama_kode) ?></td>
                    <td style="border: 1px solid #ccc; padding: 6px;"><?= Html::encode($model->suku) ?></td>
                    <td style="border: 1px solid #ccc; padding: 6px;"><?= Html::encode($model->kode_negara) ?></td>

                    <!-- Identitas -->
                    <td style="border: 1px solid #ccc; padding: 6px;">
                        <?= Html::encode(
                            !$model->is_current_domisili
                                ? ($model->provinsidomisili->nama_prop ?? '-')
                                : ($model->provinsiidentitas->nama_prop ?? '-')
                        ) ?>
                    </td>

                    <!-- Kabupaten / Kota -->
                    <td style="border: 1px solid #ccc; padding: 6px;">
                        <?= Html::encode(
                            !$model->is_current_domisili
                                ? ($model->kabupatendomisili->nama_kab ?? '-')
                                : ($model->kabupatenidentitas->nama_kab ?? '-')
                        ) ?>
                    </td>

                    <!-- Kecamatan -->
                    <td style="border: 1px solid #ccc; padding: 6px;">
                        <?= Html::encode(
                            !$model->is_current_domisili
                                ? ($model->kecamatandomisili->nama_kec ?? '-')
                                : ($model->kecamatanidentitas->nama_kec ?? '-')
                        ) ?>
                    </td>

                    <td style="border: 1px solid #ccc; padding: 6px;"><?= Html::encode($model->desa_lurah_identitas) ?></td>
                    <td style="border: 1px solid #ccc; padding: 6px;"><?= Html::encode($model->alamat_identitas) ?></td>
                    <td style="border: 1px solid #ccc; padding: 6px;"><?= $model->rt_identitas ?>/<?= $model->rw_identitas ?></td>
                    <td style="border: 1px solid #ccc; padding: 6px;"><?= Html::encode($model->kode_post_identitas) ?></td>

                    <!-- Domisili -->
                    <!-- Provinsi -->
                    <td style="border: 1px solid #ccc; padding: 6px;">
                        <?= Html::encode(
                            $model->is_current_domisili
                                ? ($model->provinsidomisili->nama_prop ?? '-')
                                : ($model->provinsiidentitas->nama_prop ?? '-')
                        ) ?>
                    </td>

                    <!-- Kabupaten / Kota -->
                    <td style="border: 1px solid #ccc; padding: 6px;">
                        <?= Html::encode(
                            $model->is_current_domisili
                                ? ($model->kabupatendomisili->nama_kab ?? '-')
                                : ($model->kabupatenidentitas->nama_kab ?? '-')
                        ) ?>
                    </td>

                    <!-- Kecamatan -->
                    <td style="border: 1px solid #ccc; padding: 6px;">
                        <?= Html::encode(
                            $model->is_current_domisili
                                ? ($model->kecamatandomisili->nama_kec ?? '-')
                                : ($model->kecamatanidentitas->nama_kec ?? '-')
                        ) ?>
                    </td>

                    <!-- <td style="border: 1px solid #ccc; padding: 6px;"><?php // Html::encode($model->desa_lurah_domisili) 
                                                                            ?></td> -->
                    <td style="border: 1px solid #ccc; padding: 6px;"><?= Html::encode($model->desa_lurah_domisili) ?></td>
                    <td style="border: 1px solid #ccc; padding: 6px;"><?= Html::encode($model->alamat_domisili) ?></td>
                    <td style="border: 1px solid #ccc; padding: 6px;"><?= $model->rt_domisili ?>/<?= $model->rw_domisili ?></td>
                    <td style="border: 1px solid #ccc; padding: 6px;"><?= Html::encode($model->kode_post_domisili) ?></td>

                    <td style="border: 1px solid #ccc; padding: 6px;"><?= Html::encode($model->informasi_lain) ?></td>
                    <td style="border: 1px solid #ccc; padding: 6px; text-align:center;">
                        <?= $model->is_aktif == 1 ? '<span style="color: green;">Aktif</span>' : '<span style="color: red;">Resign</span>' ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>