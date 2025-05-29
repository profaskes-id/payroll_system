<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\ShiftKerja $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="shift-kerja-form table-container">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class=" col-12">
            <?= $form->field($model, 'nama_shift')->textInput() ?>
        </div>

        <div class="col-md-6 col-12">
            <?= $form->field($model, 'jam_masuk')->textInput(['type' => 'time']) ?>
        </div>


        <div class="col-md-6 col-12">
            <?= $form->field($model, 'jam_keluar')->textInput(['type' => 'time']) ?>
        </div>


        <div class="col-md-6 col-12">
            <?= $form->field($model, 'mulai_istirahat')->textInput(['type' => 'time']) ?>
        </div>


        <div class="col-md-6 col-12">
            <?= $form->field($model, 'berakhir_istirahat')->textInput(['type' => 'time']) ?>
        </div>


        <div class="col-12">
            <?= $form->field($model, 'jumlah_jam')->textInput(['maxlength' => true, 'type' => 'number']) ?>
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