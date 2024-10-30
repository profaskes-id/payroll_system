<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\TransaksiGaji $model */

$this->title = Yii::t('app', 'Update Transaksi Gaji: {name}', [
    'name' => $model->id_transaksi_gaji,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Transaksi Gajis'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_transaksi_gaji, 'url' => ['view', 'id_transaksi_gaji' => $model->id_transaksi_gaji]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="transaksi-gaji-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
