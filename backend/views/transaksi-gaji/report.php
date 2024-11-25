<table border="1">
    <tr>
        <th>
            Nama Karyawan
        </th>
        <th>
            Bulan
        </th>
        <th>
            Tahun
        </th>
        <th>
            Periode Gaji
        </th>
        <th>
            Jumlah Tunjangan
        </th>
        <th>
            Jumlah Potongan
        </th>
        <th>
            Gaji Diterima
        </th>
    </tr>

    <?php
    $model = $dataProvider->models;
    foreach ($model as $value) :
    ?>

        <tr>
            <td><?= $value['nama'] ?? '-' ?></td>
            <td><?= $value['bulan'] ?? '-' ?></td>
            <td><?= $value['tahun'] ?? '-' ?></td>
            <td><?= $value['id_periode_gaji'] ?? '-' ?></td>
            <td><?= $value['jumlah_tunjangan'] ?? '-' ?></td>
            <td><?= $value['jumlah_potongan'] ?? '-' ?></td>
            <td><?= $value['gaji_diterima'] ?? '-' ?></td>

        </tr>
    <?php endforeach; ?>
</table>


<?php
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; Filename = Rekapan-Transaksi.xls");
die;
?>