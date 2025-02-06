<div style="font-size: 12px; position: relative;">
    <div style="overflow-x: auto;">
        <table style="border: 1px solid black; width: 100%; border-collapse: collapse;">
            <thead>
                <tr>
                    <th colspan="15" style="text-align: center; background-color: #f2f2f2;">
                        <h4>Data Pengeluaran Tahun <?= $tahun ?></h4>
                    </th>
                </tr>
                <tr>
                    <th style="border: 1px solid black;">Kategori</th>
                    <th style="border: 1px solid black;">Sub Kategori</th>
                    <th style="border: 1px solid black;">Januari</th>
                    <th style="border: 1px solid black;">Februari</th>
                    <th style="border: 1px solid black;">Maret</th>
                    <th style="border: 1px solid black;">April</th>
                    <th style="border: 1px solid black;">Mei</th>
                    <th style="border: 1px solid black;">Juni</th>
                    <th style="border: 1px solid black;">Juli</th>
                    <th style="border: 1px solid black;">Agustus</th>
                    <th style="border: 1px solid black;">September</th>
                    <th style="border: 1px solid black;">Oktober</th>
                    <th style="border: 1px solid black;">November</th>
                    <th style="border: 1px solid black;">Desember</th>
                    <th style="border: 1px solid black;">Total</th>
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
                    echo "<td rowspan='{$subkategoriCount}' style='border: 1px solid black;'>{$kategori['nama_kategori']}</td>";

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
                        echo "<td style='border: 1px solid black;'>{$sub['nama_subkategori']}</td>";
                        echo "<td style='border: 1px solid black;'>" . ($sub['bulan']['januari']) . "</td>";
                        echo "<td style='border: 1px solid black;'>" . ($sub['bulan']['februari']) . "</td>";
                        echo "<td style='border: 1px solid black;'>" . ($sub['bulan']['maret']) . "</td>";
                        echo "<td style='border: 1px solid black;'>" . ($sub['bulan']['april']) . "</td>";
                        echo "<td style='border: 1px solid black;'>" . ($sub['bulan']['mei']) . "</td>";
                        echo "<td style='border: 1px solid black;'>" . ($sub['bulan']['juni']) . "</td>";
                        echo "<td style='border: 1px solid black;'>" . ($sub['bulan']['juli']) . "</td>";
                        echo "<td style='border: 1px solid black;'>" . ($sub['bulan']['agustus']) . "</td>";
                        echo "<td style='border: 1px solid black;'>" . ($sub['bulan']['september']) . "</td>";
                        echo "<td style='border: 1px solid black;'>" . ($sub['bulan']['oktober']) . "</td>";
                        echo "<td style='border: 1px solid black;'>" . ($sub['bulan']['november']) . "</td>";
                        echo "<td style='border: 1px solid black;'>" . ($sub['bulan']['desember']) . "</td>";
                        echo "<td style='border: 1px solid black;'>" . ($subTotal) . "</td>"; // Tampilkan total subkategori
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
                echo "<td colspan='2' style='border: 1px solid black; text-align: left;'><strong>Total Per Bulan:</strong></td>";
                echo "<td style='border: 1px solid black;'><strong>" . ($totalPerBulan['januari']) . "</strong></td>";
                echo "<td style='border: 1px solid black;'><strong>" . ($totalPerBulan['februari']) . "</strong></td>";
                echo "<td style='border: 1px solid black;'><strong>" . ($totalPerBulan['maret']) . "</strong></td>";
                echo "<td style='border: 1px solid black;'><strong>" . ($totalPerBulan['april']) . "</strong></td>";
                echo "<td style='border: 1px solid black;'><strong>" . ($totalPerBulan['mei']) . "</strong></td>";
                echo "<td style='border: 1px solid black;'><strong>" . ($totalPerBulan['juni']) . "</strong></td>";
                echo "<td style='border: 1px solid black;'><strong>" . ($totalPerBulan['juli']) . "</strong></td>";
                echo "<td style='border: 1px solid black;'><strong>" . ($totalPerBulan['agustus']) . "</strong></td>";
                echo "<td style='border: 1px solid black;'><strong>" . ($totalPerBulan['september']) . "</strong></td>";
                echo "<td style='border: 1px solid black;'><strong>" . ($totalPerBulan['oktober']) . "</strong></td>";
                echo "<td style='border: 1px solid black;'><strong>" . ($totalPerBulan['november']) . "</strong></td>";
                echo "<td style='border: 1px solid black;'><strong>" . ($totalPerBulan['desember']) . "</strong></td>";
                echo "<td style='border: 1px solid black;'><strong>" . (array_sum($totalPerBulan)) . "</strong></td>"; // Total keseluruhan
                echo "</tr>";
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; Filename = rekapan-pengeluaran-tahun-{$tahun}.xls");
die;
?>