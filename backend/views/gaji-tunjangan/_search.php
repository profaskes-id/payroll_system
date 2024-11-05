<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\GajiTunjanganSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="gaji-tunjangan-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_gaji_tunjangan') ?>

    <?= $form->field($model, 'id_tunjangan_detail') ?>

    <?= $form->field($model, 'nama_tunjangan') ?>

    <?= $form->field($model, 'jumlah') ?>

    <?= $form->field($model, 'id_transaksi_gaji') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
