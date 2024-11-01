<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\GajiTunjangan $model */

$this->title = Yii::t('app', 'Update Gaji Tunjangan: {name}', [
    'name' => $model->id_gaji_tunjangan,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Gaji Tunjangan'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_gaji_tunjangan, 'url' => ['view', 'id_gaji_tunjangan' => $model->id_gaji_tunjangan]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="gaji-tunjangan-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>