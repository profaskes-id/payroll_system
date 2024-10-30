<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\PeriodeGaji $model */

$this->title = Yii::t('app', 'Create Periode Gaji');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Periode Gajis'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="periode-gaji-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
