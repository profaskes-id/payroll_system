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

function formatJamDesimal($decimalHours)
{
    // Handle null
    if ($decimalHours === null) {
        return '00:00';
    }

    // Handle empty string or non-numeric values
    if (!is_numeric($decimalHours)) {
        return '00:00';
    }

    // Handle 0
    if ((float)$decimalHours == 0) {
        return '00:00';
    }

    $decimalHours = (float)$decimalHours;
    $hours = floor(abs($decimalHours));
    $minutes = round((abs($decimalHours) - $hours) * 60);

    // Handle overflow minutes (jika menit >= 60)
    if ($minutes >= 60) {
        $hours += 1;
        $minutes -= 60;
    }

    // Handle negative values
    $sign = ($decimalHours < 0) ? '-' : '';

    return $sign . sprintf("%02d:%02d", $hours, $minutes);
}


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
        <?php
        // Pastikan nilai parameter ada atau kosong jika tidak ada
        $params = [];
        $params['tanggal_awal'] = isset($tanggal_awal) ? $tanggal_awal : '';
        $params['tanggal_akhir'] = isset($tanggal_akhir) ? $tanggal_akhir : '';
        ?>

        <div class="mt-2 mt-md-0 col-md-2">
            <p class="d-block">
                <?= Html::a(
                    'Print to PDF <i class="fa fa-print"></i>',
                    array_merge(['report'], $params),
                    ['target' => '_blank', 'class' => 'cetak-button']
                ) ?>
            </p>
        </div>
        <div class="mt-2 mt-md-0 col-md-2">
            <p class="d-block">
                <?= Html::a(
                    'Export to exel <i class="fa fa-table"></i>',
                    array_merge(['exel'], $params),
                    ['target' => '_blank', 'class' => 'tambah-button']
                ) ?>
            </p>
        </div>
    </div>

    <div style="margin-top: 10px;">
        <div class="collapse width" id="collapseWidthExample">
            <div style="width: 100%;">
                <?= $this->render('_search', ['tanggal_awal' => $tanggal_awal, 'tanggal_akhir' => $tanggal_akhir]) ?>
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
                            Rekapan Absensi
                        </h3>
                    </th>
                </tr>
                <tr class="text-center" style="vertical-align: middle;">
                    <?php foreach ($tanggal_bulanan as $key => $item) : ?>
                        <?php
                        $day_of_week = 1; // default: Senin
                        if (isset($tanggal_bulanan[$key]) && !empty($tanggal_bulanan[$key])) {
                            $date = date_create($tanggal_bulanan[$key]);
                            if ($date) {
                                $day_of_week = (int) date_format($date, 'w');
                            }
                        }


                        ?>
                        <td <?php if ($day_of_week == 0) echo 'style="background-color: #ababab; color:white;"'; ?>>
                            <?= $item ?>
                        </td>
                    <?php endforeach ?>
                    <td style="background-color: #f8f9fa; font-weight: bold; border: 1px solid #dee2e6;">Total Hadir <span class="text-sm">(Hari)</span></td>
                    <td style="background-color: #f8f9fa; font-weight: bold; border: 1px solid #dee2e6;">Jumlah Terlambat <span class="text-sm">(X)</span></td>
                    <td style="background-color: #f8f9fa; font-weight: bold; border: 1px solid #dee2e6;">Total Telambat <span class="text-sm">(Jam)</span></td>
                    <td style="background-color: #f8f9fa; font-weight: bold; border: 1px solid #dee2e6;">Total Lembur (X)</td>
                    <td style="background-color: #f8f9fa; font-weight: bold; border: 1px solid #dee2e6;">Lama Lembur (jam)</td>
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

                                $day_of_week = 1; // default: Senin
                                if (isset($tanggal_bulanan[$key - 1]) && !empty($tanggal_bulanan[$key - 1])) {
                                    $date = date_create($tanggal_bulanan[$key - 1]);
                                    if ($date) {
                                        $day_of_week = (int) date_format($date, 'w');
                                    }
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
                                                echo formatJamDesimal($data['jumlah_jam_lembur']);
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
                                                <span style='color: red;'>
                                                    <?= isset($data['lama_terlambat']) && $data['lama_terlambat'] ? date('H:i', strtotime($data['lama_terlambat'])) : '00:00' ?>
                                                </span><br />

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


            <!-- Footer Section -->
            <!-- 1. Total Hadir -->
            <tr>
                <th style="font-size:13px; background-color: #facc15; color:#000; border:1px solid #000">Hadir</th>
                <th style="font-size:11px; background-color: #facc15; color:#000; border:1px solid #000"></th>

                <?php foreach ($rekapanAbsensi as $key => $rekapan) : ?>
                    <td style="font-weight:600; text-align:center; background-color: #facc15; color:#000; border:1px solid #000">
                        <?= $rekapan ? ($rekapan > 0 ? $rekapan : '0') : '0' ?>
                    </td>
                <?php endforeach; ?>

                <!-- Summary columns -->
                <td colspan="5" style="font-weight:bold; text-align:center; background-color: #dee2e6; color:#000; border:1px solid #000">
                </td>

            </tr>

            <!-- 2. Tidak Hadir -->
            <tr>
                <th style="font-size:13px; background-color: #84cc16; color:#000; border:1px solid #000">Tidak Hadir</th>
                <th style="font-size:11px; background-color: #84cc16; color:#000; border:1px solid #000"></th>

                <?php foreach ($rekapanAbsensi as $key => $rekapan) : ?>
                    <td style="font-weight:600; text-align:center; background-color: #84cc16; color:#000; border:1px solid #000">
                        <?= (isset($hasil) && is_array($hasil)) ? max(0, (count($hasil) - $rekapan)) : '0' ?>
                    </td>
                <?php endforeach; ?>

                <!-- Summary columns -->
                <td colspan="5" style="font-weight:bold; text-align:center; background-color: #dee2e6; color:#000; border:1px solid #000">
                </td>
            </tr>

            <!-- 3. Terlambat -->
            <tr>
                <th style="font-size:13px; background-color: #f43f5e; color:#fff; border:1px solid #000">Terlambat</th>
                <th style="font-size:11px; background-color: #f43f5e; color:#fff; border:1px solid #000"></th>

                <?php foreach ($keterlambatanPerTanggal as $key => $terlambattgl) : ?>
                    <?php
                    $dataTerlambat = ($terlambattgl && $terlambattgl > 0) ? $terlambattgl : '0';
                    ?>
                    <td style="font-weight:600; text-align:center; background-color: #f43f5e; color:#fff; border:1px solid #000">
                        <?= $dataTerlambat ?>
                    </td>
                <?php endforeach; ?>

                <!-- Summary columns -->
                <td colspan="5" style="font-weight:bold; text-align:center; background-color: #dee2e6; color:#000; border:1px solid #000">
                </td>
        </table>
    </div>
</div>