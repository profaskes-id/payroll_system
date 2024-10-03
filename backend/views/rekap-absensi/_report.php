<?php

use backend\models\Absensi;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var backend\models\AbsensiSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Rekap Absensi';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="absensi-index position-relative">


    <h2>Data Rekapan Absnesi</h2>
    <h4>Bulan : <?= date('F') ?></h4>
    <h4>tahun : <?= date('Y') ?></h4>




    <div class="table-container table-responsive">

        <table class="table table-bordered table-responsive">
            <tr>
                <th>Nama</th>
                <?php foreach ($tanggal_bulanan as $item) : ?>
                    <?php
                    $date = date_create($item . '-' . date('m-Y'));
                    $day_of_week = date_format($date, 'w');
                    ?>
                    <td <?php if ($day_of_week == 0) echo 'style="background-color: #be123c; color:white;"'; ?>>
                        <?= $item ?>
                    </td>
                <?php endforeach ?>
            </tr>

            <?php foreach ($hasil as $karyawan) : ?>
                <tr>
                    <?php foreach ($karyawan as $key => $data) : ?>

                        <?php if ($key == 0) : ?>
                            <td>
                                <?php $text  = strtolower($data); ?>
                                <span style="text-transform: capitalize;"><?= $text ?></span>
                            </td>

                        <?php else : ?>
                            <?php
                            $date = date_create($tanggal_bulanan[$key - 1]  . '-' . date('m-Y'));
                            $day_of_week = date_format($date, 'w');
                            ?>
                            <td <?php if ($day_of_week == 0) echo 'style="background-color: #be123c; color:white;"'; ?>>
                                <?php if ($data !== null && $data['status_hadir'] !== null && $data['jam_masuk_karyawan'] !== null):  ?>
                                    <?php
                                    $jamKerjakaryawan = $data['jam_masuk_karyawan'];
                                    $jamKerjaKantor = $data['jam_masuk_kantor'];

                                    $jam_masuk_kerja_time = strtotime($jamKerjakaryawan);
                                    $jam_karyawan_masuk_time = strtotime($jamKerjaKantor);
                                    ?>

                                    <?php if ($jam_karyawan_masuk_time <= $jam_masuk_kerja_time) : ?>
                                        <span style='color: red;'><?= $data['status_hadir'] ?></span>
                                    <?php else :  ?>
                                        <span style='color: black;'> <?= $data['status_hadir'] ?> </span>
                                    <?php endif ?>
                                <?php endif ?>
                            </td>
                        <?php endif ?>

                    <?php endforeach ?>
                </tr>
            <?php endforeach ?>
            <tr>
                <th style="font-size:11px; background-color: #ffde21; color:000">Rekapan Hadir</th>
                <?php foreach ($rekapanAbsensi as $key => $rekapan) :  ?>
                    <?php
                    $date = date_create($tanggal_bulanan[$key - 1]  . '-' . $bulan . '-' . $tahun);
                    $day_of_week = date_format($date, 'w');
                    ?>
                    <?php if ($day_of_week == 0): ?>
                        <td style="font-weight:600; background-color: #be123c; color:fff"><?= '' ?? '' ?></td>
                    <?php else : ?>
                        <td style="font-weight:600; background-color: #ffde21; color:000"><?= $rekapan ?? '0' ?></td>
                    <?php endif; ?>
                <?php endforeach ?>
            </tr>
        </table>
    </div>



</div>