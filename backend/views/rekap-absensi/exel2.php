<?php

use backend\models\Tanggal;

$tanggal = new Tanggal;
?>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<?php
ob_start();
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=Rekapan-absensi(" . $tanggal->getIndonesiaFormatTanggal($tanggal_awal) . " - " . $tanggal->getIndonesiaFormatTanggal($tanggal_akhir) . ").xls");
?>

<style>
    td,
    th {
        mso-number-format: "\@";
        white-space: nowrap;
    }
</style>

<table border="1">
    <thead>
        <tr>
            <th rowspan="3">Nama</th>
            <th rowspan="3">Kode Karyawan</th>
            <th rowspan="3">Bagian</th>
            <th rowspan="3">Jabatan</th>
            <th colspan="<?= count($tanggal_bulanan) * 2 + 5 ?>" style="text-align:center; font-weight:bold;">
                Rekapan Absensi dari <?= $tanggal->getIndonesiaFormatTanggal($tanggal_awal) ?> S/D <?= $tanggal->getIndonesiaFormatTanggal($tanggal_akhir) ?>
            </th>
        </tr>
        <tr>
            <?php foreach ($tanggal_bulanan as $key => $item) : ?>
                <?php
                $day_of_week = 1;
                if (isset($tanggal_bulanan[$key]) && !empty($tanggal_bulanan[$key])) {
                    $date = date_create($tanggal_bulanan[$key]);
                    if ($date) {
                        $day_of_week = (int) date_format($date, 'w');
                    }
                }
                ?>
                <th colspan="2" style="border: 1px solid #808080; padding: 5px; <?= ($day_of_week == 0) ? 'background-color: #aaa; color:white;' : 'background-color: #fff; color:black;' ?>">
                    <?= date('d-m-y', strtotime($item)) ?>
                </th>
            <?php endforeach ?>
            <th rowspan="2">Total Hadir</th>
            <th rowspan="2">Jumlah Terlambat</th>
            <th rowspan="2">Total Telambat</th>
            <th rowspan="2">Total lembur</th>
            <th rowspan="2">Jumlah Jam Lembur</th>
        </tr>
        <tr>
            <?php foreach ($tanggal_bulanan as $key => $item) : ?>
                <?php
                $day_of_week = 1;
                if (isset($tanggal_bulanan[$key]) && !empty($tanggal_bulanan[$key])) {
                    $date = date_create($tanggal_bulanan[$key]);
                    if ($date) {
                        $day_of_week = (int) date_format($date, 'w');
                    }
                }
                ?>
                <th style="border: 1px solid #808080; padding: 2px; <?= ($day_of_week == 0) ? 'background-color: #aaa; color:white;' : 'background-color: #fff; color:black;' ?>">Masuk</th>
                <th style="border: 1px solid #808080; padding: 2px; <?= ($day_of_week == 0) ? 'background-color: #aaa; color:white;' : 'background-color: #fff; color:black;' ?>">Pulang</th>
            <?php endforeach ?>
        </tr>
    </thead>

    <tbody>
        <?php foreach ($hasil as $karyawan) : ?>
            <tr>
                <?php if (is_array($karyawan)) : ?>
                    <?php foreach ($karyawan as $key => $data) : ?>
                        <?php if ($key == 0) : ?>
                            <td><?= strtolower($data['nama']) ?></td>
                            <td><?= $data['kode_karyawan'] ?></td>
                            <td><?= $data['bagian'] ?></td>
                            <td><?= $data['jabatan'] ?></td>
                        <?php else : ?>
                            <?php if ($key > 0 && $key <= count($tanggal_bulanan)) : ?>
                                <?php
                                $day_of_week = 1;
                                if (isset($tanggal_bulanan[$key - 1]) && !empty($tanggal_bulanan[$key - 1])) {
                                    $date = date_create($tanggal_bulanan[$key - 1]);
                                    if ($date) {
                                        $day_of_week = (int) date_format($date, 'w');
                                    }
                                }
                                ?>
                                <!-- Jam Masuk -->
                                <td style="border: 1px solid #808080; padding: 2px; text-align: center; <?= ($day_of_week == 0) ? 'background-color: #aaa; color:white;' : '' ?>">
                                    <?= ($data !== null && isset($data['jam_masuk_karyawan'])) ? $data['jam_masuk_karyawan'] : '' ?>
                                </td>
                                <!-- Jam Pulang -->
                                <td style="border: 1px solid #808080; padding: 2px; text-align: center; <?= ($day_of_week == 0) ? 'background-color: #aaa; color:white;' : '' ?>">
                                    <?= ($data !== null && isset($data['jam_pulang'])) ? $data['jam_pulang'] : '' ?>
                                </td>
                            <?php elseif ($key == (count($karyawan) - 5)) : ?>
                                <td style="text-align: center;"><?= $data['total_hadir'] ?></td>
                            <?php elseif ($key == (count($karyawan) - 4)) : ?>
                                <td style="text-align: center;"><?= $data['total_terlambat'] ?></td>
                            <?php elseif ($key == (count($karyawan) - 3)) : ?>
                                <td style="text-align: center;"><?= gmdate('H:i:s', $data['detik_terlambat']) ?></td>
                            <?php elseif ($key == (count($karyawan) - 2)) : ?>
                                <td style="text-align: center;"><?= $data['total_lembur'] ?></td>
                            <?php elseif ($key == (count($karyawan) - 1)) : ?>
                                <td style="text-align: center;"><?= $data['jumlah_jam_lembur'] ?></td>
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php endforeach ?>
                <?php endif; ?>
            </tr>
        <?php endforeach ?>
    </tbody>

    <tfoot>
        <!-- 1. Hadir -->
        <tr>
            <th style="font-size:13px; background-color: #facc15; color:#000">Hadir</th>
            <th style="font-size:11px; background-color: #facc15; color:#000"></th>
            <th style="font-size:11px; background-color: #facc15; color:#000"></th>
            <th style="font-size:11px; background-color: #facc15; color:#000"></th>

            <?php foreach ($rekapanAbsensi as $key => $rekapan) : ?>
                <td colspan="2" style="font-weight:600; text-align:center; background-color: #facc15; color:#000">
                    <?= $rekapan ? ($rekapan > 0 ? $rekapan : '') : '' ?>
                </td>
            <?php endforeach; ?>
            <td colspan="6"></td>
        </tr>

        <!-- 2. Tidak Hadir -->
        <tr>
            <th style="font-size:13px; background-color: #84cc16; color:#000">Tidak Hadir</th>
            <th style="font-size:13px; background-color: #84cc16; color:#000"> </th>
            <th style="font-size:13px; background-color: #84cc16; color:#000"> </th>
            <th style="font-size:11px; background-color: #84cc16; color:#000"></th>

            <?php foreach ($rekapanAbsensi as $key => $rekapan) : ?>
                <td colspan="2" style="font-weight:600; text-align:center; background-color: #84cc16; color:#000">
                    <?= (isset($hasil) && is_array($hasil)) ? (count($hasil) - $rekapan) : '' ?>
                </td>
            <?php endforeach; ?>

            <td colspan="6"></td>
        </tr>

        <!-- 3. Terlambat -->
        <tr>
            <th style="font-size:13px; background-color: #f43f5e; color:#fff">Terlambat</th>
            <th style="font-size:13px; background-color: #f43f5e; color:#fff"></th>
            <th style="font-size:13px; background-color: #f43f5e; color:#fff"></th>
            <th style="font-size:13px; background-color: #f43f5e; color:#fff"></th>
            <?php foreach ($keterlambatanPerTanggal as $key => $terlambattgl) : ?>
                <?php
                $dataTerlambat = ($terlambattgl && $terlambattgl > 0) ? $terlambattgl : '';
                $dataTerlambat = ($dataTerlambat <= 0) ? '' : $dataTerlambat;
                ?>
                <td colspan="2" style="font-weight:600; text-align:center; background-color: #f43f5e; color:#fff">
                    <?= $dataTerlambat ?>
                </td>
            <?php endforeach ?>

            <td colspan="6"></td>
        </tr>
    </tfoot>
</table>