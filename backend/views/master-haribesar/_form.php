<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\MasterHaribesar $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="master-haribesar-form table-container">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-6">
            <?= $form->field($model, 'tanggal')->textInput(['type' => 'date']) ?>
        </div>
        <div class="col-6">
            <?= $form->field($model, 'nama_hari')->textarea(['rows' => 1]) ?>
        </div>
        <div class="col-6">
            <?= $form->field($model, 'libur_nasional')->dropDownList(
                [0 => 'Tidak', 1 => 'Iya'],
                ['prompt' => 'Pilih...']
            ) ?>
        </div>
        <div class="col-6">
            <?= $form->field($model, 'pesan_default')->textarea(['rows' => 1]) ?>
        </div>
        <div class="col-6">
            <?= $form->field($model, 'lampiran')->textarea(['rows' => 1]) ?>
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