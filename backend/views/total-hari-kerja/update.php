<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\TotalHariKerja $model */

$this->title = Yii::t('app', 'Update Total Hari Kerja');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Total Hari Kerjas'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_total_hari_kerja, 'url' => ['view', 'id_total_hari_kerja' => $model->id_total_hari_kerja]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="total-hari-kerja-update">

    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
        'holidaysGroupedByMonth' => $holidaysGroupedByMonth

    ]) ?>

</div>