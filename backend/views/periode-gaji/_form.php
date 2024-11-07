<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\PeriodeGaji $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="periode-gaji-form table-container">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'bulan')->dropDownList([
        '1' => 'Januari',
        '2' => 'Februari',
        '3' => 'Maret',
        '4' => 'April',
        '5' => 'Mei',
        '6' => 'Juni',
        '7' => 'Juli',
        '8' => 'Agustus',
        '9' => 'September',
        '10' => 'Oktober',
        '11' => 'November',
        '12' => 'Desember',
    ], ['prompt' => 'Pilih Bulan']) ?>
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