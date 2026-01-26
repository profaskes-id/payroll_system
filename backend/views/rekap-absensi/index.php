<?php

use backend\models\MasterHaribesar;
use backend\models\Tanggal;
use yii\helpers\Html;

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

<style>
    .sticky-col {
        position: sticky;
        background-color: white;
        /* penting agar tidak transparan */
        z-index: 2;
        /* agar tidak tertutup kolom lain */
    }

    .first-col {
        left: 0;
    }

    .second-col {
        left: 100px;
        /* sesuaikan dengan lebar kolom pertama */
    }

    .minggu {
        background: #ababab;
        color: white;
    }

    .sabtu {
        background: #c2a17a;
        color: white;
    }

    .libur {
        background: #ffc0cb;
        /* merah muda tipis */
        color: black;
    }
</style>


<?php
$liburNasional = MasterHaribesar::find()->select(['kode', 'tanggal', 'nama_hari'])->where(['libur_nasional' => 1])->asArray()->all();
$liburDates = array_column($liburNasional, 'tanggal');

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



    <div class="overflow-x-auto table-container table-responsive">
        <p class="text-xs text-muted text-capitalize">jika data karyawan tidak ada, silahkan tambahkan data jam kerja karyawan</p>
        <table class="table table-bordered table-responsive">
            <thead>
                <tr>
                    <!-- kolom kiri -->
                    <th rowspan="2" class="text-center sticky-col first-col">
                        Nama dan Kode Karyawan
                    </th>
                    <th rowspan="2" class="text-center sticky-col second-col">
                        Bagian & Jabatan
                    </th>

                    <!-- hari -->
                    <?php foreach ($tanggal_bulanan as $item) : ?>
                        <?php
                        $date = new DateTime($item);
                        $hari = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];

                        // Tentukan class
                        $class = '';
                        if (in_array($date->format('Y-m-d'), $liburDates)) {
                            $class = 'libur'; // prioritas libur nasional
                        } elseif ($date->format('w') == 0) {
                            $class = 'minggu';
                        } elseif ($date->format('w') == 6) {
                            $class = 'sabtu';
                        }
                        ?>
                        <td class="text-center <?= $class ?>">
                            <p class="text-sm"><?= $hari[(int)$date->format('w')] ?></p>
                        </td>
                    <?php endforeach ?>

                    <!-- kolom total -->
                    <th rowspan="2">Total Hadir<br><small>(Hari)</small></th>
                    <th rowspan="2">Jumlah Terlambat<br><small>(X)</small></th>
                    <th rowspan="2">Total Terlambat<br><small>(Jam)</small></th>
                    <th rowspan="2">Total Lembur<br><small>(X)</small></th>
                    <th rowspan="2">Lama Lembur<br><small>(Jam)</small></th>
                </tr>

                <!-- BARIS KE-2: TANGGAL -->
                <tr>
                    <?php foreach ($tanggal_bulanan as $item) : ?>
                        <?php
                        $date = new DateTime($item);
                        $bulan = [
                            1 => 'Jan',
                            'Feb',
                            'Mar',
                            'Apr',
                            'Mei',
                            'Jun',
                            'Jul',
                            'Agu',
                            'Sep',
                            'Okt',
                            'Nov',
                            'Des'
                        ];
                        $tanggal = $date->format('d') . '-' . $bulan[(int)$date->format('m')] . '-' . $date->format('Y');

                        // Tentukan class sama seperti baris hari
                        $class = '';
                        if (in_array($date->format('Y-m-d'), $liburDates)) {
                            $class = 'libur';
                        } elseif ($date->format('w') == 0) {
                            $class = 'minggu';
                        } elseif ($date->format('w') == 6) {
                            $class = 'sabtu';
                        }
                        ?>
                        <td class="text-center <?= $class ?>">
                            <p class="p-0 text-xs"><?= $tanggal ?></p>
                        </td>
                    <?php endforeach ?>
                </tr>
            </thead>

            <?php $no = 1; ?>
            <tbody>
                <?php foreach ($hasil as $karyawan) : ?>
                    <tr style="vertical-align: middle;
        background-color: <?= ($no % 2 == 0) ? '#e1e1e1' : 'transparent'; ?>">

                        <?php foreach ($karyawan as $key => $data) : ?>

                            <?php if ($key == 0) : ?>
                                <!-- Nama dan Kode Karyawan -->
                                <td class="sticky-col first-col">
                                    <?php $text  = strtolower($data['nama']); ?>
                                    <div class="d-flex flex-column">
                                        <p style="margin:0; padding:0; text-transform: capitalize; font-weight:bold; font-size:12px;"><?= $text ?></p>
                                        <p style="margin:0; padding:0; text-transform: capitalize; font-size:11px"><?= $data['kode_karyawan'] ?></p>
                                    </div>
                                </td>

                                <!-- Bagian & Jabatan -->
                                <td class="sticky-col second-col" style="background-color: <?= $data['color'] ?>;">
                                    <div>
                                        <p style="margin:0; padding:0; text-transform: capitalize; font-size:10px;"><?= $data['bagian'] ?></p>
                                        <hr style="margin:2px 0; padding:0;">
                                        <p style="margin:0; padding:0; text-transform: capitalize; font-size:10px;"><?= $data['jabatan'] ?></p>
                                    </div>
                                </td>

                            <?php else : ?>

                                <?php
                                // Cek tanggal untuk setiap kolom
                                if (isset($tanggal_bulanan[$key - 1])) {
                                    $date = new DateTime($tanggal_bulanan[$key - 1]);
                                }

                                // Tentukan class warna seperti header
                                $class = '';
                                if (in_array($date->format('Y-m-d'), $liburDates)) {
                                    $class = 'libur';
                                } elseif ($date->format('w') == 0) {
                                    $class = 'minggu';
                                } elseif ($date->format('w') == 6) {
                                    $class = 'sabtu';
                                }
                                ?>

                                <td class="text-center <?= $class ?>">
                                    <p style="width:50px; padding:0; text-align:center; vertical-align:middle;">
                                        <?php
                                        // Bagian akhir rekap absensi
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
                                                echo sprintf('<span style="font-weight:600">%02d:%02d:%02d</span>', $jam, $menit, $detik);
                                                break;
                                            case $lastIndex - 2:
                                                echo $data['total_lembur'];
                                                break;
                                            case $lastIndex - 1:
                                                echo formatJamDesimal($data['jumlah_jam_lembur']);
                                                break;
                                            default:
                                                if ($data !== null && $data['status_hadir'] !== null && $data['jam_masuk_karyawan'] !== null):
                                                    $jamKerjakaryawan = $data['jam_masuk_karyawan'];
                                                    $jamKerjaKantor = $data['jam_masuk_kantor'] ?? '08:00:00';
                                                    $karyawan_absen_pada = strtotime($jamKerjakaryawan);
                                                    $jam_kantor_masuk = strtotime($jamKerjaKantor);

                                                    if ($data['is_lembur'] == 1):
                                                        echo "<span style='color:black'>{$data['status_hadir']}</span><br><span style='color:black'>Lembur</span>";
                                                    elseif ($data['is_wfh'] == 1):
                                                        echo "<span style='color:blue; font-weight:700'>{$data['status_hadir']}</span><br><span style='color:blue; font-weight:700'>WFH</span>";
                                                    elseif ($data['is_24jam'] == 1):
                                                        echo "<span style='color:green; font-weight:700'>{$data['status_hadir']}</span><br><span style='color:green; font-weight:700'>24 Jam</span>";
                                                    elseif ($data['is_terlambat'] == 1):
                                                        $lama = isset($data['lama_terlambat']) && $data['lama_terlambat'] ? date('H:i', strtotime($data['lama_terlambat'])) : '00:00';
                                                        echo "<span style='color:red'>{$data['status_hadir']}</span><br><span style='color:red'>{$lama}</span>";
                                                    else:
                                                        echo "<span style='color:black'>{$data['status_hadir']}</span>";
                                                    endif;
                                                endif;
                                                break;
                                        }
                                        ?>
                                    </p>
                                </td>

                            <?php endif; ?>

                        <?php endforeach ?>
                    </tr>
                    </tr>
                    <?php $no++; ?>
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