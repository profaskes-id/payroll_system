<?php

use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\JadwalKerja $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="jadwal-kerja-form table-container">

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
            <?= $form->field($model, 'nama_hari')->textInput(['maxlength' => true]) ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'jam_masuk')->textInput(['type' => 'time']) ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'jam_keluar')->textInput(['type' => 'time']) ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'lama_istirahat')->textInput() ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'jumlah_jam')->textInput(['type' => 'number']) ?>
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