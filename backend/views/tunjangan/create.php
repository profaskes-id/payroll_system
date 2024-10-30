<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\Tunjangan $model */

$this->title = Yii::t('app', 'Create Tunjangan');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tunjangans'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tunjangan-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
