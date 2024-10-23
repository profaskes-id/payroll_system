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


<div style="font-size: 10px" class=" absensi-index position-relative">

    <div class="table-container table-responsive">

        <table class="table table-bordered table-responsive " style="font-size: 10px;">
            <tr>
                <th rowspan="3" class="text-center ">Nama dan Kode Karyawan</th>
                <th rowspan="3" style="vertical-align: middle;" class="text-center ">Bagian dan jabatan </th>
            </tr>
            <tr>
                <th class="text-center" colspan="<?= count($tanggal_bulanan) + 3  ?>">
                    <h3>
                        Rekapan Absensi Bulan <?= $tanggal->getBulan($bulan) . ' Tahun ' . $tahun ?>
                    </h3>
                </th>
            </tr>
            <tr class="text-center" style="vertical-align: middle;">
                <?php foreach ($tanggal_bulanan as $key => $item) : ?>
                    <?php
                    if (!isset($tanggal_bulanan[$key - 1])) {
                        $day_of_week = 1;
                    } else {
                        $date = date_create($tanggal_bulanan[$key]  . '-' . $bulan . '-' . $tahun);
                        $day_of_week = date_format($date, 'w');
                    }
                    ?>
                    <td <?php if ($day_of_week == 0) echo 'style="background-color: #aaa; color:white;"'; ?>>
                        <?= $item ?>
                    </td>
                <?php endforeach ?>
                <td>Hadir</td>
                <td>Terlamat</td>
                <td>Total Terlambat</td>
            </tr>

            <?php foreach ($hasil as $karyawan) : ?>
                <tr class="text-center">
                    <?php foreach ($karyawan as $key => $data) : ?>

                        <?php if ($key == 0) : ?>
                            <td style="width: 150px;">
                                <?php $text  = strtolower($data['nama']); ?>
                                <div class=" d-flex flex-column">
                                    <p style="margin: 0; padding:0;  text-transform: capitalize;  font-weight: bold"><?= $text ?></p>

                                    <p style="margin: 0; padding:0;  text-transform: capitalize;"><?= $data['kode_karyawan'] ?></p>
                                </div>
                            </td>
                            <td style="width: 100px;">
                                <div>
                                    <p style="margin: 0; padding:0;  text-transform: capitalize; "><?= $data['bagian'] ?></p>
                                    <p style="margin: 0; padding:0;  text-transform: capitalize;"><?= $data['jabatan'] ?></p>
                                </div>
                            </td>

                        <?php else : ?>
                            <?php
                            if (!isset($tanggal_bulanan[$key - 1])) {
                                $day_of_week = 1;
                            } else {
                                $date = date_create($tanggal_bulanan[$key - 1]  . '-' . $bulan . '-' . $tahun);
                                $day_of_week = date_format($date, 'w');
                            }
                            ?>
                            <td <?php if ($day_of_week == 0) echo 'style="background-color: #aaa; color:white;"'; ?>>
                                <p style="width: 20px; padding:0;  text-align: center; vertical-align: middle;">
                                    <?php if ($key == (count($karyawan) - 1)): ?>

                                        <?php
                                        $jam = floor($data['detik_terlambat'] / 3600); // Menghitung jam
                                        $menit = floor(($data['detik_terlambat'] % 3600) / 60); // Menghitung menit
                                        $detik = $data['detik_terlambat'] % 60; // Menghitung detik

                                        $formattedTime = sprintf('%02d:%02d:%02d', $jam, $menit, $detik);
                                        ?>

                                        <span style="font-weight:600; text-align:center; "><?= $formattedTime ?></span>
                                    <?php endif; ?>
                                    <?php if ($key == (count($karyawan) - 2)): ?>
                                        <?= $data['total_terlambat'] ?>
                                    <?php endif; ?>
                                    <?php if ($key == (count($karyawan) - 3)): ?>
                                        <?= $data['total_hadir'] ?>
                                    <?php endif; ?>
                                    <?php if ($data !== null && $data['status_hadir'] !== null && $data['jam_masuk_karyawan'] !== null): ?>
                                        <?php
                                        $jamKerjakaryawan = $data['jam_masuk_karyawan']; //karyawan masuj
                                        $jamKerjaKantor = $data['jam_masuk_kantor']; // jam kantor
                                        $karyawan_absen_pada = strtotime($jamKerjakaryawan);
                                        $jam_kantor_masuk = strtotime($jamKerjaKantor);
                                        ?>

                                        <?php if ($karyawan_absen_pada > $jam_kantor_masuk) : ?>
                                            <span style='color: red;'>H</span><br />
                                        <?php else : ?>
                                            <span style='color: black;'><?= $data['status_hadir'] ?></span>
                                        <?php endif; ?>
                                    <?php else : ?>
                                    <?php endif; ?>
                                </p>
                            </td>
                        <?php endif ?>

                    <?php endforeach ?>
                </tr>
            <?php endforeach ?>
            <tr>
                <th style="font-size:10px; background-color: #ffde21; color:#000">Rekapan Hadir</th>
                <th style="font-size:10px; background-color: #ffde21; color:#fff"></th>
                <?php foreach ($rekapanAbsensi as $key => $rekapan) :  ?>
                    <?php
                    if (!isset($tanggal_bulanan[$key])) {
                        $day_of_week = 1;
                    } else {
                        $date = date_create($tanggal_bulanan[$key - 1]  . '-' . $bulan . '-' . $tahun);
                        $day_of_week = date_format($date, 'w');
                    }
                    ?>
                    <?php if ($day_of_week == 0): ?>
                        <td style="font-weight:600; text-align:center; background-color: #aaa; color:fff"><?= '' ?? '' ?></td>
                    <?php else : ?>
                        <td style="font-weight:600; text-align:center; background-color: #ffde21; color:000"><?= $rekapan ?? '0' ?></td>
                    <?php endif; ?>
                <?php endforeach ?>
            </tr>
        </table>
    </div>



</div>