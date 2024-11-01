<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\TunjanganDetail $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="tunjangan-detail-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_tunjangan')->textInput() ?>

    <?= $form->field($model, 'id_karyawan')->textInput() ?>

    <?= $form->field($model, 'jumlah')->textInput(['maxlength' => true]) ?>


    <div class="form-group">
        <button class="add-button" type="submit">
            <span>
                Save
            </span>
        </button>
    </div>


    <?php ActiveForm::end(); ?>

</div>