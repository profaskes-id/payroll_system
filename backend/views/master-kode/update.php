<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\MasterKode $model */

$this->title = 'Update Master Kode: ' . $model->nama_group;
$this->params['breadcrumbs'][] = ['label' => 'Master Kode', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->nama_group, 'url' => ['view', 'nama_group' => $model->nama_group, 'kode' => $model->kode]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="master-kode-update">

    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>
    <div class="table-container">
        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>
    </div>

</div>