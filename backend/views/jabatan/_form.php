<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\MasterKode $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="master-kode-form table-container">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'nama_group')->hiddenInput(['value' => $nama_group ?? $model->nama_group, 'maxlength' => true])->label(false) ?>

    <?= $form->field($model, 'kode')->textInput() ?>

    <?= $form->field($model, 'nama_kode')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->hiddenInput(['value' => '1'])->label(false) ?>

    <?= $form->field($model, 'urutan')->textInput(['maxlength' => true, 'type' => 'number']) ?>

    <div class="form-group">
        <button class="add-button" type="submit">
            <span>
                Save
            </span>
        </button>
    </div>
    <?php ActiveForm::end(); ?>

</div>