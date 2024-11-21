<?php

use backend\models\Terbilang;
?>



<h1 class="text-center fw-bold" style="font-weight: bold; font-size: 20px">PT Profaskes Softech Indonesia</h1>
<p class="text-center fw-bold" style="font-size: 12px;">Ruko Plaza Menteng, Blok B No.17, Jalan Mh.Thamrin, Cibatu, Cikarang Selatan, Kab.Bekasi, Jawa Barat 17530.</p>
<hr style="margin-top: -10px;">

<p class="text-center " style="font-weight: bold;">Slip Gaji Karyawan</p>
<p class="text-center fw-bold" style="font-size: 12px;">Periode : 04 Oktober 2024 s/d 05 November 2024 </p>




<table class="w-100 border-0 table">
    <tr style="border: 0px solid #dedede; padding: 0; margin: 0 ">
        start <td colspan="2">
            <table class=" w-100 ">
                <tr style="border: 0.1px solid #dedede; padding: 0; margin: 0 ">

                    <th class="p-2 text-start text-capitalize" style="font-size:12px;">nama</th>
                    <th class="p-2 text-start" style="font-size:12px;">:</th>
                    <td class="p-2 text-start" style='height: 20px; padding:10px; font-size:12px;'><?= $model['nama'] ?> </td>
                </tr>
                <tr style="border: 0.1px solid #dedede; padding: 0; margin: 0 ">

                    <th class="p-2 text-start text-capitalize" style="font-size:12px;">kode karyawan</th>
                    <th class="p-2 text-start" style="font-size:12px;">:</th>
                    <td class="p-2 text-start" style='height: 20px; padding:10px; font-size:12px;'><?= $model['kode_karyawan'] ?> </td>
                </tr>
                <tr style="border: 0.1px solid #dedede; padding: 0; margin: 0 ">

                    <th class="p-2 text-start text-capitalize" style="font-size:12px;">nomer identitas</th>
                    <th class="p-2 text-start" style="font-size:12px;">:</th>
                    <td class="p-2 text-start" style='height: 20px; padding:10px; font-size:12px;'><?= $model['nomer_identitas'] ?> </td>
                </tr>
                <tr style="border: 0.1px solid #dedede; padding: 0; margin: 0 ">

                    <th class="p-2 text-start text-capitalize" style="font-size:12px;">bagian</th>
                    <th class="p-2 text-start" style="font-size:12px;">:</th>
                    <td class="p-2 text-start" style='height: 20px; padding:10px; font-size:12px;'><?= $model['bagian'] ?> </td>
                </tr>
                <tr style="border: 0.1px solid #dedede; padding: 0; margin: 0 ">

                    <th class="p-2 text-start text-capitalize" style="font-size:12px;">jabatan</th>
                    <th class="p-2 text-start" style="font-size:12px;">:</th>
                    <td class="p-2 text-start" style='height: 20px; padding:10px; font-size:12px;'><?= $model['jabatan'] ?> </td>
                </tr>
                <tr style="border: 0.1px solid #dedede; padding: 0; margin: 0 ">

                    <th class="p-2 text-start text-capitalize" style="font-size:12px;">jam kerja</th>
                    <th class="p-2 text-start" style="font-size:12px;">:</th>
                    <td class="p-2 text-start" style='height: 20px; padding:10px; font-size:12px;'><?= $jamKerja['nama_jam_kerja'] ?> </td>
                </tr>
                <tr style="border: 0.1px solid #dedede; padding: 0; margin: 0 ">

                    <th class="p-2 text-start text-capitalize" style="font-size:12px;">status karyawan</th>
                    <th class="p-2 text-start" style="font-size:12px;">:</th>
                    <td class="p-2 text-start" style='height: 20px; padding:10px; font-size:12px;'><?= $model['status_karyawan'] ?> </td>
                </tr>
                <tr style="border: 0.1px solid #dedede; padding: 0; margin: 0 ">

                    <th class="p-2 text-start text-capitalize" style="font-size:12px;">jumlah hari kerja</th>
                    <th class="p-2 text-start" style="font-size:12px;">:</th>
                    <td class="p-2 text-start" style='height: 20px; padding:10px; font-size:12px;'><?= $model['jumlah_hari_kerja'] ?> Hari</td>
                </tr>
                <tr style="border: 0.1px solid #dedede; padding: 0; margin: 0 ">

                    <th class="p-2 text-start text-capitalize" style="font-size:12px;">jumlah hadir</th>
                    <th class="p-2 text-start" style="font-size:12px;">:</th>
                    <td class="p-2 text-start" style='height: 20px; padding:10px; font-size:12px;'><?= $model['jumlah_hadir'] ?> Hari</td>
                </tr>
                <tr style="border: 0.1px solid #dedede; padding: 0; margin: 0 ">

                    <th class="p-2 text-start text-capitalize" style="font-size:12px;">jumlah sakit</th>
                    <th class="p-2 text-start" style="font-size:12px;">:</th>
                    <td class="p-2 text-start" style='height: 20px; padding:10px; font-size:12px;'><?= $model['jumlah_sakit'] ?> Hari</td>
                </tr>
                <tr style="border: 0.1px solid #dedede; padding: 0; margin: 0 ">

                    <th class="p-2 text-start text-capitalize" style="font-size:12px;">jumlah cuti</th>
                    <th class="p-2 text-start" style="font-size:12px;">:</th>
                    <td class="p-2 text-start" style='height: 20px; padding:10px; font-size:12px;'><?= $model['jumlah_cuti'] ?> Hari</td>
                </tr>
                <tr style="border: 0.1px solid #dedede; padding: 0; margin: 0 ">

                    <th class="p-2 text-start text-capitalize" style="font-size:12px;">gaji pokok</th>
                    <th class="p-2 text-start" style="font-size:12px;">:</th>
                    <td class="p-2 text-start" style='height: 20px; padding:10px; font-size:12px;'><?= 'Rp ' . number_format($model['gaji_pokok'], 0, ',', '.'); ?> </td>
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

                    <th class="p-1 text-start text-capitalize" style="font-size: 12px;">jumlah jam lembur</th>
                    <th class="p-1 text-center" style="font-size: 12px;">:</th>
                    <td class="p-1 text-end" style='height: 20px; padding:10px; font-size:12px;'><?= $model['jumlah_jam_lembur'] ?> </td>
                </tr>
                <tr style="border: 0.1px solid #dedede; padding: 0; margin: 0 ">

                    <th class="p-1 text-start text-capitalize" style="font-size: 12px;">lembur perjam</th>
                    <th class="p-1 text-center" style="font-size: 12px;">:</th>
                    <td class="p-1 text-end" style='height: 20px; padding:10px; font-size:12px;'><?= 'Rp ' . number_format($model['lembur_perjam'], 0, ',', '.'); ?> </td>
                </tr>
                <tr style="border: 0.1px solid #dedede; padding: 0; margin: 0 ">

                    <th class="p-1 text-start text-capitalize" style="font-size: 12px;">total lembur</th>
                    <th class="p-1 text-center" style="font-size: 12px;">:</th>
                    <td class="p-1 text-end" style='height: 20px; padding:10px; font-size:12px;'><?= 'Rp ' . number_format($model['total_lembur'], 0, ',', '.'); ?> </td>
                </tr>
                <tr style="border: 0.1px solid #dedede; padding: 0; margin: 0 ">

                    <th class="p-1 text-start text-capitalize" style="font-size: 12px;">jumlah tunjangan</th>
                    <th class="p-1 text-center" style="font-size: 12px;">:</th>
                    <td class="p-1 text-end" style='height: 20px; padding:10px; font-size:12px;'><?= 'Rp ' . number_format($model['jumlah_tunjangan'], 0, ',', '.'); ?> </td>
                </tr>
            </table>
        </td>
        <td class="ps-2">
            <table class="w-100">
                <tr style="border: 0.1px solid #dedede; padding: 0; margin: 0 ">

                    <th style="font-size: 12px;" class="p-1 text-start text-capitalize">jumlah WFH</th>
                    <th class="p-1 text-center" style="font-size: 12px;">:</th>
                    <td class="p-1 text-end" style='height: 20px; padding:10px; font-size:12px;'><?= $model['jumlah_wfh'] ?> Hari</td>
                </tr>

                <tr style="border: 0.1px solid #dedede; padding: 0; margin: 0 ">

                    <th style="font-size: 12px;" class="p-1 text-start ">Potongan WFH hari</th>
                    <th class="p-1 text-center" style="font-size: 12px;">:</th>
                    <td class="p-1 text-end" style='height: 20px; padding:10px; font-size:12px;'><?= 'Rp ' . number_format($model['potongan_wfh_hari'], 0, ',', '.'); ?> </td>
                </tr>
                <tr style="border: 0.1px solid #dedede; padding: 0; margin: 0 ">

                    <th style="font-size: 12px;" class="p-1 text-start ">Jumlah Potongan WFH</th>
                    <th class="p-1 text-center" style="font-size: 12px;">:</th>
                    <td class="p-1 text-end" style='height: 20px; padding:10px; font-size:12px;'><?= 'Rp ' . number_format($model['jumlah_potongan_wfh'], 0, ',', '.'); ?> </td>
                </tr>
                <tr style="border: 0.1px solid #dedede; padding: 0; margin: 0 ">

                    <th style="font-size: 12px;" class="p-1 text-start text-capitalize">jumlah potongan</th>
                    <th class="p-1 text-center" style="font-size: 12px;">:</th>
                    <td class="p-1 text-end" style='height: 20px; padding:10px; font-size:12px;'><?= 'Rp ' . number_format($model['jumlah_potongan'], 0, ',', '.'); ?> </td>
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

                    <th class="p-2 text-start text-capitalize" style="font-size: 12px;">gaji diterima</th>
                    <th class="p-2 text-center" style="font-size: 12px;">:</th>
                    <td class="p-2 text-end" style='height: 20px; padding:10px; font-size:12px;'><?= 'Rp ' . number_format($model['gaji_diterima'], 0, ',', '.'); ?> </td>
                </tr>
                <tr style="background-color: #353535; border: 1px solid #dedede; padding: 0; margin: 0 ">

                    <th class="p-2 text-start text-capitalize;" style="font-size:12px; background-color: #353535; color: #fff">Terbilang</th>
                    <th class="p-2 text-center" style="font-size:12px; background-color: #353535; color: #fff">:</th>
                    <?php
                    $terbilang = Terbilang::toTerbilang($model['gaji_diterima']) . ' Rupiah';
                    ?>
                    <td class="p-2 text-end " style='background-color: #353535; color: #fff; height: 20px; padding:10px; font-size:12px;'><strong style="font-weight: bold;"><?= $terbilang ?></strong></td>
                </tr>
            </table>
        </td>
    </tr>
</table>