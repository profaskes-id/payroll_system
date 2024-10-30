<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\PotonganDetail $model */

$this->title = Yii::t('app', 'Create Potongan Detail');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Potongan Details'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="potongan-detail-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
