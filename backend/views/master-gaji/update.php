<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\MasterGaji $model */

$this->title = Yii::t('app', 'Update Data Gaji {name}', [
    'name' => $model->karyawan->nama,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Master Gaji'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->karyawan->nama, 'url' => ['view', 'id_gaji' => $model->id_gaji]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="master-gaji-update">


    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>