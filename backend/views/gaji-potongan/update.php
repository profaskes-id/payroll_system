<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\GajiPotongan $model */

$this->title = Yii::t('app', 'Update Gaji Potongan: {name}', [
    'name' => $model->id_gaji_potongan,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Gaji Potongans'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_gaji_potongan, 'url' => ['view', 'id_gaji_potongan' => $model->id_gaji_potongan]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="gaji-potongan-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
