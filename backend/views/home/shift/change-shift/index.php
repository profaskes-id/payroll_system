<?php

use yii\helpers\Html;
use yii\helpers\Url;
?>


<div class="relative z-50 max-w-md p-6 mx-auto mt-10 bg-white rounded-lg shadow-md">
    <h2 class="mb-4 text-xl font-semibold text-indigo-600">Ubah Shift Kerja</h2>

    <?php if (!empty($currentShift)): ?>
        <div class="p-4 mb-4 bg-gray-100 rounded">
            <p><strong>Shift Saat Ini:</strong> <?= Html::encode($currentShift['nama_shift']) ?></p>
            <p><strong>Jam Masuk:</strong> <?= $currentShift['jam_masuk'] ?></p>
            <p><strong>Jam Keluar:</strong> <?= $currentShift['jam_keluar'] ?></p>
        </div>
    <?php endif; ?>

    <?php if (Yii::$app->session->hasFlash('success')): ?>
        <div class="mb-4 text-green-600">
            <?= Yii::$app->session->getFlash('success') ?>
        </div>
    <?php endif; ?>

    <?php if (Yii::$app->session->hasFlash('error')): ?>
        <div class="mb-4 text-red-600">
            <?= Yii::$app->session->getFlash('error') ?>
        </div>
    <?php endif; ?>



    <form method="post" action="<?= Url::to(['change-shift', 'id_karyawan' => $id_karyawan]) ?>" class="space-y-4">
        <div>
            <label for="shift" class="block mb-1 text-gray-700">Pilih Shift Baru</label>
            <select name="shift_kerja" id="shift" class="w-full p-2 border rounded select2">
                <?php foreach ($allDataShift as $shift): ?>
                    <option value="<?= Html::encode($shift['id_shift_kerja']) ?>"
                        <?= isset($currentShift['id_shift_kerja']) && $shift['id_shift_kerja'] == $currentShift['id_shift_kerja'] ? 'selected' : '' ?>>
                        <?= Html::encode($shift['nama_shift']) ?> (<?= Html::encode($shift['jam_masuk']) ?> - <?= Html::encode($shift['jam_keluar']) ?>)
                    </option>
                <?php endforeach; ?>
            </select>

        </div>


        <div class="col-12 ">
            <label class="block mb-1 " for="tanggal-awal">Tanggal Awal</label>
            <input type="date" name="tanggal_awal" id="tanggal-awal" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" value="<?= isset($model->tanggal_awal) ? htmlspecialchars($model->tanggal_awal) : '' ?>">
        </div>
        <div class="col-12">
            <label class="block mb-1 " for="tanggal-akhir">Tanggal Akhir</label>
            <input type="date" name="tanggal_akhir" id="tanggal-akhir" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" value="<?= isset($model->tanggal_akhir) ? htmlspecialchars($model->tanggal_akhir) : '' ?>">
        </div>


        <button type="submit" class="w-full px-4 py-2 text-white bg-indigo-600 rounded hover:bg-indigo-700">
            Simpan Perubahan
        </button>
    </form>
</div>