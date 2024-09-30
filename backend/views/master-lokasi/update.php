<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\MasterLokasi $model */

$this->title = Yii::t('app', 'Update Master Lokasi: {name}', [
    'name' => $model->label,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Master Lokasis'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_master_lokasi, 'url' => ['view', 'id_master_lokasi' => $model->id_master_lokasi]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="master-lokasi-update">

    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>