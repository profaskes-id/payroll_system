<?php
/** @var yii\web\View $this */
/** @var array $model */

use backend\models\Tanggal;
use yii\helpers\Html;

$tanggalFormater = new Tanggal();
?>

<div class="container relative z-50 p-0 mx-auto md:p-6">
    <div class="flex items-center justify-between">
        <h1 class="mb-4 text-2xl font-bold">Detail Pengajuan Absensi</h1>
        <p class="">
            <?= Html::a('Back', ['/tanggapan/absensi'], ['class' => 'costume-btn']) ?>
        </p>
    </div>

    <div class="flex items-center justify-between my-5">
        <div class="flex justify-start space-x-4">
            <?= Html::a('Tanggapi', ['absensi-update', 'id' => $model['id']], ['class' => 'tw-add bg-blue-500 px-6 relative']) ?>
            <?= Html::a('Delete', ['absensi-delete', 'id' => $model['id']], [
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
                    <td class="px-6 py-4 text-sm font-medium text-gray-900 capitalize whitespace-nowrap">Tanggal Absen</td>
                    <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap"><?= ($model['tanggal_absen']) ?></td>
                </tr>
                <tr>
                    <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">Jam Masuk</td>
                    <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap"><?= Html::encode($model['jam_masuk'] ?? '-') ?></td>
                </tr>
                <tr>
                    <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">Jam Keluar</td>
                    <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap"><?= Html::encode($model['jam_keluar'] ?? '-') ?></td>
                </tr>
                <tr>
                    <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">Alasan Pengajuan</td>
                    <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap"><?= Html::encode($model['alasan_pengajuan'] ?? '-') ?></td>
                </tr>
                <tr>
                    <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">Status</td>
                    <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap">
                        <?php
                        if ($model['status'] == 0) {
                            echo '<span class="text-yellow-500">Pending</span>';
                        } elseif ($model['status'] == 1) {
                            echo '<span class="text-green-500">Disetujui</span>';
                        } elseif ($model['status'] == 2) {
                            echo '<span class="text-danger">Ditolak</span>';
                        } else {
                            echo '<span class="text-rose-500">Status tidak valid</span>';
                        } ?>
                    </td>
                </tr>
                <tr>
                    <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">Tanggal Pengajuan</td>
                    <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap"><?= ($model['tanggal_pengajuan']) ?></td>
                </tr>
                <tr>
                    <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">Approver</td>
<td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap">
    <?= Html::encode(
        $model['approver']['profile']['full_name'] ?? 
        $model['approver']['username'] ?? 
        'Belum disetujui'
    ) ?>
</td>     
                </tr>
                <tr>
                    <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">Tanggal Disetujui</td>
                    <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap">
                        <?=  $model['tanggal_disetujui'] ?? 'Belum ditanggapi' ?>
                    </td>
                </tr>
                <tr>
                    <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">Catatan Approver</td>
                    <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap"><?= Html::encode($model['catatan_approver'] ?? 'Tidak ada catatan') ?></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>