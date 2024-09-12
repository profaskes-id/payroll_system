<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\RiwayatKesehatan $model */

$this->title = Yii::t('app', 'Update Riwayat Kesehatan: {name}', [
    'name' => $model->id_riwayat_kesehatan,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Riwayat Kesehatans'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_riwayat_kesehatan, 'url' => ['view', 'id_riwayat_kesehatan' => $model->id_riwayat_kesehatan]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="riwayat-kesehatan-update">

    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['/riwayat-kesehatan/view', 'id_riwayat_kesehatan' => $model->id_riwayat_kesehatan], ['class' => 'costume-btn']) ?>
        </p>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>