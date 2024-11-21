<?php

use backend\models\Terbilang;
?>



<h1 class="text-center fw-bold" style="font-weight: bold; font-size: 20px">PT Profaskes Softech Indonesia</h1>
<p class="text-center fw-bold" style="font-size: 12px;">Ruko Plaza Menteng, Blok B No.17, Jalan Mh.Thamrin, Cibatu, Cikarang Selatan, Kab.Bekasi, Jawa Barat 17530.</p>
<hr style="margin: -10px 0;">

<p class="text-center " style="font-weight: bold; padding:0; margin-top:-20px;">Slip Gaji Karyawan</p>
<p class="text-center fw-bold" style="padding:0; margin-bottom:5px; font-size: 12px;">Periode : 04 Oktober 2024 s/d 05 November 2024 </p>




<table class="w-100 border-0 table">
    <tr style="border: 0px solid #dedede; padding: 0; margin: 0 ">
        <td colspan="1">
            <table class=" w-100 ">
                <tr style="border: 0.1px solid #dedede; padding: 0; margin: 0 ">

                    <th class="text-start text-capitalize" style="font-size:12px; margin:0; padding:0px;">nama</th>
                    <th class="text-start" style="font-size:12px; margin:0; padding:0px;">:</th>
                    <td class="text-start" style='height: 20px; padding:10px; font-size:12px; margin:0; padding:0px;'><?= $model['nama'] ?> </td>
                </tr>
                <tr style="border: 0.1px solid #dedede; padding: 0; margin: 0 ">

                    <th class="text-start text-capitalize" style="font-size:12px; margin:0; padding:0px;">kode karyawan</th>
                    <th class="text-start" style="font-size:12px; margin:0; padding:0px;">:</th>
                    <td class="text-start" style='height: 20px; padding:10px; font-size:12px; margin:0; padding:0px;'><?= $model['kode_karyawan'] ?> </td>
                </tr>
                <tr style="border: 0.1px solid #dedede; padding: 0; margin: 0 ">

                    <th class="text-start text-capitalize" style="font-size:12px; margin:0; padding:0px;">nomer identitas</th>
                    <th class="text-start" style="font-size:12px; margin:0; padding:0px;">:</th>
                    <td class="text-start" style='height: 20px; padding:10px; font-size:12px; margin:0; padding:0px;'><?= $model['nomer_identitas'] ?> </td>
                </tr>
                <tr style="border: 0.1px solid #dedede; padding: 0; margin: 0 ">

                    <th class="text-start text-capitalize" style="font-size:12px; margin:0; padding:0px;">bagian</th>
                    <th class="text-start" style="font-size:12px; margin:0; padding:0px;">:</th>
                    <td class="text-start" style='height: 20px; padding:10px; font-size:12px; margin:0; padding:0px;'><?= $model['bagian'] ?> </td>
                </tr>
                <tr style="border: 0.1px solid #dedede; padding: 0; margin: 0 ">

                    <th class="text-start text-capitalize" style="font-size:12px; margin:0; padding:0px;">jabatan</th>
                    <th class="text-start" style="font-size:12px; margin:0; padding:0px;">:</th>
                    <td class="text-start" style='height: 20px; padding:10px; font-size:12px; margin:0; padding:0px;'><?= $model['jabatan'] ?> </td>
                </tr>
                <tr style="border: 0.1px solid #dedede; padding: 0; margin: 0 ">

                    <th class="text-start text-capitalize" style="font-size:12px; margin:0; padding:0px;">jam kerja</th>
                    <th class="text-start" style="font-size:12px; margin:0; padding:0px;">:</th>
                    <td class="text-start" style='height: 20px; padding:10px; font-size:12px; margin:0; padding:0px;'><?= $jamKerja['nama_jam_kerja'] ?> </td>
                </tr>

            </table>

        </td>
        <td colspan="1">
            <table class=" w-100 ">
                <tr style="border: 0.1px solid #dedede; padding: 0; margin: 0 ">

                    <th class="text-start text-capitalize" style="font-size:12px; margin:0; padding:0px;">status karyawan</th>
                    <th class="text-start" style="font-size:12px; margin:0; padding:0px;">:</th>
                    <td class="text-start" style='height: 20px; padding:10px; font-size:12px; margin:0; padding:0px;'><?= $model['status_karyawan'] ?> </td>
                </tr>
                <tr style="border: 0.1px solid #dedede; padding: 0; margin: 0 ">

                    <th class="text-start text-capitalize" style="font-size:12px; margin:0; padding:0px;">jumlah hari kerja</th>
                    <th class="text-start" style="font-size:12px; margin:0; padding:0px;">:</th>
                    <td class="text-start" style='height: 20px; padding:10px; font-size:12px; margin:0; padding:0px;'><?= $model['jumlah_hari_kerja'] ?> Hari</td>
                </tr>
                <tr style="border: 0.1px solid #dedede; padding: 0; margin: 0 ">

                    <th class="text-start text-capitalize" style="font-size:12px; margin:0; padding:0px;">jumlah hadir</th>
                    <th class="text-start" style="font-size:12px; margin:0; padding:0px;">:</th>
                    <td class="text-start" style='height: 20px; padding:10px; font-size:12px; margin:0; padding:0px;'><?= $model['jumlah_hadir'] ?> Hari</td>
                </tr>
                <tr style="border: 0.1px solid #dedede; padding: 0; margin: 0 ">

                    <th class="text-start text-capitalize" style="font-size:12px; margin:0; padding:0px;">jumlah sakit</th>
                    <th class="text-start" style="font-size:12px; margin:0; padding:0px;">:</th>
                    <td class="text-start" style='height: 20px; padding:10px; font-size:12px; margin:0; padding:0px;'><?= $model['jumlah_sakit'] ?> Hari</td>
                </tr>
                <tr style="border: 0.1px solid #dedede; padding: 0; margin: 0 ">

                    <th class="text-start text-capitalize" style="font-size:12px; margin:0; padding:0px;">jumlah cuti</th>
                    <th class="text-start" style="font-size:12px; margin:0; padding:0px;">:</th>
                    <td class="text-start" style='height: 20px; padding:10px; font-size:12px; margin:0; padding:0px;'><?= $model['jumlah_cuti'] ?> Hari</td>
                </tr>
                <tr style="border: 0.1px solid #dedede; padding: 0; margin: 0 ">

                    <th class="text-start text-capitalize" style="font-size:12px; margin:0; padding:0px;">gaji pokok</th>
                    <th class="text-start" style="font-size:12px; margin:0; padding:0px;">:</th>
                    <td class="text-start" style='height: 20px; padding:10px; font-size:12px; margin:0; padding:0px;'><?= 'Rp ' . number_format($model['gaji_pokok'], 0, ',', '.'); ?> </td>
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
                    <td class="p-1 text-end" style='height: 20px; padding:10px; font-size:12px; margin:0; padding:0px;'><?= $model['jumlah_jam_lembur'] ?> </td>
                </tr>
                <tr style="border: 0.1px solid #dedede; padding: 0; margin: 0 ">

                    <th class="p-1 text-start text-capitalize" style="font-size: 12px;">lembur perjam</th>
                    <th class="p-1 text-center" style="font-size: 12px;">:</th>
                    <td class="p-1 text-end" style='height: 20px; padding:10px; font-size:12px; margin:0; padding:0px;'><?= 'Rp ' . number_format($model['lembur_perjam'], 0, ',', '.'); ?> </td>
                </tr>
                <tr style="border: 0.1px solid #dedede; padding: 0; margin: 0 ">

                    <th class="p-1 text-start text-capitalize" style="font-size: 12px;">total lembur</th>
                    <th class="p-1 text-center" style="font-size: 12px;">:</th>
                    <td class="p-1 text-end" style='height: 20px; padding:10px; font-size:12px; margin:0; padding:0px;'><?= 'Rp ' . number_format($model['total_lembur'], 0, ',', '.'); ?> </td>
                </tr>
                <tr style="border: 0.1px solid #dedede; padding: 0; margin: 0 ">

                    <th class="p-1 text-start text-capitalize" style="font-size: 12px;">jumlah tunjangan</th>
                    <th class="p-1 text-center" style="font-size: 12px;">:</th>
                    <td class="p-1 text-end" style='height: 20px; padding:10px; font-size:12px; margin:0; padding:0px;'><?= 'Rp ' . number_format($model['jumlah_tunjangan'], 0, ',', '.'); ?> </td>
                </tr>
                <tr style="border: 0.1px solid #dedede; padding: 0; margin: 0 ">

                    <th class="p-1 text-start text-capitalize" style="font-size: 10px;"><?= $model['keterangan_tunjangan_lainnya'] ?></th>
                    <th class="p-1 text-center" style="font-size: 12px;">:</th>
                    <td class="p-1 text-end" style='height: 20px; padding:10px; font-size:12px; margin:0; padding:0px;'><?= 'Rp ' . number_format($model['tunjangan_lainnya'], 0, ',', '.'); ?> </td>
                </tr>
            </table>
        </td>
        <td class="ps-2">
            <table class="w-100">
                <tr style="border: 0.1px solid #dedede; padding: 0; margin: 0 ">

                    <th style="font-size: 12px;" class="p-1 text-start text-capitalize">jumlah WFH</th>
                    <th class="p-1 text-center" style="font-size: 12px;">:</th>
                    <td class="p-1 text-end" style='height: 20px; padding:10px; font-size:12px; margin:0; padding:0px;'><?= $model['jumlah_wfh'] ?> Hari</td>
                </tr>

                <tr style="border: 0.1px solid #dedede; padding: 0; margin: 0 ">

                    <th style="font-size: 12px;" class="p-1 text-start ">Potongan WFH hari</th>
                    <th class="p-1 text-center" style="font-size: 12px;">:</th>
                    <td class="p-1 text-end" style='height: 20px; padding:10px; font-size:12px; margin:0; padding:0px;'><?= 'Rp ' . number_format($model['potongan_wfh_hari'], 0, ',', '.'); ?> </td>
                </tr>
                <tr style="border: 0.1px solid #dedede; padding: 0; margin: 0 ">

                    <th style="font-size: 12px;" class="p-1 text-start ">Jumlah Potongan WFH</th>
                    <th class="p-1 text-center" style="font-size: 12px;">:</th>
                    <td class="p-1 text-end" style='height: 20px; padding:10px; font-size:12px; margin:0; padding:0px;'><?= 'Rp ' . number_format($model['jumlah_potongan_wfh'], 0, ',', '.'); ?> </td>
                </tr>
                <tr style="border: 0.1px solid #dedede; padding: 0; margin: 0 ">

                    <th style="font-size: 12px;" class="p-1 text-start text-capitalize">jumlah potongan</th>
                    <th class="p-1 text-center" style="font-size: 12px;">:</th>
                    <td class="p-1 text-end" style='height: 20px; padding:10px; font-size:12px; margin:0; padding:0px;'><?= 'Rp ' . number_format($model['jumlah_potongan'], 0, ',', '.'); ?> </td>
                </tr>
                <tr style="border: 0.1px solid #dedede; padding: 0; margin: 0 ">

                    <th class="p-1 text-start text-capitalize" style="font-size: 10px;"><?= $model['keterangan_potongan_lainnya'] ?></th>
                    <th class="p-1 text-center" style="font-size: 12px;">:</th>
                    <td class="p-1 text-end" style='height: 20px; padding:10px; font-size:12px; margin:0; padding:0px;'><?= 'Rp ' . number_format($model['potongan_lainnya'], 0, ',', '.'); ?> </td>
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

                    <th class="text-start text-capitalize" style="font-size: 12px;">gaji diterima</th>
                    <th class="text-center" style="font-size: 12px;">:</th>
                    <td class="text-end" style='height: 20px; padding:10px; font-size:12px; margin:0; padding:0px;'><?= 'Rp ' . number_format($model['gaji_diterima'], 0, ',', '.'); ?> </td>
                </tr>
                <tr style=" border: 1px solid #dedede; padding: 0; margin: 0 ">

                    <th class="text-start text-capitalize;" style="font-size:12px; margin:0; padding:0px; background-color: rgba(53, 53, 53, 0.08); color: #000">Terbilang</th>
                    <th class="text-center" style="font-size:12px; margin:0; padding:0px; background-color: #fff; color: #000">:</th>
                    <?php
                    $terbilang = Terbilang::toTerbilang($model['gaji_diterima']) . ' Rupiah';
                    ?>
                    <td class="text-end " style='background-color: rgba(53, 53, 53, 0.08); color: #000; height: 20px; padding:10px; font-size:12px; margin:0; padding:0px;'><strong style="font-weight: bold;"><?= $terbilang ?></strong></td>
                </tr>
            </table>
        </td>
    </tr>
</table>