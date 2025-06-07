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

        <table style="text-align: center; font-size: 12px; border: 1px solid gray; width:100%;">
            <tr style=" border: 1px solid #252525;">
                <th style=" border: 1px solid #252525; vertical-align: middle;" rowspan="3" class="text-center ">Nama dan Kode Karyawan</th>
                <th style=" border: 1px solid #252525; vertical-align: middle;" rowspan="3" class="text-center ">Bagian & Jabatan</th>
            </tr>
            <tr style="border: 1px solid #252525;">
                <th style=" border: 1px solid #252525;" class="text-center" colspan="<?= count($tanggal_bulanan) + 3  ?>">
                    <h3>
                        Rekapan Absensi dari <?= $tanggal->getIndonesiaFormatTanggal($tanggal_awal) ?> S/D <?= $tanggal->getIndonesiaFormatTanggal($tanggal_akhir) ?>

                    </h3>
                </th>
            </tr>
            <tr style="border: 1px solid #252525; text-align:center;">
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
                <td style="border: 1px solid #808080; padding: 5px;">Total Lembur</td>
                <td style="border: 1px solid #808080; padding: 5px;">Jumlah jam Lembur</td>
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
                            $day_of_week = 1; // default: Senin
                            if (isset($tanggal_bulanan[$key - 1]) && !empty($tanggal_bulanan[$key - 1])) {
                                $date = date_create($tanggal_bulanan[$key - 1]);
                                if ($date) {
                                    $day_of_week = (int) date_format($date, 'w');
                                }
                            }
                            ?>

                            <?php if ($day_of_week == 0) : ?>
                                <td style=" border: 1px solid #808080; padding: 2px; background-color: #aaa; color:white;">
                                <?php else :  ?>
                                <td style=" border: 1px solid #808080; padding: 2px; ">
                                <?php endif; ?>
                                <p style="max-width: 20px; padding:0;   text-align: center  !important; vertical-align: middle; ">




                                    <?php if ($key == (count($karyawan) - 5)): ?>
                                        <?= $data['total_hadir']
                                        ?>
                                    <?php endif; ?>
                                    <?php if ($key == (count($karyawan) - 4)): ?>
                                        <?= $data['total_terlambat']
                                        ?>
                                    <?php endif; ?>
                                    <?php if ($key == (count($karyawan) - 3)): ?>
                                        <?= gmdate('H:i:s', $data['detik_terlambat']) ?>
                                    <?php endif; ?>

                                    <?php if ($key == (count($karyawan) - 2)): ?>
                                        <?= $data['total_lembur']
                                        ?>
                                    <?php endif; ?>
                                    <?php if ($key == (count($karyawan) - 1)): ?>
                                        <?= $data['jumlah_jam_lembur']
                                        ?>
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

            <!-- 1 -->
            <tr>
                <th style="font-size:13px; background-color: #facc15; color:#000">Hadir</th>
                <th style="font-size:11px; background-color: #facc15; color:#000"></th>

                <?php
                $lastKey = array_key_last($rekapanAbsensi); // Mendapatkan kunci terakhir
                foreach ($rekapanAbsensi as $key => $rekapan) :
                    $isLast = ($key === $lastKey);
                    $colspan = $isLast ? 'colspan="1"' : ''; // Tambahkan colspan jika terakhir
                ?>
                    <td style="font-weight:600; text-align:center; background-color: #facc15; color:#000" <?php echo $colspan; ?>>
                        <?php echo $rekapan ? ($rekapan > 0 ? $rekapan : '') : '' ?>
                    </td>
                <?php endforeach; ?>
                <td colspan="5"></td>

            </tr>



            <!-- 2 -->
            <tr>
                <th style="font-size:13px; background-color: #84cc16; color:#000">Tidak Hadir</th>
                <th style="font-size:11px; background-color: #84cc16; color:#000"></th>

                <?php
                $lastKey = array_key_last($rekapanAbsensi); // Mendapatkan kunci terakhir
                foreach ($rekapanAbsensi as $key => $rekapan) :
                    if ($key !== $lastKey) :
                ?>
                        <td style="font-weight:600; text-align:center; background-color: #84cc16; color:#000">
                            <?php echo ($rekapan && isset($hasil)) ? (count($hasil) - $rekapan) : ''; ?>
                        </td>
                <?php
                    endif;
                endforeach;

                ?>
                <td colspan="6" style="font-weight:600; text-align:center; background-color: #fff; color:#000">
                </td>
            </tr>



            <!-- 3 -->
            <tr>
                <th style="font-size:13px; background-color: #f43f5e; color:#fff">Terlambat</th>
                <th style="font-size:13px; background-color: #f43f5e; color:#fff"></th>
                <?php foreach ($keterlambatanPerTanggal as $key => $terlambattgl) :  ?>
                    <?php
                    $dataTerlambat = ($terlambattgl && $terlambattgl > 0) ?  $terlambattgl : '';
                    $dataTerlambat = ($dataTerlambat <= 0) ? '' : $dataTerlambat;
                    ?>
                    <td style="font-weight:600; text-align:center; background-color: #f43f5e; color:#fff"><?php echo $dataTerlambat ?></td>
                <?php endforeach ?>
                <td colspan="1" style="font-weight:600; text-align:center; background-color: #f43f5e; color:#fff"><?php echo count($keterlambatanPerTanggal) ?></td>
                <td colspan="5" style="font-weight:600; text-align:center; background-color: #fff; color:#fff"></td>
            </tr>

        </table>
    </div>



</div>