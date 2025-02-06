<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\PengaturanAplikasi $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="pengaturan-aplikasi-form table-container">

    <?php $form = ActiveForm::begin(); ?>
<div class="row">

    <div class="col-12 col-md-6">
        
        <?= $form->field($model, 'logo_sidebar')->fileInput(['class' => 'w-100 border form-control',  'maxlength' => true]) ?>
    </div>
    <div class="col-12 col-md-6">
        
        <?= $form->field($model, 'title_sidebar')->textInput(['class' => 'w-100 border form-control',  'maxlength' => true]) ?>
    </div>
    <div class="col-12 col-md-6">
        
        <?= $form->field($model, 'subtitle_sidebar')->textInput(['class' => 'w-100 border form-control',  'maxlength' => true]) ?>
    </div>
    <div class="col-12 col-md-6">
        
        <?= $form->field($model, 'logo_login')->fileInput(['class' => 'w-100 border form-control',  'maxlength' => true]) ?>
    </div>
    <div class="col-12 col-md-6">
        
        <?= $form->field($model, 'backround_login')->fileInput(['class' => 'w-100 border form-control',  'maxlength' => true]) ?>
    </div>
</div>
<div class="form-group">
        <button class="add-button" type="submit">
            <span>
                Save
            </span>
        </button>
    </div>
    <?php ActiveForm::end(); ?>

</div>
