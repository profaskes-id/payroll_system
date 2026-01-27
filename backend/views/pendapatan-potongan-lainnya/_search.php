<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\PendapatanPotonganLainnyaSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="pendapatan-potongan-lainnya-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_ppl') ?>

    <?= $form->field($model, 'id_karyawan') ?>

    <?= $form->field($model, 'bulan') ?>
    <?= $form->field($model, 'tahun') ?>

    <?= $form->field($model, 'keterangan') ?>

    <?= $form->field($model, 'is_pendapatan') ?>

    <?php // echo $form->field($model, 'is_potongan') 
    ?>

    <?php // echo $form->field($model, 'created_at') 
    ?>

    <?php // echo $form->field($model, 'updated_at') 
    ?>

    <?php // echo $form->field($model, 'jumlah') 
    ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>