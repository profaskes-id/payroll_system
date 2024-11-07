<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\GajiPotongan $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="gaji-potongan-form table-container">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'nama_potongan')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'jumlah')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'id_transaksi_gaji')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>