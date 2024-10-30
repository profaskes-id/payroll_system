<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\Potongan $model */

$this->title = Yii::t('app', 'Update Potongan: {name}', [
    'name' => $model->id_potongan,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Potongans'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_potongan, 'url' => ['view', 'id_potongan' => $model->id_potongan]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="potongan-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
