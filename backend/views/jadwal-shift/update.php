<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\JadwalShift $model */

$this->title = Yii::t('app', 'Update Jadwal Shift: {name}', [
    'name' => $model->id_jadwal_shift,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Jadwal Shifts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_jadwal_shift, 'url' => ['view', 'id_jadwal_shift' => $model->id_jadwal_shift]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="jadwal-shift-update">


    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>