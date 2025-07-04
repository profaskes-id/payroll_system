<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\MasterHaribesar $model */

$this->title = Yii::t('app', 'Update Master Hari Libur',);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Master Hari Libur'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->nama_hari, 'url' => ['view', 'kode' => $model->kode]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="master-haribesar-update">

    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>