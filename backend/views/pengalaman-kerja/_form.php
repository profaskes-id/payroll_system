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
        <?php $id_karyawan = Yii::$app->request->get('id_karyawan'); ?>

        <?= $form->field($model, 'id_karyawan')->hiddenInput(['value' => $id_karyawan ?? $model->id_karyawan])->label(false) ?>



        <div class="col-12">
            <?= $form->field($model, 'perusahaan')->textInput(['maxlength' => true])->label('Nama Perusahaan') ?>
        </div>
        <div class="col-12">
            <?= $form->field($model, 'posisi')->textInput(['maxlength' => true]) ?>
        </div>

        <div class="col-12">
            <?= $form->field($model, 'masuk_pada')->textInput(["min" => "1900-01-01", "max" => "2100-12-31", "step" => "year", 'type' => 'date']) ?>
        </div>

        <div class="col-12">
            <?= $form->field($model, 'keluar_pada')->textInput(["min" => "1900-01-01", "max" => "2100-12-31", "step" => "year", 'type' => 'date']) ?>
        </div>
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