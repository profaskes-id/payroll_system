<?php

/** @var yii\web\View $this */
/** @var array $model */ // Assuming $model is passed to the view

use backend\models\Tanggal;
use yii\helpers\Html;

$tanggalFormater = new Tanggal();
?>

<div class="container relative z-50 p-6 mx-auto">


    <div class="flex items-center justify-between">

        <h1 class="mb-4 text-2xl font-bold">Detail Pengajuan lembur</h1>
        <p class="">
            <?= Html::a('Back', ['/tanggapan/lembur'], ['class' => 'costume-btn']) ?>
        </p>
    </div>


    <div class="flex items-center justify-between my-5">
        <div class="flex justify-start space-x-4">

            <?= Html::a('Tanggapi', ['lembur-update', 'id_pengajuan_lembur' => $model['id_pengajuan_lembur']], ['class' => 'tw-add bg-blue-500 px-6 relative']) ?>
            <?= Html::a('Delete', ['lembur-delete', 'id_pengajuan_lembur' => $model['id_pengajuan_lembur']], [
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
                    <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">pekerjaan</td>
                    <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap">
                        <?php
                        // Misalnya $item->pekerjaan berisi string JSON
                        $pekerjaan = json_decode($model['pekerjaan']);

                        // Cek apakah $pekerjaan berhasil di-decode menjadi array
                        if (is_array($pekerjaan)) {
                            echo '<ul>';  // Membuka tag <ul> untuk list poin
                            foreach ($pekerjaan as $pekerjaanItem) {
                                echo '<li>' . htmlspecialchars($pekerjaanItem) . '</li>';  // Menampilkan setiap item dalam <li>
                            }
                            echo '</ul>';  // Menutup tag <ul>
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">tanggal</td>
                    <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap">
                        <?php echo  $tanggalFormater->getIndonesiaFormatTanggal($model['tanggal']); ?>
                    </td>
                </tr>
                <tr>
                    <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">jam mulai</td>
                    <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap"><?= Html::encode($model['jam_mulai']) ?></td>
                </tr>
                <tr>
                    <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">jam selesai</td>
                    <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap"><?= Html::encode($model['jam_selesai']) ?></td>
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
<td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap">
    <?= Html::encode(
        $model['disetujuiOleh']['profile']['full_name'] ?? 
        $model['disetujuiOleh']['username'] ?? 
        'Belum disetujui'
    ) ?>
</td>                </tr>
                <tr>
                    <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">Catatan Admin</td>
                    <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap"><?= Html::encode($model['catatan_admin'] ?? 'Tidak ada catatan') ?></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>