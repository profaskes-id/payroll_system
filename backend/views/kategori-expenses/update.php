<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\KategoriExpenses $model */

$this->title = 'Update Kategori Expenses ';
$this->params['breadcrumbs'][] = ['label' => 'Kategori Expenses', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->nama_kategori, 'url' => ['view', 'id_kategori_expenses' => $model->id_kategori_expenses]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="kategori-expenses-update">

    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>