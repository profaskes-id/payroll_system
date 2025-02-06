<?php

use backend\models\ExpensesDetail;
use hail812\adminlte3\yii\grid\ActionColumn;
use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ListView;

/** @var yii\web\View $this */
/** @var backend\models\ExpensesDetail $model */

$this->title = 'Update Expenses Detail ';
$this->params['breadcrumbs'][] = ['label' => 'Expenses Details', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_expense_detail, 'url' => ['view', 'id_expense_detail' => $model->id_expense_detail]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="expenses-detail-update">

    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
        'headerEx' => $headerEx,
        'dataHEader' => $dataHEader
    ]) ?>





</div>