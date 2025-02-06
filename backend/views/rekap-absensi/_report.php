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

        <table style="font-size: 12px; border: 1px solid gray; width:100%;">
            <tr style=" border: 1px solid #252525;">

                <th rowspan="3" class="text-center ">Nama dan Kode Karyawan</th>
                <th rowspan="3" style="vertical-align: middle;" class="text-center ">Bagian & Jabatan</th>
            </tr>
            <tr style="border: 1px solid #252525;">
                <th class="text-center" colspan="<?= count($tanggal_bulanan) + 3  ?>">
                    <h3>
                        Rekapan Absensi Bulan <?= $tanggal->getBulan($bulan) . ' Tahun ' . $tahun ?>
                    </h3>
                </th>
            </tr>
            <tr style="border: 1px solid #252525; text-align:center;">
                <?php foreach ($tanggal_bulanan as $key => $item) : ?>
                    <?php
                    if (!isset($tanggal_bulanan[$key - 1])) {
                        $day_of_week = 1;
                    } else {
                        $date = date_create($tanggal_bulanan[$key]  . '-' . $bulan . '-' . $tahun);
                        $day_of_week = date_format($date, 'w');
                    }
                    ?>

                    <?php if ($day_of_week == 0): ?>
                        <td style="border: 1px solid #808080; padding: 5px; background-color: #aaa; color:white;">
                            <?= $item ?>
                        </td>
                    <?php else: ?>
                        <td style="border: 1px solid #808080; padding: 5px; background-color: #fff; color:black;">
                            <?= $item ?>
                        </td>
                    <?php endif; ?>
                <?php endforeach ?>
                <td style="border: 1px solid #808080; padding: 5px;">Total Hadir</td>
                <td style="border: 1px solid #808080; padding: 5px;">Jumlah Terlambat</td>
                <td style="border: 1px solid #808080; padding: 5px;">Total Telambat</td>
                <!-- <td>Tidak Tadir</td> -->
            </tr>

            <?php foreach ($hasil as $karyawan) : ?>
                <tr style="border: 1px solid #252525;">
                    <?php foreach ($karyawan as $key => $data) : ?>

                        <?php if ($key == 0) : ?>
                            <td style=" border: 1px solid #808080; padding: 5px; width: 150px;">
                                <?php $text  = strtolower($data['nama']); ?>
                                <div class=" d-flex flex-column">
                                    <p style="margin: 0; padding:0;  text-transform: capitalize;  font-weight: bold"><?= $text ?></p>

                                    <p style="margin: 0; padding:0;  text-transform: capitalize;"><?= $data['kode_karyawan'] ?></p>
                                </div>
                            </td>
                            <td style=" border: 1px solid #808080; padding: 5px; width: 100px;">
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

                            <?php if ($day_of_week == 0) : ?>
                                <td style=" border: 1px solid #808080; padding: 2px; background-color: #aaa; color:white;">
                                <?php else :  ?>
                                <td style=" border: 1px solid #808080; padding: 2px; ">
                                <?php endif; ?>
                                <p style="max-width: 20px; padding:0;   text-align: center  !important; vertical-align: middle; ">


                                    <?php if ($key == (count($karyawan) - 1)): ?>

                                        <?php
                                        $jam = floor($data['detik_terlambat'] / 3600); // Menghitung jam
                                        $menit = floor(($data['detik_terlambat'] % 3600) / 60); // Menghitung menit
                                        $detik = $data['detik_terlambat'] % 60; // Menghitung detik

                                        $formattedTime = sprintf('%02d:%02d:%02d', $jam, $menit, $detik);
                                        ?>

                                        <span style="font-weight:600;  text-align:center; "><?= $formattedTime ?></span>
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


                                        $jam_kantor_masuk = strtotime($jamKerjaKantor ?? "08:00:00");
                                        ?>


                                        <?php if ($data['is_lembur'] == 1) : ?>
                                            <span style='color: black;'><?= $data['status_hadir'] ?></span><br />
                                            <span style='color: black; font-size:10px;'>Lembur</span><br />

                                        <?php elseif ($data['is_24jam'] == 1) : ?>
                                            <span style='color: green; font-weight:700;'><?= $data['status_hadir'] ?></span><br />
                                            <span style='color: black; font-size:10px;'>24 jam</span><br />



                                        <?php elseif ($data['is_wfh'] == 1) : ?>
                                            <span style='color: blue; font-weight:700;'><?= $data['status_hadir'] ?></span><br />
                                            <span style='color: blue; font-size:10px;'>WFH</span>



                                        <?php elseif ($data['is_terlambat'] == 1) : ?>
                                            <span style='color: red;'><?= $data['status_hadir'] ?></span>

                                        <?php else : ?>
                                            <span style='color: black;'><?= $data['status_hadir'] ?></span>
                                        <?php endif; ?>
                                    <?php endif; ?>

                                </p>
                                </td>
                            <?php endif ?>

                        <?php endforeach ?>
                </tr>
            <?php endforeach ?>

            <tr style="border: 1px solid #252525;">

                <th style="border: 1px solid #808080; padding: 5px; font-size:13px; background-color: #facc15; color:#000">Hadir</th>
                <th style="border: 1px solid #808080; padding: 5px; font-size:11px; background-color: #facc15; color:#000"></th>
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
                        <td style="border: 1px solid #808080; padding: 5px; font-weight:600; text-align:center; background-color: #aaa; color:#000"><?= '' ?? '' ?></td>
                    <?php else : ?>
                        <?php
                        // Tentukan warna background
                        $bgColor = ($key === $lastKey) ? 'fff' : '#facc15'; // Ubah menjadi lightblue jika ini adalah elemen terakhir
                        ?>
                        <td style="border: 1px solid #808080; padding: 5px; font-weight:600; text-align:center; background-color: <?= $bgColor ?>; color:#000"><?= $rekapan ? ($rekapan > 0 ? $rekapan : '') : '' ?></td>
                    <?php endif; ?>
                <?php endforeach ?>
                <td style=" border: 1px solid #808080; padding: 5px; font-weight:600; text-align:center; background-color: #fff; color:#000"></td>
            </tr>

            <!--  2 -->
            <tr style="border: 1px solid #252525;">

                <th style=" border: 1px solid #808080; padding: 5px; font-size:13px; background-color: #84cc16; color:#000">Tidak Hadir</th>
                <th style=" border: 1px solid #808080; padding: 5px; font-size:13px; background-color: #84cc16; color:#000"></th>
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
                        <td style=" border: 1px solid #808080; padding: 5px; font-weight:600; text-align:center; background-color: #aaa; color:#000"><?= '' ?? '' ?></td>
                    <?php else : ?>
                        <?php
                        $data = ($rekapan && $rekapan > 0) ? $karyawanTotal - $rekapan : '';
                        $data = ($data < 0) ? '' : $data;

                        // Tentukan background color
                        $bgColor = ($key === $lastKey) ? '#fff' : '#84cc16'; // Ubah menjadi putih jika ini adalah elemen terakhir
                        ?>
                        <td style=" border: 1px solid #808080; padding: 5px; font-weight:600; text-align:center; background-color: <?= $bgColor ?>; color:#000"><?php echo $data ?></td>
                    <?php endif; ?>
                <?php endforeach ?>
                <td style=" border: 1px solid #808080; padding: 5px; font-weight:600; text-align:center; background-color: #fff; color:#000"></td>
            </tr>

            <!-- 3 -->
            <tr style="border: 1px solid #252525;">

                <th style=" border: 1px solid #808080; padding: 5px; font-size:13px; background-color: #f43f5e; color:#fff">Terlambat</th>
                <th style=" border: 1px solid #808080; padding: 5px; font-size:13px; background-color: #f43f5e; color:#fff"></th>
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
                        <td style="border: 1px solid #808080; padding: 5px; font-weight:600; text-align:center; background-color: #aaa; color:#fff"><?= '' ?? '' ?></td>
                    <?php else : ?>
                        <?php
                        $dataTerlambat = ($terlambattgl && $terlambattgl > 0) ?  $terlambattgl : '';
                        $dataTerlambat = ($dataTerlambat <= 0) ? '' : $dataTerlambat;
                        ?>
                        <td style="border: 1px solid #808080; padding: 5px; font-weight:600; text-align:center; background-color: #f43f5e; color:#fff"><?php echo $dataTerlambat ?></td>
                    <?php endif; ?>
                <?php endforeach ?>
                <td style="border: 1px solid #808080; padding: 5px; font-weight:600; text-align:center; background-color: #fff; color:#fff"></td>
                <td style="border: 1px solid #808080; padding: 5px; font-weight:600; text-align:center; background-color: #fff; color:#fff"></td>
            </tr>

        </table>
    </div>



</div>