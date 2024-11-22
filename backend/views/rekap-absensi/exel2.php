<?php

use backend\models\Tanggal;

$tanggal = new Tanggal;

?>



<table border="1">
    <thead>

        <tr>
            <th rowspan="3">Nama dan Kode Karyawan</th>
            <th rowspan="3">Bagian & Jabatan</th>
        </tr>
        <tr>
            <th colspan="<?= count($tanggal_bulanan) + 4 - 1  ?>">
                <h3>
                    Rekapan Absensi Bulan <?= $tanggal->getBulan($bulan) . ' Tahun ' . $tahun ?>
                </h3>
            </th>
        </tr>
        <tr>
            <?php foreach ($tanggal_bulanan as $key => $item) : ?>
                <?php
                if (!isset($tanggal_bulanan[$key - 1])) {
                    $day_of_week = 1;
                } else {
                    $date = date_create($tanggal_bulanan[$key]  . '-' . $bulan . '-' . $tahun);
                    $day_of_week = date_format($date, 'w');
                }
                ?>
                <td <?php if ($day_of_week == 0) echo 'style="background-color: gray; color:white;"'; ?>>
                    <?= $item ?>
                </td>
            <?php endforeach ?>
            <td>Total Hadir</td>
            <td>Jumlah Terlambat</td>
            <td>Total Telambat</td>
        </tr>
    </thead>

    <tbody>
        <?php foreach ($hasil as $karyawan) : ?>
            <tr>
                <?php foreach ($karyawan as $key => $data) : ?>

                    <?php if ($key == 0) : ?>
                        <td>
                            <?php $text  = strtolower($data['nama']); ?>
                            <div class=" d-flex flex-column">
                                <p><?= $text ?></p>

                                <p><?= $data['kode_karyawan'] ?></p>
                            </div>
                        </td>
                        <td>
                            <div>
                                <p><?= $data['bagian'] ?></p>
                                <hr>
                                <p><?= $data['jabatan'] ?></p>
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

                        <td <?php if ($day_of_week == 0) echo 'style="background-color: gray; color:white;"'; ?>>
                            <p>
                                <?php if ($key == (count($karyawan) - 1)): ?>

                                    <?php
                                    $jam = floor($data['detik_terlambat'] / 3600); // Menghitung jam
                                    $menit = floor(($data['detik_terlambat'] % 3600) / 60); // Menghitung menit
                                    $detik = $data['detik_terlambat'] % 60; // Menghitung detik

                                    $formattedTime = sprintf('%02d:%02d:%02d', $jam, $menit, $detik);
                                    ?>

                                    <span><?= $formattedTime ?></span>
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
                                        <span><?= $data['status_hadir'] ?></span><br />
                                        <span>Lembur</span><br />

                                    <?php elseif ($data['is_wfh'] == 1) : ?>
                                        <span><?= $data['status_hadir'] ?></span><br />
                                        <span>WFH</span><br />


                                    <?php elseif ($karyawan_absen_pada > $jam_kantor_masuk) : ?>
                                        <span><?= $data['status_hadir'] ?></span><br />
                                        <?php
                                        $selisih_detik = $karyawan_absen_pada - $jam_kantor_masuk;
                                        $menit = floor($selisih_detik / 60);
                                        $detik = $selisih_detik % 60;
                                        $jam = floor($menit / 60);
                                        // Hitung menit yang tersisa
                                        $menit = $menit % 60;

                                        // Jika menit atau jam negatif, set menjadi 0
                                        if ($menit < 0) {
                                            $menit = 0;
                                        }
                                        if ($jam < 0) {
                                            $jam = 0;
                                        }

                                        // Tambahkan nol di depan jika kurang dari 10
                                        $menit = str_pad($menit, 2, '0', STR_PAD_LEFT);
                                        $detik = str_pad($detik, 2, '0', STR_PAD_LEFT);
                                        $jam = str_pad($jam, 2, '0', STR_PAD_LEFT);

                                        if ($jam > 0) {
                                            echo "<span style='color:red;'>+{$jam}:{$menit}:{$detik}</span>";
                                        } else {
                                            echo "<span style='color:red;'>+{$jam}:{$menit}:{$detik}</span>";
                                        }
                                        ?>
                                    <?php else : ?>
                                        <span><?= $data['status_hadir'] ?></span>
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
        <th>Hadir</th>
        <th></th>
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
                <td style="background-color: gray;"></td>
            <?php else : ?>
                <?php
                // Tentukan warna background
                $bgColor = ($key === $lastKey) ? 'fff' : '#facc15'; // Ubah menjadi lightblue jika ini adalah elemen terakhir
                ?>
                <td><?= $rekapan ? ($rekapan > 0 ? $rekapan : '') : '' ?></td>
            <?php endif; ?>
        <?php endforeach ?>
        <td></td>

    </tr>

    <!--  2 -->
    <tr>
        <th>Tidak Hadir</th>
        <th></th>
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
                <td style="background-color: gray;"></td>
            <?php else : ?>
                <?php
                $data = ($rekapan && $rekapan > 0) ? $karyawanTotal - $rekapan : '';
                $data = ($data < 0) ? '' : $data; // Ubah menjadi putih jika ini adalah elemen terakhir
                ?>
                <td><?php echo $data ?></td>
            <?php endif; ?>
        <?php endforeach ?>
        <td></td>
    </tr>

    <!-- 3 -->
    <tr>
        <th>Terlambat</th>
        <th></th>
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
                <td style="background-color: gray;"></td>
            <?php else : ?>
                <?php
                $dataTerlambat = ($terlambattgl && $terlambattgl > 0) ?  $terlambattgl : '';
                $dataTerlambat = ($dataTerlambat <= 0) ? '' : $dataTerlambat;
                ?>
                <td><?php echo $dataTerlambat ?></td>
            <?php endif; ?>
        <?php endforeach ?>
        <td></td>
        <td></td>
    </tr>
</table>


<?php
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; Filename = Data.xls");
// die;
?>