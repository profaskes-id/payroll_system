<?php

/** @var yii\web\View $this */
/** @var array $model */ // Assuming $model is passed to the view

use backend\models\Tanggal;
use yii\helpers\Html;

$tanggalFormater = new Tanggal();
?>

<div class="container p-6 mx-auto relative z-[50]">


    <div class="flex items-center justify-between">

        <h1 class="mb-4 text-2xl font-bold">Detail Pengajuan WFH</h1>
        <p class="">
            <?= Html::a('Back', ['/tanggapan/wfh'], ['class' => 'costume-btn']) ?>
        </p>
    </div>


    <div class="flex items-center justify-between my-5">
        <div class="flex justify-start space-x-4">

            <?= Html::a('Tanggapi', ['wfh-update', 'id' => $model['id_pengajuan_wfh']], ['class' => 'tw-add bg-blue-500 px-6 relative']) ?>
            <?= Html::a('Delete', ['wfh-delete', 'id_pengajuan_wfh' => $model['id_pengajuan_wfh']], [
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
                    <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">Alasan</td>
                    <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap"><?= Html::encode($model['alasan']) ?></td>
                </tr>
                <tr>
                    <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">Lokasi</td>
                    <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap"><?= Html::encode($model['lokasi']) ?></td>
                </tr>
                <tr>
                    <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">Alamat</td>
                    <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap"><?= Html::encode($model['alamat']) ?></td>
                </tr>
                <tr>
                    <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">Lokasi</td>
                    <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap">
                        <?php if (!empty($model['latitude']) && !empty($model['longitude'])): ?>
                            <a href="https://www.google.com/maps?q=<?= Html::encode($model['latitude']) ?>,<?= Html::encode($model['longitude']) ?>"
                                target="_blank"
                                class="inline-flex items-center px-3 py-1 text-sm font-medium text-blue-600 rounded-md bg-blue-50 hover:bg-blue-100 hover:text-blue-700"
                                title="Buka di Google Maps">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                Lihat Peta
                            </a>
                            <div class="mt-1 text-xs text-gray-500">
                                Lat: <?= Html::encode($model['latitude']) ?>, Long: <?= Html::encode($model['longitude']) ?>
                            </div>
                        <?php else: ?>
                            <span class="text-gray-400">-</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">Tanggal</td>
                    <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap">
                        <?php
                        $json_tanggal_array = $model['tanggal_array'];
                        $tanggal_array = json_decode($json_tanggal_array, true);
                        foreach ($tanggal_array as $tanggal) {
                            echo "<p class='text-base'>" . $tanggalFormater->getIndonesiaFormatTanggal($tanggal) . "</p>";
                        }
                        ?>
                    </td>
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
                    <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap"><?= Html::encode($model['disetujui_pada'] ?? 'Belum disetujui') ?></td>
                </tr>
                <tr>
                    <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">Disetujui Oleh</td>
                    <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap"><?= Html::encode($model['disetujui_oleh'] ?? 'Belum disetujui') ?></td>
                </tr>
                <tr>
                    <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">Catatan Admin</td>
                    <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap"><?= Html::encode($model['catatan_admin'] ?? 'Tidak ada catatan') ?></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>