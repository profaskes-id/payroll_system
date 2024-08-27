<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\Pengumuman $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="pengumuman-form table-container">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-12">
            <?= $form->field($model, 'judul')->textInput(['maxlength' => true]) ?>
        </div>


        <div class="col-12">
            <?= $form->field($model, 'deskripsi')->textarea(['rows' => 6]) ?>
        </div>

        <div class="form-group">
            <button class="add-button" type="submit">
                <span>
                    Submit
                </span>
            </button>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>