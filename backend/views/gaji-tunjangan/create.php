<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\GajiTunjangan $model */

$this->title = Yii::t('app', 'Create Gaji Tunjangan');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Gaji Tunjangans'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="gaji-tunjangan-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
