<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\MasterKode $model */

$this->title = Yii::t('app', 'Update Jabatan : {name}', [
    'name' => $model->nama_kode,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Master Kodes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->nama_group, 'url' => ['view', 'nama_group' => $model->nama_group, 'kode' => $model->kode]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="master-kode-update">

    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>