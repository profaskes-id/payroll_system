<?php

use backend\models\helpers\KaryawanHelper;
use backend\models\Karyawan;
use backend\models\MasterKode;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\PengajuanAbsensi $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="relative p-2 bg-white rounded-lg shadow-md md:p-6">
    <?php $form = ActiveForm::begin(); ?>

    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
        <!-- Karyawan Field -->
        <div class="">
            <p class="block mb-2 text-sm font-medium text-gray-900 capitalize">Karyawan</p>
            <?php
            $data = \yii\helpers\ArrayHelper::map(KaryawanHelper::getKaryawanData(), 'id_karyawan', 'nama');
            echo $form->field($model, 'id_karyawan')->dropDownList(
                $data,
                [
                    'disabled' => !$model->isNewRecord,
                    'prompt' => 'Pilih Karyawan ...',
                    'class' => 'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5'
                ]
            )->label(false);
            ?>
        </div>

        <!-- Tanggal Absen Field -->
        <div class="mb-4">
            <?= $form->field($model, 'tanggal_absen')->textInput([
                'type' => 'date',
                'class' => 'w-full bg-gray-50 border border-gray-300 rounded-lg p-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500'
            ]) ?>
        </div>

        <!-- Jam Masuk Field -->
        <div class="mb-4">
            <?= $form->field($model, 'jam_masuk')->textInput([
                'type' => 'time',
                'class' => 'w-full bg-gray-50 border border-gray-300 rounded-lg p-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500'
            ]) ?>
        </div>

        <!-- Jam Keluar Field -->
        <div class="mb-4">
            <?= $form->field($model, 'jam_keluar')->textInput([
                'type' => 'time',
                'class' => 'w-full bg-gray-50 border border-gray-300 rounded-lg p-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500'
            ]) ?>
        </div>

        <!-- Alasan Pengajuan Field -->
        <div class="mb-4 md:col-span-2">
            <?= $form->field($model, 'alasan_pengajuan')->textarea([
                'rows' => 3,
                'class' => 'w-full bg-gray-50 border border-gray-300 rounded-lg p-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500'
            ]) ?>
        </div>

        <!-- Status Field (only for update) -->
        <?php if (!$model->isNewRecord): ?>
            <div class="mb-4 md:col-span-2">
                <?php
                $data = \yii\helpers\ArrayHelper::map(
                    MasterKode::find()
                        ->where(['nama_group' => Yii::$app->params['status-pengajuan']])
                        ->andWhere(['!=', 'status', 0])
                        ->orderBy(['urutan' => SORT_ASC])
                        ->all(),
                    'kode',
                    'nama_kode'
                );

                echo $form->field($model, 'status')->radioList($data, [
                    'item' => function ($index, $label, $name, $checked, $value) use ($model) {
                        $isChecked = $value == 1 ? true : $checked;

                        return Html::radio($name, $isChecked, [
                            'value' => $value,
                            'label' => $label,
                            'labelOptions' => ['class' => 'radio-label mr-4'],
                        ]);
                    },
                ])->label('Status Pengajuan');
                ?>
            </div>

            <!-- Catatan Approver Field -->
            <div class="mb-4 md:col-span-2">
                <?= $form->field($model, 'catatan_approver')->textarea([
                    'rows' => 2,
                    'class' => 'w-full bg-gray-50 border border-gray-300 rounded-lg p-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500'
                ]) ?>
            </div>
        <?php endif; ?>

        <!-- Submit Button -->
        <div class="md:col-span-2">
            <button type="submit" class="px-10 py-2 font-bold text-white bg-blue-500 rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <?= $model->isNewRecord ? 'Submit' : 'Update' ?>
            </button>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>