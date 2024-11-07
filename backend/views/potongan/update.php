<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\Potongan $model */

$this->title = Yii::t('app', 'Update Potongan  {name}', [
    'name' => $model->nama_potongan,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Potongan'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->nama_potongan, 'url' => ['view', 'id_potongan' => $model->id_potongan]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="potongan-update">

    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>