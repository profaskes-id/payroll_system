<?php

use backend\models\helpers\KaryawanHelper;
use backend\models\Karyawan;
use backend\models\MasterKode;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\PengajuanDinas $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="relative p-2 bg-white rounded-lg shadow-md md:p-6">

    <?php $form = ActiveForm::begin(); ?>

    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

        <div class="">
            <p class="block mb-2 text-sm font-medium text-gray-900 capitalize ">Karyawan</p>
            <?php
            $data = \yii\helpers\ArrayHelper::map(KaryawanHelper::getKaryawanData(), 'id_karyawan', 'nama');
            echo $form->field($model, 'id_karyawan')->dropDownList(
                $data,
                [
                    'disabled' => true,
                    'prompt' => 'Pilih Karyawan ...',
                    'class' => 'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 '
                ]
            )->label(false);
            ?>
        </div>

        <!-- Tanggal Mulai Field -->
        <div class="mb-4">
            <?= $form->field($model, 'tanggal_mulai')->textInput([
                'type' => 'date',
                'class' => 'w-full bg-gray-50 border border-gray-300 rounded-lg p-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500'
            ]) ?>
        </div>

        <!-- Tanggal Selesai Field -->
        <div class="mb-4">
            <?= $form->field($model, 'tanggal_selesai')->textInput([
                'type' => 'date',
                'class' => 'w-full bg-gray-50 border border-gray-300 rounded-lg p-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500'
            ]) ?>
        </div>

        <!-- Biaya yang Disetujui Field (hanya jika bukan record baru) -->
        <?php if (!$model->isNewRecord) : ?>
            <div class="mb-4">
                <?= $form->field($model, 'biaya_yang_disetujui')->textInput([
                    'maxlength' => true,
                    "type" => "number",
                    "step" => "0.01",
                    "min" => "0",
                    "placeholder" => "0.00",
                    'value' => $model->estimasi_biaya,
                    'class' => 'w-full bg-gray-50 border border-gray-300 rounded-lg p-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500'
                ]) ?>
            </div>

        <?php endif; ?>
        <div class="mb-4">

            <!-- Estimasi Biaya Field -->
            <?= $form->field($model, 'estimasi_biaya')->textInput([
                'maxlength' => true,
                "type" => "number",
                "step" => "0.01",
                "min" => "0",
                "placeholder" => "0.00",
                'class' => 'w-full bg-gray-50 border border-gray-300 rounded-lg p-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500'
            ]) ?>
        </div>

        <!-- Keterangan Perjalanan Field -->
        <div class="mb-4 ">
            <?= $form->field($model, 'keterangan_perjalanan')->textarea([
                'rows' => 1,
                'class' => 'w-full bg-gray-50 border border-gray-300 rounded-lg p-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500'
            ]) ?>
        </div>

        <div class="mb-4 col-12">

            <?php
            $data = \yii\helpers\ArrayHelper::map(MasterKode::find()->where(['nama_group' => Yii::$app->params['status-pengajuan']])->andWhere(['!=', 'status', 0])->orderBy(['urutan' => SORT_ASC])->all(), 'kode', 'nama_kode');


            echo $form->field($model, 'status')->radioList($data, [
                'item' => function ($index, $label, $name, $checked, $value) use ($model) {
                    // Tentukan apakah radio button untuk value 1 harus checked

                    if ($model->isNewRecord) {
                        $isChecked = $value == 1 ? true : $checked;
                    } else {
                        $isChecked = $checked;
                    }

                    return Html::radio($name, $isChecked, [
                        'value' => $value,
                        'label' => $label,
                        'labelOptions' => ['class' => 'radio-label mr-4'],
                    ]);
                },
            ])->label('Status Pengajuan');
            ?>
        </div>


        <!-- Catatan Admin Field (hanya jika bukan record baru) -->
        <?php if (!$model->isNewRecord): ?>
            <div class="mb-4">
                <?= $form->field($model, 'catatan_admin')->textarea([
                    'rows' => 2,
                    'class' => 'w-full bg-gray-50 border border-gray-300 rounded-lg p-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500'
                ]) ?>
            </div>
        <?php endif; ?>

        <!-- Submit Button -->
        <div class="">
            <button type="submit" class="px-10 py-2 font-bold text-white bg-blue-500 rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                Submit
            </button>
        </div>

    </div>

    <?php ActiveForm::end(); ?>

</div>