<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\MasterCuti $model */

$this->title = 'Create Master Cuti';
$this->params['breadcrumbs'][] = ['label' => 'Master Cutis', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="master-cuti-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
