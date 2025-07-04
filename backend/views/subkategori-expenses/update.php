<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\SubkategoriExpenses $model */

$this->title = 'Update Subkategori Expenses ';
$this->params['breadcrumbs'][] = ['label' => 'Subkategori Expenses', 'url' => ['/kategori-expenses/index']];
$this->params['breadcrumbs'][] = ['label' => $model->nama_subkategori, 'url' => ['view', 'id_subkategori_expenses' => $model->id_subkategori_expenses]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="subkategori-expenses-update">

    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa fa-reply"></i> Back', ['/kategori-expenses/index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>