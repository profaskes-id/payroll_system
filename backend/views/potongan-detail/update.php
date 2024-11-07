<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\PotonganDetail $model */

$this->title = Yii::t('app', 'Potongan  {p} {k}', [
    'p' => $model->potongan->nama_potongan,
    'k' => $model->karyawan->nama,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Potongan Detail'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->potongan->nama_potongan, 'url' => ['view', 'id_potongan_detail' => $model->id_potongan_detail]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="potongan-detail-update">

    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>