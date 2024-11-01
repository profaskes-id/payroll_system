<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\GajiPotongan $model */

$this->title = Yii::t('app', 'Create Gaji Potongan');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Gaji Potongan'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="gaji-potongan-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>