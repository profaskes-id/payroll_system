<?php

use backend\models\Tanggal;

$this->title = 'Rekap Absensi ';
$tanggal = new Tanggal();

use yii\helpers\Html;
?>
<?php
$params = [];

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

<?php if (!$karyawan): ?>
    <div style="margin-top: 20px; background-color: #f0f0f0; padding: 15px; border: 1px solid #ccc;">
        <strong>Silakan pilih karyawan terlebih dahulu pada bagian search di atas.</strong>
    </div>
<?php else: ?>
    <div style="margin: 20px 0;">
        <div style="border: 1px solid #ddd; margin-bottom: 20px;">
            <div style="background-color: #f8f9fa; padding: 10px;">
                <strong><?= Html::encode($karyawan['nama']) ?></strong><br>
                Kode: <?= Html::encode($karyawan['kode_karyawan']) ?><br>
                Bagian: <?= Html::encode($karyawan['bagian'])
                        ?><br>
                Jabatan: <?= Html::encode($karyawan['jabatan']) ?><br>

            </div>
            <div style="padding: 10px;">
                <table border="1" cellpadding="5" cellspacing="0" style="border-collapse: collapse; width: 100%;">
                    <thead>
                        <tr style="background-color: #343a40; color: white; text-align: center;">
                            <th>Tanggal</th>
                            <th>Hari</th>
                            <th>Jam Masuk</th>
                            <th>Jam Pulang</th>
                            <th>Total Jam</th>
                            <th>Shift</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($absensi)): ?>
                            <tr>
                                <td colspan="7" style="text-align: center; color: #888;">Data absensi tidak ditemukan.</td>
                            </tr>
                        <?php else: ?>

                            <?php foreach ($absensi as $entry): ?>

                                <?php
                                $tgl = $entry['tanggal'];
                                $jamMasuk = $entry['jam_masuk'];
                                $jamPulang = $entry['jam_pulang'];

                                $totalJam = '';
                                if ($jamMasuk && $jamPulang && $jamPulang !== '00:00:00') {
                                    $masuk = new DateTime($jamMasuk);
                                    $pulang = new DateTime($jamPulang);
                                    $interval = $masuk->diff($pulang);
                                    $totalJam = $interval->format('%H:%I:%S');
                                }

                                $hari = date('w', strtotime($tgl));
                                $hariNama = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'][$hari];
                                $rowStyle = $hari === 0 ? 'background-color: #e0e0e0;' : '';
                                ?>
                                <tr style="<?= $rowStyle ?>">
                                    <td><?= Html::encode(date('d-m-Y', strtotime($tgl))) ?></td>
                                    <td><?= Html::encode($hariNama) ?></td>
                                    <td><?= Html::encode($jamMasuk) ?></td>
                                    <td><?= Html::encode($jamPulang) ?></td>
                                    <td><?= Html::encode($totalJam) ?></td>
                                    <td><?= Html::encode($entry['nama_shift'] ?? '-') ?></td>
                                    <td><?= Html::encode($entry['keterangan'] ?: '-') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php endif; ?>