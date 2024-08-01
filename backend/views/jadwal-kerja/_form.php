<?php

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
            <?= $form->field($model, 'id_jam_kerja')->textInput() ?>
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
            <?= $form->field($model, 'jumlah_jam')->textInput() ?>
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