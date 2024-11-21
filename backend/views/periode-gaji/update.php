<?php

use backend\models\Tanggal;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\PeriodeGaji $model */


$tanggal = new Tanggal();
$this->title = Yii::t('app', 'Update Periode Gaji  {name}', [
    'name' => $tanggal->getBulan($model->bulan),
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Periode Gaji'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $tanggal->getBulan($model->bulan), 'url' => ['view', 'bulan' => $model->bulan, 'tahun' => $model->tahun]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="periode-gaji-update">

    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>