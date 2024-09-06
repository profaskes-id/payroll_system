<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\MasterHaribesarSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="master-haribesar-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'kode') ?>

    <?= $form->field($model, 'tanggal') ?>

    <?= $form->field($model, 'nama_hari') ?>

    <?= $form->field($model, 'libur_nasional') ?>

    <?= $form->field($model, 'pesan_default') ?>

    <?php // echo $form->field($model, 'lampiran') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
