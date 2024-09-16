<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\AtasanKaryawan $model */

$this->title = Yii::t('app', 'Update Atasan Karyawan: {name}', [
    'name' => $model->id_atasan_karyawan,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Atasan Karyawan'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_atasan_karyawan, 'url' => ['view', 'id_atasan_karyawan' => $model->id_atasan_karyawan]];
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