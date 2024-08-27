<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\HariLibur $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="hari-libur-form table-container">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'tanggal')->textInput(['type' => 'date']) ?>

        </div>
        <div class="col-md-6">

            <?= $form->field($model, 'nama_hari_libur')->textInput(['maxlength' => true]) ?>
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