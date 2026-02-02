<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\RekapLembur $model */

$this->title = 'Tambahkan Data Lembur';
$this->params['breadcrumbs'][] = ['label' => 'Rekap Lemburs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rekap-lembur-create">

    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>