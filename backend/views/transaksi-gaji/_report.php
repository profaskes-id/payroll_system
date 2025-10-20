<?php

use yii\helpers\Html;

$periodeText = $periode_gaji ? "Periode: {$bulan}/{$tahun}" : "Periode: Bulan Ini";
?>

<div style="text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px;">
    <div style="font-size: 16px; font-weight: bold; margin-bottom: 5px;">LAPORAN TRANSAKSI GAJI KARYAWAN</div>
    <div style="font-size: 14px; margin-bottom: 5px;"><?= $periodeText ?></div>
    <div>Tanggal Cetak: <?= date('d/m/Y H:i:s') ?></div>
</div>

<table style="width: 100%; border-collapse: collapse; font-size: 8px; margin-bottom: 10px;">
    <thead>
        <tr>
            <th style="background-color: #343a40; color: white; font-weight: bold; padding: 8px 4px; border: 1px solid #444; text-align: center; width: 30px;">#</th>
            <th style="background-color: #343a40; color: white; font-weight: bold; padding: 8px 4px; border: 1px solid #444; text-align: center;">Nama Karyawan</th>
            <th style="background-color: #343a40; color: white; font-weight: bold; padding: 8px 4px; border: 1px solid #444; text-align: center;">Bagian & Jabatan</th>
            <th style="background-color: #343a40; color: white; font-weight: bold; padding: 8px 4px; border: 1px solid #444; text-align: center;">Gaji Pokok</th>
            <th style="background-color: #343a40; color: white; font-weight: bold; padding: 8px 4px; border: 1px solid #444; text-align: center;">Tunjangan</th>
            <th style="background-color: #343a40; color: white; font-weight: bold; padding: 8px 4px; border: 1px solid #444; text-align: center;">Potongan</th>
            <th style="background-color: #343a40; color: white; font-weight: bold; padding: 8px 4px; border: 1px solid #444; text-align: center;">Total Hadir</th>
            <th style="background-color: #343a40; color: white; font-weight: bold; padding: 8px 4px; border: 1px solid #444; text-align: center;">Total Tidak Hadir</th>
            <th style="background-color: #343a40; color: white; font-weight: bold; padding: 8px 4px; border: 1px solid #444; text-align: center;">Potongan Absensi</th>
            <th style="background-color: #343a40; color: white; font-weight: bold; padding: 8px 4px; border: 1px solid #444; text-align: center;">Potongan Terlambat</th>
            <th style="background-color: #343a40; color: white; font-weight: bold; padding: 8px 4px; border: 1px solid #444; text-align: center;">Total Lembur</th>
            <th style="background-color: #343a40; color: white; font-weight: bold; padding: 8px 4px; border: 1px solid #444; text-align: center;">Dinas Luar Belum Terbayar</th>
            <th style="background-color: #343a40; color: white; font-weight: bold; padding: 8px 4px; border: 1px solid #444; text-align: center;">Gaji Diterima</th>
            <th style="background-color: #343a40; color: white; font-weight: bold; padding: 8px 4px; border: 1px solid #444; text-align: center;">Status</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $no = 1;
        $totalGajiBersih = 0;
        $totalGajiPokok = 0;
        $totalTunjangan = 0;
        $totalPotongan = 0;
        $totalLembur = 0;
        $totalDinasLuar = 0;

        foreach ($finalData as $data):
            $gajiBersih = $data['gaji_bersih'] ?? 0;
            $gajiPokok = $data['nominal_gaji'] ?? 0;
            $tunjangan = $data['tunjangan_karyawan'] ?? 0;
            $potonganKaryawan = $data['potongan_karyawan'] ?? 0;
            $potonganAbsensi = $data['potongan_absensi'] ?? 0;
            $potonganTerlambat = $data['potongan_terlambat'] ?? 0;
            $totalPotonganKaryawan = $potonganKaryawan + $potonganAbsensi + $potonganTerlambat;
            $lembur = $data['total_pendapatan_lembur'] ?? 0;
            $dinasLuar = $data['dinas_luar_belum_terbayar'] ?? 0;
            $totalHadir = $data['total_absensi'] ?? 0;
            $totalTidakHadir = $data['total_alfa_range'] ?? 0;

            $totalGajiBersih += $gajiBersih;
            $totalGajiPokok += $gajiPokok;
            $totalTunjangan += $tunjangan;
            $totalPotongan += $totalPotonganKaryawan;
            $totalLembur += $lembur;
            $totalDinasLuar += $dinasLuar;
        ?>
            <tr>
                <td style="padding: 6px 4px; border: 1px solid #ddd; text-align: center;"><?= $no++ ?></td>
                <td style="padding: 6px 4px; border: 1px solid #ddd;"><?= Html::encode($data['nama'] ?? '') ?><br>
                    <small style="color: #666;">ID: <?= Html::encode($data['id_karyawan'] ?? '') ?></small>
                </td>
                <td style="padding: 6px 4px; border: 1px solid #ddd;">
                    <div><strong><?= Html::encode($data['nama_bagian'] ?? '') ?></strong></div>
                    <div style="color: #666;"><?= Html::encode($data['jabatan'] ?? '') ?></div>
                </td>
                <td style="padding: 6px 4px; border: 1px solid #ddd; text-align: right;"><?= number_format($gajiPokok, 0, ',', '.') ?></td>
                <td style="padding: 6px 4px; border: 1px solid #ddd; text-align: right;"><?= number_format($tunjangan, 0, ',', '.') ?></td>
                <td style="padding: 6px 4px; border: 1px solid #ddd; text-align: right;"><?= number_format($totalPotonganKaryawan, 0, ',', '.') ?></td>
                <td style="padding: 6px 4px; border: 1px solid #ddd; text-align: center;"><?= $totalHadir ?> hari</td>
                <td style="padding: 6px 4px; border: 1px solid #ddd; text-align: center;"><?= $totalTidakHadir ?> hari</td>
                <td style="padding: 6px 4px; border: 1px solid #ddd; text-align: right;"><?= number_format($potonganAbsensi, 0, ',', '.') ?></td>
                <td style="padding: 6px 4px; border: 1px solid #ddd; text-align: right;"><?= number_format($potonganTerlambat, 0, ',', '.') ?></td>
                <td style="padding: 6px 4px; border: 1px solid #ddd; text-align: right;"><?= number_format($lembur, 0, ',', '.') ?></td>
                <td style="padding: 6px 4px; border: 1px solid #ddd; text-align: right;"><?= number_format($dinasLuar, 0, ',', '.') ?></td>
                <td style="padding: 6px 4px; border: 1px solid #ddd; text-align: right; font-weight: bold;"><?= number_format($gajiBersih, 0, ',', '.') ?></td>
                <td style="padding: 6px 4px; border: 1px solid #ddd; text-align: center;">
                    <span style="padding: 2px 6px; border-radius: 3px; font-size: 7px; font-weight: bold; 
                    background-color: <?= ($data['status'] ?? 0) == 1 ? '#d4edda' : '#f8d7da' ?>; 
                    color: <?= ($data['status'] ?? 0) == 1 ? '#155724' : '#721c24' ?>;">
                        <?= ($data['status'] ?? 0) == 1 ? 'AKTIF' : 'NON-AKTIF' ?>
                    </span>
                </td>
            </tr>
        <?php endforeach; ?>

        <!-- TOTAL ROW -->
        <tr style="background-color: #f8f9fa; font-weight: bold;">
            <td colspan="3" style="padding: 8px 4px; border: 1px solid #333; text-align: right;">TOTAL KESELURUHAN:</td>
            <td style="padding: 8px 4px; border: 1px solid #333; text-align: right;"><?= number_format($totalGajiPokok, 0, ',', '.') ?></td>
            <td style="padding: 8px 4px; border: 1px solid #333; text-align: right;"><?= number_format($totalTunjangan, 0, ',', '.') ?></td>
            <td style="padding: 8px 4px; border: 1px solid #333; text-align: right;"><?= number_format($totalPotongan, 0, ',', '.') ?></td>
            <td style="padding: 8px 4px; border: 1px solid #333; text-align: center;">-</td>
            <td style="padding: 8px 4px; border: 1px solid #333; text-align: center;">-</td>
            <td style="padding: 8px 4px; border: 1px solid #333; text-align: right;">-</td>
            <td style="padding: 8px 4px; border: 1px solid #333; text-align: right;">-</td>
            <td style="padding: 8px 4px; border: 1px solid #333; text-align: right;"><?= number_format($totalLembur, 0, ',', '.') ?></td>
            <td style="padding: 8px 4px; border: 1px solid #333; text-align: right;"><?= number_format($totalDinasLuar, 0, ',', '.') ?></td>
            <td style="padding: 8px 4px; border: 1px solid #333; text-align: right; background-color: #e7f3ff;"><?= number_format($totalGajiBersih, 0, ',', '.') ?></td>
            <td style="padding: 8px 4px; border: 1px solid #333; text-align: center;">-</td>
        </tr>
    </tbody>
</table>

<?php if (empty($finalData)): ?>
    <div style="text-align: center; padding: 40px; font-style: italic; color: #666;">
        Tidak ada data transaksi gaji untuk periode ini.
    </div>
<?php endif; ?>

<div style="margin-top: 20px; font-size: 9px; color: #666;">
    <p><strong>Keterangan:</strong></p>
    <ul style="margin: 0; padding-left: 15px;">
        <li>Total Hadir: Jumlah hari karyawan hadir bekerja</li>
        <li>Total Tidak Hadir: Jumlah hari karyawan tidak hadir (alfa)</li>
        <li>Potongan: Total semua potongan (karyawan + absensi + terlambat)</li>
        <li>Gaji Diterima: Gaji bersih setelah semua penambahan dan pengurangan</li>
    </ul>
</div>