<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\ExpensesHeaderSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="expenses-header-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_expense_header') ?>

    <?= $form->field($model, 'tanggal') ?>

    <?= $form->field($model, 'create_at') ?>

    <?= $form->field($model, 'create_by') ?>

    <?= $form->field($model, 'update_at') ?>

    <?php // echo $form->field($model, 'update_by') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
