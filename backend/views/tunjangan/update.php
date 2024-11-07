<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\Tunjangan $model */

$this->title = Yii::t('app', 'Update {name}', [
    'name' => $model->nama_tunjangan,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tunjangan'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->nama_tunjangan, 'url' => ['view', 'id_tunjangan' => $model->id_tunjangan]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="tunjangan-update">

    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>