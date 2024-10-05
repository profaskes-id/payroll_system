<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\AtasanKaryawan $model */

$this->title = Yii::t('app', 'Update Atasan  {name}', [
    'name' => $model->karyawan->nama,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Atasan Karyawan'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->karyawan->nama, 'url' => ['view', 'id_atasan_karyawan' => $model->karyawan->nama]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="atasan-karyawan-update">

    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>