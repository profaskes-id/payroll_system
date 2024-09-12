<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\PengajuanLembur $model */

$this->title = Yii::t('app', 'Update Pengajuan Lembur: {name}', [
    'name' => $model->id_pengajuan_lembur,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Pengajuan Lemburs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_pengajuan_lembur, 'url' => ['view', 'id_pengajuan_lembur' => $model->id_pengajuan_lembur]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="pengajuan-lembur-update">


    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>