<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\Tunjangan $model */

$this->title = Yii::t('app', 'Update Tunjangan: {name}', [
    'name' => $model->id_tunjangan,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tunjangans'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_tunjangan, 'url' => ['view', 'id_tunjangan' => $model->id_tunjangan]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="tunjangan-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
