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
$tanggal = new Tanggal;

?>
<div class="absensi-index position-relative">
    <div class="row">
        <div class="col-9 col-md-10">
            <button style="width: 100%;" class="add-button" type="submit" data-toggle="collapse" data-target="#collapseWidthExample" aria-expanded="false" aria-controls="collapseWidthExample">
                <i class="fas fa-search"></i>
                <span>
                    Search
                </span>
            </button>
        </div>
        <div class="col-2">
            <p class="d-inline-block">
                <?= Html::a('Print PDF <i class="fa fa-print"></i>', ['report'], ['target' => '_blank', 'class' => 'cetak-button']) ?>
            </p>
        </div>
    </div>

    <div style="margin-top: 10px;">
        <div class="collapse width" id="collapseWidthExample">
            <div class="" style="width: 100%;">
                <?php echo $this->render('_search', ['bulan' => $bulan, 'tahun' => $tahun,]) ?>
            </div>
        </div>
    </div>




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
            <tr class="text-center" style="vertical-align: middle;">
                <?php foreach ($tanggal_bulanan as $item) : ?>
                    <?php
                    $date = date_create($item . '-' . $bulan . '-' . $tahun);
                    $day_of_week = date_format($date, 'w');
                    ?>
                    <td <?php if ($day_of_week == 0) echo 'style="background-color: #aaa; color:white;"'; ?>>
                        <?= $item ?>
                    </td>
                <?php endforeach ?>
            </tr>

            <?php foreach ($hasil as $karyawan) : ?>
                <tr style="vertical-align: middle;">
                    <?php foreach ($karyawan as $key => $data) : ?>

                        <?php if ($key == 0) : ?>
                            <td>
                                <?php $text  = strtolower($data['nama']); ?>
                                <div class=" d-flex flex-column">
                                    <p style="margin: 0; padding:0;  text-transform: capitalize;  font-weight: bold"><?= $text ?></p>

                                    <p style="margin: 0; padding:0;  text-transform: capitalize;"><?= $data['kode_karyawan'] ?></p>
                                </div>
                            </td>
                            <td>
                                <div class="">
                                    <p style="margin: 0; padding:0;  text-transform: capitalize; "><?= $data['bagian'] ?></p>
                                </div>
                            </td>
                            <td>

                                <div class="">
                                    <p style="margin: 0; padding:0;  text-transform: capitalize;"><?= $data['jabatan'] ?></p>
                                </div>
                            </td>


                        <?php else : ?>
                            <?php
                            $date = date_create($tanggal_bulanan[$key - 1]  . '-' . $bulan . '-' . $tahun);
                            $day_of_week = date_format($date, 'w');
                            ?>
                            <td <?php if ($day_of_week == 0) echo 'style="background-color: #aaa; color:white;"'; ?>>
                                <p style="width: 40px; text-align: center; vertical-align: middle;">
                                    <?php if ($data !== null && $data['status_hadir'] !== null && $data['jam_masuk_karyawan'] !== null): ?>
                                        <?php
                                        $jamKerjakaryawan = $data['jam_masuk_karyawan'];
                                        $jamKerjaKantor = $data['jam_masuk_kantor'];

                                        $jam_masuk_kerja_time = strtotime($jamKerjakaryawan);
                                        $jam_karyawan_masuk_time = strtotime($jamKerjaKantor);
                                        ?>

                                        <?php if ($jam_karyawan_masuk_time > $jam_masuk_kerja_time) : ?>
                                            <span style='color: red;'><?= $data['status_hadir'] ?></span><br />
                                            <?php
                                            // Hitung selisih waktu
                                            $selisih_detik = $jam_karyawan_masuk_time - $jam_masuk_kerja_time;
                                            $menit = floor($selisih_detik / 60);
                                            $detik = $selisih_detik % 60;

                                            // Tambahkan nol di depan jika kurang dari 10
                                            $menit = str_pad($menit, 2, '0', STR_PAD_LEFT);
                                            $detik = str_pad($detik, 2, '0', STR_PAD_LEFT);
                                            ?>
                                            <span style='color: red; font-size: 12px; display: flex;'>+<?= $menit ?>:<?= $detik ?></span>
                                        <?php else : ?>
                                            <span style='color: black;'><?= $data['status_hadir'] ?></span>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </p>



                            <?php endif ?>
                            </td>

                        <?php endforeach ?>
                </tr>
            <?php endforeach ?>
            <tr>
                <th style="font-size:11px; background-color: #ffde21; color:#000">Rekapan Hadir</th>
                <th style="font-size:11px; background-color: #ffde21; color:#fff"></th>
                <th style="font-size:11px; background-color: #ffde21; color:#fff"></th>
                <?php foreach ($rekapanAbsensi as $key => $rekapan) :  ?>
                    <?php
                    $date = date_create($tanggal_bulanan[$key - 1]  . '-' . $bulan . '-' . $tahun);
                    $day_of_week = date_format($date, 'w');
                    ?>
                    <?php if ($day_of_week == 0): ?>
                        <td style="font-weight:600; background-color: #aaa; color:fff"><?= '' ?? '' ?></td>
                    <?php else : ?>
                        <td style="font-weight:600; background-color: #ffde21; color:000"><?= $rekapan ?? '0' ?></td>
                    <?php endif; ?>
                <?php endforeach ?>
            </tr>
        </table>
    </div>
</div>