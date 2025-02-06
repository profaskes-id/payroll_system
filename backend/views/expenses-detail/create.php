<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\ExpensesDetail $model */

$this->title = 'Tambah Biaya & Beban';
$this->params['breadcrumbs'][] = ['label' => 'Expenses Details', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="expenses-detail-create">

    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
        'headerEx' => $headerEx,
        'nextKode' => $nextKode

    ]) ?>

</div>