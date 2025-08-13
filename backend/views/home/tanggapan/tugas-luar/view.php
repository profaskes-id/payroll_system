<?php

/** @var yii\web\View $this */
/** @var array $model */

use backend\models\Tanggal;
use yii\helpers\Html;

$tanggalFormater = new Tanggal();
?>

<div class="container relative z-50 p-6 mx-auto">
    <div class="flex items-center justify-between">
        <h1 class="mb-4 text-2xl font-bold">Detail Pengajuan Tugas Luar</h1>
        <p class="">
            <?= Html::a('Back', ['/tanggapan/tugas-luar'], ['class' => 'costume-btn']) ?>
        </p>
    </div>

    <div class="flex items-center justify-between my-5">
        <div class="flex justify-start space-x-4">
            <?= Html::a('Tanggapi', ['tugas-luar-update', 'id_tugas_luar' => $model['id_tugas_luar']], ['class' => 'tw-add bg-blue-500 px-6 relative']) ?>
            <?= Html::a('Delete', ['tugas-luar-delete', 'id_tugas_luar' => $model['id_tugas_luar']], [
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
                    <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap"><?= Html::encode($model['karyawan']['nama'] ?? '-') ?></td>
                </tr>
                <tr>
                    <td class="px-6 py-4 text-sm font-medium text-gray-900 capitalize whitespace-nowrap">Tanggal Tugas</td>
                    <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap"><?= $model['tanggal'] ?></td>
                </tr>
                <tr>
                    <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">Status Pengajuan</td>
                    <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap">
                        <?php
                        if ($model['status_pengajuan'] == 0) {
                            echo '<span class="text-yellow-500">Pending</span>';
                        } elseif ($model['status_pengajuan'] == 1) {
                            echo '<span class="text-green-500">Disetujui</span>';
                        } elseif ($model['status_pengajuan'] == 2) {
                            echo '<span class="text-danger">Ditolak</span>';
                        } else {
                            echo '<span class="text-rose-500">Status tidak valid</span>';
                        } ?>
                    </td>
                </tr>


                <tr>
                    <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">Catatan Approver</td>
                    <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap"><?= Html::encode($model['catatan_approver'] ?? 'Tidak ada catatan') ?></td>
                </tr>
                <tr>
                    <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">Catatan Approver</td>
                    <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap"><?= Html::encode($model['catatan_approver'] ?? 'Tidak ada catatan') ?></td>
                </tr>
                <tr>
                    <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">Dibuat Pada</td>
                    <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap">
                        <?= Html::encode(
                            $model['updated']['profile']['full_name'] ??
                                $model['updated']['username'] ??
                                'Belum disetujui'
                        ) ?>
                    </td>
                </tr>
                <tr>
                    <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">Diupdate Pada</td>
                    <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap"><?= $model['updated_at'] ?></td>
                </tr>
            </tbody>
        </table>
    </div>
    <h4 class="mt-4 text-lg font-medium text-gray-900">Detail Tugas Luar</h4>

    <div class="mt-4 overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">#</th>
                    <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Keterangan</th>
                    <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Jam Diajukan</th>
                    <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Jam Check In</th>
                    <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Status Check</th>
                    <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Bukti Foto</th>
                    <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Lokasi</th>
                    <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Status</th>
                    <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php foreach ($model->detailTugasLuars as $index => $detail): ?>
                    <tr>
                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap"><?= $index + 1 ?></td>
                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap"><?= Html::encode($detail->keterangan) ?></td>
                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap"><?= date('H:i', strtotime($detail->jam_diajukan)) ?></td>
                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                            <?= $detail->jam_check_in ? date('H:i', strtotime($detail->jam_check_in)) : '-' ?>
                        </td>
                        <td class="px-6 py-4 text-sm whitespace-nowrap">
                            <?php if ($detail->status_check === 1): ?>
                                <span class="text-green-600">Checked In</span>
                            <?php else: ?>
                                <span class="text-yellow-600">Belum Check In</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 text-sm text-center text-gray-500 whitespace-nowrap">
                            <?php if ($detail->bukti_foto): ?>
                                <?php $imageUrl = Yii::getAlias('@web/uploads/bukti_tugas_luar/') . $detail->bukti_foto; ?>
                                <a href="<?= $imageUrl ?>" target="_blank" title="Klik untuk melihat ukuran penuh">
                                    <img src="<?= $imageUrl ?>"
                                        class="inline-block object-cover w-20 border border-gray-200 rounded-md h-15"
                                        alt="Bukti Tugas Luar">
                                </a>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 text-sm text-center text-gray-500 whitespace-nowrap">
                            <?php if ($detail->latitude && $detail->longitude): ?>
                                <?php $mapUrl = "https://www.google.com/maps?q={$detail->latitude},{$detail->longitude}"; ?>
                                <a href="<?= $mapUrl ?>"
                                    target="_blank"
                                    title="Buka di Google Maps"
                                    class="inline-flex items-center px-3 py-1 text-sm font-medium text-blue-700 bg-white border border-blue-300 rounded-md shadow-sm hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <svg class="w-4 h-4 mr-1 -ml-1 text-blue-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                    </svg>
                                    Lihat Peta
                                </a>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 text-sm whitespace-nowrap">
                            <?php if ($detail->status_pengajuan_detail !== null): ?>
                                <?php if ($detail->status_pengajuan_detail == 0): ?>
                                    <span class="text-yellow-600">Pending</span>
                                <?php elseif ($detail->status_pengajuan_detail == 1): ?>
                                    <span class="text-green-600">Disetujui</span>
                                <?php elseif ($detail->status_pengajuan_detail == 2): ?>
                                    <span class="text-red-600">Ditolak</span>
                                <?php endif; ?>
                            <?php else: ?>
                                <span class="text-red-600">master kode tidak aktif</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 text-sm text-center text-gray-500 whitespace-nowrap">
                            <a href="<?= Yii::$app->urlManager->createUrl(['/tanggapan/tugas-luar-delete-detail', 'id' => $detail->id_detail]) ?>"
                                class="inline-flex items-center px-2 py-1 text-xs font-medium text-white bg-red-600 border border-transparent rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                                data-confirm="Apakah Anda yakin ingin menghapus detail ini?"
                                data-method="post"
                                title="Hapus">
                                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</div>