<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\PengalamanKerja $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="pengalaman-kerja-form tabel-container">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'id_karyawan')->textInput() ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'perusahaan')->textInput(['maxlength' => true]) ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'posisi')->textInput(['maxlength' => true]) ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'masuk_pada')->textInput() ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'keluar_pada')->textInput() ?>
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