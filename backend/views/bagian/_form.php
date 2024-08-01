<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\Bagian $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="bagian-form table-container">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'nama_bagian')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <button class="add-button" type="submit">
            <span>
                Submit
            </span>
        </button>
    </div>
    <?php ActiveForm::end(); ?>
</div>