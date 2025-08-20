<?php

/** @var yii\web\View $this */
/** @var array $model */ // Assuming $model is passed to the view

use backend\models\Tanggal;
use yii\helpers\Html;

$tanggalFormater = new Tanggal();
?>

<div class="container relative z-50 p-0 mx-auto md:p-6">


    <div class="flex items-center justify-between">

        <h1 class="mb-4 text-2xl font-bold">Detail Pengajuan Dinas</h1>
        <p class="">
            <?= Html::a('Back', ['/tanggapan/dinas'], ['class' => 'costume-btn']) ?>
        </p>
    </div>


    <div class="flex items-center justify-between my-5">
        <div class="flex justify-start space-x-4">

            <?= Html::a('Tanggapi', ['dinas-update', 'id' => $model['id_pengajuan_dinas']], ['class' => 'tw-add bg-blue-500 px-6 relative']) ?>
            <?= Html::a('Delete', ['dinas-delete', 'id_pengajuan_dinas' => $model['id_pengajuan_dinas']], [
                'class' => 'tw-add bg-rose-500 px-5 relative'
            ]) ?>
        </div>


    </div>




    <div class="overflow-hidden bg-white rounded-lg shadow-md">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Field</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Value</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">

                <tr>
                    <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">Karyawan</td>
                    <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap"><?= Html::encode($model['karyawan']['nama']) ?></td>
                </tr>
                <tr>
                    <td class="px-6 py-4 text-sm font-medium text-gray-900 capitalize whitespace-nowrap">keterangan perjalanan</td>
                    <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap"><?= Html::encode($model['keterangan_perjalanan']) ?></td>
                </tr>
                <tr>
                    <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">Estimasi Biaya</td>
                    <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap"><?= "Rp "  . number_format($model['estimasi_biaya'], 0, ',', '.') ?></td>
                </tr>
                <tr>
                    <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">Biaya Yang Disetujui</td>
                    <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap"><?= "Rp "  . number_format($model['biaya_yang_disetujui'] ?? 0, 0, ',', '.') ?></td>
                </tr>
                <tr>
                    <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">Tanggal Mulai</td>
                    <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap"><?= $tanggalFormater->getIndonesiaFormatTanggal($model['tanggal_mulai']);   ?></td>

                </tr>
                <tr>

                    <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">Tanggal Selesai</td>
                    <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap"><?= $tanggalFormater->getIndonesiaFormatTanggal($model['tanggal_selesai']);   ?></td>
                </tr>
                <tr>
                    <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">Status</td>
                    <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap">

                        <?php
                        if ($model['status'] == 0) {
                            echo '<span class="text-yellow-500">Menuggu Tanggapan</span>';
                        } elseif ($model['status'] == 1) {
                            echo '<span class="text-green-500">Pengajuan Telah Disetujui</span>';
                        } elseif ($model['status'] == 2) {
                            echo '<span class="text-danger">Pengajuan  Ditolak</span>';
                        } else {
                            echo "<span class='text-rose-500'>master kode tidak aktif</span>";
                        } ?>
                    </td>
                </tr>
                <tr>
                    <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">Disetujui Pada</td>
                    <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap"><?= Html::encode($model['disetujui_pada'] ?? 'Belum ditanggapi') ?></td>
                </tr>


                <tr>
                    <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">Disetujui Oleh</td>
                        <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap">
    <?= Html::encode($model['user']['profile']['full_name'] ?? $model['user']['username'] ?? 'Belum disetujui') ?>
</td>
                </tr>
                <tr>
                    <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap"><?= Html::encode($model['catatan_admin'] ?? 'Tidak ada catatan') ?></td>
                </tr>

            </tbody>
        </table>
    </div>
</div>