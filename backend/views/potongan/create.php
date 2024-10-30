<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\Potongan $model */

$this->title = Yii::t('app', 'Create Potongan');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Potongans'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="potongan-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
