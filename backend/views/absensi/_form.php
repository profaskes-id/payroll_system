<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\Absensi $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="absensi-form table-container">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">


        <div class="col-md-6">
            <?= $form->field($model, 'id_karyawan')->textInput() ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'id_jam_kerja')->textInput() ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'tanggal')->textInput(['type' => 'date']) ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'hari')->textInput(['maxlength' => true]) ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'jam_masuk')->textInput(['type' => 'time']) ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'jam_pulang')->textInput(['type' => 'time']) ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'kode_status_hadir')->textInput(['maxlength' => true]) ?>
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