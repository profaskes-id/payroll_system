<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\ExpensesHeader $model */

$this->title = 'Update Expenses Header: ' . $model->id_expense_header;
$this->params['breadcrumbs'][] = ['label' => 'Expenses Headers', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_expense_header, 'url' => ['view', 'id_expense_header' => $model->id_expense_header]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="expenses-header-update">

    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>