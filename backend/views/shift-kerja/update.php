<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\ShiftKerja $model */

$this->title = Yii::t('app', 'Update {name}', [
    'name' => $model->nama_shift,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Shift Kerja'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->nama_shift, 'url' => ['view', 'id_shift_kerja' => $model->id_shift_kerja]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="shift-kerja-update">

    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>