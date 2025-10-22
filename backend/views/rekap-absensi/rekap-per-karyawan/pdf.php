<?php

use yii\helpers\Html;

$this->title = 'Report Rekap Absensi';
?>

<h3 class="text-center">Rekap Absensi Karyawan</h3>
<p class="text-center">
    Periode: <?= Html::encode(date('d-m-Y', strtotime($tanggal_awal))) ?> s/d <?= Html::encode(date('d-m-Y', strtotime($tanggal_akhir))) ?>
</p>

<hr>

<?php if (!$karyawan): ?>
    <p><strong>Karyawan tidak ditemukan.</strong></p>
<?php else: ?>
    <table>
        <tr>
            <td><strong>Nama</strong></td>
            <td>: <?= Html::encode($karyawan['nama']) ?></td>
        </tr>
        <tr>
            <td><strong>Kode</strong></td>
            <td>: <?= Html::encode($karyawan['kode_karyawan']) ?></td>
        </tr>
        <tr>
            <td><strong>Bagian</strong></td>
            <td>: <?= Html::encode($karyawan['nama_bagian']) ?></td>
        </tr>
        <tr>
            <td><strong>Jabatan</strong></td>
            <td>: <?= Html::encode($karyawan['jabatan']) ?></td>
        </tr>
        <tr>
            <td><strong>Jam Kerja</strong></td>
            <td>: <?= Html::encode($karyawan['nama_jam_kerja']) ?></td>
        </tr>
    </table>

    <br>

    <table class="table table-bordered table-sm">
        <thead class="text-center thead-light">
            <tr>
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
            <?php if (empty($dataAbsensi)): ?>
                <tr>
                    <td colspan="7" class="text-center text-muted">Tidak ada data absensi.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($dataAbsensi as $entry): ?>
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
                    ?>
                    <tr<?= $hari === 0 ? ' class="table-secondary"' : '' ?>>
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
<?php endif; ?>