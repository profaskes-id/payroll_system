<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\DataPekerjaan $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="data-pekerjaan-form tabel-container">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">

        <div class="col-md-6">
            <?= $form->field($model, 'id_karyawan')->textInput() ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'id_bagian')->textInput() ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'dari')->textInput() ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'sampai')->textInput() ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'status')->textInput(['maxlength' => true]) ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'jabatan')->textInput(['maxlength' => true]) ?>
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