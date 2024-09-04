<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\MasterCuti $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="master-cuti-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'jenis_cuti')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'deskripsi_singkat')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'total_hari_pertahun')->textInput() ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
