<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\Tunjangan $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="tunjangan-form table-container">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'nama_tunjangan')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <button class="add-button" type="submit">
            <span>
                Save
            </span>
        </button>
    </div>



    <?php ActiveForm::end(); ?>

</div>