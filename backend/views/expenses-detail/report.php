<?php
// Fungsi helper untuk memformat angka ke Rupiah

use yii\helpers\Html;

$this->title = 'Laporan Pengeluaran';
function formatRupiah($angka)
{
    return 'Rp ' . number_format($angka, 0, ',', '.');
}


?>
<style>
    .custom-link {
        color: black;
        /* Warna default hitam */
        cursor: pointer;
        text-decoration: none;
        /* Hilangkan garis bawah */
        transition: color 0.1s ease;
        /* Animasi perubahan warna */
    }

    .custom-link:hover {
        color: blue;
        text-decoration: underline;
        /* Warna biru saat di-hover */
    }
</style>



<div class="costume-container">
    <p class="">
        <?= Html::a('<i class="svgIcon fa fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
    </p>
</div>


<div style="display: flex; justify-content: flex-start; gap: 10px;">

    <div style="width: 10vw;">
        <p class="d-block">
            <?= Html::a("Export to exel <i class='fa fa-table'></i> ", ['exel', 'tahun' => $tahun], ['target' => '_blank', 'class' => 'tambah-button']) ?>
        </p>
    </div>

    <div style="width: 10vw;">
        <p class="d-block">
            <?= Html::a("Export to PDF <i class='fa fa-print'></i> ", ['pdf', 'tahun' => $tahun], ['target' => '_blank', 'class' => 'cetak-button']) ?>
        </p>
    </div>
</div>

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
                    foreach ($kategori['subkategori'] as $index => $sub) {                        // Jika ini adalah subkategori pertama, tampilkan kategori
                        if ($index > 0) {
                            echo "<tr>";
                        }

                        // Hitung total untuk subkategori
                        $subTotal = $sub['total'];
                        $total += $subTotal;

                        // Tampilkan subkategori dan data bulan
                        // Array nama bulan untuk memudahkan perulangan
                        $bulan = ['januari', 'februari', 'maret', 'april', 'mei', 'juni', 'juli', 'agustus', 'september', 'oktober', 'november', 'desember'];

                        echo "<td>{$sub['nama_subkategori']}</td>";


                        foreach ($bulan as $namaBulan) {
                            $tanggalAwal = "01-" . str_pad(array_search($namaBulan, $bulan) + 1, 2, '0', STR_PAD_LEFT) . "-{$tahun}";
                            $tanggalAkhir = date('d-m-Y', strtotime('+1 month -1 day', strtotime($tanggalAwal))); // Hitung tanggal akhir
                            $id_kategori_expenses = $kategori['id_kategori_expenses'];
                            $id_subkategori_expenses = $sub['id_subkategori_expenses'];

                            echo "<td>" . Html::a(formatRupiah($sub['bulan'][$namaBulan]), '#', [
                                'onclick' => "postData('{$tanggalAwal}', '{$tanggalAkhir}', '{$id_kategori_expenses}', '{$id_subkategori_expenses}'); return false;",
                                'class' => 'custom-link' // Tambahkan class untuk styling hover
                            ]) . "</td>";
                        }
                        echo "<td>"  . formatRupiah($subTotal) . "</td>"; // Tampilkan total subkategori
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

                foreach ($bulan as $namaBulan) {
                    $tanggalAwal = "01-" . str_pad(array_search($namaBulan, $bulan) + 1, 2, '0', STR_PAD_LEFT) . "-{$tahun}";
                    $tanggalAkhir = date('d-m-Y', strtotime('+1 month -1 day', strtotime($tanggalAwal))); // Hitung tanggal akhir
                    $id_kategori_expenses = ''; // Sub kategori kosong
                    $id_subkategori_expenses = ''; // Kategori kosong

                    echo "<td><strong>" . Html::a(formatRupiah($totalPerBulan[$namaBulan]), '#', [
                        'onclick' => "postData('{$tanggalAwal}', '{$tanggalAkhir}', '{$id_kategori_expenses}', '{$id_subkategori_expenses}'); return false;",
                        'class' => 'custom-link', // Tambahkan class untuk styling hover
                    ]) . "</strong></td>";
                }

                echo "<td><strong>" . formatRupiah(array_sum($totalPerBulan)) . "</strong></td>"; // Total keseluruhan
                echo "</tr>";
                ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    function postData(tanggalAwal, tanggalAkhir, kategori, id_subkategori_expenses) {
        var form = document.createElement('form');
        form.method = 'POST';
        form.action = '/panel/expenses-detail/index';
        form.target = '_blank'; // Buka di tab baru

        // Tambahkan input hidden untuk tanggal_awal
        var inputAwal = document.createElement('input');
        inputAwal.type = 'hidden';
        inputAwal.name = 'tanggal_awal';
        inputAwal.value = tanggalAwal;
        form.appendChild(inputAwal);

        // Tambahkan input hidden untuk tanggal_akhir
        var inputAkhir = document.createElement('input');
        inputAkhir.type = 'hidden';
        inputAkhir.name = 'tanggal_akhir';
        inputAkhir.value = tanggalAkhir;
        form.appendChild(inputAkhir);

        // Tambahkan input hidden untuk id_kategori_expenses
        var inputKategori = document.createElement('input');
        inputKategori.type = 'hidden';
        inputKategori.name = 'id_kategori_expenses';
        inputKategori.value = kategori;
        form.appendChild(inputKategori);

        // Tambahkan input hidden untuk id_subkategori_expenses
        var inputId = document.createElement('input');
        inputId.type = 'hidden';
        inputId.name = 'id_subkategori_expenses';
        inputId.value = id_subkategori_expenses;
        form.appendChild(inputId);


        // Submit form
        document.body.appendChild(form);
        form.submit();
    }
</script>