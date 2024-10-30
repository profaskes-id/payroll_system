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
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
