<?php

use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\JamKerjaKaryawan $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="jam-kerja-karyawan-form table-container">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-6">
            <?php
            $data = \yii\helpers\ArrayHelper::map(\backend\models\JamKerja::find()->all(), 'id_jam_kerja', 'nama_jam_kerja');
            echo $form->field($model, 'id_jam_kerja')->widget(Select2::classname(), [
                'data' => $data,
                'language' => 'id',
                'options' => ['placeholder' => 'Pilih Jam Kerja ...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);
            ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'jenis_shift')->textInput() ?>
        </div>
    </div>


    <div class="form-group">
        <button class="add-button" type="submit">
            <span>
                Submit
            </span>
        </button>
    </div>

    <?php ActiveForm::end(); ?>

</div>