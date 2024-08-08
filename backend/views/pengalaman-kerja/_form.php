<?php

use backend\models\MasterKode;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\PengalamanKerja $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="pengalaman-kerja-form table-container">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-6">
            <?php
            $data = \yii\helpers\ArrayHelper::map(\backend\models\Karyawan::find()->all(), 'id_karyawan', 'nama');
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


        <div class="col-md-6">
            <?= $form->field($model, 'perusahaan')->textInput(['maxlength' => true])->label('Nama Perusahaan') ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'posisi')->textInput(['maxlength' => true]) ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'masuk_pada')->textInput(["min" => "1900-01-01", "max" => "2100-12-31", "step" => "year", 'type' => 'date']) ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'keluar_pada')->textInput(["min" => "1900-01-01", "max" => "2100-12-31", "step" => "year", 'type' => 'date']) ?>
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