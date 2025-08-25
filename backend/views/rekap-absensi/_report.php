<?php
use backend\models\Tanggal;

$this->title = 'Rekap Absensi';
$this->params['breadcrumbs'][] = $this->title;
$tanggal = new Tanggal();
?>

<div style="font-size: 10px; width: 100%; overflow-x: auto;">
    <div class="table-container" style="width: 100%; max-width: 100%;">
        <table style="width: 100%; border-collapse: collapse; text-align: center; font-family: Arial, sans-serif;">
            <!-- Header 1 -->
            <tr>
                <th style="border: 1px solid #000; padding: 5px; vertical-align: middle; width: 150px;" rowspan="3">NAMA & KODE KARYAWAN</th>
                <th style="border: 1px solid #000; padding: 5px; vertical-align: middle; width: 120px;" rowspan="3">BAGIAN & JABATAN</th>
                <th style="border: 1px solid #000; padding: 8px;" colspan="<?= count($tanggal_bulanan)*2 + 5 ?>">
                    <h3 style="margin: 0; font-size: 14px;">REKAPAN ABSENSI <?= strtoupper($tanggal->getIndonesiaFormatTanggal($tanggal_awal)) ?> S/D <?= strtoupper($tanggal->getIndonesiaFormatTanggal($tanggal_akhir)) ?></h3>
                </th>
            </tr>
            
            <!-- Header 2 - Dates -->
            <tr>
                <?php foreach ($tanggal_bulanan as $key => $item) : ?>
                    <?php
                    $day_of_week = date('w', strtotime($item));
                    $bg_color = ($day_of_week == 0) ? '#dddddd' : '#ffffff';
                    $text_color = ($day_of_week == 0) ? '#ff0000' : '#000000';
                    ?>
                    <th  style="border: 1px solid #000; padding: 5px; background-color: <?= $bg_color ?>; color: <?= $text_color ?>; font-size: 10px;">
                        <?= date('d-m-y', strtotime($item)) ?>
                    </th>
                <?php endforeach ?>
                <th style="border: 1px solid #000; padding: 5px; font-size: 10px;">TOTAL HADIR</th>
                <th style="border: 1px solid #000; padding: 5px; font-size: 10px;">TERLAMBAT</th>
                <th style="border: 1px solid #000; padding: 5px; font-size: 10px;">TOTAL WAKTU TERLAMBAT</th>
                <th style="border: 1px solid #000; padding: 5px; font-size: 10px;">LEMBUR</th>
                <th style="border: 1px solid #000; padding: 5px; font-size: 10px;">TOTAL JAM LEMBUR</th>
            </tr>
            
            <!-- Header 3 - In/Out -->
            <tr>
                
                <th colspan="5" style="border: 1px solid #000;"></th>
            </tr>
            
            <!-- Employee Data -->
            <?php foreach ($hasil as $karyawan) : ?>
                <tr>
                    <?php foreach ($karyawan as $key => $data) : ?>

                        <?php if ($key == 0) : ?>
                            <!-- Employee Info -->
                            <td style="border: 1px solid #000; padding: 5px; text-align: left;">
                                <div style="font-weight: bold; text-transform: capitalize;"><?= strtolower($data['nama']) ?></div>
                                <div style="font-size: 9px;"><?= $data['kode_karyawan'] ?></div>
                            </td>
                            <td style="border: 1px solid #000; padding: 5px; text-align: left;">
                                <div style="text-transform: capitalize;"><?= $data['bagian'] ?></div>
                                <div style="font-size: 9px; text-transform: capitalize;"><?= $data['jabatan'] ?></div>
                            </td>
                        <?php else : ?>
                            <?php if ($key > 0 && $key <= count($tanggal_bulanan)) : ?>
                                <?php
                                $day_of_week = date('w', strtotime($tanggal_bulanan[$key-1]));
                                $bg_color = ($day_of_week == 0) ? '#f5f5f5' : '#ffffff';
                                ?>
                                <!-- Attendance Data -->
                                <td style="border: 1px solid #000; padding: 3px; background-color: <?= $bg_color ?>; font-size: 10px;">

                                    <?= ($data !== null && isset($data['status_hadir'])) ? $data['status_hadir'] : '-' ?>
                                    <br>
                                    <?= ($data !== null && isset($data['jam_masuk_karyawan'])) ? $data['jam_masuk_karyawan'] : '-' ?>
                                    <br>
                                    <?= ($data !== null && isset($data['jam_pulang'])) ? $data['jam_pulang'] : '-' ?>
                                </td>

                            <?php elseif ($key == (count($karyawan) - 5)) : ?>
                                <td style="border: 1px solid #000; padding: 3px; font-weight: bold;"><?= $data['total_hadir'] ?></td>
                            <?php elseif ($key == (count($karyawan) - 4)) : ?>
                                <td style="border: 1px solid #000; padding: 3px; font-weight: bold;"><?= $data['total_terlambat'] ?></td>
                            <?php elseif ($key == (count($karyawan) - 3)) : ?>
                                <td style="border: 1px solid #000; padding: 3px; font-weight: bold;"><?= gmdate('H:i:s', $data['detik_terlambat']) ?></td>
                            <?php elseif ($key == (count($karyawan) - 2)) : ?>
                                <td style="border: 1px solid #000; padding: 3px; font-weight: bold;"><?= $data['total_lembur'] ?></td>
                            <?php elseif ($key == (count($karyawan) - 1)) : ?>
                                <td style="border: 1px solid #000; padding: 3px; font-weight: bold;"><?= $data['jumlah_jam_lembur'] ?></td>
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php endforeach ?>
                </tr>
            <?php endforeach ?>
            
            <!-- Summary Footer -->
            <!-- 1. Total Hadir -->
            <tr>
                <th style="border: 1px solid #000; padding: 5px; background-color: #facc15; text-align: left;" colspan="2">TOTAL HADIR</th>
                <?php foreach ($rekapanAbsensi as $rekapan) : ?>
                    <td  style="border: 1px solid #000; padding: 3px; background-color: #facc15; font-weight: bold;">
                        <?= $rekapan ?: '0' ?>
                    </td>
                <?php endforeach; ?>
                <td colspan="5" style="border: 1px solid #000; padding: 3px; background-color: #fff; font-weight: bold;">

                </td>

            </tr>
            
            <!-- 2. Tidak Hadir -->
            <tr>
                <th style="border: 1px solid #000; padding: 5px; background-color: #84cc16; text-align: left;" colspan="2">TIDAK HADIR</th>
                <?php foreach ($rekapanAbsensi as $rekapan) : ?>
                    <td style="border: 1px solid #000; padding: 3px; background-color: #84cc16; font-weight: bold;">
                        <?= (isset($hasil) && is_array($hasil)) ? max(0, (count($hasil) - $rekapan)) : '0' ?>
                    </td>
                <?php endforeach; ?>

                <td colspan="5" style="border: 1px solid #000;"></td>
            </tr>
            
            <!-- 3. Terlambat -->
            <tr>
                <th style="border: 1px solid #000; padding: 5px; background-color: #f43f5e; color: #fff; text-align: left;" colspan="2">TERLAMBAT</th>
                <?php foreach ($keterlambatanPerTanggal as $terlambattgl) : ?>
                    <td  style="border: 1px solid #000; padding: 3px; background-color: #f43f5e; color: #fff; font-weight: bold;">
                        <?= ($terlambattgl && $terlambattgl > 0) ? $terlambattgl : '0' ?>
                    </td>
                <?php endforeach; ?>

                <td colspan="5" style="border: 1px solid #000;"></td>
            </tr>
        </table>
    </div>
</div>