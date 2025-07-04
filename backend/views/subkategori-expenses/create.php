<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\SubkategoriExpenses $model */

$this->title = 'Create Subkategori Expenses';
$this->params['breadcrumbs'][] = ['label' => 'Subkategori Expenses', 'url' => ['/kategori-expenses/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="subkategori-expenses-create">

    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa fa-reply"></i> Back', ['/kategori-expenses/index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>