<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\TransaksiGaji $model */

$this->title = Yii::t('app', 'Update Transaksi Gaji : {name}', [
    'name' => $model->nama,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Transaksi Gaji'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->nama, 'url' => ['view', 'id_transaksi_gaji' => $model->id_transaksi_gaji]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="transaksi-gaji-update">

    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
        'rekapandata' => $rekapandata,

    ]) ?>

</div>