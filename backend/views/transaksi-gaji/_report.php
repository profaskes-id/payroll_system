<?php

use yii\helpers\Html;

$namaBulan = [
    '01' => 'Januari',
    '02' => 'Februari',
    '03' => 'Maret',
    '04' => 'April',
    '05' => 'Mei',
    '06' => 'Juni',
    '07' => 'Juli',
    '08' => 'Agustus',
    '09' => 'September',
    '10' => 'Oktober',
    '11' => 'November',
    '12' => 'Desember',
];

// Pastikan $bulan punya leading zero
$bulanFormatted = str_pad($bulan, 2, '0', STR_PAD_LEFT);

// Ambil nama bulan dari array
$bulanNama = $namaBulan[$bulanFormatted] ?? $bulan;

// Format akhir
$periodeText = $periode_gaji ? "Periode: {$bulanNama} {$tahun}" : "Periode: Bulan Ini";
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
            <th style="background-color: #343a40; color: white; font-weight: bold; padding: 8px 4px; border: 1px solid #444; text-align: center; width: 30px;">Status Laporan</th>
            <th style="background-color: #343a40; color: white; font-weight: bold; padding: 8px 4px; border: 1px solid #444; text-align: center;">Nama Karyawan</th>
            <th style="background-color: #343a40; color: white; font-weight: bold; padding: 8px 4px; border: 1px solid #444; text-align: center;">Bagian & Jabatan</th>
            <th style="background-color: #343a40; color: white; font-weight: bold; padding: 8px 4px; border: 1px solid #444; text-align: center;">Gaji Pokok</th>
            <th style="background-color: #343a40; color: white; font-weight: bold; padding: 8px 4px; border: 1px solid #444; text-align: center;">Tunjangan</th>
            <th style="background-color: #343a40; color: white; font-weight: bold; padding: 8px 4px; border: 1px solid #444; text-align: center;">Potongan</th>
            <th style="background-color: #343a40; color: white; font-weight: bold; padding: 8px 4px; border: 1px solid #444; text-align: center;">Pendapatan Lain</th>
            <th style="background-color: #343a40; color: white; font-weight: bold; padding: 8px 4px; border: 1px solid #444; text-align: center;">Potongan lain</th>
            <th style="background-color: #343a40; color: white; font-weight: bold; padding: 8px 4px; border: 1px solid #444; text-align: center;">Kasbon</th>
            <th style="background-color: #343a40; color: white; font-weight: bold; padding: 8px 4px; border: 1px solid #444; text-align: center;">Hari Kerja Efektif</th>
            <th style="background-color: #343a40; color: white; font-weight: bold; padding: 8px 4px; border: 1px solid #444; text-align: center;">Total Hadir</th>
            <th style="background-color: #343a40; color: white; font-weight: bold; padding: 8px 4px; border: 1px solid #444; text-align: center;">Total Tidak Hadir</th>
            <th style="background-color: #343a40; color: white; font-weight: bold; padding: 8px 4px; border: 1px solid #444; text-align: center;">Potongan Absensi</th>
            <th style="background-color: #343a40; color: white; font-weight: bold; padding: 8px 4px; border: 1px solid #444; text-align: center;">Potongan Terlambat</th>
            <th style="background-color: #343a40; color: white; font-weight: bold; padding: 8px 4px; border: 1px solid #444; text-align: center;">Total Lembur</th>
            <th style="background-color: #343a40; color: white; font-weight: bold; padding: 8px 4px; border: 1px solid #444; text-align: center;">Dinas Luar Belum Terbayar</th>
            <th style="background-color: #343a40; color: white; font-weight: bold; padding: 8px 4px; border: 1px solid #444; text-align: center;">Gaji Diterima</th>
            <th style="background-color: #343a40; color: white; font-weight: bold; padding: 8px 4px; border: 1px solid #444; text-align: center;"> Bank</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $no = 1;
        $totalGajiBersih = 0;
        $totalGajiPokok = 0;
        $totalTunjangan = 0;
        $totalPotongan = 0;
        $totalkasbon = 0;
        $totalLembur = 0;
        $totalDinasLuar = 0;
        $pendapatanLain = 0;
        $potonganLain = 0;
        $hariKerjaEfektif = 0;
        $potonganabsensi = 0;
        $potonganterlambat = 0;

        foreach ($finalData as $data):

            $nama_bank = $data['nama_bank'];
            $nomer_rekening = $data['nomer_rekening'];
            $hari_kerja_efektif = $data['hari_kerja_efektif'];
            $pendapatan_lainnya = floatval($data['pendapatan_lainnya'] ?? 0);
            $potongan_lainnya = floatval($data['potongan_lainnya'] ?? 0);
            $gajiPokok = floatval($data['nominal_gaji'] ?? 0);
            $tunjangan = floatval($data['tunjangan_karyawan'] ?? 0);
            $potonganKaryawan = floatval($data['potongan_karyawan'] ?? 0);
            $kasbonKaryawan = floatval($data['potongan_kasbon'] ?? 0); // Perbaikan: kasbon_karyawan -> potongan_kasbon
            $potonganAbsensi = floatval($data['potongan_absensi'] ?? 0);
            $potonganTerlambat = floatval($data['potongan_terlambat'] ?? 0);
            $totalPotonganKaryawan = $potonganKaryawan + $potonganAbsensi + $potonganTerlambat;
            $lembur = floatval($data['total_pendapatan_lembur'] ?? 0);
            $dinasLuar = floatval($data['dinas_luar_belum_terbayar'] ?? 0);
            $totalHadir = $data['total_absensi'] ?? 0;
            $totalTidakHadir = $data['total_alfa_range'] ?? 0;

            // Hitung gaji bersih
            $gajiBersih = ($gajiPokok + $tunjangan + $lembur + $dinasLuar) - ($potonganKaryawan + $kasbonKaryawan + $potonganAbsensi + $potonganTerlambat) + $pendapatan_lainnya - $potongan_lainnya;

            $totalGajiBersih += $gajiBersih;
            $totalGajiPokok += $gajiPokok;
            $totalTunjangan += $tunjangan;
            $totalPotongan += $totalPotonganKaryawan;
            $totalkasbon += $kasbonKaryawan;
            $totalLembur += $lembur;
            $totalDinasLuar += $dinasLuar;
            $pendapatanLain += $pendapatan_lainnya;
            $potonganLain += $potongan_lainnya;

            $hariKerjaEfektif = $hari_kerja_efektif;
            $potonganabsensi += $potonganAbsensi;
            $potonganterlambat += $potonganTerlambat;
        ?>

            <tr>
                <td style="padding: 6px 4px; border: 1px solid #ddd; text-align: center;"><?= $no++ ?></td>
                <td style="padding: 6px 4px; border: 1px solid #ddd; text-align: center;">
                    <?php
                    $status = $data['status_laporan'] ?? 'draft';

                    if ($status === 'fix') {
                        echo '<span style="color: #2e7d32; font-weight: bold;">FIX</span>';
                    } else {
                        echo '<span style="color: #000000;">DRAFT</span>';
                    }
                    ?>
                </td>
                <td style="padding: 6px 4px; border: 1px solid #ddd;"><?= Html::encode($data['nama'] ?? '') ?></td>
                <td style="padding: 6px 4px; border: 1px solid #ddd;">
                    <div><strong><?= Html::encode($data['nama_bagian'] ?? '') ?></strong></div>
                    <div style="color: #666;"><?= Html::encode($data['jabatan'] ?? '') ?></div>
                    <div style="color: #666;"><?= Html::encode($data['status_pekerjaan'] ?? '') ?></div>
                </td>
                <td style="padding: 6px 4px; border: 1px solid #ddd; text-align: right;"><?= number_format($gajiPokok, 0, ',', '.') ?></td>
                <td style="padding: 6px 4px; border: 1px solid #ddd; text-align: right;"><?= number_format($tunjangan, 0, ',', '.') ?></td>
                <td style="padding: 6px 4px; border: 1px solid #ddd; text-align: right;"><?= number_format($totalPotonganKaryawan, 0, ',', '.') ?></td>


                <td style="padding: 6px 4px; border: 1px solid #ddd; text-align: right;"><?= number_format($pendapatan_lainnya, 0, ',', '.') ?></td>
                <td style="padding: 6px 4px; border: 1px solid #ddd; text-align: right;"><?= number_format($potongan_lainnya, 0, ',', '.') ?></td>


                <td style="padding: 6px 4px; border: 1px solid #ddd; text-align: right;"><?= number_format($kasbonKaryawan, 0, ',', '.') ?></td>
                <td style="padding: 6px 4px; border: 1px solid #ddd; text-align: center;"><?= $hari_kerja_efektif ?> hari</td>
                <td style="padding: 6px 4px; border: 1px solid #ddd; text-align: center;"><?= $totalHadir ?> hari</td>
                <td style="padding: 6px 4px; border: 1px solid #ddd; text-align: center;"><?= $totalTidakHadir ?> hari</td>
                <td style="padding: 6px 4px; border: 1px solid #ddd; text-align: right;"><?= number_format($potonganAbsensi, 0, ',', '.') ?></td>
                <td style="padding: 6px 4px; border: 1px solid #ddd; text-align: right;"><?= number_format($potonganTerlambat, 0, ',', '.') ?></td>
                <td style="padding: 6px 4px; border: 1px solid #ddd; text-align: right;"><?= number_format($lembur, 0, ',', '.') ?></td>
                <td style="padding: 6px 4px; border: 1px solid #ddd; text-align: right;"><?= number_format($dinasLuar, 0, ',', '.') ?></td>
                <td style="padding: 6px 4px; border: 1px solid #ddd; text-align: right; font-weight: bold;"><?= number_format($gajiBersih, 0, ',', '.') ?></td>
                <td style="padding: 8px 4px; border: 1px solid #333; text-align: right;">
                    <p style="padding: 0; margin:0">
                        <?= $nama_bank ?>
                    </p>
                    <hr style="padding: 0; margin:0">
                    <p style="padding: 0; margin:0">
                        <?= $nomer_rekening ?>
                    </p>
                </td>
            </tr>
        <?php endforeach; ?>

        <!-- TOTAL ROW -->
        <tr style="background-color: #f8f9fa; font-weight: bold;">
            <td colspan="4" style="padding: 8px 4px; border: 1px solid #333; text-align: right;">TOTAL KESELURUHAN:</td>
            <td style="padding: 8px 4px; border: 1px solid #333; text-align: right;"><?= number_format($totalGajiPokok, 0, ',', '.') ?></td>
            <td style="padding: 8px 4px; border: 1px solid #333; text-align: right;"><?= number_format($totalTunjangan, 0, ',', '.') ?></td>
            <td style="padding: 8px 4px; border: 1px solid #333; text-align: right;"><?= number_format($totalPotongan, 0, ',', '.') ?></td>
            <td style="padding: 8px 4px; border: 1px solid #333; text-align: right;"><?= number_format($pendapatanLain, 0, ',', '.') ?></td>
            <td style="padding: 8px 4px; border: 1px solid #333; text-align: right;"><?= number_format($potonganLain, 0, ',', '.') ?></td>
            <td style="padding: 8px 4px; border: 1px solid #333; text-align: right;"><?= number_format($totalkasbon, 0, ',', '.') ?></td>
            <td style="padding: 8px 4px; border: 1px solid #333; text-align: center;">-</td>
            <td style="padding: 8px 4px; border: 1px solid #333; text-align: center;">-</td>
            <td style="padding: 8px 4px; border: 1px solid #333; text-align: center;">-</td>
            <td style="padding: 8px 4px; border: 1px solid #333; text-align: right;"><?= number_format($potonganabsensi, 0, ',', '.') ?></td>
            <td style="padding: 8px 4px; border: 1px solid #333; text-align: right;"><?= number_format($potonganterlambat, 0, ',', '.') ?></td>
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

<!-- <div style="margin-top: 20px; font-size:9px; color: #666;">
    <p><strong>Keterangan:</strong></p>
    <ul style="margin: 0; padding-left: 15px; text-transform: capitalize;">
        <li>Tunjangan: Tunjangan yang diberikan perusahaan kepada karyawan</li>
        <li>Potongan: Potongan yang diberikan perusahaan kepada karyawan</li>
        <li>Pendapatan Lainnya: Potongan yang diberikan perusahaan kepada karyawan</li>
        <li>Potongan: Potongan yang diberikan perusahaan kepada karyawan</li>
        <li>Total Hadir: Jumlah hari karyawan hadir bekerja</li>
        <li>Total Tidak Hadir: Jumlah hari karyawan tidak hadir (alfa)</li>
        <li>Potongan Absensi: Potongan alfa & potongan WFH sesuai ketentuan perusahaan</li>
        <li>Total Lembur: Biaya lembur yang dibayarkan perusahaan kepada karyawan</li>
        <li>Dinas Luar Belum Terbayar: Biaya dinas luar yang belum dibayarkan perusahaan</li>
        <li>Gaji diterima: Gaji bersih setelah semua penambahan dan pengurangan</li>
    </ul>
</div> -->