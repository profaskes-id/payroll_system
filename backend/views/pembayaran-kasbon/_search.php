<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\PembayaranKasbonSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="pembayaran-kasbon-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_pembayaran_kasbon') ?>

    <?= $form->field($model, 'id_karyawan') ?>

    <?= $form->field($model, 'id_kasbon') ?>

    <?= $form->field($model, 'bulan') ?>
    <?= $form->field($model, 'tahun') ?>

    <?= $form->field($model, 'jumlah_potong') ?>



    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>