<?php

use backend\models\helpers\KaryawanHelper;
use backend\models\helpers\StatusPengajuanHelper;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\PengajuanTugasLuar $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="pengajuan-tugas-luar-form table-container">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">

        <div class="col-12">
            <?php
            $data = \yii\helpers\ArrayHelper::map(KaryawanHelper::getKaryawanData(), 'id_karyawan', 'nama');
            echo $form->field($model, 'id_karyawan')->widget(Select2::classname(), [
                'data' => $data,
                'language' => 'id',
                'options' => ['placeholder' => 'Pilih Karyawan ...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ])->label('Karyawan');
            ?>
        </div>

        <div class="col-12">
            <?php

            $data = \yii\helpers\ArrayHelper::map(StatusPengajuanHelper::getStatusPengajuan(), 'kode', 'nama_kode');
            echo $form->field($model, 'status_pengajuan')->radioList($data, [
                'item' => function ($index, $label, $name, $checked, $value) use ($model) {
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
            ])->label('Status');
            ?>
        </div>




        <div class="form-group">
            <button class="add-button" type="submit">
                <span>
                    Save
                </span>
            </button>
        </div>
        <?php ActiveForm::end(); ?>

    </div>