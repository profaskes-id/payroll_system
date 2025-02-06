<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\ExpensesHeader $model */

$this->title = 'Tambah Expenses Header';
$this->params['breadcrumbs'][] = ['label' => 'Expenses Header', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="expenses-header-create">

    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>