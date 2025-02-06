<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\KategoriExpenses $model */

$this->title = 'Tambah Kategori Expenses';
$this->params['breadcrumbs'][] = ['label' => 'Kategori Expenses', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="kategori-expenses-create">

    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>