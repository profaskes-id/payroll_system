<?php
// Fungsi helper untuk memformat angka ke Rupiah

use yii\helpers\Html;

$this->title = 'Laporan Pengeluaran';
function formatRupiah($angka)
{
    return 'Rp ' . number_format($angka, 0, ',', '.');
}
?>

<div style="font-size: 12px" class="absensi-index position-relative">
    <div class="table-container table-responsive">
        <table class="table border w-100 table-bordered">
            <thead>
                <tr>
                    <th colspan="15" class="text-center">
                        <h4>Data Pengeluaran Tahun <?= $tahun ?></h4>
                    </th>
                </tr>
                <tr>
                    <th>Kategori</th>
                    <th>Sub Kategori</th>
                    <th>Januari</th>
                    <th>Februari</th>
                    <th>Maret</th>
                    <th>April</th>
                    <th>Mei</th>
                    <th>Juni</th>
                    <th>Juli</th>
                    <th>Agustus</th>
                    <th>September</th>
                    <th>Oktober</th>
                    <th>November</th>
                    <th>Desember</th>
                    <th>Total</th>
                </tr>
            </thead>

            <tbody>
                <?php
                // Inisialisasi total per bulan
                $totalPerBulan = [
                    'januari' => 0,
                    'februari' => 0,
                    'maret' => 0,
                    'april' => 0,
                    'mei' => 0,
                    'juni' => 0,
                    'juli' => 0,
                    'agustus' => 0,
                    'september' => 0,
                    'oktober' => 0,
                    'november' => 0,
                    'desember' => 0,
                ];

                foreach ($data as $kategori) {
                    // Hitung jumlah subkategori untuk row span
                    $subkategoriCount = count($kategori['subkategori']);
                    $total = 0; // Inisialisasi total untuk kategori

                    // Tampilkan kategori dengan row span
                    echo "<tr>";
                    echo "<td rowspan='{$subkategoriCount}'>{$kategori['nama_kategori']}</td>";

                    // Loop melalui setiap subkategori
                    foreach ($kategori['subkategori'] as $index => $sub) {
                        // Jika ini adalah subkategori pertama, tampilkan kategori
                        if ($index > 0) {
                            echo "<tr>";
                        }

                        // Hitung total untuk subkategori
                        $subTotal = $sub['total'];
                        $total += $subTotal;

                        // Tampilkan subkategori dan data bulan
                        echo "<td>{$sub['nama_subkategori']}</td>";
                        echo "<td>" . formatRupiah($sub['bulan']['januari']) . "</td>";
                        echo "<td>" . formatRupiah($sub['bulan']['februari']) . "</td>";
                        echo "<td>" . formatRupiah($sub['bulan']['maret']) . "</td>";
                        echo "<td>" . formatRupiah($sub['bulan']['april']) . "</td>";
                        echo "<td>" . formatRupiah($sub['bulan']['mei']) . "</td>";
                        echo "<td>" . formatRupiah($sub['bulan']['juni']) . "</td>";
                        echo "<td>" . formatRupiah($sub['bulan']['juli']) . "</td>";
                        echo "<td>" . formatRupiah($sub['bulan']['agustus']) . "</td>";
                        echo "<td>" . formatRupiah($sub['bulan']['september']) . "</td>";
                        echo "<td>" . formatRupiah($sub['bulan']['oktober']) . "</td>";
                        echo "<td>" . formatRupiah($sub['bulan']['november']) . "</td>";
                        echo "<td>" . formatRupiah($sub['bulan']['desember']) . "</td>";
                        echo "<td>" . formatRupiah($subTotal) . "</td>"; // Tampilkan total subkategori
                        echo "</tr>";

                        // Tambahkan ke total per bulan
                        $totalPerBulan['januari'] += $sub['bulan']['januari'];
                        $totalPerBulan['februari'] += $sub['bulan']['februari'];
                        $totalPerBulan['maret'] += $sub['bulan']['maret'];
                        $totalPerBulan['april'] += $sub['bulan']['april'];
                        $totalPerBulan['mei'] += $sub['bulan']['mei'];
                        $totalPerBulan['juni'] += $sub['bulan']['juni'];
                        $totalPerBulan['juli'] += $sub['bulan']['juli'];
                        $totalPerBulan['agustus'] += $sub['bulan']['agustus'];
                        $totalPerBulan['september'] += $sub['bulan']['september'];
                        $totalPerBulan['oktober'] += $sub['bulan']['oktober'];
                        $totalPerBulan['november'] += $sub['bulan']['november'];
                        $totalPerBulan['desember'] += $sub['bulan']['desember'];
                    }
                }

                // Tampilkan total per bulan di bagian bawah tabel
                echo "<tr>";
                echo "<td colspan='2' class='text-left'><strong>Total Per Bulan:</strong></td>";
                echo "<td><strong>" . formatRupiah($totalPerBulan['januari']) . "</strong></td>";
                echo "<td><strong>" . formatRupiah($totalPerBulan['februari']) . "</strong></td>";
                echo "<td><strong>" . formatRupiah($totalPerBulan['maret']) . "</strong></td>";
                echo "<td><strong>" . formatRupiah($totalPerBulan['april']) . "</strong></td>";
                echo "<td><strong>" . formatRupiah($totalPerBulan['mei']) . "</strong></td>";
                echo "<td><strong>" . formatRupiah($totalPerBulan['juni']) . "</strong></td>";
                echo "<td><strong>" . formatRupiah($totalPerBulan['juli']) . "</strong></td>";
                echo "<td><strong>" . formatRupiah($totalPerBulan['agustus']) . "</strong></td>";
                echo "<td><strong>" . formatRupiah($totalPerBulan['september']) . "</strong></td>";
                echo "<td><strong>" . formatRupiah($totalPerBulan['oktober']) . "</strong></td>";
                echo "<td><strong>" . formatRupiah($totalPerBulan['november']) . "</strong></td>";
                echo "<td><strong>" . formatRupiah($totalPerBulan['desember']) . "</strong></td>";
                echo "<td><strong>" . formatRupiah(array_sum($totalPerBulan)) . "</strong></td>"; // Total keseluruhan
                echo "</tr>";
                ?>
            </tbody>
        </table>
    </div>
</div>