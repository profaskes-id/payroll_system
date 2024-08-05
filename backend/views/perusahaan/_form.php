<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\Perusahaan $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="perusahaan-form table-container">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'nama_perusahaan')->textInput(['maxlength' => true]) ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'status_perusahaan')->textInput() ?>
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