<?php

use backend\models\Terbilang;
?>

<h1 class="text-center fw-bold" style="font-weight: bold; font-size: 20px">PT Profaskes Softech Indonesia</h1>
<p class="text-center fw-bold" style="font-size: 12px;">Ruko Plaza Menteng, Blok B No.17, Jalan Mh.Thamrin, Cibatu, Cikarang Selatan, Kab.Bekasi, Jawa Barat 17530.</p>
<hr style="margin: -10px 0;">

<p class="text-center " style="font-weight: bold; padding:0; margin-top:-20px;">Slip Gaji Karyawan</p>


<table class="table border-0 w-100">
    <tr style="border: 0px solid #dedede; padding: 0; margin: 0 ">
        <td colspan="1">
            <table class=" w-100">
                <tr style="border: 0.1px solid #dedede; padding: 0; margin: 0 ">
                    <th class="text-start text-capitalize" style="font-size:12px; margin:0; padding:0px;">nama</th>
                    <th class="text-start" style="font-size:12px; margin:0; padding:0px;">:</th>
                    <td class="text-start" style='height: 20px; padding:10px; font-size:12px; margin:0; padding:0px;'><?= $model['nama'] ?? '-' ?> </td>
                </tr>
                <tr style="border: 0.1px solid #dedede; padding: 0; margin: 0 ">
                    <th class="text-start text-capitalize" style="font-size:12px; margin:0; padding:0px;">kode karyawan</th>
                    <th class="text-start" style="font-size:12px; margin:0; padding:0px;">:</th>
                    <td class="text-start" style='height: 20px; padding:10px; font-size:12px; margin:0; padding:0px;'><?= $model['id_karyawan'] ?? '-' ?> </td>
                </tr>
                <tr style="border: 0.1px solid #dedede; padding: 0; margin: 0 ">
                    <th class="text-start text-capitalize" style="font-size:12px; margin:0; padding:0px;">gaji pokok</th>
                    <th class="text-start" style="font-size:12px; margin:0; padding:0px;">:</th>
                    <td class="text-start" style='height: 20px; padding:10px; font-size:12px; margin:0; padding:0px;'><?= 'Rp ' . number_format($model['nominal_gaji'] ?? 0, 0, ',', '.'); ?> </td>
                </tr>
                <tr style="border: 0.1px solid #dedede; padding: 0; margin: 0 ">
                    <th class="text-start text-capitalize" style="font-size:12px; margin:0; padding:0px;">gaji per jam</th>
                    <th class="text-start" style="font-size:12px; margin:0; padding:0px;">:</th>
                    <td class="text-start" style='height: 20px; padding:10px; font-size:12px; margin:0; padding:0px;'><?= 'Rp ' . number_format($model['gaji_perjam'] ?? 0, 0, ',', '.'); ?> </td>
                </tr>
                <tr style="border: 0.1px solid #dedede; padding: 0; margin: 0 ">
                    <th class="text-start text-capitalize" style="font-size:12px; margin:0; padding:0px;">metode perhitungan</th>
                    <th class="text-start" style="font-size:12px; margin:0; padding:0px;">:</th>
                    <td class="text-start" style='height: 20px; padding:10px; font-size:12px; margin:0; padding:0px;'><?= $model['metode_perhitungan_gaji'] ?? '-' ?> </td>
                </tr>
            </table>
        </td>
        <td colspan="1">
            <table class=" w-100">
                <tr style="border: 0.1px solid #dedede; padding: 0; margin: 0 ">
                    <th class="text-start text-capitalize" style="font-size:12px; margin:0; padding:0px;">periode</th>
                    <th class="text-start" style="font-size:12px; margin:0; padding:0px;">:</th>
                    <td class="text-start" style='height: 20px; padding:10px; font-size:12px; margin:0; padding:0px;'>
                        <?= date('F Y', strtotime($model['tanggal_awal'])) ?>
                    </td>
                </tr>
                <tr style="border: 0.1px solid #dedede; padding: 0; margin: 0 ">
                    <th class="text-start text-capitalize" style="font-size:12px; margin:0; padding:0px;">tanggal awal</th>
                    <th class="text-start" style="font-size:12px; margin:0; padding:0px;">:</th>
                    <td class="text-start" style='height: 20px; padding:10px; font-size:12px; margin:0; padding:0px;'><?= $model['tanggal_awal'] ?? '-' ?></td>
                </tr>
                <tr style="border: 0.1px solid #dedede; padding: 0; margin: 0 ">
                    <th class="text-start text-capitalize" style="font-size:12px; margin:0; padding:0px;">tanggal akhir</th>
                    <th class="text-start" style="font-size:12px; margin:0; padding:0px;">:</th>
                    <td class="text-start" style='height: 20px; padding:10px; font-size:12px; margin:0; padding:0px;'><?= $model['tanggal_akhir'] ?? '-' ?></td>
                </tr>
                <tr style="border: 0.1px solid #dedede; padding: 0; margin: 0 ">
                    <th class="text-start text-capitalize" style="font-size:12px; margin:0; padding:0px;">status</th>
                    <th class="text-start" style="font-size:12px; margin:0; padding:0px;">:</th>
                    <td class="text-start" style='height: 20px; padding:10px; font-size:12px; margin:0; padding:0px;'>
                        <?= (isset($model['id_transaksi_gaji']) && $model['id_transaksi_gaji'] != null) ? 'Sudah Diproses' : 'Belum Diproses' ?>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <tr>
        <td colspan="2"><br></td>
    </tr>

    <tr style="border: 0 solid #dedede; padding: 0; margin: 0 ">
        <td class="pe-2">
            <table class="w-100">
                <tr style="border: 0.1px solid #dedede; padding: 0; margin: 0 ">
                    <th class="p-1 text-start text-capitalize" style="font-size: 12px; background-color: #e8f5e8;">PENDAPATAN</th>
                    <th class="p-1 text-center" style="font-size: 12px; background-color: #e8f5e8;"></th>
                    <td class="p-1 text-end" style='height: 20px; padding:10px; font-size:12px; margin:0; padding:0px; background-color: #e8f5e8;'></td>
                </tr>
                <tr style="border: 0.1px solid #dedede; padding: 0; margin: 0 ">
                    <th class="p-1 text-start text-capitalize" style="font-size: 12px;">Gaji Pokok</th>
                    <th class="p-1 text-center" style="font-size: 12px;">:</th>
                    <td class="p-1 text-end" style='height: 20px; padding:10px; font-size:12px; margin:0; padding:0px;'><?= 'Rp ' . number_format($model['nominal_gaji'] ?? 0, 0, ',', '.'); ?> </td>
                </tr>
                <tr style="border: 0.1px solid #dedede; padding: 0; margin: 0 ">
                    <th class="p-1 text-start text-capitalize" style="font-size: 12px;">Tunjangan</th>
                    <th class="p-1 text-center" style="font-size: 12px;">:</th>
                    <td class="p-1 text-end" style='height: 20px; padding:10px; font-size:12px; margin:0; padding:0px;'><?= 'Rp ' . number_format($model['tunjangan_karyawan'] ?? 0, 0, ',', '.'); ?> </td>
                </tr>
                <tr style="border: 0.1px solid #dedede; padding: 0; margin: 0 ">
                    <th class="p-1 text-start text-capitalize" style="font-size: 12px; background-color: #f0f8ff;">Total Pendapatan</th>
                    <th class="p-1 text-center" style="font-size: 12px; background-color: #f0f8ff;">:</th>
                    <?php
                    $totalPendapatan = ($model['nominal_gaji'] ?? 0) + ($model['tunjangan_karyawan'] ?? 0);
                    ?>
                    <td class="p-1 text-end" style='height: 20px; padding:10px; font-size:12px; margin:0; padding:0px; background-color: #f0f8ff;'><strong><?= 'Rp ' . number_format($totalPendapatan, 0, ',', '.'); ?></strong></td>
                </tr>
            </table>
        </td>
        <td class="ps-2">
            <table class="w-100">
                <tr style="border: 0.1px solid #dedede; padding: 0; margin: 0 ">
                    <th class="p-1 text-start text-capitalize" style="font-size: 12px; background-color: #ffe8e8;">POTONGAN</th>
                    <th class="p-1 text-center" style="font-size: 12px; background-color: #ffe8e8;"></th>
                    <td class="p-1 text-end" style='height: 20px; padding:10px; font-size:12px; margin:0; padding:0px; background-color: #ffe8e8;'></td>
                </tr>
                <tr style="border: 0.1px solid #dedede; padding: 0; margin: 0 ">
                    <th class="p-1 text-start text-capitalize" style="font-size: 12px;">Potongan</th>
                    <th class="p-1 text-center" style="font-size: 12px;">:</th>
                    <td class="p-1 text-end" style='height: 20px; padding:10px; font-size:12px; margin:0; padding:0px;'><?= 'Rp ' . number_format($model['potongan_karyawan'] ?? 0, 0, ',', '.'); ?> </td>
                </tr>
                <tr style="border: 0.1px solid #dedede; padding: 0; margin: 0 ">
                    <th class="p-1 text-start text-capitalize" style="font-size: 12px; background-color: #fff8f0;">Total Potongan</th>
                    <th class="p-1 text-center" style="font-size: 12px; background-color: #fff8f0;">:</th>
                    <td class="p-1 text-end" style='height: 20px; padding:10px; font-size:12px; margin:0; padding:0px; background-color: #fff8f0;'><strong><?= 'Rp ' . number_format($model['potongan_karyawan'] ?? 0, 0, ',', '.'); ?></strong></td>
                </tr>
            </table>
        </td>
    </tr>

    <tr>
        <td colspan="2"><br></td>
    </tr>

    <tr style="border: 0px solid #dedede;">
        <td colspan="2">
            <table class="w-100">
                <tr style="border: 1px solid #dedede; padding: 0; margin: 0 ">
                    <th class="text-start text-capitalize" style="font-size: 14px; background-color: #f0f0f0;">GAJI BERSIH</th>
                    <th class="text-center" style="font-size: 14px; background-color: #f0f0f0;">:</th>
                    <?php
                    $gajiBersih = $totalPendapatan - ($model['potongan_karyawan'] ?? 0);
                    ?>
                    <td class="text-end" style='height: 20px; padding:10px; font-size:14px; margin:0; padding:0px; background-color: #f0f0f0;'>
                        <strong><?= 'Rp ' . number_format($gajiBersih, 0, ',', '.'); ?></strong>
                    </td>
                </tr>
                <tr style="border: 1px solid #dedede; padding: 0; margin: 0 ">
                    <th class="text-start text-capitalize;" style="font-size:12px; margin:0; padding:0px; background-color: rgba(53, 53, 53, 0.08); color: #000">Terbilang</th>
                    <th class="text-center" style="font-size:12px; margin:0; padding:0px; background-color: #fff; color: #000">:</th>
                    <?php
                    $terbilang = Terbilang::toTerbilang($gajiBersih) . ' Rupiah';
                    ?>
                    <td class="text-end " style='background-color: rgba(53, 53, 53, 0.08); color: #000; height: 20px; padding:10px; font-size:12px; margin:0; padding:0px;'>
                        <strong style="font-weight: bold;"><?= $terbilang ?></strong>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <tr>
        <td colspan="2"><br></td>
    </tr>

    <tr>
        <td colspan="2">
            <table class="w-100">
                <tr>
                    <td style="text-align: center; padding-top: 30px;">
                        <p style="margin: 0; font-size: 12px;">Disetujui oleh,</p>
                        <br><br><br>
                        <p style="margin: 0; font-size: 12px; border-top: 1px solid #000; padding-top: 5px; width: 200px; margin: 0 auto;">
                            _________________________
                        </p>
                    </td>
                    <td style="text-align: center; padding-top: 30px;">
                        <p style="margin: 0; font-size: 12px;">Diterima oleh,</p>
                        <br><br><br>
                        <p style="margin: 0; font-size: 12px; border-top: 1px solid #000; padding-top: 5px; width: 200px; margin: 0 auto;">
                            <?= $model['nama'] ?? '_________________________' ?>
                        </p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>