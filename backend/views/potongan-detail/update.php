<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\PotonganDetail $model */

$this->title = Yii::t('app', 'Update Potongan Detail: {name}', [
    'name' => $model->id_potongan_detail,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Potongan Details'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_potongan_detail, 'url' => ['view', 'id_potongan_detail' => $model->id_potongan_detail]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="potongan-detail-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
