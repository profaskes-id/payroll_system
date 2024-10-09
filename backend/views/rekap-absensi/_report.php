<?php

use backend\models\Absensi;
use backend\models\Tanggal;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var backend\models\AbsensiSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Rekap Absensi';
$this->params['breadcrumbs'][] = $this->title;
$tanggal = new Tanggal();

?>


<div style="font-size: 12px" class=" absensi-index position-relative">

    <div class="table-container table-responsive">

        <table class="table table-bordered table-responsive">
            <tr>
                <th rowspan="3" class="text-center ">Nama dan Kode Karyawan</th>
                <th rowspan="3" style="vertical-align: middle;" class="text-center ">Bagian </th>
                <th rowspan="3" style="vertical-align: middle;" class="text-center ">Jabatan </th>
            </tr>
            <tr>
                <th class="text-center" colspan="<?= count($tanggal_bulanan) ?>">
                    <h3>
                        Rekapan Absensi Bulan <?= $tanggal->getBulan($bulan) . ' Tahun ' . $tahun ?>
                    </h3>
                </th>
            </tr>
            <tr>
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
                                <?php $text  = strtolower($data['nama']); ?>
                                <div class="d-flex flex-column">
                                    <p style="margin: 0; font-size:12px; padding:0;  text-transform: capitalize;  font-weight: bold"><?= $text ?></p>

                                    <p style="margin: 0; font-size:12px; padding:0;  text-transform: capitalize;"><?= $data['kode_karyawan'] ?></p>
                                </div>
                            </td>
                            <td>
                                <div class="">
                                    <p style="margin: 0; font-size:12px; padding:0;  text-transform: capitalize; "><?= $data['bagian'] ?></p>
                                </div>
                            </td>
                            <td>

                                <div class="">
                                    <p style="margin: 0; font-size:12px; padding:0;  text-transform: capitalize;"><?= $data['jabatan'] ?></p>
                                </div>
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
                <th style="font-size:11px; background-color: #ffde21; color:#000">Rekapan Hadir</th>
                <th style="font-size:11px; background-color: #ffde21; color:#fff"></th>
                <th style="font-size:11px; background-color: #ffde21; color:#fff"></th> <?php foreach ($rekapanAbsensi as $key => $rekapan) :  ?>
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