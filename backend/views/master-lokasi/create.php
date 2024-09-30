<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\MasterLokasi $model */

$this->title = Yii::t('app', 'Create Master Lokasi');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Master Lokasis'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="master-lokasi-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
