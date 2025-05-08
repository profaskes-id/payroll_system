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
        <div class="col-12 col-md-8">
            <button style="width: 100%;" class="add-button" type="submit" data-toggle="collapse" data-target="#collapseWidthExample" aria-expanded="false" aria-controls="collapseWidthExample">
                <i class="fas fa-search"></i>
                <span>
                    Search
                </span>
            </button>
        </div>
        <div class="col-3 col-md-2">
            <p class="d-block ">
                <?= Html::a('Print to PDF <i class="fa fa-print"></i>', ['report'], ['target' => '_blank', 'class' => 'cetak-button']) ?>
            </p>
        </div>
        <div class="col-3 col-md-2">
            <p class="d-block">
                <?= Html::a('Export to exel <i class="fa fa-table"></i>', ['exel'], ['target' => '_blank', 'class' => 'tambah-button']) ?>
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
        <p class="text-xs text-muted text-capitalize">jika data karyawan tidak ada, silahkan tambahkan data jam kerja karyawan</p>
        <table class="table table-bordered table-responsive">
            <thead>

                <tr>
                    <th rowspan="3" class="text-center ">Nama dan Kode Karyawan</th>
                    <th rowspan="3" style="vertical-align: middle;" class="text-center ">Bagian & Jabatan</th>
                </tr>
                <tr>
                    <th class="text-center" colspan="<?= count($tanggal_bulanan) + 6 - 1  ?>">
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
                    <td>Total Hadir</td>
                    <td>Jumlah Terlambat</td>
                    <td>Total Telambat</td>
                    <td>Total Lembur</td>
                    <td>Lama Lembur</td>
                </tr>
            </thead>

            <tbody>
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
                                        <p style="font-size: 11px; margin: 0; padding:0;  text-transform: capitalize; "><?= $data['bagian'] ?></p>
                                        <hr style="margin:2px 0; padding:0;">
                                        <p style="font-size: 11px; margin: 0; padding:0;  text-transform: capitalize;"><?= $data['jabatan'] ?></p>
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

                                <td <?php if ($day_of_week == 0) echo 'style="  background-color: #aaa; color:white;"'; ?>>
                                    <p style=" width: 50px; padding:0;   text-align: center; vertical-align: middle;">

                                        <!-- bagian akhir rekap absensi -->
                                        <?php
                                        $lastIndex = count($karyawan);
                                        switch ($key) {
                                            case $lastIndex - 5:
                                                echo $data['total_hadir'];
                                                break;
                                            case $lastIndex - 4:
                                                echo $data['total_terlambat'];
                                                break;
                                            case $lastIndex - 3:
                                                $jam = floor($data['detik_terlambat'] / 3600);
                                                $menit = floor(($data['detik_terlambat'] % 3600) / 60);
                                                $detik = $data['detik_terlambat'] % 60;
                                                $formattedTime = sprintf('%02d:%02d:%02d', $jam, $menit, $detik);
                                                echo "<span style='font-weight:600; text-align:center;'>$formattedTime</span>";
                                                break;
                                            case $lastIndex - 2:
                                                echo $data['total_lembur'];
                                                break;
                                            case $lastIndex - 1:
                                                echo $data['jumlah_jam_lembur'];
                                                break;
                                        }
                                        ?>


                                        <?php if ($data !== null && $data['status_hadir'] !== null && $data['jam_masuk_karyawan'] !== null): ?>
                                            <?php
                                            $jamKerjakaryawan = $data['jam_masuk_karyawan']; //karyawan masuj
                                            $jamKerjaKantor = $data['jam_masuk_kantor']; // jam kantor
                                            $karyawan_absen_pada = strtotime($jamKerjakaryawan);

                                            $jam_kantor_masuk = strtotime($jamKerjaKantor ?? "08:00:00");
                                            ?>

                                            <?php if ($data['is_lembur'] == 1) : ?>
                                                <span style='color: black;'><?= $data['status_hadir'] ?></span><br />
                                                <span style='color: black;'>Lembur</span><br />

                                            <?php elseif ($data['is_wfh'] == 1) : ?>
                                                <span style='color: blue; font-weight:700;'><?= $data['status_hadir'] ?></span><br />
                                                <span style='color: blue; font-weight:700;'>WFH</span><br />

                                            <?php elseif ($data['is_24jam'] == 1) : ?>
                                                <span style='color: green; font-weight:700;'><?= $data['status_hadir'] ?></span><br />
                                                <span style='color: green; font-weight:700;'>24 Jam</span><br />


                                            <?php elseif ($data['is_terlambat'] == 1) : ?>
                                                <span style='color: red;'><?= $data['status_hadir'] ?></span><br />
                                                <span style='color: red;'><?= $data['lama_terlambat'] ?></span><br />

                                            <?php else : ?>
                                                <span style='color: black;'><?= $data['status_hadir'] ?></span>
                                            <?php endif; ?>
                                        <?php else : ?>
                                        <?php endif; ?>
                                    </p>
                                <?php endif ?>
                                </td>

                            <?php endforeach ?>
                    </tr>
                <?php endforeach ?>
            </tbody>


            <!-- 1 -->
            <tr>
                <th style="font-size:13px; background-color: #facc15; color:#000">Hadir</th>
                <th style="font-size:11px; background-color: #facc15; color:#000"></th>
                <?php
                $lastKey = array_key_last($rekapanAbsensi); // Mendapatkan kunci terakhir
                foreach ($rekapanAbsensi as $key => $rekapan) :
                    if (!isset($tanggal_bulanan[$key])) {
                        $day_of_week = 1;
                    } else {
                        $date = date_create($tanggal_bulanan[$key - 1] . '-' . $bulan . '-' . $tahun);
                        $day_of_week = date_format($date, 'w');
                    }
                ?>
                    <?php if ($day_of_week == 0): ?>
                        <td style="font-weight:600; text-align:center; background-color: #aaa; color:#000"><?= '' ?? '' ?></td>
                    <?php else : ?>
                        <?php
                        // Tentukan warna background
                        $bgColor = ($key === $lastKey) ? 'fff' : '#facc15'; // Ubah menjadi lightblue jika ini adalah elemen terakhir
                        ?>
                        <td style="font-weight:600; text-align:center; background-color: <?= $bgColor ?>; color:#000"><?= $rekapan ? ($rekapan > 0 ? $rekapan : '') : '' ?></td>
                    <?php endif; ?>
                <?php endforeach ?>
                <td style="font-weight:600; text-align:center; background-color: #fff; color:#000"></td>
                <!-- <td style="font-weight:600; text-align:center; background-color: #fff; color:#000"></td> -->
            </tr>

            <!--  2 -->
            <tr>
                <th style="font-size:13px; background-color: #84cc16; color:#000">Tidak Hadir</th>
                <th style="font-size:13px; background-color: #84cc16; color:#000"></th>
                <?php
                $lastKey = array_key_last($rekapanAbsensi); // Mendapatkan kunci terakhir
                foreach ($rekapanAbsensi as $key => $rekapan) :
                    if (!isset($tanggal_bulanan[$key])) {
                        $day_of_week = 1;
                    } else {
                        $date = date_create($tanggal_bulanan[$key - 1] . '-' . $bulan . '-' . $tahun);
                        $day_of_week = date_format($date, 'w');
                    }
                ?>
                    <?php if ($day_of_week == 0): ?>
                        <td style="font-weight:600; text-align:center; background-color: #aaa; color:#000"><?= '' ?? '' ?></td>
                    <?php else : ?>
                        <?php
                        $data = ($rekapan && $rekapan > 0) ? $karyawanTotal - $rekapan : '';
                        $data = ($data < 0) ? '' : $data;

                        // Tentukan background color
                        $bgColor = ($key === $lastKey) ? '#fff' : '#84cc16'; // Ubah menjadi putih jika ini adalah elemen terakhir
                        ?>
                        <td style="font-weight:600; text-align:center; background-color: <?= $bgColor ?>; color:#000"><?php echo $data ?></td>
                    <?php endif; ?>
                <?php endforeach ?>
                <td style="font-weight:600; text-align:center; background-color: #fff; color:#000"></td>
                <!-- <td style="font-weight:600; text-align:center; background-color: #fff; color:#000"></td> -->
            </tr>

            <!-- 3 -->
            <tr>
                <th style="font-size:13px; background-color: #f43f5e; color:#fff">Terlambat</th>
                <th style="font-size:13px; background-color: #f43f5e; color:#fff"></th>
                <?php foreach ($keterlambatanPerTanggal as $key => $terlambattgl) :  ?>
                    <?php
                    if (!isset($tanggal_bulanan[$key])) {
                        $day_of_week = 1;
                    } else {
                        $date = date_create($tanggal_bulanan[$key - 1]  . '-' . $bulan . '-' . $tahun);
                        $day_of_week = date_format($date, 'w');
                    }
                    ?>
                    <?php if ($day_of_week == 0): ?>
                        <td style="font-weight:600; text-align:center; background-color: #aaa; color:#fff"><?= '' ?? '' ?></td>
                    <?php else : ?>
                        <?php
                        $dataTerlambat = ($terlambattgl && $terlambattgl > 0) ?  $terlambattgl : '';
                        $dataTerlambat = ($dataTerlambat <= 0) ? '' : $dataTerlambat;
                        ?>
                        <td style="font-weight:600; text-align:center; background-color: #f43f5e; color:#fff"><?php echo $dataTerlambat ?></td>
                    <?php endif; ?>
                <?php endforeach ?>
                <td style="font-weight:600; text-align:center; background-color: #fff; color:#fff"></td>
                <td style="font-weight:600; text-align:center; background-color: #fff; color:#fff"></td>
                <!-- <td style="font-weight:600; text-align:center; background-color: #fff; color:#fff"></td> -->
            </tr>
        </table>
    </div>
</div>