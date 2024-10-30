<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\TunjanganDetail $model */

$this->title = Yii::t('app', 'Update Tunjangan Detail: {name}', [
    'name' => $model->id_tunjangan_detail,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tunjangan Details'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_tunjangan_detail, 'url' => ['view', 'id_tunjangan_detail' => $model->id_tunjangan_detail]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="tunjangan-detail-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
