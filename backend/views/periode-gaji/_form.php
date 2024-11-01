<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\PeriodeGaji $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="periode-gaji-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'bulan')->textInput(['type' => 'number']) ?>

    <?= $form->field($model, 'tahun')->textInput(['type' => 'number']) ?>

    <?= $form->field($model, 'tanggal_awal')->textInput(['type' => 'date']) ?>

    <?= $form->field($model, 'tanggal_akhir')->textInput(['type' => 'date']) ?>

    <?= $form->field($model, 'terima')->textInput(['type' => 'date']) ?>


    <div class="form-group">
        <button class="add-button" type="submit">
            <span>
                Save
            </span>
        </button>
    </div>


    <?php ActiveForm::end(); ?>

</div>