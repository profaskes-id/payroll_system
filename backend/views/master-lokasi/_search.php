<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\MasterLokasiSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="master-lokasi-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_master_lokasi') ?>

    <?= $form->field($model, 'label') ?>

    <?= $form->field($model, 'alamat') ?>

    <?= $form->field($model, 'longtitude') ?>

    <?= $form->field($model, 'latitude') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
