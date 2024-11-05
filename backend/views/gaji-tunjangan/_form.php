<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\GajiTunjangan $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="gaji-tunjangan-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_tunjangan_detail')->textInput() ?>

    <?= $form->field($model, 'nama_tunjangan')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'jumlah')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'id_transaksi_gaji')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
