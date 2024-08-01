<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\JamKerja $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="jam-kerja-form table-container">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'nama_jam_kerja')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <button class="add-button" type="submit">
            <span>
                Submit
            </span>
        </button>
    </div>

    <?php ActiveForm::end(); ?>

</div>