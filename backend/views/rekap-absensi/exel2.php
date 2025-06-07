<?php

use backend\models\Tanggal;

$tanggal = new Tanggal;
?>


<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<?php
ob_start(); // start output buffering
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=Rekapan-absensi.xls");
?>
<!-- lalu HTML dan PHP lanjut -->


<style>
    td,
    th {
        mso-number-format: "\@";
    }
</style>



<table border="1">
    <thead>

        <tr>
            <th rowspan="3">Nama </th>
            <th rowspan="3">Kode Karyawan</th>
            <th rowspan="3">Bagian </th>
            <th rowspan="3">jabatan</th>

        </tr>
        <tr>
            <th colspan="<?= count($tanggal_bulanan) + 5 ?>" style="text-align:center; font-weight:bold;">
                Rekapan Absensi dari <?= $tanggal->getIndonesiaFormatTanggal($tanggal_awal) ?> S/D <?= $tanggal->getIndonesiaFormatTanggal($tanggal_akhir) ?>
            </th>

        </tr>
        <tr style="border: 1px solid #252525; text-align:center;overflow: hidden">
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
            <td>Total Hadir</td>
            <td>Jumlah Terlambat</td>
            <td>Total Telambat</td>
            <td>Total lembur</td>
            <td>Jumlah Jam Lembur</td>
        </tr>
    </thead>

    <tbody>
        <?php foreach ($hasil as $karyawan) : ?>
            <tr>
                <?php if (is_array($karyawan)) : ?>
                    <?php foreach ($karyawan as $key => $data) : ?>

                        <?php if ($key == 0) : ?>
                            <td>
                                <?php $text  = strtolower($data['nama']); ?> <p><?= $text ?></p>
                            </td>
                            <td>
                                <p><?= $data['kode_karyawan'] ?></p>
                            </td>
                            <td>
                                <p><?= $data['bagian'] ?></p>
                            </td>
                            <td>
                                <p><?= $data['jabatan'] ?></p>
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
                    <?php endif; ?>
            </tr>
        <?php endforeach ?>
    </tbody>

    <tfoot>
        <tr>
            <th style="font-size:13px; background-color: #facc15; color:#000">Hadir</th>
            <th style="font-size:11px; background-color: #facc15; color:#000"></th>
            <th style="font-size:11px; background-color: #facc15; color:#000"></th>
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
            <th style="font-size:13px; background-color: #84cc16; color:#000"> </th>
            <th style="font-size:13px; background-color: #84cc16; color:#000"> </th>
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
            <th style="font-size:13px; background-color: #f43f5e; color:#fff"></th>
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
    </tfoot>

</table>