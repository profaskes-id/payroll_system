<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\PotonganDetail $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="potongan-detail-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_potongan')->textInput() ?>

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