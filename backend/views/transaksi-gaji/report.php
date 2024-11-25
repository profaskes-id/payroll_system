<?php

use backend\models\helpers\KaryawanHelper;
use backend\models\PeriodeGaji;
use backend\models\Tanggal;

$karyawan = new KaryawanHelper();
$tanggal = new Tanggal();
$periodeGaji = PeriodeGaji::findOne($periode_gajiID);

$model = $dataProvider->models;
?>

<table border="1">
    <tr>
        <!-- =========== -->
        <th>Karyawan</th>
        <th>Kode Karyawan</th>
        <th>Bagian</th>
        <th>Jabatan</th>
        <th>Jam Kerja</th>
        <th>Status Karyawan</th>

        <!-- \\======== -->
        <th>Bulan - Tahun</th>
        <th>Jumlah Hari kerja</th>
        <th>Jumlah Hadir</th>
        <th>Jumlah Sakit</th>
        <th>Jumlah Cuti</th>

        <!-- ========= -->
        <th>Jumlah Jam Lembur</th>
        <th>Lembur Perjam</th>
        <th>Total Lembur</th>

        <!-- ======= -->
        <th>Jumlah WFH</th>
        <th>Potongan WFH / Hari</th>
        <th>Jumlah Potongan WFH</th>

        <!-- ===== -->
        <th>Jumlah tidak Hadir</th>
        <th>Potongan Tidak Hadir / hari</th>
        <th>Jumlah potongan tidak Hadir</th>

        <!-- ============ -->
        <th>Potongan Terlambat / Menit</th>
        <th>Jumlah Potongan Terlambat</th>

        <!-- =========== -->
        <th>Keterangan Tunjangan Lainnya</th>
        <th>Tunjangan Lainnya</th>
        <th>Keterangan Potongan Lainnya</th>
        <th>Potongan Lainnya</th>

        <!-- =========== -->
        <th>Jumlah Tunjangan</th>
        <th>Jumlah Potongan</th>
        <th>Gaji Pokok </th>
        <th>Gaji Diterima</th>
        <th>Terima Pada Tanggal</th>
    </tr>


    <?php foreach ($model as $value) : ?>

        <tr>
            <!-- ========== -->
            <td><?= $karyawan->getKaryawanById($value['id_karyawan'])[0]['nama'] ??  $value['id_karyawan'] ?? '-' ?></td>
            <td><?= $value['kode_karyawan'] ?? '-' ?></td>
            <td><?= $value['bagian'] ?? '-' ?></td>
            <td><?= $value['jabatan'] ?? '-' ?></td>
            <td><?= $value['jam_kerja'] ?? '-' ?></td>
            <td><?= $value['status_karyawan'] ?? '-' ?></td>

            <!-- ====== -->
            <td><?= ($tanggal->getBulan($value['bulan']) ?? '-') . '/' . $value['tahun'] ?></td>
            <td><?= $value['jumlah_hari_kerja'] ?? '-' ?></td>
            <td><?= $value['jumlah_hadir'] ?? '-' ?></td>
            <td><?= $value['jumlah_sakit'] ?? '-' ?></td>
            <td><?= $value['jumlah_cuti'] ?? '-' ?></td>

            <!-- ========= -->
            <td><?= $value['jumlah_jam_lembur'] ?? '-' ?></td>
            <td><?= $value['lembur_perjam'] ?? '-' ?></td>
            <td><?= $value['total_lembur'] ?? '-' ?></td>

            <!-- ===== -->
            <td><?= $value['jumlah_wfh'] ?? '-' ?></td>
            <td><?= $value['potongan_wfh_hari'] ?? '-' ?></td>
            <td><?= $value['jumlah_potongan_wfh'] ?? '-' ?></td>

            <!-- ===== -->
            <td><?= $value['jumlah_tidak_hadir'] ?? '-' ?></td>
            <td><?= $value['potongan_tidak_hadir_hari'] ?? '-' ?></td>
            <td><?= $value['jumlah_potongan_tidak_hadir'] ?? '-' ?></td>

            <!-- ============= -->
            <td><?= $value['potongan_terlambat_permenit'] ?? '-' ?></td>
            <td><?= $value['jumlah_potongan_terlambat'] ?? '-' ?></td>
            <!-- =============== -->
            <td><?= $value['keterangan_tunjangan_lainnya'] ?? '-' ?></td>
            <td><?= $value['tunjangan_lainnya'] ?? '-' ?></td>
            <td><?= $value['keterangan_potongan_lainnya'] ?? '-' ?></td>
            <td><?= $value['potongan_lainnya'] ?? '-' ?></td>

            <!-- =========== -->
            <td><?= $value['jumlah_tunjangan'] ?? '-' ?></td>
            <td><?= $value['jumlah_potongan'] ?? '-' ?></td>
            <td><?= $value['gaji_pokok'] ?? '-' ?></td>
            <td><?= $value['gaji_diterima'] ?? '-' ?></td>
            <td><?= $value['terima'] ?? '-' ?></td>

        </tr>
    <?php endforeach; ?>
</table>


<?php
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; Filename = Rekapan-Transaksi.xls");
die;
?>