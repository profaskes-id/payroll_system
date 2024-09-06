<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\MasterCuti $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="master-cuti-form table-container">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row"></div>
    <?= $form->field($model, 'jenis_cuti')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'deskripsi_singkat')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'total_hari_pertahun')->textInput() ?>

    <?= $form->field($model, 'status')->dropDownList(
        [0 => 'Tidak Aktif', 1 => 'Aktif',], // Daftar opsi
        ['prompt' => 'Pilih Status Aktif'] // Opsi tambahan, misalnya placeholder
    )->label('Status Aktif') ?>
    <div class="form-group">
        <button class="add-button" type="submit">
            <span>
                Submit
            </span>
        </button>
    </div>

    <?php ActiveForm::end(); ?>

</div>