<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\Potongan $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="potongan-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'nama_potongan')->textInput(['maxlength' => true]) ?>


    <div class="form-group">
        <button class="add-button" type="submit">
            <span>
                Save
            </span>
        </button>
    </div>


    <?php ActiveForm::end(); ?>

</div>