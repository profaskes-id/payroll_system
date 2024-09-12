<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\PengajuanDinas $model */

$this->title = Yii::t('app', 'Update Pengajuan Dinas: {name}', [
    'name' => $model->id_pengajuan_dinas,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Pengajuan Dinas'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_pengajuan_dinas, 'url' => ['view', 'id_pengajuan_dinas' => $model->id_pengajuan_dinas]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="pengajuan-dinas-update">

    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>