<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\TotalHariKerja $model */

$this->title = Yii::t('app', 'Tambah Total Hari Kerja');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Total Hari Kerja'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="total-hari-kerja-create">

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