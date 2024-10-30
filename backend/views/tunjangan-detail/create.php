<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\TunjanganDetail $model */

$this->title = Yii::t('app', 'Create Tunjangan Detail');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tunjangan Details'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tunjangan-detail-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
